<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class RequestCreatedFromFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereDate('created_at','>=',$value);
    }
}
