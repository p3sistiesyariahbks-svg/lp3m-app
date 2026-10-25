<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings\SettingsCluster;
use App\Settings\SecuritySettings;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSecurity extends SettingsPage
{
    protected static ?string $cluster = SettingsCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Security';

    protected static string $settings = SecuritySettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Enable Maintenance Mode')
                    ->description('When enabled, visitors see a maintenance page. Admins and whitelisted IPs can still access the site.')
                    ->afterHeader([
                        Toggle::make('maintenance_mode')
                            ->hiddenLabel()
                            ->live(),
                    ])
                    ->schema([
                        Grid::make()
                            ->schema([
                                Textarea::make('maintenance_message')
                                    ->string()
                                    ->minLength(3)
                                    ->maxLength(1024)
                                    ->label('Maintenance Message')
                                    ->autosize()
                                    ->disabled(fn (Get $get) => ! $get('maintenance_mode'))
                                    ->belowContent('Message displayed to visitors during maintenance.')
                                    ->columnSpanFull(),
                                TextInput::make('secret_bypass_key')
                                    ->nullable()
                                    ->string()
                                    ->minLength(3)
                                    ->maxLength(100)
                                    ->label('Secret Bypass Key')
                                    ->placeholder('my-secret-key')
                                    ->disabled(fn (Get $get) => ! $get('maintenance_mode'))
                                    ->belowContent('Users can enter this key on the maintenance page to bypass'),
                                TagsInput::make('ip_whitelist')
                                    ->nullable()
                                    ->nestedRecursiveRules([
                                        'ipv4',
                                        'distinct',
                                    ])
                                    ->label('IP Whitelist')
                                    ->placeholder('192.168.1.1')
                                    ->disabled(fn (Get $get) => ! $get('maintenance_mode'))
                                    ->belowContent('IPs ranges that can bypass maintenance mode.')
                                    ->columnSpanFull(),
                                Section::make('Schedule Maintenance')
                                    ->description('Schedule maintenance to start at a specific time. A banner will be shown to visitors before it starts.')
                                    ->afterHeader([
                                        Toggle::make('schedule_maintenance')
                                            ->hiddenLabel()
                                            ->live()
                                            ->disabled(fn (Get $get) => ! $get('maintenance_mode')),
                                    ])
                                    ->schema([
                                        Grid::make()
                                            ->schema([
                                                DateTimePicker::make('scheduled_start_time')
                                                    ->nullable()
                                                    ->afterOrEqual('today')
                                                    ->label('Scheduled Start Time')
                                                    ->native(false)
                                                    ->default(now()->addDay())
                                                    ->minDate(now())
                                                    ->disabled(fn (Get $get) => ! $get('schedule_maintenance'))
                                                    ->belowContent('Maintenance mode will activate at this time.'),
                                                TextInput::make('scheduled_banner_message')
                                                    ->nullable()
                                                    ->string()
                                                    ->minLength(3)
                                                    ->maxLength(100)
                                                    ->label('Schedule Banner Message')
                                                    ->disabled(fn (Get $get) => ! $get('schedule_maintenance'))
                                                    ->belowContent('Message shown in the banner before maintenance starts.')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->compact()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
