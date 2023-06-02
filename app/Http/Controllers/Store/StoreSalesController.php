<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\JournalHd;
use App\Models\JournalType;
use App\Models\MaintenanceCar;
use App\Models\Note;
use App\Models\StoreDtItem;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\StoreItem;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Trucks;
use App\Models\CompanyMenuSerial;
use App\Models\MaintenanceCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\InvoiceQR\InvoiceDateElement;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StoreSalesController extends Controller
{
    //
    public function index(Request $request, $page)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = ['company' => $company, 'branch' => session('branch')];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();


        switch ($page) {
            case 'quote':
                $view = 'store.sales.quote.index';
                break;

            case 'inv':
                $view = 'store.sales.inv.index';
                break;

            case 'return':
                $view = 'store.sales.return.index';
                break;

            default:
                abort(404);
        }
        if ($page == 'inv') {
            $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
            $payemnt_method_list = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
            $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->get();
            $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
            $customer = Customer::where('company_group_id', $company->company_group_id)->get();

            $cars_list = MaintenanceCar::where('company_group_id', '=', $company->company_group_id)
                ->with('brand')->get();
            $trucks = DB::table('trucks')->where('company_group_id', $company->company_group_id)->get();

            $employees_e = Employee::where('company_group_id', $company->company_group_id)
//                ->whereHas('category', function ($q) {
//                    $q->where('system_Code', '=', 486);
//                })
                ->get();


            $employees = Employee::where('company_group_id', $company->company_group_id)
                ->whereHas('category', function ($q) {
                    $q->where('system_Code', '=', 498);
                })->get();
            $mntns_card = MaintenanceCard::where('mntns_cards_status', SystemCode::where('system_code', 50002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->get();
            $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62006')->first()->system_code_id;
            $purchase = DB::table('store_hd')->where('store_hd.company_id', $company->company_id)
                ->where('store_hd.store_vou_type', $vou_type)
                ->join('branches', 'store_hd.branch_id', 'branches.branch_id')
                ->join('system_codes as payment_method', 'payment_method.system_code_id', 'store_hd.store_vou_pay_type')
                ->join('system_codes as store_category_type', 'store_category_type.system_code_id', 'store_hd.store_category_type')
                ->join('system_codes as status', 'status.system_code_id', 'store_hd.store_vou_status')
                ->join('bonds', 'bonds.bond_code', 'store_hd.bond_code')
                ->join('customers', 'customers.customer_id', 'store_hd.store_acc_no')
                ->join('accounts', 'customers.customer_account_id', 'accounts.acc_id')
                ->join('companies_menu_report', function ($join) {
                    return $join->where('report_code', '=', 65001);
                })
                ->select('store_vou_date', 'store_acc_name', 'store_vou_ref_after', 'payment_method.system_code_name_ar as payment_method_name',
                    'branches.branch_name_ar', 'store_category_type.system_code_name_ar as store_category_type_name',
                    'status.system_code_name_ar as status_name_ar', 'status.system_code as status_system_code', 'store_vou_payment',
                    'bonds.bond_code', 'store_hd_code', 'store_vou_total', 'uuid', 'store_hd_id', 'store_hd.company_group_id'
                    , 'customers.customer_id', 'customers.customer_account_id', 'accounts.acc_code', 'accounts.acc_name_ar'
                    , 'store_hd.company_id', 'report_url', 'store_hd.journal_hd_id')->paginate();

            return view($view, compact('companies', 'branch_lits', 'page', 'warehouses_type_lits', 'user_data', 'purchase',
                'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'item_disc_type', 'customer', 'cars_list', 'trucks',
                'employees_e', 'employees', 'mntns_card'));
        } else {
            return view($view, compact('companies', 'branch_lits', 'page', 'warehouses_type_lits', 'user_data'));
        }


    }

    public function data(Request $request, $page)
    {

        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->get();

        $cars_list = MaintenanceCar::where('company_group_id', '=', $company->company_group_id)
            ->with('brand')->get();
        $trucks = Trucks::where('company_group_id', $company->company_group_id)->get();

        $employees_e = Employee::where('company_group_id', $company->company_group_id)
//            ->whereHas('category', function ($q) {
//                $q->where('system_Code', '=', 486);
//            })
            ->get();

        $employees = Employee::where('company_group_id', $company->company_group_id)
            ->whereHas('category', function ($q) {
                $q->where('system_Code', '=', 498);
            })->get();


        $mntns_card = MaintenanceCard::where('mntns_cards_status', SystemCode::where('system_code', 50002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->get();
        switch ($page) {
            case 'quote':
                $view = 'store.sales.quote.data';
                break;

            case 'inv':
                $view = 'store.sales.inv.data';
                break;

            case 'return':
                $view = 'store.sales.return.data';
                break;

            default:
                abort(404);
        }

        $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list',
            'item_disc_type', 'customer', 'employees', 'mntns_card', 'employees_e', 'cars_list', 'trucks'));

        return \Response::json(['view' => $view->render(), 'success' => true]);
    }


    public function dataTable(Request $request, $companyId, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        switch ($page) {
            case 'quote':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62005')->first()->system_code_id;
                $action_view = 'store.sales.quote.Actions.actions';
                break;

            case 'inv':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62006')->first()->system_code_id;
                $action_view = 'store.sales.inv.Actions.actions';
                break;

            case 'return':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62007')->first()->system_code_id;
                $action_view = 'store.sales.return.Actions.actions';
                break;

            default:
                abort(404);
        }


        if ($page == 'quote' || $page == 'return') {

            $purchase = Purchase::where('company_id', $company->company_id);

            if ($request->search['branch_id']) {
                info($request->search['branch_id']);
                $purchase = $purchase->where('branch_id', '=', $request->search['branch_id']);
            }
            if ($request->search['warehouses_type']) {
                $purchase = $purchase->where('company_id', $companyId)->where('store_category_type', '=', $request->search['warehouses_type']);
            }

            $purchase = $purchase->where('company_id', $companyId)->where('store_vou_type', '=', $vou_type)->orderBy('created_date', 'desc')->get();

            return Datatables::of($purchase)
                ->addIndexColumn()
                ->addColumn('branch', function ($row) {
                    return optional($row->branch)->getBranchName();

                })
                ->addColumn('store_vou_date', function ($row) {
                    return $row->created_date ? $row->created_date->format('Y-m-d H:m') : 'no date';
                })
                ->addColumn('customer', function ($row) {
                    return $row->store_acc_name;
                })
                ->addColumn('customer_mobile', function ($row) {
                    return $row->store_vou_ref_after ? $row->store_vou_ref_after : '';
                })
                ->addColumn('payment_method', function ($row) {

                    return optional($row->paymentMethod)->getSysCodeName();

                })
                ->addColumn('warahouse_type', function ($row) {

                    return $row->storeCategory->getSysCodeName();

                })
                ->addColumn('store_vou_status', function ($row) {
                    return optional($row->status)->getSysCodeName();
                })
                ->addColumn('bond', function ($row) {
                    return $row->bond ? $row->bond_code : 'لا يوجد سند';
                })
                ->addColumn('payment', function ($row) {
                    return $row->store_vou_payment ? $row->store_vou_payment : 0;
                })
                ->addColumn('action', function ($row) use ($action_view) {
                    return (string)view($action_view, compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);

        }
    }

    public function edit(Request $request, $uuid, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
        $purchase = Purchase::where('uuid', $uuid)->first();
        $itemes = Storeitem::where('company_id', $company->company_id)
            ->where('branch_id', '=', $purchase->branch_id)
            ->where('item_category', '=', $purchase->store_category_type)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->where('customer_category', '=', 9)->get();


        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();
        $system_code_types = [];

        $bonds_cash = [];
        $bonds_capture = [];

        $attachment_types = SystemCode::where('sys_category_id', 11)->get();
        $attachments = Attachment::where('transaction_id', $purchase->store_hd_id)->where('app_menu_id', 65)
            ->where('attachment_type', '!=', 2)->get();

        $notes = Note::where('transaction_id', $purchase->store_hd_id)->where('app_menu_id', 65)->get();

        $query_journals = DB::table('journal_header')
            ->join('journal_details', function ($join) use ($purchase) {
                $join->on('journal_header.journal_hd_id', '=', 'journal_details.journal_hd_id')
                    ->where('journal_details.cc_voucher_id', '=', $purchase->store_hd_id)
                    ->where('journal_details.cost_center_id', '=', 65);
            })
            ->join('system_codes', 'system_codes.system_code_id', '=', 'journal_header.journal_status')
            ->join('users', 'users.user_id', '=', 'journal_header.journal_user_entry_id')
            ->select('journal_header.journal_hd_code', 'journal_header.journal_hd_id', 'journal_header.journal_hd_date',
                'journal_header.journal_hd_notes', 'journal_header.journal_hd_debit',
                'journal_header.journal_hd_credit', 'users.user_name_ar', 'users.user_name_en', 'system_codes.system_code_name_ar'
                , 'system_codes.system_code_name_en')->get();

        $journals = $query_journals->groupBy('journal_hd_id');

        switch ($page) {
            case 'quote':
                $view = 'store.sales.quote.edit_quote';
                break;

            case 'inv':
                //       انواع الايرادات
                $system_code_types = SystemCode::where('sys_category_id', 58)
                    ->where('company_group_id', $company->company_group_id)->get();

                $view = 'store.sales.inv.edit_inv';

                $bonds_cash = Bond::where('bond_ref_no', $purchase->store_hd_code)
                    ->where('bond_type_id', 2)->latest()->get();

                $bonds_capture = Bond::where('bond_ref_no', $purchase->store_hd_code)
                    ->where('bond_type_id', 1)->latest()->get();

                break;

            case 'invnew':
                $view = 'store.sales.inv.edit_inv_new';
                break;

            case 'return':
                //       انواع المصروفات
                $system_code_types = SystemCode::where('sys_category_id', 59)
                    ->where('company_group_id', $company->company_group_id)->get();
                $view = 'store.sales.return.edit_return';

                $bonds_cash = Bond::where('bond_ref_no', $purchase->store_hd_code)
                    ->where('bond_type_id', 2)->latest()->get();

                $bonds_capture = Bond::where('bond_ref_no', $purchase->store_hd_code)
                    ->where('bond_type_id', 1)->latest()->get();
                break;

            default:
                abort(404);
        }


        return view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list',
            'payemnt_method_list', 'vendor_list', 'purchase', 'itemes', 'customer', 'system_code_types', 'journals',
            'payment_methods', 'banks', 'bonds_capture', 'bonds_cash', 'attachment_types', 'notes', 'attachments'));
    }

    public function store(Request $request, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;

        $company = Company::where('company_id', $company_id)->first();
        switch ($page) {
            case 'quote': ////عرض سعر
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62005')->first();
                return StoreSalesController::storeHeader($request, $vou_type);
                break;

            case 'invnew': ////فاتوره بيع جديده و صرف كارت صيانه
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62006')->first();
                return StoreSalesController::storenewinv($request, $vou_type);
                break;

            case 'inv': ////استيراد من عرض سعر
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62006')->first();
                return StoreSalesController::storeAll($request, $vou_type);
                break;

            case 'return': //////////مرتجع عميل
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62007')->first();
                return StoreSalesController::storeAll($request, $vou_type);
                break;

            case 'quote_from_file': ////////////////عرض سعر استيراد من ملف
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62005')->first();
                return StoreSalesController::storeFromFile($request, $vou_type);
                break;

            default:
                abort(404);
        }
    }


    public function storeHeader(Request $request, $type)
    {

        $rules = [
            'store_category_type' => 'required',
            'store_acc_no' => 'required',
            'store_acc_name' => 'required',
            'store_acc_tax_no' => 'required',
            'store_vou_pay_type' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;
        $employees = Employee::where('company_group_id', $company->company_group_id)->get();

        //gnerate mntns cards no
        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 93);
        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم كرت الصيانة يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'Qut-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
        $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id;

        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch->branch_id;

        $purchase->store_category_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', $request->store_category_type)->first()->system_code_id;;
        $purchase->store_vou_type = $type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $request->store_acc_no;
        $purchase->store_acc_name = $request->store_acc_name;
        $purchase->store_acc_tax_no = $request->store_acc_tax_no;
        $purchase->store_vou_ref_after = $request->store_vou_ref_after;
        $purchase->store_vou_pay_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', $request->store_vou_pay_type)->first()->system_code_id;
        $purchase->store_vou_notes = $request->store_vou_notes;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;
        $purchase->store_vou_ref_4 = $request->store_vou_ref_4;

        $purchase_save = $purchase->save();
        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);


    }


    public function storenewinv(Request $request, $type)
    {

        if ($request->inv_type == 'card_mnts') {
            $rules = [
                'store_category_type' => 'required',
//                'store_mntns_no' => 'required',
                //     'store_acc_name' => 'required',
                'store_mntns_tech' => 'required',
            ];
        } else {
            $rules = [
                'store_category_type' => 'required',
                'store_acc_no' => 'required',
                'store_acc_name' => 'required',
                'store_acc_tax_no' => 'required',
                'store_vou_pay_type' => 'required',

            ];
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        //gnerate mntns cards no
        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 6565);

        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم  الفاتورة يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'sales-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
        $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id;

        \DB::beginTransaction();

        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch->branch_id;
        $purchase->store_category_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', $request->store_category_type)->first()->system_code_id;
        $purchase->store_vou_type = $type->system_code_id;
        $purchase->store_hd_code = $new_serial;

        if ($request->store_mntns_no) {

            $mnts_card = MaintenanceCard::where('mntns_cards_id', $request->store_mntns_no)->first();

            $store_acc_no = $mnts_card->customer_id;
            $store_acc_name = $mnts_card->customer->customer_name_full_ar;
            $store_vou_ref_after = $mnts_card->customer->customer_mobile;
            $store_vou_pay_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', 57001)->first()->system_code_id;
            $purchase->store_vou_ref_1 = $mnts_card->mntns_cards_id;
            $purchase->store_vou_ref_2 = $request->store_mntns_tech;
            $purchase->store_vou_ref_3 = $mnts_card->car->car_cost_center;
            $purchase->store_acc_tax_no = $mnts_card->customer->customer_vat_no;
        } elseif (!$request->store_mntns_no && $request->mntns_cars_id) {
            $mnts_car = MaintenanceCar::where('mntns_cars_id', $request->mntns_cars_id)->first();
            $store_acc_no = $mnts_car->customer_id;
            $store_acc_name = $mnts_car->customer->customer_name_full_ar;
            $store_vou_ref_after = $mnts_car->customer->customer_mobile;
            $store_vou_pay_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', 57001)->first()->system_code_id;
            $purchase->store_vou_ref_1 = '';
            $purchase->store_vou_ref_2 = $request->store_mntns_tech;
            $purchase->store_vou_ref_3 = $mnts_car->car_cost_center;
            $purchase->store_acc_tax_no = $mnts_car->customer->customer_vat_no;
        } else {
            $store_acc_no = $request->store_acc_no;
            $store_acc_name = $request->store_acc_name;
            $store_vou_ref_after = $request->store_vou_ref_after;
            $store_vou_pay_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', $request->store_vou_pay_type)->first()->system_code_id;
            $purchase->store_acc_tax_no = $request->store_acc_tax_no;
        }

        $purchase->store_acc_no = $store_acc_no;
        $purchase->store_acc_name = $store_acc_name;
        $purchase->store_vou_ref_after = $store_vou_ref_after;
        $purchase->store_vou_pay_type = $store_vou_pay_type;
        $purchase->store_vou_notes = $request->store_vou_notes;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;
        $purchase->store_vou_ref_4 = $request->store_vou_ref_4 ? $request->store_vou_ref_4 : '';

        $purchase_save = $purchase->save();
        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);
    }


    public function storeItem(Request $request, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        switch ($page) {
            case 'quote':
                $header = Purchase::where('uuid', '=', $request->purchase_uuid)->first();
                return StoreSalesController::storeItemData($request, $header);
                break;

            case 'inv':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62006')->first();
                return StoreSalesController::storeAll($request, $vou_type);
                break;

            case 'invnew':
                $header = Purchase::where('uuid', '=', $request->purchase_uuid)->first();
                return StoreSalesController::storeItemnew($request, $header);
                break;
            default:
                abort(404);
        }
    }

    public function storeItemData(Request $request, $header)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $rules = [
            'purchase_uuid' => 'required',
            'item_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        if ($header->store_vou_status != SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن الاضافة الطلب مكتمل او ملغي']);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        $purchase_details = new PurchaseDetails();

        $item_data = json_decode($request->item_table_data, true);
        \DB::beginTransaction();
        $is_added_befor = PurchaseDetails::where('store_hd_id', $header->store_hd_id)->where('store_vou_item_id', '=', $item_data['store_vou_item_id'])->where('isdeleted', '=', 0);
        // if ($is_added_befor->count() > 0) {
        //     return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا الصنف مسبقا..!']);
        // }

        $purchase_details->uuid = \DB::raw('NEWID()');
        $purchase_details->store_hd_id = $header->store_hd_id;
        $purchase_details->company_group_id = $header->company_group_id;
        $purchase_details->company_id = $header->company_id;
        $purchase_details->branch_id = $header->branch_id;

        $purchase_details->store_category_type = $header->store_category_type;
        $purchase_details->store_vou_type = $header->store_vou_type;
        $purchase_details->store_vou_date = Carbon::now();
        $purchase_details->created_user = auth()->user()->user_id;
        $purchase_details->store_acc_no = $header->store_acc_no;

        $purchase_details->store_vou_item_id = $item_data['store_vou_item_id'];
        $purchase_details->store_vou_qnt_q = $item_data['store_vou_qnt_o'];
        $purchase_details->store_vou_loc = $item_data['store_vou_loc'];

        $purchase_details->store_vou_disc_amount = $item_data['store_vou_disc_amount'];
        $purchase_details->store_voue_disc_value = $item_data['store_voue_disc_value'];
        $purchase_details->store_vou_disc_type = $item_data['store_vou_disc_type'];

        $purchase_details->store_vou_item_price_cost = $item_data['store_vou_item_price_cost'];
        $purchase_details->store_vou_item_price_sales = $item_data['store_vou_item_price_sales'];
        $purchase_details->store_vou_item_price_unit = $item_data['store_vou_item_price_unit'];
        $purchase_details->store_vou_item_total_price = $item_data['store_vou_item_total_price'];
        $purchase_details->store_vou_vat_rate = $item_data['store_vou_vat_rate'];
        $purchase_details->store_vou_vat_amount = $item_data['store_vou_vat_amount'];
        $purchase_details->store_vou_price_net = $item_data['store_vou_price_net'];
        $purchase_details_save = $purchase_details->save();

        if (!$purchase_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = StoreSalesController::updateHeaderTotal($purchase_details->purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $purchase_details->purchase->itemSumTotal(),
            'total_sum_vat' => $purchase_details->purchase->itemSumVat(),
            'total_sum_net' => $purchase_details->purchase->itemSumNet(),
        ];


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase_details->refresh()->uuid, 'total' => $total]);

    }


    public function storeItemnew(Request $request, $header)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $rules = [
            'purchase_uuid' => 'required',
            'item_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        $qty_field = 'store_vou_qnt_o';

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        if ($header->store_vou_status != SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن الاضافة الطلب مكتمل او ملغي']);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        $purchase_details = new PurchaseDetails();

        $item_data = json_decode($request->item_table_data, true);

        $is_added_befor = PurchaseDetails::where('store_hd_id', $header->store_hd_id)->where('store_vou_item_id', '=', $item_data['store_vou_item_id'])->where('isdeleted', '=', 0);
        // if ($is_added_befor->count() > 0) {
        //     return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا الصنف مسبقا..!']);
        // }
        \DB::beginTransaction();

        $purchase_details->uuid = \DB::raw('NEWID()');
        $purchase_details->store_hd_id = $header->store_hd_id;
        $purchase_details->company_group_id = $header->company_group_id;
        $purchase_details->company_id = $header->company_id;
        $purchase_details->branch_id = $header->branch_id;

        $purchase_details->store_category_type = $header->store_category_type;
        $purchase_details->store_vou_type = $header->store_vou_type;
        $purchase_details->store_vou_date = Carbon::now();
        $purchase_details->created_user = auth()->user()->user_id;
        $purchase_details->store_acc_no = $header->store_acc_no;

        $purchase_details->store_vou_item_id = $item_data['store_vou_item_id'];
        $purchase_details->store_vou_qnt_o = $item_data['store_vou_qnt_o'];

        $purchase_details->store_vou_loc = $item_data['store_vou_loc'];
        $purchase_details->store_vou_item_price_cost = $item_data['store_vou_item_price_cost'];
        $purchase_details->store_vou_item_price_sales = $item_data['store_vou_item_price_sales'];
        $purchase_details->store_vou_item_price_unit = $item_data['store_vou_item_price_unit'];
        $purchase_details->store_vou_item_total_price = $item_data['store_vou_item_total_price'];
        $purchase_details->store_vou_vat_rate = $item_data['store_vou_vat_rate'];
        $purchase_details->store_vou_vat_amount = $item_data['store_vou_vat_amount'];

        $purchase_details->store_vou_disc_type = $item_data['store_vou_disc_type'];
        $purchase_details->store_voue_disc_value = $item_data['store_voue_disc_value'];
        $purchase_details->store_vou_disc_amount = $item_data['store_vou_disc_amount'];

        $purchase_details->store_vou_price_net = $item_data['store_vou_price_net'];
        $purchase_details_save = $purchase_details->save();

        if (count($item_data['item_stor_dt_serial']) > 0) {
            foreach ($item_data['item_stor_dt_serial'] as $item_stor_dt_serial) {

                if ($item_stor_dt_serial != null) {
                    $item = StoreDtItem::where('store_dt_item_id', $item_stor_dt_serial)->first();
                    $item->update([
                        'stor_vou_qut_out' => 1,
                        'store_inv_id' => $header->store_hd_id
                    ]);
                }
            }
        }

        if (count($item_data['item_stor_dt_serial_n']) > 0) {
            foreach ($item_data['item_stor_dt_serial_n'] as $item_stor_dt_serial_n) {
                if ($item_stor_dt_serial_n != null) {
                    StoreDtItem::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'store_hd_id' => $header->store_hd_id,
                        'store_dt_id' => $purchase_details->store_dt_id,
                        'item_id_dt' => $item_data['store_vou_item_id'],
                        'store_vou_code' => $header->store_hd_code,
                        'stor_vou_date' => Carbon::now(),
                        'stor_vou_qut_in' => 1,
                        'stor_vou_qut_out' => 1,
                        'item_stor_dt_serial' => $item_stor_dt_serial_n,
                        'created_by' => auth()->user()->user_id,
                        'store_inv_id' => $header->store_hd_id
                    ]);
                }
            }
        }


        if ($header->mntsCard) {
            $header->mntsCard->mntns_cards_vat_amount = $header->mntsCard->mntns_cards_vat_amount +
                $purchase_details->store_vou_vat_amount;

            $header->mntsCard->mntns_cards_total_amount = $header->mntsCard->mntns_cards_total_amount +
                $purchase_details->store_vou_price_net;

            $header->mntsCard->mntns_cards_due_amount = $header->mntsCard->mntns_cards_due_amount +
                $purchase_details->store_vou_price_net;

            $header->mntsCard->save();

        }

        if (!$purchase_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $store_item = StoreItem::where('item_id', $item_data['store_vou_item_id'])->first();
        // if ($store_item->item_balance < $item_data['store_vou_qnt_o']) {
        //     return \Response::json(['success' => false, 'msg' => 'الكمية الحالية غير كافية']);
        // }

        $store_item->item_balance = $store_item->item_balance - $item_data['store_vou_qnt_o'];
        $store_item->last_price_sales = $store_item->item_price_sales;
        // $store_item->item_price_sales = (floatval($item_data['store_vou_item_price_unit'])) / 2;
        $store_item->updated_user = auth()->user()->user_id;
        $store_item->updated_date = Carbon::now();

        $store_item_save = $store_item->save();

        if (!$store_item_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }


        $update_total = StoreSalesController::updateHeaderTotal($purchase_details->purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $purchase_details->purchase->itemSumTotal(),
            'total_sum_vat' => $purchase_details->purchase->itemSumVat(),
            'total_sum_net' => $purchase_details->purchase->itemSumNet(),
        ];


        ///////////journalقيد فاتوره مبيعات
//        $journal = new JournalsController();
//        $total_amount = $purchase_details->purchase->itemSumNet();
//        $cc_voucher_id = $purchase_details->purchase->store_hd_id;
//        $sales_notes = '   ايراد فاتوره مبيعات  رقم' . $header->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
//        if ($purchase_details->purchase->journalHd) {
//            $vat_amount = $purchase_details->purchase->itemSumVat();
//            $journal->updateInvoiceJournal($total_amount, $vat_amount,
//                65, $cc_voucher_id, $items_id = [], $sales_notes);
//        } else {
//            $customer_id = $purchase_details->purchase->store_acc_no;
//            $customer_notes = '   فاتوره مبيعات رقم' . ' ' . $purchase_details->purchase->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
//            $vat_notes = '   ضريبه قيمه مضافه  مبيعات رقم' . ' ' . $purchase_details->purchase->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
//            $notes = '   قيد فاتوره مبيعات  رقم' . ' ' . ' ' . $purchase_details->purchase->store_acc_name;
//            $sales_notes = '   ايراد فاتوره مبيعات رقم' . ' ' . $purchase_details->purchase->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
//            $message = $journal->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
//                65, $vat_notes, $sales_notes, 41, $items_id = [], $items_amount = [], $notes);
//
//            if ($message) {
//                return $message;
//            }
//        }

        /////////////////سند فاتوره بيع جديده
        if ($purchase_details->purchase->paymentMethod) {
            if ($purchase_details->purchase->paymentMethod->system_code == 54001) {

                $bond_controller = new BondsController();
                $payment_method = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', 57001)->first();
                $transaction_type = 65;
                $transaction_id = $purchase_details->purchase->store_hd_id;
                $customer_id = $purchase_details->purchase->store_acc_no;
                $total_amount = $purchase_details->purchase->store_vou_total;
                $bond_doc_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', 58001)->first(); ////ايرادات مبيعات
                $bond_ref_no = $purchase_details->purchase->store_hd_code;
                $bond_notes = '  سند قبض فاتوره مبيعات رقم' . ' ' . $purchase_details->purchase->store_hd_code;


                $bond_journal = new JournalsController();
                if ($purchase_details->purchase->Bond) {
                    $bond = $bond_controller->updateBond($total_amount, $transaction_id);
                    $cc_voucher_id = $bond->bond_id;
                    $cost_center_id = 53;
                    $bond_journal->updateCaptureJournal($total_amount, $cc_voucher_id, $cost_center_id);
                } else {
                    $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id,
                        $customer_id, 'customer', '', $total_amount, $bond_doc_type, $bond_ref_no,
                        $bond_notes);

                    $cc_voucher_id = $bond->bond_id;
                    $journal_category_id = 6;
                    $cost_center_id = 53;
                    $account_type = 56002;
                    $journal_notes = '   سداد مبيعات  سند قبض  ' . ' ' . $bond->bond_code . ' فاتوره  رقم' . ' ' . $purchase_details->purchase->store_hd_code;
                    $customer_notes = '  سداد عميل  سند رقم' . ' ' . $bond->bond_code . ' فاتوره  رقم' . ' ' . $purchase_details->purchase->store_hd_code;
                    $sales_notes = '  سداد فاتوره مبيعات  سند رقم' . ' ' . $bond->bond_code . ' فاتوره  رقم' . ' ' . $purchase_details->purchase->store_hd_code;
                    $message = $bond_journal->AddCaptureJournal($account_type, $customer_id, $bond_doc_type->system_code, $total_amount,
                        $cc_voucher_id, $payment_method, $bank_id = '', $journal_category_id,
                        $cost_center_id, $journal_notes, $customer_notes, $sales_notes);

                    if (isset($message)) {
                        return \Response::json(['success' => false, 'msg' => $message]);
                    }

                }

            }
        }
        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase_details->refresh()->uuid, 'total' => $total]);


    }


    public function storeAll(Request $request, $type)
    {
        $rules = [
            'store_vou_ref_before' => 'required|exists:store_hd,uuid',
            'item_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $store_vou_ref_before = Purchase::where('uuid', '=', $request->store_vou_ref_before)->first();
        $branch_id = $store_vou_ref_before->branch_id;
        $company = $store_vou_ref_before->company;
        //gnerate code


        switch ($type->system_code) {
            case '62006':

                $qty_field = 'store_vou_qnt_o';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 65);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم المبيعات يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'S-INV-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125002')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125002')->first()->system_code_id;
                break;

            case '62007':

                $qty_field = 'store_vou_qnt_o_r';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('app_menu_id', 94);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم المبيعات يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'SR-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125002')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125003')->first()->system_code_id;
                break;

            default:
                abort(404);
        }


        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch_id;

        $purchase->store_category_type = $store_vou_ref_before->store_category_type;
        info($type->system_code_id);

        $purchase->store_vou_type = $type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $store_vou_ref_before->store_acc_no;
        $purchase->store_acc_name = $store_vou_ref_before->store_acc_name;
        $purchase->store_acc_tax_no = $store_vou_ref_before->store_acc_tax_no;
        $purchase->store_vou_pay_type = $store_vou_ref_before->store_vou_pay_type;
        $purchase->store_vou_notes = $store_vou_ref_before->store_vou_notes;
        $purchase->store_vou_ref_before = $store_vou_ref_before->store_hd_code;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;

        $purchase_save = $purchase->save();

        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);

        //store item

        $item_data = json_decode($request->item_data, true);

        $purcahse_details = new PurchaseDetails();
        if (count($item_data) > 0) {

            $item_data_set = [];

            foreach ($item_data as $i => $d) {

                $item = PurchaseDetails::where('uuid', '=', $d['uuid'])->first();
                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $purchase->store_hd_id,
                    'company_group_id' => $store_vou_ref_before->company_group_id,
                    'company_id' => $store_vou_ref_before->company_id,
                    'branch_id' => $store_vou_ref_before->branch_id,

                    'store_category_type' => $store_vou_ref_before->store_category_type,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $store_vou_ref_before->store_acc_no,

                    'store_vou_item_id' => $item->store_vou_item_id,
                    $qty_field => floatval($d[$qty_field]),
                    'store_vou_loc' => $item->store_vou_loc,
                    'store_vou_item_price_cost' => floatval($item->store_vou_item_price_cost),
                    'store_vou_item_price_unit' => floatval($d['store_vou_item_price_unit']),
                    'store_vou_item_total_price' => floatval($d['store_vou_item_total_price']),

                    'store_vou_disc_type' => $d['store_vou_disc_type'],
                    'store_voue_disc_value' => floatval($d['store_voue_disc_value']),
                    'store_vou_disc_amount' => floatval($d['store_vou_disc_amount']),

                    'store_vou_vat_rate' => floatval($d['store_vou_vat_rate']),
                    'store_vou_vat_amount' => floatval($d['store_vou_vat_amount']),
                    'store_vou_price_net' => floatval($d['store_vou_price_net']),

                ];

                $item->store_vou_qnt_t_i_r = $item->store_vou_qnt_t_i_r + $d[$qty_field];
                $item_save = $item->save();

                if (!$item_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                //check in befor request need to close
                $update_status = PurchaseController::checkStatus($store_vou_ref_before);
                if ($update_status->getData()->success) {
                    $store_vou_ref_before->store_vou_status = $store_vou_ref_before_status;
                    $store_vou_ref_before_save = $store_vou_ref_before->save();

                    if (!$store_vou_ref_before_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                }

                //update item details when type equle enter Receipt
                if ($type->system_code == '62006') {
                    $store_item = StoreItem::where('item_id', $item->store_vou_item_id)->first();
                    // if ($store_item->item_balance < $d[$qty_field]) {
                    //     return \Response::json(['success' => false, 'msg' => 'الكمية الحالية غير كافية']);
                    // }
                    $store_item->item_balance = $store_item->item_balance - $d[$qty_field];
                    $store_item->last_price_sales = $store_item->item_price_sales;
                    //  $store_item->item_price_sales = (floatval($d['store_vou_item_price_unit'])) / 2;
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                } elseif ($type->system_code == '62007') {

                    $store_item = StoreItem::where('item_id', $item->store_vou_item_id)->first();
                    $store_item->item_balance = $store_item->item_balance + $d[$qty_field];
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }


                }


            }

            $purcahse_details_save = $purcahse_details->insert($item_data_set);

            if (!$purcahse_details_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }

        }

        $update_total = StoreSalesController::updateHeaderTotal($purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);

    }


    public function updateHeaderTotal($header)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        $header = $header;
        $header->store_vou_amount = $header->itemSumTotal();
        $header->store_vou_desc = $header->itemSumDisc();
        $header->store_vou_vat_amount = $header->itemSumVat();
        $header->store_vou_total = $header->itemSumNet();

        //  return $header->itemSumNet();

        if ($header->store_vou_type == SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62006')->first()->system_code_id) {
            $header->qr_data = QRDataGenerator::fromArray([
                new SellerNameElement($header->company->companyGroup->company_group_ar),
                new TaxNoElement($header->company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($header->itemSumNet()),
                new TaxAmountElement($header->itemSumVat())
            ])->toBase64();

        }

        $header_save = $header->save();

        if (!$header_save) {
            return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
        }

        $header->refresh();

//        استيراد من عرض سعر
        if ($header->storeVouType->system_code == 62006 && $header->store_vou_ref_before) {////
            ///////////journalقيد فاتوره مبيعات

//            $header->storeVouType->system_code == 62006  استيراد من عرض  سعر
            $journal = new JournalsController();
            $total_amount = $header->store_vou_total;
            $cc_voucher_id = $header->store_hd_id;

            $sales_notes = '   ايراد فاتوره مبيعات  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;

            if (isset($header->journal_hd_id)) {
                $vat_amount = $header->store_vou_vat_amount;
                $journal->updateInvoiceJournal($total_amount, $vat_amount,
                    65, $cc_voucher_id, $items_id = [], $sales_notes);
            } else {
                $customer_id = $header->store_acc_no;
                $customer_notes = ' ايراد فاتوره مبيعات  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $vat_notes = ' ضريبه قيمه مضافه مبيعات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $notes = ' قيد فاتوره مبيعات  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $sales_notes = ' ايراد فاتوره مبيعات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $journal->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
                    65, $vat_notes, $sales_notes, 41, $items_id = [], $items_amount = [], $notes);
            }


            $journal_type_2 = JournalType::where('company_group_id', $header->company_group_id)
                ->where('journal_types_code', 61)->first();

            foreach ($header->details as $purchase_detail) {
                $cost[] = $purchase_detail->store_vou_item_price_cost * $purchase_detail->store_vou_qnt_o;
            }

            $total_cost_amount = array_sum($cost);

            if (isset($journal_type_2)) {
                if ($header->journalHd2) {
                    $journal->updateStoreJournalsInvoice($cc_voucher_id, $total_cost_amount);
                } else {
                    $cost_notes = ' تكلفه بضائع  فاتورة' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $journal->addStoreJournalsInvoice(65, $cc_voucher_id, $total_cost_amount,
                        $journal_type_2->journal_types_id, $notes, $cost_notes);
                }
            }
        }

        if ($header->storeVouType->system_code == 62007) { ////مرتجع عميل
            $journal_controller = new JournalsController();
            $total_amount = $header->store_vou_total;
            $customer_id = $header->store_acc_no;
            $cc_voucher_id = $header->store_hd_id;
            $customer_notes = '   مرتجع مبيعات   رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $cost_center_id = 94; ////مرتجع مبيعات
            $vat_notes = '   ضريبه قيمه مضافه مرتجع مبيعات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $sales_notes = '   مرتجع مبيعات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $journal_category_id = 48; //مرتجع مبيعات مستودعات

            $notes = '  قيد مرتجع مبيعات  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $items_id = [];
            $items_amount = [];

            $message = $journal_controller->addSalesInvoiceJournal($total_amount, $customer_id, $cc_voucher_id,
                $customer_notes, $cost_center_id, $vat_notes, $sales_notes, $journal_category_id, $items_id,
                $items_amount, $notes);


            $journal_type_2 = JournalType::where('company_group_id', $header->company_group_id)
                ->where('journal_types_code', 61)->first();


            foreach ($header->details as $purchase_detail) {
                $cost[] = $purchase_detail->store_vou_item_price_cost * $purchase_detail->store_vou_qnt_o_r;
            }

            $total_cost_amount = array_sum($cost);


            if (isset($journal_type_2)) {
                if ($header->journalHd2) {
                    $journal_controller->updateStoreJournalsSales($cc_voucher_id, $total_cost_amount);
                } else {
                    $cost_notes = '  تكلفه المخزون مرتجع عميل' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $journal_controller->addStoreJournalsSales(65, $cc_voucher_id, $total_cost_amount,
                        $journal_type_2->journal_types_code, $notes, $cost_notes);
                }
            }

        }

        return ['success' => true, 'msg' => 'تمت العملية  بنجاح'];
    }

    public
    function deleteItem(Request $request)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        info($request->uuid);;
        $rules = [
            'uuid' => 'required|exists:store_dt,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $purchase_details = PurchaseDetails::where('uuid', '=', $request->uuid)->first();
        if ($purchase_details->purchase->store_vou_status != SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن الحذف الطلب مكتمل او ملغي']);
        }

        $purchase_items = StoreDtItem::where('store_inv_id', $purchase_details->purchase->store_hd_id)
            ->where('item_id_dt', $purchase_details->store_vou_item_id)->get();

        if ($purchase_items->count() > 0) {
            return \Response::json(['success' => false, 'msg' => 'لا يمكن الحذف . تم اضافه عناصر تفصيليه']);
        }

        $purchase_details->isdeleted = 1;
        $purchase_details_save = $purchase_details->save();

        if (!$purchase_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        if ($purchase_details->storeVouType->system_code == '62006') {
            $store_item = StoreItem::where('item_id', $purchase_details->store_vou_item_id)->first();
            $store_item->item_balance = $store_item->item_balance + $purchase_details->store_vou_qnt_o;
            $store_item->updated_user = auth()->user()->user_id;
            $store_item->updated_date = Carbon::now();
            $store_item_save = $store_item->save();

            if (!$store_item_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }
        }


        $update_total = StoreSalesController::updateHeaderTotal($purchase_details->purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $purchase_details->purchase->itemSumTotal(),
            'total_sum_vat' => $purchase_details->purchase->itemSumVat(),
            'total_sum_net' => $purchase_details->purchase->itemSumNet(),
        ];

        ///////////journalقيد فاتوره مبيعات
        $journal = new JournalsController();
        $total_amount = $purchase_details->purchase->itemSumNet();
        $cc_voucher_id = $purchase_details->purchase->store_hd_id;
        $sales_notes = '   ايراد فاتوره مبيعات  رقم' . $purchase_details->purchase->store_hd_code;
        if ($purchase_details->purchase->journalHd) {
            $vat_amount = $purchase_details->purchase->itemSumVat();
            $journal->updateInvoiceJournal($total_amount, $vat_amount,
                65, $cc_voucher_id, $items_id = [], $sales_notes);
        }

        if ($purchase_details->purchase->Bond) {
            $bond_controller = new BondsController();
            $transaction_id = $cc_voucher_id;
            $bond_controller->updateBond($total_amount, $transaction_id);
        }


        return \Response::json(['success' => true, 'msg' => 'تمت الحذف بنجاح', 'data' => $purchase_details, 'total' => $total]);

    }

    public
    function generate(Request $request)
    {

        $rules = [
            'uuid' => 'required|exists:store_hd,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }
        \DB::beginTransaction();
        $purchase = Purchase::where('uuid', '=', $request->uuid)->first();

        if ($purchase->details->count() == 0) {
            return \Response::json(['success' => false, 'msg' => 'لايوجد اي اصناف لايمكن تصدير الفاتورة']);
        }
        $current_serial = CompanyMenuSerial::where('company_id', $purchase->company_id)
            ->where('app_menu_id', 65);
        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم  الفاتورة يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'S-INV-' . $purchase->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
        $store_vou_status = SystemCode::where('company_group_id', $purchase->company_group_id)->where('system_code', '=', '125002')->first()->system_code_id;

        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_hd_code = $new_serial;
        $purchase_save = $purchase->save();

        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);
        $purchase->refresh();

        if ($purchase->storeVouType->system_code == 62006) {////
            ///////////journalقيد فاتوره مبيعات

//            $header->storeVouType->system_code == 62006 فاتوره بيع استيراد من عرض  سعر
//            + فاتوره بيع جديده
            $journal = new JournalsController();
            $total_amount = $purchase->store_vou_total;
            $cc_voucher_id = $purchase->store_hd_id;

            $sales_notes = '   ايراد فاتوره مبيعات  رقم' . ' ' . $purchase->store_hd_code . ' ' . $purchase->store_acc_name;

            if (isset($header->journal_hd_id)) {
                $vat_amount = $purchase->store_vou_vat_amount;
                $journal->updateInvoiceJournal($total_amount, $vat_amount,
                    65, $cc_voucher_id, $items_id = [], $sales_notes);
            } else {
                $customer_id = $purchase->store_acc_no;
                $customer_notes = ' ايراد فاتوره مبيعات  رقم' . ' ' . $purchase->store_hd_code . ' ' . $purchase->store_acc_name;
                $vat_notes = ' ضريبه قيمه مضافه مبيعات رقم' . ' ' . $purchase->store_hd_code . ' ' . $purchase->store_acc_name;
                $notes = ' قيد فاتوره مبيعات  رقم' . ' ' . $purchase->store_hd_code . ' ' . $purchase->store_acc_name;
                $sales_notes = ' ايراد فاتوره مبيعات رقم' . ' ' . $purchase->store_hd_code . ' ' . $purchase->store_acc_name;
                $journal->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
                    65, $vat_notes, $sales_notes, 41, $items_id = [], $items_amount = [], $notes);
            }


            $journal_type_2 = JournalType::where('company_group_id', $purchase->company_group_id)
                ->where('journal_types_code', 61)->first();

            foreach ($purchase->details as $purchase_detail) {
                $cost[] = $purchase_detail->store_vou_item_price_cost * $purchase_detail->store_vou_qnt_o;
            }

            $total_cost_amount = array_sum($cost);

            if (isset($journal_type_2)) {
                if ($purchase->journalHd2) {
                    $journal->updateStoreJournalsInvoice($cc_voucher_id, $total_cost_amount);
                } else {
                    $cost_notes = 'قيد تكلفه المخزون';
                    $journal->addStoreJournalsInvoice(65, $cc_voucher_id, $total_cost_amount,
                        $journal_type_2->journal_types_id, $notes, $cost_notes);
                }
            }
        }

        \DB::commit();

        return \Response::json(['success' => true, 'msg' => 'تمت الحذف بنجاح']);

    }

    public
    function getDataFromFile(Request $request)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $customer = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 9)->get();

        $file = $request->file('file_quote');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $row_limit = $sheet->getHighestDataRow();
        $column_limit = $sheet->getHighestDataColumn();
        $row_range = range(2, $row_limit);
        $column_range = range('F', $column_limit);
        $row_count = 2;
        $data = array();
        foreach ($row_range as $row) {
            $item = StoreItem::where('item_code', $sheet->getCell('A' . $row));
            if ($item->count()) {
                $item = $item->first();
                $store_vou_item_total_price = $sheet->getCell('B' . $row)->getValue() * $item->item_price_sales;
                $store_vou_vat_amount = $store_vou_item_total_price * 15 / 100;
                $data[] = [
                    'uuid' => $item->uuid,
                    'store_vou_item_id' => $item->item_id,
                    'store_vou_item_code' => $item->item_code,
                    'store_vou_item_name' => $item->item_name_a,
                    'qty' => $sheet->getCell('B' . $row)->getValue(),
                    'store_vou_item_price_unit' => $item->item_price_sales,
                    'store_vou_item_total_price' => $store_vou_item_total_price,
                    'store_vou_disc_amount' => 0,
                    'store_vou_vat_amount' => $store_vou_vat_amount,
                    'store_vou_price_net' => $store_vou_item_total_price + $store_vou_vat_amount,
                    'item_balance' => $item->item_balance,
                    'item_price_cost' => $item->item_price_cost,
                    'last_price_cost' => $item->last_price_cost,
                ];
            } else {

                $data[] = [
                    'uuid' => uniqid(),
                    'store_vou_item_id' => 0,
                    'store_vou_item_code' => $sheet->getCell('A' . $row)->getValue(),
                    'store_vou_item_name' => 'NA',
                    'store_vou_item_total_price' => 0,
                    'store_vou_disc_amount' => 0,
                    'store_vou_vat_amount' => 0,
                    'store_vou_price_net' => 0,
                    'qty' => $sheet->getCell('B' . $row)->getValue(),
                    'store_vou_item_price_unit' => 0,
                    'item_balance' => 0,
                    'item_price_cost' => 0,
                    'last_price_cost' => 0,
                ];
            }
            $row_count++;
        }

        $view = 'store.sales.quote.show_data';
        $msg = 'تم استرداد الملف بنجاح';
        $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'data', 'item_disc_type', 'customer'));
        return response()->json(['success' => true, 'msg' => $msg, 'view' => $view->render()]);

    }

    public
    function storeFromFile(Request $request, $type)
    {
        $rules = [
            'store_category_type_f' => 'required',
            'store_acc_no_f' => 'required',
            'store_acc_name_f' => 'required',
            'store_acc_tax_no_f' => 'required',
            'store_vou_pay_type_f' => 'required',
            'item_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }
        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;
        //gnerate mntns cards no
        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 93);
        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم كرت الصيانة يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'Qut-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
        $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id;

        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch->branch_id;

        $purchase->store_category_type = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', $request->store_category_type_f)->first()->system_code_id;;
        $purchase->store_vou_type = $type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $request->store_acc_no_f;
        $purchase->store_acc_name = $request->store_acc_name_f;
        $purchase->store_acc_tax_no = $request->store_acc_tax_no_f;
        $purchase->store_vou_pay_type = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', $request->store_vou_pay_type_f)->first()->system_code_id;
        $purchase->store_vou_notes = $request->store_vou_notes_f;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;

        $purchase_save = $purchase->save();
        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        //store item
        $item_data = json_decode($request->item_data, true);
        $purcahse_details = new PurchaseDetails();
        if (count($item_data) > 0) {

            $item_data_set = [];

            foreach ($item_data as $i => $d) {
                if ($d['store_vou_item_id'] != 0) {
                    $item = StoreItem::where('uuid', '=', $d['uuid'])->first();
                } else {

                    $item = StoreItem::where('item_id', '=', 0)->first();
                }

                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $purchase->store_hd_id,
                    'company_group_id' => $purchase->company_group_id,
                    'company_id' => $purchase->company_id,
                    'branch_id' => $purchase->branch_id,

                    'store_category_type' => $purchase->store_category_type,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $purchase->store_acc_no,

                    'store_vou_item_id' => $item->item_id,
                    'store_vou_item_code' => $d['store_vou_item_code'],
                    'store_vou_qnt_q' => $d['store_vou_qnt_i'],
                    'store_vou_loc' => $item->store_vou_loc,
                    'store_vou_item_price_cost' => floatval($item->store_vou_item_price_cost),
                    'store_vou_item_price_unit' => floatval($d['store_vou_item_price_unit']),
                    'store_vou_item_total_price' => floatval($d['store_vou_item_total_price']),

                    'store_vou_disc_type' => $d['store_vou_disc_type'],
                    'store_voue_disc_value' => floatval($d['store_voue_disc_value']),
                    'store_vou_disc_amount' => floatval($d['store_vou_disc_amount']),

                    'store_vou_vat_rate' => floatval($d['store_vou_vat_rate']),
                    'store_vou_vat_amount' => floatval($d['store_vou_vat_amount']),
                    'store_vou_price_net' => floatval($d['store_vou_price_net']),

                ];

            }

            $purcahse_details_save = $purcahse_details->insert($item_data_set);

            if (!$purcahse_details_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }

        }

        $update_total = StoreSalesController::updateHeaderTotal($purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);

    }

