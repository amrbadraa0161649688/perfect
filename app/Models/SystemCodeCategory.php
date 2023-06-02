<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemCodeCategory extends Model
{
    use HasFactory;

    protected $table='system_code_categories';

    protected $primaryKey='sys_category_id';

    protected $fillable = ['company_group_id', 'company_id', 'sys_category_name_ar',
        'sys_category_name_en', 'sys_category_app', 'sys_category_type', 'created_user', 'updated_user'];

    public function systemCodes(){
        return $this->hasMany('App\Models\SystemCode','sys_category_id');
    }
    public function company(){

        return $this->belongsTo('App\Models\Company' , 'company_id');

    }
    public function companyGroup(){

        return $this->belongsTo('App\Models\CompanyGroup' , 'company_group_id');

    }
}
