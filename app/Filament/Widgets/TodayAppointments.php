<?php

namespace App\Filament\Widgets;

use App\Models\Tenant\Appointment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TodayAppointments extends BaseWidget
{
    protected static ?string $heading = 'Citas de hoy';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = ['md' => 1, 'xl' => 1];

    protected function getTableQuery(): Builder
    {
        return Appointment::query()
            ->whereDate('scheduled_at', today())
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->orderBy('scheduled_at')
            ->limit(8);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('scheduled_at')->label('Hora')->time('H:i')->weight('bold'),
            Tables\Columns\TextColumn::make('customer.name')->label('Cliente')->limit(20),
            Tables\Columns\TextColumn::make('type')
                ->label('')
                ->formatStateUsing(fn ($s) => $s === 'at_home' ? '🏠' : '🏪'),
            Tables\Columns\BadgeColumn::make('status')
                ->label('Estado')
                ->colors([
                    'gray' => 'pending',
                    'info' => 'confirmed',
                    'warning' => ['in_queue', 'in_progress'],
                    'success' => 'ready',
                ])
                ->formatStateUsing(fn (Appointment $r) => $r->statusLabel()),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'Sin citas hoy';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Cuando un cliente reserve, aparecerá aquí';
    }
}
