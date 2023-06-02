<?php

namespace App\Http\Controllers\Naql;

use App\Http\Controllers\Controller;
use App\Http\Resources\WaybillNaqlResource;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NaqlController extends Controller
{
    public function createTrip($vehicle_plateTypeId, $rightLetter, $middleLetter, $leftLetter, $vehicle_number,
                               $driver_identityNumber, $driver_issueNumber, $driver_mobile,
                               $trip_receivedDate, $trip_expectedDeliveryDate, $waybills)
    {
        $curl = curl_init();
        //  return $waybills;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bayan-stg.api.elm.sa/api/v1/carrier/trip",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
                "vehicle": {
                    "plateTypeId": 2,
                    "vehiclePlate": {
                        "rightLetter": "أ",
                        "middleLetter": "د",
                        "leftLetter": "ح",
                        "number": "8747"
                    }
                },
                "driver": {
                    "identityNumber": "2362561934",
                    "issueNumber": 5,
                    "mobile": "+966560000000"
                },
                "extraDriver": {
                    "identityNumber": "1000000012",
                    "issueNumber": 1,
                    "mobile": "+966500000012"
                },
               "receivedDate":"2022-09-14",
               "expectedDeliveryDate":"2022-09-15",
               "notes":"string",
               "waybills":[
                {
                "sender": {
                    "name": "string",
                    "phone": "+966555555555",
                    "countryCode": "SA",
                    "cityId": 1,
                    "address": "string",
                    "notes": "string"
                },
                "recipient": {
                    "name": "string",
                    "phone": "+966555555555",
                    "countryCode": "SA",
                    "cityId": 1,
                    "address": "string",
                    "notes": "ششش"
                },
                   "receivingLocation": {
                         "countryCode": "SA",
                         "cityId": 1,
                         "address": "receivingLocation"
                    },
                "deliveryLocation": {
                         "countryCode": "SA",
                         "cityId": 2,
                         "address": "deliveryLocation"
                    },
                "items": [
                    {
                        "unitId": 1,
                        "valid": true,
                        "quantity": 1,
                        "deliverToClient": false,
                        "price": 12,
                        "goodTypeId": 1,
                        "weight": 12,
                        "dimensions": "2*2*2",
                        "itemNumber": "12"
                    }
                ],
                "fare": 1000,
                "tradable": true,
                "extraCharges": "string",
                "paymentMethodId": 1,
                "paymentComment": "string",
                "paidBySender": true
               },
                {
                "sender": {
                    "name": "sender2",
                    "phone": "+966555555552",
                    "countryCode": "SA",
                    "cityId": 1,
                    "address": "sender2",
                    "notes": "sender2"
                },
                "recipient": {
                    "name": "recipient2",
                    "phone": "+966555555555",
                    "countryCode": "SA",
                    "cityId": 1,
                    "address": "recipient2",
                    "notes": "recipient2"
                },
                "receivingLocation": {
                         "countryCode": "SA",
                         "cityId": 1,
                         "address": "receivingLocation"
                    },
                "deliveryLocation": {
                         "countryCode": "SA",
                         "cityId": 2,
                         "address": "deliveryLocation"
                    },
                "items": [
                    {
                        "unitId": 1,
                        "valid": true,
                        "quantity": 1,
                        "deliverToClient": false,
                        "price": 12,
                        "goodTypeId": 1,
                        "weight": 12,
                        "dimensions": "2*2*2",
                        "itemNumber": "12"
                    }
                ],
                "fare": 1000,
                "tradable": true,
                "extraCharges": "string",
                "paymentMethodId": 1,
                "paymentComment": "string",
                "paidBySender": true
               }
               ]
            }',
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "Content-Type : application/json",
                "_postman_id: 95f65dbb-1936-4a3a-8ec5-99b09d88f44c",
                "_exporter_id: 3170768",
                "accept : application/json",
                "app-id : bb885d7a",
                "app-key : a0dd5f039a8b2ee9cba1a5e47b67b88f",
                "client_id : 1000000076"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);


        // return $response;

        curl_close($curl);

        if ($err) {
            //return 'a';
            return "cURL Error #:" . $err;
        } else {
            return $response;
            return (json_decode($response));
        }


    }

    public function addWaybillToTrip($waybill, $trip_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
            "tripId":' . $trip_id . ',
            "sender": {
                "name": "' . $waybill->waybill_sender_name . '",
                "phone": "' . $waybill->waybill_sender_mobile . '",
                "countryCode": "SA",
                "cityId": 1,
                "address": "' . $waybill->locfrom->system_code_name_ar . '"
            },
           "recipient": {
            "name": "' . $waybill->waybill_receiver_name . '",
            "phone": "' . $waybill->waybill_receiver_mobile . '",
            "countryCode": "SA",
            "cityId": 1,
            "address": "' . $waybill->locTo->system_code_name_ar . '"
           },
           "items": [
            {
            "unitId": 1,
            "valid": true,
            "quantity": ' . $waybill->details->waybill_qut_received_customer . ',
            "deliverToClient": true,
            "price": ' . $waybill->details->waybill_item_price . ',
            "goodTypeId": 9,
            "weight": 70.91,
            "dimensions": "10 x 10 x 10",
            "dangerousCode": "1100"
            }
            ],
            "fare": ' . $waybill->waybill_total_amount . ',
            "tradable": true,
            "paidBySender": true,
            "receivingLocation": {
                "countryCode": "SA",
                "cityId": 1,
                "address":"' . $waybill->locfrom->system_code_name_ar . '"
            },
            "deliveryLocation": {
                "countryCode": "SA",
                "cityId": 2,
                "address":"' . $waybill->locTo->system_code_name_ar . '"
            }
           }',
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "Content-Type : application/json",
                "_postman_id: 95f65dbb-1936-4a3a-8ec5-99b09d88f44c",
                "_exporter_id: 3170768",
                "accept : application/json",
                "app-id : bb885d7a",
                "app-key : a0dd5f039a8b2ee9cba1a5e47b67b88f",
                "client_id : 1000000076"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);


        return $response;

        curl_close($curl);

        if ($err) {
            //return 'a';
            return "cURL Error #:" . $err;
        } else {
            return $response;
            return (json_decode($response));
        }
    }


    public function cancelWaybillInTrip($waybill_id)
    {
        // reasonId = 1 الغاء وثيقه
        $waybill_id = 160986;
        $curl = curl_init();
//return $waybill_id;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
             "waybillId":' . $waybill_id . ',
             "reasonId": 1
            }',
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "Content-Type : application/json",
                "_postman_id: 95f65dbb-1936-4a3a-8ec5-99b09d88f44c",
                "_exporter_id: 3170768",
                "accept : application/json",
                "app-id : bb885d7a",
                "app-key : a0dd5f039a8b2ee9cba1a5e47b67b88f",
                "client_id : 1000000076"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);


        return $response;

        curl_close($curl);

        if ($err) {
            //return 'a';
            return "cURL Error #:" . $err;
        } else {
            return $response;
            return (json_decode($response));
        }

