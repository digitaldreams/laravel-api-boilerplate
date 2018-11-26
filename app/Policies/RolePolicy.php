<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Role;

class RolePolicy
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
     * Show all Roles
     *
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return false;
    }

    /**
     * Can create Role
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * View Role
     *
     * @param User $currentUser
     * @param Role $role
     * @return bool
     */
    public function show(User $currentUser, Role $role)
    {
        return true;
    }

    /**
     * Ability to update Role
     *
     * @param User $currentUser
     * @param Role $role
     * @return bool
     */
    public function update(User $currentUser, Role $role)
    {
        return false;
    }

    /**
     * Can able to Delete Role
     *
     * @param User $currentUser
     * @param Role $role
     * @return bool
     */
    public function destroy(User $currentUser, Role $role)
    {
        return false;
    }
}
