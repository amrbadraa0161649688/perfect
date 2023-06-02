<?php

namespace App\Http\Controllers\CarRent\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarRentDetailsResource;
use App\Http\Resources\CarRentResource;
use App\Models\CarRentCars;
use App\Models\Customer;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarRentController extends Controller
{

    public function getCustomerData()
    {
        if (request()->customer_id) {
            $customer = Customer::where('customer_id', request()->customer_id)->first();
            return response()->json(['data' => $customer, 'id_type' => $customer->TypeCode]);
        }
        if (request()->driver_id) {
            $driver = Customer::where('customer_id', request()->driver_id)->first();
            return response()->json(['data' => $driver, 'id_type' => $driver->TypeCode]);
        }
        if (request()->commissioner_id) {
            $commissioner = Customer::where('customer_id', request()->commissioner_id)->first();
            return response()->json(['data' => $commissioner, 'id_type' => $commissioner->TypeCode]);
        }
        if (request()->customer_identity) {
            $customer = Customer::where('customer_identity', request()->customer_identity)->first();
            return response()->json(['data' => $customer ? $customer->name : null]);
        }
    }

    public function getCars(): \Illuminate\Http\JsonResponse
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;

        $car_status = SystemCode::where('system_code', 123001)->where('company_group_id', $company->company_group_id)->first();

        $sys_code = SystemCode::where('system_code', 538)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

        $customer = Customer::where('customer_id', request()->customer_id)
            ->with('activePriceList')->first();

        $cars = CarRentCars::where('company_group_id', $company->company_group_id)
            ->where('branch_id', $branch->branch_id)
//            ->where('complete', 1)
            ->where('car_status_id', $car_status->system_code_id)
            ->whereHas('model', function ($q) use ($sys_code, $customer) {
                if ($customer && $customer->activePriceList) {
                    $q->whereHas('priceListDts', function ($qq) use ($sys_code, $customer) {
                        $qq->whereHas('priceListHd', function ($query) use ($sys_code, $customer) {
                            $query->where('rent_list_status', '=', 1)
                                ->where('rent_list_start_date', '<', Carbon::now())
                                ->where('rent_list_end_date', '>', Carbon::now())
                                ->where('customer_id', '=', $customer->customer_id);
                        });
                    });
                } else {
                    $q->whereHas('priceListDts', function ($qq) use ($sys_code) {
                        $qq->whereHas('priceListHd', function ($query) use ($sys_code) {
                            $query->where('rent_list_status', '=', 1)
                                ->where('rent_list_start_date', '<', Carbon::now())
                                ->where('rent_list_end_date', '>', Carbon::now())
                                ->where('customer_type_id', '=', $sys_code);
                        });
                    });
                }
            })
            ->with(['brand', 'truckerStatus', 'brandDetails', 'model' => function ($q) use ($sys_code, $customer) {
                if ($customer && $customer->activePriceList) {
                    $q->with('priceListDts', function ($qq) use ($sys_code, $customer) {
                        $qq->with('priceListHd', function ($query) use ($sys_code, $customer) {
                            $query->where('rent_list_status', '=', 1)
                                ->where('rent_list_start_date', '<', Carbon::now())
                                ->where('rent_list_end_date', '>', Carbon::now())
                                ->where('customer_id', '=', $customer->customer_id)->latest();
                        });
                    })->latest();
                } else {
                    $q->with('priceListDts', function ($qq) use ($sys_code) {
                        $qq->with('priceListHd', function ($query) use ($sys_code) {
                            $query->where('rent_list_status', '=', 1)
                                ->where('rent_list_start_date', '<', Carbon::now())
                                ->where('rent_list_end_date', '>', Carbon::now())
                                ->where('customer_type_id', '=', $sys_code)->latest();
                        });
                    })->latest();
                }
            }
            ])->get();
//        return response()->json(['data' => $cars]);


        $records = CarRentResource::collection($cars);
        return response()->json(['data' => $records]);
    }

    public function getCarDetails(Request $request): \Illuminate\Http\JsonResponse
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_code = SystemCode::where('system_code', 538)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
        $customer = Customer::where('customer_id', request()->customer_id)
            ->with('activePriceList')->first();

        $carRentCar = CarRentCars::find($request->id);
        $carRentCar->load(['brand', 'category', 'truckerStatus', 'registrationType', 'insuranceType', 'fuelType',
            'oilType', 'radioStatus', 'safetyTriangle', 'screenStatus', 'speedometerStatus', 'screenStatus',
            'seatsStatus', 'spareTireTools', 'TiresStatus', 'spareTireStatus', 'keysStatus', 'firstAidKit',
            'carAcStatus', 'model' => function ($q) use ($sys_code, $customer) {
                if ($customer && $customer->activePriceList) {
                    $q->with('priceListDts', function ($qq) use ($sys_code, $customer) {
                        $qq->with('priceListHd', function ($query) use ($sys_code, $customer) {
                            $query->where('rent_list_status', '=', 1)
                                ->where('rent_list_start_date', '<', Carbon::now())
                                ->where('rent_list_end_date', '>', Carbon::now())
                                ->where('customer_id', '=', $customer->customer_id)->latest();
                        });
                    })->latest();
                } else {
                    $q->with('priceListDts', function ($qq) use ($sys_code) {
                        $qq->with('priceListHd', function ($query) use ($sys_code) {
                            $query->where('rent_list_status', '=', 1)
                                ->where('rent_list_start_date', '<', Carbon::now())
                                ->where('rent_list_end_date', '>', Carbon::now())
                                ->where('customer_type_id', '=', $sys_code)->latest();
                        });
                    })->latest();
                }
            }]);
        $records = CarRentDetailsResource::make($carRentCar);
        return response()->json(['data' => $records]);
    }
}
