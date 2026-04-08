<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceOnboarding
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();
        $tenant = tenant();

        // Solo aplica a owners cuando el tenant no ha completado onboarding
        // y no cuando aún debe cambiar password (ese middleware corre antes).
        if (
            $user
            && $user->role === 'owner'
            && ! ($user->must_change_password ?? false)
            && $tenant
            && ! $tenant->onboarding_completed_at
        ) {
            $onboardingRoute = 'filament.admin.pages.onboarding';
            $profileRoute = 'filament.admin.auth.profile';
            $logoutRoute = 'filament.admin.auth.logout';
            $currentRoute = $request->route()?->getName();
            $isLivewireUpdate = $request->is('livewire/*');

            if (
                ! $isLivewireUpdate
                && $currentRoute !== $onboardingRoute
                && $currentRoute !== $profileRoute
                && $currentRoute !== $logoutRoute
            ) {
                return redirect()->route($onboardingRoute, ['tenant' => tenant('id')]);
            }
        }

        return $next($request);
    }
}
