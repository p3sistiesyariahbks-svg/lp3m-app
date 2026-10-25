<?php

namespace App\Settings;

use App\Enums\BackupFrequency;
use App\Enums\BackupRetentionPeriod;
use App\Enums\BackupStorageLocation;
use App\Enums\BackupTimeOfDay;
use App\Enums\BackupType;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\EnumCast;

class BackupSettings extends Settings
{
    public bool $enable_scheduled_backups;

    public BackupFrequency $frequency;

    public BackupType $backup_type;

    public BackupTimeOfDay $time_of_day;

    public BackupRetentionPeriod $retention_period;

    public BackupStorageLocation $storage_location;

    public static function group(): string
    {
        return 'backup';
    }

    public static function casts(): array
    {
        return [
            'frequency' => EnumCast::class,
            'backup_type' => EnumCast::class,
            'time_of_day' => EnumCast::class,
            'retention_period' => EnumCast::class,
            'storage_location' => EnumCast::class,
        ];
    }
}
