<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripHd extends Model
{
    use HasFactory;
    protected $table = 'trip_hd';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'trip_hd_id';


//'trip_hd_fees_1' المصروف
//'trip_hd_fees_2' المكافأه
//'trip_hd_fees_3' قيمة الرد

    protected $fillable = [
        'trip_hd_code',
        'company_group_id',
        'company_id',
        'branch_id',
        'trip_hd_date',
        'trip_hd_start_date',
        'trip_hd_end_date',
        'truck_id',
        'driver_id',
        'driver_mobil',
        'driver_rad_count',
        'trip_line_hd_id',
        'trip_hd_notes',
        'truck_meter_start',
        'truck_meter_end',
        'trip_hd_distance',
        'trip_hd_distance',
        'trip_hd_fees_1',
        'trip_hd_fees_2',
        'trip_hd_fees_3',
        'trip_hd_fees_4',
        'trip_hd_started_date',
        'trip_hd_ended_date',
        'trip_hd_status',
        'bond_id', 'trip_loc_transit', 'trip_id', 'status_id', 'http_status',
        'created_at', 'updated_at', 'created_user', 'updated_user',

    ];

    public function getCountTripDtsAttribute()
    {
        $trip_dts = TripDt::where('trip_hd_id', $this->trip_hd_id);
        return $trip_dts->count();
    }

    public function setTripHdStartDateAttribute($value)
    {
        $this->attributes['trip_hd_start_date'] = Carbon::createFromFormat('Y-m-d\TH:i', $value)->format('Y-m-d H:i');
    }

    public function setTripHdEndDateAttribute($value)
    {
        $this->attributes['trip_hd_end_date'] = Carbon::createFromFormat('Y-m-d\TH:i', $value)->format('Y-m-d H:i');
    }

    public function tripdts()
    {

        return $this->hasMany('App\Models\TripDt', 'trip_hd_id');

    }

    public function company()
    {

        return $this->belongsTo('App\Models\Company', 'company_id');

    }

    public function branch()
    {

        return $this->belongsTo('App\Models\Branch', 'branch_id');

    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'trip_hd_status');

    }


    public function truck()
    {

        return $this->belongsTo('App\Models\Trucks', 'truck_id');


    }

    public function driver()
    {

        return $this->belongsTo('App\Models\Employee', 'driver_id');

    }


    public function tripLine()
    {

        return $this->belongsTo('App\Models\TripLineHd', 'trip_line_hd_id');


    }


    public function report_url_trip()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '10401');
    }

    public function user()
    {

        return $this->belongsTo('App\Models\User', 'created_user');

    }

    public function loc_transit()
    {
        return $this->belongsTo('App\Models\SystemCode', 'trip_loc_transit')->withDefault('لا يوجد فرع');
    }

    public function getStrinAsDate($att)
    {
        return (new Carbon($this->attributes[$att]))->format('Y-m-d');
    }

    public function getPetroInsertDataAttribute()
    {
        return 
        [
            'plate' => $this->truck->truck_plate_en,
            'trip_number' => $this->trip_hd_code,
//            'max_trip_consumption_rial' => $this->trip_hd_fees_1,
            'start_date' => $this->getStrinAsDate('trip_hd_start_date'),
            'end_date' => $this->getStrinAsDate('trip_hd_end_date')
        ];
    }

}
