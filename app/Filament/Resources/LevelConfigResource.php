<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelConfigResource\Pages;
use App\Models\Tenant\LevelConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LevelConfigResource extends Resource
{
    protected static ?string $model = LevelConfig::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Niveles';
    protected static ?string $modelLabel = 'Nivel';
    protected static ?string $pluralModelLabel = 'Niveles';
    protected static ?string $navigationGroup = 'Fidelización';
    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('levels');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Configuración del nivel')->columns(2)->schema([
                Forms\Components\Select::make('level')
                    ->label('Nivel')
                    ->required()
                    ->options([
                        'bronze' => '🥉 Bronce',
                        'silver' => '🥈 Plata',
                        'gold' => '🥇 Oro',
                        'platinum' => '💎 Platino',
                    ]),
                Forms\Components\TextInput::make('min_visits')
                    ->label('Visitas mínimas')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\TextInput::make('min_spent')
                    ->label('Gasto mínimo')
                    ->prefix('$')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\TextInput::make('multiplier')
                    ->label('Multiplicador de puntos')
                    ->numeric()
                    ->default(1.00)
                    ->step(0.1)
                    ->helperText('1.0 = normal, 1.5 = 50% más puntos, 2.0 = doble'),
                Forms\Components\TextInput::make('color')
                    ->label('Color')
                    ->default('#cd7f32'),
                Forms\Components\TextInput::make('sort_order')->label('Orden')->numeric()->default(0),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('#'),
                Tables\Columns\TextColumn::make('level')
                    ->label('Nivel')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bronze' => '🥉 Bronce',
                        'silver' => '🥈 Plata',
                        'gold' => '🥇 Oro',
                        'platinum' => '💎 Platino',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('min_visits')->label('Visitas min')->sortable(),
                Tables\Columns\TextColumn::make('min_spent')->label('Gasto min')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('multiplier')->label('Mult. puntos')->suffix('x'),
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
            'index' => Pages\ListLevelConfigs::route('/'),
            'create' => Pages\CreateLevelConfig::route('/create'),
            'edit' => Pages\EditLevelConfig::route('/{record}/edit'),
        ];
    }
}
