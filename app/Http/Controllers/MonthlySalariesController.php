<?php

namespace App\Http\Controllers;

use App\Models\AccounPeriod;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\EmployeesMonthlyPayroll;
use App\Models\EmployeeVariableDt;
use App\Models\JournalDt;
use App\Models\JournalHd;
use App\Models\JournalType;
use App\Models\SystemCode;
use App\Models\Reports;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\countOf;

class MonthlySalariesController extends Controller
{
    //
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $account_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('emp_payroll_status', 1)->get();
        if (request()->company_id) {
            $account_periods = AccounPeriod::where('company_id', request()->company_id)
                ->where('emp_payroll_status', 1)->get();
        }

        $report_url_salary = Reports::where('company_id', $company->company_id)
            ->where('report_code', '16001')->get();

        $report_url_salary_h = Reports::where('company_id', $company->company_id)
            ->where('report_code', '16002')->get();

            $report_url_salary_emp = Reports::where('company_id', $company->company_id)
            ->where('report_code', '16005')->get();    

        return view('MonthlySalaries.index', compact('account_periods', 'companies', 'report_url_salary', 'report_url_salary_h','report_url_salary_emp'));
    }


    public function show($id)
    {
        $account_period = AccounPeriod::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;

        $branches = $company->branches;
        $monthly_salaries = EmployeesMonthlyPayroll::where('period_id', $account_period->acc_period_id)->get();

        if (request()->branch_id) {
            $monthly_salaries = EmployeesMonthlyPayroll::where('period_id', $account_period->acc_period_id)
                ->whereIn('emp_branch_id', request()->branch_id)->get();
        }
        $report_url_salary = Reports::where('company_id', $company->company_id)
            ->where('report_code', '16001')->get();

        $report_url_salary_h = Reports::where('company_id', $company->company_id)
            ->where('report_code', '16002')->get();

            $report_url_salary_emp = Reports::where('company_id', $company->company_id)
            ->where('report_code', '16005')->get();   

        return view('MonthlySalaries.show', compact('monthly_salaries', 'branches', 'account_period', 'report_url_salary', 'report_url_salary_h','report_url_salary_emp'));
    }


    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $system_code_benefits = SystemCode::where('sys_category_id', 25)->where('company_id', $company->company_id)->get();


        $employees_ids = EmployeeContract::where('emp_contract_company_id', request()->company_id)
            ->where('emp_contract_is_active', 1)->pluck('emp_id')->toArray();

            $employees = Employee::where('emp_status', SystemCode::where('system_code', 23)->where('company_group_id', $company->company_group_id)->first()->system_code_id)
            ->whereIn('emp_id', $employees_ids)->get();
    
            if (request()->branch_id) {
                $employees_ids = EmployeeContract::where('emp_contract_company_id', request()->company_id)
                    ->where('emp_contract_is_active', 1)->whereIn('emp_contract_branch_id', request()->branch_id)
                    ->pluck('emp_id')->toArray();
                $employees = Employee::where('emp_status', SystemCode::where('system_code', 23)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->whereIn('emp_id', $employees_ids)->get();
            }
    

        return view('MonthlySalaries.create', compact('system_code_benefits', 'companies', 'employees'));
    }


    public function store(Request $request)
    {
//        DB::beginTransaction();

        $company = Company::find($request->company_id);
        $acc_period = AccounPeriod::find($request->acc_period_id);
        $acc_period_new = AccounPeriod::where('company_group_id', $company->company_group_id)->where( 'acc_period_year' , $acc_period->acc_period_year)->where( 'acc_period_month' , ($acc_period->acc_period_month) + 1)->first();


        $journal_status = SystemCode::where('system_code', 903)->where('company_group_id', $company->company_group_id)->first();

        $amount_h = array_sum($request->emp_due_salary);

        $journal_notes = 'قيد رواتب  ' . ' '  . ' ' . $acc_period->acc_period_name_ar;

        $cost_center_type_id = SystemCode::where('system_code', 56005)->where('company_group_id', $company->company_group_id)
            ->first()->system_code_id;

        $journal_type = SystemCode::where('system_code', 809)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_category = JournalType::where('journal_types_code', 80)
            ->where('company_group_id', $company->company_group_id)->first();

           // $journal = $this->addaMonthlySalariesJournal($request->emp_id, $journal_type, $journal_status, $amount_h,
           //    $journal_category, $journal_notes, $request->acc_period_id, 16,
          //  $cost_center_type_id);

            
            $journal = $this->addSalariesJournal($request->emp_id, $journal_type, $journal_status, $amount_h,
            $journal_category, $journal_notes, $request->acc_period_id, 16,
            $cost_center_type_id);

        $count = count($request->emp_id);
        $total_salaries = array_sum($request->emp_net_salary);

        foreach ($request->emp_id as $k => $emp_id) {

            $employee = Employee::find($emp_id);

            $monthly_payroll = EmployeesMonthlyPayroll::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'emp_id' => $emp_id,
                'period_id' => $acc_period->acc_period_id,
                'period_month' => $acc_period->acc_period_month,
                'period_year' => $acc_period->acc_period_year,
                'emp_name_full_ar' => $employee->emp_name_full_ar,
                'emp_name_full_en' => $employee->emp_name_full_en,
                'emp_code' => $employee->emp_code,
                'emp_identity' => $employee->emp_identity,
                'emp_status' => $employee->emp_status,
                'emp_status_name_ar' => $employee->status->system_code_name_ar,
                'emp_status_name_en' => $employee->status->system_code_name_en,
                'emp_direct_date' => $employee->emp_direct_date,
                'emp_branch_id' => $employee->emp_default_branch_id,
                'emp_branch_name_ar' => $employee->branch->branch_name_ar,
                'emp_branch_name_en' => $employee->branch->branch_name_en,
                'emp_bank_id' => $employee->emp_bank_id,
                'emp_bank_code' => $employee->bank ? $employee->bank->system_code_id : null,
                'emp_bank_name_ar' => $employee->bank ? $employee->bank->system_code_name_ar : null,
                'emp_bank_name_en' => $employee->bank ? $employee->bank->system_code_name_en : null,
                'emp_is_bank_payment' => $employee->emp_is_bank_payment,
                'emp_main_salary' => $request->emp_main_salary[$k],
                'emp_housing_salary' => $request->emp_housing_salary[$k],
                'emp_transportation_salary' => $request->emp_transportation_salary[$k],
                'emp_food_salary' => $request->emp_food_salary[$k],
                'emp_nature_work_salary' => $request->emp_nature_work_salary[$k],
                'emp_allowance_salary' => $request->emp_allowance_salary[$k],
                'emp_others_salary' => $request->emp_others_salary[$k],
                'emp_add_monthly_salary' => $request->emp_add_monthly_salary[$k],
                'emp_due_salary' => $request->emp_due_salary[$k],
                'emp_insurance_salary' => $request->emp_insurance_salary[$k],
                'emp_loans_salary' => $request->emp_loans_salary[$k],
                'emp_deducts_salary' => $request->emp_deducts_salary[$k],
                'emp_deducts_monthly_salary' => $request->emp_deducts_monthly_salary[$k],
                'emp_deducts_total' => $request->emp_deducts_total[$k],
                'emp_net_salary' => $request->emp_net_salary[$k],
                'created_user' => auth()->user()->user_id

            ]);

            $employee->update([
                'emp_direct_date' => $acc_period_new->start_date
            ]);

        }

        $acc_period->update([
            'emp_payroll_status' => 1,
            'acc_period_is_payroll' => false,
            'emp_payroll_employee_no' => $count,
            'emp_payroll_net_amout' => $total_salaries

        ]);

        $acc_period_new->update([
            'emp_payroll_status' => 0,
            'acc_period_is_payroll' => true
        ]);  
