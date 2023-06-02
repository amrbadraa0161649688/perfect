<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class CreatedByFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('createdBy',function($q) use ($value){
            $q->where('full_name','like',"%{$value}%");
        });
    }
}
