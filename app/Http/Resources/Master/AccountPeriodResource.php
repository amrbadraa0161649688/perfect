<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AccountPeriodResource extends JsonResource
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
            'month'=>$this->month,
            'status_name'=>$this->status == '1' ? 'مرحل' : 'غير مرحل',
            'status'=>$this->status,
            'is_active'=>$this->is_active,
            'is_active_name'=>$this->is_active === '1' ? 'فعال' : 'غير فعال',
            'rates'=>$this->rates->map(function($item){
                return [
                    'id'=>$item->id,
                    'currency_id'=>$item->currency_id,
                    'currency_name'=>optional($item->currency)->name,
                    'rate'=>$item->rate
                ];
            })
        ];
    }
}
