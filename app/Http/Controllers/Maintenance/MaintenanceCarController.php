<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Trucks;
use App\Models\MaintenanceCar;
use App\Models\SystemCode;
use Yajra\DataTables\Facades\DataTables;
use Lang;

class MaintenanceCarController extends Controller
{
    //
    public function index(Request $request)
    {
        //return request()->company_id;
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        return view('Maintenance.MaintenanceCar.index', compact('companies','company'));

    }

    public function data(Request $request)
    {
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $trucks = Trucks::where('company_id', $company->company_id)->get();
        $view = view('Maintenance.MaintenanceCar.data',compact('company','companies','trucks'));

        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function dataTable(Request $request,$companyId)
    {
        
        $maintenance_car = MaintenanceCar::where('company_id', $companyId)->get();
        
            return Datatables::of($maintenance_car)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return (string)view('Maintenance.MaintenanceCar.Actions.actions', compact('row'));
                })
                ->addColumn('customer', function ($row) {
                    if (\Lang::getLocale() == 'ar') {
                        return $row->customer->customer_name_full_ar;
                    } else {
                        return $row->customer->customer_name_full_en;
                    }
                })
                ->addColumn('brand', function ($row) {
                    if (\Lang::getLocale() == 'ar') {
                        return  $row->brand->system_code_name_ar;
                    } else {
                        return  $row->brand->system_code_name_en;
                    }
                })
                ->addColumn('truckname', function ($row) {
                    if (\Lang::getLocale() == 'ar') {
                        return optional($row->truckname)->truck_id;
                    } else {
                        return optional($row->truckname)->truck_id;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function create(Request $request)
    {
       
        $company = session('company') ? session('company') : auth()->user()->company;
        $brand = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',32)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->get();
        return view('Maintenance.MaintenanceCar.create',compact('brand','customer'));
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        \DB::beginTransaction();
        $maintenance_car = new MaintenanceCar();
        $maintenance_car->uuid = \DB::raw('NEWID()');
        $maintenance_car->company_id= $company->company_id;
        $maintenance_car->company_group_id = $company->company_group_id;
        $maintenance_car->customer_id = $request->customer_id;
        $maintenance_car->mntns_cars_brand_id = $request->mntns_cars_brand_id;
        
        $maintenance_car->mntns_cars_plate_no =   $request->mntns_cars_plate_no;
        $maintenance_car->mntns_cars_chasie_no = $request->mntns_cars_chasie_no;
        $maintenance_car->mntns_cars_type = $request->mntns_cars_type;
        $maintenance_car->mntns_cars_model = $request->mntns_cars_model;
        $maintenance_car->mntns_cars_color = $request->mntns_cars_color;
        $maintenance_car->mntns_cars_meter = $request->mntns_cars_meter;
        $maintenance_car->mntns_cars_owner = $request->mntns_cars_owner;
        $maintenance_car->mntns_cars_driver = $request->mntns_cars_driver;
        $maintenance_car->mntns_cars_mobile_no = $request->mntns_cars_mobile_no;
        $maintenance_car->mntns_cars_address = $request->mntns_cars_address;
        $maintenance_car->mntns_cars_vat_no = $request->mntns_cars_vat_no;

        $maintenance_car->created_user = auth()->user()->user_id;

        $maintenance_car_save = $maintenance_car->save();
        if(!$maintenance_car_save)
        {
            return redirect()->route('maintenance-car.create')->with(['warning' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return redirect()->route('maintenance-car.index')->with(['success' => 'تم اضافة المركبة بنجاح']);;

    }

    public function edit(Request $request,$uuid)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $brand = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',32)->get();
        $customer = Customer::where('company_group_id', $company->company_group_id)->get();
        $maintenance_car = MaintenanceCar::where('uuid', $uuid)->first();
        $trucks = Trucks::where('company_id', $company->company_id)->get();
        return view('Maintenance.MaintenanceCar.edit',compact('brand','customer','maintenance_car','trucks'));
    }

    public function update(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        \DB::beginTransaction();
        $maintenance_car = MaintenanceCar::where('uuid','=',$request->uuid)->first();
        
        $maintenance_car->company_id= $company->company_id;
        $maintenance_car->company_group_id = $company->company_group_id;
        $maintenance_car->customer_id = $request->customer_id;
        $maintenance_car->mntns_cars_brand_id = $request->mntns_cars_brand_id;
        $maintenance_car->car_cost_center = $request->car_cost_center;
        $maintenance_car->mntns_cars_plate_no =   $request->mntns_cars_plate_no;
        $maintenance_car->mntns_cars_chasie_no = $request->mntns_cars_chasie_no;
        $maintenance_car->mntns_cars_type = $request->mntns_cars_type;
        $maintenance_car->mntns_cars_model = $request->mntns_cars_model;
        $maintenance_car->mntns_cars_color = $request->mntns_cars_color;
        $maintenance_car->mntns_cars_meter = $request->mntns_cars_meter;
        $maintenance_car->mntns_cars_owner = $request->mntns_cars_owner;
        $maintenance_car->mntns_cars_driver = $request->mntns_cars_driver;
        $maintenance_car->mntns_cars_mobile_no = $request->mntns_cars_mobile_no;
        $maintenance_car->mntns_cars_address = $request->mntns_cars_address;
        $maintenance_car->mntns_cars_vat_no = $request->mntns_cars_vat_no;
        $maintenance_car_save = $maintenance_car->save();

        if(!$maintenance_car_save)
        {
            return redirect()->route('maintenance-car.edit')->with(['warning' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return redirect()->route('maintenance-car.index')->with(['success' => 'تم تعديل المركبة بنجاح']);

    }

    
}
