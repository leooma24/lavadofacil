<?php

declare(strict_types=1);

use App\Http\Controllers\Customer\AppController;
use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\PushController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/*
|--------------------------------------------------------------------------
| Tenant Routes — PWA del cliente del car wash
|--------------------------------------------------------------------------
|
| Path-based tenancy: lavadofacil.tu-app.co/{tenant}/...
| El parámetro {tenant} es el slug del car wash. stancl resuelve el tenant
| automáticamente y lo "consume" del request, así que los controladores no
| reciben {tenant} como argumento.
|
*/

// Patrón global: el {tenant} no puede ser una palabra reservada (rutas centrales).
// Sin esto, /central, /admin, /livewire, etc. serían interpretados como tenants.
Route::pattern('tenant', '(?!central|admin|api|tenancy|livewire|filament|storage|images|build|css|js|favicon\.ico|robots\.txt|sitemap\.xml|up)[A-Za-z0-9_-]+');

Route::middleware([
    'web',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}')->group(function () {
    // Raíz → app o login según sesión
    Route::get('/', fn () => auth('customer')->check()
        ? redirect()->route('customer.home', ['tenant' => tenant('id')])
        : redirect()->route('customer.login', ['tenant' => tenant('id')]))->name('tenant.home');

    // PWA manifest dinámico por tenant — start_url/scope con el slug para que
    // al instalar como acceso directo abra la URL correcta y no /app (que
    // stancl intentaría resolver como tenant y devolvería 500).
    Route::get('/manifest.webmanifest', function () {
        $t = tenant();
        $slug = $t->id;
        return response()->json([
            'name' => $t->name,
            'short_name' => $t->name,
            'description' => 'Programa de fidelización de '.$t->name,
            'start_url' => '/'.$slug.'/app',
            'scope' => '/'.$slug.'/',
            'display' => 'standalone',
            'orientation' => 'portrait',
            'background_color' => '#0a0a0a',
            'theme_color' => $t->primary_color ?? '#10b981',
            'icons' => [
                [
                    'src' => '/images/lavadofacil_icon.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable',
                ],
                [
                    'src' => '/images/lavadofacil_icon.png',
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable',
                ],
            ],
        ], 200, ['Content-Type' => 'application/manifest+json']);
    })->name('customer.manifest');

    // Auth público
    Route::get('/login', [AuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('customer.logout');

    // Auto-login para el demo público (solo funciona en lavadodemo)
    Route::get('/demo-entrar', [AuthController::class, 'autoLoginDemo'])->name('customer.demo.enter');

    // App protegida
    Route::middleware('auth:customer')->group(function () {
        Route::get('/app', [AppController::class, 'home'])->name('customer.home');
        Route::get('/app/visitas', [AppController::class, 'visits'])->name('customer.visits');
        Route::get('/app/premios', [AppController::class, 'prizes'])->name('customer.prizes');
        Route::get('/app/perfil', [AppController::class, 'profile'])->name('customer.profile');
        Route::get('/app/ranking', [AppController::class, 'ranking'])->name('customer.ranking');

        // Catálogo + reservas
        Route::get('/app/catalogo', [AppController::class, 'catalog'])->name('customer.catalog');
        Route::get('/app/servicio/{service}', [AppController::class, 'showService'])->name('customer.service.show');
        Route::post('/app/servicio/{service}/reservar', [AppController::class, 'bookService'])->name('customer.service.book');
        Route::get('/app/citas', [AppController::class, 'appointments'])->name('customer.appointments');
        Route::post('/app/citas/{appointment}/respuesta', [AppController::class, 'respondAppointment'])->name('customer.appointment.respond');

        // Push notifications
        Route::get('/app/push/key', [PushController::class, 'vapidKey'])->name('customer.push.key');
        Route::post('/app/push/subscribe', [PushController::class, 'subscribe'])->name('customer.push.subscribe');
        Route::post('/app/push/unsubscribe', [PushController::class, 'unsubscribe'])->name('customer.push.unsubscribe');

        // Encuesta post-visita
        Route::get('/app/encuesta/{visit}', [AppController::class, 'showSurvey'])->name('customer.survey.show');
        Route::post('/app/encuesta/{visit}', [AppController::class, 'storeSurvey'])->name('customer.survey.store');
    });
});
