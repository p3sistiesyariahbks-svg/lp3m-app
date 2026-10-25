<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BackupType: string implements HasLabel
{
    case DatabaseOnly = 'database_only';
    case Full = 'full';

    public function getLabel(): string
    {
        return match ($this) {
            self::DatabaseOnly => 'Database Only',
            self::Full => 'Full Backup',
        };
    }
}
