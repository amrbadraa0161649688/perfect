<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentBrand extends Model
{
    use HasFactory;

    protected $table = 'car_rent_brand';
    protected $primaryKey = 'brand_id';
    public $timestamps = false;

    protected $fillable = [
        'company_group_id', 'company_id', 'brand_name_ar', 'brand_name_en', 'brand_logo_url'
    ];

    public function branddt()
    {
        return $this->hasMany(CarRentBrandDt::class, 'brand_id');
    }

    public function getName()
    {
        if (app()->getLocale() == 'ar')
            return $this->brand_name_ar;
        return $this->brand_name_en;
    }

    public function getNameAttribute()
    {
        return $this['brand_name_' . app()->getLocale()];
    }

    public function getBrandLogoUrlAttribute($value): ?string
    {
        return $value ? asset($value) : null;
    }

    public function setBrandLogoUrlAttribute($value)
    {
        $this->attributes['brand_logo_url'] = is_file($value) ? uploadFile($value, 'Brands') : $value;
    }
}
