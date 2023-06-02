<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class TreasuryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return 
        [
            'id' => $this->id,
            'company' => new CompanyResource($this->company) ,
            'subsidiary' => new SubsidiaryResource($this->subsidiary),
            'branch' => new BranchResource($this->branch),
            'currency' => new CurrencyResource($this->currency),
            'account' => new AccountResource($this->account),

        ];
    }
}
