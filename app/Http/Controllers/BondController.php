<?php

namespace App\Http\Controllers;

use App\Exports\BondsExport;
use App\Http\Controllers\General\JournalsController;
use App\Models\AccounPeriod;
use App\Models\ApplicationsMenu;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\CarRentAccident;
use App\Models\CarRentContract;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\InvoiceHd;
use App\Models\JournalDt;
use App\Models\JournalHd;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\SystemCodeCode;
use App\Models\WaybillHd;
use Maatwebsite\Excel\Facades\Excel;

class BondController extends Controller
{
    //
    public function index()
    {
        $company_auth = session('company') ? session('company') : auth()->user()->company;

        $query = Bond::where('bond_type_id', 1)->where('company_group_id', $company_auth->company_group_id);
        $companies = Company::where('company_group_id', $company_auth->company_group_id)->get();

        $payment_methods = SystemCodeCode::where('sys_category_id', 57)
            ->where('company_group_id', $company_auth->company_group_id)->get();
        $data = request()->all();

        $flag = 0;
        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 53 && $job_permission->permission_update) {
                    $flag += 1;
                }
            }
        } else {
            $flag += 1;
        }

        if (request()->query->count() > 0) {

            if (request()->company_id) {
                $query = $query->whereIn('company_id', request()->company_id);
            }

            if (request()->branch_ids) {
                $query = $query->whereIn('branch_id', request()->branch_ids);
            }

            if (request()->bond_method_type) {
                if ($flag == 1) {
                    $query = $query->whereIn('bond_method_type', request()->bond_method_type)
                        ->where('company_group_id', $company_auth->company_group_id);
                } else {
                    $query = $query->whereIn('bond_method_type', request()->bond_method_type)
                        ->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                }
            }

            if (request()->transaction_type) {
                if ($flag == 1) {
                    $query = $query->whereIn('transaction_type', request()->transaction_type)
                        ->where('company_group_id', $company_auth->company_group_id);
                } else {
                    $query = $query->whereIn('transaction_type', request()->transaction_type)
                        ->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                }


            }

            if (request()->bond_acc_id) {
                if ($flag == 1) {
                    $query = $query->where('bond_acc_id', request()->bond_acc_id)
                        ->where('company_group_id', $company_auth->company_group_id);
                } else {
                    $query = $query->where('bond_acc_id', request()->bond_acc_id)
                        ->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                }

            }

            if (request()->bond_code) {
                if ($flag == 1) {
                    $query = $query->where('bond_code', 'like', '%' . request()->bond_code . '%')
                        ->where('company_group_id', $company_auth->company_group_id);
                } else {
                    $query = $query->where('bond_code', 'like', '%' . request()->bond_code . '%')
                        ->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                }

            }

            if (request()->bond_check_no) {
                if ($flag == 1) {
                    $query = $query->where('bond_check_no', request()->bond_check_no)
                        ->where('company_group_id', $company_auth->company_group_id);
                } else {
                    $query = $query->where('bond_check_no', request()->bond_check_no)
                        ->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                }

            }

            if (request()->created_date_from && request()->created_date_to) {
                if ($flag == 1) {
                    $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                        ->whereDate('created_date', '<=', request()->created_date_to)
                        ->where('company_group_id', $company_auth->company_group_id);
                } else {
                    $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                        ->whereDate('created_date', '<=', request()->created_date_to)
                        ->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                }

            }

        } else {
            $query = $query->where('company_id', $company_auth->company_id)
                ->where('branch_id', session('branch')['branch_id']);
        }

        $total = $query->sum('bond_amount_credit');
        $bonds = $query->latest()->paginate();

        return view('Bonds.Capture.index', compact('bonds', 'companies', 'data',
            'payment_methods', 'flag', 'total', 'company_auth'));

    }

    public function create()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $applications = $company->appsActive;
