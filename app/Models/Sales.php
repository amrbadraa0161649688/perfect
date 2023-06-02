<?php

namespace App\Models; 

use App\Models\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory, CompanyTrait;
    protected $table = 'sales_cars_hd';
    protected $primaryKey = 'store_hd_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const store_vou_date = 'store_vou_date';

    protected $guarded = [];

    public function journalHd()
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function details()
    {
        return $this->hasMany('App\Models\SalesDetails', 'store_hd_id')->where('isdeleted', '=', 0);
    }

    public function paymentMethod()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_pay_type');

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

        return $this->belongsTo('App\Models\Customer', 'store_acc_no')->where('customer_category', '=', 1);

    }

    public function vendorBy($page)
    {
        if($page !='return-sales')
        {
            return $this->belongsTo('App\Models\Customer', 'store_acc_no')->where('customer_category', '=', 1);
        }

        return $this->belongsTo('App\Models\Customer', 'store_acc_no')->where('customer_category', '=', 2);

    }

    public function customer()
    {

        return $this->belongsTo('App\Models\Customer', 'store_acc_no')->where('customer_category', '=', 2);

    }
    

    public function itemSumTotal()
    {
        return $this->hasMany('App\Models\SalesDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_item_total_price');
    }

    public function itemSumVat()
    {
        return $this->hasMany('App\Models\SalesDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_vat_amount');
    }

    public function itemSumDisc()
    {
        return $this->hasMany('App\Models\SalesDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_disc_amount');
    }

    public function itemSumNet()
    {
        return $this->hasMany('App\Models\SalesDetails', 'store_hd_id')
            ->where('isdeleted', '=', 0)
            ->sum('store_vou_price_net');
    }


    public function sourceBranch()
    {

        return $this->belongsTo('App\Models\Branch', 'store_vou_ref_1');

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

    public function status()
    {

        return $this->belongsTo('App\Models\SystemCode', 'store_vou_status');
    }

    public function report_url_cars()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '7601');
    }

    public function report_url_pr()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '7901');
    }

    public function report_url_pr_branch()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '7902');
    }

    public function report_url_pr_supp()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '7903');
    }

    public function report_url_po()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '8001');
    }

    public function report_url_ins()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '8101');
    }

    public function report_url_q()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '1221');
    }

    public function report_url_inv()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '8301');
    }

    public function report_url_ins_r()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '8401');
    }

    public function report_url_inv_r()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '1231');
    }

    public function report_url_trans()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '8201');
    }

    public function branch(){

        return $this->belongsTo('App\Models\Branch', 'branch_id');

    }

}
