<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceCardDtCheckResource extends JsonResource
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
            'mntns_cards_dt_id' => $this->mntns_cards_dt_id,
            'mntns_cards_item_id' => $this->mntns_cards_item_id,
            'mntns_type_value' => $this->mntns_cards_item_price,
            'mntns_cards_item_disc_type' => $this->mntns_cards_disc_type,
            'mntns_cards_item_disc_amount' => $this->mntns_cards_disc_amount, ////////////نسبه او قيمه الخصم
            'discount' => $this->mntns_cards_disc_value, /////////القيمه///////
            'vat_rate' => $this->mntns_cards_vat_value, /////////////النسبه
            'vat_value' => $this->mntns_cards_vat_amount, ///////////القيمه
            'total_after_vat' => $this->mntns_cards_amount, ///////////القيمه
            'total_before_vat' => $this->mntns_cards_item_price - $this->mntns_cards_disc_value, ///////////القيمه
        ];
    }
}
