<?php

namespace App\Http\Controllers;

use App\Http\Controllers\General\JournalsController;
use App\Http\Controllers\Naql\NaqlController;
use App\Http\Controllers\Naql\NaqlAPIController;
use App\Http\Controllers\Naql\NaqlWayAPIController;
use App\InvoiceQR\InvoiceDateElement;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\Models\CompanyMenuSerial;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\SMSCategory;
use App\Models\WaybillDt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Customer;
use App\Models\WaybillHd;
use App\Models\Employee;
use App\Models\Trucks;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class WaybillController extends Controller
{

    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $trucks = Trucks::where('company_group_id', $company->company_group_id)
        ->where('truck_type', SystemCode::where('company_group_id', $company->company_group_id)
        ->where('system_code_search','=', 'nakliatt')->first()->system_code_id)->get();

        $sys_codes_waybill_status_codes = SystemCode::whereIn('system_code', ['41001', '41003', '41004', '41008'])
            ->where('company_group_id', $company->company_group_id)->pluck('system_code_id')
            ->toArray();

        $customers = Customer::where('customer_category', 2)
            ->where('company_group_id', $company->company_group_id)->get();
        $data = request()->all();
        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)->get();

        $way_pills_all = WaybillHd::where('company_id', $company->company_id)
            ->whereIn('waybill_status', $sys_codes_waybill_status_codes)
            ->where('waybill_type_id', 1);

        $way_pills = WaybillHd::where('company_id', $company->company_id)
            ->whereIn('waybill_status', $sys_codes_waybill_status_codes)
            ->where('waybill_type_id', 1)->sortable()->paginate();

        if (request()->company_id) {

            $query = WaybillHd::where('waybill_type_id', 1)->whereIn('company_id', request()->company_id);

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                    ->whereDate('created_date', '<=', request()->created_date_to);
            }

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
            }
            if (request()->trucks_id) {
                $query = $query->whereIn('waybill_truck_id', request()->trucks_id);
            }
            if (request()->statuses_id) {

                $query = $query->whereIn('waybill_status', request()->statuses_id);

            }
            if (request()->waybill_ref) {
                $query = $query->where('waybill_ticket_no', 'like', '%' . request()->waybill_ref . '%');
            }
            if (request()->expected_date_from && request()->expected_date_to) {

                $query = $query->whereDate('waybill_delivery_expected', '>=', request()->expected_date_from)
                    ->whereDate('waybill_delivery_expected', '<=', request()->expected_date_to);
            }

            $way_pills_all = $query;
            $way_pills = $query->sortable()->paginate();
        }

        $total = array_sum($way_pills->pluck('waybill_total_amount')->toArray());
        $total_vat = array_sum($way_pills->pluck('waybill_vat_amount')->toArray());


        $query0 = $way_pills->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41004)->first()->system_code_id)
            ->where('waybill_type_id', 1)
            ->count();//بوليصه عميل

        $query1 = $way_pills->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41001)->first()
            ->system_code_id)->where('company_id', $company->company_id)
            ->where('waybill_type_id', 1)
            ->count(); ///امر تحميل

        $query2 = $way_pills->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41003)->first()
            ->system_code_id)->where('company_id', $company->company_id)
            ->where('waybill_type_id', 1)
            ->count(); /// فاتورة شراء

        $query3 = $way_pills->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41008)->first()
            ->system_code_id)->where('company_id', $company->company_id)
            ->where('waybill_type_id', 1)
            ->count(); /// تم التسليم


        $total_waybills = DB::table('waybill_hd')
            ->where('company_id', $company->company_id)
            ->where('waybill_type_id', 1)->count();

        $query0_p = $total_waybills > 0 ? number_format(($query0 / $total_waybills) * 100, 2) : 0;
        $query1_p = $total_waybills > 0 ? number_format(($query1 / $total_waybills) * 100, 2) : 0;
        $query2_p = $total_waybills > 0 ? number_format(($query2 / $total_waybills) * 100, 2) : 0;
        $query3_p = $total_waybills > 0 ? number_format(($query3 / $total_waybills) * 100, 2) : 0;


        $total_all = $way_pills_all->sum('waybill_total_amount');
        $total_vat_all = $way_pills_all->sum('waybill_vat_amount');


        return view('Waybill.index', compact('companies', 'way_pills', 'customers',
            'sys_codes_waybill_status', 'total', 'total_vat', 'total_all', 'total_vat_all', 'data',
            'query0', 'query1', 'query2', 'query3', 'total_waybills', 'trucks',
            'query0_p', 'query1_p', 'query2_p', 'query3_p', 'company'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_id', $company->company_id)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
            ->get()->whereNotIn('system_code', [41005, 41002, 41006, 41007, 41008, 41009]);


        $suppliers = Customer::where('customer_category', 1)->where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', $company->company_group_id)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', 485)->where('company_group_id', $company->company_group_id)->get();
//        $trucks = Trucks::where('company_group_id', $company->company_group_id)
//            ->where('truck_status', SystemCode::where('system_code', 80)->first()->system_code_id)
//            ->get();

        $trucks = Trucks::where('company_group_id', $company->company_group_id)
            ->get();
        return view('Waybill.create', compact('companies', 'suppliers', 'customers', 'employees',
            'trucks', 'sys_codes_location', 'sys_codes_item', 'sys_codes_unit', 'sys_codes_waybill_status'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'waybill_load_date' => 'date',
            'waybill_delivery_expected' => 'after_or_equal:waybill_load_date',
        ], [
            'after_or_equal' => 'تاريخ الوصول المتوقع يجب ان يسبق تاريخ التحميل او يساويه'
        ]);

        $company = session('company') ? session('company') : auth()->user()->company;
        $waybill_status_code = SystemCode::where('system_code', $request->waybill_status)->first();
        $last_waypill_serial = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 70)->latest()->first();

        if (isset($last_waypill_serial)) {
            $last_waypill_serial_no = $last_waypill_serial->serial_last_no;
            $array_number = explode('-', $last_waypill_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_waypill_serial->update(['serial_last_no' => $string_number]);

        } else {
            $string_number = 'WAY-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => Company::where('company_id', $request->company_id)->first()->company_group_id,
                'company_id' => $request->company_id,
                'app_menu_id' => 70,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $supplier = Customer::where('customer_id', $request->supplier_id)->first();
        if ($request->customer_id) {
            $customer = Customer::where('customer_id', $request->customer_id)->first();
        }

        $waybill_fees_vat_amount = ($request->waybill_item_vat_rate ) * ($request->waybill_fees_load
                + $request->waybill_fees_difference + $request->waybill_fees_wait);

        \DB::beginTransaction();

        $waybill_hd = WaybillHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'waybill_code' => $string_number,
            'waybill_type_id' => 1,
            'supplier_id' => $request->supplier_id,
            'waybill_sender_name' => $supplier->customer_name_full_ar,
            'waybill_sender_company' => $supplier->customer_company,
            'waybill_sender_address' => $supplier->customer_address_1,
            'waybill_sender_phone' => $supplier->customer_phone,
            'waybill_sender_mobile' => $request->waybill_sender_mobile ? $request->waybill_sender_mobile : '',
            'waybill_sender_mobile_code' => $supplier->customer_mobile_code,
            'waybill_receiver_company' => isset($customer) ? $customer->customer_company : null,
            'waybill_receiver_address' => isset($customer) ? $customer->customer_address_1 : null,
            'waybill_receiver_phone' => isset($customer) ? $customer->customer_phone : null,
            'waybill_receiver_mobile' => $request->waybill_receiver_mobile ? $request->waybill_receiver_mobile : '',
            'waybill_receiver_mobile_code' => isset($customer) ? $customer->customer_mobile_code : null,
            'waybill_status' => $waybill_status_code->system_code_id,
            'waybill_loc_from' => $request->waybill_loc_from,
            'waybill_driver_id' => $request->waybill_driver_id ? $request->waybill_driver_id : null,
            'waybill_truck_id' => $request->waybill_truck_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id ? $request->customer_id : null,
            'customer_contract' => $request->customer_contract ? $request->customer_contract : null,
            'waybill_load_date' => $request->waybill_load_date,
            'waybill_unload_date' => $request->waybill_unload_date ? $request->waybill_unload_date : null,
            'waybill_ticket_no' => $request->waybill_ticket_no ? $request->waybill_ticket_no : null,
            'waybill_vat_rate' => $request->waybill_item_vat_rate,
            'waybill_vat_amount' => $request->waybill_item_vat_amount + $waybill_fees_vat_amount, ///customer
            'waybill_total_amount' => $request->waybill_total_amount, ///customer
            'waybill_loc_to' => $request->waybill_loc_to,
            'waybill_delivery_expected' => $request->waybill_delivery_expected ? $request->waybill_delivery_expected : null,
            'created_user' => auth()->user()->user_id,
            'waybill_create_user' => auth()->user()->user_id,
            'waybill_fees_total' => $request->waybill_total_fees_amount,
            'waybill_delivery_user' => $request->waybill_status == 479 ? auth()->user()->user_id : null
        ]);


        if ($request->waybill_driver_id) {
            $driver = Employee::where('emp_id', $request->waybill_driver_id)->first();
            $driver->update([
                'issueNumber' => $request->issueNumber,
                'emp_identity' => $request->emp_identity,
            ]);
        }

        if ($request->supplier_id) {
            $supplier = Customer::where('customer_id', $request->supplier_id)->first();
            $supplier->update([
                'customer_mobile' => $request->waybill_sender_mobile
            ]);
        }

        if ($request->customer_id) {
            $customer = Customer::where('customer_id', $request->customer_id)->first();
            $customer->update([
                'customer_mobile' => $request->waybill_receiver_mobile
            ]);
        }

        if ($request->waybill_item_quantity && $request->waybill_item_price) {
            $item_amount = $request->waybill_item_quantity * $request->waybill_item_price;
        }

        $waybill_dt = WaybillDt::create([
            'waybill_hd_id' => $waybill_hd->waybill_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'branch_id' => session('branch')['branch_id'],

            'waybill_item_id' => $request->waybill_item_id ? $request->waybill_item_id : null,
            'waybill_item_vat_rate' => $request->waybill_item_vat_rate ? $request->waybill_item_vat_rate : null,
            'waybill_item_vat_amount' => $request->waybill_item_vat_amount ? $request->waybill_item_vat_amount : null,

//            customer
            'waybill_item_quantity' => $request->waybill_item_quantity ? $request->waybill_item_quantity : null,
            'waybill_item_unit' => $request->waybill_item_unit,
            'waybill_item_price' => $request->waybill_item_price ? $request->waybill_item_price : null,
            'waybill_item_amount' => isset($item_amount) ? $item_amount : null,
            'waybill_total_amount' => $request->waybill_sub_total_amount ? $request->waybill_sub_total_amount : null,
            'waybill_qut_requried_customer' => $request->waybill_qut_requried_customer ? $request->waybill_qut_requried_customer : null,
            'waybill_qut_received_customer' => $request->waybill_qut_received_customer ? $request->waybill_qut_received_customer : null,

//           supplier
            'waybill_price_supplier' => $request->waybill_price_supplier,
            'waybill_vat_amount_supplier' => $request->waybill_vat_amount_supplier,
            'waybill_amount_supplier' => $request->waybill_amount_supplier,
            'waybill_qut_requried_supplier' => $request->waybill_qut_requried_supplier ? $request->waybill_qut_requried_supplier : null,
            'waybill_qut_received_supplier' => $request->waybill_qut_received_supplier ? $request->waybill_qut_received_supplier : null,

            'created_user' => auth()->user()->user_id
        ]);

        $total_fees = $request->waybill_fees_load + $request->waybill_fees_wait + $request->waybill_fees_difference;

        $item_unit = SystemCode::where('system_code', 93)->where('company_group_id', $company->company_group_id)
            ->first();
        $system_code_service = SystemCode::where('system_code', 541)->where('company_group_id', $company->company_group_id)
            ->first();


        if ($request->waybill_total_fees_amount != 0) {
            $waybill_dt_fees = WaybillDt::create([
                'waybill_hd_id' => $waybill_hd->waybill_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'branch_id' => session('branch')['branch_id'],
                'waybill_item_quantity' => 1,
                'waybill_item_unit' => $item_unit->system_code_id,
                'waybill_item_price' => $total_fees,
                'waybill_item_id' => $system_code_service->system_code_id, // خدمات شحن,
                'waybill_item_vat_rate' => $request->waybill_item_vat_rate ? $request->waybill_item_vat_rate : null,

                'waybill_total_amount' => $request->waybill_total_fees_amount,
                'waybill_item_vat_amount' => $waybill_fees_vat_amount ? $waybill_fees_vat_amount : 0,
                'waybill_fees_load' => $request->waybill_fees_load ? $request->waybill_fees_load : null,
                'waybill_fees_wait' => $request->waybill_fees_wait ? $request->waybill_fees_wait : null,
                'waybill_fees_difference' => $request->waybill_fees_difference ? $request->waybill_fees_difference : null,

                'created_user' => auth()->user()->user_id
            ]);
        }

        if ($request->waybill_load_date) {
            $truck = Trucks::where('truck_id', $request->waybill_truck_id)->first();
            $truck_status = SystemCode::where('system_code', 82)->first();
            $truck->update(['truck_status' => $truck_status->system_code_id]);
        }

        if ($request->waybill_delivery_date) {
            $truck = Trucks::where('truck_id', $request->waybill_truck_id)->first();
            $truck_status = SystemCode::where('system_code', 80)->first();
            $truck->update(['truck_status' => $truck_status->system_code_id]);
        }

        $waybill_status = SystemCode::where('system_code', $request->waybill_status)->first();

        if ($waybill_status->system_code == 41004) {
            $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
                ->where('app_menu_id', 73)->latest()->first();

            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number_invoice = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number_invoice]);

            } else {
                $string_number_invoice = 'INV-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => Company::where('company_id', $request->company_id)->first()->company_group_id,
                    'company_id' => $request->company_id,
                    'app_menu_id' => 73,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number_invoice,
                    'created_user' => auth()->user()->user_id
                ]);
            }


            $invoice_hd = InvoiceHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'invoice_date' => $request->waybill_load_date,
                'invoice_due_date' => $request->waybill_delivery_date ? $request->waybill_delivery_date : null,
                'invoice_amount' => $request->waybill_total_amount,
                'invoice_vat_rate' => $waybill_dt->waybill_item_vat_rate,
                'invoice_vat_amount' => $waybill_dt->waybill_item_vat_amount + $waybill_fees_vat_amount,
                'invoice_discount_total' => 0,
                'invoice_down_payment' => 0,
                'invoice_total_payment' => 0,
                'invoice_no' => $string_number_invoice,
                'created_user' => auth()->user()->user_id,
                'branch_id' => session('branch')['branch_id'],
                'customer_id' => $request->customer_id,
                'invoice_is_payment' => 1,
                'invoice_type' => 1
            ]);

            $waybill_hd->waybill_invoice_id = $invoice_hd->invoice_id;
            $waybill_hd->save();

            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $waybill_dt->waybill_item_id,
                'waybill_item_unit' => $waybill_dt->waybill_item_unit,
                'invoice_item_quantity' => $waybill_dt->waybill_item_quantity,
                'invoice_item_price' => $waybill_dt->waybill_item_price,
                'invoice_item_amount' => isset($item_amount) ? $item_amount : null,
                'invoice_item_unit' => $request->waybill_item_unit ? $request->waybill_item_unit : null,
                'invoice_item_vat_rate' => $waybill_dt->waybill_item_vat_rate,
                'invoice_item_vat_amount' => $request->waybill_item_vat_amount,
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $request->waybill_sub_total_amount,
                'invoice_reference_no' => $waybill_hd->waybill_id,
                'created_user' => auth()->user()->user_id,
                'invoice_from_date' => Carbon::now(),
            ]);

            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $system_code_service->system_code_id, //خدمات شحن
                'invoice_item_amount' => $request->waybill_fees_load
                    + $request->waybill_fees_difference + $request->waybill_fees_wait,
                'invoice_item_unit' => $item_unit->system_code_id,
                'invoice_item_quantity' => 1,
                'invoice_item_price' => $total_fees,
                'invoice_item_vat_rate' => $waybill_dt->waybill_item_vat_rate,
                'invoice_item_vat_amount' => $waybill_fees_vat_amount,
                'invoice_total_amount' => $request->waybill_total_fees_amount,
                'invoice_reference_no' => $waybill_hd->waybill_id,
                'created_user' => auth()->user()->user_id,
                'invoice_from_date' => Carbon::now(),
            ]);

            $journal_invoice = new JournalsController();
            $total_amount = $request->waybill_total_amount;
            $customer_id = $request->customer_id;
            $cc_voucher_id = $invoice_hd->invoice_id;
            $customer_notes = 'فاتوره المبيعات رقم ' . ' ' . $invoice_hd->invoice_no . 'شركه' . ' ' . $invoice_hd->company->company_name_ar . ' ' .
                'العميل ' . ' ' . $invoice_hd->customer->customer_name_full_ar;
            $vat_notes = ' ضريبه قيمه مضافه فاتوره المبيعات رقم ' . ' ' . $invoice_hd->invoice_no;

            $sales_notes = '';

            $notes = '  قيد  فاتوره المبيعات رقم ' . ' ' . $invoice_hd->invoice_no;
            $items_id = [(int)$request->waybill_item_id, $system_code_service->system_code_id];
            $items_amount = [$request->waybill_item_quantity * $request->waybill_item_price, (float)$invoice_hd->invoiceDetails[1]
                ->invoice_item_amount];


            $journal_invoice->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id,
                $customer_notes, 73, $vat_notes, $sales_notes, 38,
                $items_id, $items_amount, $notes);


            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();
            $invoice_hd->update(['qr_data' => $qr]);

        }


        if ($request->waybill_status == 41004 || $request->waybill_status == 41003) {

            ////اضافه فاتوره مشستريات علي البوليصه
            $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
                ->where('app_menu_id', 120)->latest()->first();
            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => Company::where('company_id', $request->company_id)->first()->company_group_id,
                    'company_id' => $request->company_id,
                    'app_menu_id' => 120,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);

            }


            $invoice_hd = InvoiceHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'invoice_date' => Carbon::now(),
                'invoice_due_date' => $request->waybill_load_date,
                'invoice_amount' => $request->waybill_amount_supplier,
                'invoice_vat_rate' => $request->waybill_item_vat_rate,
                'invoice_vat_amount' => $request->waybill_vat_amount_supplier,
                'invoice_discount_total' => 0,
                'invoice_down_payment' => 0,
                'invoice_total_payment' => 0,
                'gr_number' => $request->waybill_ticket_no ? $request->waybill_ticket_no : null,
                'invoice_no' => $string_number,
                'created_user' => auth()->user()->user_id,
                'branch_id' => session('branch')['branch_id'],
                'customer_id' => $request->supplier_id,
                'acc_period_id' => $request->acc_period_id,
                'invoice_notes' => '  فاتوره مشتريات   بوليصه الشحن رقم ' . ' ' . $waybill_hd->waybill_id,
                'invoice_is_payment' => 1,
                'invoice_type' => 11,
            ]);

            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $request->waybill_item_id,
                'invoice_item_unit' => $request->waybill_item_unit,
                'invoice_item_quantity' => $request->waybill_item_quantity_supplier,
                'invoice_item_price' => $request->waybill_price_supplier,
                'invoice_item_amount' => $request->waybill_price_supplier * $request->waybill_item_quantity_supplier,
                'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                'invoice_item_vat_amount' => $request->waybill_vat_amount_supplier,
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $request->waybill_amount_supplier,
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => '  فاتوره مشتريات   بوليصه الشحن  ' . ' ' . $waybill_hd->waybill_id,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now()
            ]);

            $waybill_hd->purchase_invoice_id = $invoice_hd->invoice_id;
            $waybill_hd->save();

