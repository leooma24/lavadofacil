<?php

namespace Database\Seeders\Tenant;

use App\Models\User;
use App\Models\Tenant\LevelConfig;
use App\Models\Tenant\MessageTemplate;
use App\Models\Tenant\Prize;
use App\Models\Tenant\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder que corre dentro del contexto del tenant.
 * Carga datos iniciales: owner, niveles, premios, servicios, plantillas WhatsApp+Email.
 *
 * Uso: tenancy()->initialize($tenant); $this->call(TenantInitialDataSeeder::class);
 */
class TenantInitialDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedOwner();
        $this->seedLevels();
        $this->seedPrizes();
        $this->seedServices();
        $this->seedMessageTemplates();
    }

    private function seedOwner(): void
    {
        // El tenant model ya se conoce porque estamos en su contexto
        $tenant = tenant();

        User::updateOrCreate(
            ['email' => $tenant->owner_email],
            [
                'name' => $tenant->owner_name,
                'password' => Hash::make('password'),
                'phone' => $tenant->owner_phone,
                'role' => 'owner',
                'is_active' => true,
                'must_change_password' => true,
                'email_verified_at' => now(),
            ]
        );
    }

    private function seedLevels(): void
    {
        $levels = [
            ['level' => 'bronze', 'min_visits' => 0, 'min_spent' => 0, 'multiplier' => 1.00, 'color' => '#cd7f32', 'icon' => '🥉', 'sort_order' => 1, 'perks' => ['Acumular sellos', 'Ruleta de premios']],
            ['level' => 'silver', 'min_visits' => 5, 'min_spent' => 500, 'multiplier' => 1.25, 'color' => '#c0c0c0', 'icon' => '🥈', 'sort_order' => 2, 'perks' => ['25% más puntos', 'Acceso a rifas', 'Descuento 5%']],
            ['level' => 'gold', 'min_visits' => 15, 'min_spent' => 1500, 'multiplier' => 1.50, 'color' => '#ffd700', 'icon' => '🥇', 'sort_order' => 3, 'perks' => ['50% más puntos', '2x tickets de rifa', 'Descuento 10%', 'Servicio prioritario']],
            ['level' => 'platinum', 'min_visits' => 30, 'min_spent' => 3000, 'multiplier' => 2.00, 'color' => '#e5e4e2', 'icon' => '💎', 'sort_order' => 4, 'perks' => ['2x puntos', '3x tickets de rifa', 'Descuento 15%', 'Servicio premium', 'Lavado gratis cumpleaños']],
        ];

        foreach ($levels as $level) {
            LevelConfig::updateOrCreate(['level' => $level['level']], $level);
        }
    }

    private function seedPrizes(): void
    {
        $prizes = [
            ['name' => 'Lavado Gratis', 'type' => 'free_wash', 'value' => 0, 'probability_weight' => 5, 'is_active' => true, 'sort_order' => 1],
            ['name' => '50% de Descuento', 'type' => 'discount_pct', 'value' => 50, 'probability_weight' => 10, 'is_active' => true, 'sort_order' => 2],
            ['name' => '25% de Descuento', 'type' => 'discount_pct', 'value' => 25, 'probability_weight' => 20, 'is_active' => true, 'sort_order' => 3],
            ['name' => '10% de Descuento', 'type' => 'discount_pct', 'value' => 10, 'probability_weight' => 30, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Aromatizante Gratis', 'type' => 'product', 'value' => 50, 'probability_weight' => 25, 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Encerado Gratis', 'type' => 'product', 'value' => 100, 'probability_weight' => 10, 'is_active' => true, 'sort_order' => 6],
        ];

        foreach ($prizes as $prize) {
            Prize::updateOrCreate(['name' => $prize['name']], $prize);
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['name' => 'Lavado Básico', 'price' => 80, 'duration_min' => 15, 'points_earned' => 10, 'stamps_earned' => 1, 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Lavado Premium', 'price' => 150, 'duration_min' => 25, 'points_earned' => 20, 'stamps_earned' => 1, 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Encerado', 'price' => 250, 'duration_min' => 40, 'points_earned' => 35, 'stamps_earned' => 2, 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Detallado Completo', 'price' => 500, 'duration_min' => 90, 'points_earned' => 80, 'stamps_earned' => 3, 'sort_order' => 4, 'is_active' => true],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }

    private function seedMessageTemplates(): void
    {
        $vars = ['nombre', 'telefono', 'nivel', 'visitas', 'puntos', 'racha'];

        // ═══ WHATSAPP TEMPLATES ═══
        $whatsappTemplates = [
            ['type' => 'welcome', 'name' => 'Bienvenida', 'body' => "¡Hola *{nombre}*! 👋 Bienvenido a nuestro programa de fidelización. Por cada lavado acumulas un sello, y al llegar a 8 obtienes un premio sorpresa 🎁🚗"],
            ['type' => 'birthday', 'name' => 'Cumpleaños', 'body' => "🎂 ¡Feliz cumpleaños *{nombre}*! Como regalo, te damos un *lavado gratis* este mes. Solo presenta este mensaje. ¡Disfrútalo! 🎁"],
            ['type' => 'reactivation', 'name' => 'Reactivación 30 días', 'body' => "Hola *{nombre}*, ¡te extrañamos! 😢 Han pasado más de 30 días desde tu última visita. Vuelve esta semana y te damos *20% de descuento* en cualquier servicio."],
            ['type' => 'streak_bonus', 'name' => 'Racha de visitas', 'body' => "🔥 ¡*{nombre}*, llevas {racha} visitas seguidas! Como bonus, tu próximo lavado tiene un descuento especial. ¡Sigue así!"],
            ['type' => 'raffle_winner', 'name' => 'Ganador de rifa', 'body' => "🏆 ¡FELICIDADES *{nombre}*! Eres el ganador de nuestra rifa mensual. Pasa por tu premio cuando gustes. ¡Te esperamos!"],
            ['type' => 'level_up', 'name' => 'Subió de nivel', 'body' => "🎉 ¡*{nombre}*, subiste a nivel *{nivel}*! Ahora obtienes más beneficios cada visita. Gracias por tu lealtad 💙"],
            ['type' => 'vip_renewal', 'name' => 'Renovación VIP', 'body' => "*{nombre}*, tu membresía VIP está por vencer. Renuévala y sigue disfrutando lavados ilimitados, descuentos exclusivos y atención prioritaria 💎"],
            ['type' => 'package_expiring', 'name' => 'Paquete por vencer', 'body' => "Hola *{nombre}*, tu paquete prepago tiene lavados sin usar y está por vencer. ¡Aprovéchalos antes de que expire! 🚗"],
            ['type' => 'post_visit', 'name' => 'Agradecimiento post-visita', 'body' => "Gracias *{nombre}* por tu visita 🙏 Esperamos que hayas quedado satisfecho con tu lavado. ¿Cómo lo calificarías del 1 al 5?"],
            ['type' => 'prize_ready', 'name' => 'Premio listo', 'body' => "🎁 *{nombre}*, ¡completaste tu tarjeta de 8 sellos! Pasa a girar la ruleta y reclamar tu premio sorpresa cuando vengas."],
        ];

        foreach ($whatsappTemplates as $tpl) {
            MessageTemplate::updateOrCreate(
                ['channel' => 'whatsapp', 'type' => $tpl['type']],
                array_merge($tpl, ['channel' => 'whatsapp', 'variables' => $vars, 'is_active' => true])
            );
        }

        // ═══ EMAIL TEMPLATES ═══
        $emailTemplates = [
            ['type' => 'welcome', 'name' => 'Bienvenida', 'subject' => '¡Bienvenido a nuestro programa de fidelización!', 'body' => "Hola {nombre},\n\nGracias por registrarte. Por cada lavado acumulas un sello en tu tarjeta digital. Al completar 8 sellos, obtienes un premio sorpresa de nuestra ruleta.\n\n¡Te esperamos pronto!"],
            ['type' => 'birthday', 'name' => 'Cumpleaños', 'subject' => '🎂 Feliz cumpleaños {nombre}', 'body' => "¡Feliz cumpleaños {nombre}!\n\nEste mes tienes un lavado completamente gratis como nuestro regalo. Solo menciona este correo en tu próxima visita."],
            ['type' => 'reactivation', 'name' => 'Reactivación 60 días', 'subject' => 'Te extrañamos, {nombre}', 'body' => "Hola {nombre},\n\nHan pasado más de 60 días desde tu última visita y queremos verte de nuevo. Tenemos un descuento especial del 25% esperándote."],
            ['type' => 'monthly_summary', 'name' => 'Resumen mensual', 'subject' => 'Tu resumen del mes en {nombre}', 'body' => "Hola {nombre},\n\nEste mes acumulaste {visitas} visitas y {puntos} puntos. Sigue así para subir de nivel y desbloquear más beneficios."],
            ['type' => 'monthly_ranking', 'name' => 'Ranking del mes', 'subject' => 'Top clientes del mes 🏆', 'body' => "Hola {nombre},\n\nVer dónde quedaste en nuestro ranking mensual de clientes. Gracias por ser parte de nuestra comunidad."],
            ['type' => 'raffle_winner', 'name' => 'Ganador de rifa', 'subject' => '🏆 ¡Eres el ganador de la rifa!', 'body' => "FELICIDADES {nombre},\n\nEres el ganador de nuestra rifa mensual. Pasa por tu premio cuando gustes."],
            ['type' => 'invoice', 'name' => 'Recibo / Factura', 'subject' => 'Tu recibo de lavado', 'body' => "Hola {nombre},\n\nGracias por tu visita. Adjunto encontrarás el detalle de tu servicio."],
            ['type' => 'vip_reminder', 'name' => 'Recordatorio VIP', 'subject' => 'Tu membresía VIP', 'body' => "Hola {nombre},\n\nTu membresía VIP está por vencer. Renuévala para seguir disfrutando lavados ilimitados y beneficios exclusivos."],
            ['type' => 'package_expiring', 'name' => 'Paquete por vencer', 'subject' => 'Tu paquete está por vencer', 'body' => "Hola {nombre},\n\nTu paquete prepago tiene lavados sin usar. Aprovéchalos antes de que expire."],
            ['type' => 'survey', 'name' => 'Encuesta post-visita', 'subject' => '¿Cómo estuvo tu lavado?', 'body' => "Hola {nombre},\n\nNos encantaría saber cómo estuvo tu última visita. ¿Podrías dedicarnos 30 segundos?"],
        ];

        foreach ($emailTemplates as $tpl) {
            MessageTemplate::updateOrCreate(
                ['channel' => 'email', 'type' => $tpl['type']],
                array_merge($tpl, ['channel' => 'email', 'variables' => $vars, 'is_active' => true])
            );
        }
    }
}
