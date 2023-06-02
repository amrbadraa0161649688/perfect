<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WaybillCargoInvoiceResource extends JsonResource
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
            'waybill_load_date' => $this->waybill_load_date,
            'item_name_ar' => $this->detailsCar->item->system_code_name_ar,
            'item_name_en' => $this->detailsCar->item->system_code_name_en,
            'waybill_car_chase' => $this->detailsCar->waybill_car_chase,
            'waybill_car_plate' => $this->detailsCar->waybill_car_plate,
            'waybill_total_amount' => $this->waybill_total_amount,
            'waybill_item_amount' => $this->detailsCar->waybill_item_amount ,
            'waybill_add_amount' => $this->detailsCar->waybill_add_amount,
            'waybill_discount_total' => $this->detailsCar->waybill_discount_total,
            'waybill_vat_amount' => $this->waybill_vat_amount,
            'waybill_ticket_no' => $this->waybill_ticket_no,
            'waybill_car_desc' => $this->detailsCar->waybill_car_desc,
            'waybill_item_quantity' => $this->detailsCar->waybill_item_quantity,
        ];
    }
}
