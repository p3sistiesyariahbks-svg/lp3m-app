<?php

namespace App\Filament\Resources\GalleryPhotos\Widgets;

use App\Enums\StatusType;
use App\Filament\Resources\GalleryPhotos\Pages\ListGalleryPhotos;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GalleryPhotoStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListGalleryPhotos::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Photos',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedPhoto)
                ->description('All photos'),
            Stat::make(
                'Drafts',
                $this->getPageTableQuery()
                    ->where('status', StatusType::Draft)
                    ->count()
            )
                ->icon(Heroicon::OutlinedPencilSquare)
                ->description('Not yet published'),
            Stat::make(
                'Published',
                $this->getPageTableQuery()
                    ->where('status', StatusType::Published)
                    ->count()
            )
                ->icon(Heroicon::OutlinedEye)
                ->description('Live on site'),
            Stat::make(
                'Scheduled',
                $this->getPageTableQuery()
                    ->where('status', StatusType::Scheduled)
                    ->count()
            )
                ->icon(Heroicon::OutlinedCalendar)
                ->description('Awaiting publish date'),
        ];
    }
}
