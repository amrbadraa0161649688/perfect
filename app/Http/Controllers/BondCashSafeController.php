<?php

namespace App\Http\Controllers;

use App\Exports\BondCashExport;
use App\Http\Controllers\General\JournalsController;
use App\Models\ApplicationsMenu;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\InvoiceHd;
use App\Models\MaintenanceCardDetails;
use App\Models\Note;
use App\Models\SystemCode;
use App\Models\Trucks;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BondCashSafeController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bonds = Bond::where('company_id', $company->company_id)->where('bond_type_id', 11)
            ->latest()->paginate();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $data = request()->all();

        if (request()->company_id) {

            $query = Bond::where('bond_type_id', 11)->whereIn('company_id', request()->company_id);
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
                $query = $query->where('bond_code', 'like', '%' . request()->bond_code . '%');
                $bonds = $query->paginate();
            }

            if (request()->bond_check_no) {
                $query = $query->where('bond_check_no', request()->bond_check_no);
                $bonds = $query->paginate();
            }

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                    ->whereDate('created_date', '<=', request()->created_date_to);
                $bonds = $query->paginate();
            }


        }

        return view('Bonds.CashSafe.index', compact('bonds', 'companies', 'data',
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
        $system_code_types = SystemCode::where('sys_category_id', 153)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();

        return view('Bonds.CashSafe.create', compact('applications', 'company', 'branch',
            'current_date', 'account_types', 'system_code_types', 'payment_methods', 'banks'));
    }


    public function store(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 147)->latest()->first();

        DB::beginTransaction();

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
                'app_menu_id' => 147,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $payment_method = SystemCode::where('system_code', $request->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();

        $customer_type = $request->customer_type;
        $bond_account_id = $request->bond_acc_id;


        Bond::create([
            'company_group_id' => auth()->user()->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 11,  //سند صرف عهده
            'bond_type_name' => 'Payment',
            'transaction_type' => 147,
            'bond_method_type' => $payment_method->system_code,
            'customer_id' => $request->customer_id ? $request->customer_id : $request->emp_id,
            'bond_car_id' => $request->bond_car_id ? $request->bond_car_id : '',
            'customer_type' => $customer_type,
            'bond_date' => Carbon::now(),
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : null,
            'bond_ref_no' => isset($bond_ref_no) ? $bond_ref_no : $request->bond_ref_no,
            'bond_doc_type' => $request->bond_doc_type,
            'bond_check_no' => $request->process_number ? $request->process_number : null,

            'bond_vat_amount' => $request->bond_vat_amount,
            'bond_vat_rate' => $request->bond_vat_rate,
            'bond_amount_credit' => $request->bond_amount_total,
            'bond_amount_balance' => $request->bond_amount_total ? (-1) * $request->bond_amount_total : null,

            'bond_acc_id' => $bond_account_id,
            'bond_notes' => $request->bond_notes,
            'created_user' => auth()->user()->user_id
        ]);
        DB::commit();

        return redirect()->route('bonds.cash.safe');

    }

    public function show($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 147)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 147)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        return view('Bonds.CashSafe.show', compact('bond', 'attachments', 'notes', 'payment_methods',
            'attachment_types'));
    }


    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        //       انواع المصروفات
        $system_code_types = SystemCode::where('sys_category_id', 59)
            ->where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            return response()->json(['data' => $bond]);
        }
        return view('Bonds.CashSafe.edit', compact('bond', 'payment_methods', 'banks', 'id',
            'system_code_types'));
    }


    public function update($id, Request $request)
    {

        $bond = Bond::find($id);
        $bond->update([
            'bond_method_type' => $request->bond_method_type ? $request->bond_method_type : $bond->bond_method_type,
            'bond_check_no' => $request->bond_check_no ? $request->bond_check_no : $bond->bond_check_no,
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : $bond->bond_bank_id,
            'bond_notes' => $request->bond_notes,
            'updated_user' => auth()->user()->user_id,
            'bond_amount_credit' => $request->bond_amount_credit,
            'bond_vat_rate' => $request->bond_vat_rate,
            'bond_vat_amount' => $request->bond_vat_amount,
            'bond_doc_type' => $request->bond_doc_type
        ]);

        $bond->refresh();

        if ($bond->journalCash) {
            $journal_controller = new JournalsController();
            $amount_total = $bond->bond_amount_credit;
            $vat_amount = $bond->bond_vat_amount;
            $cc_voucher_id = $bond->bond_id;
            $doc_type = SystemCode::where('system_code_id', $bond->bond_doc_type)
                ->where('company_group_id', $bond->company_group_id)->first()->system_code;
            $journal_controller->UpdateCashSafe($cc_voucher_id, $amount_total, $vat_amount, $doc_type);
        }

        return back();
    }


    public function approveBond(Request $request)
    {
        $bond = Bond::find($request->bond_id);
        $company = session('company') ? session('company') : auth()->user()->company;

        $doc_type = SystemCode::where('system_code_id', $bond->bond_doc_type)
            ->where('company_group_id', $company->company_group_id)->first()->system_code;

        $journal_category_id = 59; ////قيد سند صرف  عهده

        $amount_total = $bond->bond_amount_credit;
        $vat_amount = $bond->bond_vat_amount;
        $cc_voucher_id = $bond->bond_id;

        $cost_center_id = 147;
        $journal_notes = '  قيد سند صرف عهده رقم' . ' ' . $bond->bond_code . ' ' . $bond->bond_notes;

        $j_add_date = Carbon::now();
        $journals_controller = new JournalsController();
        $journals_controller->AddCashSafeJournal($doc_type, $amount_total, $vat_amount, $cc_voucher_id,
            $journal_category_id, $cost_center_id, $journal_notes, $j_add_date);

        return back()->with('تم اضافه القيد');
    }

    public function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bonds = Bond::where('company_id', $company->company_id)->where('bond_type_id', 11)
            ->latest()->get();
        if (request()->company_id) {

            $query = Bond::where('bond_type_id', 11)->whereIn('company_id', request()->company_id);
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

        return Excel::download(new BondCashExport($bonds), 'bonds-cash.xlsx');

    }

}
