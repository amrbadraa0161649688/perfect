<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_group_id',
        'company_id',
        'user_id',
        'created_by',
        'status',
        'lat',
        'lon',
        'location_details',
        'city_id'
    ];

    protected $table = 'user_locations';


    public function city()
    {
        return $this->belongsTo('App\Models\SystemCode', 'city_id');
    }

    public function user()
    {
        return $this->belongsTo(UserMobile::class, 'user_id');
    }
}
