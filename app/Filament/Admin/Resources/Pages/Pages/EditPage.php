<?php

namespace App\Filament\Admin\Resources\Pages\Pages;

use App\Enums\StatusType;
use App\Filament\Admin\Resources\Pages\PageResource;
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
        $data['published_date'] = $this->record->published_at?->toDateString();
        $data['published_time'] = $this->record->published_at?->format('H:i');
        $data['seo_title'] = $this->record->seoMeta?->title;
        $data['meta_description'] = $this->record->seoMeta?->description;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['status'] === StatusType::Scheduled) {
            $data['published_at'] = "{$this->data['published_date']} {$this->data['published_time']}";
        }

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
