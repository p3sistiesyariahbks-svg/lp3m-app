<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public ?string $homepage_title;

    public ?string $title_suffix;

    public ?string $meta_description;

    public ?string $og_image;

    public array $verification;

    public static function group(): string
    {
        return 'seo';
    }
}
