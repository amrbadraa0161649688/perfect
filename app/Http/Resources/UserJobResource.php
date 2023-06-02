<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserJobResource extends JsonResource
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
            'permissions' => $this->permissions
        ];
    }
}
