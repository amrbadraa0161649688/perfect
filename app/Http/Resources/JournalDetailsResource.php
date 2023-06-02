<?php

namespace App\Http\Resources;

use App\Models\InvoiceHd;
use App\Models\SystemCode;
use App\Models\WaybillHd;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class JournalDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->cost_center_id == 70) {
            //waybill
            if ($this->cc_customer_id) {
                $waybills = DB::table('waybills')->where('customer_id', $this->cc_customer_id)->get();
            }
            if ($this->cc_supplier_id) {
                $waybills = DB::table('waybills')->where('customer_id', $this->cc_supplier_id)->get();
            }

            if ($this->cc_employee_id) {
                $waybills = DB::table('waybills')->where('customer_id', $this->cc_employee_id)->get();
            }
        }

        if ($this->cost_center_id == 73) {
            //invoice
            if ($this->cc_customer_id) {
                $invoices = DB::table('invoices')->where('customer_id', $this->cc_customer_id)->get();
            }
            if ($this->cc_supplier_id) {
                $invoices = DB::table('invoices')->where('customer_id', $this->cc_supplier_id)->get();
            }
            if ($this->cc_employee_id) {
                $invoices = DB::table('invoices')->where('customer_id', $this->cc_employee_id)->get();
            }
        }

        $customer = DB::table('customers')->where('customers.customer_id', '=', $this->cc_customer_id)->first();
        $supplier = DB::table('customers')->where('customers.customer_id', '=', $this->cc_supplier_id)->first();
        $employee = DB::table('employees')->where('employees.emp_id', '=', $this->cc_employee_id)->first();
        $truck = DB::table('trucks')->where('trucks.truck_id', '=', $this->cc_car_id)->first();
        $branch = DB::table('branches')->where('branches.branch_id', '=', $this->cc_branch_id)->first();
        return [
            ////journal_dt
            'journal_dt_id' => $this->journal_dt_id,
            'journal_dt_date' => $this->journal_dt_date,
            'account_id' => $this->account_id,
            'journal_dt_debit' => $this->journal_dt_debit,
            'journal_dt_credit' => $this->journal_dt_credit,
            'journal_dt_notes' => $this->journal_dt_notes,
            'cc_supplier_id' => $this->cc_supplier_id,
            'cc_employee_id' => $this->cc_employee_id,
            'cc_supplier_required' => false,
            'cc_voucher_required' => false,
            'cost_center_required' => false,
            'cc_customer_required' => false,
            'cc_customer_voucher_required' => false,
            'cost_center_customer_required' => false,
            'cc_employees_required' => false,
            'cc_employees_voucher_required' => false,
            'cost_center_employees_required' => false,
            'cars_required' => false,
            'cost_center_id' => $this->cc_employee_id,
            'cc_car_id' => $this->cc_car_id,
            'cc_branch_id' => $this->cc_branch_id,
            'customer_cost_center_id' => $this->cost_center_id,
            'customer_cc_voucher_id' => $this->cc_voucher_id,
            'supplier_cost_center_id' => $this->cost_center_id,
            'supplier_cc_voucher_id' => $this->cc_voucher_id,
            'employee_cost_center_id' => $this->cost_center_id,
            'employee_cc_voucher_id' => $this->cc_voucher_id,
            'cc_customer_id' => $this->cc_customer_id,
            ////accounts
            'account_name_ar' => $this->acc_name_ar,
            'account_name_en' => $this->acc_name_en,
            'account_code' => $this->acc_code,
            'cc_customer' => isset($customer) ? $customer : '',
            'cc_supplier' => isset($supplier) ? $supplier : '',
            'cc_employee' => isset($employee) ? $employee : '',
            'cc_car' => isset($truck) ? $truck : '',
            'cc_branch' => isset($branch) ? $branch : '',
            'account_obj' => DB::table('accounts')->where('accounts.acc_id', '=', $this->account_id)->first(),
            'account' => DB::table('accounts')->where('accounts.acc_id', '=', $this->account_id)->first(),
            'cost_center_type_id' => DB::table('system_codes')->where('system_codes.system_code_id', $this->cost_center_type_id)->first()->system_code,
            'waybills' => isset($waybills) ? $waybills : '',
            'invoices' => isset($invoices) ? $invoices : '',

        ];
    }
}
