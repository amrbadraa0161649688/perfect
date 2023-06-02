<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\JournalType;
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
use App\Models\CompanyMenuSerial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
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
                $view = 'store.purchase.request.index';
                break;

            case 'order':
                $view = 'store.purchase.order.index';
                break;

            case 'receiving':

                $view = 'store.purchase.receiving.index';
                break;

            case 'return':
                $view = 'store.purchase.return.index';
                break;

            default:
                abort(404);
        }

        return view($view, compact('companies', 'branch_lits', 'page', 'warehouses_type_lits', 'user_data'));
    }

    public function data(Request $request, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id) ? request()->company_id : $company_auth_id);
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('customer_category', 1)->where('company_group_id', '=', $company->company_group_id)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();


        switch ($page) {
            case 'request':
                $view = 'store.purchase.request.data';
                break;

            case 'order':
                $view = 'store.purchase.order.data';
                break;

            case 'receiving':
                $view = 'store.purchase.receiving.data';
                break;

            case 'return':
                $view = 'store.purchase.return.data';
                break;

            default:
                abort(404);
        }

        $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'item_disc_type'));
        return \Response::json(['view' => $view->render(), 'success' => true]);
    }

    public function dataTable(Request $request, $companyId, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;

        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        switch ($page) {
            case 'request':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62001')->first()->system_code_id;
                $action_view = 'store.purchase.request.Actions.actions';
                break;

            case 'order':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62002')->first()->system_code_id;
                $action_view = 'store.purchase.order.Actions.actions';
                break;

            case 'receiving':
                $vou_type = SystemCode::where('company_id', $company->company_id)->whereIn('system_code', ['62003', '62009'])->get()->pluck('system_code_id');
                $action_view = 'store.purchase.receiving.Actions.actions';
                break;

            case 'return':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62004')->first()->system_code_id;
                $action_view = 'store.purchase.return.Actions.actions';
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

//        $purchase = $purchase->whereIn('store_vou_type', (array)$vou_type)->orderBy('created_date', 'desc')
//            ->select('branch_id', 'store_acc_no', 'store_vou_pay_type', 'store_category_type', 'created_date',
//                'store_vou_amount', 'bond_id', 'store_vou_payment', 'store_vou_status', 'uuid', 'company_id',
//                'store_hd_code')->get();
//
        $purchase = $purchase->whereIn('store_vou_type', (array)$vou_type)->orderBy('created_date', 'desc')->get();


        return Datatables::of($purchase)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($action_view) {
                return (string)view($action_view, compact('row'));
            })
            ->addColumn('branch', function ($row) {
                return optional($row->branch)->getBranchName();

            })
            ->addColumn('vendor', function ($row) {
                return optional($row->vendor)->getCustomerName();
            })
            ->addColumn('payment_method', function ($row) {

                return optional($row->paymentMethod)->getSysCodeName();
            })
            ->addColumn('warahouse_type', function ($row) {

                return $row->storeCategory ? $row->storeCategory->getSysCodeName() : '';

            })
            ->addColumn('store_vou_date', function ($row) {
                return $row->created_date->format('Y-m-d H:m');
            })
            ->addColumn('bond', function ($row) {
                return $row->bond ? $row->bond_code : 'لا يوجد سند';
            })
            ->addColumn('payment', function ($row) {
                return $row->store_vou_payment ? $row->store_vou_payment : 0;
            })
            ->addColumn('store_vou_status', function ($row) {
                return optional($row->status)->getSysCodeName();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();
        switch ($page) {
            case 'request':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62001')->first();
                return PurchaseController::storeHeader($request, $vou_type);
                break;

            case 'order':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62002')->first();
                return PurchaseController::storeOrder($request, $vou_type);
                break;

            case 'direct_order':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62002')->first();
                return PurchaseController::storeHeader($request, $vou_type);
                break;

            case 'receiving':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62003')->first();
                return PurchaseController::storeOrder($request, $vou_type);
                break;

            case 'new_receving':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62003')->first();
                return PurchaseController::storeHeader($request, $vou_type);
                break;

            case 'return':

                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62004')->first();
                return PurchaseController::storeOrder($request, $vou_type);
                break;

            case 'trans':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62009')->first();
                return PurchaseController::storeOrder($request, $vou_type);
                break;

            default:
                abort(404);
        }
    }

    public function storeItem(Request $request, $page)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id)) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        switch ($page) {
            case 'request':

                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62001')->first()->system_code_id;
                $header = Purchase::where('uuid', '=', $request->purchase_uuid)->first();
                return PurchaseController::storeItemData($request, $header, $page);
                break;

            case 'direct_order':

                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62002')->first()->system_code_id;
                $header = Purchase::where('uuid', '=', $request->purchase_uuid)->first();
                return PurchaseController::storeItemData($request, $header, $page);
                break;

            case 'receiving':
                $view = 'store.purchase.receiving.index';
                break;

            case 'new_receving':

                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62003')->first()->system_code_id;
                $header = Purchase::where('uuid', '=', $request->purchase_uuid)->first();


                return PurchaseController::storeItemData($request, $header, $page);
                break;

            case 'return':
                $view = 'store.purchase.return.index';
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

        if ($type->system_code == '62001') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 62);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم طلب الشراء يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'REQ-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id;
        } elseif ($type->system_code == '62002') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 92);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم امر الشراء يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'PO-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id;
        } elseif ($type->system_code == '62003') {
            $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 64);
            if (!$current_serial->count()) {
                return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن الاستلام يرجي التواصل مع مدير النظام']);
            }
            $current_serial = $current_serial->first();
            $new_serial = 'ER-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125002')->first()->system_code_id;
        } else {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }


        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch->branch_id;

        $purchase->store_category_type = SystemCode::where('system_code', '=', $request->store_category_type)
            ->where('company_id', $company->company_id)->first()->system_code_id;

        $purchase->store_vou_type = $type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $request->store_acc_no;
        $purchase->store_acc_name = $request->store_acc_name;
        $purchase->store_acc_tax_no = $request->store_acc_tax_no;
        $purchase->store_vou_pay_type = $request->store_vou_pay_type;
        $purchase->store_vou_notes = $request->store_vou_notes;
        $purchase->store_vou_ref_after = $request->store_vou_ref_after;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;
        $purchase->vou_datetime = $request->vou_datetime;


        $purchase_save = $purchase->save();

        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);

    }

    public function updateHeaderTotal($header)
    {
        $header = $header;
        $header->store_vou_amount = $header->itemSumTotal();
        $header->store_vou_desc = $header->itemSumDisc();
        $header->store_vou_vat_amount = $header->itemSumVat();
        $header->store_vou_total = $header->itemSumNet();

        $header_save = $header->save();

        if (!$header_save) {
            return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
        }

        //////////////journal
        if ($header->storeVouType->system_code == 62003 && $header->store_vou_ref_before) {
            ///62003///استيراد من امر شراء

            $journal_controller = new JournalsController();
            $total_amount = $header->store_vou_total;
            $vat_amount = $header->store_vou_vat_amount;
            $cc_voucher_id = $header->store_hd_id;
            $cost_center_id = 64;

            if ($header->journal_hd_id) {
                $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);
            } else {
                $supplier_id = $header->store_acc_no;

                $purchasing_notes = '  قيد  مشتريات رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $vat_notes = ' ضريبه قيمه مضافه مشتريات  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                $supplier_notes = 'مشتريات  مورد   رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;

                $notes = '  قيد مشتريات  رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;

                $journal_category_id = 35;
                // $journal_category_id = JournalType::where('journal_types_code', 35)
                //     ->where('company_group_id', $header->company->company_group_id)->first()->journal_types_code;

                $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
                    $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
                    $journal_category_id, $notes);

            }
        }

        if ($header->storeVouType->system_code == 62004) { ////مرتجع مورد
            if ($header->vendor) {
                $journal_controller = new JournalsController();
                $total_amount = $header->store_vou_total;
                $vat_amount = $header->store_vou_vat_amount;
                $cc_voucher_id = $header->store_hd_id;
                $cost_center_id = 66;

                if ($header->journal_hd_id) {
                    $journal_controller->updateReturnPurchasingOrder($total_amount, $vat_amount,
                        $cc_voucher_id, $cost_center_id);
                } else {
                    $supplier_id = $header->store_acc_no;
                    $customer_id = '';
                    $purchasing_notes = '   مرتجع مشتريات مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $vat_notes = '   ضريبه قيمه مضافه مرتجع مشتريات مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $supplier_notes = '   مرتجع مشتريات  مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
                    $notes = '   مرتجع مشتريات  مورد رقم' . ' ' . $header->store_hd_code . ' ' . $header->store_acc_name;
//                    $journal_category_id = 46;
                    $journal_category_id = JournalType::where('journal_types_code', 46)
                        ->where('company_group_id', $header->company->company_group_id)->first()->journal_types_code;

                    $journal_controller->addReturnPurchasingOrder($total_amount, $vat_amount, $supplier_id,
                        $customer_id, $supplier_notes, $cost_center_id, $cc_voucher_id, $journal_category_id,
                        $purchasing_notes, $vat_notes, $notes);

                }
            }
        }


        if ($header->storeVouType->system_code == 62009) {
            $journal_controller = new JournalsController();
            $cost_center_id = 64;
            $cc_voucher_id = $header->store_hd_id;
            $total_amount = $header->store_vou_amount;
            $notes = 'قيد اذن تحويل رقم ' . $header->store_hd_code;
            $cost_notes = 'قيد اذن تحويل رقم ' . $header->store_hd_code;
            $store_vou_ref_before = $header->store_vou_ref_before;
            $transfer_before_type_code = 62008;
            //return $store_vou_ref_before;

            $journal_controller->storeTransferPermission($cost_center_id, $cc_voucher_id, $total_amount,
                35, $notes, $cost_notes, $store_vou_ref_before, $transfer_before_type_code);
        }

        return ['success' => true, 'msg' => 'تمت العملية  بنجاح'];
    }

    public function storeItemData(Request $request, $header, $page)
    {

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        $rules = [
            'purchase_uuid' => 'required',
            'item_table_data' => 'required',
        ];

        $validator =
            Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        // return $header->store_vou_status;

        // if ($header->store_vou_status != SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id) {
        //    return \Response::json(['success' => false, 'msg' => 'لايمكن الاضافة الطلب مكتمل او ملغي']);
        // }

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

        $purchase_details = new PurchaseDetails();
        $item_data = json_decode($request->item_table_data, true);

        $is_added_befor = PurchaseDetails::where('store_hd_id', $header->store_hd_id)->where('store_vou_item_id', '=', $item_data['store_vou_item_id'])->where('isdeleted', '=', 0);
        if ($is_added_befor->count() > 0) {
            return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا الصنف مسبقا..!']);
        }

        \DB::beginTransaction();


        $header->store_acc_name = $request->store_acc_name;
        $header->store_acc_tax_no = $request->store_acc_tax_no;
        $header->store_vou_pay_type = $request->store_vou_pay_type;
        $header->store_vou_ref_after = $request->store_vou_ref_after;
        $header->vou_datetime = $request->vou_datetime;
        $header->store_vou_notes = $request->store_vou_notes;
        $header->store_vou_desc = $request->store_vou_desc;
        $header->save();

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

        $purchase_details->{$qty_field} = $item_data[$qty_field];
        $purchase_details->store_vou_loc = $item_data['store_vou_loc'];
        // $purchase_details->store_vou_item_price_cost = $item_data['store_vou_item_price_cost'];
        $purchase_details->store_vou_item_price_unit = $item_data['store_vou_item_price_unit'];
        $purchase_details->store_vou_item_total_price = $item_data['store_vou_item_total_price'];
        $purchase_details->store_vou_vat_rate = $item_data['store_vou_vat_rate'];
        $purchase_details->store_vou_vat_amount = $item_data['store_vou_vat_amount'];
        $purchase_details->store_vou_price_net = $item_data['store_vou_price_net'];
        if (isset($item_data['store_vou_item_check'])) {
            $purchase_details->store_vou_item_check = $item_data['store_vou_item_check'];
        }
        //$request->emp_is_bank_payment ? 1 : 0

        // return $item_data;
        $purchase_details_save = $purchase_details->save();

        if (isset($item_data['item_stor_dt_serial'])) {
            foreach ($item_data['item_stor_dt_serial'] as $item_stor_dt_serial) {
                if ($item_stor_dt_serial != '') {
                    StoreDtItem::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'store_hd_id' => $header->store_hd_id,
                        'store_dt_id' => $purchase_details->store_dt_id,
                        'item_id_dt' => $purchase_details->store_vou_item_id,
                        'store_vou_code' => $header->store_hd_code,
                        'stor_vou_date' => Carbon::now(),
                        'stor_vou_qut_in' => 1,
                        'item_stor_dt_serial' => $item_stor_dt_serial,
                        'created_by' => auth()->user()->user_id
                    ]);
                }
            }
        }

        if (!$purchase_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        if ($page == 'new_receving') {
            $store_item = StoreItem::where('item_id', $item_data['store_vou_item_id'])->first();
            $brofet_rate = $store_item->itemCategory->system_code_tax_perc;

            $store_item->item_balance = $store_item->item_balance + $item_data[$qty_field];
            $store_item->old_price_cost = $store_item->item_price_cost;
            if ($store_item->item_price_cost == 0) {
                $store_item->item_price_cost = (floatval($item_data['store_vou_item_price_unit']));
            } else {
                $store_item->item_price_cost = ($store_item->item_price_cost + (floatval($item_data['store_vou_item_price_unit']))) / 2;
            }

            $store_item->old_price_sales = $store_item->item_price_sales;
            $store_item->item_price_sales = $store_item->item_price_cost + ($store_item->item_price_cost * $brofet_rate);
            $store_item->updated_user = auth()->user()->user_id;
            $store_item->updated_date = Carbon::now();

            $store_item_save = $store_item->save();

            if (!$store_item_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }
        }

        $update_total = PurchaseController::updateHeaderTotal($purchase_details->purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $purchase_details->purchase->itemSumTotal(),
            'total_sum_vat' => $purchase_details->purchase->itemSumVat(),
            'total_sum_net' => $purchase_details->purchase->itemSumNet(),
        ];

        info($purchase_details->purchase->storeVouType->system_code);

        if ($purchase_details->purchase->storeVouType->system_code == 62003 && !$header->store_vou_ref_before) { ////اذن استلام جديد
            //////////////journal
            $journal_controller = new JournalsController();
            $total_amount = $total['total_sum_net'];
            $vat_amount = $total['total_sum_vat'];
            $cc_voucher_id = $purchase_details->purchase->store_hd_id;
            $cost_center_id = 64;
            $notes = '  قيد مشتريات اذن استلام  رقم' . ' ' . $purchase_details->purchase->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
            if ($purchase_details->purchase->journal_hd_id) {
                $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);
            } else {
                $supplier_id = $purchase_details->purchase->store_acc_no;
                $purchasing_notes = ' قيد مشتريات اذن الاستلام رقم' . ' ' . $purchase_details->purchase->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
                $vat_notes = '   ضريبه قيمه مضافه مشتريات اذن الاستلام رقم' . ' ' . $purchase_details->purchase->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
                $supplier_notes = '  مشتريات  مورد اذن الاستلام رقم' . ' ' . $purchase_details->purchase->store_hd_code . ' ' . $purchase_details->purchase->store_acc_name;
//                $journal_category_id = 35; ////فاتوره مشتريات مستودع من اذن استلام
                $journal_category_id = JournalType::where('journal_types_code', 35)
                    ->where('company_group_id', $purchase_details->purchase->company->company_group_id)->first()->journal_types_id;

                $message = $journal_controller->addPurchasingJournal($total_amount, $vat_amount, $supplier_id,
                    $purchasing_notes, $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
                    $journal_category_id, $notes);

                if (isset($message)) {
                    return \Response::json(['success' => false, 'msg' => $message]);
                }


            }
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase_details->refresh()->uuid, 'total' => $total]);

    }

    public function deleteItem(Request $request)
    {
        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

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
        $purchase_details->isdeleted = 1;
        $purchase_details_save = $purchase_details->save();

        if (!$purchase_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        if ($purchase_details->storeVouType->system_code == '62003') {
            $store_item = StoreItem::where('item_id', $purchase_details->store_vou_item_id)->first();

            if ($store_item->item_balance < $purchase_details->store_vou_qnt_i) {
                return \Response::json(['success' => false, 'msg' => 'لايوجد كمية كافية  لايمكن اتمام العملية']);
            }
            $store_item->item_balance = $store_item->item_balance - $purchase_details->store_vou_qnt_i;
            $store_item->item_price_cost = $store_item->old_price_cost;
            $store_item->item_price_sales = $store_item->old_price_sales;
            $store_item->updated_user = auth()->user()->user_id;
            $store_item->updated_date = Carbon::now();

            $store_item_save = $store_item->save();

            if (!$store_item_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }


        }

        $update_total = PurchaseController::updateHeaderTotal($purchase_details->purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [
            'total_sum' => $purchase_details->purchase->itemSumTotal(),
            'total_sum_vat' => $purchase_details->purchase->itemSumVat(),
            'total_sum_net' => $purchase_details->purchase->itemSumNet(),
        ];

        //////////////journal
        if ($purchase_details->purchase->journalHd) {
            $journal_controller = new JournalsController();
            $total_amount = $total['total_sum_net'];
            $vat_amount = $total['total_sum_vat'];
            $cc_voucher_id = $purchase_details->purchase->store_hd_id;
            $cost_center_id = 64;
            $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);

        }


        return \Response::json(['success' => true, 'msg' => 'تمت الحذف بنجاح', 'data' => $purchase_details, 'total' => $total]);

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

        $warehouses_type_lits = SystemCode::where('company_id', $company->company_id)->where('system_code_id', '=', $purchase->store_category_type)->get();
        $unit_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 35)->get();

        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $system_code_types = [];


        $bonds_cash = [];
        $bonds_capture = [];

        $attachment_types = SystemCode::where('sys_category_id', 11)->get();
        $attachments = Attachment::where('transaction_id', $purchase->store_hd_id)->where('app_menu_id', 64)
            ->where('attachment_type', '!=', 2)->get();

        $notes = Note::where('transaction_id', $purchase->store_hd_id)->where('app_menu_id', 64)->get();

        $journals = DB::table('journal_header')
            ->join('journal_details', function ($join) use ($purchase) {
                $join->on('journal_header.journal_hd_id', '=', 'journal_details.journal_hd_id')
                    ->where('journal_details.cc_voucher_id', '=', $purchase->store_hd_id)
                    ->where('journal_details.cost_center_id', '=', 64);
            })
            ->join('system_codes', 'system_codes.system_code_id', '=', 'journal_header.journal_status')
            ->join('users', 'users.user_id', '=', 'journal_header.journal_user_entry_id')
            ->get();


        switch ($page) {
            case 'request':
                $view = 'store.purchase.request.edit_request';
                break;

            case 'order':
                $view = 'store.purchase.order.edit_order';
                break;

            case 'direct_order':
                $view = 'store.purchase.order.edit_direct_order';
                break;

            case 'receiving':
                //       انواع المصروفات
                $system_code_types = SystemCode::where('sys_category_id', 59)
                    ->where('company_group_id', $company->company_group_id)->get();
                $bonds_cash = Bond::where('bond_ref_no', $purchase->store_hd_code)
                    ->where('bond_type_id', 2)->latest()->get();

                $bonds_capture = Bond::where('bond_ref_no', $purchase->store_hd_code)
                    ->where('bond_type_id', 1)->latest()->get();


                $view = 'store.purchase.receiving.edit_receive';
                break;

            case 'new_receving':
                $view = 'store.purchase.receiving.create_new_receive';
                break;

            case 'new_receiving':
                $view = 'store.purchase.receiving.edit_receive';
                break;

            case 'return':
                //       انواع الايرادات
                $system_code_types = SystemCode::where('sys_category_id', 58)
                    ->where('company_group_id', $company->company_group_id)->get();
                $view = 'store.purchase.return.edit_return';
                break;

            default:
                abort(404);
        }

        return view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list',
            'payemnt_method_list', 'vendor_list', 'purchase', 'itemes', 'warehouses_type_lits', 'unit_lits',
            'system_code_types', 'payment_methods', 'banks', 'attachment_types', 'attachments', 'notes', 'journals',
            'bonds_cash', 'bonds_capture'));
    }


    function getPurchaseByCode(Request $request)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = isset(request()->company_id) ? request()->company_id : $company_auth_id;
        $company = Company::where('company_id', $company_id)->first();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $warehouses_type_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 55)->get();
        $payemnt_method_list = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('sys_category_id', '=', 57)->get();
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 1)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $customer = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 9)->get();
        $page = $request->page;
        switch ($request->page) {

            case 'receiving':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62002')->first()->system_code_id;
                $view = 'store.purchase.receiving.show_data';
                $msg = 'تم استرداد امر الشراء بنجاح';
                break;

            case 'return':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62003')->first()->system_code_id;
                $view = 'store.purchase.return.show_data';
                $msg = 'تم استرداد ااذن الاستلام بنجاح';
                break;
            // SALES
            case 'inv':
                info('inv');
                $customer = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 9)->get();
                $vou_type = SystemCode::where('company_id', '=', $company->company_id)->where('system_code', '=', '62005')->first()->system_code_id;
                $view = 'store.sales.inv.show_data';
                $msg = 'تم استرداد عرض الاسعار بنجاح';
                break;
            case 's-return':
                $customer = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_category', '=', 9)->get();
                $vou_type = SystemCode::where('company_id', '=', $company->company_id)->where('system_code', '=', '62006')->first()->system_code_id;
                $view = 'store.sales.return.show_data';
                $msg = 'تم استرداد فاتورة المبيعات بنجاح';
                break;

            case 'trans':
                $vou_type = SystemCode::where('company_id', '=', $company->company_id)->where('system_code', '=', '62008')->first()->system_code_id;
                $view = 'store.purchase.receiving.show_trans_data';
                $msg = '1تم استرداد اذن التحويل بنجاح';
                break;

            case 'trans_from_request':
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62001')->first()->system_code_id;
                $view = 'store.trans.trans.show_data';
                $msg = 'تم استرداد طلب الشراء بنجاح';
                break;


            default:
                $vou_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '62001')->first()->system_code_id;
                $view = 'store.purchase.order.show_data';
                $msg = 'تم استرداد طلب الشراء بنجاح';

        }
        info($request->page);
        $purchase_request = Purchase::where('company_group_id', '=', $company->company_group_id)->where('store_hd_code', '=', $request->request_code);
        if (!$purchase_request->count()) {
            return response()->json(['success' => false, 'msg' => 'الرجاء ادخال كود ']);
        }

        $purchase_request = $purchase_request->first();
        $view = view($view, compact('company', 'companies', 'branch_list', 'warehouses_type_list', 'payemnt_method_list', 'vendor_list', 'purchase_request', 'item_disc_type', 'customer', 'page'));
        return response()->json(['success' => true, 'msg' => $msg, 'view' => $view->render()]);

    }

    function checkStatus($StoreVouRefBefore)
    {
        $store_vou_type = $StoreVouRefBefore->storeVouType;
        $qty = $store_vou_type->system_code_filter;
        $store_vou_qnt_t_i_r = 'store_vou_qnt_t_i_r';

        $details = PurchaseDetails::where('store_hd_id', '=', $StoreVouRefBefore->store_hd_id);
        //  ->where(\DB::raw($qty), '!=', \DB::raw('store_vou_qnt_t_i_r'));

        if ($details->count()) {
            return \Response::json(['success' => false, 'msg' => 'keep request open ']);
        } else {
            return \Response::json(['success' => true, 'msg' => 'close request']);
        }
    }

    public function storeOrder(Request $request, $type)
    {

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

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

        $store_acc_name = $store_vou_ref_before->store_acc_name;
        $store_acc_tax_no = $store_vou_ref_before->store_acc_tax_no;
        $store_vou_pay_type = $store_vou_ref_before->store_vou_pay_type;
        $store_acc_no = $store_vou_ref_before->store_acc_no;

        //gnerate code
        switch ($type->system_code) {
            case '62002':
                info('order');
                $qty_field = 'store_vou_qnt_p';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 92);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم امر الشراء يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'PO-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                break;

            case '62003':
                info('Enternse purcahse');
                $qty_field = 'store_vou_qnt_i';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', $branch)->where('app_menu_id', 64);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن الاستلام يرجي التواصل   ']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'ER-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('system_code', '=', '125002')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('system_code', '=', '125002')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                break;

            case '62004':
                info('purcahse return');
                $qty_field = 'store_vou_qnt_i_r';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 66);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم مرتجع المورد يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'PR-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('system_code', '=', '125002')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('system_code', '=', '125003')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                break;

            case '62009':

                if ($store_vou_ref_before->store_vou_ref_3 != session('branch')->branch_id) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن اتمام عملية الاستلام من فرع غير الفرع المستلم']);
                }

                info('Enternse purcahse From trasnfer');
                $qty_field = 'store_vou_qnt_t_i';
                $branch_id = $store_vou_ref_before->store_vou_ref_3;
                $store_acc_no = $request->store_acc_no_trans;
                $store_acc_name = $request->store_acc_name_trans;
                $store_acc_tax_no = $request->store_acc_tax_no_trans;
                $store_vou_ref_after = $request->store_vou_ref_after_trans;

                $store_vou_pay_type = SystemCode::where('system_code', '=', $request->store_vou_pay_type_trans)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

                $current_serial = CompanyMenuSerial::where('company_id', 50)->where('branch_id', 1067)->where('app_menu_id', 64);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن الاستلامم']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'ER-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                $store_vou_status = SystemCode::where('system_code', '=', '125002')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('system_code', '=', '125002')
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                break;

            default:
                abort(404);
        }

        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = request()->company_id;
        $purchase->branch_id = $branch_id;

        $purchase->store_category_type = $store_vou_ref_before->store_category_type;
        $purchase->store_vou_type = $type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $store_acc_no;
        $purchase->store_acc_name = $store_acc_name;
        $purchase->store_acc_tax_no = $store_acc_tax_no;
        $purchase->store_vou_pay_type = $store_vou_pay_type;
        $purchase->store_vou_notes = $store_vou_ref_before->store_vou_notes;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->vou_datetime = $request->vou_datetime;

        if ($type->system_code == '62009') {
            $purchase->store_vou_ref_after = $store_vou_ref_after;
        }


        $purchase->store_vou_ref_before = $store_vou_ref_before->store_hd_code;
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

                // return $item_data[2][$qty_field];

