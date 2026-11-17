<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Clusters\Settings\SettingsCluster;
use App\Settings\AppearanceSettings;
use BackedEnum;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Enums\ThemeMode;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                                        ->enum(ThemeMode::class)
                                        ->label('Default Theme')
                                        ->options([
                                            ThemeMode::System->value => 'System',
                                            ThemeMode::Light->value => 'Light',
                                            ThemeMode::Dark->value => 'Dark',
                                        ])
                                        ->descriptions([
                                            ThemeMode::System->value => "Match visitor's OS preference",
                                            ThemeMode::Light->value => 'Always start in light mode',
                                            ThemeMode::Dark->value => 'Always start in dark mode',
                                        ])
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
                Section::make('Auth Pages')
                    ->description('Configure Auth Pages settings.')
                    ->schema([
                        Grid::make()
                            ->schema([
                                Radio::make('layout_style')
                                    ->required()
                                    ->string()
                                    ->enum(MediaPosition::class)
                                    ->label('Layout Style')
                                    ->options([
                                        MediaPosition::Cover->value => 'Cover',
                                        MediaPosition::Left->value => 'Left',
                                        MediaPosition::Right->value => 'Right',
                                        MediaPosition::Top->value => 'Top',
                                        MediaPosition::Bottom->value => 'Bottom',
                                    ])
                                    ->live()
                                    ->columnSpanFull(),
                                FileUpload::make('media_path')
                                    ->nullable()
                                    ->maxSize(2048)
                                    ->acceptedFileTypes([
                                        'image/png',
                                        'image/jpeg',
                                        'video/mp4',
                                    ])
                                    ->getUploadedFileNameForStorageUsing(
                                        fn (TemporaryUploadedFile $file): string => 'background-media.'.$file->extension(),
                                    )
                                    ->label('Background Media')
                                    ->imageEditor()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->downloadable()
                                    ->helperText('Image or video shown alongside/behind the form.')
                                    ->columnSpanFull(),
                                TextInput::make('media_alt')
                                    ->requiredWith('media_path')
                                    ->string()
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->label('Media Alt Text')
                                    ->columnSpanFull(),
                                TextInput::make('media_size')
                                    ->requiredWith('media_path')
                                    ->string()
                                    ->regex('/^\d+(\.\d+)?(px|%)$/')
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->label('Media Size')
                                    ->placeholder('50%, 250px')
                                    ->helperText('Ignored when layout is Cover.'),
                                TextInput::make('blur')
                                    ->requiredWith('media_path')
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
