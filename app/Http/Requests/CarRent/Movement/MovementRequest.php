<?php

namespace App\Http\Requests\CarRent\Movement;

use Illuminate\Foundation\Http\FormRequest;

class MovementRequest extends FormRequest
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
//            'brand_name_ar' => 'required|max:150',
//            'brand_name_en' => 'required|max:150',
//            'brand_logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    }
}
