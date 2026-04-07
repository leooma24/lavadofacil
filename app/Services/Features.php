<?php

namespace App\Services;

use App\Models\Tenant;

/**
 * Helper para feature toggles del tenant actual.
 *
 * Patrón híbrido:
 * - El plan define qué features están DISPONIBLES (plans.features)
 * - El dueño define qué features están ACTIVAS (tenants.enabled_features)
 *
 * Una feature está habilitada solo si:
 *   1) El plan del tenant la incluye
 *   2) El dueño la activó (o no la ha desactivado explícitamente)
 */
class Features
{
    /**
     * Catálogo maestro de features. Source of truth para labels y descripciones.
     */
    public const CATALOG = [
        'loyalty_card' => [
            'label' => 'Tarjeta de sellos',
            'description' => 'Tarjeta de 8 sellos con premio al completarla',
            'group' => 'Fidelización',
        ],
        'rewards_wheel' => [
            'label' => 'Ruleta de premios',
            'description' => 'Ruleta con premios ponderados al completar tarjeta',
            'group' => 'Fidelización',
        ],
        'raffle' => [
            'label' => 'Rifa mensual',
            'description' => 'Cada visita otorga tickets para la rifa del mes',
            'group' => 'Fidelización',
        ],
        'levels' => [
            'label' => 'Niveles',
            'description' => 'Bronce, Plata, Oro, Platino según historial',
            'group' => 'Fidelización',
        ],
        'streaks' => [
            'label' => 'Racha de visitas',
            'description' => 'Bonus por visitar varios días/semanas seguidas',
            'group' => 'Fidelización',
        ],
        'birthdays' => [
            'label' => 'Cumpleaños',
            'description' => 'Felicitación y premio automático en su cumpleaños',
            'group' => 'Marketing',
        ],
        'referrals' => [
            'label' => 'Referidos',
            'description' => 'Código único de referido con recompensa',
            'group' => 'Marketing',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp',
            'description' => 'Botones de envío manual vía wa.me con plantillas',
            'group' => 'Marketing',
        ],
        'reactivation' => [
            'label' => 'Reactivación',
            'description' => 'Detecta clientes dormidos y los reactiva',
            'group' => 'Marketing',
        ],
        'ranking' => [
            'label' => 'Ranking mensual',
            'description' => 'Top de clientes más frecuentes del mes',
            'group' => 'Engagement',
        ],
        'challenges' => [
            'label' => 'Reto mensual',
            'description' => 'Reto configurable con premio al completarlo',
            'group' => 'Engagement',
        ],
        'surveys' => [
            'label' => 'Encuestas',
            'description' => 'Encuesta post-visita para medir satisfacción',
            'group' => 'Engagement',
        ],
        'vip' => [
            'label' => 'Suscripción VIP',
            'description' => 'Membresía mensual con lavados ilimitados',
            'group' => 'Monetización',
        ],
        'prepaid' => [
            'label' => 'Paquetes prepago',
            'description' => 'Venta de paquetes de lavados con descuento',
            'group' => 'Monetización',
        ],
        'forecast' => [
            'label' => 'Predicción de ganancias',
            'description' => 'Estadísticas y proyección de ingresos',
            'group' => 'Reportes',
        ],
    ];

    /**
     * ¿La feature está activa para el tenant actual?
     */
    public static function enabled(string $key): bool
    {
        $tenant = tenant();
        if (! $tenant instanceof Tenant) {
            return false;
        }

        // 1) ¿El plan la incluye?
        if (! self::availableInPlan($tenant, $key)) {
            return false;
        }

        // 2) ¿El dueño la tiene activada? (si nunca la tocó, default = true)
        $enabled = $tenant->enabled_features ?? [];

        return $enabled[$key] ?? true;
    }

    /**
     * ¿La feature está incluida en el plan del tenant?
     */
    public static function availableInPlan(Tenant $tenant, string $key): bool
    {
        $plan = $tenant->plan;
        if (! $plan) {
            return false;
        }

        $planFeatures = $plan->features ?? [];

        return (bool) ($planFeatures[$key] ?? false);
    }

    /**
     * Lista de features disponibles en el plan del tenant actual,
     * con su estado actual (on/off) — para pintar la página de configuración.
     *
     * @return array<string, array{label: string, description: string, group: string, enabled: bool}>
     */
    public static function availableForCurrentTenant(): array
    {
        $tenant = tenant();
        if (! $tenant instanceof Tenant || ! $tenant->plan) {
            return [];
        }

        $planFeatures = $tenant->plan->features ?? [];
        $enabled = $tenant->enabled_features ?? [];

        $result = [];
        foreach (self::CATALOG as $key => $meta) {
            if (! ($planFeatures[$key] ?? false)) {
                continue;
            }
            $result[$key] = $meta + ['enabled' => $enabled[$key] ?? true];
        }

        return $result;
    }
}
