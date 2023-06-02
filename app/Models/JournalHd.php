<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class JournalHd extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'journal_header';
    protected $primaryKey = 'journal_hd_id';
    protected $dates = ['journal_hd_date', 'created_at', 'updated_at'];

    protected $fillable = ['company_group_id', 'company_id', 'branch_id',
        'journal_type_id', 'journal_hd_code', 'period_id', 'journal_category_id',
        'journal_status', 'journal_doc_no', 'journal_file_no', 'journal_hd_notes',
        'journal_statement', 'journal_hd_debit', 'journal_hd_credit', 'journal_hd_balance',
        'journal_user_update_id', 'journal_user_entry_id', 'journal_hd_date'];

    public function invoice()
    {
        return $this->hasOne('App\Models\InvoiceHd', 'journal_hd_id');
    }

    public function journalCategory()
    {
        return $this->belongsTo('App\Models\JournalType', 'journal_category_id');
    }

    public function getJournalHdDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'journal_user_entry_id');
    }


    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function accountPeriod()
    {
        return $this->belongsTo('App\Models\AccounPeriod', 'period_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function report_url_journal()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '33001');
    }

    public function report_url_journal_all()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '33002');
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

    public function journalDetails()
    {
        return $this->hasMany('App\Models\JournalDt', 'journal_hd_id');
    }
}
