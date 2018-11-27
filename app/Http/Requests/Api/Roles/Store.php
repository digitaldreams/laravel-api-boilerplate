<?php

namespace App\Http\Requests\Api\Roles;

use App\Models\Role;
use Dingo\Api\Http\FormRequest;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create', Role::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:250',
            'slug' => 'alpha_num|max:250|unique:roles,slug',
            'permissions.*' => 'exists:permissions,id'

        ];
    }
}
