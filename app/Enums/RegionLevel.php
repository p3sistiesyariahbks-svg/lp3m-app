<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RegionLevel: string implements HasLabel
{
    case Regional = 'regional';
    case National = 'national';
    case International = 'international';

    public function getLabel(): string
    {
        return match ($this) {
            self::Regional => 'Regional',
            self::National => 'National',
            self::International => 'International',
        };
    }
}
