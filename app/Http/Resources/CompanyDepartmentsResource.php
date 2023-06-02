<?php

namespace App\Http\Resources;

use App\Models\CompanyDetailsStr;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyDepartmentsResource extends JsonResource
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
            'department_id' => $this->department_id,
            'department_name_ar' => $this->department_name_ar,
            'department_name_en' => $this->department_name_en,
        ];
    }
}
