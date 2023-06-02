<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDivisionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'division_id' => $this->division_id,
            'division_name_en' => $this->division_name_en,
            'division_name_ar' => $this->division_name_ar,
        ];
    }
}
