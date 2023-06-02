<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Attachment;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\EmployeeRequest;
use App\Models\Note;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use App\Models\User;
use App\Models\UserBranch;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use App\Models\Reports;

class EmployeeController extends Controller
{
    //
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $top_employees = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('system_code', 23)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();
        $all_employees = Employee::where('company_group_id', $company->company_group_id)->count();

        $vacation_employees = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('system_code', 115)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();
        $vacation_no_salary = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('system_code', 116)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

        $stopped_employees = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('system_code', 117)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();
        $end_employees = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('system_code', 118)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();


        $all_employeess = $top_employees + $vacation_employees + $vacation_no_salary + $stopped_employees;
        $employees = Employee::where('emp_default_company_id', $company->company_id)->get();

        $sys_codes_emp_status = SystemCode::where('sys_category_id', 4)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)
            ->where('company_group_id', $company->company_group_id)->get();

        $jobs = $company->jobs;


        $branches = $company->companyGroup->branches;


        if (request()->company_id) {
            $query = Employee::whereIn('emp_default_company_id', request()->company_id);
            $query_count = Employee::where('company_group_id', $company->company_group_id)
                ->whereIn('emp_default_company_id', request()->company_id);

            $all_employees = $query_count->count();
            $all_employeess = $top_employees + $vacation_employees + $vacation_no_salary + $stopped_employees;
            $top_employees = $query_count->where('emp_status', SystemCode::where('system_code', 23)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            $vacation_employees = Employee::whereIn('emp_default_company_id', request()->company_id)->where('emp_status', SystemCode::where('system_code', 115)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            $vacation_no_salary = Employee::whereIn('emp_default_company_id', request()->company_id)->where('emp_status', SystemCode::where('system_code', 116)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            $stopped_employees = Employee::whereIn('emp_default_company_id', request()->company_id)->where('emp_status', SystemCode::where('system_code', 117)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            $end_employees = Employee::whereIn('emp_default_company_id', request()->company_id)->where('emp_status', SystemCode::where('system_code', 118)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            $employees = $query->get();

            if (request()->from_date && request()->to_date) {
                $employees = $query->get();
            }
            if (request()->emp_status) {
                $query = $query->whereIn('emp_status', request()->emp_status);
                $employees = $query->get();
            }
            if (request()->emp_nationality) {
                $query = $query->whereIn('emp_nationality', request()->emp_nationality);
                $employees = $query->get();
            }
            if (request()->emp_private_mobile) {
                $query = $query->where('emp_private_mobile', request()->emp_private_mobile);
                $employees = $query->get();
            }
            if (request()->emp_identity) {
                $query = $query->where('emp_identity', request()->emp_identity);
                $employees = $query->get();
            }
            if (request()->emp_code_full) {
                $query = $query->where('emp_code', 'like', '%' . request()->emp_code_full . '%');
                $employees = $query->get();
            }
            if (request()->emp_name_full) {
                $query = $query->where('emp_name_full_ar', 'like', '%' . request()->emp_name_full . '%');
                $employees = $query->get();
            }

            if (request()->branch_id) {
                $query = $query->where('emp_default_branch_id', request()->branch_id);
                $employees = $query->get();
            }

            if (request()->job_id) {
                $employees_jobs_id = EmployeeContract::whereIn('emp_contract_job_id', request()->job_id)->pluck('emp_id')->toArray();
                $employees_ids = $employees->pluck('emp_id')->toArray();
                $employees = Employee::whereIn('emp_id', array_intersect($employees_jobs_id, $employees_ids))->get();
            }

            if (request()->from_date && request()->to_date) {
                $emp_attachments_ids = Attachment::whereDate('issue_date', '>=', request()->from_date)
                    ->whereDate('expire_date', '<=', request()->to_date)
                    ->where('app_menu_id', 8)->pluck('transaction_id')->toArray();

                $employees_ids = $employees->pluck('emp_id')->toArray();
                $employees = Employee::whereIn('emp_id', array_intersect($emp_attachments_ids, $employees_ids))->get();
            }


        }

        $saudi_employees = Employee::where('emp_nationality', 25)->count();
        $all_employeess_p = $all_employeess > 0 ? number_format(($saudi_employees / $all_employeess) * 100, 2) : 0;
        $all_employeess_v = $top_employees > 0 ? number_format(($vacation_employees / $top_employees) * 100, 2) : 0;
        $all_employeess_v_no = $top_employees > 0 ? number_format(($vacation_no_salary / $top_employees) * 100, 2) : 0;
        $all_employeess_s = $top_employees > 0 ? number_format(($stopped_employees / $top_employees) * 100, 2) : 0;
        $all_employeess_end = $all_employeess > 0 ? number_format(($end_employees / $all_employeess) * 100, 2) : 0;

        $emp_report = Reports::where('report_code', '80013')->get();

        return view('Employees.index', compact('companies', 'top_employees', 'jobs', 'branches', 'all_employeess', 'end_employees', 'emp_report',
            'all_employees', 'vacation_employees', 'vacation_no_salary', 'stopped_employees', 'employees', 'sys_codes_nationality_country',
            'sys_codes_emp_status', 'all_employeess_p', 'all_employeess_p', 'all_employeess_v', 'all_employeess_v_no', 'all_employeess_s', 'all_employeess_end'));

    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_status = SystemCode::where('sys_category_id', 4)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)
            ->get();

        $sys_codes_reasons_leaving = SystemCode::where('sys_category_id', 23)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_job_identity = SystemCode::where('sys_category_id', 22)
            ->get();

        $sys_codes_social_status = SystemCode::where('sys_category_id', 20)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_religion = SystemCode::where('sys_category_id', 21)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_sponsor_names = SystemCode::where('sys_category_id', 13)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)
            ->get();


        $sys_codes_gender = SystemCode::where('sys_category_id', 43)
            ->where('company_group_id', $company->company_group_id)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $companies_ids = CompanyGroup::where('company_group_id', $company->company_group_id)->first()
            ->companies->pluck('company_id')->toArray();
        $employees = Employee::whereIn('emp_default_company_id', $companies_ids)
            ->where('emp_category', 494)->get();

        return view('Employees.create', compact('sys_codes_status', 'sys_codes_countries', 'sys_codes_reasons_leaving',
            'sys_codes_job_identity', 'sys_codes_social_status', 'sys_codes_religion', 'sys_codes_sponsor_names',
            'sys_codes_nationality_country', 'sys_codes_gender', 'companies', 'employees', 'sys_codes_banks'));

    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        DB::beginTransaction();

        $request->validate([
            'emp_photo_url' => 'required|file'
        ]);

        $photo = $this->getPhoto($request->emp_photo_url);


        Employee::create([
            'emp_code' => $request->emp_code,
            'emp_name_full_ar' => $request->emp_name_full_ar,
            'emp_name_full_en' => $request->emp_name_full_en,
            'emp_photo_url' => 'Employees/' . $photo,

            'emp_name_1_ar' => $request->emp_name_1_ar,
            'emp_name_2_ar' => $request->emp_name_2_ar,
            'emp_name_3_ar' => $request->emp_name_3_ar,
            'emp_name_4_ar' => $request->emp_name_4_ar,

            'emp_name_1_en' => $request->emp_name_1_en,
            'emp_name_2_en' => $request->emp_name_2_en,
            'emp_name_3_en' => $request->emp_name_3_en,
            'emp_name_4_en' => $request->emp_name_4_en,

            'emp_nationality' => $request->emp_nationality,
            'emp_identity' => $request->emp_identity,
            'emp_status' => $request->emp_status,
            'emp_social_status' => $request->emp_social_status,
            'emp_work_start_date' => $request->emp_work_start_date,
            'emp_direct_date' => $request->emp_work_start_date,
            // 'emp_hijri_start_date' > $request->emp_hijri_start_date,
            'emp_family_count' => $request->emp_family_count,
            'emp_work_end_date' => $request->emp_work_end_date,
            // 'emp_hijri_end_date' > $request->emp_hijri_end_date,
            'emp_gender' => $request->emp_gender,////////
            'emp_reason_leaving' => $request->emp_reason_leaving,////////////
            'emp_religion' => $request->emp_religion,
            'emp_birthday' => $request->emp_birthday,
            'emp_birthday_hijiri' => $request->emp_birthday_hijiri,
            'emp_birth_country' => $request->emp_birth_country,
            'emp_birth_city' => $request->emp_birth_city,
            'emp_birth_address' => $request->emp_birth_address,
            'emp_private_mobile' => $request->emp_private_mobile,
            'emp_po_box_postal' => $request->emp_po_box_postal,
            'emp_current_address' => $request->emp_current_address,
            'emp_work_mobile' => $request->emp_work_mobile,
            'emp_email_work' => $request->emp_email_work,
            'emp_email_private' => $request->emp_email_private,
            'emp_sponsor_id' => $request->emp_sponsor_id,
            'issueNumber' => $request->issueNumber,
            'emp_previous_sponsor_name' => $request->emp_previous_sponsor_name,/////////////
            'emp_previous_sponsor_phone' => $request->emp_previous_sponsor_phone,
            'emp_job_in_identity' => $request->emp_job_in_identity,
            'company_group_id' => $company->company_group_id,
            'emp_default_company_id' => $request->emp_default_company_id,
            'emp_default_branch_id' => $request->emp_default_branch_id,
            'emp_manager_id' => $request->emp_manager_id,/////////
            'emp_bank_id' => $request->emp_bank_id,
            'emp_bank_account' => $request->emp_bank_account,
            'emp_is_bank_payment' => $request->emp_is_bank_payment ? 1 : 0,
            'emp_category' => 484
//            'emp_is_user_application' => $request->emp_is_user_application ? 1 : 0,

        ]);


//        if ($request->emp_is_user_application) {
//            $user = User::create([
//                'emp_id' => $employee->emp_id,
//                'company_group_id' => $employee->company_group_id,
//                'company_id' => $employee->emp_default_company_id,
//                'user_code' => $employee->emp_code,
//                'user_password' => Hash::make($request->password),
//                'user_email' => $employee->emp_email_private,
//                'user_name_ar' => $employee->emp_name_full_ar,
//                'user_name_en' => $employee->emp_name_full_en,
//                'user_profile_url' => $employee->emp_photo_url,
//                'user_default_branch_id' => $employee->emp_default_branch_id,
//                'user_status_id' => 1,
////            'user_token',
//                'user_start_date' => $employee->emp_work_start_date,
//                'user_end_date' => $employee->emp_work_end_date,
//                'user_mobile' => $employee->emp_private_mobile,
////            'user_otp',
//            ]);
//            UserBranch::create([
//                'user_id' => $user->user_id,
//                'company_id' => $user->company_id,
//                'branch_id' => $user->user_default_branch_id,
//                'user_branch_is_defaul' => 1,
//            ]);
//        }

        DB::commit();
        return redirect()->route('employees');

    }

    public function edit(Request $request, $id)

    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_status = SystemCode::where('sys_category_id', 4)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)
            ->get();
        $sys_codes_reasons_leaving = SystemCode::where('sys_category_id', 23)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_job_identity = SystemCode::where('sys_category_id', 22)
            ->get();
        $sys_codes_social_status = SystemCode::where('sys_category_id', 20)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_religion = SystemCode::where('sys_category_id', 21)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_sponsor_names = SystemCode::where('sys_category_id', 13)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)
            ->get();
        $sys_codes_banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_emp_category = SystemCode::where('sys_category_id', 42)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_gender = SystemCode::where('sys_category_id', 43)
            ->where('company_group_id', $company->company_group_id)->get();

        $employee = Employee::find($id);

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $companies_ids = CompanyGroup::where('company_group_id', $company->company_group_id)->first()
            ->companies->pluck('company_id')->toArray();

        $employees = Employee::whereIn('emp_default_company_id', $companies_ids)
            ->where('emp_category', 494)->get();


        $old_contract = EmployeeContract::where('emp_contract_is_active', 1)->first();

        if (isset($old_contract)) {
            $old_system_codes = $old_contract->salaries->pluck('emp_salary_item_id')->toArray();

            $system_codes_all = SystemCode::where('sys_category_id', 25)
                ->where('company_group_id', $company->company_group_id)->pluck('system_code_id')->toArray();

            $system_codes_sub = array_diff($system_codes_all, $old_system_codes);
            $salary_details = SystemCode::whereIn('system_code_id', $system_codes_sub)
                ->where('company_group_id', $company->company_group_id)->get();
        } else {
            $salary_details = SystemCode::where('sys_category_id', 25)
                ->where('company_group_id', $company->company_group_id)->get();
        }
        $request_types = SystemCode::where('sys_category_id', 46)->where('company_group_id', $company->company_group_id)->get();
        $employee_requests = EmployeeRequest::where('emp_request_approved', 1)->where('emp_id', $employee->emp_id)->get();

        $qr = '';
        if (request()->emp_request_type_id) {

            $employee_requests = EmployeeRequest::where('emp_request_approved', 1)->where('emp_id', $employee->emp_id)
                ->where('emp_request_type_id', request()->emp_request_type_id)->get();

            $qr = 'requests';

        }


        $attachments = Attachment::where('transaction_id', $employee->emp_id)->where('app_menu_id', 8)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)->where('company_group_id', $company->company_group_id)->get();
        $notes = Note::where('transaction_id', $employee->emp_id)->where('app_menu_id', 8)->get();

        $employee = $employee->refresh();

        return view('Employees.edit', compact('employee', 'sys_codes_status', 'sys_codes_countries',
            'sys_codes_reasons_leaving', 'sys_codes_job_identity', 'sys_codes_social_status', 'salary_details',
            'sys_codes_religion', 'sys_codes_sponsor_names', 'sys_codes_nationality_country', 'companies',
            'employees', 'attachments', 'attachment_types', 'sys_codes_gender', 'sys_codes_emp_category',
            'notes', 'sys_codes_banks',
            'request_types', 'employee_requests', 'qr', 'id'));

    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if ($request->emp_photo_url) {
            $photo = $this->getPhoto($request->emp_photo_url);
        }

        $employee->update([

            'emp_code' => $request->emp_code,
            'emp_name_full_ar' => $request->emp_name_1_ar . ' ' . $request->emp_name_2_ar . ' ' . $request->emp_name_3_ar . ' ' . $request->emp_name_4_ar,
            'emp_name_full_en' => $request->emp_name_1_en . ' ' . $request->emp_name_2_en . ' ' . $request->emp_name_3_en . ' ' . $request->emp_name_4_en,
            'emp_photo_url' => isset($photo) ? 'Employees/' . $photo : $employee->emp_photo_url,
            'emp_name_1_ar' => $request->emp_name_1_ar,
            'emp_name_2_ar' => $request->emp_name_2_ar,
            'emp_name_3_ar' => $request->emp_name_3_ar,
            'emp_name_4_ar' => $request->emp_name_4_ar,
            'emp_name_1_en' => $request->emp_name_1_en,
            'emp_name_2_en' => $request->emp_name_2_en,
            'emp_name_3_en' => $request->emp_name_3_en,
            'emp_name_4_en' => $request->emp_name_4_en,
            'emp_nationality' => $request->emp_nationality,
            'emp_identity' => $request->emp_identity,
            'emp_status' => $request->emp_status,
            'emp_social_status' => $request->emp_social_status,
            'emp_work_start_date' => isset($request->emp_work_start_date) ? $request->emp_work_start_date : $employee->emp_work_start_date,
            'emp_hijri_start_date' => isset($request->emp_hijri_start_date) ? $request->emp_hijri_start_date : $employee->emp_hijri_start_date,
            'emp_family_count' => $request->emp_family_count,
            'emp_work_end_date' => isset($request->emp_work_end_date) ? $request->emp_work_end_date : $employee->emp_work_end_date,
            'emp_hijri_end_date' => isset($request->emp_hijri_end_date) ? $request->emp_hijri_end_date : $employee->emp_hijri_end_date,
            'emp_gender' => $request->emp_gender,
            'emp_category' => $request->emp_category,
            'emp_reason_leaving' => $request->emp_reason_leaving,
            'emp_religion' => $request->emp_religion,
            'emp_birthday' => isset($request->emp_birthday) ? $request->emp_birthday : $employee->emp_birthday,
            'emp_birthday_hijiri' => isset($request->emp_birthday_hijiri) ? $request->emp_birthday_hijiri : $employee->emp_birthday_hijiri,
            'emp_birth_country' => $request->emp_birth_country,
            'emp_birth_city' => $request->emp_birth_city,
            'emp_birth_address' => $request->emp_birth_address,
            'emp_private_mobile' => $request->emp_private_mobile,
            'emp_current_address' => $request->emp_current_address,
            'emp_work_mobile' => $request->emp_work_mobile,
            'emp_email_work' => $request->emp_email_work,
            'emp_email_private' => $request->emp_email_private,
            'emp_sponsor_id' => $request->emp_sponsor_id,
            'emp_previous_sponsor_name' => $request->emp_previous_sponsor_name,
            'emp_previous_sponsor_phone' => $request->emp_previous_sponsor_phone,
            'emp_job_in_identity' => $request->emp_job_in_identity,
            'emp_default_company_id' => $request->emp_default_company_id,
            'emp_default_branch_id' => $request->emp_default_branch_id,
            'emp_manager_id' => $request->emp_manager_id,
            'emp_direct_date' => $request->emp_direct_date,
            'emp_bank_id' => $request->emp_bank_id,
            'emp_bank_account' => $request->emp_bank_account,
            'emp_is_bank_payment' => $request->emp_is_bank_payment ? 1 : 0,
            'emp_is_user_application' => $request->emp_is_user_application ? 1 : 0,
            'issueNumber' => $request->issueNumber,

        ]);
