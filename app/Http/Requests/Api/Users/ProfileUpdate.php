<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:250',
            'last_name' => 'nullable|string|max:250',
            'email' => 'nullable|email|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|max:15',
            'address' => 'nullable|string|max:250',
            'password' => 'min:6|confirmed'
        ];
    }
}