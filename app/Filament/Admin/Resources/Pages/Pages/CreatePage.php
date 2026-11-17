<?php

namespace App\Filament\Admin\Resources\Pages\Pages;

use App\Enums\StatusType;
use App\Filament\Admin\Resources\Pages\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['status'] === StatusType::Scheduled) {
            $data['published_at'] = "{$this->data['published_date']} {$this->data['published_time']}";
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->saveSEO([
            'title' => $this->data['seo_title'],
            'description' => $this->data['meta_description'],
        ]);
    }
}
