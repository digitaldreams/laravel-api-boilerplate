<?php

namespace App\Policies;

use \App\Models\Repayment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RepaymentPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        //return true if user has super power
    }

        /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return true;
    }
    /**
     * Determine whether the user can view the Repayment.
     *
     * @param  User  $user
     * @param  Repayment  $repayment
     * @return mixed
     */
    public function view(User $user, Repayment  $repayment)
    {
        return true;
    }
    /**
     * Determine whether the user can create Repayment.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }
    /**
     * Determine whether the user can update the Repayment.
     *
     * @param User $user
     * @param  Repayment  $repayment
     * @return mixed
     */
    public function update(User $user, Repayment  $repayment)
    {
        return true;
    }
    /**
     * Determine whether the user can delete the Repayment.
     *
     * @param User  $user
     * @param  Repayment  $repayment
     * @return mixed
     */
    public function delete(User $user, Repayment  $repayment)
    {
        return true;
    }

}
