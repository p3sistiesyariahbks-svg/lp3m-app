<?php

namespace App\Filament\Admin\Resources\Locations\Widgets;

use App\Filament\Admin\Resources\Locations\Pages\ListLocations;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LocationStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListLocations::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Locations',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedMapPin)
                ->description('All locations'),
            Stat::make(
                'Active',
                $this->getPageTableQuery()
                    ->where('is_active', true)
                    ->count()
            )
                ->icon(Heroicon::OutlinedCheckCircle)
                ->description('Active locations'),
            Stat::make(
                'Inactive',
                $this->getPageTableQuery()
                    ->where('is_active', false)
                    ->count()
            )
                ->icon(Heroicon::OutlinedXCircle)
                ->description('Inactive locations'),
        ];
    }
}
