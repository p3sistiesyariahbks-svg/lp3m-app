<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Announcement;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    protected function getGuard(): ?string
    {
        return Filament::getCurrentPanel()?->getAuthGuard();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Announcement', $this->getGuard());
    }

    public function view(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('View:Announcement', $this->getGuard());
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Announcement', $this->getGuard());
    }

    public function update(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('Update:Announcement', $this->getGuard());
    }

    public function delete(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('Delete:Announcement', $this->getGuard());
    }

    public function restore(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('Restore:Announcement', $this->getGuard());
    }

    public function forceDelete(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('ForceDelete:Announcement', $this->getGuard());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Announcement', $this->getGuard());
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Announcement', $this->getGuard());
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Announcement', $this->getGuard());
    }

    public function replicate(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('Replicate:Announcement', $this->getGuard());
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Announcement', $this->getGuard());
    }
}
