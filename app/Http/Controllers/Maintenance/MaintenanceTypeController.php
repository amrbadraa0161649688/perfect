<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\MaintenanceType;
use App\Models\SystemCode;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Lang;

class MaintenanceTypeController extends Controller
{
    //
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $maintenance_card = MaintenanceType::where('company_id', request()->company_id)->get();
        if ($request->ajax()) {
            return Datatables::of($maintenance_card)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return (string)view('Maintenance.MaintenanceType.Actions.actions', compact('row'));
                })
                ->addColumn('company', function ($row) {
                    if (\Lang::getLocale() == 'ar') {
                        return $row->company->company_name_ar  ; 
                    } else {
                        return $row->company->company_name_en;
                    }
                })
                ->addColumn('mntns_card_type', function ($row) {
                  
                    return optional($row->card)->getSysCodeName() ; 
                    
                        
                })
                ->addColumn('mntns_type_name', function ($row) {
                    if (\Lang::getLocale() == 'ar') {
                        return $row->mntns_type_name_ar;
                    } else {
                        return $row->mntns_type_name_en;
                    }
                })
                ->addColumn('type_cat', function ($row) {
                    if (\Lang::getLocale() == 'ar') {
                        return $row->typeCat->system_code_name_ar;
                    } else {
                        return $row->typeCat->system_code_name_en;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('Maintenance.MaintenanceType.index', compact('companies'));

    }

    public function create(Request $request)
    {
        
        $company = session('company') ? session('company') : auth()->user()->company;
        $type_cat = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',45)->get();
        $card_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',48)->get();
           
        return view('Maintenance.MaintenanceType.create', compact('type_cat','card_list'));
    }

    public function store(Request $request)
    {
        //return str_replace(':', '.', $request->mntns_type_hours) ;
        $company = session('company') ? session('company') : auth()->user()->company;
        \DB::beginTransaction();
        $maintenance_type = new MaintenanceType();
        $maintenance_type->uuid = \DB::raw('NEWID()');
        $maintenance_type->company_id= $company->company_id;
        $maintenance_type->company_group_id = $company->company_group_id;
        $maintenance_type->mntns_type_category =  SystemCode::where('system_code','=', $request->mntns_type)->first()->system_code_id ; 
        $maintenance_type->mntns_card_type = SystemCode::where('system_code','=', $request->mntns_card_type)->first()->system_code_id ;
        $maintenance_type->mntns_type_code = $request->mntns_type_code;
        $maintenance_type->mntns_type_name_ar = $request->mntns_type_name_ar;
        $maintenance_type->mntns_type_name_en = $request->mntns_type_name_en;

        $maintenance_type->mntns_type_hours = str_replace(':', '.', $request->mntns_type_hours) ;
        $maintenance_type->mntns_type_emp_no = $request->mntns_type_emp_no;
        $maintenance_type->mntns_type_value = $request->mntns_type_value;

        $maintenance_type->created_user = auth()->user()->id;

        $maintenance_type_save = $maintenance_type->save();
        if(!$maintenance_type_save)
        {
            return redirect()->route('maintenance-type.create')->with(['warning' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return redirect()->route('maintenance-type.index')->with(['success' => 'تم اضافة نوع الاصلاح بنجاح']);;
    }

    public function edit(Request $request , $uuid)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $maintenance_type = MaintenanceType::where('company_id', $company->company_id)->where('uuid','=',$uuid)->first();
        $type_cat = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',45)->get();
        $card_list = SystemCode::where('company_id', $company->company_id)->where('sys_category_id','=',48)->get();
           
        return view('Maintenance.MaintenanceType.edit', compact('card_list','type_cat','maintenance_type'));
    }

    public function update(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $maintenance_type = MaintenanceType::where('uuid','=',$request->uuid)->first();
        $maintenance_type->company_id= $company->company_id;
        $maintenance_type->company_group_id = $company->company_group_id;
        $maintenance_type->mntns_type_category = SystemCode::where('system_code','=', $request->mntns_type)->first()->system_code_id ;
        $maintenance_type->mntns_card_type =  SystemCode::where('system_code','=', $request->mntns_card_type)->first()->system_code_id ;
        $maintenance_type->mntns_type_code = $request->mntns_type_code;
        $maintenance_type->mntns_type_name_ar = $request->mntns_type_name_ar;
        $maintenance_type->mntns_type_name_en = $request->mntns_type_name_en;

        $maintenance_type->mntns_type_hours = str_replace(':', '.', $request->mntns_type_hours) ;
        $maintenance_type->mntns_type_emp_no = $request->mntns_type_emp_no;
        $maintenance_type->mntns_type_value = $request->mntns_type_value;

        $maintenance_type_save = $maintenance_type->save();
        if(!$maintenance_type_save)
        {
            return redirect()->route('maintenance-type.create')->with(['warning' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return redirect()->route('maintenance-type.index')->with(['success' => 'تم تعديل نوع الاصلاح بنجاح']);
    }
}
