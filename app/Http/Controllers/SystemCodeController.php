<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use App\Models\Account;
use App\Models\SystemCodeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemCodeController extends Controller
{
    ///////////////get company group by system_code_category_id selected first
    public function getCompanyGroup()
    {
        $company_group_ids = SystemCodeCategory::where('sys_category_id', request()->sys_category_id)->pluck('company_group_id')->toArray();
        $company_groups = CompanyGroup::whereIn('company_group_id', $company_group_ids)->get();
        return response()->json(['status' => 200, 'data' => $company_groups]);
    }

    ////get companies by sys_category_id and company_group_id selected
    public function getCompany()
    {
        $company_ids = SystemCodeCategory::where('sys_category_id', request()->sys_category_id)->where('company_group_id', request()->company_group_id)->pluck('company_id')->toArray();
        $companies = Company::whereIn('company_id', $company_ids)->get();
        return response()->json(['status' => 200, 'data' => $companies]);
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_category_coed=SystemCodeCategory::find($request->sys_category_id);
       


        $system_code = SystemCode::create([
            'company_id' => $company->company_id,
            'company_group_id' => $company->company_group_id,
            'sys_category_id' => $request->sys_category_id,
            'system_code_name_ar' => $request->system_code_name_ar,
            'system_code_name_en' => $request->system_code_name_en,


            'system_code_search' => 0,
            'system_code_filter' => 0,
            'system_code' => $request->system_code,
            'system_code_url' => 1,
            'system_code_posted' => 0,//bit
            'system_code_status' => 1,//bit
            //'system_code_emp_id' => $request->system_code_emp_id,
            'system_code_acc_id' => $request->system_code_acc_id,
            // 'system_code_tax_perc' => $request->system_code_tax_perc,
        ]);

        return back()->with(['message' => 'تم اضافه كود النظام']);
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();
        $sys_code = SystemCode::find($id);
    //    $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
    $sys_codes_location  = $company->companyGroup->branches;
        $sys_codes_location_byab = SystemCode::where('sys_category_id', 139)->get();
        return view('systemCode.edit', compact('sys_code','accountL','sys_codes_location','sys_codes_location_byab'));

    }

    public function update($id, Request $request)
    {

        $system_code = SystemCode::find($id);
        $system_code->update([
            'system_code_name_ar' => $request->system_code_name_ar,
            'system_code_name_en' => $request->system_code_name_en,
            'system_code' => $request->system_code,


            'system_code_search' => $request->system_code_search,
            'system_code_filter' => $request->system_code_filter,
           'system_code_acc_id' => $request->system_code_acc_id,
//            'system_code_tax_perc' => $request->system_code_tax_perc,

'system_code_url' => $request->system_code_url,
            'system_code_posted' => $request->system_code_posted,
            'system_code_status' => $request->system_code_status,
//            'system_code_emp_id' => $request->system_code_emp_id,
        ]);

        return back()->with(['message' => 'تم تعديل كود النظام']);
    }

    public function delete($id)
    {
        $system_code = SystemCode::find($id);
        $system_code->delete();
        return response()->json(['status' => 200, 'data' => 'deleted']);
    }
}
