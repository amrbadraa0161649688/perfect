<?php

namespace App\Http\Requests\CarRent\Brand;

use Illuminate\Foundation\Http\FormRequest;

class BrandDtRequest extends FormRequest
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
            'brand_dt_name_ar' => 'required|max:150',
            'brand_dt_name_en' => 'required|max:150',
            'brand_id' => 'required|exists:car_rent_brand,brand_id',
            'brand_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    }
}
