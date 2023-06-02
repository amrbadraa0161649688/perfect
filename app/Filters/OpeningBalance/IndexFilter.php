<?php

namespace App\Filters\OpeningBalance;

use App\Filters\AbstractFilter;

class IndexFilter extends AbstractFilter{
    protected $filters = [
        'year'=>YearFilter::class,
        'subsidiary'=>SubsidiaryFilter::class,
        'account_name'=>AccountCodeNameFilter::class,
    ];
}
