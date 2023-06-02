<?php

namespace App\Http\Controllers;

use App\Http\Resources\HandOversResource;
use App\Mail\EmployeeRequestEmail;
use App\Models\AccounPeriod;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\CompanyMenuSerial;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\EmployeeRequest;
use App\Models\EmployeeRequestDt;
use App\Models\Notification;
use App\Models\SystemCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmployeeRequestController extends Controller
{


    // emp_request_days عدد الايام في طلب الاجازه
//     vacation_days  عدد الاايام الفعلي للاجازه بعد طلب المباشره

//// 0 الطلب مرفوض
/// 1 الطلب تم الموافقه عليه
/// 2 تحت التنفيذ
///
    // emp_request_days عدد الايام في طلب الاجازه
//     vacation_days  عدد الاايام الفعلي للاجازه بعد طلب المباشره

//// 0 الطلب مرفوض
/// 1 الطلب تم الموافقه عليه
/// 2 تحت التنفيذ
///
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employee_requests = EmployeeRequest::where('company_group_id', $company->company_group_id)->latest()->get();
        $request_types = SystemCode::where('sys_category_id', 46)->where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('company_group_id', $company->company_group_id)->get();

        if (request()->emp_request_type_id) {
            $query = EmployeeRequest::where('company_group_id', $company->company_group_id)
                ->whereIn('emp_request_type_id', request()->emp_request_type_id);
            $employee_requests = $query->get();


            if (request()->sub_emp_id) {
                $query = $query->whereIn('sub_emp_id', request()->sub_emp_id);
                $employee_requests = $query->get();
            }

            if (request()->emp_id) {

                $query = $query->whereIn('emp_id', request()->emp_id);
                $employee_requests = $query->get();
            }

            if (request()->emp_request_code) {
                $query = $query->where('emp_request_code', request()->emp_request_code);
                $employee_requests = $query->get();
            }

            if (request()->emp_request_start_date && request()->emp_request_end_date) {
                $query = $query->where('emp_request_start_date', '>=', request()->emp_request_start_date)
                    ->where('emp_request_end_date', '<=', request()->emp_request_end_date);
                $employee_requests = $query->get();
            }
        }
        return view('EmployeeRequests.index', compact('employee_requests', 'request_types', 'employees'));
    }


    public function create()
    {
        $vacation_requests = EmployeeRequest::where('emp_request_type_id', 503)->where('emp_request_approved', 1)->get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $request_types = SystemCode::where('sys_category_id', 46)->where('company_group_id', $company->company_group_id)->get();
        $vacation_types = SystemCode::where('sys_category_id', 2)->where('company_group_id', $company->company_group_id)->get();
        $alter_employees = Employee::where('emp_status', 23)->where('company_group_id', $company->company_group_id)->get();

        $employees_ids = Employee::where('company_group_id', $company->company_group_id)
            ->pluck('emp_id')->toArray();
        $employees = Employee::whereIn('emp_id', $employees_ids)->get();
//        $employees = Employee::where('company_group_id', $company->company_group_id)
//            ->where('emp_status', 23)->get();


        ///التامين الطبي
        $insurance_categories = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 107)->get();
        $insurance_types = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 106)->get();


        //////////تسليم العهده
        $hand_over_items = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 111)->get();
        $hand_over_statuses = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 118)->get();

        ////اجراء جزائي
        $panel_action_reasons = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 108)->get();

        ///////////طلب توقف عن العمل
        $stop_working_reasons = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 109)->get();

        //////طلب سلفه
        $account_periods = AccounPeriod::where('company_group_id', $company->company_group_id)->where('acc_period_is_active', 1)->get();

        //////تكليف بمهمه عمل
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        /////////////طلب تقييم موظف
        $employee_evaluation_types = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 119)->get();
        $per_employees = Employee::where('company_group_id', $company->company_group_id)
            ->where('emp_status', SystemCode::where('system_code', 40001)->first()->system_code_id)->get();

        $interview_evaluations = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 114)->get();
        $employee_evaluations = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 115)->get();