//        return $employee;

        $old_user = User::where('emp_id', $employee->emp_id)->first();
        if ($request->emp_is_user_application) {
            if (isset ($old_user)) {

                $old_user->update([
                    'company_group_id' => $employee->company_group_id,
                    'company_id' => $employee->emp_default_company_id,
                    'user_code' => $employee->emp_code,
                    'user_password' => Hash::make(123456),
                    'user_email' => $employee->emp_email_private,
                    'user_name_ar' => $employee->emp_name_full_ar,
                    'user_name_en' => $employee->emp_name_full_en,
                    'user_profile_url' => $employee->emp_photo_url,
                    'user_default_branch_id' => $employee->emp_default_branch_id,
                    'user_start_date' => $employee->emp_work_start_date,
                    'user_end_date' => $employee->emp_work_end_date,
                    'user_mobile' => $employee->emp_work_mobile,

                ]);
                $user_branch = UserBranch::where('user_id', $old_user->user_id)->first();
                $user_branch->update([
                    'company_id' => $old_user->company_id,
                    'job_id' => $employee->contractActive->job_id,
                    'branch_id' => $old_user->user_default_branch_id,
                    'user_branch_is_defaul' => 1,
                ]);

            } else {
                $user = User::create([
                    'emp_id' => $employee->emp_id,
                    'company_group_id' => $employee->company_group_id,
                    'company_id' => $employee->emp_default_company_id,
                    'user_code' => $employee->emp_code,
                    'user_password' => Hash::make(123456),
                    'user_email' => $employee->emp_email_private,
                    'user_name_ar' => $employee->emp_name_full_ar,
                    'user_name_en' => $employee->emp_name_full_en,
                    'user_profile_url' => $employee->emp_photo_url,
                    'user_default_branch_id' => $employee->emp_default_branch_id,
                    'user_status_id' => 1,
                    'user_start_date' => $employee->emp_work_start_date,
                    'user_end_date' => $employee->emp_work_end_date,
                    'user_mobile' => $employee->emp_work_mobile,
                ]);
                UserBranch::create([
                    'user_id' => $user->user_id,
                    'company_id' => $user->company_id,
                    'job_id' => $employee->contractActive->job_id,
                    'branch_id' => $user->user_default_branch_id,
                    'user_branch_is_defaul' => 1,
                ]);
            }
        } else {
            if (isset($old_user)) {
                $user_branch = UserBranch::where('user_id', $old_user->user_id)->first();
                $user_branch->delete();
                $old_user->delete();

            }
        }

        return redirect()->route('employees')->with(['success' => 'تم تحديث بيانات الموظف']);
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::find($id);
        $employee->certificates()->delete();
        $employee->contracts()->delete();
        $employee->salaries()->delete();
        $employee->experience()->delete();
        foreach ($employee->allRequests as $emp_request) {
            $emp_request->requestDetails()->delete();
            $emp_request->delete();
        }

        $employee->delete();
        return back()->with(['success' => 'تم الحذف']);
    }

    public function delete($id)
    {
        $permission = Attachment::find($id);
        $permission->Delete();
        return back()->with(['error', ' تم حذف الملف ']);
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Employees"), $name);
        return $name;
    }

    public function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $employees = Employee::where('emp_default_company_id', $company->company_id)->get();


        if (request()->company_id) {
            $query = Employee::whereIn('emp_default_company_id', request()->company_id);
            $query_count = DB::table('employees')->where('company_group_id', $company->company_group_id)
                ->whereIn('emp_default_company_id', request()->company_id);

            $employees = $query->get();

            if (request()->from_date && request()->to_date) {
                $employees = $query->get();
            }
            if (request()->emp_status) {
                $query = $query->whereIn('emp_status', request()->emp_status);
                $employees = $query->get();
            }
            if (request()->emp_nationality) {
                $query = $query->whereIn('emp_nationality', request()->emp_nationality);
                $employees = $query->get();
            }
            if (request()->emp_private_mobile) {
                $query = $query->where('emp_private_mobile', request()->emp_private_mobile);
                $employees = $query->get();
            }
            if (request()->emp_identity) {
                $query = $query->where('emp_identity', request()->emp_identity);
                $employees = $query->get();
            }
            if (request()->emp_name_full) {
                $query = $query->where('emp_name_full_ar', 'like', '%' . request()->emp_name_full . '%');
                $employees = $query->get();
            }

            if (request()->branch_id) {
                $query = $query->where('emp_default_branch_id', request()->branch_id);
                $employees = $query->get();
            }

            if (request()->job_id) {
                $employees_jobs_id = EmployeeContract::whereIn('emp_contract_job_id', request()->job_id)->pluck('emp_id')->toArray();
                $employees_ids = $employees->pluck('emp_id')->toArray();
                $employees = Employee::whereIn('emp_id', array_intersect($employees_jobs_id, $employees_ids))->get();
            }

            if (request()->from_date && request()->to_date) {
                $emp_attachments_ids = Attachment::whereDate('issue_date', '>=', request()->from_date)
                    ->whereDate('expire_date', '<=', request()->to_date)
                    ->where('app_menu_id', 8)->pluck('transaction_id')->toArray();

                $employees_ids = $employees->pluck('emp_id')->toArray();
                $employees = Employee::whereIn('emp_id', array_intersect($emp_attachments_ids, $employees_ids))->get();
            }

        }

        return Excel::download(new \App\Exports\EmployeeExports($employees), 'employees.xlsx');


    }

}
