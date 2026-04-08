<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si el usuario está cambiando su password, limpia el flag
        if (! empty($data['password'])) {
            $data['must_change_password'] = false;
        }
        return $data;
    }
}
