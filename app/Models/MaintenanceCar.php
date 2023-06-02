<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceCar extends Model
{
    use HasFactory,CompanyTrait;

    protected $table = 'maintenance_cars';
    protected $primaryKey = 'mntns_cars_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    protected $guarded;

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function brand(){

        return $this->belongsTo('App\Models\SystemCode', 'mntns_cars_brand_id','system_code_id');

    }
    public function truckname()
    {

        return $this->belongsTo('App\Models\Trucks', 'car_cost_center','truck_id');

    }
}
