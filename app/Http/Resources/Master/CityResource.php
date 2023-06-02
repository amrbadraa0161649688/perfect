<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return[
            'id' => $this->id,
            'name_en' => $this->name_en ?? '',
            'name_ar' => $this->name_ar ?? '',
            'is_active' => intval($this->is_active ?? 0),
            // 'governorate'=>new GovernorateResource($this->governorate),
            'gov_id'=>$this->gov_id,
        ];
    }
}
