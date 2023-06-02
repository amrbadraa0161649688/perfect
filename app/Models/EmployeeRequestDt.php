<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequestDt extends Model
{
    use HasFactory;

    protected $table = 'employees_request_dt';
    protected $primaryKey = 'emp_request_dt_id';

    public $timestamps = false;


    protected $fillable = [
        'emp_request_id', 'company_group_id', 'company_id', 'emp_request_type_id', 'emp_id', 'item_id', 'item_name_ar', 'item_name_en',
        'item_qunt', 'item_value', 'item_date', 'item_start_date', 'item_end_date', 'item_status', 'item_notes', 'item_relation',
        'item_category', 'item_type', 'item_excellent', 'item_very_good', 'item_good', 'item_middle', 'item_weak', 'item_result',
        'item_evaluation', 'item_reasons', 'sponsor_id_1', 'sponsor_id_2', 'manager_notes', 'hr_notes', 'ceo_notes', 'item_period_id',
        'item_value_1', 'item_value_2', 'item_value_3', 'item_loc_id', 'item_loc_name', 'item_emp_division', 'item_emp_job',
        'item_emp_certificate', 'item_recommendation', 'item_recommendation_hr'
    ];


    public function itemEvaluation()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_evaluation');
    }

    //////////////طلب اخلاء الطرف
    public function itemLeaveWork()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_id');
    }

    public function getItemStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function itemType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_type');
    }

    public function itemReasons()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_reasons');
    }

    public function getItemEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }


    public function sponsor1()
    {
        return $this->belongsTo('App\Models\Employee', 'sponsor_id_1');
    }


    public function sponsor2()
    {
        return $this->belongsTo('App\Models\Employee', 'sponsor_id_2');
    }


    public function status()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_status');
    }


    public function category()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_category');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_type');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\SystemCode', 'item_id');
    }

}
