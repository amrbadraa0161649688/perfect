<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceCardCheckResource extends JsonResource
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
            'mntns_cards_id' => $this->mntns_cards_id,
            'mntns_cards_type' => $this->cardType->system_code_id,
            'branch_name_en' => $this->branch->branch_name_en,
            'branch_name_ar' => $this->branch->branch_name_ar,
            'created_date' => Carbon::parse($this->created_date)->format('d-m-Y'),
            'mntns_cards_status' => $this->mntns_cards_status,
            'customer_name_full_ar' => $this->customer->customer_name_full_ar,
            'customer_vat_no' => $this->customer->customer_vat_no,
            'customer_mobile' => $this->customer->customer_mobile,
            'mntns_cars_id' => $this->mntns_cars_id,
            'mntns_cars_brand_id' => $this->car->mntns_cars_brand_id,
            'mntns_cars_color' => $this->car->mntns_cars_color,
            'mntns_cars_model' => $this->car->mntns_cars_model,
            'mntns_cars_meter' => $this->car->mntns_cars_meter,
            'mntns_cards_notes' => $this->mntns_cards_notes,
            'updated_user' => $this->updated_user,
            'card_dts' => MaintenanceCardDtCheckResource::collection($this->details)
        ];
    }
}
