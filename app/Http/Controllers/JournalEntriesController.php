<?php

namespace App\Http\Controllers;

use App\Exports\CustomersSuppliersExport;
use App\Http\Resources\JournalDetailsResource;
use App\Imports\ImportJournal;
use App\Models\Account;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\InvoiceHd;
use App\Models\JournalDt;
use App\Models\JournalHd;
use App\Models\AccounPeriod;
use App\Models\Note;
use App\Models\SystemCode;
use App\Models\Trucks;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class JournalEntriesController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $companie = Company::where('company_id', $company->company_id)->get();

        $flag = 0;
        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 33 && $job_permission->permission_approve) {
                    $flag += 1;
                }
            }
        } else {
            $flag += 1;
        }


        if ($flag > 0) {
            $journals = JournalHd::where('company_id', $company->company_id)->latest()->sortable()->paginate();
        } else {
            $journals = JournalHd::where('company_id', $company->company_id)
                ->where('journal_user_entry_id', auth()->user()->user_id)
                ->whereHas('journalType', function ($q) {
                    return $q->where('system_code', '=', 801);
                })
                ->orWhere(function ($query) use ($company) {
                    return $query->where('company_id', $company->company_id)
                        ->whereDoesntHave('journalType', function ($q) {
                            $q->where('system_code', '=', 801);
                        });
                })->latest()->sortable()->paginate();
        }

        $journal_types = SystemCode::where('sys_category_id', 8)
            ->where('company_group_id', $company->company_group_id)->get();
        $journal_statuses = SystemCode::where('sys_category_id', 9)
            ->where('company_group_id', $company->company_group_id)
            ->get();
        $data = request()->all();

        if (request()->company_id) {
//            $query = JournalHd::whereIn('company_id', request()->company_id);

            if ($flag > 0) {
                $query = JournalHd::whereIn('company_id', request()->company_id);
            } else {
                $query = JournalHd::whereIn('company_id', request()->company_id)
                    ->where('journal_user_entry_id', auth()->user()->user_id)
                    ->whereHas('journalType', function ($q) {
                        return $q->where('system_code', '=', 801);
                    })
                    ->orWhere(function ($query) use ($company) {
                        return $query->where('company_id', $company->company_id)
                            ->whereDoesntHave('journalType', function ($q) {
                                $q->where('system_code', '=', 801);
                            });
                    });
            }


            if (request()->branch_id) {
                $query = $query->whereIn('branch_id', request()->branch_id);

            }

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('journal_hd_date', '>=', request()->created_date_from)
                    ->whereDate('journal_hd_date', '<=', request()->created_date_to);

            }

            if (request()->journal_type_id) {
//                $journal_type = SystemCode::find(request()->journal_type_id);

                $query = $query->whereIn('journal_type_id', request()->journal_type_id);

            }

            if (request()->journal_status) {
                $query = $query->whereIn('journal_status', request()->journal_status);

            }

            if (request()->journal_hd_code) {
                $query = $query->where('journal_hd_code', 'like', '%' . request()->journal_hd_code . '%');

            }
            if (request()->journal_hd_notes) {
                $query = $query->where('journal_hd_notes', 'like', '%' . request()->journal_hd_notes . '%');

            }

            $journals = $query->latest()->sortable()->paginate();
        }

        return view('JournalEntries.index', compact('companies', 'journals', 'journal_types', 'companie',
            'journal_statuses', 'data', 'flag'));
    }

    public
    function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $journal_types = SystemCode::where('sys_category_id', 8)
            ->where('company_group_id', $company->company_group_id)->get();
        $journal_statuses = SystemCode::where('sys_category_id', 9)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', [901, 902, 903, 904])->get();
        $branch = session('branch');

        $flag = 0;

        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 33 && $job_permission->permission_approve) {
                    $flag += 1;
                }
            }
        } else {
            $flag += 1;
        }
        return view('JournalEntries.create', compact('account_types', 'journal_types',
            'companies', 'journal_statuses', 'branch', 'company', 'flag'));
    }

    public
    function store(Request $request)
    {
        // return $request->all();

        if ($request->total_difference > 0) {
            return back()->with(['error' => 'الفرق بين الدائن والمدين لا يساوي صفر']);
        }

        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;

        $last_journal_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 33)->where('journal_type', $request->journal_type_id)->latest()->first();
        if (isset($last_journal_reference)) {
            $last_journal_reference_number = $last_journal_reference->serial_last_no;
            $array_number = explode('-', $last_journal_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number_journal = implode('-', $array_number);
            $last_journal_reference->update(['serial_last_no' => $string_number_journal]);
        } else {
            $string_number_journal = 'J-' . $request->journal_type_id . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'app_menu_id' => 33,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number_journal,
                'created_user' => auth()->user()->user_id,
                'journal_type' => $request->journal_type_id
            ]);
        }

        $account_period = AccounPeriod::where('acc_period_year', Carbon::parse($request->journal_hd_date)->format('Y'))
            ->where('acc_period_month', Carbon::parse($request->journal_hd_date)->format('m'))
            ->where('acc_period_is_active', 1)->first();


        if ($request->journal_status) {
            $journal_status = SystemCode::where('system_code_id', $request->journal_status)->first();
        } else {
            $journal_status = SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 902)->first();
        }


        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'branch_id' => $request->branch_id,
            'journal_type_id' => $request->journal_type_id,
            'journal_hd_code' => $string_number_journal,
            'journal_hd_date' => $request->journal_hd_date,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_file_no' => $request->journal_file_no,
            'journal_hd_notes' => $request->journal_hd_notes,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_credit' => $request->journal_hd_credit,
            'journal_hd_debit' => $request->journal_hd_debit,
        ]);


        foreach ($request->account_id as $k => $account_id) {

            if (isset($request->customer_cost_center_id[$k])) {
                $cost_center_id = isset($request->customer_cost_center_id[$k]) ? $request->customer_cost_center_id[$k] : '';
                $cc_voucher_id = isset($request->customer_cc_voucher_id[$k]) ? $request->customer_cc_voucher_id[$k] : '';

            }

            if (isset($request->supplier_cost_center_id[$k])) {
                $cost_center_id = isset($request->supplier_cost_center_id[$k]) ? $request->supplier_cost_center_id[$k] : '';
                $cc_voucher_id = isset($request->supplier_cc_voucher_id[$k]) ? $request->supplier_cc_voucher_id[$k] : '';
            }

            if (isset($request->employee_cost_center_id[$k])) {
                $cost_center_id = isset($request->employee_cost_center_id[$k]) ? $request->employee_cost_center_id[$k] : '';
                $cc_voucher_id = isset($request->employee_cc_voucher_id[$k]) ? $request->employee_cc_voucher_id[$k] : '';
            }

            if (isset($request->cc_car_id[$k])) {
                $cc_car_id = $request->cc_car_id[$k];
            }

            if (isset($request->cc_branch_id[$k])) {
                $cc_branch_id = $request->cc_branch_id[$k];
            }

            $cost_center_type = SystemCode::where('system_code', $request->cost_center_type_id[$k])
                ->where('company_group_id', $company->company_group_id)->first();


            DB::table('journal_details')->insert([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $request->journal_type_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $request->account_id[$k],
                'journal_dt_notes' => $request->journal_dt_notes[$k],
                'journal_dt_debit' => $request->journal_dt_debit[$k],
                'journal_dt_credit' => $request->journal_dt_credit[$k],
                'journal_dt_balance' => $request->journal_dt_credit[$k] - $request->journal_dt_debit[$k],
                'cc_customer_id' => isset($request->cc_customer_id[$k]) ? $request->cc_customer_id[$k] : null,
                'cc_supplier_id' => isset($request->cc_supplier_id[$k]) ? $request->cc_supplier_id[$k] : null,
                'cc_employee_id' => isset($request->cc_employee_id[$k]) ? $request->cc_employee_id[$k] : null,
                'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
                'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cost_center_type_id' => $cost_center_type->system_code_id,
                'cost_center_id' => isset($cost_center_id) ? $cost_center_id : null,
                'cc_voucher_id' => isset($cc_voucher_id) ? $cc_voucher_id : null,
            ]);
        }
        DB::commit();
        return redirect()->route('journal-entries')->with(['success' => 'تم الاضافه']);
    }


    public function storeFromSheet(Request $request)
    {
        try {
            $company = session('company') ? session('company') : auth()->user()->company;

            $last_journal_reference = CompanyMenuSerial::where('company_id', $request->company_id)
                ->where('app_menu_id', 33)->where('journal_type', $request->journal_type_id)->latest()->first();
            if (isset($last_journal_reference)) {
                $last_journal_reference_number = $last_journal_reference->serial_last_no;
                $array_number = explode('-', $last_journal_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number_journal = implode('-', $array_number);
                $last_journal_reference->update(['serial_last_no' => $string_number_journal]);
            } else {
                $string_number_journal = 'J-' . $request->journal_type_id . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $request->company_id,
                    'app_menu_id' => 33,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number_journal,
                    'created_user' => auth()->user()->user_id,
                    'journal_type' => $request->journal_type_id
                ]);
            }

            $account_period = AccounPeriod::where('acc_period_year', Carbon::parse($request->journal_hd_date)->format('Y'))
                ->where('acc_period_month', Carbon::parse($request->journal_hd_date)->format('m'))
                ->where('acc_period_is_active', 1)->first();


            if ($request->journal_status) {
                $journal_status = SystemCode::where('system_code_id', $request->journal_status)->first();
            } else {
                $journal_status = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 902)->first();
            }


            $journal_hd = JournalHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'branch_id' => $request->branch_id,
                'journal_type_id' => $request->journal_type_id,
                'journal_hd_code' => $string_number_journal,
                'journal_hd_date' => $request->journal_hd_date,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_status' => $journal_status->system_code_id,
                'journal_file_no' => $request->journal_file_no,
                'journal_hd_notes' => $request->journal_hd_notes,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_user_update_id' => auth()->user()->user_id,
            ]);

            $acc_period_id = isset($acc_period) ? $acc_period->acc_period_id : 1;

            Excel::import(new ImportJournal($journal_hd->journal_hd_id, $request->journal_type_id, $acc_period_id, $journal_status->system_code_id),
                $request->file('file')->store('files'));

        } catch (\Exception $e) {

            return back()->with(['error' => $e->getMessage()]);
        }

        return redirect()->route('journal-entries.show', $journal_hd->journal_hd_id);
    }

