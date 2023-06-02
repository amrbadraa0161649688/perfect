<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

class DocFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('doc_no','like',"%{$value}%");
    }
}
