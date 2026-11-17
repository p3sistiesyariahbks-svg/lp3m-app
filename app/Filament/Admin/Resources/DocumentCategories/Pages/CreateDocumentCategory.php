<?php

namespace App\Filament\Admin\Resources\DocumentCategories\Pages;

use App\Enums\TaxonomyType;
use App\Filament\Admin\Resources\DocumentCategories\DocumentCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentCategory extends CreateRecord
{
    protected static string $resource = DocumentCategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = TaxonomyType::Documents;

        return $data;
    }
}
