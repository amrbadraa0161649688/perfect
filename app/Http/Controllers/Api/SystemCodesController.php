<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmployeeContract;
use App\Models\SystemCode;
use Illuminate\Http\Request;

class SystemCodesController extends Controller
{
    public function getSalaryItems()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $old_system_codes = EmployeeContract::where('emp_contract_is_active', 1)->first()
            ->salaries->pluck('emp_salary_item_id')->toArray();

        $system_codes_all = SystemCode::where('sys_category_id', 25)
            ->where('company_group_id', $company->company_group_id)->pluck('system_code_id')->toArray();

        $system_codes_sub = array_diff($system_codes_all, $old_system_codes);
        $salary_details = SystemCode::whereIn('system_code_id', $system_codes_sub)->get();

        return response()->json(['data' => $salary_details]);

    }
}
