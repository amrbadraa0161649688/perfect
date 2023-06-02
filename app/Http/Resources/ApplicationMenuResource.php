<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationMenuResource extends JsonResource
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
            'app_menu_id' => $this->app_menu_id,
            'app_menu_name_ar' => $this->app_menu_name_ar,
            'app_menu_name_en' => $this->app_menu_name_en,
            'app_menu_icon' => $this->app_menu_icon,
            'app_menu_url' => $this->app_menu_url,
        ];
    }
}
