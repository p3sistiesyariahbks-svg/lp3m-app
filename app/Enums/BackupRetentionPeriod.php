<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BackupRetentionPeriod: int implements HasLabel
{
    case SevenDays = 7;
    case FourteenDays = 14;
    case ThirtyDays = 30;
    case SixtyDays = 60;
    case NinetyDays = 90;

    public function getLabel(): string
    {
        return match ($this) {
            self::SevenDays => '7 days',
            self::FourteenDays => '14 days',
            self::ThirtyDays => '30 days',
            self::SixtyDays => '60 days',
            self::NinetyDays => '90 days',
        };
    }
}
