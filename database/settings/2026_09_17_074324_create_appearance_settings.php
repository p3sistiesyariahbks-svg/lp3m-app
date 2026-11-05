<?php

use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Enums\ThemeMode;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appearance.default_theme', ThemeMode::System);
        $this->migrator->add('appearance.brand_color', '#6366F1');
        $this->migrator->add('appearance.items_per_page', 12);
        $this->migrator->add('appearance.layout_style', MediaPosition::Cover);
        $this->migrator->add('appearance.media_path');
        $this->migrator->add('appearance.media_alt');
        $this->migrator->add('appearance.media_size', '50%');
        $this->migrator->add('appearance.blur', 8);
    }
};
