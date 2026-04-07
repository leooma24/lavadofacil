<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\Tenant\PushSubscription;
use Illuminate\Console\Command;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

/**
 * Envía una notificación push a todos los clientes suscritos del tenant dado.
 * Uso: php artisan push:send {tenant} "Título" "Mensaje"
 */
class PushSendCommand extends Command
{
    protected $signature = 'push:send {tenant : ID del tenant} {title : Título} {body : Mensaje} {--url=/app : URL al hacer click}';
    protected $description = 'Envía notificación push a clientes del tenant';

    public function handle(): int
    {
        $tenant = Tenant::find($this->argument('tenant'));
        if (! $tenant) {
            $this->error('Tenant no existe');
            return self::FAILURE;
        }

        $publicKey = config('webpush.vapid.public_key');
        $privateKey = config('webpush.vapid.private_key');
        if (! $publicKey || ! $privateKey) {
            $this->error('VAPID keys no configuradas. Genera con tinker y pon en .env (ver config/webpush.php)');
            return self::FAILURE;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'),
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ],
        ]);

        $payload = json_encode([
            'title' => $this->argument('title'),
            'body' => $this->argument('body'),
            'url' => $this->option('url'),
            'icon' => '/images/lavadofacil_icon.png',
        ]);

        tenancy()->initialize($tenant);
        try {
            $subscriptions = PushSubscription::all();
            foreach ($subscriptions as $sub) {
                $webPush->queueNotification(
                    Subscription::create([
                        'endpoint' => $sub->endpoint,
                        'publicKey' => $sub->public_key,
                        'authToken' => $sub->auth_token,
                    ]),
                    $payload,
                );
            }

            $sent = 0;
            $failed = 0;
            foreach ($webPush->flush() as $report) {
                if ($report->isSuccess()) {
                    $sent++;
                } else {
                    $failed++;
                    // Suscripción inválida → eliminar
                    if ($report->isSubscriptionExpired()) {
                        PushSubscription::where('endpoint', $report->getEndpoint())->delete();
                    }
                }
            }

            $this->info("Push enviado: {$sent} ok, {$failed} fallidos");
        } finally {
            tenancy()->end();
        }

        return self::SUCCESS;
    }
}
