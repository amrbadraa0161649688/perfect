<?php

namespace App\Http\Resources\Api;

use Google\Service\CloudSupport\ListAttachmentsResponse;
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
class CarWayBillResource extends JsonResource
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
            'location_form' => $this->locfrom ? LiteListResource::make($this->locfrom) : null,
            'location_to' => $this->locto ? LiteListResource::make($this->locto) : null,
            'status' => $this->status ? LiteListResource::make($this->status) : null,
            'car' => count($this->waybillCarDts) ? $this->waybillCarDts->first() : null,
            'total' => (int)$this->waybill_total_amount,
            'vat_amount' => (int)$this->waybill_vat_amount,
            'sub_total' => (int)($this->waybill_total_amount - $this->waybill_vat_amount),

            'paid' => $this->waybill_total_amount == $this->waybill_paid_amount,
            'cancel' => $this->status && $this->status->system_code == 41001 ?? false,

            'images' => $this->attachments ? LiteListAttachmentResource::collection($this->attachments) : null,

//            'images' => $this->whenLoaded('attachments', function () {
//                return LiteListAttachmentResource::collection($this->attachments);
//            })
        ];
    }
}
