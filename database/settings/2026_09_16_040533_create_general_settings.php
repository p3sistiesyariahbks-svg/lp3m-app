<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Laravel');
        $this->migrator->add('general.site_description');
        $this->migrator->add('general.support_email');
        $this->migrator->add('general.site_logo');
        $this->migrator->add('general.favicon');
        $this->migrator->add('general.timezone', 'UTC');
        $this->migrator->add('general.date_format', 'Y-m-d');
    }
};