//        قيد المبيعات

            $journal_controller = new JournalsController();
            $total_amount = $invoice_hd->invoice_amount;
            $vat_amount = $invoice_hd->invoice_vat_amount;
            $supplier_id = $invoice_hd->customer_id;
            $purchasing_notes = '  قيد مشتريات  فاتوره  رقم' . ' ' . $invoice_hd->invoice_no;
            $vat_notes = '   ضريبه مدفوعه  فاتوره المشتريات رقم' . ' ' . $invoice_hd->invoice_no;
            $supplier_notes = '  قيد  مورد  فاتوره المشتريات رقم' . ' ' . $invoice_hd->invoice_no;
            $notes = '  قيد  مشتريات  فاتوره المشتريات رقم' . ' ' . $invoice_hd->invoice_no;
            $cost_center_id = 120;
            $cc_voucher_id = $invoice_hd->invoice_id;
            $journal_category_id = 34;

            $message = $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
                $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
                $journal_category_id, $notes);

        }


        \DB::commit();

        if (isset($message)) {
            return back()->with(['error' => $message]);
        } else {
            return redirect()->route('Waybills')->with(['success' => 'تمت الاضافه']);
        }

        //////////////
        ///


    }

    public function edit($id)
    {
        if (request()->ajax()) {
            $way_bill = WaybillHd::where('waybill_id', request()->id)->with('detailsDiesel')
                ->with('company')->first();
            $driver = $way_bill->driver;
            return response()->json(['data' => $way_bill, 'driver' => $driver]);
        }
        $waybill_id = $id;
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)->where('company_group_id', $company->company_group_id)->get();

        $way_bill = WaybillHd::where('waybill_id', $waybill_id)->first();

        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
            ->get()->whereNotIn('system_code', 41005);


        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)->get();

        if ($way_bill->status->system_code == '41001') {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->get()->whereNotIn('system_code', [41002, 41008, 41007, 41009, 41006]);
        } elseif ($way_bill->status->system_code == '41008') {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->get()->whereNotIn('system_code', [41002, 41003, 41004, 41005, 41001, 41007, 41009, 41006]);
        } elseif ($way_bill->status->system_code == '41003') {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->get()->whereNotIn('system_code', [41001, 41002, 41005, 41008, 41007, 41009, 41006]);
        } elseif ($way_bill->status->system_code == '41004') {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->get()->whereNotIn('system_code', [41001, 41002, 41003, 41005, 41007, 41009, 41006]);

        } elseif ($way_bill->status->system_code == '41005') {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->get()->whereNotIn('system_code', [41002, 41003, 41004, 41008, 41001, 41007, 41009, 41006]);
        }

        //   if (auth()->user()->user_type_id != 1) {
        //      foreach (session('job')->permissions as $job_permission) {
        //          if ($job_permission->app_menu_id == 70 && $job_permission->permission_delete) {
        //              $sys_codes_waybill_status = $sys_codes_waybill_status->whereNotIn('system_code', 41005);
        //          }
        //       }
        //   }
        $trucks = Trucks::where('company_group_id', $company->company_group_id)
            ->where('truck_status', SystemCode::where('system_code', 80)->first()->system_code_id)
            ->get();
        $suppliers = Customer::where('customer_category', 1)->where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', 485)->where('company_group_id', $company->company_group_id)->get();
        return view('Waybill.edit', compact('way_bill', 'companies', 'suppliers', 'customers', 'employees',
            'trucks', 'sys_codes_location', 'sys_codes_item', 'sys_codes_unit', 'sys_codes_waybill_status', 'waybill_id'));
    }

    public function update(Request $request, $id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $waybill_hd = WaybillHd::where('waybill_id', $id)->first();
        $supplier = Customer::where('customer_id', $request->supplier_id)->first();
        if ($request->customer_id) {
            $customer = Customer::where('customer_id', $request->customer_id)->first();
        }
        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)->where('system_code_id', $request->waybill_status)->first();

        $waybill_fees_vat_amount = ($request->waybill_item_vat_rate) * ($request->waybill_fees_load
                + $request->waybill_fees_difference + $request->waybill_fees_wait);

        \DB::begintransaction();

        if ($sys_codes_waybill_status->system_code == 41008) {
            $truck = Trucks::where('truck_id', $request->waybill_truck_id)->first();
            $truck_status = SystemCode::where('system_code', 80)->first(); ///جاهزه
            $truck->update(['truck_status' => $truck_status->system_code_id]);
        } elseif ($request->waybill_truck_id) {

            if ($request->waybill_truck_id != $waybill_hd->waybill_truck_id) {
                $new_truck = Trucks::where('truck_id', $request->waybill_truck_id)->first();

                $new_truck->update([
                    'truck_status' => SystemCode::where('system_code', 82)->first()->system_code_id ///محمله
                ]);

                $waybill_hd->truck->update([
                    'truck_status' => SystemCode::where('system_code', 80)->first()->system_code_id ///جاهزه
                ]);
            }
        }

        $waybill_hd->update([
            'supplier_id' => $request->supplier_id,
            'waybill_sender_name' => $supplier->customer_name_full_ar,
            'waybill_sender_company' => $supplier->customer_company,
            'waybill_sender_address' => $supplier->customer_address_1,
            'waybill_sender_phone' => $supplier->customer_phone,
            'waybill_sender_mobile' => $supplier->customer_mobile,
            'waybill_sender_mobile_code' => $supplier->customer_mobile_code,
            'waybill_receiver_company' => isset($customer) ? $customer->customer_company : null,
            'waybill_receiver_address' => isset($customer) ? $customer->customer_address_1 : null,
            'waybill_receiver_phone' => isset($customer) ? $customer->customer_phone : null,
            'waybill_receiver_mobile' => isset($customer) ? $customer->customer_mobile : null,
            'waybill_receiver_mobile_code' => isset($customer) ? $customer->customer_mobile_code : null,

            'waybill_status' => $sys_codes_waybill_status->system_code_id,
            'waybill_loc_from' => $request->waybill_loc_from,
            'waybill_driver_id' => $request->waybill_driver_id,
            'waybill_truck_id' => $request->waybill_truck_id ? $request->waybill_truck_id : $waybill_hd->waybill_truck_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'customer_contract' => $request->customer_contract,
            'waybill_load_date' => $request->waybill_load_date,
            'waybill_unload_date' => $request->waybill_unload_date,
            'waybill_ticket_no' => $request->waybill_ticket_no,
            'waybill_vat_rate' => $request->waybill_item_vat_rate,
            'waybill_vat_amount' => $request->waybill_item_vat_amount + $waybill_fees_vat_amount, ///customer
            'waybill_total_amount' => $request->waybill_total_amount, ///customer
            'waybill_loc_to' => $request->waybill_loc_to,
            'waybill_delivery_expected' => $request->waybill_delivery_expected,
            'updated_user' => auth()->user()->user_id,
            'waybill_fees_total' => $request->waybill_total_fees_amount,
            'waybill_delivery_user' => $request->waybill_status == 479
        ]);

        if ($request->waybill_item_quantity && $request->waybill_item_price) {
            $item_amount = $request->waybill_item_quantity * $request->waybill_item_price;
        }

        if ($request->waybill_driver_id) {
            $driver = Employee::where('emp_id', $request->waybill_driver_id)->first();
            $driver->update([
                'issueNumber' => $request->issueNumber,
                'emp_identity' => $request->emp_identity,
            ]);
        }

        if ($request->supplier_id) {
            $supplier = Customer::where('customer_id', $request->supplier_id)->first();
            $supplier->update([
                'customer_mobile' => $request->waybill_sender_mobile
            ]);
        }

        if ($request->customer_id) {
            $customer = Customer::where('customer_id', $request->customer_id)->first();
            $customer->update([
                'customer_mobile' => $request->waybill_receiver_mobile
            ]);
        }

        $waybill_dts = WaybillDt::where('waybill_hd_id', $waybill_hd->waybill_id)->get();

        $waybill_dts[0]->update([
            'branch_id' => session('branch')['branch_id'],
            'waybill_item_id' => $request->waybill_item_id,
            'waybill_item_vat_rate' => $request->waybill_item_vat_rate ? $request->waybill_item_vat_rate : null,
//            customer
            'waybill_item_quantity' => $request->waybill_item_quantity ? $request->waybill_item_quantity : null,

            'waybill_item_price' => $request->waybill_item_price ? $request->waybill_item_price : null,
            'waybill_item_amount' => isset($item_amount) ? $item_amount : null,
            'waybill_item_unit' => $request->waybill_item_unit ? $request->waybill_item_unit : null,
            'waybill_item_vat_amount' => $request->waybill_item_vat_amount ? $request->waybill_item_vat_amount : null,
            'waybill_total_amount' => $request->waybill_sub_total_amount,
            'waybill_qut_requried_customer' => $request->waybill_qut_requried_customer ? $request->waybill_qut_requried_customer : null,
            'waybill_qut_received_customer' => $request->waybill_qut_received_customer ? $request->waybill_qut_received_customer : null,

//           supplier
            'waybill_price_supplier' => $request->waybill_price_supplier,
            'waybill_vat_amount_supplier' => $request->waybill_vat_amount_supplier,
            'waybill_amount_supplier' => $request->waybill_amount_supplier,
            'waybill_qut_requried_supplier' => $request->waybill_qut_requried_supplier ? $request->waybill_qut_requried_supplier : null,
            'waybill_qut_received_supplier' => $request->waybill_qut_received_supplier ? $request->waybill_qut_received_supplier : null,
            'updated_user' => auth()->user()->user_id
        ]);

        $total_fees = $request->waybill_fees_load + $request->waybill_fees_wait + $request->waybill_fees_difference;
        $item_unit = SystemCode::where('system_code', 93)->where('company_group_id', $company->company_group_id)
            ->first();
        $system_code_service = SystemCode::where('system_code', 541)->where('company_group_id', $company->company_group_id)
            ->first();
        if (isset($waybill_dts[1])) {
            $waybill_dts[1]->update([
                'branch_id' => session('branch')['branch_id'],
                'waybill_item_quantity' => 1,
                'waybill_item_unit' => $item_unit->system_code_id,
                'waybill_item_price' => $total_fees,
                'waybill_item_vat_rate' => $request->waybill_item_vat_rate,
                'waybill_total_amount' => $request->waybill_total_fees_amount,
                'waybill_item_vat_amount' => $waybill_fees_vat_amount,
                'waybill_fees_load' => $request->waybill_fees_load,
                'waybill_fees_wait' => $request->waybill_fees_wait,
                'waybill_fees_difference' => $request->waybill_fees_difference,
                'created_user' => auth()->user()->user_id
            ]);
        } elseif ($request->waybill_total_fees_amount != 0) {
            $waybill_dt_fees = WaybillDt::create([
                'waybill_hd_id' => $waybill_hd->waybill_id,
                'company_group_id' => $waybill_hd->company_group_id,
                'company_id' => $waybill_hd->company_id,
                'branch_id' => session('branch')['branch_id'],
                'waybill_item_quantity' => 1,
                'waybill_item_unit' => $item_unit->system_code_id,
                'waybill_item_price' => $total_fees,
                'waybill_item_id' => $system_code_service->system_code_id, // خدمات شحن,
                'waybill_item_vat_rate' => $request->waybill_item_vat_rate ? $request->waybill_item_vat_rate : null,

                'waybill_total_amount' => $request->waybill_total_fees_amount,
                'waybill_item_vat_amount' => $waybill_fees_vat_amount ? $waybill_fees_vat_amount : 0,
                'waybill_fees_load' => $request->waybill_fees_load,
                'waybill_fees_wait' => $request->waybill_fees_wait,
                'waybill_fees_difference' => $request->waybill_fees_difference,
                'created_user' => auth()->user()->user_id
            ]);
        }


        if ($sys_codes_waybill_status->system_code == 41004) { ///بوليصه عميل

            $invoice_hd = InvoiceHd::where('invoice_id', $waybill_hd->waybill_invoice_id)->first();

            if (isset($invoice_hd)) {
                $invoice_hd->update([
                    'invoice_due_date' => $request->waybill_delivery_date ? $request->waybill_delivery_date : null,
                    'invoice_amount' => $request->waybill_total_amount,
                    'invoice_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_vat_amount' => $request->waybill_item_vat_amount + $waybill_fees_vat_amount,
                    'updated_user' => auth()->user()->user_id,
                    'branch_id' => session('branch')['branch_id'],
                    'customer_id' => $request->customer_id,
                    'invoice_is_payment' => 1,
                    'invoice_type' => 1
                ]);

                $invoice_dts = InvoiceDt::where('invoice_id', $invoice_hd->invoice_id)->get();

                $invoice_dts[0]->update([
                    'branch_id' => session('branch')['branch_id'],
                    'invoice_item_id' => $request->waybill_item_id,
                    'invoice_item_quantity' => $request->waybill_item_quantity,
                    'invoice_item_price' => $request->waybill_item_price,
                    'invoice_item_amount' => isset($item_amount) ? $item_amount : null,
                    'invoice_item_unit' => $request->waybill_item_unit ? $request->waybill_item_unit : null,
                    'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_item_vat_amount' => $request->waybill_item_vat_amount,
                    'invoice_total_amount' => $request->waybill_sub_total_amount,
                    'updated_user' => auth()->user()->user_id,
                ]);

                if (isset($invoice_dts[1])) {
                    $invoice_dts[1]->update([
                        'branch_id' => session('branch')['branch_id'],
                        'invoice_item_amount' => $request->waybill_fees_load
                            + $request->waybill_fees_difference + $request->waybill_fees_wait,
                        'invoice_item_price' => $total_fees,
                        'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                        'invoice_item_vat_amount' => $waybill_fees_vat_amount,
                        'invoice_total_amount' => $request->waybill_total_fees_amount,
                        'updated_user' => auth()->user()->user_id,
                        'invoice_from_date' => Carbon::now(),
                    ]);
                } else {
                    InvoiceDt::create([
                        'company_group_id' => $waybill_hd->company_group_id,
                        'company_id' => $waybill_hd->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'invoice_id' => $invoice_hd->invoice_id,
                        'invoice_item_id' => $system_code_service->system_code_id, //خدمات شحن

                        'invoice_item_amount' => $request->waybill_fees_load
                            + $request->waybill_fees_difference + $request->waybill_fees_wait,
                        'invoice_item_unit' => $item_unit->system_code_id,
                        'invoice_item_quantity' => 1,
                        'invoice_item_price' => $total_fees,
                        'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                        'invoice_item_vat_amount' => $waybill_fees_vat_amount,

                        'invoice_total_amount' => $request->waybill_total_fees_amount,
                        'invoice_reference_no' => $waybill_hd->waybill_id,
                        'created_user' => auth()->user()->user_id,
                        'invoice_from_date' => Carbon::now(),
                    ]);
                }

                $invoice_journal = new JournalsController();
                $total_amount = $invoice_hd->invoice_amount;
                $vat_amount = $invoice_hd->invoice_vat_amount;
                $cc_voucher_id = $invoice_hd->invoice_id;
                $invoice_journal->updateInvoiceJournal($total_amount, $vat_amount, 73,
                    $cc_voucher_id, $items_id = [], '');

            } else {
                $last_invoice_reference = CompanyMenuSerial::where('company_id', $waybill_hd->company_id)
                    ->where('app_menu_id', 73)->latest()->first();
                if (isset($last_invoice_reference)) {
                    $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                    $array_number = explode('-', $last_invoice_reference_number);
                    $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                    $string_number_invoice = implode('-', $array_number);
                    $last_invoice_reference->update(['serial_last_no' => $string_number_invoice]);
                } else {
                    $string_number_invoice = 'INV-' . session('branch')['branch_id'] . '-1';
                    CompanyMenuSerial::create([
                        'company_group_id' => $waybill_hd->company_group_id,
                        'company_id' => $waybill_hd->company_id,
                        'app_menu_id' => 73,
                        'acc_period_year' => Carbon::now()->format('y'),
                        'serial_last_no' => $string_number_invoice,
                        'created_user' => auth()->user()->user_id
                    ]);
                }

                $invoice_hd = InvoiceHd::create([
                    'company_group_id' => $waybill_hd->company_group_id,
                    'company_id' => $waybill_hd->company_id,
                    'invoice_date' => $request->waybill_load_date,
                    'invoice_due_date' => $request->waybill_delivery_date ? $request->waybill_delivery_date : null,
                    'invoice_amount' => $request->waybill_total_amount,
                    'invoice_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_vat_amount' => $request->waybill_item_vat_amount + $waybill_fees_vat_amount,
                    'invoice_discount_total' => 0,
                    'invoice_down_payment' => 0,
                    'invoice_total_payment' => 0,
                    'invoice_no' => $string_number_invoice,
                    'created_user' => auth()->user()->user_id,
                    'branch_id' => session('branch')['branch_id'],
                    'customer_id' => $request->customer_id,
                    'invoice_type' => 1
                ]);

                $waybill_hd->waybill_invoice_id = $invoice_hd->invoice_id;
                $waybill_hd->save();

                InvoiceDt::create([
                    'company_group_id' => $waybill_hd->company_group_id,
                    'company_id' => $waybill_hd->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'invoice_id' => $invoice_hd->invoice_id,
                    'invoice_item_id' => $request->waybill_item_id,
                    'invoice_item_quantity' => $request->waybill_item_quantity,
                    'invoice_item_price' => $request->waybill_item_price,
                    'invoice_item_amount' => isset($item_amount) ? $item_amount : null,
                    'invoice_item_unit' => $request->waybill_item_unit ? $request->waybill_item_unit : null,
                    'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_item_vat_amount' => $request->waybill_item_vat_amount,
                    'invoice_discount_type' => 1,
                    'invoice_discount_amount' => 0,
                    'invoice_discount_total' => 0,
                    'invoice_total_amount' => $request->waybill_sub_total_amount,
                    'invoice_reference_no' => $waybill_hd->waybill_id,
                    'created_user' => auth()->user()->user_id,
                    'invoice_from_date' => Carbon::now(),
                ]);

                InvoiceDt::create([
                    'company_group_id' => $waybill_hd->company_group_id,
                    'company_id' => $waybill_hd->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'invoice_id' => $invoice_hd->invoice_id,
                    'invoice_item_id' => $system_code_service->system_code_id, //خدمات شحن

                    'invoice_item_amount' => $request->waybill_fees_load
                        + $request->waybill_fees_difference + $request->waybill_fees_wait,
                    'invoice_item_unit' => $item_unit->system_code_id,
                    'invoice_item_quantity' => 1,
                    'invoice_item_price' => $total_fees,
                    'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_item_vat_amount' => $waybill_fees_vat_amount,
                    'invoice_total_amount' => $request->waybill_total_fees_amount,
                    'invoice_reference_no' => $waybill_hd->waybill_id,
                    'created_user' => auth()->user()->user_id,
                    'invoice_from_date' => Carbon::now(),
                ]);

                $journal_invoice = new JournalsController();
                $total_amount = $request->waybill_total_amount;
                $customer_id = $request->customer_id;
                $cc_voucher_id = $invoice_hd->invoice_id;
                $customer_notes = 'فاتوره المبيعات رقم ' . ' ' . $invoice_hd->invoice_no . 'شركه' . ' ' . $invoice_hd->company->company_name_ar . ' ' .
                    'العميل ' . ' ' . $invoice_hd->customer->customer_name_full_ar;
                $vat_notes = ' ضريبه قيمه مضافه فاتوره المبيعات رقم ' . ' ' . $invoice_hd->invoice_no;
                $sales_notes = '';
                $notes = '  قيد  فاتوره المبيعات رقم ' . ' ' . $invoice_hd->invoice_no;
                $items_id = [(int)$request->waybill_item_id, $system_code_service->system_code_id];
                $items_amount = [$request->waybill_item_quantity * $request->waybill_item_price, (float)$invoice_hd->invoiceDetails[1]
                    ->invoice_item_amount];


                $journal_invoice->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id,
                    $customer_notes, 73, $vat_notes, $sales_notes, 38,
                    $items_id, $items_amount, $notes);
            }


            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();
            $invoice_hd->update(['qr_data' => $qr]);

        }


        /////////////////تحديث فاتوره الشمتريات الخاصه بالمورد
        if ($sys_codes_waybill_status->system_code == 41003 || $sys_codes_waybill_status->system_code == 41004) {
            if ($waybill_hd->purchaseInvoice) {
                $waybill_hd->purchaseInvoice->update([
                    'invoice_due_date' => $request->waybill_load_date,
                    'invoice_amount' => $request->waybill_amount_supplier,
                    'invoice_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_vat_amount' => $request->waybill_vat_amount_supplier,
                    'updated_user' => auth()->user()->user_id,
                    'customer_id' => $request->supplier_id,
                ]);

                $waybill_hd->purchaseInvoice->invoiceDetail->update([
                    'invoice_item_id' => $request->waybill_item_id,
                    'invoice_item_unit' => $request->waybill_item_unit,
                    'invoice_item_quantity' => $request->waybill_item_quantity_supplier,
                    'invoice_item_price' => $request->waybill_price_supplier,
                    'invoice_item_amount' => $request->waybill_price_supplier * $request->waybill_item_quantity_supplier,
                    'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_item_vat_amount' => $request->waybill_vat_amount_supplier,
                    'invoice_total_amount' => $request->waybill_amount_supplier,
                    'updated_user' => auth()->user()->user_id,
                    'invoice_to_date' => Carbon::now()
                ]);

                $journal_controller = new JournalsController();
                $total_amount = $waybill_hd->purchaseInvoice->invoice_amount;
                $vat_amount = $waybill_hd->purchaseInvoice->invoice_vat_amount;
                $cc_voucher_id = $waybill_hd->purchaseInvoice->invoice_id;
                $journal_controller->updatePurchasingJournal($total_amount, $vat_amount,
                    $cc_voucher_id, 120);
            } else {
                $last_invoice_reference = CompanyMenuSerial::where('company_id', $waybill_hd->company_id)
                    ->where('app_menu_id', 120)->latest()->first();
                if (isset($last_invoice_reference)) {
                    $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                    $array_number = explode('-', $last_invoice_reference_number);
                    $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                    $string_number = implode('-', $array_number);
                    $last_invoice_reference->update(['serial_last_no' => $string_number]);
                } else {
                    $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                    CompanyMenuSerial::create([
                        'company_group_id' => $waybill_hd->company_group_id,
                        'company_id' => $waybill_hd->company_id,
                        'app_menu_id' => 120,
                        'acc_period_year' => Carbon::now()->format('y'),
                        'serial_last_no' => $string_number,
                        'created_user' => auth()->user()->user_id
                    ]);

                }


                $invoice_hd = InvoiceHd::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $waybill_hd->company_id,
                    'invoice_date' => Carbon::now(),
                    'invoice_due_date' => $request->waybill_load_date,
                    'invoice_amount' => $request->waybill_amount_supplier,
                    'invoice_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_vat_amount' => $request->waybill_vat_amount_supplier,
                    'invoice_discount_total' => 0,
                    'invoice_down_payment' => 0,
                    'invoice_total_payment' => 0,
                    'invoice_no' => $string_number,
                    'created_user' => auth()->user()->user_id,
                    'branch_id' => session('branch')['branch_id'],
                    'customer_id' => $request->supplier_id,
                    'acc_period_id' => $request->acc_period_id,
                    'invoice_notes' => 'تم اضافه فاتوره مشتريات للمورد علي بوليصه الشحن رقم ' . $waybill_hd->waybill_id,
                    'invoice_is_payment' => 1,
                    'invoice_type' => 11,
                ]);

                InvoiceDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'invoice_id' => $invoice_hd->invoice_id,
                    'invoice_item_id' => $request->waybill_item_id,
                    'invoice_item_unit' => $request->waybill_item_unit,
                    'invoice_item_quantity' => $request->waybill_item_quantity_supplier,
                    'invoice_item_price' => $request->waybill_price_supplier,
                    'invoice_item_amount' => $request->waybill_price_supplier * $request->waybill_item_quantity_supplier,
                    'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_item_vat_amount' => $request->waybill_vat_amount_supplier,
                    'invoice_discount_type' => 1,
                    'invoice_discount_amount' => 0,
                    'invoice_discount_total' => 0,
                    'invoice_total_amount' => $request->waybill_amount_supplier,
                    'created_user' => auth()->user()->user_id,
                    'invoice_item_notes' => 'تم اضافه فاتوره مشتريات للمورد علي بوليصه الشحن رق ' . $waybill_hd->waybill_id,
                    'invoice_from_date' => Carbon::now(),
                    'invoice_to_date' => Carbon::now()
                ]);

                $waybill_hd->purchase_invoice_id = $invoice_hd->invoice_id;
                $waybill_hd->save();

                //        قيد المشتريات
                $journal_controller = new JournalsController();
                $total_amount = $invoice_hd->invoice_amount;
                $vat_amount = $invoice_hd->invoice_vat_amount;
                $supplier_id = $invoice_hd->customer_id;
                $purchasing_notes = 'تم اضافه قيد مشتريات علي فاتوره المشتريات رقم' . $invoice_hd->invoice_no;
                $vat_notes = 'تم اضافه قيد ضريبه مدفوعه علي فاتوره المشتريات رقم' . $invoice_hd->invoice_no;
                $supplier_notes = 'تم اضافه قيد  مورد علي فاتوره المشتريات رقم' . $invoice_hd->invoice_no;
                $notes = 'تم اضافه قيد  مشتريات علي فاتوره المشتريات رقم' . $invoice_hd->invoice_no;
                $cost_center_id = 120;
                $cc_voucher_id = $invoice_hd->invoice_id;
                $journal_category_id = 34;

                $message = $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
                    $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
                    $journal_category_id, $notes);

                if (isset($message)) {
                    return back()->with(['error' => $message]);
                }
            }

        }

