<?php

use App\Enums\SiteLanguage;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'Laravel');
        $this->migrator->add('general.company_office');
        $this->migrator->add('general.support_email');
        $this->migrator->add('general.latitude', -6.2088);
        $this->migrator->add('general.longitude', 106.8456);
        $this->migrator->add('general.support_phone');
        $this->migrator->add('general.footer_page');
        $this->migrator->add('general.site_logo');
        $this->migrator->add('general.favicon');
        $this->migrator->add('general.site_language', SiteLanguage::English);
        $this->migrator->add('general.timezone', 'UTC');
        $this->migrator->add('general.date_format', 'Y-m-d');
    }
};
