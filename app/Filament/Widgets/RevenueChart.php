<?php

namespace App\Filament\Widgets;

use App\Models\Tenant\Visit;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos últimos 14 días';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn ($d) => now()->subDays($d));

        $revenue = $days->map(fn ($d) => (float) Visit::whereDate('visited_at', $d)->sum('total'));
        $visits = $days->map(fn ($d) => Visit::whereDate('visited_at', $d)->count());

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos $',
                    'data' => $revenue->all(),
                    'borderColor' => '#06b6d4',
                    'backgroundColor' => 'rgba(6, 182, 212, 0.15)',
                    'fill' => true,
                    'tension' => 0.4,
                    'borderWidth' => 3,
                    'pointBackgroundColor' => '#06b6d4',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
                [
                    'label' => 'Visitas',
                    'data' => $visits->all(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.0)',
                    'fill' => false,
                    'tension' => 0.4,
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                    'pointRadius' => 0,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->format('d/m'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => true, 'position' => 'top', 'align' => 'end'],
            ],
            'scales' => [
                'y' => ['beginAtZero' => true, 'position' => 'left'],
                'y1' => ['beginAtZero' => true, 'position' => 'right', 'grid' => ['drawOnChartArea' => false]],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
