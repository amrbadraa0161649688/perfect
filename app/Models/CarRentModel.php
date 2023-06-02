<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CarRentModel extends Model
{
    use HasFactory;

    protected $table = 'car_rent_model';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $primaryKey = 'car_rent_model_id';

    protected $fillable = [
        'car_rent_model_code', 'company_group_id', 'company_id',
        'car_rent_model_status', 'car_brand_id', 'car_brand_dt_id', 'car_model_year',
        'car_category_id', 'car_color', 'car_weight', 'gear_box_type_id',
        'engine_type', 'fuel_type_id', 'car_doors', 'car_passengers', 'car_desc',
        'car_photo_url', 'oil_type', 'oil_change_km', 'car_purchase_date', 'car_qty',
        'car_purchase_id', 'car_purchase_cost', 'owner_name', 'owner_identity_no', 'Property_type',
        'created_at', 'updated_at', 'created_user', 'updated_user',
    ];

    public function priceListDts()
    {
        return $this->hasMany('App\Models\CarPriceListDt', 'car_model_id');
    }

    public function brandDetail()
    {
        return $this->belongsTo('App\Models\CarRentBrandDt', 'car_brand_dt_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function boxType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'gear_box_type_id');
    }

    public function fuelType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'fuel_type_id');
    }

    public function engineType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'engine_type');
    }

    public function oilType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'oil_type');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_rent_model_status');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\CarRentBrand', 'car_brand_id');
    }

    public function brandDetails()
    {
        return $this->belongsTo('App\Models\CarRentBrandDt', 'car_brand_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_category_id');
    }

    public function PropertyType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'Property_type');
    }

    public function carRentCars()
    {
        return $this->hasMany('App\Models\CarRentCars', 'car_rent_model_id');
    }

    public function getIdAttribute()
    {
        return $this->car_rent_model_id;
    }
}
