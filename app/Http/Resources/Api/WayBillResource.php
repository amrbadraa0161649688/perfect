<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed email
 * @property mixed phone
 * @property mixed avatar
 * @property mixed type
 * @property mixed position
 * @property mixed active
 * @property mixed roles
 */
class WayBillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->waybill_id,
            'code' => $this->waybill_code,
            'date' => $this->waybill_load_date,
            'total' => $this->waybill_total_amount,
            'product_type' => isset($this->Wdetails->item) ? LiteListResource::make($this->Wdetails->item) : '',
            'quantity' => $this->Wdetails ? $this->Wdetails->waybill_item_quantity : 0,
            'location' => $this->location ? LiteListResource::make($this->location) : null,
            'status' => $this->status ? LiteListResource::make($this->status) : null,
            'rate' => $this->rate,
            'url' => $this->invoice_url,
        ];
    }
}
