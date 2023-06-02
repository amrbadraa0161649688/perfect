<?php
namespace App\Filters\Account;

use App\Filters\AbstractBasicFilter;

class NatureFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('nature',$value);
    }
}