///////////////api
    public
    function getCompanyData()
    {
        $company = Company::where('company_id', request()->company_id)->first();

        $branches = DB::table('branches')->where('company_id', request()->company_id)->get();

        $customers = DB::table('customers')->whereIn('customer_category', [2, 3, 9])
            ->where('company_group_id', $company->company_group_id)->get();

        $suppliers = DB::table('customers')->where('customers.customer_category', 1)
            ->where('customers.company_group_id', $company->company_group_id)->get();

        $employees = DB::table('employees')->where('employees.company_group_id', $company->company_group_id)->get();

        $accounts = DB::table('accounts')->where('accounts.company_group_id', $company->company_group_id)
            ->where('accounts.acc_level', $company->companyGroup->accounts_levels_number)->get();

        $branch_id = session('branch')['branch_id'];

        $trucks = DB::table('trucks')->where('trucks.company_id', $company->company_id)->get();

        return response()->json(['data' => $branches, 'customers' => $customers,
            'suppliers' => $suppliers, 'employees' => $employees, 'accounts' => $accounts,
            'trucks' => $trucks, 'branch_id' => $branch_id]);
    }

///////////////api
    public
    function getData()
    {
        ///get waybills or invoices for customer and suppliers
        if (request()->cost_center_id == 70) {
            //waybill
            if (request()->cc_customer_id) {
                $waybills = DB::table('waybill_hd')->where('waybill_hd.customer_id', request()->cc_customer_id)->get();
            }
            if (request()->cc_supplier_id) {
                $waybills = DB::table('waybill_hd')->where('waybill_hd.customer_id', request()->cc_supplier_id)->get();
            }

            if (request()->cc_employee_id) {
                $waybills = DB::table('waybill_hd')->where('waybill_hd.customer_id', request()->cc_employee_id)->get();
            }
        }

        if (request()->cost_center_id == 73) {
            //invoice
            if (request()->cc_customer_id) {
                DB::table('invoice_header')->where('invoice_header.customer_id', request()->cc_customer_id)->get();
            }
            if (request()->cc_supplier_id) {
                DB::table('invoice_header')->where('invoice_header.customer_id', request()->cc_supplier_id)->get();
            }
            if (request()->cc_employee_id) {
                DB::table('invoice_header')->where('invoice_header.customer_id', request()->cc_employee_id)->get();
            }
        }

        if (isset($invoices)) {
            return response()->json(['invoices' => $invoices]);
        }

        if (isset($waybills)) {
            return response()->json(['waybills' => $waybills]);
        }

    }

    public
    function edit(Request $request, $id)
    {
        $journal_hd = JournalHd::findOrFail($id);

        $current_date = Carbon::parse($journal_hd->journal_hd_date)->format('Y-m-d\TH:i');

        if ($request->ajax()) {

            $journal_hd = JournalHd::where('journal_hd_id', request()->journal_hd_id)->first();

            $journal_dts = DB::table('journal_details')
                ->where('journal_details.journal_hd_id', '=', $journal_hd->journal_hd_id)
                ->leftJoin('accounts', function ($join) {
                    $join->on('journal_details.account_id', '=', 'accounts.acc_id');
                })
                ->get();

            return response()->json(['data' => $journal_hd,
                'journal_dts' => JournalDetailsResource::collection($journal_dts)]);

        }

        $company = session('company') ? session('company') : auth()->user()->company;
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $journal_types = SystemCode::where('sys_category_id', 8)
            ->where('company_group_id', $company->company_group_id)->get();
        $journal_statuses = SystemCode::where('sys_category_id', 9)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', [903, 904, 905, $journal_hd->journalStatus->system_code])
            ->get();
        $branch = session('branch');

        $flag = 0;

        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 33 && $job_permission->permission_approve) {
                    $flag += 1;
                }
            }
        } else {
            $flag += 1;
        }


        return view('JournalEntries.edit', compact('account_types', 'companies', 'current_date',
            'journal_types', 'journal_statuses', 'id', 'journal_hd', 'branch', 'flag'));
    }

    public
    function update(Request $request, $id)
    {
        if ($request->total_difference > 0) {
            return back()->with(['error' => 'الفرق بين الدائن والمدين لا يساوي صفر']);
        }
        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;

        $journal_hd = JournalHd::find($id);

        $account_period = AccounPeriod::where('acc_period_year', Carbon::parse($request->journal_hd_date)->format('Y'))
            ->where('acc_period_month', Carbon::parse($request->journal_hd_date)->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_hd->update([
            //  'journal_type_id' => $request->journal_type_id,
            'journal_hd_date' => $request->journal_hd_date,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : $journal_hd->period_id,
            'journal_status' => $request->journal_status,
            'journal_file_no' => $request->journal_file_no,
            'journal_hd_notes' => $request->journal_hd_notes,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_credit' => $request->journal_hd_credit,
            'journal_hd_debit' => $request->journal_hd_debit,
        ]);

        if (isset($request->old_journal_details_ids)) {
            foreach ($request->old_journal_details_ids as $k => $old_journal_details_ids) {
                $journal_detail = JournalDt::find($old_journal_details_ids);
                $journal_detail->update([
                    //  'journal_type_id' => $request->journal_type_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : $journal_detail->period_id,
//                    'journal_dt_date' => $request->journal_dt_date,
                    'journal_status' => $request->journal_status,
                    'account_id' => $request->old_account_id[$k],
                    'journal_dt_notes' => $request->old_journal_dt_notes[$k],
                    'journal_dt_date' => date('Y-m-d', strtotime(str_replace('-', '/', $request->old_journal_dt_date[$k]))),
                    'journal_dt_debit' => $request->old_journal_dt_debit[$k],
                    'journal_dt_credit' => $request->old_journal_dt_credit[$k],
                    'journal_dt_balance' => $request->old_journal_dt_credit[$k] - $request->old_journal_dt_debit[$k],
                    'journal_user_entry_id' => auth()->user()->user_id,
                    ///////////////////////////////////
                    // 'cc_branch_id' => $request->cc_branch_id[$k] ? $request->cc_branch_id[$k] : null,
                ]);

            }
        }

        if (count($request->account_id) > 0) {

            foreach ($request->account_id as $k => $account_id) {
                if ($account_id != null) {
                    if ($request->customer_cost_center_id[$k]) {
                        $cost_center_id = $request->customer_cost_center_id[$k] ? $request->customer_cost_center_id[$k] : '';
                        $cc_voucher_id = $request->customer_cc_voucher_id[$k] ? $request->customer_cc_voucher_id[$k] : '';

                    }

                    if ($request->supplier_cost_center_id[$k]) {
                        $cost_center_id = $request->supplier_cost_center_id[$k] ? $request->supplier_cost_center_id[$k] : '';
                        $cc_voucher_id = $request->supplier_cc_voucher_id[$k] ? $request->supplier_cc_voucher_id[$k] : '';
                    }

                    if ($request->employee_cost_center_id[$k]) {
                        $cost_center_id = $request->employee_cost_center_id[$k] ? $request->employee_cost_center_id[$k] : '';
                        $cc_voucher_id = $request->employee_cc_voucher_id[$k] ? $request->employee_cc_voucher_id[$k] : '';
                    }

                    if ($request->cc_car_id[$k]) {
                        $cc_car_id = $request->cc_car_id[$k] ? $request->cc_car_id[$k] : '';
                    }

                    if ($request->cc_branch_id[$k]) {
                        $cc_branch_id = $request->cc_branch_id[$k];
                    }

                    //  return $request->cc_employee_id[$k];
                    $cost_center_type = SystemCode::where('system_code', $request->cost_center_type_id[$k])->first();

                    JournalDt::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $request->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'journal_type_id' => $journal_hd->journal_type_id,
                        'journal_hd_id' => $journal_hd->journal_hd_id,
                        'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                        'journal_dt_date' => date('Y-m-d', strtotime(str_replace('-', '/', $request->journal_dt_date[$k]))),
                        'journal_status' => $request->journal_status,
                        'account_id' => $request->account_id[$k],
                        'journal_dt_notes' => $request->journal_dt_notes[$k],
                        'journal_dt_debit' => $request->journal_dt_debit[$k],
                        'journal_dt_credit' => $request->journal_dt_credit[$k],
                        'journal_dt_balance' => $request->journal_dt_credit[$k] - $request->journal_dt_debit[$k],
                        'cc_customer_id' => $request->cc_customer_id[$k] ? $request->cc_customer_id[$k] : null,
                        'cc_supplier_id' => $request->cc_supplier_id[$k] ? $request->cc_supplier_id[$k] : null,
                        'cc_employee_id' => $request->cc_employee_id[$k] ? $request->cc_employee_id[$k] : null,
                        'cc_car_id' => $request->cc_car_id[$k] ? $request->cc_car_id[$k] : null,
                        'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
                        'journal_user_entry_id' => auth()->user()->user_id,
                        'cost_center_type_id' => $cost_center_type->system_code_id,
                        'cost_center_id' => isset($cost_center_id) ? $cost_center_id : null,
                        'cc_voucher_id' => isset($cc_voucher_id) ? $cc_voucher_id : null,
                    ]);

                }
            }
        }

        DB::commit();
        return redirect()->route('journal-entries.edit', $id)->with(['success' => 'تم التحديث']);

    }

    public
    function delete()
    {
        $journal_detail = JournalDt::find(request()->journal_dt_id);
        $journal_detail->delete();
        return response()->json(['success' => 'deleted']);
    }

    public
    function show($id)
    {

        $journal_hd = JournalHd::find($id);
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 33)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 33)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $journal_hd->company_group_id)->get();
        return view('JournalEntries.show', compact('journal_hd', 'attachments', 'notes',
            'attachment_types'));
    }


    public function edit_2($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $journal_hd = JournalHd::findOrFail($id);

        $flag = 0;

        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 33 && $job_permission->permission_approve) {
                    $flag += 1;
                }
            }
        } else {
            $flag += 1;
        }
        $branch = session('branch');
        $journal_statuses = SystemCode::where('sys_category_id', 9)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', [903, 904, 905, $journal_hd->journalStatus->system_code])
            ->select('system_code_id', 'system_code_name_ar')->get();

        $current_date = Carbon::parse($journal_hd->journal_hd_date)->format('Y-m-d\TH:i');
        $journal_dts = DB::table('journal_details')
            ->where('journal_details.journal_hd_id', '=', $journal_hd->journal_hd_id)
            ->leftJoin('accounts', function ($join) {
                $join->on('journal_details.account_id', '=', 'accounts.acc_id');
            })
            ->leftJoin('system_codes', function ($join) {
                $join->on('journal_details.cost_center_type_id', '=', 'system_codes.system_code_id');
            })
            ->leftJoin('customers', function ($join) {
                $join->on('journal_details.cc_customer_id', '=', 'customers.customer_id'); ///customers
            })
            ->leftJoin('customers as suppliers', function ($join) {
                $join->on('journal_details.cc_customer_id', '=', 'suppliers.customer_id'); ///suppliers
            })
            ->leftJoin('branches', function ($join) {
                $join->on('journal_details.cc_branch_id', '=', 'branches.branch_id');
            })
            ->leftJoin('employees', function ($join) {
                $join->on('journal_details.cc_employee_id', '=', 'employees.emp_id');
            })
            ->leftJoin('trucks', function ($join) {
                $join->on('journal_details.cc_car_id', '=', 'trucks.truck_id');
            })
            ->select('suppliers.customer_name_full_ar as supplier_name_full_ar',
                'suppliers.customer_id as supplier_id', 'customers.customer_name_full_ar as customer_name_full_ar',
                'customers.customer_id as customer_id', 'account_id', 'journal_dt_notes',
                'journal_dt_date', 'journal_dt_id', 'journal_dt_debit', 'journal_dt_credit', 'employees.emp_name_full_ar', 'trucks.truck_code',
                'trucks.truck_name', 'branches.branch_name_ar', 'system_codes.system_code', 'system_codes.system_code_name_ar')
            ->paginate(50);


        $accounts = DB::table('accounts')->where('accounts.company_group_id', $company->company_group_id)
            ->where('accounts.acc_level', $company->companyGroup->accounts_levels_number)->select('acc_id', 'acc_code', 'acc_name_ar')
            ->get();

        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();

        return view('JournalEntries.edit_2', compact('journal_hd', 'flag', 'journal_statuses', 'journal_dts',
            'accounts', 'branch', 'current_date', 'account_types'));
    }


    public function update_2($id, Request $request)
    {

        DB::beginTransaction();


        $company = session('company') ? session('company') : auth()->user()->company;

        $journal_hd = JournalHd::find($id);

        $account_period = AccounPeriod::where('acc_period_year', Carbon::parse($request->journal_hd_date)->format('Y'))
            ->where('acc_period_month', Carbon::parse($request->journal_hd_date)->format('m'))
            ->where('acc_period_is_active', 1)->first();

        foreach ($request->journal_details_ids as $k => $journal_details_ids) {
            if ($journal_details_ids != 0) {
                $journal_detail = JournalDt::find($journal_details_ids);
                $journal_detail->update([
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : $journal_detail->period_id,
//                    'journal_dt_date' => $request->journal_dt_date,
                    'journal_status' => $request->journal_status,
                    'account_id' => $request->account_id[$k],
                    'journal_dt_notes' => $request->journal_dt_notes[$k],
                    'journal_dt_date' => date('Y-m-d', strtotime(str_replace('-', '/', $request->journal_dt_date[$k]))),
                    'journal_dt_debit' => $request->journal_dt_debit[$k],
                    'journal_dt_credit' => $request->journal_dt_credit[$k],
                    'journal_dt_balance' => $request->journal_dt_credit[$k] - $request->journal_dt_debit[$k],
                    'journal_user_entry_id' => auth()->user()->user_id,
                ]);
            } elseif ($journal_details_ids == 0 && $request->account_id[$k] != null) {

                if ($request->customer_cost_center_id[$k]) {
                    $cost_center_id = $request->customer_cost_center_id[$k] ? $request->customer_cost_center_id[$k] : '';
                    $cc_voucher_id = $request->customer_cc_voucher_id[$k] ? $request->customer_cc_voucher_id[$k] : '';
                }

                if ($request->supplier_cost_center_id[$k]) {
                    $cost_center_id = $request->supplier_cost_center_id[$k] ? $request->supplier_cost_center_id[$k] : '';
                    $cc_voucher_id = $request->supplier_cc_voucher_id[$k] ? $request->supplier_cc_voucher_id[$k] : '';
                }

                if ($request->employee_cost_center_id[$k]) {
                    $cost_center_id = $request->employee_cost_center_id[$k] ? $request->employee_cost_center_id[$k] : '';
                    $cc_voucher_id = $request->employee_cc_voucher_id[$k] ? $request->employee_cc_voucher_id[$k] : '';
                }

                if ($request->cc_car_id[$k]) {
                    $cc_car_id = $request->cc_car_id[$k] ? $request->cc_car_id[$k] : '';
                }

                if ($request->cc_branch_id[$k]) {
                    $cc_branch_id = $request->cc_branch_id[$k];
                }

                //  return $request->cc_employee_id[$k];
                $cost_center_type = SystemCode::where('system_code', $request->cost_center_type_id[$k])->first();

                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_hd->journal_type_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => date('Y-m-d', strtotime(str_replace('-', '/', $request->journal_dt_date[$k]))),
                    'journal_status' => $request->journal_status,
                    'account_id' => $request->account_id[$k],
                    'journal_dt_notes' => $request->journal_dt_notes[$k],
                    'journal_dt_debit' => $request->journal_dt_debit[$k],
                    'journal_dt_credit' => $request->journal_dt_credit[$k],
                    'journal_dt_balance' => $request->journal_dt_credit[$k] - $request->journal_dt_debit[$k],
                    'cc_customer_id' => $request->cc_customer_id[$k] ? $request->cc_customer_id[$k] : null,
                    'cc_supplier_id' => $request->cc_supplier_id[$k] ? $request->cc_supplier_id[$k] : null,
                    'cc_employee_id' => $request->cc_employee_id[$k] ? $request->cc_employee_id[$k] : null,
                    'cc_car_id' => $request->cc_car_id[$k] ? $request->cc_car_id[$k] : null,
                    'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cost_center_type_id' => $cost_center_type->system_code_id,
                    'cost_center_id' => isset($cost_center_id) ? $cost_center_id : null,
                    'cc_voucher_id' => isset($cc_voucher_id) ? $cc_voucher_id : null,
                ]);

            }
        }

        $journal_dts = DB::table('journal_details')
            ->where('journal_details.journal_hd_id', '=', $journal_hd->journal_hd_id);
        $total_credit = $journal_dts->sum('journal_dt_credit');
        $total_debit = $journal_dts->sum('journal_dt_debit');

        if ($total_credit != $total_debit) {

            DB::rollBack();

            return redirect()->back()->with('error',
                "الفرق بين الدائن والمدين لا يساوي صفر");
        }

        $journal_hd->update([
            //  'journal_type_id' => $request->journal_type_id,
            'journal_hd_date' => $request->journal_hd_date,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : $journal_hd->period_id,
            'journal_status' => $request->journal_status,
            'journal_file_no' => $request->journal_file_no,
            'journal_hd_notes' => $request->journal_hd_notes,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_credit' => $total_credit,
            'journal_hd_debit' => $total_debit
        ]);

        DB::commit();
        // something went wrong


        return redirect()->route('journal-entries.edit_2', $id)->with(['success' => 'تم التحديث']);
    }


    public
    function createSheet()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $journal_types = SystemCode::where('sys_category_id', 8)
            ->where('company_group_id', $company->company_group_id)->get();
        $journal_statuses = SystemCode::where('sys_category_id', 9)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', [901, 902, 903, 904])->get();
        return view('JournalEntries.createSheet', compact('companies', 'journal_types', 'journal_statuses'));
    }

    public
    function export()
    {
        return Excel::download(new \App\Exports\JournalDataExport(), 'journals.xlsx');
    }

//    public function exportUsers()
//    {
//        return Excel::download(new CustomersSuppliersExport(), 'customers_suppliers.xlsx');
//    }

    public
    function approveJournal(Request $request)
    {
        $journal = JournalHd::find($request->journal_hd_id);
        $journal_status = SystemCode::where('company_group_id', $journal->company_group_id)
            ->where('system_code', 903)->first();
        $journal->journal_status = $journal_status->system_code_id;
        $journal->save();
        return back();
    }

    public function updateJournalStatus(Request $request)
    {
        $journal = JournalHd::find($request->journal_hd_id);
        $journal->journal_status = $request->journal_status;
        $journal->save();
        return back();
    }
}