//        اخلاء طرف
        $system_code_items = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 111)->get();

        $last_request_serial = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 9)->latest()->first();
        if (isset($last_request_serial)) {
            $last_request_serial_no = $last_request_serial->serial_last_no;

            $array_number = explode('-', $last_request_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_request_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'REQ-' . $company->company_id . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 9,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        return view('EmployeeRequests.create', compact('request_types', 'vacation_types', 'string_number',
            'alter_employees', 'employees', 'vacation_requests', 'insurance_categories', 'insurance_types', 'companies',
            'hand_over_items', 'hand_over_statuses', 'panel_action_reasons', 'account_periods', 'stop_working_reasons',
            'system_code_items', 'employee_evaluation_types', 'per_employees', 'company', 'interview_evaluations',
            'employee_evaluations'));

    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        if ($request->vacation_type == 1) {

            // if ($request->emp_request_days > $request->days_available) {
            //    return back()->with(['error' => 'عدد الأيام المطلوبه أكبر من المتاح']);
            //  }
            if ($request->emp_request_days == 0) {

                return back()->with(['error' => ' لا يوجد عدد أيام أجازه بالطلب المقدم ']);
            }
        }

        $employee = Employee::where('emp_id', $request->emp_id)->first();
        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();


        /// emp_request_type_id    system_code not system_code_id
        $emp_request_type = SystemCode::where('system_code', $request->emp_request_type_id)
            ->where('company_group_id', $company->company_group_id)->first();

        if ($request->emp_request_type_id == 503) {

            $employee_request = EmployeeRequest::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'emp_request_code' => $request->emp_request_code,
                'emp_request_type_id' => $emp_request_type->system_code_id,
                'emp_request_status' => 0,
                'emp_id' => $request->emp_id,
                'emp_request_date' => Carbon::now(),
                'request_manager_id' => $employee->emp_manager_id,
                'emp_request_notes' => $request->emp_request_notes,
                'emp_request_days' => $request->emp_request_days,
                'emp_request_start_date' => $request->emp_request_start_date,
                'emp_request_end_date' => $request->emp_request_end_date,
                'sub_emp_id' => $request->sub_emp_id,
                'vacation_type' => $request->vacation_type,
                'vacation_phone' => $request->vacation_phone,
                'vacation_address' => $request->vacation_address,
//                'vacation_days' => $request->vacation_days,
                'vacatio_balance_day' => ceil($request->days_available),
                'emp_request_amount' => $request->emp_request_amount,
                'created_user' => auth()->user()->user_id,
//                'emp_request_status' => 2
            ]);


//            Mail::to($manager->emp_email_work)
//                ->send(new EmployeeRequestEmail(
//                    'هناك طلب أجازه رقم' . ' ' . $string_number . ' ' . 'يحتاج الي موافقتك'));

            if (isset($manager)) {
                $user = User::where('emp_id', $manager->emp_id)->first();
                Notification::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'notification_type' => 'request',
                    'notification_app_type' => 9,
                    'notification_user_id' => isset($user) ? $user->user_id : '',
                    'notifiable_id' => $employee_request->emp_request_id,
                    'notification_data' => 'هناك طلب أجازه رقم' . ' ' . $request->emp_request_code . ' ' . 'يحتاج الي موافقتك',
                    'notification_status' => 0
                ]);
            }

        }
        else if ($request->emp_request_type_id == 504) {
            /////////////////بدون طلب اجازه
            $employee = Employee::where('emp_id', $request->emp_id)->first();

            if (!$request->emp_request_id) {
                $employee_request = EmployeeRequest::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'emp_request_code' => $request->emp_request_code,
                    'emp_request_type_id' => $emp_request_type->system_code_id,
                    'emp_request_status' => 2,
                    'emp_id' => $request->emp_id,
                    'emp_request_date' => Carbon::now(),
                    'emp_request_notes' => 'طلب مباشره عمل لموظف جديد',
                    'emp_request_days' => 0,
                    'request_manager_id' => $employee->emp_manager_id,
                    'emp_direct_date' => $request->emp_direct_date,
                    'created_user' => auth()->user()->user_id,
                    'request_approved' => 0,
                ]);

            }
            else {
                $old_request = EmployeeRequest::where('emp_request_id', $request->emp_request_id)->first();

                $employee_request = EmployeeRequest::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'emp_request_code' => $request->emp_request_code,
                    'emp_request_type_id' => $emp_request_type->system_code_id,
                    'emp_request_status' => 2,
                    'emp_id' => $request->emp_id,
                    'emp_request_date' => Carbon::now(),
                    'emp_request_notes' => $old_request->emp_request_notes,
                    'emp_request_days' => $old_request->emp_request_days,
                    'emp_request_start_date' => $old_request->emp_request_start_date,
                    'emp_request_end_date' => $old_request->emp_request_end_date,
                    'start_date' => $old_request->emp_request_start_date,
                    'end_date' => $request->emp_direct_date,
                    'request_manager_id' => $employee->emp_manager_id,
                    'emp_direct_date' => $request->emp_direct_date,
                    'vacation_type' => $old_request->vacation_type,
                    'vacation_days' => $request->actual_vacation_days,
                    'vacation_phone' => $old_request->vacation_phone,
                    'vacation_address' => $old_request->vacation_address,
                    'sub_emp_id' => $old_request->sub_emp_id,
                    'vacatio_balance_day' => $old_request->vacatio_balance_day - $request->actual_vacation_days,
                    'emp_request_amount' => $old_request->emp_request_amount,
                    'created_user' => auth()->user()->user_id,
                    'request_approved' => 0,
                    'request_id' => $old_request->emp_request_id,
                ]);

                $old_request->request_id = $employee_request->emp_request_id;
                $old_request->save();
            }


