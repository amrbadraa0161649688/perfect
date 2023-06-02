<?php

namespace App\Http\Controllers\Tracking\WaybillCar;

use App\Http\Controllers\Controller;
use App\Models\WaybillHd;
use Illuminate\Http\Request;

class trackingController extends Controller
{
    public function show($id)
    {
        $waybill_hd = WaybillHd::find($id);
        return view('Tracking.show', compact('waybill_hd'));
    }


    public function claim($id)
    {
        $waybill_hd = WaybillHd::find($id);
        return view('Tracking.claim', compact('waybill_hd'));
    }

}
