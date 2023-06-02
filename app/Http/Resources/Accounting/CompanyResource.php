<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'logo_url'=> $this->getLogoUrl(),
            'name' => $this->name ?? '',
            'commercial_registration_no' => $this->commercial_registration_no ?? '' ,
            'tax_card' => $this->tax_card ?? '',
            'tree_levels'=>$this->tree_levels ?? '',
            'gov_id'=>$this->gov_id ?? '',
            'gov_name'=>optional($this->gov)->name_ar ?? '',
            'city_id'=>$this->city_id ?? '',
            'city_name'=>optional($this->city)->name_ar ?? '',
            'street_name'=>$this->street_name ?? '',
            'building_number'=>$this->building_number ?? '',
            'has_subsidiary_company'=>$this->has_subsidiary_company == '1',
            'prefix'=>$this->prefix ?? '',
            'serial_per_company'=>$this->serial_per_company == '1',
            'subsidiaries'=>SubsidiaryResource::collection($this->subsidiaries),

        ];
    }
}
