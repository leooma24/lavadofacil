<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Tenant\Appointment;
use App\Models\Tenant\Customer;
use App\Models\Tenant\MessageTemplate;
use App\Models\Tenant\Service;
use App\Models\Tenant\WhatsappMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Citas';
    protected static ?string $modelLabel = 'Cita';
    protected static ?string $pluralModelLabel = 'Citas';
    protected static ?string $navigationGroup = 'Operación';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = Appointment::whereIn('status', ['pending', 'confirmed', 'in_queue', 'in_progress'])->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Cita')->columns(2)->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Cliente')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $s) => Customer::where('name', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%")->limit(20)
                        ->get()->mapWithKeys(fn ($c) => [$c->id => "{$c->name} — {$c->phone}"]))
                    ->getOptionLabelUsing(fn ($v) => Customer::find($v)?->name),
                Forms\Components\Select::make('service_id')
                    ->label('Servicio / paquete')
                    ->options(fn () => Service::where('is_active', true)->orderBy('sort_order')->pluck('name', 'id')),
                Forms\Components\Select::make('type')
                    ->label('Modalidad')
                    ->required()
                    ->default('in_shop')
                    ->live()
                    ->options([
                        'in_shop' => '🏪 En el lavado',
                        'at_home' => '🏠 A domicilio',
                    ]),
                Forms\Components\TextInput::make('address')
                    ->label('Dirección (domicilio)')
                    ->placeholder('Calle, número, colonia')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'at_home')
                    ->required(fn (Forms\Get $get) => $get('type') === 'at_home')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('Fecha y hora')
                    ->required()
                    ->default(now()->addHour()),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->required()
                    ->default('pending')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'in_queue' => 'En cola',
                        'in_progress' => 'En proceso',
                        'ready' => 'Listo para traer',
                        'completed' => 'Terminada',
                        'cancelled' => 'Cancelada',
                    ]),
                Forms\Components\Textarea::make('notes')->label('Notas')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('queue_position')
                    ->label('#')
                    ->badge()
                    ->color('warning')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('scheduled_at')->label('Fecha')->dateTime('d/m H:i')->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Cliente')->searchable(),
                Tables\Columns\TextColumn::make('service.name')->label('Servicio')->limit(30),
                Tables\Columns\TextColumn::make('type')
                    ->label('Modalidad')
                    ->formatStateUsing(fn ($state) => $state === 'at_home' ? '🏠 Domicilio' : '🏪 Lavado')
                    ->badge(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'gray' => 'pending',
                        'info' => 'confirmed',
                        'warning' => ['in_queue', 'in_progress'],
                        'success' => ['ready', 'completed'],
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (Appointment $r) => $r->statusLabel()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pendiente', 'confirmed' => 'Confirmada',
                    'in_queue' => 'En cola', 'in_progress' => 'En proceso',
                    'ready' => 'Listo', 'completed' => 'Terminada', 'cancelled' => 'Cancelada',
                ]),
                Tables\Filters\SelectFilter::make('type')->options([
                    'in_shop' => 'En el lavado', 'at_home' => 'A domicilio',
                ]),
                Tables\Filters\Filter::make('today')->label('Hoy')
                    ->query(fn ($q) => $q->whereDate('scheduled_at', today())),
            ])
            ->actions([
                Action::make('confirm')
                    ->label('Confirmar')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(fn (Appointment $r) => $r->status === 'pending')
                    ->action(fn (Appointment $r) => $r->update(['status' => 'confirmed'])),

                Action::make('queue')
                    ->label('Poner en cola')
                    ->icon('heroicon-o-queue-list')
                    ->color('warning')
                    ->visible(fn (Appointment $r) => in_array($r->status, ['confirmed', 'pending']))
                    ->action(function (Appointment $r) {
                        $next = (Appointment::whereIn('status', ['in_queue', 'in_progress'])
                            ->max('queue_position') ?? 0) + 1;
                        $r->update(['status' => 'in_queue', 'queue_position' => $next]);
                    }),

                Action::make('start')
                    ->label('Iniciar')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->visible(fn (Appointment $r) => $r->status === 'in_queue')
                    ->action(fn (Appointment $r) => $r->update(['status' => 'in_progress'])),

                Action::make('ready')
                    ->label('Avisar "ya puede traerlo"')
                    ->icon('heroicon-o-bell-alert')
                    ->color('success')
                    ->visible(fn (Appointment $r) => in_array($r->status, ['confirmed', 'in_queue']) && $r->type === 'in_shop')
                    ->requiresConfirmation()
                    ->modalDescription('Se encolará un mensaje de WhatsApp en la bandeja para avisar al cliente que ya puede traer su auto.')
                    ->action(function (Appointment $r) {
                        $template = MessageTemplate::where('channel', 'whatsapp')->where('type', 'appointment_ready')->first();
                        $body = $template
                            ? $template->render($r->customer)
                            : "Hola {$r->customer->name}, ya puedes traer tu auto al lavado 🚗 Te esperamos.";

                        WhatsappMessage::create([
                            'customer_id' => $r->customer_id,
                            'sent_by_user_id' => Auth::id() ?? 1,
                            'type' => 'appointment_ready',
                            'phone' => $r->customer->phone,
                            'body' => $body,
                            'sent_at' => null,
                            'notes' => "Cita #{$r->id} lista",
                        ]);

                        $r->update(['ready_notified_at' => now()]);

                        Notification::make()->title('Mensaje encolado en la bandeja')->success()->send();
                    }),

                Action::make('complete')
                    ->label('Terminar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Appointment $r) => $r->status === 'in_progress')
                    ->action(fn (Appointment $r) => $r->update(['status' => 'completed', 'queue_position' => null])),

                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('scheduled_at', 'asc')
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
