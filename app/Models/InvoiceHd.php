<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class InvoiceHd extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'invoice_header';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $primaryKey = 'invoice_id';

    protected $dates = ['invoice_due_date', 'invoice_date', 'supply_date'];

    public $sortable = ['invoice_no', 'invoice_date', 'customer_id'];

    protected $fillable = [
        'company_group_id',
        'invoice_due_date',
        'company_id',
        'branch_id',
        'invoice_no',
        'invoice_type',
        'customer_id',
        'customer_name',
        'customer_address',
        'customer_tax_no',
        'customer_phone',
        'invoice_due_date',
        'po_number',
        'supply_date',
        'payment_tems',
        'gr_number',
        'invoice_date',
        'acc_period_id',
        'invoice_amount',
        'invoice_vat_rate',
        'invoice_vat_amount',
        'invoice_discount_total',
        'invoice_down_payment',
        'invoice_total',
        'invoice_total_payment', 'credit_invoice_id',
        'invoice_is_payment', 'credit_invoice_discount',
        'invoice_notes',
        'invoice_acc_voucher_id', 'invoice_voucher_date',
        'invoice_voucher_by', 'journal_hd_id',
        'co_vat_collect', 'co_acc_sales', 'co_acc_sales_r', 'invoice_status',
        'created_user', 'updated_user', 'qr_data', 'bond_code', 'bond_date'];

    public function waybill()
    {
        return $this->hasOne('App\Models\WaybillHd', 'waybill_invoice_id');
    }

    public function discountInvoice()
    {
        return $this->belongsTo('App\Models\InvoiceHd', 'credit_invoice_id');
    }

    public function getSupplyDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function invoiceReturn()
    {
        /////////////ربط اشعار الخصم بالفاتوره الي عليها الخصم
        return $this->belongsTo('App\Models\InvoiceHd', 'credit_invoice_id');
    }

    public function waybillCars()
    {
        return $this->hasMany('App\Models\WaybillHd', 'waybill_invoice_id');
    }

    public function getInvoiceDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function waybillDiesel()
    {
        return $this->hasOne('App\Models\WaybillHd', 'purchase_invoice_id');
    }

    public function bond()
    {
        return $this->belongsTo('App\Models\Bond', 'bond_code');
    }

    public function journalPurchase()
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id')
            ->where('journal_category_id', 34);
    }

    public function journalHd()
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalDt()
    {
        return $this->hasMany('App\Models\JournalDt', 'cc_voucher_id');
    }

    public function journalHdCars()
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }


    public function journalHdReturn() ////فاتوره مرتجع اشعار الخصم
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalHdDiesel()  ////قيد فاتوره البيع لديزل
    {
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id')
            ->where('journal_category_id', 38);
    }


    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function report_url()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73001');
    }

    public function report_sample()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73002');
    }

    public function report_url_acc()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73003');
    }

    public function report_url_purchase()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '7311');
    }

    public function report_url_car()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73009');
    }

    public function report_url_car_10()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73010');
    }

    public function report_url_car_11()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73011');
    }

    public function report_url_car_12()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73012');
    }

    public function report_url_car_13()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73013');
    }

    public function report_url_car_14()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73014');
    }

    public function report_url_all()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73005');
    }

    public function report_url_credit()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73008');
    }

    public function report_url_debit()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73007');
    }

    public function report_url_return()

    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '73004');
    }

    public function report_url_cargo_smal()

    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '910001');
    }

    public function report_url_cargo_smal_dt()

    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '910002');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function Waybilltickno()
    {
        return $this->hasOne('App\Models\WaybillHd', 'waybill_invoice_id', 'invoice_id');
    }


    public function Waybillptickno()
    {
        return $this->hasOne('App\Models\WaybillHd', 'purchase_invoice_id', 'invoice_id');
    }

    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_user');
    }

    public function AccountPeriod()
    {
        return $this->belongsTo('App\Models\AccounPeriod', 'acc_period_id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany('App\Models\InvoiceDt', 'invoice_id');
    }

    public function invoiceDetail()
    {
        return $this->hasOne('App\Models\InvoiceDt', 'invoice_id');
    }


    public function invoicestatus()
    {
        return $this->hasOne('App\Models\SystemCodeCode', 'system_code', 'invoice_status');
    }

    public function invoicemeth()
    {
        return $this->hasOne('App\Models\SystemCodeCode', 'system_code', 'payment_tems');
    }


    public function getTotalValueinvoice()
    {

        return $this->invoiceDetails->sum(function (InvoiceDt $invoice_detail) {

            return ($invoice_detail->invoice_item_amount + $invoice_detail->invoice_item_vat_amount - $invoice_detail->invoice_discount_total);

        });

    }
}
