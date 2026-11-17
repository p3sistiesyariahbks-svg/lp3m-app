<?php

namespace App\Filament\Workspace\Resources\Placements\Pages;

use App\Filament\Workspace\Resources\Placements\Actions\RegisterPlacementAction;
use App\Filament\Workspace\Resources\Placements\PlacementResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPlacement extends ViewRecord
{
    protected static string $resource = PlacementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RegisterPlacementAction::make(),
        ];
    }
}
