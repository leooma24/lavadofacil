<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoyaltyCardResource\Pages;
use App\Models\Tenant\LoyaltyCard;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LoyaltyCardResource extends Resource
{
    protected static ?string $model = LoyaltyCard::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Tarjetas de sellos';
    protected static ?string $modelLabel = 'Tarjeta';
    protected static ?string $pluralModelLabel = 'Tarjetas';
    protected static ?string $navigationGroup = 'Fidelización';
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('loyalty_card');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Cliente')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer.phone')->label('Teléfono')->searchable(),
                Tables\Columns\TextColumn::make('stamps_count')
                    ->label('Sellos actuales')
                    ->badge()
                    ->color(fn (int $state) => $state >= 7 ? 'success' : ($state >= 4 ? 'warning' : 'gray'))
                    ->formatStateUsing(fn (int $state) => "{$state} / 8"),
                Tables\Columns\TextColumn::make('current_card_number')->label('Tarjeta #')->sortable(),
                Tables\Columns\TextColumn::make('completed_count')->label('Completadas')->sortable(),
                Tables\Columns\TextColumn::make('last_stamp_at')->label('Último sello')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('almost_complete')
                    ->label('Casi completas (≥6 sellos)')
                    ->query(fn ($q) => $q->where('stamps_count', '>=', 6)),
            ])
            ->defaultSort('stamps_count', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoyaltyCards::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
