<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Users\Destroy;
use App\Http\Requests\Api\Users\Index;
use App\Http\Requests\Api\Users\Store;
use App\Http\Requests\Api\Users\Update;
use App\Http\Requests\Api\Users\Show;
use App\Http\Requests\Api\Users\Attach;
use App\Http\Requests\Api\Users\Detach;
use App\Http\Requests\Api\Users\Sync;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * RESTful User Controller
 *
 * @Resource("Users", uri="/users")
 */
class UserController extends ApiController
{

    public function index(Index $request)
    {
        $users = User::q($request->get('q'))->paginate($request->get('limit', 10));
        if (!$users->isEmpty()) {
            return $this->response->paginator($users, new UserTransformer(), $request->all());
        } else {
            return $this->response->noContent();
        }
    }


    public function store(Store $request)
    {
        $user = (new User())->fill($request->all());
        if ($user->save()) {
            if ($request->has('groups') && is_array($request->get('groups'))) {
                $user->groups()->sync($request->get('groups', []));
            }
            return $this->response->item($user, new UserTransformer());
        } else {
            return $this->response->error('Unable to create user', 403);
        }
    }


    public function show(Show $request, User $user)
    {
        return $this->response->item($user, new UserTransformer());
    }

    public function update(Update $request, User $user)
    {
        $user->fill($request->all());
        if (!empty($request->get('password'))) {
            $user->password = bcrypt($request->get('password'));
        }
        if ($user->save()) {
            return $this->response->item($user, new UserTransformer());
        } else {
            return $this->response->error('Unable to uodate user', 403);
        }
    }

    public function destroy(Destroy $request, User $user)
    {
        if ($user->delete()) {
            return $this->response->array([
                'message' => 'User successfully deleted',
                'status_code' => 200
            ]);
        } else {
            return $this->response->errorNotFound();
        }
    }

    public function sync(Sync $request, User $user)
    {
        $modelQuery = ($request->get('type') == 'team') ? $user->teams() : $user->roles();
        $modelQuery->sync($request->get('ids', []));
        return $this->response->array([
            'message' => $request->get('type') . ' successfully synchronized',
            'ids' => $modelQuery->select(['id'])->pluck('id')->toArray(),
            'status_code' => 200
        ]);
    }

    public function attach(Attach $request, User $user)
    {
        $modelQuery = $user->roles();
        $modelQuery->syncWithoutDetaching($request->get('ids', []));

        return $this->response->array([
            'message' => $request->get('type') . ' successfully attached',
            'ids' => $modelQuery->select(['id'])->pluck('id')->toArray(),
            'status_code' => 200
        ]);
    }

    public function detach(Detach $request, User $user)
    {
        $modelQuery = $user->roles();
        $user->roles()->detach($request->get('ids', []));
        return $this->response->array([
            'message' => 'Roles  successfully detached',
            'ids' => $modelQuery->select(['id'])->pluck('id')->toArray(),
            'status_code' => 200
        ]);
    }
}
