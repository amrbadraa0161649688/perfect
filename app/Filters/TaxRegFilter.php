<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class TaxRegFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->where('tax_reg_no','like',"%{$value}%");
    }
}
