<?php

namespace App\Filament\Resources\PrepaidPackageResource\Pages;

use App\Filament\Resources\PrepaidPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrepaidPackage extends EditRecord
{
    protected static string $resource = PrepaidPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
