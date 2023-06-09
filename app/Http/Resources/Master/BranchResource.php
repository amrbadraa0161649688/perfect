<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'id'=>$this->id ?? '',
            'name'=>$this->name ?? '',
            'code'=>$this->code ?? '',
            'company'=>new CompanyResource($this->company),
        ];
    }
}
