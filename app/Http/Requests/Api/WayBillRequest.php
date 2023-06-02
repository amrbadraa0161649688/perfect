<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WayBillRequest extends FormRequest
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
            'quantity' => 'required|numeric',
            'date' => 'required|date|after:today',
            'product_type_id' => 'required|exists:system_codes,system_code_id',
            'payment_method_id' => 'required|exists:system_codes,system_code_id',
            'user_location_id' => 'required|exists:user_locations,id',
        ];
    }
}
