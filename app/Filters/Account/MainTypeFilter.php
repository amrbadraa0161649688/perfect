<?php
namespace App\Filters\Account;

use App\Filters\AbstractBasicFilter;

class MainTypeFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('main_type_id',$value);
    }
}
