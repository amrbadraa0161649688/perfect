<?php

namespace App\Models\Views;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'v_vouchers';

    protected $guarded = [];

//    public function getJournalDateAttribute($value)
//    {
//        return Carbon::parse($value)->format('m-d-Y');
//    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_id');
    }
}
