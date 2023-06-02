<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = 'company_id';

    protected $table = 'companies';

    protected $guarded = [];

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function apps()
    {
        return $this->belongsToMany('App\Models\Application', 'companies_app', 'company_id', 'app_id')->withPivot('company_app_id', 'co_app_is_active');
    }

    public function appsActive()
    {
        return $this->belongsToMany('App\Models\Application', 'companies_app', 'company_id', 'app_id')->wherePivot('co_app_is_active', 1);
    }

    public function getCompanyLogoAttribute($value)
    {
        return asset($value);
    }

    public function systemCodeCategories()
    {
        return $this->hasMany('App\Models\SystemCodeCategory', 'company_id');
    }

    public function departments()
    {
        return $this->belongsToMany('App\Models\Department', 'companies_details_str', 'company_id', 'str_code')->where('str_type', 'department')->with('divisionsJobs');
    }

    public function divisions()
    {
        return $this->belongsToMany('App\Models\Division', 'companies_details_str', 'company_id', 'str_code')->where('str_type', 'division');
    }

    public function jobs()
    {
        return $this->belongsToMany('App\Models\Job', 'companies_details_str', 'company_id', 'str_code')->where('str_type', 'job');
    }

    public function getCoOpenDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getCoEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function setCoOpenDateAttribute($value)
    {
        $this->attributes['co_open_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
    }

    public function setCoEndDateAttribute($value)
    {
        $this->attributes['co_end_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
    }

    public function accountsMain()
    {
        return $this->belongsToMany('App\Models\Account', 'accounts_company', 'company_id', 'acc_code')
            ->wherePivot('acc_level', 1);
    }

    public function accounts()
    {
        return $this->belongsToMany('App\Models\Account', 'accounts_company',
            'company_id', 'acc_code')->withPivot('acc_level');
    }

    public function branches()
    {
        return $this->hasMany('App\Models\Branch', 'company_id');
    }

    public function getCompanyName()
    {
        if (app()->getLocale() == 'ar')
            return $this->company_name_ar;
        return $this->company_name_en;
    }

    public function report_url_all()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73005');
    }

    public function report_url_inv_acc()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73021');
    }

    public function report_url_inv_all()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73022');
    }

    public function report_url_journal_all()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '33002');
    }


    public function getNameAttribute()
    {
        return $this['company_name_' . app()->getLocale()];
    }


}
