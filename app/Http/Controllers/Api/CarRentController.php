<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarRentController extends Controller
{
    public function saveContract($c_personAddress, $c_email, $c_mobile, $c_idTypeCode, $c_idNumber,
                                 $c_hijriBirthDate, $extraKmCost, $rentDayCost, $rentHourCost, $fullFuelCost, $driverFarePerDay,
                                 $driverFarePerHour, $vehicleTransferCost, $internationalAuthorizationCost, $discount, $paid, $paymentMethodCode,
                                 $additionalCoverageCost, $full_car_plate, $oilChangeKmDistance, $enduranceAmount, $fuelTypeCode, $oilChangeDate,
                                 $oilType, $ac, $carSeats, $fireExtinguisher, $firstAidKit, $keys, $radioStereo, $safetyTriangle, $screen,
                                 $spareTire, $spareTireTools, $speedometer, $tires, $availableFuel, $odometerReading, $workingBranchId,
                                 $rentPolicyId, $d_idTypeCode, $d_personAddress, $d_idNumber, $extendedCoverageId, $allowedKmPerHour,
                                 $receiveBranchId, $returnBranchId, $allowedKmPerDay, $contractTypeCode, $allowedLateHours


    )
    {
//        ' .  . '


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tajeer-stg.api.elm.sa/rental-api/rent-contract",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
 
                    "renter":{
                   
                    "personAddress":"' . $c_personAddress . '",
                     
                    "email":"' . $c_email . '",
                     
                    "mobile":"' . $c_mobile . '",
                     
                    "idTypeCode":"' . $c_idTypeCode . '",
                     
                    "idNumber":"' . $c_idNumber . '",
                     
                    "hijriBirthDate": "' . $c_hijriBirthDate . '"
                     
                    },
                    "paymentDetails":{
 
                    "extraKmCost":' . $extraKmCost . ',
                     
                    "rentDayCost":' . $rentDayCost . ',
                     
                    "rentHourCost":' . $rentHourCost . ',
                     
                    "lateHourCost":0,
                     
                    "fullFuelCost":' . $fullFuelCost . ',
                     
                    "driverFarePerDay":' . $driverFarePerDay . ',
                     
                    "driverFarePerHour":' . $driverFarePerHour . ',
                     
                    "vehicleTransferCost":' . $vehicleTransferCost . ',
                     
                    "driverCost":0,
                     
                    "internationalAuthorizationCost": ' . $internationalAuthorizationCost . ' ,
                     
                    "discount":' . $discount . ',
                     
                    "paid":100,
                     
                    "extraDriverCost":' . $paid . ',
                     
                    "paymentMethodCode":' . $paymentMethodCode . ',
                     
                    "additionalCoverageCost":' . $additionalCoverageCost . ',
                     
                    "fuelCost":0
                     
                    },
                    "vehicleDetails":{
                     
                    "plateNumber":"' . $full_car_plate . '" ,
                     
                    "oilChangeKmDistance":' . $oilChangeKmDistance . ',
                     
                    "enduranceAmount":' . $enduranceAmount . ',
                     
                    "fuelTypeCode":' . $fuelTypeCode . ',
                     
                    "oilChangeDate":"' . $oilChangeDate . '",
                     
                    "oilType":"' . $oilType . '",
                     
                    "insuranceExpiryDate":0,
                     
                    "insuranceNumber":""
                    ,
                     
                    "insuranceTypeCode":""
                    ,
                     
                    "تويتا":"brandNameAr"
                    ,
                     
                    "brandNameEn":"Toyota",
                     
                    "operationCardNumber":"14-00000013",
                     
                    "operationCardExpiryDate":"1444/02/30",
                     
                    "plateType":"PRIVATE",
                     
                    "كامري":"modelNameAr"
                    ,
                     
                    "modelNameEn":"Camry"
                    ,
                     
                    "manufactureYear":"2019",
                     
                    "يييي":"color"
                    ,
                     
                    "numberOfPassengers":5,
                     
                    "other1":"other1",
                     
                    "other2":"other2"
                     
                    },
                    "rentStatus":{
                     
                    "ac":"' . $ac . '",
                     
                    "carSeats":"' . $carSeats . '",
                     
                    "fireExtinguisher":"' . $fireExtinguisher . '",
                     
                    "firstAidKit":"' . $firstAidKit . '",
                     
                    "keys":"' . $keys . '",
                     
                    "radioStereo":"' . $radioStereo . '",
                     
                    "safetyTriangle":"' . $safetyTriangle . '",
                     
                    "screen":"' . $screen . '",
                     
                    "spareTire":"' . $spareTire . '",
                     
                    "spareTireTools":"' . $spareTireTools . '",
                     
                    "speedometer":"' . $speedometer . '",
                     
                    "tires":"' . $tires . '",
                     
                    "sketchInfo":"[{\"type\":\"small�scratch\",\"x\":769.8333129882812,\"y\":119.61669921875},{\"type\":\"s
                    mall�scratch\",\"x\":702.8333129882812,\"y\":109.61669921875},{\"type\":\"v
                    ery-deep�scratch\",\"x\":741.8333129882812,\"y\":308.4753579947155},{\"type\":\
                    "bend-in-body\",\"x\":151.83331298828125,\"y\":312.62390469286186}]",
                     
                    "notes":"Notes",
                     
                    "availableFuel":"' . $availableFuel . '",
                     
                    "odometerReading":"' . $odometerReading . '",
                     
                    "other1":"other1",
                     
                    "other2":"other2"
                    },
                     
                    "workingBranchId":' . $workingBranchId . ',
                     
                    "rentPolicyId":' . $rentPolicyId . ',
                     
                    "rentedDriver":{
                    
                    "idTypeCode":"' . $c_idTypeCode . '",
                     
                    "idNumber":"' . $c_idNumber . '",
                     
                    "hijriBirthDate": "' . $c_hijriBirthDate . '"
                     
                    },
                     
                    "extraDriver":{
                     
                    "idTypeCode":"' . $d_idTypeCode . '",
                     
                    "personAddress":"' . $d_personAddress . '",
                     
                    "idNumber":"' . $d_idNumber . '",
                     
                    "birthDate":"1981-11-01T00:00" 
                     
                    },
                    "plateNumber":"' . $full_car_plate . '"
                    ,
                     
                    "extendedCoverageId":"' . $extendedCoverageId . '",
                     
                    "contractStartDate":"2021-11-26T13:37",
                     
                    "contractEndDate":"2021-11-28T13:37",
                     
                    "tammExternalAuthorizationCountries":[
                     
                                {
                                 
                                "id":1,
                                 
                                "code":1
                                 
                                },
                                 
                                {
                                 
                                "id":2,
                                 
                                "code":2
                                 
                                },
                                 
                                {
                                 
                                "id":5,
                                 
                                "code":5
                                 
                                }
                     
                    ],
                    "authorizationDetails":{
                     
                    "rentDate":"2021-11-26T13:37",
                     
                    "rentEndDate":"2021-11-28T13:40",
                     
                    "authorizationTypeCode":"2",
                     
                    "authorizationStartDate":"2021-11-26T13:37",
                     
                    "authorizationEndDate":"2022-05-26T10:37",
                     
                    "externalAuthorizationCountries":""
                    ,
                     
                    "numberOfAllowedDelayHours":12,
                     
                    "freeKMPerHour":10,
                     
                    "freeKMPerDay":100,
                     
                    "driverFarePerHour":' . $driverFarePerHour . ',
                     
                    "driverFarePerDay":' . $driverFarePerDay . ',
                     
                    "rentLocation":2,
                     
                    "arrivalLocation":2,
                     
                    "rentDuration":2,
                     
                    "tammExternalAuthorizationCountries":[
                     
                    {
                     
                    "id":1,
                     
                    "code":1
                     
                    },
                     
                    {
                     
                    "id":2,
                     
                    "code":2
                     
                    },
                    {
                     
                    "id":5,
                     
                    "code":5
                     
                    }
                     
                    ]
                     
                    },
                     
                    "addtionalServices":{
                     
                    "carSeatPerDay":"10",
                     
                    "disabilitiesAidsPerDay":"20",
                     
                    "carDelivery":"200",
                     
                    "navigationSystemPerDay":"40",
                     
                    "internetPerDay":"50"
                     
                    },
                    "allowedKmPerHour":' . $allowedKmPerHour . ',
                     
                    "receiveBranchId":' . $receiveBranchId . ',
                     
                    "returnBranchId":' . $returnBranchId . ',
                     
                    "allowedKmPerDay":' . $allowedKmPerDay . ',
                     
                    "contractTypeCode":"' . $contractTypeCode . '",
                     
                    "oilChangeDate":"2021-12-11T00:00",
                     
                    "allowedLateHours":' . $allowedLateHours . '
                    }',
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "content-type: application/json",
                "app-id : 9999",
                "app-key : 9999"
            ),
        ));



        $response = curl_exec($curl);
        $err = curl_error($curl);


        return $response;

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }

    }


    public function getAllPolicies()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tajeer-stg.api.elm.sa/rental-api/rent-policy/all",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "_postman_id: fca13e84-392f-441b-8cb3-351a70dd932b",
                "cache-control: no-cache",
                "app-id: c49fda9f",
                "app-key: 0a0ecdd133cbda8414c36b1d9f8f8f51",
                "Authorization: Basic YXBpVXNlcjIwNzgzNTc6QEFiemVsMjAyMg"
            ),
        ));


        $response = curl_exec($curl);

        $err = curl_error($curl);
        return response()->json(['data' => json_decode($response)]);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }

    }

    public function getAllBranches()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tajeer-stg.api.elm.sa/rental-api/branch/all",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "_postman_id: fca13e84-392f-441b-8cb3-351a70dd932b",
                "cache-control: no-cache",
                "app-id: c49fda9f",
                "app-key: 0a0ecdd133cbda8414c36b1d9f8f8f51",
                "Authorization: Basic YXBpVXNlcjIwNzgzNTc6QEFiemVsMjAyMg"
            ),
        ));


        $response = curl_exec($curl);

        $err = curl_error($curl);
        return response()->json(['data' => json_decode($response)]);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }

    }

    public function getExtendedCoverage()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tajeer-stg.api.elm.sa/rental-api/extended-coverage/all",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "_postman_id: fca13e84-392f-441b-8cb3-351a70dd932b",
                "cache-control: no-cache",
                "app-id: c49fda9f",
                "app-key: 0a0ecdd133cbda8414c36b1d9f8f8f51",
                "Authorization: Basic YXBpVXNlcjIwNzgzNTc6QEFiemVsMjAyMg"
            ),
        ));


        $response = curl_exec($curl);

        $err = curl_error($curl);
        return response()->json(['data' => json_decode($response)]);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }

    }


}
