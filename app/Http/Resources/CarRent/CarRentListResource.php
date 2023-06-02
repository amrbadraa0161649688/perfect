<?php

namespace App\Http\Resource\CarRent;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarRentListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable
    {
        return [
            'car_id' => $this->car_id,
            'plate_number' => $this->full_car_plate,
            'brand_name_ar' => $this->brand ? $this->brand->brand_name_ar : '',
            'brand_name_en' => $this->brand ? $this->brand->brand_name_en : '',
            'car_rent_model_id' => $this->car_rent_model_id,
            'model_name_ar' => $this->brand->branddt ? $this->brandDetails->brand_dt_name_ar : '',
            'model_name_en' => $this->brand->branddt ? $this->brandDetails->brand_dt_name_en : '',
            'car_model_year' => $this->car_model_year,
            'color' => $this->car_color,
            'last_odometer' => $this->last_odometer,
            'tracker_status_ar' => $this->truckerStatus ? $this->truckerStatus->system_code_name_ar : '',
            'tracker_status_en' => $this->truckerStatus ? $this->truckerStatus->system_code_name_ar : '',
        ];
    }
}
