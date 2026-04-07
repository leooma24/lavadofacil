<?php

namespace App\Services;

use App\Models\Tenant\Customer;
use App\Models\Tenant\LevelConfig;
use App\Models\Tenant\LoyaltyCard;
use App\Models\Tenant\Prize;
use App\Models\Tenant\PrizeSpin;
use App\Models\Tenant\Raffle;
use App\Models\Tenant\RaffleTicket;
use App\Models\Tenant\Service;
use App\Models\Tenant\Stamp;
use App\Models\Tenant\Visit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Centraliza el registro de una visita y todos sus efectos secundarios:
 * - Crea visit + visit_services
 * - Suma sellos a la tarjeta de fidelidad (si feature loyalty_card on)
 * - Genera tickets de rifa del mes (si feature raffle on)
 * - Actualiza contadores del cliente
 */
class VisitRegistrar
{
    /**
     * @param  array<int, array{service_id:int, quantity:int, unit_price:float}>  $items
     */
    public function register(int $customerId, array $items, float $discount, string $paymentMethod, ?string $notes = null): Visit
    {
        return DB::transaction(function () use ($customerId, $items, $discount, $paymentMethod, $notes) {
            $customer = Customer::findOrFail($customerId);

            // 1. Calcular totales y sellos
            $subtotal = 0;
            $stampsTotal = 0;
            $pointsTotal = 0;
            $servicesData = [];

            foreach ($items as $item) {
                $svc = Service::find($item['service_id']);
                if (! $svc) {
                    continue;
                }
                $qty = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['unit_price'] ?? $svc->price);
                $lineSubtotal = $price * $qty;

                $subtotal += $lineSubtotal;
                $stampsTotal += $svc->stamps_earned * $qty;
                $pointsTotal += $svc->points_earned * $qty;

                $servicesData[$svc->id] = [
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'subtotal' => $lineSubtotal,
                ];
            }

            // 1b. Aplicar multiplicador de nivel a puntos
            if (Features::enabled('levels')) {
                $levelConfig = LevelConfig::where('level', $customer->level)->first();
                if ($levelConfig && $levelConfig->multiplier > 1) {
                    $pointsTotal = (int) round($pointsTotal * (float) $levelConfig->multiplier);
                }
            }

            $total = max(0, $subtotal - $discount);

            // 2. Crear la visita
            $visit = Visit::create([
                'customer_id' => $customer->id,
                'served_by_user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'earned_stamps' => $stampsTotal,
                'points_earned' => $pointsTotal,
                'notes' => $notes,
                'visited_at' => now(),
            ]);

            // 3. Pivot visit_services
            $visit->services()->sync($servicesData);

            // 4. Actualizar cliente
            $customer->increment('total_visits');
            $customer->increment('total_spent', $total);
            $customer->increment('points_balance', $pointsTotal);
            $customer->last_visit_at = now();
            $customer->save();

            // 4b. Auto-promoción de nivel
            if (Features::enabled('levels')) {
                $this->updateLevel($customer);
            }

            // 5. Tarjeta de sellos (y premio si se completa)
            if (Features::enabled('loyalty_card') && $stampsTotal > 0) {
                $this->addStamps($customer, $visit, $stampsTotal);
            }

            // 6. Tickets de rifa del mes
            if (Features::enabled('raffle')) {
                $this->generateRaffleTickets($customer, $visit);
            }

            return $visit;
        });
    }

    protected function addStamps(Customer $customer, Visit $visit, int $count): void
    {
        $card = LoyaltyCard::firstOrCreate(
            ['customer_id' => $customer->id],
            ['stamps_count' => 0, 'started_at' => now()],
        );

        for ($i = 0; $i < $count; $i++) {
            Stamp::create([
                'loyalty_card_id' => $card->id,
                'visit_id' => $visit->id,
                'stamped_by_user_id' => Auth::id(),
                'stamped_at' => now(),
            ]);

            $card->stamps_count++;
            $card->last_stamp_at = now();

            // Tarjeta completa al llegar a 8
            if ($card->stamps_count >= 8) {
                $card->stamps_count = 0;
                $card->completed_count++;
                $card->current_card_number++;
                $card->is_complete = true;
                $card->completed_at = now();

                // Si la ruleta de premios está activa, asignar un premio
                if (Features::enabled('rewards_wheel')) {
                    $this->assignRandomPrize($customer, $card);
                }
            }
        }

        $card->save();
    }

    /**
     * Promueve al cliente al nivel más alto cuyo umbral cumple.
     */
    protected function updateLevel(Customer $customer): void
    {
        $newLevel = LevelConfig::query()
            ->where('min_visits', '<=', $customer->total_visits)
            ->where('min_spent', '<=', $customer->total_spent)
            ->orderByDesc('sort_order')
            ->value('level');

        if ($newLevel && $newLevel !== $customer->level) {
            $customer->level = $newLevel;
            $customer->save();
        }
    }

    /**
     * Selección ponderada por probability_weight. Respeta stock.
     */
    protected function assignRandomPrize(Customer $customer, LoyaltyCard $card): ?PrizeSpin
    {
        $prizes = Prize::where('is_active', true)
            ->where(fn ($q) => $q->whereNull('stock')->orWhere('stock', '>', 0))
            ->get();

        if ($prizes->isEmpty()) {
            return null;
        }

        $totalWeight = $prizes->sum('probability_weight');
        if ($totalWeight <= 0) {
            return null;
        }

        $roll = random_int(1, $totalWeight);
        $cumulative = 0;
        $chosen = null;
        foreach ($prizes as $prize) {
            $cumulative += $prize->probability_weight;
            if ($roll <= $cumulative) {
                $chosen = $prize;
                break;
            }
        }

        if (! $chosen) {
            return null;
        }

        if ($chosen->stock !== null) {
            $chosen->decrement('stock');
        }

        return PrizeSpin::create([
            'customer_id' => $customer->id,
            'loyalty_card_id' => $card->id,
            'prize_id' => $chosen->id,
            'spun_at' => now(),
        ]);
    }

    protected function generateRaffleTickets(Customer $customer, Visit $visit): void
    {
        $raffle = Raffle::where('month', now()->month)
            ->where('year', now()->year)
            ->where('status', 'active')
            ->first();

        if (! $raffle) {
            return;
        }

        // Verificar tope por cliente
        if ($raffle->max_tickets_per_customer) {
            $current = RaffleTicket::where('raffle_id', $raffle->id)
                ->where('customer_id', $customer->id)
                ->count();
            if ($current >= $raffle->max_tickets_per_customer) {
                return;
            }
        }

        RaffleTicket::create([
            'raffle_id' => $raffle->id,
            'customer_id' => $customer->id,
            'visit_id' => $visit->id,
            'ticket_number' => strtoupper(substr(uniqid(), -8)),
            'generated_at' => now(),
        ]);
    }
}
