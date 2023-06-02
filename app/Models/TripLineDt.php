<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripLineDt extends Model
{
    use HasFactory;
    protected $table = 'trip_line_dt';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'trip_line_dt_id';

    protected $fillable = [
        'trip_line_hd_id',
        'company_group_id',
        'company_id',
        'loc_code',
        'loc_status',
        'loc_from',
        'loc_to' ,
        'distance' ,
        'distance_time',
        'cost_fees_1',
        'cost_fees_2',
        'cost_fees_3',
        'created_at', 'updated_at', 'created_user', 'updated_user',

    ];

    public function company(){

        return $this->belongsTo('App\Models\Company', 'company_id');

    }


    public function status(){

        return $this->belongsTo('App\Models\SystemCode', 'loc_status');

    }

    public function locFrom(){

        return $this->belongsTo('App\Models\SystemCode', 'loc_from');

    }

    public function locTo(){

        return $this->belongsTo('App\Models\SystemCode', 'loc_to');

    }

}
