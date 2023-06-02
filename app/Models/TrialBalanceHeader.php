<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialBalanceHeader extends Model
{
    use HasFactory;

    protected $table = 'trial_balance_header';

    protected $fillable = ['user_id', 'level', 'is_zero', 'company_group_id', 'company_id',
        'from_date', 'to_date', 'total_opening_balance_debit', 'total_opening_balance_credit',
        'total_trans_balance_debit', 'total_trans_balance_credit', 'total_balance_debit',
        'total_balance_credit'];
}
