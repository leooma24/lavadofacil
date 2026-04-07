<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Stancl\Tenancy\Events\TenancyInitialized;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Path-based tenancy: el parámetro {tenant} en cualquier ruta no puede
        // ser una palabra reservada (rutas centrales). Aplica globalmente, incluido
        // el panel admin de Filament que vive en /{tenant}/admin.
        Route::pattern('tenant', '(?!central|admin|api|tenancy|livewire|filament|storage|images|build|css|js|favicon\.ico|robots\.txt|sitemap\.xml|up)[A-Za-z0-9_-]+');

        // Path-based tenancy: cuando stancl inicializa un tenant, registramos
        // su slug como default del parámetro {tenant} en el URL generator. Así
        // route('customer.login') auto-rellena {tenant} sin pasarlo a mano.
        Event::listen(TenancyInitialized::class, function (TenancyInitialized $event) {
            // Default del parámetro {tenant} en route() helpers
            URL::defaults(['tenant' => $event->tenancy->tenant->getTenantKey()]);
        });

        // Livewire update route corre globalmente y NO pasa por los middleware
        // del PanelProvider. Hay que registrarla manualmente con el middleware
        // de tenancy para que Filament admin del tenant funcione (login, CRUDs).
        // Patrón canónico stancl + Filament path-based: registrar la ruta de
        // Livewire DENTRO del prefijo {tenant} para que InitializeTenancyByPath
        // pueda resolver el tenant del segmento del path (no del Referer).
        // Sin esto, las requests Livewire del panel del tenant llegan a una
        // ruta sin contexto y Filament muestra "Session expired".
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/{tenant}/livewire/update', $handle)
                ->middleware([
                    'web',
                    InitializeTenancyByPath::class,
                ]);
        });

        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/livewire/livewire.js', $handle);
        });
    }
}
