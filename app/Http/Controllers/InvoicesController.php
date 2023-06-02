<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\WaybillCarInvoiceResource;
use App\Http\Resources\WaybillCargoInvoiceResource;
use App\InvoiceQR\InvoiceDateElement;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\Mail\InvoiceCargoMail;
use App\Models\Attachment;
use App\Models\CompanyMenuSerial;
use App\Models\JournalDt;
use App\Models\JournalType;
use App\Models\Note;
use Carbon\Carbon;
use App\Models\Bond;
use Illuminate\Http\Request;
use App\Models\AccounPeriod;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Customer;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\WaybillHd;
use App\Models\Reports;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lang;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{

    ////////////////invoice type = 3 for الفواتير الضريبيه
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', 1)->where('company_id', $company->company_id)
            ->sortable()->paginate();
        $data = request()->all();
        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 1)->whereIn('company_id', request()->company_id);
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

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->sortable()->paginate();

            }
            if (request()->invoice_no) {
                $query = $query->where('invoice_no', 'like', '%' . request()->invoice_no . '%');
                $invoices = $query->sortable()->paginate();
            }
            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->sortable()->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $report_url = Reports::where('company_id', $company->company_id)
            ->where('report_status', 1)->where('report_code', '73001')->get();


        return view('Invoices.index', compact('companies', 'customers', 'invoices', 'total_amount',
            'data', 'report_url'));
    }

    public function show($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();
        $accounts = $company->accounts->where('acc_level', $company->companyGroup->accounts_levels_number);
        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 106)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 106)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $invoice->company_group_id)->get();

        return view('Invoices.show', compact('invoice', 'companies', 'accounts', 'attachments', 'notes', 'attachment_types'));
    }

    public function create()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();


        $waybill_invoice_item = WaybillHd::where('company_group_id', $company_group->company_group_id)
            ->where('waybill_invoice_id', null)->get();
        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'waybill_invoice_item', 'accounts_periods'));
    }


    public function store(Request $request)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 73)->latest()->first();

        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 73,
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
            'invoice_discount_total' => 0,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'invoice_is_payment' => 1,
            'invoice_type' => 1
        ]);

        foreach ($request->invoice_item_id as $k => $invoice_item_id) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->invoice_item_id[$k],
                'invoice_item_unit' => $request->invoice_item_unit[$k],
                'invoice_item_amount' => $request->invoice_item_amount[$k],
                'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                'invoice_item_price' => $request->invoice_item_price[$k],
                'invoice_item_vat_rate' => $request->invoice_item_vat_rate[$k],
                'invoice_item_vat_amount' => $request->invoice_item_vat_amount[$k],
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $request->invoice_total_amount[$k],
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $request->invoice_item_notes[$k],
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => $request->invoice_due_date
            ]);

        }

        ////////////////////القيد المحاسبي للفاتوره
        $journal_controller = new JournalsController();
        $total_amount = $request->invoice_amount;
        $customer_id = $request->customer_id;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $customer_notes = $invoice_hd->invoice_no . 'تم انشاء قيد للعميل  الفاتوره رقم';
        $cost_center_id = 73;
        $vat_notes = $invoice_hd->invoice_no . 'تم انشاء قيد ضريبي للفاتوره رقم';
        $sales_notes = $invoice_hd->invoice_no . 'تم انشاء قيد ايرادات للفاتوره رقم';
        $journal_category_id = 37;
        $notes = $invoice_hd->invoice_notes;

        // return $request->invoice_item_id;
        $journal_controller->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
            $cost_center_id, $vat_notes, $sales_notes, $journal_category_id, $request->invoice_item_id,
            $request->invoice_item_amount, $notes);

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($company_group->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);

        return redirect()->route('invoices')->with(['success' => 'تمت الاضافه']);
    }

    public function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoices = InvoiceHd::where('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $query = InvoiceHd::whereIn('company_id', request()->company_id);
            $invoices = $query->get();

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $invoices = $query->get();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->get();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->get();

            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->get();
            }

        }

        return Excel::download(new InvoiceExport($invoices), 'invoices.xlsx');
    }

    ////////////////////////////////////////////////////////////
    ////////////////////////فواتير مبيعات الجوال

    public function index2()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', 2)->where('company_id', $company->company_id)->where('branch_id', session('branch')['branch_id'])->get();


        if (request()->company_id) {
            $query = InvoiceHd::whereIn('company_id', request()->company_id);
            $invoices = $query->get();

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $invoices = $query->get();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->get();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->get();

            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->get();
            }

        }


        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        return view('Invoices.Sales.index', compact('companies', 'customers', 'invoices', 'total_amount'));
    }


    public function show2($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();


        return view('Invoices.Sales.show', compact('invoice', 'companies'));
    }


    public function create2()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_id', $company->company_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('Invoices.Sales.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit'));
    }

    public function store2(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 89)->latest()->first();

        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 89,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }


        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'acc_period_id' => 14,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => Carbon::now(),
            'invoice_amount_b' => $request->invoice_amount - $request->invoice_vat_amount,
            'invoice_amount' => $request->invoice_amount,
            'invoice_vat_rate' => 15,
            'invoice_vat_amount' => $request->invoice_vat_amount,
            'invoice_discount_total' => 0,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => 2,
            'invoice_is_payment' => 1,
            'invoice_type' => 2
        ]);


        $item_unit = SystemCode::where('system_code', 95)->where('company_group_id', $company->company_group_id)
            ->first();
        foreach ($request->invoice_item_id as $k => $invoice_item_id) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->invoice_item_id[$k],
                'invoice_item_unit' => $item_unit->system_code_id,

                'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                'invoice_item_price' => $request->invoice_item_price[$k],
                'invoice_item_vat_rate' => 15,
                'invoice_item_vat_amount' => $request->invoice_item_quantity[$k] * $request->invoice_item_price[$k] * 15 / 100,
                'invoice_item_amount' => $request->invoice_item_quantity[$k] * $request->invoice_item_price[$k],
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => ($request->invoice_item_quantity[$k] * $request->invoice_item_price[$k]) + ($request->invoice_item_quantity[$k] * $request->invoice_item_price[$k]) * 15 / 100,
                'invoice_reference_no' => $request->invoice_reference_no,
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $request->invoice_item_notes[$k],
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now()
            ]);
        }

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);

        return redirect()->route('invoices.sales.show', $invoice_hd->invoice_id)->with(['success' => 'تمت الاضافه']);
    }


    public function createDs()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_id', $company->company_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $mntns_cards_type = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 70)
            ->select('system_code_id', 'system_code', 'system_code_name_ar', 'system_code_name_en')->get();
        $selected_type_id = $mntns_cards_type->where('system_code', 70003)->first()->system_code_id;

        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('invoices.Sales.createDs', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'selected_type_id'));
    }

    public function create91()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_id', $company->company_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $mntns_cards_type = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 70)
            ->select('system_code_id', 'system_code', 'system_code_name_ar', 'system_code_name_en')->get();
        $selected_type_id = $mntns_cards_type->where('system_code', 70001)->first()->system_code_id;

        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('invoices.Sales.create91', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'selected_type_id'));
    }

    public function create95()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_id', $company->company_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $mntns_cards_type = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 70)
            ->select('system_code_id', 'system_code', 'system_code_name_ar', 'system_code_name_en')->get();
        $selected_type_id = $mntns_cards_type->where('system_code', 70002)->first()->system_code_id;

        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();
        return view('invoices.Sales.create95', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'selected_type_id'));
    }



