<?php
/**
 * Created by PhpStorm.
 * User: mhosny
 * Date: 3/7/19
 * Time: 8:11 PM
 */

namespace App\Filters;



use Illuminate\Http\Request;

abstract class AbstractFilter
{
    protected $request;

    protected $filters = [

    ];
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder){
        foreach ($this->resolveFilters() as $key=>$value){
            $this->resolveClass($key,$builder)->filter($value);
        }
        return $builder;
    }

    public function resolveClass($key,$builder){
        $class = $this->filters[$key];
        return new $class($builder);
    }

    public function resolveFilters(){
        return array_filter($this->request->only(array_keys($this->filters)));
    }
}