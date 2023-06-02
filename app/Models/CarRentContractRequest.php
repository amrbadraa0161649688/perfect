<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentContractRequest extends Model
{
    use HasFactory;
    protected $table = 'car_rent_contract_request';
    protected $primaryKey = 'car_rent_contract_request_id';
    protected $guarded = [];


    public function CarRentContract()
    {
        return $this->belongsTo('App\Models\CarRentContract', 'contract_id');
    }
}
