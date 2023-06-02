<?php

namespace App\Http\Controllers\Naql;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use App\Models\TripHd;
use App\Models\Trucks;
use App\Models\Company;

class NaqlAPIController extends Controller
{
    //
    public static function createTrip($tripHd)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip';
        $url = $prod_url;

        $truck = Trucks::where('truck_id', '=', $tripHd->truck_id)->first();
        $app_id = $truck->company->app_id;//'bb885d7a';
        $app_key = $truck->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $truck->company->client_id;//'1000000076';


        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,

        ])->post($url, [
            "vehicle" => NaqlAPIcontroller::getVehicle($tripHd),
            "driver" => NaqlAPIcontroller::getDriver($tripHd),
            "receivedDate" => $tripHd->getStrinAsDate('trip_hd_start_date'),
            "expectedDeliveryDate" => $tripHd->getStrinAsDate('trip_hd_end_date'),
            "notes" => $tripHd->trip_hd_notes,
            "waybills" => NaqlAPIcontroller::getWaybills($tripHd->tripdts),

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

    public static function getTripPDF($tripHd)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/' . $tripHd->trip_id . '/print';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip/' . $tripHd->trip_id . '/print';
        $url = $prod_url;

        $truck = Trucks::where('truck_id', '=', $tripHd->truck_id)->first();
        $app_id = $truck->company->app_id;//'bb885d7a';
        $app_key = $truck->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $truck->company->client_id;//'1000000076';

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

    public function addWaybillToTrip($tripHd, $waybill_data)
    {
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip/waybill';
        $url = $prod_url;

        $truck = Trucks::where('truck_id', '=', $tripHd->truck_id)->first();
        $app_id = $truck->company->app_id;//'bb885d7a';
        $app_key = $truck->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $truck->company->client_id;//'1000000076';


        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,

        ])->post($url, [
            "tripId" => $tripHd->trip_id,
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
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI->getBody())];
        };

    }

    public static function getVehicle($tripHd)
    {
        $truck = $tripHd->truck;
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

    public static function getDriver($tripHd)
    {
        $driver = $tripHd->driver;
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

        foreach ($tripDt as $td) {

            $waybill_data = $td->waybillh;
            $Waybill = array
            (
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
                "paymentComment" => "0",

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

            );
            array_push($Waybills, $Waybill);
        }
        return $Waybills;
    }

    public function cancelWaybill($waybill)
    {

        /////// 2 ملغيه
        $stage_url = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill/cancel';
        $prod_url = 'https://bayan.api.elm.sa/api/v1/carrier/trip/waybill/cancel';
        $url = $prod_url;

        $app_id = $waybill->company->app_id;//'bb885d7a';
        $app_key = $waybill->company->app_key;//'a0dd5f039a8b2ee9cba1a5e47b67b88f';
        $client_id = $waybill->company->client_id;//'1000000076';

        //return $client_id;
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => $app_id,
            'app-key' => $app_key,
            'client_id' => $client_id,
        ])->put($url, [
            "waybillId" => $waybill->waybillId,
            "reasonId" => 1
        ]);

        // return $waybill;

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

}
