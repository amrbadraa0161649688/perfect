<?php

namespace App\Http\Controllers;

use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Http\Controllers\Naql\NaqlController;
use App\Http\Controllers\Naql\NaqlAPIController;
use App\Http\Resources\TripDtsResource;
use App\Http\Resources\WaybillCarsResource;
use App\Http\Resources\WaybillNaqlResource;
use App\Http\Resources\WayBillResource;
use App\Models\ApplicationsMenu;
use App\Models\Bond;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Employee;
use App\Models\InvoiceDt;
use App\Models\JournalDt;
use App\Models\JournalHd;
use App\Models\JournalType;
use App\Models\Note;
use App\Models\SMSCategory;
use App\Models\SystemCode;
use App\Models\TripDt;
use App\Models\TripHd;
use App\Models\TripLineDt;
use App\Models\TripLineHd;
use App\Models\Trucks;
use App\Models\Reports;
use App\Models\UsersPermissionsRol;
use App\Models\WaybillDt;
use App\Models\WaybillHd;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripControllers extends Controller
{
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $query_count = DB::table('Trucks')->where('company_group_id', $company->company_group_id);
        $ready_truck = $query_count->where('truck_status', SystemCode::where('system_code', 80)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

        $query = TripHd::where('company_group_id', $company->company_group_id)
            ->select('trip_hd_id', 'trip_hd_code', 'branch_id', 'trip_hd_start_date', 'truck_id', 'driver_id',
                'trip_line_hd_id', 'trip_hd_started_date', 'trip_hd_ended_date', 'trip_hd_status',
                'trip_loc_transit', 'http_status');

        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 39)->where('company_group_id', $company->company_group_id)->get();
        $data = request()->all();


        if (count(request()->query) > 1) {
            if (request()->branch_id) {
                $query = $query->whereIn('branch_id', request()->branch_id);
                //            جاهزه
                $ready_trip = TripHd::where('company_group_id', $company->company_group_id)->whereHas('tripLine', function ($q) {
                    $q->whereHas('locFrom', function ($q2) {
                        $q2->whereIn('branch_id', request()->branch_id);
                    });
                })->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
                $go_trip = TripHd::where('company_group_id', $company->company_group_id)->whereHas('tripLine', function ($q) {
                    $q->whereHas('locFrom', function ($q2) {
                        $q2->whereIn('branch_id', request()->branch_id);
                    });
                })->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
                $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)->whereHas('tripLine', function ($q) {
                    $q->whereHas('tripLineDtF', function ($q2) {
                        $q2->whereHas('locTo', function ($q3) {
                            $q3->whereIn('branch_id', request()->branch_id);
                        });
                    });
                })->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            }

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('trip_hd_start_date', '>=', request()->created_date_from)
                    ->whereDate('trip_hd_end_date', '<=', request()->created_date_to);

                //            جاهزه
                $ready_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereDate('trip_hd_start_date', '>=', request()->created_date_from)
                    ->whereDate('trip_hd_end_date', '<=', request()->created_date_to)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
                $go_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereDate('trip_hd_start_date', '>=', request()->created_date_from)
                    ->whereDate('trip_hd_end_date', '<=', request()->created_date_to)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
                $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereDate('trip_hd_start_date', '>=', request()->created_date_from)
                    ->whereDate('trip_hd_end_date', '<=', request()->created_date_to)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            }

            if (request()->loc_from) {
                $trip_line_id = TripLineHd::whereIn('trip_line_loc_from', request()->loc_from)->pluck('trip_line_hd_id')->toArray();
                $query = $query->whereIn('trip_line_hd_id', $trip_line_id);

                //            جاهزه
                $ready_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_line_hd_id', $trip_line_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
                $go_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_line_hd_id', $trip_line_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
                $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_line_hd_id', $trip_line_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            }

            if (request()->loc_to) {
                $trip_line_id = TripLineHd::whereIn('trip_line_loc_to', request()->loc_to)->pluck('trip_line_hd_id')->toArray();
                $query = $query->whereIn('trip_line_hd_id', $trip_line_id);

                //            جاهزه
                $ready_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_line_hd_id', $trip_line_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
                $go_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_line_hd_id', $trip_line_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
                $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_line_hd_id', $trip_line_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            }

            if (request()->trip_hd_code) {
                $query = $query->where('trip_hd_code', 'like', '%' . request()->trip_hd_code . '%');

                //            جاهزه
                $ready_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->where('trip_hd_code', 'like', '%' . request()->trip_hd_code . '%')
                    ->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
                $go_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->where('trip_hd_code', 'like', '%' . request()->trip_hd_code . '%')
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
                $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)
                    ->where('trip_hd_code', 'like', '%' . request()->trip_hd_code . '%')
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            }

            if (request()->truck_code) {
                $truck_code_id = Trucks::where('truck_name', 'like', '%' . request()->truck_code . '%')->where('company_group_id', $company->company_group_id)->first();
                $query = $query->where('truck_id', $truck_code_id->truck_id);

                //            جاهزه
                $ready_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->where('truck_id', $truck_code_id->truck_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
                $go_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->where('truck_id', $truck_code_id->truck_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
                $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)
                    ->where('truck_id', $truck_code_id->truck_id)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            }


            if (request()->trip_hd_status) {
                $query = $query->whereIn('trip_hd_status', request()->trip_hd_status);

                //            جاهزه
                $ready_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_hd_status', request()->trip_hd_status)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
                $go_trip = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_hd_status', request()->trip_hd_status)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
                $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)
                    ->whereIn('trip_hd_status', request()->trip_hd_status)
                    ->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            }


            $trips = $query->latest()->paginate();

        } else {
//            جاهزه
            $ready_trip = TripHd::where('company_group_id', $company->company_group_id)
                ->where('branch_id', session('branch')['branch_id'])
                ->select('trip_hd_id', 'trip_hd_code', 'branch_id', 'trip_hd_start_date', 'truck_id', 'driver_id',
                    'trip_line_hd_id', 'trip_hd_started_date', 'trip_hd_ended_date', 'trip_hd_status',
                    'trip_loc_transit')->whereHas('tripLine', function ($q) {
                    $q->whereHas('locFrom', function ($q2) {
                        $q2->where('branch_id', session('branch')['branch_id']);
                    });
                })->where('trip_hd_status', SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//انطلقت
            $go_trip = TripHd::where('company_group_id', $company->company_group_id)
                ->where('branch_id', session('branch')['branch_id'])
                ->select('trip_hd_id', 'trip_hd_code', 'branch_id', 'trip_hd_start_date', 'truck_id', 'driver_id',
                    'trip_line_hd_id', 'trip_hd_started_date', 'trip_hd_ended_date', 'trip_hd_status',
                    'trip_loc_transit')->whereHas('tripLine', function ($q) {
                    $q->whereHas('locFrom', function ($q2) {
                        $q2->where('branch_id', session('branch')['branch_id']);
                    });
                })->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

//       انطلقت الي
            $go_trip_to = TripHd::where('company_group_id', $company->company_group_id)
                ->where('branch_id', session('branch')['branch_id'])
                ->select('trip_hd_id', 'trip_hd_code', 'branch_id', 'trip_hd_start_date', 'truck_id', 'driver_id',
                    'trip_line_hd_id', 'trip_hd_started_date', 'trip_hd_ended_date', 'trip_hd_status',
                    'trip_loc_transit')->whereHas('tripLine', function ($q) {
                    $q->whereHas('tripLineDtF', function ($q2) {
                        $q2->whereHas('locTo', function ($q3) {
                            $q3->where('branch_id', session('branch')['branch_id']);
                        });
                    });
                })->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();

            $trips = $query->where('branch_id', session('branch')['branch_id'])->latest()->paginate();

        }


        if (session('count')) {
            $request->session()->forget('count');
        }

        $report_url_trips = Reports::where('report_code', '10401')->first()->report_url;
        return view('Trips.Trips.index', compact('companies', 'company', 'trips', 'sys_codes_location', 'report_url_trips',
            'sys_codes_status', 'ready_trip', 'go_trip', 'go_trip_to', 'data', 'ready_truck'));


    }

    public function create()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $trip_lines = TripLineHd::where('company_id', $company->company_id)->where('trip_line_status', 1)->get();

        $trucks1 = Trucks::where('company_id', $company->company_id)
            ->whereHaS('driver')
            ->where('truck_status', '!=', SystemCode::where('system_code', 82)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
            ->whereHas('trips', function ($query) use ($company) {
                $query->where('trip_hd_status', SystemCode::where('system_code', 39004)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
                    ->orWhere('trip_hd_status', SystemCode::where('system_code', 39005)
                        ->where('company_group_id', $company->company_group_id)->first()->system_code_id);
            })->get();

        $trucks2 = Trucks::where('company_id', $company->company_id)
            ->whereHaS('driver')->whereDoesntHave('trips')->get();


        $trucks = $trucks1->merge($trucks2);

//        $trip_status= SystemCode::where('sys_category_id',)->->get;
        $sys_line_type = SystemCode::where('sys_category_id', 126)
            ->where('company_group_id', $company->company_group_id)->get();

        $current_date = Carbon::now()->format('Y-m-d\TH:i');

        $launched_trips = TripHd::where('company_group_id', $company->company_group_id)->where('trip_hd_status',
            SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 39002)->first()->system_code_id)
            ->select('trip_hd_code', 'trip_hd_id')->get();

        return view('Trips.Trips.create', compact('company', 'current_date', 'trip_lines', 'trucks', 'sys_line_type',
            'launched_trips'));
    }

    public function create2(Request $request, $id)
    {
        $trip = TripHd::find($id);

        $company = session('company') ? session('company') : auth()->user()->company;
        $tripe_lineDls_from = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', $company->company_group_id)->get();
        $tripe_lineDls_to = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', $company->company_group_id)->get();
        $tripe_lineDls = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', $company->company_group_id)->get();
        // $tripe_lineDls_from = $trip->tripLine->tripLineDt->pluck('loc_from')->toArray();
        // $tripe_lineDls_to = $trip->tripLine->tripLineDt->pluck('loc_to')->toArray();
        //   $tripe_lineDls = SystemCode::whereIn('system_code_id', array_unique(array_merge($tripe_lineDls_from, $tripe_lineDls_to)))->get();
        if (session('count')) {
            $request->session()->put('count', session('count'));
        } else {
            $request->session()->put('count', 1);
        }
        return view('Trips.Trips.create_trip2', compact('trip', 'tripe_lineDls'));
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $driver_emp = Employee::find($request->driver_id);

        $last_trip_hd_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 104)->latest()->first();

