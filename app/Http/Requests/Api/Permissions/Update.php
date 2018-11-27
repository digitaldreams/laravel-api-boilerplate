<?php

namespace App\Http\Requests\Api\Permissions;

use Dingo\Api\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update', $this->route('permission'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'method' => 'required|string|max:250',
            'class' => 'string|max:250',
            'roles.*' => 'exists:roles,id'
        ];
    }

}
