<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';


    protected $primaryKey = 'emp_id';

    protected $dates = ['emp_last_vacation_start', 'emp_work_start_date'];

    protected $appends = ['basicSalary', 'housingSalary', 'transportSalary',
        'foodSalary', 'naturalSalary', 'allowanceSalary', 'otherSalary',
        'employeeSalaryAdds', 'employeeSubSolaf', 'employeeSubTawedat', 'employeeSubCut',
        'insuranceSalary', 'loansSalary', 'deductsSalary', 'employeeSalarySubs'];


    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Models\SystemCode', 'emp_category');
    }

    public function truck()
    {
        return $this->hasOne('App\Models\Trucks', 'truck_driver_id');
    }


    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'emp_id');
    }

    public function allRequests()
    {
        return $this->hasMany('App\Models\EmployeeRequest', 'emp_id');
    }

    public function getLastPanelActionDateAttribute()
    {
        $query = EmployeeRequestDt::where('emp_id', $this->emp_id)->where('emp_request_type_id',
            SystemCode::where('system_code', 46009)->first()->system_code_id)->first();
        return isset($query) ? $query->item_start_date : 0;
    }

    public function getTotalAncestorsAttribute()
    {
///////////////اجمالي السلفه بالراتب
        $company = session('company') ? session('company') : auth()->user()->company;
        $query = EmployeeRequestDt::where('emp_id', $this->emp_id)
            ->where('emp_request_type_id', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 46006)->first()->system_code_id)->get();
        return isset($query) ? $query->sum('item_value') : 0;

    }

    ///////////////اجمالي الغياب خصومات شهريه 27

    public function employeeVariableCut()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        return $this->hasMany('App\Models\EmployeeVariableDt', 'emp_id')->where('acc_period_id', request()->acc_period_id)
            ->where('emp_variables_type', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 2701)->first()->system_code_id);
    }


    public function getEmployeeSubCutAttribute()
    {

        return $this->employeeVariableCut->sum('emp_variables_debit');

    }


///////////////اجمالي السلفه خصومات شهريه 28

    public function employeeVariableSolaf()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        return $this->hasMany('App\Models\EmployeeVariableDt', 'emp_id')->where('acc_period_id', request()->acc_period_id)
            ->where('emp_variables_type', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 2801)->first()->system_code_id);
    }


    public function getEmployeeSubSolafAttribute()
    {

        return $this->employeeVariableSolaf->sum('emp_variables_debit');

    }


///////////////اجمالي تعويضات خصومات شهريه 29
    public function employeeVariableTawedat()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        return $this->hasMany('App\Models\EmployeeVariableDt', 'emp_id')->where('acc_period_id', request()->acc_period_id)
            ->where('emp_variables_type', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 2901)->first()->system_code_id);
    }

    public function getEmployeeSubTawedatAttribute()
    {

        return $this->employeeVariableTawedat->sum('emp_variables_debit');

    }


////////////////////////////////////////////////

    public function socialStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'emp_social_status');
    }


    public function nationality()
    {
        return $this->belongsTo('App\Models\SystemCode', 'emp_nationality');
    }

//    public function getEmpWorkStartDateAttribute($value)
//    {
//        return Carbon::parse($value)->format('Y-m-d')
//    }
//    public function getEmpLastVacationStartAttribute($value)
//    {
//        return Carbon::parse($value)->format('Y-m-d')
//    }

    public function manager()
    {
        return $this->belongsTo('App\Models\Employee', 'emp_manager_id');
    }

    public function pendingVacations()
    {
        return $this->hasMany('App\Models\EmployeeRequest', 'emp_id')
            ->where('emp_request_type_id', SystemCode::where('system_code', 503)->where('company_group_id', $this->company_group_id)
                ->first()->system_code_id)->where('emp_request_approved', 1)
            ->where('request_id', null);
    }

    public function contracts()
    {
        return $this->hasMany('App\Models\EmployeeContract', 'emp_id');

    }

    public function bank()
    {

        return $this->belongsTo('App\Models\SystemCode', 'emp_bank_id');
    }

    public function company()
    {

        return $this->belongsTo('App\Models\Company', 'emp_default_company_id');

    }

    public function branch()
    {

        return $this->belongsTo('App\Models\Branch', 'emp_default_branch_id');

    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'emp_status')->withDefault(23);

    }

    public function emp_job()
    {

        return $this->belongsTo('App\Models\SystemCode', 'emp_job_in_identity');

    }

    public function getEmpPhotoUrlAttribute($value)
    {
        return asset($value);
    }

    public function certificates()
    {

        return $this->hasMany('App\Models\EmployeeCertificate', 'emp_id');

    }

    public function experience()
    {

        return $this->hasMany('App\Models\EmployeeExperience', 'emp_id');
    }

    public function activeContract()
    {
        return $this->hasMany('App\Models\EmployeeContract', 'emp_id')
            ->where('emp_contract_is_active', 1);
    }

    public function contractActive()
    {
        return $this->hasOne('App\Models\EmployeeContract', 'emp_id')
            ->where('emp_contract_is_active', 1);
    }

    public function salaries()
    {
        return $this->hasMany('App\Models\EmployeeSalary', 'emp_id');
    }

    public function salariesActive()
    {


        return $this->hasMany('App\Models\EmployeeSalary', 'emp_id')
            ->where('emp_salary_is_active', 1);
        // return $this->hasMany('App\Models\EmployeeSalary', 'emp_id');
    }

    public function getTotalSalaryAttribute()
    {
        return $this->salariesActive->sum(function (EmployeeSalary $salary) {
            return ($salary->emp_salary_credit - $salary->emp_salary_debit);
        });
    }

