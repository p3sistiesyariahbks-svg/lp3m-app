<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MailDriver: string implements HasLabel
{
    case Smtp = 'smtp';
    case Postmark = 'postmark';
    case Resend = 'resend';

    public function getLabel(): string
    {
        return match ($this) {
            self::Smtp => 'SMTP',
            self::Resend => 'Resend',
            self::Postmark => 'Postmark',
        };
    }
}
