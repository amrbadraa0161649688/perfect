<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalanceDetail extends Model
{
    use HasFactory;

    protected $table = 'trial_balance_detail';

    protected $fillable = ['trial_balance_header_id', 'account_id', 'account_name', 'code',
        'nature', 'level', 'main_type_id', 'opening_balance_debit', 'opening_balance_credit',
        'opening_balance_sign', 'trans_debit', 'trans_credit', 'trans_balance_debit', 'trans_balance_credit',
        'trans_balance_sign', 'balance_debit', 'balance_credit'];

}
