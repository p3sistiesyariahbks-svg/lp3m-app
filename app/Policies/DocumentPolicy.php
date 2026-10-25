<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Document;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class DocumentPolicy
{
    use HandlesAuthorization;

    protected function getGuard(): ?string
    {
        return Filament::getCurrentPanel()?->getAuthGuard();
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Document', $this->getGuard());
    }

    public function view(AuthUser $authUser, Document $document): bool
    {
        return $authUser->can('View:Document', $this->getGuard());
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Document', $this->getGuard());
    }

    public function update(AuthUser $authUser, Document $document): bool
    {
        return $authUser->can('Update:Document', $this->getGuard());
    }

    public function delete(AuthUser $authUser, Document $document): bool
    {
        return $authUser->can('Delete:Document', $this->getGuard());
    }

    public function restore(AuthUser $authUser, Document $document): bool
    {
        return $authUser->can('Restore:Document', $this->getGuard());
    }

    public function forceDelete(AuthUser $authUser, Document $document): bool
    {
        return $authUser->can('ForceDelete:Document', $this->getGuard());
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Document', $this->getGuard());
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Document', $this->getGuard());
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Document', $this->getGuard());
    }

    public function replicate(AuthUser $authUser, Document $document): bool
    {
        return $authUser->can('Replicate:Document', $this->getGuard());
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Document', $this->getGuard());
    }
}
