<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\EmployeeSalary;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use App\Models\User;
use App\Models\UserBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeContractController extends Controller
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


    public function create($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $companies_ids = CompanyGroup::where('company_group_id', $company->company_group_id)->first()
            ->companies->pluck('company_id')->toArray();

        $employees = Employee::whereIn('emp_default_company_id', $companies_ids)
            ->where('emp_category', 494)->get();


        //$employees = Employee::where('emp_default_company_id', $company->company_id)->get();
        $employee = Employee::find($id);

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $contracts_types = SystemCode::where('company_group_id', $company->company_group_id)->
        where('sys_category_id', 24)->get();
        return view('EmployeeContracts.create', compact('employee', 'companies', 'contracts_types', 'employees'));
    }

    public function store(Request $request)
    {


        if ($request->emp_contract_is_active) {
            $emp_contract_old = EmployeeContract::where('emp_id', $request->emp_id)
                ->where('emp_contract_is_active', 1)->first();
            if (isset($emp_contract_old)) {
                return back()->with(['error' => 'يوجد عقد مفعل للموظف']);
            }
        }

        $emp_contract = EmployeeContract::create([
            'emp_id' => $request->emp_id,
            'emp_contract_type_id' => $request->emp_contract_type_id,
            'emp_contract_company_id' => $request->emp_contract_company_id,
            'emp_contract_job_id' => $request->emp_contract_job_id,
            'emp_contract_start_date' => $request->emp_contract_start_date,
            'emp_contract_end_date' => $request->emp_contract_end_date,
            'emp_contract_work_hours' => $request->emp_contract_work_hours,
//            'emp_contract_total_salary' => $request->emp_contract_total_salary,
            'emp_contract_ticket_type' => $request->emp_contract_ticket_type,
            'emp_contract_notes' => $request->emp_contract_notes,
            'emp_contract_branch_id' => $request->emp_contract_branch_id,
            'created_user' => auth()->user()->user_id,
            'emp_contract_manager_id' => $request->emp_contract_manager_id,
            'emp_contract_is_active' => $request->emp_contract_is_active ? 1 : 0,
        ]);

        $user = User::where('emp_id', $emp_contract->emp_id)->first();
        if (isset($user)) {
            $user_branch = UserBranch::where('user_id', $user->user_id)
                ->where('branch_id', $request->emp_contract_branch_id)->first();

            if (isset($user_branch)) {
                $user_branch->job_id = $request->emp_contract_job_id;
                $user_branch->start_date = $request->emp_contract_start_date;
                $user_branch->end_date = $request->emp_contract_end_date;
                $user_branch->save();
            }
        }
        return redirect('/employees-add/' . $request->emp_id . '/edit?qr=contracts');
    }


    public function update(Request $request, $id)
    {

        $contract = EmployeeContract::find($id);

        if ($contract->emp_contract_is_active != 1) {
            if ($request->emp_contract_is_active) {
                $other_contract = EmployeeContract::where('emp_id', $contract->emp_id)
                    ->where('emp_contract_is_active', 1)->first();
                if (isset($other_contract)) {
                    return back()->with(['error' => 'يوجد عقد اخر فعال']);
                }
            }

        }


        DB::beginTransaction();

        $contract = EmployeeContract::find($id);


        if ($request->emp_salary_item_id) {
            $all_details = array_merge($request->emp_salary_item_id_old, $request->emp_salary_item_id);

            if ($this->checkDuplicates($all_details)) {
                return back()->with(['error' => 'يوجد تكرار لنفس النوع في مفردات الراتب']);
            }
        } elseif($request->emp_salary_item_id_old) {
            if ($this->checkDuplicates($request->emp_salary_item_id_old)) {
                return back()->with(['error' => 'يوجد تكرار لنفس النوع في مفردات الراتب']);
            }

        }


        $contract->update([

//            'emp_contract_type_id' => $request->emp_contract_type_id,
            'emp_contract_job_id' => $request->emp_contract_job_id,
            'emp_contract_start_date' => $request->emp_contract_start_date,
            'emp_contract_end_date' => $request->emp_contract_end_date,
            'emp_contract_work_hours' => $request->emp_contract_work_hours,
            'emp_contract_notes' => $request->emp_contract_notes,
            'created_user' => auth()->user()->user_id,
            'emp_contract_manager_id' => $request->emp_contract_manager_id,
            'emp_contract_is_active' => $request->emp_contract_is_active ? 1 : 0,


        ]);


        if ($request->emp_salary_item_id_old) {
            foreach ($request->emp_salary_item_id_old as $k => $emp_salary) {
                $emp_salary = EmployeeSalary::where('emp_id_salary', $request->emp_id_salary_old[$k])->first();
                $emp_salary->update([
                    'emp_salary_item_id' => $request->emp_salary_item_id_old[$k],
                    'emp_contract_start' => $contract->emp_contract_start_date,
                    'emp_contract_end' => $contract->emp_contract_end_date,
//                'emp_salary_notes' => $request->emp_salary_notes[$k],
                    'emp_salary_debit' => $request->emp_salary_debit_old[$k],
                    'emp_salary_credit' => $request->emp_salary_credit_old[$k],
                    'emp_salary_is_active' => $contract->emp_contract_is_active,
                    'updated_user' => auth()->user()->user_id
                ]);
            }

        }


        if ($request->emp_salary_item_id) {
            foreach ($request->emp_salary_credit as $k => $contract_st) {
                EmployeeSalary::create([
                    'emp_id' => $contract->emp_id,
                    'emp_contract_id' => $contract->emp_contract_id,
                    'emp_salary_item_id' => $request->emp_salary_item_id[$k],
                    'emp_contract_start' => $contract->emp_contract_start_date,
                    'emp_contract_end' => $contract->emp_contract_end_date,
//                'emp_salary_notes' => $request->emp_salary_notes[$k],
                    'emp_salary_debit' => isset($request->emp_salary_debit[$k]) ? $request->emp_salary_debit[$k] : 0,
                    'emp_salary_credit' => isset($request->emp_salary_credit[$k]) ? $request->emp_salary_credit[$k] : 0,
                    'emp_salary_is_active' => $contract->emp_contract_is_active,
                    'created_user' => auth()->user()->user_id,
                    'updated_user' => auth()->user()->user_id,
                ]);
            }
        }

        if ($request->emp_salary_item_id_old) {

            $contract->emp_contract_total_salary = array_sum($request->emp_salary_credit) + array_sum($request->emp_salary_credit_old)
                - array_sum($request->emp_salary_debit) - array_sum($request->emp_salary_debit_old);

        } else {
            $contract->emp_contract_total_salary = array_sum($request->emp_salary_credit) - array_sum($request->emp_salary_debit);
        }


        $contract->save();
        $emp_salary->save();
        $user = User::where('emp_id', $contract->emp_id)->first();
        if (isset($user)) {
            $user->user_default_branch_id = $contract->emp_contract_branch_id;
            $user->save();
            $user_branch = UserBranch::where('user_id', $user->user_id)
                ->where('branch_id', $contract->emp_contract_branch_id)->first();
            if (isset($user_branch)) {
                $user_branch->job_id = $request->emp_contract_job_id;
                $user_branch->start_date = $request->emp_contract_start_date;
                $user_branch->end_date = $request->emp_contract_end_date;
                $user_branch->save();
            }
        }
        $employee=Employee::where('emp_id' , $contract->emp_id)->first();
        if(asset($employee)){

            $employee->emp_default_branch_id = $contract->emp_contract_branch_id;
            $employee->save();

        }

        DB::commit();
        return back()->with(['success' => 'تم تحديث العقد']);

    }


    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies_ids = CompanyGroup::where('company_group_id', $company->company_group_id)->first()
            ->companies->pluck('company_id')->toArray();

        $employees = Employee::whereIn('emp_default_company_id', $companies_ids)
            ->where('emp_category', 494)->get();
        $contract = EmployeeContract::find($id);
        return view('EmployeeContracts.edit', compact('contract', 'employees'));

    }


}
