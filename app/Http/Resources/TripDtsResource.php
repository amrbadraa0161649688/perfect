<?php

namespace App\Http\Resources;

use App\Models\WaybillDt;
use Illuminate\Http\Resources\Json\JsonResource;

class TripDtsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'trip_dt_id' => $this->trip_dt_id,
            'waybill_id' => $this->waybill->waybill_id,
            'waybill_obj' => $this->waybill,
            'waybill_load_date' => $this->waybill->waybill_load_date,
            'waybill_dt' => $this->waybill->detailsCar,
            'car_type' => WaybillDt::where('waybill_hd_id', $this->waybill->waybill_id)->first()->waybill_car_desc,
            'loc_from' => $this->waybill->locfrom,
            'loc_to' => $this->waybill->locTo,
            'loc_transit' => $this->waybill->LocTransit,
            'waybill_total_amount' => $this->waybill->waybill_total_amount,
            'customer' => $this->waybill->customer,
            'payment' => $this->waybill->payment,
        ];
    }
}
