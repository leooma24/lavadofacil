<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Redirección por defecto cuando un guest entra a una ruta protegida.
        $middleware->redirectGuestsTo(function ($request) {
            if ($request->routeIs('customer.*') || str_starts_with($request->path(), tenant('id') ?? '___')) {
                return route('customer.login');
            }
            return null;
        });

        // Demo read-only: en el tenant `lavadodemo` ningún cambio persiste
        // (transacción que se hace rollback al terminar el request).
        $middleware->appendToGroup('web', \App\Http\Middleware\DemoReadOnly::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
