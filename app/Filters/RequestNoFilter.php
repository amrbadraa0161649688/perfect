<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class RequestNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('request_no','like',"%{$value}%");
    }
}
