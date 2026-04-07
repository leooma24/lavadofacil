<?php

namespace App\Filament\Widgets;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Visit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopCustomers extends BaseWidget
{
    protected static ?string $heading = 'Top clientes del mes';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = ['md' => 1, 'xl' => 1];

    protected function getTableQuery(): Builder
    {
        $monthStart = now()->startOfMonth();

        return Customer::query()
            ->select('customers.*')
            ->selectSub(
                Visit::selectRaw('COUNT(*)')
                    ->whereColumn('customer_id', 'customers.id')
                    ->where('visited_at', '>=', $monthStart),
                'visits_month',
            )
            ->selectSub(
                Visit::selectRaw('COALESCE(SUM(total),0)')
                    ->whereColumn('customer_id', 'customers.id')
                    ->where('visited_at', '>=', $monthStart),
                'spent_month',
            )
            ->whereHas('visits', fn ($q) => $q->where('visited_at', '>=', $monthStart))
            ->orderByDesc('visits_month')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('rank')
                ->label('#')
                ->state(fn ($rowLoop) => match ($rowLoop->iteration) {
                    1 => '🥇', 2 => '🥈', 3 => '🥉',
                    default => '#'.$rowLoop->iteration,
                }),
            Tables\Columns\TextColumn::make('name')->label('Cliente')->weight('bold'),
            Tables\Columns\TextColumn::make('visits_month')->label('Visitas')->badge()->color('success'),
            Tables\Columns\TextColumn::make('spent_month')->label('Gastado')->money('MXN')->color('primary'),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
