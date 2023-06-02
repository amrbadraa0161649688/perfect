<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class JournalDetailResource extends JsonResource
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
            'statement' => $this->statement ?? '',
            'account' =>  new AccountResource ($this->account),
            'cost-center' =>  new CostCenterWithoutAccountsResource ($this->costCenter),
            'debit' =>  intVal($this->debit),
            'credit' =>  intVal($this->credit),
            'balance' =>  intVal($this->balance),


            
        ];
    }
}
