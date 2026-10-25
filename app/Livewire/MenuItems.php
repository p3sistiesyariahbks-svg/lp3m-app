<?php

namespace App\Livewire;

use Datlechin\FilamentMenuBuilder\Livewire\MenuItems as BaseMenuItems;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;

class MenuItems extends BaseMenuItems
{
    public function editAction(): Action
    {
        return parent::editAction()
            ->slideOver(false)
            ->modalWidth(Width::Prose);
    }
}
