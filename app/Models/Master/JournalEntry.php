<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;

use App\Models\Master\Company;
use App\Models\Master\Subsidiary;
use App\Models\Master\Branch;
use App\Models\Master\EntryStatus;
use App\Models\Master\AccountingEntry;
use App\Models\Master\AccountPeriod;
use App\Models\Master\JournalDetail;

class JournalEntry extends Model implements PermissionSeederContract
{
    use PermissionSeederTrait,HasFilter;
    protected $table = 'journal_entries';
    protected $fillable = [
                'company_id','subsidiary_id','branch_id','user_statement','general_statement','accounting_entry_id','journal_entry_no',
                'doc_no','file_no','account_period_id','date','entry_status_id','debit','credit','balance'
                            ];


    public function journalDetails(){
        return $this->hasMany(JournalDetail::class,'journal_entry_id','id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function subsidiary(){
        return $this->belongsTo(Subsidiary::class,'subsidiary_id','id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }

    public function accountingEntry(){
        return $this->belongsTo(AccountingEntry::class,'accounting_entry_id','id');
    }

    public function accountPeriod(){
        return $this->belongsTo(AccountPeriod::class,'account_period_id','id');
    }

    public function entryStatus(){
        return $this->belongsTo(EntryStatus::class,'entry_status_id','id');
    }

    public static function map($item){
        return [
            'id'=>$item->id,
            'subsidiary_id'=>$item->subsidiary_id ?? '',
            'subsidiary_name'=>optional($item->subsidiary)->name ?? '',
            'branch_id'=>$item->branch_id ?? '',
            'branch_name'=>optional($item->branch)->name ?? '',
            'accounting_entry_name'=>optional($item->accountingEntry)->name ?? '',
            'accounting_entry_id'=>$item->accounting_entry_id ?? '',
            'account_period_id'=>$item->account_period_id ?? '',
            'account_period_name'=>optional($item->accountPeriod)->year .'/'.optional($item->accountPeriod)->month,
            'doc_no' => $item->doc_no ?? '',
            'file_no' => $item->file_no ?? '',
            'debit' => $item->debit ?? 0,
            'credit' => $item->credit ?? 0,
            'journal_entry_no'=>$item->journal_entry_no ?? '',
            'date'=>$item->date ?? '',
            'general_statement'=>$item->general_statement ?? '',
            'entry_status_id'=>$item->entry_status_id ?? '',
            'entry_status_name'=>optional($item->entryStatus)->name_ar,

        ];
    }
}
