<?php

namespace App\Http\Controllers\Tajeer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\RequestException;
use App\Models\CarRentContractRequest;


class TajeerAPIController extends Controller
{
    //
    private static $url_api_mode = 'stage';
    private static $app_id = 'c49fda9f';//'c49fda9f';
    private static $app_key = '0a0ecdd133cbda8414c36b1d9f8f8f51';//'0a0ecdd133cbda8414c36b1d9f8f8f51';
    private static $authorization = 'Basic YXBpVXNlcjM2MTQwNzY6SGlsbEAyMDIyS3Nh';// 'Basic YXBpVXNlcjM2MTQwNzY6SGlsbGtzYUAyMDIy';//'Basic YXBpVXNlcjIwNzgzNTc6RGVtb0BzdGFnMjI=';

    public static function ValidateContract($CarContract)
    {
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/validate-contract';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/validate-contract';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->post($url, [

            'workingBranchId' => 10544,//need to update in sys code //$CarContract->workingBranchId,
            'vehicleDetails' => TajeerAPIController::getVehicleDetails($CarContract),
            'contractStartDate' => $CarContract->getStrinAsDate('contractStartDate'),
            'contractEndDate'=> $CarContract->getStrinAsDate('contractEndDate'),
            'contractTypeCode' => $CarContract->contractTypeAPI->system_code_search, 

            'renter'=> TajeerAPIController::getRenter($CarContract),
            // "rentedDriver" => 
            // [
            //      "idTypeCode" => 1, 
            //      "idNumber" => "1028558328", 
            //      "hijriBirthDate" => "14230101" 
            // ],

            'extraDriver' => TajeerAPIController::getExtraDriver($CarContract),
            
        ]);

        $log = TajeerAPIController::storeRequest($CarContract,'ValidateContract',$requestAPI,$requestAPI->getStatusCode());

        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };
    }

    public static function SaveContract($CarContract)
    {
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->post($url, [
    
            "renter"=> TajeerAPIController::getRenter($CarContract),
            "paymentDetails" => TajeerAPIController::getPaymentDetails($CarContract),
            "vehicleDetails" => TajeerAPIController::getVehicleDetails($CarContract),
            "rentStatus" => TajeerAPIController::getRentStatus($CarContract),
            "extraDriver" => TajeerAPIController::getExtraDriver($CarContract),
    
            "extendedCoverageId" => $CarContract->extendedCoverageId,
            "workingBranchId" => 10544,//need to update in sys code //$CarContract->workingBranchId,
            "rentPolicyId" => 59,//need to update in sys code //$CarContract->rentPolicyId,
            "contractStartDate" => $CarContract->getStrinAsDate('contractStartDate'),
            "contractEndDate"=> $CarContract->getStrinAsDate('contractEndDate'),
    
            "authorizationDetails" =>  TajeerAPIController::getAuthorizationDetails($CarContract),
    
            "allowedKmPerHour" => $CarContract->allowedKmPerHour,
            "receiveBranchId" => 10544,//need to update in sys code //$CarContract->receiveBranchId, 
            "returnBranchId" => 10544,///need to update in sys code /$CarContract->returnBranchId, 
            "allowedKmPerDay" => $CarContract->allowedKmPerDay, 
            "contractTypeCode" => $CarContract->contractTypeAPI->system_code_search, 
            "allowedLateHours" => $CarContract->allowedLateHours,
            
        ]);

        $log = TajeerAPIController::storeRequest($CarContract,'SaveContract',$requestAPI,$requestAPI->getStatusCode());

        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };
    }

    public static function CreateContractWeb($CarContract)
    {
        $stage_url = 'https://tajeerstg.tga.gov.sa/#/public-contract/'. $CarContract->contract_Number .'/'. $CarContract->contract_Token;
        $prod_url = 'https://tajeer.tga.gov.sa/#/public-contract/{contractNumber}/{token}';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->get($url);

            
        $log = TajeerAPIController::storeRequest($CarContract,'CreateContractWeb',$requestAPI,$requestAPI->getStatusCode());

        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => true ,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => $requestAPI ];
        };
    }

    public static function CreateContractService($CarContract)
    {
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/create-contract';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/create-contract';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'multipart/form-data',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->post($url,[
            'rent_contract' => 
            [   
                "id" => 19077,
                "workingBranchId" => 10544,
                "renterOTPValue" =>"404012",
                "otpValue" =>"404012", 
                "vehicleOwnerIdVersion" => 1 //not available in table
            ]
           
        ]);

        $log = TajeerAPIController::storeRequest($CarContract,'CreateContractService',$requestAPI,$requestAPI->getStatusCode());   
       
        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => true ,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => $requestAPI ];
        };
    }

    public static function getContractPDF($CarContract)
    {
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/print/'.$CarContract->contract_Number;
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/print/' . $CarContract->contract_Number;
        
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;

        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->get($url);

        $log = TajeerAPIController::storeRequest($CarContract,'getContractPDF','file',$requestAPI->getStatusCode());

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

    public static function getSummarizedContractPDF($CarContract)
    {
        
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/print/'.$CarContract->contract_Number.'/summarized-contract';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/print/'.$CarContract->contract_Number.'/summarized-contract';
        
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;

        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->get($url);

        $log = TajeerAPIController::storeRequest($CarContract,'getSummarizedContractPDF','file',$requestAPI->getStatusCode());

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

    public static function getContract($CarContract)
    {
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/'. $CarContract->contract_Number;
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/' . $CarContract->contract_Number;
        
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;

        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->get($url);

        $log = TajeerAPIController::storeRequest($CarContract,'getContract',$requestAPI,$requestAPI->getStatusCode());

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
            return ['success' => false, 'error' => true, 'statusCode' => $requestAPI->getStatusCode(), 'body' => json_decode($requestAPI)];
        };
    }

    public static function CloseContract($CarContract)
    {
        
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/closure';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/closure';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->put($url,[

            "id" => $CarContract->contract_web_id,
            'returnStatus' => TajeerAPIController::getRentStatus($CarContract,false),
            'actualReturnBranchId' => 10544,
            'closureCode' => 4, //not available in table
            'closurePaymentDetails' =>  TajeerAPIController::getClosurePaymentDetails($CarContract), 
            'contractActualEndDate' => $CarContract->getStrinAsDate('contractEndDate'), 
            'mainClosureCode' => 2 ////not available in table
           
        ]);

        $log = TajeerAPIController::storeRequest($CarContract,'CloseContract',$requestAPI,$requestAPI->getStatusCode());
       
        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => true ,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => $requestAPI ];
        };
    }


    public static function CancelContract($CarContract)
    {
        
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/'. $CarContract->contract_web_id .'/cancel';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/'.$CarContract->contract_Number.'/cancel';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->put($url,[

            "cancellationReason" => ''
           
        ]);

        $log = TajeerAPIController::storeRequest($CarContract,'CancelContract',$requestAPI,$requestAPI->getStatusCode());
       
        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => true ,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => $requestAPI ];
        };
    }

    public static function SuspendContract($CarContract)
    {
        
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/suspension';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/suspension';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->put($url,[

            "id" => $CarContract->contract_web_id,
            'returnStatus' => TajeerAPIController::getRentStatus($CarContract,false),
            'actualReturnBranchId' => 10544,
            'suspensionCode' => '1', //not available in table
            'suspensionPaymentDetails' =>  TajeerAPIController::getSuspensionPaymentDetails($CarContract), 
        ]);

        $log = TajeerAPIController::storeRequest($CarContract,'SuspendContract',$requestAPI,$requestAPI->getStatusCode());    
       
        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => true ,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => $requestAPI ];
        };
    }


    public static function GetSavedContractByPlateNumber($CarContract)
    {
        
        $stage_url = 'https://tajeer-stg.api.elm.sa/rental-api/rent-contract/get-saved-contract';
        $prod_url = 'https://tajeer.api.elm.sa/rental-api/rent-contract/get-saved-contract';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'app-id' => self::$app_id,
            'app-key' => self::$app_key,
            'Authorization' => self::$authorization,
        ])->post($url, TajeerAPIController::getVehicleDetails($CarContract,false), 
        );

            
        $log = TajeerAPIController::storeRequest($CarContract,'GetSavedContractByPlateNumber',$requestAPI,$requestAPI->getStatusCode());

        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => true ,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI) ];
        };
    }

    public static function getRenter($CarContract)
    {
        $company_data = $CarContract->company;
        return 
        [   
                   
            'personAddress' =>  $company_data->co_address, 
            'email' =>  $company_data->co_email, 
            'mobile' => '966' . substr( $company_data->co_mobile_number,1), 
            'idTypeCode' =>  1, 
            'idNumber' =>  $company_data->company_register, 
            'hijriBirthDate' =>   $company_data->co_birthdate_hijri,   
            'nationalityCode' =>  113, 

            
        ];
    }
    

    public static function getPaymentDetails($CarContract)
    {
        return 
        [
            'extraKmCost' => $CarContract->extraKmCost,
            'rentDayCost' => $CarContract->rentDayCost,
            'rentHourCost' => $CarContract-> rentHourCost,
            'fullFuelCost' => $CarContract->fullFuelCost,
            'driverFarePerDay' => $CarContract->driverFarePerDay,
            'driverFarePerHour' => $CarContract->driverFarePerHour,
            'vehicleTransferCost' => $CarContract->vehicleTransferCost,
            'internationalAuthorizationCost' => $CarContract->internationalAuthorizationCost,
            'discount' => $CarContract->discount,
            'paid' => $CarContract->paid,
            'extraDriverCost' => $CarContract->extraDriverCost,
            'paymentMethodCode' => $CarContract->paymentMethod->system_code_search,
            'otherPaymentMethodCode' => $CarContract->otherPaymentMethodCode,
            'additionalCoverageCost' => $CarContract->additionalCoverageCost,
        ];
    }

    public static function getClosurePaymentDetails($CarContract)
    {
        return 
        [
            'paymentMethodCode' => $CarContract->paymentMethod->system_code_search, 
            'otherPaymentMethodCode' =>  $CarContract->otherPaymentMethodCode, 
            'oilChangeCost' => 100, //not avilable in table 
            'paid' => $CarContract->paid, 
            'discount' =>  $CarContract->discount
        ];
    }

    public static function getSuspensionPaymentDetails($CarContract)
    {
        return 
        [
            'paymentMethodCode' => $CarContract->paymentMethod->system_code_search, 
            'otherPaymentMethodCode' =>  $CarContract->otherPaymentMethodCode, 
            'oilChangeCost' => 100, //not avilable in table
            'sparePartsCost' => 0, //not avilable in table
            'damageCost' => 0, //not avilable in table
            'paid' => $CarContract->paid,
        ];
    }

    public static function getVehicleDetails($CarContract)
    {
        return 
        [
            'plateNumber' => $CarContract->plateNumber,
            'firstChar' => $CarContract->firstChar,
            'secondChar' => $CarContract->secondChar,
            'thirdChar' => $CarContract->thirdChar,
            "plateType" => 1
        ];
    }

    public static function getRentStatus($CarContract,$all=true)
    {
        return 
        [
            'ac' => $CarContract->ac,
            'carSeats' => $CarContract->carSeats,
            'fireExtinguisher' => $CarContract->fireExtinguisher,
            'firstAidKit' => $CarContract->firstAidKit,
            'keys' => $CarContract->keys,
            'radioStereo' => $CarContract->radioStereo,
            'safetyTriangle' => $CarContract->safetyTriangle,
            'screen' => $CarContract->screen,
            'spareTire' => $CarContract->spareTire,
            'spareTireTools' => $CarContract->spareTireTools,
            'speedometer' => $CarContract->speedometer,
            'tires' => $CarContract->tires,
            'sketchInfo' => '[]',
            'notes' => $CarContract->notes,
            'availableFuel' => $CarContract->availableFuel,
            'odometerReading' => $CarContract->odometerReading,
            'other1' => $CarContract->other1,
            'other2' => $CarContract->other2,
            
            
            'oilChangeKmDistance' => (($all==true) ? $CarContract->oilChangeKmDistance : ''),
            'enduranceAmount' => (($all==true) ? 0 : ''),//$CarContract->enduranceAmount,
            'fuelTypeCode' => (($all==true) ?  $CarContract->fuelTypeCode  : ''),
            'oilChangeDate' => (($all==true) ? $CarContract->getStrinAsDate('oilChangeDate')  : ''),
            'oilType' => (($all==true) ? $CarContract->oilType : '')
            
        ];
    }

    public static function getExtraDriver($CarContract)
    {

        if($CarContract->customer_id == $CarContract->driver_id )
        {
            return [
                'idTypeCode' => $CarContract->c_idTypeCode, 
                'personAddress' => $CarContract->c_personAddress, 
                'idNumber' => $CarContract->c_idNumber, 
                'birthDate' => $CarContract->getStrinAsDate('c_birthDate')
            ];
            
        } else {
            return 
            [
                'idTypeCode' => $CarContract->d_idTypeCode,
                'personAddress' => $CarContract->d_personAddress,
                'idNumber' => $CarContract->d_idNumber,
                'birthDate' => $CarContract->getStrinAsDate('d_birthDate')
            ];   
        }
       
    }

    public static function getExternalAuthorizationCountries($CarContract)
    {
        if($CarContract->authorizationTypeCode == 2)
        {
            return [ 
                [ 'code'=> $CarContract->tammExternalAuthorizationCountries ], 
            ];
        }
        return [];
    }
    public static function getAuthorizationDetails($CarContract)
    {
        return 
        [ 
            'authorizationTypeCode' => $CarContract->authorizationTypeCode, 
            'authorizationEndDate' => '',//$CarContract->getStrinAsDate('taam_date'), 
            'tammExternalAuthorizationCountries'=> TajeerAPIController::getExternalAuthorizationCountries($CarContract),
            'addtionalServices' => [
                'carSeatPerDay' => $CarContract->carSeatPerDay,
                'disabilitiesAidsPerDay' => $CarContract->disabilitiesAidsPerDay,
                'carDelivery' => $CarContract->carDelivery,
                'navigationSystemPerDay' => $CarContract->navigationSystemPerDay,
                'internetPerDay' => $CarContract->internetPerDay
            ],
        ];
    }

    public function storeRequest($CarContract,$service_name,$requestAPI,$statusCode)
    {

        $api_request = new CarRentContractRequest();
        $api_request->contract_id = $CarContract->contract_id;
        $api_request->url_mode = self::$url_api_mode;
        $api_request->service_name = $service_name;
        $api_request->http_status = $statusCode;
        $api_request->http_response = $requestAPI;
        $api_request->created_user = (auth() ? auth()->user()->user_id : 0);
        $api_request_save = $api_request->save();
       
        if (!$api_request_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    }

}
