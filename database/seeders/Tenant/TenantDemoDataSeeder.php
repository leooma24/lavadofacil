<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant\Customer;
use App\Models\Tenant\LoyaltyCard;
use App\Models\Tenant\Prize;
use App\Models\Tenant\PrizeSpin;
use App\Models\Tenant\Raffle;
use App\Models\Tenant\Service;
use App\Models\User;
use App\Services\VisitRegistrar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Datos demo abundantes para el tenant: clientes, visitas históricas,
 * rifa del mes, premios. Pensado para que un visitante vea TODO funcionando.
 *
 * Corre DESPUÉS de TenantInitialDataSeeder (que ya creó owner, niveles,
 * premios, servicios y plantillas).
 */
class TenantDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Login del owner para que VisitRegistrar pueda usar Auth::id()
        $owner = User::where('role', 'owner')->first();
        if (! $owner) {
            $this->command->warn('No hay owner — corre TenantInitialDataSeeder primero');
            return;
        }
        Auth::login($owner);

        $this->seedLuxuryPackages();
        $this->seedRaffle();
        $customers = $this->seedCustomers();
        $this->seedHistoricalVisits($customers);
        $this->seedSleepingCustomers();
        $this->seedGuaranteedPrizeSpins($customers);
        $this->seedAppointments($customers);
        $this->seedPrepaidPackages($customers);
        $this->seedReferrals($customers);
        $this->seedVipSubscriptions($customers);
        $this->seedSurveys($customers);
        $this->seedMonthlyChallenge($customers);
        $this->polishStarCustomer();

        $this->command->info('✓ Demo data sembrada: '.Customer::count().' clientes');
    }

    /**
     * Refina al cliente "estrella" del demo (Carlos Méndez, 6681112233)
     * para que cuando alguien entre al auto-login vea la app en su mejor
     * estado: nivel Oro, VIP, PIN fijo 1234, stats impresionantes.
     */
    private function polishStarCustomer(): void
    {
        $star = Customer::where('phone', '6681112233')->first();
        if (! $star) return;

        $star->update([
            'pin_code' => '1234',
            'level' => 'gold',
            'is_vip' => true,
            'vip_until' => now()->addMonths(6),
            'total_visits' => max(25, (int) $star->total_visits),
            'total_spent' => max(12500, (float) $star->total_spent),
            'points_balance' => 850,
            'current_streak' => 6,
            'longest_streak' => 8,
            'whatsapp_opt_in' => true,
        ]);
    }

    private function seedAppointments($customers): void
    {
        $services = Service::where('is_active', true)->get();
        if ($services->isEmpty()) return;

        // 8 citas: pasadas, hoy y futuras
        $specs = [
            [0, 'in_shop', 'completed'],
            [-1, 'in_shop', 'completed'],
            [-2, 'at_home', 'completed'],
            [0, 'in_shop', 'in_progress'],
            [0, 'in_shop', 'confirmed'],
            [1, 'at_home', 'confirmed'],
            [2, 'in_shop', 'pending'],
            [3, 'at_home', 'pending'],
        ];

        foreach ($specs as $i => [$dayOffset, $type, $status]) {
            $customer = $customers->random();
            \DB::table('appointments')->insert([
                'customer_id' => $customer->id,
                'service_id' => $services->random()->id,
                'type' => $type,
                'address' => $type === 'at_home' ? 'Av. Reforma '.rand(100, 999).', Culiacán' : null,
                'scheduled_at' => now()->addDays($dayOffset)->setTime(rand(9, 17), [0, 30][rand(0, 1)]),
                'queue_position' => $status === 'pending' ? $i + 1 : null,
                'status' => $status,
                'notes' => null,
                'created_at' => now()->subDays(max(1, abs($dayOffset))),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedPrepaidPackages($customers): void
    {
        $packages = [
            ['name' => 'Paquete 5 Lavados', 'description' => '5 lavados básicos a precio especial', 'washes_count' => 5, 'price' => 450, 'validity_days' => 90, 'sort_order' => 10],
            ['name' => 'Paquete 10 Lavados', 'description' => '10 lavados, ahorra 25%', 'washes_count' => 10, 'price' => 800, 'validity_days' => 180, 'sort_order' => 20],
            ['name' => 'Paquete 20 Lavados', 'description' => '20 lavados, ahorra 35%', 'washes_count' => 20, 'price' => 1500, 'validity_days' => 365, 'sort_order' => 30],
        ];

        foreach ($packages as $pkg) {
            \DB::table('prepaid_packages')->updateOrInsert(
                ['name' => $pkg['name']],
                array_merge($pkg, ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]),
            );
        }

        // 4 clientes han comprado paquetes
        $pkgs = \DB::table('prepaid_packages')->get();
        $buyers = $customers->take(4);
        foreach ($buyers as $i => $customer) {
            $pkg = $pkgs[$i % $pkgs->count()];
            \DB::table('customer_packages')->insert([
                'customer_id' => $customer->id,
                'package_id' => $pkg->id,
                'washes_total' => $pkg->washes_count,
                'washes_remaining' => max(0, $pkg->washes_count - rand(0, 3)),
                'purchased_at' => now()->subDays(rand(5, 60)),
                'expires_at' => now()->addDays($pkg->validity_days),
                'amount_paid' => $pkg->price,
                'payment_method' => ['cash', 'card', 'transfer'][rand(0, 2)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedReferrals($customers): void
    {
        // Top 3 clientes refirieron a los siguientes 6
        $referrers = $customers->take(3);
        $referreds = $customers->slice(3, 6);

        foreach ($referrers as $i => $referrer) {
            foreach ($referreds->slice($i * 2, 2) as $j => $referred) {
                \DB::table('referrals')->insert([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $referred->id,
                    'referral_code' => 'REF'.strtoupper(\Illuminate\Support\Str::random(6)),
                    'status' => $j === 0 ? 'rewarded' : 'converted',
                    'converted_at' => now()->subDays(rand(5, 30)),
                    'rewarded_at' => $j === 0 ? now()->subDays(rand(1, 5)) : null,
                    'reward_description' => $j === 0 ? 'Lavado básico gratis' : null,
                    'created_at' => now()->subDays(rand(30, 60)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function seedVipSubscriptions($customers): void
    {
        // Los 3 que ya marcamos como is_vip tienen suscripción activa
        foreach ($customers->take(3) as $customer) {
            \DB::table('vip_subscriptions')->insert([
                'customer_id' => $customer->id,
                'plan_name' => 'VIP Mensual Ilimitado',
                'monthly_price' => 599,
                'starts_at' => now()->subDays(rand(30, 60)),
                'ends_at' => now()->addDays(rand(15, 90)),
                'status' => 'active',
                'auto_renew' => true,
                'washes_included' => 999,
                'washes_used' => rand(2, 12),
                'created_at' => now()->subDays(60),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedSurveys($customers): void
    {
        // Tomamos 30 visitas al azar y les agregamos encuesta
        $visits = \DB::table('visits')->inRandomOrder()->limit(30)->get();
        $comments = [
            'Excelente servicio, muy rápido', 'Mi carro quedó como nuevo',
            'Buen trato del personal', 'Súper recomendado', 'Volveré sin duda',
            'El mejor lavado de la ciudad', null, null,
        ];

        foreach ($visits as $visit) {
            $rating = [5, 5, 5, 4, 4, 3][rand(0, 5)];
            \DB::table('surveys')->insert([
                'visit_id' => $visit->id,
                'customer_id' => $visit->customer_id,
                'rating' => $rating,
                'nps' => $rating >= 4 ? rand(8, 10) : rand(5, 7),
                'comments' => $comments[array_rand($comments)],
                'would_recommend' => $rating >= 4,
                'answered_at' => now()->subDays(rand(0, 30)),
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }

    private function seedMonthlyChallenge($customers): void
    {
        $challengeId = \DB::table('monthly_challenges')->insertGetId([
            'name' => 'Reto del Mes — 4 Visitas',
            'description' => 'Visítanos 4 veces este mes y gana un lavado completo gratis',
            'month' => now()->month,
            'year' => now()->year,
            'goal_type' => 'visits',
            'goal_value' => 4,
            'reward_description' => 'Lavado completo gratis + aromatizante premium',
            'reward_points' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Progreso para los top 8 clientes
        foreach ($customers->take(8) as $i => $customer) {
            $progress = [4, 4, 3, 3, 2, 2, 1, 1][$i];
            \DB::table('challenge_progress')->insert([
                'challenge_id' => $challengeId,
                'customer_id' => $customer->id,
                'current_value' => $progress,
                'completed_at' => $progress >= 4 ? now()->subDays(rand(1, 5)) : null,
                'claimed_at' => $i === 0 ? now()->subDays(1) : null,
                'created_at' => now()->subDays(15),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Siembra los 5 paquetes premium estilo Luxury Auto.
     */
    private function seedLuxuryPackages(): void
    {
        $packages = [
            [
                'name' => 'Paquete II — Detallado Interior Integral',
                'price' => 700, 'duration_min' => 90, 'stamps_earned' => 2, 'points_earned' => 70, 'sort_order' => 10,
                'description' => "**Limpieza profunda de:**\n\n- Asientos\n- Cielo\n- Postes laterales\n- Puertas y paneles\n- Consola central\n- Tablero\n- Guantera\n\n**Beneficios adicionales:** Restauración de plásticos para recuperar el brillo original.\n\n**Ideal para:** un vehículo que necesita una renovación completa del interior con acabado premium.",
            ],
            [
                'name' => 'Paquete III — Detallado Interior Premium Total',
                'price' => 1200, 'duration_min' => 150, 'stamps_earned' => 3, 'points_earned' => 120, 'sort_order' => 20,
                'description' => "**Incluye limpieza y detallado profundo de:**\n\n- Asientos, cielo, postes laterales\n- Puertas y paneles, consola central\n- Tablero, guantera, cajuela\n- Alfombra completa\n\n**Beneficios:** Restauración profesional de plásticos para devolver brillo y textura original.\n\n**Ideal para:** clientes que buscan máxima renovación del interior.",
            ],
            [
                'name' => 'Paquete IV — Detallado Interior & Exterior Premium',
                'price' => 1600, 'duration_min' => 180, 'stamps_earned' => 4, 'points_earned' => 160, 'sort_order' => 30,
                'description' => "**Detallado interior profundo** + **Restauración de plásticos** con protección UV.\n\n**Servicios exteriores añadidos (Premium Finish):**\n\n- **Pulido en una etapa** (eliminación de marcas)\n- **Abrillantado profesional**\n- **Encerado protector** (sellado con protección UV)",
            ],
            [
                'name' => 'Paquete V — Elite Total + Cerámica',
                'price' => 2500, 'duration_min' => 300, 'stamps_earned' => 6, 'points_earned' => 250, 'sort_order' => 40,
                'description' => "Limpieza profunda interior completa + **Desmontaje de Asientos** para acceso total + **Limpieza a Detalle** con vapor en zonas delicadas + **Servicio Exterior Avanzado** con descontaminado completo y pulido de corrección.\n\n**Protección Cerámica (sellado nano-tecnológico):**\n\n- Brillo profundo\n- Efecto hidrofóbico\n- Protección UV\n- Duración superior al encerado tradicional",
            ],
            [
                'name' => 'Paquete VI — Master Total Full Showroom',
                'price' => 3500, 'duration_min' => 480, 'stamps_earned' => 8, 'points_earned' => 350, 'sort_order' => 50,
                'description' => "**TODO el Paquete V más:**\n\n- Descontaminado completo de pintura (químico + mecánico, clay bar)\n- Corrección de pintura multi-etapa (elimina swirls, rayones, marcas)\n- Pulido avanzado tipo showroom\n- Sellado cerámica premium nano-tecnológico\n- Encerado profesional extra sobre cerámica\n- Detallado de motor (vapor + brocha fina)\n- Pulido y restauración de faros\n- Sellador repelente de agua en cristales\n\n⭐ **Llena tu tarjeta de un solo lavado y obtén premio inmediato.**",
            ],
        ];

        foreach ($packages as $pkg) {
            \App\Models\Tenant\Service::updateOrCreate(
                ['name' => $pkg['name']],
                array_merge($pkg, ['is_active' => true]),
            );
        }
    }

    private function seedRaffle(): void
    {
        Raffle::updateOrCreate(
            ['month' => now()->month, 'year' => now()->year],
            [
                'name' => 'Rifa del mes',
                'description' => 'Sorteo mensual entre todos los clientes activos',
                'prize_description' => '🎁 Lavado completo gratis por 3 meses + kit de aromatizantes',
                'tickets_required' => 1,
                'max_tickets_per_customer' => 20,
                'draw_date' => now()->endOfMonth(),
                'status' => 'active',
            ],
        );
    }

    /**
     * @return \Illuminate\Support\Collection<int, Customer>
     */
    private function seedCustomers()
    {
        $names = [
            ['Carlos Méndez', '6681112233'],
            ['María Hernández', '6682223344'],
            ['Jorge Ramírez', '6683334455'],
            ['Sofía Torres', '6684445566'],
            ['Luis Castillo', '6685556677'],
            ['Ana Gutiérrez', '6686667788'],
            ['Diego Vargas', '6687778899'],
            ['Lucía Domínguez', '6688889900'],
            ['Roberto Ortiz', '6689990011'],
            ['Patricia Reyes', '6680001122'],
            ['Andrés Núñez', '6681234567'],
            ['Valeria Cruz', '6682345678'],
            ['Fernando Rivas', '6683456789'],
            ['Gabriela Soto', '6684567890'],
            ['Héctor Chávez', '6685678901'],
            ['Adriana Peña', '6686789012'],
            ['Ricardo Vega', '6687890123'],
            ['Mariana Rojas', '6688901234'],
            ['Javier Aguilar', '6689012345'],
            ['Daniela Flores', '6680123456'],
        ];

        $customers = collect();

        foreach ($names as $i => [$name, $phone]) {
            $customer = Customer::updateOrCreate(
                ['phone' => $phone],
                [
                    'name' => $name,
                    'pin_code' => substr($phone, -4),
                    'email' => strtolower(str_replace(' ', '.', \Illuminate\Support\Str::ascii($name))).'@demo.com',
                    'birthdate' => now()->subYears(rand(20, 55))->subDays(rand(0, 364)),
                    'level' => 'bronze',
                    'whatsapp_opt_in' => true,
                    'is_vip' => $i < 3, // Primeros 3 son VIP
                    'vip_until' => $i < 3 ? now()->addMonths(3) : null,
                    'registered_at' => now()->subDays(rand(30, 180)),
                ],
            );
            $customers->push($customer);
        }

        // Algunos cumpleaños este mes para que se vean en filtros
        $customers->take(4)->each(function (Customer $c) {
            $c->birthdate = now()->subYears(rand(25, 45))->setMonth(now()->month)->setDay(rand(1, 28));
            $c->save();
        });

        return $customers;
    }

    private function seedHistoricalVisits($customers): void
    {
        $registrar = app(VisitRegistrar::class);
        $services = Service::where('is_active', true)->get();

        if ($services->isEmpty()) {
            return;
        }

        // Distribución: clientes top con muchas visitas, otros con pocas
        $visitDistribution = [25, 22, 20, 18, 15, 13, 12, 10, 9, 8, 7, 6, 5, 4, 4, 3, 3, 2, 2, 1];

        foreach ($customers as $i => $customer) {
            $visitCount = $visitDistribution[$i] ?? 1;

            for ($v = 0; $v < $visitCount; $v++) {
                // Visita en algún momento de los últimos 60 días
                $visitedAt = now()->subDays(rand(0, 60))->setTime(rand(8, 19), rand(0, 59));

                // Elegir 1-2 servicios al azar
                $picked = $services->random(rand(1, 2));
                $items = $picked->map(fn ($s) => [
                    'service_id' => $s->id,
                    'quantity' => 1,
                    'unit_price' => (float) $s->price,
                ])->all();

                $visit = $registrar->register(
                    customerId: $customer->id,
                    items: $items,
                    discount: 0,
                    paymentMethod: ['cash', 'card', 'transfer'][rand(0, 2)],
                    notes: null,
                );

                // Backdate la visita y la actualización del cliente
                $visit->visited_at = $visitedAt;
                $visit->created_at = $visitedAt;
                $visit->save();
            }
        }
    }

    /**
     * Garantiza que los top 3 clientes tengan al menos 1-2 premios visibles
     * en el panel "Mis premios" de la PWA, sin depender del azar.
     */
    private function seedGuaranteedPrizeSpins($customers): void
    {
        $prizes = Prize::where('is_active', true)->get();
        if ($prizes->isEmpty()) {
            return;
        }

        foreach ($customers->take(3) as $customer) {
            $card = LoyaltyCard::firstOrCreate(
                ['customer_id' => $customer->id],
                ['stamps_count' => 0, 'started_at' => now()->subDays(60)],
            );

            // 1-2 premios ganados en distintas fechas
            for ($i = 0; $i < rand(1, 2); $i++) {
                PrizeSpin::create([
                    'customer_id' => $customer->id,
                    'loyalty_card_id' => $card->id,
                    'prize_id' => $prizes->random()->id,
                    'spun_at' => now()->subDays(rand(5, 50)),
                    'claimed_at' => $i === 0 ? now()->subDays(rand(1, 4)) : null,
                ]);
            }
        }
    }

    private function seedSleepingCustomers(): void
    {
        // 5 clientes que NO han venido en 45+ días
        $sleeping = [
            ['Miguel Salazar', '6691111111', 50],
            ['Karla Estrada', '6692222222', 60],
            ['Pablo Cárdenas', '6693333333', 75],
            ['Elena Maldonado', '6694444444', 40],
            ['Tomás Quiñones', '6695555555', 90],
        ];

        foreach ($sleeping as [$name, $phone, $daysAgo]) {
            Customer::updateOrCreate(
                ['phone' => $phone],
                [
                    'name' => $name,
                    'pin_code' => substr($phone, -4),
                    'email' => strtolower(str_replace(' ', '.', \Illuminate\Support\Str::ascii($name))).'@demo.com',
                    'birthdate' => now()->subYears(rand(25, 50)),
                    'level' => 'bronze',
                    'whatsapp_opt_in' => true,
                    'total_visits' => rand(2, 8),
                    'total_spent' => rand(200, 1000),
                    'last_visit_at' => now()->subDays($daysAgo),
                    'registered_at' => now()->subDays($daysAgo + 30),
                ],
            );
        }
    }
}
