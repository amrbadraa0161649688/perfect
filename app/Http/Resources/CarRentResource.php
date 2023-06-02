<?php

namespace App\Http\Resources;

use App\Models\CarPriceListDt;
use App\Models\CarPriceListHd;
use App\Models\Customer;
use App\Models\PriceListDt;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CarRentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $price_list_dt = isset($this->model->priceListDts) ? $this->model->priceListDts->first() : null;

        return [
            'car_id' => $this->car_id,
            'plate_number' => $this->full_car_plate,
            'brand_name_ar' => $this->brand ? $this->brand->brand_name_ar : '',
            'brand_name_en' => $this->brand ? $this->brand->brand_name_en : '',
            'car_rent_model_id' => $this->car_rent_model_id,
            'model_name_ar' => $this->brand->branddt ? $this->brandDetails->brand_dt_name_ar : '',
            'model_name_en' => $this->brand->branddt ? $this->brandDetails->brand_dt_name_en : '',
            'car_model_year' => $this->car_model_year??'',
            'color' => $this->car_color??'',
            'last_odometer' => $this->last_odometer??'',
            'tracker_status_ar' => $this->truckerStatus ? $this->truckerStatus->system_code_name_ar : '',
            'tracker_status_en' => $this->truckerStatus ? $this->truckerStatus->system_code_name_ar : '',
            'category_name_ar' => $this->category ? $this->category->system_code_name_ar : '',
            'category_name_en' => $this->category ? $this->category->system_code_name_en : '',
            'daily_price' => $price_list_dt ? $price_list_dt->rent_price : 0,
            'monthly_price' => $price_list_dt ? $price_list_dt->rent_price * 30 : 0,
            'rent_type_name_ar' => $price_list_dt ? $price_list_dt->rentType->system_code_name_ar : '',
            'rent_type_name_en' => $price_list_dt ? $price_list_dt->rentType->system_code_name_en : '',
        ];
    }
}