////////////////////////////////// invoices cargo small
////////////////////////////////////////////////////////


    public function indexcargo()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', null)->where('company_id', $company->company_id)->paginate();
        $data = request()->all();

        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', null)->whereIn('company_id', request()->company_id);
            $invoices = $query->paginate();

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $invoices = $query->paginate();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('created_date', '<=', request()->to_date);
                $invoices = $query->paginate();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->paginate();

            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        return view('Invoices.Cargo.index', compact('companies', 'customers', 'invoices',
            'total_amount', 'data'));
    }

    public function showcargo($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();


        return view('Invoices.Cargo.show', compact('invoice', 'companies'));
    }


    public function createcargo()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $customers = Customer::where('customer_category', 3)->where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.Cargo.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit'));
    }


    public function storecargo(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 91)->latest()->first();

        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'INV-' . $request->company_id . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 91,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }

        foreach ($request->waybill_id as $waybill_id) {
            $waybill = WaybillHd::find($waybill_id);
            $waybill_total_amount[] = $waybill->waybill_total_amount;
            $waybill_add_amount[] = $waybill->detailsCar->waybill_add_amount;
            $waybill_discount_total[] = $waybill->detailsCar->waybill_discount_total;
            $waybill_item_amount[] = $waybill->detailsCar->waybill_item_amount;
            $waybill_vat_amount[] = $waybill->waybill_vat_amount;
        }

        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'acc_period_id' => $request->acc_period_id,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => $request->invoice_due_date,
            'invoice_amount' => array_sum($waybill_total_amount),
            'invoice_vat_rate' => ((array_sum($waybill_total_amount) - array_sum($waybill_item_amount)) * 100) / array_sum($waybill_total_amount),
            'invoice_vat_amount' => array_sum($waybill_vat_amount),
            'invoice_discount_total' => 0,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'invoice_status' => 121001,
            'invoice_is_payment' => 1,
        ]);


        foreach ($request->waybill_id as $waybill_id) {
            $waybill = WaybillHd::find($waybill_id);

            $invoice_dt = InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $waybill->detailsCar->waybill_item_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => $waybill->detailsCar->waybill_qut_received_customer,
                'invoice_item_price' => $waybill->detailsCar->waybill_item_price + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                'invoice_item_vat_rate' => $waybill->waybill_vat_rate,
                'invoice_item_vat_amount' => $waybill->waybill_vat_amount,
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $waybill->waybill_total_amount,
                'invoice_item_amount' => $waybill->detailsCar->waybill_item_amount + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => '  فاتوره  بوليصه شحن  رقم' . ' ' . $waybill->waybill_code,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now(),
//                'invoice_reference_no' => $waybill->waybill_code
            ]);

            $waybill->waybill_invoice_id = $invoice_hd->invoice_id;
            $waybill->save();

            $invoice_dt->invoice_reference_no = $waybill->waybill_id;
            $invoice_dt->save();


            // WaybillHd::where('waybill_id', '=', $request->invoice_reference_no[$k])->update(array('waybill_invoice_id' => $invoice_hd->invoice_id));


        }

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);


        return redirect()->route('invoicesCargo2')->with(['success' => 'تمت الاضافه']);
    }


    public function getCustomerWaybills()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $system_code_status_cancelled = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41005)->first();
        if (request()->from_date && request()->to_date) {
            $waybills = WaybillHd::where('customer_id', request()->customer_id)
                ->where('waybill_type_id', 4)->where('company_id', request()->company_id)
                ->whereDate('waybill_load_date', '>=', Carbon::parse(request()->from_date)->format('Y-m-d'))
                ->whereDate('waybill_load_date', '<=', Carbon::parse(request()->to_date)->format('Y-m-d'))
                ->where('waybill_invoice_id', null)
                ->where('waybill_status', '!=', $system_code_status_cancelled->system_code_id)
                ->where('waybill_payment_method', 54003)
                ->get();
        } else {
            $waybills = WaybillHd::where('customer_id', request()->customer_id)
                ->where('waybill_type_id', 4)->where('company_id', request()->company_id)
                ->where('waybill_invoice_id', null)
                ->where('waybill_status', '!=', $system_code_status_cancelled->system_code_id)
                ->where('waybill_payment_method', 54003)
                ->get();
        }


        return response()->json(['data' => WaybillCarInvoiceResource::collection($waybills)]);
    }

    public function getCustcargoWaybills()
    {
        $company = Company::find(request()->company_id);

        $waybill_statuses = SystemCode::whereIn('system_code', [41005, 41001])
            ->where('company_group_id', $company->company_group_id)
            ->pluck('system_code_id')->toArray();

        $waybills = WaybillHd::where('customer_id', request()->customer_id)
            ->where('waybill_type_id', 2)->where('company_id', request()->company_id)
            ->where('waybill_invoice_id', null)
            ->whereNotNull('waybill_truck_id')
            ->whereNotIn('waybill_status', $waybill_statuses)
            ->get();

        return response()->json(['data' => WaybillCargoInvoiceResource::collection($waybills)]);
    }

    public function getCompanyAccountPeriods()
    {
        $accounts_periods = AccounPeriod::where('company_id', request()->company_id)
            ->where('acc_period_is_active', 1)->get();

        return response()->json(['data' => $accounts_periods]);

    }


    public function editcargo($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoice = InvoiceHd::find($id);
        $account_periods = AccounPeriod::where('company_id', $invoice->company_id)
            ->where('acc_period_is_active', 1)->get();
        $customers = Customer::where('company_group_id', $invoice->company->company_group_id)->get();

        $system_code_status_cancelled = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41005)->first();

