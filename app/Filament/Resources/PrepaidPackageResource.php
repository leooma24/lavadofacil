<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrepaidPackageResource\Pages;
use App\Models\Tenant\PrepaidPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PrepaidPackageResource extends Resource
{
    protected static ?string $model = PrepaidPackage::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Paquetes prepago';
    protected static ?string $modelLabel = 'Paquete';
    protected static ?string $pluralModelLabel = 'Paquetes';
    protected static ?string $navigationGroup = 'Monetización';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('prepaid');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Paquete')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                Forms\Components\TextInput::make('washes_count')
                    ->label('Cantidad de lavados')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->prefix('$')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('validity_days')
                    ->label('Validez (días)')
                    ->numeric()
                    ->default(365),
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
                Tables\Columns\TextColumn::make('name')->label('Paquete')->searchable(),
                Tables\Columns\TextColumn::make('washes_count')->label('Lavados')->badge(),
                Tables\Columns\TextColumn::make('price')->label('Precio')->money('MXN'),
                Tables\Columns\TextColumn::make('price_per_wash')
                    ->label('$ / lavado')
                    ->state(fn (PrepaidPackage $r) => '$'.number_format($r->price / max(1, $r->washes_count), 2)),
                Tables\Columns\TextColumn::make('validity_days')->label('Validez')->suffix(' días'),
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
            'index' => Pages\ListPrepaidPackages::route('/'),
            'create' => Pages\CreatePrepaidPackage::route('/create'),
            'edit' => Pages\EditPrepaidPackage::route('/{record}/edit'),
        ];
    }
}
