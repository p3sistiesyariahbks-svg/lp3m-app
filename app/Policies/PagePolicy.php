<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Page;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PagePolicy
{
    use HandlesAuthorization;

    protected function getGuard(): ?string
    {
        return Filament::getCurrentPanel()?->getAuthGuard();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Page', $this->getGuard());
    }

    public function view(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('View:Page', $this->getGuard());
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Page', $this->getGuard());
    }

    public function update(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('Update:Page', $this->getGuard());
    }

    public function delete(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('Delete:Page', $this->getGuard());
    }

    public function restore(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('Restore:Page', $this->getGuard());
    }

    public function forceDelete(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('ForceDelete:Page', $this->getGuard());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Page', $this->getGuard());
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Page', $this->getGuard());
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Page', $this->getGuard());
    }

    public function replicate(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('Replicate:Page', $this->getGuard());
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Page', $this->getGuard());
    }
}
