<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinFormSubType extends Model
{
    use HasFactory;

    protected $table = 'fin_form_sub_type';

    protected $primaryKey = 'fin_sub_type_id';

    protected $guarded = [];

    const UPDATED_AT = null;

    public function formType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'form_type_id');
    }

    public function formSubTypeDts()
    {
        return $this->hasMany('App\Models\FinFormSubTypeDt', 'fin_sub_type_id')
            ->orderBY('acc_order');
    }


}