//                return $d['store_vou_item_check'];
                $item = PurchaseDetails::where('uuid', '=', $d['uuid'])->first();
                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $purchase->store_hd_id,
                    'company_group_id' => $store_vou_ref_before->company_group_id,
                    'company_id' => request()->company_id,
                    'branch_id' => $branch_id,

                    'store_category_type' => $store_vou_ref_before->store_category_type,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $store_acc_no,

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
                    'store_vou_item_check' => (isset($d['store_vou_item_check']) ? $d['store_vou_item_check'] ? 1 : 0 : null),

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
                if ($type->system_code == '62003') {
                    $store_item = StoreItem::where('item_id', $item->store_vou_item_id)->first();
                    $brofet_rate = $store_item->itemCategory->system_code_tax_perc;

                    $store_item->item_balance = $store_item->item_balance + $d[$qty_field];
                    $store_item->old_price_cost = $store_item->item_price_cost;
                    if ($store_item->item_price_cost == 0) {
                        $store_item->item_price_cost = (floatval($d['store_vou_item_price_unit']) - floatval($d['store_vou_disc_amount']));
                    } else {
                        $store_item->item_price_cost = ($store_item->item_price_cost + (floatval($d['store_vou_item_price_unit']) - floatval($d['store_vou_disc_amount']))) / 2;
                    }

                    $store_item->old_price_sales = $store_item->item_price_sales;
                    $store_item->item_price_sales = $store_item->item_price_cost + ($store_item->item_price_cost * $brofet_rate);
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                } elseif ($type->system_code == '62004') {
                    $store_item = StoreItem::where('item_id', $item->store_vou_item_id)->first();
                    $store_item->item_balance = $store_item->item_balance - $d[$qty_field];
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();
                    if (!$store_item_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                } elseif ($type->system_code == '62009') {
                    $source_item = StoreItem::where('item_id', $item->store_vou_item_id)
                        ->where('branch_id', "=", $store_vou_ref_before->store_vou_ref_1)
                        ->where('item_category', '=', $store_vou_ref_before->store_vou_ref_2)->first();

                    $source_item_brofet_rate = $source_item->itemCategory->system_code_tax_perc;


                    $dest_item = StoreItem::where('item_code', $source_item->item_code)
                        ->where('branch_id', "=", $store_vou_ref_before->store_vou_ref_3)
                        ->where('company_id', '=', request()->company_id);


                    if ($dest_item->count() > 0) {

                        if ($d[$qty_field] > $source_item->item_balance) {
                            return \Response::json(['success' => false, 'msg' => 'الكمية الحالية  اقل من الكمية المحولة ']);
                        }
                        //update source item blance
                        $source_item->item_balance = $source_item->item_balance - $d[$qty_field];
                        $source_item->updated_user = auth()->user()->user_id;
                        $source_item->updated_date = Carbon::now();
                        $source_item_save = $source_item->save();

                        if (!$source_item_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }

                        //update dest item balance
                        $dest_item = $dest_item->first();
                        $dest_item_brofet_rate = $dest_item->itemCategory->system_code_tax_perc;

                        $dest_item->item_balance = $dest_item->item_balance + $d[$qty_field];
                        $dest_item->updated_user = auth()->user()->user_id;
                        $dest_item->updated_date = Carbon::now();

                        $dest_item->old_price_cost = $dest_item->item_price_cost;
                        if ($dest_item->item_price_cost == 0) {
                            $dest_item->item_price_cost = (floatval($d['store_vou_item_price_unit']) - floatval($d['store_vou_disc_amount']));
                        } else {
                            $dest_item->item_price_cost = ($dest_item->item_price_cost + (floatval($d['store_vou_item_price_unit']) - floatval($d['store_vou_disc_amount']))) / 2;
                        }

                        $dest_item->old_price_sales = $dest_item->item_price_sales;
                        $dest_item->item_price_sales = $dest_item->item_price_cost + ($dest_item->item_price_cost * $dest_item_brofet_rate);
                        $dest_item->updated_user = auth()->user()->user_id;


                        $dest_item_save = $dest_item->save();

                        if (!$dest_item_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }

                    } else {

                        //update source item blance
                        $source_item->item_balance = $source_item->item_balance - $d[$qty_field];
                        $source_item->updated_user = auth()->user()->user_id;
                        $source_item->updated_date = Carbon::now();
                        $source_item_save = $source_item->save();

                        if (!$source_item_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }

                        $dest_item_brofet_rate = $source_item->itemCategory->system_code_tax_perc;
                        //add dest item
                        $add_dest_item = new StoreItem();

                        $add_dest_item->uuid = \DB::raw('NEWID()');
                        $add_dest_item->company_group_id = $source_item->company_group_id;
                        $add_dest_item->company_id = request()->company_id;
                        $add_dest_item->branch_id = $store_vou_ref_before->store_vou_ref_3;
                        $add_dest_item->item_category = $store_vou_ref_before->store_vou_ref_4;

                        $add_dest_item->item_location = $source_item->item_location;
                        $add_dest_item->item_code = $source_item->item_code;
                        $add_dest_item->item_vendor_code = $source_item->item_vendor_code;
                        $add_dest_item->item_name_e = $source_item->item_name_e;
                        $add_dest_item->item_name_a = $source_item->item_name_a;
                        $add_dest_item->item_price_sales = $source_item->item_price_sales;
                        $add_dest_item->item_price_cost = $source_item->item_price_cost;
                        $add_dest_item->item_balance = $d[$qty_field];
                        $add_dest_item->item_code_1 = $source_item->item_code_1;
                        $add_dest_item->item_code_2 = $source_item->item_code_2;
                        $add_dest_item->item_desc = $source_item->item_desc;
                        $add_dest_item->item_unit = $source_item->item_unit;
                        $add_dest_item->created_user = auth()->user()->user_id;

                        $dest_item->old_price_cost = $source_item->item_price_cost;
                        if ($source_item->item_price_cost == 0) {
                            $dest_item->item_price_cost = (floatval($d['store_vou_item_price_unit']) - floatval($d['store_vou_disc_amount']));
                        } else {
                            $dest_item->item_price_cost = ($source_item->item_price_cost + (floatval($d['store_vou_item_price_unit']) - floatval($d['store_vou_disc_amount']))) / 2;
                        }

                        $dest_item->old_price_sales = $source_item->item_price_sales;
                        $dest_item->item_price_sales = $source_item->item_price_cost + ($source_item->item_price_cost * $dest_item_brofet_rate);


                        $add_dest_item_save = $add_dest_item->save();

                        if (!$add_dest_item_save) {
                            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                        }

                    }

                }


            }

            foreach ($item_data_set as $item_data_s) {
                $purcahse_details_save = $purcahse_details->insert($item_data_s);
                if (!$purcahse_details_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }
            }
        }


        $update_total = PurchaseController::updateHeaderTotal($purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);

    }

