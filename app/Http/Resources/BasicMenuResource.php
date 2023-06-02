<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicMenuResource extends JsonResource
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
            'app_id' => $this->app_id,
            'app_name_ar' => $this->app_name_ar,
            'app_name_en' => $this->app_name_en,
            'app_icon' => $this->app_icon,
            'application_menu'=>ApplicationMenuResource::collection($this->applicationMenu)
        ];
    }
}
