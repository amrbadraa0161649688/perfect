<?php

namespace App\Models;

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaintenanceCard extends Model
{
    use HasFactory, CompanyTrait;
    protected $table = 'maintenance_cards_hd';
    protected $primaryKey = 'mntns_cards_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $guarded = [];

    public function salesInvoice()
    {
        return $this->hasMany('App\Models\Purchase', 'store_vou_ref_1');
    }


    public function getCardTotalVatFromInvAttribute()
    {
        return $total_vat = DB::table('store_hd')
            ->where('store_hd.store_vou_ref_1', $this->mntns_cards_id)
            ->sum('store_vou_vat_amount');

    }

    public function getCardTotalValFromInvAttribute()
    {
        return $total_net = DB::table('store_hd')
            ->where('store_hd.store_vou_ref_1', $this->mntns_cards_id)
            ->sum('store_vou_total');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function report_card_q_url()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '71001');
    }

    public function report_card_inv_url()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '71003');
    }

    public function report_card_w_url()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '71002');
    }

    public function customer()
    {

        return $this->belongsTo('App\Models\Customer', 'customer_id');

    }

    public function car()
    {

        return $this->belongsTo('App\Models\MaintenanceCar', 'mntns_cars_id');

    }

    public function truckname()
    {

        return $this->belongsTo('App\Models\Trucks', 'mntns_cars_id', 'truck_id');

    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'mntns_cards_status');

    }


    public function cardType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'mntns_cards_type');

    }

    public function cardCategory()
    {
        return $this->belongsTo('App\Models\SystemCode', 'mntns_cards_category');
    }

    public function details()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')->where('isdeleted', '=', 0);
    }

    public function detailsO()
    {
        return $this->hasOne('App\Models\MaintenanceCardDetails', 'mntns_cards_id')->where('isdeleted', '=', 0);
    }

    public function internalDetails()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')->where('mntns_cards_item_type', '=', 535)->where('isdeleted', '=', 0);
    }

    public function externalDetails()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 536)
            ->where('isdeleted', '=', 0);
    }

    public function partDetails()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 537)
            ->where('isdeleted', '=', 0);
    }

    public function internalSumTotal()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 535)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_amount');
    }

    public function internalSumVat()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 535)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_vat_amount');
    }

    public function internalSumDisc()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 535)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_disc_amount');
    }

    public function externalSumTotal()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 536)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_amount');
    }

    public function externalSumVat()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 536)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_vat_amount');
    }


    public function partSumTotal()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 537)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_amount');
    }

    public function partSumVat()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 537)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_vat_amount');
    }

    public function partSumDisc()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('mntns_cards_item_type', '=', 537)
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_disc_amount');
    }

    public function cardSumTotal()
    {
        return $this->hasMany('App\Models\MaintenanceCardDetails', 'mntns_cards_id')
            ->where('isdeleted', '=', 0)
            ->sum('mntns_cards_amount');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function asset()
    {
        return $this->belongsTo('App\Models\AssetsM', 'mntns_cars_id');
    }

}
