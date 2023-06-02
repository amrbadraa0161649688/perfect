<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_id_salary';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $table = 'employees_salary';

    protected $fillable = ['emp_id', 'emp_contract_id', 'emp_salary_item_id',
        'emp_salary_debit', 'emp_salary_credit', 'emp_contract_start', 'emp_contract_end',
        'emp_salary_notes', 'emp_salary_is_active', 'created_user', 'updated_user'];


    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'emp_id');
    }

    public function salaryItem()
    {
        return $this->belongsTo('App\Models\SystemCode', 'emp_salary_item_id');
    }

    public function salaryItem2()
    {
        $item = SystemCode::where('system_code', 'emp_salary_item_id')
            ->where('company_group_id', $this->employee->company_group_id)
            ->first();
        return $item;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }
}
