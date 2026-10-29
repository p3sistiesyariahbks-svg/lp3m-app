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
}
