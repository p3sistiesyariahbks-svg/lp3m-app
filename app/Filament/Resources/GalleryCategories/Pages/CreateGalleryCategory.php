<?php

namespace App\Filament\Resources\GalleryCategories\Pages;

use App\Enums\TaxonomyType;
use App\Filament\Resources\GalleryCategories\GalleryCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryCategory extends CreateRecord
{
    protected static string $resource = GalleryCategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = TaxonomyType::Gallery;

        return $data;
    }
}
