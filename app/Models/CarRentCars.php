<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentCars extends Model
{
    use HasFactory;

    protected $table = 'car_rent_cars';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'car_id';

    protected $dates = ['car_operation_card_date', 'insurance_date_end', 'tracker_install_date'];

//    protected $fillable = [
//        'car_id', 'company_group_id', 'company_id', 'branch_id', 'car_status_id',
//        'plate_ar_1', 'plate_ar_2', 'plate_ar_3', 'plate_en_1', 'plate_en_2', 'plate_en_3',
//        'car_plate_number', 'full_car_plate' ,'car_chase', 'car_motor_no', 'car_registration_no', 'car_registration_type',
//        'car_operation_card_no', 'car_trucker_status', 'car_ownership_status','owner_name',
//        'car_brand_id', 'car_rent_model_id', 'car_model_year',
//        'car_category_id', 'car_color', 'car_weight', 'gear_box_type_id',
//        'engine_type', 'fuel_type_id', 'car_doors', 'car_passengers', 'car_desc',
//        'car_photo_url', 'oil_type', 'oil_change_km', last_oil_change_date , 'car_purchase_date',
//        'car_purchase_id', 'car_brand_dt_id', 'car_purchase_cost', 'owner_name', 'owner_identity_no', 'Property_type',
//        ,insurance_company , insurance_type , insurance_document_no , insurance_date_end
//        , insurance_value , insurance_amount, 'odometer_start' , 'car_Safety_Triangle' ,'car_Fire_extinguisher' , 'car_Radio_Stereo_status'
//        , 'car_Screen_status' , 'car_Speedometer_status' , 'car_Seats_status' , 'car_Spare_Tire_tools' , 'car_Spare_Tire_status'
//        , 'car_keys_status' , 'allowedKmPerHour' , 'car_ac_status'
//        'last_odometer','complete','enduranceAmount' , 'availableFuel' ,'car_Tires_status' , 'car_First_Aid_Kit'
//
//
//        'created_at', 'updated_at', 'created_user', 'updated_user',
//
//    ];

    protected $guarded = [];

    /////////////car_trucker_status , insurance_date_end , car_brand_dt_id , tracker_install_date , car_ownership_status


    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function getLastOilChangeDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getTrackerInstallDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getInsuranceDateEndAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function company()
    {

        return $this->belongsTo('App\Models\Company', 'company_id');

    }

    public function registrationType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_registration_type');
    }

    public function insuranceType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'insurance_type');
    }

    public function radioStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Radio_Stereo_status');
    }

    public function screenStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Screen_status');
    }

    public function speedometerStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Speedometer_status');
    }

    public function seatsStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Seats_status');
    }

    public function spareTireTools()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Spare_Tire_tools');
    }

    public function TiresStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Tires_status');
    }

    public function spareTireStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Spare_Tire_status');
    }

    public function firstAidKit()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_First_Aid_Kit');
    }

    public function keysStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_keys_status');
    }

    public function safetyTriangle()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_Safety_Triangle');
    }

    public function model()
    {
        return $this->belongsTo('App\Models\CarRentModel', 'car_rent_model_id');
    }

    public function truckerStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'car_trucker_status');
    }

    public function fuelType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'fuel_type_id');

    }

    public function oilType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'oil_type');

    }

    public function status()

    {

        return $this->belongsTo('App\Models\SystemCode', 'car_status_id');

    }

    public function engineType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'engine_type');

    }

    public function boxType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'gear_box_type_id');

    }

    public function category()
    {

        return $this->belongsTo('App\Models\SystemCode', 'car_category_id');

    }

    public function carAcStatus()
    {

        return $this->belongsTo('App\Models\SystemCode', 'car_ac_status');

    }
    public function plateType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'platetype');
    }


    public function getCarPhotoUrlAttribute($value)
    {
        return asset($value);
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\CarRentBrand', 'car_brand_id');
    }

    public function brandDetails()
    {
        return $this->belongsTo('App\Models\CarRentBrandDt', 'car_brand_dt_id');
    }
}
