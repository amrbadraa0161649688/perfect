<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Department;
use App\Models\Division;
use App\Models\Job;

class CompanyAdministrativeStructuresController extends Controller
{
    public function getCompanyDetails()
    {
        //get departments of company with divisions with jobs
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $departments = Department::where('company_group_id', $company->company_group_id)
            ->whereJsonContains('company_id', $company->company_id)->get();
        $divisions = Division::where('company_group_id', $company->company_group_id)
            ->whereJsonContains('company_id', $company->company_id)->get();
        $jobs = Job::where('company_group_id', $company->company_group_id)
            ->whereJsonContains('company_id', $company->company_id)->get();

        if (request()->company_id) {
            $company = Company::where('company_id', request()->company_id)->first();
            $companies = Company::where('company_group_id', $company->company_group_id)->get();
            $departments = Department::whereJsonContains('company_id', $company->company_id)->get();
            $divisions = Division::whereJsonContains('company_id', $company->company_id)->get();
            $jobs = Job::whereJsonContains('company_id', $company->company_id)->get();
        }

        return view('administrativeStructure.index',
            compact('departments', 'divisions', 'jobs', 'companies', 'company'));
    }

}
