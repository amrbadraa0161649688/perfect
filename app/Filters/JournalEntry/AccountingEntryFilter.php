<?php
namespace App\Filters\JournalEntry;

use App\Filters\AbstractBasicFilter;

class AccountingEntryFilter extends AbstractBasicFilter{

    public function filter($value)
    {
        return $this->builder->where('accounting_entry_id',$value);
    }
}
