<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    use HasFactory;
    protected $table = 'employees_request';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $primaryKey = 'emp_request_id';

//    protected $dates = ['emp_request_end_date', 'emp_request_start_date'
//        , 'start_date', 'end_date'];

    protected $fillable = [

        'company_group_id', 'company_id', 'emp_request_type_id', 'emp_request_code',
        'emp_request_status', 'emp_id', 'emp_request_date', 'emp_request_notes', 'emp_request_days',
        'emp_request_start_date', 'emp_request_end_date', 'start_date', 'end_date',
        'emp_direct_date', 'sub_emp_id', 'vacation_type', 'vacation_phone', 'vacation_address',
        'vacation_days', 'vacatio_balance_day', 'emp_request_manager_id', 'emp_request_approved',
        'emp_request_hr_id', 'emp_request_hr_approver', 'emp_request_reason', 'emp_request_amount',
        'created_user', 'updated_user', 'request_id'

    ];


    ////طلب تسليم العهده
    public function handOverDetails()
    {
        return $this->hasMany('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

    /////////////اجراء جزائي
    public function panelActionDetails()
    {
        return $this->hasOne('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

//////////////اخلاء طرف
    public function jobLeaveDetails()
    {
        return $this->hasMany('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

    /////////////تكليف مهمه
    public function jobAssignmentDetails()
    {
        return $this->hasOne('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

//////////////توقف عن العمل
    public function stopWorkingDetails()
    {
        return $this->hasOne('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    ///////////////طلب السلفه
    public function ancestorsRequestDetails()
    {
        return $this->hasOne('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

    public function resignationDetails()
    {
        return $this->hasOne('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

    public function employeeEvaluation()
    {
        return $this->hasMany('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

    public function requestDetails()
    {
        return $this->hasMany('App\Models\EmployeeRequestDt', 'emp_request_id');
    }

    public function SetCategoryAttribute()
    {
        return $this->requestDetails->first()->category;
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'emp_id');
    }

    public function requestType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'emp_request_type_id');
    }

    public function employeeVacationRequest()
    {
        return $this->belongsTo('App\Models\EmployeeRequest', 'request_id');
    }

    public function getEmpDirectDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }


    public function getEmpRequestStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getEmpRequestEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
