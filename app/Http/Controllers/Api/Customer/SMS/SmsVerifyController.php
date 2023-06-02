<?php

namespace App\Http\Controllers\Api\Customer\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\SMSCategory;
use App\Models\SMSProviders;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SmsVerifyController extends Controller
{
    public function send(Request $request)
    {
        $rules = [
            'phone' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return responseFail('fail', 422, $validator->errors());
        }
        $code = rand(0000, 9999);
        $response = Http::get('http://REST.GATEWAY.SA/api/SendSMS?api_id=API72346673700&api_password=Gateway@123&sms_type=T&encoding=T&sender_id=Gateway.sa&phonenumber=' . $request->phone . '&textmessage=' . $code . '&uid=xyz');
        if ($response['status'] != "S") {
            return responseFail('try again');
        }

        return responseSuccess(['code' => $code], __('messages.form.submit'));
    }

    public function check(Request $request, $uuid)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $providers = SMSProviders::where('company_id', $company_id)->get();
        $category = SMSCategory::where('uuid', $uuid)->first();
        $sms_category_type_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 135)->get();
        $view = view('sms.category.edit', compact('category', 'providers', 'company', 'companies', 'sms_category_type_lits'));
        return \Response::json(['view' => $view->render(), 'success' => true]);
    }
}
