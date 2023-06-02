<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

class BranchFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('branch_id',$value);
    }
}
