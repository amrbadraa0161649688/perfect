<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Branch;
use App\Models\Master\Subsidiary;
use App\Models\Master\Company;
use App\Models\Master\Currency;
use App\Models\Master\Account;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class Treasury extends Model implements PermissionSeederContract
{
    use PermissionSeederTrait;

    protected $table = 'treasuries';
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id','id');
    }

    public function subsidiary()
    {
        return $this->belongsTo(Subsidiary::class,'subsidiary_id','id');
    }
    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }


}