//            Mail::to($manager->emp_email_work)
//                ->send(new EmployeeRequestEmail(
//                    'هناك طلب مباشره رقم' . ' ' . $string_number . ' ' . 'يحتاج الي موافقتك'));

            if (isset($manager)) {
                $user = User::where('emp_id', $manager->emp_id)->first();
                Notification::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'notification_type' => 'request',
                    'notification_app_type' => 9,
                    'notification_user_id' => isset($user) ? $user->user_id : '',
                    'notifiable_id' => $employee_request->emp_request_id,
                    'notification_data' => 'هناك طلب مباشره رقم' . ' ' . $request->emp_request_code . ' ' . 'يحتاج الي موافقتك',
                    'notification_status' => 0
                ]);
            }

        }


        return redirect()->route('employee-requests')->with(['success' => 'تمت الاضافه']);
    }

    public function edit($id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $request_types = SystemCode::where('sys_category_id', 46)->where('company_group_id', $company->company_group_id)->get();
        $vacation_types = SystemCode::where('sys_category_id', 2)->where('company_group_id', $company->company_group_id)->get();
        $alter_employees = Employee::where('emp_status', 23)->where('company_group_id', $company->company_group_id)->get();
        return view('EmployeeRequests.edit', compact('id', 'request_types', 'vacation_types'
            , 'alter_employees'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $employee_request = EmployeeRequest::find($id);
        $employee = Employee::where('emp_id', $employee_request->emp_id)->first();

        $sys_codes_status = SystemCode::where('system_code', 116)->where('company_group_id', $employee->company_group_id)->first();

        $employee_request->update([
            'emp_request_notes' => $request->emp_request_notes,
            'emp_request_days' => $request->emp_request_days,
            'emp_request_start_date' => $request->emp_request_start_date,
            'emp_request_end_date' => $request->emp_request_end_date,
            'sub_emp_id' => $request->sub_emp_id,
            'vacation_phone' => $request->vacation_phone,
            'vacation_address' => $request->vacation_address,
            'vacation_days' => $request->emp_request_days,
            'emp_request_amount' => $request->emp_request_amount,
            'emp_request_manager_id' => $employee->emp_manager_id,
            'emp_request_reason' => $request->emp_request_reason,
            'emp_request_approved' => $request->emp_request_status,
            'updated_user' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_status,
            'vacation_type' => $request->vacation_type ? $request->vacation_type : $employee_request->vacation_type,
        ]);


        if ($request->emp_request_status == 1) {
            //  return $employee_request->vacatio_balance_day - $employee_request->emp_request_days;
            $employee->update([
                'emp_status' => $sys_codes_status->system_code_id, // اجازه  بدون براتب
                'emp_vacation_balance' => $employee_request->vacatio_balance_day - $employee_request->emp_request_days,
                'emp_last_vacation_start' => $employee_request->emp_request_start_date,
                'emp_last_vacation_end' => $employee_request->emp_request_end_date
            ]);

        }

        DB::commit();

        return redirect()->route('employee-requests')->with(['success' => 'تم التحديث']);

    }


    /////////////////التامين الطبي

    public function storeMedicalInsurance(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();
        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'emp_request_notes' => $request->emp_request_notes,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
        ]);

        foreach ($request->item_name_ar as $k => $item) {
            EmployeeRequestDt::create([
                'emp_request_id' => $employee_request->emp_request_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'emp_request_type_id' => $request_type->system_code_id,
                'emp_id' => $request->emp_id,
                'item_category' => $request->item_category,
                'item_type' => $request->item_type,
                'item_name_ar' => $request->item_name_ar[$k],
                'item_relation' => $request->item_relation[$k],
                'item_date' => $request->item_date[$k],
            ]);
        }

        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تامين طبي رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب تامين طبي']);
    }

    public function editMedicalInsurance($id)
    {
        $employee_request = EmployeeRequest::find($id);
        $insuranceCategories = SystemCode::where('sys_category_id', 107)->get();
        $insuranceTypes = SystemCode::where('sys_category_id', 106)->get();
        return view('EmployeeRequests.Medical.edit', compact('employee_request',
            'insuranceTypes', 'insuranceCategories'));
    }

    public function updateMedicalInsurance(Request $request, $id)
    {
        $employee_request = EmployeeRequest::find($id);
        $employee_request->update([
            'emp_request_status' => $request->emp_request_status,
            'updated_user' => auth()->user()->user_id,
            'emp_request_notes' => $request->emp_request_notes,
            'emp_request_hr_id' => auth()->user()->user_id,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
        ]);

        if ($request->emp_request_status == 1) {
            return back()->with(['success' => 'تم الموافقه علي الطلب']);
        }

        if ($request->emp_request_status == 0) {
            return back()->with(['success' => 'تم رفض الطلب']);
        }

    }
    ///////////////////////////
    ///
    ///
    /// طلب تسليم العهده

    public function storeHandOver(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();
        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'emp_request_notes' => $request->emp_request_notes,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
        ]);

        foreach ($request->item_id as $k => $item_id) {
            EmployeeRequestDt::create([
                'emp_request_id' => $employee_request->emp_request_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'emp_request_type_id' => $request_type->system_code_id,
                'emp_id' => $request->emp_id,
                'item_id' => $request->item_id[$k],
                'item_value' => $request->item_value[$k],
                'item_qunt' => $request->item_qunt[$k],
                'item_status' => isset($request->item_status[$k]) ? $request->item_status[$k] : '',
                'item_notes' => $request->item_notes[$k],
            ]);
        }

        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب تسليم عهده']);

    }

    public function editHandOver($id)
    {
        $employee_request = EmployeeRequest::find($id);
        return view('EmployeeRequests.handOver.edit', compact('employee_request'));
    }

    public function updateHandOver(Request $request, $id)
    {
        $employee_request = EmployeeRequest::find($id);

        $employee_request->update([
            'emp_request_status' => $request->emp_request_status,
            'updated_user' => auth()->user()->user_id,
            'emp_request_approved' => $request->emp_request_status,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_hr_id' => auth()->user()->user_id
        ]);

        if ($request->emp_request_status == 1) {
            return back()->with(['success' => 'تم الموافقه علي الطلب']);
        }

        if ($request->emp_request_status == 0) {
            return back()->with(['success' => 'تم رفض الطلب']);
        }
    }
