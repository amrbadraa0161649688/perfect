<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\DivisionResource;
use App\Http\Resources\JobResource;
use App\Models\Company;
use App\Models\Division;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{

    public function store(Request $request)
    {
        $division = Division::find($request->division_id);

        $job_old = Job::where('company_group_id', $division->company_group_id)->where('job_code', $request->job_code)->first();
        if (isset($job_old)) {
            return response()->json(['status' => 500 ,
                'data' => 'لا يمكن اضافه وظيفه بنفس الكود لنفس الرشكه الرئيسيه']);
        }

        //return $request->division_company_id;
        $job = Job::create([
            'division_id' => $request->division_id,
            'department_id' => $division->department_id,
            'company_id' => json_encode($request->division_company_id),
            'company_group_id' => $division->company_group_id,
            'job_name_ar' => $request->job_name_ar,
            'job_name_en' => $request->job_name_en,
            'job_code' => $request->job_code,
            'job_status' => isset($request->job_status) ? 1 : 0,
        ]);

        foreach ($request->division_company_id as $company_id) {
            $company = Company::find($company_id);
            $company->jobs()->attach($job->job_id, ['str_type' => 'job']);
        }

        return route('administrativeStructures', ['qr' => 'job']);
    }

    public function edit($id)
    {
        $job = Job::find($id);
        $companies_all_ids = Division::where('division_id', $job->division_id)->first()->company_id;
        $companies_selected_ids = Job::where('job_id', $job->job_id)->first()->company_id;
        $companies = Company::whereIn('company_id', array_diff(json_decode($companies_all_ids), json_decode($companies_selected_ids)))->get();
        return view('Jobs.edit', compact('job', 'companies'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'company_id' => 'array',
            'job_name_ar' => 'required',
            'job_name_en' => 'required',
            'job_code' => 'required',
            'job_status' => 'required',
        ]);

        $job = Job::find($id);

        if ($job->job_code !== $request->job_code) {
            $job_old = Job::where('company_group_id', $job->company_group_id)
                ->where('job_code', $request->job_code)->first();
            if (isset($job_old)) {
                return back()->withErrors(['data' => 'يوجد وظيفه بهذا الكود بداخل الشركه الرئيسيه']);
            }
        }

        $job->update([
            'company_id' => isset($request->company_id) ? json_encode(array_merge($request->company_id, json_decode($job->company_id))) : $job->company_id,
            'job_name_ar' => $request->job_name_ar,
            'job_name_en' => $request->job_name_en,
            'job_code' => $request->job_code,
            'job_status' => $request->job_status,
        ]);

        if ($request->company_id) {
            foreach ($request->company_id as $company_id) {
                $company = Company::find($company_id);
                $company->jobs()->attach($job->job_id, ['str_type' => 'job']);
            }
        }

        return back()->with(['success' => 'تم التعديل']);
    }

    public function delete($id)
    {
        $job = Job::find($id);
        foreach (json_decode($job->company_id) as $company_id) {
            $company = Company::find($company_id);
            $company->jobs()->detach($job->job_id);
        }
        $job->delete();
        return redirect('/company-administrative-structure/departments?qr=job')->with(['error' => 'تم الحذف']);
    }


    public function getCompaniesForStore()
    {
        /// get company belongs to division
        $companies_ids = Division::where('division_id', request()->division_id)->first()->company_id;
        $companies = Company::whereIn('company_id', json_decode($companies_ids))->get();
        return response()->json(['status' => 200, 'data' => CompanyResource::collection($companies)]);
    }

}
