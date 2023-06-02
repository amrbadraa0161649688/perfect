<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class RequestCreatedToFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereDate('created_at','<=',$value);
    }
}
