<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Permissions\Attach;
use App\Http\Requests\Api\Permissions\Destroy;
use App\Http\Requests\Api\Permissions\Detach;
use App\Http\Requests\Api\Permissions\Store;
use App\Http\Requests\Api\Permissions\Sync;
use App\Http\Requests\Api\Permissions\Update;
use App\Http\Requests\Api\Permissions\Show;
use App\Transformers\PermissionTransformer;
use Illuminate\Http\Request;
use App\Models\Permission;

/**
 * RESTful Permission Controller
 *
 * @Resource("Permissions", uri="/permissions")
 */
class PermissionController extends ApiController
{

    public function index(Request $request)
    {
        $permissions = Permission::search($request->get('q'))->paginate($request->get('limit', 10));

        $this->attachRelation();

        if (!$permissions->isEmpty()) {
            return $this->response->paginator($permissions, new PermissionTransformer(), $request->all());
        } else {
            return $this->response->noContent();
        }
    }

    public function store(Store $request)
    {
        $permission = (new Permission())->fill($request->all());
        if ($permission->save()) {
            if ($request->has('roles') && is_array($request->get('roles'))) {
                $permission->roles()->sync($request->get('roles', []));
            }
            return $this->response->item($permission, new PermissionTransformer());
        } else {
            return $this->response->error('Unable to create permission', 403);
        }
    }

    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        //Attach Relation to the Transformer
        $this->attachRelation();

        return $this->response->item($permission, new PermissionTransformer());

    }

    public function update(Update $request, $id)
    {
        $permission = Permission::findOrFail($id);;
        if ($permission->fill($request->all())->save()) {
            if ($request->has('roles') && is_array($request->get('roles'))) {
                $permission->roles()->sync($request->get('roles', []));
            }
            return $this->response->item($permission, new PermissionTransformer());
        } else {
            return $this->response->error('Unable to update permission', 403);
        }
    }

    public function destroy(Destroy $request, $id)
    {
        if (Permission::destroy($id)) {
            return $this->response->array([
                'message' => 'Permission successfully deleted',
                'status_code' => 200
            ]);
        } else {
            return $this->response->noContent();
        }
    }

    public function roleSync(Sync $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->roles()->sync($request->get('roles', []));

        return $this->response->array([
            'message' => 'Roles successfully synchronized',
            'role_ids' => $permission->roles()->pluck('id')->toArray(),
            'status_code' => 200
        ]);

    }

    public function roleAttach(Attach $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->roles()->syncWithoutDetaching($request->get('roles', []));

        return $this->response->array([
            'message' => 'Roles successfully attached',
            'role_ids' => $permission->roles()->pluck('id')->toArray(),
            'status_code' => 200
        ]);

    }

    public function roleDetach(Detach $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->roles()->detach($request->get('roles', []));

        return $this->response->array([
            'message' => 'Roles successfully detached',
            'role_ids' => $permission->roles()->pluck('id')->toArray(),
            'status_code' => 200
        ]);

    }
}
