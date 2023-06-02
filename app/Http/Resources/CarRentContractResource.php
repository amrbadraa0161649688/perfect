<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CarRentContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $days_count = Carbon::parse($this->invoice_from_date)->diffInDays(Carbon::now());

        return [
            'contract_id' => $this->contract_id,
            'contract_code' => $this->contract_code,
            'contractStartDate' => $this->contractStartDate,
            'full_car_plate' => $this->car->full_car_plate,
            'model_name_ar' => $this->car ? $this->car->brandDetails->brand_dt_name_ar : '',
            'model_name_en' => $this->car ? $this->car->brandDetails->brand_dt_name_en : '',
            'car_model_year' => $this->car->car_model_year,
            'contract_type_ar' => $this->contractType->system_code_name_ar,
            'contract_type_en' => $this->contractType->system_code_name_en,
            'rentDayCost' => $this->rentDayCost,
            'from_date' => $this->invoice_from_date,
            'to_date' => Carbon::now()->format('Y-m-d'),
            'closed_date' => Carbon::parse($this->closed_datetime)->format('Y-m-d'),
            'days_count' => Carbon::parse($this->invoice_from_date)->diffInDays(Carbon::now()),
            'total_daily_cost' => $days_count * $this->rentDayCost,
            'discount_value' => $this->discount_value,
            'vat_amount' => ceil(($this->contract_vat_rate / 100) * $days_count * $this->rentDayCost),
            'vat_rate' => $this->contract_vat_rate

        ];
    }
}
