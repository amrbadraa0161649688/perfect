<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

use App\Models\Master\JournalEntry;
use App\Models\Master\Account;
use App\Models\Master\CostCenter;

class JournalDetail extends Model
{
    protected $table = 'journal_details';
    protected $guarded = [];



    public function journalEntry(){
        return $this->belongsTo(JournalEntry::class,'journal_entry_id','id');
    }

    public function account(){
        return $this->belongsTo(Account::class,'account_id','id');
    }

    public function costCenter(){
        return $this->belongsTo(CostCenter::class,'cost_center_id','id');
    }

}
