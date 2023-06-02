<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class AuthProfileRequest extends FormRequest
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
        $id = auth()->user()->user_id;
        return [
            'name' => 'sometimes|string|max:255',
            'user_mobile' => 'sometimes|unique:users_mobile,user_mobile,' . $id.',user_id',
            'user_email' => 'nullable|string|email|max:255|unique:users_mobile,user_email,' . $id.',user_id',
            'user_identity' => 'required|unique:users_mobile,user_identity',
        ];
    }
}