//انواع الحساب
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
//       انواع الايرادات
        $system_code_types = SystemCode::where('sys_category_id', 58)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)
            ->get();

        $current_date = Carbon::now()->format('Y-m-d\TH:i');

        if (request()->invoice_id) {
            $invoice = InvoiceHd::where('invoice_id', request()->invoice_id)->first();
            return view('Bonds.Capture.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'invoice'));
        }

        if (request()->waybill_id) {
            $way_bill = WaybillHd::where('waybill_id', request()->waybill_id)->first();
            return view('Bonds.Capture.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'way_bill'));
        }


        if (request()->contract_id) {
            $contract = CarRentContract::find(request()->contract_id);
            return view('Bonds.Capture.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'contract'));
        }

        if (request()->car_accident_id) {
            $car_accident = CarRentAccident::find(request()->car_accident_id);
            return view('Bonds.Capture.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'car_accident'));
        }

        return view('Bonds.Capture.create', compact('applications', 'company', 'branch',
            'account_types', 'system_code_types', 'payment_methods', 'banks', 'current_date'));
    }

    public function store(Request $request)
    {
        //return $request->customer_id;
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 53)->latest()->first();
        $current_date = Carbon::now()->format('Y-m-d\TH:i');
        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'R-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 53,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $payment_method = SystemCodeCode::where('system_code', $request->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();


        if ($request->transaction_type) {
            $application_menu = ApplicationsMenu::where('app_menu_id', $request->transaction_type)->first();

            if ($application_menu->app_menu_id == 73) {
                $invoice = InvoiceHd::where('company_id', $company->company_id)->where('invoice_type', '<>', 11)
                    ->where('invoice_no', $request->bond_ref_no)->latest()->first();
                $customer_type = 'customer';
                if (isset($invoice)) {
                    $transaction_id = $invoice->invoice_id;
                    $invoice_total_payment = $invoice->invoice_total_payment + $request->bond_amount_debit;
                    $invoice->update(['invoice_total_payment' => $invoice_total_payment]);
                    $account_id = $invoice->customer->customer_account_id;
                    if ($invoice->invoice_total_payment == $invoice->invoice_amount) {
                        $invoice->update(['invoice_status' => 121004]);
                    }
                } else {
                    return back()->with(['error' => 'لا يوجد فاتوره بهذا الرقم']);
                }

            }

            if ($application_menu->app_menu_id == 70) {
                $waybill = WaybillHd::where('company_id', $company->company_id)
                    ->where('waybill_code', request()->bond_ref_no)->first();

                $customer_type = 'customer';

                if (isset($waybill)) {
                    $transaction_id = $waybill->waybill_id;
                    $waybill_paid_amount = $waybill->waybill_paid_amount + $request->bond_amount_debit;
                    $waybill->update(['waybill_paid_amount' => $waybill_paid_amount]);
                    $account_id = $waybill->customer->customer_account_id;
                } else {
                    return back()->with(['error' => 'لا يوجد بوليصه بهذا الرقم']);
                }
            }

            if ($application_menu->app_menu_id == 44) {
                $contract = CarRentContract::where('company_id', $company->company_id)
                    ->where('contract_code', $request->bond_ref_no)->latest()->first();

                $customer_type = 'customer';
                $account_id = $contract->customer->customer_account_id;

                if (isset($contract)) {
                    $transaction_id = $contract->contract_id;

                } else {
                    return back()->with(['error' => 'لا يوجد عقد بهذا الرقم']);
                }

            }

            //return $request->bond_ref_no;

            if ($application_menu->app_menu_id == 47) {
                $car_accident = CarRentAccident::
                where('car_accident_code', $request->bond_ref_no)->latest()->first();

                $customer_type = 'customer';
                $account_id = $car_accident->contract->customer->customer_account_id;

                if (isset($car_accident)) {
                    $transaction_id = $car_accident->car_accident_id;

                } else {
                    return back()->with(['error' => 'لا يوجد حادث بهذا الرقم']);
                }

            }
        }


        if (!isset($account_id) && !$request->bond_acc_id) {
            return back()->with(['error' => 'لا يوجد رقم حساب صحيح']);
        }

        $customer_type_code = SystemCode::where('system_code_id', $request->account_type)->first();
        if ($customer_type_code->system_code == 56001) {
            $customer_type = 'supplier';
            $customer_id = $request->customer_id;
        } elseif ($customer_type_code->system_code == 56002) {
            $customer_type = 'customer';
            $customer_id = $request->customer_id;
        } elseif ($customer_type_code->system_code == 56003) {
            $customer_type = 'employee';
            $customer_id = $request->emp_id;
        } elseif ($customer_type_code->system_code == 56004) {
            $customer_type = 'car';
            $customer_id = $request->car_id;
        } elseif ($customer_type_code->system_code == 56005) {
            $customer_type = 'branch';
            $customer_id = $request->branch_id;
        }

        $bond = Bond::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 1, ///سند قبض
            'bond_type_name' => 'Receipt',
            'bond_method_type' => $payment_method->system_code,
            'transaction_type' => $request->transaction_type ? $request->transaction_type : 0,
            'transaction_id' => isset($transaction_id) ? $transaction_id : null,
            'customer_id' => $customer_id,
            'customer_type' => $customer_type,
            'bond_date' => ($request->bond_date),
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : null,
            'bond_ref_no' => $request->bond_ref_no ? $request->bond_ref_no : null,
            'bond_doc_type' => $request->bond_doc_type,
            'bond_check_no' => $request->process_number ? $request->process_number : null,
            'bond_amount_debit' => $request->bond_amount_debit ? $request->bond_amount_debit : null,
            'bond_amount_balance' => $request->bond_amount_debit ? $request->bond_amount_debit : null,
            'bond_acc_id' => isset($account_id) ? $account_id : $request->bond_acc_id,
            'bond_notes' => $request->bond_notes,
            'created_user' => auth()->user()->user_id
        ]);

        if (isset($waybill)) {
            $waybill->update([
                'bond_id' => $bond->bond_id,
                'bond_code' => $bond->bond_code,
                'bond_date' => $bond->bond_date,
            ]);
            if ($waybill->invoice) {
                $waybill->invoice->update([
                    'bond_code' => $bond->bond_code,
                    'bond_date' => $bond->bond_date,
                ]);
            }
        }

        if (isset($invoice)) {
            $invoice->update([
                'bond_code' => $bond->bond_code,
                'bond_date' => $bond->bond_date,
            ]);

            if (!$invoice->invoice_type) {////فاتوره البيع للعميل
                if ($invoice->invoice_id) {
                    $invoice->invoice_id->update([
                        'bond_code' => $bond->bond_code,
                        'bond_id' => $bond->bond_id,
                        'bond_date' => $bond->bond_date,
                        'waybill_paid_amount' => $invoice->invoice_id->invoice_total_payment
                            + $request->bond_amount_debit
                    ]);
                }
            }
        }

        if (isset($contract)) {
            $extra_days = floor($request->bond_amount_debit / $contract->rentDayCost);
            $contract->days_count = $contract->days_count + $extra_days;
            $contract_date = Carbon::createFromFormat('Y-m-d', $contract->contractEndDateDate);
            $contract->contractEndDate = $contract_date->addDay($extra_days);
            $contract->paid = $contract->paid + $request->bond_amount_debit;

            $contract->save();
            $journal_category_id = 8;
        }

        if (isset($car_accident)) {
            $car_accident->car_accident_payment = $car_accident->car_accident_payment + $request->bond_amount_debit;
            $car_accident->car_accident_due = $car_accident->car_accident_due - $request->bond_amount_debit;
            $car_accident->save();

            $car_accident->contract->total_due = $car_accident->contract->total_due - $request->bond_amount_debit;
            $car_accident->contract->contract_total_payment = $car_accident->contract->contract_total_payment + $request->bond_amount_debit;
            $car_accident->contract->save();
        }


        //////////القيود اليوميه
        $journal_type = SystemCode::where('system_code', 803)->where('company_group_id', $company->company_group_id)->first();
        $last_journal_reference = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 33)->where('journal_type', $journal_type->system_code_id)->latest()->first();
        if (isset($last_journal_reference)) {
            $last_journal_reference_number = $last_journal_reference->serial_last_no;
            $array_number = explode('-', $last_journal_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number_journal = implode('-', $array_number);
            $last_journal_reference->update(['serial_last_no' => $string_number_journal]);
        } else {
            $string_number_journal = 'Journal-' . $journal_type->system_code_id . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 33,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number_journal,
                'created_user' => auth()->user()->user_id,
                'journal_type' => $journal_type->system_code_id
            ]);
        }

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();
////سند قبض

        $journal_status = SystemCode::where('system_code', 903)->first(); ////قيد مرحل
        $account_type = SystemCode::where('system_code_id', $request->account_type)->first()->system_code;
        $journal_category_id = 1;

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'journal_hd_date' => $bond->bond_date,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_credit' => $request->bond_amount_debit,
            'journal_hd_debit' => $request->bond_amount_debit,
            'journal_hd_notes' => 'قيد سند قبض لـ' . $request->bond_ref_no . ' ' . $request->bond_notes,
            'journal_category_id' => $journal_category_id ? $journal_category_id : 1 ////قيد سند قبض خارجي
        ]);

        /////نقدي وفيزا
        if ($request->bond_method_type == 57001 || $request->bond_method_type == 57002 ||
            $request->bond_method_type == 57003 || $request->bond_method_type == 57004) {
            $account_id_1 = $payment_method->system_code_acc_id;
        }

        ///بنك
        if ($request->bond_method_type == 57005) {
            $bank = SystemCode::where('system_code_id', $request->bond_bank_id)->first();
            $account_id_1 = $bank->system_code_acc_id;
        }

        $cost_center_type_id = SystemCode::where('system_code', 56005)->first()->system_code_id;
        ///الفرع
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_1,
            'journal_dt_debit' => $request->bond_amount_debit,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $request->bond_amount_debit,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => 53,////from application menu
            'cc_voucher_id' => $bond->bond_id,
            'journal_dt_notes' => isset($contract) ? 'قيد سند قبض علي العقد رقم' . $contract->contract_code : ' قيد سند قبض  لـ' . $request->bond_ref_no . $request->bond_notes,
        ]);


        /////مورد او عميل
        if ($account_type == '56002' || $account_type == '56001') {
            $account_id_2 = Customer::where('customer_id', $request->customer_id)->first()->customer_account_id;
        } else {
            $account_id_2 = SystemCode::where('system_code_id', $request->bond_doc_type)->first()->system_code_acc_id;
        }

        ////عميل
        if ($account_type == 56002) {
            $cc_customer_id = $request->customer_id;
        }
        ///مورد
        if ($account_type == 56001) {
            $cc_supplier_id = $request->customer_id;
        }
