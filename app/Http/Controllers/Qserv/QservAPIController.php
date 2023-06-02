<?php

namespace App\Http\Controllers\Qserv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use App\Models\StationInvoiceQR;
use App\Models\Branch;
use App\Models\SystemCode;

class QservAPIController extends Controller
{
    //
    private static $url_api_mode = 'stage';

    public static function GetTransactions($fromDate,$toDate,$stationId)
    {
        $stage_url = 'http://qserappyazan1-001-site1.etempurl.com/api/B2B/GetTransactions';
        $prod_url = 'http://qserappyazan1-001-site1.etempurl.com/api/B2B/GetTransactions';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get($url, [

            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'stationId' => $stationId  
            
        ]);

        if($requestAPI->serverError()) //500
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()), 'param' =>['fromDate' => $fromDate, 'toDate' => $toDate, 'stationId' => $stationId ] ];
        };

        if($requestAPI->failed() || $requestAPI->clientError()) //400
        {
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()), 'param' =>['fromDate' => $fromDate, 'toDate' => $toDate, 'stationId' => $stationId ]];
        };

        if($requestAPI->successful()) //200
        {
            $newTrans = $requestAPI->getBody();
            $tran = QservAPIController::storeTrans($newTrans,$fromDate,$toDate,$stationId);
            if($tran['success'] == false)
            {   
                return ['success' => false,'error' => true,'statusCode'=> 400 ,'body' => [] , 'param' =>['fromDate' => $fromDate, 'toDate' => $toDate, 'stationId' => $stationId ], 'msg' => $tran['msg']];

            }
            return ['success' => true,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()), 'param' =>['fromDate' => $fromDate, 'toDate' => $toDate, 'stationId' => $stationId ],'msg' => $tran['msg']];
        };
    }

    public static function GetTanks($stationId)
    {
        $stage_url = 'http://qserappyazan1-001-site1.etempurl.com/api/B2B/GetTanks';
        $prod_url = 'http://qserappyazan1-001-site1.etempurl.com/api/B2B/GetTanks';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->get($url, [

            'stationId' => $stationId,
            
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
            return ['success' => false,'error' => true,'statusCode'=> $requestAPI->getStatusCode() ,'body' => json_decode($requestAPI->getBody()) ];
        };
    }

    public static function UpdatePrice($stationId,$price,$fuelType)
    {
        $stage_url = 'http://qserappyazan1-001-site1.etempurl.com/api/B2B/UpdatePrice';
        $prod_url = 'http://qserappyazan1-001-site1.etempurl.com/api/B2B/UpdatePrice';
        $url   = (self::$url_api_mode == 'stage') ? $stage_url : $prod_url;
      
        $requestAPI = \Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url,[
            'zoneId' => 1,
            'stationId' => (int) $stationId,
            'price' => (float) $price,
            'fuelType' => (int) $fuelType
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
            return ['success' => true ,'error' => false,'statusCode'=> $requestAPI->getStatusCode() ,'body' => $requestAPI ];
        };
    }

    public static function storeTrans($newTrans,$fromDate,$toDate,$stationId)
    {
        $trans = StationInvoiceQR::get();
        
        $new_trans_data_set = [];
        $new_trans_count = 0;

        $new = json_decode($newTrans);
        foreach($new as $nt)
        {
            if(!in_array($nt->transactionId,$trans->pluck('transaction_id')->toArray()))
            //if(!in_array($nt->transactionId,$trans->pluck('r_id')->toArray()))
            {
                $branch = Branch::where('station_id', $stationId)->first();
                $company = $branch->company;
                $grade = SystemCode::where('sys_category_id','=',70)->where('company_id', $company->company_id)->whereIn('system_code', ['70001' ,'70002', '70003'])->where('system_code_filter', '=', 1)->first();
                $vat_rate = 1.15;
                $total_vat = $nt->amount - ($nt->amount / $vat_rate);
                $new_trans_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'company_group_id' => $company->company_group_id,
                    'company_id' =>  $company->company_id,
                    'branch_id' => $branch->branch_id,
                    'station_id' => $branch->station_id,

                    'nozzle_id' => $nt->nozzleId,
                    'zone_id' => $nt->zoneId,
                    'created_by' => 1,//auth()->user()->user_id,
                    'fuel_type' => $nt->fuelType,

                    'payment_method' => $nt->paymentMethod,
                   
                    //'pumpside' => $nt->pumpside,
                    'grade' => $grade->system_code_name_en,
                    'grade_ar' => $grade->system_code_name_ar,
                    'volume' => $nt->volume,

                    'amount' => $nt->amount,
                    'price' => $nt->price,
                    'transaction_id' => $nt->transactionId,
                    'r_id' => $nt->uId,

                    'vate_rate' => abs(1 - $vat_rate),
                    'total_vat' => $total_vat,
                    //'inv_code' => '',
                    //'inv_date' => '',
                    'employee_id' => $nt->employeeId,
                    //'qr' => '',
                    'print_status' => 'pending',
                    'trans_date' => $nt->transactionDate
                ];
                $new_trans_count = $new_trans_count+1;
            }
        }
        if($new_trans_count == 0)
        {
            return ['success'=> true, 'error'=> false , 'msg' => 'There no any new records'];
        }

        $inv = new StationInvoiceQR;
        foreach (array_chunk($new_trans_data_set,20) as $chunk)
        {
            $inv_save = $inv->insert($chunk);
           if (! $inv_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }
        }
        return ['success'=> true, 'error'=> false , 'msg' => 'There '.$new_trans_count.' records has been added'];
    }


}
