<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MailEncryption: string implements HasLabel
{
    case Tls = 'tls';
    case Ssl = 'ssl';
    case None = 'none';

    public function getLabel(): string
    {
        return match ($this) {
            self::Tls => 'TLS',
            self::Ssl => 'SSL',
            self::None => 'None',
        };
    }
}
