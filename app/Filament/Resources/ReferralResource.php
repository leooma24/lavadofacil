<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferralResource\Pages;
use App\Models\Tenant\Referral;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Referidos';
    protected static ?string $modelLabel = 'Referido';
    protected static ?string $pluralModelLabel = 'Referidos';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('referrals');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('referrer.name')->label('Quién refirió')->searchable(),
                Tables\Columns\TextColumn::make('referred.name')->label('Cliente nuevo')->searchable(),
                Tables\Columns\TextColumn::make('referral_code')->label('Código')->copyable()->badge(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'gray' => 'pending',
                        'info' => 'converted',
                        'success' => 'rewarded',
                    ])
                    ->formatStateUsing(fn ($s) => match ($s) {
                        'pending' => 'Pendiente',
                        'converted' => 'Convertido',
                        'rewarded' => 'Recompensado',
                    }),
                Tables\Columns\TextColumn::make('converted_at')->label('Convertido')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('reward_description')->label('Recompensa')->limit(40),
            ])
            ->actions([
                Tables\Actions\Action::make('reward')
                    ->label('Marcar recompensado')
                    ->icon('heroicon-o-gift')
                    ->color('success')
                    ->visible(fn (Referral $r) => $r->status === 'converted')
                    ->action(function (Referral $r) {
                        $r->update(['status' => 'rewarded', 'rewarded_at' => now()]);
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferrals::route('/'),
        ];
    }
}
