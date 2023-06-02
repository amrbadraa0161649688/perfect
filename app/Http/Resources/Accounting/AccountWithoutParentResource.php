<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Master\Account;

class AccountWithoutParentResource extends JsonResource
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
            'code' => $this->code ?? '',
            'name' => $this->name ?? '',
            'level' => intVal($this->level),
            'nature' => ($this->nature=='c')? 'Credit':'Debit',
            'appearance' => ($this->appearance=='i')? 'Income List':'Budget',
            'main_type' =>  new MainTypeResource ($this->mainType),

        ];
    }
}
