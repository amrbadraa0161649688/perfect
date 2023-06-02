<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class OpeningBalanceResource extends JsonResource
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
            'id'=>$this->id,
            'year'=>$this->year,
            'account'=>new AccountResource($this->account),
            'debtor_funds'=>$this->debtor_funds,
            'creditor_funds'=>$this->creditor_funds,
        ];
    }
}
