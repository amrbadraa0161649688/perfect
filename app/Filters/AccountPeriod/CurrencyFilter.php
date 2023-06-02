<?php
namespace App\Filters\AccountPeriod;

use App\Filters\AbstractBasicFilter;

class CurrencyFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('currency',function($q)use($value){
            return $q->where('id',$value);
        });
    }
}
