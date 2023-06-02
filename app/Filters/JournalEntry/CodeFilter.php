<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

class CodeFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('journal_entry_no','like',"%{$value}%");
    }
}
