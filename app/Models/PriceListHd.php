<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListHd extends Model
{
    //use HasFactory;

    protected $primaryKey = 'price_list_id';

    protected $table = 'price_list_hd';

    protected $dates = ['price_list_start_date', 'price_list_end_date'];

    protected $fillable = ['price_list_category', 'company_group_id', 'company_id',
        'customer_id', 'price_list_code', 'price_list_start_date' , 'price_list_end_date',
        'price_list_status', 'price_list_notes', 'created_user', 'updated_user'];


    public function pricelistDetails()
    {
        return $this->hasMany('App\Models\PriceListDt', 'price_list_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }
    public function getPriceListStartDateAttribute($value)
    {
        return $value?date('d/m/Y', strtotime($value)):null;
    }
    public function getPriceListEndDateAttribute($value)
    {
        return $value?date('d/m/Y', strtotime($value)):null;
    }
}

