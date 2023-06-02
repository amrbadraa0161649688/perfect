<?php

namespace App\Filters\Account;

use App\Filters\AbstractFilter;

class IndexFilter extends AbstractFilter{
    protected $filters = [
        'code'=>CodeFilter::class,
        'name'=>NameFilter::class,
        'nature'=>NatureFilter::class,
        'main_type'=>MainTypeFilter::class,
        'appearance'=>ApperanceFilter::class,
        'account_codename'=>AccountCodeNameFilter::class

    ];
}
