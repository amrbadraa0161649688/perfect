<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDepartmentsTreeResource extends JsonResource
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
            'company_name_en' => $this->company_name_en,
            'company_name_ar' => $this->company_name_ar,
            'departments' => CompanyDepartmentsResource::collection($this->departments)
        ];
    }
}
