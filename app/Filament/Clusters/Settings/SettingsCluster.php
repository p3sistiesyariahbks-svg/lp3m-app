<?php

namespace App\Filament\Clusters\Settings;

use App\Filament\Pages\ManageAppearance;
use App\Filament\Pages\ManageAuthentication;
use App\Filament\Pages\ManageBackup;
use App\Filament\Pages\ManageEmail;
use App\Filament\Pages\ManageGeneral;
use App\Filament\Pages\ManageNotification;
use App\Filament\Pages\ManageSecurity;
use App\Filament\Pages\ManageSeo;
use App\Filament\Pages\ManageSocialLinks;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class SettingsCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function getClusteredComponents(): array
    {
        return [
            ManageGeneral::class,
            ManageSeo::class,
            ManageSocialLinks::class,
            ManageEmail::class,
            ManageNotification::class,
            ManageBackup::class,
            ManageAuthentication::class,
            ManageSecurity::class,
            ManageAppearance::class,
        ];
    }
}
