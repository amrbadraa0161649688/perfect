<?php

namespace App\Http\Resources;

use App\Models\PurchaseDetails;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $store_dt = PurchaseDetails::where('store_hd_id', $this->store_hd_id)->first();
        return [
            'store_hd_id' => $this->store_hd_id,
            'item_code' => $store_dt->item->item_code,
            'item_name_e' => $store_dt->item->item_name_e,
            'store_vou_qnt_p' => $store_dt->store_vou_qnt_p,
            'store_vou_item_price_unit' => $store_dt->store_vou_item_price_unit,
            'store_vou_item_total_price' => $store_dt->store_vou_item_total_price,
            'discType' => $store_dt->discType ? $store_dt->discType->system_code_name_ar : 'لا يوجد خصم',
            'store_voue_disc_value' => $store_dt->store_voue_disc_value,
            'store_vou_disc_amount' => $store_dt->store_vou_disc_amount,
            'store_vou_vat_amount' => $store_dt->store_vou_vat_amount,
            'store_vou_price_net' => $store_dt->store_vou_price_net,
            'store_vou_total' => $store_dt->store_vou_price_net,
            'store_category_name' => $this->storeCategory->system_code_name_ar,
            'store_acc_name' => $this->store_acc_name,
            'store_acc_tax_no' => $this->store_acc_tax_no,
        ];
    }
}
