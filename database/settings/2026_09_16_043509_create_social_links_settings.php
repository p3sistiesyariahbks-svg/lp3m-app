<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social_links.facebook');
        $this->migrator->add('social_links.twitter');
        $this->migrator->add('social_links.instagram');
        $this->migrator->add('social_links.linkedin');
        $this->migrator->add('social_links.youtube');
        $this->migrator->add('social_links.github');
        $this->migrator->add('social_links.discord');
    }
};
