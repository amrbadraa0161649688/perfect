<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\StoreAccBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreAccountsController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $store_accounts = StoreAccBranch::where('company_group_id', $company->company_group_id)->paginate();
        return view('Store.Accounts.index', compact('store_accounts'));
    }


    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)
            ->select('company_name_ar', 'company_id')->get();
        $company_id = $company->company_id;
        $journal_types = DB::table('journal_types')->where('company_group_id', $company->company_group_id)
            ->whereIn('journal_types_code', [35, 41, 46, 48, 61, 62, 71, 72, 73, 74, 75, 76, 77, 78, 79])
            ->select('journal_types_code', 'journal_types_name_ar')->get();

        $accounts = DB::table('accounts')->where('company_group_id', $company->company_group_id)
            ->select('acc_id', 'acc_name_ar', 'acc_name_en')->get();
        return view('Store.Accounts.create', compact('companies', 'company_id',
            'journal_types', 'accounts'));
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        StoreAccBranch::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => $request->company_group_id,
            'store_category_type_id' => $request->store_category_type_id,
            'journal_type_code' => $request->journal_type_code,
            'acc_id_1' => $request->acc_id_1,
            'acc_id_2' => $request->acc_id_2,
            'acc_id_3' => $request->acc_id_3,
            'acc_id_4' => $request->acc_id_4
        ]);

        return redirect()->route('storeAccounts.index')->with(['success' => 'تم اضافه حساب']);
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $store_account = StoreAccBranch::findOrFail($id);
        $journal_types = DB::table('journal_types')->where('company_group_id', $company->company_group_id)
            ->whereIn('journal_types_code', [35, 41, 46, 48, 61, 62, 71, 72, 73, 74, 75, 76, 77, 78, 79])
            ->select('journal_types_code', 'journal_types_name_ar')->get();

        $accounts = DB::table('accounts')->where('company_group_id', $company->company_group_id)
            ->select('acc_id', 'acc_name_ar', 'acc_name_en')->get();
        return view('Store.Accounts.edit', compact('store_account', 'journal_types', 'accounts'));
    }


    public function update(Request $request, $id)
    {
        $store_account = StoreAccBranch::findOrFail($id);
        $store_account->update([
            'journal_type_code' => $request->journal_type_code,
            'acc_id_1' => $request->acc_id_1,
            'acc_id_2' => $request->acc_id_2,
            'acc_id_3' => $request->acc_id_3,
            'acc_id_4' => $request->acc_id_4
        ]);

        return redirect()->route('storeAccounts.index')->with(['success' => 'تم التعديل']);
    }

    public function getBranches()
    {
        $company = DB::table('companies')->where('company_id', request()->company_id)->first();
        $branches = DB::table('branches')->where('company_id', $company->company_id)
            ->select('branch_id', 'branch_name_ar', 'branch_name_en')->get();

        $store_types = DB::table('system_codes')->where('sys_category_id', 55)
            ->where('company_id', request()->company_id)->select('system_code_name_ar',
                'system_code_name_en', 'system_code_id')->get();

        return response()->json(['data' => $branches, 'store_types' => $store_types]);
    }


}
