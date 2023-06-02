<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WayBillResource extends JsonResource
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
            'waybill_code' => $this->waybill_code,
            'company_id' => $this->company_id,
            'customer_contract' => $this->customer_contract,
            'waybill_load_date' => $this->waybill_load_date,
            'waybill_unload_date' => $this->waybill_unload_date,
            'customer_id' => $this->customer_id,
            'waybill_ticket_no' => $this->waybill_ticket_no,
            'supplier_id' => $this->supplier_id,
            'waybill_status' => $this->waybill_status,
            'waybill_loc_from' => $this->waybill_loc_from,
            'waybill_loc_to' => $this->waybill_loc_to,
            'waybill_delivery_expected' => $this->waybill_delivery_expected,
            'waybill_truck_type_id' => $this->waybill_truck_type_id,
            'waybill_vat_rate' => $this->waybill_vat_rate,
            'waybill_vat_amount' => $this->waybill_vat_amount,
            'waybill_total_amount' => $this->waybill_total_amount,
            'waybill_truck_id' => $this->waybill_truck_id,
            'waybill_driver_id' => $this->waybill_driver_id,
            'waybill_delivery_date' => $this->waybill_delivery_date,
            'waybill_return' => $this->waybill_return,

        ];
    }
}
