<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\CompanyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyAppController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'app_id' => 'required',
            'co_app_is_active' => 'required'
        ]);

        $company = Company::where('company_id', $request->company_id)->first();
        if ($company->apps->contains($request->app_id)) {
            return back()->withErrors(['msg' => 'تم اضافه النظام من قبل']);
        }
        CompanyApp::create([
            'company_id' => $request->company_id,
            'company_group_id' => $company->companyGroup->company_group_id,
            'app_id' => $request->app_id,
            'co_app_is_active' => $request->co_app_is_active
        ]);
        return redirect('/company/' . $company->company_id . '/edit?qr=applications')->with('success', ' تم اضافه النظام للشركه  ');
    }

    ////////////remain
    public function update($id, Request $request)
    {
        $company_app = CompanyApp::find($id);
        $company_app->update([
            'co_app_is_active' => $request->co_app_is_active
        ]);
        return redirect('/company/' . $company_app->company_id . '/edit?qr=applications')->with('success', 'تم تعديل النظام ');
    }

    public function delete($id, Request $request)
    {
//        $app = Application::where('app_id',$id)->first();
        $company_app = CompanyApp::where('company_id', $request->company_id)->where('company_app_id', $id)->first();
        $company_app->delete();
        return back()->with(['error' => 'تم الحذف']);
    }
}
