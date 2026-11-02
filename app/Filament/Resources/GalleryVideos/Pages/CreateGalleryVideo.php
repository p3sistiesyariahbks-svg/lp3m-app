<?php

namespace App\Filament\Resources\GalleryVideos\Pages;

use App\Enums\GalleryType;
use App\Filament\Resources\GalleryVideos\GalleryVideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryVideo extends CreateRecord
{
    protected static string $resource = GalleryVideoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = GalleryType::Videos;

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
