<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinFormAccount extends Model
{
    use HasFactory;

    protected $table = 'fin_form_account';

    protected $primaryKey = 'fin_form_acc_id';

    protected $guarded = [];

    const UPDATED_AT = null;

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'acc_account_id');
    }

    public function finFormSubTypeDt()
    {
        return $this->belongsTo('App\Models\FinFormSubTypeDt', 'fin_sub_type_dt_id');
    }
}
