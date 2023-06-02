<?php
namespace App\Filters\Account;

use App\Filters\AbstractBasicFilter;

class ApperanceFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('appearance',$value);
    }
}
