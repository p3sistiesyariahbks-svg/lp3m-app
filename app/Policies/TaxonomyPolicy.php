<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Taxonomy;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class TaxonomyPolicy
{
    use HandlesAuthorization;

    protected function getGuard(): ?string
    {
        return Filament::getCurrentPanel()?->getAuthGuard();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Taxonomy', $this->getGuard());
    }

    public function view(AuthUser $authUser, Taxonomy $taxonomy): bool
    {
        return $authUser->can('View:Taxonomy', $this->getGuard());
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Taxonomy', $this->getGuard());
    }

    public function update(AuthUser $authUser, Taxonomy $taxonomy): bool
    {
        return $authUser->can('Update:Taxonomy', $this->getGuard());
    }

    public function delete(AuthUser $authUser, Taxonomy $taxonomy): bool
    {
        return $authUser->can('Delete:Taxonomy', $this->getGuard());
    }

    public function restore(AuthUser $authUser, Taxonomy $taxonomy): bool
    {
        return $authUser->can('Restore:Taxonomy', $this->getGuard());
    }

    public function forceDelete(AuthUser $authUser, Taxonomy $taxonomy): bool
    {
        return $authUser->can('ForceDelete:Taxonomy', $this->getGuard());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Taxonomy', $this->getGuard());
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Taxonomy', $this->getGuard());
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Taxonomy', $this->getGuard());
    }

    public function replicate(AuthUser $authUser, Taxonomy $taxonomy): bool
    {
        return $authUser->can('Replicate:Taxonomy', $this->getGuard());
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Taxonomy', $this->getGuard());
    }
}
