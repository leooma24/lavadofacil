<?php

namespace App\Filament\Widgets;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStats extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today = today();
        $monthStart = now()->startOfMonth();

        $visitsToday = Visit::whereDate('visited_at', $today)->count();
        $revenueToday = (float) Visit::whereDate('visited_at', $today)->sum('total');

        $visitsMonth = Visit::where('visited_at', '>=', $monthStart)->count();
        $revenueMonth = (float) Visit::where('visited_at', '>=', $monthStart)->sum('total');

        $newCustomersMonth = Customer::where('created_at', '>=', $monthStart)->count();
        $totalCustomers = Customer::count();

        // Sparkline: visitas por día últimos 7 días
        $sparkline = collect(range(6, 0))
            ->map(fn ($d) => Visit::whereDate('visited_at', now()->subDays($d))->count())
            ->all();

        // Sparkline ingresos
        $revenueSparkline = collect(range(6, 0))
            ->map(fn ($d) => (float) Visit::whereDate('visited_at', now()->subDays($d))->sum('total'))
            ->all();

        // Sparkline clientes nuevos
        $customersSparkline = collect(range(6, 0))
            ->map(fn ($d) => Customer::whereDate('created_at', now()->subDays($d))->count())
            ->all();

        return [
            Stat::make('Visitas hoy', $visitsToday)
                ->description('$' . number_format($revenueToday, 2) . ' generados hoy')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($sparkline)
                ->color('success'),

            Stat::make('Ingresos del mes', '$' . number_format($revenueMonth, 0))
                ->description($visitsMonth . ' visitas en el mes')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($revenueSparkline)
                ->color('primary'),

            Stat::make('Clientes totales', $totalCustomers)
                ->description("+{$newCustomersMonth} nuevos este mes")
                ->descriptionIcon('heroicon-m-user-plus')
                ->chart($customersSparkline)
                ->color('warning'),
        ];
    }
}
