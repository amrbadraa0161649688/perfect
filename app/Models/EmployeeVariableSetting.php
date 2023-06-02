<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeVariableSetting extends Model
{
    use HasFactory;

    protected $table = 'employees_variables_setting';

    protected $primaryKey = 'emp_variables_type_id';

    protected $fillable = ['company_group_id', 'company_id', 'emp_variables_type_code',
        'emp_variables_salary_type', 'emp_variables_method', 'emp_variables_factor',
        'emp_variables_main_type'];


    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function systemCodeType(){
        return $this->belongsTo('App\Models\SystemCode','emp_variables_type_code');
    }

    public function systemCodeSalaryType(){
        return $this->belongsTo('App\Models\SystemCode','emp_variables_salary_type');
    }

    public function systemCodeMethod(){
        return $this->belongsTo('App\Models\SystemCode','emp_variables_method');
    }
}
