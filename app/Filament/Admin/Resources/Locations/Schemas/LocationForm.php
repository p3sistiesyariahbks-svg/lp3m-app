<?php

namespace App\Filament\Admin\Resources\Locations\Schemas;

use App\Enums\RegionLevel;
use EduardoRibeiroDev\FilamentLeaflet\Enums\GeoSearchProvider;
use EduardoRibeiroDev\FilamentLeaflet\Enums\TileLayer;
use EduardoRibeiroDev\FilamentLeaflet\Fields\GeoSearchInput;
use EduardoRibeiroDev\FilamentLeaflet\Fields\MapPicker;
use EduardoRibeiroDev\FilamentLeaflet\ValueObjects\GeoSearchResult;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Components\Attributes\Exposed;
use Filament\Support\Enums\Alignment;
use Kisame76\FilamentAdvancedRichEditor\Forms\Components\AdvancedRichEditor;
use Livewire\Attributes\Renderless;

class LocationForm
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
                                    TextInput::make('name')
                                        ->required()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Name')
                                        ->autofocus(),
                                    Radio::make('level')
                                        ->required()
                                        ->string()
                                        ->enum(RegionLevel::class)
                                        ->options(RegionLevel::class)
                                        ->default(RegionLevel::Regional)
                                        ->inline(),
                                    AdvancedRichEditor::make('content')
                                        ->required()
                                        ->minLength(3)
                                        ->maxLength(65535)
                                        ->toolbarAlignment(Alignment::Left)
                                        ->spatieMediaLibrary()
                                        ->mediaLibrary(false)
                                        ->headingLevels([1, 2, 3, 4, 5, 6])
                                        ->fileAttachmentsMaxSize(2048)
                                        ->resizableImages()
                                        ->helperText('Use the toolbar to format text, add headings, lists, links, and images.'),
                                ]),
                            Section::make('Address')
                                ->schema([
                                    GeoSearchInput::make('location_search')
                                        ->label('Search Location')
                                        ->provider(GeoSearchProvider::Nominatim)
                                        ->limit(10)
                                        ->withAddressDetails()
                                        ->language('id')
                                        ->cacheResults(0)
                                        ->minSearchLength(3)
                                        ->textMode(false)
                                        ->dehydrated(false)
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, GeoSearchResult $state): void {
                                            $set('coordinates', $state->coordinate);
                                            $set('lat', $state->coordinate->lat);
                                            $set('lng', $state->coordinate->lng);
                                            $set('address', $state->displayName);
                                        }),
                                    MapPicker::make('coordinates')
                                        ->label('Map')
                                        ->height(300)
                                        ->zoom(13)
                                        ->autoCenter()
                                        ->tileLayersUrl(TileLayer::OpenStreetMap)
                                        ->static(false)
                                        ->followMarker()
                                        ->dehydrated(false),
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('lat')
                                                ->required()
                                                ->numeric()
                                                ->minValue(-90)
                                                ->maxValue(90)
                                                ->label('Latitude')
                                                ->readOnly(),
                                            TextInput::make('lng')
                                                ->required()
                                                ->numeric()
                                                ->minValue(-180)
                                                ->maxValue(180)
                                                ->label('Longitude')
                                                ->readOnly(),
                                        ]),
                                    Textarea::make('address')
                                        ->nullable()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(1024)
                                        ->label('Full Address')
                                        ->autosize(),
                                ])
                                ->collapsed(),
                        ])
                            ->columnSpan(2),
                        Group::make([
                            Section::make('Status')
                                ->schema([
                                    Toggle::make('is_active')
                                        ->required()
                                        ->label('Visibility')
                                        ->helperText('Controls whether this product appears in sales channels.')
                                        ->inline(),
                                ]),
                        ]),
                    ]),
            ]);
    }
}
