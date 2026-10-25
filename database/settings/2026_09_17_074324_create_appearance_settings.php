<?php

use App\Enums\AuthMediaPosition;
use App\Enums\DefaultTheme;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('appearance.default_theme', DefaultTheme::System);
        $this->migrator->add('appearance.brand_color', '#6366F1');
        $this->migrator->add('appearance.items_per_page', 10);
        $this->migrator->add('appearance.show_share_buttons', true);
        $this->migrator->add('appearance.layout_style', AuthMediaPosition::Cover);
        $this->migrator->add('appearance.media_path');
        $this->migrator->add('appearance.media_alt');
        $this->migrator->add('appearance.media_size');
        $this->migrator->add('appearance.blur', 8);
    }
};
