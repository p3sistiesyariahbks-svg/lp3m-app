<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AuthenticationSettings extends Settings
{
    public bool $enable_user_registration;

    public bool $require_email_verification;

    public bool $enable_google_login;

    public ?string $google_client_id;

    public ?string $google_client_secret;

    public bool $enable_github_login;

    public ?string $github_client_id;

    public ?string $github_client_secret;

    public bool $enable_facebook_login;

    public ?string $facebook_app_id;

    public ?string $facebook_app_secret;

    public static function group(): string
    {
        return 'authentication';
    }

    public static function encrypted(): array
    {
        return [
            'google_client_secret',
            'github_client_secret',
            'facebook_app_secret',
        ];
    }
}
