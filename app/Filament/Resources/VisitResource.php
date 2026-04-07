<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitResource\Pages;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Service;
use App\Models\Tenant\Visit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Visitas';
    protected static ?string $modelLabel = 'Visita';
    protected static ?string $pluralModelLabel = 'Visitas';
    protected static ?string $navigationGroup = 'Operación';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Cliente')->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Cliente')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(fn (string $search) => Customer::query()
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->limit(30)
                        ->get()
                        ->mapWithKeys(fn ($c) => [$c->id => "{$c->name} — {$c->phone}"]))
                    ->getOptionLabelUsing(fn ($value) => optional(Customer::find($value))->name),
            ]),

            Forms\Components\Section::make('Servicios')->schema([
                Forms\Components\Repeater::make('items')
                    ->label('Servicios')
                    ->schema([
                        Forms\Components\Select::make('service_id')
                            ->label('Servicio')
                            ->required()
                            ->options(fn () => Service::where('is_active', true)->pluck('name', 'id'))
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $svc = Service::find($state);
                                if ($svc) {
                                    $set('unit_price', $svc->price);
                                }
                            }),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cant.')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->live(onBlur: true),
                        Forms\Components\TextInput::make('unit_price')
                            ->label('Precio')
                            ->prefix('$')
                            ->numeric()
                            ->required()
                            ->live(onBlur: true),
                    ])
                    ->columns(3)
                    ->minItems(1)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcTotal($get, $set)),
            ]),

            Forms\Components\Section::make('Cobro')->columns(2)->schema([
                Forms\Components\TextInput::make('discount')
                    ->label('Descuento')
                    ->prefix('$')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Get $get, Set $set) => self::recalcTotal($get, $set)),
                Forms\Components\Select::make('payment_method')
                    ->label('Método de pago')
                    ->required()
                    ->default('cash')
                    ->options([
                        'cash' => 'Efectivo',
                        'card' => 'Tarjeta',
                        'transfer' => 'Transferencia',
                        'vip' => 'Suscripción VIP',
                        'package' => 'Paquete prepago',
                    ]),
                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->prefix('$')
                    ->numeric()
                    ->readOnly()
                    ->dehydrated()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->rows(2)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    protected static function recalcTotal(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) ($item['unit_price'] ?? 0) * (int) ($item['quantity'] ?? 1);
        }
        $discount = (float) ($get('discount') ?? 0);
        $set('total', max(0, $subtotal - $discount));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visited_at')->label('Fecha')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Cliente')->searchable(),
                Tables\Columns\TextColumn::make('total')->label('Total')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('payment_method')->label('Pago')->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'cash' => 'Efectivo',
                        'card' => 'Tarjeta',
                        'transfer' => 'Transfer',
                        'vip' => 'VIP',
                        'package' => 'Paquete',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('earned_stamps')->label('Sellos')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('servedBy.name')->label('Atendió'),
            ])
            ->filters([
                Tables\Filters\Filter::make('today')
                    ->label('Hoy')
                    ->query(fn ($q) => $q->whereDate('visited_at', today())),
            ])
            ->defaultSort('visited_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisits::route('/'),
            'create' => Pages\CreateVisit::route('/create'),
        ];
    }
}
