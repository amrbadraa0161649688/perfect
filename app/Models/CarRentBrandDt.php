<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentBrandDt extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'car_rent_brand_dt';

    protected $primaryKey = 'brand_dt_id';

    protected $fillable = [
        'brand_id','brand_dt_name_ar','brand_dt_name_en','company_id','company_group_id'
    ];

    public function brand(){
        return $this->belongsTo('App\Models\CarRentBrand', 'brand_id');
    }

    public function getBrandName(){
    	if(app()->getLocale() == 'ar')
            return $this->brand_dt_name_ar;
        return $this->brand_dt_name_en;
	}

    public function getNameAttribute()
    {
        return $this['brand_dt_name_' . app()->getLocale()];
    }
}
