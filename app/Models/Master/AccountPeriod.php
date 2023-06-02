<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Model;
//use Bitwise\PermissionSeeder\PermissionSeederContract;
//use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;

use App\Models\Master\Currency;
use App\Models\Master\PeriodStatus;

class AccountPeriod extends Model
{
   // use PermissionSeederTrait,HasFilter;

    protected $table = 'periods';
    protected $guarded = [];

    public function rates(){
        return $this->hasMany(AccountPeriodRates::class,'account_period_id','id');
    }

    public function status()
    {
        return $this->belongsTo(PeriodStatus::class,'status_id','id');
    }



}
