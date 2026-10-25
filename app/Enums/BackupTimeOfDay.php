<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BackupTimeOfDay: string implements HasLabel
{
    case Midnight = '00:00';
    case OneAm = '01:00';
    case TwoAm = '02:00';
    case ThreeAm = '03:00';
    case FourAm = '04:00';
    case FiveAm = '05:00';
    case SixAm = '06:00';
    case Noon = '12:00';
    case SixPm = '18:00';

    public function getLabel(): string
    {
        return match ($this) {
            self::Midnight => 'Midnight (00:00)',
            self::OneAm => '1:00 AM',
            self::TwoAm => '2:00 AM',
            self::ThreeAm => '3:00 AM',
            self::FourAm => '4:00 AM',
            self::FiveAm => '5:00 AM',
            self::SixAm => '6:00 AM',
            self::Noon => 'Noon (12:00)',
            self::SixPm => '6:00 PM',
        };
    }
}
