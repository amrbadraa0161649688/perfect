<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name ?? '',
            'commercial_reg_no' => $this->commercial_reg_no ?? '',
            'tax_reg_no' => $this->tax_reg_no ?? '',
            'customer_type_id'=>optional($this->customerType)->code ?? '',
            'customer_type_name'=>optional($this->customerType)->name_ar ?? '',
            //'note' => $this->when($this->customer_type_id ==3, $this->note ??''),
            // 'customer_type_id' =>  new CustomerTypeResource ($this->customerType),
            'address' =>  $this->getAddress(),
            'gov_id' =>  $this->gov_id ?? '',
            'gov_name'=> optional($this->governorate)->name_ar ?? '',
            'city_id' =>  $this->city_id ?? '',
            'city_name'=> optional($this->city)->name_ar ?? '',
            'street_name' =>  $this->street_name ?? '',
            'building_number' =>  $this->building_number ?? '',
            'customer_account_id'=>$this->customer_account_id,
            'customer_account_name' =>optional($this->customerAccount)->getAccountCodeName(),
            'supplier_account_id'=>$this->supplier_account_id,
            'supplier_account_name' =>optional($this->supplierAccount)->getAccountCodeName()

        ];
    }
}
