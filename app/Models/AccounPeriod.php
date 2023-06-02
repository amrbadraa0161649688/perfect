<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccounPeriod extends Model
{
    use HasFactory;

    protected $primaryKey = 'acc_period_id';

    protected $table = 'accounts_period';

    protected $fillable = ['company_group_id', 'company_id', 'acc_period_name_ar',
        'acc_period_name_en', 'acc_period_month', 'acc_period_year', 'acc_period_is_active',
        'acc_period_is_payroll', 'emp_payroll_status', 'emp_payroll_vouchers',
        'acc_invoice_serial','acc_invoice_add_serial','acc_invoice_disc_serial',
        'created_user', 'updated_user', 'emp_payroll_employee_no', 'emp_payroll_net_amout'];

    public function companyGroup()
    {
        return $this->belongsTo('App\Models\CompanyGroup', 'company_group_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
