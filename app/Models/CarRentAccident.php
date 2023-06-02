<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentAccident extends Model
{
    use HasFactory;

    protected $table = 'car_rent_accident';
    protected $primaryKey = 'car_accident_id';

    protected $guarded = [];


    public function getCarAccidentDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getCarAccidentDateCloseAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function accidentType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_accident_type_id');
    }

    public function accidentInsuranceCompany()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_accident_insurance');
    }

    public function accidentAppreciationBody()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_accident_appreciate');
    }

    public function carAccidentStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_accident_status');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function contract()
    {
        return $this->belongsTo('App\Models\CarRentContract', 'contract_id');
    }

    public function car()
    {
        return $this->belongsTo('App\Models\CarRentCars', 'car_id');
    }

    public function cartruck()
    {
        return $this->belongsTo('App\Models\Trucks','car_id');
    }

    /////معقب الحادث
    public function carFollower()
    {
        return $this->belongsTo('App\Models\Employee', 'car_accident_follower');
    }

    ////شركه التامين
    public function carInsuranceCompany()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_accident_insurance');
    }


    public function getCarAccidentUrlDocAttribute()
    {
        return asset('Files/' . $this->car_accident_url_doc);
    }


}
