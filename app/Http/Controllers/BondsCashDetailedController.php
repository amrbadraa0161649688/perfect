<?php

namespace App\Http\Controllers;

use App\Http\Controllers\General\JournalsController;
use App\Models\Bond;
use App\Models\BondDetails;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\JournalType;
use App\Models\SystemCode;
use App\Models\TripHd;
use App\Models\Trucks;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BondsCashDetailedController extends Controller
{

    //////////////////////////////////////////////////////////////////سند صرف تفصيلي
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bonds = Bond::where('company_id', $company->company_id)->where('bond_type_id', 5)
            ->latest()->paginate();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();


        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $data = request()->all();

        if (request()->company_id) {

            $query = Bond::where('bond_type_id', 5)->whereIn('company_id', request()->company_id);
            $bonds = $query->paginate();

            if (request()->branch_ids) {
                $query = $query->whereIn('branch_id', request()->branch_ids);
                $bonds = $query->paginate();
            }

            if (request()->bond_method_type) {
                $query = $query->whereIn('bond_method_type', request()->bond_method_type);
                $bonds = $query->paginate();
            }

            if (request()->transaction_type) {
                $query = $query->whereIn('transaction_type', request()->transaction_type);
                $bonds = $query->paginate();

            }
            if (request()->bond_acc_id) {
                $query = $query->where('bond_acc_id', request()->bond_acc_id);
                $bonds = $query->paginate();
            }

            if (request()->bond_code) {
                $query = $query->where('bond_code', request()->bond_code);
                $bonds = $query->paginate();
            }

            if (request()->bond_check_no) {
                $query = $query->where('bond_check_no', request()->bond_check_no);
                $bonds = $query->paginate();
            }

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                    ->whereDate('created_date', '<=', request()->created_date_from);
                $bonds = $query->paginate();
            }


        }

        return view('Bonds.CashDetailed.index', compact('bonds', 'companies', 'data',
            'payment_methods'));
    }


    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $applications = $company->appsActive;
        $current_date = Carbon::now()->format('Y-m-d\TH:i');

//انواع الحساب
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
//       انواع المصروفات
        $system_code_types = SystemCode::where('sys_category_id', 59)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();

        return view('Bonds.CashDetailed.create', compact('applications', 'company', 'branch',
            'current_date', 'account_types', 'system_code_types', 'payment_methods', 'banks'));
    }


    public function store(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 130)->latest()->first();

        \DB::beginTransaction();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'BD-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 130,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        //  return $request->all();

        $bond = Bond::create([
            'company_group_id' => auth()->user()->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 5,  //سند صرف تفصيلي
            'bond_type_name' => 'Payment',
            'bond_method_type' => $request->bond_method_type,
            'bond_date' => Carbon::now(),
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : null,
            'bond_check_no' => $request->process_number ? $request->process_number : null,
            'bond_vat_amount' => $request->bond_vat_amount_total,
            'bond_vat_rate' => number_format($request->bond_vat_amount_total / $request->bond_amount_total, 2),
            'bond_amount_credit' => $request->bond_amount_total,
            'bond_amount_balance' => (-1) * $request->bond_amount_total,
            'bond_notes' => $request->bond_notes,
            'created_user' => auth()->user()->user_id,
            'transaction_type' => '1'
        ]);
