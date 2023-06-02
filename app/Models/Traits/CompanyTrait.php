<?php

namespace App\Models\Traits;

trait CompanyTrait
{
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}