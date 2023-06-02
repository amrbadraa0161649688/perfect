<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaybillDt extends Model
{
    use HasFactory;

    protected $table = 'waybill_dt';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $primaryKey = 'waybill_dt_id';

    protected $fillable = [
        'waybill_hd_id',
        'company_group_id',
        'company_id',
        'branch_id',
        'waybill_distance',

        //   العميل
        'waybill_item_id',
        'waybill_item_unit',
        'waybill_item_quantity',
        'waybill_item_price',
        'waybill_item_amount',
        'waybill_add_amount',
        'waybill_item_vat_rate',
        'waybill_item_vat_amount',
        'waybill_discount_type',
        'waybill_discount_amount',
        'waybill_discount_total',
        'waybill_total_amount',
        'waybill_qut_requried_customer',
        'waybill_qut_received_customer',

        'waybill_price_supplier',
        'waybill_qut_requried_supplier',
        'waybill_qut_received_supplier',
        'waybill_vat_amount_supplier',
        'waybill_amount_supplier',

        'waybill_fees_difference',
        'waybill_fees_wait',
        'waybill_fees_load',
        'waybill_item_qut_difference',
        'waybill_goods_value',
        'waybill_insurance_status',
        'waybill_insurance_value',

        'waybill_car_desc',
        'waybill_car_chase',
        'waybill_car_plate',
        'waybill_car_model',
        'waybill_car_color',
        'waybill_car_owner',
        'waybill_car_notes',

        'created_date', 'updated_date',
        'created_user', 'updated_user'];


    public function waybillActive()
    {
        // return $this->hasOne('App\Models\WaybillHd', 'waybill_id')
        //     ->where('emp_contract_is_active', 1);
        return $this->belongsTo('App\Models\WaybillHd', 'waybill_hd_id', 'waybill_id')->where('waybill_status', '480');
    }

    public function waybill()
    {
        return $this->belongsTo('App\Models\WaybillHd' ,'waybill_id');
    }

    public function waybillhd()
    {
        return $this->belongsTo('App\Models\WaybillHd','waybill_hd_id' );
    }

    public function waybillh()
    {
        return $this->hasOne('App\Models\WaybillHd','waybill_hd_id' );
    }

    public function carModel()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_car_model');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_item_id');
    }

    public function itemcarwaybil()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_item_id')->where('sys_category_id', 64);
    }

    public function itemUnit()
    {
        return $this->belongsTo('App\Models\SystemCode', 'waybill_item_unit');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }


    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_user');
    }


}
