<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Religion: string implements HasLabel
{
    case Islam = 'islam';
    case Protestant = 'protestant';
    case Catholic = 'catholic';
    case Hindu = 'hindu';
    case Buddha = 'buddha';
    case Khonghucu = 'khonghucu';

    public function getLabel(): string
    {
        return match ($this) {
            self::Islam => 'Islam',
            self::Protestant => 'Protestant Christianity',
            self::Catholic => 'Catholicism',
            self::Hindu => 'Hinduism',
            self::Buddha => 'Buddhism',
            self::Khonghucu => 'Confucianism',
        };
    }
}
