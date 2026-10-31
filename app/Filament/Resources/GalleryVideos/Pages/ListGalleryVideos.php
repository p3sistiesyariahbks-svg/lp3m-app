<?php

namespace App\Filament\Resources\GalleryVideos\Pages;

use App\Filament\Resources\GalleryVideos\GalleryVideoResource;
use App\Filament\Resources\GalleryVideos\Widgets\GalleryVideoStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListGalleryVideos extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = GalleryVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GalleryVideoStatsOverview::class,
        ];
    }
}
