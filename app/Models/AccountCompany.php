<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCompany extends Model
{
    use HasFactory;
    protected $table = 'accounts_company';
    protected $primaryKey = 'accounts_company';
    protected $fillable = ['company_id', 'company_group_id', 'acc_code', 'acc_parent', 'acc_level'];

    public function accountChildren()
    {
        return $this->belongsTo('App\Models\Account', 'acc_code');
    }

    public function report_url_tree()
    {
        return $this->belongsTo('App\Models\Reports', 'company_id', 'company_id')->where('report_code', '18001');
    }
}
