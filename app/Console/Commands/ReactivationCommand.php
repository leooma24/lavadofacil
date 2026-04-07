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
 * Detecta clientes sin visita en 21+ días y encola un mensaje de reactivación
 * en la bandeja del dueño. Corre cada lunes.
 */
class ReactivationCommand extends Command
{
    protected $signature = 'reminders:reactivation {--days=21}';
    protected $description = 'Encola mensajes de reactivación para clientes dormidos';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $tenants = Tenant::where('status', '!=', 'cancelled')->get();
        $total = 0;

        foreach ($tenants as $tenant) {
            tenancy()->initialize($tenant);
            try {
                if (! Features::enabled('reactivation') || ! Features::enabled('whatsapp')) {
                    continue;
                }

                $template = MessageTemplate::where('channel', 'whatsapp')
                    ->where('type', 'reactivation')
                    ->where('is_active', true)
                    ->first();

                $sleeping = Customer::where('whatsapp_opt_in', true)
                    ->where(function ($q) use ($days) {
                        $q->where('last_visit_at', '<=', now()->subDays($days))
                          ->where('last_visit_at', '>', now()->subDays($days + 30)); // ventana
                    })
                    ->get();

                foreach ($sleeping as $customer) {
                    $exists = WhatsappMessage::where('customer_id', $customer->id)
                        ->where('type', 'reactivation')
                        ->where('created_at', '>=', now()->subDays(30))
                        ->exists();
                    if ($exists) continue;

                    $body = $template
                        ? $template->render($customer)
                        : "Hola {$customer->name}, te extrañamos 😢 Vuelve esta semana y te damos un sello de regalo 🚗";

                    WhatsappMessage::create([
                        'customer_id' => $customer->id,
                        'template_id' => $template?->id,
                        'sent_by_user_id' => DB::table('users')->value('id') ?? 1,
                        'type' => 'reactivation',
                        'phone' => $customer->phone,
                        'body' => $body,
                        'sent_at' => null,
                        'notes' => "Sin visita en {$days}+ días",
                    ]);
                    $total++;
                }
            } finally {
                tenancy()->end();
            }
        }

        $this->info("Mensajes de reactivación encolados: {$total}");
        return self::SUCCESS;
    }
}
