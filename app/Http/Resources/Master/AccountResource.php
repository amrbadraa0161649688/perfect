<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Master\Account;

class AccountResource extends JsonResource
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
            'id'=> $this->id,
            'name'=>$this->name ?? '',
            'code'=>$this->code ?? '',
            'level' => intval($this->level),
            'nature'=>$this->nature ?? '',
            'nature_name'=>trans('account.'.$this->nature),
            'main_type_id'=>$this->main_type_id ?? '',
            'main_type_name'=>optional($this->mainType)->name ?? '',
            'parent_id'=>$this->parent_id,
            'parent_name'=>optional($this->parent)->name ?? '',
            'appearance'=>$this->appearance ?? '',
            'appearance_name'=>$this->appearance == null ? '' : trans('account.'.$this->appearance),
            'parent_code_name'=>is_null($this->parent) ? '' : optional($this->parent)->code.' '.optional($this->parent)->name,
            'code_name'=>$this->getAccountCodeName()

        ];
    }
}
