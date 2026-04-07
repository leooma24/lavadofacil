<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Tenant\Customer;
use App\Models\Tenant\MessageTemplate;
use App\Models\Tenant\WhatsappMessage;
use App\Services\Features;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Detecta clientes con cumpleaños HOY en cada tenant que tiene la feature
 * `birthdays` activa, y registra un mensaje pendiente en `whatsapp_messages`
 * con la plantilla `birthday`. El dueño los verá en su panel y dará click
 * en el botón WhatsApp para enviarlos manualmente (wa.me, no API).
 *
 * Programado para correr a las 9 AM cada día.
 */
class BirthdayNotifyCommand extends Command
{
    protected $signature = 'birthdays:notify';
    protected $description = 'Crea recordatorios de cumpleaños del día en todos los tenants';

    public function handle(): int
    {
        $tenants = Tenant::where('status', '!=', 'cancelled')->get();
        $totalQueued = 0;

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            try {
                if (! Features::enabled('birthdays')) {
                    continue;
                }

                $template = MessageTemplate::where('channel', 'whatsapp')
                    ->where('type', 'birthday')
                    ->where('is_active', true)
                    ->first();

                $birthdayCustomers = Customer::query()
                    ->whereRaw('MONTH(birthdate) = ?', [now()->month])
                    ->whereRaw('DAY(birthdate) = ?', [now()->day])
                    ->where('whatsapp_opt_in', true)
                    ->get();

                foreach ($birthdayCustomers as $customer) {
                    // Evitar duplicados si ya se creó hoy
                    $alreadyQueued = WhatsappMessage::where('customer_id', $customer->id)
                        ->where('type', 'birthday')
                        ->whereDate('created_at', today())
                        ->exists();

                    if ($alreadyQueued) {
                        continue;
                    }

                    $body = $template
                        ? $template->render($customer)
                        : "🎂 ¡Feliz cumpleaños {$customer->name}! Te esperamos para festejarte 🚗";

                    WhatsappMessage::create([
                        'customer_id' => $customer->id,
                        'template_id' => $template?->id,
                        'sent_by_user_id' => DB::table('users')->value('id') ?? 1,
                        'type' => 'birthday',
                        'phone' => $customer->phone,
                        'body' => $body,
                        'sent_at' => null,
                        'notes' => 'Auto-generado por birthdays:notify',
                    ]);

                    $totalQueued++;
                }

                $this->info("✓ {$tenant->id}: ".$birthdayCustomers->count().' cumpleaños hoy');
            } finally {
                tenancy()->end();
            }
        }

        $this->info("Total mensajes encolados: {$totalQueued}");
        return self::SUCCESS;
    }
}
