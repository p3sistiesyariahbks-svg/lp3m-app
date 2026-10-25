<?php

namespace App\Filament\Resources\NewsCategories\Pages;

use App\Enums\TaxonomyType;
use App\Filament\Resources\NewsCategories\NewsCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsCategory extends CreateRecord
{
    protected static string $resource = NewsCategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = TaxonomyType::News;

        return $data;
    }
}
