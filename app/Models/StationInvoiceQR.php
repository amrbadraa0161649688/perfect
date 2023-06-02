<?php

namespace App\Models;
use App\Models\Traits\CompanyTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class StationInvoiceQR extends Model
{
    use HasFactory,CompanyTrait;

    protected $primaryKey = 'station_inv_id';
    protected $table = 'station_invoice_qr';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $dates = ['inv_date'];

    public function scopeGetTotalAmount($query,$fuelType = null,$paymentMethod = null,$fromDate= null,$endDate = null)
    {
        $query = $query
        ->whereDate('trans_date', '>=', $fromDate)
        ->whereDate('trans_date', '<=', $endDate)
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
        ->whereDate('trans_date', '>=', $fromDate)
        ->whereDate('trans_date', '<=', $endDate)
        ->WhereIn('fuel_type',$fuelType)
        ->WhereIn('payment_method',$paymentMethod)
        ->selectRaw('sum(volume) as total_volume')->first();

        if($query['total_volume'])
        {
            return $query['total_volume'];
        }

        return 0;
    }

    public function scopeGetTotalByEmp($query,$fuelType = null,$paymentMethod = null,$fromDate= null,$endDate = null)
    {
        
        $query = $query
        ->whereDate('trans_date', '>=', $fromDate)
        ->whereDate('trans_date', '<=', $endDate)
        ->WhereIn('fuel_type',$fuelType)
        ->WhereIn('payment_method',$paymentMethod)
        ->selectRaw('sum(amount) as total_amount')
        ->selectRaw('sum(volume) as total_volume')
        ->selectRaw('employee_id as emplyee')
        ->groupBy('employee_id')
        ->orderBY('employee_id')
        ->get();

        return [
                    'emp' => $query->pluck('emplyee'),
                    'total_amount' => $query->pluck('total_amount') ,
                    'total_volume' => $query->pluck('total_volume') ,
                    'count' => count($query)
                
                ];

    }

    public function scopeGetTotalByNozzle($query,$fuelType = null,$paymentMethod = null,$fromDate= null,$endDate = null)
    {
        
        $query = $query
        ->whereDate('trans_date', '>=', $fromDate)
        ->whereDate('trans_date', '<=', $endDate)
        ->WhereIn('fuel_type',$fuelType)
        ->WhereIn('payment_method',$paymentMethod)
        ->selectRaw('sum(amount) as total_amount')
        ->selectRaw('sum(volume) as total_volume')
        ->selectRaw('nozzle_id as nozzle')
        ->groupBy('nozzle_id')
        ->orderBY('nozzle_id')
        ->get();

        return [
                    'nozzle' => $query->pluck('nozzle'),
                    'total_amount' => $query->pluck('total_amount') ,
                    'total_volume' => $query->pluck('total_volume') ,
                    'count' => count($query)
                
                ];

    }


    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\SystemCode', 'payment_method','system_code_filter')
            ->where('sys_category_id',144)
            ->where('company_id','=', $this->company_id);
    }

    public function fuelType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'payment_method','system_code_filter')
            ->where('sys_category_id',70)
            ->where('company_id','=', $this->company_id);
    }

}
