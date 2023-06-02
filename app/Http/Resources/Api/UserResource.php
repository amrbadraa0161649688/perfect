<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed email
 * @property mixed phone
 * @property mixed avatar
 * @property mixed type
 * @property mixed position
 * @property mixed active
 * @property mixed roles
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->user_id,
            'name' => $this->user_name,
            'email' => $this->user_email,
            'phone' => $this->user_mobile,
            'image' => $this->user_profile_url,
            'status' => $this->user_status_id,
            'created_at' => $this->created_at,
            'user_identity' => $this->user_identity,
            'parent' => $this->when($this->parent_id, function () {
                return UserResource::make($this->parent);
            }),
        ];
    }
}
