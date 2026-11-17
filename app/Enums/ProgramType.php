<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProgramType: string implements HasLabel
{
    case KKN = 'kkn';
    case Internship = 'internship';
    case K2P = 'k2p';

    public function getLabel(): string
    {
        return match ($this) {
            self::KKN => 'KKN',
            self::Internship => 'Internship',
            self::K2P => 'K2P',
        };
    }
}
