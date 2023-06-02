<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationMenuResource;
use App\Http\Resources\JobPermissionResource;
use App\Models\Application;
use App\Models\ApplicationsMenu;
use App\Models\Company;
use App\Models\CompanyApp;
use App\Models\CompanyGroup;
use App\Models\Job;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{

    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $jobs = Job::whereJsonContains('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $jobs = Job::whereJsonContains('company_id', request()->company_id)->get();
        }

        return view('JobPermissions.index', compact('jobs', 'companies'));

    }

    public function show($id)
    {
        $job = Job::find($id);
        $companies = Company::whereIn('company_id', json_decode($job->company_id))->get();
        return view('jobPermissions.show', compact('job', 'companies'));
    }

    public function getCompanyApplications()
    {
        $applications_ids = array_unique(CompanyApp::where('company_id', request()->company_id)->pluck('app_id')->toArray());
        $applications = Application::whereIn('app_id', $applications_ids)->get();
        return response()->json(['status' => 200, 'data' => $applications]);
    }

    public function getApplicationsMenu()
    {
        $applications_menu = ApplicationsMenu::where('app_id', request()->application_id)->get();
        return response()->json(['status' => 200, 'data' => ApplicationMenuResource::collection($applications_menu)]);
    }


    public function getRemainApplicationMenu()
    {
        $application_menu_ids = ApplicationsMenu::where('app_id', request()->app_id)->pluck('app_menu_id')->toArray();
        $job_permissions_application_menu_ids = Permission::where('job_id', request()->job_id)->where('company_id', request()->company_id)->pluck('app_menu_id')->toArray();
        $applications_menu_sub_ids = array_diff($application_menu_ids, $job_permissions_application_menu_ids);
        $application_menu = ApplicationsMenu::whereIn('app_menu_id', $applications_menu_sub_ids)->get();
        return response()->json(['data' => $application_menu])->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $app_menu = ApplicationsMenu::where('app_menu_id', $request->app_menu_id)->first();
        $company = session('company') ? session('company') : auth()->user()->company;

        if (session('job')) {
            if ($request->job_id == session('job')['job_id']) {
                $job = Job::where('job_id', $request->job_id)->first();
                $request->session()->put('job', $job);
            }
        }
        Permission::create([
            'company_id' => $company->company_id,
            'company_group_id' => $company->company_group_id,
            'job_id' => $request->job_id,
            'app_menu_id' => $request->app_menu_id,
            'permission_name' => $app_menu->app_menu_name_en,
            'permission_name_ar' => $app_menu->app_menu_name_ar,
            'permission_name_en' => $app_menu->app_menu_name_en,
            'permission_view' => $request->has('permission_view') ? 1 : 0,
            'permission_add' => $request->has('permission_add') ? 1 : 0,
            'permission_update' => $request->has('permission_update') ? 1 : 0,
            'permission_delete' => $request->has('permission_delete') ? 1 : 0,
            'permission_print' => $request->has('permission_print') ? 1 : 0,
            'permission_approve' => $request->has('permission_approve') ? 1 : 0,
            'permission_status' => 1,
            'permission_gurad_name' => 'web',
            'created_user' => auth()->user()->user_id,
            'updated_user' => auth()->user()->user_id
        ]);
        return back()->with(['success', 'تم اضافه صلاحيه']);
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (session('job')) {
            if ($permission->job_id == session('job')['job_id']) {
                $job = Job::where('job_id', $permission->job_id)->first();
                $request->session()->put('job', $job);
            }
        }
        $permission->update([
            'permission_view' => $request->has('permission_view') ? 1 : 0,
            'permission_add' => $request->has('permission_add') ? 1 : 0,
            'permission_update' => $request->has('permission_update') ? 1 : 0,
            'permission_delete' => $request->has('permission_delete') ? 1 : 0,
            'permission_print' => $request->has('permission_print') ? 1 : 0,
            'permission_approve' => $request->has('permission_approve') ? 1 : 0,
            'updated_user' => auth()->user()->user_id
        ]);
        return back()->with(['success', 'تم تعديل صلاحيه']);
    }

    public function delete($id)
    {
        $permission = Permission::find($id);
        $permission->Delete();
        return back()->with(['error', 'تم حذف صلاحيه']);
    }
}
