<?php
namespace App\Filters\OpeningBalance;

use App\Filters\AbstractBasicFilter;

class YearFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('year',$value);
    }
}
