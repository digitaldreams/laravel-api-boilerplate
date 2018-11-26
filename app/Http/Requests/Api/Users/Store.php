<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
            'first_name' => 'string|max:150',
            'last_name' => 'string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|max:15',
            'address' => 'string|max:250',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
