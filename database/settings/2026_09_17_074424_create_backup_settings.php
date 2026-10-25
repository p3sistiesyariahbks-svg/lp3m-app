<?php

use App\Enums\BackupFrequency;
use App\Enums\BackupRetentionPeriod;
use App\Enums\BackupStorageLocation;
use App\Enums\BackupTimeOfDay;
use App\Enums\BackupType;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('backup.enable_scheduled_backups', false);
        $this->migrator->add('backup.frequency', BackupFrequency::Daily);
        $this->migrator->add('backup.backup_type', BackupType::DatabaseOnly);
        $this->migrator->add('backup.time_of_day', BackupTimeOfDay::TwoAm);
        $this->migrator->add('backup.retention_period', BackupRetentionPeriod::ThirtyDays);
        $this->migrator->add('backup.storage_location', BackupStorageLocation::Local);
    }
};
