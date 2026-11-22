<?php

namespace App\Filament\Admin\Resources\Departments\Pages;

use App\Filament\Admin\Resources\Departments\DepartmentResource;
use App\Filament\Admin\Resources\Departments\Widgets\DepartmentStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DepartmentStatsOverview::class,
        ];
    }
}