//        $waybills = WaybillHd::where('customer_id', $invoice->customer_id)
//            ->where('waybill_type_id', 4)->where('company_id', $invoice->company_id)
//            ->where('waybill_invoice_id', null)->where('waybill_status', 480)
//            ->where('waybill_payment_method', 54003)
//            ->get();

        $waybill_status = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41004)->first();////بوليصه عميل

        $waybills = WaybillHd::where('customer_id', $invoice->customer_id)
            ->where('waybill_type_id', 2)->where('company_id', $invoice->company_id)
            ->where('waybill_status', '!=', $system_code_status_cancelled->system_code_id)
            ->where('waybill_invoice_id', null)
            ->get();


        $invoice_statuses = SystemCode::where('company_id', $invoice->company_id)
            ->whereIn('system_code', [121001, 121002, 121003])->get();

        $invoice_status_2 = SystemCode::where('company_id', $invoice->company_id)
            ->whereIn('system_code', [121001, 121003])->get();

        $invoice_status_1 = SystemCode::where('company_id', $invoice->company_id)
            ->whereIn('system_code', [121002, 121003])->get();

        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 119)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 119)->get();
        return view('Invoices.cargo.edit', compact('invoice', 'account_periods', 'customers',
            'waybills', 'invoice_statuses', 'invoice_status_1', 'invoice_status_2', 'attachments',
            'attachment_types', 'notes'));
    }

    public function updatecargo(Request $request, $id)
    {
        $invoice_hd = InvoiceHd::find($id);

        DB::beginTransaction();

        if (isset($request->waybill_id)) {
            $discount_value = $request->discount_value;

            if ($discount_value > 0) {
                $invoice_status = SystemCode::where('system_code', 121003)
                    ->where('company_group_id', $invoice_hd->company_group_id)->first()->system_code;
            } else {
                $invoice_status = $request->invoice_status ? $request->invoice_status : $invoice_hd->invoice_status;
            }

            foreach ($request->waybill_id as $waybill_id) {
                $waybill = WaybillHd::find($waybill_id);
                $waybill_total_amount[] = $waybill->waybill_total_amount;
                $waybill_add_amount[] = $waybill->detailsCar->waybill_add_amount;
                $waybill_item_amount[] = $waybill->detailsCar->waybill_item_amount;
                $waybill_vat_amount[] = $waybill->waybill_vat_amount;
            }

            if ($request->discount_value == 0) {
                $invoice_hd->update([
                    'acc_period_id' => $request->acc_period_id,
                    'invoice_due_date' => $request->invoice_due_date,
                    'invoice_amount' => array_sum($waybill_total_amount) - $request->discount_value,
                    'invoice_vat_amount' => array_sum($waybill_vat_amount),
                    'invoice_notes' => $request->invoice_notes,
                    'updated_user' => auth()->user()->user_id,
                    'invoice_status' => $invoice_status,
                    'po_number' => $request->po_number,
                    'payment_tems' => $request->payment_tems,
                    'gr_number' => $request->gr_number,
                    'supply_date' => $request->supply_date,
                    'invoice_discount_total' => $request->discount_value
                ]);
            } else {
                $invoice_hd->update([
                    'acc_period_id' => $request->acc_period_id,
                    'invoice_due_date' => $request->invoice_due_date,
                    'invoice_amount' => $request->total_net,
                    'invoice_vat_amount' => $request->total_vat,
                    'invoice_notes' => $request->invoice_notes,
                    'updated_user' => auth()->user()->user_id,
                    'invoice_status' => $invoice_status,
                    'po_number' => $request->po_number,
                    'payment_tems' => $request->payment_tems,
                    'gr_number' => $request->gr_number,
                    'supply_date' => $request->supply_date,
                    'invoice_discount_total' => $request->discount_value
                ]);
            }

        } else {

            $invoice_hd->update([
                'acc_period_id' => $request->acc_period_id,
                'invoice_due_date' => $request->invoice_due_date,
                // 'invoice_vat_rate' => (array_sum($request->waybill_total_amount) - array_sum($waybill_item_amount)) / 100,
                // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
                'invoice_notes' => $request->invoice_notes,
                'updated_user' => auth()->user()->user_id,
                'invoice_status' => $request->invoice_status ? $request->invoice_status : $invoice_hd->invoice_status,
                'po_number' => $request->po_number,
                'payment_tems' => $request->payment_tems,
                'gr_number' => $request->gr_number,
                'supply_date' => $request->supply_date,
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_tax_no' => $request->customer_tax_no,
                'customer_phone' => $request->customer_phone,
            ]);

        }


        if ($request->invoice_status && $request->invoice_status == 121002) {
            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($invoice_hd->company->company_tax_no),
                new InvoiceDateElement($request->invoice_due_date),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();

            $invoice_hd->update(['qr_data' => $qr]);
        }

        if ($request->invoice_status && $request->invoice_status == 121003) {
            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($invoice_hd->company->company_tax_no),
                new InvoiceDateElement($request->invoice_due_date),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();

            $invoice_hd->update(['qr_data' => $qr]);
        }

        if (isset($request->waybill_id)) {

            foreach ($invoice_hd->invoiceDetails as $invoice_detail) {
                $invoice_detail->delete();
            }

            foreach ($invoice_hd->waybillCars as $invoice_waybill) {
                $invoice_waybill->update(['waybill_invoice_id' => null]);
            }

            foreach ($request->waybill_id as $waybill_id) {

                $waybill = WaybillHd::find($waybill_id);

                $invoice_dt = InvoiceDt::create([
                    'company_group_id' => $invoice_hd->company_group_id,
                    'company_id' => $invoice_hd->company_id,
                    'branch_id' => $invoice_hd->branch_id,
                    'invoice_id' => $invoice_hd->invoice_id,
                    'invoice_item_id' => $waybill->detailsCar->waybill_item_id,
                    'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                    'invoice_item_quantity' => $waybill->detailsCar->waybill_qut_received_customer,
                    'invoice_item_price' => $waybill->detailsCar->waybill_item_price + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                    'invoice_item_vat_rate' => $waybill->waybill_vat_rate,
                    'invoice_item_vat_amount' => $waybill->waybill_vat_amount,
                    'invoice_discount_type' => 1,
                    'invoice_discount_amount' => 0,
                    'invoice_discount_total' => 0,
                    'invoice_total_amount' => $waybill->waybill_total_amount,
                    'invoice_item_amount' => $waybill->detailsCar->waybill_item_amount + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                    'created_user' => auth()->user()->user_id,
                    'invoice_item_notes' => '  فاتوره  بوليصه شحن  رقم' . ' ' . $waybill->waybill_code . ' ' . $request->customer_name,
                    'invoice_from_date' => Carbon::now(),
                    'invoice_to_date' => Carbon::now(),
                    'invoice_reference_no' => $waybill->waybill_id
                ]);

                $waybill->waybill_invoice_id = $invoice_hd->invoice_id;
                $waybill->save();
            }

//            if ($waybill->journal_dt_id) {
//                $journal_dt = JournalDt::where('journal_dt_id', $waybill->journal_dt_id)->first();
//                if (isset($journal_dt)) {
//                    $journal_dt->update(['cc_car_id' => $waybill->waybill_truck_id]);
//                }
//            }

            $invoice_dt->invoice_reference_no = $waybill->waybill_id;
            $invoice_dt->save();

            if ($invoice_hd->journalHd) {
                $invoice_hd->journalHd->journalDetails()->delete();
                $invoice_hd->journalHd()->delete();
            }

            $invoice_journal = new JournalsController();
            $total_amount = WaybillHd::whereIn('waybill_id', $request->waybill_id)->sum('waybill_total_amount');
            $cc_voucher_id = $invoice_hd->invoice_id;
            $customer_notes = 'ايراد فاتورة بضائع رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
            $vat_notes = '   ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
            $sales_notes = '   ايراد فاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
            $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
            $items_id = $request->waybill_id;
            $items_amount = $waybill_item_amount;


            $message = $invoice_journal->addInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
                $customer_notes, 119, $vat_notes, $sales_notes, 39, $items_id,
                $items_amount, $notes);

            if ($message) {
                return redirect('invoices-cargo-add/' . $invoice_hd->invoice_id . '/edit?message=' . $message);
            }

        }

        if ($request->invoice_status == 121003 && $request->has('send_email')) {

            $invoice_waybills = $invoice_hd->waybillCars;
            $details = [
                'title' => 'الساده ' . '  ' . '  /  ' . '  ' . $invoice_hd->customer->customer_name_full_ar . '  ' . ' ؛ ' . '  ' . 'المحترمين ' . '  ' . '  ' . ' ,,, ',
                'body' => $invoice_hd->invoice_due_date . '  ' . 'بتاريخ ' . '  ' . $invoice_hd->invoice_no . '  ' . 'تم اصدار الفاتوره رقم '


            ];
            //  ' ومرفق اصل الفاتوره و المستندات الخاصه بها '   . '  '.   $invoice_hd->invoice_due_date   . '  ' . 'بتاريخ ' . '  ' .  $invoice_hd->invoice_no . '  ' . 'تم اصدار الفاتوره رقم '
            //   'تم اصدار الفاتوره رقم '. '  ' . $invoice_hd->invoice_no . '  ' . 'بتاريخ ' . '  ' . $invoice_hd->invoice_due_date . '  ' . ' ومرفق اصل الفاتوره و المستندات الخاصه بها '


            foreach ($invoice_waybills as $k => $invoice_waybill) {
                $invoice_waybill_ids[] = $invoice_hd->waybillCars;
                if (isset($invoice_waybill_ids[$k])) {
                    $invoice_waybill_id[] = $invoice_waybill->waybill_id;


                    $attachment_files[] = Attachment::whereIn('transaction_id', $invoice_waybill_id)
                        ->where('app_menu_id', 90)->pluck('attachment_file_url');
                }


                $attachment_files_arr = [];
                foreach ($attachment_files as $attachment_file) {
                    foreach ($attachment_file as $attachment_f) {
                        $attachment_files_arr[] = asset('Files/' . $attachment_f);
                    }

                }
            }
            // return $attachment_files_arr;


            \Mail::to($invoice_hd->customer->customer_email)
                ->send(new InvoiceCargoMail($details, $attachment_files_arr));


        }

        DB::commit();
        return back()->with(['success' => 'تم التعديل']);

    }







