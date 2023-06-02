<?php

namespace App\Models;

use App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'companies_depatments';
    protected $primaryKey = 'department_id';

    protected $fillable = ['company_group_id', 'company_id', 'department_name_ar', 'department_name_en',
        'department_code', 'created_user', 'updated_user'];

    public function divisionsJobs()
    {
        return $this->hasMany('App\Models\Division', 'department_id')
            ->whereJsonContains('company_id',request()->company_id)->with('jobsCompany');
    }

    public function divisions()
    {
        return $this->hasMany('App\Models\Division', 'department_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup','company_group_id');
    }


}
