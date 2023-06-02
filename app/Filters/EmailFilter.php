<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class EmailFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('email','like',"%{$value}%");
    }
}
