<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccounPeriod;
use App\Models\AccountCompany;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\JournalDt;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function indexMain()
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $companies = DB::table('companies')->where('company_group_id', $company->company_group_id)->select('company_id', 'company_name_ar',
            'company_name_en', 'co_vat_paid', 'co_vat_collect')->get();

        $branches = DB::table('branches')->where('company_group_id', $company->company_group_id)->select('branch_id', 'branch_name_ar',
            'branch_name_en')->get();

        //   customers
        $customers_j = DB::table('journal_details')->whereIn('company_id', $companies->pluck('company_id')->toArray())
            ->where('cc_customer_id', '!=', 0);

        $customers_j_total = $customers_j->sum('journal_dt_debit') - $customers_j->sum('journal_dt_credit');
        $customers_j_count = count(array_unique($customers_j->pluck('cc_customer_id')->toArray()));

        //// suppliers
        $suppliers_j = DB::table('journal_details')->whereIn('company_id', $companies->pluck('company_id')->toArray())
            ->where('cc_supplier_id', '!=', 0);

        $suppliers_j_total = $suppliers_j->sum('journal_dt_debit') - $customers_j->sum('journal_dt_credit');
        $suppliers_j_count = count(array_unique($suppliers_j->pluck('cc_supplier_id')->toArray()));


        /////banks
        $banks_main_account = Account::where('company_group_id', $company->company_group_id)->where('appearance', 'bank')->first();
        $banks_accounts_ids = Account::where('acc_parent', $banks_main_account->acc_id)->pluck('acc_id')->toArray();

        $banks_j = DB::table('journal_details')->whereIn('company_id', $companies->pluck('company_id')->toArray())
            ->whereIn('account_id', $banks_accounts_ids);

        $banks_j_total = $banks_j->sum('journal_dt_debit') - $banks_j->sum('journal_dt_credit');
        $banks_j_count = $banks_j->count();


        //// branches
        $branches_j = DB::table('journal_details')->whereIn('company_id', $companies->pluck('company_id')->toArray())
            ->where('cc_branch_id', '!=', 0);

        $branches_j_total = $branches_j->sum('journal_dt_debit') - $branches_j->sum('journal_dt_credit');
        $branches_j_count = count(array_unique($branches_j->pluck('cc_supplier_id')->toArray()));


        //// vat collect
        $vat_collect_j = DB::table('journal_details')->whereIn('company_id', $companies->pluck('company_id')->toArray())
            ->whereIn('account_id', $companies->pluck('co_vat_collect')->toArray());

        $vat_collect_j_total = $vat_collect_j->sum('journal_dt_debit') - $vat_collect_j->sum('journal_dt_credit');
        $vat_collect_j_count = $vat_collect_j->count();


        //// vat paid
        $vat_paid_j = DB::table('journal_details')->whereIn('company_id', $companies->pluck('company_id')->toArray())
            ->whereIn('account_id', $companies->pluck('co_vat_paid')->toArray());

        $vat_paid_j_total = $vat_paid_j->sum('journal_dt_debit') - $vat_paid_j->sum('journal_dt_credit');
        $vat_paid_j_count = $vat_paid_j->count();

        $customers_journal_dts = [];
        $suppliers_journal_dts = [];
        $branches_journal_dts = [];
        $banks_journal_dts = [];
        $vat_paid_journal_dts = [];
        $vat_collect_journal_dts = [];

        if (request()->qr == 'customers') {

            if (request()->company_id) {
                $customers_j = $customers_j->whereIn('company_id', request()->company_id);
            }

            if (request()->branch_id) {
                $customers_j = $customers_j->whereIn('branch_id', request()->branch_id);
            }

            if (request()->created_date_from) {
                $customers_j = $customers_j->whereDate('journal_dt_date', '>=', request()->created_date_from);
            }

            if (request()->created_date_to) {
                $customers_j = $customers_j->whereDate('journal_dt_date', '<=', request()->created_date_to);
            }

            $customers_journal_dts = $customers_j->get()->groupBy('cc_customer_id');
        }

        if (request()->qr == 'suppliers') {

            if (request()->company_id) {
                $suppliers_j = $suppliers_j->whereIn('company_id', request()->company_id);
            }

            if (request()->branch_id) {
                $suppliers_j = $suppliers_j->whereIn('branch_id', request()->branch_id);
            }

            if (request()->created_date_from) {
                $suppliers_j = $suppliers_j->whereDate('journal_dt_date', '>=', request()->created_date_from);
            }

            if (request()->created_date_to) {
                $suppliers_j = $suppliers_j->whereDate('journal_dt_date', '<=', request()->created_date_to);
            }

            $suppliers_journal_dts = $suppliers_j->get()->groupBy('cc_supplier_id');
        }

        if (request()->qr == 'branches') {

            if (request()->company_id) {
                $branches_j = $branches_j->whereIn('company_id', request()->company_id);
            }

            if (request()->branch_id) {
                $branches_j = $branches_j->whereIn('branch_id', request()->branch_id);
            }

            if (request()->created_date_from) {
                $branches_j = $branches_j->whereDate('journal_dt_date', '>=', request()->created_date_from);
            }

            if (request()->created_date_to) {
                $branches_j = $branches_j->whereDate('journal_dt_date', '<=', request()->created_date_to);
            }

            $branches_journal_dts = $branches_j->get()->groupBy('cc_branch_id');

        }

        if (request()->qr == 'banks') {
            if (request()->company_id) {
                $banks_j = $banks_j->whereIn('company_id', request()->company_id);
            }

            if (request()->branch_id) {
                $banks_j = $banks_j->whereIn('branch_id', request()->branch_id);
            }

            if (request()->created_date_from) {
                $banks_j = $banks_j->whereDate('journal_dt_date', '>=', request()->created_date_from);
            }

            if (request()->created_date_to) {
                $banks_j = $banks_j->whereDate('journal_dt_date', '<=', request()->created_date_to);
            }

            $banks_journal_dts = $banks_j->get()->groupBy('account_id');
        }

        if (request()->qr == 'vatPaid') {

            if (request()->company_id) {
                $vat_paid_j = $vat_paid_j->whereIn('company_id', request()->company_id);
            }

            if (request()->branch_id) {
                $vat_paid_j = $vat_paid_j->whereIn('branch_id', request()->branch_id);
            }

            if (request()->created_date_from) {
                $vat_paid_j = $vat_paid_j->whereDate('journal_dt_date', '>=', request()->created_date_from);
            }

            if (request()->created_date_to) {
                $vat_paid_j = $vat_paid_j->whereDate('journal_dt_date', '<=', request()->created_date_to);
            }

            $vat_paid_journal_dts = $vat_paid_j->get()->groupBy('cc_voucher_id');
        }

        if (request()->qr == 'vatCollect') {

            if (request()->company_id) {
                $vat_collect_j = $vat_collect_j->whereIn('company_id', request()->company_id);
            }

            if (request()->branch_id) {
                $vat_collect_j = $vat_collect_j->whereIn('branch_id', request()->branch_id);
            }

            if (request()->created_date_from) {
                $vat_collect_j = $vat_collect_j->whereDate('journal_dt_date', '>=', request()->created_date_from);
            }

            if (request()->created_date_to) {
                $vat_collect_j = $vat_collect_j->whereDate('journal_dt_date', '<=', request()->created_date_to);
            }

            $vat_collect_journal_dts = $vat_collect_j->get()->groupBy('cc_voucher_id');
        }


        return view('Accounts.indexMain', compact('customers_j_total', 'customers_j_count',
            'suppliers_j_total', 'suppliers_j_count', 'branches_j_total', 'branches_j_count', 'vat_collect_j_total',
            'vat_collect_j_count', 'vat_paid_j_total', 'vat_paid_j_count', 'customers_journal_dts', 'suppliers_journal_dts',
            'branches_journal_dts', 'banks_j_total', 'banks_j_count', 'banks_journal_dts', 'vat_paid_journal_dts',
            'vat_collect_journal_dts', 'companies', 'branches'));
    }

    public function getAccountParents()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if (request()->acc_level > 1) {
            $accounts = Account::where('company_group_id', $company->company_group_id)
                ->where('acc_level', (request()->acc_level - 1))->get();
            return response()->json(['data' => $accounts]);
        } else {
            return response()->json(['status' => 500, 'message' => 'لا يوجد حسابات لهذا المستوي']);
        }
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $account_sheets = SystemCode::where('sys_category_id', 7)->get();
        $account_types = SystemCode::where('sys_category_id', 5)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        if (request()->account_id) {
            $parent_account = Account::where('acc_id', request()->account_id)->first();
            return view('Accounts.create', compact('account_sheets',
                'account_types', 'companies', 'parent_account'));
        } else {
            return view('Accounts.create', compact('account_sheets',
                'account_types', 'companies'));
        }

    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
