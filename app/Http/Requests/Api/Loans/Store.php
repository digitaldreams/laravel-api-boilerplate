<?php

namespace App\Http\Requests\Api\Loans;

use App\Models\Loan;
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
        return auth()->user()->can('create', Loan::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'duration' => 'nullable|max:20',
            'interest_rate' => 'required|numeric',
            'arrangement_fee' => 'required|numeric',
            'status' => 'nullable|max:20',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [

        ];
    }

}
