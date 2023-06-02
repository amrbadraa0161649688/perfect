<?php
namespace App\Filters\OpeningBalance;

use App\Filters\AbstractBasicFilter;

class AccountCodeFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('account',function($q)use($value){
            return $q->where('code','like',"%{$value}%");
        });
    }
}
