<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesMonthlyPayroll extends Model
{
    use HasFactory;

    protected $table = 'employees_monthly_payroll';

    protected $primaryKey = 'monthly_payroll_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    public $timestamps = false;


    protected $fillable = ['company_group_id','monthly_payroll_id',

        'company_id', 'emp_id', 'period_id', 'period_month', 'period_year', 'emp_name_full_ar',

        'emp_name_full_en', 'emp_code', 'emp_identity', 'emp_status', 'emp_status_name_ar',

        'emp_status_name_en', 'emp_direct_date', 'emp_department_id', 'emp_department_name_ar',

        'emp_department_name_en', 'emp_division_id', 'emp_division_name_ar', 'emp_division_name_en',

        'emp_job_id', 'emp_job_name_ar', 'emp_job_name_en', 'emp_branch_id', 'emp_branch_name_ar', 'emp_branch_name_en',

        'emp_bank_id', 'emp_bank_code', 'emp_bank_name_ar', 'emp_bank_name_en', 'emp_is_bank_payment',

        'emp_main_salary', 'emp_housing_salary', 'emp_transportation_salary', 'emp_food_salary', 'emp_nature_work_salary',

        'emp_allowance_salary', 'emp_others_salary', 'emp_add_monthly_salary', 'emp_due_salary', 'emp_insurance_salary',

        'emp_loans_salary', 'emp_deducts_salary', 'emp_deducts_monthly_salary', 'emp_deducts_total', 'emp_net_salary',

        'created_user',


    ];
}
