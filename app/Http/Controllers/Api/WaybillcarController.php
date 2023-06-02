<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomersBlock;
use App\Models\PriceListDt;
use App\Models\PriceListHd;
use App\Models\Trucks;
use App\Models\Customer;
use App\Models\SystemCode;

use App\Models\WaybillHd;
use App\Models\WaybillDt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WaybillcarController extends Controller
{

    public function getPriceList()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $loc_from = json_decode(request()->loc_from)[0];
        $locs_to = json_decode(request()->loc_to);

        $items = array();
        $price_list_hd_ids = PriceListHd::where('customer_id', request()->customer_id)
            ->where('price_list_status', 1)->where('company_id', $company->company_id)
            ->pluck('price_list_id')->toArray();

        foreach ($locs_to as $k => $loc_to) {
            $items[] = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                ->where('item_id', request()->item_id)->where('loc_from', $loc_from)
                ->where('loc_to', $locs_to[$k])->first();

            if (isset($items[$k])) {
                //مصروف السائق
                $items_cost_fees[] = $items[$k]->cost_fees;

//مصروف الطريق
                $items_distance_fees[] = $items[$k]->distance_fees;

                //             المنتج
                $items_max_fees[] = $items[$k]->max_fees;
            }

        }

        if (isset($items_cost_fees) && isset($items_max_fees) && isset($items_distance_fees)) {
            $cost_fees = max($items_cost_fees);
            $max_fees = max($items_max_fees);
            $distance_fees = max($items_distance_fees);
        } else {
            $cost_fees = 0;
            $max_fees = 0;
            $distance_fees = 0;
        }

        return response()->json(['data' => $items, 'cost_fees' => number_format($cost_fees, 2), 'max_fees' => number_format($max_fees, 2),
            'distance_fees' => number_format($distance_fees, 2)]);
    }


    public function getSenderInfo()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sender_block = CustomersBlock::where('company_group_id', $company->company_group_id)
            ->where('customer_identity', request()->sender_id)
            ->orWhere('customer_mobile', request()->customer_mobile)->first();

        if (isset($sender_block)) {
            if ($sender_block->customer_block_status == 1) {
                $sender_block_flag = 1;
            } else {
                $sender_block_flag = 0;
            }
        } else {
            $sender_block_flag = 0;
        }

        $sender_info = WaybillHd::where('waybill_sender_mobile_code', request()->sender_id)->first();

        $sender_car = WaybillDt::where('waybill_hd_id', $sender_info->waybill_id)->first();

        return response()->json(['sender_info' => $sender_info, 'sender_car' => $sender_car,
            'sender_block_flag' => $sender_block_flag]);

    }


    public function getContractsList()
    {
        $contracts_list = PriceListHd::where('customer_id', request()->customer_id)
            ->where('price_list_status', 1)->whereDate('price_list_start_date', '<=', Carbon::now())
            ->whereDate('price_list_end_date', '>=', Carbon::now())->get();
//

        $contracts_code = PriceListHd::where('customer_id', request()->customer_id)
            ->where('price_list_status', 1)->whereDate('price_list_start_date', '<=', Carbon::now())
            ->whereDate('price_list_end_date', '>=', Carbon::now())->first();


        if (isset($contracts_code)) {
            $contracts_code_f = $contracts_code->price_list_id;
        } else {
            $contracts_code_f = 0;
        }

        if (count($contracts_list) > 0) {
            return response()->json(['contracts_list' => $contracts_list, 'contracts_first' => $contracts_code_f]);
        } else {
            return response()->json(['status' => 500, 'message' => 'لا يوجد عقود متوفره للعميل']);
        }

    }

    public function getPrice()
    {

        if (request()->waybill_id) {
            $waybill = WaybillHd::where('waybill_id', request()->waybill_id)->first();
            $price_list_hd_id = PriceListHd::where('price_list_code', $waybill->customer_contract)->first();

        } else {
            $price_list_hd_id = PriceListHd::where('price_list_id', request()->price_list_id)->first();
        }


        $items = DB::table('price_list_dt')->where('price_list_id', $price_list_hd_id->price_list_id)
            ->where('item_id', request()->item_id)->where('loc_from', request()->waybill_loc_from)
            ->where('loc_to', request()->waybill_loc_to);

        $max_fees = $items->max('max_fees') ? $items->max('max_fees') : 0;
        $min_fees = $items->min('min_fees') ? $items->min('min_fees') : 0;
        $max_distance = $items->max('distance') ? $items->max('distance') : 0;
        $price_factor = $items->max('price_factor') ? $items->max('price_factor') : 0;

        return response()->json(['max_fees' => $max_fees, 'min_fees' => $min_fees,
            'max_distance' => $max_distance, 'price_factor' => $price_factor]);
    }

    public function getPricedesile()
    {
        // $price_list_hd_id = PriceListHd::where('price_list_id', request()->price_list_id)->first();

        $items = DB::table('price_list_dt')->where('customer_id', 176)
            ->where('item_id', request()->item_id)->where('loc_from', request()->waybill_loc_from)
            ->where('loc_to', request()->waybill_loc_to);

        $max_fees = $items->max('max_fees');
        $min_fees = $items->min('min_fees');

        return response()->json(['max_fees' => $max_fees, 'min_fees' => $min_fees]);
    }


    public function getTrucks()
    {
        $trucks = Trucks::where('company_id', request()->company_id)->get();
        return response()->json(['data' => $trucks]);
    }

    public function getTruckDriver()
    {
        $truck = Trucks::find(request()->truck_id);
        $driver = $truck->driver;
        return response()->json(['data' => $driver]);
    }

    public function getcustomertype()
    {
        //$customer = Customer::find(request()->customer_id);
        $customer = Customer::where('customer_id', request()->customer_id)->first();
        $SystemCode = SystemCode::where('system_code_id', $customer->customer_type)->first();

        return response()->json(['data' => $SystemCode, 'customer_name' => $customer->customer_name_full_ar,
            'customer_mobile' => '0' . $customer->customer_mobile, 'customer_address' => $customer->customer_address_1,
            'customer_tax_no' => $customer->customer_vat_no, 'customer_identity' => $customer->customer_identity,
            'account' => $customer->account, 'customer_vat_rate' => $customer->customer_vat_rate, 'customer_discount_rate' =>
                $customer->customer_discount_rate]);
    }

    public function getCountWaybillsDaily()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $truck = Trucks::where('truck_id', request()->truck_id)->first();

        if (!$truck->driver) {
            return response()->json(['status' => 500, 'driver_message' => 'لا يوجد سائق للشاحنه']);
        }
        $waybills_count = WaybillHd::where('waybill_driver_id', $truck->truck_driver_id)
            ->whereDate('waybill_load_date', Carbon::parse(request()->waybill_load_date))
            ->where('customer_id', request()->customer_id)->count();


        if ($waybills_count > 0) {
            $count = $waybills_count;
        } else {
            $count = 0;
        }


        $price_list_cost_fees = PriceListDt::where('item_id', 64006)
            ->where('loc_from', request()->waybill_loc_from)
            ->where('loc_to', request()->waybill_loc_to)
            // ->where('distance_time', $count + 1)
            ->whereHas('priceListHd', function ($query) {
                $query->where('customer_id', request()->customer_id)
                    ->where('price_list_status', 1)->whereDate('price_list_start_date', '<=', Carbon::now())
                    ->whereDate('price_list_end_date', '>=', Carbon::now())
                    ->where('price_list_category', 'int');
            })->max('cost_fees');

        if ($price_list_cost_fees) {
            return response()->json(['data' => $count, 'waybill_fees_load' => $price_list_cost_fees]);
        } else {
            return response()->json(['status' => 500, 'price_message' => 'لا يوجد قائمه اسعار']);
        }

    }


}
