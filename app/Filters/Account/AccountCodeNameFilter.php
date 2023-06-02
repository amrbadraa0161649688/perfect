<?php
namespace App\Filters\Account;

use App\Filters\AbstractBasicFilter;

class AccountCodeNameFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereHas('accounts',function($q)use($value){
            // return $q->where('name','like',"%{$value}%")
            // ->orWhere('code','like',"%{$value}%");
            return $q->where('accounts.name','like',"%{$value}%")
            ->orWhere('accounts.code','like',"%{$value}%");
        });
    }
}
