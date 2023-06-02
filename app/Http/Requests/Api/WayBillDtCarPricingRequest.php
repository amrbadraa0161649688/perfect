<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class WayBillDtCarPricingRequest extends FormRequest
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
            'location_from' => 'required',
            'location_to' => 'required',
            'date' => 'required',
            'car_id' => 'required_if:type,4|exists:waybill_dt_car,waybill_dt_id',
            'type' => 'required|in:4,5',
        ];
    }
}
