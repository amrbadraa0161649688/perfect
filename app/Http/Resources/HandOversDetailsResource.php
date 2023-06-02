<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HandOversDetailsResource extends JsonResource
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
            'item_name_ar' => $this->item->system_code_name_ar,
            'item_name_en' => $this->item->system_code_name_en,
            'item_notes' => $this->item_notes,
            'item_qunt' => $this->item_qunt,
            'item_value' => $this->item_value,
            'item_status_name_ar' => $this->status->system_code_name_ar,
            'item_status_name_en' => $this->status->system_code_name_en,
        ];
    }
}