//////////////اغلاق البوليصه في نقل
        // return  $waybill_hd->waybillId;
        if ($waybill_hd->waybillId) {
            $naql_controller = new NaqlWayAPIController();

            if ($sys_codes_waybill_status->system_code == 41008) {
                $data = $naql_controller->closeWaybillTrip($waybill_hd);
                if ($data['statusCode'] != 200) {
                    return back()->with(['error' => 'يوجد مشكله في اغلاق البوليصه في نظام نقل']);
                }
                $waybill_hd->status_id = 3;
                $waybill_hd->save();
            }

            ////////////تحديث البوليصه في نقل
            $naql_controller->updateWaybill($waybill_hd);

        }

        \DB::commit();

        return back()->with(['success' => 'تم تحديث البوليصه']);
    }

    public function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_waybill_status_codes = SystemCode::whereIn('system_code', ['41001', '41003', '41002', '41004'])
            ->where('company_group_id', $company->company_group_id)->pluck('system_code_id')
            ->toArray();

        $way_pills = WaybillHd::where('company_id', $company->company_id)
            ->whereIn('waybill_status', $sys_codes_waybill_status_codes)
            ->where('waybill_type_id', 1)->get();

        if (request()->company_id) {

            // return explode('',json_decode());
            $query = WaybillHd::where('waybill_type_id', 1)->whereIn('company_id', request()->company_id);
            $way_pills = $query->get();

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                    ->whereDate('created_date', '<=', request()->created_date_from);
                $way_pills = $query->get();
            }

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $way_pills = $query->get();
            }

            if (request()->statuses_id) {
                $query = $query->whereIn('waybill_status', request()->statuses_id);
                $way_pills = $query->get();

            }

            if (request()->expected_date_from && request()->expected_date_to) {
                $query = $query->whereDate('waybill_delivery_expected', '>=', request()->expected_date_from)
                    ->whereDate('waybill_delivery_expected', '<=', request()->expected_date_to);
                $way_pills = $query->get();
            }

        }
        return Excel::download(new \App\Exports\WayBillExports($way_pills), 'way_bills.xlsx');
    }

    public function createTrip(Request $request)
    {
        $trip = WaybillHd::where('waybill_id', '=', $request->id)->first();

        $date_now = Carbon::now()->format('Y-m-d');

        if ($trip->waybill_load_date > $trip->waybill_delivery_expected) {
            return \Response::json(['success' => false, 'msg' => ' تاريخ التحميل  للبوليصه اكبر من تاريخ الوصول المتوقع للبوليصه']);
        }

        if ($trip->waybill_load_date < $date_now) {
            return \Response::json(['success' => false, 'msg' => ' تاريخ التحميل للبوليصه اقل التاريخ الحالي']);
        }


        if ($trip->waybill_delivery_expected < $date_now) {
            return \Response::json(['success' => false, 'msg' => ' تاريخ التوصيل المتوقع للبوليصه اقل التاريخ الحالي']);
        }

        //return
        $send_trip = NaqlWayAPIController::createTrip($trip);

        if ($send_trip['statusCode'] == 200) {
            $trip_id = $send_trip['body']->tripId;
            //  $waybills = $send_trip['body']->waybills;
            $trip->http_status = 200;
            $trip->trip_id = $trip_id;
            $trip->waybillId = $send_trip['body']->waybills[0]->waybillId;
            $trip->status_id = 1;
            $trip_update = $trip->update();

            if (!$trip_update) {
                return \Response::json(['success' => false, 'msg' => ' 1حدثت مشكلة']);
            }

            $category = SMSCategory::where('company_id', $trip->company_id)->where('sms_name_ar', 'sms delivary trip')->first();
            if (isset($category) && $category->sms_is_sms) {
                $employee = Employee::where('emp_id', $trip->waybill_driver_id)->first();
                $mobNo = '+966' . substr($employee->emp_work_mobile, 1);
                $parm1 = $trip->waybill_code;
                $file_name = 'Waybill' . $trip->trip_hd_code . '.pdf';
                $url = asset('Waybills/' . $file_name);
                $shortUrl = SMS\smsQueueController::getShortUrl($url);
                $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, null, null, null, $shortUrl);
            }

            return \Response::json(['success' => true, 'msg' => 'تم التوثيق بنجاح']);
        }

        $trip->http_status = $send_trip['statusCode'];
        $trip->status_id = 2;
        $trip_update = $trip->update();

        if (!$trip_update) {
            return \Response::json(['success' => false, 'msg' => 'لم تكتمل عملية التوثيق']);
        }

        return \Response::json(['success' => false, 'msg' => '3حدثت مشكلة']);
    }

    public function getPhoneNumber()
    {
        $data = Customer::where('customer_id', request()->customer_id)->first();
        return response()->json(['data' => $data->customer_mobile, 'vat_rate' => $data->customer_vat_rate]);
    }

    public function cancelWaybill()
    {
        $waybill = WaybillHd::where('waybill_id', request()->id)->first();
        $cancel_waybill = new NaqlWayAPIController();
        $data = $cancel_waybill->cancelWaybill($waybill);

        if ($data['statusCode'] == 200) {
            $waybill->cancel_status = 200;
            $waybill->save();
            return \Response::json(['success' => true, 'msg' => 'تم الالغاء بنجاح']);
        }

        if ($data['statusCode'] == 400) {
            return \Response::json(['error' => true, 'msg' => 'رقم البوليصه غير صحيح']);
        }
    }

    public function printWaybill()
    {
        $waybill = WaybillHd::where('waybill_id', request()->id)->first();
        $print_waybill = new NaqlWayAPIController();
        $data = $print_waybill->printWaybill($waybill);

        if ($data['statusCode'] == 200) {
//            $waybill->cancel_status = 200;
//            $waybill->save();
            $file_name = 'waybill' . $waybill->waybill_code . '.pdf';
            file_put_contents('Waybills/' . $file_name, $data);
            //return asset('Waybills/' . $file_name);
            return \Response::json(['success' => true, 'msg' => asset('Waybills/' . $file_name)]);
        }

        if ($data['statusCode'] == 400) {
            return \Response::json(['error' => true, 'msg' => 'رقم البوليصه غير صحيح']);
        }
    }

}

