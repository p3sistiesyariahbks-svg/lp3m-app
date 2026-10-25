<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public ?string $homepage_title;

    public ?string $title_suffix;

    public ?string $default_meta_description;

    public ?string $default_social_image;

    public static function group(): string
    {
        return 'seo';
    }
}
