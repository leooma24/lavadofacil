<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Tenant\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Servicios';
    protected static ?string $modelLabel = 'Servicio';
    protected static ?string $pluralModelLabel = 'Servicios';
    protected static ?string $navigationGroup = 'Operación';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos del servicio')->columns(2)->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del servicio o paquete')
                    ->placeholder('Ej: Paquete VI — Detallado Master Total')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->minValue(0),
                Forms\Components\TextInput::make('duration_min')
                    ->label('Duración (minutos)')
                    ->numeric()
                    ->default(15)
                    ->minValue(1),

                Forms\Components\TextInput::make('stamps_earned')
                    ->label('Sellos otorgados')
                    ->helperText('Sugerencia: $0-300 = 1 sello · $300-600 = 2 · $600-1200 = 3 · $1200-2000 = 4 · $2000-3000 = 6 · $3000+ = 8 (llena tarjeta)')
                    ->numeric()
                    ->default(1)
                    ->minValue(0),
                Forms\Components\TextInput::make('points_earned')
                    ->label('Puntos otorgados')
                    ->numeric()
                    ->default(10)
                    ->minValue(0),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Orden de aparición')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]),

            Forms\Components\Section::make('Imagen y descripción')->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('Imagen / flyer del paquete')
                    ->image()
                    ->imageEditor()
                    ->directory('services')
                    ->maxSize(4096)
                    ->helperText('JPG/PNG hasta 4 MB. Aparece en la PWA del cliente.'),

                Forms\Components\MarkdownEditor::make('description')
                    ->label('Descripción detallada')
                    ->helperText('Puedes usar listas, negritas, etc. Aparece en la PWA del cliente cuando vea el catálogo.')
                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'heading', 'link'])
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Servicio')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Precio')->money('MXN')->sortable(),
                Tables\Columns\TextColumn::make('duration_min')->label('Min')->suffix(' min'),
                Tables\Columns\TextColumn::make('stamps_earned')->label('Sellos')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('points_earned')->label('Puntos')->badge()->color('info'),
                Tables\Columns\IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Activo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
