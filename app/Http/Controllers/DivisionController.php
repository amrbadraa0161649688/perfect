<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\DivisionResource;
use App\Models\Company;
use App\Models\Department;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DivisionController extends Controller
{

    public function store(Request $request)
    {
        $query = Department::where('department_id', $request->department_id)->first();
        $division_old = Division::where('company_Group_id', $query->company_group_id)
            ->where('division_code', $request->division_code)->first();
        if (isset($division_old)) {
            return response()->json(['status' => 500, 'data' => 'لا يمكن اضافه قسم بهذا الكود لنفس الشركه الرئيسيه']);
        }

        $division = Division::create([
            'department_id' => request()->department_id,
            'company_id' => json_encode($request->company_id),
            'company_group_id' => $query->company_group_id,
            'division_name_ar' => $request->division_name_ar,
            'division_name_en' => $request->division_name_en,
            'division_code' => $request->division_code,
            'division_status' => $request->division_status,
        ]);

        foreach ($request->company_id as $company_id) {
            $company = Company::find($company_id);
            $company->divisions()->attach($division->division_id, ['str_type' => 'division']);
        }

        return route('administrativeStructures', ['qr' => 'division']);
    }

    public function edit($id)
    {
        $division = Division::find($id);
        $companies_all_ids = Department::where('department_id', $division->department_id)
            ->first()->company_id;
        $companies_selected_ids = $division->company_id;
        $companies = Company::whereIn('company_id', array_diff(json_decode($companies_all_ids), json_decode($companies_selected_ids)))->get();
        return view('Divisions.edit', compact('division', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $division = Division::find($id);
        if ($division->division_code !== $request->division_code) {
            $division_old = Division::where('division_code', $request->division_code)
                ->where('company_group_id', $division->company_group_id)->first();
            if (isset($division_old)) {
                return back()->with(['error' => 'يوجد قسم بنفس الكود في الشركه الرئيسيه']);
            }
        }

        $division->update([
            'company_id' => isset($request->company_id) ? json_encode(array_merge($request->company_id, json_decode($division->company_id))) : $division->company_id,
            'division_name_ar' => $request->division_name_ar,
            'division_name_en' => $request->division_name_en,
            'division_code' => $request->division_code,
            'division_status' => $request->division_status,
        ]);

        if ($request->company_id) {
            foreach ($request->company_id as $company_id) {
                $company = Company::find($company_id);
                $company->divisions()->attach($division->division_id, ['str_type' => 'division']);
            }
        }

        return back()->with('message', 'تم تعديل القسم');
    }

    public
    function delete($id)
    {
        $division = Division::find($id);
        if ($division->jobs->count() > 0) {
            return redirect('/company-administrative-structure/departments?qr=division')
                ->withErrors(['msg' => 'لا يمكن حذف القسم لانها تحتوي علي وظائف']);
        }
        foreach (json_decode($division->company_id) as $company_id) {
            $company = Company::find($company_id);
            $company->divisions()->detach($division->division_id);
        }
        $division->delete();
        return redirect('/company-administrative-structure/departments?qr=division')
            ->with(['error' => 'تم الحذف']);
    }


    public
    function getCompaniesForStore()
    {
        $companies = Company::whereIn('company_id', json_decode(Department::where('department_id', request()->department_id)->first()->company_id))->get();
        return response()->json(['status' => 200, 'data' => $companies]);
    }

}
