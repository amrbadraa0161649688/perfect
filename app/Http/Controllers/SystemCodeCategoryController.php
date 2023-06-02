<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCodeCategory;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemCodeCategoryController extends Controller
{
    public function index()
    {
        $system_codes_category = SystemCodeCategory::where('sys_category_app', "1")->get();
        $applications = Application::get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        return view('systemCodeCategory.index', compact('system_codes_category', 'companies',
            'applications', 'company'));
    }

    public function indexloc()
    {
        $system_codes_category = SystemCodeCategory::where('sys_category_id',34)->get();
        $applications = Application::get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        return view('systemCodeCategory.index', compact('system_codes_category', 'companies',
            'applications', 'company'));
    }

    public function store(Request $request)
    {
        SystemCodeCategory::create([
            'sys_category_name_ar' => $request->sys_category_name_ar,
            'sys_category_name_en' => $request->sys_category_name_en,
            'sys_category_app' => $request->sys_category_app,
            'sys_category_type' => $request->sys_category_type,
        ]);

        return back()->with(['success' => 'تم اضافه فئه كود نظام']);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'sys_category_name_ar' => 'required',
            'sys_category_name_en' => 'required',
            'sys_category_type' => 'required',
        ]);
        $system_code_category = SystemCodeCategory::find($id);

        $system_code_category->update([
            'sys_category_name_ar' => $request->sys_category_name_ar,
            'sys_category_name_en' => $request->sys_category_name_en,
            'sys_category_type' => $request->sys_category_type,
        ]);

        return back()->with(['success' => 'تم تعديل  فئه كود النظام']);
    }

    public function delete($id)
    {
        $system_code_category = SystemCodeCategory::find($id);
        if ($system_code_category->systemCodes->count() > 0) {
            return response()->json(['status' => 500, 'data' => 'can not be deleted']);
        }
        $system_code_category->delete();
        return response()->json(['status' => 200, 'data' => 'deleted']);

    }

//////////////////////////////////products


    public function index2()
    {
        $system_codes_category = SystemCodeCategory::where('sys_category_id', 28)->get();
        $applications = Application::get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        return view('systemCodeCategory.index', compact('system_codes_category', 'companies',
            'applications', 'company'));
    }

    public function store2(Request $request)
    {
        SystemCodeCategory::create([
            'sys_category_name_ar' => $request->sys_category_name_ar,
            'sys_category_name_en' => $request->sys_category_name_en,
            'sys_category_app' => $request->sys_category_app,
            'sys_category_type' => $request->sys_category_type,
        ]);

        return back()->with(['success' => 'تم اضافه فئه كود نظام']);
    }

    public function update2($id, Request $request)
    {
        $request->validate([
            'sys_category_name_ar' => 'required',
            'sys_category_name_en' => 'required',
            'sys_category_type' => 'required',
        ]);
        $system_code_category = SystemCodeCategory::find($id);

        $system_code_category->update([
            'sys_category_name_ar' => $request->sys_category_name_ar,
            'sys_category_name_en' => $request->sys_category_name_en,
            'sys_category_type' => $request->sys_category_type,
        ]);

        return back()->with(['success' => 'تم تعديل  فئه كود النظام']);
    }

    public function delete2($id)
    {
        $system_code_category = SystemCodeCategory::find($id);
        if ($system_code_category->systemCodes->count() > 0) {
            return response()->json(['status' => 500, 'data' => 'can not be deleted']);
        }
        $system_code_category->delete();
        return response()->json(['status' => 200, 'data' => 'deleted']);

    }


}
