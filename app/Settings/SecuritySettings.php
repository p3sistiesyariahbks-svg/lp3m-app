<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SecuritySettings extends Settings
{
    public bool $maintenance_mode;

    public string $maintenance_message;

    public ?string $secret_bypass_key;

    public array $ip_whitelist;

    public bool $schedule_maintenance;

    public ?string $scheduled_start_time;

    public ?string $scheduled_banner_message;

    public static function group(): string
    {
        return 'security';
    }
}
