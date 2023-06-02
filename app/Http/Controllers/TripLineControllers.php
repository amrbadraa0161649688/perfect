<?php

namespace App\Http\Controllers;

use App\Http\Resources\TripLineDtResource;
use App\Http\Resources\TripLineHdResource;
use App\Http\Resources\TripLineResource;
use App\Models\TripLineDt;
use App\Models\TripLineHd;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Customer;
use App\Models\CompanyMenuSerial;
use App\Models\Reports;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lang;
use Yajra\DataTables\DataTables;


class TripLineControllers extends Controller
{
    //

    public function index(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $companys = Company::where('company_group_id', $company->company_group_id)->get();

        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 39)->where('company_group_id', $company->company_group_id)->get();

        $sys_truck_type = SystemCode::where('sys_category_id', 29)->where('company_group_id', $company->company_group_id)->get();
        $sys_line_type = SystemCode::where('sys_category_id', 126)->where('company_group_id', $company->company_group_id)->get();


        if ($request->ajax()) {
            $data = TripLineHd::where('company_group_id', $company->company_group_id)->latest()->get();

            if (request()->company_id) {
                $data = TripLineHd::whereIn('company_id', request()->company_id)->latest()->get();
                //  $query_count = TripHd::whereIn('branch_id', request()->branch_id);


                if (request()->loc_from) {

                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('trip_line_loc_from', request()->loc_from)->latest()->get();

                }

                if (request()->loc_to) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('trip_line_loc_to', request()->loc_to)->latest()->get();

                }
                if (request()->sys_truck_types) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('truck_type', request()->sys_truck_types)->latest()->get();

                }

                if (request()->sys_line_types) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('trip_line_type', request()->sys_line_types)
                        ->latest()->get();
                    // $data = $data->paginate();
                }

                if (request()->trip_line_hd_code) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->where('trip_line_code', 'like', '%' . request()->trip_line_hd_code . '%')
                        ->latest()->get();
                    // $data = $data->paginate();
                }

                if (request()->sys_truck_types && request()->sys_line_types) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('truck_type', request()->sys_truck_types)
                        ->whereIn('trip_line_type', request()->sys_line_types)->latest()->get();

                }

                if (request()->sys_truck_types && request()->sys_line_types && request()->statuses) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('truck_type', request()->sys_truck_types)->whereIn('trip_line_type', request()->sys_line_types)
                        ->whereIn('trip_line_status', request()->statuses)->latest()->get();

                }
                if (request()->sys_truck_types && request()->sys_line_types && request()->statuses && request()->loc_from) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('truck_type', request()->sys_truck_types)->whereIn('trip_line_type', request()->sys_line_types)
                        ->whereIn('trip_line_status', request()->statuses)
                        ->whereIn('trip_line_loc_from', request()->loc_from)->latest()->get();

                }

                if (request()->sys_truck_types && request()->sys_line_types && request()->statuses && request()->loc_from && request()->loc_to) {
                    $data = TripLineHd::whereIn('company_id', request()->company_id)->whereIn('truck_type', request()->sys_truck_types)->whereIn('trip_line_type', request()->sys_line_types)
                        ->whereIn('trip_line_status', request()->statuses)
                        ->whereIn('trip_line_loc_from', request()->loc_from)
                        ->whereIn('trip_line_loc_to', request()->loc_to)->latest()->get();

                }

            }


            return Datatables::of($data)
                ->addIndexColumn()
