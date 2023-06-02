<?php

namespace App\Http\Controllers\SalesCar;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Models\Bond;
use App\Models\JournalType;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CompanyMenuSerial;
use App\Models\Sales;
use App\Models\SalesDetails;
use App\Models\SalesCar;
use App\Models\CarRentBrandDt;
use App\Models\CarRentBrand;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

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


class SalesCarController extends Controller
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
            case 'request':
                $view = 'salesCar.purchase.request.index';
                break;

            case 'order':
                $view = 'salesCar.purchase.order.index';
                break;

            case 'receiving':
                $view = 'salesCar.purchase.receiving.index';
                break;

            case 'quote':
                $view = 'salesCar.sales.quote.index';
                break;

            case 'inv':
                $view = 'salesCar.sales.inv.index';
                break;

            case 'return':
                $view = 'salesCar.return.index';
                break;

            default:
                abort(404);
        }

        return view($view, compact('companies', 'branch_lits', 'page', 'warehouses_type_lits', 'user_data'));
    }

    public function data(Request $request, $page)
    {

        //return request()->search['warehouses_type'];
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->where('customer_category', '=', 2)->get();
        $car_brand = CarRentBrand::where('company_id', $company->company_id)->get();

        switch ($page) {
            case 'request':

                $view = 'salesCar.purchase.request.data';
                break;

            case 'order':
                $view = 'salesCar.purchase.order.data';
                break;

            case 'receiving':
                $view = 'salesCar.purchase.receiving.data';
                break;

            case 'quote':
                $view = 'salesCar.sales.quote.data';
                break;

            case 'inv':
                $view = 'salesCar.sales.inv.data';
                break;

            case 'return':
                $view = 'salesCar.return.data';
                break;

            default:
                abort(404);
        }

        $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'item_disc_type', 'car_brand', 'customer'));
        return \Response::json(['view' => $view->render(), 'success' => true]);
    }


    public function dataTable(Request $request, $companyId, $page)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        switch ($page) {
            case 'request':
                $vou_type = SystemCode::where('system_code', '=', '104001')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                $action_view = 'salesCar.purchase.request.Actions.actions';
                $row_type = 'vendor';
                break;

            case 'order':
                $vou_type = SystemCode::where('system_code', '=', '104002')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                $action_view = 'salesCar.purchase.order.Actions.actions';
                $row_type = 'vendor';
                break;

            case 'receiving':
                $vou_type = SystemCode::whereIn('system_code', ['104003', '104009'])
                    ->where('company_group_id', $company->company_group_id)->get()->pluck('system_code_id');
                $action_view = 'salesCar.purchase.receiving.Actions.actions';
                $row_type = 'vendor';
                break;

            case 'quote':
                $vou_type = SystemCode::where('system_code', '=', '104004')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                $action_view = 'salesCar.sales.quote.Actions.actions';
                $row_type = 'customer';
                break;

            case 'inv':
                $vou_type = SystemCode::where('system_code', '=', '104005')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                $action_view = 'salesCar.sales.inv.Actions.actions';
                $row_type = 'customer';
                break;

            case 'return':
                $vou_type = SystemCode::whereIn('system_code', ['104006', '104007'])
                    ->where('company_group_id', $company->company_group_id)->pluck('system_code_id');
                $action_view = 'salesCar.return.Actions.actions';
                $row_type = 'vendor';
                break;

            default:
                abort(404);
        }


        $sales = Sales::where('company_id', $companyId)->whereIn('store_vou_type', (array)$vou_type)->orderBy('created_date', 'desc')->get();