//        \DB::beginTransaction();


        if (isset($last_trip_hd_serial)) {
            $last_trip_hd_serial_no = $last_trip_hd_serial->serial_last_no;
            $array_number = explode('-', $last_trip_hd_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_trip_hd_number = implode('-', $array_number);
            $last_trip_hd_serial->update(['serial_last_no' => $string_trip_hd_number]);
        } else {
            $string_trip_hd_number = 'TRP-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 104,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_trip_hd_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $trip_hd_status = SystemCode::where('system_code', 39001)->where('company_group_id', $company->company_group_id)->first();

        $trip_hd_line = TripLineHd::where('trip_line_hd_id', $request->trip_line_hd_id)->first();
        $loc_transit = TripLineDt::where('trip_line_hd_id', $trip_hd_line->trip_line_hd_id)->first()->loc_to;
        $trip_hd_fees_1 = $request->trip_hd_fees_1;

        $trip = TripHd::create([
            'trip_hd_code' => $string_trip_hd_number,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'trip_hd_date' => Carbon::now(),
            'trip_hd_start_date' => $request->trip_hd_start_date,
            'trip_hd_end_date' => $request->trip_hd_end_date,
            'truck_id' => $request->truck_id,
            'driver_id' => $request->driver_id,
            'driver_mobil' => $request->driver_mobil,
            'driver_rad_count' => $request->driver_rad_count,
            'trip_line_hd_id' => $request->trip_line_hd_id,
            'truck_meter_start' => $request->truck_meter_start,
            'truck_meter_end' => $request->truck_meter_end,
            'trip_hd_distance' => $request->trip_hd_distance,
            'trip_hd_fees_1' => $trip_hd_fees_1,
            'trip_hd_fees_2' => $request->trip_hd_fees_2 ? $request->trip_hd_fees_2 : 0,
            'trip_hd_started_date' => $request->trip_hd_started_date,
            'trip_hd_ended_date' => $request->trip_hd_ended_date,
            'trip_hd_status' => $trip_hd_status->system_code_id,
            'trip_hd_notes' => $request->trip_hd_notes,
            'created_user' => auth()->user()->user_id,
            'trip_loc_transit' => $loc_transit
        ]);

        $driver_emp->update([
            'issueNumber' => $request->issueNumber,
            'emp_private_mobile' => $request->emp_private_mobile
        ]);

        if ($request->trip_line_type == 126006) {
            $old_trip_id = $request->old_trip_id;
            $new_trip_id = $trip->trip_hd_id;
            $old_trip_hd_fees_1 = $request->old_trip_line_fess_1;
            $old_trip_hd_fees_2 = $request->old_trip_hd_fees_2;
            $trip_line_distance = $request->old_trip_line_distance;
            $trip_line_hd_id = $request->old_trip_line_hd_id;
            $this->addFirstAidTrip($old_trip_id, $new_trip_id, $old_trip_hd_fees_1, $old_trip_hd_fees_2,
                $trip_line_distance, $trip_line_hd_id);

            return redirect()->route('Trips');
        }

//        $naql_trip = new NaqlController();
//        return $naql_trip->createTrip();

//        \DB::commit();


        return redirect()->route('Trips.create2', $trip->trip_hd_id);

    }

    public function storeDt(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $tripHd = TripHd::find($request->trip_hd_id);

        if ($tripHd->tripLine->tripLineTypeT->system_code == 126005 || $tripHd->tripLine->tripLineTypeT->system_code == 126004) {
            $tripHd->trip_hd_fees_1 = $request->trip_hd_fees_1;
            $tripHd->trip_hd_fees_2 = $request->trip_hd_fees_2;
            $tripHd->save();
        }

        if ($request->waybill_id) {
            $waybill_ids = array_unique($request->waybill_id);
            foreach ($waybill_ids as $k => $waybill_id) {
                $waybill = WaybillHd::find($waybill_id);
                $tripdt = TripDt::where('trip_hd_id', $request->trip_hd_id)->latest()->first();

                if (isset($tripdt)) {
                    $last_trip_dt_serial_no = $tripdt->trip_hd_code;
                    $array_number = explode('-', $last_trip_dt_serial_no);
                    $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                    $string_trip_dt_number = implode('-', $array_number);
                } else {
                    $string_trip_dt_number = $tripHd->trip_hd_code . '-1';
                }


                TripDt::create([
                    'trip_hd_id' => $request->trip_hd_id,
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'trip_hd_code' => $string_trip_dt_number,
                    'trip_dt_serial' => $request->trip_dt_serial,
                    'trip_dt_loc_from' => $request->trip_dt_loc_from,
                    'trip_dt_loc_to' => $request->trip_dt_loc_to,
                    'waybill_transit_loc_1' => $request->trip_dt_loc_to,
                    'trip_dt_start_date' => $tripHd->trip_hd_start_date,
                    'trip_dt_end_date' => $tripHd->trip_hd_end_date,
                    'waybill_id' => $waybill_id,
                    'trip_waybill_status' => SystemCode::where('system_code', 39001)
                        ->where('company_group_id', $company->company_group_id)->first()->system_code_id,
                ]);

                if ($tripHd->tripLine->tripLineTypeT->system_code != 126005) {
                    $waybill->update([
                        'waybill_trip_id' => $tripHd->trip_hd_id,
                        'waybill_transit_loc_1' => $request->trip_dt_loc_to,
                        'waybill_truck_id' => $tripHd->truck_id
                    ]);
                }


                if ($waybill->journal_dt_id) {
                    $journal_dt = JournalDt::where('journal_dt_id', $waybill->journal_dt_id)->first();
                    $journal_dt->update(['cc_car_id' => $waybill->waybill_truck_id]);
                }

            }
        }


        $request->session()->put('count', session('count') + 1);

        return back()->with(['success' => session('count') - 1 . 'تم إضافة البيان رقم']);
    }

    public function updateStatus(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        //$trip_status = SystemCode::where('system_code_id', $request->system_code_id)->first();
        $truck_status_1 = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 80)->first();////جاهزه  ///
        $truck_status_2 = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 82)->first();////محمله  ///


        $trip = TripHd::find($request->trip_hd_id);


        if ($trip->status->system_code == 39001) {  ////  جاهزه تتحدث لانطلقت ////
            \DB::beginTransaction();
            $status_id = SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 39002)->first()->system_code_id;

            $trip->update([
                'trip_hd_status' => $status_id,
                'trip_hd_started_date' => Carbon::now(),
                'updated_user' => auth()->user()->user_id,
            ]);

            if ($trip->count_trip_dts > 0) {
                foreach ($trip->tripdts as $trip_dt) {

                    $items_id = [];

                    $waybill_status = SystemCode::where('company_group_id', $company->company_group_id)
                        ->where('system_code', 41006)->first(); ////في الطريق

                    $trip_dt->waybill->update([
                        'waybill_status' => $waybill_status->system_code_id,
                        'waybill_trip_status' => $status_id,
                        'updated_user' => auth()->user()->user_id,
                    ]);

                    $trip_dt->waybill->statusM()->attach($waybill_status->system_code_id, ['status_date' => Carbon::now()]);

                    $trip_dt->update([
                        'trip_status' => $status_id,
                        'updated_user' => auth()->user()->user_id,
                    ]);


                    if ($trip_dt->waybill->invoice) {
                        $waybill = $trip_dt->waybill;
                        $journal_dts = JournalDt::where('journal_hd_id', $waybill->invoice->journal_hd_id)->get();

                        if (!isset($journal_dts[2])) {
                            $item = SystemCode::where('system_code_id', $waybill->detailsCar->waybill_item_id)
                                ->where('company_group_id', $company->company_group_id)->first();

                            if ($item->system_code_acc_id) {
                                // $item->system_code_acc_id;
                                $sales_notes = '  قيد ايراد للفاتوره رقم ' . $waybill->invoice->invoice_no . ' بوليصة شحن ' . $waybill->waybill_code;
                                $cc_voucher_id = $waybill->invoice->invoice_id;
                                $cost_center_type_id_car = SystemCode::where('system_code', 56004)
                                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
                                $journal_status = SystemCode::where('system_code', 903)
                                    ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
                                $journal_type = SystemCode::where('system_code', 808)
                                    ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
                                $journal_dt = JournalDt::create([
                                    'company_group_id' => $company->company_group_id,
                                    'company_id' => $company->company_id,
                                    'branch_id' => session('branch')['branch_id'],
                                    'journal_type_id' => $journal_type->system_code_id,
                                    'journal_hd_id' => $waybill->invoice->journal_hd_id,
                                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                                    'journal_dt_date' => Carbon::now(),
                                    'journal_status' => $journal_status->system_code_id,
                                    'account_id' => $item->system_code_acc_id,
                                    'journal_dt_debit' => 0,
                                    'journal_dt_credit' => $waybill->waybill_total_amount - $waybill->waybill_vat_amount,
                                    'journal_dt_balance' => $waybill->waybill_total_amount - $waybill->waybill_vat_amount,
                                    'journal_user_entry_id' => auth()->user()->user_id,
                                    // 'journal_dt_notes' => 'ايراد بوليصه شحن سياره رقم' . $waybill_car->waybill_code,
                                    'journal_dt_notes' => $sales_notes,
                                    'cost_center_type_id' => $cost_center_type_id_car,
                                    'cost_center_id' => 119,
                                    'cc_voucher_id' => $cc_voucher_id,
                                    'cc_car_id' => $waybill->waybill_truck_id ? $waybill->waybill_truck_id : ''
                                ]);

                                $waybill->journal_dt_id = $journal_dt->journal_dt_id;
                                $waybill->save();
                            } else {
                                return back()->with(['error' => 'لا يوجد حساب مرتبط بنوع الشحن علي البوليصه']);
                            }
                        } else {
                            $journal_dts[2]->update(['cc_car_id' => $waybill->waybill_truck_id]);
                        }


                        $waybill->invoice->update([
                            'invoice_amount' => $waybill->waybill_total_amount,
                            'invoice_vat_amount' => $waybill->waybill_vat_amount,
                        ]);

                        $invoice_dt = InvoiceDt::where('invoice_id', $waybill->invoice->invoice_id)
                            ->where('invoice_reference_no', $waybill->waybill_code)->first();

                        if (isset($invoice_dt)) {
                            $invoice_dt->update([
                                'invoice_item_vat_amount' => $waybill->waybill_vat_amount,
                                'invoice_total_amount' => $waybill->waybill_total_amount,
                            ]);
                        }

                        $journal_controller = new JournalsController();
                        $total_amount = $waybill->invoice->invoice_amount;
                        $cc_voucher_id = $waybill->invoice->invoice_id;
                        $vat_amount = $waybill->invoice->invoice_vat_amount;
                        $sales_notes = '';
                        $items_id[] = $waybill->waybill_id;
                        $journal_controller->updateInvoiceJournal($total_amount, $vat_amount, 88, $cc_voucher_id, $items_id, $sales_notes);

                    }
                }

            }

            $trip->truck->update([
                'truck_status' => $truck_status_2->system_code_id,
                'updated_user' => auth()->user()->user_id,
                'truck_last_starting_location' => session('branch')['branch_id'],
                'truck_last_starting_date' => Carbon::now(),
                'updated_date' => Carbon::now()
            ]);

            $transaction_type = ApplicationsMenu::where('app_menu_id', 104)->first()->app_menu_id;
            $transaction_id = $trip->trip_hd_id;
            $j_add_date = $trip->trip_hd_start_date;
            $bond_car_id = $trip->truck_id;
            $customer_type = 'car';
            $bond_ref_no = $trip->trip_hd_code;
            $bond_cash = new BondsController();

            $journal_controller = new JournalsController();
            $cost_center_id = 54;
            $customer_id = $trip->truck_id;

            //return $journal_type;

            $journal_type = JournalType::where('journal_types_code', 51)
                ->where('company_group_id', $company->company_group_id)->first();

            if ($trip->trip_hd_fees_1 > 0 && isset($journal_type->tax_rate)) {
                //trip_hd_fees_1 سند صرف مصروف الطريق

                $total_amount = $trip->trip_hd_fees_1;
                $bond_notes = ' سند صرف  ديزل رحله رقم ' . $trip->trip_hd_code;
                $bond_vat_amount = ($trip->trip_hd_fees_1 / 115 * 100) * $journal_type->tax_rate;
                $bond_vat_rate = $journal_type->tax_rate;
                $payment_method = SystemCode::where('system_code', $journal_type->bond_payment_type_code)
                    ->where('company_group_id', $trip->company_group_id)->first();

                $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                    ->where('company_group_id', $company->company_group_id)->first(); ///////////من المصروفات
                $bond_account_id = $bond_doc_type->system_code_acc_id;

                $bond = $bond_cash->addCashBond($payment_method, $transaction_type, $transaction_id, '', $customer_type,
                    '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id, number_format($bond_vat_amount, 2),
                    $bond_vat_rate, $bond_car_id, $j_add_date);


                $cc_voucher_id = $bond->bond_id;
                $journal_category_id = 51;
                $journal_notes = '   سند صرف ديزل رقم' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $customer_notes = '  سند صرف  ديزل رقم ' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $cash_notes = ' ديزل سند صرف' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $message = $journal_controller->AddCashJournal(56004, $customer_id, $bond_doc_type->system_code,
                    $total_amount, number_format($bond_vat_amount, 2), $cc_voucher_id, $payment_method, '',
                    $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);

                if (isset($message)) {
                    return back()->with(['error' => $message]);
                }
            }

            $journal_type_2 = JournalType::where('journal_types_code', 52)
                ->where('company_group_id', $company->company_group_id)->first();

            if ($trip->trip_hd_fees_2 > 0 && isset($journal_type_2->tax_rate)) {
                //trip_hd_fees_2 سند صرف مكافاه الطريق

                $total_amount = $trip->trip_hd_fees_2;
                $bond_notes = ' سند صرف  رحله رقم ' . $trip->trip_hd_code;

                $bond_vat_amount = 0;
                $bond_vat_rate = 0;
                $payment_method = SystemCode::where('system_code', $journal_type_2->bond_payment_type_code)
                    ->where('company_group_id', $trip->company_group_id)->first();
                $bond_doc_type = SystemCode::where('system_code_id', $journal_type_2->bond_type_id)
                    ->where('company_group_id', $company->company_group_id)->first(); ///////////من المصروفات
                $bond_account_id = $bond_doc_type->system_code_acc_id;

                $bond = $bond_cash->addCashBond($payment_method, $transaction_type, $transaction_id, '', $customer_type,
                    '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id, $bond_vat_amount,
                    $bond_vat_rate, $bond_car_id, $j_add_date);


                $cc_voucher_id = $bond->bond_id;
                $journal_category_id = 52;
                $journal_notes = ' سند صرف رقم' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $customer_notes = 'سند صرف رقم ' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $cash_notes = 'سند صرف رقم' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $message = $journal_controller->AddCashJournal(56004, $customer_id, $bond_doc_type->system_code,
                    $total_amount, $bond_vat_amount, $cc_voucher_id, $payment_method, '',
                    $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);

                if (isset($message)) {
                    return back()->with(['error' => $message]);
                }
            }

            $journal_type_53 = JournalType::where('journal_types_code', 53)
                ->where('company_group_id', $company->company_group_id)->first();

            $journal_type_54 = JournalType::where('journal_types_code', 54)
                ->where('company_group_id', $company->company_group_id)->first();


            if (isset($journal_type_53) && $trip->trip_hd_fees_1 > 0) {
                $journal_category_id = 53;
                $journal_notes = 'قيد استحقاق ديزل' . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $amount_total = $trip->trip_hd_fees_1;
                $cc_voucher_id = $trip->trip_hd_id;

                if ($amount_total > 0) {
                    $journal_controller->AddEntitlementJournal(56004, $amount_total, $cc_voucher_id
                        , $journal_category_id, $cost_center_id, $journal_notes, $j_add_date);
                }
            }

            if (isset($journal_type_54) && $trip->trip_hd_fees_2 > 0) {
                $journal_category_id = 54;
                $journal_notes = 'قيد استحقاق مصروف ' . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $amount_total = $trip->trip_hd_fees_2;
                $cc_voucher_id = $trip->trip_hd_id;
                $journal_controller->AddEntitlementJournal(56004, $amount_total, $cc_voucher_id
                    , $journal_category_id, $cost_center_id, $journal_notes, $j_add_date);

            }

            \DB::commit();

            return response()->json(['status' => 200, 'message' => 'تم انطلاق الرحله بنجاح']);
        } else if (($trip->status->system_code == 39002 || $trip->status->system_code == 39003) && $trip->trip_loc_transit != $trip->tripLine->trip_line_loc_to) {///  اتطلقت تتحدث لوصلت  ///

            \DB::beginTransaction();

            $status_arrived = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 39003)->first()->system_code_id; //للرحله

            $trip->update([
                'trip_hd_status' => $status_arrived,
                'trip_hd_started_date' => Carbon::now(),
                'updated_user' => auth()->user()->user_id,
            ]);

            $waybill_status = SystemCode::where('system_code', 41007)->where('company_group_id', $company->company_group_id)->first(); ////لوصلت

            if ($trip->count_trip_dts > 0) {
                foreach ($trip->tripdts as $trip_dt) {
                    if ($trip_dt->waybill->status->system_code == 41006
                        && $trip_dt->waybill->waybill_loc_to == $trip->trip_loc_transit) { //////////الحاله محمله للبوليصه

                        $trip_dt->waybill->waybill_status = $waybill_status->system_code_id;
                        $trip_dt->waybill->updated_user = auth()->user()->user_id;
                        $trip_dt->waybill->save();

                        $category = SMSCategory::where('company_id', $trip->company_id)->where('sms_name_ar', 'sms delivary car')->first();

                        if (isset($category) && $category->sms_is_sms) {
                            $receiver_mob = $trip_dt->waybill->waybill_receiver_mobile;
                            $mobNo = '+966' . substr($receiver_mob, 1);
                            $parm1 = $trip_dt->waybill->waybill_code;
                            $parm2 = $trip_dt->waybill->locTo->system_code_name_ar;
                            $file_name = 'Trip' . $trip->trip_hd_code . '.pdf';
                            $url = asset('Waybills/' . $file_name);
                            $shortUrl = SMS\smsQueueController::getShortUrl($url);
                            $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, $parm2, null, null, $shortUrl);
                        }

                        $trip_dt->waybill->statusM()->attach($waybill_status->system_code_id, ['status_date' => Carbon::now()]);

                    } else {
                        if ($trip->tripLine->tripLineDt->count() == 1) {
                            $trip_new_location = TripLineDt::where('trip_line_hd_id', $trip->trip_line_hd_id)->first();
                        } else {
                            $trip_new_location = TripLineDt::where('trip_line_hd_id', $trip->trip_line_hd_id)
                                ->where('loc_from', $trip->trip_loc_transit)->first();
                        }

                        if ($trip->tripLine->trip_line_loc_to == $trip->trip_loc_transit) {
                            $trip->trip_loc_transit = $trip->tripLine->trip_line_loc_to;
                            $trip->save();
                        } else {
                            $trip->trip_loc_transit = $trip_new_location->loc_to;
                            $trip->save();
                        }

                    }
                }

                if ($trip->tripLine->tripLineDt->count() > 0) {
                    if ($trip->trip_loc_transit != $trip->tripLine->trip_line_loc_to) {
                        if ($trip->tripLine->tripLineDt->count() == 1) {
                            $trip_new_location = TripLineDt::where('trip_line_hd_id', $trip->trip_line_hd_id)->first();
                        } else {
                            $trip_new_location = TripLineDt::where('trip_line_hd_id', $trip->trip_line_hd_id)
                                ->where('loc_from', $trip->trip_loc_transit)->first();
                        }

                        $trip->trip_loc_transit = $trip_new_location->loc_to;
                        $trip->save();
                    }
                }

            }

            if ($trip->count_trip_dts == 0) {
                $trip_new_location = TripLineDt::where('trip_line_hd_id', $trip->trip_line_hd_id)
                    ->where('loc_from', $trip->trip_loc_transit)->first();
                $trip->trip_loc_transit = $trip_new_location->loc_to;
                $trip->save();
            }

            \DB::commit();

            return response()->json(['status' => 200, 'message' => 'تم وصول البوالص المطابقه للفرع بنجاح ']);

        } else if (($trip->status->system_code == 39002 || $trip->status->system_code == 39003) && $trip->trip_loc_transit == $trip->tripLine->trip_line_loc_to) {  ////  وصلت تتحدث لانتهت ////
            DB::beginTransaction();
            $status_id = SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 39004)->first()->system_code_id;

            $trip->update([
                'trip_hd_status' => $status_id,
                'trip_hd_ended_date' => Carbon::now(),
                'updated_user' => auth()->user()->user_id
            ]);

            $trip->truck->update([
                'truck_status' => $truck_status_1->system_code_id,
                'updated_user' => auth()->user()->user_id,
                'truck_last_end_location' => session('branch')['branch_id'],
                'truck_last_end_date' => Carbon::now(),
                'updated_date' => Carbon::now()
            ]);

            $waybill_status = SystemCode::where('system_code', 41007)->where('company_group_id', $company->company_group_id)->first(); ////لوصلت
            if ($trip->count_trip_dts > 0) {
                foreach ($trip->tripdts as $trip_dt) {

                    $close_waybill = new NaqlAPIController();
                    $data = $close_waybill->closeWaybillTrip($trip_dt->waybill);
                    if ($data['statusCode'] == 200) {
                        $trip_dt->waybill->status_id = 3;
                        $trip_dt->waybill->save();
                    }


                    if ($trip_dt->waybill->status->system_code == 41006 ////41006
                        && $trip_dt->waybill->waybill_loc_to == $trip->trip_loc_transit) { //////////الحاله محمله للبوليصه

                        $trip_dt->waybill->waybill_status = $waybill_status->system_code_id;
                        $trip_dt->waybill->updated_user = auth()->user()->user_id;
                        $trip_dt->waybill->save();

                        $trip_dt->waybill->statusM()->attach($waybill_status->system_code_id, ['status_date' => Carbon::now()]);

                        $category = SMSCategory::where('company_id', $trip->company_id)->where('sms_name_ar', 'sms delivary car')->first();


                        if (isset($category) && $category->sms_is_sms && $trip_dt->waybill->customer->cus_type->system_code == 538) {
                            $receiver_mob = $trip_dt->waybill->waybill_receiver_mobile;
                            $first_num = substr($receiver_mob, 0, 1);
                            if ($first_num == 0) {
                                $mobNo = '+966' . substr($receiver_mob, 0);
                            } else {
                                $mobNo = '+966' . $receiver_mob;
                            }
                            $parm1 = $trip_dt->waybill->waybill_code;
                            $parm2 = $trip_dt->waybill->locTo->system_code_name_ar;
                            $shortUrl = $trip_dt->waybill->locTo->system_code_url;
                            // $shortUrl = SMS\smsQueueController::getShortUrl($url);
                            $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, $parm2, null, null, $shortUrl);
                        }

                    }
                }
            }

            $journal_type_55 = JournalType::where('journal_types_code', 55)
                ->where('company_group_id', $company->company_group_id)->first();

            $journal_type_56 = JournalType::where('journal_types_code', 56)
                ->where('company_group_id', $company->company_group_id)->first();

            // return [$journal_type_55, $journal_type_56];

            $transaction_type = ApplicationsMenu::where('app_menu_id', 104)->first()->app_menu_id;
            $transaction_id = $trip->trip_hd_id;
            $j_add_date = $trip->trip_hd_start_date;
            $bond_car_id = $trip->truck_id;
            $customer_type = 'car';
            $bond_ref_no = $trip->trip_hd_code;
            $bond_cash = new BondsController();

            $journal_controller = new JournalsController();
            $cost_center_id = 54;
            $trip_id = $trip->trip_hd_id;

            if (isset($journal_type_55) && $trip->trip_hd_fees_1 > 0) {
                $journal_type = $journal_type_55;

                $total_amount = $trip->trip_hd_fees_1;
                $bond_notes = ' سند صرف  ديزل رحله رقم ' . $trip->trip_hd_code;
                $bond_vat_amount = ($trip->trip_hd_fees_1 / 115 * 100) * $journal_type->tax_rate;
                $bond_vat_rate = $journal_type->tax_rate;

                $payment_method = SystemCode::where('system_code', $journal_type->bond_payment_type_code)
                    ->where('company_group_id', $trip->company_group_id)->first();

                $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                    ->where('company_group_id', $company->company_group_id)->first(); ///////////من المصروفات

                $bond_account_id = $bond_doc_type->system_code_acc_id;

                $bond = $bond_cash->addCashBond($payment_method, $transaction_type, $transaction_id, '', $customer_type,
                    '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id, number_format($bond_vat_amount, 2),
                    $bond_vat_rate, $bond_car_id, $j_add_date);

                $cc_voucher_id = $bond->bond_id;
                $journal_notes = '   سند صرف رقم' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $journal_controller->AddEntitlement2Journal(56005, $total_amount, $cc_voucher_id, $trip_id
                    , 55, $cost_center_id, $journal_notes, $j_add_date);

            }

            if (isset($journal_type_56) && $trip->trip_hd_fees_2 > 0) {
                $journal_type = $journal_type_56;
                $payment_method = SystemCode::where('system_code', $journal_type->bond_payment_type_code)
                    ->where('company_group_id', $trip->company_group_id)->first();
                $total_amount = $trip->trip_hd_fees_2;
                $bond_notes = ' سند صرف  رحله رقم ' . $trip->trip_hd_code;
                $bond_vat_amount = 0;
                $bond_vat_rate = 0;

                $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                    ->where('company_group_id', $company->company_group_id)->first(); ///////////من المصروفات

                $bond_account_id = $bond_doc_type->system_code_acc_id;

                $bond = $bond_cash->addCashBond($payment_method, $transaction_type, $transaction_id, '', $customer_type,
                    '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id, $bond_vat_amount,
                    $bond_vat_rate, $bond_car_id, $j_add_date);

                $cc_voucher_id = $bond->bond_id;

                $journal_notes = '   سند صرف رقم' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                $journal_controller->AddEntitlement2Journal(56005, $total_amount, $cc_voucher_id, $trip_id
                    , 56, $cost_center_id, $journal_notes, $j_add_date);

            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => 'تم انتهاء الرحله']);
        }

    }

    public function confirmUpdate(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $company_branches_names = $company->branches->pluck('branch_name_ar')->toArray();
        $trip = TripHd::find($request->trip_hd_id);
        $status_id = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 39003)->first()->system_code_id; ////وصلت

        if ($trip->count_trip_dts > 0) {
            foreach ($trip->tripdts as $tripDt) {
                $trip_dt_branches_names[] = $tripDt->waybill->locTo->system_code_name_ar;

            }

            $diff_array = array_diff($trip_dt_branches_names, $company_branches_names);

            foreach ($trip->tripdts as $tripDt) {
                if (in_array($tripDt->waybill->locTo->system_code_name_ar, $diff_array)) {
                    $tripDt->update([
                        'trip_waybill_status' => $status_id
                    ]);

                    $tripDt->waybill->waybill_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 41007)->first()->system_code_id;
                    $tripDt->waybill->waybill_trip_status = $status_id;
                    $tripDt->waybill->updated_user = auth()->user()->user_id;
                    $tripDt->waybill->save();

                    $tripDt->waybill->statusM()->attach(SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 41007)->first()->system_code_id, ['status_date' => Carbon::now()]);

                }

                $tripDt_status = SystemCode::where('system_code_id', $tripDt->trip_waybill_status)->first();
                $trip_dt_statuses_2[] = $tripDt_status->system_code;
            }

            if (!in_array("39002", $trip_dt_statuses_2) && !in_array("39001", $trip_dt_statuses_2)) {
                $trip->update([
                    'trip_hd_status' => $status_id
                ]);
            }
        } else {
            $trip->update([
                'trip_hd_status' => $status_id
            ]);
        }


        return response()->json(['status' => 200, 'message' => 'تم التحديث بنجاح']);
    }

    public function edit($id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $trip = TripHd::find($id);
        $trip_car_dt = TripDt::where('trip_hd_id', $id)->get();
        $tripe_lineDls = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', $company->company_group_id)->get();

        $bonds_cash = Bond::where('bond_type_id', 2)->where('bond_ref_no', $trip->trip_hd_code)->latest()->get();

        if (session('branch')['branch_id'] == $trip->branch_id) {
            $trip_statuses = SystemCode::where('sys_category_id', 39)->where('company_group_id', $company->company_group_id)
                ->whereIn('system_code', [39001, 39005])->get();
        } else {
            $trip_statuses = SystemCode::where('sys_category_id', 39)->where('company_group_id', $company->company_group_id)
                ->whereIn('system_code', [39001])->get();
        }


        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $notes = Note::where('transaction_id', $trip->trip_hd_id)->where('app_menu_id', 104)->get();
        $note = Note::where('transaction_id', $trip->trip_hd_id)->where('app_menu_id', 104)->latest()->first();

        $journal_types = JournalType::whereIn('journal_types_code', [53, 54, 55, 56])->where('company_group_id', $company->company_group_id)
            ->pluck('journal_types_id')->toArray();

        $entitlement_journals = JournalHd::whereIn('journal_category_id', $journal_types)
            ->whereHas('journalDetails', function ($q) use ($trip) {
                $q->where('cc_voucher_id', $trip->trip_hd_id);
            })->latest()->get();


        $trip_lines = TripLineHd::where('company_id', $company->company_id)->where('trip_line_status', 1)
            ->latest()->get();

        $user_edit_role = UsersPermissionsRol::where('user_id', auth()->user()->user_id)
            ->where('rols_id', 12)->first();

        $edit_flag = 0;
        if (isset($user_edit_role)) {
            $edit_flag = 1;
        }

        if (isset($note)) {
            return view('Trips.Trips.edit', compact('trip', 'tripe_lineDls', 'bonds_cash', 'edit_flag',
                'trip_statuses', 'payment_methods', 'notes', 'note', 'trip_car_dt', 'entitlement_journals', 'trip_lines'));
        } else {
            return view('Trips.Trips.edit', compact('trip', 'tripe_lineDls', 'bonds_cash', 'edit_flag',
                'trip_statuses', 'payment_methods', 'notes', 'trip_car_dt', 'entitlement_journals', 'trip_lines'));
        }


    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;

        $trip_hd = TripHd::where('trip_hd_id', $id)->first();
        $driver_emp = Employee::find($request->driver_id);


        $now = new DateTime();

        if ($request->trip_hd_fees_1 > 0 && $request->trip_hd_fees_1 != $trip_hd->trip_hd_fees_1) {
            $notes = 'تم تغيير الديزل من ' . $trip_hd->trip_hd_fees_1 . ' الي ' . $request->trip_hd_fees_1 . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 104,
                'transaction_id' => $trip_hd->trip_hd_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        if ($request->trip_hd_fees_2 > 0 && $request->trip_hd_fees_2 != $trip_hd->trip_hd_fees_2) {
            $notes = 'تم تغيير الحافز من ' . $trip_hd->trip_hd_fees_2 . ' الي ' . $request->trip_hd_fees_2 . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 104,
                'transaction_id' => $trip_hd->trip_hd_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        $trip_hd->update([
            'trip_hd_status' => $request->trip_hd_status ? $request->trip_hd_status : $trip_hd->trip_hd_status,
            'trip_hd_fees_1' => $request->trip_hd_fees_1 > 0 ? $request->trip_hd_fees_1 : $trip_hd->trip_hd_fees_1,
            'trip_hd_fees_2' => $request->trip_hd_fees_2 > 0 ? $request->trip_hd_fees_2 : $trip_hd->trip_hd_fees_2,
        ]);


        if ($request->trip_line_hd_id) {
            if ($request->trip_line_hd_id != $trip_hd->trip_line_hd_id) {
                $this->updateTripLineHd($trip_hd->trip_hd_id, $request->trip_hd_fees_1, $request->trip_hd_fees_2);
                $loc_transit = TripLineDt::where('trip_line_hd_id', $request->trip_line_hd_id)->first()->loc_to;
                $trip_hd->trip_line_hd_id = $request->trip_line_hd_id;
                $trip_hd->trip_loc_transit = $loc_transit;
                $trip_hd->save();
            }
        }

        $trip_status_5 = SystemCode::where('sys_category_id', 39)->where('company_group_id', $company->company_group_id)
            ->where('system_code_id', $request->trip_hd_status)->first();

        if (isset($trip_status_5->system_code) == 39005) {
            $truck_status_5 = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', 80)->first();////جاهزه  ///

            $trip_hd->truck->update([
                'truck_status' => $truck_status_5->system_code_id,
                'updated_user' => auth()->user()->user_id,
                'truck_last_starting_location' => session('branch')['branch_id'],
                'truck_last_starting_date' => Carbon::now(),
                'updated_date' => Carbon::now()
            ]);
        }

        $driver_emp->update([
            'issueNumber' => $request->issueNumber,
            'emp_private_mobile' => $request->driver_mobil
        ]);

        if ($request->notes_id) {
            $note = Note::where('notes_id', $request->notes_id)->first();
            $note->update(['notes_data' => $request->notes_data]);
        }

        $tripHd = TripHd::find($request->trip_hd_id);
        $naql_controller = new NaqlAPIController();

        if ($request->trip_dt_id) {
            $trip_dt_id_array = array_filter($request->trip_dt_id);

            foreach ($request->waybill_id_old as $k => $waybill_id) {

                $waybill = WaybillHd::where('waybill_id', $waybill_id)->first();

                if (isset($trip_dt_id_array[$k])) {

                    $tripDt = TripDt::where('trip_dt_id', $request->trip_dt_id[$k])->first();

                    if ($tripDt->waybill_id != $waybill_id) {
                        $data_cancel = $naql_controller->cancelWaybill($waybill);

                        if ($data_cancel['statusCode'] == 200) {
                            $waybill->update([
                                'http_status' => null,
                                'waybillId' => null,
                                'trip_id' => null
                            ]);
                        }

                        $data = $naql_controller->addWaybillToTrip($trip_hd, $waybill);

                        if ($data['statusCode'] == 200) {
                            $waybill->update([
                                'http_status' => 200,
                                'waybillId' => $data['body']->waybillId,
                                'trip_id' => $trip_hd->trip_id
                            ]);
                        }
                    }

                    $tripDt->update([
                        'trip_dt_loc_from' => $request->trip_dt_loc_from,
                        'trip_dt_loc_to' => $request->trip_dt_loc_to,
                        'waybill_transit_loc_1' => $request->trip_dt_loc_to,
                        'waybill_id' => $waybill_id,
                        'trip_waybill_status' => $waybill->waybill_status,
                    ]);

                } else {

                    $tripdt = TripDt::where('trip_hd_id', $request->trip_hd_id)->latest()->first();

                    if (isset($tripdt)) {
                        $last_trip_dt_serial_no = $tripdt->trip_hd_code;
                        $array_number = explode('-', $last_trip_dt_serial_no);
                        $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                        $string_trip_dt_number = implode('-', $array_number);
                    } else {
                        $string_trip_dt_number = $tripHd->trip_hd_code . '-1';
                    }

                    TripDt::create([
                        'trip_hd_id' => $request->trip_hd_id,
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'trip_hd_code' => $string_trip_dt_number,
                        'trip_dt_serial' => $request->trip_dt_serial,
                        'trip_dt_loc_from' => $request->trip_dt_loc_from,
                        'trip_dt_loc_to' => $request->trip_dt_loc_to,
                        'waybill_transit_loc_1' => $request->trip_dt_loc_to,
                        'trip_dt_start_date' => $tripHd->trip_hd_start_date,
                        'trip_dt_end_date' => $tripHd->trip_hd_end_date,
                        'waybill_id' => $waybill_id,
                        'trip_waybill_status' => $waybill->waybill_status,

                    ]);


                    $data = $naql_controller->addWaybillToTrip($trip_hd, $waybill);

                    if ($data['statusCode'] == 200) {
                        $waybill->update([
                            'http_status' => 200,
                            'waybillId' => $data['body']->waybillId,
                            'trip_id' => $trip_hd->trip_id
                        ]);
                    }

                }

                if ($waybill->journal_dt_id) {
                    $journal_dt = JournalDt::where('journal_dt_id', $waybill->journal_dt_id)->first();
                    $journal_dt->update(['cc_car_id' => $waybill->truck_id]);
                }

            }
        }

        //  new waybills
        if (isset($request->waybill_id)) {
            if (count($request->waybill_id) > 0) {
                $tripdt = TripDt::where('trip_hd_id', $request->trip_hd_id)->latest()->first();

                if (isset($tripdt)) {
                    $trip_dt_serial = $tripdt->trip_dt_serial + 1;
                } else {
                    $trip_dt_serial = 1;
                }
                $waybill_ids = array_unique($request->waybill_id);

                foreach ($waybill_ids as $k => $waybill_id) {

                    $waybill = WaybillHd::find($waybill_id);

                    $tripHd = TripHd::find($request->trip_hd_id);

                    if (isset($tripdt)) {
                        $last_trip_dt_serial_no = $tripdt->trip_hd_code;
                        $array_number = explode('-', $last_trip_dt_serial_no);
                        $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                        $string_trip_dt_number = implode('-', $array_number);
                    } else {
                        $string_trip_dt_number = $tripHd->trip_hd_code . '-1';
                    }

                    TripDt::create([
                        'trip_hd_id' => $request->trip_hd_id,
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'trip_hd_code' => $string_trip_dt_number,
                        'trip_dt_serial' => $trip_dt_serial,
                        'trip_dt_loc_from' => $request->trip_dt_loc_from,
                        'trip_dt_loc_to' => $request->trip_dt_loc_to,
                        'waybill_transit_loc_1' => $request->trip_dt_loc_to,
                        'trip_dt_start_date' => $tripHd->trip_hd_start_date,
                        'trip_dt_end_date' => $tripHd->trip_hd_end_date,
                        'waybill_id' => $waybill_id,
                        'trip_waybill_status' => $waybill->waybill_status,
                    ]);

                    $waybill->update([
                        'waybill_trip_id' => $tripHd->trip_hd_id,
                        'waybill_truck_id' => $tripHd->truck_id,
                        'waybill_transit_loc_1' => $request->trip_dt_loc_to,
                    ]);

                    DB::commit();
                    $data = $naql_controller->addWaybillToTrip($trip_hd, $waybill);


                    if ($data['statusCode'] == 200) {
                        $waybill->update([
                            'http_status' => 200,
                            'waybillId' => $data['body']->waybillId,
                            'trip_id' => $trip_hd->trip_id
                        ]);
                    }

                }
            }
        }

        DB::commit();

        return back()->with(['success' => 'تم التعديل']);

    }


//////////////////////API functions
    public function getTripDts()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $trip_dts = TripDt::where('trip_hd_id', request()->trip_hd_id)
            ->where('trip_dt_serial', request()->trip_dt_serial)->get();


        if (count($trip_dts) > 0) {
            $trip_loc_from = $trip_dts[0]->trip_dt_loc_from;
            $trip_loc_to = $trip_dts[0]->trip_dt_loc_to;

            $waybills1 = DB::table('waybill_hd')
                ->where('company_id', $company->company_id)->where('waybill_type_id', 4)
                ->where('waybill_transit_loc_1', $trip_loc_to)->get();
            $waybills2 = DB::table('waybill_hd')->where('company_id', $company->company_id)->where('waybill_type_id', 4)
                ->where('waybill_transit_loc_1', $trip_loc_from)->get();

            $trip_dts = DB::table('Trip_dt')
                ->where('Trip_dt.trip_hd_id', '=', request()->trip_hd_id)
                ->where('trip_dt_serial', request()->trip_dt_serial)
                ->join('waybill_dt', 'Trip_dt.waybill_id', '=', 'waybill_dt.waybill_hd_id')
                ->join('waybill_hd', function ($join) {
                    $join->on('Trip_dt.waybill_id', '=', 'waybill_hd.waybill_id');
                })
                ->join('customers', 'waybill_hd.customer_id', '=', 'customers.customer_id')
                ->leftJoin('system_codes as loc_to', 'waybill_hd.waybill_loc_to', '=', 'loc_to.system_code_id')
                ->leftJoin('system_codes as loc_from', 'waybill_hd.waybill_loc_from', '=', 'loc_from.system_code_id')
                ->leftJoin('system_codes as loc_transit', 'waybill_hd.waybill_transit_loc_1', '=', 'loc_transit.system_code_id')
                // ->leftJoin('system_codes as payment', 'waybill_hd.waybill_payment_method', '=', 'payment.system_code')
                ->leftJoin('system_codes as payment', function ($q) {
                    $q->on('payment.system_code', '=', 'waybill_hd.waybill_payment_method');
                    $q->on('payment.company_group_id', '=', 'waybill_hd.company_group_id');
                })
                ->select('Trip_dt.trip_dt_id', 'loc_to.system_code_name_ar as loc_to_name', 'loc_to.system_code_id as loc_to_id',
                    'loc_from.system_code_name_ar as loc_from_name', 'Trip_dt.branch_id',
                    'loc_transit.system_code_name_ar as loc_transit_name', 'payment.system_code_name_ar as payment_method_name_ar',
                    'waybill_hd.waybill_id', 'waybill_hd.waybill_code', 'waybill_dt.waybill_car_plate', 'waybill_dt.waybill_car_desc',
                    'waybill_dt.waybill_car_owner', 'customers.customer_name_full_ar', 'waybill_hd.waybill_total_amount', 'waybill_hd.waybill_fees_total')
                ->get();


            return response()->json(['status' => 200, 'data' => $trip_dts,
                'waybills' => $waybills1->merge($waybills2), 'trip_loc_from' => $trip_loc_from,
                'trip_loc_to' => $trip_loc_to]);
        } else {
            return response()->json(['status' => 500, 'message' => 'لا يوجد بيان بهذا الرقم']);
        }

    }

    public function deleteTripDt()
    {
        $trip_td = TripDt::where('trip_dt_id', request()->trip_dt_id)->first();
        if (isset($trip_td)) {
            $waybill_status = SystemCode::where('system_code', 41004)->where('company_group_id', $trip_td->company_group_id)->first();
            $trip_td->waybill->waybill_transit_loc_1 = $trip_td->trip->tripLine->trip_line_loc_from;
            $trip_td->waybill->waybill_trip_id = null;
            $trip_td->waybill->waybill_truck_id = null;
            $trip_td->waybill->waybill_status = $waybill_status->system_code_id;
            $trip_td->waybill->save();

            if ($trip_td->trip->tripLine->tripLineTypeT->system_code == 126004) {
                $trip_td->trip->trip_hd_fees_1 = $trip_td->trip->trip_hd_fees_1 - $trip_td->waybill->waybill_fees_total;
                $trip_td->trip->save();
            }

            if ($trip_td->trip->trip_id) {
                $naql_controller = new NaqlAPIController();
                $data_cancel = $naql_controller->cancelWaybill($trip_td->waybill);

                if ($data_cancel['statusCode'] == 200) {
                    $trip_td->waybill->update([
                        'http_status' => null,
                        'waybillId' => null,
                        'trip_id' => null,
                        'cancel_status' => 200
                    ]);
                }
            }

            $trip_td->delete();
        }

        return response()->json(['message' => 'تم الحذف', 'trip_hd_fees_1' => $trip_td->trip->trip_hd_fees_1]);
    }

    public function getTruck()
    {
        $truck = Trucks::find(request()->truck_id);
        $status = $truck->status;
        $driver = $truck->driver;
        $truck_type = $truck->truckType;

        return response()->json(['data' => $truck, 'status' => $status, 'driver' => $driver,
            'truck_type' => $truck_type]);

    }

    public function getTripLine()
    {
        $trip_line = TripLineHd::find(request()->trip_line_hd_id);
        return response()->json(['data' => $trip_line]);
    }

    public function getOldTripLines()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $trip_lines = TripLineHd::where('company_id', $company->company_id)->where('trip_line_status', 1)
            ->select('trip_line_hd_id', 'trip_line_desc')->get();
        return response()->json(['data' => $trip_lines]);
    }

    public function getTripLines()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $trip_lines = [];
        $trip_line = SystemCode::where('system_code', request()->trip_line_type)->where('company_group_id', $company->company_group_id)
            ->first();


        if (request()->branch_id == 0) {
            $trip_lines = TripLineHd::where('trip_line_type', $trip_line->system_code_id)
                ->where('truck_type', request()->truck_type_id)
                ->where('company_id', $company->company_id)->where('trip_line_status', 1)
                ->latest()->get();
        } elseif (request()->branch_id != 0) {
            if (request()->trip_line_type && request()->truck_type_id) {
                $trip_lines = TripLineHd::where('trip_line_type', $trip_line->system_code_id)
                    ->where('truck_type', request()->truck_type_id)
                    ->where('company_id', $company->company_id)->where('trip_line_status', 1)
                    ->whereHas('locFrom', function ($query) {
                        $query->where('branch_id', request()->branch_id);
                    })->latest()->get();

            } else {
                $trip_lines = TripLineHd::where('company_id', $company->company_id)->where('trip_line_status', 1)
                    ->whereHas('locFrom', function ($query) {
                        $query->where('branch_id', request()->branch_id);
                    })->latest()->get();
            }

        }

        return response()->json(['data' => $trip_lines]);
    }

    public function getArrivalDate()
    {
        $start_date = Carbon::parse(request()->trip_hd_start_date);
        $end_date = $start_date->addHours(request()->trip_line_time)->format('Y-m-d\TH:i');
        return response()->json(['data' => $end_date]);

    }

    public function getWaybillHd()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $waybill_status = SystemCode::whereIn('system_code', [41001, 41008, 41005])->where('company_group_id', $company->company_group_id)
            ->pluck('system_code_id')->toArray();

        $trip_hd = TripHd::find(request()->trip_id);

        if ($trip_hd->tripLine->tripLineTypeT->system_code == 126005) {
            $waybill_cars = DB::table('waybill_hd')
                ->join('waybill_dt', 'waybill_hd.waybill_id', '=', 'waybill_dt.waybill_hd_id')
                // ->where('waybill_dt.waybill_item_id', '!=', SystemCode::where('system_code', 64006)
                //     ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
                ->leftJoin('system_codes as loc_to', 'waybill_hd.waybill_loc_to', '=', 'loc_to.system_code_id')
                ->leftJoin('system_codes as loc_from', 'waybill_hd.waybill_loc_from', '=', 'loc_from.system_code_id')
                ->leftJoin('system_codes as loc_transit', 'waybill_hd.waybill_transit_loc_1', '=', 'loc_transit.system_code_id')
                ->join('customers', 'waybill_hd.customer_id', '=', 'customers.customer_id')
                ->select('loc_to.system_code_name_ar as loc_to_name', 'loc_to.system_code_id as loc_to_id',
                    'loc_from.system_code_name_ar as loc_from_name',
                    'loc_transit.system_code_name_ar as loc_transit_name',
                    'waybill_hd.waybill_id', 'waybill_hd.waybill_code', 'waybill_dt.waybill_car_plate', 'waybill_dt.waybill_car_desc',
                    'waybill_dt.waybill_car_owner', 'customers.customer_name_full_ar', 'waybill_hd.waybill_total_amount', 'waybill_hd.waybill_fees_total'
                    , 'waybill_hd.waybill_trip_id')
                ->where(function ($q) {
                    return $q->where('waybill_loc_from', request()->loc_from)
                        ->orWhere('waybill_loc_to', request()->loc_to);
                })
                ->where('waybill_type_id', 4)->where(function ($query) use ($trip_hd) {
                    return $query->where('waybill_trip_id', '!=', $trip_hd->trip_hd_id)
                        ->orWhere('waybill_trip_id', '=', Null);
                })->whereNotIn('waybill_status', $waybill_status)->get();

        } else {
            $waybill_cars = DB::table('waybill_hd')
                ->join('waybill_dt', 'waybill_hd.waybill_id', '=', 'waybill_dt.waybill_hd_id')
                // ->where('waybill_dt.waybill_item_id', '!=', SystemCode::where('system_code', 64006)
                //     ->where('company_group_id', $company->company_group_id)->first()->system_code_id)
                ->leftJoin('system_codes as loc_to', 'waybill_hd.waybill_loc_to', '=', 'loc_to.system_code_id')
                ->leftJoin('system_codes as loc_from', 'waybill_hd.waybill_loc_from', '=', 'loc_from.system_code_id')
                ->leftJoin('system_codes as loc_transit', 'waybill_hd.waybill_transit_loc_1', '=', 'loc_transit.system_code_id')
                ->join('customers', 'waybill_hd.customer_id', '=', 'customers.customer_id')
                ->select('loc_to.system_code_name_ar as loc_to_name', 'loc_to.system_code_id as loc_to_id',
                    'loc_from.system_code_name_ar as loc_from_name',
                    'loc_transit.system_code_name_ar as loc_transit_name',
                    'waybill_hd.waybill_id', 'waybill_hd.waybill_code', 'waybill_dt.waybill_car_plate', 'waybill_dt.waybill_car_desc',
                    'waybill_dt.waybill_car_owner', 'customers.customer_name_full_ar', 'waybill_hd.waybill_total_amount', 'waybill_hd.waybill_fees_total'
                    , 'waybill_hd.waybill_trip_id')->where('waybill_type_id', 4)->where('waybill_transit_loc_1', request()->loc_from)
                ->where(function ($query) use ($trip_hd) {
                    return $query->where('waybill_trip_id', '!=', $trip_hd->trip_hd_id)
                        ->orWhere('waybill_trip_id', '=', Null);
                })->whereNotIn('waybill_status', $waybill_status)->get();
        }

        return response()->json(['data' => $waybill_cars]);

    }

    public
    function getWaybillData()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $waybill_car = DB::table('waybill_hd')
            ->where('waybill_hd.waybill_id', request()->waybill_id)
            ->join('waybill_dt', 'waybill_hd.waybill_id', '=', 'waybill_dt.waybill_hd_id')
            ->leftJoin('system_codes as loc_to', 'waybill_hd.waybill_loc_to', '=', 'loc_to.system_code_id')
            ->leftJoin('system_codes as loc_from', 'waybill_hd.waybill_loc_from', '=', 'loc_from.system_code_id')
            ->leftJoin('system_codes as loc_transit', 'waybill_hd.waybill_transit_loc_1', '=', 'loc_transit.system_code_id')
            ->join('customers', 'waybill_hd.customer_id', '=', 'customers.customer_id')
            ->select('loc_to.system_code_name_ar as loc_to_name', 'loc_to.system_code_id as loc_to_id',
                'loc_from.system_code_name_ar as loc_from_name',
                'loc_transit.system_code_name_ar as loc_transit_name',
                'waybill_hd.waybill_id', 'waybill_hd.waybill_code', 'waybill_dt.waybill_car_plate', 'waybill_dt.waybill_car_desc',
                'waybill_dt.waybill_car_owner', 'customers.customer_name_full_ar', 'waybill_hd.waybill_total_amount', 'waybill_hd.waybill_fees_total')->first();

        return response()->json(['waybill_car' => $waybill_car]);

    }

    public
    function getWaybillDataOld()
    {
        $waybill_car = DB::table('waybill_hd')
            ->where('waybill_hd.waybill_id', request()->waybill_id)
            ->join('waybill_dt', 'waybill_hd.waybill_id', '=', 'waybill_dt.waybill_hd_id')
            ->leftJoin('system_codes as loc_to', 'waybill_hd.waybill_loc_to', '=', 'loc_to.system_code_id')
            ->leftJoin('system_codes as loc_from', 'waybill_hd.waybill_loc_from', '=', 'loc_from.system_code_id')
            ->leftJoin('system_codes as loc_transit', 'waybill_hd.waybill_transit_loc_1', '=', 'loc_transit.system_code_id')
            ->leftJoin('system_codes as payment', function ($q) {
                $q->on('payment.system_code', '=', 'waybill_hd.waybill_payment_method');
                $q->on('payment.company_group_id', '=', 'waybill_hd.company_group_id');
            })
            ->join('customers', 'waybill_hd.customer_id', '=', 'customers.customer_id')
            ->select('loc_to.system_code_name_ar as loc_to_name', 'loc_to.system_code_id as loc_to_id',
                'loc_from.system_code_name_ar as loc_from_name',
                'loc_transit.system_code_name_ar as loc_transit_name', 'payment.system_code_name_ar as payment_method_name_ar',
                'waybill_hd.waybill_id', 'waybill_hd.waybill_code', 'waybill_dt.waybill_car_plate', 'waybill_dt.waybill_car_desc',
                'waybill_dt.waybill_car_owner', 'customers.customer_name_full_ar', 'waybill_hd.waybill_total_amount', 'waybill_hd.waybill_fees_total')
            ->first();

        return response()->json(['waybill_car' => $waybill_car]);
    }

    public
    function createTrip(Request $request)
    {

        $trip = TripHd::where('trip_hd_id', '=', $request->id)->first();
        $send_trip = NaqlAPIController::createTrip($trip);

        if ($send_trip['statusCode'] == 200) {
            $trip_id = $send_trip['body']->tripId;
            $waybills = $send_trip['body']->waybills;

            $trip->http_status = 200;
            $trip->trip_id = $trip_id;
            $trip->status_id = 1;

            $trip_update = $trip->update();

            if (!$trip_update) {
                return \Response::json(['success' => false, 'msg' => ' 1حدثت مشكلة']);
            }

            $category = SMSCategory::where('company_id', $trip->company_id)->where('sms_name_ar', 'sms bayan')->first();

            if (isset($category) && $category->sms_is_sms) {

                $data = NaqlAPIController::getTripPDF($trip);

                if ($data['statusCode'] == 200) {
                    $file_name = 'Trip' . $trip->trip_hd_code . '.pdf';
                    file_put_contents('Waybills/' . $file_name, $data);
                }

                $employee = Employee::where('emp_id', $trip->driver_id)->first();
                $mobNo = '+966' . substr($employee->emp_work_mobile, 1);
                $parm1 = $trip->trip_hd_code;
                $file_name = 'Trip' . $trip->trip_hd_code . '.pdf';
                $url = asset('Waybills/' . $file_name);
                $shortUrl = SMS\smsQueueController::getShortUrl($url);
                $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, null, null, null, $shortUrl);
            }

            $tripDt = $trip->tripdts;

            foreach ($tripDt as $key => $td) {
                $waybill = $td->waybill;
                $waybill->http_status = 200;
                $waybill->trip_id = $trip_id;
                $waybill->status_id = 1;
                $waybill->waybillId = $waybills[$key]->waybillId;

                $waybill_update = $waybill->update();

                if (!$waybill_update) {
                    return \Response::json(['success' => false, 'msg' => ' 2 حدثت مشكلة']);
                }
            }
            return \Response::json(['success' => true, 'msg' => 'تم التوثيق بنجاح']);
        }

        $trip->http_status = $send_trip['statusCode'];
        $trip->status_id = 2;
        $trip_update = $trip->update();

        if (!$trip_update) {
            return \Response::json(['success' => false, 'msg' => 'لم تكتمل عملية التوثيق']);
        }

        return \Response::json(['success' => false, 'msg' => '3حدثت مشكلة']);
    }

    public
    function printTrip(Request $request)
    {
        $trip = TripHd::where('trip_hd_id', '=', $request->id)->first();
        $print_trip = new NaqlAPIController();
        $data = NaqlAPIController::getTripPDF($trip);

        if ($data['statusCode'] == 200) {
            $file_name = 'Trip' . $trip->trip_hd_code . '.pdf';
            if (file_exists(public_path() . '/Waybills/' . $file_name)) {
                return \Response::json(['success' => true, 'msg' => asset('Waybills/' . $file_name)]);
            } else {
                file_put_contents('Waybills/' . $file_name, $data);
                return \Response::json(['success' => true, 'msg' => asset('Waybills/' . $file_name)]);
            }
        } elseif ($data['statusCode'] == 400) {
            return \Response::json(['error' => true, 'msg' => 'رقم البوليصه غير صحيح']);
        } else {
            return \Response::json(['error' => true, 'msg' => 'يوجد خطا']);
        }

    }

    public
    function getOldTripData()
    {
        $trip = TripHd::find(request()->old_trip_id);
        return response()->json(['trip' => $trip, 'trip_line' => $trip->tripLine]);
    }

    public
    function addFirstAidTrip($old_trip_id, $new_trip_id, $trip_hd_fees_1, $trip_hd_fees_2,
                             $trip_line_distance, $trip_line_hd_id)
    {
        $loc_transit = TripLineHd::find($trip_line_hd_id);
        $old_trip = TripHd::find($old_trip_id);
        $old_trip->trip_hd_fees_1 = $trip_hd_fees_1;
        $old_trip->trip_hd_fees_2 = $trip_hd_fees_2;
        $old_trip->trip_hd_distance = $trip_line_distance;
        $old_trip->trip_line_hd_id = $trip_line_hd_id;
        $old_trip->trip_loc_transit = $loc_transit->trip_line_loc_to;
        $old_trip->save();

//        قيود الاستحقاق
        $journal_type_0 = JournalType::where('journal_types_code', 53)
            ->where('company_group_id', $old_trip->company_group_id)->first();

        if (isset($journal_type_0)) {
            $journal_hds[0] = JournalHd::where('journal_category_id', JournalType::where('journal_types_code', 53)
                ->where('company_group_id', $old_trip->company_group_id)->first()->journal_types_id)
                ->whereHas('journalDetails', function ($query) use ($old_trip) {
                    $query->where('cc_voucher_id', $old_trip->trip_hd_id);
                })->first();
        }


        $journal_type_1 = JournalType::where('journal_types_code', 54)
            ->where('company_group_id', $old_trip->company_group_id)->first();
        if (isset($journal_type_1)) {
            $journal_hds[1] = JournalHd::where('journal_category_id', JournalType::where('journal_types_code', 54)
                ->where('company_group_id', $old_trip->company_group_id)->first()->journal_types_id)
                ->whereHas('journalDetails', function ($query) use ($old_trip) {
                    $query->where('cc_voucher_id', $old_trip->trip_hd_id);
                })->first();
        }

        $journal_controller = new JournalsController();

        if (isset($journal_hds[0])) {
            $journal_controller->updateEntitlementJournal($old_trip->trip_hd_fees_1, $journal_hds[0]->journal_hd_id);
        }
        if (isset($journal_hds[1])) {
            $journal_controller->updateEntitlementJournal($old_trip->trip_hd_fees_2, $journal_hds[1]->journal_hd_id);
        }

/////////////////////////////////


        /////سندات الصرف
        $journal_type = JournalType::where('journal_types_code', 51)
            ->where('company_group_id', $old_trip->company_group_id)->first();


        if (isset($journal_type)) {
            $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                ->where('company_group_id', $old_trip->company_group_id)->first();

            $bond_1 = Bond::where('transaction_id', $old_trip->trip_hd_id)
                ->where('bond_doc_type', $bond_doc_type->system_code_id)->first();

            $bond_vat_amount = ($old_trip->trip_hd_fees_1 / 115 * 100) * $journal_type->tax_rate;

            if (isset($bond_1)) {
                $bond_1->update([
                    'bond_amount_credit' => $old_trip->trip_hd_fees_1,
                    'bond_amount_balance' => $old_trip->trip_hd_fees_1 ? (-1) * $old_trip->trip_hd_fees_1 : null,
                    'bond_vat_amount' => number_format($bond_vat_amount, 2),
                ]);

                $bond_1->journalCash->update([
                    'journal_hd_credit' => $old_trip->trip_hd_fees_1,
                    'journal_hd_debit' => $old_trip->trip_hd_fees_1,
                ]);

                $bond_1->journalCash->journalDetails[0]->update([
                    'journal_dt_debit' => $old_trip->trip_hd_fees_1 - number_format($bond_vat_amount, 2),
                    'journal_dt_balance' => $old_trip->trip_hd_fees_1 - number_format($bond_vat_amount, 2),
                ]);

                $bond_1->journalCash->journalDetails[1]->update([
                    'journal_dt_debit' => number_format($bond_vat_amount, 2),
                    'journal_dt_balance' => number_format($bond_vat_amount, 2),
                ]);

                $bond_1->journalCash->journalDetails[2]->update([
                    'journal_dt_credit' => $old_trip->trip_hd_fees_1,
                    'journal_dt_balance' => $old_trip->trip_hd_fees_1,
                ]);
            }
        }

        $journal_type_2 = JournalType::where('journal_types_code', 52)
            ->where('company_group_id', $old_trip->company_group_id)->first();

        if (isset($journal_type_2)) {
            $bond_doc_type = SystemCode::where('system_code_id', $journal_type_2->bond_type_id)
                ->where('company_group_id', $old_trip->company_group_id)->first();

            $bond_2 = Bond::where('transaction_id', $old_trip->trip_hd_id)
                ->where('bond_doc_type', $bond_doc_type->system_code_id)->latest()->first();

            if (isset($bond_2)) {
                $bond_2->update([
                    'bond_amount_credit' => $old_trip->trip_hd_fees_2,
                    'bond_amount_balance' => $old_trip->trip_hd_fees_2 ? (-1) * $old_trip->trip_hd_fees_2 : null,
                ]);

                $bond_2->journalCash->update([
                    'journal_hd_credit' => $old_trip->trip_hd_fees_2,
                    'journal_hd_debit' => $old_trip->trip_hd_fees_2,
                ]);

                $bond_2->journalCash->journalDetails[0]->update([
                    'journal_dt_debit' => $old_trip->trip_hd_fees_2,
                    'journal_dt_balance' => $old_trip->trip_hd_fees_2,
                ]);

                $bond_2->journalCash->journalDetails[1]->update([
                    'journal_dt_credit' => $old_trip->trip_hd_fees_2,
                    'journal_dt_balance' => $old_trip->trip_hd_fees_2,
                ]);
            }
        }

        $new_trip = TripHd::find($new_trip_id);

        foreach ($old_trip->tripdts as $trip_dt) {
            $trip_dt->update(['trip_hd_id' => $new_trip->trip_hd_id]);
            $trip_dt->waybill->update([
                'waybill_trip_id' => $new_trip->trip_hd_id,
                'waybill_transit_loc_1' => $new_trip->tripLine->trip_line_loc_from,
                'waybill_truck_id' => $new_trip->truck_id
            ]);
        }
    }

    public
    function updateTripLineHd($trip_id, $trip_hd_fees_1, $trip_hd_fees_2)
    {
        $trip = TripHd::find($trip_id);

        /////سندات الصرف
        $journal_type = JournalType::where('journal_types_code', 51)
            ->where('company_group_id', $trip->company_group_id)->first();


        if (isset($journal_type)) {
            $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                ->where('company_group_id', $trip->company_group_id)->first();

            $bond_1 = Bond::where('transaction_id', $trip->trip_hd_id)
                ->where('bond_doc_type', $bond_doc_type->system_code_id)->first();

            $bond_vat_amount = ($trip->trip_hd_fees_1 / 115 * 100) * $journal_type->tax_rate;

            if (isset($bond_1)) {
                $bond_1->update([
                    'bond_amount_credit' => $trip->trip_hd_fees_1,
                    'bond_amount_balance' => $trip->trip_hd_fees_1 ? (-1) * $trip->trip_hd_fees_1 : null,
                    'bond_vat_amount' => number_format($bond_vat_amount, 2),
                ]);

                $bond_1->journalCash->update([
                    'journal_hd_credit' => $trip->trip_hd_fees_1,
                    'journal_hd_debit' => $trip->trip_hd_fees_1,
                ]);

                $bond_1->journalCash->journalDetails[0]->update([
                    'journal_dt_debit' => $trip->trip_hd_fees_1 - number_format($bond_vat_amount, 2),
                    'journal_dt_balance' => $trip->trip_hd_fees_1 - number_format($bond_vat_amount, 2),
                ]);

                $bond_1->journalCash->journalDetails[1]->update([
                    'journal_dt_debit' => number_format($bond_vat_amount, 2),
                    'journal_dt_balance' => number_format($bond_vat_amount, 2),
                ]);

                $bond_1->journalCash->journalDetails[2]->update([
                    'journal_dt_credit' => $trip->trip_hd_fees_1,
                    'journal_dt_balance' => $trip->trip_hd_fees_1,
                ]);
            }
        }

        $journal_type_2 = JournalType::where('journal_types_code', 52)
            ->where('company_group_id', $trip->company_group_id)->first();

        if (isset($journal_type_2)) {
            $bond_doc_type = SystemCode::where('system_code_id', $journal_type_2->bond_type_id)
                ->where('company_group_id', $trip->company_group_id)->first();

            $bond_2 = Bond::where('transaction_id', $trip->trip_hd_id)
                ->where('bond_doc_type', $bond_doc_type->system_code_id)->latest()->first();

            if (isset($bond_2)) {
                $bond_2->update([
                    'bond_amount_credit' => $trip->trip_hd_fees_2,
                    'bond_amount_balance' => $trip->trip_hd_fees_2 ? (-1) * $trip->trip_hd_fees_2 : null,
                ]);

                $bond_2->journalCash->update([
                    'journal_hd_credit' => $trip->trip_hd_fees_2,
                    'journal_hd_debit' => $trip->trip_hd_fees_2,
                ]);

                $bond_2->journalCash->journalDetails[0]->update([
                    'journal_dt_debit' => $trip->trip_hd_fees_2,
                    'journal_dt_balance' => $trip->trip_hd_fees_2,
                ]);

                $bond_2->journalCash->journalDetails[1]->update([
                    'journal_dt_credit' => $trip->trip_hd_fees_2,
                    'journal_dt_balance' => $trip->trip_hd_fees_2,
                ]);
            }
        }

        //////////////////////////////////////////
        ///
        /// //////////////////////////////////////
        ///
        //    قيود الاستحقاق للانطلاق
        $journal_type_0 = JournalType::where('journal_types_code', 53)
            ->where('company_group_id', $trip->company_group_id)->first();

        $j_add_date = $trip->trip_hd_start_date;
        $cost_center_id = 54;
        $journal_controller = new JournalsController();

        if (isset($journal_type_0)) {
            $journal_hds[0] = JournalHd::where('journal_category_id', JournalType::where('journal_types_code', 53)
                ->where('company_group_id', $trip->company_group_id)->first()->journal_category_id)
                ->whereHas('journalDetails', function ($query) use ($trip) {
                    $query->where('cc_voucher_id', $trip->trip_hd_id);
                })->first();

            if (isset($journal_hds[0])) {
                $journal_controller->updateEntitlementJournal($trip->trip_hd_fees_1, $journal_hds[0]->journal_hd_id);
            } else {
                if ($trip_hd_fees_1 > 0 && ($trip->status->system_code == 39002 || $trip->status->system_code == 39003 ||
                        $trip->status->system_code == 39004)) {
                    $journal_category_id = 53;
                    $journal_notes = 'قيد استحقاق ديزل' . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                    $amount_total = $trip->trip_hd_fees_1;
                    $cc_voucher_id = $trip->trip_hd_id;

                    $journal_controller->AddEntitlementJournal(56004, $amount_total, $cc_voucher_id
                        , $journal_category_id, $cost_center_id, $journal_notes, $j_add_date);
                }
            }
        }


        $journal_type_1 = JournalType::where('journal_types_code', 54)
            ->where('company_group_id', $trip->company_group_id)->first();

        if (isset($journal_type_1)) {
            $journal_hds[1] = JournalHd::where('journal_category_id', JournalType::where('journal_types_code', 54)
                ->where('company_group_id', $trip->company_group_id)->first()->journal_types_id)
                ->whereHas('journalDetails', function ($query) use ($trip) {
                    $query->where('cc_voucher_id', $trip->trip_hd_id);
                })->first();

            if (isset($journal_hds[1])) {
                $journal_controller->updateEntitlementJournal($trip->trip_hd_fees_2, $journal_hds[1]->journal_hd_id);
            } else {
                if ($trip_hd_fees_2 > 0 && isset($journal_type_1) && ($trip->status->system_code == 39002 || $trip->status->system_code == 39003 ||
                        $trip->status->system_code == 39004)) {
                    $journal_category_id = 54;
                    $journal_notes = 'قيد استحقاق مصروف ' . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                    $amount_total = $trip->trip_hd_fees_2;
                    $cc_voucher_id = $trip->trip_hd_id;
                    $journal_controller->AddEntitlementJournal(56004, $amount_total, $cc_voucher_id
                        , $journal_category_id, $cost_center_id, $journal_notes, $j_add_date);

                }
            }
        }


        /* $transaction_type = ApplicationsMenu::where('app_menu_id', 104)->first()->app_menu_id;
        $transaction_id = $trip->tip_hd_id;
        $j_add_date = $trip->trip_hd_start_date;
        $bond_car_id = $trip->truck_id;
        $customer_type = 'car';
        $bond_ref_no = $trip->trip_hd_code;
        $bond_cash = new BondsController();

        $journal_controller = new JournalsController();
        $cost_center_id = 54;

        /////////////////قيود الاستحقاق للانتهاء
       $journal_type_55 = JournalType::where('journal_types_code', 55)
            ->where('company_group_id', $trip->company_group_id)->first();

        if (isset($journal_type_55)) {
            $journal_hds_55 = JournalHd::where('journal_category_id', JournalType::where('journal_types_code', 55)
                ->where('company_group_id', $trip->company_group_id)->first()->journal_types_id)
                ->whereHas('journalDetails', function ($query) use ($trip) {
                    $query->where('cc_voucher_id', $trip->trip_hd_id);
                })->first();

            if (isset($journal_hds_55)) {
                $journal_hds_55->update([
                    'journal_hd_credit' => $trip_hd_fees_1,
                    'journal_hd_debit' => $trip_hd_fees_1,
                ]);

                $journal_hds_55->journalDetails[0]->update([
                    'journal_dt_debit' => $trip_hd_fees_1,
                    'journal_dt_balance' => $trip_hd_fees_1,
                ]);

                $journal_hds_55->journalDetails[1]->update([
                    'journal_dt_credit' => $trip_hd_fees_1,
                    'journal_dt_balance' => $trip_hd_fees_1,
                ]);

                $bond = Bond::where('journal_hd_id', $journal_hds_55->journal_hd_id)->first();
                $bond->update([
                    'bond_amount_credit' => $trip_hd_fees_1,
                    'bond_amount_balance' => $trip_hd_fees_1 ? (-1) * $trip_hd_fees_1 : null,
                ]);

            } else {
                if ($trip_hd_fees_1 > 0 && $trip->status->system_code == 39004) {
                    /////add bond and journal
                    $journal_type = $journal_type_55;

                    $total_amount = $trip->trip_hd_fees_1;
                    $bond_notes = ' سند صرف  ديزل رحله رقم ' . $trip->trip_hd_code;
                    $bond_vat_amount = ($trip->trip_hd_fees_1 / 115 * 100) * $journal_type->tax_rate;
                    $bond_vat_rate = $journal_type->tax_rate;

                    $payment_method = SystemCode::where('system_code', $journal_type->bond_payment_type_code)
                        ->where('company_group_id', $trip->company_group_id)->first();

                    $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                        ->where('company_group_id', $trip->company_group_id)->first(); ///////////من المصروفات

                    $bond_account_id = $bond_doc_type->system_code_acc_id;

                    $bond = $bond_cash->addCashBond($payment_method, $transaction_type, $transaction_id, '', $customer_type,
                        '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id, number_format($bond_vat_amount, 2),
                        $bond_vat_rate, $bond_car_id, $j_add_date);

                    $cc_voucher_id = $bond->bond_id;
                    $journal_notes = '   سند صرف رقم' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                    $journal_controller->AddEntitlement2Journal(56005, $total_amount, $cc_voucher_id, $trip_id
                        , 55, $cost_center_id, $journal_notes, $j_add_date);
                }
            }
        }


        $journal_type_56 = JournalType::where('journal_types_code', 56)
            ->where('company_group_id', $trip->company_group_id)->first();

        if (isset($journal_type_56)) {
            $journal_hds_56 = JournalHd::where('journal_category_id', JournalType::where('journal_types_code', 56)
                ->where('company_group_id', $trip->company_group_id)->first()->journal_types_id)
                ->whereHas('journalDetails', function ($query) use ($trip) {
                    $query->where('cc_voucher_id', $trip->trip_hd_id);
                })->first();

            if (isset($journal_hds_56)) {
                $journal_hds_56->update([
                    'journal_hd_credit' => $trip_hd_fees_2,
                    'journal_hd_debit' => $trip_hd_fees_2,
                ]);

                $journal_hds_56->journalDetails[0]->update([
                    'journal_dt_debit' => $trip_hd_fees_2,
                    'journal_dt_balance' => $trip_hd_fees_2,
                ]);

                $journal_hds_56->journalDetails[1]->update([
                    'journal_dt_credit' => $trip_hd_fees_2,
                    'journal_dt_balance' => $trip_hd_fees_2,
                ]);

                $bond = Bond::where('journal_hd_id', $journal_hds_56->journal_hd_id)->first();

                $bond->update([
                    'bond_amount_credit' => $trip_hd_fees_2,
                    'bond_amount_balance' => $trip_hd_fees_2 ? (-1) * $trip_hd_fees_2 : null,
                ]);
            } else {
                if ($trip_hd_fees_2 > 0 && $trip->status->system_code == 39004) {
                    /////add bond and journal
                    $journal_type = $journal_type_56;
                    $payment_method = SystemCode::where('system_code', $journal_type->bond_payment_type_code)
                        ->where('company_group_id', $trip->company_group_id)->first();
                    $total_amount = $trip->trip_hd_fees_2;
                    $bond_notes = ' سند صرف  رحله رقم ' . $trip->trip_hd_code;
                    $bond_vat_amount = 0;
                    $bond_vat_rate = 0;

                    $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                        ->where('company_group_id', $trip->company_group_id)->first(); ///////////من المصروفات

                    $bond_account_id = $bond_doc_type->system_code_acc_id;

                    $bond = $bond_cash->addCashBond($payment_method, $transaction_type, $transaction_id, '', $customer_type,
                        '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id, $bond_vat_amount,
                        $bond_vat_rate, $bond_car_id, $j_add_date);

                    $cc_voucher_id = $bond->bond_id;

                    $journal_notes = '   سند صرف رقم' . $bond->bond_code . ' ' . 'رحله رقم' . ' ' . $trip->trip_hd_code . ' ' . $trip->driver->emp_name_full_ar;
                    $journal_controller->AddEntitlement2Journal(56005, $total_amount, $cc_voucher_id, $trip_id
                        , 56, $cost_center_id, $journal_notes, $j_add_date);
                }
            }
        } */
    }
}
