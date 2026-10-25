<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Menu;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MenuPolicy
{
    use HandlesAuthorization;

    protected function getGuard(): ?string
    {
        return Filament::getCurrentPanel()?->getAuthGuard();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Menu', $this->getGuard());
    }

    public function view(AuthUser $authUser, Menu $menu): bool
    {
        return $authUser->can('View:Menu', $this->getGuard());
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Menu', $this->getGuard());
    }

    public function update(AuthUser $authUser, Menu $menu): bool
    {
        return $authUser->can('Update:Menu', $this->getGuard());
    }

    public function delete(AuthUser $authUser, Menu $menu): bool
    {
        return $authUser->can('Delete:Menu', $this->getGuard());
    }

    public function restore(AuthUser $authUser, Menu $menu): bool
    {
        return $authUser->can('Restore:Menu', $this->getGuard());
    }

    public function forceDelete(AuthUser $authUser, Menu $menu): bool
    {
        return $authUser->can('ForceDelete:Menu', $this->getGuard());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Menu', $this->getGuard());
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Menu', $this->getGuard());
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Menu', $this->getGuard());
    }

    public function replicate(AuthUser $authUser, Menu $menu): bool
    {
        return $authUser->can('Replicate:Menu', $this->getGuard());
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Menu', $this->getGuard());
    }
}