//    سند صرف
    public function addBondWithJournal(Request $request)
    {
        $company_auth_id = session('company') ? session('company')['company_id'] : auth()->user()->company_id;
        $company_id = (isset(request()->company_id) ? request()->company_id : $company_auth_id);
        $company = Company::where('company_id', $company_id)->first();
        // return $request->all();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', $request->bond_method_type)->first();
        $transaction_type = 64;
        $transaction_id = $request->transaction_id;
        $purchase = Purchase::find($request->transaction_id);
        $customer_id = $purchase->store_acc_no;
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
        $cost_center_id = 54;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
//        $journal_category_id = 14;

        $journal_category_id = JournalType::where('journal_types_code', 14)
            ->where('company_group_id', $company->company_group_id)->first()->journal_types_code;

        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = ' سند صرف ' . ' ' . $bond->bond_code . ' ' . ' اذن استلام ' . ' ' . $request->bond_ref_no . ' ' . $purchase->vendor->customer_name_full_ar;
        $customer_notes = ' سند صرف  ' . ' ' . $bond->bond_code . ' ' . ' اذن استلام ' . ' ' . $request->bond_ref_no . ' ' . $purchase->vendor->customer_name_full_ar;
        $cash_notes = '  سند صرف  ' . ' ' . $bond->bond_code . ' ' . ' اذن استلام ' . ' ' . $request->bond_ref_no . ' ' . $purchase->vendor->customer_name_full_ar;
        $message = $journal_controller->AddCashJournal(56001, $customer_id, $bond_doc_type,
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

        return redirect()->route('store-purchase-receiving.edit', $purchase->uuid)->with(['success' => 'تم اضافه السند']);

    }

    ///سند قبض
    public function addBondWithJournal2(Request $request)
    {
        // return $request->all();
        \DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('system_code', $request->bond_method_type)
            ->where('company_group_id', $company->company_group_id)->first();
        $transaction_type = 66;
        $transaction_id = $request->transaction_id;
        $purchase = Purchase::find($request->transaction_id);
        $customer_id = $purchase->store_acc_no;
        $customer_type = $request->customer_type;
        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';

        $total_amount = $request->bond_amount_credit;
        $bond_doc_type = SystemCode::where('system_code_id', $request->bond_doc_type)->first();
        $bond_ref_no = $request->bond_ref_no;
        $bond_notes = $request->bond_notes ? $request->bond_notes : '';
        $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

//
        $journal_controller = new JournalsController();
        $cost_center_id = 53;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
//        $journal_category_id = 6;
        $journal_category_id = JournalType::where('journal_types_code', 6)
            ->where('company_group_id', $purchase->company->company_group_id)->first()->journal_types_code;

        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = '  سند قبض ' . ' ' . $bond->bond_code . ' ' . ' اذن استلام ' . ' ' . $request->bond_ref_no . ' ' . $purchase->vendor->customer_name_full_ar;
        $customer_notes = '  سند قبض   ' . ' ' . $bond->bond_code . ' ' . ' اذن استلام ' . ' ' . $request->bond_ref_no . ' ' . $purchase->vendor->customer_name_full_ar;
        $cash_notes = '  سند قبض  ' . ' ' . $bond->bond_code . ' ' . ' اذن استلام ' . ' ' . $request->bond_ref_no . ' ' . $purchase->vendor->customer_name_full_ar;
        $message = $journal_controller->AddCaptureJournal(56001, $customer_id, $bond_doc_type,
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

    public function update(Request $request)
    {

        $data = json_decode($request->item_table_data);
        $purchase = Purchase::where('uuid', $request->purchase_uuid)->first();

        // return $purchase->storeVouType->system_code;

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
        $purchase->save();


        if ($purchase->journal_hd_id) {
            $journal_controller = new JournalsController();
            $total_amount = $purchase->store_vou_total;
            $vat_amount = $purchase->store_vou_vat_amount;
            $cc_voucher_id = $purchase->store_hd_id;


            if ($purchase->storeVouType->system_code == 62003) { ////اذن استلام
                $cost_center_id = 64;
                $journal_controller->updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id);
                return route('store-purchase-receiving.edit', $purchase->uuid);
            }

            if ($purchase->storeVouType->system_code == 62004) { ///////////مرتجع مورد
                $cost_center_id = 66;
                $journal_controller->updateReturnPurchasingOrder($total_amount, $vat_amount,
                    $cc_voucher_id, $cost_center_id);
                return route('store-purchase-return.edit', $purchase->uuid);
            }

        }

    }


    public function updateItemSerial(Request $request)
    {
        $item = StoreDtItem::where('store_dt_item_id', $request->store_dt_item_id)->first();

        $item->update([
            'item_stor_dt_serial' => $request->item_stor_dt_serial
        ]);

        return back();
    }


    public function deleteItemSerial(Request $request)
    {
        $item = StoreDtItem::where('store_dt_item_id', $request->store_dt_item_id)->first();
        //return $request->item_stor_dt_serial;
        $item->delete();

        return back();
    }

}
