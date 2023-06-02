<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BondResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'bond_id' => $this->bond_id,
            'bond_code' => $this->bond_code,
            'created_date' => $this->created_date,
            'company' => $this->company,
            'branch' => $this->branch,
            'account' => $this->account,
            'paymentMethod' => $this->paymentMethod,
            'bond_amount_debit' => $this->bond_amount_debit,
            'user' => $this->userCreated,
            'journalCapture' => $this->journalCapture,
        ];
    }
}
