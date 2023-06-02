<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPriceListHd extends Model
{
    use HasFactory;
    protected $table = 'car_rent_price_hd';
    protected $primaryKey = 'rent_list_id';
    protected $fillable = [

        'company_group_id',
        'company_id',
        'customer_id',
        'customer_type_id',
        'price_customer_category',
        'price_branches',
        'rent_list_start_date',
        'rent_list_end_date',
        'rent_list_code',
        'rent_list_status',
        'rent_list_notes',
        'created_user',
        'updated_user',
        'created_at',
        'updated_at',

    ];

    public function getRentListStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getRentListEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function branches()
    {
        return $this->belongsTo('App\Models\Branch', 'price_branches');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function priceListDetails()
    {
        return $this->hasMany('App\Models\CarPriceListDt', 'rent_list_id');
    }

    public function customerType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'customer_type_id');
    }
}
