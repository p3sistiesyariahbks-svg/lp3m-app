<?php

namespace App\Filament\Admin\Resources\GalleryPhotos\Pages;

use App\Filament\Admin\Resources\GalleryPhotos\GalleryPhotoResource;
use App\Filament\Admin\Resources\GalleryPhotos\Widgets\GalleryPhotoStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListGalleryPhotos extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = GalleryPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GalleryPhotoStatsOverview::class,
        ];
    }
}
