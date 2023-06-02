<?php
namespace App\Filters\OpeningBalance;

use App\Filters\AbstractBasicFilter;

class SubsidiaryFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('subsidiary_id',$value);
    }
}
