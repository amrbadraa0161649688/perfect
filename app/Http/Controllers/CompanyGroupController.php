<?php

namespace App\Http\Controllers;

use App\Models\CompanyGroup;
use Illuminate\Http\Request;

class CompanyGroupController extends Controller
{
    public function index()
    {
        $companies_group = CompanyGroup::latest()->get();
        return view('CompaniesGroup.index', compact('companies_group'));
    }

    public function create()
    {
        return view('CompaniesGroup.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_group_logo' => 'required|file'
        ]);

        if ($request->company_group_logo) {
            $logo = $this->getPhoto($request->company_group_logo);
        }

        CompanyGroup::create([
            'company_group_ar' => $request->company_group_ar,
            'company_group_en' => $request->company_group_en,
            'company_group_logo' => 'Companies/' . $logo,
            //'company_group_logo' => '/images/123.png',
            'commercial_register' => $request->commercial_register,
            'tax_number' => $request->tax_number,
            'postal_code' => $request->postal_code,
            'postal_box' => $request->postal_box,
            'responsible_person' => $request->responsible_person,
            'mobile_number' => $request->mobile_number,
            'phone_no' => $request->phone_no,
            'main_email' => $request->main_email,
            'main_address' => $request->main_address,
            'companys_number' => $request->companys_number,
            'c_group_is_active' => $request->c_group_is_active,
            'open_date' => $request->open_date,
            'end_date' => $request->end_date
        ]);
        return redirect()->route('mainCompanies')->with('success', 'تم اضافه الشركه الرئيسيه ');
    }

    public function show($id)
    {
        $company_group = CompanyGroup::where('company_group_id', $id)->first();
        return response()->json(['status' => 200, 'data' => $company_group]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'company_group_logo' => 'file'
        ]);

        $company_group = CompanyGroup::where('company_group_id', $id)->first();
        if ($file=$request->file('company_group_logo')) {
            $logo = $this->getPhoto($file);
        }
        $company_group->update([
            'company_group_ar' => $request->company_group_ar,
            'company_group_en' => $request->company_group_en,
            'company_group_logo' => isset($logo) ? 'Companies/' . $logo : $company_group->company_group_logo,
            // 'company_group_logo' => '/images/123.png',
            'commercial_register' => $request->commercial_register,
            'tax_number' => $request->tax_number,
            'postal_code' => $request->postal_code,
            'postal_box' => $request->postal_box,
            'responsible_person' => $request->responsible_person,
            'mobile_number' => $request->mobile_number,
            'phone_no' => $request->phone_no,
            'main_email' => $request->main_email,
            'main_address' => $request->main_address,
            'companys_number' => $request->companys_number,
            'c_group_is_active' => $request->c_group_is_active,
            'open_date' => $request->open_date,
            'end_date' => $request->end_date
        ]);

        return back()->with('success', 'تم تعديل الشركه الرئيسيه ');
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('Companies'), $name);
        return $name;
    }
}
