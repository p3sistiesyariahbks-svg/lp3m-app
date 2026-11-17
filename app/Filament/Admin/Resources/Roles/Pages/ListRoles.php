<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use Waguilar\FilamentGuardian\Base\Roles\Pages\BaseListRoles;

class ListRoles extends BaseListRoles
{
    protected static string $resource = RoleResource::class;
}
