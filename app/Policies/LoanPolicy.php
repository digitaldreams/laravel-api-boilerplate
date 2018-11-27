<?php

namespace App\Policies;

use \App\Models\Loan;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoanPolicy
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
     * Determine whether the user can view the Loan.
     *
     * @param  User $user
     * @param  Loan $loan
     * @return mixed
     */
    public function view(User $user, Loan $loan)
    {
        return true;
    }

    /**
     * Determine whether the user can create Loan.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Loan.
     *
     * @param User $user
     * @param  Loan $loan
     * @return mixed
     */
    public function update(User $user, Loan $loan)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the Loan.
     *
     * @param User $user
     * @param  Loan $loan
     * @return mixed
     */
    public function delete(User $user, Loan $loan)
    {
        return true;
    }

}
