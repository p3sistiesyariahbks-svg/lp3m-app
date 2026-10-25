<?php

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum DefaultTheme: string implements HasDescription, HasLabel
{
    case System = 'system';
    case Light = 'light';
    case Dark = 'dark';

    public function getLabel(): string
    {
        return match ($this) {
            self::System => 'System',
            self::Light => 'Light',
            self::Dark => 'Dark',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::System => 'Match visitor\'s OS preference',
            self::Light => 'Always start in light mode',
            self::Dark => 'Always start in dark mode',
        };
    }
}
