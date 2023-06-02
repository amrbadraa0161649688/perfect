<?php
/**
 * Created by PhpStorm.
 * User: mhosny
 * Date: 3/7/19
 * Time: 8:14 PM
 */

namespace App\Filters;


class AbstractBasicFilter
{
    protected $builder;

    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    public function filter($value){
        return $this->builder;
    }

}