<?php

namespace App\Filament\Central\Resources;

use App\Filament\Central\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Facturas';
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Factura')->columns(2)->schema([
                Forms\Components\Select::make('tenant_id')
                    ->label('Tenant')
                    ->required()
                    ->searchable()
                    ->options(fn () => Tenant::pluck('name', 'id')),
                Forms\Components\Select::make('subscription_id')
                    ->label('Suscripción')
                    ->options(fn () => Subscription::with('tenant')->get()
                        ->mapWithKeys(fn ($s) => [$s->id => "#{$s->id} — {$s->tenant?->name}"])),
                Forms\Components\TextInput::make('invoice_number')->label('Folio')->required()->default(fn () => 'INV-'.now()->format('Ymd-His')),
                Forms\Components\TextInput::make('amount')->label('Monto')->prefix('$')->numeric()->required(),
                Forms\Components\TextInput::make('currency')->label('Moneda')->default('MXN')->maxLength(3),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->required()
                    ->default('pending')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagada',
                        'overdue' => 'Vencida',
                        'cancelled' => 'Cancelada',
                    ]),
                Forms\Components\DateTimePicker::make('paid_at')->label('Fecha de pago'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Folio')->searchable(),
                Tables\Columns\TextColumn::make('tenant.name')->label('Tenant')->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('Monto')->money('MXN')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'pending',
                        'danger' => ['overdue', 'cancelled'],
                    ])
                    ->formatStateUsing(fn ($s) => match ($s) {
                        'paid' => 'Pagada',
                        'pending' => 'Pendiente',
                        'overdue' => 'Vencida',
                        'cancelled' => 'Cancelada',
                        default => $s,
                    }),
                Tables\Columns\TextColumn::make('paid_at')->label('Pagada')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('created_at')->label('Creada')->date('d/m/Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'paid' => 'Pagada', 'pending' => 'Pendiente',
                    'overdue' => 'Vencida', 'cancelled' => 'Cancelada',
                ]),
            ])
            ->actions([
                Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (Invoice $r) {
                        $r->loadMissing(['tenant', 'subscription.plan']);
                        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $r])->setPaper('letter');
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "factura-{$r->invoice_number}.pdf",
                        );
                    }),

                Action::make('mark_paid')
                    ->label('Marcar pagada')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Invoice $r) => $r->status === 'pending' || $r->status === 'overdue')
                    ->requiresConfirmation()
                    ->action(function (Invoice $r) {
                        $r->update(['status' => 'paid', 'paid_at' => now()]);
                        Notification::make()->title('Factura marcada como pagada')->success()->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
