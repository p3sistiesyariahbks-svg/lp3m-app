<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\SeoSettings;
use BackedEnum;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSeo extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'SEO';

    protected static string $settings = SeoSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make([
                    Section::make('SEO Defaults')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('homepage_title')
                                        ->nullable()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Homepage Title')
                                        ->placeholder('Welcome to My Site')
                                        ->belowContent('Title shown on the homepage'),
                                    TextInput::make('title_suffix')
                                        ->nullable()
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(100)
                                        ->label('Title Suffix')
                                        ->placeholder('| My Site')
                                        ->belowContent('Appended to all page titles (e.g., " | My Site")'),
                                    Textarea::make('default_meta_description')
                                        ->string()
                                        ->minLength(3)
                                        ->maxLength(1024)
                                        ->label('Default Meta Description')
                                        ->autosize()
                                        ->belowContent('Used when pages don\'t have their own description (max 160 chars)')
                                        ->columnSpanFull(),
                                    SpatieMediaLibraryFileUpload::make('default_social_image')
                                        ->nullable()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                        ->label('Default Social Image')
                                        ->imageEditor()
                                        ->collection('settings')
                                        ->belowContent('Used for social sharing when pages don\'t have their own image')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                    Section::make('Permalinks')
                        ->schema([

                        ]),
                ]),
            ]);
    }
}
