<?php

namespace App\Http\Requests\CarRent;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
            'id_type_code' => 'required|exists:system_codes,system_code',
            'customer_identity' => 'required|max:20|min:10|unique:customers,customer_identity',
            'customer_type' => 'required|exists:system_codes,system_code_id',
            'customer_classification' => 'required|exists:system_codes,system_code_id',
            'customer_name_full_ar' => 'required|max:250',
            'customer_name_full_en' => 'nullable|max:250',
            'customer_name_1_ar' => 'required|max:50',
            'customer_name_2_ar' => 'nullable|max:50',
            'customer_name_3_ar' => 'nullable|max:50',
            'customer_name_4_ar' => 'required|max:50',
            'customer_name_1_en' => 'nullable|max:50',
            'customer_name_2_en' => 'nullable|max:50',
            'customer_name_3_en' => 'nullable|max:50',
            'customer_name_4_en' => 'nullable|max:50',
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
            'customer_vat_no' => 'nullable|max:50',
            'customer_vat_rate' => 'required|numeric',

            'customer_credit_limit' => 'nullable|numeric',
//            'customer_ref_no' => 'nullable|integer',
        ];
    }
}
