<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use Illuminate\Http\Request;

class EmployeeVariableSetting extends Controller
{
    public function index()
    {
        $company_auth = session('company') ? session('company') : auth()->user()->company;

        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $companies = Company::where('company_group_id', $company_auth->company_group_id)->get();
        //        انواع الاضافات
        $system_codes_add_ons = SystemCode::where('company_group_id', $company_group->company_group_id)
            ->where('company_group_id', $company_auth->company_group_id)->where('sys_category_id', 14)->get();
//        انواع الحسميات
        $system_codes_discounts = SystemCode::where('company_group_id', $company_group->company_group_id)
            ->where('company_group_id', $company_auth->company_group_id)->where('sys_category_id', 15)->get();
//         طرق احتساب الراتب
        $system_codes_methods = SystemCode::where('company_group_id', $company_group->company_group_id)
            ->where('company_group_id', $company_auth->company_group_id)->where('sys_category_id', 16)->get();
// نوع الراتب
        $system_codes_salary_types = SystemCode::where('company_group_id', $company_group->company_group_id)
            ->where('company_group_id', $company_auth->company_group_id)->where('sys_category_id', 17)->get();

        //        الااضافات السابقه
        $variables_add_ons = \App\Models\EmployeeVariableSetting::where('emp_variables_main_type', 1)
            ->where('company_group_id', $company_group->company_group_id)
            ->where('company_group_id', $company_auth->company_group_id)->get();

// الحسميات السابقه
        $variables_discounts = \App\Models\EmployeeVariableSetting::where('emp_variables_main_type', 2)
            ->where('company_group_id', $company_group->company_group_id)->get();

        if (request()->company_id) {
//        الااضافات السابقه
            $variables_add_ons = \App\Models\EmployeeVariableSetting::where('emp_variables_main_type', 1)
                ->where('company_group_id', $company_group->company_group_id)
                ->where('company_id', request()->company_id)->get();
// الحسميات السابقه
            $variables_discounts = \App\Models\EmployeeVariableSetting::where('emp_variables_main_type', 2)
                ->where('company_group_id', $company_group->company_group_id)
                ->where('company_id', request()->company_id)->get();

        }

        $accounts = Account::where('company_group_id', $company_auth->company_group_id)
            ->where('acc_level', $company_auth->companyGroup->accounts_levels_number)->get();

        $salary_types = SystemCode::where('sys_category_id', 25)->where('company_group_id', $company_auth->company_group_id)
            ->latest()->get();

        return view('EmployeeVariableSettings.index', compact('companies', 'system_codes_add_ons',
            'system_codes_discounts', 'system_codes_methods', 'system_codes_salary_types', 'variables_add_ons',
            'variables_discounts', 'accounts', 'company_auth', 'salary_types'));
    }

    public
    function storeAdd_ons(Request $request)
    {
        $emp_variables_type_codes = $this->array_remove_null($request->emp_variables_type_code);
        $emp_variables_salary_types = $this->array_remove_null($request->emp_variables_salary_type);
        $emp_variables_methods = $this->array_remove_null($request->emp_variables_method);
        $emp_variables_factors = $this->array_remove_null($request->emp_variables_factor);
        if ($this->checkDuplicates($emp_variables_type_codes)) {
            if (count($emp_variables_type_codes) != count($emp_variables_salary_types) ||
                count($emp_variables_type_codes) != count($emp_variables_methods) ||
                count($emp_variables_type_codes) != count($emp_variables_factors) ||
                count($emp_variables_salary_types) != count($emp_variables_methods) ||
                count($emp_variables_salary_types) != count($emp_variables_factors) |
                count($emp_variables_methods) != count($emp_variables_factors)) {
                return back()->with(['error' => 'يوجد بيانات غير مكتمله']);
            }
            $company = session('company') ? session('company') : auth()->user()->company;

            foreach ($emp_variables_type_codes as $k => $emp_variables_type_code) {

                \App\Models\EmployeeVariableSetting::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'emp_variables_type_code' => $emp_variables_type_code,
                    'emp_variables_salary_type' => $emp_variables_salary_types[$k],
                    'emp_variables_method' => $emp_variables_methods[$k],
                    'emp_variables_factor' => $emp_variables_factors[$k],
                    'emp_variables_main_type' => 1 // add_ons type
                ]);
            }

