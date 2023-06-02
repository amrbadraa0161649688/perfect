<?php

namespace App\Http\Resources;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'department_code' => $this->department_code,
            'companies' => CompanyResource::collection(Company::whereIn('company_id', json_decode($this->company_id))->get())
        ];
    }
}
