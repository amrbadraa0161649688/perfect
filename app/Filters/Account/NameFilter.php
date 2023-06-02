<?php
namespace App\Filters\Account;

use App\Filters\AbstractBasicFilter;

class NameFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('name','like',"%{$value}%");
    }
}
