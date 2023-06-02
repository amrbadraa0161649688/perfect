<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $primaryKey = 'acc_id';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $table = 'accounts';

    protected $fillable = ['company_group_id', 'acc_name_ar', 'acc_name_en', 'acc_code', 'acc_level',
        'acc_parent', 'acc_sheet', 'acc_type', 'acc_is_active', 'created_user', 'updated_user', 'nature'];

    public function accounts()
    {
        return $this->hasMany('App\Models\Account', 'acc_parent');
    }

    public function journal_details()
    {
        return $this->hasMany('App\Models\JournalDt', 'account_id');
    }

    public function childrenAccounts()
    {
        return $this->hasMany('App\Models\AccountCompany', 'acc_parent');
    }

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\companyGroup', 'company_group_id');
    }

    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'accounts_company', 'acc_code', 'company_id');
    }

}
