<?php

namespace App\Http\Controllers;

use App\Models\ApplicationsMenu;
use App\Models\Bond;
use App\Models\CarRentContract;
use App\Models\CompanyMenuSerial;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BondAdditionController extends Controller
{
    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $applications = $company->appsActive;
//انواع الحساب
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
//       انواع الاضافات
        $system_code_types = SystemCode::where('sys_category_id', 60)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();


        if (request()->contract_id) {
            $contract = CarRentContract::find(request()->contract_id);
            return view('Bonds.Addition.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'contract'));
        }

        return view('Bonds.Addition.create', compact('applications', 'company', 'branch',
            'account_types', 'system_code_types', 'payment_methods', 'banks'));
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');


        DB::begintransaction();
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

        $payment_method = SystemCode::where('system_code', $request->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();

        if ($request->transaction_type) {
            $application_menu = ApplicationsMenu::where('app_menu_id', $request->transaction_type)->first();
            if ($application_menu->app_menu_id == 44) {

                $contract = CarRentContract::where('company_id', $company->company_id)
                    ->where('contract_code', $request->bond_ref_no)->latest()->first();

                $customer_type = 'customer';
                $account_id = $contract->customer->customer_account_id;

                if (isset($contract)) {
                    $transaction_id = $contract->contract_id;

                } else {
                    return back()->with(['error' => 'لا يوجد عقد بهذا الرقم']);
                }

            }
        }


        Bond::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 3, ///سند اضافه
            'bond_type_name' => 'Addition',
            'bond_method_type' => $payment_method->system_code,
            'transaction_type' => $request->transaction_type ? $request->transaction_type : 0,
            'transaction_id' => isset($transaction_id) ? $transaction_id : null,
            'customer_id' => $request->customer_id,
            'customer_type' => isset($customer_type) ? $customer_type : $request->customer_type,
            'bond_date' => Carbon::now(),
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : null,
            'bond_ref_no' => $request->bond_ref_no ? $request->bond_ref_no : null,
            'bond_doc_type' => $request->bond_doc_type,
            'bond_check_no' => $request->process_number ? $request->process_number : null,
            'bond_amount_debit' => $request->bond_amount_total ? $request->bond_amount_total : null,
            'bond_amount_balance' => $request->bond_amount_total ? $request->bond_amount_total : null,
            'bond_acc_id' => isset($account_id) ? $account_id : $request->bond_acc_id,
            'bond_notes' => $request->bond_notes,
            'created_user' => auth()->user()->user_id,
            'bond_vat_amount' => $request->bond_vat_amount,
            'bond_vat_rate' => $request->bond_vat_rate,
        ]);

        if (isset($contract)) {
            $contract->contract_total_add = $contract->contract_total_add + $request->bond_amount_total;
            $contract->contract_net_amount = $contract->contract_net_amount + $request->bond_amount_total;
            $contract->save();
        }

        DB::commit();

        if (isset($contract)) {
            return redirect()->route('car-rent.edit', $contract->contract_id);
        }

    }

    public function show($id)
    {
        $bond = Bond::find($id);
        return view('Bonds.Addition.show', compact('bond'));
    }
}
