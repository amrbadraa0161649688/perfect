<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CompanyTrait;

class FuelTransaction extends Model
{
    use HasFactory,CompanyTrait;

    protected $primaryKey = 'fuel_transaction_id';

    protected $table = 'fuel_transactions';


    public function scopeGetTotalAmount($query,$fuelType = null,$paymentMethod = null,$fromDate= null,$endDate = null)
    {
        $query = $query
        ->whereDate('r_transaction_date', '>=', $fromDate)
        ->whereDate('r_transaction_date', '<=', $endDate)
        ->WhereIn('fuel_type',$fuelType)
        ->WhereIn('payment_method',$paymentMethod)
        ->selectRaw('sum(amount) as total_amount')->first();

        if($query['total_amount'])
        {
            return $query['total_amount'];
        }

        return 0;
    }

    public function scopeGetTotalVolume($query,$fuelType = null,$paymentMethod = null,$fromDate= null,$endDate = null)
    {
        $query = $query
        ->whereDate('r_transaction_date', '>=', $fromDate)
        ->whereDate('r_transaction_date', '<=', $endDate)
        ->WhereIn('fuel_type',$fuelType)
        ->WhereIn('payment_method',$paymentMethod)
        ->selectRaw('sum(volume) as total_volume')->first();

        if($query['total_volume'])
        {
            return $query['total_volume'];
        }

        return 0;
    }
}
