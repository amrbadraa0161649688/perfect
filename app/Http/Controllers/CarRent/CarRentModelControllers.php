<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;

use App\Models\CarRentCars;
use App\Models\CompanyMenuSerial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\CarRentModel;
use App\Models\Customer;
use App\Models\CarRentBrand;
use App\Models\CarRentBrandDt;
use Illuminate\Support\Facades\DB;

class CarRentModelControllers extends Controller
{
    //
    public function index()
    {

        // بالنوع  و الموديل و السنه و رقم الدفعه//
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $brand_hds = CarRentBrand::where('company_group_id', $company->company_group_id)->get();
        $brand_dts = CarRentBrandDt::where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 67)->where('company_group_id', $company->company_group_id)->get();

        $cars_model = CarRentModel::where('company_group_id', $company->company_group_id);
        if (request()->company_id) {
            $cars_model->whereIn('company_id', request()->company_id);
            $brand_dts = CarRentBrandDt::whereIn('company_id', request()->company_id)->get();

            if (request()->input('car_rent_model_code')) {
                $cars_model->where('car_rent_model_code', request()->input('car_rent_model_code'));
            }

            if (request()->brand_hd) {
                $cars_model->whereIn('car_brand_id', request()->brand_hd);
            }
            if (request()->brand_dt) {
                $cars_model->whereIn('car_brand_dt_id', request()->brand_dt);
            }
            if (request()->car_purchase_date_from) {
                $cars_model->whereDate('car_purchase_date', '>=', request()->car_purchase_date_from);
            }
            if (request()->car_purchase_date_to) {
                $cars_model->whereDate('car_purchase_date', '<=', request()->car_purchase_date_to);
            }

            if (request()->car_rent_model_code) {
                $cars_model->where('car_rent_model_code', 'like', '%' . request()->car_rent_model_code . '%');
            }

            if (request()->car_model_year) {
                $cars_model->where('car_model_year', request()->car_model_year);
            }
        }
        $cars_model = $cars_model->paginate(EnumSetting::Paginate);
        return view('CarRent.CarsModel.index', compact('cars_model', 'companies', 'brand_dts',
            'sys_codes_status', 'brand_hds'));
    }

    public function create(Request $request)
    {
        session()->remove('redirect_path');
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;

        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5)->get();


        $sys_codes_type = SystemCode::where('sys_category_id', 90)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 67)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_ownership_status = SystemCode::where('sys_category_id', 31)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_status = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();
        $sys_platetype_status = SystemCode::where('sys_category_id', 147)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_status_68 = SystemCode::where('sys_category_id', 68)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status_69 = SystemCode::where('sys_category_id', 69)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status_70 = SystemCode::where('sys_category_id', 70)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status_71 = SystemCode::where('sys_category_id', 71)->where('company_group_id', $company->company_group_id)->get();

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


        $sys_codes_manufactuer = SystemCode::where('sys_category_id', 32)->where('company_group_id', $company->company_group_id)->get();

        $car_rent_brands = CarRentBrand::where('company_id', $company->company_id)->get();
        $car_rent_brands_dt = CarRentBrandDt::where('company_id', $company->company_id)->get();


        $last_car_model_serial = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 100)->latest()->first();

        if (isset($last_car_model_serial)) {
            $last_car_model_serial_no = $last_car_model_serial->serial_last_no;
            $array_number = explode('-', $last_car_model_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_car_model_serial->update(['serial_last_no' => $string_number]);

        } else {
            $string_number = 'B-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 100,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        return view('CarRent.CarsModel.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_location',
            'sys_codes_ownership_status', 'sys_codes_manufactuer', 'sys_codes_ownership_status', 'sys_codes_tracker_status', 'companies', 'suppliers',
            'sys_codes_status_68', 'sys_codes_status_69', 'sys_codes_status_70', 'sys_codes_status_71',
            'car_rent_brands', 'car_rent_brands_dt', 'string_number', 'sys_codes_registration_types', 'sys_codes_insurance_types'
            , 'sys_codes_Safety_Triangles', 'sys_codes_ac_statuses', 'sys_codes_Radio_statuses', 'sys_codes_Fire_extinguishers'
            , 'sys_codes_Screen_statuses', 'sys_codes_Speedometer_statuses', 'sys_codes_Seats_statuses'
            , 'sys_codes_Spare_Tire_tools', 'sys_codes_Tires_statuses', 'sys_codes_Spare_Tire_statuses'
            , 'sys_codes_First_Aid_Kits', 'sys_codes_car_keys_statuses', 'sys_platetype_status'));

    }


    public function store(Request $request)
    {
//         return $request->all();
        $request->validate([
            'last_oil_change_date' => 'required|date',
            'car_photo_url' => 'image|mimes:jpeg,png,jpg,gif',
        ]);


        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_code_status = SystemCode::where('system_code', 123001)->
        where('company_group_id', $company->company_group_id)->first();

        DB::beginTransaction();

        $car_photo_url = $this->getPhoto($request->car_photo_url);
        $car_model = CarRentModel::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'car_rent_model_code' => $request->car_rent_model_code,
            'car_purchase_date' => $request->car_purchase_date,
            'car_qty' => $request->car_qty,
            'car_rent_model_status' => $request->car_rent_model_status,
            'car_brand_id' => $request->car_brand_id,
            'car_brand_dt_id' => $request->car_brand_dt_id,
            'car_model_year' => $request->car_model_year,
            'car_category_id' => $request->car_category_id,
            'Property_type' => $request->Property_type,
            'car_purchase_cost' => $request->car_purchase_cost,
            'owner_name' => $request->owner_name,
            'car_photo_url' => 'Cars/' . $car_photo_url,
            'gear_box_type_id' => $request->gear_box_type_id,
            'engine_type' => $request->engine_type,
            'fuel_type_id' => $request->fuel_type_id,
            'car_color' => $request->car_color,
            'oil_type' => $request->oil_type,
            'oil_change_km' => $request->oil_change_km,
            'car_doors' => $request->car_doors,
            'car_passengers' => $request->car_passengers,
            'created_user' => auth()->user()->user_id,
        ]);

        $i = 1;
        for ($i; $i <= $request->car_qty; $i++) {
            CarRentCars::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'branch_id' => session('branch')['branch_id'],
                'car_rent_model_id' => $car_model->car_rent_model_id,
                'car_status_id' => $sys_code_status->system_code_id,
                'car_brand_id' => $request->car_brand_id,
                'car_brand_dt_id' => $request->car_brand_dt_id,
                'car_model_year' => $car_model->car_model_year,
                'car_category_id' => $car_model->car_category_id,
                'car_color' => $car_model->car_color,
                'car_weight' => $car_model->car_weight,
                'gear_box_type_id' => $car_model->gear_box_type_id,
                'engine_type' => $car_model->engine_type,
                'fuel_type_id' => $car_model->fuel_type_id,
                'car_doors' => $car_model->car_doors,
                'car_passengers' => $car_model->car_passengers,
                'car_desc' => $car_model->car_desc,
                'car_photo_url' => 'Cars/' . $car_photo_url,
                'oil_type' => $request->oil_type,
                'oil_change_km' => $request->oil_change_km,
                'car_purchase_date' => $request->car_purchase_date,
                'car_purchase_cost' => $request->car_purchase_cost,
                'owner_name' => $request->owner_name,
//                'car_ownership_status' => $request->car_ownership_status,
                'Property_type' => $request->Property_type,
                'created_user' => auth()->user()->user_id,
                'allowedKmPerHour' => $request->allowedKmPerHour,
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

                'insurance_type' => $request->insurance_type,
                'insurance_document_no' => $request->insurance_document_no,
                'insurance_value' => $request->insurance_value,
                'insurance_date_end' => $request->insurance_date_end,
                'car_trucker_status' => $request->car_trucker_status,
                'tracker_serial' => $request->tracker_serial,
            ]);
        }

        $counter = $request->car_qty;

        DB::commit();

        return redirect()->route('CarRentModel.create2', [$counter, $car_model->car_rent_model_id]);


    }

    public function create2($counter, $car_model_id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $car_model = CarRentModel::find($car_model_id);
        $sys_codes_insurance_types = SystemCode::where('sys_category_id', 87)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_status = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();
        return view('CarRent.CarsModel.create-2', compact('counter', 'car_model', 'sys_codes_insurance_types', 'sys_codes_tracker_status'));
    }


    public function edit($id)

    {
        $car_model = CarRentModel::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5)->get();


        $sys_codes_type = SystemCode::where('sys_category_id', 90)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 67)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_ownership_status = SystemCode::where('sys_category_id', 31)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_status = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_status_68 = SystemCode::where('sys_category_id', 68)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status_69 = SystemCode::where('sys_category_id', 69)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status_70 = SystemCode::where('sys_category_id', 70)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status_71 = SystemCode::where('sys_category_id', 71)->where('company_group_id', $company->company_group_id)->get();


        $sys_codes_manufactuer = SystemCode::where('sys_category_id', 32)->where('company_group_id', $company->company_group_id)->get();

        $car_rent_brands = CarRentBrand::get();
        $car_rent_brands_dt = CarRentBrandDt::get();


        return view('CarRent.CarsModel.edit', compact('car_model', 'sys_codes_type', 'sys_codes_status', 'sys_codes_location',
            'sys_codes_ownership_status', 'sys_codes_manufactuer', 'sys_codes_tracker_status', 'car_rent_brands', 'car_rent_brands_dt',
            'suppliers', 'companies', 'sys_codes_status_68', 'sys_codes_status_69', 'sys_codes_status_70', 'sys_codes_status_71'));

    }

    public function update(Request $request, $id)
    {

        $car_model = CarRentModel::find($id);
        if ($request->car_photo_url) {
            $car_photo_url = $this->getPhoto($request->car_photo_url);
        }
        $car_model->update([
            'car_rent_model_code' => $request->car_rent_model_code,
            'car_purchase_date' => $request->car_purchase_date,
            'car_rent_model_status' => $request->car_rent_model_status,
            'car_brand_id' => $request->car_brand_id,
            'car_brand_dt_id' => $request->car_brand_dt_id,
            'car_model_year' => $request->car_model_year,
            'car_category_id' => $request->car_category_id,
            'Property_type' => $request->Property_type,
            'car_purchase_cost' => $request->car_purchase_cost,
            'owner_name' => $request->owner_name,
            'car_photo_url' => isset($car_photo_url) ? 'Cars/' . $car_photo_url : $car_model->car_photo_url,
            'gear_box_type_id' => $request->gear_box_type_id,
            'engine_type' => $request->engine_type,
            'fuel_type_id' => $request->fuel_type_id,
            'car_color' => $request->car_color,
            'oil_type' => $request->oil_type,
            'oil_change_km' => $request->oil_change_km,
            'car_doors' => $request->car_doors,
            'car_passengers' => $request->car_passengers,
            'updated_user' => $request->updated_user,
            'company_id' => $request->company_id,

        ]);

        $cars = $car_model->carRentCars;

        foreach ($cars as $k => $car) {

            $car->update([
                'company_id' => $request->company_id,
                'branch_id' => session('branch')['branch_id'],
                'car_rent_model_id' => $car_model->car_rent_model_id,
                'car_brand_id' => $request->car_brand_id,
                'car_brand_dt_id' => $request->car_brand_dt_id,
                'car_model_year' => $car_model->car_model_year,
                'car_category_id' => $car_model->car_category_id,
                'car_color' => $car_model->car_color,
                'car_weight' => $car_model->car_weight,
                'gear_box_type_id' => $car_model->gear_box_type_id,
                'engine_type' => $car_model->engine_type,
                'fuel_type_id' => $car_model->fuel_type_id,
                'car_doors' => $car_model->car_doors,
                'car_passengers' => $car_model->car_passengers,
                'car_desc' => $car_model->car_desc,
                'car_photo_url' => $car_photo_url,
                'oil_type' => $request->oil_type,
                'oil_change_km' => $request->oil_change_km,
                'car_purchase_date' => $request->car_purchase_date,
                'car_purchase_cost' => $request->car_purchase_cost,
                'owner_name' => $request->owner_name,
                'Property_type' => $request->Property_type,
                'updated_user' => auth()->user()->user_id,


            ]);

        }


        return redirect()->route('CarRentModel')->with(['success' => 'تم تحديث بيانات الناقله']);


    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Cars"), $name);
        return $name;
    }

    public function getBrandDetails()
    {
        $brand = CarRentBrand::find(request()->car_brand_id);
        $brand_details = $brand->branddt;
        return response()->json(['data' => $brand_details]);
    }

}