///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////// فواتير المرتجع


    public function indexreturn()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', 8)->where('company_id', $company->company_id)->sortable()->paginate();
        $data = request()->all();
        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 8)->whereIn('company_id', request()->company_id);
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

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->sortable()->paginate();

            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->sortable()->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        return view('Invoices.Returns.index', compact('companies', 'customers', 'invoices', 'total_amount',
            'data'));
    }

    public function showreturn($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();


        return view('Invoices.Returns.show', compact('invoice', 'companies'));
    }

    public function createreturn()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();


        $waybill_invoice_item = WaybillHd::where('company_group_id', $company_group->company_group_id)
            ->where('waybill_invoice_id', null)->get();
        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.Returns.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'waybill_invoice_item', 'accounts_periods'));
    }


    public function storereturn(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 95)->latest()->first();

        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'INV-R-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 95,
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
            'invoice_discount_total' => 0,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'invoice_is_payment' => 1,
            'invoice_type' => 8,

        ]);

        foreach ($request->invoice_item_id as $k => $invoice_item_id) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->invoice_item_id[$k],
                'invoice_item_unit' => $request->invoice_item_unit[$k],
                'invoice_item_amount' => $request->invoice_item_amount[$k],
                'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                'invoice_item_price' => $request->invoice_item_price[$k],
                'invoice_item_vat_rate' => $request->invoice_item_vat_rate[$k],
                'invoice_item_vat_amount' => $request->invoice_item_vat_amount[$k],
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $request->invoice_total_amount[$k],

                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $request->invoice_item_notes[$k],
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => $request->invoice_due_date
            ]);


        }

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);


        return redirect()->route('Returned-invoices')->with(['success' => 'تمت الاضافه']);
    }

    public function exportreturn()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoices = InvoiceHd::where('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 8)->whereIn('company_id', request()->company_id);
            $invoices = $query->get();

            if (request()->customers_id) {
                $query = $query->where('invoice_type', 8)->whereIn('customer_id', request()->customers_id);
                $invoices = $query->get();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->where('invoice_type', 8)->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->get();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->where('invoice_type', 8)->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->get();

            }

            if (request()->statuses) {
                $query = $query->where('invoice_type', 8)->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->get();
            }

        }

        return Excel::download(new InvoiceExport($invoices), 'Returned-invoices.xlsx');
    }

    ////////////////////////////////////////////////////////////////////
    /////////////////////////////// الفواتير المحاسبيه

    public function indexacc()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', 3)->where('company_id', $company->company_id)->sortable()->paginate();
        $data = request()->all();
        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 3)->whereIn('company_id', request()->company_id);
            $invoices = $query->sortable()->paginate();

            if (request()->customers_id) {
                $query = $query->where('invoice_type', 3)->whereIn('customer_id', request()->customers_id);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->where('invoice_type', 3)->whereDate('invoice_date', '>=', request()->from_date)
                    ->whereDate('invoice_date', '<=', request()->to_date);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->where('invoice_type', 3)->where('invoice_no', '>=', request()->from_date)
                    ->where('invoice_no', '<=', request()->to_date);
                $invoices = $query->sortable()->paginate();

            }

            if (request()->statuses) {
                $query = $query->where('invoice_type', 3)->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->sortable()->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $report_url = Reports::where('company_id', $company->company_id)
            ->where('report_status', 1)->where('report_code', '73003')->get();
        $compani = Company::where('company_id', $company->company_id)->get();

        return view('Invoices.Account.index', compact('compani', 'companies', 'customers', 'invoices', 'total_amount',
            'data', 'report_url'));
    }

    public function showacc($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();

        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 106)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 106)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $invoice->company_group_id)->get();

        $accounts = $company->accounts->where('acc_level', $company->companyGroup->accounts_levels_number);

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($invoice->companyGroup->company_group_ar),
            new TaxNoElement($invoice->company->company_tax_no),
            new InvoiceDateElement($invoice->invoice_due_date),
            new TotalAmountElement($invoice->invoice_amount),
            new TaxAmountElement($invoice->invoice_vat_amount)
        ])->toBase64();

        $invoice->update(['qr_data' => $qr]);

        return view('Invoices.show', compact('invoice', 'companies', 'attachments',
            'notes', 'attachment_types', 'accounts'));
    }

    public function createacc()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();


        $waybill_invoice_item = WaybillHd::where('company_group_id', $company_group->company_group_id)
            ->where('waybill_invoice_id', null)->get();

        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.Account.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'waybill_invoice_item', 'accounts_periods'));
    }

    public function storeacc(Request $request)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 106)->latest()->first();


        if ($request->company_id == 42) {
            $last_invoice_mobi = AccounPeriod::where('company_id', $request->company_id)
                ->where('acc_period_id', $request->acc_period_id)->latest()->first();

            $last_invoice_mobi_number = $last_invoice_mobi->acc_invoice_serial;
            $mobi_number = $last_invoice_mobi_number + 1;
            $string_number = $mobi_number;
            $last_invoice_mobi->update(['acc_invoice_serial' => $string_number]);
        } else {
            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 106,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);

            }
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
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_tax_no' => $request->customer_tax_no,
            'customer_phone' => $request->customer_phone,
            'po_number' => $request->po_number,
            'payment_tems' => $request->payment_tems,
            'gr_number' => $request->gr_number,
            'supply_date' => $request->supply_date,
            'invoice_is_payment' => 1,
            'invoice_type' => 3
        ]);

        foreach ($request->invoice_item_id as $k => $invoice_item_id) {
            $invoice_item = SystemCode::where('system_code_id', $request->invoice_item_id[$k])
                ->first();
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->invoice_item_id[$k],
                'invoice_item_unit' => $request->invoice_item_unit[$k],
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
                'item_account_id' => $invoice_item->system_code_acc_id
            ]);
        }

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($company_group->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->timezone('Asia/Riyadh')->toDateTimeString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);


        return redirect()->route('invoices-acc')->with(['success' => 'تمت الاضافه']);
    }

    public function exportacc()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $invoices = InvoiceHd::where('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $query = InvoiceHd::whereIn('company_id', request()->company_id)->where('invoice_type', 3);
            $invoices = $query->where('invoice_type', 3)->get();

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $invoices = $query->get();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('created_date', '<=', request()->to_date);
                $invoices = $query->get();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->where('invoice_no', '>=', request()->due_date_from)
                    ->where('invoice_no', '<=', request()->due_date_to);
                $invoices = $query->get();

            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->get();
            }

        }

        return Excel::download(new InvoiceExport($invoices), 'invoices-acc.xlsx');
    }

    public function updateAccountsForInvoiceItems(Request $request)
    {

        foreach ($request->invoice_details_id as $k => $invoice_dt_id) {
            $invoice_dt = InvoiceDt::where('invoice_details_id', $invoice_dt_id)->first();
            if ($request->item_account_id[$k]) {
                // return $request->item_account_id[$k];
                $invoice_dt->item_account_id = $request->item_account_id[$k];
                $invoice_dt->save();
            }
        }


        if ($request->po_number) {
            $invoice_hd = InvoiceHd::find($request->invoice_id);
            $invoiced = InvoiceHd::where('invoice_id', $request->po_number)->first();
            $invoiced->credit_invoice_discount = abs($invoice_hd->invoice_amount);
            $invoiced->credit_invoice_id = $invoice_hd->invoice_id;
            $invoiced->save();

            $invoice_hd->po_number = $request->po_number;
            $invoice_hd->save();
        }

        return back()->with(['success' => 'تم التعديل']);
    }


    public function addInvoiceAccJournal($id)

    {
        DB::begintransaction();
        $invoice_hd = InvoiceHd::find($id);

        $invoice_journal = new JournalsController();
        $total_amount = $invoice_hd->invoice_amount;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $customer_notes = 'ايراد فاتورة محاسبيه رقم ' . ' ' . $invoice_hd->invoice_no;
        $vat_notes = '   ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no;
        $sales_notes = '   ايراد فاتوره رقم ' . $invoice_hd->invoice_no;
        $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no;
        $items_id = $invoice_hd->invoiceDetails->pluck('invoice_item_id')->toArray();
        $items_amount = $invoice_hd->invoiceDetails->pluck('invoice_total_amount')->toArray();


        $invoice_journal->addInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
            $customer_notes, 106, $vat_notes, $sales_notes, 37, $items_id,
            $items_amount, $notes);
        DB::commit();
        return back()->with(['success' => 'تم اضافه القيد']);
    }



    ////////////////////////////////////////////////////////////////////
    /////////////////////////////// الفواتير المحاسبيه اشعار خصم

    public function indexcredit()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', 8)->where('company_id', $company->company_id)
            ->sortable()->latest()->paginate();
        $data = request()->all();

        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 8)->whereIn('company_id', request()->company_id);

            $invoices = $query->sortable()->paginate();

            if (request()->customers_id) {
                $query = $query->where('invoice_type', 8)->whereIn('customer_id', request()->customers_id);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->where('invoice_type', 8)->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->invoice_no) {
                $query = $query->where('invoice_no', 'like', '%' . request()->invoice_no . '%');
                $invoices = $query->sortable()->paginate();

            }

            if (request()->credit_invoice_id) {
                $query = $query->whereHas('discountInvoice', function ($q) {
                    return $q->where('credit_invoice_id', 'like', '%' . request()->credit_invoice_id . '%');
                });
                $invoices = $query->sortable()->paginate();

            }

            if (request()->statuses) {
                $query = $query->where('invoice_type', 8)->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->sortable()->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $report_url = Reports::where('company_id', $company->company_id)
            ->where('report_status', 1)->where('report_code', '73008')->get();


        return view('Invoices.Credit.index', compact('companies', 'customers', 'invoices', 'total_amount',
            'data', 'report_url'));
    }


    public function showcredit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();
        $accounts = $company->accounts->where('acc_level', $company->companyGroup->accounts_levels_number);


        return view('Invoices.credit.show', compact('invoice', 'companies', 'accounts'));
    }

    public function createcredit()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();


        $waybill_invoice_item = WaybillHd::where('company_group_id', $company_group->company_group_id)
            ->where('waybill_invoice_id', null)->get();
        // $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $customers = DB::table('customers')->where('company_group_id', $company_group->company_group_id)
            ->select('customer_id', 'customer_name_full_ar')->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.credit.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'waybill_invoice_item', 'accounts_periods'));
    }


    public function storecredit(Request $request)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;

        DB::beginTransaction();

        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 107)->latest()->first();

        if ($request->company_id == 42) {
            $last_invoice_mobi = AccounPeriod::where('company_id', $request->company_id)
                ->where('acc_period_id', $request->acc_period_id)->latest()->first();

            $last_invoice_mobi_number = $last_invoice_mobi->acc_invoice_disc_serial;
            $mobi_number = $last_invoice_mobi_number + 1;
            $string_number = $mobi_number;
            $last_invoice_mobi->update(['acc_invoice_disc_serial' => $string_number]);
        } else {
            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 107,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);
            }
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
            'invoice_discount_total' => 0,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_tax_no' => $request->customer_tax_no,
            'customer_phone' => $request->customer_phone,
            'credit_invoice_id' => $request->po_number,
            'payment_tems' => $request->payment_tems,
            'gr_number' => $request->gr_number,
            'supply_date' => $request->supply_date,
            'invoice_is_payment' => 1,
            'invoice_type' => 8
        ]);

        foreach ($request->invoice_item_id as $k => $invoice_item_id) {
            $invoice_item = SystemCode::where('system_code_id', $invoice_item_id)->first();
            if (!$invoice_item->system_code_acc_id) {
                return back()->with(['error' => 'برجاء مراجعه الحسابات للعناصر الخاصه بالفاتوره']);
            }
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->invoice_item_id[$k],
                'invoice_item_unit' => $request->invoice_item_unit[$k],
                'invoice_item_amount' => $request->invoice_item_amount[$k],
                'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                'invoice_item_price' => $request->invoice_item_price[$k],
                'invoice_item_vat_rate' => $request->invoice_item_vat_rate[$k],
                'invoice_item_vat_amount' => $request->invoice_item_vat_amount[$k],
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $request->invoice_total_amount[$k],
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $request->invoice_item_notes[$k],
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => $request->invoice_due_date,
                'item_account_id' => $invoice_item->system_code_acc_id
            ]);
        }

        $invoiced = InvoiceHd::where('invoice_id', $request->po_number)->first();
        $invoiced->credit_invoice_discount = abs($request->invoice_amount);
        $invoiced->credit_invoice_id = $invoice_hd->invoice_id;
        $invoiced->save();

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($company_group->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement($request->invoice_due_date),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);

        DB::commit();

        return redirect()->route('invoices-credit')->with(['success' => 'تمت الاضافه']);
    }

    public function exportcredit()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoices = InvoiceHd::where('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $query = InvoiceHd::whereIn('company_id', request()->company_id)->where('invoice_type', 8);
            $invoices = $query->where('invoice_type', 8)->get();

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $invoices = $query->get();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->get();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->get();

            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->get();
            }

        }

        return Excel::download(new InvoiceExport($invoices), 'inv-credit.xlsx');
    }

    public function addInvoiceReturnJournal($id)
    {///////////////اشعار الخصم
        ///قيد فاتوره المرتجع

        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        DB::beginTransaction();

        $invoice_hd = InvoiceHd::find($id);

        $journal_controller = new JournalsController();
        $total_amount = abs($invoice_hd->invoice_amount);
        $cc_voucher_id = $invoice_hd->invoice_id;
        $customer_notes = 'قيد  اشعار خصم رقم ' . ' ' . $invoice_hd->invoice_no;
        $vat_notes = '   ضريبه  اشعار خصم رقم ' . $invoice_hd->invoice_no;
        $sales_notes = 'اشعار خصم رقم ' . $invoice_hd->invoice_no;
        $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no;
        $items_id = $invoice_hd->invoiceDetails->pluck('invoice_item_id')->toArray();
        $items_amount = $invoice_hd->invoiceDetails->pluck('invoice_total_amount')->toArray();
        $message = $journal_controller->addSalesInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id, $customer_notes,
            107, $vat_notes, $sales_notes, 47,
            $items_id, $items_amount, $notes);

           
        

        DB::commit();

        if (isset($message)) {
            return back()->with('success', $message);
        } else {
            return back()->with('success', 'تم اضافه القيد');
        }

    }


    public function getCustomerInvoices()
    {
        $customer = Customer::find(request()->customer_id);
        $invoices = $customer->invoices->where('invoice_type', '!=', 8);
        return response()->json(['data' => InvoiceResource::collection($invoices)]);
    }

    ////////////////////////////////////////////////////////////////////
    /////////////////////////////// الفواتير المحاسبيه اشعار اضافه

    public function indexdebit()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', 7)->where('company_id', $company->company_id)->sortable()->paginate();
        $data = request()->all();
        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 7)->whereIn('company_id', request()->company_id);
            $invoices = $query->sortable()->paginate();

            if (request()->customers_id) {
                $query = $query->where('invoice_type', 7)->whereIn('customer_id', request()->customers_id);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->where('invoice_type', 7)->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->where('invoice_type', 7)->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->sortable()->paginate();

            }

            if (request()->statuses) {
                $query = $query->where('invoice_type', 7)->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->sortable()->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $report_url = Reports::where('company_id', $company->company_id)
            ->where('report_status', 1)->where('report_code', '73008')->get();


        return view('Invoices.Debit.index', compact('companies', 'customers', 'invoices', 'total_amount',
            'data', 'report_url'));
    }

    public function showdebit($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();


        return view('Invoices.show', compact('invoice', 'companies'));
    }

    public function createdebit()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();


        $waybill_invoice_item = WaybillHd::where('company_group_id', $company_group->company_group_id)
            ->where('waybill_invoice_id', null)->get();
        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.debit.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'waybill_invoice_item', 'accounts_periods'));
    }


    public function storedebit(Request $request)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 108)->latest()->first();


        if ($request->company_id == 42) {
            $last_invoice_mobi = AccounPeriod::where('company_id', $request->company_id)
                ->where('acc_period_id', $request->acc_period_id)->latest()->first();

            $last_invoice_mobi_number = $last_invoice_mobi->acc_invoice_disc_serial;
            $mobi_number = $last_invoice_mobi_number + 1;
            $string_number = $mobi_number;
            $last_invoice_mobi->update(['acc_invoice_disc_serial' => $string_number]);
        } else {
            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 108,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);

            }
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
            'invoice_discount_total' => 0,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_tax_no' => $request->customer_tax_no,
            'customer_phone' => $request->customer_phone,
            'po_number' => $request->po_number,
            'payment_tems' => $request->payment_tems,
            'gr_number' => $request->gr_number,
            'supply_date' => $request->supply_date,
            'invoice_is_payment' => 1,
            'invoice_type' => 7
        ]);

        foreach ($request->invoice_item_id as $k => $invoice_item_id) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->invoice_item_id[$k],
                'invoice_item_unit' => $request->invoice_item_unit[$k],
                'invoice_item_amount' => $request->invoice_item_amount[$k],
                'invoice_item_quantity' => $request->invoice_item_quantity[$k],
                'invoice_item_price' => $request->invoice_item_price[$k],
                'invoice_item_vat_rate' => $request->invoice_item_vat_rate[$k],
                'invoice_item_vat_amount' => $request->invoice_item_vat_amount[$k],
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $request->invoice_total_amount[$k],

                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $request->invoice_item_notes[$k],
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => $request->invoice_due_date
            ]);


        }

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($company_group->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->timezone('Asia/Riyadh')->toDateTimeString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);


        return redirect()->route('invoices-debit')->with(['success' => 'تمت الاضافه']);
    }

    public function exportdebit()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoices = InvoiceHd::where('company_id', $company->company_id)->get();
        if (request()->company_id) {
            $query = InvoiceHd::whereIn('company_id', request()->company_id)->where('invoice_type', 7);
            $invoices = $query->where('invoice_type', 7)->get();

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $invoices = $query->get();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('invoice_due_date', '<=', request()->to_date);
                $invoices = $query->get();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);
                $invoices = $query->get();

            }

            if (request()->statuses) {
                $query = $query->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->get();
            }

        }

        return Excel::download(new InvoiceExport($invoices), 'inv-debit.xlsx');
    }

