<?php

namespace App\Transformers;

use League\Fractal\ParamBag;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    private $validParams = ['q', 'limit', 'page', 'fields'];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'roles'
    ];

    public function transform(User $user)
    {
        $data = [
            'id' => (int)$user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'phone' => $user->phone,
            'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        ];
        return $this->filterFields($data);
    }

    /**
     * Include Role
     *
     * @param User $user
     * @param ParamBag|null $paramBag
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $user, ParamBag $paramBag = null)
    {
        return $this->collection($user->roles, new RoleTransformer($paramBag->get('fields')));
    }

}