/////////////
///
///
/// طلب اجراء جزائي
    public function storePanelAction(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();
        /////////تاريخ الايقاف عن العمل
        /// عدد الايام
        $request_reason = SystemCode::where('system_code', $request->item_reasons)->first();

        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'emp_request_notes' => $request->emp_request_notes,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2
        ]);

        EmployeeRequestDt::create([
            'emp_request_id' => $employee_request->emp_request_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'item_qunt' => $request->item_qunt,
            'item_date' => $request->item_date, /////////////تاريخ الايقاف عن العمل
            'item_start_date' => $request->item_start_date, ////تاريخ الجزاء الجديد
            'item_reasons' => $request_reason->system_code_id,
        ]);

        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب اجراء جزائي']);

    }

    public function editPanelAction($id)
    {
        $employee_request = EmployeeRequest::find($id);
        $panel_action_reasons = SystemCode::where('sys_category_id', 108)->get();
        return view('EmployeeRequests.penalAction.edit',
            compact('employee_request', 'panel_action_reasons'));
    }

    public function updatePanelAction(Request $request, $id)
    {
        $employee_request = EmployeeRequest::find($id);

        $employee_request->update([
            'emp_request_status' => $request->emp_request_status,
            'updated_user' => auth()->user()->user_id,
            'emp_request_hr_id' => auth()->user()->user_id,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_approved' => $request->emp_request_status,

        ]);

        $request_reason = SystemCode::where('system_code', $request->item_reasons)->first();
        $employee_request->panelActionDetails->update([
            'item_qunt' => $request->item_qunt,
            'item_date' => $request->item_date, /////////////تاريخ الايقاف عن العمل
            'item_start_date' => $request->item_start_date, ////تاريخ الجزاء الجديد
            'item_reasons' => $request_reason->system_code_id,
        ]);

        if ($request->emp_request_status == 1) {
            return back()->with(['success' => 'تم الموافقه علي الطلب']);
        }

        if ($request->emp_request_status == 0) {
            return back()->with(['success' => 'تم رفض الطلب']);
        }
    }

    ///////////////////


/// طلب السلفه
    public function storeAncestorsRequest(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();
        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
            'emp_request_approved' => 2
        ]);


        EmployeeRequestDt::create([
            'emp_request_id' => $employee_request->emp_request_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'item_value' => $request->item_value,
            'item_start_date' => $request->item_start_date,
            'item_end_date' => $request->item_end_date,
            'sponsor_id_1' => $request->sponsor_id_1,
            'sponsor_id_2' => $request->sponsor_id_2,
        ]);


        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب سلفه جديد']);
    }

    public function editAncestorsRequest($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employees = Employee::where('company_group_id', $company->company_group_id)->get();
        $employee_request = EmployeeRequest::find($id);

        return view('EmployeeRequests.AncestorsRequest.edit', compact('employee_request',
            'employees'));
    }

    public function updateAncestorsRequest(Request $request, $id)
    {
        $employee_request = EmployeeRequest::find($id);

        $employee_request->update([
            'emp_request_date' => Carbon::now(),
            'updated_user' => auth()->user()->user_id,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_hr_id' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_status,
        ]);


        $employee_request->ancestorsRequestDetails->update([
            'item_value' => $request->item_value,
            'item_start_date' => $request->item_start_date,
            'item_end_date' => $request->item_end_date,
            'sponsor_id_1' => $request->sponsor_id_1,
            'sponsor_id_2' => $request->sponsor_id_2,
            'manager_notes' => $request->manager_notes,
            'hr_notes' => $request->hr_notes,
            'ceo_notes' => $request->ceo_notes,
        ]);

        return back()->with(['success' => ' تم التحديث']);

    }
/////////////
///

