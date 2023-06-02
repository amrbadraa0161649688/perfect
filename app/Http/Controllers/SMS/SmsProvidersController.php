<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Branch;
use App\Models\SystemCode;
use App\Models\SMSProviders;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;

class SmsProvidersController extends Controller
{
    //
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = [ 'company' => $company ,'branch'=> session('branch') ];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        return view('sms.providers.index', compact('companies','user_data'));
    }

    public function data(Request $request)
    {
        
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $view = view('sms.providers.data', compact('company','companies'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function dataTable(Request $request,$companyId)
    {
        $providers = SMSProviders::where('company_id', $companyId);

        
        $providers = $providers->get();

        return Datatables::of($providers)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return (string)view('sms.providers.Actions.actions', compact('row'));
            })
            ->addColumn('company', function ($row) {
                return optional($row->company)->company_name_ar;
                
            })
            ->addColumn('provider_is_active', function ($row) {
                return trans('sms.status_'.$row->provider_is_active); 
            })
            ->addColumn('provider_type', function ($row) {
                return optional($row->provider_type)->getBranchName();
                
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'company_id_m' => 'required|exists:companies,company_id',
            'sms_provider_name' => 'required',
            'sms_provider_phone' => 'required',
            'account_sid' => 'unique:sms_provider,account_sid',
            'account_user_name' => 'unique:sms_provider,account_user_name',
            'account_password' => 'required',
            
        ];
       
        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }
        $company = Company::where('company_id', request()->company_id_m)->first();
        \DB::beginTransaction();
        $data_set = [];
        $provider = new SMSProviders();
       
        $data_set[] = [
            'uuid' => \DB::raw('NEWID()'),
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'sms_provider_name' => $request->sms_provider_name,
            'sms_provider_phone' => $request->sms_provider_phone,
            'account_sid' => $request->account_sid,
            'account_user_name' => $request->account_user_name,
            'account_password' => $request->account_password,
            'created_user' =>  auth()->user()->user_id,
            'provider_is_active' => true,
        ];
       
        $provider_save = $provider->insert($data_set);

        if (!$provider_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }
        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    }

    public function edit(Request $request,$uuid)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $provider = SMSProviders::where('uuid', $uuid)->first();
        $view = view('sms.providers.edit',compact('provider','company','companies'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'uuid' => 'required|exists:sms_provider,uuid',
            'company_id_m_e' => 'required|exists:companies,company_id',
            'sms_provider_name_e' => 'required',
            'sms_provider_phone_e' => 'required',
            'account_sid_e' => 'required',
            'account_user_name_e' => 'required',
            'account_password_e' => 'required',
            
        ];
       
        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }
        $company = Company::where('company_id', request()->company_id_m_e)->first();
        \DB::beginTransaction();
        $data_set = [];
        $provider = SMSProviders::where('uuid','=',$request->uuid)->first();
       
        
        $provider->company_group_id = $company->company_group_id;
        $provider->company_id = $company->company_id;
        $provider->sms_provider_name = $request->sms_provider_name_e;
        $provider->sms_provider_phone = $request->sms_provider_phone_e;
        $provider->account_sid = $request->account_sid_e;
        $provider->account_user_name = $request->account_user_name_e;
        $provider->account_password = $request->account_password_e;
        $provider->updated_user =  auth()->user()->user_id;
        
        $provider_save = $provider->update();

        if (!$provider_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }
        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    }
}
