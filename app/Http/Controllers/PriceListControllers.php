<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PriceListResource;
use App\Http\Middleware\UsersApp\Add;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use App\Models\PriceListHd;
use App\Models\PriceListDt;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\Customer;
use App\Models\CompanyMenuSerial;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Reports;

use Lang;


class PriceListControllers extends Controller
{
    //

    public function index(Request $request)
    {

        $main_companies = CompanyGroup::get();
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('customer_category', 2)->where('company_group_id', auth()->user()->company_group_id)->get();


        if ($request->ajax()) {

            $data = PriceListHd::where('company_group_id', $company->company_group_id)->where('price_list_category', 'shipping cars')
                ->select('price_list_code', 'price_list_start_date', 'price_list_end_date', 'price_list_notes',
                    'price_list_id', 'price_list_status', 'customer_id')
                ->with('customer:customer_id,customer_name_full_' . app()->getLocale() . ' as customer_name_full')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    return $row->customer ? $row->customer->customer_name_full : '';
                })
                ->addColumn('status', function ($row) {
                    if ($row->price_list_status == 0) {
                        return $row->status = app()->isLocale('ar') ? 'غير فعال' : 'Not Active';
                    } else {
                        return $row->status = app()->isLocale('ar') ? 'فعال' : 'Active';
                    }
                })
                ->addColumn('action', function ($row) {
                    $price_list_report = Reports::where('report_code', '51002')->first()->report_url;
                    $price_list_all = Reports::where('report_code', '51001')->first()->report_url;

                    return (string)view('PriceList.Actions.actions', compact('row', 'price_list_report', 'price_list_all'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $price_list_all = Reports::where('report_code', '51001')->first()->report_url;
        $company_report = session('company') ? session('company') : auth()->user()->company;
        return view('PriceList.index', compact('main_companies', 'customers', 'companies', 'price_list_all', 'company_report'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 26)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 64)->where('company_group_id', $company->company_group_id)->get();


        return view('PriceList.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_item',
            'sys_codes_location', 'customers', 'companies'));

        //   $view = view('PriceList.create', compact('sys_codes_type','sys_codes_status', 'sys_codes_countries','customers', 'companies'));

        // return \Response::json([ 'view' => $view->render(), 'success' => true ]);

    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $company = session('company') ? session('company') : auth()->user()->company;
            $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
                ->where('app_menu_id', 51)->latest()->first();

            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'PL-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 51,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);
            }

            $invoice_hd = PriceListHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'price_list_category' => 'shipping cars',
                'customer_id' => $request->customer_id,
                'created_date' => Carbon::now(),
                'price_list_start_date' => $request->price_list_start_date,
                'price_list_end_date' => $request->price_list_end_date,
                'price_list_code' => $string_number,
                'price_list_status' => $request->price_list_status,
                'price_list_notes' => $request->price_list_notes,
                'created_user' => auth()->user()->user_id
            ]);

