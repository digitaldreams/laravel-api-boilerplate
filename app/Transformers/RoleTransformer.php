<?php

namespace App\Transformers;


use League\Fractal\ParamBag;
use App\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    private $validParams = ['q', 'limit', 'page', 'fields'];
    /**
     * @var array
     */
    protected $availableIncludes = [
        'users', 'permissions'
    ];

    public function transform(Role $role)
    {
        $data = [
            'id' => (int)$role->id,
            'name' => $role->name,
            'slug' => $role->slug,
        ];
        return $this->filterFields($data);
    }

    public function includePermissions(Role $role, ParamBag $paramBag = null)
    {
        return $this->collection($role->permissions, new PermissionTransformer($paramBag->get('fields')));
    }

    public function includeUsers(Role $role, ParamBag $paramBag = null)
    {
        return $this->collection($role->users, new UserTransformer($paramBag->get('fields')));
    }
}