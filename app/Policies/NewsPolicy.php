<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\News;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class NewsPolicy
{
    use HandlesAuthorization;

    protected function getGuard(): ?string
    {
        return Filament::getCurrentPanel()?->getAuthGuard();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:News', $this->getGuard());
    }

    public function view(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('View:News', $this->getGuard());
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:News', $this->getGuard());
    }

    public function update(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('Update:News', $this->getGuard());
    }

    public function delete(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('Delete:News', $this->getGuard());
    }

    public function restore(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('Restore:News', $this->getGuard());
    }

    public function forceDelete(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('ForceDelete:News', $this->getGuard());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:News', $this->getGuard());
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:News', $this->getGuard());
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:News', $this->getGuard());
    }

    public function replicate(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('Replicate:News', $this->getGuard());
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:News', $this->getGuard());
    }
}
