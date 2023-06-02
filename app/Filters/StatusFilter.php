<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class StatusFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('request_status_id',$value);
    }
}
