<?php

namespace App\Http\Controllers;

use App\Exports\BondCashExport;
use App\Exports\BondsExport;
use App\Http\Controllers\General\JournalsController;
use App\Models\Account;
use App\Models\ApplicationsMenu;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\InvoiceHd;
use App\Models\MaintenanceCardDetails;
use App\Models\Note;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\SystemCode;
use App\Models\TripHd;
use App\Models\Trucks;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BondCashController extends Controller
{
    public function index()
    {
        $company_auth = session('company') ? session('company') : auth()->user()->company;

        $query = Bond::where('bond_type_id', 2)->where('company_group_id', $company_auth->company_group_id);

        $companies = Company::where('company_group_id', $company_auth->company_group_id)->get();

        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company_auth->company_group_id)->get();

        $data = request()->all();

        $flag = 0;
        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 54 && $job_permission->permission_approve) {
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

            if (request()->journal_s) {
                if ($flag == 1) {
                    if (request()->journal_s == 1) {
                        $query = $query->where('company_group_id', $company_auth->company_group_id)->whereNotNull('journal_hd_id');
                    } elseif (request()->journal_s == 2) {
                        $query = $query->where('company_group_id', $company_auth->company_group_id)->whereNull('journal_hd_id');
                    }
                } else {
                    if (request()->journal_s == 1) {
                        $query = $query->whereNotNull('journal_hd_id')->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                    } elseif (request()->journal_s == 2) {
                        $query = $query->whereNull('journal_hd_id')->where('company_id', $company_auth->company_id)->where('branch_id', session('branch')['branch_id']);
                    }
                }


            }
        } else {
            $query = $query->where('company_id', $company_auth->company_id)
                ->where('branch_id', session('branch')['branch_id']);
        }

        $total = $query->sum('bond_amount_credit');
        $bonds = $query->latest()->paginate();
        return view('Bonds.Cash.index', compact('bonds', 'companies', 'data',
            'payment_methods', 'flag', 'company_auth', 'total'));
    }


    public
    function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $applications = $company->appsActive;
        $current_date = Carbon::now()->format('Y-m-d\TH:i');
        $truck_code = Trucks::where('company_group_id', $company->company_group_id)->get();
//انواع الحساب
        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
