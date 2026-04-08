<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();

        if ($user && ($user->must_change_password ?? false)) {
            // Permitir la página de perfil y el logout
            $profileRoute = 'filament.admin.auth.profile';
            $logoutRoute = 'filament.admin.auth.logout';
            $currentRoute = $request->route()?->getName();

            $isLivewireUpdate = $request->is('livewire/*');

            if (! $isLivewireUpdate
                && $currentRoute !== $profileRoute
                && $currentRoute !== $logoutRoute) {
                return redirect()->route($profileRoute, ['tenant' => tenant('id')])
                    ->with('warning', 'Por seguridad, cambia tu contraseña antes de continuar.');
            }
        }

        return $next($request);
    }
}
