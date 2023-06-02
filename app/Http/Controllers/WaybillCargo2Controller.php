<?php

namespace App\Http\Controllers;

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
use App\Models\Trucks;
use App\Models\Reports;
use App\Models\WaybillDt;
use App\Models\Attachment;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Customer;
use App\Models\User;
use App\Models\WaybillHd;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WaybillCargo2Controller extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        //// waybill - cargo
        $waybill_status_ids = SystemCode::whereIn('system_code', ['41001', '41004'])
            ->where('company_group_id', $company->company_group_id)->pluck('system_code_id')->toArray();
        $trucks = Trucks::where('company_group_id', $company->company_group_id)
        ->where('truck_type', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code_search','=', 'cargo')->first()->system_code_id)->get();

        $way_pills = WaybillHd::where('company_id', $company->company_id)->where('waybill_type_id', 2)
            ->whereIn('waybill_status', $waybill_status_ids)->sortable()->paginate();

        $data = request()->all();
        $sys_codes_waybill_status = SystemCode::whereIn('system_code', ['41001', '41004', '41005'])->where('company_group_id', $company->company_group_id)
            ->get();
        $customers = Customer::whereIn('customer_category', [3])->where('company_group_id', $company->company_group_id)->get();

        $user_id = WaybillHd::where('company_id', $company->company_id)->where('waybill_type_id', 2)
            ->pluck('waybill_create_user')->toArray();

        $employees = User::whereIn('user_id', array_unique($user_id))->get();

        if (request()->company_id) {
            $query = WaybillHd::whereIn('company_id', request()->company_id)->where('waybill_type_id', 2)
                ->whereIn('waybill_status', $waybill_status_ids);

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('waybill_load_date', '>=', request()->created_date_from)
                    ->whereDate('waybill_load_date', '<=', request()->created_date_to);
            }
            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
            }

            if (request()->employee_id) {
                $query = $query->whereIn('created_user', request()->employee_id);
            }

            if (request()->trucks_id) {
                $query = $query->whereIn('waybill_truck_id', request()->trucks_id);
            }

            if (request()->statuses_id) {
                $query = $query->whereIn('waybill_status', request()->statuses_id);
            }

            if (request()->waybill_waybill_no) {
                $query = $query->where('waybill_code', 'like', '%' . request()->waybill_waybill_no . '%');
            }
            if (request()->waybill_ref) {
                $query = $query->where('waybill_ticket_no', 'like', '%' . request()->waybill_ref . '%');
            }

            $way_pills = $query->paginate();

        }

        $waybill_profit_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '88005')->get();


        $waybill_cargo_daily_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '88006')->get();

        $waybill_cargo_customer_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '88007')->get();

        $waybill_cargo_truck_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '88008')->get();


        $total = array_sum($way_pills->pluck('waybill_total_amount')->toArray());
        $total_vat = array_sum($way_pills->pluck('waybill_vat_amount')->toArray());

        $query0 = $way_pills->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41004)->first()->system_code_id)
            ->count();//بوليصه عميل

        $query1 = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 41001)->first()
            ->system_code_id)->where('company_id', $company->company_id)
            ->where('waybill_type_id', 2)
            ->count(); ///امر تحميل

        $query2 = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 41003)->first()
            ->system_code_id)->where('company_id', $company->company_id)
            ->where('waybill_type_id', 2)
            ->count(); /// فاتورة شراء 

        $query3 = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 41008)->first()
            ->system_code_id)->where('company_id', $company->company_id)
            ->where('waybill_type_id', 2)
            ->count(); /// تم التسليم

        $total_waybills = DB::table('waybill_hd')
            ->where('company_id', $company->company_id)
            ->where('waybill_type_id', 2)->count();

        $query0_p = $total_waybills > 0 ? number_format(($query0 / $total_waybills) * 100, 2) : 0;
        $query1_p = $total_waybills > 0 ? number_format(($query1 / $total_waybills) * 100, 2) : 0;
        $query2_p = $total_waybills > 0 ? number_format(($query2 / $total_waybills) * 100, 2) : 0;
        $query3_p = $total_waybills > 0 ? number_format(($query3 / $total_waybills) * 100, 2) : 0;


        return view('Waybill.Cagro.index_cargo_2', compact('companies', 'way_pills', 'customers', 'employees',
            'waybill_cargo_daily_report', 'waybill_cargo_customer_report', 'waybill_cargo_truck_report', 'trucks', 'waybill_profit_report',
            'sys_codes_waybill_status', 'total', 'total_vat', 'data', 'query0', 'query1', 'query2', 'query3',
            'query0_p', 'query1_p', 'query2_p', 'query3_p', 'company'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)->where('system_code_url', 'cargo')->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', ['41001', '41004'])->get();
        $customers = Customer::where('customer_category', 3)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 485)->first()->system_code_id)->where('company_group_id', $company->company_group_id)->get();

        $trucks = Trucks::where('truck_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 80)->first()->system_code_id)->where('company_group_id', $company->company_group_id)
            ->where('truck_type', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code_search','=', 'cargo')->first()->system_code_id)->get();
        $current_date = Carbon::now()->format('Y-m-d\TH:i');
        return view('Waybill.Cagro.create_cargo_2', compact('customers', 'employees', 'current_date',
            'sys_codes_location', 'sys_codes_item', 'sys_codes_unit', 'sys_codes_waybill_status', 'company', 'trucks'));
    }

    public function createrent()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', ['41001', '41004'])->get();
        $customers = Customer::where('customer_category', 3)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 485)->first()->system_code_id)->where('company_group_id', $company->company_group_id)->get();

        $trucks = Trucks::where('company_group_id', $company->company_group_id)->get();
        $current_date = Carbon::now()->format('Y-m-d\TH:i');
        return view('Waybill.Cagro.create_cargo_rent', compact('customers', 'employees', 'current_date',
            'sys_codes_location', 'sys_codes_item', 'sys_codes_unit', 'sys_codes_waybill_status', 'company', 'trucks'));
    }

    public function store(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $last_waypill_serial = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 90)->latest()->first();

        if (isset($last_waypill_serial)) {
            $last_waypill_serial_no = $last_waypill_serial->serial_last_no;
            $array_number = explode('-', $last_waypill_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_waypill_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'WAY-CARGO2-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 90,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $customer = Customer::where('customer_id', request()->customer_id)->first();
        $sys_codes_waybill_status_id = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)
            ->where('system_code', request()->waybill_status)->first();

        $loc_from_names = SystemCode::whereIn('system_code_id', $request->waybill_loc_from)
            ->pluck('system_code_name_ar')->toArray();


        $loc_to_names = SystemCode::whereIn('system_code_id', $request->waybill_loc_to)
            ->pluck('system_code_name_ar')->toArray();

        $waybill_hd = WaybillHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'waybill_code' => $string_number,
            'waybill_type_id' => 2,
            'waybill_receiver_company' => $customer->customer_company,
            'waybill_receiver_address' => $customer->customer_address_1,
            'waybill_receiver_phone' => $customer->customer_phone,
            'waybill_receiver_mobile' => $customer->customer_mobile,
            'waybill_receiver_mobile_code' => $customer->customer_mobile_code,
            'waybill_status' => $sys_codes_waybill_status_id->system_code_id,
            'waybill_loc_from' => json_encode($request->waybill_loc_from),
            'waybill_loc_from_name' => json_encode($loc_from_names),
            'waybill_loc_to_name' => json_encode($loc_to_names),
            'waybill_sender_city' => $request->waybill_sender_city,
            'waybill_sender_mobile' => $customer->customer_mobile,
            'waybill_sender_name' => $customer->customer_name_full_ar,
            'waybill_receiver_city' => $request->waybill_receiver_city,
            'waybill_driver_id' => $request->waybill_driver_id,
            'waybill_truck_id' => $request->waybill_truck_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'waybill_ticket_no' => $request->waybill_ticket_no,
            'customer_contract' => $request->customer_contract ? $request->customer_contract : null,
            'waybill_load_date' => $request->waybill_load_date,
            'waybill_vat_rate' => $request->waybill_item_vat_rate,
            'waybill_vat_amount' => $request->waybill_item_vat_amount, ///customer
            ///
            'waybill_total_amount' => $request->waybill_total_amount, ///customer
            'waybill_add_amount' => $request->waybill_add_amount, ///customer
            //  'waybill_loc_to' => json_encode($request->waybill_loc_to),
            'waybill_fees_total' => $request->waybill_fees_wait + $request->waybill_fees_difference,
            'waybill_loc_to' => json_encode($request->waybill_loc_to),
            'waybill_delivery_expected' => $request->waybill_delivery_expected ? $request->waybill_delivery_expected : null,
            'created_user' => auth()->user()->user_id,
            'waybill_create_user' => auth()->user()->user_id,
        ]);

        $waybill_dt = WaybillDt::create([
            'waybill_hd_id' => $waybill_hd->waybill_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'waybill_item_unit' => $request->waybill_item_unit,
            'waybill_item_id' => $request->waybill_item_id,
            'waybill_item_vat_rate' => $request->waybill_item_vat_rate,
            'waybill_fees_difference' => $request->waybill_fees_difference,
            'waybill_fees_wait' => $request->waybill_fees_wait,
            'waybill_fees_load' => $request->waybill_fees_load,
            'waybill_add_amount' => $request->waybill_add_amount,
//            customer
            'waybill_item_quantity' => $request->waybill_item_quantity,
            'waybill_item_price' => $request->waybill_item_price,
            'waybill_item_vat_amount' => $request->waybill_item_vat_amount,
            'waybill_item_amount' => ($request->waybill_item_quantity * $request->waybill_item_price) + $request->waybill_add_amount,
            'waybill_total_amount' => $request->waybill_total_amount,
            'waybill_qut_requried_customer' => $request->waybill_qut_requried_customer,
            'waybill_qut_received_customer' => $request->waybill_qut_received_customer ? $request->waybill_qut_received_customer : null,
            'created_user' => auth()->user()->user_id
        ]);

        $customer_system_code_id = Customer::where('customer_id', $request->customer_id)->first()->customer_type;
        $customer_system_code = SystemCode::where('system_code_id', $customer_system_code_id)->first();
        if ($request->waybill_status == 41004 && $customer_system_code->system_code == 10001) {
            $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
                ->where('app_menu_id', 73)->latest()->first();

            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number_invoice = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number_invoice]);
            } else {
                $string_number_invoice = 'INV-' . session('branch')['branch'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 73,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number_invoice,
                    'created_user' => auth()->user()->user_id
                ]);

            }
            $invoice_hd = InvoiceHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'invoice_date' => Carbon::now(),
                'invoice_due_date' => $request->waybill_delivery_date ? $request->waybill_delivery_date : null,
                'invoice_amount' => $waybill_dt->waybill_total_amount,
                'invoice_vat_rate' => $waybill_dt->waybill_item_vat_rate,
                'invoice_vat_amount' => $waybill_dt->waybill_item_vat_amount,
                'invoice_discount_total' => 0,
                'invoice_down_payment' => 0,
                'invoice_total_payment' => 0,
                'invoice_no' => $string_number_invoice,
                'created_user' => auth()->user()->user_id,
                'branch_id' => session('branch')['branch_id'],
                'customer_id' => $request->customer_id,
                'invoice_is_payment' => 1
            ]);

            $waybill_hd->waybill_invoice_id = $invoice_hd->invoice_id;
            $waybill_hd->save();

            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $waybill_dt->waybill_item_id,
                'invoice_item_quantity' => $waybill_dt->waybill_item_quantity,
                'invoice_item_unit' => $waybill_dt->waybill_item_unit,

                'invoice_item_price' => $waybill_dt->waybill_item_price,
                'invoice_item_amount' => ($waybill_dt->waybill_item_quantity * $waybill_dt->waybill_item_price) + $waybill_dt->waybill_add_amount,
                'invoice_item_vat_rate' => $waybill_dt->waybill_item_vat_rate,
                'invoice_item_vat_amount' => $waybill_dt->waybill_item_vat_amount,
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $waybill_dt->waybill_total_amount,
                'invoice_reference_no' => $waybill_hd->waybill_id,
                'created_user' => auth()->user()->user_id,
                'invoice_from_date' => Carbon::now(),
            ]);

            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();
            $invoice_hd->update(['qr_data' => $qr]);
        }

        return redirect()->route('WaybillsCargo2')->with(['success' => 'تمت الاضافه']);
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $waybill_hd = WaybillHd::find($id);
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)->where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 485)->first()->system_code_id)->where('company_group_id', $company->company_group_id)->get();
        $waybill_status_system_code = SystemCode::where('system_code_id', $waybill_hd->waybill_status)->first();
        if ($waybill_status_system_code->system_code == 41001) {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->whereIn('system_code', ['41001', '41004', '41005'])->get();
        } elseif ($waybill_hd->waybill_invoice_id > '0') {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->whereIn('system_code', ['41004'])->get();
        } elseif ($waybill_status_system_code->system_code == 41004) {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
                ->where('company_group_id', $company->company_group_id)->whereIn('system_code', ['41004', '41005'])->get();

        }


        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 90 && $job_permission->permission_delete) {
                    $sys_codes_waybill_status = $sys_codes_waybill_status->whereIn('system_code', ['41001', '41004', '41005']);
                }
            }
        }
        $trucks = Trucks::where('company_group_id', $company->company_group_id)->get();

        $attachment_types = SystemCode::where('sys_category_id', 11)->get();
        $attachments = Attachment::where('transaction_id', $waybill_hd->waybill_id)->where('app_menu_id', 90)->get();
        $notes = Note::where('transaction_id', $waybill_hd->waybill_id)->where('app_menu_id', 90)->get();

        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        return view('Waybill.Cagro.edit_cargo_2', compact('company', 'employees',
            'sys_codes_location', 'sys_codes_waybill_status', 'waybill_hd', 'trucks', 'sys_codes_unit'
            , 'waybill_status_system_code', 'attachment_types', 'notes', 'attachments'));
    }

    public function update(Request $request, $id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_waybill_status_id = SystemCode::where('sys_category_id', 41)
            ->where('system_code', request()->waybill_status)
            ->where('company_group_id', $company->company_group_id)->first();

        $waybill_hd = WaybillHd::find($id);

        if ($sys_codes_waybill_status_id->system_code == 41005) {
            $waybill_hd->update([
                'waybill_status' => $sys_codes_waybill_status_id->system_code_id]);

            return redirect()->route('WaybillsCargo2')->with(['success' => 'تم التعديل']);
        }

        if ($request->waybill_loc_to) {
            $loc_to_names = SystemCode::whereIn('system_code_id', $request->waybill_loc_to)
                ->pluck('system_code_name_ar')->toArray();
        }

        $old_waybill_status = SystemCode::where('system_code_id', $waybill_hd->waybill_status)->first();

        if ($old_waybill_status->system_code != 41004) {
            $customer_system_code_id = Customer::where('customer_id', $waybill_hd->customer_id)->first()->customer_type;
            $customer_system_code = SystemCode::where('system_code_id', $customer_system_code_id)->first();
            if ($request->waybill_status == 41004 && $customer_system_code->system_code == 10001) {
                $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
                    ->where('app_menu_id', 73)->latest()->first();

                if (isset($last_invoice_reference)) {
                    $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                    $array_number = explode('-', $last_invoice_reference_number);
                    $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                    $string_number_invoice = implode('-', $array_number);
                    $last_invoice_reference->update(['serial_last_no' => $string_number_invoice]);
                } else {
                    $string_number_invoice = 'INV-' . session('branch')['branch'] . '-1';
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
                    'invoice_date' => Carbon::now(),
                    'invoice_no' => $string_number_invoice,
                    'invoice_due_date' => $request->waybill_delivery_date ? $request->waybill_delivery_date : null,
                    'invoice_amount' => $waybill_hd->waybill_total_amount,
                    'invoice_vat_rate' => $waybill_hd->details->waybill_item_vat_rate,
                    'invoice_vat_amount' => $waybill_hd->details->waybill_item_vat_amount,
                    'created_user' => auth()->user()->user_id,
                    'branch_id' => session('branch')['branch_id'],
                    'customer_id' => $waybill_hd->customer_id,
                    'invoice_is_payment' => 1,
                    'invoice_number' => $string_number_invoice
                ]);

                $waybill_hd->waybill_invoice_id = $invoice_hd->invoice_id;
                $waybill_hd->save();

                InvoiceDt::create([
                    'company_group_id' => $waybill_hd->company_group_id,
                    'company_id' => $waybill_hd->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'invoice_id' => $invoice_hd->invoice_id,
                    'invoice_item_id' => $waybill_hd->details->waybill_item_id,
                    'invoice_item_quantity' => $waybill_hd->details->waybill_item_quantity,
                    'invoice_item_unit' => $waybill_hd->details->waybill_item_unit,

                    'invoice_item_price' => $waybill_hd->details->waybill_item_price,
                    'invoice_item_amount' => ($request->waybill_item_quantity * $request->waybill_item_price)
                        + $request->waybill_add_amount,
                    'invoice_item_vat_rate' => $request->waybill_item_vat_rate,
                    'invoice_item_vat_amount' => $request->waybill_item_vat_amount,
                    'invoice_total_amount' => $request->waybill_total_amount,
                    'invoice_reference_no' => $waybill_hd->waybill_id,
                    'created_user' => auth()->user()->user_id,
                    'invoice_from_date' => Carbon::now(),
                ]);

                $qr = QRDataGenerator::fromArray([
                    new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                    new TaxNoElement($company->company_tax_no),
                    new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                    new TotalAmountElement($invoice_hd->invoice_amount),
                    new TaxAmountElement($invoice_hd->invoice_vat_amount)
                ])->toBase64();
                $invoice_hd->update(['qr_data' => $qr]);
            }
        }

        $waybill_hd->update([
            'waybill_status' => $sys_codes_waybill_status_id->system_code_id,
            'waybill_driver_id' => $request->waybill_driver_id,
            'waybill_truck_id' => $request->waybill_truck_id,
            'branch_id' => session('branch')['branch_id'],
            'waybill_ticket_no' => $request->waybill_ticket_no,
            'customer_contract' => $request->customer_contract ? $request->customer_contract : null,
            'waybill_load_date' => $request->waybill_load_date,
            'waybill_vat_rate' => $request->waybill_item_vat_rate,
            'waybill_vat_amount' => $request->waybill_item_vat_amount, ///customer
            'waybill_sender_city' => $request->waybill_sender_city,
            'waybill_receiver_city' => $request->waybill_receiver_city,
            'waybill_total_amount' => $request->waybill_total_amount, ///customer
            'waybill_add_amount' => $request->waybill_add_amount, ///customer
            //  'waybill_loc_to' => json_encode($request->waybill_loc_to),
            'waybill_fees_total' => $request->waybill_fees_wait + $request->waybill_fees_difference,
            'waybill_loc_to' => $request->waybill_loc_to ? json_encode($request->waybill_loc_to) : $waybill_hd->waybill_loc_to,
            'waybill_loc_to_name' => isset($loc_to_names) ? json_encode($loc_to_names) : $waybill_hd->waybill_loc_to_name,
            'waybill_delivery_expected' => $request->waybill_delivery_expected ? $request->waybill_delivery_expected : null,
            'updated_user' => auth()->user()->user_id,
            'waybill_create_user' => auth()->user()->user_id,
        ]);

        $waybill_hd->details->update([
            'branch_id' => session('branch')['branch_id'],
            'waybill_item_vat_rate' => $request->waybill_item_vat_rate,
            'waybill_fees_difference' => $request->waybill_fees_difference,
            'waybill_fees_wait' => $request->waybill_fees_wait,
            'waybill_add_amount' => $request->waybill_add_amount,
//            customer
            'waybill_item_quantity' => $request->waybill_item_quantity,
            'waybill_item_price' => $request->waybill_item_price,
            // 'waybill_item_unit' => $request->waybill_item_unit,
            'waybill_item_vat_amount' => $request->waybill_item_vat_amount,
            'waybill_item_amount' => $request->waybill_sub_total_amount,
            'waybill_total_amount' => $request->waybill_total_amount,
            'waybill_qut_requried_customer' => $request->waybill_qut_requried_customer,
            'waybill_qut_received_customer' => $request->waybill_qut_received_customer ? $request->waybill_qut_received_customer : null,
            'updated_user' => auth()->user()->user_id
        ]);

        return redirect()->route('WaybillsCargo2')->with(['success' => 'تم التعديل']);
    }

    public function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        //// waybill - cargo
        $waybill_status_ids = SystemCode::whereIn('system_code', ['41001', '41004'])->pluck('system_code_id')->toArray();
        $way_pills = WaybillHd::where('company_id', $company->company_id)->where('waybill_type_id', 2)
            ->whereIn('waybill_status', $waybill_status_ids)->get();

        if (request()->company_id) {
            $query = WaybillHd::whereIn('company_id', request()->company_id)->where('waybill_type_id', 2);
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

        return Excel::download(new \App\Exports\WayBillCargo2Exports($way_pills), 'way_bills_cargo2.xlsx');
    }

    public function deletecargo2($id)
    {
        $waybill_cargo = WaybillHd::find($id);
        $waybill_cargo->details()->delete();


        $waybill_cargo->delete();
        return back()->with(['success' => 'تم الحذف']);
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

       
        $send_trip = NaqlWayAPIController::createTrip($trip);
      // return $send_trip ;
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

            // $category = SMSCategory::where('company_id', $trip->company_id)->where('sms_name_ar', 'sms delivary trip')->first();
            // if (isset($category) && $category->sms_is_sms) {
            //     $employee = Employee::where('emp_id', $trip->waybill_driver_id)->first();
            //     $mobNo = '+966' . substr($employee->emp_work_mobile, 1);
            //     $parm1 = $trip->waybill_code;
            //     $file_name = 'Waybill' . $trip->trip_hd_code . '.pdf';
            //     $url = asset('Waybills/' . $file_name);
            //     $shortUrl = SMS\smsQueueController::getShortUrl($url);
            //     $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, null, null, null, $shortUrl);
            // }

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
        } elseif ($data['statusCode'] == 400) {
            return \Response::json(['error' => true, 'msg' => 'رقم البوليصه غير صحيح']);
        } else {
            return \Response::json(['error' => true, 'msg' => 'حدث خطا']);
        }
    }

}
