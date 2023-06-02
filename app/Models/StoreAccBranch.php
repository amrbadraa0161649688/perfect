<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAccBranch extends Model
{
    use HasFactory;


    protected $table = 'store_acc_branch';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function StoreType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'store_category_type_id');
    }

    public function JournalType()
    {
        return $this->belongsTo('App\Models\JournalType', 'journal_type_code');
    }

    public function Account1()
    {
        return $this->belongsTo('App\Models\Account', 'acc_id_1');
    }

    public function Account2()
    {
        return $this->belongsTo('App\Models\Account', 'acc_id_2');
    }

    public function Account3()
    {
        return $this->belongsTo('App\Models\Account', 'acc_id_3');
    }

    public function Account4()
    {
        return $this->belongsTo('App\Models\Account', 'acc_id_4');
    }


}
