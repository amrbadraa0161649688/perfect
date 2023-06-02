<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'car_rent_model_id' => $this->car_rent_model_id,
            'car_rent_model_code' => $this->car_rent_model_code,
            'car_brand_ar' => $this->brand->brand_name_ar,
            'car_brand_en' => $this->brand->brand_name_en,
            'car_brand_name' => $this->brand->name,
            'car_brand_dt_name' => $this->brandDetail->name,
            'car_purchase_date' => $this->car_purchase_date?date('Y-m-d', strtotime($this->car_purchase_date)):'',
            'car_qty' => $this->car_qty,
            'car_model_year' => $this->car_model_year,
        ];


    }
}
