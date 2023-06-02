<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContract extends Model
{
    use HasFactory;

    protected $table = 'employees_contracts';

    protected $primaryKey = 'emp_contract_id';

    const CREATED_AT = 'created_date';

    const UPDATED_AT = 'updated_date';

  //  protected $dates=['emp_contract_end_date'];

    protected $fillable = [
        'emp_id',
        'emp_contract_type_id',
        'emp_contract_company_id',
        'emp_contract_job_id',
        'emp_contract_branch_id',
        'emp_contract_start_date',
        'emp_contract_end_date',
        'emp_contract_work_hours',
        'emp_contract_total_salary',
        'emp_contract_ticket_type',
        'emp_contract_notes',
        'created_date',
        'updated_date',
        'created_user',
        'updated_user',
        'emp_contract_manager_id',
        'emp_contract_is_active',

    ];


    public function contractType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'emp_contract_type_id');

    }

    public function company()
    {

        return $this->belongsTo('App\Models\Company', 'emp_contract_company_id');
    }

    public function branch()
    {

        return $this->belongsTo('App\Models\Branch', 'emp_contract_branch_id');

    }

    public function job()
    {

        return $this->belongsTo('App\Models\Job', 'emp_contract_job_id');

    }

    public function salaries()
    {

        return $this->hasMany('App\Models\EmployeeSalary', 'emp_contract_id');

    }

    public function getTotalSalaryAttribute()
    {
        return $this->salaries->sum(function (EmployeeSalary $salary) {
            return ($salary->emp_salary_credit - $salary->emp_salary_debit);
        });
    }

    public function getCreditSalaryAttribute()
    {
        return $this->salaries->sum(function (EmployeeSalary $salary) {
            return $salary->emp_salary_credit;
        });
    }

    public function getdepitSalaryAttribute()
    {
        return $this->salaries->sum(function (EmployeeSalary $salary) {
            return $salary->emp_salary_debit;
        });
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'emp_id');
    }
}