/////طلب ايقاف العمل
///
///
///

    public function storeStopWorkingRequest(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();

        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
            'emp_request_approved' => 2
        ]);

        EmployeeRequestDt::create([
            'emp_request_id' => $employee_request->emp_request_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'item_value' => $request->item_value,
            'item_start_date' => $request->item_start_date,
            'item_end_date' => $request->item_end_date,
            'item_notes' => $request->item_notes,
            'item_reasons' => $request->item_reasons,
            'item_period_id' => $request->item_period_id,
            'item_qunt' => $request->item_qunt,
        ]);


        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب ايقاف عمل جديد']);

    }

    public function editStopWorkingRequest($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employees = Employee::where('company_group_id', $company->company_group_id)->get();
        $employee_request = EmployeeRequest::find($id);
        $account_periods = AccounPeriod::where('company_group_id', $company->company_group_id)
            ->where('acc_period_is_active', 1)->get();
        $stop_working_reasons = SystemCode::where('sys_category_id', 109)->get();
        return view('EmployeeRequests.StopWorking.edit', compact('employee_request',
            'employees', 'account_periods', 'stop_working_reasons'));
    }

    public function updateStopWorkingRequest(Request $request, $id)
    {

        $employee_request = EmployeeRequest::find($id);

        $employee_request->update([
            'updated_user' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_approved,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_hr_id' => auth()->user()->user_id,
            'emp_request_approved' => $request->emp_request_approved,

        ]);

        $employee_request->stopWorkingDetails->update([
            'item_value' => $request->item_value,
            'item_start_date' => $request->item_start_date,
            'item_end_date' => $request->item_end_date,
            'item_notes' => $request->item_notes,
            'item_reasons' => $request->item_reasons,
            'item_period_id' => $request->item_period_id,
            'item_qunt' => $request->item_qunt,
            'updated_user' => auth()->user()->user_id,
            'manager_notes' => $request->manager_notes,
            'hr_notes' => $request->hr_notes,
        ]);

        return back()->with(['success' => 'تم التعديل']);
    }


    ///////////////////
    ///
    ///
    ///
    /// طلب تكليف مهمه عمل
    public function storeJobAssignmentRequest(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();

        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
            'emp_request_approved' => 2,
            'emp_request_notes' => $request->item_notes
        ]);

        EmployeeRequestDt::create([
            'emp_request_id' => $employee_request->emp_request_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'item_start_date' => $request->item_start_date,
            'item_end_date' => $request->item_end_date,
            'item_notes' => $request->item_notes,
            'item_qunt' => $request->item_qunt,
            'item_loc_id' => $request->item_loc_id,
            'item_loc_name' => Branch::where('branch_id', $request->item_loc_id)->first()->branch_name_ar,
            'item_value_1' => $request->item_value_1,
            'item_value_2' => $request->item_value_2,
            'item_value_3' => $request->item_value_3,
        ]);


        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب تكليف مهمه جديد']);

    }

    public function editJobAssignmentRequest($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employee_request = EmployeeRequest::find($id);
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        return view('EmployeeRequests.jobAssignment.edit', compact('employee_request', 'companies'));
    }

    public function updateJobAssignmentRequest(Request $request, $id)
    {
        $employee_request = EmployeeRequest::find($id);
        $employee_request->update([
            'updated_user' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_status,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_approved' => $request->emp_request_approved,
            'emp_request_notes' => $request->item_notes
        ]);

        $employee_request->jobAssignmentDetails->update([
            'item_start_date' => $request->item_start_date,
            'item_end_date' => $request->item_end_date,
            'item_notes' => $request->item_notes,
            'item_qunt' => $request->item_qunt,
            'item_loc_id' => $request->item_loc_id,
            'item_loc_name' => Branch::where('branch_id', $request->item_loc_id)->first()->branch_name_ar,
            'item_value_1' => $request->item_value_1,
            'item_value_2' => $request->item_value_2,
            'item_value_3' => $request->item_value_3,
            'manager_notes' => $request->manager_notes,
            'hr_notes' => $request->hr_notes,
            'ceo_notes' => $request->ceo_notes,
        ]);

        return back()->with(['success', 'تم تعديل الطلب']);

    }

