<?php
namespace App\Filters;

use App\Filters\AbstractBasicFilter;

class UsersNameFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('users',function($q)use($value){
            return $q->where('name','like',"%{$value}%");
        });
    }
}
