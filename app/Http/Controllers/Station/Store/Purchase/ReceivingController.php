<?php

namespace App\Http\Controllers\Station\Store\Purchase;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\StoreItem;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceivingController extends Controller
{
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

        $purchase_orders = Purchase::where('company_group_id', $company->company_group_id)
            ->whereHas('details', function ($q) {
                $q->whereColumn('store_vou_qnt_t_i_r', '!=', 'store_vou_qnt_p');
            })->whereHas('storeVouType', function ($query) {
                $query->where('system_code', '=', 62002);
            })->latest()->get();

        return view('Stations.Store.Purchase.Receiving.create', compact('vendor_list', 'itemes',
            'store_category_type', 'purchase_orders'));
    }


//    اضافه اذن استلام جديد
    public function storeNewReceiving(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        DB::beginTransaction();
        $branch = session('branch');
        $string_number = $this->getSerial($branch->branch_id, $company->company_id);
        $vou_type = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', '=', '62003')->first(); ////////////اذن استلام

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
        $purchase_details->store_vou_qnt_t_i_r = $request->store_vou_qnt_t_i_r;
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

        return redirect()->route('store-purchase-receiving.index')->with('تم اضافه اذن الاستلام');

    }


//    اضافه اذن استلام من امر شراء
    public function storeReceivingFromOrder(Request $request)
    {

        DB::beginTransaction();
        $purchase_order = Purchase::where('store_hd_id', $request->store_hd_id)->first();
        $purchase_order_dt = PurchaseDetails::where('store_hd_id', $purchase_order->store_hd_id)->first();

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;
        $string_number = $this->getSerial($branch->branch_id, $company->company_id);

        $store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', '=', '125001')->first()->system_code_id;

        ///////////////////////header
        $purchase = new Purchase();
        $purchase->uuid = \DB::raw('NEWID()');
        $purchase->company_group_id = $purchase_order->company_group_id;
        $purchase->company_id = $purchase_order->company_id;
        $purchase->branch_id = $purchase_order->branch_id;
        $purchase->store_category_type = $purchase_order->store_category_type;
        $purchase->store_vou_type = $purchase_order->store_vou_type;
        $purchase->store_hd_code = $string_number;
        $purchase->store_acc_no = $purchase_order->store_acc_no;
        $purchase->store_acc_name = $purchase_order->customer_name_full_ar;
        $purchase->store_acc_tax_no = $purchase_order->customer_vat_no;
        $purchase->store_vou_status = $store_vou_status;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;
        $purchase->vou_datetime = Carbon::now();
        $purchase->store_vou_total = $purchase_order->store_vou_price_net;
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
        $purchase_details->store_vou_item_id = $purchase_order_dt->store_vou_item_id;
        $purchase_details->store_vou_item_price_unit = $purchase_order_dt->store_vou_item_price_unit;
        $purchase_details->store_vou_item_total_price = $purchase_order_dt->store_vou_item_total_price;
        $purchase_details->store_vou_vat_rate = $purchase_order_dt->store_vou_vat_rate;
        $purchase_details->store_vou_vat_amount = $purchase_order_dt->store_vou_vat_amount;
        $purchase_details->store_vou_price_net = $purchase_order_dt->store_vou_price_net;
        $purchase_details->store_vou_qnt_t_i_r = $purchase_order_dt->store_vou_qnt_r;
        $purchase_details->save();

        $purchase_order_dt->store_vou_qnt_p = $purchase_order_dt->store_vou_qnt_r;
        $purchase_order_dt->save();

        $purchase->store_vou_amount = $purchase->itemSumTotal();
        $purchase->store_vou_vat_amount = $purchase->itemSumVat();
        $purchase->store_vou_total = $purchase->itemSumNet();
        $purchase->save();

        DB::commit();

        return redirect()->route('store-purchase-receiving.index')->with('تم اضافه اذن الاستلام');

    }

    public function getSerial($branch_id, $company_id)
    {
        $company = Company::find($company_id);
        $last_card_serial = CompanyMenuSerial::where('branch_id', $branch_id)
            ->where('app_menu_id', 64)->latest()->first();

        if (isset($last_card_serial)) {
            $last_bonds_serial_no = $last_card_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_card_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'ER-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'app_menu_id' => 64,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        return $string_number;
    }

    public function getStoreHd()
    {
        $purchase = Purchase::where('store_hd_id', request()->store_hd_id)->first();
        return response()->json(['data' => new PurchaseOrderResource($purchase)]);
    }
}
