<?php

namespace App\Models;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SalesDetails extends Model
{
    use HasFactory, CompanyTrait;
    protected $table = 'sales_cars_dt';
    protected $primaryKey = 'store_dt_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const store_vou_date = 'store_vou_date';

    public function sales(){
        return $this->belongsTo('App\Models\sales', 'store_hd_id');
    }

    public function brand(){
        return $this->belongsTo('App\Models\CarRentBrand', 'store_brand_id');
    }

    public function brandDT(){
        return $this->belongsTo('App\Models\CarRentBrandDt', 'store_brand_dt_id');
    }

    public function discType(){
        return $this->belongsTo('App\Models\SystemCode', 'store_vou_disc_type','system_code_id');
    }

    public function storeVouType(){
        return $this->belongsTo('App\Models\SystemCode', 'store_vou_type');
    }

    public function car(){
        return $this->belongsTo('App\Models\SalesCar', 'store_vou_item_id');
    }

    public function getVouDate() {
        return (new Carbon($this->store_vou_date))->format('Y-m-d');
    }

    public function createdBy(){
        return $this->belongsTo('App\Models\User', 'created_user');
    }
}
