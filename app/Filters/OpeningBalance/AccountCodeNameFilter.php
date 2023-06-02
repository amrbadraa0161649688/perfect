<?php
namespace App\Filters\OpeningBalance;

use App\Filters\AbstractBasicFilter;

class AccountCodeNameFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('account',function($q)use($value){
            return $q->where('name','like',"%{$value}%")
            ->orWhere('code','like',"%{$value}%");
        });
    }
}
