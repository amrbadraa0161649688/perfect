<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WaybillCarInvoiceResource extends JsonResource
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
            'waybill_load_date' => date('d-m-y', strtotime($this->waybill_load_date)),
            'item_name_ar' => $this->detailsCar->item->system_code_name_ar,
            'item_name_en' => $this->detailsCar->item->system_code_name_en,
            'waybill_car_chase' => $this->detailsCar->waybill_car_chase,
            'waybill_car_plate' => $this->detailsCar->waybill_car_plate,
            'waybill_total_amount' => number_format($this->waybill_total_amount, 2),
            'waybill_item_amount' => $this->detailsCar->waybill_item_amount + $this->detailsCar->waybill_add_amount
                - $this->detailsCar->waybill_discount_total,
            'waybill_add_amount' => $this->detailsCar->waybill_add_amount,
            'waybill_discount_total' => $this->detailsCar->waybill_discount_total,
            'waybill_vat_amount' => $this->waybill_vat_amount,
            'waybill_loc_from_ar' => $this->locfrom->system_code_name_ar,
            'waybill_loc_from_en' => $this->locfrom->system_code_name_en,
            'waybill_loc_to_ar' => $this->locTo->system_code_name_ar,
            'waybill_loc_to_en' => $this->locTo->system_code_name_en,
            'waybill_car_desc' => $this->detailsCar->waybill_car_desc,
            'waybill_item_quantity' => $this->detailsCar->waybill_item_quantity,
            'waybill_ticket_no' => $this->waybill_ticket_no,
            'waybill_trip_id' => $this->waybill_trip_id ? 'مرحلة' : '',
            'waybill_due_amount' => $this->waybill_due_amount
        ];
    }
}
