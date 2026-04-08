<?php

namespace App\Filament\Central\Resources;

use App\Filament\Central\Resources\TenantResource\Pages;
use App\Models\Plan;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Stancl\Tenancy\Database\Models\Domain;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Car Washes (Tenants)';
    protected static ?string $modelLabel = 'Car Wash';
    protected static ?string $pluralModelLabel = 'Car Washes';
    protected static ?string $navigationGroup = 'Plataforma';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del Car Wash')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nombre del negocio')->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug (subdominio)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('URL: ' . rtrim(config('app.url'), '/') . '/{slug}')
                    ->disabled(fn ($context) => $context === 'edit'),
                Forms\Components\TextInput::make('owner_name')->label('Nombre del dueño')->required(),
                Forms\Components\TextInput::make('owner_email')->label('Email del dueño')->email()->required(),
                Forms\Components\TextInput::make('owner_phone')->label('Teléfono')->tel()->required(),
                Forms\Components\Select::make('plan_id')
                    ->label('Plan')
                    ->options(Plan::where('is_active', true)->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('status')->label('Estado')
                    ->options([
                        'trial' => 'En prueba',
                        'active' => 'Activo',
                        'suspended' => 'Suspendido',
                        'cancelled' => 'Cancelado',
                    ])->default('trial')->required(),
                Forms\Components\DateTimePicker::make('trial_ends_at')->label('Fin del trial'),
                Forms\Components\ColorPicker::make('primary_color')->label('Color principal')->default('#0ea5e9'),
                Forms\Components\Select::make('timezone')->label('Zona horaria')
                    ->options([
                        'America/Mazatlan' => 'Mazatlán (UTC-7)',
                        'America/Mexico_City' => 'CDMX (UTC-6)',
                        'America/Tijuana' => 'Tijuana (UTC-8)',
                    ])->default('America/Mazatlan'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Negocio')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('URL')->searchable()
                    ->formatStateUsing(fn ($state) => rtrim(config('app.url'), '/') . '/' . $state)
                    ->copyable(),
                Tables\Columns\TextColumn::make('owner_name')->label('Dueño'),
                Tables\Columns\TextColumn::make('owner_phone')->label('Teléfono'),
                Tables\Columns\TextColumn::make('plan.name')->label('Plan')->badge(),
                Tables\Columns\BadgeColumn::make('status')->label('Estado')
                    ->colors([
                        'warning' => 'trial',
                        'success' => 'active',
                        'danger' => 'suspended',
                        'gray' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'trial' => 'En prueba',
                        'active' => 'Activo',
                        'suspended' => 'Suspendido',
                        'cancelled' => 'Cancelado',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'trial' => 'En prueba',
                    'active' => 'Activo',
                    'suspended' => 'Suspendido',
                    'cancelled' => 'Cancelado',
                ]),
                Tables\Filters\SelectFilter::make('plan_id')->label('Plan')
                    ->options(Plan::pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\Action::make('visit')
                    ->label('Visitar')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Tenant $record) => rtrim(config('app.url'), '/') . '/' . $record->slug, true),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