//// موظف
        if ($account_type == 56003) {
            $cc_employee_id = $request->emp_id;
        }
/////فرع
        if ($account_type == 56005) {
            $cc_branch_id = $request->branch_id;
        }
/////سياره
        if ($account_type == 56004) {
            $cc_car_id = $request->car_id;
        }

        //عميل او مورد او سياره او فرع
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_2,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $request->bond_amount_debit,
            'journal_dt_balance' => $request->bond_amount_debit,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
            'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
            'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
            'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
            'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
            'cost_center_type_id' => $request->account_type ? $request->account_type : null,
            'cost_center_id' => 53,
            'cc_voucher_id' => $bond->bond_id,
            'journal_dt_notes' => isset($contract) ? 'قيد سند قبض علي العقد رقم' . $contract->contract_code : 'قيد سند قبض لـ' . $request->bond_ref_no . $request->bond_notes,
        ]);

        $bond->journal_hd_id = $journal_hd->journal_hd_id;
        $bond->save();

        if (isset($contract)) {
            return redirect()->route('car-rent.edit', $contract->contract_id);
        }
        if (isset($car_accident)) {
            return redirect()->route('car-accident.edit', $car_accident->car_accident_id);
        } else {
            return redirect()->route('Bonds-capture');
        }


    }

    public function show($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 53)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 53)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            return response()->json(['data' => $bond]);
        }
        return view('Bonds.Capture.show', compact('bond', 'notes', 'attachments',
            'attachment_types', 'id', 'banks'));
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 53)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 53)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();
//انواع الحساب
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();

        //       انواع الايرادات
        $system_code_types = SystemCode::where('sys_category_id', 58)
            ->where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            if ($bond->customer_type == 'customer') {
                $account_type = 56002;
                $account = $bond->customer->account;
            } elseif ($bond->customer_type == 'supplier') {
                $account_type = 56001;
                $account = $bond->customer->account;
            } elseif ($bond->customer_type == 'employee') {
                $account_type = 56003;
                $account = $bond->account;
            } elseif ($bond->customer_type == 'branch') {
                $account_type = 56005;
                $account = $bond->account;
            } elseif ($bond->customer_type == 'car') {
                $account_type = 56004;
                $account = $bond->account;
            }

            return response()->json(['data' => $bond, 'account_type' => $account_type,
                'account' => $account]);
        }

        return view('Bonds.Capture.edit', compact('bond', 'notes', 'attachments', 'payment_methods',
            'attachment_types', 'id', 'banks', 'account_types', 'system_code_types'));
    }

    public function update($id, Request $request)
    {

        $bond = Bond::find($id);

//        $invoice = InvoiceHd::where('bond_code', (string)$bond->bond_id)->first();

//        if ($invoice) {
//            $invoice->update([
//                'invoice_total_payment' => $request->bond_amount_debit
//            ]);
//
//            if ($invoice->waybill) {
//                $invoice->waybill->update([
//                    'waybill_paid_amount' => $invoice->waybill->waybill_paid_amount - $bond->bond_amount_debit
//                        + $request->bond_amount_debit
//                ]);
//            }
//        }

        $bond->update([
            'bond_method_type' => $request->bond_method_type ? $request->bond_method_type : $bond->bond_method_type,
            'bond_check_no' => $request->bond_check_no ? $request->bond_check_no : $bond->bond_check_no,
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : $bond->bond_bank_id,
            'bond_notes' => $request->bond_notes,
            'updated_user' => auth()->user()->user_id,
//            'bond_amount_debit' => $request->bond_amount_debit,
//            'bond_amount_balance' => $request->bond_amount_debit,
            //   'customer_id' => $request->customer_id,
            //   'bond_doc_type' => $request->bond_doc_type,
            //  'bond_acc_id' => $request->bond_acc_id,
        ]);

        $account_type = $request->account_type;
        /////مورد او عميل
        if ($account_type == '56002' || $account_type == '56001') {
            $account_id_2 = Customer::where('customer_id', $request->customer_id)->first()->customer_account_id;
        } else {
            $account_id_2 = SystemCode::where('system_code_id', $request->bond_doc_type)->first()->system_code_acc_id;
        }

        // return $account_id_2;

        ////عميل
        if ($account_type == 56002) {
            $cc_customer_id = $request->customer_id;
            $customer_type = 'customer';
        }
        ///مورد
        if ($account_type == 56001) {
            $cc_supplier_id = $request->customer_id;
            $customer_type = 'supplier';
        }
//// موظف
        if ($account_type == 56003) {
            $cc_employee_id = $request->customer_id;
            $customer_type = 'employee';
        }
/////فرع
        if ($account_type == 56005) {
            $cc_branch_id = $request->customer_id;
            $customer_type = 'branch';
        }
/////سياره
        if ($account_type == 56004) {
            $cc_car_id = $request->customer_id;
            $customer_type = 'car';
        }

        if (isset($customer_type)) {
            $bond->customer_type = $customer_type;
            $bond->save();
        }

        $journal_controller = new JournalsController();
        $amount_debit = $bond->bond_amount_debit;
        $cc_voucher_id = $bond->bond_id;
        $cost_center_id = 53;
        $journal_controller->updateCaptureJournal($amount_debit, $cc_voucher_id, $cost_center_id);

        $bond->journalCapture->journalDetails[1]->update([
            'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
            'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
            'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
            'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
            'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
            'account_id' => $account_id_2,
        ]);

        return back();
    }

    public function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bonds = Bond::where('company_id', $company->company_id)->where('bond_type_id', 1)
            ->latest()->get();
        if (request()->company_id) {

            $query = Bond::where('bond_type_id', 1)->whereIn('company_id', request()->company_id);
            $bonds = $query->paginate();

            if (request()->branch_ids) {
                $query = $query->whereIn('branch_id', request()->branch_ids);
                $bonds = $query->paginate();
            }

            if (request()->bond_method_type) {
                $query = $query->whereIn('bond_method_type', request()->bond_method_type);
                $bonds = $query->paginate();
            }

            if (request()->transaction_type) {
                $query = $query->whereIn('transaction_type', request()->transaction_type);
                $bonds = $query->paginate();

            }
            if (request()->bond_acc_id) {
                $query = $query->where('bond_acc_id', request()->bond_acc_id);
                $bonds = $query->paginate();
            }

            if (request()->bond_code) {
                $query = $query->where('bond_code', request()->bond_code);
                $bonds = $query->paginate();
            }

            if (request()->bond_check_no) {
                $query = $query->where('bond_check_no', request()->bond_check_no);
                $bonds = $query->paginate();
            }

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                    ->whereDate('created_date', '<=', request()->created_date_from);
                $bonds = $query->paginate();
            }


        }

        return Excel::download(new BondsExport($bonds), 'bonds.xlsx');

    }

    public function exportPdf($id)
    {
        $bond = Bond::find($id);
        return view('Bonds.Capture.bond-pdf', compact('bond'));
    }

}

