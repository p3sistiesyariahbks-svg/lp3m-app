<?php

namespace App\Filament\Workspace\Resources\Placements;

use App\Enums\StatusType;
use App\Filament\Workspace\Resources\Placements\Pages\ListPlacements;
use App\Filament\Workspace\Resources\Placements\Pages\ViewPlacement;
use App\Filament\Workspace\Resources\Placements\Schemas\PlacementInfolist;
use App\Filament\Workspace\Resources\Placements\Tables\PlacementsTable;
use App\Models\Placement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlacementResource extends Resource
{
    protected static ?string $model = Placement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $recordTitleAttribute = 'id';

    public static function infolist(Schema $schema): Schema
    {
        return PlacementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlacementsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['location', 'period', 'supervisor'])
            ->whereHas('period', fn (Builder $query): Builder => $query
                ->where('status', StatusType::Published)
                ->whereDate('registration_opens_at', '<=', today())
                ->whereDate('registration_closes_at', '>=', today()));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlacements::route('/'),
            'view' => ViewPlacement::route('/{record}'),
        ];
    }
}
