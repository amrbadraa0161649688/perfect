<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeVariableHd extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_variables_id';

    protected $table = 'employees_variables_hd';

    protected $fillable = ['company_group_id', 'company_id', 'acc_period_id',
        'emp_variables_date', 'emp_variables_main_type', 'created_user', 'updated_user'];

        
    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_user');
    }

    public function AccountPeriod()
    {
        return $this->belongsTo('App\Models\AccounPeriod', 'acc_period_id');
    }

    public function employeeVariableDetails()
    {
        return $this->hasMany('App\Models\EmployeeVariableDt', 'emp_variables_id');
    }

    public function getTotalValueAttribute()
    {

        return $this->employeeVariableDetails->sum(function (EmployeeVariableDt $variable_detail) {

            return ($variable_detail->emp_variables_credit - $variable_detail->emp_variables_debit);

        });

    }

    public function report_url_var_d()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '80012');
    }

    public function report_url_var_c()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '80011');
    }
}
