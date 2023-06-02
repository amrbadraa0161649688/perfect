<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\DepartmentResource;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function store(Request $request)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company_id;
        $department_old = Department::where('company_group_id', $company_group->company_group_id)
            ->where('department_code', $request->department_code)->first();
        if (isset($department_old)) {
            return back()->withErrors(['msg' => 'لا يمكن اضافه اداره بنفس الكود لنفس الشركه الرئيسيه']);
        }
        $department = Department::create([
            'company_group_id' => $company_group->company_group_id,
            'company_id' => json_encode($request->company_id),
            'department_name_ar' => $request->department_name_ar,
            'department_name_en' => $request->department_name_en,
            'department_code' => $request->department_code,
            'created_user' => auth()->user()->user_id,
        ]);

        foreach ($request->company_id as $company_id) {
            $company = Company::find($company_id);
            $company->departments()->attach($department->department_id, array('str_type' => 'department'));
        }

        return redirect('/company-administrative-structure/departments?qr=department')->with('success', 'تم اضافه ادراه');
    }

    public function update($id, Request $request)
    {
        $department = Department::where('department_id', $id)->first();
        if ($department->department_code !== $request->department_code) {
            $department_old = Department::where('department_code', $request->department_code)->where('company_group_id', $department->company_group_id)->first();
            if (isset($department_old)) {
                return back()->with(['error' => 'يوجد اداره بنفس الكود في الشركه الرئيسيه']);
            }
        }
        $department->update([
            'department_name_en' => $request->department_name_en,
            'department_name_ar' => $request->department_name_ar,
            'department_code' => $request->department_code,
            'company_id' => isset($request->company_id) ? json_encode(array_merge($request->company_id, json_decode($department->company_id))) : $department->company_id,
            'updated_user' => auth()->user()->user_id
        ]);
        if ($request->company_id) {
            foreach ($request->company_id as $company_id) {
                $company = Company::find($company_id);
                $company->departments()->attach($department->department_id, array('str_type' => 'department'));
            }
        }
        return back()->with('success', 'تم تعديل اداره');
    }

    public function delete($id)
    {
        $department = Department::find($id);
        if ($department->divisions->count() > 0) {
            return redirect('/company-administrative-structure/departments?qr=department')
                ->with(['error' => 'لا يمكن حذف الاداره لانها تحتوي علي اقسام']);
        }

        foreach (json_decode($department->company_id) as $company_id) {
            $company = Company::find($company_id);
            $company->departments()->detach($department->department_id);
        }
        $department->delete();
        return redirect('/company-administrative-structure/departments?qr=department')
            ->with('error', 'تم حذف اداره ');
    }


    public function edit($id)
    {
        $department = Department::find($id);
        ///////////get companies already selected for this department by department code and company group id
        $companies_selected_ids = Department::where('department_id', $department->department_id)->first()->company_id;
        //return json_decode($companies_selected_ids);
        $companies_all = Company::where('company_group_id', $department->company_group_id)->pluck('company_id')->toArray();
        $companies = Company::whereIn('company_id', array_diff($companies_all, json_decode($companies_selected_ids)))->get();
        return view('Departments.edit', compact('department', 'companies'));
    }
}
