<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Resources\Pages\PageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['seo_title'] = $this->record->seoMeta?->title;
        $data['meta_description'] = $this->record->seoMeta?->description;

        return $data;
    }

    protected function afterSave(): void
    {
        $this->refreshFormData([
            'slug',
        ]);

        $this->record->saveSEO([
            'title' => $this->data['seo_title'],
            'description' => $this->data['meta_description'],
        ]);
    }
}
