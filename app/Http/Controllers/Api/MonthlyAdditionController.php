<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\AccounPeriod;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\EmployeeVariableDt;
use App\Models\EmployeeVariableSetting;
use App\Models\SystemCode;
use App\Models\Trucks;
use Illuminate\Http\Request;


class MonthlyAdditionController extends Controller
{
    public function getCompany()
    {
        ///////////get company from account period
        $company = Company::where('company_id', request()->company_id)->first();
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('emp_payroll_status', 0)->get();

        $branches_ids = $company->branches->pluck('branch_id')->toArray();

        $employees = Employee::whereIn('emp_default_branch_id', $branches_ids)->get();

        $employees_variables = EmployeeVariableSetting::where('company_group_id', $company->company_group_id)
            ->where('emp_variables_main_type', 1)->with('systemCodeType')->get();

        $branches = $company->branches;

        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 2)->select('customer_id', 'customer_name_full_ar',
                'customer_name_full_en')->get();

        $suppliers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 1)->select('customer_id', 'customer_name_full_ar',
                'customer_name_full_en')->get();

        $trucks = Trucks::where('company_id', $company->company_id)->select('truck_id', 'truck_code', 'truck_name')
            ->get();

        return response()->json(['status' => 200, 'data' => $accounts_periods, 'employees' => $employees,
            'employees_variables' => $employees_variables, 'branches' => $branches, 'customers' => $customers,
            'suppliers' => $suppliers, 'trucks' => $trucks]);
    }


    public function getEmployeeBranch()
    {
        $employee = Employee::where('emp_id', request()->emp_id)->first();
        $branch = Branch::where('branch_id', $employee->emp_default_branch_id)->first();
        return response()->json(['branch' => $branch]);

    }

    public function getEmployeeVariableCode()
    {
        $employee_variable_code = EmployeeVariableSetting::where('emp_variables_type_id', request()->id)
            ->first()->emp_variables_type_code;
        return response()->json(['data' => $employee_variable_code]);
    }


    public function getEmployeeVariableCodeSystemCode()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employee_variable_code = EmployeeVariableSetting::where('emp_variables_type_code', request()->id)
            ->first()->systemCodeType->system_code;

        return response()->json(['data' => $employee_variable_code]);
    }

    public function getEmployeeVariableFactor()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employee = Employee::find(request()->emp_id);

        if (request()->emp_variables_type_code) {
            $employee_variable_setting = EmployeeVariableSetting::where('emp_variables_type_code', request()->emp_variables_type_code)
                ->where('company_group_id', $company->company_group_id)->first();
        } else {
            $employee_variable_setting = EmployeeVariableSetting::where('emp_variables_type_id', request()->emp_variables_type_id)->first();
        }

        //return $employee_variable_setting;

        if (isset($employee_variable_setting)) {

            $factor = $employee_variable_setting->emp_variables_factor;

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

            if ($employee_variable_setting->emp_variables_type_code == 27 || $employee_variable_setting->emp_variables_type_code == 29) {   ///اضافي ايام اجمالي + اضافي ساعات اجمالي + حوافز شهريه
                $salary = $employee->totalSalary;
                $value = number_format($total_salary_days * request()->days * $factor
                    + $total_salary_hours * request()->hours * $factor + $total_salary_minutes * request()->minutes * $factor, 2);
            }

            if ($employee_variable_setting->emp_variables_type_code == 28 || $employee_variable_setting->emp_variables_type_code == 68) { ///اضافي ايام اساسي + اضافي ساعات اساسي
                $salary = $employee->basicSalary;

                $value = number_format($basic_salary_days * request()->days * $factor +
                    $basic_salary_hours * request()->hours * $factor + $basic_salary_minutes * request()->minutes * $factor, 2);
            }
            if ($employee_variable_setting->emp_variables_type_code == 69) { ///حوافز شهريه
                $salary = $employee->totalSalary;
                $value = request()->value;

            }


            return response()->json(['data' => $factor, 'value' => $value, 'salary' => $salary]);
        }
    }


    public function deleteEmployeeVariableDetails(Request $request)
    {
        $employee_variable_detail = EmployeeVariableDt::find($request->id);
        $employee_variable_detail->delete();
        return response()->json(['data' => 'success']);

    }

}
