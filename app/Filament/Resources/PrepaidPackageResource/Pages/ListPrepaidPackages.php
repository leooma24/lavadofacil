<?php

namespace App\Filament\Resources\PrepaidPackageResource\Pages;

use App\Filament\Resources\PrepaidPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrepaidPackages extends ListRecords
{
    protected static string $resource = PrepaidPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