////////////////////////////////// invoices cars transportation
////////////////////////////////////////////////////////


    public function indexcars()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        // $invoices = InvoiceHd::where('invoice_type', 9)->where('company_id', $company->company_id)->get();

        $invoices = InvoiceHd::where('invoice_type', 9)->where('company_id', $company->company_id)->latest()->paginate();
        $data = request()->all();

        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 9)->whereIn('company_id', request()->company_id);


            if (request()->customers_id) {
                $query = $query->where('invoice_type', 9)->whereIn('customer_id', request()->customers_id);

            }

            if (request()->from_date && request()->to_date) {
                $query = $query->where('invoice_type', 9)->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('created_date', '<=', request()->to_date);

            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->where('invoice_type', 9)->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);


            }

            if (request()->statuses) {
                $query = $query->where('invoice_type', 9)->whereIn('invoice_status', request()->statuses);

            }
            $invoices = $query->latest()->paginate();
        }

        if (request()->invoice_code) {
            $invoices = InvoiceHd::where('invoice_type', 9)->where('company_id', $company->company_id)
                ->where('invoice_no', 'like', '%' . request()->invoice_code . '%')
                ->latest()->sortable()->paginate();
        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());
        $report_url = Reports::where('company_id', $company->company_id)
            ->where('report_status', 1)->where('report_code', '73009')->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        return view('Invoices.Cars.index', compact('companies', 'customers', 'invoices', 'total_amount', 'data', 'report_url'));
    }

    public function showcars($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();


        return view('Invoices.Cars.show', compact('invoice', 'companies'));
    }

    public function createcars()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();


        $waybill_invoice_item = WaybillHd::where('company_group_id', $company_group->company_group_id)
            ->where('waybill_invoice_id', null)->get();
        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.Cars.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'waybill_invoice_item', 'accounts_periods'));
    }

    public function storecars(Request $request)
    {
        if (!$request->waybill_id) {
            return back()->with(['error' => 'يجب اضافه بوالص']);
        }


        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('branch_id', session('branch')['branch_id'])
            ->where('app_menu_id', 119)->latest()->first();
        \DB::beginTransaction();


        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number_invoice = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number_invoice]);

        } else {
            $string_number_invoice = 'INV-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 119,
                'acc_period_year' => Carbon::now()->format('y'),
                'branch_id' => session('branch')['branch_id'],
                'serial_last_no' => $string_number_invoice,
                'created_user' => auth()->user()->user_id
            ]);
        }


        foreach ($request->waybill_id as $waybill_id) {
            $waybill = WaybillHd::find($waybill_id);
            $waybill_total_amount[] = $waybill->waybill_total_amount;
            $waybill_add_amount[] = $waybill->detailsCar->waybill_add_amount;
            $waybill_discount_total[] = $waybill->detailsCar->waybill_discount_total;
            $waybill_item_amount[] = $waybill->detailsCar->waybill_item_amount;
            $waybill_vat_amount[] = $waybill->waybill_vat_amount;
        }

        $invoice_status = SystemCode::where('system_code', 121001)
            ->where('company_group_id', $request->company_group_id)->first();


        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'acc_period_id' => $request->acc_period_id,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => $request->invoice_due_date,
            'invoice_amount' => array_sum($waybill_total_amount),
            'invoice_vat_rate' => ((array_sum($waybill_total_amount) - array_sum($waybill_item_amount)) * 100) / array_sum($waybill_total_amount),
            // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
            'invoice_vat_amount' => array_sum($waybill_vat_amount),
            'invoice_discount_total' => 0,
            'invoice_down_payment' => 0,
            'invoice_total_payment' => 0,
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number_invoice,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,

            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_tax_no' => $request->customer_tax_no,
            'customer_phone' => $request->customer_phone,
            'po_number' => $request->po_number,
            'payment_tems' => $request->payment_tems,
            'gr_number' => $request->gr_number,
            'supply_date' => $request->supply_date,

            'invoice_is_payment' => 1,
            'invoice_type' => 9, ///فاتوره السياره
            'invoice_status' => 121001
        ]);


        foreach ($request->waybill_id as $waybill_id) {
            $waybill = WaybillHd::find($waybill_id);

            $invoice_dt = InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $waybill->detailsCar->waybill_item_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id,
                'invoice_item_quantity' => $waybill->detailsCar->waybill_qut_received_customer,
                'invoice_item_price' => $waybill->detailsCar->waybill_item_price + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                'invoice_item_vat_rate' => $waybill->waybill_vat_rate,
                'invoice_item_vat_amount' => $waybill->waybill_vat_amount,
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $waybill->waybill_total_amount,
                'invoice_item_amount' => $waybill->detailsCar->waybill_item_amount + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => ' بوليصه شحن سياره رقم' . ' ' . $waybill->waybill_code,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now(),
                'invoice_reference_no' => $waybill->waybill_id
            ]);

            $waybill->waybill_invoice_id = $invoice_hd->invoice_id;
            $waybill->save();

            $invoice_dt->invoice_reference_no = $waybill->waybill_id;
            $invoice_dt->save();

        }

        $invoice_journal = new JournalsController();
        $total_amount = $invoice_hd->invoice_amount;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $customer_notes = 'ايراد فاتورة سيارات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
        $vat_notes = '   ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
        $sales_notes = '   ايراد فاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
        $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
        $items_id = $request->waybill_id;
        $items_amount = $waybill_item_amount;


        $invoice_journal->addInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
            $customer_notes, 2000, $vat_notes, $sales_notes, 40, $items_id,
            $items_amount, $notes);

        //    if ($message) {
        //       return back()->with('error', $message);
        //   }

        \DB::commit();

        return redirect()->route('invoicesCars')->with(['success' => 'تمت الاضافه']);
    }

    public function editCars($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoice = InvoiceHd::find($id);
        $account_periods = AccounPeriod::where('company_id', $invoice->company_id)
            ->where('acc_period_is_active', 1)->get();
        $customers = Customer::where('company_group_id', $invoice->company->company_group_id)->get();

        $system_code_status_cancelled = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41005)->first();
        $bonds_cash = Bond::where('bond_type_id', 2)->where('bond_ref_no', $invoice->invoice_no)->latest()->get();
        $bonds_capture = Bond::where('bond_type_id', 1)->where('bond_ref_no', $invoice->invoice_no)->latest()->get();
