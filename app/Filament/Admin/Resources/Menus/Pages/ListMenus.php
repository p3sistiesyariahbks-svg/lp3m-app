<?php

namespace App\Filament\Admin\Resources\Menus\Pages;

use App\Filament\Admin\Resources\Menus\MenuResource;
use Datlechin\FilamentMenuBuilder\Resources\MenuResource\Pages\ListMenus as BaseListMenus;
use Filament\Actions\CreateAction;

class ListMenus extends BaseListMenus
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getLocationAction(),
            CreateAction::make(),
        ];
    }
}
