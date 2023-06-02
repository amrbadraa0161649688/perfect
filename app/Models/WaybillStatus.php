<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaybillStatus extends Model
{
    use HasFactory;

    protected $table = 'waybill_status';

    const CREATED_AT = 'created_at';

    protected $guarded = [];

}
