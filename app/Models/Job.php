<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'companies_jobs';

    protected $primaryKey = 'job_id';

    protected $fillable = ['division_id', 'department_id', 'company_group_id', 'company_id',
        'job_name_ar', 'job_name_en', 'job_code', 'created_user', 'updated_user', 'job_status'];

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    public function Division()
    {
        return $this->belongsTo('App\Models\Division', 'division_id');
    }

    public function permissions()
    {
        return $this->hasMany('App\Models\Permission', 'job_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }


}
