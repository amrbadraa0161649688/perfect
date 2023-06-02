<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningVoucher extends Model
{
    use HasFactory;

    protected $table='v_opening_vouchers';

    protected $guarded = [];
}
