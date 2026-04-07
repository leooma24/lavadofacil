<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Tenant\Survey;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Encuestas';
    protected static ?string $modelLabel = 'Encuesta';
    protected static ?string $pluralModelLabel = 'Encuestas';
    protected static ?string $navigationGroup = 'Engagement';
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Services\Features::enabled('surveys');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('answered_at')->label('Fecha')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->label('Cliente')->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('⭐')
                    ->formatStateUsing(fn (int $s) => str_repeat('⭐', $s))
                    ->sortable(),
                Tables\Columns\TextColumn::make('nps')
                    ->label('NPS')
                    ->badge()
                    ->color(fn ($state) => $state >= 9 ? 'success' : ($state >= 7 ? 'warning' : 'danger')),
                Tables\Columns\IconColumn::make('would_recommend')->label('Recomienda')->boolean(),
                Tables\Columns\TextColumn::make('comments')->label('Comentario')->limit(60)->wrap(),
            ])
            ->filters([
                Tables\Filters\Filter::make('low_rating')
                    ->label('Solo malos (≤2)')
                    ->query(fn ($q) => $q->where('rating', '<=', 2)),
            ])
            ->defaultSort('answered_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
        ];
    }
}
