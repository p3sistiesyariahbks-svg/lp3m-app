<?php

namespace App\Filament\Pages;

use App\Enums\SiteLanguage;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Kisame76\FilamentAdvancedRichEditor\Forms\Components\AdvancedRichEditor;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Teknyo\LocaleHelper\Facades\LocaleHelper;

class ManageGeneral extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'General';

    protected static string $settings = GeneralSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make([
                    Section::make('Site Details')
                        ->schema([
                            TextInput::make('site_name')
                                ->required()
                                ->string()
                                ->minLength(3)
                                ->maxLength(100)
                                ->label('Site Name')
                                ->helperText('Displayed in browser title and emails'),
                            TextInput::make('company_office')
                                ->nullable()
                                ->string()
                                ->minLength(3)
                                ->maxLength(255)
                                ->label('Company Office'),
                            Grid::make()
                                ->schema([
                                    TextInput::make('latitude')
                                        ->required()
                                        ->numeric()
                                        ->minValue(-90)
                                        ->maxValue(90)
                                        ->requiredWith('longitude')
                                        ->label('Latitude')
                                        ->placeholder('-6.208800')
                                        ->helperText('Decimal degrees, from -90 to 90.'),
                                    TextInput::make('longitude')
                                        ->required()
                                        ->numeric()
                                        ->minValue(-180)
                                        ->maxValue(180)
                                        ->requiredWith('latitude')
                                        ->label('Longitude')
                                        ->placeholder('106.845600')
                                        ->helperText('Decimal degrees, from -180 to 180.'),
                                ]),
                            Grid::make()
                                ->schema([
                                    TextInput::make('support_email')
                                        ->nullable()
                                        ->email()
                                        ->label('Support Email')
                                        ->placeholder('support@example.com'),
                                    TextInput::make('support_phone')
                                        ->nullable()
                                        ->tel()
                                        ->minLength(6)
                                        ->maxLength(20)
                                        ->label('Support Phone')
                                        ->placeholder('+12345678900000'),
                                ]),
                            AdvancedRichEditor::make('footer_page')
                                ->nullable()
                                ->minLength(0)
                                ->maxLength(65535)
                                ->toolbarAlignment(Alignment::Left)
                                ->mediaLibrary(false)
                                ->headingLevels([1, 2, 3, 4, 5, 6])
                                ->fileAttachmentsMaxSize(2048)
                                ->resizableImages()
                                ->helperText('Use the toolbar to format text, add headings, lists, links, and images.'),
                        ]),
                    Section::make('Visual Assets')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    FileUpload::make('site_logo')
                                        ->nullable()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes([
                                            'image/png',
                                            'image/jpeg',
                                        ])
                                        ->getUploadedFileNameForStorageUsing(
                                            fn (TemporaryUploadedFile $file): string => 'site-logo.'.$file->extension(),
                                        )
                                        ->label('Site Logo')
                                        ->imageEditor()
                                        ->disk('public')
                                        ->visibility('public')
                                        ->downloadable()
                                        ->helperText('Recommended size: 200x60px.'),
                                    FileUpload::make('favicon')
                                        ->nullable()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes([
                                            'image/x-icon',
                                            'image/vnd.microsoft.icon',
                                        ])
                                        ->getUploadedFileNameForStorageUsing(
                                            fn (): string => 'favicon.ico',
                                        )
                                        ->label('Favicon')
                                        ->imageEditor()
                                        ->disk('public')
                                        ->visibility('public')
                                        ->downloadable()
                                        ->helperText('Accepted File Types: ico only.'),
                                ]),
                        ]),
                    Section::make('Localization')
                        ->schema([
                            Select::make('site_language')
                                ->required()
                                ->enum(SiteLanguage::class)
                                ->options(SiteLanguage::class)
                                ->label('Site Language')
                                ->native(false)
                                ->columnSpanFull(),
                            Grid::make()
                                ->schema([
                                    Select::make('timezone')
                                        ->required()
                                        ->label('Timezone')
                                        ->options(function (): array {
                                            return collect(LocaleHelper::getTimezones())
                                                ->mapWithKeys(fn (array $item, string $key) => [
                                                    $key => "{$item['identifier']} ({$item['offset_label']})",
                                                ])
                                                ->all();
                                        })
                                        ->native(false)
                                        ->preload()
                                        ->searchable(),
                                    Select::make('date_format')
                                        ->required()
                                        ->label('Date Format')
                                        ->options(function (): array {
                                            return collect(LocaleHelper::getDateFormats())
                                                ->mapWithKeys(fn (array $item, string $key) => [
                                                    $key => "{$item['example']}",
                                                ])
                                                ->all();
                                        })
                                        ->native(false)
                                        ->preload(),
                                ]),
                        ]),
                ]),
            ]);
    }
}
