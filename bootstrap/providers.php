<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\WorkspacePanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    WorkspacePanelProvider::class,
];
