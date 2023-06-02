<?php

namespace App\Http\Controllers;

use App\Http\Controllers\General\JournalsController;
use App\Http\Resources\purchaseInvoiceResource;
use App\Http\Resources\purchaseJournalDtsResource;
use App\InvoiceQR\InvoiceDateElement;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\Models\AccounPeriod;
use App\Models\Account;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\JournalDt;
use App\Models\JournalHd;
use App\Models\SystemCode;
use App\Models\Trucks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $company_group = $company->company_group_id;
        $customers = Customer::where('customer_category', 1)->where('company_group_id', $company->company_group_id)
            ->get(); /////suppliers
        $invoices = InvoiceHd::where('invoice_type', 11)
            ->where('company_id', $company->company_id)->sortable()->latest()->paginate();
        $data = request()->all();
        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 11)
                ->whereIn('company_id', request()->company_id);
            $invoices = $query->sortable()->paginate();

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->sortable()->paginate();
            }

//            if (request()->due_date_from && request()->due_date_to) {
//                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
//                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
//                $invoices = $query->sortable()->paginate();
//
//            }

            if (request()->invoice_notes) {

                $query = $query->whereHas('invoiceDetails', function ($query) {
                    $query->where('invoice_item_notes', 'like', '%' . request()->invoice_notes . '%');
                })->orWhere('invoice_notes', 'like', '%' . request()->invoice_notes . '%');

                $invoices = $query->sortable()->paginate();
            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->sortable()->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
//        $report_url = Reports::where('company_id', $company->company_id)
//            ->where('report_status', 1)->where('report_code', '73001')->get();


        return view('Invoices.Purchase.index',
            compact('companies', 'customers', 'invoices', 'total_amount', 'company_group',
                'data'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $suppliers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 1)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            $accounts = Account::where('company_group_id', $company->company_group_id)->where('acc_level', $company->companyGroup->accounts_levels_number)
                ->whereIn('main_type_id', [1, 4])->get();
            return response()->json(['data' => $accounts]);
        }


        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company->company_group_id)->get();

        $account_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
        return view('Invoices.Purchase.create', compact('suppliers', 'companies', 'payment_methods',
            'sys_codes_unit', 'account_types'));
    }

    public function show($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();
        $accounts = Account::where('company_group_id', $company->company_group_id)->where('acc_level', $company->companyGroup->accounts_levels_number)
            ->where('acc_code', 'like', '4' . '%')->get();


        return view('Invoices.purchase.show', compact('invoice', 'companies', 'accounts'));
    }


    public function store(Request $request)
    {

        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 120)->latest()->first();


        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'INV-P-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 120,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }


        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'acc_period_id' => $request->acc_period_id,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => $request->invoice_due_date,
            //'invoice_amount_b' => $request->invoice_amount - $request->invoice_vat_amount,
            'invoice_amount' => $request->invoice_amount,
            'invoice_vat_rate' => 15,
            // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
            'invoice_vat_amount' => $request->invoice_vat_amount,
            'invoice_discount_total' => $request->invoice_discount,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->supplier_id,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_tax_no' => $request->customer_tax_no,
            'customer_phone' => $request->customer_phone,
            'po_number' => $request->po_number,
            'payment_tems' => $request->payment_tems,
            'gr_number' => $request->gr_number,
            'supply_date' => $request->supply_date,
            'invoice_is_payment' => 0,
            'invoice_type' => 11
        ]);

        $invoice_dts = [];
        foreach ($request->account_id as $k => $account_id) {

            $invoice_dt = InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $account_id,
//                'invoice_item_unit' => $request->invoice_item_unit[$k],
                'invoice_item_amount' => $request->invoice_item_amount[$k],
                'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                'invoice_item_price' => $request->invoice_item_price[$k],
                'invoice_item_vat_rate' => $request->invoice_item_vat_rate[$k],
                'invoice_item_vat_amount' => $request->invoice_item_vat_amount[$k],
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => $request->invoice_discount_total[$k],
                'invoice_total_amount' => $request->invoice_total_amount[$k],
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $request->invoice_item_notes[$k],
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => $request->invoice_due_date,
                'item_account_id' => $account_id
            ]);

            $invoice_dt->cost_center_type_id = $request->cost_center_type_id[$k];
            $invoice_dt->cc_customer_id = isset($request->cc_customer_id[$k]) ? $request->cc_customer_id[$k] : null;
            $invoice_dt->cc_supplier_id = isset($request->cc_supplier_id[$k]) ? $request->cc_supplier_id[$k] : null;
            $invoice_dt->cc_branch_id = isset($request->cc_branch_id[$k]) ? $request->cc_branch_id[$k] : null;
            $invoice_dt->cc_supplier_id = isset($request->cc_supplier_id[$k]) ? $request->cc_supplier_id[$k] : null;
            $invoice_dt->cc_truck_id = isset($request->cc_truck_id[$k]) ? $request->cc_truck_id[$k] : null;
            array_push($invoice_dts, $invoice_dt);
        }

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($company->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->timezone('Asia/Riyadh')->toDateTimeString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();

        $invoice_hd->update(['qr_data' => $qr]);


        $journal_controller = new JournalsController();

        $total_amount = $invoice_hd->invoice_amount;
        $vat_amount = $invoice_hd->invoice_vat_amount;
        $cost_center_id = 120;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $vat_notes = 'ضريبه فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $invoice_hd->customer_name . ' ' . $invoice_hd->gr_number;
        $supplier_notes = ' فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $invoice_hd->customer_name . ' ' . $invoice_hd->gr_number;
        $notes = ' فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $invoice_hd->customer_name . ' ' . $invoice_hd->invoice_notes;
        $journal_category_id = 57;

        $customer = Customer::where('customer_id', $invoice_hd->customer_id)->first();


        if (($customer->cus_type->system_code) == 539) {
//    قيد فاتوره مشتريات مورد علي الحساب
            $journal_controller->SupplierPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                $cc_voucher_id, $vat_notes, $supplier_notes,
                $journal_category_id, $notes,
                $invoice_dts);

        } else {
////    قيد فاتوره مشتريات مورد افراد
            $journal_controller->SupplierCashPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                $cc_voucher_id, $vat_notes, $supplier_notes,
                $journal_category_id, $notes,
                $invoice_dts);
        }
        DB::commit();
        return redirect()->route('invoices-purchase')->with(['success' => 'تمت الاضافه']);
    }


    public function edit($id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        if (request()->ajax()) {

            $invoice_hd = InvoiceHd::find(request()->id);


            $invoice_dts = InvoiceDt::where('invoice_id', $invoice_hd->invoice_id)->select('invoice_details_id', 'invoice_item_notes',
                'invoice_item_quantity', 'invoice_item_price', 'invoice_discount_total', 'invoice_item_amount',
                'invoice_item_vat_rate', 'invoice_item_vat_amount', 'invoice_total_amount', 'item_account_id')->get();

            $count = $invoice_dts->count();
            $journal_dts = JournalDt::where('journal_hd_id', $invoice_hd->journal_hd_id)->get()->take($count);

            $payment_methods = SystemCode::where('sys_category_id', 57)
                ->where('company_group_id', $company->company_group_id)
                ->select('system_code', 'system_code_name_ar', 'system_code_name_en')->get();

            $accounts = Account::where('company_group_id', $company->company_group_id)->where('acc_level', $company->companyGroup->accounts_levels_number)
                ->whereIn('main_type_id', [1, 4])->select('acc_name_ar', 'acc_name_en', 'acc_id')->get();

            $suppliers = Customer::where('company_group_id', $company->company_group_id)
                ->where('customer_category', 1)->select('customer_name_full_ar', 'customer_name_full_en', 'customer_id')->get();

            $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
                ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->select('acc_period_id', 'acc_period_name_ar', 'acc_period_name_en')->get();

            $account_types = SystemCode::where('sys_category_id', 56)
                ->where('company_group_id', $company->company_group_id)
                ->select('system_code', 'system_code_name_ar', 'system_code_name_en')->get();

            $branches = $company->branches;

            $customers = Customer::where('company_group_id', $company->company_group_id)
                ->where('customer_category', 2)->select('customer_id', 'customer_name_full_ar',
                    'customer_name_full_en')->get();

            $branches_ids = $company->branches->pluck('branch_id')->toArray();

            $employees = Employee::whereIn('emp_default_branch_id', $branches_ids)->get();

            $trucks = Trucks::where('company_id', $company->company_id)->select('truck_id', 'truck_code', 'truck_name')
                ->get();


            return response()->json(['data' => new purchaseInvoiceResource($invoice_hd), 'invoice_dts' => $invoice_dts,
                'journal_dts' => purchaseJournalDtsResource::collection($journal_dts),
                'payment_methods' => $payment_methods, 'accounts' => $accounts, 'suppliers' => $suppliers,
                'account_types' => $account_types, 'account_periods' => $accounts_periods,
                'branches' => $branches, 'customers' => $customers, 'trucks' => $trucks, 'employees' => $employees]);
        }

        return view('Invoices.purchase.edit', compact('id'));
    }


    public function update($id, Request $request)
    {
        DB::beginTransaction();
        $invoice_hd = InvoiceHd::find($id);
        if ($invoice_hd->journalHd) {
            $journalDetails = JournalDt::where('journal_hd_id', $invoice_hd->journalHd->journal_hd_id)->get();
            foreach ($journalDetails as $journalDetail) {
                $journalDetail->delete();
            }
            $invoice_hd->journalHd->delete();
        }

        $invoice_hd->update([
            'invoice_due_date' => $request->invoice_due_date,
            'invoice_amount' => $request->invoice_amount,
            'invoice_vat_amount' => $request->invoice_vat_amount,
            'invoice_discount_total' => $request->invoice_discount,
            'invoice_notes' => $request->invoice_notes,
            'customer_id' => $request->supplier_id,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_tax_no' => $request->customer_tax_no,
            'customer_phone' => $request->customer_phone,
            'po_number' => $request->po_number,
            'payment_tems' => $request->payment_tems,
            'gr_number' => $request->gr_number,
            'supply_date' => $request->supply_date,
        ]);

        $invoice_dts = [];


        $invoice_details_old_ids = InvoiceDt::where('invoice_id', $invoice_hd->invoice_id)->pluck('invoice_details_id')
            ->toArray();

        foreach ($invoice_details_old_ids as $invoice_details_old_id) {
            if (!in_array($invoice_details_old_id, $request->invoice_details_id)) {
                $invoice_dt_old = InvoiceDt::find($invoice_details_old_id);
                $invoice_dt_old->delete();
            }
        }


        foreach ($request->invoice_details_id as $k => $invoice_dt) {

            if ($invoice_dt != 0) {
                $invoice_dt = InvoiceDt::find($invoice_dt);
                $invoice_dt->update([
                    'invoice_item_id' => $request->account_id[$k],
                    'invoice_item_amount' => $request->invoice_item_amount[$k],
                    'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                    'invoice_item_price' => $request->invoice_item_price[$k],
                    'invoice_item_vat_rate' => $request->invoice_item_vat_rate[$k],
                    'invoice_item_vat_amount' => $request->invoice_item_vat_amount[$k],
                    'invoice_discount_total' => $request->invoice_discount_total[$k],
                    'invoice_total_amount' => $request->invoice_total_amount[$k],
                    'invoice_item_notes' => $request->invoice_item_notes[$k],
                    'invoice_to_date' => $request->invoice_due_date,
                    'item_account_id' => $request->account_id[$k]
                ]);
            } else {
                $invoice_dt = InvoiceDt::create([
                    'company_group_id' => $invoice_hd->company_group_id,
                    'company_id' => $invoice_hd->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'invoice_id' => $invoice_hd->invoice_id,
                    'invoice_item_id' => $request->account_id[$k],
                    'invoice_item_amount' => $request->invoice_item_amount[$k],
                    'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                    'invoice_item_price' => $request->invoice_item_price[$k],
                    'invoice_item_vat_rate' => $request->invoice_item_vat_rate[$k],
                    'invoice_item_vat_amount' => $request->invoice_item_vat_amount[$k],
                    'invoice_discount_type' => 1,
                    'invoice_discount_amount' => 0,
                    'invoice_discount_total' => $request->invoice_discount_total[$k],
                    'invoice_total_amount' => $request->invoice_total_amount[$k],
                    'created_user' => auth()->user()->user_id,
                    'invoice_item_notes' => $request->invoice_item_notes[$k],
                    'invoice_from_date' => Carbon::now(),
                    'invoice_to_date' => $request->invoice_due_date,
                    'item_account_id' => $request->account_id[$k]
                ]);
            }

            $invoice_dt->cost_center_type_id = $request->cost_center_type_id[$k];
            $invoice_dt->cc_customer_id = isset($request->cc_customer_id[$k]) ? $request->cc_customer_id[$k] : null;
            $invoice_dt->cc_supplier_id = isset($request->cc_supplier_id[$k]) ? $request->cc_supplier_id[$k] : null;
            $invoice_dt->cc_branch_id = isset($request->cc_branch_id[$k]) ? $request->cc_branch_id[$k] : null;
            $invoice_dt->cc_supplier_id = isset($request->cc_supplier_id[$k]) ? $request->cc_supplier_id[$k] : null;
            $invoice_dt->cc_employee_id = isset($request->cc_employee_id[$k]) ? $request->cc_employee_id[$k] : null;
            $invoice_dt->cc_truck_id = isset($request->cc_truck_id[$k]) ? $request->cc_truck_id[$k] : null;
            array_push($invoice_dts, $invoice_dt);
        }


        $journal_controller = new JournalsController();

        $total_amount = $invoice_hd->invoice_amount;
        $vat_amount = $invoice_hd->invoice_vat_amount;
        $cost_center_id = 120;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $vat_notes = 'ضريبه فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $invoice_hd->customer_name . ' ' . $invoice_hd->gr_number;
        $supplier_notes = ' فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $invoice_hd->customer_name . ' ' . $invoice_hd->gr_number;
        $notes = ' فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $invoice_hd->customer_name . ' ' . $invoice_hd->invoice_notes;
        $journal_category_id = 57;

        $customer = Customer::where('customer_id', $invoice_hd->customer_id)->first();


        if (($customer->cus_type->system_code) == 539) {
//    قيد فاتوره مشتريات مورد علي الحساب
            $journal_controller->SupplierPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                $cc_voucher_id, $vat_notes, $supplier_notes,
                $journal_category_id, $notes,
                $invoice_dts);

        } else {
////    قيد فاتوره مشتريات مورد افراد
            $journal_controller->SupplierCashPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                $cc_voucher_id, $vat_notes, $supplier_notes,
                $journal_category_id, $notes,
                $invoice_dts);
        }
        DB::commit();
        return redirect()->route('invoices-purchase');
    }

}
