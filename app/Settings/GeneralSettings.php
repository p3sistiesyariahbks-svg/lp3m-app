<?php

namespace App\Settings;

use App\Enums\SiteLanguage;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\EnumCast;

class GeneralSettings extends Settings
{
    public string $site_name;

    public ?string $company_office;

    public float $latitude;

    public float $longitude;

    public ?string $support_email;

    public ?string $support_phone;

    public ?string $footer_page;

    public ?string $site_logo;

    public ?string $favicon;

    public SiteLanguage $site_language;

    public string $timezone;

    public string $date_format;

    public static function group(): string
    {
        return 'general';
    }

    public static function casts(): array
    {
        return [
            'site_language' => EnumCast::class,
        ];
    }
}
