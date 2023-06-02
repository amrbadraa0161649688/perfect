<?php
namespace App\Filters\AccountPeriod;

use App\Filters\AbstractBasicFilter;

class MonthFilter extends AbstractBasicFilter{
    
    public function filter($value)
    {
        return $this->builder->where('month',$value)->get();
    }
}
