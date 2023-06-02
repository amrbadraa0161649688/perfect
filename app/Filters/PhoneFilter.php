<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class PhoneFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('phone','like',"%{$value}%");
    }
}
