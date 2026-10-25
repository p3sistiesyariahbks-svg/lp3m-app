<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SocialLinksSettings extends Settings
{
    public ?string $facebook;

    public ?string $twitter;

    public ?string $instagram;

    public ?string $linkedin;

    public ?string $youtube;

    public ?string $github;

    public ?string $discord;

    public static function group(): string
    {
        return 'social_links';
    }
}
