<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageTemplateResource\Pages;
use App\Models\Tenant\MessageTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MessageTemplateResource extends Resource
{
    protected static ?string $model = MessageTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'Plantillas';
    protected static ?string $modelLabel = 'Plantilla';
    protected static ?string $pluralModelLabel = 'Plantillas';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Plantilla')->columns(2)->schema([
                Forms\Components\Select::make('channel')
                    ->label('Canal')
                    ->required()
                    ->options(['whatsapp' => 'WhatsApp', 'email' => 'Email']),
                Forms\Components\TextInput::make('type')
                    ->label('Tipo / clave')
                    ->required()
                    ->helperText('Ej: welcome, birthday, reactivation, card_complete'),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre visible')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('subject')
                    ->label('Asunto (solo email)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('body')
                    ->label('Cuerpo del mensaje')
                    ->required()
                    ->rows(8)
                    ->helperText('Variables disponibles: {nombre}, {telefono}, {nivel}, {visitas}, {puntos}, {racha}')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->label('Activa')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('channel')
                    ->label('Canal')
                    ->colors([
                        'success' => 'whatsapp',
                        'info' => 'email',
                    ]),
                Tables\Columns\TextColumn::make('type')->label('Tipo')->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable(),
                Tables\Columns\TextColumn::make('body')->label('Cuerpo')->limit(60)->wrap(),
                Tables\Columns\IconColumn::make('is_active')->label('Activa')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('channel')->options([
                    'whatsapp' => 'WhatsApp', 'email' => 'Email',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('channel');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessageTemplates::route('/'),
            'create' => Pages\CreateMessageTemplate::route('/create'),
            'edit' => Pages\EditMessageTemplate::route('/{record}/edit'),
        ];
    }
}
