<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCertificate extends Model
{
    use HasFactory;
    protected $table = 'employees_certificate';
    protected $primaryKey = 'emp_certificate_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = [

        'emp_id',
        'emp_certificate_country',
        'emp_certificate_collage',
        'emp_certificate_type',
        'emp_certificate_duration',
        'emp_certificate_start_date',
        'emp_certificate_end_date',
        'emp_certificate_url',
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

        return $this->belongsTo('App\models\SystemCode', 'emp_certificate_country');

    }
}
