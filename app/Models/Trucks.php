<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trucks extends Model
{
    use HasFactory;
    protected $table = 'Trucks';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $primaryKey = 'truck_id';

    protected $guarded = [];

    public function getTruckPurchaseDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getTrucksalesDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getTruckDriverEceivedAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getTruckDriverDeliveryAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }


    public function company()
    {

        return $this->belongsTo('App\Models\Company', 'company_id');

    }

    public function driver()
    {

        return $this->belongsTo('App\Models\Employee', 'truck_driver_id');

    }

    public function truckType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'truck_type');

    }

    public function plateType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'plateTypeId');
    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'truck_status');

    }

    public function branch_truck()
    {

        return $this->belongsTo('App\Models\Branch', 'branch_id');

    }

    public function branch_truck_from()
    {

        return $this->belongsTo('App\Models\Branch', 'truck_last_starting_location');

    }

    public function branch_truck_to()
    {

        return $this->belongsTo('App\Models\Branch', 'truck_last_end_location');

    }

    public function getlasttruckdate($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getTruckPhotoAttribute($value)
    {
        return asset($value);
    }

    public function trailer()
    {
        return $this->belongsTo('App\Models\AssetsM', 'trucker_id');
    }

    public function trips()
    {
        return $this->hasMany('App\Models\TripHd', 'truck_id');
    }
}
