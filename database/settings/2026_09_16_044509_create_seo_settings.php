<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('seo.homepage_title');
        $this->migrator->add('seo.title_suffix');
        $this->migrator->add('seo.default_meta_description');
        $this->migrator->add('seo.default_social_image');
    }
};
