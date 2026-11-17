<?php

namespace App\Filament\Admin\Resources\Periods\Pages;

use App\Enums\StatusType;
use App\Filament\Admin\Resources\Periods\PeriodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePeriod extends CreateRecord
{
    protected static string $resource = PeriodResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['status'] === StatusType::Scheduled) {
            $data['published_at'] = "{$this->data['published_date']} {$this->data['published_time']}";
        }

        return $data;
    }
}
