<?php

namespace App\Filters\JournalEntry;

use App\Filters\AbstractFilter;

class IndexFilter extends AbstractFilter{
    protected $filters = [
        'company'=>CompanyFilter::class,
        'subsidiary'=>SubsidiaryFilter::class,
        'branch'=>BranchFilter::class,
        'accounting_entry'=>AccountingEntryFilter::class,
        'journal_entry_no'=>CodeFilter::class,
        'to_date'=>ToFilter::class,
        'from_date'=>FromFilter::class,
        'doc_no'=>DocFilter::class,
        'file_no'=>FileFilter::class,
    ];
}