//        if ($request->search['warehouses_type']) {
//            $sales = $sales->where('store_category_type', '=', $request->search['warehouses_type']);
//        }
//        if ($request->search['branch_id']) {
//            $sales = $sales->where('branch_id', '=', $request->search['branch_id']);
//        }
        return Datatables::of($sales)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($action_view) {
                return (string)view($action_view, compact('row'));
            })
            ->addColumn('branch', function ($row) {
                return optional($row->branch)->getBranchName();

            })
            ->addColumn($row_type, function ($row) use ($row_type, $page) {
                if ($page == 'return') {
                    if ($row->storeVouType->system_code == '104006') {
                        return optional($row->customer)->getCustomerName();
                    }
                    return optional($row->{$row_type})->getCustomerName();
                }
                return optional($row->{$row_type})->getCustomerName();
            })
            ->addColumn('payment_method', function ($row) {

                return optional($row->paymentMethod)->getSysCodeName();

            })
            ->addColumn('warahouse_type', function ($row) {

                return $row->storeCategory->getSysCodeName();

            })
            ->addColumn('store_vou_date', function ($row) {
                return $row->created_date->format('Y-m-d H:m');
            })
            ->addColumn('store_vou_status', function ($row) {
                return optional($row->status)->getSysCodeName();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request, $uuid, $page)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
        $sales = Sales::where('uuid', $uuid)->first();

        $car_brand = CarRentBrand::where('company_id', $company->company_id)->get();

        $warehouses_type_lits = SystemCode::where('company_id', $company->company_id)->where('system_code_id', '=', $sales->store_category_type)->get();
        $unit_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 35)->get();
        $car_brand = CarRentBrand::where('company_id', $company->company_id)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->where('customer_category', '=', 2)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $system_code_types = [];

        $bonds_cash = [];
        $bonds_capture = [];
        switch ($page) {
            case 'request':
                $view = 'salesCar.purchase.request.edit_request';
                break;

            case 'order':
                $view = 'salesCar.purchase.order.edit_order';
                break;

            case 'direct_order':
                $view = 'salesCar.purchase.order.edit_direct_order';
                break;

            case 'receiving':
                $view = 'salesCar.purchase.receiving.edit_receive';
                break;

            case 'direct_receiving':
                $view = 'salesCar.purchase.receiving.create_direct_receiving';
                break;

            case 'quote':
                $view = 'salesCar.sales.quote.edit_quote';
                break;

            case 'inv':

                //       انواع الايرادات
                $system_code_types = SystemCode::where('sys_category_id', 58)
                    ->where('company_group_id', $company->company_group_id)->get();

                $bonds_cash = Bond::where('bond_ref_no', $sales->store_hd_code)
                    ->where('bond_type_id', 2)->latest()->get();

                $bonds_capture = Bond::where('bond_ref_no', $sales->store_hd_code)
                    ->where('bond_type_id', 1)->latest()->get();

                $view = 'salesCar.sales.inv.edit_inv';
                break;

            case 'direct_inv':
                $view = 'salesCar.sales.inv.edit_direct_inv';
                break;

            case 'return-purcahse':
                $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
                $view = 'salesCar.return.edit_return';
                break;

            case 'return-sales':
                $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 2)->get();
                $view = 'salesCar.return.edit_return';
                break;

            default:
                abort(404);
        }


        return view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list',
            'vendor_list', 'sales', 'car_brand', 'warehouses_type_lits', 'unit_lits', 'car_brand', 'customer', 'page', 'item_disc_type',
            'payment_methods', 'banks', 'system_code_types', 'bonds_cash', 'bonds_capture'));
    }

    public function store(Request $request, $page)
    {
//
//        return $page;
        $company = session('company') ? session('company') : auth()->user()->company;
        switch ($page) {
            case 'request':
                $vou_type = SystemCode::where('system_code', '=', '104001')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeHeader($request, $vou_type);
                break;

            case 'order':
                $vou_type = SystemCode::where('system_code', '=', '104002')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeAll($request, $vou_type);
                break;

            case 'direct_order':
                $vou_type = SystemCode::where('system_code', '=', '104002')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeHeader($request, $vou_type);
                break;

            case 'receiving':
                $vou_type = SystemCode::where('system_code', '=', '104003')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeRecivingAll($request, $vou_type);
                break;

            case 'direct_receiving':
                $vou_type = SystemCode::where('system_code', '=', '104003')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeHeader($request, $vou_type);
                break;

            case 'quote':
                $vou_type = SystemCode::where('system_code', '=', '104004')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeHeader($request, $vou_type);
                break;

            case 'inv':
                $vou_type = SystemCode::where('system_code', '=', '104005')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeAll($request, $vou_type);
                break;

            case 'direct_inv':
                $vou_type = SystemCode::where('system_code', '=', '104005')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeHeader($request, $vou_type);
                break;

            case 'return-sales':
                $vou_type = SystemCode::where('system_code', '=', '104006')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeAll($request, $vou_type);
                break;

            case 'return-purcahse':
                $vou_type = SystemCode::where('system_code', '=', '104007')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeAll($request, $vou_type);
                break;

            case 'trans':

                $vou_type = SystemCode::where('system_code', '=', '104009')
                    ->where('company_group_id', $company->company_group_id)->first();
                return SalesCarController::storeRecivingAll($request, $vou_type);
                break;


            default:
                abort(404);
        }
    }

    public function storeItem(Request $request, $page)
    {
        switch ($page) {
            case 'request':

                $vou_type = SystemCode::where('system_code', '=', '104001')->first()->system_code_id;
                $header = Sales::where('uuid', '=', $request->sales_uuid)->first();
                return SalesCarController::storeItemData($request, $header, $page);
                break;

            case 'direct_order':

                $vou_type = SystemCode::where('system_code', '=', '104002')->first()->system_code_id;
                $header = Sales::where('uuid', '=', $request->sales_uuid)->first();
                return SalesCarController::storeItemData($request, $header, $page);
                break;

            case 'receiving':
                $view = 'salesCar.purchase.receiving.index';
                break;

            case 'direct_receiving':

                $vou_type = SystemCode::where('system_code', '=', '104003')->first()->system_code_id;
                $header = Sales::where('uuid', '=', $request->sales_uuid)->first();
                return SalesCarController::storeDirectItemData($request, $header, $page);
                break;

            case 'quote':

                $vou_type = SystemCode::where('system_code', '=', '104004')->first()->system_code_id;
                $header = Sales::where('uuid', '=', $request->sales_uuid)->first();
                return SalesCarController::storeSalesItemData($request, $header, $page);
                break;

            case 'direct_inv':

                $vou_type = SystemCode::where('system_code', '=', '104005')->first()->system_code_id;
                $header = Sales::where('uuid', '=', $request->sales_uuid)->first();
                return SalesCarController::storeSalesItemData($request, $header, $page);
                break;


            case 'return':
                $view = 'salesCar.purchase.return.index';
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

        if ($type->system_code == '104001') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 79);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم طلب الشراء يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'REQ-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id;
        } elseif ($type->system_code == '104002') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 80);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم امر الشراء يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'PO-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id;
        } elseif ($type->system_code == '104003') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 81);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن الاستلام يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'ER-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
        } elseif ($type->system_code == '104004') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 122);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم عرض الاسعار يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'Qut-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id;
        } elseif ($type->system_code == '104005') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 83);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم فاتورة البيع يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'C-INV-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
        } else {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }


        \DB::beginTransaction();
        $sales = new  Sales();

        $sales->uuid = \DB::raw('NEWID()');

        $sales->company_group_id = $company->company_group_id;
        $sales->company_id = $company->company_id;
        $sales->branch_id = $branch->branch_id;

        $sales->store_category_type = SystemCode::where('system_code', '=', $request->store_category_type)->first()->system_code_id;;
        $sales->store_vou_type = $type->system_code_id;

        $sales->store_hd_code = $new_serial;
        $sales->store_acc_no = $request->store_acc_no;
        $sales->store_acc_name = $request->store_acc_name;
        $sales->store_acc_tax_no = $request->store_acc_tax_no;
        $sales->store_vou_pay_type = SystemCode::where('system_code', '=', $request->store_vou_pay_type)->first()->system_code_id;
        $sales->store_vou_notes = $request->store_vou_notes;
        $sales->store_vou_status = $store_vou_status;
        $sales->store_vou_date = Carbon::now();
        $sales->created_user = auth()->user()->user_id;

        $sales_save = $sales->save();
        if (!$sales_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $sales->refresh()->uuid]);


    }

    public function storeItemData(Request $request, $header, $page)
    {
        $rules = [
            'sales_uuid' => 'required',
            'item_table_data' => 'required',
        ];

        $validator =
            Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        if ($page == 'request') {
            $qty_field = 'store_vou_qnt_r';
        } elseif ($page == 'new_receving') {
            $qty_field = 'store_vou_qnt_i';
        } elseif ($page == 'direct_order') {
            $qty_field = 'store_vou_qnt_p';
        } else {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $sales_details = new SalesDetails();
        $item_data = json_decode($request->item_table_data, true);

        $is_added_befor = SalesDetails::where('store_hd_id', $header->store_hd_id)->where('store_brand_id', '=', $item_data['store_brand_id'])->where('store_brand_dt_id', '=', $item_data['store_brand_dt_id'])->where('isdeleted', '=', 0);
        if ($is_added_befor->count() > 0) {
            return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا الصنف مسبقا..!']);
        }

        \DB::beginTransaction();

        $sales_details->uuid = \DB::raw('NEWID()');
        $sales_details->store_hd_id = $header->store_hd_id;
        $sales_details->company_group_id = $header->company_group_id;
        $sales_details->company_id = $header->company_id;
        $sales_details->branch_id = $header->branch_id;

        $sales_details->store_category_type = $header->store_category_type;
        $sales_details->store_vou_type = $header->store_vou_type;
        $sales_details->store_vou_date = Carbon::now();
        $sales_details->created_user = auth()->user()->user_id;
        $sales_details->store_acc_no = $header->store_acc_no;
        $sales_details->store_vou_item_id = 0;
        $sales_details->store_brand_dt_id = $item_data['store_brand_dt_id'];
        $sales_details->store_brand_id = $item_data['store_brand_id'];
        $sales_details->store_brand_dt_id = $item_data['store_brand_dt_id'];
        $sales_details->store_brand_id = $item_data['store_brand_id'];

        $sales_details->{$qty_field} = $item_data[$qty_field];

        $sales_details->store_vou_item_price_cost = $item_data['store_vou_item_price_cost'];
        $sales_details->store_vou_item_price_unit = $item_data['store_vou_item_price_unit'];
        $sales_details->store_vou_item_total_price = $item_data['store_vou_item_total_price'];
        $sales_details->store_vou_vat_rate = $item_data['store_vou_vat_rate'];
        $sales_details->store_vou_vat_amount = $item_data['store_vou_vat_amount'];
        $sales_details->store_vou_price_net = $item_data['store_vou_price_net'];
        $sales_details_save = $sales_details->save();

        if (!$sales_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = SalesCarController::updateHeaderTotal($sales_details->sales);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $sales_details->sales->itemSumTotal(),
            'total_sum_vat' => $sales_details->sales->itemSumVat(),
            'total_sum_net' => $sales_details->sales->itemSumNet(),
        ];

        info($sales_details->sales->storeVouType->system_code);

        // if ($sales_details->sales->storeVouType->system_code == 104003) { ////اذن استلام جديد
        //     //////////////journal
        //     $journal_controller = new JournalsController();
        //     $total_amount = $total['total_sum_net'];
        //     $vat_amount = $total['total_sum_vat'];
        //     $cc_voucher_id = $sales_details->sales->store_hd_id;
        //     $cost_center_id = 64;
        //     $notes = $sales_details->sales->store_vou_notes;
        //     if ($sales_details->sales->journal_hd_id) {
        //         $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);
        //     } else {
        //         $supplier_id = $sales_details->sales->store_acc_no;
        //         $purchasing_notes = $sales_details->sales->store_hd_code . 'تم اضافه قيد لاذن الاستلام رقم';
        //         $vat_notes = $sales_details->sales->store_hd_code . 'تم اضافه قيد ضريبه لاذن الاستلام رقم';
        //         $supplier_notes = $sales_details->sales->store_hd_code . 'تم اضافه قيد مورد لاذن الاستلام رقم';
        //         $journal_category_id = 35; ////فاتوره مشتريات مستودع من اذن استلام
        //         $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
        //             $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
        //             $journal_category_id, $notes);

        //     }
        // }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $sales_details->refresh()->uuid, 'total' => $total]);

    }

    public function storeSalesItemData(Request $request, $header, $page)
    {
        $rules = [
            'sales_uuid' => 'required',
            'item_table_data' => 'required',
        ];

        $validator =
            Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        if ($page == 'quote') {
            $qty_field = 'store_vou_qnt_q';
        } elseif ($page == 'direct_inv') {
            $qty_field = 'store_vou_qnt_o';
        } else {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $sales_details = new SalesDetails();
        $item_data = json_decode($request->item_table_data, true);

        \DB::beginTransaction();

        $sales_details->uuid = \DB::raw('NEWID()');
        $sales_details->store_hd_id = $header->store_hd_id;
        $sales_details->company_group_id = $header->company_group_id;
        $sales_details->company_id = $header->company_id;
        $sales_details->branch_id = $header->branch_id;

        $sales_details->store_category_type = $header->store_category_type;
        $sales_details->store_vou_type = $header->store_vou_type;
        $sales_details->store_vou_date = Carbon::now();
        $sales_details->created_user = auth()->user()->user_id;
        $sales_details->store_acc_no = $header->store_acc_no;
        $sales_details->store_vou_item_id = $item_data['store_vou_item_id'];
        $sales_details->store_brand_dt_id = $item_data['store_brand_dt_id'];
        $sales_details->store_brand_id = $item_data['store_brand_id'];
        $sales_details->store_brand_dt_id = $item_data['store_brand_dt_id'];
        $sales_details->store_brand_id = $item_data['store_brand_id'];

        $sales_details->{$qty_field} = $item_data[$qty_field];

        if (array_key_exists('store_vou_disc_amount', $item_data)) {
            $sales_details->store_vou_disc_type = $item_data['store_vou_disc_type'];
            $sales_details->store_voue_disc_value = $item_data['store_voue_disc_value'];
            $sales_details->store_vou_disc_amount = $item_data['store_vou_disc_amount'];
        }


        $sales_details->store_vou_item_price_unit = $item_data['store_vou_item_price_unit'];
        $sales_details->store_vou_item_total_price = $item_data['store_vou_item_total_price'];
        $sales_details->store_vou_vat_rate = $item_data['store_vou_vat_rate'];
        $sales_details->store_vou_vat_amount = $item_data['store_vou_vat_amount'];
        $sales_details->store_vou_price_net = $item_data['store_vou_price_net'];
        $sales_details_save = $sales_details->save();

        if (!$sales_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        //update sales car status to 
        if ($page == 'quote') {
            $sales_car = SalesCar::where('sales_cars_id', '=', $item_data['store_vou_item_id'])
                ->update([
                    'sales_car_status' => SystemCode::where('system_code', '=', '120002')->first()->system_code_id
                    //,'sales_cars_add_amount' => $item_data['sales_cars_add_amount']
                ]);
        } elseif ($page == 'direct_inv') {
            $sales_car = SalesCar::where('sales_cars_id', '=', $item_data['store_vou_item_id'])
                ->update([
                    'sales_car_status' => SystemCode::where('system_code', '=', '120004')->first()->system_code_id
                    //,'sales_cars_add_amount' => $item_data['sales_cars_add_amount']

                ]);
        } else {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = SalesCarController::updateHeaderTotal($sales_details->sales);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $sales_details->sales->itemSumTotal(),
            'total_sum_vat' => $sales_details->sales->itemSumVat(),
            'total_sum_net' => $sales_details->sales->itemSumNet(),
        ];

        info($sales_details->sales->storeVouType->system_code);

        // if ($sales_details->sales->storeVouType->system_code == 104003) { ////اذن استلام جديد
        //     //////////////journal
        //     $journal_controller = new JournalsController();
        //     $total_amount = $total['total_sum_net'];
        //     $vat_amount = $total['total_sum_vat'];
        //     $cc_voucher_id = $sales_details->sales->store_hd_id;
        //     $cost_center_id = 64;
        //     $notes = $sales_details->sales->store_vou_notes;
        //     if ($sales_details->sales->journal_hd_id) {
        //         $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);
        //     } else {
        //         $supplier_id = $sales_details->sales->store_acc_no;
        //         $purchasing_notes = $sales_details->sales->store_hd_code . 'تم اضافه قيد لاذن الاستلام رقم';
        //         $vat_notes = $sales_details->sales->store_hd_code . 'تم اضافه قيد ضريبه لاذن الاستلام رقم';
        //         $supplier_notes = $sales_details->sales->store_hd_code . 'تم اضافه قيد مورد لاذن الاستلام رقم';
        //         $journal_category_id = 35; ////فاتوره مشتريات مستودع من اذن استلام
        //         $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
        //             $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
        //             $journal_category_id, $notes);

        //     }
        // }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $sales_details->refresh()->uuid, 'total' => $total]);

    }

    public function storeDirectItemData(Request $request, $header, $page)
    {
        $request->merge(['item_table_data' => json_decode($request->item_table_data, true)]);
        $rules = [
            'sales_uuid' => 'required',
            'item_table_data' => 'required',
            'item_table_data.sales_cars_chasie_no' => 'required|digits_between:13,26|distinct|unique:sales_cars,sales_cars_chasie_no',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;
        $qty_field = 'store_vou_qnt_i';

        $sales_details = new SalesDetails();
        $item_data = $request->item_table_data;


        \DB::beginTransaction();

        $car_details = new SalesCar();
        $car_details->uuid = \DB::raw('NEWID()');
        $car_details->company_group_id = $header->company_group_id;
        $car_details->company_id = $header->company_id;
        $car_details->branch_id = $header->branch_id;

        $car_details->supplier_id = $header->store_category_type;


        $car_details->sales_cars_brand_id = $item_data['store_brand_id'];
        $car_details->sales_cars_brand_dt_id = $item_data['store_brand_dt_id'];
        $car_details->sales_cars_model = $item_data['sales_cars_model'];
        $car_details->sales_cars_color = $item_data['sales_cars_color'];
        $car_details->sales_cars_chasie_no = $item_data['sales_cars_chasie_no'];
        $car_details->sales_cars_plate_no = $item_data['sales_cars_plate_no'];

        $car_details->sales_cars_desc = $item_data['sales_cars_desc'];
        $car_details->sales_cars_add_amount = $item_data['sales_cars_add_amount'];
        $car_details->sales_cars_disc_amount = 0;
        $car_details->sales_cars_total_amount = $item_data['store_vou_item_total_price'];
        $car_details->sales_cars_sales_amount = $item_data['sales_cars_sales_amount'];
        $car_details->sales_cars_price_amount = $item_data['store_vou_item_price_unit'];

        $car_details->sales_car_status = SystemCode::where('system_code', '=', '120001')->first()->system_code_id;

        $car_details->created_user = auth()->user()->user_id;
        $car_details_save = $car_details->save();

        if (!$car_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }


        $sales_details->uuid = \DB::raw('NEWID()');
        $sales_details->store_hd_id = $header->store_hd_id;
        $sales_details->company_group_id = $header->company_group_id;
        $sales_details->company_id = $header->company_id;
        $sales_details->branch_id = $header->branch_id;

        $sales_details->store_category_type = $header->store_category_type;
        $sales_details->store_vou_type = $header->store_vou_type;
        $sales_details->store_vou_date = Carbon::now();
        $sales_details->created_user = auth()->user()->user_id;
        $sales_details->store_acc_no = $header->store_acc_no;
        $sales_details->store_vou_item_id = $car_details->sales_cars_id;
        $sales_details->store_brand_dt_id = $item_data['store_brand_dt_id'];
        $sales_details->store_brand_id = $item_data['store_brand_id'];
        $sales_details->store_brand_dt_id = $item_data['store_brand_dt_id'];
        $sales_details->store_brand_id = $item_data['store_brand_id'];

        $sales_details->{$qty_field} = $item_data[$qty_field];

        //$sales_details->store_vou_item_price_cost = $item_data['store_vou_item_price_cost'];
        $sales_details->store_vou_item_price_unit = $item_data['store_vou_item_price_unit'];
        $sales_details->store_vou_item_total_price = $item_data['store_vou_item_total_price'];
        $sales_details->store_vou_vat_rate = $item_data['store_vou_vat_rate'];
        $sales_details->store_vou_vat_amount = $item_data['store_vou_vat_amount'];
        $sales_details->store_vou_price_net = $item_data['store_vou_price_net'];
        $sales_details_save = $sales_details->save();

        if (!$sales_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = SalesCarController::updateHeaderTotal($sales_details->sales);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $sales_details->sales->itemSumTotal(),
            'total_sum_vat' => $sales_details->sales->itemSumVat(),
            'total_sum_net' => $sales_details->sales->itemSumNet(),
        ];

        info($sales_details->sales->storeVouType->system_code);

        if ($sales_details->sales->storeVouType->system_code == 104003 && !$header->store_vou_ref_before) { ////اذن دخول جديد
            //////////////journal
            $journal_controller = new JournalsController();
            $total_amount = $total['total_sum_net'];
            $vat_amount = $total['total_sum_vat'];
            $cc_voucher_id = $sales_details->sales->store_hd_id;
            $cost_center_id = 81;
            $notes = '  قيد مشتريات اذن دخول سيارات  رقم' . ' ' . $sales_details->sales->store_hd_code . ' ' . $sales_details->sales->store_acc_name;
            if ($sales_details->sales->journal_hd_id) {
                $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);
            } else {
                $supplier_id = $sales_details->sales->store_acc_no;
                $purchasing_notes = ' قيد مشتريات اذن دخول سيارات رقم' . ' ' . $sales_details->sales->store_hd_code . ' ' . $sales_details->sales->store_acc_name;
                $vat_notes = '   ضريبه قيمه مضافه مشتريات اذن الاستلام رقم' . ' ' . $sales_details->sales->store_hd_code . ' ' . $sales_details->sales->store_acc_name;
                $supplier_notes = '  مشتريات  مورد اذن دخول سيارات رقم' . ' ' . $sales_details->sales->store_hd_code . ' ' . $sales_details->sales->store_acc_name;
                $journal_category_id = JournalType::where('journal_types_code', 71)
                    ->where('company_group_id', $sales_details->sales->company->company_group_id)->first()->journal_types_code;

                $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
                    $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
                    $journal_category_id, $notes);

            }
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $sales_details->refresh()->uuid, 'total' => $total]);

    }

    public function storeAll(Request $request, $type)
    {
        $rules = [
            'store_vou_ref_before' => 'required|exists:sales_cars_hd,uuid',
            'item_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $store_vou_ref_before = Sales::where('uuid', '=', $request->store_vou_ref_before)->first();
        $branch_id = $store_vou_ref_before->branch_id;
        $company = $store_vou_ref_before->company;
        //gnerate code
        switch ($type->system_code) {
            case '104002':
                info('order');
                $qty_field = 'store_vou_qnt_p';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 80);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم امر الشراء يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'PO-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                break;

            case '104005':
                info('inv from qoutation');
                $qty_field = 'store_vou_qnt_o';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 83);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم فاتورة البيع يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'C-INV-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                break;

            case '104006':
                info('sales retrun');
                $qty_field = 'store_vou_qnt_o_r';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 123);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم فاتورة البيع يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'C-SR-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125003')->first()->system_code_id;
                break;

            case '104007':
                info('purchase return');
                $qty_field = 'store_vou_qnt_i_r';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 84);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم فاتورة البيع يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'C-PR-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125003')->first()->system_code_id;
                break;

            default:
                abort(404);
        }


        \DB::beginTransaction();
        $sales = new Sales();

        $sales->uuid = \DB::raw('NEWID()');

        $sales->company_group_id = $company->company_group_id;
        $sales->company_id = $company->company_id;
        $sales->branch_id = $branch_id;

        $sales->store_category_type = $store_vou_ref_before->store_category_type;
        $sales->store_vou_type = $type->system_code_id;

        $sales->store_hd_code = $new_serial;
        $sales->store_acc_no = $store_vou_ref_before->store_acc_no;
        $sales->store_acc_name = $store_vou_ref_before->store_acc_name;
        $sales->store_acc_tax_no = $store_vou_ref_before->store_acc_tax_no;
        $sales->store_vou_pay_type = $store_vou_ref_before->store_vou_pay_type;
        $sales->store_vou_notes = $store_vou_ref_before->store_vou_notes;
        $sales->store_vou_status = $store_vou_status;
        $sales->store_vou_ref_before = $store_vou_ref_before->store_hd_code;
        $sales->store_vou_date = Carbon::now();
        $sales->created_user = auth()->user()->user_id;

        $sales_save = $sales->save();

        if (!$sales_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);

        //store item

        $item_data = json_decode($request->item_data, true);
        $sales_details = new SalesDetails();
        if (count($item_data) > 0) {

            $item_data_set = [];
            if ($type->system_code == '104002') {
                foreach ($item_data as $i => $d) {
                    $item = SalesDetails::where('uuid', '=', $d['uuid'])->first();
                    $item_data_set[] = [
                        'uuid' => \DB::raw('NEWID()'),
                        'store_hd_id' => $sales->store_hd_id,
                        'company_group_id' => $store_vou_ref_before->company_group_id,
                        'company_id' => $store_vou_ref_before->company_id,
                        'branch_id' => $store_vou_ref_before->branch_id,

                        'store_category_type' => $store_vou_ref_before->store_category_type,
                        'store_vou_type' => $type->system_code_id,
                        'store_vou_date' => Carbon::now(),
                        'created_user' => auth()->user()->user_id,
                        'store_acc_no' => $store_vou_ref_before->store_acc_no,

                        'store_vou_item_id' => $item->store_vou_item_id,
                        'store_brand_dt_id' => $item->store_brand_dt_id,
                        'store_brand_id' => $item->store_brand_id,
                        $qty_field => $d[$qty_field],


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
                    $update_status = SalesCarController::checkStatus($store_vou_ref_before);
                    if ($update_status->getData()->success) {

                        $store_vou_ref_before->store_vou_status = $store_vou_ref_before_status;
                        $store_vou_ref_before_save = $store_vou_ref_before->save();

                        if (!$store_vou_ref_before_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }
                    }

                }

                $sales_details_save = $sales_details->insert($item_data_set);

                if (!$sales_details_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }
            } elseif ($type->system_code == '104005') {
                foreach ($item_data as $i => $d) {
                    $item = SalesDetails::where('uuid', '=', $d['uuid'])->first();
                    $item_data_set[] = [
                        'uuid' => \DB::raw('NEWID()'),
                        'store_hd_id' => $sales->store_hd_id,
                        'company_group_id' => $store_vou_ref_before->company_group_id,
                        'company_id' => $store_vou_ref_before->company_id,
                        'branch_id' => $store_vou_ref_before->branch_id,

                        'store_category_type' => $store_vou_ref_before->store_category_type,
                        'store_vou_type' => $type->system_code_id,
                        'store_vou_date' => Carbon::now(),
                        'created_user' => auth()->user()->user_id,
                        'store_acc_no' => $store_vou_ref_before->store_acc_no,

                        'store_vou_item_id' => $item->store_vou_item_id,
                        'store_brand_dt_id' => $item->store_brand_dt_id,
                        'store_brand_id' => $item->store_brand_id,
                        $qty_field => $d[$qty_field],


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
                    $update_status = SalesCarController::checkStatus($store_vou_ref_before);
                    if ($update_status->getData()->success) {

                        $store_vou_ref_before->store_vou_status = $store_vou_ref_before_status;
                        $store_vou_ref_before_save = $store_vou_ref_before->save();

                        if (!$store_vou_ref_before_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }
                    }

                }

                $sales_details_save = $sales_details->insert($item_data_set);

                if (!$sales_details_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                //update sales car status to 
                $sales_car = SalesCar::whereIn('sales_cars_id', Arr::pluck($item_data, 'store_vou_item_id'))
                    ->update(['sales_car_status' => SystemCode::where('system_code', '=', '120004')->first()->system_code_id]);
            } // return sales
            elseif ($type->system_code == '104006') {
                foreach ($item_data as $i => $d) {
                    $item = SalesDetails::where('uuid', '=', $d['uuid'])->first();
                    $item_data_set[] = [
                        'uuid' => \DB::raw('NEWID()'),
                        'store_hd_id' => $sales->store_hd_id,
                        'company_group_id' => $store_vou_ref_before->company_group_id,
                        'company_id' => $store_vou_ref_before->company_id,
                        'branch_id' => $store_vou_ref_before->branch_id,

                        'store_category_type' => $store_vou_ref_before->store_category_type,
                        'store_vou_type' => $type->system_code_id,
                        'store_vou_date' => Carbon::now(),
                        'created_user' => auth()->user()->user_id,
                        'store_acc_no' => $store_vou_ref_before->store_acc_no,

                        'store_vou_item_id' => $item->store_vou_item_id,
                        'store_brand_dt_id' => $item->store_brand_dt_id,
                        'store_brand_id' => $item->store_brand_id,
                        $qty_field => $d[$qty_field],


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
                    $update_status = SalesCarController::checkStatus($store_vou_ref_before);
                    if ($update_status->getData()->success) {

                        $store_vou_ref_before->store_vou_status = $store_vou_ref_before_status;
                        $store_vou_ref_before_save = $store_vou_ref_before->save();

                        if (!$store_vou_ref_before_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }
                    }

                }

                $sales_details_save = $sales_details->insert($item_data_set);

                if (!$sales_details_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                //update sales car status to 
                $sales_car = SalesCar::whereIn('sales_cars_id', Arr::pluck($item_data, 'store_vou_item_id'))
                    ->update(['sales_car_status' => SystemCode::where('system_code', '=', '120001')->first()->system_code_id]);
            } //return purcahse
            elseif ($type->system_code == '104007') {

                foreach ($item_data as $i => $d) {
                    $item = SalesDetails::where('uuid', '=', $d['uuid'])->first();
                    $item_data_set[] = [
                        'uuid' => \DB::raw('NEWID()'),
                        'store_hd_id' => $sales->store_hd_id,
                        'company_group_id' => $store_vou_ref_before->company_group_id,
                        'company_id' => $store_vou_ref_before->company_id,
                        'branch_id' => $store_vou_ref_before->branch_id,

                        'store_category_type' => $store_vou_ref_before->store_category_type,
                        'store_vou_type' => $type->system_code_id,
                        'store_vou_date' => Carbon::now(),
                        'created_user' => auth()->user()->user_id,
                        'store_acc_no' => $store_vou_ref_before->store_acc_no,

                        'store_vou_item_id' => $item->store_vou_item_id,
                        'store_brand_dt_id' => $item->store_brand_dt_id,
                        'store_brand_id' => $item->store_brand_id,
                        $qty_field => $d[$qty_field],


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
                    $update_status = SalesCarController::checkStatus($store_vou_ref_before);
                    if ($update_status->getData()->success) {

                        $store_vou_ref_before->store_vou_status = $store_vou_ref_before_status;
                        $store_vou_ref_before_save = $store_vou_ref_before->save();

                        if (!$store_vou_ref_before_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }
                    }

                }

                $sales_details_save = $sales_details->insert($item_data_set);

                if (!$sales_details_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                //update sales car status to 
                $sales_car = SalesCar::whereIn('sales_cars_id', Arr::pluck($item_data, 'store_vou_item_id'))
                    ->update(['sales_car_status' => SystemCode::where('system_code', '=', '120005')->first()->system_code_id]);
            } else {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }

        }

        $update_total = SalesCarController::updateHeaderTotal($sales);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $sales->refresh()->uuid]);

    }

    public function storeRecivingAll(Request $request, $type)
    {


        if (isset($request->trans_req_type)) {
            $trans_req_type = $request->trans_req_type;
        } else {
            $trans_req_type = 'request';
        }

        $request->merge(['item_data' => json_decode($request->item_data, true)]);
        $request->merge(['trans_req_type' => $trans_req_type]);
        if ($trans_req_type == 'request') {
            $rules = [
                'store_vou_ref_before' => 'required|exists:sales_cars_hd,uuid',
                'item_data' => 'required',
                'trans_req_type' => 'required',
                'item_data.*.sales_cars_chasie_no' => 'required:trans_req_type,in:request|digits_between:13,26|distinct|unique:sales_cars,sales_cars_chasie_no',

            ];
        } else {
            $rules = [
                'store_vou_ref_before' => 'required|exists:sales_cars_hd,uuid',
                'item_data' => 'required',
                'trans_req_type' => 'required',
                'item_data.*.car_uuid' => 'required_if:trans_req_type,in:direct_trans|exists:sales_cars,uuid',

            ];
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $store_vou_ref_before = Sales::where('uuid', '=', $request->store_vou_ref_before)->first();
        $branch_id = $store_vou_ref_before->branch_id;
        $company = $store_vou_ref_before->company;
        //gnerate code
        switch ($type->system_code) {
            case '104003':
                info('Enternse purcahse');
                $qty_field = 'store_vou_qnt_i';
                $store_category_type = $store_vou_ref_before->store_category_type;
                $dest_branch = $store_vou_ref_before->branch_id;
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 81);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن الاستلام يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'ER-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                break;

            case '104009':

                info('Enternse purcahse From trasnfer');
                if ($store_vou_ref_before->store_vou_ref_3 != session('branch')->branch_id) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن اتمام عملية الاستلام من فرع غير الفرع المستلم']);
                }

                $qty_field = 'store_vou_qnt_t_i';
                $store_category_type = $store_vou_ref_before->store_vou_ref_4;
                $dest_branch = $store_vou_ref_before->store_vou_ref_3;
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $dest_branch)->where('app_menu_id', 81);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن الاستلام يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'ER-' . $dest_branch . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('system_code', '=', '125002')->first()->system_code_id;
                break;

            default:
                abort(404);
        }


        \DB::beginTransaction();
        $sales = new Sales();

        $sales->uuid = \DB::raw('NEWID()');

        $sales->company_group_id = $company->company_group_id;
        $sales->company_id = $company->company_id;
        $sales->branch_id = $dest_branch;

        $sales->store_category_type = $store_category_type;
        $sales->store_vou_type = $type->system_code_id;

        $sales->store_hd_code = $new_serial;
        $sales->store_acc_no = $store_vou_ref_before->store_acc_no;
        $sales->store_acc_name = $store_vou_ref_before->store_acc_name;
        $sales->store_acc_tax_no = $store_vou_ref_before->store_acc_tax_no;
        $sales->store_vou_pay_type = $store_vou_ref_before->store_vou_pay_type;
        $sales->store_vou_notes = $store_vou_ref_before->store_vou_notes;
        $sales->store_vou_status = $store_vou_status;
        $sales->store_vou_ref_before = $store_vou_ref_before->store_hd_code;
        $sales->store_vou_date = Carbon::now();
        $sales->created_user = auth()->user()->user_id;

        $sales_save = $sales->save();

        if (!$sales_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);

        //store item

        $item_data = $request->item_data;
        $sales_details = new SalesDetails();
        if (count($item_data) > 0) {

            $item_data_set = [];

            foreach ($item_data as $i => $d) {
                $item = SalesDetails::where('uuid', '=', $d['uuid'])->first();
                if ($trans_req_type == 'request') {
                    $car_details = new SalesCar();
                    $car_details->uuid = \DB::raw('NEWID()');
                    $car_details->company_group_id = $company->company_group_id;
                    $car_details->company_id = $company->company_id;
                    $car_details->branch_id = $dest_branch;
                    $car_details->store_category_type = $store_category_type;
                    $car_details->supplier_id = $item->store_category_type;
                    $car_details->sales_cars_plate_no = $d['sales_cars_plate_no'];

                    $car_details->sales_cars_brand_id = $item->store_brand_id;
                    $car_details->sales_cars_brand_dt_id = $item->store_brand_dt_id;
                    $car_details->sales_cars_model = $d['sales_cars_model'];
                    $car_details->sales_cars_color = $d['sales_cars_color'];
                    $car_details->sales_cars_chasie_no = $d['sales_cars_chasie_no'];

                    $car_details->sales_cars_desc = $d['sales_cars_desc'];
                    $car_details->sales_cars_add_amount = $d['sales_cars_add_amount'];
                    $car_details->sales_cars_disc_amount = $d['store_vou_disc_amount'];
                    $car_details->sales_cars_total_amount = $d['store_vou_item_total_price'];
                    $car_details->sales_cars_sales_amount = $d['sales_cars_sales_amount'];
                    $car_details->sales_cars_price_amount = $d['store_vou_item_price_unit'];

                    $car_details->sales_car_status = SystemCode::where('system_code', '=', '120001')->first()->system_code_id;

                    $car_details->created_user = auth()->user()->user_id;
                    $car_details_save = $car_details->save();

                    if (!$car_details_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                } elseif ($trans_req_type == 'direct_trans') {

                    $car_details = SalesCar::where('uuid', '=', $d['car_uuid'])
                        ->where('sales_car_status', '=', SystemCode::where('system_code', '=', '120003')->first()->system_code_id);


                    if ($car_details->count() == 0) {
                        return \Response::json(['success' => false, 'msg' => 'bحدث تغير في حالة المركبة الرجاء التواصل مع مدير النظام']);
                    }

                    $car_details = $car_details->first();
                    $car_details->sales_car_status = SystemCode::where('system_code', '=', '120001')->first()->system_code_id;
                    $car_details->branch_id = $dest_branch;
                    $car_details->sales_cars_sales_amount = $d['sales_cars_sales_amount'];
                    $car_details->updated_user = auth()->user()->user_id;
                    $car_details_save = $car_details->save();

                    if (!$car_details_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }

                } else {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }


                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $sales->store_hd_id,
                    'company_group_id' => $store_vou_ref_before->company_group_id,
                    'company_id' => $store_vou_ref_before->company_id,
                    'branch_id' => $dest_branch,

                    'store_category_type' => $store_vou_ref_before->store_category_type,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $store_vou_ref_before->store_acc_no,

                    'store_vou_item_id' => $car_details->sales_cars_id,
                    'store_brand_dt_id' => $item->store_brand_dt_id,
                    'store_brand_id' => $item->store_brand_id,
                    $qty_field => $d[$qty_field],


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
                $update_status = SalesCarController::checkStatus($store_vou_ref_before);
                if ($update_status->getData()->success) {
                    $store_vou_ref_before->store_vou_status = $store_vou_ref_before_status;
                    $store_vou_ref_before_save = $store_vou_ref_before->save();

                    if (!$store_vou_ref_before_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                }

            }

            $sales_details_save = $sales_details->insert($item_data_set);

            if (!$sales_details_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }

        }

        $update_total = SalesCarController::updateHeaderTotal($sales);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $sales->refresh()->uuid]);

    }

    public function updateHeaderTotal($header)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();

        $header = $header;
        $header->store_vou_amount = $header->itemSumTotal();
        $header->store_vou_desc = $header->itemSumDisc();
        $header->store_vou_vat_amount = $header->itemSumVat();
        $header->store_vou_total = $header->itemSumNet();

        if ($header->store_vou_type == SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '104005')->first()->system_code_id) {
            $header->qr_data = QRDataGenerator::fromArray([
                new SellerNameElement($header->company->companyGroup->company_group_ar),
                new TaxNoElement($header->company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($header->itemSumNet()),
                new TaxAmountElement($header->itemSumVat())
            ])->toBase64();
        }

        $header_save = $header->save();
        $header->refresh();
        if (!$header_save) {
            return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
        }

        if ($header->storeVouType->system_code == 104005) {
            ///////////journalقيد فاتوره مبيعات
//            $header->storeVouType->system_code == 104005 فاتوره بيع استيراد من عرض  سعر
//            + فاتوره بيع جديده سياره
            $journal = new JournalsController();
            $total_amount = $header->store_vou_total;
            $cc_voucher_id = $header->store_hd_id;

            $sales_notes = '   ايراد فاتوره مبيعات سياره رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;

            if (isset($header->journal_hd_id)) {
                $vat_amount = $header->store_vou_vat_amount;
                $journal->updateInvoiceJournal($total_amount, $vat_amount,
                    83, $cc_voucher_id, $items_id = [], $sales_notes);
            } else {
                $customer_id = $header->store_acc_no;
                $customer_notes = ' ايراد فاتوره بيع سياره  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $vat_notes = ' ضريبه قيمه مضافه مبيعات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $notes = ' قيد فاتوره بيع سياره  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $sales_notes = ' ايراد فاتوره بيع سياره رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $journal->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
                    83, $vat_notes, $sales_notes, 75, $items_id = [], $items_amount = [], $notes);
            }


            //////////////////////قيد تكلفه المخزون
//            $journal_type_2 = JournalType::where('company_group_id', $header->company_group_id)
//                ->where('journal_types_code', 61)->first();
//
//            foreach ($header->details as $purchase_detail) {
//                $cost[] = $purchase_detail->store_vou_item_price_cost * $purchase_detail->store_vou_qnt_o;
//            }
//
//            $total_cost_amount = array_sum($cost);
//
//            if (isset($journal_type_2)) {
//                if ($header->journalHd2) {
//                    $journal->updateStoreJournalsInvoice($cc_voucher_id, $total_cost_amount);
//                } else {
//                    $cost_notes = 'قيد تكلفه المخزون';
//                    $journal->addStoreJournalsInvoice(65, $cc_voucher_id, $total_cost_amount,
//                        $journal_type_2->journal_types_id, $notes, $cost_notes);
//                }
//            }
        }

        //////////////journal
        if ($header->storeVouType->system_code == 104003 && $header->store_vou_ref_before) {
            ///104003///استيراد من امر شراء

            $journal_controller = new JournalsController();
            $total_amount = $header->store_vou_total;
            $vat_amount = $header->store_vou_vat_amount;
            $cc_voucher_id = $header->store_hd_id;
            $cost_center_id = 81;

            if ($header->journal_hd_id) {
                $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);
            } else {
                $supplier_id = $header->store_acc_no;

                $purchasing_notes = '  قيد  استيراد من امر شراء رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $vat_notes = ' ضريبه قيمه مضافه استيراد من امر شارء  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $supplier_notes = 'مشتريات  مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;

                $notes = '  قيد مشتريات  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;

//                $journal_category_id = 35;
//                $journal_category_id = JournalType::where('journal_types_code', 71)
//                    ->where('company_group_id', $header->company->company_group_id)->first()->journal_types_id;

                $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
                    $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
                    71, $notes);

            }
        }

