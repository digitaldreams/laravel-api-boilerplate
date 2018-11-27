<?php

namespace App\Http\Requests\Api\Roles;

use Dingo\Api\Http\FormRequest;

class Detach extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('detach', $this->route('role'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permissions.*' => 'required|exists:permissions,id'

        ];
    }
}
