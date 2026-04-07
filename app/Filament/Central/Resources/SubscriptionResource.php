<?php

namespace App\Filament\Central\Resources;

use App\Filament\Central\Resources\SubscriptionResource\Pages;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Suscripciones';
    protected static ?string $modelLabel = 'Suscripción';
    protected static ?string $pluralModelLabel = 'Suscripciones';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Suscripción')->columns(2)->schema([
                Forms\Components\Select::make('tenant_id')
                    ->label('Tenant')
                    ->required()
                    ->searchable()
                    ->options(fn () => Tenant::pluck('name', 'id')),
                Forms\Components\Select::make('plan_id')
                    ->label('Plan')
                    ->required()
                    ->options(fn () => Plan::pluck('name', 'id')),
                Forms\Components\DateTimePicker::make('starts_at')->label('Inicia')->default(now())->required(),
                Forms\Components\DateTimePicker::make('ends_at')->label('Termina')->default(fn () => now()->addMonth())->required(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->required()
                    ->default('active')
                    ->options([
                        'active' => 'Activa',
                        'past_due' => 'Vencida',
                        'cancelled' => 'Cancelada',
                        'trial' => 'En prueba',
                    ]),
                Forms\Components\Select::make('payment_method')
                    ->label('Método de pago')
                    ->options([
                        'card' => 'Tarjeta',
                        'transfer' => 'Transferencia',
                        'cash' => 'Efectivo',
                        'stripe' => 'Stripe',
                    ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')->label('Tenant')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('plan.name')->label('Plan')->badge(),
                Tables\Columns\TextColumn::make('plan.price_monthly')->label('$/mes')->money('MXN'),
                Tables\Columns\TextColumn::make('starts_at')->label('Inicio')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('ends_at')->label('Vence')->date('d/m/Y')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'trial',
                        'danger' => ['past_due', 'cancelled'],
                    ])
                    ->formatStateUsing(fn ($s) => match ($s) {
                        'active' => 'Activa',
                        'past_due' => 'Vencida',
                        'cancelled' => 'Cancelada',
                        'trial' => 'En prueba',
                        default => $s,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Activa', 'past_due' => 'Vencida',
                    'cancelled' => 'Cancelada', 'trial' => 'En prueba',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('ends_at', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
