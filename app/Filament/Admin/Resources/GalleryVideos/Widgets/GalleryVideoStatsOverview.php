<?php

namespace App\Filament\Admin\Resources\GalleryVideos\Widgets;

use App\Enums\StatusType;
use App\Filament\Admin\Resources\GalleryVideos\Pages\ListGalleryVideos;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GalleryVideoStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListGalleryVideos::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Videos',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedVideoCamera)
                ->description('All videos'),
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
