<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewCompaniesTree extends Model
{
    use HasFactory;

    protected $table='View_companyies_tree';

    protected $fillable=['comp_g_id','company_group_ar','company_group_en','comp_id','company_name_ar',
        'company_name_en','str_id','str_code','str_type','department_id','department_code',
        'department_name_ar','department_name_en','div_dep','division_id','division_code','division_name_ar',
        'division_name_en','job_dep','job_dev','job_id','job_code','job_name_ar','job_name_en'];

    public function companies(){
        return $this->hasMany('App\Models\ViewCompaniesTree','comp_g_id','comp_id');
    }
}
