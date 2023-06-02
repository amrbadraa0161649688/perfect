<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class BankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return[
            'id'=>$this->id,
            'ar_name'=>$this->ar_name ?? '',
            'en_name'=>$this->en_name ?? '',
            'prefix'=>$this->prefix ?? '',

        ];
    }
}