            foreach ($request->item_id as $k => $item_id) {
                PriceListDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'price_list_id' => $invoice_hd->price_list_id,
                    'item_id' => $request->item_id[$k],
                    'customer_id' => $invoice_hd->customer_id,
                    'max_fees' => $request->max_fees[$k],
                    'min_fees' => $request->min_fees[$k],
                    'cost_fees' => 0,
                    'distance_time' => $request->distance_time[$k],
                    'distance_fees' => 0,
                    'distance' => $request->distance[$k],
                    'loc_from' => $request->loc_from[$k],
                    'loc_to' => $request->loc_to[$k],
                   // 'price_factor' => $request->price_factor[$k],
                    'created_user' => auth()->user()->user_id
                ]);

            }
            DB::commit();
            return redirect()->route('PriceList')->with(['success' => 'تمت الاضافه']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('price list store', [$e]);
            return back()->with(['error' => 'حدث خطأ ما']);
        }

    }


    public function showDuplicate($id)
    {
        if (request()->ajax()) {
            $price_list = PriceListHd::where('price_list_id', request()->price_list_id)->first();
            $price_list_dts = $price_list->pricelistDetails;
            return response()->json(['data' => $price_list,
                'price_list_dts' => PriceListResource::collection($price_list_dts)]);
        }

        $price_list = PriceListHd::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 64)->where('company_group_id', $company->company_group_id)->get();


        return view('PriceList.duplicate', compact('sys_codes_type', 'sys_codes_item',
            'sys_codes_location', 'companies', 'price_list', 'id', 'customers'));
    }

    public function storeDuplicate(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 51)->latest()->first();

        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'PL-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 51,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }


        $invoice_hd = PriceListHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'price_list_category' => 'shipping cars',
            'customer_id' => $request->customer_id,
            'created_date' => Carbon::now(),
            'price_list_start_date' => $request->price_list_start_date,
            'price_list_end_date' => $request->price_list_end_date,
            'price_list_code' => $string_number,
            'price_list_status' => $request->price_list_status,
            'price_list_notes' => $request->price_list_notes,
            'created_user' => auth()->user()->user_id

        ]);

        foreach ($request->item_id as $k => $item_id) {
            PriceListDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'price_list_id' => $invoice_hd->price_list_id,
                'item_id' => $request->item_id[$k],
                'customer_id' => $invoice_hd->customer_id,
                'max_fees' => $request->max_fees[$k],
                'min_fees' => $request->min_fees[$k],
                'cost_fees' => 0,
                'distance_time' => $request->distance_time[$k],
                'distance_fees' => 0,
                'distance' => $request->distance[$k],
                'loc_from' => $request->loc_from[$k],
                'loc_to' => $request->loc_to[$k],
                'created_user' => auth()->user()->user_id
            ]);

        }


        return redirect()->route('PriceList')->with(['success' => 'تمت الاضافه']);
    }

    public function edit($id)
    {
        if (request()->ajax()) {
            $price_list = PriceListHd::where('price_list_id', request()->price_list_id)->first();
            $price_list_dts = $price_list->pricelistDetails;
            return response()->json(['data' => $price_list,
                'price_list_dts' => PriceListResource::collection($price_list_dts)]);
        }

        $price_list = PriceListHd::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 64)->where('company_group_id', $company->company_group_id)->get();


        return view('PriceList.edit', compact('sys_codes_type', 'sys_codes_item',
            'sys_codes_location', 'companies', 'price_list', 'id', 'customers'));
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        $price_list_hd = PriceListHd::find($id);
        $price_list_hd->update([
            'company_id' => $request->company_id,
            'price_list_category' => 'shipping cars',
            'customer_id' => $request->customer_id,
            'updated_date' => Carbon::now(),
            'price_list_start_date' => $request->price_list_start_date,
            'price_list_end_date' => $request->price_list_end_date,
            'price_list_notes' => $request->price_list_notes,
            'price_list_status' => $request->price_list_status,
            'updated_user' => auth()->user()->user_id
        ]);

        foreach ($request->old_price_list_dt_id as $k => $price_list_dt_id) {
            $price_list_dt = PriceListDt::find($price_list_dt_id);

            $price_list_dt->update([
                'company_id' => $request->company_id,
                'item_id' => $request->old_item_id[$k],
                'customer_id' => $request->customer_id,
                'min_fees' => $request->old_min_fees[$k],
                'max_fees' => $request->old_max_fees[$k],
                'cost_fees' => 0,
                'distance_time' => $request->old_distance_time[$k],
                'distance' => $request->old_distance[$k],
                'loc_from' => $request->old_loc_from[$k],
                'loc_to' => $request->old_loc_to[$k],
                'updated_user' => auth()->user()->user_id
            ]);
        }
        $company = Company::where('company_id', $request->company_id)->first();

        //$this->removeNulls($request->loc_from);
        if (($request->loc_from) > 0 && $request->loc_from[0]) {
            foreach ($request->loc_from as $k => $loc_from) {

                PriceListDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'price_list_id' => $price_list_hd->price_list_id,
                    'item_id' => $request->item_id[$k],
                    'customer_id' => $price_list_hd->customer_id,
                    'max_fees' => $request->max_fees[$k],
                    'min_fees' => $request->min_fees[$k],
                    'cost_fees' => 0,
                    'distance_time' => $request->distance_time[$k],
//                    'distance_fees' => $request->distance_fees[$k],
                    'distance' => $request->distance[$k],
                    'loc_from' => $request->loc_from[$k],
                    'loc_to' => $request->loc_to[$k],
                    'created_user' => auth()->user()->user_id
                ]);
            }
        }

        return redirect()->route('PriceList')->with(['success' => 'تم التعديل']);

    }

    public function delete()
    {
        $price_list_dt = PriceListDt::find(request()->price_list_dt_id);
        //return 'success';
        $price_list_dt->delete();
        return response()->json(['data' => 'success']);
    }


