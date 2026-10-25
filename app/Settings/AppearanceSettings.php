<?php

namespace App\Settings;

use App\Enums\AuthMediaPosition;
use App\Enums\DefaultTheme;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\EnumCast;

class AppearanceSettings extends Settings
{
    public DefaultTheme $default_theme;

    public string $brand_color;

    public int $items_per_page;

    public bool $show_share_buttons;

    public AuthMediaPosition $layout_style;

    public ?string $media_path;

    public ?string $media_alt;

    public ?string $media_size;

    public int $blur;

    public static function group(): string
    {
        return 'appearance';
    }

    public static function casts(): array
    {
        return [
            'default_theme' => EnumCast::class,
            'layout_style' => EnumCast::class,
        ];
    }
}
