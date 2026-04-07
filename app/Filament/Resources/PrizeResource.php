<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrizeResource\Pages;
use App\Models\Tenant\Prize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PrizeResource extends Resource
{
    protected static ?string $model = Prize::class;
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Premios';
    protected static ?string $modelLabel = 'Premio';
    protected static ?string $pluralModelLabel = 'Premios';
    protected static ?string $navigationGroup = 'Fidelización';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('rewards_wheel');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Premio')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->required()
                    ->options([
                        'free_wash' => 'Lavado gratis',
                        'discount_pct' => 'Descuento %',
                        'discount_amount' => 'Descuento $',
                        'product' => 'Producto',
                        'cash' => 'Efectivo',
                        'custom' => 'Otro',
                    ]),
                Forms\Components\TextInput::make('value')
                    ->label('Valor')
                    ->numeric()
                    ->default(0)
                    ->helperText('% o $ según el tipo'),
                Forms\Components\TextInput::make('probability_weight')
                    ->label('Peso (probabilidad)')
                    ->numeric()
                    ->default(10)
                    ->minValue(1)
                    ->helperText('A mayor número, más probable. Ej: 50 vs 5 = 10x más probable'),
                Forms\Components\TextInput::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->helperText('Vacío = ilimitado'),
                Forms\Components\TextInput::make('sort_order')->label('Orden')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->label('Activo')->default(true),
                Forms\Components\Textarea::make('description')->label('Descripción')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Premio')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'free_wash' => '🚿 Lavado gratis',
                        'discount_pct' => '% Descuento',
                        'discount_amount' => '$ Descuento',
                        'product' => '🎁 Producto',
                        'cash' => '💵 Efectivo',
                        'custom' => 'Otro',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('value')->label('Valor'),
                Tables\Columns\TextColumn::make('probability_weight')->label('Peso')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('stock')->label('Stock')->placeholder('∞'),
                Tables\Columns\IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrizes::route('/'),
            'create' => Pages\CreatePrize::route('/create'),
            'edit' => Pages\EditPrize::route('/{record}/edit'),
        ];
    }
}
