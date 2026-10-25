<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Shortlink;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ShortlinkPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Shortlink');
    }

    public function view(AuthUser $authUser, Shortlink $shortlink): bool
    {
        return $authUser->can('View:Shortlink');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Shortlink');
    }

    public function update(AuthUser $authUser, Shortlink $shortlink): bool
    {
        return $authUser->can('Update:Shortlink');
    }

    public function delete(AuthUser $authUser, Shortlink $shortlink): bool
    {
        return $authUser->can('Delete:Shortlink');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Shortlink');
    }

    public function restore(AuthUser $authUser, Shortlink $shortlink): bool
    {
        return $authUser->can('Restore:Shortlink');
    }

    public function forceDelete(AuthUser $authUser, Shortlink $shortlink): bool
    {
        return $authUser->can('ForceDelete:Shortlink');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Shortlink');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Shortlink');
    }

    public function replicate(AuthUser $authUser, Shortlink $shortlink): bool
    {
        return $authUser->can('Replicate:Shortlink');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Shortlink');
    }
}
