<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LocationsRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'lat' => 'required|string|max:191',
            'lon' => 'required|string|max:191',
            'location_details' => 'nullable',
            'city_id' => 'required|exists:system_codes,system_code_id',
        ];
    }
}
