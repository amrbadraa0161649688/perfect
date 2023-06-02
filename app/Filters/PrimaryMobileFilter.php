<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class PrimaryMobileFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('primary_mobile','like',"%{$value}%");
    }
}
