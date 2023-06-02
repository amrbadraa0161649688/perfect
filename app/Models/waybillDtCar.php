<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waybillDtCar extends Model
{
    use HasFactory;

    protected $table = 'waybill_dt_car';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $primaryKey = 'waybill_dt_id';

    protected $guarded = [];

}


