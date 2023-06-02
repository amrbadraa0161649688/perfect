<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class CodeFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('code','like',"%{$value}%");
    }
}