//سند صرف
    public
    function addBondWithJournal(Request $request)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();
        // return $request->all();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', $request->bond_method_type)->first();
        $transaction_type = 94;
        $transaction_id = $request->transaction_id;
        $purchase = Purchase::find($request->transaction_id);
        $customer_id = $purchase->store_acc_no;
        $customer_type = $request->customer_type;
        $j_add_date = Carbon::now();
        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';
        $bond_vat_amount = $request->bond_vat_amount ? $request->bond_vat_amount : 0;
        $bond_vat_rate = $request->bond_vat_rate ? $request->bond_vat_rate : 0;
        $total_amount = $request->bond_amount_total;
        $bond_doc_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code_id', $request->bond_doc_type)->first();
        $bond_ref_no = $request->bond_ref_no;

        $bond_notes = $request->bond_notes ? $request->bond_notes : '';
        $bond_account_id = $request->bond_acc_id;

        $bond = $bond_controller->addCashBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id,
            $bond_vat_amount, $bond_vat_rate, '', $j_add_date);


        $journal_controller = new JournalsController();
        $cost_center_id = 54;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
        $journal_category_id = 14;

        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = '  سند صرف ' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $purchase->customer->customer_name_full_ar;
        $customer_notes = ' سند صرف' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $purchase->customer->customer_name_full_ar;
        $cash_notes = '  سند صرف' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $purchase->customer->customer_name_full_ar;
        $message = $journal_controller->AddCashJournal(56002, $customer_id, $bond_doc_type,
            $total_amount, 0, $cc_voucher_id, $payment_method, $bank_id,
            $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);

        if (isset($message)) {
            return back()->with(['error' => $message]);
        }


        $purchase->update([
            'bond_id' => $bond->bond_id,
            'bond_code' => $bond->bond_code,
            'bond_date' => Carbon::now(),
            'store_vou_payment' => $purchase->store_vou_payment + $total_amount
        ]);


        \DB::commit();

        return back()->with(['success' => 'تم اضافه السند']);

    }

