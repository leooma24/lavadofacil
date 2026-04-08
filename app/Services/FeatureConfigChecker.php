<?php

namespace App\Services;

use App\Models\Tenant\LevelConfig;
use App\Models\Tenant\MessageTemplate;
use App\Models\Tenant\MonthlyChallenge;
use App\Models\Tenant\PrepaidPackage;
use App\Models\Tenant\Prize;
use App\Models\Tenant\Raffle;
use App\Models\Tenant\Survey;
use App\Models\Tenant\VipSubscription;

/**
 * Verifica si una feature del tenant tiene configuración mínima.
 * Usado al activar features desde FeatureSettings para sugerir al owner
 * que complete su setup si falta.
 */
class FeatureConfigChecker
{
    /**
     * @return bool True si la feature tiene configuración mínima lista.
     */
    public static function isConfigured(string $feature): bool
    {
        return match ($feature) {
            'loyalty_card',
            'rewards_wheel' => Prize::where('is_active', true)->exists(),

            'levels' => LevelConfig::count() >= 4,

            'whatsapp' => MessageTemplate::where('channel', 'whatsapp')
                ->where('is_active', true)
                ->exists(),

            'birthdays' => MessageTemplate::where('type', 'birthday')
                ->where('is_active', true)
                ->exists(),

            'raffle' => Raffle::exists(),

            'vip' => VipSubscription::exists(),

            'prepaid' => PrepaidPackage::exists(),

            'surveys' => Survey::exists(),

            'challenges' => MonthlyChallenge::exists(),

            // Sin configuración específica requerida
            'streaks',
            'referrals',
            'reactivation',
            'ranking',
            'forecast' => true,

            default => true,
        };
    }

    /**
     * URL del Resource donde el dueño puede configurar la feature.
     * Se resuelve vía las clases de Resource para que respete el path-tenancy.
     */
    public static function configureUrl(string $feature): ?string
    {
        $resource = match ($feature) {
            'loyalty_card', 'rewards_wheel' => \App\Filament\Resources\PrizeResource::class,
            'levels' => \App\Filament\Resources\LevelConfigResource::class,
            'whatsapp', 'birthdays' => \App\Filament\Resources\MessageTemplateResource::class,
            'raffle' => \App\Filament\Resources\RaffleResource::class,
            'vip' => \App\Filament\Resources\VipSubscriptionResource::class,
            'prepaid' => \App\Filament\Resources\PrepaidPackageResource::class,
            'surveys' => \App\Filament\Resources\SurveyResource::class,
            'challenges' => \App\Filament\Resources\ChallengeResource::class,
            default => null,
        };

        return $resource ? $resource::getUrl('index') : null;
    }
}
