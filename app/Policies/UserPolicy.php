<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * can do everything
     *
     * @param $user
     * @return bool
     */
    public function before($user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }


    /**
     * Show all Users
     *
     * @param User $currentUser
     * @return bool
     */
    public function index(User $currentUser)
    {
        return false;
    }

    /**
     * Can create user
     *
     * @param User $currentUser
     * @return bool
     */
    public function create(User $currentUser)
    {
        return false;
    }

    /**
     * View User Profile
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function show(User $currentUser, User $user)
    {
        return $currentUser->id == $user->id;
    }

    /**
     * Ability to update user profile
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id == $user->id;
    }

    /**
     * Can able to Delete user
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->id == $user->id;
    }

    /**
     * Can able to Activate user
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function active(User $currentUser, User $user)
    {
        return false;
    }

    /**
     * Able to make one users profile inactive
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function inActive(User $currentUser, User $user)
    {
        return false;
    }

    /**
     * Can able to block User
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function block(User $currentUser, User $user)
    {
        return false;
    }
}
