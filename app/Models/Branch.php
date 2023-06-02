<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $primaryKey = 'branch_id';

    protected $fillable = ['company_group_id', 'company_id', 'branch_name_ar', 'branch_name_en',
        'branch_address', 'branch_lat', 'branch_lng', 'branch_cr', 'branch_phone', 'branch_city_id',
        'branch_manager_id', 'branch_start_date', 'branch_end_date', 'branch_code', 'branch_cost_center_id',
        'created_user', 'updated_user'];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function getBranchStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getBranchEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function setBranchStartDateAttribute($value)
    {
        $this->attributes['branch_start_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
    }

    public function setBranchEndDateAttribute($value)
    {
        $this->attributes['branch_end_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
    }

    public function getBranchName()
    {
        if (app()->getLocale() == 'ar')
            return $this->branch_name_ar;
        return $this->branch_name_en;
    }

    public function getNameAttribute()
    {
        return $this['branch_name_' . app()->getLocale()];
    }

    public function FTrans()
    {
        return $this->hasMany('App\Models\StationInvoiceQR', 'branch_id');
    }

    // public function FTrans()
    // {
    //     return $this->hasMany('App\Models\FuelTransaction', 'branch_id');
    // }
}
