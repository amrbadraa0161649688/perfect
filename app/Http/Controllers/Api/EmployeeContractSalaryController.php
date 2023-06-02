<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmployeeContract;
use App\Models\EmployeeSalary;
use Illuminate\Http\Request;

class EmployeeContractSalaryController extends Controller
{
    public function getSalaries()
    {
        $salaries = EmployeeSalary::where('emp_contract_id', request()->contract_id)->with('user')->get();
        return response()->json(['data' => $salaries]);
    }

    public function deleteSalaryDetail()
    {
        $salary_detail = EmployeeSalary::where('emp_id_salary', request()->id)->first();
        $salary = EmployeeContract::where('emp_contract_id', $salary_detail->emp_contract_id)->first();
        if ($salary_detail->emp_salary_debit > 0) {
            $salary->emp_contract_total_salary = $salary->emp_contract_total_salary + $salary_detail->emp_salary_debit;
            $salary->save();
        }
        if ($salary_detail->emp_salary_credit > 0) {
            $salary->emp_contract_total_salary = $salary->emp_contract_total_salary - $salary_detail->emp_salary_credit;
            $salary->save();
        }

        $salary_detail->delete();
        return response(['data' => 'deleted']);
    }
}
