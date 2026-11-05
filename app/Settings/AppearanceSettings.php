<?php

namespace App\Settings;

use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Enums\ThemeMode;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\EnumCast;

class AppearanceSettings extends Settings
{
    public ThemeMode $default_theme;

    public string $brand_color;

    public int $items_per_page;

    public MediaPosition $layout_style;

    public ?string $media_path;

    public ?string $media_alt;

    public string $media_size;

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
