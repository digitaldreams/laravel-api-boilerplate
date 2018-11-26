<?php
/**
 * Created by PhpStorm.
 * User: Tuhin
 * Date: 10/29/2018
 * Time: 6:36 PM
 */

namespace Permit\Http\Controllers\Api\Auth;

use Permit\Http\Controllers\Api\ApiController;
use Permit\Http\Requests\Api\Users\Register;
use Permit\Models\Role;
use Permit\Models\User;
use Permit\Transformers\UserTransformer;

class RegisterController extends ApiController
{
    public function store(Register $request)
    {
        $user = new User();
        $user->fill($request->all());
        if (in_array($request->get('role'), config('permit.adminRoles', []))) {
            return $this->response->errorForbidden('Role ' . $request->get('role') . ' is protected. You are not allowed to claim this role');
        }
        $role = Role::slug($request->get('role', Role::USER))->select(['id'])->first();

        if ($user->save()) {
            if ($role) {
                $user->roles()->sync([$role->id]);
            }
            return $this->response->item($user, new UserTransformer());
        }
        return $this->response->errorBadRequest('Unable to register');
    }
}