<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripLineResource extends JsonResource
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
            'item_id' => $this->trip_line_dt_id,
            'loc_from' => $this->loc_from,
            'loc_to' => $this->loc_to,
            'distance' => $this->distance,
            'distance_time' => $this->distance_time,
            'cost_fees_1' => $this->cost_fees_1 ? $this->cost_fees_1 : 0,
            'cost_fees_2' => $this->cost_fees_2 ? $this->cost_fees_2 : 0,
            'cost_fees_3' => $this->cost_fees_3 ? $this->cost_fees_3 : 0,
        ];
    }
}
