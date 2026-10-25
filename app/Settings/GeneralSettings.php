<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public ?string $site_description;

    public ?string $support_email;

    public ?string $site_logo;

    public ?string $favicon;

    public string $timezone;

    public string $date_format;

    public static function group(): string
    {
        return 'general';
    }
}