//        $account = Account::where('acc_code', $request->acc_code)
//            ->where('acc_level', 1)
//            ->where('company_group_id', $company->company_group_id)->latest()->first();
//        return $account;
//        if (isset($account)) {
//            return back()->with(['error' => 'يوجد حساب بنفس الكود']);
//        }

        ///في حاله الشركه الفرعيه ليس لها نفس الاب
//        if ($request->account_parent) {
//            foreach ($request->company_id as $company_id) {
//                $company = Company::where('company_id', $lcompany_id)->first();
//                if (!$company->accounts->contains($request->account_parent)) {
//                    return back()->with(['error' => 'الشركات الفرعيه التي تم اختيارها غير صحيحه']);
//                }
//            }
//        }

        $acc_type = SystemCode::where('system_code_id', $request->acc_type)->latest()->first();
        $nature = $acc_type->system_code == 501 ? 'debit' : 'credit';
        $account = Account::create([
            'company_group_id' => $company->company_group_id,
            'acc_name_ar' => $request->acc_name_ar,
            'acc_name_en' => $request->acc_name_en,
            'acc_code' => $request->acc_code,
            'acc_level' => $request->acc_level,
            'acc_parent' => $request->acc_parent,
            'acc_sheet' => $request->acc_sheet,
            'acc_type' => $request->acc_type,
            'nature' => $nature,
            'acc_is_active' => $request->acc_is_active,
        ]);


        foreach ($request->company_id as $company_id) {
            $company = Company::where('company_id', $company_id)->first();
            $company->accounts()->attach($account->acc_id,
                ['company_group_id' => $company->company_group_id, 'acc_level' => $request->acc_level,
                    'acc_parent' => $request->acc_parent]);
        }

        return redirect()->route('accountTree')->with(['success' => 'تم الاضافه']);
    }

    public function edit($id)
    {
        $account = Account::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $account_sheets = SystemCode::where('sys_category_id', 7)->get();
        $account_types = SystemCode::where('sys_category_id', 5)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->pluck('company_id')->toArray();
//
//        $companies_selec = AccountCompany::where('company_group_id', $company->company_group_id)
//            ->where('acc_code', $account->acc_id)->pluck('company_id')->toArray();
//
//        $companies_ids = array_diff($companies, array_unique($companies_selec));
//        $companies_stat = Company::whereIn('company_id', $companies_ids)->get();
        $companies_stat = Company::where('company_group_id', $company->company_group_id)->get();

        return view('Accounts.edit', compact('account_sheets',
            'account_types', 'companies', 'account', 'companies_stat'));
    }

    public function update(Request $request, $id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $account = Account::find($id);

        $acc_type = SystemCode::where('system_code_id', $request->acc_type)->first();
        $nature = $acc_type->system_code == 501 ? 'debit' : 'credit';

        $account->update([
            'acc_name_ar' => $request->acc_name_ar,
            'acc_name_en' => $request->acc_name_en,
            'acc_code' => $request->acc_code,
            'acc_level' => $request->acc_level,
            'acc_parent' => $request->acc_parent,
            'acc_sheet' => $request->acc_sheet,
            'acc_type' => $request->acc_type,
            'nature' => $nature,
            'acc_is_active' => $request->acc_is_active,
        ]);

        foreach ($account->companies as $company) {
            $company->accounts()->detach($account->acc_id);
        }

        if ($request->company_id) {
            foreach ($request->company_id as $company_id) {
                $company = Company::where('company_id', $company_id)->first();
                $company->accounts()->attach($account->acc_id, ['company_group_id' => $company->company_group_id, 'acc_level' => $account->acc_level,
                    'acc_parent' => $account->acc_parent]);
            }
        }

        return redirect()->route('accountTree')->with(['success' => 'تم التحديث']);
    }

    public function delete($id)
    {
        $account = Account::find($id);
        if (count($account->journal_details)) {
            return back()->with(['error' => 'هذا الحساب يحتوي علي قيود . ,ولا يمكن حذفه']);
        }

        if ($account->acc_level == $account->companyGroup->accounts_levels_number) {
            $account->companies()->detach();
            $account->delete();
            return back()->with(['error' => 'تم الحذف']);
        }

        if (count($account->accounts) > 0) {
            return back()->with(['error' => 'هذا الحساب يحتوي علي حسابات فرعيه . لم يتم الحذف']);
        } elseif (count($account->accounts) == 0) {
            $account->companies()->detach();
            $account->delete();
            return back()->with(['error' => 'تم الحذف']);
        }

    }

    public function getSerial()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if (request()->acc_level == 1) {
            $root = Account::where('acc_level', 1)->where('company_group_id', $company->company_group_id)->latest()->first();

            if (isset($root)) {
                $serial = $root->acc_code + 1;
            } else {
                $serial = '1';
            }
            return response()->json(['data' => $serial]);
        } elseif (request()->acc_level > 1 && request()->acc_parent) {
            $account_parent = Account::where('acc_id', request()->acc_parent)->first();
            if (count($account_parent->accounts) > 0) {
                $child_account = Account::where('acc_parent', $account_parent->acc_id)
                    ->latest()->first();
                $child_account_serial_no = $child_account->acc_code;
                $array_number = explode('-', $child_account_serial_no);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;

                $array_number[count($array_number) - 1] = '0' . $array_number[count($array_number) - 1];
                $serial = implode('-', $array_number);
            } else {
                $serial = $account_parent->acc_code . '-01';
            }
            return response()->json(['data' => $serial, 'account_parent' => $account_parent]);
        } elseif (request()->acc_parent && !request()->acc_level) {
            $account_parent = Account::where('acc_id', request()->acc_parent)->first();
            if (count($account_parent->accounts) > 0) {
                $child_account = Account::where('acc_parent', $account_parent->acc_id)
                    ->latest()->first();
                $child_account_serial_no = $child_account->acc_code;
                $array_number = explode('-', $child_account_serial_no);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;

                $array_number[count($array_number) - 1] = '0' . $array_number[count($array_number) - 1];
                $serial = implode('-', $array_number);
            } else {
                $serial = $account_parent->acc_code . '-01';
            }
            return response()->json(['data' => $serial, 'account_parent' => $account_parent]);
        }

    }

/////////////////////////////////////الفترات المحاسبيه

    public function indexperiod()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $periods = AccounPeriod::where('company_id', $company->company_id)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();


        return view('Accounts.index', compact('companies', 'periods'
        ));
    }


    public function storeperiod($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        //   $account = Account::where('acc_code', $request->acc_code)
        //      ->where('company_group_id', $company->company_group_id->first();


        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        //  $last_status_period = $periods->acc_period_is_active;


        $period = AccounPeriod::where('acc_period_id', $id)->first();
        if ($period->acc_period_is_active == "0") {

            $period->acc_period_is_active = "1";
            $period_save = $period->save();
        } else {
            $period->acc_period_is_active = "0";
            $period_save = $period->save();
        }

        $periods = AccounPeriod::where('company_id', $company->company_id)->get();

        return view('Accounts.index', compact('companies', 'periods'));
    }

}
