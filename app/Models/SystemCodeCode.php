<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemCodeCode extends Model
{
    use HasFactory;

    protected $table = 'system_codes';

    protected $primaryKey = 'system_code';

    protected $fillable = ['sys_category_id', 'company_group_id', 'company_id', 'system_code_name_ar',
        'system_code_name_en', 'system_code', 'system_code_search', 'system_code_filter',
        'system_code_acc_id', 'system_code_tax_perc', 'system_code_url', 'system_code_posted',
        'system_code_url', 'system_code_status', 'system_code_emp_id', 'created_user', 'updated_user'];

    public function systemCodeCategory()
    {
        return $this->belongsTo('App\Models\SystemCodeCategory', 'sys_category_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function getSystemCodeUrlAttribute($value)
    {
        return asset($value);
    }


    public function getSysCodeName(){
    	if(app()->getLocale() == 'ar')
            return $this->system_code_name_ar;
        return $this->system_code_name_en;
	}

}
