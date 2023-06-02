<?php

namespace App\Http\Controllers\salesCar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\SalesDetails;
use App\Models\SalesCar;
use App\Models\CarRentBrandDt;
use App\Models\CarRentBrand;
use App\Models\CompanyMenuSerial;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\InvoiceQR\InvoiceDateElement;
 
class SalesCarTransController extends Controller
{
     //
    public function index(Request $request, $page)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = [ 'company' => $company ,'branch'=> session('branch') ];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();

        info('index' );
        switch ($page) {
            case 'trans':
                $view = 'salesCar.trans.trans.index';
                break;

            default: 
                abort (404);
        }
    
        return view($view, compact('companies','branch_lits','page','warehouses_type_lits','user_data'));
    }
 
    public function data(Request $request,$page)
    {
    
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_list  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        $payemnt_method_list  = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id','=',57)->get();
        $vendor_list  = Customer::where('company_group_id','=',$company->company_group_id)->where('customer_category','=',1)->get();
        $item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',51)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->where('customer_category','=',2)->get();
        $car_brand = CarRentBrand::where('company_id', $company->company_id)->get();

        switch ($page) {
            case 'trans':
                $view = 'salesCar.trans.trans.data';
                break;

            default: 
                abort (404);
        }
        
        $view = view($view, compact('company','companies','branch_list','warehouses_type_list','payemnt_method_list','vendor_list','item_disc_type','car_brand','customer'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }
 
 
    public function dataTable(Request $request,$companyId,$page)
    {
       
        switch ($page) {
            case 'trans':
                $vou_type = SystemCode::where('system_code','=', '104008')->first()->system_code_id;
                $action_view = 'salesCar.trans.trans.Actions.actions';
                break;

            default: 
                abort (404);
        }

        $sales = Sales::where('company_id', $companyId)->where('store_vou_type','=',$vou_type)->orderBy('created_date','desc')->get();
        if($request->search['warehouses_type'])
        {
            $sales = $sales->where('store_category_type','=',$request->search['warehouses_type']);
        }
        if($request->search['branch_id'])
        {
            $sales = $sales->where('branch_id','=',$request->search['branch_id']); 
        }
        
        return Datatables::of($sales)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($action_view){
                return (string) view($action_view, compact('row'));
            })
            ->addColumn('store_vou_ref_1', function ($row) {
                return $row->sourceBranch->getBranchName();
            })
            ->addColumn('store_vou_ref_2', function ($row) {
                return $row->sourceStore->getSysCodeName();
            })
            ->addColumn('store_vou_ref_3', function ($row) {
                return  $row->destBranch->getBranchName();
            })
            ->addColumn('store_vou_ref_4', function ($row) {
                return $row->destStore->getSysCodeName();
            })
            ->addColumn('store_vou_date',function($row){
                return $row->created_date->format('Y-m-d H:m');
            })
            ->rawColumns(['action'])
            ->make(true);
    }
 
    public function store(Request $request,$page)
    {
        switch ($page) {
            case 'trans':
                $vou_type = SystemCode::where('system_code','=', '104008')->first();
                return SalesCarTransController::storeHeader($request,$vou_type);
                break;

            case 'direct_trans_form_req':
                $vou_type = SystemCode::where('system_code','=', '104008')->first();
                return SalesCarTransController::storeAll($request,$vou_type);
                break;

            default: 
                abort (404);
        }
    }
 
    public function storeHeader(Request $request,$type)
    {
        info('test');
        $rules = [
        
            'source_branch' => 'required', 
            'source_store'  => 'required',
            'dest_branch' => 'required|different:source_branch', 
            'dest_store'  => 'required|same:source_store',
        
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }

        $branch =  session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        //gnerate mntns cards no
        $current_serial =  CompanyMenuSerial::where('company_id',$company->company_id)->where('branch_id','=', $branch->branch_id)->where('app_menu_id', 82);
        if(!$current_serial->count())
        {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن التحويل يرجي التواصل مع مدير النظام' ]);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'C-TP-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-' )+1) + 1);
        $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id;

        \DB::beginTransaction();
        $sales = new Sales();

        $sales->uuid = \DB::raw('NEWID()');
        
        $sales->company_group_id = $company->company_group_id ;
        $sales->company_id = $company->company_id ;
        $sales->branch_id = $branch->branch_id ;

        $sales->store_category_type = SystemCode::where('system_code','=', $request->source_store)->first()->system_code_id;
        $sales->store_vou_type = $type->system_code_id;

        $sales->store_hd_code = $new_serial ;
        $sales->store_acc_no = $request->source_branch ;

        $sales->store_vou_ref_1 = $request->source_branch ;
        $sales->store_vou_ref_2 = SystemCode::where('system_code','=', $request->source_store)->first()->system_code_id;

        $sales->store_vou_ref_3 = $request->dest_branch ;
        $sales->store_vou_ref_4 = SystemCode::where('system_code','=', $request->dest_store)->first()->system_code_id;
        $sales->store_vou_status = $store_vou_status;

        $sales->store_vou_date = Carbon::now();
        $sales->created_user = auth()->user()->user_id ;

        $sales_save = $sales->save() ;
        if(!$sales_save)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        $current_serial->update(['serial_last_no' => $new_serial])  ;
        

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' , 'uuid' => $sales->refresh()->uuid ]);


    }

    public function edit(Request $request,$uuid,$page)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $sales = Sales::where('uuid', $uuid)->first();
        $car_brand = CarRentBrand::where('company_id', $company->company_id)->get();
        // $source_itemes = Storeitem::where('company_id', $company->company_id)
        //     ->where('branch_id','=',$sales->store_vou_ref_1)->where('item_category','=',$sales->store_vou_ref_2)
        //     ->where('item_balance','>',0)->get();

        switch ($page) {
            case 'trans':
                $vou_type = SystemCode::where('system_code','=', '104008')->first();
                $view = 'salesCar.trans.trans.edit_trans';
                break;

            default: 
                abort (404);
        }
        
        return view($view , compact('company','companies','sales','car_brand'));
    }
 
    public function storeItem(Request $request,$page)
    {
        switch ($page) {

            case 'trans':
                $header  =  Sales::where('uuid','=',$request->sales_uuid)->first();
                return SalesCarTransController::storeItemData($request,$header);
                break;

            default: 
                abort (404);

        }
    }
 
    public function storeItemData(Request $request,$header)
    {
        
        $rules = [
            'sales_uuid' => 'required', 
            'item_table_data' => 'required', 
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }

        

        $branch =  session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;

        $sales_details = new SalesDetails();

        $item_data = json_decode($request->item_table_data, true);
        \DB::beginTransaction();
        $is_added_befor = SalesDetails::where('store_hd_id',$header->store_hd_id)->where('store_vou_item_id','=',$item_data['store_vou_item_id'])->where('isdeleted','=',0);
        if($is_added_befor->count() > 0)
        {
            return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا الصنف مسبقا..!' ]);
        }

        $sales_details->uuid = \DB::raw('NEWID()');
        $sales_details->store_hd_id = $header->store_hd_id;
        $sales_details->company_group_id = $header->company_group_id ;
        $sales_details->company_id = $header->company_id ;
        $sales_details->branch_id = $header->branch_id ;

        $sales_details->store_category_type = $header->store_category_type  ;
        $sales_details->store_vou_type = $header->store_vou_type;
        $sales_details->store_vou_date = Carbon::now();
        $sales_details->created_user = auth()->user()->user_id ;
        $sales_details->store_acc_no = $header->store_acc_no ;

        $sales_details->store_brand_dt_id = $item_data['store_brand_dt_id'];
        $sales_details->store_brand_id = $item_data['store_brand_id'];

        $sales_details->store_vou_item_id =  $item_data['store_vou_item_id'];
        $sales_details->store_vou_qnt_t_o =  $item_data['store_vou_qnt_t_o'];

        $sales_details->store_vou_item_price_unit =  $item_data['store_vou_item_price_unit'];
        $sales_details->store_vou_item_total_price =  $item_data['store_vou_item_total_price'];
        $sales_details->store_vou_vat_rate =  $item_data['store_vou_vat_rate'];
        $sales_details->store_vou_vat_amount =  $item_data['store_vou_vat_amount'];
        $sales_details->store_vou_price_net =  $item_data['store_vou_price_net'];
        $sales_details_save = $sales_details->save();

        if(!$sales_details_save)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        $update_total = SalesCarController::updateHeaderTotal($sales_details->sales);

        if(!$update_total['success'])
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        $sales_car = SalesCar::where('sales_cars_id','=',$item_data['store_vou_item_id'])
            ->update([
                'sales_car_status' => SystemCode::where('system_code', '=', '120003')->first()->system_code_id,
                'sales_cars_add_amount' => $item_data['sales_cars_add_amount']
            ]);

        $total = [
            'total_sum' => $sales_details->sales->itemSumTotal() ,
            'total_sum_vat' => $sales_details->sales->itemSumVat() ,
            'total_sum_net' => $sales_details->sales->itemSumNet(),
        ];


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' , 'uuid' => $sales_details->refresh()->uuid ,'total' => $total  ]);

    }
 
 
    public function deleteItem(Request $request )
    {
        info($request->uuid);;
        $rules = [
            'uuid' => 'required|exists:sales_cars_dt,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }
    
        $sales_details = SalesDetails::where('uuid','=',$request->uuid)->first();
        $sales_details->isdeleted = 1;
        $sales_details_save = $sales_details->save();

        if(!$sales_details_save)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        $update_total = SalesCarController::updateHeaderTotal($sales_details->sales);

        if(!$update_total['success'])
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        $total = [
            'total_sum' => $sales_details->sales->itemSumTotal() ,
            'total_sum_vat' => $sales_details->sales->itemSumVat() ,
            'total_sum_net' => $sales_details->sales->itemSumNet(),
        ];
        
        return \Response::json(['success' => true, 'msg' => 'تمت الحذف بنجاح' ,'data'=> $sales_details , 'total' => $total ]);

    }
 
    public function storeAll(Request $request,$type)
    {
        $rules = [
            'store_vou_ref_before' => 'required|exists:sales_cars_hd,uuid', 
            'item_data' => 'required', 
            'source_branch' => 'required',
            'from_req_dest_branch' => 'required|different:source_branch', 
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }

        $store_vou_ref_before = Sales::where('uuid','=',$request->store_vou_ref_before)->first();
        $branch_id =  $store_vou_ref_before->branch_id;
        $company = $store_vou_ref_before->company;
        //gnerate code 
        switch ($type->system_code) {
            case '104008':
                $qty_field = 'store_vou_qnt_t_o';
                $current_serial =  CompanyMenuSerial::where('company_id',$company->company_id)->where('branch_id','=', $branch_id)->where('app_menu_id', 82);
                if(!$current_serial->count())
                {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم اذن التحويل يرجي التواصل مع مدير النظام' ]);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'C-TP-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-' )+1) + 1);
                $store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125001')->first()->system_code_id;
                $store_vou_ref_before_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '125002')->first()->system_code_id;
                break;
            default: 
                abort (404);
        }

        \DB::beginTransaction();
        $sales = new Sales();

        $sales->uuid = \DB::raw('NEWID()');
        
        $sales->company_group_id = $company->company_group_id ;
        $sales->company_id = $company->company_id ;
        $sales->branch_id = $branch_id;

        $sales->store_category_type =  $store_vou_ref_before->store_category_type   ;
        info($type->system_code_id);
        
        $sales->store_vou_type = $type->system_code_id;

        $sales->store_hd_code = $new_serial ;
        $sales->store_acc_no = $store_vou_ref_before->store_acc_no ;
        $sales->store_acc_name = $store_vou_ref_before->store_acc_name ;
        $sales->store_acc_tax_no = $store_vou_ref_before->store_acc_tax_no ;
        $sales->store_vou_pay_type = $store_vou_ref_before->store_vou_pay_type; 
        $sales->store_vou_notes = $store_vou_ref_before->store_vou_notes ;
        $sales->store_vou_status = $store_vou_status;
        $sales->store_vou_ref_before = $store_vou_ref_before->store_hd_code;
        $sales->store_vou_date = Carbon::now();
        $sales->created_user = auth()->user()->user_id ;

        $sales->store_vou_ref_1 = $store_vou_ref_before->branch_id ;
        $sales->store_vou_ref_2 = $store_vou_ref_before->store_category_type;

        $sales->store_vou_ref_3 = $request->from_req_dest_branch ;
        $sales->store_vou_ref_4 = $store_vou_ref_before->store_category_type;

        $sales_save = $sales->save() ;

        if(!$sales_save)
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        $current_serial->update(['serial_last_no' => $new_serial])  ;

        //store item 

        $item_data = json_decode($request->item_data, true);
        $sales_details = new SalesDetails();
        if(count($item_data)>0)
        {
            
            $item_data_set = [];
            
            foreach ($item_data as  $i => $d) 
            {
                $item = SalesDetails::where('uuid','=',$d['uuid'])->first();
                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $sales->store_hd_id,
                    'company_group_id' => $store_vou_ref_before->company_group_id ,
                    'company_id' => $store_vou_ref_before->company_id ,
                    'branch_id' => $store_vou_ref_before->branch_id ,

                    'store_category_type' => $store_vou_ref_before->store_category_type  ,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id ,
                    'store_acc_no' => $store_vou_ref_before->store_acc_no ,

                    'store_vou_item_id' =>  $item->store_vou_item_id,
                    $qty_field =>  $d[$qty_field],
                    
                    'store_brand_dt_id' => $d['store_brand_dt_id'],
                    'store_brand_id' => $d['store_brand_id'],
                    
                    'store_vou_item_price_cost' => floatval($item->store_vou_item_price_cost),
                    'store_vou_item_price_unit' =>  floatval($d['store_vou_item_price_unit']),
                    'store_vou_item_total_price' =>  floatval($d['store_vou_item_total_price']),

                    'store_vou_disc_type' => $d['store_vou_disc_type'],
                    'store_voue_disc_value' => floatval($d['store_voue_disc_value']),
                    'store_vou_disc_amount' => floatval($d['store_vou_disc_amount']),

                    'store_vou_vat_rate' =>  floatval($d['store_vou_vat_rate']),
                    'store_vou_vat_amount' => floatval($d['store_vou_vat_amount']),
                    'store_vou_price_net' =>  floatval($d['store_vou_price_net']),

                ];

                $item->store_vou_qnt_t_i_r = $item->store_vou_qnt_t_i_r + $d[$qty_field];
                $item_save = $item->save();

                if(!$item_save)
                {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
                }

                //check in befor request need to close
                $update_status = SalesCarController::checkStatus($store_vou_ref_before);
                if($update_status->getData()->success)
                {
                    $store_vou_ref_before->store_vou_status = $store_vou_ref_before_status;
                    $store_vou_ref_before_save = $store_vou_ref_before->save();

                    if (!$store_vou_ref_before_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                }

            }

            $sales_details_save = $sales_details->insert($item_data_set);

            if(!$sales_details_save)
            {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
            }

        }

        $update_total = SalesCarController::updateHeaderTotal($sales);

        if(!$update_total['success'])
        {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' , 'uuid' => $sales->refresh()->uuid ]);

    }
}
