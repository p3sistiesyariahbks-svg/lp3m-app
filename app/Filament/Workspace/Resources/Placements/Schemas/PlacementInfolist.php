<?php

namespace App\Filament\Workspace\Resources\Placements\Schemas;

use App\Models\Placement;
use Carbon\CarbonInterface;
use Devletes\FilamentProgressBar\Infolists\Components\ProgressBarEntry;
use EduardoRibeiroDev\FilamentLeaflet\Enums\TileLayer;
use EduardoRibeiroDev\FilamentLeaflet\Infolists\MapEntry;
use EduardoRibeiroDev\FilamentLeaflet\ValueObjects\Coordinate;
use Filament\Forms\Components\RichEditor\RichContentAttribute;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Kisame76\FilamentAdvancedRichEditor\Infolists\Components\AdvancedRichEntry;

class PlacementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make('Location Information')
                                ->schema([
                                    TextEntry::make('location.name')
                                        ->label('Name'),
                                    TextEntry::make('location.level')
                                        ->label('Level')
                                        ->badge(),
                                    TextEntry::make('location.address')
                                        ->label('Address'),
                                    TextEntry::make('supervisor.name')
                                        ->label('Supervisor'),
                                ])
                                ->columns(2),
                            Section::make('Description')
                                ->schema([
                                    AdvancedRichEntry::make('location.content')
                                        ->hiddenLabel()
                                        ->state(
                                            fn (Placement $record): ?RichContentAttribute => $record->location
                                                ->getRichContentAttribute('content')
                                        ),
                                ]),
                            Section::make('Location Map')
                                ->schema([
                                    MapEntry::make('location_map')
                                        ->hiddenLabel()
                                        ->height(400)
                                        ->zoom(13)
                                        ->autoCenter()
                                        ->tileLayersUrl(TileLayer::OpenStreetMap)
                                        ->static(false)
                                        ->recenterTimeout(null)
                                        ->state(
                                            fn (Placement $record): ?Coordinate => $record->location->coordinates
                                        ),
                                ]),
                        ])
                            ->columnSpan(2),
                        Group::make([
                            Section::make('Quota')
                                ->schema([
                                    ProgressBarEntry::make('filled')
                                        ->hiddenLabel()
                                        ->state(fn (Placement $record) => [
                                            'current' => $record->filled,
                                            'total' => $record->quota,
                                        ])
                                        ->hideProgressValue()
                                        ->hidePercentage()
                                        ->height('12px')
                                        ->textPosition('inside')
                                        ->thresholdDirection('ascending')
                                        ->warningThreshold(50)
                                        ->dangerThreshold(100)
                                        ->successLabel(
                                            fn (float $current, ?float $total): string => "{$current} / {$total} Filled",
                                        )
                                        ->warningLabel(
                                            fn (float $current, ?float $total): string => "{$current} / {$total} Filled",
                                        )
                                        ->dangerLabel('Quota Full')
                                        ->helperText('Places are reserved on selection and released on cancellation or payment expiry.'),
                                ])
                                ->dense(),
                            Section::make('Placement Information')
                                ->schema([
                                    TextEntry::make('period.name')
                                        ->label('Period'),
                                    TextEntry::make('period.registration_opens_at')
                                        ->label('Registration Date')
                                        ->formatStateUsing(
                                            fn (CarbonInterface $state, Placement $record): string => "{$state->toDateString()} – {$record->period->registration_closes_at->toDateString()}"
                                        ),
                                    TextEntry::make('period.program_starts_at')
                                        ->label('Program Date')
                                        ->formatStateUsing(
                                            fn (CarbonInterface $state, Placement $record): string => "{$state->toDateString()} – {$record->period->program_ends_at->toDateString()}"
                                        ),
                                    TextEntry::make('period.fee')
                                        ->label('Fee')
                                        ->money('IDR', locale: 'id', decimalPlaces: 0)
                                        ->default(0)
                                        ->weight(FontWeight::Bold),
                                ]),
                        ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
