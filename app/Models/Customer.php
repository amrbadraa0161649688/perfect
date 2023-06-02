<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

//customer_category => 3 =>car rent agent
    use HasFactory;

    protected $table = 'customers';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $primaryKey = 'customer_id';
    protected $appends = ['active_attachment', 'check_credit_limit'];

    protected $fillable = [
        'company_group_id',
        'customer_name_1_ar', 'customer_name_2_ar', 'customer_name_3_ar', 'customer_name_4_ar',
        'customer_name_1_en', 'customer_name_2_en', 'customer_name_3_en', 'customer_name_4_en',
        'customer_name_full_ar', 'customer_name_full_en',
        'customer_identity',
        'customer_nationality',
        'customer_type',
        'customer_category',
        'customer_birthday',
        'customer_birthday_hijiri',
        'customer_company',
        'customer_job',
        'customer_email',
        'customer_phone',
        'customer_mobile',
        'customer_mobile_code',
        'customer_address_1',
        'customer_address_2',
        'customer_address_en',
        'customer_vat_no',
        'customer_credit_limit',
        'customer_status',
        'customer_gender',
        'customer_ref_no',
        'customer_phone_home',
        'customer_vat_rate',
        'customer_classification',
//        'customer_identity_type',
        'id_type_code',
        'customer_account_id',
        'created_date', 'updated_date', 'created_user', 'updated_user',
        'customer_photo', 'postal_box', 'postal_code', 'build_no', 'unit_no',
        'customer_addition_rate',
        'customer_discount_rate',
    ];

    public function contracts()
    {
        return $this->hasMany('App\Models\CarRentContract', 'customer_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\InvoiceHd', 'customer_id');
    }

    public function getActivePriceListAttribute()
    {
        $car_price_list_hd = CarPriceListHd::where('customer_id', $this->customer_id)
            ->where('rent_list_status', '=', 1)
            ->where('rent_list_start_date', '<', Carbon::now())
            ->where('rent_list_end_date', '>', Carbon::now())
            ->latest()->first();

        if (isset($car_price_list_hd)) {
            return $car_price_list_hd;
        } else {
            return '';
        }
    }

    public function activePriceList()
    {
        return $this->hasOne(CarPriceListHd::class, 'customer_id', 'customer_id')->where('rent_list_status', '=', 1)
            ->where('rent_list_start_date', '<', Carbon::now())
            ->where('rent_list_end_date', '>', Carbon::now())
            ->latest();
    }

    public function getActiveAttachmentAttribute()
    {
        return Attachment::where('transaction_id', $this->customer_id)
            ->whereIn('attachment_type', [11001,11003])
            ->where('expire_date', '>', Carbon::now())
            ->count();
    }

    public function getCheckCreditLimitAttribute()
    {
        $balance = CarRentContract::where('customer_id', $this->customer_id)->sum('contract_balance');
        return $balance >= $this->customer_credit_limit ? 1 : 0;
    }

    public function getAgeAttribute()
    {
        if ($this->customer_birthday) {
            $from_date = Carbon::createFromFormat('Y-m-d', $this->customer_birthday);

            $end_date = Carbon::now();

            $age = $end_date->diff($from_date);

            return $age->y;
        }
    }

    public function carPriceListHd()
    {
        return $this->hasMany('App\Models\CarPriceListHd', 'customer_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'customer_account_id');
    }


    public function account_id()
    {
        return $this->belongsTo('App\Models\Account', 'customer_account_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function cus_type()
    {

        return $this->belongsTo('App\Models\SystemCode', 'customer_type');

    }

    public function classifications()
    {

        return $this->belongsTo('App\Models\SystemCode', 'customer_classification');

    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'customer_status');

    }


    public function customerBlock()
    {
        return $this->hasOne('App\Models\CustomersBlock', 'customer_id');
    }

    public function TypeCode()
    {
        return $this->belongsTo('App\Models\SystemCode', 'id_type_code','system_code_filter')
            ->where('sys_category_id', 66)->where('company_group_id', $this->company_group_id);
    }

    public function getEmpPhotoUrlAttribute($value)
    {
        return asset($value);
    }

    public function getCustomerName()
    {
        if (app()->getLocale() == 'ar')
            return $this->customer_name_full_ar;
        return $this->customer_name_full_en;
    }

    public function getNameAttribute()
    {
        return $this['customer_name_full_' . app()->getLocale()];
    }

    public function cusNationality()
    {
        return $this->belongsTo('App\Models\SystemCode', 'customer_nationality');
    }

}
