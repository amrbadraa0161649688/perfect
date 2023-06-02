<?php

namespace App\Http\Controllers;

use App\Models\AccounPeriod;
use App\Models\Employee;
use App\Models\EmployeeRequest;
use App\Models\EmployeeVariableDt;
use App\Models\EmployeeVariableHd;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeDirectRequestController extends Controller
{
    public function edit($id)
    {
        $employee_request = EmployeeRequest::find($id);

        return view('EmployeeRequests.Direct.edit', compact('employee_request'));
    }


    public function update(Request $request, $id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $employee_request = EmployeeRequest::find($id);

        $month = Carbon::parse($request->emp_direct_date)->format('m');
        $year = Carbon::parse($request->emp_direct_date)->format('Y');

        $account_period = AccounPeriod::where('company_group_id', $company->company_group_id)
            ->where('acc_period_year', $year)->where('acc_period_month', $month)
            ->first();

//        $account_period = AccounPeriod::where('acc_period_id', 1193)->first();
        $old_request = EmployeeRequest::where('emp_request_id', $employee_request->request_id)->first();
        $employee = Employee::where('emp_id', $request->emp_id)->first();
        $direct_date = $request->emp_direct_date;

        if (isset($account_period)) {

            if ($request->emp_request_approved == 1) {

                if ($employee->status->system_code == 40001) {

                    $diff_days = Carbon::parse($direct_date)->diffInDays(Carbon::now()->startOfMonth());
                    $employee_variable_hd = EmployeeVariableHd::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'acc_period_id' => $account_period->acc_period_id,
                        'emp_variables_main_type' => 2, // الخصومات
                        'created_user' => auth()->user()->user_id,
                    ]);

                    EmployeeVariableDt::create([
                        'emp_variables_id' => $employee_variable_hd->emp_variables_id,
                        'emp_id' => $employee_request->emp_id,
                        'acc_period_id' => $account_period->acc_period_id,
                        /////نوع الخصم خصم عدم مباشره
                        'emp_variables_type' => SystemCode::where('system_code', 2801)->where('company_group_id', $company->company_group_id)
                            ->first()->system_code_id,
                        'emp_variables_days' => $diff_days,
                        'emp_variables_salary' => $employee_request->emp_request_amount,
                        'emp_variables_debit' => ($employee_request->employee->totalSalary / 30) * $diff_days,
                        'emp_variables_main_type' => 2,
                        'created_user' => auth()->user()->user_id
                    ]);


                    $emp_status = SystemCode::where('system_code', 23)->where('company_group_id', $company->company_group_id)
                        ->first();

                    $employee->update([
                        'emp_status' => $emp_status->system_code_id, //على رأس العمل
                        'emp_direct_date' => $employee_request->emp_direct_date,
                        'emp_last_vacation_end' => $employee_request->emp_direct_date
                    ]);


                } else {

                    $vacation_start_date = $old_request->emp_request_start_date;
                    if (Carbon::parse($direct_date)->format('m') != Carbon::parse($vacation_start_date)->format('m')) {
                        $diff_days = Carbon::parse($direct_date)->diffInDays(Carbon::parse($direct_date)->startOfMonth());
                    } else {
                        $diff_days = $request->actual_vacation_days;
                    }
                    if ($diff_days > 0) {
                        $employee_variable_hd = EmployeeVariableHd::create([
                            'company_group_id' => $company->company_group_id,
                            'company_id' => $company->company_id,
                            'acc_period_id' => $account_period->acc_period_id,
                            'emp_variables_main_type' => 2, // الخصومات
                            'created_user' => auth()->user()->user_id,
                        ]);

                        EmployeeVariableDt::create([
                            'emp_variables_id' => $employee_variable_hd->emp_variables_id,
                            'emp_id' => $employee_request->emp_id,
                            'acc_period_id' => $account_period->acc_period_id,
                            /////نوع الخصم خصم عدم مباشره
                            'emp_variables_type' => SystemCode::where('system_code', 2801)->where('company_group_id', $company->company_group_id)
                                ->first()->system_code_id,
                            'emp_variables_days' => $diff_days,
                            'emp_variables_salary' => $employee_request->emp_request_amount,
                            'emp_variables_debit' => ($employee_request->employee->totalSalary / 30) * $diff_days,
                            'emp_variables_main_type' => 2,
                            'created_user' => auth()->user()->user_id
                        ]);
                    }


                    $emp_status = SystemCode::where('system_code', 23)->where('company_group_id', $company->company_group_id)
                        ->first();

                    $employee->update([
                        'emp_status' => $emp_status->system_code_id, //على رأس العمل
                        'emp_direct_date' => $employee_request->emp_direct_date,
                        'emp_vacation_balance' => $employee->emp_vacation_balance + $old_request->vacation_days - $request->actual_vacation_days,
                        'emp_last_vacation_start' => $employee_request->emp_request_start_date,
                        'emp_last_vacation_end' => $employee_request->emp_direct_date
                    ]);

                    $old_request->update([
                        'vacation_days' => $request->actual_vacation_days,
                        'vacatio_balance_day' => $old_request->vacatio_balance_day - $request->actual_vacation_days,
                        'emp_direct_date' => $request->emp_direct_date,
                        'start_date' => $old_request->emp_request_start_date,
                        'end_date' => $request->emp_direct_date,
                        'updated_user' => auth()->user()->user_id
                    ]);

                }
            }

            $employee_request->update([
                'emp_request_status' => $request->emp_request_approved,
                'emp_request_reason' => $request->emp_request_reason,
                'emp_request_approved' => $request->emp_request_approved,
            ]);


            return redirect()->route('employee-requests')->with(['success' => 'تم تعديل الطلب']);
        } else {

            return back()->with(['error' => 'الفتره غير فعاله']);
        }


    }
}
