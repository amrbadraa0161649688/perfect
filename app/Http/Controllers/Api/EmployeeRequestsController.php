<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\EmployeeRequest;
use App\Models\SystemCode;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;

class EmployeeRequestsController extends Controller
{

    public function getDifferenceDate()
    {
        //return request()->direct_date;


        $from_date = Carbon::createFromFormat('Y-m-d', request()->start_date);

        $end_date = Carbon::createFromFormat('Y-m-d', request()->end_date);

        $result = $from_date->gt($end_date);

        if ($result) {
            return response()->json(['status' => 500, 'message' => 'تاريخ نهايه الاجازه اقل من تاريخ بدايه الاجازه']);
        }

        $count_days = $end_date->diffInDays($from_date) + 1;

        return response()->json(['data' => $count_days]);
    }


    public function getEmployee()
    {
        $employee = Employee::where('emp_id', request()->emp_id)->first();
        $company = session('company') ? session('company') : auth()->user()->company;
        $emp_work_start_date = $employee->emp_work_start_date;
        $end_date = Carbon::now();

        $worth_dd = $end_date->diff($emp_work_start_date); //عدد الايام الي ليه من يوم ما اشتغل

        $worth_days = $worth_dd->d + ($worth_dd->m * 30) + ($worth_dd->y * 12 * 30);

        $count_days = EmployeeRequest::where('emp_id', $employee->emp_id)->where('emp_request_type_id', SystemCode::where('system_code', 503)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
            ->where('emp_request_approved', 1)->pluck('vacation_days')->toArray();


        $diff_days = ($worth_days * 2.5 / 30) - array_sum($count_days); //رصيد الاجازات المتاح
        //$diff_days = ($worth_days * 2.5 / 30) - 0;
        if ($diff_days < 0) {
            $diff_days = 0;
        }

        return response()->json(['days_available' => ceil($diff_days),
            'employee' => new EmployeeResource($employee)]);
    }


    public function getEmployees()
    {
        $employee = Employee::where('emp_id', request()->emp_id)->first();
        return response()->json(['days_available' => 0,
            'employee' => new EmployeeResource($employee)]);
    }

    public function getEmployeeRequest()
    {
        $employee_request = EmployeeRequest::where('emp_request_id', request()->id)->first();

        $employee = Employee::where('emp_id', $employee_request->emp_id)->first();

        $manager = Employee::where('emp_id', $employee->emp_manager_id)->first();

        return response()->json(['data' => $employee_request, 'employee' => new EmployeeResource($employee),
            'request_type' => $employee_request->requestType, 'manager' => isset($manager) ? $manager : '']);
    }

    public function getVacation()
    {
        $vacation_request = EmployeeRequest::where('emp_request_id', request()->vacation_id)->first();
        return response()->json(['data' => $vacation_request]);
    }

    public function getVacationBalance()
    {
        ///رصيد الاجازات من تاريخ التعين الي تاريخ بداية أخر اجازه
        ///

        $employee = Employee::find(request()->emp_id);
        $company = session('company') ? session('company') : auth()->user()->company;

//        $employee_request = EmployeeRequest::where('emp_id', request()->emp_id)
//            ->where('emp_request_approved', 1)->where('emp_request_type_id', 503)->latest()->first();

        if (!$employee->emp_work_start_date) {
            return response()->json(['status' => 500, 'message' => 'لا يوجد تاريخ تعيين للموظف']);
        }
        if (!$employee->emp_last_vacation_start) {
            return response()->json(['status' => 500, 'message' => 'لا يوجد تاريخ اخر اجازه للموظف']);
        }

        $work_start_date = $employee->emp_work_start_date;

        $end_date = $employee->emp_last_vacation_start;

        $worth_dd = $end_date->diff($work_start_date);

        $count_days = EmployeeRequest::where('emp_id', request()->emp_id)->where('emp_request_type_id', SystemCode::where('system_code', 503)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
            ->where('emp_request_approved', 1)->whereDate('emp_request_start_date', '<=', $employee->emp_last_vacation_start)
            ->pluck('vacation_days')->toArray();

        $worth_days = $worth_dd->d + ($worth_dd->m * 30) + ($worth_dd->y * 12 * 30);
        $diff_days = ($worth_days * 2.5 / 30) - array_sum($count_days); //رصيد الاجازات المتاح

        if ($diff_days < 0) {
            $diff_days = 0;
        }

        return response()->json(['data' => ceil($diff_days)]);


//        return response()->json(['status' => 500, 'message' => 'لا يوجد طلب اجازه مسبق ']);


    }


}