//                ->addColumn('company', function ($row) {
//                    return \Lang::getLocale() == 'ar'
//                        ? $row->company->company_name_ar
//                        : $row->company->company_name_en ;
//                })
                ->addColumn('truck_type', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->truck_Type->system_code_name_ar;
                    } else {
                        return $row->truck_Type->system_code_name_en;
                    }
                })
                ->addColumn('trip_line_type', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->triplinetypename->system_code_name_ar;
                    } else {
                        return $row->triplinetypename->system_code_name_en;
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->trip_line_status == 0) {
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
                    $trip_line_report = Reports::where('report_code', '105002')->first()->report_url;
                    return (string)view('Trips.TripLines.Actions.actions', compact('row', 'trip_line_report'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $trip_line_all = Reports::where('report_code', '105001')->first()->report_url;
        $company_report = session('company') ? session('company') : auth()->user()->company;

        return view('Trips.TripLines.index', compact('companies', 'company', 'sys_codes_status', 'sys_codes_location', 'sys_truck_type', 'sys_line_type', 'trip_line_all', 'company_report'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 64)->get();

        $sys_truck_type = SystemCode::where('sys_category_id', 29)->where('company_group_id', $company->company_group_id)->get();
        $sys_line_type = SystemCode::where('sys_category_id', 126)->where('company_group_id', $company->company_group_id)->get();

        return view('Trips.TripLines.create', compact('sys_codes_type', 'sys_codes_item',
            'sys_codes_location', 'companies', 'sys_truck_type', 'sys_line_type'));


    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_trip_line_reference = CompanyMenuSerial::where('company_id', $request->company_id)
            ->where('app_menu_id', 105)->latest()->first();

        DB::beginTransaction();
        if (isset($last_trip_line_reference)) {
            $last_trip_line_reference_number = $last_trip_line_reference->serial_last_no;
            $array_number = explode('-', $last_trip_line_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_trip_line_reference->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'PL-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'app_menu_id' => 105,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }


        $trip_hd = TripLineHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $request->company_id,
            'trip_line_code' => $string_number,
            'trip_line_status' => $request->trip_line_status,
            'trip_line_distance' => $request->total_distance,
            'trip_line_time' => $request->total_distance_time,
            'trip_line_fess_1' => $request->total_cost_fees_1,
            'trip_line_fees_2' => $request->total_cost_fees_2,
            'trip_line_fees_3' => $request->total_cost_fees_3,
            'trip_line_loc_from' => $request->trip_line_loc_from,
            'trip_line_loc_to' => $request->trip_line_loc_to,
            'truck_type' => $request->truck_type,
            'trip_line_type' => $request->trip_line_type,
            'created_user' => auth()->user()->user_id,
            'trip_line_desc' => 'ye'
        ]);

        $trip_line_desc_arr = '';

        foreach ($request->loc_from as $k => $loc_from) {
            $loc_from_obj = SystemCode::where('system_code_id', $loc_from)->first()->system_code_name_ar;
            if ($k == 0) {
                $trip_line_desc_arr = $loc_from_obj;
            } else {
                $trip_line_desc_arr = $trip_line_desc_arr . '-->' . $loc_from_obj;
            }

            TripLineDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $request->company_id,
                'trip_line_hd_id' => $trip_hd->trip_line_hd_id,
//                'loc_code' => $trip_hd->trip_line_code,
                'loc_status' => $trip_hd->trip_line_status,
                'loc_from' => $request->loc_from[$k],
                'loc_to' => $request->loc_to[$k],
                'distance' => $request->distance[$k],
                'distance_time' => $request->distance_time[$k],
                'cost_fees_1' => $request->cost_fees_1[$k],
                'cost_fees_2' => $request->cost_fees_2[$k],
                'cost_fees_3' => $request->cost_fees_3[$k],
                'created_user' => auth()->user()->user_id
            ]);
        }

        $trip_line_desc_arr = $trip_line_desc_arr . '-->' . SystemCode::where('system_code_id', $request->loc_to[$k])->first()->system_code_name_ar;
        $trip_hd->trip_line_desc = $trip_line_desc_arr;
        $trip_hd->save();

        DB::commit();

        return redirect()->route('TripLine')->with(['success' => 'تمت الاضافه']);
    }

    public function edit($id)
    {
        $trip_line = TripLineHd::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->get();
        $sys_codes_item = SystemCode::where('sys_category_id', 64)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $sys_truck_type = SystemCode::where('sys_category_id', 29)->where('company_group_id', $company->company_group_id)->get();
        $sys_line_type = SystemCode::where('sys_category_id', 126)->where('company_group_id', $company->company_group_id)->get();

        if (request()->ajax()) {
            $trip_line = TripLineHd::find(request()->trip_line_hd_id);
            return response()->json(['data' => new TripLineHdResource($trip_line), 'tripLineDts' => TripLineDtResource::collection($trip_line->tripLineDt)]);
        }
        return view('Trips.TripLines.edit', compact('sys_codes_type', 'sys_codes_item',
            'sys_codes_location', 'trip_line', 'id', 'sys_truck_type', 'sys_line_type', 'id'));
    }

    public function update(Request $request, $id)
    {
        $trip_line_hd = TripLineHd::find($id);

        DB::beginTransaction();

        $trip_line_hd->update([
            'trip_line_status' => $request->trip_line_status,
            'truck_type' => $request->truck_type,
            'trip_line_type' => $request->trip_line_type,
            'trip_line_loc_from' => $request->loc_from_id,
            'trip_line_loc_to' => $request->loc_to_id,
        ]);

        $trip_line_desc_arr = '';

        foreach ($request->trip_line_dt as $k => $trip_line_dt_id) {

            $loc_from_obj = SystemCode::where('system_code_id', $request->loc_from[$k])->first()->system_code_name_ar;
            if ($k == 0) {
                $trip_line_desc_arr = $loc_from_obj;
            } else {
                $trip_line_desc_arr = $trip_line_desc_arr . '-->' . $loc_from_obj;
            }


            if ($trip_line_dt_id > 0) {
                $trip_line_dt = TripLineDt::find($trip_line_dt_id);
                $trip_line_dt->update([
                    'loc_from' => $request->loc_from[$k],
                    'loc_to' => $request->loc_to[$k],
                    'distance' => $request->distance[$k],
                    'distance_time' => $request->distance_time[$k],
                    'cost_fees_1' => $request->cost_fees_1[$k],
                    'cost_fees_2' => $request->cost_fees_2[$k],
                    'cost_fees_3' => $request->cost_fees_3[$k],
                ]);
            } else {
                TripLineDt::create([
                    'company_group_id' => $trip_line_hd->company_group_id,
                    'company_id' => $trip_line_hd->company_id,
                    'trip_line_hd_id' => $trip_line_hd->trip_line_hd_id,
                    'loc_status' => $trip_line_hd->trip_line_status,
                    'loc_from' => $request->loc_from[$k],
                    'loc_to' => $request->loc_to[$k],
                    'distance' => $request->distance[$k],
                    'distance_time' => $request->distance_time[$k],
                    'cost_fees_1' => $request->cost_fees_1[$k],
                    'cost_fees_2' => $request->cost_fees_2[$k],
                    'cost_fees_3' => $request->cost_fees_3[$k],
                    'created_user' => auth()->user()->user_id
                ]);
            }
        }

        $trip_line_desc_arr = $trip_line_desc_arr . '-->' . SystemCode::where('system_code_id', $request->loc_to[$k])->first()->system_code_name_ar;
        $trip_line_hd->trip_line_desc = $trip_line_desc_arr;
        $trip_line_hd->trip_line_fess_1 = $request->total_cost_fees_1;
        $trip_line_hd->trip_line_fees_2 = $request->total_cost_fees_2;
        $trip_line_hd->trip_line_fees_3 = $request->total_cost_fees_3;
        $trip_line_hd->trip_line_time = $request->total_distance_time;
        $trip_line_hd->trip_line_distance = $request->total_distance;
        $trip_line_hd->save();
        DB::commit();

        return redirect()->route('TripLine')->with(['success' => 'تم التعديل']);

    }

    public function delete()
    {
        $trip_line_dt = TripLineDt::find(request()->trip_line_dt_id);
        $trip_line_dt->delete();
        return response()->json(['data' => 'success']);
    }


}
