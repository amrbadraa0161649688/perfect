<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentContract extends Model
{
    use HasFactory;
    protected $table = 'car_rent_contract';
    protected $primaryKey = 'contract_id';
    protected $guarded = [];


    protected $appends = array('extra_hours', 'actual_days_count', 'days_count2', 'total_hr_count');


    public function CarRentContractRequest()
    {
        return $this->hasMany('App\Models\CarRentContractRequest', 'contract_id');
    }

    public function invoiceDts()
    {
        return $this->hasMany('App\Models\InvoiceDt', 'invoice_reference_no');
    }

    public function getInvoiceFromDateAttribute()
    {
        $last_invoice_dt = InvoiceDt::where('invoice_reference_no', $this->contract_id)->latest()->first();

        if (isset($last_invoice_dt)) {
            $date = $last_invoice_dt->invoice_to_date->addDays(1);
            return Carbon::parse($date)->format('Y-m-d');
        } else {
            return $this->contractStartDateDate;
        }
    }

    public function getDaysCount2Attribute(): int
    {
        return Carbon::parse($this->contractStartDateDate)->diffInDays(Carbon::parse($this->contractEndDateDate));
    }

    public function getExtraHoursAttribute()
    {
        $from_date = Carbon::parse($this->contractStartDate);
        $to_date = Carbon::now();

        $diff = $to_date->diff($from_date);
        return $diff->h;
    }

    public function getActualDaysCountAttribute()
    {
        $days_count = Carbon::now()->diffInDays($this->contractStartDateDate);
        $d = $this->extra_hours - $this->allowedLateHours;
        if ($days_count == 0) {
            return 1;
        } else {
//            if ($d > 0) {
                if ($d >= $this->allow_hr_to_day) {
                    return $days_count + 1;
                } else {
                    return $days_count;
                }
//            }
        }
    }

    public function getTotalHrCountAttribute()
    {
        $d = $this->extra_hours - $this->allowedLateHours;

        if ($d > 0) {
            if ($d >= $this->allow_hr_to_day) {
                $this->actual_days_count = $this->actual_days_count + 1;
                return 0; ////يزيد  يوم علي عدد ايام العقد
            } else {
                return $d; //////////يتحسب اجالي تكلفه الساعات الزياده
            }

        } else {
            return 0;
        }

    }


    public function getTotalDailyCostAttribute()
    {
        return $this->ActualDaysCount * $this->rentDayCost;
    }

    public function getNetActualCostAttribute()
    {
//        $total = $this->TotalDailyCost + $this->contract_vat_amout
//            + $this->contract_total_add + $this->total_km_cost - $this->contract_total_discount;
        $subCoust = $this->TotalDailyCost + $this->total_km_cost + $this->total_hour_cost;
        $vat_amoute = ($subCoust - $this->contract_total_discount) * ($this->contract_vat_rate / 100);
        $total = $subCoust - $this->contract_total_discount + $vat_amoute + $this->contract_total_add;
        $totel_rounded = round($total, 2);
        return $totel_rounded;
    }

    public function getTotalDueAttribute()
    {
        $total_due = $this->net_actual_cost - $this->paid;
        return $total_due;
    }


    public function getContractEndDateDateAttribute()
    {
//        return Carbon::parse($value)->format('Y-m-d\TH:i');
        return Carbon::parse($this->contractEndDate)->format('Y-m-d');
    }

    public function getContractClosedDateDateAttribute()
    {
//        return Carbon::parse($value)->format('Y-m-d\TH:i');
        return Carbon::parse($this->closed_datetime)->format('Y-m-d');
    }

    public function getContractClosedDateTimeAttribute()
    {
//        return Carbon::parse($value)->format('Y-m-d\TH:i');
        return Carbon::parse($this->closed_datetime)->format('H:i');
    }

    public function getContractStartDateDateAttribute()
    {
//        return Carbon::parse($value)->format('Y-m-d\TH:i');
        return Carbon::parse($this->contractStartDate)->format('Y-m-d');
    }

    public function getContractEndDateTimeAttribute()
    {
        return Carbon::parse($this->contractEndDate)->format('H:i');
    }


    public function getContractStartDateTimeAttribute()
    {
        return Carbon::parse($this->contractStartDate)->format('H:i');
    }

    public function customer()
    {

        return $this->belongsTo('App\Models\Customer', 'customer_id');

    }


    public function driver()
    {

        return $this->belongsTo('App\Models\Customer', 'driver_id');

    }


    public function user()
    {

        return $this->belongsTo('App\Models\User', 'created_user');

    }
    public function closer()
    {
        return $this->belongsTo('App\Models\User', 'closed_user');
    }

    public function branch()
    {

        return $this->belongsTo('App\Models\Branch', 'branch_id');

    }

    public function car()
    {

        return $this->belongsTo('App\Models\CarRentCars', 'car_id');

    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'contract_status');

    }

    public function contractType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'contractTypeCode');

    }

    public function contractTypeAPI()
    {

        return $this->belongsTo('App\Models\SystemCode', 'contractTypeCode','system_code');

    }



    public function getContractTypeAttribute()
    {

        return SystemCode::where('system_code', $this->contractTypeCode)
            ->where('company_id', $this->company_id)->first();

    }


    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\SystemCode', 'paymentMethodCode');
    }


    public function carAccidents()
    {
        return $this->hasMany('App\Models\CarRentAccident', 'contract_id');
    }


    public function carPriceListHd()
    {
        return $this->belongsTo('App\Models\CarPriceListHd', 'price_list_id', 'rent_list_id');
    }

    public function getStrinAsDate($att)
    {
        return (new Carbon($this->attributes[$att]))->toIso8601ZuluString();
    }

    public function getCHijriBirthDate()
    {
        return str_replace("-", "", $this->c_hijriBirthDate);
    }


}
