<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function store(Request $request)
    {

        $company = Company::where('company_id', $request->company_id)->first();
        Branch::create([
            'company_id' => $request->company_id,
            'company_group_id' => $company->companyGroup->company_group_id,
            'branch_name_ar' => $request->branch_name_ar,
            'branch_name_en' => $request->branch_name_en,
            'branch_address' => $request->branch_address,
            'branch_lat' => $request->branch_lat,
            'branch_lng' => $request->branch_lng,
            'branch_phone' => $request->branch_phone,
            'branch_start_date' => $request->branch_start_date,
            'branch_end_date' => $request->branch_end_date,
            'branch_code' => $request->branch_code,
            'branch_city_id' => 1
        ]);

        return redirect('/company/' . $company->company_id . '/edit?qr=branches')->with('success', 'تم اضافه الفرع ');;
//        return response()->json(['status' => 200, 'data' => $branch]);
    }

    public function show($id)
    {
        $branch = Branch::where('branch_id', $id)->with('company')->with('companyGroup')->first();
        return response()->json(['status' => 200, 'data' => $branch]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'branch_name_ar' => 'required',
            'branch_name_en' => 'required',
            'branch_address' => 'required',
//            'branch_lat' => 'required',
//            'branch_lng' => 'required',
            'branch_phone' => 'required',
            'branch_start_date' => 'required',
            'branch_end_date' => 'required',
            'branch_code' => 'required'
        ]);

        $company = Company::where('company_id', $request->company_id)->first();
        $branch = Branch::where('branch_id', $id)->first();
        $branch->update([
            'company_id' => $request->company_id,
            'company_group_id' => $company->companyGroup->company_group_id,
            'branch_name_ar' => $request->branch_name_ar,
            'branch_name_en' => $request->branch_name_en,
            'branch_address' => $request->branch_address,
//            'branch_lat' => $request->branch_lat,
//            'branch_lng' => $request->branch_lng,
            'branch_phone' => $request->branch_phone,
            'branch_start_date' => $request->branch_start_date,
            'branch_end_date' => $request->branch_end_date,
            'branch_code' => $request->branch_code,
        ]);

        return redirect('/company/' . $company->company_id . '/edit?qr=branches')->with('success', 'تم تعديل الفرع');;
    }

    public function getLocation($id)
    {
        $branch = Branch::where('branch_id', $id)->first();
        return view('Companies.Branches.location', compact('branch'));
    }
}
