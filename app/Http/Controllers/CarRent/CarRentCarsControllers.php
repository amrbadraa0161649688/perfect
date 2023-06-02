<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Resources\EmployeeResource;

use App\Http\Middleware\UsersApp\Add;

use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use App\Models\Employee;
use App\Models\Trucks;
use App\Models\CarRentModel;

use App\Models\User;
use App\Models\UserBranch;
use App\Models\Customer;
use App\Models\Attachment;
use App\Models\CarRentCars;
use App\Models\Note;
use App\Models\CarRentBrand;
use App\Models\CarRentBrandDt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;

use Yajra\DataTables\Facades\DataTables;


class CarRentCarsControllers extends Controller
{
    //

    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $brand_dts = CarRentBrandDt::where('company_group_id', $company->company_group_id)->get();
        $brands = CarRentBrand::where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 123)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_statuses = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();

        $car_maintenance_status = SystemCode::whereIn('system_code', [123009, 1230010, 1230011])->where('company_group_id', $company->company_group_id)->pluck('system_code_id');
        $car_ready_status = SystemCode::whereIn('system_code', [123001])->where('company_group_id', $company->company_group_id)->pluck('system_code_id');
        $car_depose_status = SystemCode::whereIn('system_code', [123002])->where('company_group_id', $company->company_group_id)->pluck('system_code_id');
        $car_rent_status = SystemCode::whereIn('system_code', [123003])->where('company_group_id', $company->company_group_id)->pluck('system_code_id');
        $car_moving_status = SystemCode::whereIn('system_code', [123004, 123008])->where('company_group_id', $company->company_group_id)->pluck('system_code_id');
        $car_other_status = SystemCode::whereIn('system_code', [123004, 123008, 123003, 123002, 123001, 123009, 1230010, 1230011])->where('company_group_id', $company->company_group_id)->pluck('system_code_id');

        $cars = CarRentCars::where('company_group_id', $company->company_group_id);
        $car_maintenance_count = CarRentCars::where('company_group_id', $company->company_group_id);
        $car_ready_count = CarRentCars::where('company_group_id', $company->company_group_id);
        $car_depose_count = CarRentCars::where('company_group_id', $company->company_group_id);
        $car_rent_count = CarRentCars::where('company_group_id', $company->company_group_id);
        $car_moving_count = CarRentCars::where('company_group_id', $company->company_group_id);
        $car_other_count = CarRentCars::where('company_group_id', $company->company_group_id);

        if (request()->company_id) {
            $cars->whereIn('company_id', request()->company_id);
            $brand_dts = CarRentBrandDt::whereIn('company_id', request()->company_id)->get();

            $car_maintenance_count->whereIn('company_id', request()->company_id);
            $car_ready_count->whereIn('company_id', request()->company_id);
            $car_depose_count->whereIn('company_id', request()->company_id);
            $car_rent_count->whereIn('company_id', request()->company_id);
            $car_moving_count->whereIn('company_id', request()->company_id);
            $car_other_count->whereIn('company_id', request()->company_id);
            if (request()->branch_id) {
                $cars->whereIn('branch_id', request()->branch_id);

                $car_maintenance_count->whereIn('branch_id', request()->branch_id);
                $car_ready_count->whereIn('branch_id', request()->branch_id);
                $car_depose_count->whereIn('branch_id', request()->branch_id);
                $car_rent_count->whereIn('branch_id', request()->branch_id);
                $car_moving_count->whereIn('branch_id', request()->branch_id);
                $car_other_count->whereIn('branch_id', request()->branch_id);
            }
            if (request()->brand_id) {
                $cars->whereIn('car_brand_id', request()->brand_id);

                $car_maintenance_count->whereIn('car_brand_id', request()->brand_id);
                $car_ready_count->whereIn('car_brand_id', request()->brand_id);
                $car_depose_count->whereIn('car_brand_id', request()->brand_id);
                $car_rent_count->whereIn('car_brand_id', request()->brand_id);
                $car_moving_count->whereIn('car_brand_id', request()->brand_id);
                $car_other_count->whereIn('car_brand_id', request()->brand_id);
            }
            if (request()->brand_dt) {
                // return request()->brand_dt;
                $cars->whereIn('car_brand_dt_id', request()->brand_dt);

                $car_maintenance_count->whereIn('car_brand_dt_id', request()->brand_dt);
                $car_ready_count->whereIn('car_brand_dt_id', request()->brand_dt);
                $car_depose_count->whereIn('car_brand_dt_id', request()->brand_dt);
                $car_rent_count->whereIn('car_brand_dt_id', request()->brand_dt);
                $car_moving_count->whereIn('car_brand_dt_id', request()->brand_dt);
                $car_other_count->whereIn('car_brand_dt_id', request()->brand_dt);
            }

            if (request()->car_trucker_status) {
                $cars->whereIn('car_trucker_status', request()->car_trucker_status);

                $car_maintenance_count->whereIn('car_trucker_status', request()->car_trucker_status);
                $car_ready_count->whereIn('car_trucker_status', request()->car_trucker_status);
                $car_depose_count->whereIn('car_trucker_status', request()->car_trucker_status);
                $car_rent_count->whereIn('car_trucker_status', request()->car_trucker_status);
                $car_moving_count->whereIn('car_trucker_status', request()->car_trucker_status);
                $car_other_count->whereIn('car_trucker_status', request()->car_trucker_status);
            }

            if (request()->car_chase) {
                $cars->where('car_chase', 'like', '%' . request()->car_chase . '%');

                $car_maintenance_count->where('car_chase', 'like', '%' . request()->car_chase . '%');
                $car_ready_count->where('car_chase', 'like', '%' . request()->car_chase . '%');
                $car_depose_count->where('car_chase', 'like', '%' . request()->car_chase . '%');
                $car_rent_count->where('car_chase', 'like', '%' . request()->car_chase . '%');
                $car_moving_count->where('car_chase', 'like', '%' . request()->car_chase . '%');
                $car_other_count->where('car_chase', 'like', '%' . request()->car_chase . '%');
            }
            if (request()->car_motor_no) {
                $cars->where('car_motor_no', 'like', '%' . request()->car_motor_no . '%');

                $car_maintenance_count->where('car_motor_no', 'like', '%' . request()->car_motor_no . '%');
                $car_ready_count->where('car_motor_no', 'like', '%' . request()->car_motor_no . '%');
                $car_depose_count->where('car_motor_no', 'like', '%' . request()->car_motor_no . '%');
                $car_rent_count->where('car_motor_no', 'like', '%' . request()->car_motor_no . '%');
                $car_moving_count->where('car_motor_no', 'like', '%' . request()->car_motor_no . '%');
                $car_other_count->where('car_motor_no', 'like', '%' . request()->car_motor_no . '%');
            }
            if (request()->car_category_id) {
                $cars->whereIn('car_category_id', request()->car_category_id);

                $car_maintenance_count->whereIn('car_category_id', request()->car_category_id);
                $car_ready_count->whereIn('car_category_id', request()->car_category_id);
                $car_depose_count->whereIn('car_category_id', request()->car_category_id);
                $car_rent_count->whereIn('car_category_id', request()->car_category_id);
                $car_moving_count->whereIn('car_category_id', request()->car_category_id);
                $car_other_count->whereIn('car_category_id', request()->car_category_id);
            }
            if (request()->car_status_id) {
                $cars->whereIn('car_status_id', request()->car_status_id);

                $car_maintenance_count->whereIn('car_status_id', request()->car_status_id);
                $car_ready_count->whereIn('car_status_id', request()->car_status_id);
                $car_depose_count->whereIn('car_status_id', request()->car_status_id);
                $car_rent_count->whereIn('car_status_id', request()->car_status_id);
                $car_moving_count->whereIn('car_status_id', request()->car_status_id);
                $car_other_count->whereIn('car_status_id', request()->car_status_id);
            }

            if (request()->full_car_plate) {
                $cars->where('full_car_plate', 'like', '%' . request()->full_car_plate . '%');

                $car_maintenance_count->where('full_car_plate', 'like', '%' . request()->full_car_plate . '%');
                $car_ready_count->where('full_car_plate', 'like', '%' . request()->full_car_plate . '%');
                $car_depose_count->where('full_car_plate', 'like', '%' . request()->full_car_plate . '%');
                $car_rent_count->where('full_car_plate', 'like', '%' . request()->full_car_plate . '%');
                $car_moving_count->where('full_car_plate', 'like', '%' . request()->full_car_plate . '%');
                $car_other_count->where('full_car_plate', 'like', '%' . request()->full_car_plate . '%');
            }

            if (request()->car_model_year) {
                $cars->where('car_model_year', request()->car_model_year);

                $car_maintenance_count->where('car_model_year', request()->car_model_year);
                $car_ready_count->where('car_model_year', request()->car_model_year);
                $car_depose_count->where('car_model_year', request()->car_model_year);
                $car_rent_count->where('car_model_year', request()->car_model_year);
                $car_moving_count->where('car_model_year', request()->car_model_year);
                $car_other_count->where('car_model_year', request()->car_model_year);
            }
        } else {
            $cars->where('branch_id', $branch->branch_id);
        }
        $cars = $cars->paginate(EnumSetting::Paginate);
        // cards
        $car_maintenance_count = $car_maintenance_count->whereIn('car_status_id', $car_maintenance_status)->count();
        $car_ready_count = $car_ready_count->whereIn('car_status_id', $car_ready_status)->count();
        $car_depose_count = $car_depose_count->whereIn('car_status_id', $car_depose_status)->count();
        $car_rent_count = $car_rent_count->whereIn('car_status_id', $car_rent_status)->count();
        $car_moving_count = $car_moving_count->whereIn('car_status_id', $car_moving_status)->count();
        $car_other_count = $car_other_count->whereNotIn('car_status_id', $car_other_status)->count();
        return view('CarRent.Cars.index', compact('companies', 'cars', 'brand_dts', 'sys_codes_status', 'branches'
            , 'brands', 'sys_codes_tracker_statuses', 'car_maintenance_count', 'car_ready_count', 'car_depose_count', 'car_rent_count'
            , 'car_moving_count', 'car_other_count', 'car_maintenance_status', 'car_ready_status', 'car_depose_status', 'car_rent_status'
            , 'car_moving_status', 'car_other_status','company'));
    }


    public function store(Request $request)
    {

        $car_rent_model = CarRentModel::find($request->car_rent_model_id);
        $cars = $car_rent_model->carRentCars;

        //  return $request->all();


        $array_uniq = array_unique($request->car_plate_number);
        if (count($array_uniq) != count($request->car_plate_number)) {
            return back()->with(['error' => 'يوجد رقم لوحه مكرر']);
        }

        foreach ($cars as $k => $car) {
            $car->update([
                'car_chase' => $request->car_chase[$k],
                'car_motor_no' => $request->car_motor_no[$k],
                'car_operation_card_no' => $request->car_operation_card_no[$k],
                'plate_ar_1' => $request->plate_ar_1[$k],
                'plate_ar_2' => $request->plate_ar_2[$k],
                'plate_ar_3' => $request->plate_ar_3[$k],
                'plate_en_1' => $request->plate_en_1[$k],
                'plate_en_2' => $request->plate_en_2[$k],
                'plate_en_3' => $request->plate_en_3[$k],
                'car_plate_number' => $request->car_plate_number[$k],
                'complete' => 1,

                'insurance_type' => $request->insurance_type[$k],
                'insurance_document_no' => $request->insurance_document_no[$k],
                'insurance_value' => $request->insurance_value[$k],
                'insurance_date_end' => $request->insurance_date_end[$k],
                'car_trucker_status' => $request->car_trucker_status[$k],
                'tracker_serial' => $request->tracker_serial[$k],
                'car_color' => $request->car_color[$k],
                'allowedKmPerHour' => $request->allowedKmPerHour[$k],
                'platetype' => $request->platetype[$k],

            ]);
        }

        return redirect()->route('CarRentCars');


    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $sys_codes_type = SystemCode::where('sys_category_id', 123)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_ownership_status = SystemCode::where('sys_category_id', 31)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_status = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();
        $sys_platetype_statuses = SystemCode::where('sys_category_id', 147)->where('company_group_id', $company->company_group_id)->get();


        // $sys_codes_status = SystemCode::where('sys_category_id', 67)->where('company_group_id', $company->company_group_id)->get();
        //$suppliers = Customer::where('company_group_id', $company->company_group_id)->get();
        // $sys_codes_status_68 = SystemCode::where('sys_category_id', 68)->where('company_group_id', $company->company_group_id)->get();
        // $sys_codes_status_69 = SystemCode::where('sys_category_id', 69)->where('company_group_id', $company->company_group_id)->get();
        // $sys_codes_status_70 = SystemCode::where('sys_category_id', 70)->where('company_group_id', $company->company_group_id)->get();
        // $sys_codes_status_71 = SystemCode::where('sys_category_id', 71)->where('company_group_id', $company->company_group_id)->get();
        // $sys_codes_manufactuer = SystemCode::where('sys_category_id', 32)->where('company_group_id', $company->company_group_id)->get();


        $sys_codes_registration_types = SystemCode::where('sys_category_id', 86)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_insurance_types = SystemCode::where('sys_category_id', 87)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Safety_Triangles = SystemCode::where('sys_category_id', 74)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_ac_statuses = SystemCode::where('sys_category_id', 75)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Radio_statuses = SystemCode::where('sys_category_id', 76)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Fire_extinguishers = SystemCode::where('sys_category_id', 77)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Screen_statuses = SystemCode::where('sys_category_id', 78)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Speedometer_statuses = SystemCode::where('sys_category_id', 79)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Seats_statuses = SystemCode::where('sys_category_id', 80)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Spare_Tire_tools = SystemCode::where('sys_category_id', 81)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Tires_statuses = SystemCode::where('sys_category_id', 83)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_Spare_Tire_statuses = SystemCode::where('sys_category_id', 83)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_First_Aid_Kits = SystemCode::where('sys_category_id', 84)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_car_keys_statuses = SystemCode::where('sys_category_id', 85)->where('company_group_id', $company->company_group_id)->get();

        $car_rent_brands = CarRentBrand::get();
        $car_rent_brands_dt = CarRentBrandDt::get();
        $car = CarRentCars::find($id);

        return view('CarRent.Cars.edit', compact('car', 'companies',
            'sys_codes_type', 'sys_codes_ownership_status', 'sys_codes_tracker_status'
            , 'car_rent_brands', 'car_rent_brands_dt', 'sys_codes_registration_types', 'sys_codes_insurance_types'
            , 'sys_codes_Safety_Triangles', 'sys_codes_ac_statuses', 'sys_codes_Radio_statuses', 'sys_codes_Fire_extinguishers'
            , 'sys_codes_Screen_statuses', 'sys_codes_Speedometer_statuses', 'sys_codes_Seats_statuses'
            , 'sys_codes_Spare_Tire_tools', 'sys_codes_Tires_statuses', 'sys_codes_Spare_Tire_statuses'
            , 'sys_codes_First_Aid_Kits', 'sys_codes_car_keys_statuses','sys_platetype_statuses'));
    }

    public function update(Request $request, $id)
    {
        $car = CarRentCars::find($id);

        $car->update([
            'plate_ar_1' => $request->plate_ar_1,
            'plate_ar_2' => $request->plate_ar_2,
            'plate_ar_3' => $request->plate_ar_3,
            'plate_en_1' => $request->plate_en_1,
            'plate_en_2' => $request->plate_en_2,
            'plate_en_3' => $request->plate_en_3,
            'car_plate_number' => $request->car_plate_number,
//            'full_car_plate' =>$request->plate_ar_1 . ' ' . $request->plate_ar_2 . ' ' .
//                $request->plate_ar_3 . ' ' . $request->car_plate_number,
            'car_motor_no' => $request->car_motor_no,
            'car_registration_type' => $request->car_registration_type,
            'car_operation_card_no' => $request->car_operation_card_no,
            'car_operation_card_date' => $request->car_operation_card_date,
            'car_status_id' => $request->car_status_id,
            'car_ownership_status' => $request->car_ownership_status,
            'car_purchase_cost' => $request->car_purchase_cost,
            'owner_name' => $request->owner_name,
            'insurance_type' => $request->insurance_type,
            'insurance_document_no' => $request->insurance_document_no,
            'insurance_value' => $request->insurance_value,
            'insurance_date_end' => $request->insurance_date_end,
            'car_trucker_status' => $request->car_trucker_status,
            'tracker_serial' => $request->tracker_serial,
            'tracker_supplier' => $request->tracker_supplier,
            'tracker_install_date' => $request->tracker_install_date,

            'allowedKmPerHour' => $request->allowedKmPerHour,
            'oil_change_km' => $request->oil_change_km,
            'availableFuel' => $request->availableFuel,
            'odometer_start' => $request->odometer_start,
            'last_oil_change_date' => $request->last_oil_change_date,
            'car_Safety_Triangle' => $request->car_Safety_Triangle,
            'car_ac_status' => $request->car_ac_status,
            'car_Radio_Stereo_status' => $request->car_Radio_Stereo_status,
            'car_Fire_extinguisher' => $request->car_Fire_extinguisher,
            'car_Screen_status' => $request->car_Screen_status,
            'car_Speedometer_status' => $request->car_Speedometer_status,
            'car_Seats_status' => $request->car_Seats_status,
            'car_Spare_Tire_tools' => $request->car_Spare_Tire_tools,
            'car_Tires_status' => $request->car_Tires_status,
            'car_Spare_Tire_status' => $request->car_Spare_Tire_status,
            'car_First_Aid_Kit' => $request->car_First_Aid_Kit,
            'car_keys_status' => $request->car_keys_status,
            'car_color' => $request->car_color,
            'updated_user' => auth()->user()->user_id,
            'platetype' => $request->platetype,
        ]);
//        return $car;

        return redirect()->route('CarRentCars')->with(['success' => 'تم التعديل']);
    }



//public function getPhoto($photo)
//{
    //  $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
    //$photo->move(public_path("Employees"), $name);
    //return $name;
//}


}
