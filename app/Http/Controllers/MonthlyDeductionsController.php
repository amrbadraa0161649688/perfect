<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Resources\MDResource;
use App\Models\EmployeeVariableDt;
use App\Models\EmployeeVariableHd;
use App\Models\AccounPeriod;
use Illuminate\Http\Request;
use App\Models\SystemCode;
use App\Models\Employee;
use App\Models\SMSCategory;
use Carbon\Carbon;
use Lang;
use App\Models\EmployeeVariableSetting;

class MonthlyDeductionsController extends Controller
{
    public function index()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $monthly_deductions = EmployeeVariableHd::where('emp_variables_main_type', 2)
            ->where('company_group_id', $company_group->company_group_id)
            ->where('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $monthly_deductions = EmployeeVariableHd::where('emp_variables_main_type', 2)->
            where('company_group_id', $company_group->company_group_id)
                ->where('company_id', request()->company_id)->get();
        }
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('MonthlyDeductions.index', compact('monthly_deductions', 'companies'));
    }

    public function create()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('MonthlyDeductions.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $company = Company::where('company_id', $request->company_id)->first();
        //  return $request->all();
        $employee_variable_hd = EmployeeVariableHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'acc_period_id' => $request->acc_period_id,
            'emp_variables_main_type' => 2, // الخصومات
            'created_user' => auth()->user()->user_id,
        ]);

        foreach ($request->emp_id as $k => $emp_id) {
            $employee_variable_setting = \App\Models\EmployeeVariableSetting::where('emp_variables_type_id', $request->emp_variables_type[$k])
                ->first();
            EmployeeVariableDt::create([
                'emp_variables_id' => $employee_variable_hd->emp_variables_id,
                'emp_id' => $emp_id,
                'acc_period_id' => $request->acc_period_id,
                'emp_variables_type' => $employee_variable_setting->emp_variables_type_code,
                'emp_variables_hours' => isset($request->emp_variables_hours[$k]) ? $request->emp_variables_hours[$k] : 0,
                'emp_variables_minutes' => isset($request->emp_variables_minutes[$k]) ? $request->emp_variables_minutes[$k] : 0,
                'emp_variables_days' => isset($request->emp_variables_days[$k]) ? $request->emp_variables_days[$k] : 0,
                'emp_variables_salary' => $request->emp_variables_salary[$k],
                'emp_variables_factor' => $request->emp_variables_factor[$k] ? $request->emp_variables_factor[$k] : 0,
                'emp_variables_debit' => $request->emp_variables_depit[$k],
                'emp_variables_notes' => $request->emp_variables_notes[$k],
                'emp_variables_main_type' => 2,
                'created_user' => auth()->user()->user_id


            ]);

            // $sys_cat = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '135001')->first()->system_code_id;
            // $category = SMSCategory::where('company_id', $request->company_id)->where('sms_category_type', $sys_cat)->first();
            // $employee = Employee::where('emp_id', $emp_id)->first();

            // $mobNo = '+966' . substr($employee->emp_work_mobile, 1); //'+966531512993';
            // $parm1 = $employee->emp_name_1_ar;
            // $parm2 = $request->emp_variables_depit[$k];
            // $parm4 = AccounPeriod::where('acc_period_id', $request->acc_period_id)->first()->acc_period_name_ar;
            // $parm3 = $request->emp_variables_notes[$k];
            // //$shortUrl = SMS\smsQueueController::getShortUrl($url);
            // $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, $parm2, $parm3, $parm4, null);

        }

        return redirect()->route('monthly-deductions')->with(['success' => 'تمت الاضافه']);
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
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            $monthly_deductions = EmployeeVariableHd::where('emp_variables_id', $id)->first();
            $monthly_deductions_dts = $monthly_deductions->employeeVariableDetails;
            return response()->json(['data' => $monthly_deductions,
                'monthly_deductions_dts' => MDResource::collection($monthly_deductions_dts)]);
        }

        $monthly_deductions = EmployeeVariableHd::find($id);
        $account_periods = AccounPeriod::where('company_id', $monthly_deductions->company_id)->get();

        $employees_variables = \App\Models\EmployeeVariableSetting::where('company_group_id', $company->company_group_id)
            ->where('emp_variables_main_type', 2)->with('systemCodeType')->get();

        $employees = Employee::where('company_group_id', $monthly_deductions->company_group_id)->get();

        return view('MonthlyDeductions.edit', compact('monthly_deductions',
            'employees', 'companies', 'account_periods', 'id', 'employees_variables'));
    }

    public function update(Request $request, $id)
    {
       // return $request->all();
        $company = session('company') ? session('company') : auth()->user()->company;
        $monthly_deductions = EmployeeVariableHd::find($id);
//        return $request->all();
        $monthly_deductions->update([
            'company_id' => $request->company_id,
            'updated_at' => Carbon::now(),
            'acc_period_id' => $request->acc_period_id,
            'updated_user' => auth()->user()->user_id
        ]);
        \DB::beginTransaction();
        foreach ($request->emp_variables_id_dt as $k => $emp_variables_id_dt) {
            if ($emp_variables_id_dt != 0) {
                $monthly_deductions_dt = EmployeeVariableDt::find($emp_variables_id_dt);

                $monthly_deductions_dt->update([
                    'emp_id' => $request->emp_id[$k],
                    'acc_period_id' => $request->acc_period_id,
                    'emp_variables_type' => $request->emp_variables_type[$k],
                    'emp_variables_hours' => $request->emp_variables_hours[$k],
                    'emp_variables_minutes' => $request->emp_variables_minutes[$k],
                    'emp_variables_days' => $request->emp_variables_days[$k],
                    'emp_variables_salary' => $request->emp_variables_salary[$k],
                    'emp_variables_factor' => $request->emp_variables_factor[$k],
                    'emp_variables_debit' => $request->emp_variables_debit[$k],
                    'emp_variables_notes' => $request->emp_variables_notes[$k],
                    'emp_variables_main_type' => 2,
                    'created_user' => auth()->user()->user_id
                ]);

            } else {
                $employee_variable_setting = EmployeeVariableSetting::where('emp_variables_type_code', $request->emp_variables_type[$k])
                    ->where('company_group_id', $company->company_group_id)->first();
                EmployeeVariableDt::create([
                    'emp_variables_id' => $monthly_deductions->emp_variables_id,
                    'acc_period_id' => $request->acc_period_id,
                    'emp_id' => $request->emp_id[$k],
                    'emp_variables_type' => $employee_variable_setting->emp_variables_type_code,
                    'emp_variables_hours' => isset($request->emp_variables_hours[$k]) ? $request->emp_variables_hours[$k] : 0,
                    'emp_variables_minutes' => isset($request->emp_variables_minutes[$k]) ? $request->emp_variables_minutes[$k] : 0,
                    'emp_variables_days' => isset($request->emp_variables_days[$k]) ? $request->emp_variables_days[$k] : 0,
                    'emp_variables_salary' => $request->emp_variables_salary[$k],
                    'emp_variables_factor' => $request->emp_variables_factor[$k] ? $request->emp_variables_factor[$k] : 0,
                    'emp_variables_debit' => $request->emp_variables_debit[$k],
                    'emp_variables_notes' => $request->emp_variables_notes[$k],
                    'emp_variables_main_type' => 2,
                    'created_user' => auth()->user()->user_id
                ]);
            }

        }
        //   $company = Company::where('company_id', $request->company_id)->first();

        //$this->removeNulls($request->loc_from);

        \DB::commit();
        return redirect()->route('monthly-deductions')->with(['success' => 'تم التعديل']);

    }


}
