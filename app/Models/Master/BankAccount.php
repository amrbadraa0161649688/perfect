<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Bank;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class BankAccount extends Model implements PermissionSeederContract
{
  use PermissionSeederTrait;

    protected $table = 'bank_accounts';
    protected $guarded = [];

    public function bank()
    {
    return $this->belongsTo(Bank::class,'bank_id','id');

    }

    public function currency()
    {
      return $this->belongsTo(Currency::class,'currency_id','id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }

}
