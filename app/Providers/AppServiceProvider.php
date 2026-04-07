<?php

namespace App\Providers;

use App\Http\Middleware\InitializeTenancyForLivewire;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Stancl\Tenancy\Events\TenancyInitialized;

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
        // Livewire update route GLOBAL en /livewire/update.
        // Si la registráramos bajo /{tenant}/livewire/update, las páginas del
        // panel CENTRAL romperían al renderizar (URL::route('livewire.update')
        // exige el param {tenant} que no existe en contexto central).
        //
        // En su lugar, InitializeTenancyForLivewire detecta el tenant a partir
        // del header Referer (que sí trae la URL completa con el slug) y lo
        // inicializa ANTES que StartSession para que la sesión se lea de la
        // BD del tenant correcto.
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/livewire/update', $handle)
                ->middleware([
                    InitializeTenancyForLivewire::class,
                    'web',
                ]);
        });
    }
}
