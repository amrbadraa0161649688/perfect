<?php

namespace App\Http\Controllers;

use App\Exports\BondsExport;
use App\Http\Controllers\General\JournalsController;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Note;
use App\Models\SystemCode;
use App\Models\SystemCodeCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BondCaptureSafeController extends Controller
{

    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bonds = Bond::where('company_id', $company->company_id)->where('bond_type_id', 22)
            ->latest()->paginate();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $payment_methods = SystemCodeCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $data = request()->all();

        if (request()->company_id) {

            $query = Bond::where('bond_type_id', 22)->whereIn('company_id', request()->company_id);
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

        return view('Bonds.CaptureSafe.index', compact('bonds', 'companies', 'data',
            'payment_methods'));

    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $applications = $company->appsActive;
//انواع الحساب
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
//       انواع الايرادات
        $system_code_types = SystemCode::where('sys_category_id', 153)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $payment_methods = SystemCodeCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $current_date = Carbon::now()->format('Y-m-d\TH:i');


        return view('Bonds.CaptureSafe.create', compact('applications', 'company', 'branch',
            'account_types', 'system_code_types', 'payment_methods', 'banks', 'current_date'));
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 146)->latest()->first();
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
                'app_menu_id' => 146,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $payment_method = SystemCodeCode::where('system_code', $request->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();

        // return $request->bond_acc_id;
        $bond = Bond::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 22,  ///سند قبض
            'bond_type_name' => 'Receipt',
            'bond_method_type' => $payment_method->system_code,
            'transaction_type' => $request->transaction_type ? $request->transaction_type : 0,
            'customer_id' => $request->customer_id,
            'customer_type' => isset($customer_type) ? $customer_type : $request->customer_type,
            'bond_date' => ($request->bond_date),
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : null,
            'bond_ref_no' => $request->bond_ref_no ? $request->bond_ref_no : null,
            'bond_doc_type' => $request->bond_doc_type,
            'bond_check_no' => $request->process_number ? $request->process_number : null,
            'bond_amount_debit' => $request->bond_amount_debit ? $request->bond_amount_debit : null,
            'bond_amount_balance' => $request->bond_amount_debit ? $request->bond_amount_debit : null,
            'bond_acc_id' => isset($account_id) ? $account_id : $request->bond_acc_id,
            'bond_notes' => $request->bond_notes,
            'created_user' => auth()->user()->user_id
        ]);

        $journal_controller = new JournalsController();
        $amount_total = $bond->bond_amount_debit;
        $cc_voucher_id = $bond->bond_id;
        $bank_id = $request->bank_id ? $bank_id = $request->bank_id : '';
        $journal_category_id = 58;
        $cost_center_id = 146;
        $journal_notes = 'سند قبض عهده رقم ' . ' ' . $bond->bond_code;
        $journal_controller->AddCaptureSafeJournal($amount_total, $cc_voucher_id,
            $payment_method, $bank_id, $journal_category_id,
            $cost_center_id, $journal_notes);

        return redirect()->route('bonds.capture.safe');
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 146)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 146)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            return response()->json(['data' => $bond]);
        }
        return view('Bonds.CaptureSafe.edit', compact('bond', 'notes', 'attachments', 'payment_methods',
            'attachment_types', 'id', 'banks'));
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
            'bond_amount_debit' => $request->bond_amount_debit
        ]);

        if ($bond->journalCapture) {
            $journal_controller = new JournalsController();
            $amount_total = $bond->bond_amount_debit;
            $cc_voucher_id = $bond->bond_id;
            $journal_controller->UpdateCaptureSafeJournal($amount_total, $cc_voucher_id);
        }

        return back();
    }

    public function show($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        return view('Bonds.CaptureSafe.show', compact('bond', 'banks'));
    }

    public function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bonds = Bond::where('company_id', $company->company_id)->where('bond_type_id', 22)
            ->latest()->get();
        if (request()->company_id) {

            $query = Bond::where('bond_type_id', 22)->whereIn('company_id', request()->company_id);
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

        return Excel::download(new BondsExport($bonds), 'bonds.xlsx');

    }


}
