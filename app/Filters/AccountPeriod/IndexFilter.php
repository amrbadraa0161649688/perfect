<?php

namespace App\Filters\AccountPeriod;

use App\Filters\AbstractFilter;

class IndexFilter extends AbstractFilter{
    protected $filters = [
        'year'=>YearFilter::class, 
        'currency_id'=>CurrencyFilter::class, 
        'month'=>MonthFilter::class,
        'status_id'=>StatusFilter::class,
        'is_active'=>ActiveFilter::class,
    ];
}
