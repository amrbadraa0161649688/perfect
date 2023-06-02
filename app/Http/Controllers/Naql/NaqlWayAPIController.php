<?php

namespace App\Http\Controllers\Naql;

use App\Http\Controllers\Controller;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use App\Models\Trucks;
use App\Models\WaybillHd;
use App\Models\Company;

class NaqlWayAPIController extends Controller
{
    //
    public static function createTrip($WaybillHd)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip';
        $url = $prod_url;

        $truck = Trucks::where('truck_id', '=', $WaybillHd->waybill_truck_id)->first();
        $app_id = $truck->company->app_id;//'bb885d7a';
        $app_key = $truck->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $truck->company->client_id;//'1000000076';
        //  return  NaqlAPIController::getWaybills($tripHd->tripdts);
        // return $ret_data = [
        //        "vehicle" => NaqlAPIController::getVehicle($tripHd),
        //        "driver" => NaqlAPIController::getDriver($tripHd),
        //        "receivedDate" => $tripHd->getStrinAsDate('trip_hd_start_date'),
        //        "expectedDeliveryDate" => $tripHd->getStrinAsDate('trip_hd_end_date'),
        //        "notes" => $tripHd->trip_hd_notes,
        //        "waybills" => NaqlAPIController::getWaybills($tripHd->tripdts),

        //    ];
        $requestAPI = \Http::withHeaders([

            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,

        ])->post($url, [
            "vehicle" => NaqlWayAPIController::getVehicle($WaybillHd),
            "driver" => NaqlWayAPIController::getDriver($WaybillHd),
            "receivedDate" => $WaybillHd->getStrinAsDate('waybill_load_date'),
            "expectedDeliveryDate" => $WaybillHd->getStrinAsDate('waybill_delivery_expected'),
            "notes" => $WaybillHd->waybill_ticket_no,
            "waybills" => NaqlWayAPIController::getWaybills($WaybillHd),

        ]);


