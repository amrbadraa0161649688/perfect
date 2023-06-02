<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class AccountPeriodRates extends Model
{
    protected $table = 'account_period_rates';
    protected $guarded = [];

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }

    public function accountPeriod()
    {
        return $this->belongsTo(AccountPeriod::class,'account_period_id','id');
    }
}