//

        foreach ($request->bond_doc_type as $k => $bond_doc_type) {
            $account_type = SystemCode::where('system_code_id', $request->account_type[$k])->first();
            if ($request->customer_id[$k] != 0) {
                $customer = Customer::find($request->customer_id[$k]);
                $bond_mrs = $customer->customer_name_full_ar;

                $customer_type = 'customer';
            }

            if ($request->supplier_id[$k] != 0) {
                $supplier = Customer::find($request->supplier_id[$k]);
                $bond_mrs = $supplier->customer_name_full_ar;
                $customer_type = 'supplier';
            }


            if ($request->bond_branch_id[$k] != 0) {
                $branch = Branch::find($request->bond_branch_id[$k]);
                $bond_mrs = $branch->branch_name_ar;
                $customer_type = 'branch';
            }


            if ($request->bond_emp_id[$k] != 0) {
                $employee = Employee::find($request->bond_emp_id[$k]);
                $bond_mrs = $employee->emp_name_full_ar;
                $customer_type = 'employee';
            }


            if ($request->bond_car_id[$k] != 0) {
                $car = Trucks::find($request->bond_car_id[$k]);
                $bond_mrs = $car->truck_name;
                $customer_type = 'car';
            }


            $bond_detail = BondDetails::where('bond_id', $bond->bond_id)->first();
            if (isset($bond_detail)) {
                $last_bond_detail_serial_no = $bond_detail->bond_dt_serial;
                $array_number = explode('-', $last_bond_detail_serial_no);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number_detail = $array_number[count($array_number) - 1];
            } else {
                $string_number_detail = 1;
            }


            if ($request->customer_id[$k] != 0) {
                $customer_id = $request->customer_id[$k];
            } elseif ($request->supplier_id[$k] != 0) {
                $customer_id = $request->supplier_id[$k];
            } else {
                $customer_id = 0;
            }

            BondDetails::create([
                'bond_id' => $bond->bond_id,
                'bond_type_id' => 5,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'bond_dt_serial' => $string_number_detail,
                'bond_method_type' => $request->bond_method_type,
                'customer_id' => $customer_id != 0 ? $customer_id : null,
                'bond_emp_id' => $request->bond_emp_id[$k] != 0 ? $request->bond_emp_id[$k] : null,
                'bond_mrs' => $bond_mrs,
                'bond_doc_type' => $request->bond_doc_type[$k],
                'bond_date' => Carbon::now(),
                'bond_notes' => $request->bond_notes_dt[$k],
                'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : null,
                'bond_check_no' => $request->bond_check_no[$k] != 0 ? $request->bond_check_no[$k] : $request->process_number,
                'bond_vat_rate' => $request->bond_vat_rate[$k],
                'bond_vat_amount' => $request->bond_vat_amount[$k],
                'bond_amount_credit' => $request->bond_amount_credit[$k],
                'bond_amount_balance' => (-1) * $request->bond_amount_credit[$k],
                'bond_acc_id' => $request->bond_acc_id[$k],
                'bond_car_id' => is_numeric($request->bond_car_id[$k]) ? $request->bond_car_id[$k] : null,
                'bond_branch_id' => is_numeric($request->bond_branch_id[$k]) ? $request->bond_branch_id[$k] : null,
                'customer_type' => $customer_type,
                'created_user' => auth()->user()->user_id
            ]);
        }


        $journal_controller = new JournalsController();
        $cc_voucher_id = $bond->bond_id;
        $amount_total = $bond->bond_amount_credit;
//        $journal_category = 9;
        $journal_notes = 'قيد سند صرف تفصيلي رقم ' . $bond->bond_code;
        $bond_dts = $bond->bond_details;
        $vat_amount = $bond->bond_vat_amount;
        $payment_method = SystemCode::where('system_code', $request->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();
        $bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';
        $cost_center_id = 130;

        $journal_category = JournalType::where('journal_types_code', 99)->where('company_group_id', $company->company_group_id)->first();
        if (!$journal_category) {
            return back()->with('error', 'برجاء مراجعه البيانات');
        }

        $message = $journal_controller->addCashDetailedJournal($cost_center_id, $cc_voucher_id, $amount_total, $journal_category,
            $journal_notes, $bond_dts, $vat_amount, $payment_method,
            $bank_id);

        if (isset($message)) {
            return back()->with('error', $message);
        }

        \DB::commit();

        return redirect()->route('bonds-cash-detailed');
    }

    public function show($id)
    {
        $bond = Bond::find($id);
        return view('Bonds.CashDetailed.show', compact('bond'));
    }
}
