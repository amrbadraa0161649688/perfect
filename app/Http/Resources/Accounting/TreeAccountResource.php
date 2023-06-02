<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Master\Account;

class TreeAccountResource extends JsonResource
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
            'nature' => $this->when($this->nature,($this->nature=='c')? 'Credit':'Debit'),
            'appearance' => $this->when($this->appearance,($this->appearance=='i')? 'Income List':'Budget'),
            'main_type' =>  new MainTypeResource ($this->mainType),
            'childs' =>  $this->when($this->childs($this->id)->count() > 0 ,TreeAccountResource::collection ( $this->childs($this->id))),

    
        ];
    }
}
