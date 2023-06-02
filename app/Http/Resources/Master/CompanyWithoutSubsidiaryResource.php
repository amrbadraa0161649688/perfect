<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyWithoutSubsidiaryResource extends JsonResource
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
        return [
            'id' => $this->id ?? '',
            'name' => $this->name ?? '',
            'commercial_registration_no' => $this->commercial_registration_no ?? '' ,
            'tax_card' => $this->tax_card ?? '',
           

        ];
    }
}
