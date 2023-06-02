<?php

namespace App\Models;

use App\Models\Traits\CompanyTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory, CompanyTrait;
    protected $table = 'store_hd';
    protected $primaryKey = 'store_hd_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const store_vou_date = 'store_vou_date';

    protected $guarded = [];

    public function getInvSalesUuidAttribute()
    {
        $inv = Purchase::where('store_hd_code', '=', $this->store_vou_ref_before)->first();
        return $inv->uuid;
    }

    public function Bond()
    {
        return $this->belongsTo('App\Models\Bond', 'bond_id');
    }

    public function journalHd()
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }


    public function journalHd2()
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id_1');
    }

    public function details()
    {
        return $this->hasMany('App\Models\PurchaseDetails', 'store_hd_id')->where('isdeleted', '=', 0);
    }

    public function detailsO()
    {
        return $this->hasOne('App\Models\PurchaseDetails', 'store_hd_id')->where('isdeleted', '=', 0);
    }

    public function paymentMethod()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_pay_type');

    }

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_status');

    }

    public function report_url_pr()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '62001');
    }

    public function report_url_pr_branch()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '62002');
    }

    public function report_url_pr_supp()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '62003');
    }

    public function report_url_po_ins()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '64002');
    }

    public function report_url_po()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '92001');
    }

    public function report_url_ins()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '64001');
    }

    public function report_url_q()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '93001');
    }

    public function report_url_inv()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '65001');
    }

    public function report_url_ins_r()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '66001');
    }

    public function report_url_inv_r()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '94001');
    }

    public function report_url_trans()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '67001');
    }


    public function storeVouType()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_type');

    }

    public function storeCategory()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_category_type');

    }

    public function vendor()
    {

        //  return $this->belongsTo('App\Models\Customer', 'store_acc_no')->where('customer_category', '=', 1);
        return $this->belongsTo('App\Models\Customer', 'store_acc_no');

    }

    public function customer()
    {

        //return $this->belongsTo('App\Models\Customer', 'store_acc_no')->where('customer_category', '=', 2);
        return $this->belongsTo('App\Models\Customer', 'store_acc_no');

    }

    public function itemSumTotal()
    {
        return $this->hasMany('App\Models\PurchaseDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_item_total_price');
    }

    public function itemSumVat()
    {
        return $this->hasMany('App\Models\PurchaseDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_vat_amount');
    }

    public function itemSumDisc()
    {
        return $this->hasMany('App\Models\PurchaseDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_disc_amount');
    }

    public function itemSumNet()
    {
        return $this->hasMany('App\Models\PurchaseDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_price_net');
    }

    public function itemSumCostNet()
    {
        return $this->hasMany('App\Models\PurchaseDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('item_total_cost');
    }


    public function sourceBranch()
    {

        return $this->belongsTo('App\Models\Branch', 'store_vou_ref_1');

    }

    public function mntsCard()
    {
        return $this->belongsTo('App\Models\MaintenanceCard', 'store_vou_ref_1');
    }

    public function mntsCar()
    {
        return $this->belongsTo('App\Models\MaintenanceCar', 'store_vou_ref_3', 'mntns_cars_vat_no');
    }

    public function sourceStore()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_ref_2');

    }

    public function DestBranch()
    {

        return $this->belongsTo('App\Models\Branch', 'store_vou_ref_3');

    }

    public function destStore()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_ref_4');

    }

    public function branch()
    {

        return $this->belongsTo('App\Models\Branch', 'branch_id');

    }

    public function getVouDatetimeAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getDiscountRatioAttribute()
    {
        $total_b = $this->store_vou_total + $this->store_vou_desc - $this->store_vou_vat_amount;
        if ($total_b > 0) {
            $ratio = ($this->store_vou_desc / $total_b) * 100;
        } else {
            $ratio = ($this->store_vou_desc / 1) * 100;
        }

        return $ratio;
    }

    public function getTotalBondsInvAttribute()
    {
        $bonds = Bond::where([
            ['transaction_type', 65],
            ['transaction_id', $this->store_hd_id]
        ])->pluck('bond_amount_debit')->toArray();
        return array_sum($bonds);
    }


    public function items()
    {
        return $this->hasMany('App\Models\StoreDtItem', 'store_inv_id');
    }

}
