<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Note;
use App\Models\SystemCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyController extends Controller
{

    public function index($id)
    {
        $company_group = CompanyGroup::where('company_group_id', $id)->first();
        return response()->json(['status' => 200, 'data' => $company_group->companies]);
    }

    public function create($id)
    {
        $company_group = CompanyGroup::where('company_group_id', $id)->first();
        return view('Companies.create', compact('company_group'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_logo' => 'required|file'
        ]);
        $logo = $this->getPhoto($request->company_logo);

        $company = Company::create(array(
            'company_group_id' => $request->company_group_id,
            'company_name_ar' => $request->company_name_ar,
            'company_name_en' => $request->company_name_en,
            'company_logo' => 'Companies/' . $logo,
            'company_register' => $request->company_register,
            'company_tax_no' => $request->company_tax_no,
            'company_postal_code' => $request->company_postal_code,
            'company_postal_box' => $request->company_postal_box,
            'co_responsible_person' => $request->co_responsible_person,
            'co_mobile_number' => $request->co_mobile_number,
            'co_phone_no' => $request->co_phone_no,
            'co_email' => $request->co_email,
            'co_address' => $request->co_address,
            'co_is_active' => $request->co_is_active,
            'co_branches_no' => $request->co_branches_no,
            'co_emp_no' => $request->co_emp_no,
            'co_open_date' => $request->co_open_date,
            'co_end_date' => $request->co_end_date,
        ));

        return redirect()->route('company.edit', $company->company_id)->with('success', 'تم اضافه الشركه الفرعيه ');;
    }


    public function edit($id)
    {
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 2)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 2)->get();
        $company = Company::where('company_id', $id)->first();
        $branches = Branch::where('company_id', $id)->latest()->get();
        $applications = Application::where('app_status', 1)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();
        return view('Companies.edit', compact('company', 'attachments', 'branches',
            'applications', 'attachment_types', 'notes'));
    }

    public function update(Request $request, $id)
    {
        if ($request->company_logo) {
            $logo = $this->getPhoto($request->company_logo);
        }
        $company = Company::where('company_id', $id)->first();
        $company->update(array(
            'company_name_ar' => $request->company_name_ar,
            'company_name_en' => $request->company_name_en,
            'company_logo' => isset($logo) ? 'Companies/' . $logo : $company->company_logo,
            'company_register' => $request->company_register,
            'company_tax_no' => $request->company_tax_no,
            'company_postal_code' => $request->company_postal_code,
            'company_postal_box' => $request->company_postal_box,
            'co_responsible_person' => $request->co_responsible_person,
            'co_mobile_number' => $request->co_mobile_number,
            'co_phone_no' => $request->co_phone_no,
            'co_email' => $request->co_email,
            'co_address' => $request->co_address,
            'co_is_active' => $request->co_is_active,
            'co_branches_no' => $request->co_branches_no,
            'co_emp_no' => $request->co_emp_no,
            'co_open_date' => $request->co_open_date,
            'co_end_date' => $request->co_end_date,
        ));
        return redirect('/company/' . $company->company_id . '/edit?qr=data')->with('success', 'تم تعديل الشركه الفرعيه ');;
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('Companies'), $name);
        return $name;
    }
}
