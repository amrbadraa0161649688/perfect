<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMenuSerial extends Model
{
    use HasFactory;

    protected $primaryKey = 'serial_id';

    protected $table = 'companies_menu_serial';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = ['company_group_id', 'company_id', 'app_menu_id', 'acc_period_year',
        'serial_started', 'serial_last_no', 'created_user', 'updated_user', 'branch_id','journal_type'];


}