//////اذن تحويل
        if ($header->storeVouType->system_code == 104009) {
            $journal_controller = new JournalsController();
            $cost_center_id = 81;
            $cc_voucher_id = $header->store_hd_id;
            $total_amount = $header->store_vou_amount;
            $notes = 'قيد اذن تحويل لسياره رقم ' . $header->store_hd_code;
            $cost_notes = 'قيد اذن تحويل لسياره رقم ' . $header->store_hd_code;
            $store_vou_ref_before = $header->store_vou_ref_before;
            $transfer_before_type_code = 104008;
            //return $store_vou_ref_before;

            $journal_controller->storeTransferPermission($cost_center_id, $cc_voucher_id, $total_amount,
                79, $notes, $cost_notes, $store_vou_ref_before, $transfer_before_type_code);
        }

        ////مرتجع مورد
        if ($header->storeVouType->system_code == 104007) {
            if ($header->vendor) {
                $journal_controller = new JournalsController();
                $total_amount = $header->store_vou_total;
                $vat_amount = $header->store_vou_vat_amount;
                $cc_voucher_id = $header->store_hd_id;
                $cost_center_id = 84;

                if ($header->journal_hd_id) {
                    $journal_controller->updateReturnPurchasingOrder($total_amount, $vat_amount,
                        $cc_voucher_id, $cost_center_id);
                } else {
                    $supplier_id = $header->store_acc_no;
                    $customer_id = '';
                    $purchasing_notes = '   مرتجع مشتريات  سيارات مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $vat_notes = '   ضريبه قيمه مضافه مرتجع مشتريات  سيارات مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $supplier_notes = '   مرتجع مشتريات سيارات مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $notes = '   مرتجع مشتريات سيارات مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
//                    $journal_category_id = 46;
//                    $journal_category_id = JournalType::where('journal_types_code', 93)
//                        ->where('company_group_id', $header->company->company_group_id)->first()->journal_types_code;

                    $journal_controller->addReturnPurchasingOrder($total_amount, $vat_amount, $supplier_id,
                        $customer_id, $supplier_notes, $cost_center_id, $cc_voucher_id, 73,
                        $purchasing_notes, $vat_notes, $notes);

                }
            }
        }

        //////مرتجع عميل
        if ($header->storeVouType->system_code == 104006) { ////مرتجع عميل
            $journal_controller = new JournalsController();
            $total_amount = $header->store_vou_total;
            $customer_id = $header->store_acc_no;
            $cc_voucher_id = $header->store_hd_id;
            $customer_notes = '   مرتجع مبيعات  سيارات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $cost_center_id = 84; ////مرتجع مبيعات
            $vat_notes = '   ضريبه قيمه مضافه مرتجع مبيعات سيارات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $sales_notes = '   مرتجع مبيعات سيارات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $journal_category_id = 77; //مرتجع مبيعات سيارات

            $notes = '  قيد مرتجع مبيعات سيارات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
            $items_id = [];
            $items_amount = [];

            $message = $journal_controller->addSalesInvoiceJournal($total_amount, $customer_id, $cc_voucher_id,
                $customer_notes, $cost_center_id, $vat_notes, $sales_notes, $journal_category_id, $items_id,
                $items_amount, $notes);


//            $journal_type_2 = JournalType::where('company_group_id', $header->company_group_id)
//                ->where('journal_types_code', 61)->first();
//
//
//            foreach ($header->details as $purchase_detail) {
//                $cost[] = $purchase_detail->store_vou_item_price_cost * $purchase_detail->store_vou_qnt_o_r;
//            }
//
//            $total_cost_amount = array_sum($cost);
//
//
//            if (isset($journal_type_2)) {
//                if ($header->journalHd2) {
//                    $journal_controller->updateStoreJournalsSales($cc_voucher_id, $total_cost_amount);
//                } else {
//                    $cost_notes = '  تكلفه المخزون مرتجع عميل' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
//                    $journal_controller->addStoreJournalsSales(65, $cc_voucher_id, $total_cost_amount,
//                        $journal_type_2->journal_types_code, $notes, $cost_notes);
//                }
//            }

        }

        return ['success' => true, 'msg' => 'تمت العملية  بنجاح'];
    }

    public function updateHeader(Request $request)
    {
        $rules = [
            'sales_uuid' => 'required|exists:sales_cars_hd,uuid',
            'store_acc_no' => 'required',
            'store_acc_name' => 'required',
            'store_acc_tax_no' => 'required',
            'store_vou_pay_type' => 'required',
            'header_page' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }


        $sales = Sales::where('uuid', '=', $request->sales_uuid)->first();

        \DB::beginTransaction();

        $sales->store_acc_no = $request->store_acc_no;
        $sales->store_acc_name = $request->store_acc_name;
        $sales->store_acc_tax_no = $request->store_acc_tax_no;
        $sales->store_vou_pay_type = SystemCode::where('system_code', '=', $request->store_vou_pay_type)->first()->system_code_id;
        $sales->store_vou_notes = $request->store_vou_notes;
        $sales->updated_user = auth()->user()->user_id;

        if ($request->header_page == 'receiving') {
            $sales->store_vou_ref_after = $request->store_vou_ref_after;
        }

        if ($request->header_page == 'quote' || $request->header_page == 'inv') {
            $sales->store_vou_client_address = $request->store_vou_client_address;
            $sales->store_vou_client_mob = $request->store_vou_client_mob;
        }

        $sales_save = $sales->save();
        if (!$sales_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $sales->refresh()->uuid]);

    }

    public function deleteItem(Request $request)
    {
        $rules = [
            'uuid' => 'required|exists:sales_cars_dt,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $sales_details = SalesDetails::where('uuid', '=', $request->uuid)->first();
        $system_code = $sales_details->storeVouType->system_code;
        \DB::beginTransaction();
        if ($system_code == '104004') // if QOUATION CHECK CAR STATUS
        {
            $car = SalesCar::where('sales_cars_id', '=', $sales_details->store_vou_item_id)
                ->where('sales_car_status', '=', SystemCode::where('system_code', '=', '120002')->first()->system_code_id);
            if ($car->count()) {
                $car_update = $car->update(['sales_car_status' => SystemCode::where('system_code', '=', '120001')->first()->system_code_id]);
                if (!$car_update) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }
            } else {
                return \Response::json(['success' => false, 'msg' => 'لايمكن حذف المركبة من عرض السعر']);
            }

        }
        if ($system_code == '104005') // if DIRECT INVE CHECK CAR STATUS
        {
            $car = SalesCar::where('sales_cars_id', '=', $sales_details->store_vou_item_id)
                ->where('sales_car_status', '=', SystemCode::where('system_code', '=', '120004')->first()->system_code_id);
            if ($car->count()) {
                $car_update = $car->update(['sales_car_status' => SystemCode::where('system_code', '=', '120001')->first()->system_code_id]);
                if (!$car_update) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }
            } else {
                return \Response::json(['success' => false, 'msg' => 'لايمكن حذف المركبة من عرض السعر']);
            }

        }

        $sales_details->isdeleted = 1;
        $sales_details_save = $sales_details->save();

        if (!$sales_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = SalesCarController::updateHeaderTotal($sales_details->sales);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $sales_details->sales->itemSumTotal(),
            'total_sum_vat' => $sales_details->sales->itemSumVat(),
            'total_sum_net' => $sales_details->sales->itemSumNet(),
        ];


        //////////////journal
        // if ($sales_details->sales->journalHd) {
        //     $journal_controller = new JournalsController();
        //     $total_amount = $total['total_sum_net'];
        //     $vat_amount = $total['total_sum_vat'];
        //     $cc_voucher_id = $sales_details->sales->store_hd_id;
        //     $cost_center_id = 64;
        //     $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);

        // }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت الحذف بنجاح', 'data' => $sales_details, 'total' => $total]);

    }

    function checkStatus($StoreVouRefBefore)
    {
        $store_vou_type = $StoreVouRefBefore->storeVouType;
        $qty = $store_vou_type->system_code_filter;
        $store_vou_qnt_t_i_r = 'store_vou_qnt_t_i_r';

        $details = SalesDetails::where('store_hd_id', '=', $StoreVouRefBefore->store_hd_id)
            ->where(\DB::raw($qty), '!=', \DB::raw('store_vou_qnt_t_i_r'));

        if ($details->count()) {
            return \Response::json(['success' => false, 'msg' => 'keep request open ']);
        } else {
            return \Response::json(['success' => true, 'msg' => 'close request']);
        }
    }

    public function getBrandDTbyBrand(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $brand_dt = CarRentBrandDt::where('brand_id', '=', $request->brand_id)->get();

        return response()->json(['status' => 200, 'data' => $brand_dt]);

    }

    function getSalesByCode(Request $request)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $customer = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 2)->get();

        switch ($request->page) {

            case 'receiving':
                $vou_type = SystemCode::where('system_code', '=', '104002')->first()->system_code_id;
                $view = 'salesCar.purchase.receiving.show_data';
                $msg = 'تم استرداد امر الشراء بنجاح';
                $car_data = $request->car_data;
                $sales_request = Sales::where('company_id', $company->company_id)->where('store_hd_code', '=', $request->request_code)->where('store_vou_type', '=', $vou_type);
                if (!$sales_request->count()) {
                    return response()->json(['success' => false, 'msg' => 'الرجاء ادخال كود صالح']);
                }

                $sales_request = $sales_request->first();
                $page = $request->page;
                $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'sales_request', 'item_disc_type', 'customer', 'car_data', 'page'));
                return response()->json(['success' => true, 'msg' => $msg, 'view' => $view->render()]);
                break;

            case 'return':
                $vou_type = SystemCode::where('system_code', '=', '104003')->first()->system_code_id;
                $view = 'salesCar.purchase.return.show_data';
                $msg = 'تم استرداد ااذن الاستلام بنجاح';
                break;
            // SALES
            case 'inv':
                info('inv');
                $vou_type = SystemCode::where('system_code', '=', '104004')->first()->system_code_id;
                $view = 'salesCar.sales.inv.show_data';
                $msg = 'تم استرداد عرض الاسعار بنجاح';
                break;

            case 'return-sales':
                $vou_type = SystemCode::where('system_code', '=', '104005')->first()->system_code_id;
                $view = 'salesCar.return.show_data';
                $msg = 'تم استرداد فاتورة المبيعات بنجاح';
                break;

            case 'return-purcahse':
                $vou_type = SystemCode::where('system_code', '=', '104003')->first()->system_code_id;
                $view = 'salesCar.return.show_data';
                $msg = 'تم استرداد ااذن الاستلام بنجاح';
                break;

            case 'trans':

                $vou_type = SystemCode::where('system_code', '=', '104008')->first()->system_code_id;
                $view = 'salesCar.purchase.receiving.show_data';
                $msg = 'تم استرداد اذن التحويل بنجاح';
                $car_data = $request->car_data;
                $sales_request = Sales::where('company_id', $company->company_id)->where('store_hd_code', '=', $request->request_code)->where('store_vou_type', '=', $vou_type);
                if (!$sales_request->count()) {
                    return response()->json(['success' => false, 'msg' => 'الرجاء ادخال كود صالح']);
                }
                $sales_request = $sales_request->first();
                $page = $request->page;
                $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'sales_request', 'item_disc_type', 'customer', 'car_data', 'page'));
                return response()->json(['success' => true, 'msg' => $msg, 'view' => $view->render()]);

                break;

            case 'trans_from_request':
                $vou_type = SystemCode::where('system_code', '=', '104001')->first()->system_code_id;
                $view = 'salesCar.trans.trans.show_data';
                $msg = 'تم استرداد طلب الشراء بنجاح';
                break;


            default:
                $vou_type = SystemCode::where('system_code', '=', '104001')->first()->system_code_id;
                $view = 'salesCar.purchase.order.show_data';
                $msg = 'تم استرداد طلب الشراء بنجاح';

        }
        $page = $request->page;
        $sales_request = Sales::where('company_id', $company->company_id)->where('store_hd_code', '=', $request->request_code)->where('store_vou_type', '=', $vou_type);
        if (!$sales_request->count()) {
            return response()->json(['success' => false, 'msg' => 'الرجاء ادخال كود صالح']);
        }

        $sales_request = $sales_request->first();
        $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'sales_request', 'item_disc_type', 'customer', 'page'));
        return response()->json(['success' => true, 'msg' => $msg, 'view' => $view->render()]);
    }


    //    سند صرف
    public function addBondWithJournal(Request $request)
    {
        //return $request->all();
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();
        // return $request->all();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', $request->bond_method_type)->first();
        $transaction_type = $request->transaction_type ? $request->transaction_type : 81;
        $transaction_id = $request->transaction_id;
        $sales = Sales::find($request->transaction_id);
        $customer_id = $sales->store_acc_no;
        $customer_type = $request->customer_type;
        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';
        //  $total_amount = $request->bond_amount_total;
        $total_amount = $request->bond_amount_total;
        $bond_doc_type = SystemCode::where('system_code_id', $request->bond_doc_type)->first();
        $bond_ref_no = $request->bond_ref_no;
        $bond_notes = $request->bond_notes ? $request->bond_notes : '';
        $bond_account_id = $request->bond_acc_id;
        $j_add_date = Carbon::now();
        $bond_vat_amount = $request->bond_vat_amount ? $request->bond_vat_amount : 0;
        //  $bond_vat_amount = $request->bond_vat_rate * $request->bond_amount_credit;

        $bond_vat_rate = $request->bond_vat_rate ? $request->bond_vat_rate : 0;

        $bond = $bond_controller->addCashBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id,
            $bond_vat_amount, $bond_vat_rate, '', $j_add_date);


        $journal_controller = new JournalsController();
        $cost_center_id = 81;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
