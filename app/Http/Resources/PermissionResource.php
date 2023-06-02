<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'permission_id' => $this->permission_id,
            'permission_name' => $this->permission_name,
            'permission_name_ar' => $this->permission_name_ar,
            'permission_name_en' => $this->permission_name_en,
            'permission_view' => $this->permission_view,
            'permission_add' => $this->permission_add,
            'permission_update' => $this->permission_update,
            'permission_delete' => $this->permission_delete,
            'permission_print' => $this->permission_print,
            'permission_approve' => $this->permission_approve,
            'permission_status' => $this->permission_status,
            'company' => [
                'company_name_ar' => $this->company->company_name_ar,
                'company_name_en' => $this->company->company_name_en,
            ]
        ];
    }
}
