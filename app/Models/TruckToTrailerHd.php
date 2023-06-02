<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckToTrailerHd extends Model
{
    use HasFactory;

    protected $table = 'Trucks_To_Trailers_hd';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    public function truck()
    {
        return $this->belongsTo('App\Models\Trucks', 'truck_id');
    }

    public function transactionType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'transaction_type');
    }


    public function trailer()
    {
        return $this->belongsTo('App\Models\AssetsM', 'trailer_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\Models\Employee', 'driver_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'create_user');
    }

    public function items()
    {
        return $this->hasMany('App\Models\TruckToTrailerDt', 'id_hd');
    }

}