//        $apiURL = 'https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill';
//        $postInput = json_encode([
//            'waybillId' => (int)$waybill_id,
//            'reasonId' => 1
//        ]);
//
//        $headers = [
//            "accept" => "*/*",
//            "accept-language" => "en-US,en;q=0.8",
//            "Content-Type" => "application/json",
//            "_postman_id" => "95f65dbb-1936-4a3a-8ec5-99b09d88f44c",
//            "_exporter_id" => "3170768",
//            "app-id" => "bb885d7a",
//            "app-key" => "a0dd5f039a8b2ee9cba1a5e47b67b88f",
//            "client_id" => "1000000076"
//        ];
////
//        $response = Http::withHeaders($headers)->post($apiURL,
//            ['body' => $postInput]
//        );
//
//        $statusCode = $response->status();
//        // RETURN $statusCode;
//        $responseBody = json_decode($response->getBody(), true);
////
//        echo $statusCode;  // status code
////
//        dd($responseBody); // body response


    }


    public function closeWaybill1($waybill_id, $actual_delivery_date)
    {
        $curl = curl_init();
//return $waybill_id;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://bayan-stg.api.elm.sa/api/v1/carrier/trip/waybill/close",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
              "waybillId":' . $waybill_id . ',
              "actualDeliveryDate":"' . $actual_delivery_date . '"
            }',
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "Content-Type : application/json",
                "_postman_id: 95f65dbb-1936-4a3a-8ec5-99b09d88f44c",
                "_exporter_id: 3170768",
                "accept : application/json",
                "app-id : bb885d7a",
                "app-key : a0dd5f039a8b2ee9cba1a5e47b67b88f",
                "client_id : 1000000076"
            ),
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);


        return $response;

        curl_close($curl);

        if ($err) {
            //return 'a';
            return "cURL Error #:" . $err;
        } else {
            return $response;
            return (json_decode($response));
        }

    }


}
