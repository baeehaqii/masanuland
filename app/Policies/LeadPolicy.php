<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lead;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

/** Lead masuk dari website, jadi di panel hanya dilihat dan dihapus. */
class LeadPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Lead');
    }

    public function view(AuthUser $authUser, Lead $lead): bool
    {
        return $authUser->can('View:Lead');
    }

    public function delete(AuthUser $authUser, Lead $lead): bool
    {
        return $authUser->can('Delete:Lead');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Lead');
    }
}
