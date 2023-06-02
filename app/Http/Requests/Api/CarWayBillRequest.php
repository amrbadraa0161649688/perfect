<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CarWayBillRequest extends FormRequest
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
        $validate = [
            'date' => 'required|date|after:today',
            'car_id' => 'required|exists:waybill_dt_car,waybill_dt_id',

            // for location map when door to door
            'door_to_door' => 'required|boolean',
            'loc_from' => 'required_if:door_to_door,1',
            'loc_to' => 'required_if:door_to_door,1',
            'location_from' => 'required|exists:system_codes,system_code_id',
            'location_to' => 'required|exists:system_codes,system_code_id',

            'same_owner' => 'required|boolean',
            'owner_name' => 'required_if:same_owner,0',
            'owner_national' => 'required_if:same_owner,0',

            'vat' => 'required|numeric',
            'vat_amount' => 'required|numeric',
            'total' => 'required|numeric',
            'type' => 'required|in:4,5',
        ];

        if (request()->type == 4) { // carrier
            $validate['same_recipient'] = 'required|boolean';
            $validate['recipient_name'] = 'required_if:same_recipient,0';
            $validate['recipient_phone'] = 'required_if:same_recipient,0';

        }
        return $validate;
    }
}
