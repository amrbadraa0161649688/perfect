<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBranchesResource extends JsonResource
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
            'user_branch_id' => $this->user_branch_id,
            'user_id' => $this->user_id,
            'job' => [
                'job_id' => $this->job->job_id,
                'job_name_ar' => $this->job->job_name_ar,
                'job_name_en' => $this->job->job_name_en,
                'job_code' => $this->job->job_code,
            ],
            'branch' => [
                'branch_id' => $this->branch->branch_id,
                'branch_name_ar' => $this->branch->branch_name_ar,
                'branch_name_en' => $this->branch->branch_name_en,
                'branch_code' => $this->branch->branch_code,
            ],
            'company' => [
                'company_id' => $this->company->company_id,
                'company_name_ar' => $this->company->company_name_ar,
                'company_name_en' => $this->company->company_name_en,
            ],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }
}
