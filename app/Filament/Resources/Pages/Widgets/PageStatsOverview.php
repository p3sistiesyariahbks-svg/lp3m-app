<?php

namespace App\Filament\Resources\Pages\Widgets;

use App\Enums\StatusType;
use App\Filament\Resources\Pages\Pages\ListPages;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PageStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListPages::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Pages',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedDocument)
                ->description('All pages'),
            Stat::make(
                'Published',
                $this->getPageTableQuery()
                    ->where('status', StatusType::Published)
                    ->count()
            )
                ->icon(Heroicon::OutlinedEye)
                ->description('Live on site'),
            Stat::make(
                'Drafts',
                $this->getPageTableQuery()
                    ->where('status', StatusType::Draft)
                    ->count()
            )
                ->icon(Heroicon::OutlinedPencilSquare)
                ->description('Not yet published'),
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
