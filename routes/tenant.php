<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Rutas dentro del subdominio del tenant ({slug}.lavadofacil.test).
| El panel /admin de Filament tiene su propio middleware en AdminPanelProvider,
| así que aquí solo van las rutas públicas (PWA cliente).
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // PWA del cliente del car wash (Livewire) — esqueleto, se completa en fase posterior
    Route::get('/', function () {
        return view('tenant.welcome', [
            'tenant' => tenant(),
        ]);
    })->name('tenant.home');
});
