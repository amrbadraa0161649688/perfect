<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesCar extends Model
{
    use HasFactory, CompanyTrait;
    protected $table = 'sales_cars'; 
    protected $primaryKey = 'sales_cars_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const store_vou_date = 'store_vou_date';

    public function brand(){
        return $this->belongsTo('App\Models\CarRentBrand', 'sales_cars_brand_id');
    }

    public function brandDT(){
        return $this->belongsTo('App\Models\CarRentBrandDt', 'sales_cars_brand_dt_id');
    }

    public function status(){
        return $this->belongsTo('App\Models\SystemCode', 'sales_car_status','system_code_id');
    }

    public function Branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function storeCategory()
    {
        return $this->belongsTo('App\Models\SystemCode', 'store_category_type');
    }

    
}
