<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;

class JobResource extends JsonResource
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
            'job_id' => $this->job_id,
            'job_name_ar' => $this->job_name_ar,
            'job_name_en' => $this->job_name_en,
            'job_code' => $this->job_code,
            'job_status' => $this->job_status,
            'division' => [
                'division_id' => $this->division->division_id,
                'division_name_en' => $this->division->division_name_en,
                'division_name_ar' => $this->division->division_name_ar,
            ],
            'department' => [
                'department_id' => $this->department->department_id,
                'department_name_en' => $this->department->department_name_en,
                'department_name_ar' => $this->department->department_name_ar,
            ],
            'companies' => CompanyResource::collection(Company::whereIn('company_id', json_decode($this->company_id))->get()),
        ];
    }
}