//        $waybills = WaybillHd::where('customer_id', $invoice->customer_id)
//            ->where('waybill_type_id', 4)->where('company_id', $invoice->company_id)
//            ->where('waybill_invoice_id', null)->where('waybill_status', 480)
//            ->where('waybill_payment_method', 54003)
//            ->get();

        $waybill_status = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41004)->first();////بوليصه عميل

        $waybills = WaybillHd::where('customer_id', $invoice->customer_id)
            ->where('waybill_type_id', 4)->where('company_id', $invoice->company_id)
            ->where('waybill_status', '!=', $system_code_status_cancelled->system_code_id)
            ->where('waybill_invoice_id', null)
            ->where('waybill_payment_method', 54003)
            ->get();

        $invoice_statuses = SystemCode::where('company_id', $invoice->company_id)
            ->whereIn('system_code', [121001, 121002, 121003])->get();

        $invoice_status_2 = SystemCode::where('company_id', $invoice->company_id)
            ->whereIn('system_code', [121001, 121003])->get();

        $invoice_status_1 = SystemCode::where('company_id', $invoice->company_id)
            ->whereIn('system_code', [121002, 121003])->get();

        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 119)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $company->company_group_id)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 119)->get();

        if (request()->ajax()) {
            $invoice = InvoiceHd::find($id);
            return response()->json(['data' => $invoice]);
        }

        return view('Invoices.Cars.edit', compact('invoice', 'account_periods', 'customers', 'bonds_cash', 'bonds_capture',
            'waybills', 'invoice_statuses', 'invoice_status_1', 'invoice_status_2', 'attachments',
            'attachment_types', 'notes', 'id'));
    }

    public function updateCars(Request $request, $id)
    {
        $invoice_hd = InvoiceHd::find($id);
        \DB::beginTransaction();


        if (isset($request->waybill_id)) {
            foreach ($request->waybill_id as $waybill_id) {
                $waybill = WaybillHd::find($waybill_id);
                $waybill_total_amount[] = $waybill->waybill_total_amount;
                $waybill_add_amount[] = $waybill->detailsCar->waybill_add_amount;
                $waybill_discount_total[] = $waybill->detailsCar->waybill_discount_total;
                $waybill_item_amount[] = $waybill->detailsCar->waybill_item_amount;
                $waybill_vat_amount[] = $waybill->waybill_vat_amount;
            }

            $invoice_hd->update([
                'acc_period_id' => $request->acc_period_id,
                'invoice_due_date' => $request->invoice_due_date,
                'invoice_amount' => array_sum($waybill_total_amount),
                // 'invoice_vat_rate' => (array_sum($request->waybill_total_amount) - array_sum($waybill_item_amount)) / 100,
                // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
                'invoice_vat_amount' => array_sum($waybill_vat_amount),
                'invoice_notes' => $request->invoice_notes,
                'updated_user' => auth()->user()->user_id,
                'invoice_status' => $request->invoice_status ? $request->invoice_status : $invoice_hd->invoice_status,
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_tax_no' => $request->customer_tax_no,
                'customer_phone' => $request->customer_phone,
                'po_number' => $request->po_number,
                'payment_tems' => $request->payment_tems,
                'gr_number' => $request->gr_number,
                'supply_date' => $request->supply_date,
            ]);

        } else {
            $invoice_hd->update([
                'acc_period_id' => $request->acc_period_id,
                'invoice_due_date' => $request->invoice_due_date,
                // 'invoice_vat_rate' => (array_sum($request->waybill_total_amount) - array_sum($waybill_item_amount)) / 100,
                // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
                'invoice_notes' => $request->invoice_notes,
                'updated_user' => auth()->user()->user_id,
                'invoice_status' => $request->invoice_status ? $request->invoice_status : $invoice_hd->invoice_status,
                'po_number' => $request->po_number,
                'payment_tems' => $request->payment_tems,
                'gr_number' => $request->gr_number,
                'supply_date' => $request->supply_date,
                'customer_name' => $request->customer_name,
                'customer_address' => $request->customer_address,
                'customer_tax_no' => $request->customer_tax_no,
                'customer_phone' => $request->customer_phone,
            ]);
        }

        if ($request->invoice_status && $request->invoice_status == 121002) {
            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($invoice_hd->company->company_tax_no),
                new InvoiceDateElement($request->invoice_due_date),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();

            $invoice_hd->update(['qr_data' => $qr]);
        }
        if ($request->invoice_status && $request->invoice_status == 121003) {
            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($invoice_hd->company->company_tax_no),
                new InvoiceDateElement($request->invoice_due_date),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();

            $invoice_hd->update(['qr_data' => $qr]);
        }


        if (isset($request->waybill_id)) {

            $waybill_ids_old = $invoice_hd->invoiceDetails->pluck('invoice_reference_no')->toArray();

            $differenceArray = array_diff($waybill_ids_old, $request->waybill_id);
//            foreach ($invoice_hd->invoiceDetails as $invoice_detail) {
//                $invoice_detail->delete();
//            }
//
//            foreach ($invoice_hd->waybillCars as $invoice_waybill) {
//                $invoice_waybill->update(['waybill_invoice_id' => null]);
//            }

            foreach ($request->waybill_id as $waybill_id) {

                $waybill = WaybillHd::find($waybill_id);

                if (!in_array($waybill->waybill_id, $waybill_ids_old)) {
                    $invoice_dt = InvoiceDt::create([
                        'company_group_id' => $invoice_hd->company_group_id,
                        'company_id' => $invoice_hd->company_id,
                        'branch_id' => $invoice_hd->branch_id,
                        'invoice_id' => $invoice_hd->invoice_id,
                        'invoice_item_id' => $waybill->detailsCar->waybill_item_id,
                        'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                        'invoice_item_quantity' => $waybill->detailsCar->waybill_qut_received_customer,
                        'invoice_item_price' => $waybill->detailsCar->waybill_item_price + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                        'invoice_item_vat_rate' => $waybill->waybill_vat_rate,
                        'invoice_item_vat_amount' => $waybill->waybill_vat_amount,
                        'invoice_discount_type' => 1,
                        'invoice_discount_amount' => 0,
                        'invoice_discount_total' => 0,
                        'invoice_total_amount' => $waybill->waybill_total_amount,
                        'invoice_item_amount' => $waybill->detailsCar->waybill_item_amount + $waybill->detailsCar->waybill_add_amount - $waybill->detailsCar->waybill_discount_total,
                        'created_user' => auth()->user()->user_id,
                        'invoice_item_notes' => '  فاتوره  بوليصه شحن سياره رقم' . ' ' . $waybill->waybill_code . ' ' . $request->customer_name,
                        'invoice_from_date' => Carbon::now(),
                        'invoice_to_date' => Carbon::now(),
                        'invoice_reference_no' => $waybill->waybill_id
                    ]);

                    $waybill->waybill_invoice_id = $invoice_hd->invoice_id;
                    $waybill->save();

                    if ($waybill->journal_dt_id) {
                        $journal_dt = JournalDt::where('journal_dt_id', $waybill->journal_dt_id)->first();
                        $journal_dt->update(['cc_car_id' => $waybill->waybill_truck_id]);
                    }

                    $invoice_dt->invoice_reference_no = $waybill->waybill_id;
                    $invoice_dt->save();
                }
            }


            if (count($differenceArray) > 0) {

                foreach ($differenceArray as $arr_code) {
                    $waybill_old = WaybillHd::find($arr_code);

                    $invoice_dt = InvoiceDt::where('invoice_reference_no', $arr_code)
                        ->where('invoice_id', $invoice_hd->invoice_id)->first();

                    if (isset($waybill_old)) {
                        $invoice_dt->delete();
                        $waybill_old->waybill_invoice_id = null;
                        $waybill_old->save();
                    }
                }

            }
            if ($invoice_hd->journal_hd_id) {
                $invoice_hd->refresh();

                $journal_controller = new JournalsController();
                $total_amount = $invoice_hd->invoice_amount;
                $cc_voucher_id = $invoice_hd->invoice_id;
                $vat_amount = $invoice_hd->invoice_vat_amount;
                $items_id = WaybillHd::whereIn('waybill_id', $invoice_hd->invoiceDetails->pluck('invoice_reference_no')->toArray())
                    ->pluck('waybill_id')->toArray();

                $sales_notes = '   ايراد فاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;

                $journal_controller->updateInvoiceJournal($total_amount, $vat_amount, 2000, $cc_voucher_id,
                    $items_id, $sales_notes);

            } else {

                $invoice_journal = new JournalsController();
                $total_amount = $invoice_hd->invoice_amount;
                $cc_voucher_id = $invoice_hd->invoice_id;
                $customer_notes = 'ايراد فاتورة سيارات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
                $vat_notes = '   ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
                $sales_notes = '   ايراد فاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
                $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no . ' ' . $request->customer_name;
                $items_id = $request->waybill_id;
                $items_amount = $waybill_item_amount;


                $invoice_journal->addInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
                    $customer_notes, 2000, $vat_notes, $sales_notes, 40, $items_id,
                    $items_amount, $notes);


            }


        }

        \DB::commit();

        return back()->with(['success' => 'تم التعديل']);

    }

    ////////////////////////////////////////////////////////////////////
    /////////////////////////////// الفواتير المحاسبيه للايجارات

    public function indexrent()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $invoices = InvoiceHd::where('invoice_type', 5)->where('company_id', $company->company_id)->sortable()->paginate();
        $data = request()->all();
        if (request()->company_id) {
            $query = InvoiceHd::where('invoice_type', 5)->whereIn('company_id', request()->company_id);
            $invoices = $query->sortable()->paginate();

            if (request()->customers_id) {
                $query = $query->where('invoice_type', 5)->whereIn('customer_id', request()->customers_id);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->from_date && request()->to_date) {
                $query = $query->where('invoice_type', 5)->whereDate('invoice_date', '>=', request()->from_date)
                    ->whereDate('invoice_date', '<=', request()->to_date);
                $invoices = $query->sortable()->paginate();
            }

            if (request()->due_date_from && request()->due_date_to) {
                $query = $query->where('invoice_type', 5)->where('invoice_no', '>=', request()->from_date)
                    ->where('invoice_no', '<=', request()->to_date);
                $invoices = $query->sortable()->paginate();

            }

            if (request()->statuses) {
                $query = $query->where('invoice_type', 5)->whereIn('invoice_is_payment', request()->statuses);
                $invoices = $query->sortable()->paginate();
            }

        }

        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $report_url = Reports::where('company_id', $company->company_id)
            ->where('report_status', 1)->where('report_code', '73003')->get();
        $compani = Company::where('company_id', $company->company_id)->get();

        return view('Invoices.AccountRent.index', compact('compani', 'companies', 'customers', 'invoices', 'total_amount',
            'data', 'report_url'));
    }

    public function showrent($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)->where('company_group_id', $company_group->company_group_id)->first();

        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 106)->get();
        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 106)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)
            ->where('company_group_id', $invoice->company_group_id)->get();

        $accounts = $company->accounts->where('acc_level', $company->companyGroup->accounts_levels_number);

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($invoice->companyGroup->company_group_ar),
            new TaxNoElement($invoice->company->company_tax_no),
            new InvoiceDateElement($invoice->invoice_due_date),
            new TotalAmountElement($invoice->invoice_amount),
            new TaxAmountElement($invoice->invoice_vat_amount)
        ])->toBase64();

        $invoice->update(['qr_data' => $qr]);

        return view('Invoices.AccountRent.show', compact('invoice', 'companies', 'attachments',
            'notes', 'attachment_types', 'accounts'));
    }

    public function createrent()
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company_group->company_group_id)->get();

        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts_periods = AccounPeriod::where('company_id', $company->company_id)
            ->where('acc_period_is_active', 1)->where('emp_payroll_status', 0)->get();


        $waybill_invoice_item = WaybillHd::where('company_group_id', $company_group->company_group_id)
            ->where('waybill_invoice_id', null)->get();

        $customers = Customer::where('company_group_id', $company_group->company_group_id)->get();
        $companies = Company::where('company_group_id', $company_group->company_group_id)->get();


        return view('Invoices.AccountRent.create', compact('companies', 'customers',
            'sys_codes_item', 'sys_codes_unit', 'waybill_invoice_item', 'accounts_periods'));
    }

    public function storerent(Request $request)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $company = session('company') ? session('company') : auth()->user()->company;
        $company_inv = Company::where('company_id', $request->company_id)->latest()->first();
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 132)->latest()->first();


        if ($request->company_id == 42) {
            $last_invoice_mobi = AccounPeriod::where('company_id', $request->company_id)
                ->where('acc_period_id', $request->acc_period_id)->latest()->first();

            $last_invoice_mobi_number = $last_invoice_mobi->acc_invoice_serial;
            $mobi_number = $last_invoice_mobi_number + 1;
            $string_number = $mobi_number;
            $last_invoice_mobi->update(['acc_invoice_serial' => $string_number]);
        } else {
            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $request->company_id,
                    'app_menu_id' => 132,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);

            }
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
            'customer_id' => $request->customer_id,
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
            'customer_tax_no' => $request->customer_tax_no,
            'customer_phone' => $request->customer_phone,
            'po_number' => $request->po_number,
            'payment_tems' => $request->payment_tems,
            'gr_number' => $company_inv->company_name_ar,
            'supply_date' => $request->supply_date,

            'invoice_is_payment' => 1,
            'invoice_type' => 5
        ]);

        foreach ($request->invoice_item_id as $k => $invoice_item_id) {
            $invoice_item = SystemCode::where('system_code_id', $request->invoice_item_id[$k])
                ->first();
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->invoice_item_id[$k],
                'invoice_item_unit' => $request->invoice_item_unit[$k],
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
                'invoice_from_date' => $request->invoice_from_date[$k],
                'invoice_to_date' => $request->invoice_to_date[$k],
                'item_account_id' => $invoice_item->system_code_acc_id
            ]);
        }

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($invoice_hd->gr_number),
            new TaxNoElement($company_inv->company_tax_no),
            new InvoiceDateElement(Carbon::now()->timezone('Asia/Riyadh')->toDateTimeString()),
            new TotalAmountElement($invoice_hd->invoice_amount),
            new TaxAmountElement($invoice_hd->invoice_vat_amount)
        ])->toBase64();
        $invoice_hd->update(['qr_data' => $qr]);


        return redirect()->route('invoices-rent')->with(['success' => 'تمت الاضافه']);
    }


}
