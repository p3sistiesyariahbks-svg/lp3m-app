<?php

namespace App\Filament\Admin\Resources\NewsCategories\Pages;

use App\Filament\Admin\Resources\NewsCategories\NewsCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Kisame76\FilamentTreeTable\Concerns\InteractsWithExpandableRows;
use Kisame76\FilamentTreeTable\Contracts\HasExpandableRows;

class ListNewsCategories extends ListRecords implements HasExpandableRows
{
    use InteractsWithExpandableRows;

    protected static string $resource = NewsCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
