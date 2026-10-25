<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('security.maintenance_mode', false);
        $this->migrator->add(
            'security.maintenance_message',
            'We are currently performing scheduled maintenance. Please check back soon.',
        );
        $this->migrator->add('security.secret_bypass_key');
        $this->migrator->add('security.ip_whitelist', []);
        $this->migrator->add('security.schedule_maintenance', false);
        $this->migrator->add('security.scheduled_start_time');
        $this->migrator->add(
            'security.scheduled_banner_message',
            'Scheduled maintenance starting soon',
        );
    }
};
