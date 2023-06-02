<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Trucks;
use Illuminate\Http\Request;

class WayPillsController extends Controller
{
    public function getTrucks()
    {
        $trucks = Trucks::where('company_id', request()->company_id)->get();
        return response()->json(['data' => $trucks]);
    }

    public function getTruckDriver()
    {
        $truck = Trucks::find(request()->truck_id);
        $driver = $truck->driver;
        if (isset($driver)) {
            return response()->json(['status' => 200, 'data' => $driver]);
        } else {
            return response()->json(['status' => 500, 'message' => 'لا يوجد سائق مسجل للشاحنه']);
        }

    }

    public function getTruckDriverId()
    {
        $truck = Trucks::find(request()->truck_id);
        $driver = $truck->driver->driver_id;
        if (isset($driver)) {
            return response()->json(['driver_id' => $driver]);
        } else {
            return response()->json(['driver_id' => 0]);
        }
    }

    public function getTruck()
    {
        $driver = Employee::find(request()->driver_id);
        $truck = Trucks::where('truck_driver_id', $driver->emp_id)->first();
        if (isset($truck)) {
            return response()->json(['data' => $truck]);
        } else {
            return response()->json(['message' => 'لا يوجد سائق']);
        }

    }

}