///سند قبض
    public
    function addBondWithJournal2(Request $request)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();
        // return $request->all();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', $request->bond_method_type)->first();
        $transaction_type = 65;
        $transaction_id = $request->transaction_id;
        $purchase = Purchase::find($request->transaction_id);
        $customer_id = $purchase->store_acc_no;
        $customer_type = $request->customer_type;
        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';

        $total_amount = $request->bond_amount_credit;
        $bond_doc_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code_id', $request->bond_doc_type)->first();
        $bond_ref_no = $request->bond_ref_no;
        $bond_notes = $request->bond_notes ? $request->bond_notes : '';
        $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

//
        $journal_controller = new JournalsController();
        $cost_center_id = 53;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
        $journal_category_id = 6;

        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = ' سند قبض ' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $purchase->customer->customer_name_full_ar;
        $customer_notes = '  سند قبض  ' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $purchase->customer->customer_name_full_ar;
        $cash_notes = '  سند قبض' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $purchase->customer->customer_name_full_ar;
        $message = $journal_controller->AddCaptureJournal(56002, $customer_id, $bond_doc_type,
            $total_amount, $cc_voucher_id, $payment_method, $bank_id,
            $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes);

        if (isset($message)) {
            return back()->with(['error' => $message]);
        }

        $purchase->update([
            'bond_id' => $bond->bond_id,
            'bond_code' => $bond->bond_code,
            'bond_date' => Carbon::now(),
            'store_vou_payment' => $purchase->store_vou_payment + $request->bond_amount_credit
        ]);


        \DB::commit();

        return back()->with(['success' => 'تم اضافه السند']);

    }


    public function storeBond(Request $request)
    {

        // return $request->all();
        $bond = Bond::find($request->bond_id);
        $purchase = Purchase::find($request->purchase_id);

        $bond->bond_ref_no = $purchase->store_hd_code;
        $bond->transaction_type = 65;
        $bond->transaction_id = $purchase->store_hd_id;
        $bond->bond_acc_id = $purchase->customer->customer_account_id;
        $bond->save();

        $purchase->store_vou_payment = $purchase->store_vou_payment + $bond->bond_amount_debit;
        $purchase->bond_id = $bond->bond_id;
        $purchase->bond_code = $bond->bond_code;
        $purchase->bond_date = $bond->bond_date;
        $purchase->save();
        return back()->with(['success' => 'تم ربط السند']);
    }


    public function update(Request $request)
    {

        $data = json_decode($request->item_table_data);

        $purchase = Purchase::where('uuid', $request->purchase_uuid)->first();

        $purchase->store_acc_name = $request->store_acc_name;
        $purchase->store_acc_tax_no = $request->store_acc_tax_no;
        $purchase->store_vou_pay_type = $request->store_vou_pay_type;
        $purchase->store_vou_ref_after = $request->store_vou_ref_after;
        $purchase->vou_datetime = $request->vou_datetime;
        $purchase->store_vou_notes = $request->store_vou_notes;
        // $purchase->store_vou_vat_rate = $data->store_vou_vat_rate;
        $purchase->store_vou_vat_amount = $data->store_vou_vat_amount;
        $purchase->store_vou_total = $data->store_vou_price_net;
        $purchase->store_vou_desc = $data->store_vou_desc;
        $purchase->created_date = $request->created_date;
        $purchase->save();

        if ($purchase->journal_hd_id) {
            $journal_controller = new JournalsController();
            $vat_amount = $purchase->store_vou_vat_amount;
            $total_amount = $purchase->store_vou_total;
            $cc_voucher_id = $purchase->store_hd_id;

            if ($purchase->storeVouType->system_code == 62006) { ///فاتوره البيع
                $journal_controller->updateInvoiceJournal($total_amount, $vat_amount,
                    65, $cc_voucher_id, $items_id = [], '');
                return route('store-sales-inv.edit', $purchase->uuid);
            }

            if ($purchase->storeVouType->system_code == 62007) { ///مرتجع العميل
                $journal_controller->updateSalesInvoiceJournal($cc_voucher_id, $total_amount, $vat_amount);
                return route('store-sales-return.edit', $purchase->uuid);
            }
        } else {
            $url = \Request::url();
            $contains = Str::contains($url, 'store-sales');
            if ($contains) {
                return route('store-sales-inv.edit', $purchase->uuid);
            } else {
                return route('store-sales-return.edit', $purchase->uuid);
            }

        }

    }


    public function getItems()
    {

        $items = DB::table('store_dt_item')
            ->whereColumn('store_dt_item.stor_vou_qut_in', '!=', 'store_dt_item.stor_vou_qut_out')
            ->where('store_dt_item.item_id_dt', '=', request()->item_id)
            ->get();

        return response()->json(['data' => $items]);
    }


    public
    function approveAllInvoices(Request $request)
    {

        foreach ($request->store_hd_id as $store_hd_id) {
            $message =$this->externalStoreSalesInvJournal($store_hd_id);
        }

        if (isset($message)) {
            return back()->with('error', $message);
        }
        return back()->with('تم اضافه القيد');

    }

    public
    function approveOneInvoice(Request $request)
    {
        DB::beginTransaction();

        $message = $this->externalStoreSalesInvJournal($request->store_hd_id);

        DB::commit();

        if (isset($message)) {
            return back()->with('error', $message);
        }
        return back()->with('تم اضافه القيد');

    }


    public function externalStoreSalesInvJournal($cc_voucher_id)
    {
        $purchase = Purchase::find($cc_voucher_id);


        $mntns = MaintenanceCard::where('mntns_cards_no', $purchase->store_acc_tax_no)
            ->where('company_group_id', $purchase->company_group_id)
            ->first();

        if (isset($mntns)) {
            $cc_car_id = $mntns->mntns_cars_id;
            $journal_controller = new JournalsController();
            $journal_category_id = 70;
            $amount_total = $purchase->store_vou_total;
            $journal_notes = 'قيد اذن صرف رقم ' . $purchase->store_hd_code;
            $j_add_date = Carbon::now();
            $cost_center_id = 65;
            $car_notes = 'قيد اذن صرف سياره' . $purchase->store_hd_code;
            $branch_notes = 'قيد اذن صرف فرع' . $purchase->store_hd_code;
            $journal_controller->externalStoreSalesInv($cc_voucher_id, $journal_category_id,
                $amount_total, $journal_notes, $j_add_date,
                $cost_center_id, $car_notes, $branch_notes, $cc_car_id);
        } else {
            return 'لا يوجد كارت صيانه';
        }


    }


}
