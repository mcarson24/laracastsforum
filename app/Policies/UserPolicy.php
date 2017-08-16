<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the given profile.
     * 
     * @param  User   $profileUser
     * @param  User   $authenticatedUser
     * @return bool
     */
    public function update(User $profileUser, User $authenticatedUser)
    {
        return $authenticatedUser->id === $profileUser->id;
    }
}
