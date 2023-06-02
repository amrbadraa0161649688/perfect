<?php

namespace App\Http\Controllers\Qserv;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Qserv\QservAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Branch;
use App\Models\SystemCode;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\PriceListHd;
use App\Models\PriceListDt;
use App\Models\Customer;
use App\Models\FuelTransaction;
use App\Models\StationInvoiceQR;
class FuelStationController extends Controller
{
    //

    public function getBranchByCompany(Request $request)
    {
        $branchies = Branch::where('company_id', '=', $request->company_id)->get();
        return response()->json(['status' => 200, 'data' => $branchies]);
    }

    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = ['company' => $company, 'branch' => session('branch')];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $view = 'FuelStation.dashboard';

        return view($view, compact('companies', 'branch_list', 'user_data'));
    }

    public function data(Request $request)
    {
        
        $branch = Branch::where('branch_id', $request->search['branch_id'])->with('FTrans')->first();
        $stat_date = Carbon::createFromFormat('Y-m-d', $request->search['start_date'])->format('Y-m-d');
        $end_date =  Carbon::createFromFormat('Y-m-d', $request->search['end_date'])->format('Y-m-d');
           
        $company = $branch->company;
        $benzine_91 = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '70001')->first();
        $benzine_95 = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '70002')->first();
        $diesel = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '70003')->first();
        $pric_list_hd =  PriceListHd::where('branch_id','=',$branch->branch_id)->where('price_list_category','=','fuel');

        $fuel_sales = 
        [
            
            'by_amount' => [
                '91' => $branch->FTrans()->getTotalAmount([1], [1,2], $stat_date, $end_date),
                '95' => $branch->FTrans()->getTotalAmount([2], [1,2], $stat_date, $end_date),
                'diesel' => $branch->FTrans()->getTotalAmount([3], [1,2], $stat_date, $end_date),
            ],
            'by_volum' => [
                '91' => $branch->FTrans()->getTotalVolume([1], [1,2], $stat_date, $end_date),
                '95' => $branch->FTrans()->getTotalVolume([2], [1,2], $stat_date, $end_date),
                'diesel' => $branch->FTrans()->getTotalVolume([3], [1,2], $stat_date, $end_date)
            ],
            'by_payment' =>[
                '91' => [
                    'cash' => $branch->FTrans()->getTotalAmount([1], [1], $stat_date, $end_date),
                    'mada' => $branch->FTrans()->getTotalAmount([1], [2], $stat_date, $end_date)
                ],

                '95' => [
                    'cash' => $branch->FTrans()->getTotalAmount([2], [1], $stat_date, $end_date),
                    'mada' => $branch->FTrans()->getTotalAmount([2], [2], $stat_date, $end_date)
                ],

                'diesel' => [
                    'cash' => $branch->FTrans()->getTotalAmount([3], [1], $stat_date, $end_date),
                    'mada' => $branch->FTrans()->getTotalAmount([3], [2], $stat_date, $end_date)
                ],
            ]
        ];

        $fuel_sales_by_emp = $branch->FTrans()->getTotalByEmp([1,2,3], [1,2], $stat_date, $end_date);
        $fuel_sales_by_nozzle = $branch->FTrans()->getTotalByNozzle([1,2,3], [1,2], $stat_date, $end_date);

        
        if($pric_list_hd->count() == 0)
        {
            $benzine_91_data = ['price' => null , 'fees' => 'Not Define'];
            $benzine_95_data = ['price' => null , 'fees' => 'Not Define'];
            $diesel_data = ['price' => null , 'fees' => 'Not Define'];
        }
        else
        {
            $pric_list_hd =  $pric_list_hd->first();
            $benzine_91_price =  $pric_list_hd->pricelistDetails()->where('item_id',$benzine_91->system_code_id)->where('isdeleted',0)->orderBy('price_list_dt_id', 'DESC')->first();
            $benzine_95_price =  $pric_list_hd->pricelistDetails()->where('item_id',$benzine_95->system_code_id)->where('isdeleted',0)->orderBy('price_list_dt_id', 'DESC')->first();
            $diesel_price =  $pric_list_hd->pricelistDetails()->where('item_id',$diesel->system_code_id)->where('isdeleted',0)->orderBy('price_list_dt_id', 'DESC')->first();
            
            $benzine_91_price_fees = (optional($benzine_91_price)->max_fees != null ? number_format(optional($benzine_91_price)->max_fees,2) : 'Not Define')  ;
            $benzine_95_price_fees = (optional($benzine_95_price)->max_fees != null ? number_format(optional($benzine_95_price)->max_fees,2): 'Not Define');
            $diesel_price_fees = (optional($diesel_price)->max_fees != null ? number_format(optional($diesel_price)->max_fees,2) : 'Not Define');

            $benzine_91_data = ['price' => $benzine_91_price , 'fees' => $benzine_91_price_fees , 'id' => $benzine_91_price->price_list_dt_id];
            $benzine_95_data = ['price' => $benzine_95_price , 'fees' => $benzine_95_price_fees, 'id' => $benzine_95_price->price_list_dt_id];
            $diesel_data = ['price' => $diesel_price , 'fees' => $diesel_price_fees , 'id' => $diesel_price->price_list_dt_id];
        }

       
        $tanks_data = QservAPIController::GetTanks($branch->station_id);
        

        $tank_list = 
        [
            [ 'fuel_type' => $benzine_91->system_code_filter , 'name' => $benzine_91->getSysCodeName() , 'chart_data' => null],
            [ 'fuel_type' => $benzine_95->system_code_filter , 'name' => $benzine_95->getSysCodeName(), 'chart_data' => null], 
            [ 'fuel_type' => $diesel->system_code_filter , 'name' => $diesel->getSysCodeName(), 'chart_data' => null]
        ];

        $tanked_data_arr = [];
        if($tanks_data['statusCode'] == 200)
        {
            foreach ($tank_list as $key=>$tl)
            {
                foreach( $tanks_data['body'] as  $d)
                {
                    if($tl['fuel_type'] == $d->fuelType)
                    {
                        $raw =  [ 
                            'stationId' => $d->stationId , 
                            'number' => $d->number ,
                            'capacity' => $d->capacity , 
                            'maxLimit' => $d->maxLimit,
                            'minLimit' => $d->minLimit , 
                            'currentLevel' => $d->currentLevel ,
                            'currentVolume' => $d->currentVolume,
                            'id' =>  $d->id,
                            'name' => $tl['name'],
                            'fuel_type' => $tl['fuel_type'],
                            'chart_name' => 'chart_'.$tl['fuel_type'].'_'.$d->number.'_gauge',
                        ]; 
                        array_push($tanked_data_arr, $raw);
                    }
                }
            }
        }

        $view = 'FuelStation.data';

        $view = view($view, compact('branch','benzine_91','benzine_91_data','benzine_95','benzine_95_data','diesel','diesel_data','tank_list','fuel_sales','tanked_data_arr','fuel_sales_by_emp','fuel_sales_by_nozzle'));

        return \Response::json(['view' => $view->render(), 'success' => true]);

    }

    public function dataTable(Request $request)
    {
        $branch =  Branch::where('branch_id', $request->search['branch_id'])->first();
        
        // $transaction = QservAPIController::GetTransactions($request->search['start_date'],$request->search['end_date'],$branch->station_id);
        // if($transaction['statusCode'] != 200)
        // {
        //     return \Response::json(['success' => false, 'msg' => 'لاتتوفر اي بيانات ' ]);
        // }
        $trans = StationInvoiceQR::whereDate('trans_date', '>=', $request->search['start_date'])
            ->whereDate('trans_date', '<=', $request->search['end_date'])
            ->where('station_id',$branch->station_id)->get();
        $action_view = 'true';
        return Datatables::of($trans)
            ->addIndexColumn()
            ->addColumn('transactionId', function ($row) {
                return ($row->transaction_id);

            })
            ->addColumn('fuelType', function ($row) {
                return  optional($row->fuelType)->getSysCodeName();
            })
            ->addColumn('paymentMethod', function ($row) {
                return  optional($row->paymentMethod)->getSysCodeName();
            })
            ->addColumn('price', function ($row) {
                return $row->price;
            })
            ->addColumn('nozzleId', function ($row) {

                return ($row->nozzle_id);

            })
            ->addColumn('amount', function ($row) {
                return $row->amount;
            })
            ->addColumn('volume', function ($row) {
                return($row->volume);
            })
            ->addColumn('employeeId', function ($row) {
                return $row->employee_id ;
            })
            ->addColumn('transactionDate', function ($row) {
                return $row->trans_date;
            })
            ->addColumn('action', function ($row) use ($action_view) {
                return 'action';
            })
            ->rawColumns(['action'])
            ->make(true);
        
    }

    public function editPrice(Request $request)
    {
        $branch = Branch::where('station_id', $request->station_id)->first();
        $company = $branch->company;
        $fuel_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', $request->fuel_type)->first();
        $view = view('FuelStation.action.edit_price',compact('company','branch','fuel_type'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function updatePrice(Request $request)
    {
        $rules = [
            'station_id_m' => 'required|exists:branches,branch_id',
            'price_m' => 'required',
            'fuel_type_m' => 'required',
        ];
       
        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }

        $branch = Branch::where('branch_id', $request->station_id_m)->first();
        $company = $branch->company;
        $fuel_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', $request->fuel_type_m)->first();
        
        $pric_list_hd =  PriceListHd::where('branch_id','=',$branch->branch_id)->where('price_list_category','=','fuel');
        $price = $request->price_m;

        if($pric_list_hd->count() == 0)
        {
            return \Response::json([ 'success' => false , 'msg' => 'لايوجد ملف مسجل للفرع' ]);
        }

        $pric_list_hd =  $pric_list_hd->first();
        $customers = Customer::where('company_group_id', $company->company_group_id)->where('customer_category', 2)->first();
        \DB::beginTransaction();
        PriceListDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'price_list_id' => $pric_list_hd->price_list_id,
            'item_id' => $fuel_type->system_code_id,
            'customer_id' => $customers->customer_id,
            'max_fees' => $price,
            'min_fees' => $price,
            'cost_fees' => 0,
            'distance_time' => 0,
            'distance_fees' => 0,
            'distance' => 0,
            'loc_from' => $branch->branch_id,
            'loc_to' => $branch->branch_id,
            'created_user' => auth()->user()->user_id
        ]);

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح']);

    }

    public function confirmPrice(Request $request)
    {
        $rules = [
            'price_list_dt_id_m' => 'required|exists:price_list_dt,price_list_dt_id',
            'status_m' => 'required',
        ];
       
        $validator = Validator::make($request->all(), $rules);
         
        if ($validator->fails())
        {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
        }
        $pric_list_dt =  PriceListDt::where('price_list_dt_id',$request->price_list_dt_id_m)->first();
        $branch = $pric_list_dt->priceListHd;
        $fuel_type = $pric_list_dt->item;

        if($request->status_m == 'APPROVED')
        {

            $update_api = QservAPIController::UpdatePrice($branch->station_id,$pric_list_dt->price,$fuel_type->system_code_filter);

            if($update_api['statusCode'] != 200)
            {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة']);
            }
        }

        \DB::beginTransaction();

        $pric_list_dt->status = ($request->status_m == 'APPROVED' ? 'APPROVED' : 'REJECTED');
        $pric_list_dt->isdeleted = ($request->status_m == 'APPROVED' ? 0 : 1);
        $pric_list_dt->approved_by = auth()->user()->user_id;
        $pric_list_dt->approved_date = Carbon::now();

        $pric_list_dt_update = $pric_list_dt->save();

        if (!$pric_list_dt_update) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();

        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح']);

    }


    public function priceHistory(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = ['company' => $company, 'branch' => session('branch')];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get();
        $view = 'FuelStation.price_history';

        return view($view, compact('companies', 'branch_list', 'user_data'));
    }

    public function priceHistoryData(Request $request)
    {
        
        $branch = Branch::where('branch_id', $request->search['branch_id'])->first();
        $stat_date = Carbon::createFromFormat('m/d/Y', $request->search['start_date'])->format('Y-m-d');
        $end_date =  Carbon::createFromFormat('m/d/Y', $request->search['end_date'])->format('Y-m-d');
           
        $company = $branch->company;
        
        $benzine_91 = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '70001')->first();
        $benzine_95 = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '70002')->first();
        $diesel = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', '70003')->first();
        $pric_list_hd =  PriceListHd::where('branch_id','=',$branch->branch_id)->where('price_list_category','=','fuel');

        if($pric_list_hd->count() == 0)
        {
            $benzine_91_data = ['price' => null , 'fees' => 'Not Define'];
            $benzine_95_data = ['price' => null , 'fees' => 'Not Define'];
            $diesel_data = ['price' => null , 'fees' => 'Not Define'];

            $benzine_91_price =  null;
            $benzine_95_price =  null;
            $diesel_price =  null;

            $price_history = [
                '91' => PriceListDt::where('price_list_dt_id',0)->get(),
                '95' => PriceListDt::where('price_list_dt_id',0)->get(),
                'disel' => PriceListDt::where('price_list_dt_id',0)->get(),
            ];
        }
        else
        {
            $pric_list_hd =  $pric_list_hd->first();
            $benzine_91_price =  $pric_list_hd->pricelistDetails()->where('item_id',$benzine_91->system_code_id)->where('isdeleted',0)->orderBy('price_list_dt_id', 'DESC')->first();
            $benzine_95_price =  $pric_list_hd->pricelistDetails()->where('item_id',$benzine_95->system_code_id)->where('isdeleted',0)->orderBy('price_list_dt_id', 'DESC')->first();
            $diesel_price =  $pric_list_hd->pricelistDetails()->where('item_id',$diesel->system_code_id)->where('isdeleted',0)->orderBy('price_list_dt_id', 'DESC')->first();
            
            $benzine_91_price_fees = (optional($benzine_91_price)->max_fees != null ? number_format(optional($benzine_91_price)->max_fees,2) : 'Not Define')  ;
            $benzine_95_price_fees = (optional($benzine_95_price)->max_fees != null ? number_format(optional($benzine_95_price)->max_fees,2): 'Not Define');
            $diesel_price_fees = (optional($diesel_price)->max_fees != null ? number_format(optional($diesel_price)->max_fees,2) : 'Not Define');

            $benzine_91_data = ['price' => $benzine_91_price , 'fees' => $benzine_91_price_fees , 'id' => $benzine_91_price->price_list_dt_id];
            $benzine_95_data = ['price' => $benzine_95_price , 'fees' => $benzine_95_price_fees, 'id' => $benzine_95_price->price_list_dt_id];
            $diesel_data = ['price' => $diesel_price , 'fees' => $diesel_price_fees , 'id' => $diesel_price->price_list_dt_id];
            $price_history = [
                '91' => $pric_list_hd->pricelistDetails()
                    ->whereDate('created_at', '>=', $stat_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->where('item_id',$benzine_91->system_code_id)
                    ->orderBy('price_list_dt_id', 'DESC')->get(),

                '95' => $pric_list_hd->pricelistDetails()
                    ->whereDate('created_at', '>=', $stat_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->where('item_id',$benzine_95->system_code_id)
                    ->orderBy('price_list_dt_id', 'DESC')->get(),

                'disel' => $pric_list_hd->pricelistDetails()
                    ->whereDate('created_at', '>=', $stat_date)
                    ->whereDate('created_at', '<=', $end_date)
                    ->where('item_id',$diesel->system_code_id)
                    ->orderBy('price_list_dt_id', 'DESC')->get(),
            ];
            
        }

        

        

        //return $diesel_price;
        $view = 'FuelStation.price_history_data';

        $view = view($view, compact( 'branch','benzine_91','benzine_91_data','benzine_95','benzine_95_data','diesel','diesel_data', 'price_history'));

        return \Response::json(['view' => $view->render(), 'success' => true]);

    }

    public function saveTransaction()
    {

        $tran = FuelTransaction::get()->pluck('r_transaction_Id');

        return  $tran;
    

    }
}
