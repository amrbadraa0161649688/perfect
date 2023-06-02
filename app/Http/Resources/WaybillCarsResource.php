<?php

namespace App\Http\Resources;

use App\Models\Customer;
use App\Models\SystemCode;
use App\Models\WaybillDt;
use Illuminate\Http\Resources\Json\JsonResource;

class WaybillCarsResource extends JsonResource
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
            'waybill_id' => $this->waybill_id,
            'waybill_code' => $this->waybill_code,
            'waybill_dt' => WaybillDt::where('waybill_hd_id', $this->waybill_id)->first(),
            'loc_to' => SystemCode::where('system_code_id', $this->waybill_loc_to)->first(),
            'loc_from' => SystemCode::where('system_code_id', $this->waybill_loc_from)->first(),
            'loc_transit' => SystemCode::where('system_code_id', $this->waybill_transit_loc_1)->first(),
            'waybill_total_amount' => $this->waybill_total_amount,
            'customer' => Customer::where('customer_id', $this->customer_id)->first(),
            'payment' => $this->waybill_payment_method ? SystemCode::where('system_code_id', $this->waybill_payment_method)->first() : '',
        ];
    }
}
