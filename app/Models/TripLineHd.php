<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripLineHd extends Model
{
    use HasFactory;
    protected $table = 'trip_line_hd';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


//'trip_hd_fees_1' المصروف
//'trip_hd_fees_2' المكافأه
//'trip_hd_fees_3' قيمة الرد

    protected $primaryKey = 'trip_line_hd_id';

    protected $fillable = [
        'trip_line_category',
        'company_group_id',
        'company_id',
        'trip_line_code',
        'trip_line_desc',
        'trip_line_distance',
        'trip_line_time',
        'trip_line_fess_1',
        'trip_line_fees_2',
        'trip_line_fees_3',
        'trip_line_fees_4',
        'trip_line_status',
        'trip_line_loc_from',
        'trip_line_loc_to',
        'trip_line_type',
        'truck_type',

        'created_at', 'updated_at', 'created_user', 'updated_user',

    ];

    public function company()
    {

        return $this->belongsTo('App\Models\Company', 'company_id');

    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'trip_line_status');

    }

    public function tripLineDt()
    {

        return $this->hasMany('App\Models\TripLineDt', 'trip_line_hd_id');

    }

    public function tripLineDtF()
    {
        return $this->hasMany('App\Models\TripLineDt', 'trip_line_hd_id')->take(1);

    }

    public function locFrom()
    {

        return $this->belongsTo('App\Models\SystemCode', 'trip_line_loc_from')->withDefault('غير مسجل');

    }

    public function locTo()
    {

        return $this->belongsTo('App\Models\SystemCode', 'trip_line_loc_to');

    }

    public function truck_Type()
    {

        return $this->belongsTo('App\Models\SystemCode', 'truck_type');

    }


    public function tripLineTypeT()
    {

        return $this->belongsTo('App\Models\SystemCode', 'trip_line_type');

    }

    public function triplinetypename()
    {

        return $this->belongsTo('App\Models\SystemCode', 'trip_line_type');

    }

}
