<?php

namespace App\Filament\Admin\Resources\Partners\Pages;

use App\Enums\StatusType;
use App\Filament\Admin\Resources\Partners\PartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['published_date'] = $this->record->published_at?->toDateString();
        $data['published_time'] = $this->record->published_at?->format('H:i');

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] === StatusType::Scheduled) {
            $data['published_at'] = "{$this->data['published_date']} {$this->data['published_time']}";
        }

        return $data;
    }
}
