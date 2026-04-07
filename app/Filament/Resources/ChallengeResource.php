<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChallengeResource\Pages;
use App\Models\Tenant\MonthlyChallenge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChallengeResource extends Resource
{
    protected static ?string $model = MonthlyChallenge::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'Retos';
    protected static ?string $modelLabel = 'Reto';
    protected static ?string $pluralModelLabel = 'Retos';
    protected static ?string $navigationGroup = 'Engagement';
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('challenges');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Reto')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                Forms\Components\Select::make('goal_type')
                    ->label('Tipo de meta')
                    ->required()
                    ->options([
                        'visits' => 'Número de visitas',
                        'spent' => 'Monto gastado',
                        'services_count' => 'Cantidad de servicios',
                        'specific_service' => 'Servicio específico',
                        'referrals' => 'Referidos traídos',
                    ]),
                Forms\Components\TextInput::make('goal_value')
                    ->label('Meta a alcanzar')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('month')
                    ->label('Mes')
                    ->required()
                    ->default(now()->month)
                    ->options([
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
                    ]),
                Forms\Components\TextInput::make('year')->label('Año')->numeric()->default(now()->year)->required(),
                Forms\Components\TextInput::make('reward_points')->label('Puntos de recompensa')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->label('Activo')->default(true),
                Forms\Components\TextInput::make('reward_description')
                    ->label('Premio')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')->label('Descripción')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Reto')->searchable(),
                Tables\Columns\TextColumn::make('goal_type')->label('Tipo')->badge(),
                Tables\Columns\TextColumn::make('goal_value')->label('Meta'),
                Tables\Columns\TextColumn::make('reward_description')->label('Premio')->limit(40),
                Tables\Columns\TextColumn::make('progress_count')
                    ->label('Participantes')
                    ->counts('progress')
                    ->badge(),
                Tables\Columns\TextColumn::make('month')
                    ->label('Periodo')
                    ->formatStateUsing(fn ($s, MonthlyChallenge $r) => sprintf('%02d/%d', $s, $r->year)),
                Tables\Columns\IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('year', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChallenges::route('/'),
            'create' => Pages\CreateChallenge::route('/create'),
            'edit' => Pages\EditChallenge::route('/{record}/edit'),
        ];
    }
}
