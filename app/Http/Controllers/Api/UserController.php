<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Users\Destroy;
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

    public function index(Request $request)
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


    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->response->item($user, new UserTransformer());
    }

    public function update(Update $request, $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->all());

        if (!empty($request->get('password'))) {
            $user->password = bcrypt($request->get('password'));
        }

        if ($user->save()) {
            if ($request->has('groups') && is_array($request->get('groups'))) {
                $user->groups()->sync($request->get('groups', []));
            }
            return $this->response->item($user, new UserTransformer());
        } else {
            return $this->response->error('Unable to uodate user', 403);
        }
    }

    public function destroy(Destroy $request, $id)
    {
        if (User::destroy($id)) {
            return $this->response->array([
                'message' => 'User successfully deleted',
                'status_code' => 200
            ]);
        } else {
            return $this->response->errorNotFound();
        }
    }

    public function sync(Sync $request, $id)
    {
        $user = User::findOrFail($id);
        $modelQuery = ($request->get('type') == 'team') ? $user->teams() : $user->roles();
        $modelQuery->sync($request->get('ids', []));
        return $this->response->array([
            'message' => $request->get('type') . ' successfully synchronized',
            'ids' => $modelQuery->select(['id'])->pluck('id')->toArray(),
            'status_code' => 200
        ]);
    }

    public function attach(Attach $request, $id)
    {
        $user = User::findOrFail($id);
        $modelQuery = ($request->get('type') == 'team') ? $user->teams() : $user->roles();
        $modelQuery->syncWithoutDetaching($request->get('ids', []));

        return $this->response->array([
            'message' => $request->get('type') . ' successfully attached',
            'ids' => $modelQuery->select(['id'])->pluck('id')->toArray(),
            'status_code' => 200
        ]);

    }

    public function detach(Detach $request, $id)
    {
        $user = User::findOrFail($id);
        $modelQuery = $user->roles();

        $user->roles()->detach($request->get('ids', []));

        return $this->response->array([
            'message' => 'Roles  successfully detached',
            'ids' => $modelQuery->select(['id'])->pluck('id')->toArray(),
            'status_code' => 200
        ]);
    }
}
