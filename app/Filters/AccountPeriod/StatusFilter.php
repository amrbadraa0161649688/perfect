<?php
namespace App\Filters\AccountPeriod;

use App\Filters\AbstractBasicFilter;

class StatusFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('status',function($q)use($value){
            return $q->where('id',$value);
        });
    }
}