//////////////////////////////////////pricelist_cargo

    public function index_cargo(Request $request)
    {

        $main_companies = CompanyGroup::get();
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('customer_category', 2)->where('company_group_id', auth()->user()->company_group_id)->get();


        if ($request->ajax()) {

            $data = PriceListHd::where('company_group_id', $company->company_group_id)->
            where('price_list_category', 'cargo')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->customer->customer_name_full_ar;
                    } else {
                        return $row->customer->customer_name_full_en;
                    }

                })
                ->addColumn('status', function ($row) {
                    if ($row->price_list_status == 0) {
                        if (\Lang::getLocale() == 'ar') {
                            return $row->status = 'غير فعال';
                        } else {
                            return $row->status = 'Not Active';
                        }

                    } else {

                        if (\Lang::getLocale() == 'ar') {
                            return $row->status = ' فعال';
                        } else {
                            return $row->status = ' Active';
                        }
                    }
                })
                ->addColumn('action', function ($row) {
                    $price_list_report = Reports::where('report_code', '51002')->first()->report_url;
                    $price_list_all = Reports::where('report_code', '51001')->first()->report_url;
                    return (string)view('PriceListcargo.Actions.actions', compact('row', 'price_list_report'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $price_list_all = Reports::where('report_code', '51001')->first()->report_url;
        $company_report = session('company') ? session('company') : auth()->user()->company;

        return view('PriceListcargo.index', compact('main_companies', 'customers', 'companies', 'price_list_all', 'company_report'));
    }

    public function create_cargo()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 26)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('customer_category', 3)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 28)->where('company_group_id', $company->company_group_id)->get();


        return view('PriceListcargo.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_item',
            'sys_codes_location', 'customers', 'companies'));

        //   $view = view('PriceList.create', compact('sys_codes_type','sys_codes_status', 'sys_codes_countries','customers', 'companies'));

        // return \Response::json([ 'view' => $view->render(), 'success' => true ]);

    }

    public function store_cargo(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 51)->latest()->first();

        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'PL-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 51,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }


        $invoice_hd = PriceListHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'price_list_category' => 'cargo',
            'customer_id' => $request->customer_id,
            'created_date' => Carbon::now(),
            'price_list_start_date' => $request->price_list_start_date,
            'price_list_end_date' => $request->price_list_end_date,
            'price_list_code' => $string_number,
            'price_list_status' => $request->price_list_status,
            'price_list_notes' => $request->price_list_notes,

            'created_user' => auth()->user()->user_id

        ]);

        foreach ($request->item_id as $k => $item_id) {
            PriceListDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'price_list_id' => $invoice_hd->price_list_id,
                'item_id' => $request->item_id[$k],
                'customer_id' => $invoice_hd->customer_id,
                'max_fees' => $request->max_fees[$k],
                'min_fees' => $request->min_fees[$k],
                'cost_fees' => $request->cost_fees[$k],
                'distance_time' => $request->distance_time[$k],
                'distance_fees' => 0,
                'distance' => $request->distance[$k],
                'loc_from' => $request->loc_from[$k],
                'loc_to' => $request->loc_to[$k],

                'created_user' => auth()->user()->user_id
            ]);

        }


        return redirect()->route('PriceList-cargo')->with(['success' => 'تمت الاضافه']);
    }


    public function edit_cargo($id)
    {
        if (request()->ajax()) {
            $price_list = PriceListHd::where('price_list_id', request()->price_list_id)->first();
            $price_list_dts = $price_list->pricelistDetails;
            return response()->json(['data' => $price_list,
                'price_list_dts' => PriceListResource::collection($price_list_dts)]);
        }
        $price_list = PriceListHd::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::whereIn('customer_category', [2, 3, 4, 5, 6, 9])->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 28)->where('company_group_id', $company->company_group_id)->get();


        return view('PriceListcargo.edit', compact('sys_codes_type', 'sys_codes_item',
            'sys_codes_location', 'companies', 'price_list', 'id', 'customers'));
    }

    public function update_cargo(Request $request, $id)
    {
        $price_list_hd = PriceListHd::find($id);

        $price_list_hd->update([
            'company_id' => $request->company_id,
            'price_list_category' => 'cargo',
            'customer_id' => $request->customer_id,
            'updated_date' => Carbon::now(),
            'price_list_start_date' => $request->price_list_start_date,
            'price_list_end_date' => $request->price_list_end_date,
            'price_list_notes' => $request->price_list_notes,
            'price_list_status' => $request->price_list_status,
            'updated_user' => auth()->user()->user_id
        ]);

        foreach ($request->old_price_list_dt_id as $k => $price_list_dt_id) {
            $price_list_dt = PriceListDt::find($price_list_dt_id);

            $price_list_dt->update([
                'company_id' => $request->company_id,
                'item_id' => $request->old_item_id[$k],
                'customer_id' => $request->customer_id,
                'min_fees' => $request->old_min_fees[$k],
                'max_fees' => $request->old_max_fees[$k],
                'cost_fees' => $request->old_cost_fees[$k],
                'distance_time' => $request->old_distance_time[$k],
                'distance' => $request->old_distance[$k],
                'loc_from' => $request->old_loc_from[$k],
                'loc_to' => $request->old_loc_to[$k],
                'updated_user' => auth()->user()->user_id
            ]);
        }
        $company = Company::where('company_id', $request->company_id)->first();

        //$this->removeNulls($request->loc_from);
        if (($request->loc_from) > 1) {
            foreach ($request->loc_from as $k => $loc_from) {

                PriceListDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'price_list_id' => $price_list_hd->price_list_id,
                    'item_id' => $request->item_id[$k],
                    'customer_id' => $price_list_hd->customer_id,
                    'max_fees' => $request->max_fees[$k],
                    'min_fees' => $request->min_fees[$k],
                    'cost_fees' => $request->cost_fees[$k],
                    'distance_time' => $request->distance_time[$k],
//                    'distance_fees' => $request->distance_fees[$k],
                    'distance' => $request->distance[$k],
                    'loc_from' => $request->loc_from[$k],
                    'loc_to' => $request->loc_to[$k],
                    'created_user' => auth()->user()->user_id
                ]);
            }
        }

        return redirect()->route('PriceList-cargo')->with(['success' => 'تم التعديل']);

    }

    public function delete_cargo()
    {
        $price_list_dt = PriceListDt::find(request()->price_list_dt_id);
        //return 'success';
        $price_list_dt->delete();
        return response()->json(['data' => 'success']);
    }


