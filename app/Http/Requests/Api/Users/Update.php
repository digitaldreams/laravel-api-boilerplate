<?php

namespace Permit\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Will use Policy here when working with Authorization

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:150',
            'last_name' => 'nullable|string|max:150',
            'email' => 'required|email|unique:users,email,' . $this->route('user')->id,
            'phone' => 'required|max:15',
            'address' => 'string|max:250',
            'password' => 'confirmed|min:6',
        ];
    }

}
