<?php

namespace App\Http\Requests\Api\Passwords;

use Illuminate\Foundation\Http\FormRequest;

class Forget extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Will use Policy here when working with Authorization

        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|exists:users,email'
        ];
    }
}
