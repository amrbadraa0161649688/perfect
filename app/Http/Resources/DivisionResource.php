<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;

class DivisionResource extends JsonResource
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
            'division_code' => $this->division_code,
            'division_status' => $this->division_status,
            'department' => [
                'department_id' => $this->department->department_id,
                'department_name_en' => $this->department->department_name_en,
                'department_name_ar' => $this->department->department_name_ar,
            ],
            'companies' => CompanyResource::collection(Company::whereIn('company_id', json_decode($this->company_id))->get())
        ];
    }
}
