<?php

namespace App\Http\Resources;

use App\Models\UserBranch;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $query_company = UserBranch::where('user_id', $this->user_id)->first();
        $query_job = $this->defaultBranch ? UserBranch::where('user_id', $this->user_id)->where('branch_id', $this->defaultBranch->branch_id)->first() : null;
        return [
            'user_id' => $this->user_id,
            'company_group_id' => $this->company_group_id,
            'company_id' => isset($query_company) ? $query_company->company->company_id : "",
            'user_code' => $this->user_code,
            'user_name_ar' => $this->user_name_ar,
            'user_name_en' => $this->user_name_en,
            'user_mobile' => $this->user_mobile,
            'user_start_date' => $this->user_start_date,
            'user_end_date' => $this->user_end_date,
            'user_profile_url' => $this->user_profile_url,
            'user_email' => $this->user_email,
            'company_group' => [
                'company_group_ar' => $this->companyGroup->company_group_ar,
                'company_group_en' => $this->companyGroup->company_group_en,
            ],
            'company' => 1,
            'job_default' => [
                'job_name_en' => isset($query_job) ? $query_job->job->job_name_en : '',
                'job_name_ar' => isset($query_job) ? $query_job->job->job_name_ar : '',
            ]
        ];
    }
}
