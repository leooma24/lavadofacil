<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VipSubscriptionResource\Pages;
use App\Models\Tenant\Customer;
use App\Models\Tenant\VipSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VipSubscriptionResource extends Resource
{
    protected static ?string $model = VipSubscription::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Suscripciones VIP';
    protected static ?string $modelLabel = 'Suscripción VIP';
    protected static ?string $pluralModelLabel = 'Suscripciones VIP';
    protected static ?string $navigationGroup = 'Monetización';
    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('vip');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Suscripción VIP')->columns(2)->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Cliente')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $s) => Customer::where('name', 'like', "%{$s}%")
                        ->limit(20)->pluck('name', 'id'))
                    ->getOptionLabelUsing(fn ($v) => Customer::find($v)?->name),
                Forms\Components\TextInput::make('plan_name')->label('Plan')->required()->default('VIP Mensual'),
                Forms\Components\TextInput::make('monthly_price')->label('Precio mensual')->prefix('$')->numeric()->required(),
                Forms\Components\TextInput::make('washes_included')
                    ->label('Lavados incluidos')
                    ->numeric()
                    ->default(0)
                    ->helperText('0 = ilimitados'),
                Forms\Components\DateTimePicker::make('starts_at')->label('Inicia')->default(now())->required(),
                Forms\Components\DateTimePicker::make('ends_at')->label('Termina')->default(fn () => now()->addMonth())->required(),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->required()
                    ->default('active')
                    ->options([
                        'active' => 'Activa',
                        'cancelled' => 'Cancelada',
                        'expired' => 'Expirada',
                    ]),
                Forms\Components\Toggle::make('auto_renew')->label('Renovación automática')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')->label('Cliente')->searchable(),
                Tables\Columns\TextColumn::make('plan_name')->label('Plan'),
                Tables\Columns\TextColumn::make('monthly_price')->label('Precio')->money('MXN'),
                Tables\Columns\TextColumn::make('washes_used')
                    ->label('Lavados')
                    ->formatStateUsing(fn ($state, VipSubscription $record) => $record->washes_included
                        ? "{$s} / {$record->washes_included}"
                        : "{$s} / ∞"),
                Tables\Columns\TextColumn::make('ends_at')->label('Vence')->date('d/m/Y')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'cancelled',
                        'gray' => 'expired',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'active' => 'Activa',
                        'cancelled' => 'Cancelada',
                        'expired' => 'Expirada',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'active' => 'Activa', 'cancelled' => 'Cancelada', 'expired' => 'Expirada',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('ends_at');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVipSubscriptions::route('/'),
            'create' => Pages\CreateVipSubscription::route('/create'),
            'edit' => Pages\EditVipSubscription::route('/{record}/edit'),
        ];
    }
}
