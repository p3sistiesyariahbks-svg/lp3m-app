<?php

namespace App\Filament\Admin\Clusters\Settings;

use App\Filament\Admin\Pages\ManageAppearance;
use App\Filament\Admin\Pages\ManageEmail;
use App\Filament\Admin\Pages\ManageGeneral;
use App\Filament\Admin\Pages\ManageSeo;
use App\Filament\Admin\Pages\ManageSocialLinks;
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
            ManageAppearance::class,
        ];
    }
}
