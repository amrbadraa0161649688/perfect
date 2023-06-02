<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Branch;
use App\Models\SystemCode;
use App\Models\SMSQueue;
use App\Models\SMSProviders;
use App\Models\SMSCategory;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class SmsQueueController extends Controller
{
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = ['company' => $company, 'branch' => session('branch')];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $providers = SMSProviders::where('company_id', $company->company_id)->get();
        $category = SMSCategory::where('company_id', $company->company_id)->get();
        return view('sms.queue.index', compact('companies', 'user_data', 'providers', 'category'));
    }

    public function data(Request $request)
    {

        $company_id = (isset(request()->company_id) ? request()->company_id : auth()->user()->company->company_id);
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $providers = SMSProviders::where('company_id', $company->company_id)->get();
        $category = SMSCategory::where('company_id', $company->company_id)->get();

        $view = view('sms.queue.data', compact('company', 'companies', 'providers', 'category'));
        return \Response::json(['view' => $view->render(), 'success' => true]);
    }

    public function dataTable(Request $request, $companyId)
    {
        $sms_queue = SMSQueue::where('company_id', $companyId);

        if ($request->search['sms_provider_id']) {
            $sms_queue = $sms_queue->where('sms_provider_id', '=', $request->search['sms_provider_id']);
        }

        if ($request->search['sms_category_id']) {
            $sms_queue = $sms_queue->where('sms_category_id', '=', $request->search['sms_category_id']);
        }


        $sms_queue = $sms_queue->orderBy('created_at', 'desc')->get();

        return Datatables::of($sms_queue)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return (string)view('sms.queue.Actions.actions', compact('row'));
            })
            ->addColumn('company', function ($row) {
                return optional($row->company)->company_name_ar;
            })
            ->addColumn('provider', function ($row) {
                return optional($row->provider)->sms_provider_name;
            })
            ->addColumn('sms_category', function ($row) {
                return optional($row->category)->sms_name_ar;
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('sms_response_msg', function ($row) {
                return $row->sms_response_msg . '-' . $row->sms_response_id;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function PushSMS($category, $mobNo, $param1 = null, $param2 = null, $param3 = null, $param4 = null, $shortUrl = null)
    {

        $provider = $category->provider;
        $profile_id = $category->provider->account_user_name; //'ALFAYEZTR';
        $account_password = $category->provider->account_password; //'6jpp__ue';
        $sender_Id = $category->provider->account_sid; //'AlfayezGrup' ; //'ALFAYEZTR';
        $mobile_no = $mobNo; //'+966531512993' ;
        //$mobile_no = '+966580044060' ;

        $msg = $category->sms_var_1;
        $msg_en = $category->sms_var_1_en;

        if ($param1) {
            $msg = $msg . ' ' . $param1 . ' ' . $category->sms_var_2;
            // $msg_en = $msg_en . ' ' . $param1 . ' ' . $category->sms_var_2_en;
        }

        if ($param2) {
            $msg = $msg . ' ' . $param2 . ' ' . $category->sms_var_3;
            // $msg_en = $msg_en . ' ' . $param1 . ' ' . $category->sms_var_3_en;
        }


        if ($shortUrl) {
            $msg = $msg . ' ' . $shortUrl;
            $msg_en = $msg_en . ' ' . $shortUrl;
        }

        //$msg = 'Hello from perfect' ;
        //$msg = 'اهلا وسهلا بك من بيرفكت' ;
        $response = \Http::get('https://mshastra.com/sendurl.aspx?user=' . $profile_id .
            '%3E&pwd=' . $account_password .
            '%3E&senderid=' . $sender_Id .
            '%3E&mobileno=' . $mobile_no .
            '&msgtext=' . $msg . '&CountryCode=ALL&ShowError=C');

        $res = (['res' => trim($response->getBody()->getContents()), 'msg' => $msg, 'msg_en' => $msg_en]);
        return SmsQueueController::store($category, $mobNo, $res);

    }

    public function store($category, $mobNo, $res)
    {

        $qeue = new SMSQueue();
        $qeue->uuid = \DB::raw('NEWID()');
        $qeue->company_group_id = $category->company_group_id;
        $qeue->company_id = $category->company_id;

        $qeue->sms_provider_id = $category->provider->sms_provider_id;
        $qeue->sms_category_id = $category->sms_category_id;

        $qeue->sms_response_msg = trans('sms.' . $res['res']);
        //  $qeue->sms_response_id = $res['res'];

        $qeue->sms_body_ar = $res['msg'];
        $qeue->sms_body_en = $res['msg_en'];

        $qeue->sms_is_sms = $category->sms_is_sms;
        $qeue->sms_is_whatsapp = $category->sms_is_whatsapp;
        $qeue->sms_is_email = $category->sms_is_email;
        $qeue->sms_is_notification = $category->sms_is_notification;

        $qeue->sms_status_sms = 1;
        $qeue->sms_status_whatsapp = 0;
        $qeue->sms_status_email = 0;
        $qeue->sms_status_notification = 0;

        $qeue->sms_mobile_no = $mobNo;
        $qeue->sms_send_datetime = Carbon::now();
        $qeue->created_user = auth()->user()->user_id;

        $qeue_save = $qeue->save();

        if (!$qeue_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح']);

    }

    
    public function getShortUrl($url)
    {
        $builder = new \AshAllenDesign\ShortURL\Classes\Builder();
        $shortURLObject = $builder->destinationUrl($url)->make();
        $shortURL = $shortURLObject->default_short_url;
        //$shotLinkKey = $shortURLObject->url_key;
        return $shortURL;
    }

}
