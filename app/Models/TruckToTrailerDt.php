<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckToTrailerDt extends Model
{
    use HasFactory;

    protected $table = 'Trucks_To_Trailers_dt';
    protected $primaryKey = 'id_dt';

    public $timestamps = false;

    protected $guarded = [];

    public function itemType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'check_list_id');
    }
}
