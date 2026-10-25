<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Components\Section;
use Waguilar\FilamentGuardian\Base\Roles\Schemas\BaseRoleForm;
use Waguilar\FilamentGuardian\FilamentGuardianPlugin;

class RoleForm extends BaseRoleForm
{
    protected static function buildDetailsSection(FilamentGuardianPlugin $plugin): Section
    {
        return parent::buildDetailsSection($plugin)
            ->compact(false);
    }

    protected static function buildPermissionsSection(FilamentGuardianPlugin $plugin): Section
    {
        return parent::buildPermissionsSection($plugin)
            ->compact(false);
    }
}
