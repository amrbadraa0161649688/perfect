<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class AuthRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name' => ['required', 'string', 'max:255'],
            'user_email' => 'nullable|email|unique:users_mobile,user_email',
            'user_mobile' => 'required|unique:users_mobile,user_mobile',
            'user_identity' => 'required|unique:users_mobile,user_identity',
            'user_password' => 'required|min:4|confirmed',
            'type' => ['required', 'in:1,2'], // 1->customer, 2->user
        ];
    }
}
