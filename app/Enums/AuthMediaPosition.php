<?php

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum AuthMediaPosition: string implements HasDescription, HasLabel
{
    case Cover = 'cover';
    case Left = 'left';
    case Right = 'right';
    case Top = 'top';
    case Bottom = 'bottom';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cover => 'Cover',
            self::Left => 'Left',
            self::Right => 'Right',
            self::Top => 'Top',
            self::Bottom => 'Bottom',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Cover => 'Fullscreen background with centered form',
            self::Left => 'Media on left, form on right',
            self::Right => 'Media on right, form on left',
            self::Top => 'Media banner above the form',
            self::Bottom => 'Media banner below the form',
        };
    }
}
