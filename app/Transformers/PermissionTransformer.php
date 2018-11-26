<?php

namespace App\Transformers;


use League\Fractal\ParamBag;
use App\Models\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    private $validParams = ['q', 'limit', 'page', 'fields'];
    /**
     * @var array
     */
    protected $availableIncludes = [
        'roles', 'users'
    ];

    public function transform(Permission $permission)
    {
        $data = [
            'id' => (int)$permission->id,
            'method' => $permission->method,
            'class' => $permission->class,
            'updated_at' => $permission->updated_at->format('Y-m-d H:i:s'),
            'created_at' => $permission->created_at->format('Y-m-d H:i:s'),
        ];
        return $this->filterFields($data);
    }

    /**
     * Include Roles
     *
     * @param Permission $permission
     * @param ParamBag|null $paramBag
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(Permission $permission, ParamBag $paramBag = null)
    {
        return $this->collection($permission->roles, new RoleTransformer($paramBag->get('fields')));
    }

    /**
     * Include Users
     *
     * @param Permission $permission
     * @param ParamBag|null $paramBag
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUsers(Permission $permission, ParamBag $paramBag = null)
    {
        return $this->collection($permission->users, new UserTransformer($paramBag->get('fields')));
    }
}