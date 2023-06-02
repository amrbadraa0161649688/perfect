<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyGroup extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_group_id';

    protected $table = 'company_group';

    protected $fillable = ['company_group_ar', 'company_group_en', 'company_group_logo',
        'commercial_register', 'tax_number', 'postal_code', 'postal_box', 'responsible_person',
        'mobile_number', 'phone_no', 'main_email', 'main_address', 'companys_number', 'c_group_is_active',
        'open_date', 'end_date', 'created_user', 'updated_user', 'accounts_levels_number'];

//    public function accountsMain()
//    {
//        return $this->belongsToMany('App\Models\Account', 'accounts_company', 'company_group_id', 'acc_code')
//            ->wherePivot('acc_level', 1);
//    }

    public function accountsMain()
    {
        return $this->hasMany('App\Models\Account', 'company_group_id')->where('acc_level', 1);
    }

    public function companies()
    {
        return $this->hasMany('App\Models\Company', 'company_group_id');
    }

    public function report_url_tree()
    {
        return $this->belongsTo('App\Models\Reports', 'company_group_id', 'company_group_id')->where('report_code', '18001');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User', 'company_group_id');
    }

    public function getCompanyGroupLogoAttribute($value)
    {
        return asset($value);
    }

    public function getOpenDateAttribute($value)
    {
//        return $value;
      return Carbon::parse($value)->format('Y-m-d');
    }

    public function getEndDateAttribute($value)
    {
//        return $value;
     return Carbon::parse($value)->format('Y-m-d');
    }


    public function setOpenDateAttribute($value)
    {
        $this->attributes['open_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d H:i');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d H:i');
    }

    public function departments()
    {
        return $this->hasMany('App\Models\Department', 'company_group_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job', 'company_group_id');
    }

    public function apps()
    {
        return $this->belongsToMany('App\Models\Application', 'companies_app', 'company_group_id', 'app_id')->withPivot('company_app_id', 'co_app_is_active');
    }


    public function branches()
    {
        return $this->hasMany('App\Models\Branch', 'company_group_id');
    }

    public function employees()
    {
        return $this->hasMany('App\Models\Employee', 'company_group_id');
    }
}
