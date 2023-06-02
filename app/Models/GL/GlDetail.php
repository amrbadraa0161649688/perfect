<?php

namespace App\Models\GL;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'gl_detail_id';

    protected $table = 'gl_detail';

    public $timestamps = false;

    protected $fillable = ['gl_header_id', 'company_group_id', 'company_group_ar', 'company_group_en',
        'company_id', 'company_name_ar', 'company_name_en', 'id1', 'id2', 'id3', 'id4', 'id5',
        'nature', 'code1', 'code2', 'code3', 'code4', 'code5', 'name_ar1', 'name_ar2', 'name_ar3', 'name_ar4',
        'name_ar5', 'name_en1', 'name_en2', 'name_en3', 'name_en4', 'name_en5', 'level1', 'level2',
        'level3', 'level4', 'level5', 'journal_date', 'notes', 'debit', 'credit', 'balance', 'journal_hd_code','journal_hd_id',
        'journal_type_id', 'cost_center_id', 'cc_supplier_id', 'cc_customer_id', 'cc_employee_id', 'cc_car_id',
        'cc_branch_id', 'cc_voucher_id'];


    public function journalType()
    {
        return $this->belongsTo('App\Models\SystemCode', 'journal_type_id');
    }

}

