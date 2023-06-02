<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarPriceListDt extends Model
{
    use HasFactory;
    protected $table = 'car_rent_price_dt';
    protected $primaryKey = 'rent_list_dt_id';
    protected $fillable = [
        'rent_list_id',
        'company_group_id',
        'company_id',
        'customer_id',
        'car_model_id',
        'brand_id',
        'brand_dt_id',
        'rent_type_id',
        'rent_price',
        'discount_value',
        'extra_kilometer',
        'extra_kilometer_price',
        'extra_hour',
        'extra_hour_price',
        'hours_to_day',
        'extra_driver',

    ];

    public function brand()
    {
        return $this->belongsTo('App\Models\CarRentBrand', 'brand_id');
    }
    public function brandDt()
    {
        return $this->belongsTo('App\Models\CarRentBrandDt', 'brand_dt_id');
    }
    public function model()
    {
        return $this->belongsTo('App\Models\CarRentModel', 'car_model_id','car_rent_model_id');
    }

    public function priceListHd()
    {
        return $this->belongsTo('App\Models\CarPriceListHd', 'rent_list_id');
    }


    public function rentType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'rent_type_id');
    }
}