//
    public function getBasicSalaryAttribute()
    {
        //49 id of salary type basic from system code table
        if ($this->salariesActive->where('emp_salary_item_id', 49)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 49)->first()->emp_salary_credit;

        } else {
            return 0;
        }

    }

//
    public function getCreditSalaryAttribute()
    {
//         49 id of salary type basic from system code table
        return $this->salariesActive->where('emp_salary_item_id', '!=', 49)->sum('emp_salary_credit');
    }

//
    public function getDebitSalaryAttribute()
    {
//         49 id of salary type basic from system code table
        return $this->salariesActive->where('emp_salary_item_id', '!=', 49)->sum('emp_salary_debit');

    }


    public function employeeVariableDt()
    {
        return $this->hasMany('App\Models\EmployeeVariableDt', 'emp_id');
    }

    public function employeeVariableDtAdds()
    {
        return $this->hasMany('App\Models\EmployeeVariableDt', 'emp_id')->where('acc_period_id', request()->acc_period_id)
            ->where('emp_variables_main_type', 1);
    }


    public function employeeVariableDtSubs()
    {
        return $this->hasMany('App\Models\EmployeeVariableDt', 'emp_id')->where('acc_period_id', request()->acc_period_id)
            ->where('emp_variables_main_type', 2);
    }


//    تامينات اجتماعيه
    public function getInsuranceSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 56)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 56)->first()->emp_salary_debit;

        } else {
            return 0;
        }

    }


//    القروض

    public function getLoansSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 57)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 57)->first()->emp_salary_debit;

        } else {
            return 0;
        }

    }


//    السلف
    public function getDeductsSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 58)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 58)->first()->emp_salary_debit;

        } else {
            return 0;
        }

    }


    //    بدل سكن
    public function getHousingSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 50)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 50)->first()->emp_salary_credit;

        } else {
            return 0;
        }

    }


    //    بدل نقل
    public function getTransportSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 51)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 51)->first()->emp_salary_credit;

        } else {
            return 0;
        }

    }


    //    بدل طعام
    public function getFoodSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 52)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 52)->first()->emp_salary_credit;

        } else {
            return 0;
        }

    }

    //    بدل طبيعه عمل
    public function getNaturalSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 53)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 53)->first()->emp_salary_credit;

        } else {
            return 0;
        }

    }


//    علاوه دوريه

    public function getAllowanceSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 54)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 54)->first()->emp_salary_credit;

        } else {
            return 0;
        }

    }

//    بدلات أخري

    public function getOtherSalaryAttribute()
    {
        if ($this->salariesActive->where('emp_salary_item_id', 55)->first()) {

            return $this->salariesActive->where('emp_salary_item_id', 55)->first()->emp_salary_credit;

        } else {
            return 0;
        }

    }

//    الاضافات الشهريه

    public function getEmployeeSalaryAddsAttribute()
    {

        return $this->employeeVariableDtAdds->sum('emp_variables_credit');

    }


    //    الخصومات الشهريه

    public function getEmployeeSalarySubsAttribute()
    {

        return $this->employeeVariableDtSubs->sum('emp_variables_debit');

    }

    public function Report_contract_emp_sa()
    {
        return $this->belongsTo('App\Models\Reports', 'company_group_id', 'company_group_id')->where('report_code', '80001');
    }

    public function Report_contract_emp()
    {
        return $this->belongsTo('App\Models\Reports', 'company_group_id', 'company_group_id')->where('report_code', '80002');
    }

    public function report_url_info()
    {
        return $this->belongsTo('App\Models\Reports', 'company_group_id', 'company_group_id')->where('report_code', '95010');
    }

    public function getNameAttribute()
    {
        return $this['emp_name_full_' . app()->getLocale()];
    }

}
