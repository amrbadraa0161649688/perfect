<?php

namespace App\Http\Controllers\SalesCar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Branch;
use App\Models\SalesCar;
use App\Models\CarRentBrand;
use App\Models\CarRentBrandDt; 
use App\Models\SalesDetails;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    //
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = [ 'company' => $company ,'branch'=> session('branch') ];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        $car_brand_list = CarRentBrand::where('company_id', $company->company_id)->get();
        $car_brand_dt_list = CarRentBrandDt::where('company_id', $company->company_id)->get();
        $car_status_list = SystemCode::where('sys_category_id', '=', '120')->get();
      
        return view('salesCar.car.index', compact('companies','branch_list','warehouses_type_lits','car_brand_list','car_brand_dt_list','car_status_list','user_data'));
    }

    public function data(Request $request)
    {
        //return request()->search['warehouses_type'];
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        
        $view = view('salesCar.car.data', compact('company','companies','branch_list','warehouses_type_lits'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function dataTable(Request $request,$companyId)
    {
        $car = SalesCar::where('company_id', $companyId)->where('isdeleted','=',0);

        if($request->search['warehouses_type'])
        {
            $car = $car->where('store_category_type','=',$request->search['warehouses_type']);
        }
        if($request->search['branch_id'])
        {
            $car = $car->where('branch_id','=',$request->search['branch_id']); 
        }
        if($request->search['sales_cars_brand_id'])
        {
            $car = $car->where('sales_cars_brand_id','=',$request->search['sales_cars_brand_id']);
        }
        if($request->search['sales_cars_brand_dt_id'])
        {
            $car = $car->where('sales_cars_brand_dt_id','=',$request->search['sales_cars_brand_dt_id']);
        }
        if($request->search['sales_car_status'])
        {
            $car = $car->where('sales_car_status','=',$request->search['sales_car_status']);
        }
        
        $car = $car->get();

        return Datatables::of($car)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return (string)view('salesCar.car.Actions.actions', compact('row'));
            })
            ->addColumn('branch', function ($row) {
                return $row->Branch->getBranchName();
            })
            ->addColumn('brand', function ($row) {
                return $row->brand->getName();
            })
            ->addColumn('brand_dt', function ($row) {
                return $row->brandDT->getBrandName();
            })
            ->addColumn('sales_cars_status', function ($row) {
                return $row->status->getSysCodeName();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Request $request,$uuid)
    {

        $car = SalesCar::where('uuid','=',$uuid)->first();
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        $warehouses_type_lits  = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',55)->get();
        $car_brand_list = CarRentBrand::where('company_id', $company->company_id)->get();
        $car_brand_dt_list = CarRentBrandDt::where('company_id', $company->company_id)->get();
        $car_status_list = SystemCode::where('sys_category_id', '=', '120')->get();

        $car_history = SalesDetails::where('store_vou_item_id','=',$car->sales_cars_id)->where('isdeleted','=',0)->orderBy('store_vou_date');


        return view('salesCar.car.show',compact('car','company','companies','branch_list','warehouses_type_lits','car_brand_list','car_brand_dt_list','car_status_list','car_history'));
        

    }

    public function getCarbyBrand(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $car_ready_status = SystemCode::where('system_code', '=', '120001')->first()->system_code_id;
        $car_list = SalesCar::where('sales_cars_brand_dt_id','=',$request->brand_dt)
            ->where('branch_id','=',$request->branch_id)
            ->where('sales_car_status','=',$car_ready_status)->get();
           
        return response()->json(['status' => 200, 'data' => $car_list]);

    } 

}
