<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

use App;

class ToFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->whereDate('date','<=', $value);
    }
}
