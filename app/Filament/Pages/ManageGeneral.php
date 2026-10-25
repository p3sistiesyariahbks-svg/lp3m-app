<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
                                ->label('Site Name'),
                            Textarea::make('site_description')
                                ->string()
                                ->minLength(3)
                                ->maxLength(1024)
                                ->label('Site Description')
                                ->autosize()
                                ->belowContent('A short description of your site.'),
                            TextInput::make('support_email')
                                ->nullable()
                                ->email()
                                ->label('Support Email')
                                ->placeholder('support@example.com'),
                        ]),
                    Section::make('Visual Assets')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('site_logo')
                                        ->nullable()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                        ->label('Site Logo')
                                        ->imageEditor()
                                        ->collection('settings')
                                        ->belowContent('Recommended size: 200x60px'),
                                    SpatieMediaLibraryFileUpload::make('favicon')
                                        ->nullable()
                                        ->image()
                                        ->maxSize(1024)
                                        ->acceptedFileTypes(['image/icon', 'image/png', 'image/svg'])
                                        ->label('Favicon')
                                        ->imageEditor()
                                        ->collection('settings')
                                        ->belowContent('Site icon (.ico, .png, .svg)'),
                                ]),
                        ]),
                    Section::make('Localization')
                        ->schema([
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
