<?php

namespace App\Http\Resources;

use App\Models\SystemCode;
use App\Models\WaybillDt;
use Illuminate\Http\Resources\Json\JsonResource;

class WaybillNaqlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $waybill_dt = WaybillDt::where('waybill_hd_id', $this->waybill_id)->first();
        $loc_from = SystemCode::where('system_code_id', $this->waybill_loc_from)->first();
        $loc_to = SystemCode::where('system_code_id', $this->waybill_loc_to)->first();
        return [
            "sender" => (object)[
                "name" => $this->waybill_sender_name,
                "phone" => $this->waybill_sender_mobile,
                "countryCode" => "SA",
                "cityId" => 1,
                "address" => $loc_from->system_code_name_ar,
            ], //object
            "recipient" => (object)[
                "name" => $this->waybill_receiver_name,
                "phone" => $this->waybill_receiver_mobile,
                "countryCode" => "SA",
                "cityId" => 1,
                "address" => $loc_to->system_code_name_ar,
            ], //object
            "items" => [
                (object)[
                    "unitId" => 1,
                    "valid" => true,
                    "quantity" => $waybill_dt->waybill_qut_received_customer,
                    "deliverToClient" => true,
                    "price" => $waybill_dt->waybill_item_price,
                    "goodTypeId" => 9, ///سيارات
                    "weight" => 70.91,
                    "dangerousCode" => "1100"
                ]
            ], // array of object
            "fare" => $this->waybill_total_amount,
            "tradable" => true,
            "paidBySender" => true,
            "receivingLocation" => (object)[
                "countryCode" => "SA",
                "cityId" => 1,
                "address" => $loc_from->system_code_name_ar
            ], ///object
            "deliveryLocation" => (object)[
                "countryCode" => "SA",
                "cityId" => 2,
                "address" => $loc_to->system_code_name_ar
            ], ///object
        ];
    }
}
