<?php
namespace App\Filters\AccountPeriod;

use App\Filters\AbstractBasicFilter;

class ActiveFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        if($value == "مقفولة"){
            return $this->builder->where(function($q){
                return $q->whereNull('is_active')->orWhere('is_active',0);
            });
        }
        return $this->builder->where('is_active',1);
    }
}
