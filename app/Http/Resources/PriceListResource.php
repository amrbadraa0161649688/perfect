<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceListResource extends JsonResource
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
            'price_list_dt_id' => $this->price_list_dt_id,
            'item_id' => $this->item_id,
            'loc_from' => $this->loc_from,
            'loc_to' => $this->loc_to,
            'distance' => $this->distance,
            'distance_time' => $this->distance_time,
            'cost_fees' => $this->cost_fees ? $this->cost_fees : 0,
            'max_fees' => $this->max_fees ? $this->max_fees : 0,
            'min_fees' => $this->min_fees ? $this->min_fees : 0,
        ];
    }
}
