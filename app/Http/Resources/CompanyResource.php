<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'company_id' => $this->company_id,
            'company_name_ar' => $this->company_name_ar,
            'company_name_en' => $this->company_name_en,
            'co_is_active' => $this->co_is_active,
        ];
    }
}
