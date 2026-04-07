<?php

namespace App\Filament\Pages;

use App\Models\Tenant\Customer;
use App\Models\Tenant\WhatsappMessage;
use App\Services\WhatsAppLinkBuilder;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class WhatsAppInbox extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Bandeja WhatsApp';
    protected static ?string $title = 'Bandeja de WhatsApp';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.whatsapp-inbox';

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('whatsapp');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = WhatsappMessage::whereNull('sent_at')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => WhatsappMessage::query()->whereNull('sent_at')->with('customer'))
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Motivo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'birthday' => '🎂 Cumpleaños',
                        'stamp_reminder' => '🎯 Faltan sellos',
                        'reactivation' => '😢 Reactivación',
                        'welcome' => '👋 Bienvenida',
                        'low_rating_alert' => '⚠️ Mala calificación',
                        'card_complete' => '🏆 Tarjeta lista',
                        'raffle_winner' => '🎁 Ganador rifa',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'low_rating_alert' => 'danger',
                        'birthday' => 'warning',
                        'stamp_reminder' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('customer.name')->label('Cliente')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono'),
                Tables\Columns\TextColumn::make('body')->label('Mensaje')->limit(80)->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('Encolado')->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')->options([
                    'birthday' => 'Cumpleaños',
                    'stamp_reminder' => 'Faltan sellos',
                    'reactivation' => 'Reactivación',
                    'low_rating_alert' => 'Mala calificación',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->label('Enviar')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->url(fn (WhatsappMessage $r) => WhatsAppLinkBuilder::build($r->phone, $r->body), shouldOpenInNewTab: true)
                    ->after(function (WhatsappMessage $r) {
                        $r->update(['sent_at' => now()]);
                        Notification::make()
                            ->title('Marcado como enviado')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('skip')
                    ->label('Descartar')
                    ->icon('heroicon-o-x-mark')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(fn (WhatsappMessage $r) => $r->delete()),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('mark_sent')
                    ->label('Marcar enviados')
                    ->icon('heroicon-o-check')
                    ->action(fn ($records) => $records->each->update(['sent_at' => now()])),
                Tables\Actions\DeleteBulkAction::make()->label('Descartar'),
            ])
            ->emptyStateHeading('Bandeja vacía 🎉')
            ->emptyStateDescription('No hay mensajes pendientes por enviar')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
