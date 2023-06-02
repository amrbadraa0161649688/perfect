<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WayBillDtCarRequest extends FormRequest
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
            'waybill_car_model' => 'required|exists:car_rent_brand,brand_id',
            'waybill_car_type' => 'required|exists:car_rent_brand_dt,brand_dt_id',
            'plate_car_type' => 'required|exists:system_codes,system_code_id',
            'waybill_car_chase' => 'required',
            'plate_name' => 'required',
            'model_type' => 'nullable',
            'waybill_car_color' => 'nullable',
            'same_owner' => 'required|boolean',
            'owner_name' => 'nullable',
            'owner_phone' => 'nullable',
        ];
    }
}
