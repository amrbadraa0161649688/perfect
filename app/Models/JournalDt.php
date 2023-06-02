<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDt extends Model
{
    use HasFactory;

    protected $table = 'journal_details';
    protected $primaryKey = 'journal_dt_id';

    protected $fillable = ['company_group_id', 'company_id', 'branch_id',
        'journal_type_id', 'journal_hd_id', 'period_id', 'journal_dt_date', 'journal_status',
        'account_id', 'journal_dt_notes', 'journal_statement', 'journal_dt_debit', 'journal_dt_credit', 'journal_dt_balance',
        'cc_customer_id', 'cc_supplier_id', 'cc_employee_id', 'cc_car_id',
        'cc_branch_id', 'journal_user_entry_id', 'journal_user_update_id', 'cost_center_type_id',
        'cost_center_id',  ///النوع بوليصه او فاتوره
        'cc_voucher_id', /// ال id للفاتوره او البوليصه
    ];

    public function truck()
    {
        return $this->belongsTo('App\Models\Trucks', 'cc_car_id');
    }


    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'cc_branch_id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'cc_employee_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Customer', 'cc_supplier_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'cc_customer_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'account_id');
    }


    public function costCenter()
    {
        if ($this->cost_center_id == 73) {
            return $this->belongsTo('App\Models\InvoiceHd', 'cc_voucher_id');
        }

        if ($this->cost_center_id == 70) {
            return $this->belongsTo('App\Models\WaybillHd', 'cc_voucher_id');
        }

        ////سندات القبض
        if ($this->cost_center_id == 53) {
            return $this->belongsTo('App\Models\Bond', 'cc_voucher_id');
        }
    }

//    مركز التكلفه
    public function costCenterType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'cost_center_type_id');
    }

    public function accountPeriod()
    {
        return $this->belongsTo('App\Models\AccountPeriod', 'period_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    //نوع اليوميه
    public function journalType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'journal_type_id');
    }

    //    حاله القيد
    public function journalStatus()
    {
        return $this->belongsTo('App\Models\SystemCode', 'journal_status');
    }

    public function setJournalDtDateAttribute($date)
    {

        $this->attributes['journal_dt_date'] = Carbon::parse($date)->format('Y-m-d H:i:s');
    }

}
