<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinFormSubTypeDt extends Model
{
    use HasFactory;

    protected $table = 'fin_form_sub_dt';

    protected $primaryKey = 'fin_sub_type_dt_id';

    protected $guarded = [];

    const UPDATED_AT = null;

    public function formSubType()
    {
        return $this->belongsTo('App\Models\FinFormSubType', 'fin_sub_type_id');
    }

    public function formType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'form_type_id');
    }

    public function finFormAccounts()
    {
        return $this->hasMany('App\Models\FinFormAccount','fin_sub_type_dt_id');
    }
}
