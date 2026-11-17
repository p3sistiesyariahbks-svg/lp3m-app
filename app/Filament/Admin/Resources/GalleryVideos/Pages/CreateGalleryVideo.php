<?php

namespace App\Filament\Admin\Resources\GalleryVideos\Pages;

use App\Enums\GalleryType;
use App\Enums\StatusType;
use App\Filament\Admin\Resources\GalleryVideos\GalleryVideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryVideo extends CreateRecord
{
    protected static string $resource = GalleryVideoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = GalleryType::Videos;

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
