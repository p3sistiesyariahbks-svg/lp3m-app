<?php

namespace App\Filament\Resources\GalleryVideos\Pages;

use App\Filament\Resources\GalleryVideos\GalleryVideoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGalleryVideo extends EditRecord
{
    protected static string $resource = GalleryVideoResource::class;

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
