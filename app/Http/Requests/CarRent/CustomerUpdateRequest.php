<?php

namespace App\Http\Requests\CarRent;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
            'customer_birthday' => 'required|date',
            'customer_birthday_hijiri' => 'required|max:30',
            'customer_mobile' => 'required|max:50',
            'customer_mobile_code' => 'required|exists:system_codes,system_code_id',
            'customer_gender' => 'required|in:0,1',
            'customer_nationality' => 'required|exists:system_codes,system_code_id',
            'customer_address_1' => 'nullable|max:250',
            'customer_phone_home' => 'nullable|max:10',
            'customer_job' => 'nullable|max:250',
            'customer_address_2' => 'nullable|max:250',
            'customer_phone' => 'nullable|max:50',
            'customer_company' => 'nullable|max:250',
            'customer_email' => 'nullable|email|max:250',
            'path' => 'nullable',
        ];
    }
}
