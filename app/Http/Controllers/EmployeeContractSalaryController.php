<?php

namespace App\Http\Controllers;

use App\Models\EmployeeContract;
use App\Models\EmployeeSalary;
use Illuminate\Http\Request;

class EmployeeContractSalaryController extends Controller
{

    public function checkDuplicates($arr)
    {

        $unique = array_unique($arr);

        $duplicates = array_diff_assoc($arr, $unique);

        if (count($duplicates) > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function store(Request $request)
    {

        //check arrays have is the theme length

        $contract = EmployeeContract::where('emp_contract_id', $request->emp_contract_id)->first();



        if ($this->checkDuplicates($request->emp_salary_item_id)) {
            return back()->with(['error' => 'يوجد تكرار لنفس النوع في مفردات الراتب']);
        }



        foreach ($request->emp_salary_item_id as $k => $contract_st) {

            EmployeeSalary::create([
                'emp_id' => $request->emp_id,
                'emp_contract_id' => $contract->emp_contract_id,
                'emp_salary_item_id' => $request->emp_salary_item_id[$k],
                'emp_contract_start' => $contract->emp_contract_start_date,
                'emp_contract_end' => $contract->emp_contract_end_date,
//                'emp_salary_notes' => $request->emp_salary_notes[$k],
                'emp_salary_debit' => isset($request->emp_salary_debit[$k]) ? $request->emp_salary_debit[$k] : 0,
                'emp_salary_credit' => isset($request->emp_salary_credit[$k]) ? $request->emp_salary_credit[$k] : 0,
                'emp_salary_is_active' => $contract->emp_contract_is_active,
                'created_user' => auth()->user()->user_id
            ]);


            $contract->emp_contract_total_salary = array_sum($request->emp_salary_credit) - array_sum($request->emp_salary_debit);
            $contract->save();

        }

        return back()->with(['success' => 'تم اضافه مفردات الراتب']);

    }

    protected function array_remove_null($item)
    {
        $array = (array_filter($item, function ($val) {
            if ($val > 0) {
                return true;
            } else {
                return false;
            }
        }));
        return $array;
    }


}
