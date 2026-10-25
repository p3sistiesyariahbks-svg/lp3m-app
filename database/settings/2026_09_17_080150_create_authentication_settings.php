<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('authentication.enable_user_registration', true);
        $this->migrator->add('authentication.require_email_verification', true);
        $this->migrator->add('authentication.enable_google_login', false);
        $this->migrator->add('authentication.google_client_id');
        $this->migrator->addEncrypted('authentication.google_client_secret');
        $this->migrator->add('authentication.enable_github_login', false);
        $this->migrator->add('authentication.github_client_id');
        $this->migrator->addEncrypted('authentication.github_client_secret');
        $this->migrator->add('authentication.enable_facebook_login', false);
        $this->migrator->add('authentication.facebook_app_id');
        $this->migrator->addEncrypted('authentication.facebook_app_secret');
    }
};
