<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Bond;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\StoreItem;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\CompanyMenuSerial;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\InvoiceQR\InvoiceDateElement;

class StoreTransferController extends Controller
{
    //
    public function index(Request $request, $page)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = ['company' => $company, 'branch' => session('branch')];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();

        info('index');

        switch ($page) {
            case 'trans':
                $view = 'store.trans.trans.index';
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
        $branch_list = Branch::where('company_group_id', $company->company_group_id)->get();
        $warehouses_type_list = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->where('customer_category', '=', 2)->get();

        info('data');

        switch ($page) {
            case 'trans':
                $view = 'store.trans.trans.data';
                break;

            default:
                abort(404);
        }

        $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'item_disc_type', 'customer'));
        return \Response::json(['view' => $view->render(), 'success' => true]);
    }


    public function dataTable(Request $request, $companyId, $page)
    {

        switch ($page) {
            case 'trans':
                $vou_type = SystemCode::where('system_code', '=', '62008')->first()->system_code_id;
                $action_view = 'store.trans.trans.Actions.actions';
                break;

            default:
                abort(404);
        }


        $purchase = Purchase::where('company_id', $companyId);

        if ($request->search['branch_id']) {
            info($request->search['branch_id']);
            $purchase = $purchase->where('branch_id', '=', $request->search['branch_id']);
        }
        if ($request->search['warehouses_type']) {
            $purchase = $purchase->where('store_category_type', '=', $request->search['warehouses_type']);
        }

        $purchase = $purchase->where('store_vou_type', '=', $vou_type)->orderBy('created_date', 'desc')->get();


        return Datatables::of($purchase)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($action_view) {
                return (string)view($action_view, compact('row'));
            })
            ->addColumn('store_vou_ref_1', function ($row) {
                return $row->sourceBranch->getBranchName();
            })
            ->addColumn('store_vou_ref_2', function ($row) {
                return $row->sourceStore->getSysCodeName();
            })
            ->addColumn('store_vou_ref_3', function ($row) {
                return $row->destBranch->getBranchName();
            })
            ->addColumn('store_vou_ref_4', function ($row) {
                return $row->destStore->getSysCodeName();
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

    public function store(Request $request, $page)
    {
        switch ($page) {
            case 'trans':
                $vou_type = SystemCode::where('system_code', '=', '62008')->first();
                return StoreTransferController::storeHeader($request, $vou_type);
                break;

            case 'trans_form_req':
                $vou_type = SystemCode::where('system_code', '=', '62008')->first();
                return StoreTransferController::storeAll($request, $vou_type);
                break;

            default:
                abort(404);
        }
    }

    public function storeHeader(Request $request, $type)
    {
        info('test');
        $rules = [

            'source_branch' => 'required',
            'source_store' => 'required',
            'dest_branch' => 'required|different:source_branch',
            //  'dest_store'  => 'required|same:source_store',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        //gnerate mntns cards no
        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 67);
        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن التحويل يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'STP-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
        $store_vou_status = SystemCode::where('system_code', '=', '125001')->first()->system_code_id;
        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');


        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch->branch_id;

        $purchase->store_category_type = SystemCode::where('system_code', '=', $request->source_store)->first()->system_code_id;
        $purchase->store_vou_type = $type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $request->source_branch;

        $purchase->store_vou_ref_1 = $request->source_branch;
        $purchase->store_vou_ref_2 = SystemCode::where('system_code', '=', $request->source_store)->first()->system_code_id;

        $purchase->store_vou_ref_3 = $request->dest_branch;
        $purchase->store_vou_ref_4 = SystemCode::where('system_code', '=', $request->dest_store)->first()->system_code_id;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;

        $purchase_save = $purchase->save();
        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);


    }

    public function edit(Request $request, $uuid, $page)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $purchase = Purchase::where('uuid', $uuid)->first();
        $source_itemes = Storeitem::where('company_id', $purchase->company_id)->where('branch_id', '=', $purchase->store_vou_ref_1)->where('item_category', '=', $purchase->store_vou_ref_2)->where('item_balance', '>', 0)->get();
        $bonds_cash = Bond::where('bond_ref_no', $purchase->store_hd_code)
            ->where('bond_type_id', 2)->latest()->get();
        switch ($page) {
            case 'trans':
                $vou_type = SystemCode::where('system_code', '=', '62008')->first();
                $view = 'store.trans.trans.edit_trans';
                break;

            default:
                abort(404);
        }

        return view($view, compact('company', 'companies', 'purchase', 'source_itemes', 'bonds_cash'));
    }

    public function storeItem(Request $request, $page)
    {
        switch ($page) {

            case 'trans':
                $header = Purchase::where('uuid', '=', $request->purchase_uuid)->first();
                return StoreTransferController::storeItemData($request, $header);
                break;

            default:
                abort(404);

        }
    }

    public function storeItemData(Request $request, $header)
    {
        $rules = [
            'purchase_uuid' => 'required',
            'item_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        if ($header->store_vou_status != SystemCode::where('system_code', '=', '125001')->first()->system_code_id) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن الاضافة الطلب مكتمل او ملغي']);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        $purchase_details = new PurchaseDetails();

        $item_data = json_decode($request->item_table_data, true);
        \DB::beginTransaction();
        $is_added_befor = PurchaseDetails::where('store_hd_id', $header->store_hd_id)->where('store_vou_item_id', '=', $item_data['store_vou_item_id'])->where('isdeleted', '=', 0);
        if ($is_added_befor->count() > 0) {
            return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا الصنف مسبقا..!']);
        }

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
        $purchase_details->store_vou_qnt_t_o = $item_data['store_vou_qnt_t_o'];
        $purchase_details->store_vou_loc = $item_data['store_vou_loc'];
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


    public function deleteItem(Request $request)
    {
        info($request->uuid);;
        $rules = [
            'uuid' => 'required|exists:store_dt,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $purchase_details = PurchaseDetails::where('uuid', '=', $request->uuid)->first();
        if ($purchase_details->purchase->store_vou_status != SystemCode::where('system_code', '=', '125001')->first()->system_code_id) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن الحذف الطلب مكتمل او ملغي']);
        }

        $purchase_details->isdeleted = 1;
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

        return \Response::json(['success' => true, 'msg' => 'تمت الحذف بنجاح', 'data' => $purchase_details, 'total' => $total]);

    }

    public function storeAll(Request $request, $type)
    {
        $rules = [
            'store_vou_ref_before' => 'required|exists:store_hd,uuid',
            'item_data' => 'required',
            'from_req_dest_branch' => 'required',
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
            case '62008':
                $qty_field = 'store_vou_qnt_t_o';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 67);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن التحويل يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'STP-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('system_code', '=', '125001')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('system_code', '=', '125002')->first()->system_code_id;
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

        $purchase->store_vou_ref_1 = $store_vou_ref_before->branch_id;
        $purchase->store_vou_ref_2 = $store_vou_ref_before->store_category_type;

        $purchase->store_vou_ref_3 = $request->from_req_dest_branch;
        $purchase->store_vou_ref_4 = $store_vou_ref_before->store_category_type;

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
                    $qty_field => $d[$qty_field],
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
                    if ($store_item->item_balance < $d[$qty_field]) {
                        return \Response::json(['success' => false, 'msg' => 'الكمية الحالية غير كافية']);
                    }
                    $store_item->item_balance = $store_item->item_balance - $d[$qty_field];
                    $store_item->last_price_sales = $store_item->item_price_sales;
                    $store_item->item_price_sales = (floatval($d['store_vou_item_price_unit'])) / 2;
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

}




