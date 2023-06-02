<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\StoreItem;
use App\Models\Branch;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;

class StoreItemController extends Controller
{
    //
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = [ 'company' => $company ,'branch'=> session('branch') ];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
      
        return view('store.item.index', compact('companies','branch_list','warehouses_type_lits','user_data'));
    }

    public function data(Request $request)
    {
        //return request()->search['warehouses_type'];
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        $unit_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',35)->get();
        
        $view = view('store.item.data', compact('company','companies','unit_lits','branch_list','warehouses_type_lits'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function dataTable(Request $request,$companyId)
    {
        $items = StoreItem::where('company_id', $companyId)->where('isdeleted','=',0);

        if($request->search['warehouses_type'])
        {
            info('warehouses_type' . $request->search['warehouses_type'])  ;
            $items = $items->where('item_category','=',$request->search['warehouses_type']);
        }
        if($request->search['branch_id'])
        {
            info('branch_id');
            $items = $items->where('branch_id','=',$request->search['branch_id']); 
        }
        if($request->search['item_code'])
        {
            $items = $items->where('item_code','=',$request->search['item_code']);
        }
        if($request->search['item_vendor_code'])
        {
            $items = $items->where('item_vendor_code','=',$request->search['item_vendor_code']);
        }
        if($request->search['item_code_1'])
        {
            $items = $items->where('item_code_1','=',$request->search['item_code_1']);
        }
        if($request->search['item_code_1'])
        {
            $items = $items->where('item_code_2','=',$request->search['item_code_2']);
        }
        if($request->search['item_name_a'])
        {
            $items = $items->where('item_name_a','=',$request->search['item_name_a']);
        }
        if($request->search['item_name_e'])
        {
            $items = $items->where('item_name_e','=',$request->search['item_name_e']);
        }
        
        $items = $items->get();

        return Datatables::of($items)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                info($row->item_id);
                return (string)view('store.item.Actions.actions', compact('row'));
            })
            ->addColumn('branch', function ($row) {
                return optional($row->branch)->getBranchName();
                
            })
            ->addColumn('unit', function ($row) {
                optional($row->unit)->getSysCodeName();
            })
            ->addColumn('item_category', function ($row) {
                optional($row->itemCategory)->getSysCodeName();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'company_id_m' => 'required|exists:companies,company_id',
            'item_category_m' => 'required|exists:system_codes,system_code',
            'branch_id_m' => 'required|exists:branches,branch_id',
            'item_code_m' => 'required',
            //'item_vendor_code_m' => 'required',
            'item_name_a_m' => 'required',
            'item_name_e_m' => 'required',
            //'item_location_m' => 'required',
            'item_unit_m' => 'required|exists:system_codes,system_code',
            //'item_code_1_m' => 'required',
            //'item_code_2_m' => 'required',
            // 'item_price_sales_m' => 'required',
            // 'item_price_cost_m' => 'required',
            // 'item_balance_m' => 'required',
        ];
       
        
        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }
        $item_branch = $request->branch_id_m ;
        $company = Company::where('company_id', request()->company_id_m)->first();
        \DB::beginTransaction();
        $item_data_set = [];
        $store_item = new StoreItem();
        foreach ($item_branch as $i => $branch) {
            $item_data_set[] = [
                'uuid' => \DB::raw('NEWID()'),
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch,
                'item_category' =>  SystemCode::where('system_code','=',$request->item_category_m)->first()->system_code_id,
                'item_location' => $request->item_location_m,
                'item_code' => $request->item_code_m,
                'item_vendor_code' => $request->item_vendor_code_m,
                'item_name_e' => $request->item_name_e_m,
                'item_name_a' => $request->item_name_a_m,
                'item_price_sales' => $request->item_price_sales_m ? $request->item_price_sales_m : 0 ,
                'item_price_cost' => $request->item_price_cost ? $request->item_price_cost : 0 ,
                'item_balance' => $request->item_balance ? $request->item_balance : 0 ,
                'item_code_1' => $request->item_code_1_m ,
                'item_code_2' => $request->item_code_2_m, 
                'item_desc' => $request->item_desc_m,
                'item_unit' => SystemCode::where('system_code','=',$request->item_unit_m)->first()->system_code_id,
                'created_user' =>  auth()->user()->user_id,
            ];
        }
        $store_item_save = $store_item->insert($item_data_set);

        if (!$store_item_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }
        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    }

    // public function store(Request $request)
    // {
    //     $rules = [
    //         'company_id_m' => 'required|exists:companies,company_id',
    //         'item_category_m' => 'required|exists:system_codes,system_code',
    //         'branch_id_m' => 'required|exists:branches,branch_id',
    //         'item_code_m' => 'required',
    //         'item_vendor_code_m' => 'required',
    //         'item_name_a_m' => 'required',
    //         'item_name_e_m' => 'required',
    //         'item_location_m' => 'required',
    //         'item_unit_m' => 'required|exists:system_codes,system_code',
    //         'item_code_1_m' => 'required',
    //         'item_code_2_m' => 'required',
    //         // 'item_price_sales_m' => 'required',
    //         // 'item_price_cost_m' => 'required',
    //         // 'item_balance_m' => 'required',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);
         
    //     if ($validator->fails())
    //     {
    //         return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
    //     }

    //     $company = Company::where('company_id', request()->company_id_m)->first();
    //     \DB::beginTransaction();
    //     $store_item = new StoreItem();

    //     $store_item->uuid = \DB::raw('NEWID()');
    //     $store_item->company_group_id = $company->company_group_id;
    //     $store_item->company_id = $company->company_id;
    //     $store_item->branch_id = $request->branch_id_m;
    //     $store_item->item_category =  SystemCode::where('system_code','=',$request->item_category_m)->first()->system_code_id;
    //     $store_item->item_location = $request->item_location_m;
    //     $store_item->item_code = $request->item_code_m;
    //     $store_item->item_vendor_code = $request->item_vendor_code_m;
    //     $store_item->item_name_e = $request->item_name_e_m;
    //     $store_item->item_name_a = $request->item_name_a_m;
    //     $store_item->item_price_sales = 0;//$request->item_price_sales_m;
    //     $store_item->item_price_cost = 0;//$request->item_price_cost;
    //     $store_item->item_balance = 0;//$request->item_balance;
    //     $store_item->item_code_1 = $request->item_code_1_m;
    //     $store_item->item_code_2 = $request->item_code_2_m; 
    //     $store_item->item_desc = $request->item_desc_m;
    //     $store_item->item_unit = SystemCode::where('system_code','=',$request->item_unit_m)->first()->system_code_id;
    //     $store_item->created_user =  auth()->user()->user_id;

    //     $store_item_save = $store_item->save();

    //     if(!$store_item_save)
    //     {
    //         return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
    //     }

    //     \DB::commit();
    //     return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    // }

    public function edit(Request $request,$uuid)
    {

        $item = StoreItem::where('uuid', $uuid)->first();
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        $unit_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',35)->get();

        return view('store.item.edit',compact('item','company','companies','unit_lits','branch_list','warehouses_type_lits'));
        

    }

    public function update(Request $request)
    {
        $rules = [
            'uuid' => 'required|exists:store_item,uuid',
            'item_code' => 'required',
            'item_vendor_code' => 'required',
            'item_name_a' => 'required',
            'item_name_e' => 'required',
            'item_location' => 'required',
            'item_unit' => 'required|exists:system_codes,system_code',
            'item_code_1' => 'required',
            'item_code_2' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }

        $store_item = StoreItem::where('uuid','=',$request->uuid)->first();
        $store_item->item_location = $request->item_location;
        $store_item->item_code = $request->item_code;
        $store_item->item_vendor_code = $request->item_vendor_code;
        $store_item->item_name_e = $request->item_name_e;
        $store_item->item_name_a = $request->item_name_a;
        $store_item->item_code_1 = $request->item_code_1;
        $store_item->item_code_2 = $request->item_code_2; 
        $store_item->item_desc = $request->item_desc;
        $store_item->item_price_cost = $request->item_price_cost;
        $store_item->item_price_sales = $request->item_price_sales;
        $store_item->item_balance = $request->item_balance;
        $store_item->item_price_mntns = $request->item_price_mntns;
        $store_item->item_unit = SystemCode::where('system_code','=',$request->item_unit)->first()->system_code_id;
        $store_item->updated_user =  auth()->user()->user_id;

        $store_item_save = $store_item->save();

        if(!$store_item_save)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    }

    public function delete(Request $request)
    {
      
        $rules = [
            'uuid' => 'required|exists:store_item,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }

        $store_item = StoreItem::where('uuid','=',$request->uuid)->first();

        $store_item->isdeleted = 1;
        $store_item->updated_user =  auth()->user()->user_id;
        $store_item_save = $store_item->save();

        if(!$store_item_save)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);

    }

    public function search(Request $request)
    {
        
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $branch =  session('branch');
        $result = StoreItem::where('company_id', $company_id)->where('branch_id','=', $branch->branch_id);

        if(isset($request->item_code))
        {
            $result = $result->where('item_code','like','%'.$request->item_code.'%');
        }
        if($request->item_name_a)
        {
            $result = $result->where('item_name_a','like','%'.$request->item_name_a.'%');
        }
        if($request->item_name_e)
        {
            $result = $result->where('item_name_e','like','%'.$request->item_name_e.'%');   
        }
        if($request->item_vendor_code)
        {
            $result = $result->where('item_vendor_code','like','%'.$request->item_vendor_code.'%');   
        }
        if($request->item_code_1)
        {
            $result = $result->where('item_code_1','like','%'.$request->item_code_1.'%');   
        }
        if($request->item_code_2)
        {
            $result = $result->where('item_code_2','like','%'.$request->item_code_2.'%');   
        }
        $result = $result->get();
        $view = view('store.search.search_result', compact('company','result'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ,'msg'=>'تمت العملية بنجاح' ]);

    }

    public function getItemDetails(Request $request)
    {
        
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $branch =  session('branch');
        $vou_type = SystemCode::whereIn('system_code', ['62003','62009'])->pluck('system_code_id');
        $item = StoreItem::where('item_id','=', $request->item_id)->first();
        $result = StoreItem::where('company_id', $company_id)->where('item_code','=', $item->item_code);
        $item_id_lits = $result->pluck('item_id');
        $result = $result->get();
        
        $recining_history = PurchaseDetails::whereIn('store_vou_item_id', $item_id_lits)->where('isdeleted', '=', 0)->pluck('store_hd_id');
        if(sizeof($recining_history))
        {
            $store_hd_id = Purchase::whereRaw('store_hd_id IN (select MAX(store_hd_id) FROM store_hd where store_vou_type in (' . $vou_type->implode(',') .') and store_hd_id in ('.$recining_history->implode(',').')  GROUP BY store_acc_no)');
            $recining_history = PurchaseDetails::whereIn('store_hd_id', $store_hd_id->pluck('store_hd_id'))
                ->whereIn('store_vou_item_id', $item_id_lits)->where('isdeleted', '=', 0)
                ->get();
        }
        else{
            $recining_history = PurchaseDetails::where('store_hd_id', 0)
            ->whereIn('store_vou_item_id', $item_id_lits)->where('isdeleted', '=', 0)
            ->get();
        }
        
        $view = view('store.item.item_details', compact('company','result','recining_history'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ,'msg'=>'تمت العملية بنجاح' ]);

    }

}
