<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
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
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Can create Permission
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * View Permission
     *
     * @param User $currentUser
     * @param Permission $permission
     * @return bool
     */
    public function show(User $currentUser, Permission $permission)
    {
        return true;
    }

    /**
     * Ability to update Permission
     *
     * @param User $currentUser
     * @param Permission $permission
     * @return bool
     */
    public function update(User $currentUser, Permission $permission)
    {
        return false;
    }

    /**
     * Can able to Delete Permission
     *
     * @param User $currentUser
     * @param Permission $permission
     * @return bool
     */
    public function destroy(User $currentUser, Permission $permission)
    {
        return false;
    }
}
