<?php

namespace App\Http\Controllers\Station\Store\Purchase;

use App\Http\Controllers\Controller;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\StoreItem;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    //طلب شراء محطات
    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $vendor_list = Customer::where('company_group_id', '=', $company->company_group_id)
            ->where('customer_category', '=', 1)->get();

        $store_category_type = SystemCode::where('system_code', '=', 55003)
            ->where('company_group_id', $company->company_group_id)->first();

        $itemes = Storeitem::where('company_id', $company->company_id)
            ->where('branch_id', '=', $branch->branch_id)
            ->where('item_category', '=', $store_category_type->system_code_id)->get();

        return view('Stations.Store.Purchase.Request.create', compact('vendor_list', 'itemes',
            'store_category_type'));
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        DB::beginTransaction();
        $branch = session('branch');
        $last_card_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 62)->latest()->first();

        if (isset($last_card_serial)) {
            $last_bonds_serial_no = $last_card_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_card_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'REQ-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'app_menu_id' => 62,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $vou_type = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', '=', '62001')->first();

        $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', '=', '125001')->first()->system_code_id;

        ///////////////////////header
        $supplier = Customer::where('customer_id', $request->store_acc_no)->first();
        $purchase = new Purchase();
        $purchase->uuid = \DB::raw('NEWID()');
        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = session('branch')['branch_id'];
        $purchase->store_category_type = $request->store_category_type;
        $purchase->store_vou_type = $vou_type->system_code_id;
        $purchase->store_hd_code = $string_number;
        $purchase->store_acc_no = $request->store_acc_no;
        $purchase->store_acc_name = $supplier->customer_name_full_ar;
        $purchase->store_acc_tax_no = $supplier->customer_vat_no;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;
        $purchase->vou_datetime = Carbon::now();
        $purchase->store_vou_total = $request->store_vou_price_net;
        $purchase->save();

        //////////////////////details
        $purchase_details = new PurchaseDetails();
        $purchase_details->uuid = \DB::raw('NEWID()');
        $purchase_details->store_hd_id = $purchase->store_hd_id;
        $purchase_details->company_group_id = $purchase->company_group_id;
        $purchase_details->company_id = $purchase->company_id;
        $purchase_details->branch_id = $purchase->branch_id;
        $purchase_details->store_category_type = $purchase->store_category_type;
        $purchase_details->store_vou_type = $purchase->store_vou_type;
        $purchase_details->store_vou_date = Carbon::now();
        $purchase_details->created_user = auth()->user()->user_id;
        $purchase_details->store_acc_no = $purchase->store_acc_no;
        $purchase_details->store_vou_item_id = $request->item_id;
        $purchase_details->store_vou_qnt_r = $request->store_vou_qnt_r;
        $purchase_details->store_vou_item_price_unit = $request->store_vou_item_price_unit;
        $purchase_details->store_vou_item_total_price = $request->store_vou_item_total_price;
        $purchase_details->store_vou_vat_rate = $request->store_vou_vat_rate;
        $purchase_details->store_vou_vat_amount = $request->store_vou_vat_amount;
        $purchase_details->store_vou_price_net = $request->store_vou_price_net;
        $purchase_details->save();

        $purchase->store_vou_amount = $purchase->itemSumTotal();
        $purchase->store_vou_vat_amount = $purchase->itemSumVat();
        $purchase->store_vou_total = $purchase->itemSumNet();
        $purchase->save();

        DB::commit();

        return redirect()->route('store-purchase-request.index')->with('تم اضافه الكارت');

    }


    public function storePurchaseOrder(Request $request)
    {
        $purchase_request = Purchase::where('uuid', $request->purchase_uuid)->first();

        DB::beginTransaction();

        $last_card_serial = CompanyMenuSerial::where('branch_id', $purchase_request->branch_id)
            ->where('app_menu_id', 92)->latest()->first();

        if (isset($last_card_serial)) {
            $last_bonds_serial_no = $last_card_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_card_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'PO-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $purchase_request->company_group_id,
                'company_id' => $purchase_request->company_id,
                'branch_id' => $purchase_request->branch_id,
                'app_menu_id' => 92,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $vou_type = SystemCode::where('company_group_id', $purchase_request->company_group_id)
            ->where('system_code', '=', '62002')->first();

        $store_vou_status = SystemCode::where('company_group_id', $purchase_request->company_group_id)
            ->where('system_code', '=', '125001')->first()->system_code_id;

        $store_vou_status_old = SystemCode::where('company_group_id', $purchase_request->company_group_id)
            ->where('system_code', '=', '125002')->first()->system_code_id;

        $purchase_request->store_vou_status = $store_vou_status_old;
        $purchase_request->save();

        ///////////////////////header

        $purchase_order = new Purchase();
        $purchase_order->uuid = \DB::raw('NEWID()');
        $purchase_order->company_group_id = $purchase_request->company_group_id;
        $purchase_order->company_id = $purchase_request->company_id;
        $purchase_order->branch_id = $purchase_request->branch_id;
        $purchase_order->store_category_type = $purchase_request->store_category_type;
        $purchase_order->store_vou_type = $vou_type->system_code_id;
        $purchase_order->store_hd_code = $string_number;
        $purchase_order->store_acc_no = $purchase_request->store_acc_no;
        $purchase_order->store_acc_name = $purchase_request->store_acc_name;
        $purchase_order->store_acc_tax_no = $purchase_request->store_acc_tax_no;
        $purchase_order->store_vou_status = $store_vou_status;
        $purchase_order->store_vou_date = Carbon::now();
        $purchase_order->created_user = auth()->user()->user_id;
        $purchase_order->vou_datetime = Carbon::now();
        $purchase_order->store_vou_total = $request->store_vou_price_net;
        $purchase_order->store_vou_ref_before = $purchase_request->store_hd_code;
        $purchase_order->save();

        $request_purchase_detail = PurchaseDetails::where('store_hd_id', $purchase_request->store_hd_id)->first();
        //////////////////////details
        $purchase_details = new PurchaseDetails();
        $purchase_details->uuid = \DB::raw('NEWID()');
        $purchase_details->store_hd_id = $purchase_order->store_hd_id;
        $purchase_details->company_group_id = $purchase_order->company_group_id;
        $purchase_details->company_id = $purchase_order->company_id;
        $purchase_details->branch_id = $purchase_order->branch_id;
        $purchase_details->store_category_type = $purchase_order->store_category_type;
        $purchase_details->store_vou_type = $purchase_order->store_vou_type;
        $purchase_details->store_vou_date = Carbon::now();
        $purchase_details->created_user = auth()->user()->user_id;
        $purchase_details->store_acc_no = $purchase_order->store_acc_no;
        $purchase_details->store_vou_item_id = $request_purchase_detail->store_vou_item_id;
        $purchase_details->store_vou_qnt_p = $request_purchase_detail->store_vou_qnt_r;
        $purchase_details->store_vou_item_price_unit = $request_purchase_detail->store_vou_item_price_unit;
        $purchase_details->store_vou_item_total_price = $request_purchase_detail->store_vou_item_total_price;
        $purchase_details->store_vou_vat_rate = $request_purchase_detail->store_vou_vat_rate;
        $purchase_details->store_vou_vat_amount = $request_purchase_detail->store_vou_vat_amount;
        $purchase_details->store_vou_price_net = $request_purchase_detail->store_vou_price_net;
        $purchase_details->save();

        $purchase_order->store_vou_amount = $purchase_order->itemSumTotal();
        $purchase_order->store_vou_vat_amount = $purchase_order->itemSumVat();
        $purchase_order->store_vou_total = $purchase_order->itemSumNet();

        $purchase_order->save();

        $request_purchase_detail->store_vou_qnt_i_r = $request_purchase_detail->store_vou_qnt_r;
        $request_purchase_detail->save();

        DB::commit();
        return redirect()->route('store-purchase-request.index')->with('تم التحويل لامر شراء ');

    }

    public function getItemDetails()
    {
        $item = Storeitem::find(request()->item_id);
        return response()->json(['data' => $item]);
    }


}

