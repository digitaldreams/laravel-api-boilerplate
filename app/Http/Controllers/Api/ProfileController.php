<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Users\ProfileUpdate;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;


/**
 * RESTful Profile Controller
 *
 * @Resource("Profile", uri="/profile")
 */
class ProfileController extends ApiController
{

    public function show(Request $request)
    {
        return $this->response->item(auth()->user(), new UserTransformer());
    }

    public function update(ProfileUpdate $request)
    {
        $user = auth()->user();

        if ($user->fill($request->all())->save()) {
            if ($request->has('password') && !empty($request->get('password'))) {
                $user->password = bcrypt($request->get('password'));
                $user->save();
            }
            return $this->response->item($user, new UserTransformer());
        }
        return $this->response->errorBadRequest();
    }

}