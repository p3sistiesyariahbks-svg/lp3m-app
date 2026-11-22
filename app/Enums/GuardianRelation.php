<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GuardianRelation: string implements HasLabel
{
    case Father = 'father';
    case Mother = 'mother';

    public function getLabel(): string
    {
        return match ($this) {
            self::Father => 'Father',
            self::Mother => 'Mother',
        };
    }
}
