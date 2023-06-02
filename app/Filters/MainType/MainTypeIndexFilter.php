<?php

namespace App\Filters\MainType;

use App\Filters\AbstractFilter;

class MainTypeIndexFilter extends AbstractFilter{
    protected $filters = [
        'nature'=>NatureFilter::class,
    ];
}
