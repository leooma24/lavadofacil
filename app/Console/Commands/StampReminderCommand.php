<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Tenant\Customer;
use App\Models\Tenant\LoyaltyCard;
use App\Models\Tenant\MessageTemplate;
use App\Models\Tenant\WhatsappMessage;
use App\Services\Features;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Encuentra clientes a 1-2 sellos de completar tarjeta y encola un recordatorio
 * de WhatsApp en la bandeja del dueño. Corre cada lunes en la mañana.
 */
class StampReminderCommand extends Command
{
    protected $signature = 'reminders:stamps';
    protected $description = 'Recordatorio para clientes a 1-2 sellos de completar tarjeta';

    public function handle(): int
    {
        $tenants = Tenant::where('status', '!=', 'cancelled')->get();
        $total = 0;

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            try {
                if (! Features::enabled('loyalty_card') || ! Features::enabled('whatsapp')) {
                    continue;
                }

                $template = MessageTemplate::where('channel', 'whatsapp')
                    ->where('type', 'stamp_reminder')
                    ->where('is_active', true)
                    ->first();

                $cards = LoyaltyCard::where('stamps_count', '>=', 6)
                    ->where('stamps_count', '<', 8)
                    ->with('customer')
                    ->get();

                foreach ($cards as $card) {
                    if (! $card->customer || ! $card->customer->whatsapp_opt_in) {
                        continue;
                    }

                    // Evitar duplicados en los últimos 7 días
                    $exists = WhatsappMessage::where('customer_id', $card->customer_id)
                        ->where('type', 'stamp_reminder')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->exists();
                    if ($exists) continue;

                    $missing = 8 - $card->stamps_count;
                    $body = $template
                        ? $template->render($card->customer, ['faltan' => $missing])
                        : "Hola {$card->customer->name}, te faltan {$missing} sello(s) para tu lavado gratis 🚗 ¡Te esperamos!";

                    WhatsappMessage::create([
                        'customer_id' => $card->customer_id,
                        'template_id' => $template?->id,
                        'sent_by_user_id' => DB::table('users')->value('id') ?? 1,
                        'type' => 'stamp_reminder',
                        'phone' => $card->customer->phone,
                        'body' => $body,
                        'sent_at' => null,
                        'notes' => "Recordatorio: faltan {$missing} sellos",
                    ]);
                    $total++;
                }
            } finally {
                tenancy()->end();
            }
        }

        $this->info("Recordatorios encolados: {$total}");
        return self::SUCCESS;
    }
}
