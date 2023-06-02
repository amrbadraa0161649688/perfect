<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripDt extends Model
{
    use HasFactory;
    protected $table = 'trip_dt';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'trip_dt_id';

    protected $fillable = [
        'trip_hd_id',
        'company_group_id',
        'company_id',
        'branch_id',
        'trip_hd_code',
        'trip_dt_loc_from',
        'trip_dt_loc_to',
        'trip_dt_start_date',
        'trip_dt_end_date',
        'trip_waybill_status',
        'trip_dt_started_date',
        'trip_dt_ended_date',
        'waybill_id',
        'trip_dt_serial',

        'waybill_transit_loc_1',
        'waybill_transit_loc_2',
        'waybill_transit_loc_3',
        'waybill_transit_loc_4',
        'trip_dt_key_no',
        'trip_dt_parking',
        'trip_dt_exit_no',

        'created_at', 'updated_at', 'created_user', 'updated_user',

    ];

    public function trip(){
        return $this->belongsTo('App\Models\TripHd','trip_hd_id');
    }

    public function waybill()
    {
        return $this->belongsTo('App\Models\WaybillHd', 'waybill_id');
    }

    public function waybillh()
    {
        return $this->belongsTo('App\Models\WaybillHd','waybill_id');
    }

    public function company()
    {

        return $this->belongsTo('App\Models\Company', 'company_id');

    }


    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'trip_waybill_status');

    }

    public function locTo()
    {
        return $this->belongsTo('App\Models\SystemCode', 'trip_dt_loc_to');
    }

}
