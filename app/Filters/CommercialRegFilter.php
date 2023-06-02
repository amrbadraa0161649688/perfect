<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class CommercialRegFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('commercial_reg_no','like',"%{$value}%");
    }
}
