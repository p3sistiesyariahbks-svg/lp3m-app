<?php

namespace App\Filament\Resources\Documents\Pages;

use App\Filament\Resources\Documents\DocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function afterCreate(): void
    {
        $this->record->saveSEO([
            'title' => $this->data['seo_title'],
            'description' => $this->data['meta_description'],
        ]);
    }
}
