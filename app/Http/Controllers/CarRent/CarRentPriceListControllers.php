<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarModelResource;
use App\Models\Branch;
use App\Models\CarPriceListDt;
use App\Models\CarPriceListHd;
use App\Models\CarRentBrand;
use App\Models\CarRentCars;
use App\Models\CarRentModel;
use App\Models\CarRentPriceAdd;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarRentPriceListControllers extends Controller
{
    //

    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $models = CarRentModel::where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('company_group_id', $company->company_group_id)->has('carPriceListHd')->get();
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->where('company_id', $company->company_id)->get();

        $car_price_lists = CarPriceListHd::where('company_group_id', $company->company_group_id);

        if (request()->company_id) {
            $car_price_lists->whereIn('company_id', request()->company_id);
            if (request()->branch_id) {
                $car_price_lists->whereJsonContains('price_branches', request()->branch_id);
            }

            if (request()->car_rent_model_id) {
                $car_price_lists->whereHas('priceListDetails', function ($query_dt) {
                    $query_dt->whereIn('car_model_id', request()->car_rent_model_id);
                });
            }

            if (request()->customer_type) {
                $car_price_lists->whereIn('customer_type_id', request()->customer_type);
            }
            if (request()->customer_id) {
                $car_price_lists->whereIn('customer_id', request()->customer_id);
            }
        }
        $car_price_lists = $car_price_lists->paginate(EnumSetting::Paginate);
        return view('CarRent.PriceList.index', compact('car_price_lists', 'companies', 'branches'
            , 'models', 'sys_codes_type', 'customers'));

    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $contract_types = SystemCode::where('sys_category_id', 65)
            ->where('company_id', $company->company_id)->get();
        $sys_codes_type = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 27)->get();
        $sys_code_classifications = SystemCode::where('sys_category_id', 122)->where('company_group_id', $company->company_group_id)->get();

        //       انواع الاضافات
        $system_code_types = SystemCode::where('sys_category_id', 60)
            ->where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_type', SystemCode::where('system_code', 538)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
            ->where('customer_status', SystemCode::where('system_code', 26001)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id)->get();

        return view('CarRent.PriceList.create', compact('companies', 'customers', 'sys_codes_type',
            'contract_types', 'sys_code_classifications', 'system_code_types'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'price_branches' => 'required'
        ]);


        $company = Company::find($request->company_id);
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;

        $last_car_price_serial = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 43)->latest()->first();

        \DB::beginTransaction();

        if (isset($last_car_price_serial)) {
            $last_car_price_serial_no = $last_car_price_serial->serial_last_no;
            $array_number = explode('-', $last_car_price_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_car_price_serial->update(['serial_last_no' => $string_number]);

        } else {
            $string_number = 'PL-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 43,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $customer_type = SystemCode::where('system_code', $request->customer_type_code)
            ->where('company_group_id', $company->company_group_id)->first();


        $car_price_list_hd = CarPriceListHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'customer_id' => $request->customer_id ? $request->customer_id : 1,
            'customer_type_id' => $customer_type->system_code_id,
            'price_customer_category' => $request->price_customer_category,
            'price_branches' => json_encode(explode(',', $request->price_branches)),
            'rent_list_start_date' => $request->rent_list_start_date,
            'rent_list_end_date' => $request->rent_list_end_date,
            'rent_list_code' => $string_number,
            'rent_list_status' => $request->rent_list_status,
            'rent_list_notes' => $request->rent_list_notes,
            'created_user' => auth()->user()->user_id,
        ]);


        foreach ($request->car_model_id as $k => $car_model_id) {
            $car_model = CarRentModel::find($car_model_id);
            CarPriceListDt::create([
                'rent_list_id' => $car_price_list_hd->rent_list_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'customer_id' => $request->customer_id ? $request->customer_id : 1,
                'car_model_id' => $car_model_id,
                'brand_id' => $car_model->car_brand_id,
                'brand_dt_id' => $car_model->car_brand_dt_id,
                'rent_type_id' => $request->rent_type_id[$k],
                'rent_price' => $request->rent_price[$k],
                'discount_value' => $request->discount_value[$k],
                'extra_kilometer' => $request->extra_kilometer[$k],
                'extra_kilometer_price' => $request->extra_kilometer_price[$k],
                'extra_hour' => $request->extra_hour[$k],
                'extra_hour_price' => $request->extra_hour_price[$k],
                'hours_to_day' => $request->hours_to_day[$k],
                'extra_driver' => $request->extra_driver[$k],
            ]);
        }
        foreach ($request->rent_add_id as $k => $rent_add_id) {
            CarRentPriceAdd::create([
                'rent_list_id' => $car_price_list_hd->rent_list_id,
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'customer_id' => $request->customer_id ? $request->customer_id : 1,
                'rent_add_id' => $rent_add_id,
                'rent_add_price' => isset($request->rent_add_price[$k]) ? $request->rent_add_price[$k] : 0,
                'add_qty_value' => 1,
            ]);
        }

        \DB::commit();

        return redirect()->route('CarRentPriceList')->with(['success' => 'تم الإضافة بنجاح']);


    }

    public function edit($id)
    {

        $price_list_hd = CarPriceListHd::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $price_list_hd->company_group_id)->get();
        $contract_types = SystemCode::where('sys_category_id', 65)
            ->where('company_id', $price_list_hd->company_id)->get();
        $sys_codes_type = SystemCode::where('company_group_id', $price_list_hd->company_group_id)
            ->where('sys_category_id', 27)->get();
        $sys_code_classifications = SystemCode::where('sys_category_id', 122)->where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            $customer_type_code = $price_list_hd->customerType;
            $branches = Branch::whereIn('branch_id', json_decode($price_list_hd->price_branches))->get();
            return response()->json(['data' => $price_list_hd, 'price_list_dts'
            => $price_list_hd->priceListDetails, 'customer_type_code' => $customer_type_code,
                'selected_branches' => $branches]);
        }

        ////عملاء الافراد
        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_type', SystemCode::where('system_code', 538)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
            ->where('customer_status', SystemCode::where('system_code', 26001)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id)->get();

        return view('CarRent.PriceList.edit', compact('companies', 'customers', 'sys_codes_type',
            'contract_types', 'id', 'price_list_hd', 'sys_code_classifications'));

    }

    public function update(Request $request, $id)
    {
        //return $request->all();
        $company = Company::find($request->company_id);
        $car_price_list_hd = CarPriceListHd::find($id);
        $customer_type = SystemCode::where('system_code', $request->customer_type_code)
            ->where('company_group_id', $company->company_group_id)->first();
//        $customer_type = SystemCode::where('system_code', $request->customer_type_code)->first();

        $car_price_list_hd->update([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'customer_id' => $request->customer_id ? $request->customer_id : 1,
            'customer_type_id' => $customer_type->system_code_id,
            'price_customer_category' => $request->price_customer_category,
            'price_branches' => $request->price_branches ? json_encode(explode(',', $request->price_branches))
                : json_encode(json_decode($car_price_list_hd->price_branches)),
            'rent_list_start_date' => $request->rent_list_start_date,
            'rent_list_end_date' => $request->rent_list_end_date,
            'rent_list_status' => $request->rent_list_status,
            'rent_list_notes' => $request->rent_list_notes,
            'updated_user' => auth()->user()->user_id,
        ]);

        foreach ($request->rent_list_dt_id as $k => $rent_list_dt_id) {
            $rent_list_dt = CarPriceListDt::find($rent_list_dt_id);
            $car_model = CarRentModel::find($request->car_model_id[$k]);
            $rent_list_dt->update([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'customer_id' => $request->customer_id ? $request->customer_id : 1,
                'car_model_id' => $car_model->car_rent_model_id,
                'brand_id' => $car_model->car_brand_id,
                'brand_dt_id' => $car_model->car_brand_dt_id,
                'rent_type_id' => $request->rent_type_id[$k],
                'rent_price' => $request->rent_price[$k],
                'discount_value' => $request->discount_value[$k],
                'extra_kilometer' => $request->extra_kilometer[$k],
                'extra_kilometer_price' => $request->extra_kilometer_price[$k],
                'extra_hour' => $request->extra_hour[$k],
                'extra_hour_price' => $request->extra_hour_price[$k],
                'hours_to_day' => $request->hours_to_day[$k],
                'extra_driver' => $request->extra_driver[$k],
            ]);
        }

        if (isset($request->new_car_model_id)) {
//            return 'f';
            foreach ($request->new_car_model_id as $k => $car_model_id) {
                $car_model = CarRentModel::find($car_model_id);
                CarPriceListDt::create([
                    'rent_list_id' => $car_price_list_hd->rent_list_id,
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'customer_id' => $request->customer_id ? $request->customer_id : 1,
                    'car_model_id' => $car_model_id,
                    'brand_id' => $car_model->car_brand_id,
                    'brand_dt_id' => $car_model->car_brand_dt_id,
                    'rent_type_id' => $request->new_rent_type_id[$k],
                    'rent_price' => $request->new_rent_price[$k],
                    'discount_value' => $request->new_discount_value[$k],
                    'extra_kilometer' => $request->new_extra_kilometer[$k],
                    'extra_kilometer_price' => $request->new_extra_kilometer_price[$k],
                    'extra_hour' => $request->new_extra_hour[$k],
                    'extra_hour_price' => $request->new_extra_hour_price[$k],
                    'hours_to_day' => $request->new_hours_to_day[$k],
                    'extra_driver' => $request->new_extra_driver[$k],
                ]);
            }
        }

        return back()->with(['success' => 'تم التعديل']);


    }

    public function getCarModels()
    {
        $car_models = CarRentModel::where('company_id', request()->company_id)
            ->whereHas('priceListDts', function ($query) {
                $query->whereHas('priceListHd', function ($query2) {
                    $query2->where('rent_list_status', 0);
                });
            })->get();


        $car_models_2 = CarRentModel::where('company_id', request()->company_id)
            ->doesntHave('priceListDts')->get();


        if (request()->rent_list_id) {
            $car_models_3 = CarRentModel::whereIn('car_rent_model_id', CarPriceListDt::where('rent_list_id', request()->rent_list_id)
                ->pluck('car_model_id')->toArray())->get();
        }

        $result = $car_models->merge($car_models_2);

        if (isset($car_models_3)) {
            $result = $result->merge($car_models_3);
        }


        return response()->json(['data' => CarModelResource::collection($result)]);
    }

    public function deletePriceListDt(Request $request)
    {
        $price_list_dt = CarPriceListDt::find($request->rent_list_dt_id);
        $price_list_dt->delete();
        return response()->json(['data' => 'success']);
    }
}
