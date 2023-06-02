<?php

namespace App\Http\Controllers\Petro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TripHd;
use App\Models\Company;
use App\Models\Branch;
use App\Models\SystemCode;
use App\Models\WaybillHd;
use App\Http\Controllers\Petro\PetroAppAPIController;

class PetroAppController extends Controller
{
    //
    
    public function SendRequest($type,Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = ['company' => $company, 'branch' => session('branch')];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
       
        $petro_app_status_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 155);
      
       if($type == 'trip')
       {
            $obj = TripHd::where('trip_hd_id','=',$request->trip_hd_id)->first();
       }
       if($type == 'waybill')
       {
            //red data from waybill head
            $obj = WaybillHd::where('waybill_id','=',$request->waybill_id)->first();
       }
       else{
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
       }
      
        $api_request = new PetroAppAPIController();

        switch ($request->action) {
            case 'insert':
                $data = $api_request->insertTrip($obj->PetroInsertData);
                $status_code = $petro_app_status_list->where('system_code', '=', '155001')->first();
                break;

            case 'activate':
                $data = $api_request->deactivateTrip($obj->PetroInsertData);
                $status_code = $petro_app_status_list->where('system_code', '=', '155002')->first();
                break;

            case 'deactivate':
                $data = $api_request->activeTrip($obj->PetroInsertData);
                $status_code = $petro_app_status_list->where('system_code', '=', '155003')->first();
                break;

            default:
                abort(404);
        }
      
        if($data['statusCode'] != 200)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' . $data['statusCode']]);
        }
        if(isset($data['body']->type))
        {
            if($data['body']->type == 'error')
            {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
            }
        }
        
        if(!$data['body']->success)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' . $data['body']->message]);
        }
        
        $obj->petro_status = $status_code->system_code_id;
        $obj_update = $obj->update();

        if (!$obj_update) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح']);

    }
}
