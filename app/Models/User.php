<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_id',
        'company_group_id',
        'company_id',
        'user_code',
        'user_password',
        'user_email',
        'user_name_ar',
        'user_name_en',
        'user_profile_url',
        'user_default_branch_id',
        'user_status_id',
        'user_token',
        'user_start_date',
        'user_end_date',
        'user_mobile',
        'user_otp',
        'user_type_id'
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

    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     * @return \App\Models\User
     */
    public function findForPassport($username)
    {
        return $this->where('user_mobile', $username)->first();
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param string $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->user_password);
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'notification_user_id')
            ->where('notification_status', 0);
    }

    public function allNotifications()
    {
        return $this->hasMany('App\Models\Notification', 'notification_user_id');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function defaultBranch()
    {
        return $this->belongsTo('App\Models\Branch', 'user_default_branch_id');
    }

    public function branches()
    {
        return $this->belongsToMany('App\Models\Branch', 'users_branches', 'user_id', 'branch_id');
    }

    public function getUserStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getUserEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
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
        return $this->belongsToMany('App\Models\Job', 'users_branches', 'user_id', 'job_id')->withPivot('branch_id')->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
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

    public function getUserNameAttribute()
    {
        return $this['user_name_' . app()->getLocale()];
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id', 'user_id');
    }

    public function chields()
    {
        return $this->hasMany(User::class, 'user_id', 'parent_id');
    }

    public function additionRols()
    {
        return $this->hasMany('App\Models\UsersPermissionsRol', 'user_id');
    }

}
