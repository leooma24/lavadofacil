<?php

namespace App\Filament\Pages;

use App\Models\Tenant\Customer;
use App\Models\Tenant\Visit;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class Ranking extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Engagement';
    protected static ?string $navigationLabel = 'Ranking';
    protected static ?string $title = 'Ranking del mes';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.ranking';

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('ranking');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
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
                    ->orderByDesc('visits_month');
            })
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->label('#')
                    ->state(fn ($rowLoop) => $rowLoop->iteration)
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => '🥇',
                        2 => '🥈',
                        3 => '🥉',
                        default => "#{$state}",
                    }),
                Tables\Columns\TextColumn::make('name')->label('Cliente')->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Nivel')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bronze' => '🥉 Bronce',
                        'silver' => '🥈 Plata',
                        'gold' => '🥇 Oro',
                        'platinum' => '💎 Platino',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('visits_month')->label('Visitas mes')->sortable(),
                Tables\Columns\TextColumn::make('spent_month')->label('Gastado mes')
                    ->money('MXN')->sortable(),
            ])
            ->paginated([10, 25, 50]);
    }
}
