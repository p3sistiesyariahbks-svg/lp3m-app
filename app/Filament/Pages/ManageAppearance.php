<?php

namespace App\Filament\Pages;

use App\Enums\AuthMediaPosition;
use App\Enums\DefaultTheme;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\AppearanceSettings;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageAppearance extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static ?string $navigationLabel = 'Appearance';

    protected static string $settings = AppearanceSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make([
                    Section::make('Brand & Appearance')
                        ->description('Configure Brand & Appearance settings.')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    Radio::make('default_theme')
                                        ->required()
                                        ->string()
                                        ->enum(DefaultTheme::class)
                                        ->label('Default Theme')
                                        ->options(DefaultTheme::class)
                                        ->columnSpanFull(),
                                    ColorPicker::make('brand_color')
                                        ->required()
                                        ->regex('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})\b$/')
                                        ->label('Brand Color')
                                        ->helperText('The primary accent color. Every button, link, and accent re-tints from this. Visitors can preview other colors with the on-page swatch switcher (if enabled).'),
                                ]),
                        ]),
                ]),
                Section::make('Pagination')
                    ->description('Control how many items are shown per page across listing views.')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('items_per_page')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(100)
                                    ->validationAttribute('items per page')
                                    ->label('Items Per Page')
                                    ->helperText('Applies to all listing pages across the site'),
                            ]),
                    ]),
                Section::make('Social Sharing')
                    ->description('Choose whether social share buttons appear on your content pages.')
                    ->schema([
                        Section::make('Show Share Buttons')
                            ->description('Display social share buttons on content pages.')
                            ->afterHeader([
                                Toggle::make('show_share_buttons')
                                    ->hiddenLabel()
                                    ->live(),
                            ]),
                    ]),
                Section::make('Auth Pages')
                    ->description('Configure Auth Pages settings.')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Radio::make('layout_style')
                                    ->required()
                                    ->string()
                                    ->enum(AuthMediaPosition::class)
                                    ->label('Layout Style')
                                    ->options(AuthMediaPosition::class)
                                    ->live()
                                    ->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('media_path')
                                    ->nullable()
                                    ->image()
                                    ->maxSize(1024)
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'video/mp4'])
                                    ->label('Background Media')
                                    ->imageEditor()
                                    ->collection('settings')
                                    ->helperText('Image or video shown alongside/behind the form.')
                                    ->columnSpanFull(),
                                TextInput::make('media_alt')
                                    ->requiredIfAccepted('media_path')
                                    ->string()
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->label('Media Alt Text')
                                    ->columnSpanFull(),
                                TextInput::make('media_size')
                                    ->requiredIfAccepted('media_path')
                                    ->string()
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->label('Media Size')
                                    ->placeholder('50%, 250px')
                                    ->helperText('Ignored when layout is Cover.'),
                                TextInput::make('blur')
                                    ->requiredIfAccepted('media_path')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(20)
                                    ->label('Blur Intensity')
                                    ->helperText('Value must be between 0-20.'),
                            ]),
                    ]),
            ]);
    }
}
