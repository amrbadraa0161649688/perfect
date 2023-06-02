<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Branch;
use App\Models\CompanyMenuSerial;
use App\Models\Stocking;
use App\Models\StockingDetails;
use App\Models\StoreItem;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Customer;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class StockingController extends Controller
{
    //
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = [ 'company' => $company ,'branch'=> session('branch') ];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
      
        return view('store.stocking.index', compact('companies','branch_list','warehouses_type_lits','user_data'));
    }

    public function data(Request $request)
    {
        //return request()->search['warehouses_type'];
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        
        $view = view('store.stocking.data', compact('company','companies','branch_list','warehouses_type_lits'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function dataTable(Request $request,$companyId)
    {
        $stocking = Stocking::where('company_id', $companyId)->where('isdeleted','=',0);

        if($request->search['warehouses_type'])
        {
            $stocking = $stocking->where('store_category_type','=',$request->search['warehouses_type']);
        }
        if($request->search['branch_id'])
        {
            $stocking = $stocking->where('branch_id','=',$request->search['branch_id']); 
        }
        
        $stocking = $stocking->orderBy('created_date','desc')->get();

        return Datatables::of($stocking)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return (string)view('store.stocking.Actions.actions', compact('row'));
            })
            ->addColumn('branch', function ($row) {
                return $row->Branch->getBranchName();
            })
            ->addColumn('store_category_type', function ($row) {
                return $row->storeCategory->getSysCodeName();
            })
            ->addColumn('store_location', function ($row) {
                return $row->store_location;
            })
            ->addColumn('store_vou_date', function ($row) {
                return $row->getVouDate();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $rules = [
            'store_category_type' => 'required',
            'branch' => 'required',
            'store_location' => 'required',
            'store_vou_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $company = session('company') ? session('company') : auth()->user()->company;

        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $request->branch)->where('app_menu_id', 124);
        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم طلب الشراء يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'STOCKING-' . $request->branch . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
        


        \DB::beginTransaction();
        $stocking = new  Stocking(); 

        $stocking->uuid = \DB::raw('NEWID()');

        $stocking->company_group_id = $company->company_group_id;
        $stocking->company_id = $company->company_id;
        $stocking->branch_id = $request->branch;

        $stocking->store_category_type = SystemCode::where('system_code', '=', $request->store_category_type)->first()->system_code_id;;
        $stocking->store_hd_code = $new_serial;
        $stocking->store_location = $request->store_location;
        $stocking->store_vou_notes = $request->store_vou_notes;
        $stocking->store_vou_date = $request->store_vou_date;
        $stocking->created_user = auth()->user()->user_id;

        $stocking_save = $stocking->save();
        if (!$stocking_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $stocking->refresh()->uuid]);


    }

    

    public function edit(Request $request,$uuid)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        $stocking = Stocking::where('uuid', $uuid)->first();
        $itemes = Storeitem::where('company_id', $stocking->company_id)
            ->where('branch_id', '=', $stocking->branch_id)
            ->where('item_category', '=', $stocking->store_category_type)->get();

        return  view('store.stocking.edit', compact('company','companies','branch_list','warehouses_type_lits','stocking','itemes'));

    }

    public function show(Request $request,$uuid)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        $stocking = Stocking::where('uuid', $uuid)->first();
        $itemes = Storeitem::where('company_id', $stocking->company_id)
            ->where('branch_id', '=', $stocking->branch_id)
            ->where('item_category', '=', $stocking->store_category_type)->get();

        return  view('store.stocking.show', compact('company','companies','branch_list','warehouses_type_lits','stocking','itemes'));

    }

    public function addItem(Request $request)
    {

        $rules = [
            'stocking_uuid' => 'required',
            'item_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $header = Stocking::where('uuid', '=', $request->stocking_uuid)->first();
        $item_data = json_decode($request->item_table_data, true);


        \DB::beginTransaction();
        $store_item = StoreItem::where('item_id', $item_data['store_vou_item_id'])->first();
        $is_added_befor = StockingDetails::where('store_stocking_hd_id', $header->store_stocking_hd_id)->where('store_vou_item_id', '=', $item_data['store_vou_item_id'])->where('isdeleted', '=', 0);
        if ($is_added_befor->count() > 0) {
            $stocking_details = $is_added_befor->first();

            $stocking_details->store_vou_qnt = $stocking_details->store_vou_qnt +  $item_data['store_vou_qnt'];
            $stocking_details->store_vou_balance = $store_item->item_balance;
            $stocking_details->store_vou_qnt_add = ($stocking_details->store_vou_qnt > $store_item->item_balance?  ($stocking_details->store_vou_qnt -$store_item->item_balance) : 0);
            $stocking_details->store_vou_qnt_desc =($stocking_details->store_vou_qnt < $store_item->item_balance?  ($store_item->item_balance - $stocking_details->store_vou_qnt) : 0);
            $stocking_details->store_vou_loc =  $item_data['store_vou_loc'];
            $stocking_details->store_vou_item_price_cost = $store_item->item_price_cost;
            $stocking_details->store_vou_item_price_sales =  $store_item->item_price_sales; 
            $stocking_details->store_vou_item_price_unit = $store_item->item_unit;
            //$stocking_details->store_vou_item_total_price = $store_item->;
            $stocking_details->updated_user = auth()->user()->user_id;

            $stocking_details_update = $stocking_details->save();

            if(!$stocking_details_update)
            {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }

            //\DB::commit();
            //return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح']);
            
        }
        else{

            $stocking_details = new StockingDetails();
            $stocking_details->uuid = \DB::raw('NEWID()');

            $stocking_details->store_stocking_hd_id = $header->store_stocking_hd_id;
            $stocking_details->company_group_id  = $header->company_group_id;
            $stocking_details->company_id = $header->company_id;
            $stocking_details->branch_id = $header->branch_id;
            $stocking_details->store_category_type = $header->store_category_type;
            $stocking_details->store_vou_date = $header->store_vou_date;
            $stocking_details->store_vou_item_id = $item_data['store_vou_item_id'];
            $stocking_details->store_vou_qnt =  $item_data['store_vou_qnt'];
            $stocking_details->store_vou_balance = $store_item->item_balance;
            $stocking_details->store_vou_qnt_add = ($stocking_details->store_vou_qnt > $store_item->item_balance?  ($stocking_details->store_vou_qnt -$store_item->item_balance) : 0);
            $stocking_details->store_vou_qnt_desc =($stocking_details->store_vou_qnt < $store_item->item_balance?  ($store_item->item_balance - $stocking_details->store_vou_qnt) : 0);
            $stocking_details->store_vou_loc =  $item_data['store_vou_loc'];
            $stocking_details->store_vou_item_price_cost = $store_item->item_price_cost;
            $stocking_details->store_vou_item_price_sales =  $store_item->item_price_sales; 
            $stocking_details->store_vou_item_price_unit = $store_item->item_unit;
            $stocking_details->created_user = auth()->user()->user_id;
            $stocking_details->status ='NEW';
            $stocking_details_update = $stocking_details->save();

            if(!$stocking_details_update)
            {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }
        }

            \DB::commit();
            $view = 'store.stocking.table.row_table';
            $view = view($view, compact('header'));
            return response()->json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'view' => $view->render()]);
           
        



    }

    public function updateStockingQty(Request $request)
    {

        $rules = [
            'item_uuid' => 'required',
            'item_qty' => 'required',
            'stocking_item_location' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }
        \DB::beginTransaction();
        $stocking_details = StockingDetails::where('uuid','=',$request->item_uuid)->first();
        $item_balance = $stocking_details->store_vou_balance;
        $item_qty = $request->item_qty;

       
        
        $store_item = StoreItem::where('item_id','=',$stocking_details->store_vou_item_id)->first();
        $store_vou_loc = $stocking_details->store_vou_loc;
        

        if($store_item->item_location != $request->stocking_item_location)
        {
            $store_vou_loc = $request->stocking_item_location;
            $store_item->item_location = $request->stocking_item_location;
            $store_item->updated_user =  auth()->user()->user_id;
            $store_item_save = $store_item->save();

            if(!$store_item_save)
            {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
            }

        }

        $stocking_details->store_vou_loc = $store_vou_loc;
        $stocking_details->store_vou_qnt = $item_qty;
        $stocking_details->store_vou_qnt_add = ($item_qty > $item_balance?  ($item_qty -$item_balance) : 0);
        $stocking_details->store_vou_qnt_desc =($item_qty < $item_balance?  ($item_balance - $item_qty) : 0);
        $stocking_details_save = $stocking_details->save();

        if (!$stocking_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح']);


    }

    public function getItemLocation(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $item_location_list = StoreItem::where('branch_id','=',$request->branch)
                        ->where('item_category','=',$request->store_category_type)
                        ->select('item_location')->groupby('item_location')->get();
           
        return response()->json(['status' => 200, 'data' => $item_location_list]);

    } 

    public function getItemByCode(Request $request)
    {
        $item_data = StoreItem::where('branch_id','=',$request->branch)
                        ->where('item_category','=',$request->store_category_type)
                        ->where('item_code','=',$request->item_code);
                        
        if($item_data->count())  
        {
            return response()->json(['success' => true, 'data' => $item_data->first(), 'msg' => 'تم استرداد الصنف بنجاح']);
        } 
        return response()->json(['success' => false,  'msg' => 'الرجاء التاكد من كتابة الكود بشكل صحيح']);
       

    }

    public function deleteItem(Request $request)
    {
        $rules = [
            'uuid' => 'required|exists:store_stocking_dt,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $store_stocking_details = StockingDetails::where('uuid', '=', $request->uuid)->first();
        $header = $store_stocking_details->stocking;
       
        $store_stocking_details->isdeleted = 1;
        $store_stocking_details_save = $store_stocking_details->save();

        if (!$store_stocking_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }
        
        $view = 'store.stocking.table.row_table';
        $view = view($view, compact('header'));
        return response()->json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'view' => $view->render()]);

    }

    public function stocking(Request $request)
    {
        $rules = [
            'stocking_uuid' => 'required|exists:store_stocking_hd,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }


        \DB::beginTransaction();
        $header = Stocking::where('uuid', '=', $request->stocking_uuid)->first();
        $store_vou_ref_1 = null;
        $store_vou_ref_2 = null;
       
        $store_stocking_details_to_enterance = StockingDetails::where('store_stocking_hd_id', '=', $header->store_stocking_hd_id)
            ->where('isdeleted', '=', 0)
            ->whereRaw('store_vou_balance < store_vou_qnt');
           

        $store_stocking_details_to_inv = StockingDetails::where('store_stocking_hd_id', '=', $header->store_stocking_hd_id)
            ->where('isdeleted', '=', 0)
            ->whereRaw('store_vou_balance > store_vou_qnt');

        if($store_stocking_details_to_enterance->count() == 0 && $store_stocking_details_to_inv->count() == 0 )
        {
            return \Response::json(['success' => false, 'msg' => 'جميع الاصناف مطابقة لايمكن اكمال التسوية']);
        }

        if($store_stocking_details_to_enterance->count() > 0 )
        {
            $enterance_item_id_list  = $store_stocking_details_to_enterance->pluck('store_vou_item_id');
            $stocking_enterance_item = StockingController::stockingItem($header,$store_stocking_details_to_enterance,'ER');
            //return $stocking_enterance_item ;
            if(!$stocking_enterance_item['success'])
            {
                return \Response::json(['success' => false, 'msg' => $stocking_enterance_item['msg']]);
            }
            $store_vou_ref_1 = $stocking_enterance_item['store_hd_id'];

        }

        if($store_stocking_details_to_inv->count() > 0 )
        {
            $item_id_list  = $store_stocking_details_to_inv->pluck('store_vou_item_id');
            $stocking_sales_item = StockingController::stockingItem($header,$store_stocking_details_to_inv,'INV');
           
            if(!$stocking_sales_item['success'])
            {
                return \Response::json(['success' => false, 'msg' => $stocking_sales_item['msg']]);
            }
            $store_vou_ref_2 = $stocking_sales_item['store_hd_id'];
            
        }

        $header->store_vou_status = 'COMPLETE';
        $header->updated_user =  auth()->user()->user_id;
        //enterance id
        $header->store_vou_ref_1 = $store_vou_ref_1;
        //sales inv id
        $header->store_vou_ref_2 = $store_vou_ref_2;
        $header_save = $header->save();

        if(!$header_save)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاخ' ]);


    }
    
    public function stockingItem($header,$stockingDetails,$type)
    {
        $branch_id = $header->branch_id;
        $company_group_id = $header->company_group_id;
        $company_id = $header->company_id;
        $store_category_type = $header->store_category_type;

        if($type == 'ER')
        {
            $qty_field = 'store_vou_qnt_i';
            $vou_type = SystemCode::where('system_code', '=', '62003')->first();
            $current_serial = CompanyMenuSerial::where('company_id',  $company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 64);
            if (!$current_serial->count()) {
                return ['success' => false, 'msg' => 'لايمكن تحديد رقم اذن الاستلام يرجي التواصل مع مدير النظام'];
            }
            $current_serial = $current_serial->first();
            $new_serial = 'ER-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);

        }
        else{

            $qty_field = 'store_vou_qnt_o';
            $vou_type = SystemCode::where('system_code', '=', '62006')->first();
            $current_serial = CompanyMenuSerial::where('company_id',  $company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 65);
            if (!$current_serial->count()) {
                return ['success' => false, 'msg' => 'لايمكن تحديد رقم المبيعات يرجي التواصل مع مدير النظام'];
            }
            $current_serial = $current_serial->first();
            $new_serial = 'S-INV-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
            
        }

        $store_acc = Customer::where('company_group_id', '=', $header->company_group_id)->where('customer_category', '=', 0);
        if($store_acc->count() == 0)
        {
            return \Response::json(['success' => false, 'msg' => 'الرجاء التواصل مع مدير النظام لاضافة الحساب']);
        }
        $store_acc = $store_acc->first();
        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company_group_id;
        $purchase->company_id = $company_id;
        $purchase->branch_id = $branch_id;
        $purchase->store_category_type = $store_category_type;
        $purchase->store_vou_type = $vou_type->system_code_id;
        $purchase->store_hd_code = $new_serial;

        $purchase->store_acc_no = $store_acc->customer_id;
        $purchase->store_acc_name = $store_acc->customer_name_full_ar;
        $purchase->store_acc_tax_no = $store_acc->customer_vat_no;
        //$purchase->store_vou_pay_type = $store_vou_ref_before->store_vou_pay_type;
        $purchase->store_vou_notes = 'تسوية جرد';
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;
        $purchase_save = $purchase->save();

        if (!$purchase_save) {
            ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
        }

        $current_serial->update(['serial_last_no' => $new_serial]);

        //store item
        $item_list_id = $stockingDetails->pluck('store_vou_item_id');
        $item_list = $stockingDetails;
        $purcahse_details = new PurchaseDetails();
        if ($item_list->count() > 0) 
        {

            $item_data_set = [];

            $vat_rate = 0.15;
            

            foreach ($item_list->get() as $i => $d) 
            {

                $item = StoreItem::where('item_id', '=', $d->store_vou_item_id)->first();
                $store_vou_qnt = abs ($d->store_vou_qnt - $d->store_vou_balance);
                $store_vou_item_total_price = $store_vou_qnt * $item->item_price_sales;
                $store_vou_vat_amount = $vat_rate *$store_vou_item_total_price;
                $store_vou_price_net = $store_vou_vat_amount + $store_vou_item_total_price;


                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $purchase->store_hd_id,
                    'company_group_id' => $company_group_id,
                    'company_id' => $company_id,
                    'branch_id' => $branch_id,

                    'store_category_type' => $store_category_type,
                    'store_vou_type' => $vou_type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $store_acc->customer_id,
                    //'store_acc_name' => $store_acc->customer_name_full_ar,
                    //'store_acc_tax_no' => $store_acc->customer_vat_no,
                    'store_vou_item_id' => $item->item_id,
                    $qty_field => $store_vou_qnt,
                    'store_vou_loc' => $item->store_vou_loc,
                    'store_vou_item_price_cost' => floatval($item->item_price_cost),
                    'store_vou_item_price_sales' => floatval($item->item_price_sales),
                    'store_vou_item_price_unit' => floatval($item->item_price_sales),
                    'store_vou_item_total_price' => floatval($store_vou_item_total_price),

                    //'store_vou_disc_type' => $d['store_vou_disc_type'],
                    //'store_voue_disc_value' => floatval($d['store_voue_disc_value']),
                    //'store_vou_disc_amount' => floatval($d['store_vou_disc_amount']),

                    'store_vou_vat_rate' => $vat_rate,
                    'store_vou_vat_amount' => $store_vou_vat_amount,
                    'store_vou_price_net' => $store_vou_price_net,

                ];


                //update item details when type equle enter Receipt
                if ($type == 'INV') {
                    $store_item = StoreItem::where('item_id',  $d->store_vou_item_id)->first();
                   
                    if ($store_item->item_balance < $d[$qty_field]) {
                        return ['success' => false, 'msg' => 'الكمية الحالية غير كافية'];
                    }
                    $store_item->item_balance = $store_item->item_balance - $store_vou_qnt;
                    $store_item->last_price_sales = $store_item->item_price_sales;
                  //  $store_item->item_price_sales = (floatval($d['store_vou_item_price_unit'])) / 2;
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
                    }
                } 

                elseif ($type == 'ER') {
                    $store_item = StoreItem::where('item_id',  $d->store_vou_item_id)->first();
                    $brofet_rate = $store_item->itemCategory->system_code_tax_perc;

                    $store_item->item_balance = $store_item->item_balance + $store_vou_qnt;
                    $store_item->old_price_cost = $store_item->item_price_cost;
                    if ($store_item->item_price_cost == 0) {
                        $store_item->item_price_cost = floatval($d['store_vou_item_price_unit']);
                    } else {
                        $store_item->item_price_cost = ($store_item->item_price_cost + floatval($d['store_vou_item_price_unit'])) / 2;
                    }

                    $store_item->old_price_sales = $store_item->item_price_sales;
                    $store_item->item_price_sales = $store_item->item_price_cost + ($store_item->item_price_cost * $brofet_rate);
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
                    }
                }
                else{
                    return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
                }
            }

            $purcahse_details_save = $purcahse_details->insert($item_data_set);

            if (!$purcahse_details_save) {
                return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
            }

            if($type == 'INV')
            {
                $update_total = StoreSalesController::updateHeaderTotal($purchase);
            }
            elseif($type == 'ER'){
                $update_total = PurchaseController::updateHeaderTotal($purchase);
            }

            if (!$update_total['success']) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }

            \DB::commit();
            return ['success' => true, 'store_hd_id' => $purchase->store_hd_id];
        }
       
         
    }
}