////////////////
///
/// طلب اخلاء طرف
    public function storeJobLeaveRequest(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();
        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
            'emp_request_approved' => 2,
        ]);

        foreach ($request->item_id as $k => $item_id) {
            EmployeeRequestDt::create([
                'emp_request_id' => $employee_request->emp_request_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'emp_request_type_id' => $request_type->system_code_id,
                'emp_id' => $request->emp_id,
                'item_id' => $request->item_id[$k],
                'item_notes' => isset($request->item_notes[$k]) ? $request->item_notes[$k] : '',
                'item_reasons' => $request->item_reasons,
                'item_status' => isset($request->item_status[$k]) ? $request->item_status[$k] : 0,
                'item_result' => isset($request->item_result) ? 1 : 0,

            ]);
        }


        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب اخلاء طرف جديد']);


    }

    public function editJobLeaveRequest($id)
    {
        $employee_request = EmployeeRequest::find($id);
        $stop_working_reasons = SystemCode::where('sys_category_id', 109)->get();
        return view('EmployeeRequests.jobLeave.edit', compact('employee_request', 'stop_working_reasons'));
    }

    public function updateJobLeaveRequest(Request $request, $id)
    {
        $employee_request = EmployeeRequest::find($id);

        $employee_request->update([
            'updated_user' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_status,
            'emp_request_hr_approver' => $request->emp_request_status,
            'emp_request_approved' => $request->emp_request_status,
        ]);

        foreach ($request->item_id as $k => $item_id) {
            $item = EmployeeRequestDt::find($item_id);
            $item->update([
                'item_notes' => isset($request->item_notes[$k]) ? $request->item_notes[$k] : '',
                'item_reasons' => $request->item_reasons,
                'item_status' => isset($request->item_status[$k]) ? $request->item_status[$k] : 0,
                'item_result' => isset($request->item_result) ? 1 : 0,
                'manager_notes' => $request->manager_notes,
                'hr_notes' => $request->hr_notes,
                'ceo_notes' => $request->ceo_notes,
            ]);
        }

        return back()->with(['success' => 'تم تعديل البيانات']);

    }


    ///////////////////
    ///
    ///
    ///
    ///
    /// طلب تقييم موظف
    public function storeEmployeeEvaluationRequest(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();
        //  return $request->item_emp_division;
        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id ? $employee->emp_manager_id : '',
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
            'emp_request_approved' => 2
        ]);

        $item_type = SystemCode::where('system_code', $request->item_type)->first();

        if ($request->item_type == 119001) { ////////مقابله شخصييه

            foreach ($request->item_evaluation as $k => $item) {
                EmployeeRequestDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'emp_request_id' => $employee_request->emp_request_id,
                    'emp_request_type_id' => $request_type->system_code_id,
                    'item_evaluation' => $request->item_evaluation[$k],
                    'item_excellent' => isset($request->item_excellent[$k]) ? $request->item_excellent[$k] : 0,
                    'item_good' => isset($request->item_good[$k]) ? $request->item_good[$k] : 0,
                    'item_middle' => isset($request->item_middle[$k]) ? $request->item_middle[$k] : 0,
                    'item_weak' => isset($request->item_weak[$k]) ? $request->item_weak[$k] : 0,
                    'item_result' => isset($request->item_result) ? 1 : 0,
                    'item_recommendation' => isset($request->item_recommendation) ? 1 : 0,
                    'item_type' => $item_type->system_code_id, ////نوع المقابله
                    'item_emp_job' => $request->item_emp_job,
                    'item_emp_division' => $request->item_emp_division,
                    'item_emp_certificate' => $request->item_emp_certificate
                ]);
            }

            return redirect()->route('employee-requests')->with(['success' => 'تم اضافه طلب تقييم مقابله شخصيه']);

        }

        if ($request->item_type == 119002) { //////////موظف تحت التجربه

            foreach ($request->item_evaluation as $k => $item) {
                EmployeeRequestDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'emp_request_id' => $employee_request->emp_request_id,
                    'emp_request_type_id' => $request_type->system_code_id,
                    'item_evaluation' => $request->item_evaluation[$k],
                    'item_excellent' => isset($request->item_excellent[$k]) ? $request->item_excellent[$k] : 0,
                    'item_good' => isset($request->item_good[$k]) ? $request->item_good[$k] : 0,
                    'item_very_good' => isset($request->item_very_good[$k]) ? $request->item_very_good[$k] : 0,
                    'item_middle' => isset($request->item_middle[$k]) ? $request->item_middle[$k] : 0,
                    'item_weak' => isset($request->item_weak[$k]) ? $request->item_weak[$k] : 0,
                    'item_recommendation' => isset($request->item_recommendation) ? $request->item_recommendation : 0,
                    'item_recommendation_hr' => isset($request->item_recommendation_hr) ? $request->item_recommendation_hr : 0,
                    'item_type' => $item_type->system_code_id, ////نوع المقابله
                    'item_emp_job' => $request->item_emp_job,
                    'item_emp_division' => $request->item_emp_division,
                    'item_emp_certificate' => $request->item_emp_certificate
                ]);
            }

            return redirect()->route('employee-requests')->with(['success' => 'تم اضافه طلب تقييم موظف تحت التجربه']);
        }


        if ($request->item_type == 119003) { /////تقييم موظف

            foreach ($request->item_evaluation as $k => $item) {
                EmployeeRequestDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'emp_request_id' => $employee_request->emp_request_id,
                    'emp_request_type_id' => $request_type->system_code_id,
                    'item_evaluation' => $request->item_evaluation[$k],
                    'item_excellent' => isset($request->item_excellent[$k]) ? $request->item_excellent[$k] : 0,
                    'item_good' => isset($request->item_good[$k]) ? $request->item_good[$k] : 0,
                    'item_middle' => isset($request->item_middle[$k]) ? $request->item_middle[$k] : 0,
                    'item_weak' => isset($request->item_weak[$k]) ? $request->item_weak[$k] : 0,
                    'item_result' => isset($request->item_result) ? 1 : 0,
                    'item_recommendation' => isset($request->item_recommendation) ? $request->item_recommendation : 0,
                    'item_type' => $item_type->system_code_id, ////نوع المقابله
                    'item_emp_job' => $request->item_emp_job,
                    'item_emp_division' => $request->item_emp_division,
                    'item_recommendation_hr' => isset($request->item_recommendation_hr) ? $request->item_recommendation_hr : 0,
                ]);
            }

            return redirect()->route('employee-requests')->with(['success' => 'تم اضافه طلب تقييم موظف']);

        }


    }


    public function editEmployeeEvaluationRequest($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employee_request = EmployeeRequest::find($id);
        return view('EmployeeRequests.EmployeeEvaluation.edit', compact('employee_request',
            'company'));
    }

    public function updateEmployeeEvaluationRequest(Request $request, $id)
    {

        $employee_request = EmployeeRequest::find($id);

        $employee_request->update([
            'updated_user' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_approved,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_approved' => $request->emp_request_approved,
        ]);

        if ($request->emp_request_status == 1) {
            $employee_request->employee->update(['emp_status' => SystemCode::where('system_code', 40002)
                ->first()->system_code_id]);
        }

        $item_type = SystemCode::where('system_code_id', $employee_request->employeeEvaluation->first()->item_type)->first();

        if ($item_type->system_code == 119001) { ////////مقابله شخصييه

            foreach ($request->item_evaluation as $k => $item) {
                $item = EmployeeRequestDt::where('emp_request_dt_id', $item)->first();
                $item->update([
                    'item_excellent' => isset($request->item_excellent[$k]) ? $request->item_excellent[$k] : 0,
                    'item_good' => isset($request->item_good[$k]) ? $request->item_good[$k] : 0,
                    'item_middle' => isset($request->item_middle[$k]) ? $request->item_middle[$k] : 0,
                    'item_weak' => isset($request->item_weak[$k]) ? $request->item_weak[$k] : 0,
                    'item_result' => isset($request->item_result) ? 1 : 0,
                    'item_recommendation' => isset($request->item_recommendation) ? 1 : 0,
                    'item_emp_job' => $request->item_emp_job,
                    'item_emp_division' => $request->item_emp_division,
                    'item_emp_certificate' => $request->item_emp_certificate,
                    'manager_notes' => $request->manager_notes,
                    'hr_notes' => $request->hr_notes,

                ]);
            }

            return back()->with(['success' => 'تم تعديل طلب تقييم مقابله شخصيه']);

        }

        if ($item_type->system_code == 119002) { //////////موظف تحت التجربه

            foreach ($request->item_evaluation as $k => $item) {
                $item = EmployeeRequestDt::where('emp_request_dt_id', $item)->first();
                $item->update([
                    'item_excellent' => isset($request->item_excellent[$k]) ? $request->item_excellent[$k] : 0,
                    'item_good' => isset($request->item_good[$k]) ? $request->item_good[$k] : 0,
                    'item_very_good' => isset($request->item_very_good[$k]) ? $request->item_very_good[$k] : 0,
                    'item_middle' => isset($request->item_middle[$k]) ? $request->item_middle[$k] : 0,
                    'item_weak' => isset($request->item_weak[$k]) ? $request->item_weak[$k] : 0,
                    'item_recommendation' => isset($request->item_recommendation) ? $request->item_recommendation : 0,
                    'item_recommendation_hr' => isset($request->item_recommendation_hr) ? $request->item_recommendation_hr : 0,
                    'item_type' => $item_type->system_code_id, ////نوع المقابله
                    'item_emp_job' => $request->item_emp_job,
                    'item_emp_division' => $request->item_emp_division,
                    'item_emp_certificate' => $request->item_emp_certificate,
                    'manager_notes' => $request->manager_notes,
                    'hr_notes' => $request->hr_notes,

                ]);
            }

            return back()->with(['success' => 'تم تعديل طلب تقييم موظف تحت التجربه']);
        }

        if ($item_type->system_code == 119003) { /////تقييم موظف

            foreach ($request->item_evaluation as $k => $item) {
                $item = EmployeeRequestDt::where('emp_request_dt_id', $item)->first();
                $item->update([
                    'item_excellent' => isset($request->item_excellent[$k]) ? $request->item_excellent[$k] : 0,
                    'item_good' => isset($request->item_good[$k]) ? $request->item_good[$k] : 0,
                    'item_middle' => isset($request->item_middle[$k]) ? $request->item_middle[$k] : 0,
                    'item_weak' => isset($request->item_weak[$k]) ? $request->item_weak[$k] : 0,
                    'item_result' => isset($request->item_result) ? $request->item_result[$k] : 0,
                    'item_recommendation' => isset($request->item_recommendation) ? $request->item_recommendation : 0,
                    'item_emp_job' => $request->item_emp_job,
                    'item_emp_division' => $request->item_emp_division,
                    'item_recommendation_hr' => isset($request->item_recommendation_hr) ? $request->item_recommendation_hr : 0,
                    'manager_notes' => $request->manager_notes,
                    'hr_notes' => $request->hr_notes,
                ]);
            }

            return redirect()->route('employee-requests')->with(['success' => 'تم تعديل طلب تقييم موظف']);

        }
    }

    /////////
    ///
    ///
    /// طلب استقاله
    public function storeResignationRequest(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();

        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
            'emp_request_approved' => 2,
            'emp_request_notes' => $request->emp_request_notes
        ]);

        EmployeeRequestDt::create([
            'emp_request_id' => $employee_request->emp_request_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'item_reasons' => $request->item_reasons,
        ]);


        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب استقاله جديد']);
    }

    public function editResignationRequest($id)
    {
        $employee_request = EmployeeRequest::find($id);
        $stop_working_reasons = SystemCode::where('sys_category_id', 109)->get();
        return view('EmployeeRequests.Resignation.edit', compact('employee_request',
            'stop_working_reasons'));
    }

    public function updateResignationRequest(Request $request, $id)
    {

        $employee_request = EmployeeRequest::find($id);

        $employee_request->update([
            'updated_user' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_status,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_hr_id' => auth()->user()->user_id,
            'emp_request_approved' => $request->emp_request_approved,
            'emp_request_notes' => $request->emp_request_notes

        ]);

        $employee_request->resignationDetails->update([
            'item_reasons' => $request->item_reasons,
            'updated_user' => auth()->user()->user_id,
            'manager_notes' => $request->manager_notes,
            'hr_notes' => $request->hr_notes,
            'ceo_notes' => $request->ceo_notes,
        ]);

        return back()->with(['success' => 'تم التعديل']);
    }

