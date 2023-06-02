<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       return[
           'id'=>$this->id,
           'account_no'=>$this->account_no ?? '',
           'currency'=>new CurrencyResource($this->currency),
           'currency_id'=>$this->currency_id ?? '',
           'bank_id'=>$this->bank_id,
           'account_id'=>$this->account_id ?? '',
           'account_name'=>optional($this->account)->getAccountCodeName() ?? '',
           'bank'=>new BankResource($this->bank),
           'branch_name'=>$this->branch ?? '',
           'account' => new AccountResource($this->account),

       ];
    }
}
