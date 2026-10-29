<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Gallery;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class GalleryPolicy
{
    use HandlesAuthorization;

    protected function getGuard(): ?string
    {
        return Filament::getCurrentPanel()?->getAuthGuard();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Gallery', $this->getGuard());
    }

    public function view(AuthUser $authUser, Gallery $gallery): bool
    {
        return $authUser->can('View:Gallery', $this->getGuard());
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Gallery', $this->getGuard());
    }

    public function update(AuthUser $authUser, Gallery $gallery): bool
    {
        return $authUser->can('Update:Gallery', $this->getGuard());
    }

    public function delete(AuthUser $authUser, Gallery $gallery): bool
    {
        return $authUser->can('Delete:Gallery', $this->getGuard());
    }

    public function restore(AuthUser $authUser, Gallery $gallery): bool
    {
        return $authUser->can('Restore:Gallery', $this->getGuard());
    }

    public function forceDelete(AuthUser $authUser, Gallery $gallery): bool
    {
        return $authUser->can('ForceDelete:Gallery', $this->getGuard());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Gallery', $this->getGuard());
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Gallery', $this->getGuard());
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Gallery', $this->getGuard());
    }

    public function replicate(AuthUser $authUser, Gallery $gallery): bool
    {
        return $authUser->can('Replicate:Gallery', $this->getGuard());
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Gallery', $this->getGuard());
    }
}
