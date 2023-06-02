<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

class FileFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('file_no','like',"%{$value}%");
    }
}
