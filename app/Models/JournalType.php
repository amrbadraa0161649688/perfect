<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalType extends Model
{
    use HasFactory;
    protected $primaryKey = 'journal_types_id';
    protected $table = 'journal_types';

    protected $fillable = ['journal_types_code'
        , 'journal_types_name_en'
        , 'journal_types_name_ar'
        , 'journal_types_table'
        , 'company_group_id'
        , 'company_id'
        , 'acc_debit_type'
        , 'account_id_debit'
        , 'acc_credit_type'
        , 'account_id_credit'
        , 'journal_types_status'
        , 'bond_type_id'
        , 'created_date'
        , 'updated_date'
        , 'created_user'
        , 'updated_user'];
}
