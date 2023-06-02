<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccounPeriod;
use App\Models\Company;
use App\Models\EmployeeVariableDt;
use App\Models\EmployeeVariableHd;
use App\Models\EmployeeVariableSetting;

use App\Http\Resources\MDResource;
use App\Models\SystemCode;
use App\Models\Employee;
use Carbon\Carbon;

class MonthlyAdditionsController extends Controller
{
    public function index()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $monthly_additions = EmployeeVariableHd::where('emp_variables_main_type', 1)->
        where('company_group_id', $company_group->company_group_id)
            ->where('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $monthly_additions = EmployeeVariableHd::where('emp_variables_main_type', 1)
                ->where('company_id', request()->company_id)->get();
        }
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('MonthlyAdditions.index', compact('monthly_additions', 'companies'));
    }

    public function create()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('MonthlyAdditions.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $company = Company::where('company_id', $request->company_id)->first();

        $employee_variable_hd = EmployeeVariableHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'acc_period_id' => $request->acc_period_id,
            'emp_variables_main_type' => 1, // الاضافات
            'created_user' => auth()->user()->user_id
        ]);

        foreach ($request->emp_id as $k => $emp_id) {
            $employee_variable_setting = \App\Models\EmployeeVariableSetting::where('emp_variables_type_id', $request->emp_variables_type[$k])
                ->first();
            EmployeeVariableDt::create([
                'emp_variables_id' => $employee_variable_hd->emp_variables_id,
                'acc_period_id' => $request->acc_period_id,
                'emp_id' => $emp_id,
                'emp_variables_type' => $employee_variable_setting->emp_variables_type_code,
                'emp_variables_hours' => isset($request->emp_variables_hours[$k]) ? $request->emp_variables_hours[$k] : 0,
                'emp_variables_minutes' => isset($request->emp_variables_minutes[$k]) ? $request->emp_variables_minutes[$k] : 0,
                'emp_variables_days' => isset($request->emp_variables_days[$k]) ? $request->emp_variables_days[$k] : 0,
                'emp_variables_salary' => $request->emp_variables_salary[$k],
                'emp_variables_factor' => $request->emp_variables_factor[$k] ? $request->emp_variables_factor[$k] : 0,
                'emp_variables_credit' => $request->emp_variables_credit[$k],
                'emp_variables_notes' => $request->emp_variables_notes[$k],
                'emp_variables_main_type' => 1,
                'created_user' => auth()->user()->user_id
            ]);
        }

        return redirect()->route('monthly-additions')->with(['success' => 'تمت الاضافه']);
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


    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->companyGroup;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            $monthly_additions = EmployeeVariableHd::where('emp_variables_id', $id)->first();
            $monthly_additions_dts = $monthly_additions->employeeVariableDetails;
            return response()->json(['data' => $monthly_additions,
                'monthly_additions_dts' => MDResource::collection($monthly_additions_dts)]);
        }

        $monthly_additions = EmployeeVariableHd::find($id);
        $account_periods = AccounPeriod::where('company_id', $monthly_additions->company_id)->get();
//        $emp_variabl_type = SystemCode::where('sys_category_id', 14)->where('company_group_id', $monthly_additions->company_group_id)->get();

        $employees_variables = EmployeeVariableSetting::where('company_group_id', $company->company_group_id)
            ->where('emp_variables_main_type', 1)->with('systemCodeType')->get();

        $employees = Employee::where('company_group_id', $monthly_additions->company_group_id)->get();

        return view('MonthlyAdditions.edit', compact('monthly_additions',
            'employees', 'companies', 'account_periods', 'id', 'employees_variables'));
    }

    public function update(Request $request, $id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $monthly_additions = EmployeeVariableHd::find($id);
        $monthly_additions->update([
            'company_id' => $request->company_id,
            'updated_at' => Carbon::now(),
            'acc_period_id' => $request->acc_period_id,
            'updated_user' => auth()->user()->user_id
        ]);
        \DB::beginTransaction();
        foreach ($request->old_emp_variables_id_dt as $k => $emp_variables_id_dt) {

            if ($emp_variables_id_dt != 0) {
                $monthly_additions_dt = EmployeeVariableDt::find($emp_variables_id_dt);

                $monthly_additions_dt->update([
                    'emp_id' => $request->old_emp_id[$k],
                    'acc_period_id' => $request->acc_period_id,
                    'emp_variables_type' => $request->old_emp_variables_type[$k],
                    //'emp_variables_hours' => $request->old_emp_variables_hours[$k],
                    'emp_variables_minutes' => $request->old_emp_variables_minutes[$k],
                    'emp_variables_days' => $request->old_emp_variables_days[$k],
                    'emp_variables_salary' => $request->old_emp_variables_salary[$k],
                    'emp_variables_factor' => $request->old_emp_variables_factor[$k],
                    'emp_variables_credit' => $request->old_emp_variables_credit[$k],
                    'emp_variables_notes' => $request->old_emp_variables_notes[$k],
                    'emp_variables_main_type' => 1,
                    'created_user' => auth()->user()->user_id
                ]);
            } else {
                $employee_variable_setting = EmployeeVariableSetting::where('emp_variables_type_code', $request->old_emp_variables_type)
                    ->where('company_group_id', $company->company_group_id)->first();
                // return $employee_variable_setting;

                EmployeeVariableDt::create([
                    'emp_variables_id' => $monthly_additions->emp_variables_id,
                    'acc_period_id' => $request->acc_period_id,
                    'emp_id' => $request->old_emp_id_id[$k],
                    'emp_variables_type' => $employee_variable_setting->emp_variables_type_code,
                    'emp_variables_hours' => isset($request->old_emp_variables_hours[$k]) ? $request->old_emp_variables_hours[$k] : 0,
                    'emp_variables_minutes' => isset($request->old_emp_variables_minutes[$k]) ? $request->old_emp_variables_minutes[$k] : 0,
                    'emp_variables_days' => isset($request->old_emp_variables_days[$k]) ? $request->old_emp_variables_days[$k] : 0,
                    'emp_variables_salary' => $request->old_emp_variables_salary[$k],
                    'emp_variables_factor' => $request->old_emp_variables_factor[$k] ? $request->old_emp_variables_factor[$k] : 0,
                    'emp_variables_credit' => $request->old_emp_variables_credit[$k],
                    'emp_variables_notes' => $request->old_emp_variables_notes[$k],
                    'emp_variables_main_type' => 1,
                    'created_user' => auth()->user()->user_id
                ]);
            }


        }

        \DB::commit();
        return redirect()->route('monthly-additions')->with(['success' => 'تم التعديل']);

    }

}
