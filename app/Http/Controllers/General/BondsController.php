<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Bond;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\JournalType;
use App\Models\MaintenanceCard;
use App\Models\Purchase;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BondsController extends Controller
{
    /////اضافه سند قبض
    public function addBond($payment_method, $transaction_type, $transaction_id, $customer_id, $customer_type,
                            $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes)
    {
        ////////////system code object of payment method
        /// $transaction_type id from application menu
        /// $bond_doc_type ايرادات المبيعات من ال system code
        /// $bond_ref_no الكود لفاتوره البيع نفسها
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 53)->latest()->first();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'R-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
//                'branch_id' => $branch->branch_id,
                'app_menu_id' => 53,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'branch_id' => session('branch')['branch_id'],
                'created_user' => auth()->user()->user_id
            ]);
        }

        $customer = Customer::where('customer_id', $customer_id)->first();

        $bond = Bond::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 1, ///سند قبض
            'bond_type_name' => 'Receipt',
            'bond_method_type' => $payment_method->system_code,
            'transaction_type' => $transaction_type,
            'transaction_id' => $transaction_id,
            'customer_id' => $customer_id,
            'customer_type' => $customer_type,
            'bond_date' => Carbon::now(),
            'bond_amount_debit' => $total_amount,
            'bond_amount_balance' => $total_amount,
            'bond_doc_type' => $bond_doc_type->system_code_id,
            'bond_bank_id' => $bond_bank_id ? $bond_bank_id : null,
            'bond_ref_no' => $bond_ref_no,
            'bond_notes' => $bond_notes,
            'created_user' => auth()->user()->user_id,
            'bond_acc_id' => $customer->customer_account_id,
        ]);

        if ($transaction_type == 65) {
            $sales_invoice = Purchase::where('store_hd_id', $transaction_id)->first();
            $sales_invoice->update([
                'bond_id' => $bond->bond_id,
                'bond_code' => $bond->bond_code,
                'bond_date' => Carbon::now(),
            ]);
        }

        return $bond;
    }

    ////////////تحديث سند القبض
    public function updateBond($total_amount, $transaction_id)
    {
        $bond = Bond::where('bond_id', Purchase::where('store_hd_id', $transaction_id)->first()->bond_id)->first();
        $bond->update([
            'bond_amount_debit' => $total_amount,
            'bond_amount_balance' => $total_amount,
            'updated_user' => auth()->user()->user_id,
        ]);
        return $bond;
    }


    //////////////اضافه سند صرف
    public function addCashBond($payment_method, $transaction_type, $transaction_id, $customer_id, $customer_type,
                                $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id,
                                $bond_vat_amount, $bond_vat_rate, $bond_car_id, $j_add_date)
    {
        ////////////system code object of payment method
        /// $transaction_type id from application menu
        /// /// $bond_doc_type المصروفات  من ال system code
        ///   /// $bond_ref_no الكود للبوليصه نفسها
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 54)->latest()->first();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'P-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 54,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }


        $bond = Bond::create([
            'company_group_id' => auth()->user()->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 2, ///سند صرف
            'bond_type_name' => 'Payment',
            'bond_method_type' => $payment_method->system_code,
            'transaction_type' => $transaction_type,
            'transaction_id' => isset($transaction_id) ? $transaction_id : null,
            'customer_id' => $customer_id ? $customer_id : '',
            'customer_type' => $customer_type,
            'bond_date' => $j_add_date,
            'bond_bank_id' => $bond_bank_id ? $bond_bank_id : null,
            'bond_ref_no' => $bond_ref_no,
            'bond_doc_type' => $bond_doc_type->system_code_id,
            'bond_amount_credit' => $total_amount,
            'bond_amount_balance' => $total_amount ? (-1) * $total_amount : null,
            'bond_acc_id' => $bond_account_id,
            'bond_notes' => $bond_notes,
            'bond_car_id' => $bond_car_id ? $bond_car_id : '',
            'created_user' => auth()->user()->user_id,
            'bond_vat_amount' => $bond_vat_amount,
            'bond_vat_rate' => $bond_vat_rate,
        ]);

        return $bond;

    }

    //////////////سند اضافه
    public function addAdditionBond($payment_method, $transaction_type, $transaction_id, $customer_id, $customer_type,
                                    $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes,
                                    $bond_vat_amount, $bond_vat_rate)
    {

        /////////////$payment_method object from system code
        /// $transaction_type id from application menu

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');


        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 53)->latest()->first();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'R-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 53,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $customer = Customer::where('customer_id', $customer_id)->first();

        $bond = Bond::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 3, ///سند اضافه
            'bond_type_name' => 'Addition',
            'bond_method_type' => $payment_method->system_code,
            'transaction_type' => $transaction_type,
            'transaction_id' => $transaction_id,
            'customer_id' => $customer_id,
            'customer_type' => $customer_type,
            'bond_date' => Carbon::now(),
            'bond_bank_id' => $bond_bank_id ? $bond_bank_id : null,
            'bond_ref_no' => $bond_ref_no,
            'bond_doc_type' => $bond_doc_type->system_code_id,
            'bond_amount_debit' => $total_amount,
            'bond_amount_balance' => $total_amount,
            'bond_acc_id' => $customer->customer_account_id,
            'bond_notes' => $bond_notes,
            'created_user' => auth()->user()->user_id,
            'bond_vat_amount' => $bond_vat_amount,
            'bond_vat_rate' => $bond_vat_rate,
        ]);

        return $bond;


    }
}
