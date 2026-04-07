<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;

/**
 * Inicializa tenancy para la ruta global de Livewire `/livewire/update`,
 * que NO incluye el slug en su URL. Extraemos el slug del header Referer
 * (que sí lleva la URL completa de donde el usuario está navegando, ej:
 * https://lavadofacil.tu-app.co/lavadodemo/admin/customers).
 *
 * Si no hay referer (o es del dominio central /central), no inicializamos
 * tenancy y dejamos pasar la request — sirve al panel central.
 */
class InitializeTenancyForLivewire
{
    public function handle($request, Closure $next)
    {
        $referer = $request->headers->get('referer');

        if ($referer) {
            $path = ltrim(parse_url($referer, PHP_URL_PATH) ?? '', '/');
            $first = explode('/', $path)[0] ?? '';

            // Reservados — no son tenants
            $reserved = ['central', 'admin', 'api', 'tenancy', 'livewire', 'filament', 'storage', 'images', 'build', 'css', 'js', ''];

            if (! in_array($first, $reserved, true)) {
                $tenant = Tenant::find($first);
                if ($tenant) {
                    tenancy()->initialize($tenant);
                }
                // Si no existe, sigue como central (no rompemos la request)
            }
        }

        return $next($request);
    }
}
