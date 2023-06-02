<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccounPeriod;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeVariableDt;
use App\Models\EmployeeVariableSetting;
use Illuminate\Http\Request;

class MonthlyDeductionsController extends Controller
{
    public function getCompany()
    {
        ///////////get company from account period
        $company = Company::where('company_id', request()->company_id)->first();
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();

        $branches_ids = $company->branches->pluck('branch_id')->toArray();
        $employees = Employee::whereIn('emp_default_branch_id', $branches_ids)->get();
        $employees_variables = EmployeeVariableSetting::where('company_group_id', $company->company_group_id)->where('emp_variables_main_type', 2)->with('systemCodeType')->get();
        return response()->json(['status' => 200, 'data' => $accounts_periods, 'employees' => $employees,
            'employees_variables' => $employees_variables]);
    }

    public function getEmployeeBranch()
    {
        $employee = Employee::where('emp_id', request()->emp_id)->first();
        $branch = Branch::where('branch_id', $employee->emp_default_branch_id)->first();
        return response()->json(['branch' => $branch]);
    }

    public function getEmployeeVariableFactor()
    {
        $employee = Employee::find(request()->emp_id);
        $company = session('company') ? session('company') : auth()->user()->company;

        if (request()->emp_variables_type_code) {
            $employee_variable_setting = EmployeeVariableSetting::where('emp_variables_type_code', request()->emp_variables_type_code)
                    ->where('company_group_id', $company->company_group_id)->first();
        } else {
            $employee_variable_setting = EmployeeVariableSetting::where('emp_variables_type_id', request()->emp_variables_type_id)
            ->where('company_group_id', $company->company_group_id)->first();
        }

        $factor = $employee_variable_setting->emp_variables_factor;
        // $basic_salary = $employee->salariesActive->where('emp_salary_item_id', 49)->first()->emp_salary_credit;

        $basic_salary = $employee->basicSalary;
        $total_salary = $employee->totalSalary;

//        basic salary
        $basic_salary_days = $basic_salary / 30;
        $basic_salary_hours = $basic_salary_days / 8;
        $basic_salary_minutes = $basic_salary_hours / 60;

//        total salary
        $total_salary_days = $total_salary / 30;
        $total_salary_hours = $total_salary_days / 8;
        $total_salary_minutes = $total_salary_hours / 60;

        if ($employee_variable_setting->emp_variables_type_code == 30) {     ///خصم ايام اجمالي و خصم عدم مباشره
            $salary = $employee->totalSalary;
            $value = ceil($total_salary_days * request()->days * $factor
                + $total_salary_hours * request()->hours * $factor + $total_salary_minutes * request()->minutes * $factor);
        }

        if ($employee_variable_setting->emp_variables_type_code == 31) { ///   خصم ساعات اساسي
            $salary = $employee->basicSalary;
            $value = ceil($basic_salary_days * request()->days * $factor +
                $basic_salary_hours * request()->hours * $factor + $basic_salary_minutes * request()->minutes * $factor);
        }

//        خصم عدم مباشره
        if ($employee_variable_setting->emp_variables_type_code == 32) {
            $salary = $employee->totalSalary;
            $value = request()->value;
        }

        return response()->json(['data' => $factor, 'value' => $value, 'salary' => $salary]);

    }


    public function deleteEmployeeVariableDetails(Request $request)
    {
        $employee_variable_detail = EmployeeVariableDt::find($request->id);
        $employee_variable_detail->delete();
        return response()->json(['data' => 'success']);

    }
}
