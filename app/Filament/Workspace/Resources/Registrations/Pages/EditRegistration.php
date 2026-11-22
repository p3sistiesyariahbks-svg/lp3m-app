<?php

namespace App\Filament\Workspace\Resources\Registrations\Pages;

use App\Enums\RegistrationStatus;
use App\Filament\Workspace\Resources\Registrations\RegistrationResource;
use Filament\Resources\Pages\EditRecord;

class EditRegistration extends EditRecord
{
    protected static string $resource = RegistrationResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            ...$data,
            'status' => RegistrationStatus::Submitted,
            'submitted_at' => now(),
            'note' => null,
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
