<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

class CompanyFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('company_id',$value);
    }
}
