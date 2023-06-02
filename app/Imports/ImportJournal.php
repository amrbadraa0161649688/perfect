<?php

namespace App\Imports;

use App\Models\JournalDt;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportJournal implements ToModel, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    public function __construct($journal_hd_id, $journal_type_id, $acc_period_id, $journal_status_id)
    {
        $this->journal_hd_id = $journal_hd_id;
        $this->journal_type_id = $journal_type_id;
        $this->acc_period_id = $acc_period_id;
        $this->journal_status_id = $journal_status_id;
    }


    public function model(array $row)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        if (isset($row[5])) { //customer
            $cost_center_type = SystemCode::where('system_code', 56002)
                ->where('company_group_id', $company->company_group_id)->first();
        } elseif (isset($row[6])) { //supplier
            $cost_center_type = SystemCode::where('system_code', 56001)
                ->where('company_group_id', $company->company_group_id)->first();
        } elseif (isset($row[7])) { //employee
            $cost_center_type = SystemCode::where('system_code', 56003)
                ->where('company_group_id', $company->company_group_id)->first();
        } elseif (isset($row[8])) { //car
            $cost_center_type = SystemCode::where('system_code', 56004)
                ->where('company_group_id', $company->company_group_id)->first();
        } else { //branch
            $cost_center_type = SystemCode::where('system_code', 56005)
                ->where('company_group_id', $company->company_group_id)->first();
        }

        return new JournalDt([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'account_id' => $row['account_id'],
            'journal_dt_notes' => $row['journal_dt_notes'],
            'journal_dt_debit' => $row['journal_dt_debit'],
            'journal_dt_credit' => $row['journal_dt_credit'],
            'journal_dt_balance' => $row['journal_dt_debit'] - $row['journal_dt_credit'],
            'cc_customer_id' => isset($row['cc_customer_id']) ? $row['cc_customer_id'] : null,
            'cc_supplier_id' => isset($row['cc_supplier_id']) ? $row['cc_supplier_id'] : null,
            'cc_employee_id' => isset($row['cc_employee_id']) ? $row['cc_employee_id'] : null,
            'cc_car_id' => isset($row['cc_car_id']) ? $row['cc_car_id'] : null,
            'cc_branch_id' => isset($row['cc_branch_id']) ? $row['cc_branch_id'] : null,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_voucher_id' => isset($row['cc_voucher_id']) ? $row['cc_voucher_id'] : null,
            'journal_status' => $this->journal_status_id,
            'journal_dt_date' => $row['journal_dt_date'],
            'journal_hd_id' => $this->journal_hd_id,
            'journal_type_id' => $this->journal_type_id,
            'period_id' => $this->acc_period_id,
            'cost_center_type_id' => $cost_center_type->system_code_id,
        ]);
    }
}
