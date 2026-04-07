<?php

namespace App\Filament\Resources\LevelConfigResource\Pages;

use App\Filament\Resources\LevelConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLevelConfigs extends ListRecords
{
    protected static string $resource = LevelConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
