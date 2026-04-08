<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/**
 * Panel del DUEÑO del car wash.
 * Vive en lavadofacil.tu-app.co/{tenant}/admin (path-based tenancy).
 * Usa la BD del tenant (no la central).
 * Login con tabla `users` (no `central_users`).
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('{tenant}/admin')
            ->login()
            ->profile(\App\Filament\Pages\Auth\EditProfile::class)
            ->brandName('LavadoFácil')
            ->brandLogo(asset('images/lavadofacil_logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/lavadofacil_icon.png'))
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Cyan,
                'gray' => Color::Slate,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => Blade::render('@include("filament.custom-theme")'),
            )
            ->authGuard('web')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\OverviewStats::class,
                \App\Filament\Widgets\RevenueChart::class,
                \App\Filament\Widgets\TopCustomers::class,
                \App\Filament\Widgets\TodayAppointments::class,
            ])
            ->middleware([
                InitializeTenancyByPath::class,
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\ForcePasswordChange::class,
            ]);
    }
}
