<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class UserMobile extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $table = 'user_mobile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_group_id',
        'user_identity',
        'user_nationality',
        'user_name_ar',
        'user_name_en',
        'user_mobile',
        'user_code',
        'user_password',
        'user_email',
        'user_profile_url',
        'user_status_id',
        'user_token',
        'user_otp',
        'user_type_id',
        'user_mobile_type',
        'user_address',
        'user_start_date',
        'user_end_date',
        'user_last_login',
        'user_balance',
        'customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function notifications()
    {
        return $this->hasMany(Notification::class, 'notification_user_id')
            ->where('notification_status', 0);
    }

    public function allNotifications()
    {
        return $this->hasMany(Notification::class, 'notification_user_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo(CompanyGroup::class, 'company_group_id');
    }

    public function defaultBranch()
    {
        return $this->belongsTo(Branch::class, 'user_default_branch_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'users_branches', 'user_id', 'branch_id');
    }

    public function getUserStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getUserEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

//    public function setUserStartDateAttribute($value)
//    {
//        $this->attributes['user_start_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
//    }
//
//    public function setUserEndDateAttribute($value)
//    {
//        $this->attributes['user_end_date'] = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
//    }

    public function getUserProfileUrlAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'users_branches', 'user_id', 'job_id')
            ->withPivot('branch_id')->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function getUserName()
    {
        if (app()->getLocale() == 'ar')
            return $this->user_name_ar;
        return $this->user_name_en;
    }

}
