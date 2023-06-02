<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bond extends Model
{
    use HasFactory;

    protected $table = 'bonds';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $dates = ['created_date', 'updated_date'];

    protected $primaryKey = 'bond_id';

    protected $fillable = [
        'bond_code',
        'company_group_id',
        'company_id',
        'branch_id',
        'journal_hd_id',
        'bond_type_id',
        'bond_type_name',
        'bond_method_type', ///payment_method
        'transaction_type', // نوع الحركه (فاتوره او بوليصه)
        'transaction_id',
        'customer_id',
        'bond_mrs',
        'bond_doc_type',
        'bond_notes',
        'bond_bank_id',
        'bond_check_no', // رقم العمليه
        'bond_ref_no',
        'bond_date',
        'bond_vat_no',
        'bond_vat_rate',
        'bond_vat_amount',

        'bond_amount_debit',
        'bond_amount_credit',
        'bond_amount_balance',
        'bond_acc_id',
        'customer_type',

        'bond_is_copy',
        'bond_driver_id',
        'bond_car_id',
        'created_user', 'updated_user'];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function truck()
    {
        return $this->belongsTo('App\Models\Trucks', 'bond_car_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'bond_acc_id');
    }

    public function journalCashMaintenanceCard()
    {
        /////قيد سند صرف كارت الصيانه
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalCash()
    {
        /////قيد سند صرف خارجي
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalCashCu()
    {
        /////قيد سند صرف مرتجع عميل او مورد
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function carContract()
    {
        ////سند قبض عقد الايجار
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id')
            ->where('journal_category_id', 8);
    }

    public function journalCashTrip1()
    {
        /////قيد سند صرف للرحله للمصروف
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalCashTrip2()
    {
        /////قيد سند صرف للرحله لمكافاه الطريق
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalCapture()
    {
        /////قيد سند قيض خارجي
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalCaptureContract()
    {
        /////قيد سند قيض لعقد التاجير
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id')
            ->where('journal_category_id', 8);
    }

    public function journalBondWaybillCar()
    {
        /////قيد سند قيض علي بوليصه سياره
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }


    public function maintenanceCard()
    {
        /////قيد سند قيض علي كارت الصيانه
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }


    public function journalBondInvoiceSales()
    {
        /////////////////فسد سند قبض علي اتوره بيع
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }


    public function journalBondWaybillCars()
    {
        /////////////////فسد سند صرف علي بوليصه سياره
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id');
    }

    public function journalBondCashRentContract()
    {
        /////////////////فسد سند صرف علي عقد ايجاره
        return $this->belongsTo('App\Models\JournalHd', 'journal_hd_id')
            ->whereHas('journalCategory', function ($query) {
                $query->where('journal_types_code', 16);
            });
    }


    public function bank()
    {
        return $this->belongsTo('App\Models\SystemCode', 'bond_bank_id');
    }

    public function paymentMethod()
    {
        ///طريقه الدفع
        return $this->belongsTo('App\Models\SystemCodeCode', 'bond_method_type');
    }


    public function getPaymentMethodNameAttribute()
    {
        $payment_method = \App\Models\SystemCode::where('company_group_id', $this->company_group_id)
            ->where('system_code', $this->bond_method_type)
            ->first();
        if (app()->getLocale() == 'ar') {
            return $payment_method->system_code_name_ar;
        } else {
            return $payment_method->system_code_name_en;
        }
    }

    public function transactionType()
    {
        ////from application_menu_table
        return $this->belongsTo('App\Models\ApplicationsMenu', 'transaction_type');
    }

    public function getBondDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function transaction()
    {
        if ($this->transaction_type == 73) {
            return $this->belongsTo('App\Models\InvoiceHd', 'transaction_id');
        }

        if ($this->transaction_type == 70 || $this->transaction_type == 88) {
            return $this->belongsTo('App\Models\WaybillHd', 'transaction_id');
        }

        if ($this->transaction_type == 65) {
            return $this->belongsTo('App\Models\Purchase', 'transaction_id');
        }
    }

    public function report_url_payment()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '54001');
    }

    public function report_url_payment_trip()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '54099');
    }

    public function report_url_payment_trip_all()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '54098');
    }

    public function report_url_receipt()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '53001');
    }

    public function report_url_bond_today()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '54007');
    }

    public function bondDocType()
    {
        ////انواع الايرادات
        return $this->belongsTo('App\Models\SystemCode', 'bond_doc_type');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function customer()
    {
        if ($this->customer_type == 'customer' || $this->customer_type == 'supplier') {
            return $this->belongsTo('App\Models\Customer', 'customer_id')->withDefault('لا يوجد عميل');
        }

        if ($this->customer_type == 'employee') {
            return $this->belongsTo('App\Models\Employee', 'customer_id')->withDefault('لا يوجد موظف');
        }

        if ($this->customer_type == 'branch') {
            return $this->belongsTo('App\Models\Branch', 'customer_id')->withDefault('لا يوجد فرع');
        }

        if ($this->customer_type == 'car') {
            return $this->belongsTo('App\Models\Trucks', 'customer_id')->withDefault('لا يوجد سياره');
        }

    }

//    public function invoice()
//    {
//        return $this->hasOne('App\Models\Bond','bond_code');
//    }

    public function userCreated()
    {
        return $this->belongsTo('App\Models\User', 'created_user');
    }

    public function userUpdated()
    {
        return $this->belongsTo('App\Models\User', 'updated_user');
    }


    public function invoiceDetails()
    {
        return $this->hasMany('App\Models\InvoiceDt', 'invoice_id');
    }

    public function getTotalValueinvoice()
    {

        return $this->invoiceDetails->sum(function (InvoiceDt $invoice_detail) {

            return ($invoice_detail->invoice_item_amount + $invoice_detail->invoice_item_vat_amount - $invoice_detail->invoice_discount_total);

        });

    }

    public function bond_details()
    {
        return $this->hasMany('App\Models\BondDetails', 'bond_id');
    }
}
