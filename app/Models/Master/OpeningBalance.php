<?php

namespace App\Models\Master;


use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

use App\Models\Master\Account;
use App\Traits\HasFilter;


class OpeningBalance extends Model implements PermissionSeederContract
{
    use PermissionSeederTrait,HasFilter;

    protected $table = 'opening_balances';
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }

    public function subsidiary()
    {
        return $this->belongsTo(Subsidiary::class,'subsidiary_id','id');
    }


    public static function map($item){
        return [
            'year' => $item->year ?? '',
            'account_name' => optional($item->account)->getAccountCodeName() ?? '',
            'debit'=>$item->debtor_funds ?? 0,
            'credit'=>$item->creditor_funds ?? 0,
            'subsidiary_name'=>optional($item->subsidiary)->name ?? ''
        ];
    }

}
