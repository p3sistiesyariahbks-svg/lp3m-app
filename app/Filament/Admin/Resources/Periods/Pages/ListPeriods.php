<?php

namespace App\Filament\Admin\Resources\Periods\Pages;

use App\Filament\Admin\Resources\Periods\PeriodResource;
use App\Filament\Admin\Resources\Periods\Widgets\PeriodStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListPeriods extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = PeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PeriodStatsOverview::class,
        ];
    }
}
