<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeVariableDt extends Model
{
    use HasFactory;

    protected $table='employees_variables_dt';

    protected $primaryKey='emp_variables_id_dt';
//     emp_variables_credit for addition to salary
//     emp_variables_debit for discount to salary
    protected $fillable = ['emp_variables_id', 'emp_id', 'emp_variables_type', 'emp_variables_hours',
        'emp_variables_minutes', 'emp_variables_days', 'emp_variables_salary', 'emp_variables_factor',
        'emp_variables_debit', 'emp_variables_credit', 'emp_variables_notes', 'emp_variables_main_type',
        'created_user', 'updated_user','acc_period_id'];

    public function employeeVariableSetting()
    {
        return $this->belongsTo('App\Models\EmployeeVariableSetting', 'emp_variables_id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'emp_id');
    }

    public function EmployeeVariableType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'emp_variables_type');
    }

    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_user');
    }
}
