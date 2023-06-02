<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomersBlock extends Model
{
    use HasFactory;

    protected $table='customers_block';
    protected $primaryKey ='customer_block_id';
    protected $fillable = [

        'company_group_id',
        'customer_identity',
        'customer_id',
        'customer_name_full_ar',
        'customer_name_full_en',
        'customer_mobile',
        'customer_block_status',
        'user_block',
        'startdate_block',
        'customer_block_notes',
        'user_unblock',
        'enddate_unblock',
        'customer_unblock_notes',

    ];
}