//////////////
///
///
///
///طلب تصفيه حساب
    public function storeReckoningRequest(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $employee = Employee::find($request->emp_id);
        $request_type = SystemCode::where('system_code', $request->emp_request_type_id)->first();

        $employee_request = EmployeeRequest::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'emp_request_code' => $request->emp_request_code,
            'emp_request_type_id' => $request_type->system_code_id,
            'emp_id' => $request->emp_id,
            'emp_request_date' => Carbon::now(),
            'request_manager_id' => $employee->emp_manager_id,
            'created_user' => auth()->user()->user_id,
            'emp_request_status' => 2,
            'emp_request_hr_approver' => 2,
            'emp_request_approved' => 2,
            'emp_request_notes' => $request->emp_request_notes
        ]);

        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        if (isset($manager)) {

            $user = User::where('emp_id', $manager->emp_id)->first();

            Notification::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'notification_type' => 'request',
                'notification_app_type' => 9,
                'notification_user_id' => isset($user) ? $user->user_id : '',
                'notifiable_id' => $employee_request->emp_request_id,
                'notification_data' => 'هناك طلب تسليم عهده رقم' . ' ' . $employee_request . ' ' . 'يحتاج الي موافقتك',
                'notification_status' => 0
            ]);
        }

        return redirect()->route('employee-requests')->with(['success' => ' تم اضافه طلب تصفيه حساب']);

    }

    public function editReckoningRequest($id)
    {
        $employee_request = EmployeeRequest::find($id);
        $last_vacation = EmployeeRequest::where('emp_id', $employee_request->emp_id)
            ->where('emp_request_type_id', SystemCode::where('system_code', 503)->first()->system_code_id)->first();

        $from_date = $employee_request->employee->emp_work_start_date;
        $to_date = Carbon::now();


        $answer_in_days = $to_date->diff($from_date);

        $items = EmployeeRequest::where('emp_id', $employee_request->employee->emp_id)
            ->where('emp_request_type_id', SystemCode::where('system_code', 46005)->first()->system_code_id)
            ->get();

        if (isset($last_vacation)) {
            return view('EmployeeRequests.Reckoning.edit', compact('employee_request', 'items', 'answer_in_days',
                'last_vacation'));
        } else {
            return view('EmployeeRequests.Reckoning.edit', compact('employee_request', 'items', 'answer_in_days'));
        }


    }

    public function updateReckoningRequest(Request $request, $id)
    {
        $employee_request = EmployeeRequest::find($id);
        $employee_request->update([
            'updated_user' => auth()->user()->user_id,
            'emp_request_status' => $request->emp_request_status,
            'emp_request_hr_approver' => $request->emp_request_hr_approver,
            'emp_request_hr_id' => auth()->user()->user_id,
            'emp_request_approved' => $request->emp_request_approved,
        ]);

        return back()->with(['success' => 'تم التعديل']);
    }


    ///////
    ///
    //
    public function getEmployeeVacationData()
    {
        $employee = Employee::find(request()->emp_id);
        $last_vacation = EmployeeRequest::where('emp_id', request()->emp_id)
            ->where('emp_request_type_id', SystemCode::where('system_code', 503)->first()->system_code_id)->first();

        $from_date = $employee->emp_work_start_date;
        $to_date = Carbon::now();


        $answer_in_days = $to_date->diff($from_date);

        $items = EmployeeRequest::where('emp_id', request()->emp_id)
            ->where('emp_request_type_id', SystemCode::where('system_code', 46005)->first()->system_code_id)
            ->get();

        if (isset($last_vacation)) {
            return response()->json(['days' => $answer_in_days->d, 'years' => $answer_in_days->y,
                'months' => $answer_in_days->m, 'last_vacation' => $last_vacation, 'items' =>
                    HandOversResource::collection($items)]);

        } else {
            return response()->json(['days' => $answer_in_days->d, 'years' => $answer_in_days->y,
                'months' => $answer_in_days->m, 'message' => 'لا يوجد اجازات سابقه للموظف', 'items' =>
                    HandOversResource::collection($items)]);
        }
    }

    public function getDifferenceDate()
    {
        $from_date = Carbon::parse(request()->start_date);
        $to_date = Carbon::parse(request()->end_date);

        $answer_in_days = $to_date->diff($from_date);
        return response()->json(['days' => $answer_in_days->d, 'year' => $answer_in_days->y, 'month' => $answer_in_days->m]);
    }

    public function getBranches()
    {
        $company = Company::find(request()->company_id);
        $branches = $company->branches;
        return response()->json(['data' => $branches]);
    }

    public function delete($id)
    {
        $employee_request = EmployeeRequest::find($id);

        if ($employee_request->requestType->system_code == 46006) {////////////طلب سلفه
            $employee_request->ancestorsRequestDetails()->delete();
        } elseif ($employee_request->requestType->system_code == 46009) { ///اجراء جزائي
            $employee_request->panelActionDetails()->delete();
        } elseif ($employee_request->requestType->system_code == 46010) { ///توقف عن العمل
            $employee_request->stopWorkingDetails()->delete();
        } elseif ($employee_request->requestType->system_code == 46007) { /// تكليف مهمه عمل
            $employee_request->jobAssignmentDetails()->delete();
        } elseif ($employee_request->requestType->system_code == 46008) { /// طلب اخلاء طرف
            $employee_request->jobLeaveDetails()->delete();
        } elseif ($employee_request->requestType->system_code == 46003) { ///تقييم موظف
            $employee_request->employeeEvaluation()->delete();
        } elseif ($employee_request->requestType->system_code == 46011) { /// طلب استقاله
            $employee_request->resignationDetails()->delete();
        } else {
            $employee_request->requestDetails()->delete();
        }
        $employee_request->delete();
        return back()->with(['success' => 'تم حذف الطلب']);
    }

}
