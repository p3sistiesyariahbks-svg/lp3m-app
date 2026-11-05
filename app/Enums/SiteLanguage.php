<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SiteLanguage: string implements HasLabel
{
    case English = 'en';
    case Indonesian = 'id';

    public function getLabel(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Indonesian => 'Indonesian',
        };
    }
}