        if ($requestAPI->serverError()) //500
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->successful()) //200
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

    }

    public static function getVehicle($WaybillHd)
    {
        $truck = $WaybillHd->truck;
        return
            [
                "plateTypeId" => $truck->plateType->system_code_search,
                "vehiclePlate" =>
                    [
                        "rightLetter" => $truck->rightLetter,
                        "middleLetter" => $truck->middleLetter,
                        "leftLetter" => $truck->leftLetter,
                        "number" => $truck->plate_number
                    ]
            ];
    }

    public static function getDriver($WaybillHd)
    {
        $driver = $WaybillHd->driver;
        return
            [
                "identityNumber" => $driver->emp_identity,
                "issueNumber" => $driver->issueNumber,
                "mobile" => '+966' . substr($driver->emp_private_mobile, 1)
            ];
    }

    public static function getWaybills($tripDt)
    {
        $Waybills = [];

        $waybill_data = $tripDt;


        if (is_array(json_decode($waybill_data->waybill_loc_from))) {
            $city_loc_from = SystemCode::where('system_code_id', json_decode($waybill_data->waybill_loc_from)[0])
                ->first();

            $city_loc_from = SystemCode::where('system_code_id', json_decode($waybill_data->waybill_loc_from)[0])
                ->first();

            $cityId_from = $city_loc_from->system_code_search;
            $address_from = $city_loc_from->system_code_name_ar;
        } else {
            $cityId_from = $waybill_data->locfrom->system_code_search;
            $address_from = $waybill_data->locfrom->getSysCodeName();
        }


        if (is_array(json_decode($waybill_data->waybill_loc_to))) {
            $city_loc_to = SystemCode::where('system_code_id', json_decode($waybill_data->waybill_loc_to)[0])
                ->first();

            $cityId_to = $city_loc_to->system_code_search;
            $address_to = $city_loc_to->system_code_name_ar;
        } else {
            $cityId_to = $waybill_data->locTo->system_code_search;
            $address_to = $waybill_data->locTo->getSysCodeName();
        }


        $Waybill = array
        (
            "sender" =>
                [
                    "name" => $waybill_data->waybill_sender_name,
                    "phone" => '+966' . $waybill_data->waybill_sender_mobile,
                    "countryCode" => "SA",
                    "cityId" => $cityId_from,
                    "address" => $address_from,
                    "notes" => 'تذكره رقم' . ' ' . $waybill_data->waybill_ticket_no,
                ],
            "recipient" =>
                [
                    "name" => $waybill_data->customer->customer_name_full_ar,
                    "phone" => '+966' . $waybill_data->waybill_sender_mobile,
                    "countryCode" => "SA",
                    "cityId" => $cityId_to,
                    "address" => $address_to,
                    "notes" => 'بوليصه رقم' . ' ' . $waybill_data->waybill_code,
                ],
            "items" => [
                [
                    "unitId" => 2,
                    //$waybill_data->Wdetails->item->system_code_filter,
                    "valid" => true,
                    "quantity" => 1,
                    "deliverToClient" => true,
                    "price" => (int)$waybill_data->waybill_fees_total,
                    "goodTypeId" => $waybill_data->Wdetails->item->system_code_search,
                    //$waybill_data->Wdetails->item->system_code_search,
                    "weight" => 45 ,
                    //(int)$waybill_data->Wdetails->waybill_item_quantity,
                    "dimensions" => "1",
                    "itemNumber" => "1",
                ]
            ],
            "deliverToClient" => true,
            "fare" => (int)$waybill_data->waybill_fees_total,
            "tradable" => true,
            "extraCharges" => "0",
            "paymentMethodId" => 1,
            "paymentComment" => "0",
            "paidBySender" => true,

            "receivingLocation" => [
                "countryCode" => "SA",
                "cityId" => $cityId_from,
                "address" => $address_from
            ],
            "deliveryLocation" =>
                [
                    "countryCode" => "SA",
                    "cityId" => $cityId_to,
                    "address" => $address_to
                ]

        );
        array_push($Waybills, $Waybill);

        return $Waybills;
    }

    public function cancelWaybill($waybill)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill/cancel';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip/waybill/cancel';
        $url = $prod_url;

        $app_id = $waybill->company->app_id;//'bb885d7a';
        $app_key = $waybill->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $waybill->company->client_id;//'1000000076';

        //return $waybill->waybillId;
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,
        ])->put($url, [
            "waybillId" => $waybill->waybillId,
            "reasonId" => 1
        ]);


        if ($requestAPI->serverError()) //500
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->successful()) //200
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };
    }

    public function printWaybill($waybill)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/' . $waybill->trip_id . '/print';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip/' . $waybill->trip_id . '/print';
        $url = $prod_url;

        $app_id = $waybill->company->app_id;//'bb885d7a';
        $app_key = $waybill->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $waybill->company->client_id;//'1000000076';

        //return $app_key;
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,
        ])->get($url);


        if ($requestAPI->serverError()) //500
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->successful()) //200
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => $requestAPI];
        };
    }

    /// 3 مغلقه
    public function closeWaybillTrip($waybill)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill/close';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip/waybill/close';
        $url = $prod_url;

        $app_id = $waybill->company->app_id;//'bb885d7a';
        $app_key = $waybill->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $waybill->company->client_id;//'1000000076';


        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,
        ])->put($url, [
            "waybillId" => $waybill->waybillId,
            "actualDeliveryDate" => Carbon::now()->format('Y-m-d')
        ]);


        if ($requestAPI->serverError()) //500
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->successful()) //200
        {
            return ['success' => true, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };
    }


    ////update waybill
    public function updateWaybill($waybill_data)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip/waybill';
        $url = $prod_url;

        $app_id = $waybill_data->company->app_id;//'bb885d7a';
        $app_key = $waybill_data->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $waybill_data->company->client_id;//'1000000076';


        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,
        ])->put($url, [
            "waybillId" => $waybill_data->waybillId,
            "sender" =>
                [
                    "name" => $waybill_data->waybill_sender_name,
                    "phone" => '+966' . $waybill_data->waybill_sender_mobile,
                    "countryCode" => "SA",
                    "cityId" => $waybill_data->locfrom->system_code_search,
                    "address" => $waybill_data->locfrom->getSysCodeName(),
                    "notes" => 'سياره رقم' . ' ' . $waybill_data->Wdetails->waybill_car_plate,
                ],
            "recipient" =>
                [
                    "name" => $waybill_data->waybill_receiver_name,
                    "phone" => '+966' . $waybill_data->waybill_receiver_mobile,
                    "countryCode" => "SA",
                    "cityId" => $waybill_data->locTo->system_code_search,
                    "address" => $waybill_data->locTo->getSysCodeName(),
                    "notes" => 'بوليصه رقم' . ' ' . $waybill_data->waybill_code,
                ],
            "items" => [
                [
                    "unitId" => $waybill_data->Wdetails->item->system_code_filter,
                    "valid" => true,
                    "quantity" => (int)$waybill_data->Wdetails->waybill_item_quantity,
                    "deliverToClient" => true,
                    "price" => $waybill_data->waybill_total_amount,
                    "goodTypeId" => $waybill_data->Wdetails->item->system_code_search,
                    "weight" => 1,

                    "dimensions" => "1",
                    "itemNumber" => "1",
                ]
            ],
            "deliverToClient" => true,
            "fare" => $waybill_data->waybill_total_amount,
            "tradable" => true,
            "extraCharges" => "0",
            "paymentMethodId" => 1,
            "paymentComment" => "string",
            "paidBySender" => true,
            "receivingLocation" => [
                "countryCode" => "SA",
                "cityId" => $waybill_data->locfrom->system_code_search,
                "address" => $waybill_data->locfrom->getSysCodeName()
            ],
            "deliveryLocation" =>
                [
                    "countryCode" => "SA",
                    "cityId" => $waybill_data->locTo->system_code_search,
                    "address" => $waybill_data->locTo->getSysCodeName()
                ]
        ]);


        if ($requestAPI->serverError()) //500
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

        if ($requestAPI->successful()) //200
        {
            return ['success' => true, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

    }

}
