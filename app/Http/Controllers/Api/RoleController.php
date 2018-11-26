<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Roles\Destroy;
use App\Http\Requests\Api\Roles\Store;
use App\Http\Requests\Api\Roles\Update;
use App\Http\Requests\Api\Roles\Show;
use App\Http\Requests\Api\Roles\Sync;
use App\Http\Requests\Api\Roles\Attach;
use App\Http\Requests\Api\Roles\Detach;
use App\Transformers\RoleTransformer;
use Illuminate\Http\Request;
use App\Models\Role;

/**
 * RESTful Role Controller
 *
 * @Resource("Roles", uri="/roles")
 */
class RoleController extends ApiController
{

    public function index(Request $request)
    {
        $roles = Role::q($request->get('q'))->paginate($request->get('limit', 10));

        $this->attachRelation();

        if (!$roles->isEmpty()) {
            return $this->response->paginator($roles, new RoleTransformer(), $request->all());
        } else {
            return $this->response->noContent();
        }
    }


    public function store(Store $request)
    {
        $role = (new Role())->fill($request->all());
        if ($role->save()) {
            if ($request->has('permissions') && is_array($request->get('permissions'))) {
                $role->permissions()->sync($request->get('permissions', []));
            }
            return $this->response->item($role, new RoleTransformer());
        } else {
            return $this->response->error('Unable to create role', 403);
        }
    }

    public function show(Show $request, $id)
    {
        $role = Role::findOrFail($id);
        return $this->response->item($role, new RoleTransformer());
    }

    public function update(Update $request, $id)
    {
        $role = Role::findOrFail($id);
        if ($role->fill($request->all())->save()) {
            if ($request->has('permissions') && is_array($request->get('permissions'))) {
                $role->permissions()->sync($request->get('permissions', []));
            }
            return $this->response->item($role, new RoleTransformer());
        } else {
            return $this->response->error('Unable to update role', 403);
        }
    }

    public function destroy(Destroy $request, $id)
    {
        if (Role::destroy($id)) {
            return $this->response->array([
                'message' => 'Role successfully deleted',
                'status_code' => 200
            ]);
        } else {
            return $this->response->noContent();
        }
    }

    public function permissionSync(Sync $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->sync($request->get('permissions', []));

        return $this->response->array([
            'message' => 'Permissions successfully synchronized',
            'permissions_ids' => $role->permissions()->pluck('id')->toArray(),
            'status_code' => 200
        ]);
    }

    public function permissionAttach(Attach $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->syncWithoutDetaching($request->get('permissions', []));

        return $this->response->array([
            'message' => 'Permissions successfully attached',
            'permissions_ids' => $role->permissions()->pluck('id')->toArray(),
            'status_code' => 200
        ]);

    }

    public function permissionDetach(Detach $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach($request->get('permissions', []));

        return $this->response->array([
            'message' => 'Permissions successfully detached',
            'permission_ids' => $role->permissions()->pluck('id')->toArray(),
            'status_code' => 200
        ]);
    }

}
