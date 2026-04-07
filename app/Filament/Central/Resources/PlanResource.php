<?php

namespace App\Filament\Central\Resources;

use App\Filament\Central\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Planes';
    protected static ?string $modelLabel = 'Plan';
    protected static ?string $pluralModelLabel = 'Planes';
    protected static ?string $navigationGroup = 'Plataforma';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del Plan')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                Forms\Components\TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->label('Descripción')->columnSpanFull(),
                Forms\Components\TextInput::make('price_monthly')->label('Precio mensual')->numeric()->prefix('$')->required(),
                Forms\Components\TextInput::make('price_yearly')->label('Precio anual')->numeric()->prefix('$')->required(),
                Forms\Components\TextInput::make('max_customers')->label('Máx. clientes (vacío = ilimitado)')->numeric(),
                Forms\Components\TextInput::make('max_locations')->label('Máx. sucursales')->numeric()->default(1),
                Forms\Components\TextInput::make('max_staff')->label('Máx. personal')->numeric()->default(2),
                Forms\Components\Toggle::make('is_active')->label('Activo')->default(true),
                Forms\Components\TextInput::make('sort_order')->label('Orden')->numeric()->default(0),
            ]),
            Forms\Components\Section::make('Features incluidas')->columns(2)->schema([
                Forms\Components\Toggle::make('features.ruleta')->label('Ruleta de premios'),
                Forms\Components\Toggle::make('features.rifa')->label('Rifa mensual'),
                Forms\Components\Toggle::make('features.niveles')->label('Niveles (Bronce/Plata/Oro/Platino)'),
                Forms\Components\Toggle::make('features.referidos')->label('Referidos'),
                Forms\Components\Toggle::make('features.cumpleanos')->label('Cumpleaños automático'),
                Forms\Components\Toggle::make('features.racha')->label('Racha de visitas'),
                Forms\Components\Toggle::make('features.reactivacion')->label('Reactivación dormidos'),
                Forms\Components\Toggle::make('features.ranking')->label('Ranking mensual'),
                Forms\Components\Toggle::make('features.reto')->label('Reto mensual'),
                Forms\Components\Toggle::make('features.encuesta')->label('Encuesta post-visita'),
                Forms\Components\Toggle::make('features.vip')->label('Suscripción VIP'),
                Forms\Components\Toggle::make('features.paquetes')->label('Paquetes prepago'),
                Forms\Components\Toggle::make('features.prediccion')->label('Predicción de ganancias'),
                Forms\Components\Toggle::make('features.whatsapp')->label('Plantillas WhatsApp'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->sortable(),
                Tables\Columns\TextColumn::make('price_monthly')->label('Mensual')->money('MXN'),
                Tables\Columns\TextColumn::make('price_yearly')->label('Anual')->money('MXN'),
                Tables\Columns\TextColumn::make('max_customers')->label('Clientes')->placeholder('Ilimitado'),
                Tables\Columns\IconColumn::make('is_active')->label('Activo')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('Orden'),
            ])
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