//        DB::commit();

        return redirect()->route('monthly-salaries')->with(['success' => 'تمت اضافه الرواتب']);

    }


////////////////////////////////////////////////////////////////



public function addSalariesJournal($emp_id, $journal_type, $journal_status, $amount_h, $journal_category,
                                            $journal_notes, $account_period_id, $cost_center_id,
                                            $cost_center_type_id)
    {
                        $company = session('company') ? session('company') : auth()->user()->company;
                        $employees = Employee::whereIn('emp_id', $emp_id)->get();
                        $acc_period = AccounPeriod::find($account_period_id);

                        $last_journal_reference = CompanyMenuSerial::where('branch_id', session('branch')['branch_id'])
                        ->where('app_menu_id', 16)->latest()->first();

                        if (isset($last_journal_reference)) {
                                $last_journal_reference_number = $last_journal_reference->serial_last_no;
                                $array_number = explode('-', $last_journal_reference_number);
                                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                                $string_number_journal = implode('-', $array_number);
                                $last_journal_reference->update(['serial_last_no' => $string_number_journal]);
                                } else {
                                $string_number_journal = 'J-' . session('branch')['branch_id'] . '-1';
                                CompanyMenuSerial::create([
                                'company_group_id' => $company->company_group_id,
                                'company_id' => $company->company_id,
                                'branch_id' => session('branch')['branch_id'],
                                'app_menu_id' => 16,
                                'acc_period_year' => Carbon::now()->format('y'),
                                'serial_last_no' => $string_number_journal,
                                'created_user' => auth()->user()->user_id
                                ]);
                            }

                        $journal_hd = JournalHd::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'journal_type_id' => $journal_type->system_code_id,
                        'journal_hd_code' => $string_number_journal,
                        'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                        'journal_status' => $journal_status->system_code_id,
                        'journal_user_entry_id' => auth()->user()->user_id,
                        'journal_user_update_id' => auth()->user()->user_id,
                        'journal_hd_date' => $acc_period->end_date,
                        'journal_hd_credit' => $amount_h,
                        'journal_hd_debit' => $amount_h,
                        'journal_category_id' => $journal_category->journal_types_id,
                        'journal_hd_notes' => $journal_notes
                        ]);


                        $journal_dt = [];
                        $journal_dt_2 = [];
                        $journal_dt_3 = [];
                        $journal_dt_5 = [];
                        $journal_dt_56 = [];
                        $journal_dt_29 = [];
                        $journal_dt_27 = [];


                        foreach ($employees->groupBy('emp_default_branch_id') as $k => $employee_g) {

                                    $branch = Branch::where('branch_id', $k)->first();
                                                foreach ($employee_g as $employee) {

                                                    $emptotalsalary = $employee->basicSalary + $employee->housingSalary
                                                    + $employee->transportSalary
                                                    + $employee->foodSalary
                                                    +  $employee->naturalSalary
                                                    + $employee->allowanceSalary
                                                    + $employee->otherSalary
                                                    +$employee->employeeSalaryAdds ;

                                                            $journal_obj_49 = $this->createJournalDtObj($branch->store_acc_id, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                                                            $journal_hd->journal_hd_date, $journal_hd->journal_status, $emptotalsalary, $cost_center_type_id,
                                                            $cost_center_id, $branch);

                                                        array_push($journal_dt, $journal_obj_49);



                                                            // foreach ($employee_g as $employee) {
                                    
                                                            // }
                                                }

                                                    ///////////// خصومات الغياب لكل فرع
                                                foreach ($employee_g as $employee) {
                                                    $emptotalcut = $employee->employeeSubCut ;
                                                    $account_id_27 = SystemCode::where('company_group_id', $company->company_group_id)
                                                    ->where('system_code', 2701)->first()->system_code_acc_id;

                                                            $journal_obj_27 = $this->createJournalDtObjCredit($account_id_27, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                                                            $journal_hd->journal_hd_date, $journal_hd->journal_status,  $emptotalcut, $cost_center_type_id,
                                                            $cost_center_id, $branch);

                                                        array_push($journal_dt_27, $journal_obj_27);



                                                            // foreach ($employee_g as $employee) {
                                    
                                                            // }
                                                }


                                     foreach ($employee_g as $employee) {

                                        ///////////////تامينات اجتماعي
                                        $account_id_56 = SystemCode::where('company_group_id', $company->company_group_id)
                                        ->where('system_code', 56)->first()->system_code_acc_id;
  

                                        $journal_obj_56 = $this->createJournalDtObj2($account_id_56, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                                            $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->insuranceSalary, $cost_center_type_id,
                                            $cost_center_id, $employee);
                                            
                                            array_push($journal_dt_56, $journal_obj_56);
                                           
                                        }

                                            
                                            
                                                        /////////////  السلف لكل موظف
                                            foreach ($employee_g as $employee) {

                                                //$employee_vars_ids_2 = $employee->employeeSubSolaf->pluck('emp_variables_type')->toarray();

                                                //$employee_vars_ids_3 = $employee_vars_ids_2->where('emp_id',$employee->emp_id)->first();
                                                $system_code_adds_ids = SystemCode::whereIn('system_code', [2801])->pluck('system_code_acc_id');

                                                $system_code_adds_ids_2 = SystemCode::where('company_group_id', $company->company_group_id)
                                                ->where('system_code', 2801)->first()->system_code_acc_id;
    

                                                $journal_obj_sub = $this->createJournalDtObj2($system_code_adds_ids_2, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                                                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->employeeSubSolaf, $cost_center_type_id,
                                                    $cost_center_id, $employee);

                                                array_push($journal_dt_3, $journal_obj_sub);
                                            }

                                                    /////////////  التعويضات
                                            foreach ($employee_g as $employee) {

                                               // $employee_vars_ids_2 = $employee->employeeSubTawedat->pluck('emp_variables_type')->toarray();

                                                //$employee_vars_ids_3 = $employee_vars_ids_2->where('emp_id',$employee->emp_id)->first();
                                                $system_code_adds_ids = SystemCode::whereIn('system_code',[2901] )->pluck('system_code_acc_id');

                                                $system_code_adds_ids_2 = SystemCode::where('company_group_id', $company->company_group_id)
                                                ->where('system_code', 2901)->first()->system_code_acc_id;
    

                                                $journal_obj_sub_29 = $this->createJournalDtObj2($system_code_adds_ids_2, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                                                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->employeeSubTawedat, $cost_center_type_id,
                                                    $cost_center_id, $employee);

                                                array_push($journal_dt_29, $journal_obj_sub_29);
                                            }



                                            foreach ($employee_g as $employee) {

                                                $employee_vars_ids_2 = $employee->employeeVariableDtSubs->pluck('emp_variables_type')->toarray();

                                                $empnetsalary = $employee->basicSalary +$employee->housingSalary
                                                + $employee->transportSalary
                                                + $employee->foodSalary
                                                + $employee->naturalSalary
                                                + $employee->allowanceSalary
                                                + $employee->otherSalary
                                                +  $employee->employeeSalaryAdds

                                              - ($employee->insuranceSalary
                                               + $employee->loansSalary
                                               + $employee->deductsSalary
                                               +$employee->EmployeeSalarySubs) ;
    

                                                $journal_obj_sub = $this->createJournalDtObj2($company->co_salary_account, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                                                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $empnetsalary, $cost_center_type_id,
                                                    $cost_center_id, $employee);

                                                array_push($journal_dt_5, $journal_obj_sub);
                                            }


                        }
                                             $collection_debit = new Collection($journal_dt);
                                            $collection_credit = new Collection($journal_dt_2);
                                            $collection_credit_3 = new Collection($journal_dt_3);
                                            $collection_credit_5 = new Collection($journal_dt_5);
                                            $collection_credit_56 = new Collection($journal_dt_56);
                                            $collection_credit_29 = new Collection($journal_dt_29);
                                            $collection_credit_27 = new Collection($journal_dt_27);
                    
                
                
                                                    foreach ($collection_debit->groupBy(['cc_branch_id']) as $collection_d) {
                                                        $journal_dt_debit = $collection_d->sum('journal_dt_debit');
                                                        $amount_debit_total[] = $collection_d->sum('journal_dt_debit');
                                                        $branchn = Branch::where('branch_id',  $collection_d[0]['cc_branch_id'])->first();
                                                        $acc_period = AccounPeriod::find($account_period_id);

                                                        if ($journal_dt_debit > 0) {
                                                            $journal_dtt = JournalDt::create([
                                                                'company_group_id' => $company->company_group_id,
                                                                'company_id' => $company->company_id,
                                                                'branch_id' => session('branch')['branch_id'],
                                                                'journal_type_id' => $journal_type->system_code_id,
                                                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                                                'period_id' => $account_period_id,
                                                                'journal_dt_date' =>$journal_hd->journal_hd_date,
                                                                'journal_status' => $collection_d[0]['journal_status'],
                                                                'account_id' => $collection_d[0]['account_id'],
                                                                'journal_dt_debit' => $journal_dt_debit,
                                                                'journal_dt_credit' => 0,
                                                                'journal_dt_balance' => $journal_dt_debit,
                                                                'journal_user_entry_id' => auth()->user()->user_id,
                                                                'cc_branch_id' => $collection_d[0]['cc_branch_id'],
                                                                'cost_center_type_id' => $collection_d[0]['cost_center_type_id'],
                                                                'cost_center_id' => $collection_d[0]['cost_center_id'],////from application menu
                                                                'cc_voucher_id' => null,
                                                                'journal_dt_notes' => 'قيد رواتب ' . ' ' . $branchn->branch_name_ar . ' ' . $acc_period->acc_period_name_ar
                                                            ]);
                                                        
                                                        }
                                                    }                         

                                                    foreach ($collection_credit_27->groupBy(['cc_branch_id']) as $collection_credit) {
                                                        $journal_dt_credit = $collection_credit->sum('journal_dt_credit');
                                                        $amount_debit_total[] = $collection_credit->sum('journal_dt_credit');
                                                        $branchn = Branch::where('branch_id',  $collection_credit[0]['cc_branch_id'])->first();
                                                        $acc_period = AccounPeriod::find($account_period_id);

                                                        if ($journal_dt_credit > 0) {
                                                            $journal_dtt = JournalDt::create([
                                                                'company_group_id' => $company->company_group_id,
                                                                'company_id' => $company->company_id,
                                                                'branch_id' => session('branch')['branch_id'],
                                                                'journal_type_id' => $journal_type->system_code_id,
                                                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                                                'period_id' => $account_period_id,
                                                                'journal_dt_date' => $journal_hd->journal_hd_date,
                                                                'journal_status' => $collection_credit[0]['journal_status'],
                                                                'account_id' => $collection_credit[0]['account_id'],
                                                                'journal_dt_debit' => 0,
                                                                'journal_dt_credit' => $journal_dt_credit,
                                                                'journal_dt_balance' => $journal_dt_credit,
                                                                'journal_user_entry_id' => auth()->user()->user_id,
                                                                'cc_branch_id' => $collection_credit[0]['cc_branch_id'],
                                                                'cost_center_type_id' => $collection_credit[0]['cost_center_type_id'],
                                                                'cost_center_id' => $collection_credit[0]['cost_center_id'],////from application menu
                                                                'cc_voucher_id' => null,
                                                                'journal_dt_notes' => 'خصومات  ' . ' ' . $branchn->branch_name_ar . ' ' . $acc_period->acc_period_name_ar
                                                            ]);
                                                        
                                                        }
                                                    }                         


                                                    foreach ($collection_credit_3->groupBy('cc_employee_id') as $collection_c) {
                                                        $journal_dt_credit = $collection_c->sum('journal_dt_credit');
                                                        $amount_credit_total[] = $collection_c->sum('journal_dt_credit');

                                                        $employeen = Employee::where('emp_id',  $collection_c[0]['cc_employee_id'])->first();
                                                        $accountn = Account::where('acc_id',  $collection_c[0]['account_id'])->first();
                                                        $acc_period = AccounPeriod::find($account_period_id);

                                                        if ($journal_dt_credit > 0) {
                                                            $journal_dtt_c = JournalDt::create([
                                                                'company_group_id' => $company->company_group_id,
                                                                'company_id' => $company->company_id,
                                                                'branch_id' => session('branch')['branch_id'],
                                                                'journal_type_id' => $journal_type->system_code_id,
                                                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                                                'period_id' => $account_period_id,
                                                                'journal_dt_date' => $journal_hd->journal_hd_date,
                                                                'journal_status' => $collection_c[0]['journal_status'],
                                                                'account_id' => $collection_c[0]['account_id'],
                                                                'journal_dt_debit' => 0,
                                                                'journal_dt_credit' => $journal_dt_credit,
                                                                'journal_dt_balance' => $journal_dt_credit,
                                                                'journal_user_entry_id' => auth()->user()->user_id,
                                                               
                                                                'cc_employee_id' => $collection_c[0]['cc_employee_id'],
                                                                'cost_center_type_id' => $collection_c[0]['cost_center_type_id'],
                                                                'cost_center_id' => $collection_c[0]['cost_center_id'],////from application menu
                                                                'cc_voucher_id' => null,
                                                                'journal_dt_notes' => $accountn->acc_name_ar . ' ' . $employeen->emp_name_full_ar . ' ' . $acc_period->acc_period_name_ar
                                                            ]);
                                                        }
                                                    }

                                                    foreach ($collection_credit_29->groupBy('cc_employee_id') as $collection_c29) {
                                                        $journal_dt_credit = $collection_c29->sum('journal_dt_credit');
                                                        $amount_credit_total[] = $collection_c29->sum('journal_dt_credit');

                                                        $employeen = Employee::where('emp_id',  $collection_c29[0]['cc_employee_id'])->first();
                                                        $accountn = Account::where('acc_id',  $collection_c29[0]['account_id'])->first();
                                                        $acc_period = AccounPeriod::find($account_period_id);

                                                        if ($journal_dt_credit > 0) {
                                                            $journal_dtt_c = JournalDt::create([
                                                                'company_group_id' => $company->company_group_id,
                                                                'company_id' => $company->company_id,
                                                                'branch_id' => session('branch')['branch_id'],
                                                                'journal_type_id' => $journal_type->system_code_id,
                                                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                                                'period_id' => $account_period_id,
                                                                'journal_dt_date' => $journal_hd->journal_hd_date,
                                                                'journal_status' => $collection_c29[0]['journal_status'],
                                                                'account_id' => $collection_c29[0]['account_id'],
                                                                'journal_dt_debit' => 0,
                                                                'journal_dt_credit' => $journal_dt_credit,
                                                                'journal_dt_balance' => $journal_dt_credit,
                                                                'journal_user_entry_id' => auth()->user()->user_id,
                                                               
                                                                'cc_employee_id' => $collection_c29[0]['cc_employee_id'],
                                                                'cost_center_type_id' => $collection_c29[0]['cost_center_type_id'],
                                                                'cost_center_id' => $collection_c29[0]['cost_center_id'],////from application menu
                                                                'cc_voucher_id' => null,
                                                                'journal_dt_notes' => 'تعويضات' . ' ' . $employeen->emp_name_full_ar . ' ' . $acc_period->acc_period_name_ar
                                                            ]);
                                                        }
                                                    }





                                                    foreach ($collection_credit_56->groupBy('cc_employee_id') as $collection_c56) {
                                                        $journal_dt_credit = $collection_c56->sum('journal_dt_credit');
                                                        $amount_credit_total[] = $collection_c56->sum('journal_dt_credit');

                                                        $employeen = Employee::where('emp_id',  $collection_c56[0]['cc_employee_id'])->first();
                                                        $accountn = Account::where('acc_id',  $collection_c56[0]['account_id'])->first();
                                                        $acc_period = AccounPeriod::find($account_period_id);

                                                        if ($journal_dt_credit > 0) {
                                                            $journal_dtt_c = JournalDt::create([
                                                                'company_group_id' => $company->company_group_id,
                                                                'company_id' => $company->company_id,
                                                                'branch_id' => session('branch')['branch_id'],
                                                                'journal_type_id' => $journal_type->system_code_id,
                                                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                                                'period_id' => $account_period_id,
                                                                'journal_dt_date' =>$journal_hd->journal_hd_date,
                                                                'journal_status' => $collection_c56[0]['journal_status'],
                                                                'account_id' => $collection_c56[0]['account_id'],
                                                                'journal_dt_debit' => 0,
                                                                'journal_dt_credit' => $journal_dt_credit,
                                                                'journal_dt_balance' => $journal_dt_credit,
                                                                'journal_user_entry_id' => auth()->user()->user_id,
                                                               
                                                                'cc_employee_id' => $collection_c56[0]['cc_employee_id'],
                                                                'cost_center_type_id' => $collection_c56[0]['cost_center_type_id'],
                                                                'cost_center_id' => $collection_c56[0]['cost_center_id'],////from application menu
                                                                'cc_voucher_id' => null,
                                                                'journal_dt_notes' => $accountn->acc_name_ar . ' ' . $employeen->emp_name_full_ar . ' ' . $acc_period->acc_period_name_ar
                                                            ]);
                                                        }
                                                    }

                                                    foreach ($collection_credit_5->groupBy('cc_employee_id') as $collection_c) {
                                                        $journal_dt_credit = $collection_c->sum('journal_dt_credit');
                                                        $amount_credit_total[] = $collection_c->sum('journal_dt_credit');

                                                        $employeen = Employee::where('emp_id',  $collection_c[0]['cc_employee_id'])->first();
                                                        $accountn = Account::where('acc_id',  $collection_c[0]['account_id'])->first();
                                                         $acc_period = AccounPeriod::find($account_period_id);

                                                        if ($journal_dt_credit > 0) {
                                                            $journal_dtt_c = JournalDt::create([
                                                                'company_group_id' => $company->company_group_id,
                                                                'company_id' => $company->company_id,
                                                                'branch_id' => session('branch')['branch_id'],
                                                                'journal_type_id' => $journal_type->system_code_id,
                                                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                                                'period_id' => $account_period_id,
                                                                'journal_dt_date' => $journal_hd->journal_hd_date,
                                                                'journal_status' => $collection_c[0]['journal_status'],
                                                                'account_id' => $collection_c[0]['account_id'],
                                                                'journal_dt_debit' => 0,
                                                                'journal_dt_credit' => $journal_dt_credit,
                                                                'journal_dt_balance' => $journal_dt_credit,
                                                                'journal_user_entry_id' => auth()->user()->user_id,
                                                               
                                                                'cc_employee_id' => $collection_c[0]['cc_employee_id'],
                                                                'cost_center_type_id' => $collection_c[0]['cost_center_type_id'],
                                                                'cost_center_id' => $collection_c[0]['cost_center_id'],////from application menu
                                                                'cc_voucher_id' => null,
                                                                'journal_dt_notes' => $accountn->acc_name_ar . ' ' . $employeen->emp_name_full_ar . ' ' . $acc_period->acc_period_name_ar
                                                            ]);
                                                        }
                                                    }


                        
                        

                                                    return $journal_hd;            

    }

    /////////////////////////////////////////////////////////////////////////////



    public function addaMonthlySalariesJournal($emp_id, $journal_type, $journal_status, $amount_h, $journal_category,
                                               $journal_notes, $account_period_id, $cost_center_id,
                                               $cost_center_type_id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $employees = Employee::whereIn('emp_id', $emp_id)->get();

        $last_journal_reference = CompanyMenuSerial::where('branch_id', session('branch')['branch_id'])
            ->where('app_menu_id', 16)->latest()->first();

        if (isset($last_journal_reference)) {
            $last_journal_reference_number = $last_journal_reference->serial_last_no;
            $array_number = explode('-', $last_journal_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number_journal = implode('-', $array_number);
            $last_journal_reference->update(['serial_last_no' => $string_number_journal]);
        } else {
            $string_number_journal = 'J-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'app_menu_id' => 16,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number_journal,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => Carbon::now(),
            'journal_hd_credit' => $amount_h,
            'journal_hd_debit' => $amount_h,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes
        ]);


        $journal_dt = [];
        $journal_dt_2 = [];


        foreach ($employees->groupBy('emp_default_branch_id') as $k => $employee_g) {

            $branch = Branch::where('branch_id', $k)->first();

            foreach ($employee_g as $employee) {

                /////////////////ACCOUNTS
                /////basic salary
                $account_id_49 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 49)->first()->system_code_acc_id;

                /////////// بدل سكن
                $account_id_50 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 50)->first()->system_code_acc_id;

                /////بدل نقل
                $account_id_51 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 51)->first()->system_code_acc_id;

                //////بدل طعام
                $account_id_52 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 52)->first()->system_code_acc_id;


                //////بدل طبيعه عمل
                $account_id_53 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 53)->first()->system_code_acc_id;

                //////علاوه دوريه
                $account_id_54 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 54)->first()->system_code_acc_id;

                ////////////بدلات اخري
                $account_id_55 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 55)->first()->system_code_acc_id;


                $employee_vars_ids = $employee->employeeVariableDtAdds->pluck('emp_variables_type')->toArray();

                $system_code_adds_ids = SystemCode::whereIn('system_code_id', $employee_vars_ids)->pluck('system_code_acc_id');
            //  $branchss = Branch::where('company_group_id', $company->company_group_id)->pluck('branch_id')->toArray();
            //   $system_code_adds_ids = Branch::whereIn('branch_id', $branchss)->pluck('store_acc_id');

                $journal_obj_49 = $this->createJournalDtObj($account_id_49, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->basicSalary, $cost_center_type_id,
                    $cost_center_id, $branch);

                $journal_obj_50 = $this->createJournalDtObj($account_id_50, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->housingSalary, $cost_center_type_id,
                    $cost_center_id, $branch);


                $journal_obj_51 = $this->createJournalDtObj($account_id_51, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->transportSalary, $cost_center_type_id,
                    $cost_center_id, $branch);

                $journal_obj_52 = $this->createJournalDtObj($account_id_52, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->foodSalary, $cost_center_type_id,
                    $cost_center_id, $branch);

                $journal_obj_53 = $this->createJournalDtObj($account_id_53, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->naturalSalary, $cost_center_type_id,
                    $cost_center_id, $branch);


                $journal_obj_54 = $this->createJournalDtObj($account_id_54, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->allowanceSalary, $cost_center_type_id,
                    $cost_center_id, $branch);

                $journal_obj_55 = $this->createJournalDtObj($account_id_55, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->otherSalary, $cost_center_type_id,
                    $cost_center_id, $branch);


                array_push($journal_dt, $journal_obj_49);
                array_push($journal_dt, $journal_obj_50);
                array_push($journal_dt, $journal_obj_51);
                array_push($journal_dt, $journal_obj_52);
                array_push($journal_dt, $journal_obj_53);
                array_push($journal_dt, $journal_obj_54);
                array_push($journal_dt, $journal_obj_55);


                foreach ($system_code_adds_ids as $system_code_adds_id) {
                    $journal_obj_add = $this->createJournalDtObj($system_code_adds_id, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                        $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->otherSalary, $cost_center_type_id,
                        $cost_center_id, $branch);

                    array_push($journal_dt, $journal_obj_add);
                }


                //////////////الخصومات


                ///////////////تامينات اجتماعي
                $account_id_56 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 56)->first()->system_code_acc_id;


                ////////////القروض
                $account_id_57 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 57)->first()->system_code_acc_id;


                ////////////السلف
                $account_id_58 = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 58)->first()->system_code_acc_id;

                $employee_vars_ids_2 = $employee->employeeVariableDtSubs->pluck('emp_variables_type')->toArray();

                $system_code_adds_ids_2 = SystemCode::whereIn('system_code_id', $employee_vars_ids_2)->pluck('system_code_acc_id');


                $journal_obj_56 = $this->createJournalDtObj2($account_id_56, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->insuranceSalary, $cost_center_type_id,
                    $cost_center_id, $employee);


                $journal_obj_57 = $this->createJournalDtObj2($account_id_57, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->loansSalary, $cost_center_type_id,
                    $cost_center_id, $employee);

                $journal_obj_58 = $this->createJournalDtObj2($account_id_58, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                    $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->deductsSalary, $cost_center_type_id,
                    $cost_center_id, $employee);

                array_push($journal_dt_2, $journal_obj_56);
                array_push($journal_dt_2, $journal_obj_57);
                array_push($journal_dt_2, $journal_obj_58);

                foreach ($system_code_adds_ids_2 as $system_code_adds_id_2) {
                    $journal_obj_sub = $this->createJournalDtObj($system_code_adds_id_2, $journal_type, $journal_hd->journal_hd_id, $account_period_id,
                        $journal_hd->journal_hd_date, $journal_hd->journal_status, $employee->employeeSalarySubs, $cost_center_type_id,
                        $cost_center_id, $employee);

                    array_push($journal_dt_2, $journal_obj_sub);
                }

            }
        }

        $collection_debit = new Collection($journal_dt);
        $collection_credit = new Collection($journal_dt_2);


        foreach ($collection_debit->groupBy('account_id') as $collection_d) {
            $journal_dt_debit = $collection_d->sum('journal_dt_debit');
            $amount_debit_total[] = $collection_d->sum('journal_dt_debit');
            if ($journal_dt_debit > 0) {
                $journal_dtt = JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => $account_period_id,
                    'journal_dt_date' => $collection_d[0]['journal_dt_date'],
                    'journal_status' => $collection_d[0]['journal_status'],
                    'account_id' => $collection_d[0]['account_id'],
                    'journal_dt_debit' => $journal_dt_debit,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $journal_dt_debit,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_branch_id' => $branch->branch_id,
                    'cost_center_type_id' => $collection_d[0]['cost_center_type_id'],
                    'cost_center_id' => $collection_d[0]['cost_center_id'],////from application menu
                    'cc_voucher_id' => null,
                    'journal_dt_notes' => 'قيد رواتب فرع' . ' ' . $branch->branch_name_ar
                ]);
            }
        }

        foreach ($collection_credit->groupBy('account_id') as $collection_c) {
            $journal_dt_credit = $collection_c->sum('journal_dt_credit');
            $amount_credit_total[] = $collection_c->sum('journal_dt_credit');
            if ($journal_dt_credit > 0) {
                $journal_dtt_c = JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => $account_period_id,
                    'journal_dt_date' => $collection_c[0]['journal_dt_date'],
                    'journal_status' => $collection_c[0]['journal_status'],
                    'account_id' => $collection_c[0]['account_id'],
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $journal_dt_credit,
                    'journal_dt_balance' => $journal_dt_credit,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_branch_id' => $branch->branch_id,
                    'cost_center_type_id' => $collection_c[0]['cost_center_type_id'],
                    'cost_center_id' => $collection_c[0]['cost_center_id'],////from application menu
                    'cc_voucher_id' => null,
                    'journal_dt_notes' => 'قيد موظف' . ' ' . $employee->emp_name_full_ar
                ]);
            }
        }

            //////////////////حساب رواتب مستحقه
        $diff_credit = array_sum($amount_debit_total) - array_sum($amount_credit_total);

        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => $account_period_id,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_hd->journal_status,
            'account_id' => $company->co_salary_account,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $diff_credit,
            'journal_dt_balance' => $diff_credit,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => $branch->branch_id,
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => 16,////from application menu
            'cc_voucher_id' => null,
            'journal_dt_notes' => 'قيد رواتب مستحقه'
        ]);

        return $journal_hd;

    }


    public function createJournalDtObj($account_id, $journal_type, $journal_hd_id, $account_period_id,
                                       $journal_hd_date, $journal_status_id, $amount_debit, $cost_center_type_id,
                                       $cost_center_id, $branch)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $journal_obj = [
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd_id,
            'period_id' => $account_period_id,
            'journal_dt_date' => $journal_hd_date,
            'journal_status' => $journal_status_id,
            'account_id' => $account_id,
            'journal_dt_debit' => $amount_debit,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_debit,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => $branch->branch_id,
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => null,
            'journal_dt_notes' => 'قيد رواتب فرع' . ' ' . $branch->branch_name_ar
        ];

        return $journal_obj;

    }

    public function createJournalDtObjCredit($account_id, $journal_type, $journal_hd_id, $account_period_id,
                                        $journal_hd_date, $journal_status_id, $amount_debit, $cost_center_type_id,
                                        $cost_center_id, $branch)
                                        {
                                        $company = session('company') ? session('company') : auth()->user()->company;
                                        $journal_obj = [
                                        'company_group_id' => $company->company_group_id,
                                        'company_id' => $company->company_id,
                                        'branch_id' => session('branch')['branch_id'],
                                        'journal_type_id' => $journal_type->system_code_id,
                                        'journal_hd_id' => $journal_hd_id,
                                        'period_id' => $account_period_id,
                                        'journal_dt_date' => $journal_hd_date,
                                        'journal_status' => $journal_status_id,
                                        'account_id' => $account_id,
                                        'journal_dt_debit' => 0,
                                        'journal_dt_credit' => $amount_debit,
                                        'journal_dt_balance' => $amount_debit,
                                        'journal_user_entry_id' => auth()->user()->user_id,
                                        'cc_branch_id' => $branch->branch_id,
                                        'cost_center_type_id' => $cost_center_type_id,
                                        'cost_center_id' => $cost_center_id,////from application menu
                                        'cc_voucher_id' => null,
                                        'journal_dt_notes' => 'خصومات فرع' . ' ' . $branch->branch_name_ar
                                        ];

                                        return $journal_obj;

                                        }

        public function createJournalDtObj2($account_id, $journal_type, $journal_hd_id, $account_period_id,
                                            $journal_hd_date, $journal_status_id, $amount_credit, $cost_center_type_id,
                                            $cost_center_id, $employee)
        {
            $company = session('company') ? session('company') : auth()->user()->company;
            $journal_obj = [
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd_id,
            'period_id' => $account_period_id,
            'journal_dt_date' => $journal_hd_date,
            'journal_status' => $journal_status_id,
            'account_id' => $account_id,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_credit,
            'journal_dt_balance' => $amount_credit,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_employee_id' => $employee->emp_id,
            
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => null,
            'journal_dt_notes' => 'قيد موظف' . ' ' . $employee->emp_name_full_ar
        ];

        return $journal_obj;

    }
}
