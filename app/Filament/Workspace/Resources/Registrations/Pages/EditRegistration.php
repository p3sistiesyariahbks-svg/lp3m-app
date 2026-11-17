<?php

namespace App\Filament\Workspace\Resources\Registrations\Pages;

use App\Filament\Workspace\Resources\Registrations\RegistrationResource;
use Filament\Resources\Pages\EditRecord;

class EditRegistration extends EditRecord
{
    protected static string $resource = RegistrationResource::class;

    protected function getFormActions(): array
    {
        return [];
    }
}