            return back()->with(['success' => 'تمت الاضافه']);
        } else {
            return back()->with(['error' => 'لايمكن اضافه نفس نوع الاضافه اكثر من مره']);
        }

    }

    public
    function editAdd_ons($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $variable_add_ons = \App\Models\EmployeeVariableSetting::where('emp_variables_type_id', $id)->first();

        //        انواع الاضافات
        $system_codes_add_ons = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('company_group_id', $company->company_group_id)->where('sys_category_id', 14)->get();
//         طرق احتساب الراتب
        $system_codes_methods = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('company_group_id', $company->company_group_id)->where('sys_category_id', 16)->get();
// نوع الراتب
        $system_codes_salary_types = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('company_group_id', $company->company_group_id)->where('sys_category_id', 17)->get();


        return view('EmployeeVariableSettings.edit', compact('variable_add_ons', 'system_codes_add_ons',
            'system_codes_methods', 'system_codes_salary_types'));
    }

    public
    function updateAdd_ons(Request $request, $id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employee_variable = \App\Models\EmployeeVariableSetting::find($id);

        if ($request->emp_variables_type_code != $employee_variable->emp_variables_type_code) {
            $old_variable = EmployeeVariableSetting::where('emp_variables_main_type', 1)
                ->where('company_group_id', $company->company_group_id)
                ->where('emp_variables_type_code', $request->emp_variables_type_code)->first();
            if (isset($old_variable)) {
                return back()->with(['error' => 'نفس نوع الاضافه موجود مسبقا']);
            } else {

                $employee_variable->update([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'emp_variables_type_code' => $request->emp_variables_type_code,
                    'emp_variables_salary_type' => $request->emp_variables_salary_type,
                    'emp_variables_method' => $request->emp_variables_method,
                    'emp_variables_factor' => $request->emp_variables_factor,
                ]);

                return redirect()->route('employees-variables-setting.add_ons')->with(['success' => 'تم التعديل']);
            }
        } else {
            $employee_variable->update([
                'emp_variables_type_code' => $request->emp_variables_type_code,
                'emp_variables_salary_type' => $request->emp_variables_salary_type,
                'emp_variables_method' => $request->emp_variables_method,
                'emp_variables_factor' => $request->emp_variables_factor,
            ]);

            return redirect()->route('employees-variables-setting.add_ons')->with(['success' => 'تم التعديل']);
        }


    }

    public
    function storeDiscounts(Request $request)
    {
        $emp_variables_type_codes = $this->array_remove_null($request->emp_variables_type_code);
        $emp_variables_salary_types = $this->array_remove_null($request->emp_variables_salary_type);
        $emp_variables_methods = $this->array_remove_null($request->emp_variables_method);
        $emp_variables_factors = $this->array_remove_null($request->emp_variables_factor);

        $company = session('company') ? session('company') : auth()->user()->company;

        if ($this->checkDuplicates($emp_variables_type_codes)) {
            if ($this->checkDuplicates($emp_variables_type_codes)) {
                if (count($emp_variables_type_codes) != count($emp_variables_salary_types) ||
                    count($emp_variables_type_codes) != count($emp_variables_methods) ||
                    count($emp_variables_type_codes) != count($emp_variables_factors) ||
                    count($emp_variables_salary_types) != count($emp_variables_methods) ||
                    count($emp_variables_salary_types) != count($emp_variables_factors) |
                    count($emp_variables_methods) != count($emp_variables_factors)) {
                    return back()->with(['error' => 'يوجد بيانات غير مكتمله']);
                }

                foreach ($emp_variables_type_codes as $k => $emp_variables_type_code) {
                    \App\Models\EmployeeVariableSetting::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'emp_variables_type_code' => $emp_variables_type_code,
                        'emp_variables_salary_type' => $emp_variables_salary_types[$k],
                        'emp_variables_method' => $emp_variables_methods[$k],
                        'emp_variables_factor' => $emp_variables_factors[$k],
                        'emp_variables_main_type' => 2 // discounts type
                    ]);
                }

                return back()->with(['success' => 'تمت الاضافه']);
            } else {
                return back()->with(['errors' => 'لايمكن اضافه نفس نوع الخصم اكثر من مره']);
            }
        } else {
            return back()->with(['error' => 'لايمكن اضافه نفس نوع الاضافه اكثر من مره']);
        }

    }

    public
    function editDiscount($id)
    {
        $variable_discount = \App\Models\EmployeeVariableSetting::where('emp_variables_type_id', $id)->first();
        $company = session('company') ? session('company') : auth()->user()->company;

        //        انواع الاضافات
        $system_codes_discounts = SystemCodeCategory::where('company_group_id', $company->company_group_id)->where('sys_category_id', 15)->first()->systemCodes;
//         طرق احتساب الراتب
        $system_codes_methods = SystemCodeCategory::where('company_group_id', $company->company_group_id)->where('sys_category_id', 16)->first()->systemCodes;
// نوع الراتب
        $system_codes_salary_types = SystemCodeCategory::where('company_group_id', $company->company_group_id)->where('sys_category_id', 17)->first()->systemCodes;

        return view('EmployeeVariableSettings.editDiscount', compact('variable_discount', 'system_codes_discounts',
            'system_codes_methods', 'system_codes_salary_types'));
    }

    public
    function updateDiscount(Request $request, $id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $employee_variable = \App\Models\EmployeeVariableSetting::find($id);

        if ($request->emp_variables_type_code != $employee_variable->emp_variables_type_code) {
            $old_variable = EmployeeVariableSetting::where('emp_variables_main_type', 2)
                ->where('company_group_id', $company->company_group_id)
                ->where('emp_variables_type_code', $request->emp_variables_type_code)->first();
            if (isset($old_variable)) {
                return back()->with(['error' => 'نفس نوع الاضافه موجود مسبقا']);
            } else {
                $employee_variable->update([
                    'emp_variables_type_code' => $request->emp_variables_type_code,
                    'emp_variables_salary_type' => $request->emp_variables_salary_type,
                    'emp_variables_method' => $request->emp_variables_method,
                    'emp_variables_factor' => $request->emp_variables_factor,
                ]);

                return redirect()->route('employees-variables-setting.add_ons')->with(['success' => 'تم التعديل']);

            }
        } else {
            $employee_variable->update([
                'emp_variables_type_code' => $request->emp_variables_type_code,
                'emp_variables_salary_type' => $request->emp_variables_salary_type,
                'emp_variables_method' => $request->emp_variables_method,
                'emp_variables_factor' => $request->emp_variables_factor,
            ]);

            return redirect()->route('employees-variables-setting.add_ons')->with(['success' => 'تم التعديل']);

        }

    }


//     remove null from array
    protected
    function array_remove_null($item)
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


//    check if array has duplicate values
    public
    function checkDuplicates($item)
    {
        $array_reduced = array_unique($item);
        if (count($item) == count($array_reduced)) {
            return true;
        } else {
            return false;
        }
    }

    public function storeSalariesAccount(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $company->co_salary_account = $request->co_salary_account;
        $company->save();
        return back();
    }

    public function storeSalaryTypesAccounts(Request $request)
    {
        foreach ($request->system_code_id as $k => $system_code_id) {
            $system_code = SystemCode::where('system_code_id', $system_code_id)->first();
            if (isset($request->system_code_acc_id[$k])) {
                $system_code->system_code_acc_id = $request->system_code_acc_id[$k];
                $system_code->save();
            }
        }

        return back();
    }
}
