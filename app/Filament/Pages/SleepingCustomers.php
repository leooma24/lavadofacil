<?php

namespace App\Filament\Pages;

use App\Models\Tenant\Customer;
use App\Models\Tenant\MessageTemplate;
use App\Services\WhatsAppLinkBuilder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class SleepingCustomers extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-moon';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Clientes dormidos';
    protected static ?string $title = 'Clientes dormidos';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.sleeping-customers';

    public int $days = 30;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('reactivation');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => Customer::query()
                ->where(function ($q) {
                    $q->whereNull('last_visit_at')
                        ->orWhere('last_visit_at', '<=', now()->subDays($this->days));
                })
                ->where('whatsapp_opt_in', true))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Cliente')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono')->searchable(),
                Tables\Columns\TextColumn::make('total_visits')->label('Visitas')->sortable(),
                Tables\Columns\TextColumn::make('total_spent')->label('Gastado')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('last_visit_at')
                    ->label('Última visita')
                    ->date('d/m/Y')
                    ->placeholder('Nunca')
                    ->sortable(),
                Tables\Columns\TextColumn::make('days_since')
                    ->label('Días sin venir')
                    ->state(fn (Customer $r) => $r->last_visit_at
                        ? (int) $r->last_visit_at->diffInDays(now())
                        : '—')
                    ->badge()
                    ->color('danger'),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')
                    ->label('Reactivar')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(function (Customer $customer) {
                        $template = MessageTemplate::where('channel', 'whatsapp')
                            ->where('type', 'reactivation')
                            ->where('is_active', true)
                            ->first();

                        if ($template) {
                            return WhatsAppLinkBuilder::fromTemplate($template, $customer);
                        }

                        $msg = "Hola {$customer->name}, te extrañamos 🚗 ven a vernos pronto";
                        return WhatsAppLinkBuilder::quick($customer, $msg);
                    }, shouldOpenInNewTab: true),
            ])
            ->defaultSort('last_visit_at', 'asc')
            ->paginated([10, 25, 50]);
    }

    public function getDaysOptions(): array
    {
        return [
            15 => 'Hace más de 15 días',
            30 => 'Hace más de 30 días',
            60 => 'Hace más de 60 días',
            90 => 'Hace más de 90 días',
        ];
    }

    public function updatedDays(): void
    {
        $this->resetTable();
    }
}
