<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Tenant\Customer;
use App\Models\Tenant\MessageTemplate;
use App\Services\WhatsAppLinkBuilder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';
    protected static ?string $navigationGroup = 'Clientes';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del Cliente')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
                Forms\Components\TextInput::make('phone')->label('Teléfono')->tel()->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')->label('Email')->email(),
                Forms\Components\DatePicker::make('birthdate')->label('Fecha de nacimiento'),
                Forms\Components\Select::make('level')->label('Nivel')->options([
                    'bronze' => 'Bronce',
                    'silver' => 'Plata',
                    'gold' => 'Oro',
                    'platinum' => 'Platino',
                ])->default('bronze'),
                Forms\Components\Toggle::make('whatsapp_opt_in')->label('Acepta WhatsApp')->default(true),
                Forms\Components\Toggle::make('is_vip')->label('Es VIP'),
                Forms\Components\DateTimePicker::make('vip_until')->label('VIP hasta'),
                Forms\Components\Textarea::make('notes')->label('Notas')->columnSpanFull()->rows(3),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono')->searchable(),
                Tables\Columns\BadgeColumn::make('level')->label('Nivel')
                    ->colors([
                        'gray' => 'bronze',
                        'info' => 'silver',
                        'warning' => 'gold',
                        'success' => 'platinum',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'bronze' => '🥉 Bronce',
                        'silver' => '🥈 Plata',
                        'gold' => '🥇 Oro',
                        'platinum' => '💎 Platino',
                    }),
                Tables\Columns\TextColumn::make('total_visits')->label('Visitas')->sortable(),
                Tables\Columns\TextColumn::make('total_spent')->label('Gastado')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('current_streak')->label('Racha')->icon('heroicon-o-fire'),
                Tables\Columns\IconColumn::make('is_vip')->label('VIP')->boolean(),
                Tables\Columns\TextColumn::make('last_visit_at')->label('Última visita')->date('d/m/Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')->options([
                    'bronze' => 'Bronce',
                    'silver' => 'Plata',
                    'gold' => 'Oro',
                    'platinum' => 'Platino',
                ]),
                Tables\Filters\Filter::make('vip')->label('Solo VIP')->query(fn ($q) => $q->where('is_vip', true)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    self::makeWhatsAppAction('welcome', 'Bienvenida', 'heroicon-o-hand-raised'),
                    self::makeWhatsAppAction('reminder', 'Recordatorio', 'heroicon-o-bell'),
                    self::makeWhatsAppAction('birthday', 'Cumpleaños', 'heroicon-o-cake'),
                    self::makeWhatsAppAction('reactivation', 'Reactivación', 'heroicon-o-arrow-path'),
                    self::makeWhatsAppAction('card_complete', 'Tarjeta lista', 'heroicon-o-trophy'),
                    self::makeWhatsAppAction('raffle_winner', 'Ganador rifa', 'heroicon-o-gift'),
                ])
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->visible(fn (Customer $r) => ! empty($r->phone) && $r->whatsapp_opt_in)
                    ->button(),

                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    /**
     * Crea un Action que abre wa.me con el contenido de la plantilla del tipo dado.
     * Si la plantilla no existe, usa un fallback genérico.
     */
    protected static function makeWhatsAppAction(string $type, string $label, string $icon): Tables\Actions\Action
    {
        return Tables\Actions\Action::make("wa_{$type}")
            ->label($label)
            ->icon($icon)
            ->url(function (Customer $customer) use ($type) {
                $template = MessageTemplate::where('channel', 'whatsapp')
                    ->where('type', $type)
                    ->where('is_active', true)
                    ->first();

                if ($template) {
                    return WhatsAppLinkBuilder::fromTemplate($template, $customer);
                }

                $fallback = "Hola {$customer->name} 👋";
                return WhatsAppLinkBuilder::quick($customer, $fallback);
            }, shouldOpenInNewTab: true);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
