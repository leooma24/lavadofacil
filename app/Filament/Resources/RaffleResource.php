<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RaffleResource\Pages;
use App\Models\Tenant\Raffle;
use App\Models\Tenant\RaffleTicket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class RaffleResource extends Resource
{
    protected static ?string $model = Raffle::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'Rifas';
    protected static ?string $modelLabel = 'Rifa';
    protected static ?string $pluralModelLabel = 'Rifas';
    protected static ?string $navigationGroup = 'Fidelización';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('raffle');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos de la rifa')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Nombre')->required(),
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
                Forms\Components\DatePicker::make('draw_date')->label('Fecha del sorteo')->required(),
                Forms\Components\TextInput::make('tickets_required')
                    ->label('Tickets por visita')
                    ->numeric()->default(1)->minValue(1),
                Forms\Components\TextInput::make('max_tickets_per_customer')
                    ->label('Máx tickets por cliente')
                    ->helperText('Vacío = sin tope')
                    ->numeric(),
                Forms\Components\Textarea::make('prize_description')
                    ->label('Premio')->required()->rows(2)->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Rifa')->searchable(),
                Tables\Columns\TextColumn::make('month')
                    ->label('Periodo')
                    ->formatStateUsing(fn ($state, Raffle $r) => sprintf('%02d/%d', $state, $r->year)),
                Tables\Columns\TextColumn::make('prize_description')->label('Premio')->limit(40),
                Tables\Columns\TextColumn::make('tickets_count')
                    ->label('Tickets')
                    ->counts('tickets')
                    ->badge(),
                Tables\Columns\TextColumn::make('draw_date')->label('Sorteo')->date('d/m/Y'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'drawn',
                        'gray' => 'closed',
                    ])
                    ->formatStateUsing(fn ($s) => match ($s) {
                        'active' => 'Activa',
                        'drawn' => 'Sorteada',
                        'closed' => 'Cerrada',
                    }),
                Tables\Columns\TextColumn::make('winner.name')->label('Ganador'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('draw')
                    ->label('Sortear')
                    ->icon('heroicon-o-sparkles')
                    ->color('warning')
                    ->visible(fn (Raffle $r) => $r->status === 'active')
                    ->requiresConfirmation()
                    ->modalHeading('Sortear ganador')
                    ->modalDescription('Se elegirá al azar uno de los tickets emitidos. Esta acción no se puede deshacer.')
                    ->action(function (Raffle $raffle) {
                        $ticket = RaffleTicket::where('raffle_id', $raffle->id)
                            ->inRandomOrder()
                            ->first();

                        if (! $ticket) {
                            Notification::make()
                                ->title('No hay tickets')
                                ->body('Esta rifa no tiene tickets emitidos.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $raffle->update([
                            'status' => 'drawn',
                            'winner_customer_id' => $ticket->customer_id,
                            'winning_ticket_number' => $ticket->ticket_number,
                        ]);

                        Notification::make()
                            ->title('¡Ganador sorteado!')
                            ->body("Ticket #{$ticket->ticket_number} — {$ticket->customer->name}")
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('year', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRaffles::route('/'),
            'create' => Pages\CreateRaffle::route('/create'),
            'edit' => Pages\EditRaffle::route('/{record}/edit'),
        ];
    }
}
