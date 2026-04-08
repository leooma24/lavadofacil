<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Demo read-only para el tenant `lavadodemo`.
 *
 * Estrategia: abrimos una transacción en la conexión `tenant` al inicio
 * del request y la hacemos rollback al final (en terminate). Así Filament
 * muestra su UI normal ("Guardado", "Eliminado", etc.) para buena UX de
 * demo, pero los cambios NUNCA llegan a la BD.
 *
 * Las sesiones viven en la BD central (conexión `mysql`), así que el login
 * y todo lo demás siguen funcionando.
 */
class DemoReadOnly
{
    public function handle(Request $request, Closure $next)
    {
        $tenant = tenant();
        if (! $tenant || $tenant->getTenantKey() !== 'lavadodemo') {
            return $next($request);
        }

        // GETs no necesitan transacción
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return $next($request);
        }

        // Abrimos transacción en la conexión del tenant
        try {
            DB::connection('tenant')->beginTransaction();
        } catch (\Throwable $e) {
            return $next($request);
        }

        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        $tenant = tenant();
        if (! $tenant || $tenant->getTenantKey() !== 'lavadodemo') {
            return;
        }

        try {
            $conn = DB::connection('tenant');
            if ($conn->transactionLevel() > 0) {
                $conn->rollBack();
            }
        } catch (\Throwable $e) {
            Log::warning('DemoReadOnly rollback failed: '.$e->getMessage());
        }
    }
}
