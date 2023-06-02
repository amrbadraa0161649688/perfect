<?php
namespace App\Filters\MainType;

use App\Filters\AbstractBasicFilter;

class NatureFilter extends AbstractBasicFilter{
    
    public function filter($value)
    {
        return $this->builder->where('code',$value)->get();
    }
}
