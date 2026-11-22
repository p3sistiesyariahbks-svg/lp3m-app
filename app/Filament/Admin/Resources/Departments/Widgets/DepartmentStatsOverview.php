<?php

namespace App\Filament\Admin\Resources\Departments\Widgets;

use App\Filament\Admin\Resources\Departments\Pages\ListDepartments;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DepartmentStatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListDepartments::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Departments',
                $this->getPageTableQuery()
                    ->count()
            )
                ->icon(Heroicon::OutlinedBuildingLibrary)
                ->description('All departments'),
            Stat::make(
                'Active',
                $this->getPageTableQuery()
                    ->where('is_active', true)
                    ->count()
            )
                ->icon(Heroicon::OutlinedCheckCircle)
                ->description('Active departments'),
            Stat::make(
                'Inactive',
                $this->getPageTableQuery()
                    ->where('is_active', false)
                    ->count()
            )
                ->icon(Heroicon::OutlinedXCircle)
                ->description('Inactive departments'),
        ];
    }
}
