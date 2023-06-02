<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $primaryKey = 'division_id';

    protected $table = 'companies_divisions';

    protected $fillable = ['department_id', 'company_group_id', 'company_id',
        'division_name_ar', 'division_name_en', 'division_code', 'updated_user',
        'created_user', 'division_status'];

    public function jobsCompany()
{
    return $this->hasMany('App\Models\Job', 'division_id')->whereJsonContains('company_id',request()->company_id);
}

    public function jobs()
    {
        return $this->hasMany('App\Models\Job', 'division_id');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }
}
