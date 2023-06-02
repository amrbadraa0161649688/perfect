<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'acc_name_ar' => $this->acc_name_ar,
            'acc_name_en' => $this->acc_name_en,
            'acc_code' => $this->acc_code,
        ];
    }
}
