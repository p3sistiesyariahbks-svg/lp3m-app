<?php

namespace App\Settings;

use App\Enums\MailDriver;
use App\Enums\MailEncryption;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\EnumCast;

class EmailSettings extends Settings
{
    public bool $enable_email;

    public MailDriver $mail_driver;

    public ?string $postmark_api_key;

    public ?string $resend_api_key;

    public ?string $smtp_host;

    public ?int $smtp_port;

    public ?string $smtp_username;

    public ?string $smtp_password;

    public MailEncryption $encryption;

    public ?string $from_email_address;

    public ?string $from_name;

    public static function group(): string
    {
        return 'email';
    }

    public static function casts(): array
    {
        return [
            'mail_driver' => EnumCast::class,
            'encryption' => EnumCast::class,
        ];
    }

    public static function encrypted(): array
    {
        return [
            'postmark_api_key',
            'resend_api_key',
            'smtp_password',
        ];
    }
}
