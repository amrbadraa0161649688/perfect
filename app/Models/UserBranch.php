<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    use HasFactory;

    protected $table = 'users_branches';
    protected $primaryKey = 'user_branch_id';

    protected $fillable = [
        'user_id',
        'company_id',
        'job_id', 'branch_id',
        'start_date',
        'end_date',
        'user_branch_is_defaul',
        'user_id_created',
        'user_id_updated',
        'start_time',
        'end_time'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function job()
    {
        return $this->belongsTo('App\Models\Job', 'job_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

//    public function setStartDateAttribute($value)
//    {
//        $this->attributes['start_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
//    }
//
//    public function setEndDateAttribute($value)
//    {
//        $this->attributes['end_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
//    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

}
