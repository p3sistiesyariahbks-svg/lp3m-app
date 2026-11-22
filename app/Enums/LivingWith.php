<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LivingWith: string implements HasLabel
{
    case Parents = 'parents';
    case Guardian = 'guardian';
    case BoardingSchool = 'boarding_school';
    case Orphanage = 'orphanage';
    case Other = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::Parents => 'Parents',
            self::Guardian => 'Guardian',
            self::BoardingSchool => 'Boarding School',
            self::Orphanage => 'Orphanage',
            self::Other => 'Other',
        };
    }
}
