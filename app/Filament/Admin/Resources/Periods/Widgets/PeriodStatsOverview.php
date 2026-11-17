<?php

namespace App\Filament\Admin\Resources\Periods\Widgets;

use App\Enums\StatusType;
use App\Filament\Admin\Resources\Periods\Pages\ListPeriods;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PeriodStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListPeriods::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Periods',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedCalendar)
                ->description('All periods'),
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