//        $journal_category_id = 14;

        if ($transaction_type == 81) { //////////
            $journal_category_id = 72;
            $account_type = 56001;
            $customer_name_full_ar = $sales->vendor->customer_name_full_ar;
        }

        if ($transaction_type == 84) {
            //////// مرتجع عميل استيراد فاتوره مبيعات
            $journal_category_id = 78;
            $account_type = 56002;
            $customer_name_full_ar = $sales->customer->customer_name_full_ar;
        }


        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = ' سند صرف ' . ' ' . $bond->bond_code . ' ' . ' اذن دخول سياره ' . ' ' . $request->bond_ref_no . ' ' . $customer_name_full_ar;
        $customer_notes = ' سند صرف  ' . ' ' . $bond->bond_code . ' ' . ' اذن دخول سياره ' . ' ' . $request->bond_ref_no . ' ' . $customer_name_full_ar;
        $cash_notes = '  سند صرف  ' . ' ' . $bond->bond_code . ' ' . ' اذن دخول سياره ' . ' ' . $request->bond_ref_no . ' ' . $customer_name_full_ar;
        $message = $journal_controller->AddCashJournal($account_type, $customer_id, $bond_doc_type,
            $total_amount, 0, $cc_voucher_id, $payment_method, $bank_id,
            $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);

        if (isset($message)) {
            return back()->with(['error' => $message]);
        }


        $sales->update([
//            'bond_id' => $bond->bond_id,
//            'bond_code' => $bond->bond_code,
//            'bond_date' => Carbon::now(),
            'store_vou_payment' => $sales->store_vou_payment + $total_amount
        ]);


        \DB::commit();

        if ($transaction_type == 84) {
            return back()->with(['success' => 'تم اضافه السند']);
        } else {
            return redirect()->route('sales-car-receiving.edit', $sales->uuid)->with(['success' => 'تم اضافه السند']);
        }
    }


    ///سند قبض       فاتوره بيع + مرتجع مورد(استيراد اذن استلام)
    public
    function addBondWithJournal2(Request $request)
    {
        //  return $request->all();
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', $request->bond_method_type)->first();
//        $transaction_type = 83;
        $transaction_type = $request->transaction_type ? $request->transaction_type : 83; ////83 في حاله فاتوره البيع
        $transaction_id = $request->transaction_id;
        $sales = Sales::find($request->transaction_id);
        $customer_id = $sales->store_acc_no;
        $customer_type = $request->customer_type;
        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';

        $total_amount = $request->bond_amount_credit;
        $bond_doc_type = SystemCode::where('company_id', $company->company_id)->where('system_code_id', $request->bond_doc_type)->first();
        $bond_ref_no = $request->bond_ref_no;
        $bond_notes = $request->bond_notes ? $request->bond_notes : '';
        $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

//
        $journal_controller = new JournalsController();
        $cost_center_id = 53;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
        if ($transaction_type == 83) { //////////فاتوره بيع سياره
            $journal_category_id = 76;
            $account_type = 56002;
            $customer_name_full_ar = $sales->customer->customer_name_full_ar;
        }

        if ($transaction_type == 84) {   //////////////مرتجه مورد استيراد اذن استلام
            $journal_category_id = 74;
            $account_type = 56001;
            $customer_name_full_ar = $sales->vendor->customer_name_full_ar;
        }


        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = ' سند قبض ' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $customer_name_full_ar;
        $customer_notes = '  سند قبض  ' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $customer_name_full_ar;
        $cash_notes = '  سند قبض' . ' ' . $bond->bond_code . ' ' . ' فاتوره ' . ' ' . $request->bond_ref_no . ' ' . $customer_name_full_ar;
        $message = $journal_controller->AddCaptureJournal($account_type, $customer_id, $bond_doc_type,
            $total_amount, $cc_voucher_id, $payment_method, $bank_id,
            $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes);

        if (isset($message)) {
            return back()->with(['error' => $message]);
        }

        $sales->update([
//            'bond_id' => $bond->bond_id,
//            'bond_code' => $bond->bond_code,
//            'bond_date' => Carbon::now(),
            'store_vou_payment' => $sales->store_vou_payment + $request->bond_amount_credit
        ]);


        \DB::commit();

        return back()->with(['success' => 'تم اضافه السند']);

    }

    public function storeBond(Request $request)
    {

        // return $request->all();
        $bond = Bond::find($request->bond_id);
        $sales = sales::find($request->sales_id);

        $bond->bond_ref_no = $sales->store_hd_code;
        $bond->transaction_type = 83;
        $bond->transaction_id = $sales->store_hd_id;
        $bond->bond_acc_id = $sales->customer->customer_account_id;
        $bond->save();

        $sales->store_vou_payment = $sales->store_vou_payment + $bond->bond_amount_debit;
//        $sales->bond_id = $bond->bond_id;
//        $sales->bond_code = $bond->bond_code;
//        $sales->bond_date = $bond->bond_date;
        $sales->save();
        return back()->with(['success' => 'تم ربط السند']);
    }


}
