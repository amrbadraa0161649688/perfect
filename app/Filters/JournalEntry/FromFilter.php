<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

class FromFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereDate('date','>=', $value);
    }
}
