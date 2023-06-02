<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeExperience extends Model
{
    use HasFactory;
    protected $table = 'employees_experience';
    protected $primaryKey = 'emp_experience_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable=[

        'emp_id',
        'emp_experience_job',/////
        'emp_experience_company',////
        'emp_experience_country',////
        'emp_experience_period',////
        'emp_experience_salary',////
        'emp_experience_leave_reason',
        'emp_experience_start_date',/////
        'emp_experience_end_date',/////
        'emp_experience_file_url',
        'created_date',
        'updated_date',
        'created_user',
        'updated_user',

    ];

    public function employee()
    {

        return $this->belongsTo('App\Models\Employee', 'emp_id');

    }

    public function sys_code_country()
    {

        return $this->belongsTo('App\models\SystemCode', 'emp_experience_country');

    }

}