//       انواع المصروفات
        $system_code_types = SystemCode::where('sys_category_id', 59)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)
            ->where('system_code_filter', 'waybill')->get();

        if (request()->trip_id) {
            $trip = TripHd::find(request()->trip_id);
            return view('Bonds.Cash.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'trip'));
        }
        if (request()->invoice_id) {
            $invoice = InvoiceHd::find(request()->invoice_id);
            return view('Bonds.Cash.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'invoice'));
        }

        if (request()->store_sales_inv_id) {
            $sales_inv = Purchase::where('store_hd_id', request()->store_sales_inv_id)->first();
            return view('Bonds.Cash.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'sales_inv'));
        }

        if (request()->store_transfer_id) {
            $store_transfer = Purchase::where('store_hd_id', request()->store_transfer_id)->first();
            return view('Bonds.Cash.create', compact('applications', 'company', 'branch',
                'account_types', 'system_code_types', 'payment_methods', 'banks', 'store_transfer'));
        }

        return view('Bonds.Cash.create', compact('applications', 'company', 'branch', 'truck_code',
            'current_date', 'account_types', 'system_code_types', 'payment_methods', 'banks'));
    }

    public
    function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_bonds_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 54)->latest()->first();

        \DB::beginTransaction();

        if (isset($last_bonds_serial)) {
            $last_bonds_serial_no = $last_bonds_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_bonds_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'P-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 54,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $payment_method = SystemCode::where('system_code', $request->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();

//        $customer_type = $request->customer_type;
        $bond_account_id = $request->bond_acc_id;


        if (!$request->bond_acc_id) {
            return back()->with(['error' => 'لا يوجد رقم حساب صحيح']);
        }
        if ($request->customer_id) {
            $customer_id = $request->customer_id;
        } elseif ($request->emp_id) {
            $customer_id = $request->emp_id;
        } elseif ($request->branch_id) {
            $customer_id = $request->branch_id;
        }


        if ($request->transaction_type) {
            $application_menu = ApplicationsMenu::where('app_menu_id', $request->transaction_type)->first();
            //return $application_menu;
            if ($application_menu->app_menu_id == 73) {
                $invoice = InvoiceHd::where('company_id', $company->company_id)
                    ->where('invoice_no', $request->bond_ref_no)->first();
                if (isset($invoice)) {
                    $transaction_id = $invoice->invoice_id;
                    $invoice_total_payment = $invoice->invoice_total_payment - $request->bond_amount_credit;
                    $invoice->update(['invoice_total_payment' => $invoice_total_payment]);
                } else {
                    return back()->with(['error' => 'لا يوجد فاتوره بهذا الرقم']);
                }

            }

            if ($application_menu->app_menu_id == 70) {
                $waybill = WaybillHd::where('company_id', $company->company_id)
                    ->where('waybill_code', request()->bond_ref_no)->first();
                if (isset($waybill)) {
                    $transaction_id = $waybill->waybill_id;
                    $waybill_paid_amount = $waybill->waybill_paid_amount - $request->bond_amount_credit;
                    $waybill->update(['waybill_paid_amount' => $waybill_paid_amount]);
                } else {
                    return back()->with(['error' => 'لا يوجد بوليصه بهذا الرقم']);
                }
            }

            if ($application_menu->app_menu_id == 71 && $request->bond_ref_no) {
                $maintenance_card_dts = MaintenanceCardDetails::where('mntns_cards_dt_id', $request->bond_ref_no)->first();
                $bond_ref_no = $maintenance_card_dts->card->mntns_cards_no;
            }

            if ($application_menu->app_menu_id == 104) { ///بيان الترحيل
                $trip_hd = TripHd::where('company_id', $company->company_id)
                    ->where('trip_hd_code', $request->bond_ref_no)->first();
                $transaction_id = $trip_hd->trip_hd_id;

                $customer_type = 'car';
            }

            if ($application_menu->app_menu_id == 65) { ///اذن الصرف
                $sales_inv = Purchase::where('company_group_id', $company->company_group_id)
                    ->where('store_hd_code', $request->bond_ref_no)->first();
                $transaction_id = $sales_inv->store_hd_id;

            }

            if ($application_menu->app_menu_id == 67) { ///اذن التحويل
                $store_transfer = Purchase::where('company_group_id', $company->company_group_id)
                    ->where('store_hd_code', $request->bond_ref_no)->first();
                $transaction_id = $store_transfer->store_hd_id;

            }

        }


        $account_type = SystemCode::where('system_code_id', $request->account_type)->first();

        if ($account_type->system_code == 56001) {
            $customer_type = 'supplier';
        } elseif ($account_type->system_code == 56002) {
            $customer_type = 'customer';
        } elseif ($account_type->system_code == 56003) {
            $customer_type = 'employee';
        } elseif ($account_type->system_code == 56004) {
            $customer_type = 'car';
        } elseif ($account_type->system_code == 56005) {
            $customer_type = 'branch';
        }

        $bond = Bond::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'bond_code' => $string_number,
            'bond_type_id' => 2, ///سند صرف
            'bond_type_name' => 'Payment',

            'bond_method_type' => $payment_method->system_code,
            'transaction_type' => $request->transaction_type ? $request->transaction_type : 0,
            'transaction_id' => isset($transaction_id) ? $transaction_id : null,
            'customer_id' => isset($customer_id) ? $customer_id : '',
            'bond_car_id' => $request->bond_car_id ? $request->bond_car_id : '',
            'customer_type' => $customer_type,
            'bond_date' => Carbon::now(),
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : null,
            'bond_ref_no' => isset($bond_ref_no) ? $bond_ref_no : $request->bond_ref_no,
            'bond_doc_type' => $request->bond_doc_type,
            'bond_check_no' => $request->process_number ? $request->process_number : null,

            'bond_vat_amount' => $request->bond_vat_amount,
            'bond_vat_rate' => $request->bond_vat_rate,
            'bond_amount_credit' => $request->bond_amount_total,
            'bond_amount_balance' => $request->bond_amount_total ? (-1) * $request->bond_amount_total : null,

            'bond_acc_id' => $bond_account_id,
            'bond_notes' => $request->bond_notes,
            'created_user' => auth()->user()->user_id
        ]);

        if (isset($maintenance_card_dts)) {
            $maintenance_card_dts->bond_id = $bond->bond_id;
            $maintenance_card_dts->save();
        }

        if (isset($sales_inv)) {
            $sales_inv->bond_id = $bond->bond_id;
            $sales_inv->bond_code = $bond->bond_code;
            $sales_inv->bond_date = $bond->bond_date;
            $sales_inv->save();
        }

        if (isset($store_transfer)) {
            $store_transfer->bond_id = $bond->bond_id;
            $store_transfer->bond_code = $bond->bond_code;
            $store_transfer->bond_date = $bond->bond_date;
            $store_transfer->save();
        }

        if (isset($maintenance_card_dts)) {
            $maintenance_card_dts->bond_id = $bond->bond_id;
            $maintenance_card_dts->save();
        }

        if (isset($trip_hd)) {
            $trip_hd->bond_id = $bond->bond_id;
            $trip_hd->save();
        }


        \DB::commit();

        return redirect()->route('Bonds-cash');

    }

    public
    function show($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 54)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 54)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        return view('Bonds.Cash.show', compact('bond', 'attachments', 'notes', 'payment_methods',
            'attachment_types'));
    }

    public
    function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($id);
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        //       انواع المصروفات
        $system_code_types = SystemCode::where('sys_category_id', 59)
            ->where('company_group_id', $company->company_group_id)->get();

        //انواع الحساب
        $account_types = SystemCode::where('sys_category_id', 56)
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

        $flag = 0;
        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 54 && $job_permission->permission_approve) {
                    $flag += 1;
                }
            }
        } else {
            $flag += 1;
        }
        return view('Bonds.Cash.edit', compact('bond', 'payment_methods', 'banks', 'id',
            'system_code_types', 'flag', 'account_types'));
    }


    public
    function update($id, Request $request)
    {

        $bond = Bond::find($id);

        // $invoice = InvoiceHd::where('bond_code', (string)$bond->bond_id)->first();

//        if (isset($invoice)) {
//            $invoice_total_payment = $invoice->invoice_total_payment + $bond->bond_amount_credit - $request->bond_amount_credit;
//
//            $invoice->update(['invoice_total_payment' => $invoice_total_payment]);
//        }

        $bond->update([
            'bond_method_type' => $request->bond_method_type ? $request->bond_method_type : $bond->bond_method_type,
            'bond_check_no' => $request->bond_check_no ? $request->bond_check_no : $bond->bond_check_no,
            'bond_bank_id' => $request->bond_bank_id ? $request->bond_bank_id : $bond->bond_bank_id,
            'bond_notes' => $request->bond_notes,
            'updated_user' => auth()->user()->user_id,
//            'bond_amount_credit' => $request->bond_amount_credit,
//            'bond_amount_balance' => $request->bond_amount_credit * -1,
//            'bond_vat_rate' => $request->bond_vat_rate,
//            'bond_vat_amount' => $request->bond_vat_amount,
            'bond_doc_type' => $request->bond_doc_type,
            'customer_id' => $request->customer_id,
            'bond_acc_id' => $request->bond_acc_id,
        ]);

        $account_type = $request->account_type;

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

        $bond->customer_type = $customer_type;
        $bond->save();

        $bond->refresh();

        if ($bond->journalCash) {
            $journal_controller = new JournalsController();
            $amount_total = $bond->bond_amount_credit;
            $vat_amount = $bond->bond_vat_amount;
            $cc_voucher_id = $bond->bond_id;
            $bank_id = $bond->bond_bank_id ? $bond->bond_bank_id : '';
            $payment_method = $bond->paymentMethod;
            $doc_type = SystemCode::where('system_code_id', $bond->bond_doc_type)
                ->where('company_group_id', $bond->company_group_id)->first()->system_code;
            $journal_controller->updateCashJournal($doc_type, $amount_total, $vat_amount, $cc_voucher_id,
                $payment_method, $bank_id);

            $account_type_code = SystemCode::where('system_code', $account_type)
                ->where('company_group_id', $bond->company_group_id)->first();

            $bond->journalCapture->journalDetails[0]->update([
                'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
                'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
                'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
                'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
                'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
                'cost_center_type_id' => $account_type_code->system_code_id
            ]);
        }


        return back();
    }

    public
    function export()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $bonds = Bond::where('company_id', $company->company_id)->where('bond_type_id', 2)
            ->latest()->get();
        if (request()->company_id) {

            $query = Bond::where('bond_type_id', 2)->whereIn('company_id', request()->company_id);
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

        return Excel::download(new BondCashExport($bonds), 'bonds-cash.xlsx');


    }


    public
    function approveAllBond(Request $request)
    {

        foreach ($request->bond_id as $bond_id) {
            $this->approveBond($bond_id);
        }
        return back()->with('تم اضافه القيد');

    }

    public
    function approveOneBond(Request $request)
    {
        DB::beginTransaction();
        $bond = Bond::find($request->bond_id);
        if ($bond->customer_type != null) {
            $this->approveBond($request->bond_id);
        }
        DB::commit();
        return back()->with('تم اضافه القيد');

    }

    public
    function approveBond($bond_id)
    {

        $bond = Bond::find($bond_id);
        $company = session('company') ? session('company') : auth()->user()->company;

        $payment_method = SystemCode::where('system_code', $bond->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();


        if ($bond->customer_type == 'customer') {
            $account_type_system_code = 56002;
        } elseif ($bond->customer_type == 'supplier') {
            $account_type_system_code = 56001;
        } elseif ($bond->customer_type == 'employee') {
            $account_type_system_code = 56003;
        } elseif ($bond->customer_type == 'branch') {
            $account_type_system_code = 56005;
        } elseif ($bond->customer_type == 'car') {
            $account_type_system_code = 56004;
        }

        if (isset($account_type_system_code)) {
            $account_type = SystemCode::where('system_code', $account_type_system_code)
                ->where('company_group_id', $company->company_group_id)->first();
        }

        $doc_type = SystemCode::where('system_code_id', $bond->bond_doc_type)
            ->where('company_group_id', $company->company_group_id)->first()->system_code;

        if ($bond->transaction_type == 71) {
            $journal_category_id = 15; ///قيد سند صرف كارت صيانه
        } else {
            $journal_category_id = 9; ////قيد سند صرف سند خارجي
        }

        $amount_total = $bond->bond_amount_credit;
        $vat_amount = $bond->bond_vat_amount;
        $cc_voucher_id = $bond->bond_id;
        $bank_id = $bond->bond_bank_id ? $bond->bond_bank_id : '';

        $cost_center_id = 54;
        $journal_notes = '  قيد سند صرف  سند رقم' . ' ' . $bond->bond_code . ' ' . $bond->bond_notes;
        $customer_notes = '  قيد سند صرف  سند رقم' . ' ' . $bond->bond_code . ' ' . $bond->bond_notes;
        $cash_notes = '  قيد سند صرف  رقم' . ' ' . $bond->bond_code . ' ' . $bond->bond_notes;

        if ($bond->bond_car_id) {
            $customer_id = $bond->bond_car_id;
        } else {
            $customer_id = $bond->customer_id;
        }

        $j_add_date = $bond->bond_date;

        $journals_controller = new JournalsController();
        $journals_controller->AddCashApprovJournal($account_type->system_code, $customer_id, $doc_type, $amount_total
            , $vat_amount, $cc_voucher_id, $payment_method, $bank_id, $journal_category_id
            , $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);

        // return back()->with('تم اضافه القيد');
    }


}
