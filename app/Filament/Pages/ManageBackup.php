<?php

namespace App\Filament\Pages;

use App\Enums\BackupFrequency;
use App\Enums\BackupRetentionPeriod;
use App\Enums\BackupStorageLocation;
use App\Enums\BackupTimeOfDay;
use App\Enums\BackupType;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\BackupSettings;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageBackup extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedServer;

    protected static ?string $navigationLabel = 'Backup';

    protected static string $settings = BackupSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make([
                    Callout::make()
                        ->description('Scheduled backups require a cron job on your server. Add this entry to your crontab: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1')
                        ->info()
                        ->color(null),
                    Section::make('Enable Scheduled Backups')
                        ->description('Automatically back up your site on a schedule. Backups protect your data against accidental loss.')
                        ->afterHeader([
                            Toggle::make('enable_scheduled_backups')
                                ->hiddenLabel()
                                ->live(),
                        ])
                        ->schema([
                            Grid::make()
                                ->schema([
                                    Select::make('frequency')
                                        ->required()
                                        ->string()
                                        ->enum(BackupFrequency::class)
                                        ->label('Frequency')
                                        ->options(BackupFrequency::class)
                                        ->native(false)
                                        ->selectablePlaceholder(false)
                                        ->disabled(fn (Get $get) => ! $get('enable_scheduled_backups'))
                                        ->belowContent('How often to run automatic backups.'),
                                    Select::make('backup_type')
                                        ->required()
                                        ->string()
                                        ->enum(BackupType::class)
                                        ->label('Backup Type')
                                        ->options(BackupType::class)
                                        ->native(false)
                                        ->selectablePlaceholder(false)
                                        ->disabled(fn (Get $get) => ! $get('enable_scheduled_backups'))
                                        ->belowContent('What to include in each backup.'),
                                    Select::make('time_of_day')
                                        ->required()
                                        ->string()
                                        ->enum(BackupTimeOfDay::class)
                                        ->label('Time of Day')
                                        ->options(BackupTimeOfDay::class)
                                        ->native(false)
                                        ->selectablePlaceholder(false)
                                        ->disabled(fn (Get $get) => ! $get('enable_scheduled_backups'))
                                        ->belowContent('When to run the backup (server time).'),
                                    Select::make('retention_period')
                                        ->required()
                                        ->string()
                                        ->enum(BackupRetentionPeriod::class)
                                        ->label('Retention Period')
                                        ->options(BackupRetentionPeriod::class)
                                        ->native(false)
                                        ->selectablePlaceholder(false)
                                        ->disabled(fn (Get $get) => ! $get('enable_scheduled_backups'))
                                        ->belowContent('How long to keep old backups before automatic cleanup.'),
                                    Select::make('storage_location')
                                        ->required()
                                        ->string()
                                        ->enum(BackupStorageLocation::class)
                                        ->label('Storage Location')
                                        ->options(BackupStorageLocation::class)
                                        ->native(false)
                                        ->selectablePlaceholder(false)
                                        ->disabled(fn (Get $get) => ! $get('enable_scheduled_backups'))
                                        ->belowContent('Where to store backup files.'),
                                ]),
                        ]),
                ]),
            ]);
    }
}
