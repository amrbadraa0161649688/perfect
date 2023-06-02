<?php

namespace App\Http\Controllers\Petro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use App\Models\Branch;
use App\Models\SystemCode;

class PetroAppAPIController extends Controller
{
    //
    private static $url_api_mode = 'stage';
    private static $api_key = '';
    
    public function __construct()
    {
        $username = 'app';
        $pass = 'Aa123456';
        self::$api_key = PetroAppAPIController::GenerateApiKey($username,$pass)['api_key'];
    }

    public static function getApiKey()
    {
        return self::$api_key;
    }
    public static function GenerateApiKey($username,$pass)
    {
        \Log::info('t');
        $stage_url = 'https://pre.petroapp.com.sa/webservice/get_apiKey';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/get_apiKey';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, [

            'username' => $username,
            'password' => $pass,
            
        ]);

        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) , 'api_key' => 'NO_KEY' ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()),  'api_key' => 'NO_KEY'];
        };

        if($requestAPI->successful()) //200
        {
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) , 'api_key' => json_decode($requestAPI->getBody())->data->api_key ];
        };
    }

    public static function RefreshToken()
    {
        $stage_url = 'https://pre.petroapp.com.sa/webservice/refresh_token';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/refresh_token';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;

        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$api_key,
        ])->post($url);

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
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };
    }

    public static function getVehicles()
    {
        $stage_url = 'https://pre.petroapp.com.sa/webservice/vehicles';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/vehicles';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;

        $api_key = PetroAppAPIController::GenerateApiKey('','');
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$api_key,
        ])->get($url,[
            'plate' => []
        ]);

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
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };
    }

    //NEED TEST
    public static function UpdatedVehicleConsumption()
    {
        $stage_url = 'https://pre.petroapp.com.sa/webservice/updated_vehicle_consumption';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/updated_vehicle_consumption';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$api_key,
        ])->post($url,[
            'plate' => '',
            'current_consumption' => '',
        ]);

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
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };
    }

    //NEED TEST
    public static function insertTrip($obj)
    {
        $stage_url = 'https://pre.petroapp.com.sa/webservice/insert_trip';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/insert_trip';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$api_key,
        ])->post($url,[
            'plate' => $obj['plate'],
            'trip_number' => $obj['trip_number'],
            'max_trip_consumption_rial' => $obj['max_trip_consumption_rial'],
            'start_date' => $obj['start_date'],
            'end_date' => $obj['end_date'],
        ]);

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
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };
    }

    //NEED TEST
    public static function UpdateTrip()
    {
        $stage_url = 'https://pre.petroapp.com.sa/webservice/update_trip';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/update_trip';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$api_key,
        ])->post($url,[

            'current_trip_number' => '',
            'trip_number' => '',

            'increase_trip_consumption' => '',
            'reset' => '',
            'trip_consumption' => '',

            'start_date' => '',
            'end_date' => '',
        ]);

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
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };
    }

    public static function deactivateTrip($obj)
    {
        $stage_url = 'https://pre.petroapp.com.sa/webservice/deactivate_trip/';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/deactivate_trip/';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$api_key,
        ])->post(
            $url.$obj['trip_number']
            );

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
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };
    }

    public static function activeTrip($obj)
    {
        $stage_url = 'https://pre.petroapp.com.sa/webservice/active_trip/';
        $prod_url = 'https://pre.petroapp.com.sa/webservice/active_trip/';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$api_key,
        ])->post(
            $url.$obj['trip_number'],
        );

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
            return ['success' => true,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody())];
        };
    }

}
