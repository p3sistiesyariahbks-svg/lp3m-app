<?php

namespace App\Filament\Resources\GalleryPhotos\Pages;

use App\Enums\GalleryType;
use App\Filament\Resources\GalleryPhotos\GalleryPhotoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryPhoto extends CreateRecord
{
    protected static string $resource = GalleryPhotoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = GalleryType::Photos;

        return $data;
    }
}
