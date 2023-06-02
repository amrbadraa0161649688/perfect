<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Branch;
use App\Models\SystemCode;
use App\Models\SMSCategory;
use App\Models\SMSProviders;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;

class SmsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = [ 'company' => $company ,'branch'=> session('branch') ];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $providers = SMSProviders::where('company_id', $company->company_id)->get();
        $sms_category_type_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 135)->get();
        return view('sms.category.index', compact('companies','user_data','providers','sms_category_type_lits'));
    }

    public function data(Request $request)
    {
        
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $providers = SMSProviders::where('company_id', $company_id)->get();
        $sms_category_type_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 135)->get();
        $view = view('sms.category.data', compact('company','companies','providers','sms_category_type_lits'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function dataTable(Request $request,$companyId)
    {
        $category = SMSCategory::where('company_id', $companyId);

        if($request->search['sms_provider_id'])
        {
            $category = $category->where('sms_provider_id','=',$request->search['sms_provider_id']);
        }
        $category = $category->get();

        return Datatables::of($category)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return (string)view('sms.category.Actions.actions', compact('row'));
            })
            ->addColumn('company', function ($row) {
                return optional($row->company)->company_name_ar;
                
            })
            ->addColumn('provider', function ($row) {
                return optional($row->provider)->sms_provider_name;
                
            })
            
            ->editColumn('sms_is_sms', function ($row) {
                return ($row->sms_is_sms == 1? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-remove"></i>');
                
            })
            ->editColumn('sms_is_whatsapp', function ($row) {
                return ($row->sms_is_whatsapp == 1? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-remove"></i>');
                
            })
            ->editColumn('sms_is_email', function ($row) {
                return ($row->sms_is_email == 1? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-remove"></i>');
                
            })
            ->editColumn('sms_is_notification', function ($row) {
                return ($row->sms_is_notification == 1? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-remove"></i>');
                
            })

            ->addColumn('sms_is_active', function ($row) {
                return trans('sms.status_'.$row->sms_is_active); 
            })
            ->rawColumns(['action','sms_is_sms','sms_is_whatsapp','sms_is_email','sms_is_notification'])
            ->make(true);
    }


    public function store(Request $request)
    {
        
        $rules = [
            'company_id_m' => 'required|exists:companies,company_id',
            'sms_provider_id_m' => 'required|exists:sms_provider,sms_provider_id',
            'sms_category_type_m' => 'required',
            'sms_name_ar' => 'required',
            'sms_name_en' => 'required',
            //'sms_body_ar' => 'required',
            //'sms_body_en' => 'required',
           
        ];
       
        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }
        $company = Company::where('company_id', request()->company_id_m)->first();
        \DB::beginTransaction();
        $data_set = [];
        $category = new SMSCategory();
       
        $category->uuid = \DB::raw('NEWID()');
        $category->company_group_id = $company->company_group_id;
        $category->company_id = $company->company_id;
        $category->sms_provider_id = $request->sms_provider_id_m;
        $category->sms_name_ar = $request->sms_name_ar;
        $category->sms_name_en = $request->sms_name_en;
        $category->sms_category_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', $request->sms_category_type_m)->first()->system_code_id;
        //$category->sms_body_ar = $request->sms_body_ar;
        //$category->sms_body_en = $request->sms_body_en;
        $category->created_user =  auth()->user()->user_id;

        $category->sms_var_1 = (isset($request->sms_var_1) == 1 ? $request->sms_var_1 :null);
        $category->sms_var_2 = (isset($request->sms_var_2) == 1 ? $request->sms_var_2 :null);
        $category->sms_var_3 = (isset($request->sms_var_3) == 1 ? $request->sms_var_3 :null);
        $category->sms_var_4 = (isset($request->sms_var_4) == 1 ? $request->sms_var_4 :null);
        
        $category->sms_var_1_en = (isset($request->sms_var_1_en) == 1 ? $request->sms_var_1_en :null);
        $category->sms_var_2_en = (isset($request->sms_var_2_en) == 1 ? $request->sms_var_2_en :null);
        $category->sms_var_3_en = (isset($request->sms_var_3_en) == 1 ? $request->sms_var_3_en :null);
        $category->sms_var_4_en = (isset($request->sms_var_4_en) == 1 ? $request->sms_var_4_en :null);

        $category->sms_is_sms = (isset($request->sms) == 1 ? 1 :0);
        $category->sms_is_whatsapp =  (isset($request->whatsaap) == 1 ? 1 :0);
        $category->sms_is_email =  (isset($request->email) == 1 ? 1 :0);
        $category->sms_is_notification = (isset($request->notification) == 1 ? 1 :0);

        $category->sms_is_active = true;
        
       
        $category_save = $category->save();

        if (!$category_save) {
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
        $providers = SMSProviders::where('company_id', $company_id)->get();
        $category = SMSCategory::where('uuid', $uuid)->first();
        $sms_category_type_lits = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 135)->get();
        $view = view('sms.category.edit',compact('category','providers','company','companies','sms_category_type_lits'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'uuid' => 'required|exists:sms_category,uuid',
            'company_id_m_e' => 'required|exists:companies,company_id',
            'sms_provider_id_m' => 'required|exists:sms_provider,sms_provider_id',
            'sms_category_type_m' => 'required',
            'sms_name_ar' => 'required',
            'sms_name_en' => 'required',
            //'sms_body_ar' => 'required',
            //'sms_body_en' => 'required',
            
        ];

        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }
        $company = Company::where('company_id', request()->company_id_m_e)->first();
        \DB::beginTransaction();
        $data_set = [];
       
        
        $category =  SMSCategory::where('uuid','=',$request->uuid)->first();

        
       
        $category->company_group_id = $company->company_group_id;
        $category->company_id = $company->company_id;
        $category->sms_provider_id = $request->sms_provider_id_m;
        $category->sms_name_ar = $request->sms_name_ar;
        $category->sms_name_en = $request->sms_name_en;
        $category->sms_category_type = SystemCode::where('company_group_id', '=', $company->company_group_id)->where('system_code', '=', $request->sms_category_type_m)->first()->system_code_id;
        //$category->sms_body_ar = $request->sms_body_ar;
        //$category->sms_body_en = $request->sms_body_en;
        $category->updated_user =  auth()->user()->user_id;

        $category->sms_var_1 = (isset($request->sms_var_1) == 1 ? $request->sms_var_1 :null);
        $category->sms_var_2 = (isset($request->sms_var_2) == 1 ? $request->sms_var_2 :null);
        $category->sms_var_3 = (isset($request->sms_var_3) == 1 ? $request->sms_var_3 :null);
        $category->sms_var_4 = (isset($request->sms_var_4) == 1 ? $request->sms_var_4 :null);

        $category->sms_var_1_en = (isset($request->sms_var_1_en) == 1 ? $request->sms_var_1_en :null);
        $category->sms_var_2_en = (isset($request->sms_var_2_en) == 1 ? $request->sms_var_2_en :null);
        $category->sms_var_3_en = (isset($request->sms_var_3_en) == 1 ? $request->sms_var_3_en :null);
        $category->sms_var_4_en = (isset($request->sms_var_4_en) == 1 ? $request->sms_var_4_en :null);
        


        $category->sms_is_sms = (isset($request->sms) == 1 ? 1 :0);
        $category->sms_is_whatsapp =  (isset($request->whatsaap) == 1 ? 1 :0);
        $category->sms_is_email =  (isset($request->email) == 1 ? 1 :0);
        $category->sms_is_notification = (isset($request->notification) == 1 ? 1 :0);
        
        $category_save = $category->update();

        if (!$category_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }
        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    }

}
