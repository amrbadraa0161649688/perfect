<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class UserMobile extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    protected $table = 'users_mobile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

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
        'customer_id',
        'parent_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password',
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

    public function getUserProfileUrlAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function getUserNameAttribute()
    {
        return $this['user_name_' . app()->getLocale()];
    }

    public function getIdAttribute()
    {
        return $this->user_id;
    }

    public function parent()
    {
        if ($this->user_type_id == 1) // customer
            return $this->belongsTo(Customer::class, 'parent_id', 'customer_id');

        return $this->belongsTo(User::class, 'parent_id', 'user_id');
    }
}