//////////////////////////////////////pricelist_int

    public function index_int(Request $request)
    {

        $main_companies = CompanyGroup::get();
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('customer_category', 2)->where('company_group_id', auth()->user()->company_group_id)->get();


        if ($request->ajax()) {

            $data = PriceListHd::where('company_group_id', $company->company_group_id)->
            where('price_list_category', 'int')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->customer->customer_name_full_ar;
                    } else {
                        return $row->customer->customer_name_full_en;
                    }

                })
                ->addColumn('status', function ($row) {
                    if ($row->price_list_status == 0) {
                        if (\Lang::getLocale() == 'ar') {
                            return $row->status = 'غير فعال';
                        } else {
                            return $row->status = 'Not Active';
                        }

                    } else {

                        if (\Lang::getLocale() == 'ar') {
                            return $row->status = ' فعال';
                        } else {
                            return $row->status = ' Active';
                        }
                    }
                })
                ->addColumn('action', function ($row) {
                    $price_list_report = Reports::where('report_code', '51002')->first()->report_url;
                    $price_list_all = Reports::where('report_code', '51001')->first()->report_url;
                    return (string)view('PriceListint.Actions.actions', compact('row', 'price_list_report'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $price_list_all = Reports::where('report_code', '51001')->first()->report_url;
        $company_report = session('company') ? session('company') : auth()->user()->company;
        return view('PriceListint.index', compact('main_companies', 'customers', 'companies', 'price_list_all', 'company_report'));
    }

    public function create_int()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 26)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 64)->where('company_group_id', $company->company_group_id)->get();


        return view('PriceListint.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_item',
            'sys_codes_location', 'customers', 'companies'));

        //   $view = view('PriceList.create', compact('sys_codes_type','sys_codes_status', 'sys_codes_countries','customers', 'companies'));

        // return \Response::json([ 'view' => $view->render(), 'success' => true ]);

    }

    public function store_int(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 51)->latest()->first();

        if (isset($last_invoice_reference)) {
            $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
            $array_number = explode('-', $last_invoice_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_invoice_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'PL-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 51,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }


        $invoice_hd = PriceListHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'price_list_category' => 'int',
            'customer_id' => $request->customer_id,
            'created_date' => Carbon::now(),
            'price_list_start_date' => $request->price_list_start_date,
            'price_list_end_date' => $request->price_list_end_date,
            'price_list_code' => $string_number,
            'price_list_status' => $request->price_list_status,
            'price_list_notes' => $request->price_list_notes,
            'created_user' => auth()->user()->user_id
        ]);

        foreach ($request->item_id as $k => $item_id) {
            PriceListDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'price_list_id' => $invoice_hd->price_list_id,
                'item_id' => $request->item_id[$k],
                'customer_id' => $invoice_hd->customer_id,
                'max_fees' => $request->max_fees[$k],
                'min_fees' => $request->min_fees[$k],
                'cost_fees' => $request->cost_fees[$k],
                'distance_time' => $request->distance_time[$k],
                'distance_fees' => 0,
                'distance' => $request->distance[$k],
                'loc_from' => $request->loc_from[$k],
                'loc_to' => $request->loc_to[$k],
                'created_user' => auth()->user()->user_id
            ]);

        }


        return redirect()->route('PriceList-int')->with(['success' => 'تمت الاضافه']);
    }

    public function edit_int($id)
    {
        if (request()->ajax()) {
            $price_list = PriceListHd::where('price_list_id', request()->price_list_id)->first();
            $price_list_dts = $price_list->pricelistDetails;
            return response()->json(['data' => $price_list,
                'price_list_dts' => PriceListResource::collection($price_list_dts)]);
        }
        $price_list = PriceListHd::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 64)->where('company_group_id', $company->company_group_id)->get();


        return view('PriceListint.edit', compact('sys_codes_type', 'sys_codes_item',
            'sys_codes_location', 'companies', 'price_list', 'id', 'customers'));
    }

    public function update_int(Request $request, $id)
    {
        $price_list_hd = PriceListHd::find($id);
        $price_list_hd->update([
            'company_id' => $request->company_id,
            'price_list_category' => 'int',
            'customer_id' => $request->customer_id,
            'updated_date' => Carbon::now(),
            'price_list_start_date' => $request->price_list_start_date,
            'price_list_end_date' => $request->price_list_end_date,
            'price_list_notes' => $request->price_list_notes,
            'price_list_status' => $request->price_list_status,
            'updated_user' => auth()->user()->user_id
        ]);

        foreach ($request->old_price_list_dt_id as $k => $price_list_dt_id) {
            $price_list_dt = PriceListDt::find($price_list_dt_id);

            $price_list_dt->update([
                'company_id' => $request->company_id,
                'item_id' => $request->old_item_id[$k],
                'customer_id' => $request->customer_id,
                'min_fees' => $request->old_min_fees[$k],
                'max_fees' => $request->old_max_fees[$k],
                'cost_fees' => $request->old_cost_fees[$k],
                'distance_time' => $request->old_distance_time[$k],
                'distance' => $request->old_distance[$k],
                'loc_from' => $request->old_loc_from[$k],
                'loc_to' => $request->old_loc_to[$k],
                'updated_user' => auth()->user()->user_id
            ]);
        }
        $company = Company::where('company_id', $request->company_id)->first();

        //$this->removeNulls($request->loc_from);
        if (($request->loc_from) > 1) {
            foreach ($request->loc_from as $k => $loc_from) {

                PriceListDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'price_list_id' => $price_list_hd->price_list_id,
                    'item_id' => $request->item_id[$k],
                    'customer_id' => $price_list_hd->customer_id,
                    'max_fees' => $request->max_fees[$k],
                    'min_fees' => $request->min_fees[$k],
                    'cost_fees' => $request->cost_fees[$k],
                    'distance_time' => $request->distance_time[$k],
//                    'distance_fees' => $request->distance_fees[$k],
                    'distance' => $request->distance[$k],
                    'loc_from' => $request->loc_from[$k],
                    'loc_to' => $request->loc_to[$k],
                    'created_user' => auth()->user()->user_id
                ]);
            }
        }

        return redirect()->route('PriceList-int')->with(['success' => 'تم التعديل']);

    }

    public function delete_int()
    {
        $price_list_dt = PriceListDt::find(request()->price_list_dt_id);
        //return 'success';
        $price_list_dt->delete();
        return response()->json(['data' => 'success']);
    }


}
