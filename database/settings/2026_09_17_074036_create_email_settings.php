<?php

use App\Enums\MailDriver;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('email.mail_driver', MailDriver::Smtp);
        $this->migrator->addEncrypted('email.postmark_api_key');
        $this->migrator->addEncrypted('email.resend_api_key');
        $this->migrator->add('email.smtp_host');
        $this->migrator->add('email.smtp_port', 587);
        $this->migrator->add('email.smtp_username');
        $this->migrator->addEncrypted('email.smtp_password');
        $this->migrator->add('email.from_email_address');
        $this->migrator->add('email.from_name');
    }
};
