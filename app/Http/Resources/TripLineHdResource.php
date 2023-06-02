<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripLineHdResource extends JsonResource
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
            'trip_line_code' => $this->trip_line_code,
            'company_name_en' => $this->company->company_name_en,
            'company_name_ar' => $this->company->company_name_ar,
            'trip_line_status' => $this->trip_line_status,
            'user_name_ar' => $this->user->user_name_ar,
            'user_name_en' => $this->user->user_name_en,
            'loc_from_name_ar' => $this->locFrom->system_code_name_ar,
            'loc_from_id' => $this->locFrom->system_code_id,
            'loc_to_id' => $this->locTo->system_code_id,
            'loc_from_name_en' => $this->locFrom->system_code_name_en,
            'loc_to_name_ar' => $this->locTo->system_code_name_ar,
            'loc_to_name_en' => $this->locTo->system_code_name_en,
            'truck_type' => $this->truck_type,
            'trip_line_type' => $this->trip_line_type,
            'trip_line_desc' => $this->trip_line_desc,
            'trip_line_distance' => $this->trip_line_distance,
            'trip_line_time' => $this->trip_line_time,
            'trip_line_fess_1' => $this->trip_line_fess_1,
            'trip_line_fees_2' => $this->trip_line_fees_2,
            'trip_line_notes' => explode(',', $this->trip_line_desc)

        ];
    }
}
