<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BackupStorageLocation: string implements HasLabel
{
    case Local = 'local';
    case S3 = 's3';

    public function getLabel(): string
    {
        return match ($this) {
            self::Local => 'Local Disk',
            self::S3 => 'Amazon S3',
        };
    }
}
