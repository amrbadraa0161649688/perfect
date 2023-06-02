<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PriceListDt;
use App\Models\PriceListHd;
use App\Models\WaybillDt;
use App\Models\WaybillHd;
use Illuminate\Http\Request;
use App\Models\SystemCode;

class WaybillCargo2Controller extends Controller
{
    public function getPriceList()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $loc_from = json_decode(request()->loc_from)[0];
        $locs_to = json_decode(request()->loc_to);

        $items = array();
        $price_list_hd_ids = PriceListHd::where('customer_id', request()->customer_id)
            ->where('price_list_status', 1)
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

        return response()->json(['data' => $items, 'cost_fees' => floatval($cost_fees), 'max_fees' => floatval($max_fees),
            'distance_fees' => number_format($distance_fees,2)]);
    }

    public function getPrice()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $loc_from = 29;
        $locs_to = 29;

        $items = array();
        $price_list_hd_ids = PriceListHd::where('customer_id', request()->customer_id)
            ->where('price_list_status', 1)->where('company_id', $company->company_id)
            ->pluck('price_list_id')->toArray();

            $items = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                ->where('item_id', request()->item_id)->where('loc_from', $loc_from)
                ->where('loc_to', $locs_to)->first();

                $items_max_fees = $items->max_fees;

        return response()->json(['data' => $items,  'max_fees' => $items_max_fees,
            ]);
    }

    public function getPriceD()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $loc_from = 29;
        $locs_to = 29;

        $items = array();
        $price_list_hd_ids = PriceListHd::where('price_list_category', 'fuel')
            ->where('price_list_status', 1)
            ->pluck('price_list_id')->toArray();

            $items = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                ->where('item_id',  '=',
                SystemCode::where('system_code', 70003)->where('sys_category_id', '=', 70)->first()->system_code_id)->first();

                $items_max_fees = $items->max_fees;

        return response()->json(['data' => $items,  'max_fees' => $items_max_fees,
            ]);
    }

    public function getPrice91()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $loc_from = 29;
        $locs_to = 29;

        $items = array();
        $price_list_hd_ids = PriceListHd::where('price_list_category', 'fuel')
            ->where('price_list_status', 1)
            ->pluck('price_list_id')->toArray();

            $items = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                ->where('item_id',  '=',
                SystemCode::where('system_code', 70001)->where('sys_category_id', '=', 70)->first()->system_code_id)->first();

                $items_max_fees = $items->max_fees;

        return response()->json(['data' => $items,  'max_fees' => $items_max_fees,
            ]);
    }

    public function getPrice95()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $loc_from = 29;
        $locs_to = 29;

        $items = array();
        $price_list_hd_ids = PriceListHd::where('price_list_category', 'fuel')
            ->where('price_list_status', 1)
            ->pluck('price_list_id')->toArray();

            $items = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                ->where('item_id',  '=',
                SystemCode::where('system_code', 70002)->where('sys_category_id', '=', 70)->first()->system_code_id)->first();

                $items_max_fees = $items->max_fees;

        return response()->json(['data' => $items,  'max_fees' => $items_max_fees,
            ]);
    }


    public function getwaybillinfo()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
       

        $waybill = WaybillDt::where('waybill_hd_id', request()->waybill_id)->first();

        return response()->json(['data' => $waybill]);

    }


    public function getwaybillteckit()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
       
        $waybills = WaybillHd::where('waybill_ticket_no', request()->waybill_id)->first();
        $waybill = WaybillDt::where('waybill_hd_id', $waybills->waybill_id)->first();


        return response()->json(['data' => $waybill]);

    }

}
