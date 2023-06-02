<?php

namespace App\Http\Controllers;

use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Http\Controllers\Naql\NaqlWayAPIController;
use App\InvoiceQR\InvoiceDateElement;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\Models\AccounPeriod;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\CarRentBrand;
use App\Models\CarRentBrandDt;
use App\Models\CompanyMenuSerial;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\JournalDt;
use App\Models\JournalType;
use App\Models\Note;
use App\Models\PriceListHd;
use App\Models\SMSCategory;
use App\Models\TripDt;
use App\Models\TripHd;
use App\Models\UsersPermissionsRol;
use App\Models\WaybillDt;
use App\Models\waybillDtCar;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Customer;
use App\Models\WaybillHd;
use App\Models\Employee;
use App\Models\Trucks;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WaybillCarController extends Controller
{
    public function __construct()
    {
        set_time_limit(8000000);
    }

    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data = request()->all();
        $branches = $company->branches;
        $sys_codes_loc_to = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_waybill_status = SystemCode::whereIn('system_code', ['41001', '41004', '41005',
            '41006', '41007', '41008'])
            ->where('company_group_id', $company->company_group_id)->get();

        $customers = Customer::where('customer_category', 2)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_shipping = SystemCode::where('sys_category_id', 64)
            ->where('company_group_id', $company->company_group_id)->get();

        $query = WaybillHd::where('waybill_type_id', 4)->where('company_group_id', $company->company_group_id)
            ->select('waybill_id', 'waybill_load_date', 'waybill_sender_name', 'waybill_receiver_mobile', 'waybill_total_amount',
                'waybill_paid_amount', 'waybill_trip_id', 'waybill_code', 'waybill_loc_from', 'waybill_loc_to', 'waybill_trip_id',
                'company_id', 'waybill_invoice_id', 'waybill_status');


        if (request()->query->count() > 1 && !request()->waybill_status_filter) {

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('created_date', '>=', request()->created_date_from)
                    ->whereDate('created_date', '<=', request()->created_date_to);
            }

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
            }

            if (request()->statuses_id) {
                $query = $query->whereIn('waybill_status', request()->statuses_id);
            }

            if (request()->invoice_id) {
                // return request()->invoice_id;
                if (request()->invoice_id == 1) {
                    $query = $query->whereHas('invoice');
                } else {
                    $query = $query->whereDoesntHave('invoice');
                }

            }

            if (request()->waybill_item_id) {

                $query = $query->whereHas('detailsCar', function ($q) {
                    $q->whereIn('waybill_item_id', request()->waybill_item_id);
                });

            }

            if (request()->expected_date_from) {

                $query = $query->where('waybill_receiver_mobile', 'like', '%' . request()->expected_date_from . '%');
            }

            if (request()->waybill_code) {
                $query = $query->where('waybill_code', 'like', '%' . request()->waybill_code . '%');
            }

            if (request()->expected_date_to) {

                $way_pill_no_1 = WaybillDt::where('company_id', $company->company_id)
                    ->where('waybill_car_plate', 'like', '%' . request()->expected_date_to . '%')
                    ->orWhere('waybill_car_chase', 'like', '%' . request()->expected_date_to . '%')
                    ->pluck('waybill_hd_id')->toArray();

                $way_pill_no_2 = waybillDtCar::where('company_id', $company->company_id)
                    ->where('waybill_car_plate', 'like', '%' . request()->expected_date_to . '%')
                    ->orWhere('waybill_car_chase', 'like', '%' . request()->expected_date_to . '%')
                    ->pluck('waybill_hd_id')->toArray();

                $way_pill_no = array_merge($way_pill_no_1, $way_pill_no_2);

                $query = $query->whereIn('waybill_id', $way_pill_no);

            }

            if (request()->branch_id) {
                $branch_from = request()->branch_id;

                $query = $query->whereHas('locfrom', function ($q) use ($branch_from) {
                    $q->whereIn('branch_id', $branch_from);
                });

                /////////////////function 2
                $waybills_r = $this->getWaybillsByBranchFilter($company, $branch_from);
                $transit_waybills_count = $waybills_r[0];
                $transit_waybills_cars_count = $waybills_r[1];
                $arrived_waybills_count = $waybills_r[2];
                $arrived_waybills_cars_count = $waybills_r[3];
                $delivered_waybills_count = $waybills_r[4];
                $delivered_waybills_cars_count = $waybills_r[5];

            } elseif (request()->branch_to) {
                $branch_to = request()->branch_to;
                $query = $query->whereHas('locTo', function ($q) use ($branch_to) {
                    $q->whereIn('branch_id', $branch_to);
                });

                /////////////////function 2
                $waybills_r = $this->getWaybillsByBranchFilter($company, request()->branch_to);
                $transit_waybills = $waybills_r[0];
                $transit_waybills_count = $waybills_r[1];
                $transit_waybills_cars_count = $waybills_r[2];

                $arrived_waybills = $waybills_r[3];
                $arrived_waybills_count = $waybills_r[4];
                $arrived_waybills_cars_count = $waybills_r[5];

                $delivered_waybills = $waybills_r[6];
                $delivered_waybills_count = $waybills_r[7];
                $delivered_waybills_cars_count = $waybills_r[8];
            } else {
                //////////////function 1
                $waybills_r = $this->getWaybillsByBranch($company);
                $transit_waybills = $waybills_r[0];
                $transit_waybills_count = $waybills_r[1];
                $transit_waybills_cars_count = $waybills_r[2];

                $arrived_waybills = $waybills_r[3];
                $arrived_waybills_count = $waybills_r[4];
                $arrived_waybills_cars_count = $waybills_r[5];

                $delivered_waybills = $waybills_r[6];
                $delivered_waybills_count = $waybills_r[7];
                $delivered_waybills_cars_count = $waybills_r[8];
            }

        } else {
            $query = $query->whereHas('locfrom', function ($q) {
                $q->where('branch_id', session('branch')['branch_id']);
            });

            //////////////function 1
            $waybills_r = $this->getWaybillsByBranch($company);
            $transit_waybills = $waybills_r[0];
            $transit_waybills_count = $waybills_r[1];
            $transit_waybills_cars_count = $waybills_r[2];

            $arrived_waybills = $waybills_r[3];
            $arrived_waybills_count = $waybills_r[4];
            $arrived_waybills_cars_count = $waybills_r[5];

            $delivered_waybills = $waybills_r[6];
            $delivered_waybills_count = $waybills_r[7];
            $delivered_waybills_cars_count = $waybills_r[8];

        }

        $way_pills_s = $query->get();
        $way_pills = $query->latest()->paginate();

        ////////////////محجوزه
        $reserved_waybills = $way_pills_s->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41001)->first()->system_code_id);
        $reserved_waybills_count = $reserved_waybills->count();
        $reserved_waybills_cars_count = array_sum($reserved_waybills->pluck('carsCount')->toArray());

        ///////////////////بوليصه
        $way_waybills = $way_pills_s->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41004)->first()->system_code_id);
        $way_waybills_count = $way_waybills->count();
        $way_waybills_cars_count = array_sum($way_waybills->pluck('carsCount')->toArray());


        ///////متاخره
        $late_waybills = $query->whereDoesntHave('trip')->whereDate('waybill_delivery_expected', '<=', Carbon::now()->addDays(2));
        $late_waybills_count = $late_waybills->count();
        $late_waybills_cars_count = array_sum($late_waybills->get()->pluck('carsCount')->toArray());

        if (request()->waybill_status_filter) {
            $waybill_status_filter = request()->waybill_status_filter;

            if ($waybill_status_filter == 41001) { ///محجوزه
                $way_pills = $reserved_waybills->latest()->paginate();
            }

            if ($waybill_status_filter == 41004) { ///بوليصه
                $way_pills = $way_waybills->latest()->paginate();
            }

            if ($waybill_status_filter == 41006) { ///في الطريق
                $way_pills = $transit_waybills->latest()->paginate();
            }

            if ($waybill_status_filter == 41007) { ///وصلت
                $way_pills = $arrived_waybills->latest()->paginate();
            }

            if ($waybill_status_filter == 41008) { ///سلمت
                $way_pills = $delivered_waybills->latest()->paginate();
            }

            if ($waybill_status_filter == 'late') { ///متاخره
                $way_pills = $late_waybills->latest()->paginate();
            }
        }

        $total = $query->sum('waybill_total_amount');
        $total_vat = $query->sum('waybill_vat_amount');

        if (session('waybill_hd')) {
            $request->session()->forget('waybill_hd');
        }

        if (session('waybill_dt')) {
            $request->session()->forget('waybill_dt');
        }


        return view('Waybill.Cars.index_car', compact('customers', 'sys_codes_waybill_status', 'data', 'company',
            'branches', 'sys_codes_shipping', 'sys_codes_loc_to', 'way_pills', 'total', 'total_vat',
            'reserved_waybills_count', 'reserved_waybills_cars_count',
            'way_waybills_count', 'way_waybills_cars_count',
            'late_waybills_count', 'late_waybills_cars_count',
            'transit_waybills_count', 'transit_waybills_cars_count',
            'arrived_waybills_count', 'arrived_waybills_cars_count',
            'delivered_waybills_count', 'delivered_waybills_cars_count'));
    }


    public function getWaybillsByBranch($company)
    {
        //       في الطريق الي فرع الدخول   ///////////////

        $transit_waybills = DB::table('waybill_hd')->where('waybill_type_id', 4)
            ->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 41006)->first()->system_code_id)
            ->whereExists(function ($q) {
                return $q->from('system_codes')
                    ->whereColumn('system_codes.system_code_id', 'waybill_hd.waybill_loc_to')
                    ->where('system_codes.branch_id', '=', session('branch')['branch_id']);
            });

        $transit_waybills_count = $transit_waybills->count();


        $transit_waybills_cars_count = array_sum($transit_waybills->pluck('waybills_cars_count')->toArray());

//وصلت
        $arrived_waybills = DB::table('waybill_hd')->where('waybill_type_id', 4)
            ->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 41007)->first()->system_code_id)
            ->whereExists(function ($q) {
                return $q->from('system_codes')
                    ->whereColumn('system_codes.system_code_id', 'waybill_hd.waybill_loc_to')
                    ->where('system_codes.branch_id', '=', session('branch')['branch_id']);
            });

        $arrived_waybills_count = $arrived_waybills->count();

        $arrived_waybills_cars_count = array_sum($arrived_waybills->pluck('waybills_cars_count')->toArray());

//        سلمت
        $delivered_waybills = DB::table('waybill_hd')->where('waybill_type_id', 4)
            ->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 41008)->first()->system_code_id)
            ->whereExists(function ($q) {
                return $q->from('system_codes')
                    ->whereColumn('system_codes.system_code_id', 'waybill_hd.waybill_loc_to')
                    ->where('system_codes.branch_id', '=', session('branch')['branch_id']);
            });

        $delivered_waybills_count = $delivered_waybills->count();

        $delivered_waybills_cars_count = array_sum($delivered_waybills->pluck('waybills_cars_count')->toArray());

        return [$transit_waybills, $transit_waybills_count, $transit_waybills_cars_count,
            $arrived_waybills, $arrived_waybills_count, $arrived_waybills_cars_count,
            $delivered_waybills, $delivered_waybills_count, $delivered_waybills_cars_count];
    }


    public function getWaybillsByBranchFilter($company, $branch_id)
    {
        //       في الطريق الي فرع الدخول   ///////////////

        $transit_waybills = DB::table('waybill_hd')
            ->where('company_group_id', $company->company_group_id)
            ->where('waybill_type_id', 4)
            ->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 41006)->first()->system_code_id)
            ->whereExists(function ($q) use ($branch_id) {
                return $q->from('system_codes')
                    ->whereColumn('system_codes.system_code_id', 'waybill_hd.waybill_loc_to')
                    ->whereIn('system_codes.branch_id', $branch_id);
            });

        $transit_waybills_count = $transit_waybills->count();

        $transit_waybills_cars_count = array_sum($transit_waybills->pluck('waybills_cars_count')->toArray());

//وصلت
        $arrived_waybills = DB::table('waybill_hd')->where('company_group_id', $company->company_group_id)
            ->where('waybill_type_id', 4)
            ->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 41007)->first()->system_code_id)
            ->whereExists(function ($q) use ($branch_id) {
                return $q->from('system_codes')
                    ->whereColumn('system_codes.system_code_id', 'waybill_hd.waybill_loc_to')
                    ->whereIn('system_codes.branch_id', $branch_id);
            });

        $arrived_waybills_count = $arrived_waybills->count();

        $arrived_waybills_cars_count = array_sum($arrived_waybills->pluck('waybills_cars_count')->toArray());


//        سلمت
        $delivered_waybills = DB::table('waybill_hd')
            ->where('company_group_id', $company->company_group_id)
            ->where('waybill_type_id', 4)
            ->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 41008)->first()->system_code_id)
            ->whereExists(function ($q) use ($branch_id) {
                return $q->from('system_codes')
                    ->whereColumn('system_codes.system_code_id', 'waybill_hd.waybill_loc_to')
                    ->whereIn('system_codes.branch_id', $branch_id);
            });
        $delivered_waybills_count = $delivered_waybills->count();

        $delivered_waybills_cars_count = array_sum($delivered_waybills->pluck('waybills_cars_count')->toArray());;

        return [$transit_waybills_count, $transit_waybills_cars_count, $arrived_waybills_count, $arrived_waybills_cars_count, $delivered_waybills_count, $delivered_waybills_cars_count];
    }


    public function create(Request $request)
    {
        if (session('waybill_hd')) {
            $request->session()->forget('waybill_hd');
        }

        if (session('waybill_dt')) {
            $request->session()->forget('waybill_dt');
        }

        if (session('waybill_item')) {
            $request->session()->forget('waybill_item');
        }

        if (session('waybill_status')) {
            $request->session()->forget('waybill_status');
        }

        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_shipping = SystemCode::where('sys_category_id', 64)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_payment_methods = SystemCode::where('sys_category_id', 54)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_payment_methods_n = SystemCode::where('sys_category_id', 54)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', ['54001', '54002'])->get();


        $sys_codes_payment_type = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)
            ->where('system_code_filter', 'waybill')->get();

        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
            ->get()->whereIn('system_code', ['41001', '41004']);


        $suppliers = Customer::where('customer_category', 1)->where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', 485)->where('company_group_id', $company->company_group_id)->get();
        $trucks = Trucks::where('company_group_id', $company->company_group_id)->get();


        $customersـtype = Customer::where('customer_id', $request->customer_id)->first();

        $current_date = Carbon::now()->format('Y-m-d\TH:i');
        $banks = SystemCode::where('sys_category_id', 40)->where('company_group_id', $company->company_group_id)->get();
        $branch = session('branch') ? session('branch') : auth()->user()->company;
        $sys_codes_loc_session = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)
            ->where('system_code_name_ar', $branch->branch_name_ar)->first();
        //  $brands = CarRentBrand::where('company_group_id', $company->company_group_id)->get();
        $brands = SystemCode::where('sys_category_id', 148)->get();
        $sys_code_waybill_status = SystemCode::where('system_code', 41004)->where('company_group_id', $company->company_group_id)
            ->first();

        if (request()->ajax()) {
            $colors_list = SystemCode::where('sys_category_id', 149)->get();
            $types_list = SystemCode::where('sys_category_id', 148)->get();
            return response()->json(['typesList' => $types_list, 'colorsList' => $colors_list]);
        }
        return view('Waybill.Cars.create_car', compact('companies', 'suppliers', 'customers', 'employees', 'sys_codes_loc_session',
            'customersـtype', 'trucks', 'sys_codes_location', 'sys_codes_item', 'sys_codes_unit', 'banks', 'current_date', 'brands',
            'sys_codes_payment_type', 'sys_codes_payment_methods', 'sys_codes_payment_methods_n',
            'sys_codes_shipping', 'sys_codes_waybill_status', 'sys_code_waybill_status'));
    }


    public function store(Request $request)
    {

        DB::beginTransaction();
        $request->validate([
            'waybill_sender_mobile_code' => 'numeric',
            'waybill_receiver_mobile_code' => 'numeric'
        ]);

        $company = session('company') ? session('company') : auth()->user()->company;

        if (session('waybill_hd')) {
            $waybill_status_code = SystemCode::where('system_code', SystemCode::where('system_code_id',
                session('waybill_hd')['waybill_status'])->first()->system_code)
                ->where('company_group_id', $company->company_group_id)->first();
        } else {

            $waybill_status_code = SystemCode::where('system_code', $request->waybill_status)
                ->where('company_group_id', $company->company_group_id)->first();
        }


        $branch = session('branch');
        $last_waypill_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 88)->latest()->first();

        if (!session('waybill_hd')) {
            $customer_contract = PriceListHd::where('price_list_id', $request->customer_contract)->first();

        }

        if (isset($last_waypill_serial)) {
            $last_waypill_serial_no = $last_waypill_serial->serial_last_no;
            $array_number = explode('-', $last_waypill_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_waybill_number = implode('-', $array_number);
            $last_waypill_serial->update(['serial_last_no' => $string_waybill_number]);
        } else {
            $string_waybill_number = 'CAR-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 88,
                'acc_period_year' => Carbon::now()->format('y'),
                'branch_id' => session('branch')['branch_id'],
                'serial_last_no' => $string_waybill_number,
                'created_user' => auth()->user()->user_id
            ]);
        }


        $waybill_fees_vat_amount = ($request->waybill_vat_rate / 100) * ($request->waybill_fees_load);

        if ($request->waybill_payment_method) {
            $payment_method = SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', $request->waybill_payment_method)->first();
        } else {
            $payment_method = SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', 54003)->first();
        }

        if ($payment_method->system_code == 54003) {
            $waybill_payment_terms = SystemCode::where('system_code', 57005)->first();
        } else {
            $waybill_payment_terms = SystemCode::where('system_code', $request->waybill_payment_terms)->first();
        }

        if (session('waybill_hd')) {
            if (session('waybill_hd')['waybill_payment_method'] == 54003) {
                $waybill_payment_terms = SystemCode::where('system_code', 57005)
                    ->where('company_group_id', $company->company_group_id)->first();
            } else {
                $waybill_payment_terms = SystemCode::where('system_code', $request->waybill_payment_terms)
                    ->where('company_group_id', $company->company_group_id)->first();
            }
        }

        if ($request->waybill_item_id == 64005 || $request->waybill_item_id == 64006) {
            $waybill_truck_id = $request->waybill_truck_id;
        } else {
            $waybill_truck_id = '';
        }

        $waybill_hd = WaybillHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'waybill_code' => $string_waybill_number,
            'waybill_type_id' => 4,
            'branch_id' => session('branch')['branch_id'],
            'waybill_ticket_no' => $request->waybill_ticket_no ? $request->waybill_ticket_no : null,
            'waybill_payment_terms' => $waybill_payment_terms->system_code,
            'waybill_payment_method' => session('waybill_hd') ? session('waybill_hd')['waybill_payment_method'] : $payment_method->system_code,
            'waybill_paid_amount' => $request->waybill_paid_amount ? $request->waybill_paid_amount : 0,
            'waybill_status' => $waybill_status_code->system_code_id,
            'customer_id' => session('waybill_hd') ? session('waybill_hd')['customer_id'] : $request->customer_id,
            'customer_contract' => session('waybill_hd') ? session('waybill_hd')['customer_contract'] : $customer_contract->price_list_code,
            'created_user' => auth()->user()->user_id,
            'waybill_create_user' => auth()->user()->user_id,
            'waybill_loc_from' => $request->waybill_loc_from,
            'waybill_transit_loc_1' => $request->waybill_loc_from,
            'waybill_loc_to' => $request->waybill_loc_to,

            'waybill_sender_name' => $request->waybill_sender_name,
            'waybill_sender_mobile' => $request->waybill_sender_mobile,
            'waybill_sender_mobile_code' => $request->waybill_sender_mobile_code,
            'waybill_receiver_name' => $request->waybill_receiver_name,
            'waybill_receiver_mobile' => $request->waybill_receiver_mobile,
            'waybill_receiver_mobile_code' => $request->waybill_receiver_mobile_code,

            'waybill_driver_id' => $request->waybill_driver_id,
            'waybill_truck_id' => $waybill_truck_id,
            'waybill_load_date' => $request->waybill_load_date,
            'waybill_unload_date' => $request->waybill_unload_date ? $request->waybill_unload_date : null,
            'waybill_vat_rate' => $request->waybill_vat_rate,
            'waybill_vat_amount' => $request->waybill_vat_amount, ///customer
            'waybill_total_amount' => $request->waybill_total_amount, ///customer
            'waybill_delivery_expected' => $request->waybill_delivery_expected ? $request->waybill_delivery_expected : null,
            'waybill_delivery_user' => $request->waybill_status == 41008 ? auth()->user()->user_id : null,
            'waybill_trip_status' => SystemCode::where('system_code', 39001)->first()->system_code_id,
            'waybill_add_amount' => $request->waybill_add_amount ? $request->waybill_add_amount : 0,
            'waybill_discount_amount' => $request->waybill_discount_total ? $request->waybill_discount_total : 0,
            'waybill_return' => $request->waybill_return,
            'waybill_loc_paid' => $request->waybill_loc_paid
        ]);

        $waybill_hd->statusM()->attach($waybill_status_code->system_code_id, ['status_date' => Carbon::now()]);


        $customer = Customer::where('customer_id', $request->customer_id)->first();

//        if (isset($customer->cus_type->system_code) == 538) {
//            if ($request->waybill_payment_method == 54001 || $request->waybill_payment_method == 54002) {
//                $category = SMSCategory::where('company_id', $waybill_hd->company_id)
//                    ->where('sms_name_ar', 'sms waybill tracking')->first();
//
//                if (isset($category) && $category->sms_is_sms) {
//                    $mobNo = '+966' . substr($request->waybill_sender_mobile, 0);
//
//                    $parm1 = $waybill_hd->waybill_code;
//
//                    $url = asset('tracking/' . $waybill_hd->waybill_id);
//                    // return $url;
//                    $shortUrl = SMS\smsQueueController::getShortUrl($url);
//                    $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, null, null, null, $shortUrl);
//                }
//            }
//        }

        $item = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', $request->waybill_item_id)->first();


        $waybill_dt = WaybillDt::create([
            'waybill_hd_id' => $waybill_hd->waybill_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],

            'waybill_item_id' => session('waybill_dt') ? session('waybill_dt')['waybill_item_id'] : $item->system_code_id,
            'waybill_item_vat_rate' => $request->waybill_vat_rate ? $request->waybill_vat_rate : null,
            'waybill_item_vat_amount' => $request->waybill_vat_amount ? $request->waybill_vat_amount : null,
            'waybill_car_chase' => $request->waybill_car_chase,
            'waybill_car_plate' => $request->waybill_car_plate,
            'waybill_car_desc' => $request->waybill_car_desc,
            'waybill_car_owner' => $request->waybill_car_owner,
            'waybill_car_color' => $request->waybill_car_color,
            'waybill_car_model' => $request->waybill_car_model,
            'waybill_discount_total' => $request->waybill_discount_total ? $request->waybill_discount_total : 0,
            'waybill_add_amount' => $request->waybill_add_amount,
            'waybill_distance' => $request->waybill_distance,

//            customer

            'waybill_item_unit' => 0,
            'waybill_item_price' => $request->waybill_item_price,
            'waybill_item_amount' => $request->waybill_item_price,
            'waybill_total_amount' => $request->waybill_total_amount,
            'waybill_qut_requried_customer' => $request->waybill_qut_requried_customer ? $request->waybill_qut_requried_customer : null,
            'waybill_qut_received_customer' => $request->waybill_qut_received_customer ? $request->waybill_qut_received_customer : null,

//           supplier
            'waybill_price_supplier' => $request->waybill_price_supplier,
            'waybill_vat_amount_supplier' => $request->waybill_vat_amount_supplier,
            'waybill_amount_supplier' => $request->waybill_amount_supplier,
            'waybill_qut_requried_supplier' => $request->waybill_qut_requried_supplier ? $request->waybill_qut_requried_supplier : null,
            'waybill_qut_received_supplier' => $request->waybill_qut_received_supplier ? $request->waybill_qut_received_supplier : null,

            'waybill_fees_load' => $request->waybill_fees_load,
            'created_user' => auth()->user()->user_id,
            'waybill_fees_difference' => $request->waybill_fees_difference,
            'waybill_car_notes' => $request->waybill_car_notes,
            'waybill_item_quantity' => $request->waybill_qut_received_customer,
        ]);

        if (isset($request->waybill_car_chase_arr)) {
            foreach ($request->waybill_car_chase_arr as $k => $waybill_car_chase_arr) {
                waybillDtCar::create([
                    'waybill_hd_id' => $waybill_hd->waybill_id,
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'waybill_item_id' => session('waybill_dt') ? session('waybill_dt')['waybill_item_id'] : $item->system_code_id,
                    'waybill_item_vat_rate' => $request->waybill_vat_rate ? $request->waybill_vat_rate : null,
                    'waybill_item_vat_amount' => $request->waybill_vat_amount ? $request->waybill_vat_amount : null,
                    'waybill_car_chase' => $request->waybill_car_chase_arr[$k],
                    'waybill_car_plate' => $request->waybill_car_chase_arr[$k],
                    'waybill_car_desc' => $request->waybill_car_desc_arr[$k],
                    'waybill_car_owner' => $request->waybill_car_owner_arr[$k],
                    'waybill_car_color' => $request->waybill_car_color_arr[$k],
                    'waybill_car_model' => $request->waybill_car_model_arr[$k],
                    'waybill_discount_total' => $request->waybill_discount_total ? $request->waybill_discount_total : 0,
                    'waybill_add_amount' => $request->waybill_add_amount,
                    'waybill_distance' => $request->waybill_distance,

//            customer
                    'waybill_item_quantity' => $request->waybill_item_quantity ? $request->waybill_item_quantity : null,
                    'waybill_item_unit' => 0,
                    'waybill_item_price' => $request->waybill_item_price,
                    'waybill_item_amount' => $request->waybill_item_price,
                    'waybill_total_amount' => $request->waybill_total_amount,
                    'waybill_qut_requried_customer' => $request->waybill_qut_requried_customer ? $request->waybill_qut_requried_customer : null,
                    'waybill_qut_received_customer' => $request->waybill_qut_received_customer ? $request->waybill_qut_received_customer : null,

//           supplier
                    'waybill_price_supplier' => $request->waybill_price_supplier,
                    'waybill_vat_amount_supplier' => $request->waybill_vat_amount_supplier,
                    'waybill_amount_supplier' => $request->waybill_amount_supplier,
                    'waybill_qut_requried_supplier' => $request->waybill_qut_requried_supplier ? $request->waybill_qut_requried_supplier : null,
                    'waybill_qut_received_supplier' => $request->waybill_qut_received_supplier ? $request->waybill_qut_received_supplier : null,

                    'waybill_fees_load' => $request->waybill_fees_load,
                    'created_user' => auth()->user()->user_id,
                    'waybill_fees_difference' => $request->waybill_fees_difference,
                    'waybill_car_notes' => $request->waybill_car_notes
                ]);
            }
        }


//        اضافه فاتوره وسند في حاله الدفع علي الحساب
        $waybill_payment_method = session('waybill_hd') ? session('waybill_hd')['waybill_payment_method'] : $payment_method->system_code;

        if ($waybill_payment_method == 54001 || $waybill_payment_method == 54002) {
            $last_invoice_reference = CompanyMenuSerial::where('branch_id', $branch->branch_id)
                ->where('app_menu_id', 119)->latest()->first();

            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 119,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'branch_id' => session('branch')['branch_id'],
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);

            }
            $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
                ->where('acc_period_month', Carbon::now()->format('m'))
                ->where('acc_period_is_active', 1)->first();

            $invoice_hd = InvoiceHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'acc_period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'invoice_date' => Carbon::now(),
                'invoice_due_date' => Carbon::now(),
                'invoice_amount' => $request->waybill_total_amount,
                'invoice_vat_rate' => $waybill_hd->waybill_vat_rate,
                // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
                'invoice_vat_amount' => $waybill_hd->waybill_vat_amount,
                'invoice_discount_total' => 0,
                'invoice_down_payment' => 0,
                'invoice_total_payment' => 0,
                'invoice_notes' => '  فاتوره  بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
                'invoice_no' => $string_number,
                'created_user' => auth()->user()->user_id,
                'branch_id' => session('branch')['branch_id'],
                'customer_id' => session('waybill_hd') ? session('waybill_hd')['customer_id'] : $request->customer_id,
                'invoice_is_payment' => 1,
                'invoice_type' => 9, ///فاتوره السياره
                'invoice_status' => 121003,
                'customer_address' => 'الممكله العربيه السعوديه',
                'customer_name' => $request->waybill_sender_name,
                'customer_phone' => $request->waybill_sender_mobile,
            ]);

            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();

            $invoice_hd->update(['qr_data' => $qr]);

            $invoice_dt = InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $item->system_code_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => $request->waybill_qut_received_customer,
                'invoice_item_price' => $request->waybill_item_price,
                'invoice_item_amount' => $request->waybill_sub_total_amount,
                'invoice_item_vat_rate' => $request->waybill_vat_rate,
                'invoice_item_vat_amount' => $request->waybill_vat_amount,
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $request->waybill_total_amount,
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => '  فاتوره  بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now()
            ]);

            $waybill_hd->waybill_invoice_id = $invoice_hd->invoice_id;
            $waybill_hd->save();

            $invoice_dt->invoice_reference_no = $waybill_hd->waybill_id;
            $invoice_dt->save();

            $invoice_journal = new JournalsController();
            $total_amount = $invoice_hd->invoice_amount;
            $cc_voucher_id = $invoice_hd->invoice_id;
            $items_id[] = $waybill_hd->waybill_id;
            $customer_notes = 'قيد فاتورة شحن سياره رقم' . ' ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $vat_notes = '  قيد ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $sales_notes = '  قيد ايراد للفاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $message = $invoice_journal->addWaybillInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
                $customer_notes, 119, $vat_notes, $sales_notes, 40, $items_id,
                $items_amount = [], $notes);

            if ($message) {
                return back()->with(['error' => $message]);
            }


            if ($request->waybill_paid_amount > 0) {

                ////////////////////////////
                /// اضافه سند قبض وقيد علي سند القبض
                $bond_controller = new BondsController();
                $transaction_type = 88; ///بوليصه السايرات
                $transaction_id = $waybill_hd->waybill_id;
                $customer_id = $waybill_hd->customer_id;
                $customer_type = 'customer';
                $total_amount = $request->waybill_paid_amount;
                $bond_doc_type = SystemCode::where('system_code', 58002)
                    ->where('company_group_id', $company->company_group_id)->first(); ////ايرادات مبيعات
                // return $bond_doc_type;
                $bond_ref_no = $waybill_hd->waybill_code;
                $bond_notes = '  سداد بوليصه رقم ' . ' ' . $waybill_hd->waybill_code . ' ' . 'بواسطه' . ' ' . $waybill_hd->waybill_sender_name;

                $payment_method = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $waybill_payment_terms->system_code)->first();

                $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id,
                    $customer_id, $customer_type, '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

                $invoice_hd->bond_code = $bond->bond_id;
                $invoice_hd->bond_date = Carbon::now();
                $invoice_hd->invoice_total_payment = $request->waybill_paid_amount;
                $invoice_hd->save();

                $waybill_hd->bond_code = $bond->bond_code;
                $waybill_hd->bond_id = $bond->bond_id;
                $waybill_hd->bond_date = $bond->bond_date;
                $waybill_hd->save();

                $bond_journal = new JournalsController();
                $cc_voucher_id = $bond->bond_id;
                $journal_category_id = 4; ////سند قبض بوليصه سياره
                $cost_center_id = 53;
                $account_type = 56002;
                $bank_id = $request->bank_id ? $request->bank_id : '';
                $journal_notes = '  قيد  سند القبض رقم ' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
                $payment_method_terms = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $bond->bond_method_type)->first();
                //return $request->waybill_payment_terms;
                $customer_notes = '  قيض عميل  سند رقم' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
                $sales_notes = '  قيض ايرادات  سند رقم' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
                //return $payment_method_terms;
                $message1 = $bond_journal->AddCaptureJournal($account_type, $customer_id, $bond_doc_type->system_code, $total_amount,
                    $cc_voucher_id, $payment_method_terms, $bank_id, $journal_category_id,
                    $cost_center_id, $journal_notes, $customer_notes, $sales_notes);

                if (isset($message1)) {
                    return back()->with(['error' => $message1]);
                }

            }

        }


        ////////////////////اضافه سند صرف لمصروف الطريق
        if ($request->waybill_fees_load > 0) {
            $payment_terms = SystemCode::where('system_code', 57001)
                ->where('company_group_id', $company->company_group_id)->first(); ///الدفع نقدي
            $trip = TripHd::where('trip_hd_id', $waybill_hd->waybill_trip_id)->first();

            $bond_controller = new BondsController();
            $transaction_type = 88;
            $transaction_id = $waybill_hd->waybill_id;
            $bond_car_id = $waybill_hd->waybill_truck_id;
            $j_add_date = Carbon::parse($request->waybill_load_date)->toDateString();

            $customer_type = 'car';
            $bond_bank_id = $request->bank_id ? $request->bank_id : '';
            $total_amount = $request->waybill_fees_load;
            ///مصاريف للسائق
            $bond_ref_no = $waybill_hd->waybill_code;
            $bond_notes = '  سند صرف مصروف الطريق بوليصه  رقم' . $waybill_hd->waybill_code;

            $journal_category_id = 12;

            $journal_type = JournalType::where('journal_types_code', $journal_category_id)
                ->where('company_group_id', $company->company_group_id)->first();

            $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)->where('company_group_id', $company->company_group_id)
                ->first();

            if ($journal_type->bond_type_id) {
                $bond_account_id = $journal_type->account_id_debit;

            } else {
                return back()->with(['error' => 'لا يوجد نشاط مضاف لهذا النوع من القيود']);
            }


            $bond = $bond_controller->addCashBond($payment_terms, $transaction_type, $transaction_id,
                '', $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no,
                $bond_notes, $bond_account_id, 0, 0, $bond_car_id, $j_add_date);


            $journal_controller = new JournalsController();
            $cost_center_id = 54;
            $cc_voucher_id = $bond->bond_id;
            // $bank_id = $request->bank_id ? $request->bank_id : '';

            if ($request->bank_id) {
                $bank_id = $request->bank_id;
            } else {
                // return back()->with(['error' => 'لا يوجد بنك لاضافه قيد سند الصرف']);
                $bank_id = '';
            }

            $customer_id = $waybill_hd->waybill_truck_id;
            $journal_notes = ' اضافه قيد سند صرف البوليصه رقم' . $waybill_hd->waybill_code . 'سند الصرف رقم' . $bond->bond_code;
            $customer_notes = ' اضافه قيد سند صرف  للعميل البوليصه رقم' . $waybill_hd->waybill_code;
            $cash_notes = ' اضافه قيد سند صرف  لبوليصه رقم' . $waybill_hd->waybill_code;
            $message = $journal_controller->AddCashJournal(56002, $customer_id, $bond_doc_type->system_code,
                $total_amount, 0, $cc_voucher_id, $payment_terms, $bank_id,
                $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);

            if (isset($message)) {
                return back()->with(['error' => $message]);
            }
        }

//        if ($request->waybill_item_id == 64006) {
//            $start_date = $request->waybill_load_date;
//            $end_date = $request->waybill_delivery_expected;
//            $truck_id = $request->waybill_truck_id;
//            $rad_count = $request->driver_rad;
//            $trip_hd_fees_1 = $request->waybill_fees_load;
//            $loc_from = $request->waybill_loc_from;
//            $loc_to = $request->waybill_loc_to;
//            $waybill_id = $waybill_hd->waybill_id;
//            $waybill_code = $waybill_hd->waybill_code;
//            $this->addTrip($start_date, $end_date, $truck_id, $rad_count, $trip_hd_fees_1,
//                $loc_from, $loc_to, $waybill_id, $waybill_code);
//        }

        $request->session()->put('waybill_hd', $waybill_hd);
        $request->session()->put('waybill_dt', $waybill_dt);
        $request->session()->put('waybill_item', $waybill_dt->item);
        $request->session()->put('waybill_status', $waybill_hd->status);
        DB::commit();

        return redirect()->route('Waybill.create_car2');
    }

    public
    function create2()
    {
        if (!session('waybill_hd')) {
            return redirect()->route('Waybill.create_car');
        }
        $waybill_hd = session('waybill_hd');
        $waybill_item = session('waybill_item');
        $waybill_dt = session('waybill_dt');
        $waybill_status = session('waybill_status');


        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_shipping = SystemCode::where('sys_category_id', 64)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_payment_methods = SystemCode::where('sys_category_id', 54)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_payment_methods_n = SystemCode::where('sys_category_id', 54)
            ->where('company_group_id', $company->company_group_id)
            ->whereIn('system_code', ['54001', '54002'])
            ->get();

        $sys_codes_payment_type = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)
            ->where('system_code_filter', 'waybill')->get();

        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)
            ->get()->whereIn('system_code', ['41001', '41004']);


        $suppliers = Customer::where('customer_category', 1)->where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', 485)->where('company_group_id', $company->company_group_id)->get();
        $trucks = Trucks::where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $attachment_types = SystemCode::where('sys_category_id', 11)->get();
        $attachments = Attachment::where('transaction_id', $waybill_hd->waybill_id)->where('app_menu_id', 88)->get();
        $notes = Note::where('transaction_id', $waybill_hd->waybill_id)->where('app_menu_id', 88)->get();
        $current_date = Carbon::now()->format('Y-m-d\TH:i');
        return view('Waybill.Cars.create_car2', compact('waybill_hd', 'waybill_dt', 'waybill_status', 'waybill_item', 'sys_codes_item',
            'sys_codes_unit', 'sys_codes_shipping', 'sys_codes_payment_methods', 'sys_codes_payment_methods_n',
            'sys_codes_payment_type', 'sys_codes_waybill_status', 'suppliers', 'customers', 'sys_codes_location', 'companies',
            'employees', 'trucks', 'banks', 'attachment_types', 'attachments', 'notes', 'current_date'));
    }

    public
    function edit($id)
    {

        $waybill_hd = WaybillHd::find($id);

        $waybill_dt = WaybillDt::where('waybill_hd_id', $waybill_hd->waybill_id)->first();

        $waybill_item = $waybill_dt->item;
        $waybill_status = $waybill_hd->status->system_code;
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_item = SystemCode::where('sys_category_id', 28)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_shipping = SystemCode::where('sys_category_id', 64)
            ->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_payment_methods = SystemCode::where('sys_category_id', 54)
            ->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_payment_methods_n = SystemCode::where('sys_category_id', 54)
            ->where('company_group_id', $company->company_group_id)->get()->whereIn('system_code', ['54001', '54002']);

        $sys_codes_payment_type = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)
            ->where('system_code_filter', 'waybill')->get();

        $bonds_cash = Bond::where('bond_type_id', 2)->where('bond_ref_no', $waybill_hd->waybill_code)->latest()->get();

        $bonds_capture = Bond::where('bond_type_id', 1)->where('bond_ref_no', $waybill_hd->waybill_code)->latest()->get()
            ->merge(Bond::where('bond_id', $waybill_hd->bond_id)->get());

        $addition_rols = UsersPermissionsRol::where('user_id', auth()->user()->user_id)->where('rols_id', 13)->first();

        if (isset($addition_rols)) {
            $user_permission = false;
        } else {
            $user_permission = true;
        }


        if ($waybill_hd->status->system_code == 41007) { ///لو حالتها وصلت
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                ->get()->whereIn('system_code', ['41008']);
        } elseif ($waybill_hd->status->system_code == 41008) { ///لو حالتها تم التلسيم
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                ->get()->whereIn('system_code', ['41008']);
        } else if ($waybill_hd->invoice && !$waybill_hd->trip) {
            if ($waybill_hd->customer->cus_type->system_code == 538) {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', ['41001', '41004', '41005']);
            } else {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', ['41001', '41004']);
            }
        } else if ($waybill_hd->customer && !$waybill_hd->invoice) {
            if ($waybill_hd->customer->cus_type->system_code == 539) {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', ['41001', '41004', '41005']);
            } else {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', ['41001', '41004']);
            }

        } elseif ($waybill_hd->invoice && $waybill_hd->customer) {
            if ($waybill_hd->invoice->invoice_status == 121001 && $waybill_hd->customer->cus_type->system_code == 539) {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', ['41001', '41004']);
            } elseif ($waybill_hd->invoice->invoice_status == 121002 || $waybill_hd->invoice->invoice_status == 121003) {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', [$waybill_hd->status->system_code, '41008']);
            } else {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', ['41001', '41004']);
            }
        } elseif ($waybill_hd->invoice && $waybill_hd->trip) {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                ->get()->whereIn('system_code', ['41004', '41001', '41008']);
        } elseif ($waybill_hd->customer) {
            if ($waybill_hd->customer->cus_type->system_code == 539) {
                if (!$waybill_hd->invoice && !$waybill_hd->trip) {
                    $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                        ->get()->whereIn('system_code', ['41001', '41004', '41005']);
                } else {
                    $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                        ->get()->whereIn('system_code', ['41001', '41004']);
                }

            } else {
                $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                    ->get()->whereIn('system_code', ['41001', '41004']);
            }
        } else {
            $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)->where('company_group_id', $company->company_group_id)
                ->get()->whereIn('system_code', ['41001', '41004']);
        }


        $sys_codes_waybill_trip_status = SystemCode::whereIn('system_code_id', [$waybill_hd->waybill_status])->get();
        $sys_codes_waybill_status = $sys_codes_waybill_status->merge($sys_codes_waybill_trip_status);

        $suppliers = Customer::where('customer_category', 1)->where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('customer_category', 2)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('emp_category', 485)->where('company_group_id', $company->company_group_id)->get();
        $trucks = Trucks::where('company_group_id', $company->company_group_id)->get();

        $attachment_types = SystemCode::where('sys_category_id', 11)->get();
        $attachments = Attachment::where('transaction_id', $waybill_hd->waybill_id)->where('app_menu_id', 88)
            ->where('attachment_type', '!=', 2)->get();
        $photos_attachments = Attachment::where('transaction_id', $waybill_hd->waybill_id)->where('app_menu_id', 88)
            ->where('attachment_type', '=', 2)->get();

        $notes = Note::where('transaction_id', $waybill_hd->waybill_id)->where('app_menu_id', 88)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        return view('Waybill.Cars.edit_car', compact('waybill_hd', 'waybill_dt', 'waybill_item', 'sys_codes_item',
            'sys_codes_unit', 'sys_codes_shipping', 'sys_codes_payment_methods', 'sys_codes_payment_methods_n', 'waybill_status',
            'sys_codes_payment_type', 'sys_codes_waybill_status', 'suppliers', 'customers', 'sys_codes_location', 'companies',
            'employees', 'trucks', 'banks', 'attachment_types', 'attachments', 'notes', 'bonds_cash', 'bonds_capture',
            'photos_attachments', 'user_permission'));
    }

    public
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'waybill_sender_mobile_code' => 'numeric',
                'waybill_receiver_mobile_code' => 'numeric'
            ]);

        $company = session('company') ? session('company') : auth()->user()->company;
        $waybill_hd = WaybillHd::where('waybill_id', $id)->first();
        // return $waybill_hd->waybill_payment_method ;

        \DB::beginTransaction();
        if ($waybill_hd->invoice) {
            $waybill_hd->invoice->update([
                'invoice_amount' => $waybill_hd->invoice->invoice_amount - ($waybill_hd->waybill_total_amount)
                    + ($request->waybill_total_amount),
                'invoice_vat_amount' => $waybill_hd->invoice->invoice_vat_amount - $waybill_hd->waybill_vat_amount
                    + $request->waybill_vat_amount,
            ]);

            $invoice_dt = InvoiceDt::where('invoice_id', $waybill_hd->invoice->invoice_id)
                ->first();

            $invoice_dt->update([
                'invoice_item_vat_amount' => $request->waybill_vat_amount,
                'invoice_total_amount' => $request->waybill_total_amount,
            ]);


            $journal_controller = new JournalsController();
            $total_amount = $waybill_hd->invoice->invoice_amount;
            $cc_voucher_id = $waybill_hd->invoice->invoice_id;
            $vat_amount = $waybill_hd->invoice->invoice_vat_amount;
            $sales_notes = '';
            $items_id[] = $waybill_hd->waybill_id;

            $journal_controller->updateWaybillInvoiceJournal($total_amount, $vat_amount, 119, $cc_voucher_id, $items_id, $sales_notes);
        }

        if ($request->waybill_status) {
            $waybill_status = SystemCode::where('system_code', $request->waybill_status)
                ->where('company_group_id', $company->company_group_id)->first();
        }

        if ($request->waybill_discount_amount_form_paid) {
            $waybill_paid_amount = $request->waybill_discount_amount_form_paid;
        } else {
            $waybill_paid_amount = $request->new_waybill_paid_amount + $waybill_hd->waybill_paid_amount;
        }

        ////////////////////////notes
        $now = new DateTime();
        if ($request->waybill_receiver_name != $waybill_hd->waybill_receiver_name) {
            $notes = 'تم تغيير اسم المستلم من ' . $waybill_hd->waybill_receiver_name . ' الي ' . $request->waybill_receiver_name . ' من ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_receiver_mobile != $waybill_hd->waybill_receiver_mobile) {
            $notes = 'تم تغيير هاتف المستلم من ' . $waybill_hd->waybill_receiver_mobile . ' الي ' . $request->waybill_receiver_mobile . ' من ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_receiver_mobile_code != $waybill_hd->waybill_receiver_mobile_code) {
            $notes = 'تم تغيير رقم هويه المستلم من ' . $waybill_hd->waybill_receiver_mobile_code . ' الي ' . $request->waybill_receiver_mobile_code . ' من ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_ticket_no != $waybill_hd->waybill_ticket_no) {
            $notes = 'تم تغيير رقم التعميد من ' . $waybill_hd->waybill_ticket_no . ' الي ' . $request->waybill_ticket_no . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_sender_name != $waybill_hd->waybill_sender_name) {
            $notes = 'تم تغيير اسم الشاحن  من ' . $waybill_hd->waybill_sender_name . ' الي ' . $request->waybill_sender_name . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_sender_mobile != $waybill_hd->waybill_sender_mobile) {
            $notes = 'تم تغيير هاتف الشاحن  من ' . $waybill_hd->waybill_sender_mobile . ' الي ' . $request->waybill_sender_mobile . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_sender_mobile_code != $waybill_hd->waybill_sender_mobile_code) {
            $notes = 'تم تغيير رقم هويه الشاحن  من ' . $waybill_hd->waybill_sender_mobile_code . ' الي ' . $request->waybill_sender_mobile_code . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_total_amount != $waybill_hd->waybill_total_amount) {
            $notes = 'تم تغيير الاجمالي للبوليصه  من ' . $waybill_hd->waybill_total_amount . ' الي ' . $request->waybill_total_amount . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_add_amount != $waybill_hd->waybill_add_amount) {
            $notes = 'تم تغيير الاضافات للبوليصه  من ' . $waybill_hd->waybill_add_amount . ' الي ' . $request->waybill_add_amount . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if ($request->waybill_discount_total != $waybill_hd->waybill_discount_amount) {
            $notes = 'تم تغيير الخصومات للبوليصه  من ' . $waybill_hd->waybill_discount_amount . ' الي ' . $request->waybill_discount_total . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }
        if (isset($waybill_status)) {
            if ($waybill_status->system_code_id != $waybill_hd->waybill_status) {
                $notes = 'تم تغيير الحاله للبوليصه  من ' . $waybill_hd->status->system_code_name_ar . ' الي ' . $waybill_status->system_code_name_ar . ' بواسطه ' . auth()->user()->user_name_ar;
                Note::create([
                    'app_menu_id' => 88,
                    'transaction_id' => $waybill_hd->waybill_id,
                    'notes_serial' => rand(11111, 99999),
                    'notes_data' => $notes,
                    'notes_date' => $now->format('Y-m-d'),
                    'notes_user_id' => auth()->user()->user_id
                ]);
            }
        }

        if ($request->waybill_loc_paid != $waybill_hd->waybill_loc_paid) {
            $waybill_loc_paid = SystemCode::where('system_code_id', $request->waybill_loc_paid)->first();
            if (isset($waybill_hd->LocPaid)) {
                $notes = 'تم تغيير مكان الدفع للبوليصه  من ' . $waybill_hd->LocPaid->system_code_name_ar . ' الي ' . $waybill_loc_paid->system_code_name_ar . ' بواسطه ' . auth()->user()->user_name_ar;
            } else {
                $notes = 'تم تغيير مكان الدفع للبوليصه الي ' . $waybill_loc_paid->system_code_name_ar . ' بواسطه ' . auth()->user()->user_name_ar;
            }
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);

        }

        $waybill_hd->update([
            'waybill_receiver_name' => $request->waybill_receiver_name,
            'waybill_receiver_mobile' => $request->waybill_receiver_mobile,
            'waybill_receiver_mobile_code' => $request->waybill_receiver_mobile_code,
            'waybill_paid_amount' => $waybill_paid_amount,
//            'waybill_payment_terms' => $request->waybill_payment_terms
            'waybill_ticket_no' => $request->waybill_ticket_no,
            'waybill_sender_name' => $request->waybill_sender_name,
            'waybill_sender_mobile' => $request->waybill_sender_mobile,
            'waybill_sender_mobile_code' => $request->waybill_sender_mobile_code,
            'waybill_total_amount' => $request->waybill_total_amount,
            'waybill_vat_amount' => $request->waybill_vat_amount,
            'waybill_add_amount' => $request->waybill_add_amount,
            'waybill_discount_amount' => $request->waybill_discount_total,
            'waybill_status' => isset($waybill_status) ? $waybill_status->system_code_id : $waybill_hd->waybill_status,
            'waybill_delivery_user' => $request->waybill_status == 41008 ? auth()->user()->user_id : '',
            'waybill_delivery_date' => $request->waybill_status == 41008 ? Carbon::now() : '',

            'receiver_name' => $request->receiver_name ? $request->receiver_name : '',
            'receiver_id' => $request->receiver_id ? $request->receiver_id : '',
            'waybill_return' => $request->waybill_return,
            'waybill_loc_paid' => $request->waybill_loc_paid
        ]);

        if (isset($waybill_status)) {
            $waybill_hd->statusM()->attach($waybill_status->system_code_id, ['status_date' => Carbon::now()]);
        }


        $waybill_dt = WaybillDt::where('waybill_hd_id', $waybill_hd->waybill_id)->first();

        if ($request->waybill_car_chase != $waybill_dt->waybill_car_chase) {

            $notes = 'تم تغيير رقم الشاسيه للبوليصه  من ' . $waybill_dt->waybill_car_chase . ' الي ' . $request->waybill_car_chase . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        if ($request->waybill_car_plate != $waybill_dt->waybill_car_plate) {

            $notes = 'تم تغيير رقم الهيكل للبوليصه  من ' . $waybill_dt->waybill_car_plate . ' الي ' . $request->waybill_car_plate . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        if ($request->waybill_car_desc != $waybill_dt->waybill_car_desc) {

            $notes = 'تم تغيير وصف السياره للبوليصه  من ' . $waybill_dt->waybill_car_desc . ' الي ' . $request->waybill_car_desc . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        if ($request->waybill_car_owner != $waybill_dt->waybill_car_owner) {

            $notes = 'تم تغيير مالك السياره للبوليصه  من ' . $waybill_dt->waybill_car_owner . ' الي ' . $request->waybill_car_owner . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        if ($request->waybill_car_color != $waybill_dt->waybill_car_color) {

            $notes = 'تم تغيير لون السياره للبوليصه  من ' . $waybill_dt->waybill_car_color . ' الي ' . $request->waybill_car_color . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        if ($request->waybill_car_model != $waybill_dt->waybill_car_model) {

            $notes = 'تم تغيير موديل السياره للبوليصه  من ' . $waybill_dt->waybill_car_model . ' الي ' . $request->waybill_car_model . ' بواسطه ' . auth()->user()->user_name_ar;
            Note::create([
                'app_menu_id' => 88,
                'transaction_id' => $waybill_hd->waybill_id,
                'notes_serial' => rand(11111, 99999),
                'notes_data' => $notes,
                'notes_date' => $now->format('Y-m-d'),
                'notes_user_id' => auth()->user()->user_id
            ]);
        }

        $waybill_dt->update([
            'waybill_car_chase' => $request->waybill_car_chase,
            'waybill_car_plate' => $request->waybill_car_plate ? $request->waybill_car_plate : $waybill_dt->waybill_car_plate,
            'waybill_car_desc' => $request->waybill_car_desc ? $request->waybill_car_desc : $waybill_dt->waybill_car_desc,
            'waybill_car_owner' => $request->waybill_car_owner ? $request->waybill_car_owner : $waybill_dt->waybill_car_owner,
            'waybill_car_color' => $request->waybill_car_color ? $request->waybill_car_color : $waybill_dt->waybill_car_color,
            'waybill_car_model' => $request->waybill_car_model ? $request->waybill_car_model : $waybill_dt->waybill_car_model,
            'updated_user' => auth()->user()->user_id,
            'waybill_item_vat_amount' => $request->waybill_vat_amount ? $request->waybill_vat_amount : null,
            'waybill_total_amount' => $request->waybill_total_amount,
            'waybill_discount_total' => $request->waybill_discount_total,
            'waybill_add_amount' => $request->waybill_add_amount,
            'waybill_car_notes' => $request->waybill_car_notes
        ]);

        $waybill_hd->refresh();

        if ($waybill_hd->waybillId) {
            $naql_controller = new NaqlWayAPIController();
            $naql_controller->updateWaybill($waybill_hd);
        }


        if ($request->waybill_status == 41005 && $waybill_hd->waybill_paid_amount > 0) {

            $payment_terms = SystemCode::where('system_code', $request->waybill_payment_terms)
                ->where('company_group_id', $company->company_group_id)->first(); ///الدفع

            $bond_controller = new BondsController();
            $transaction_type = 88;
            $transaction_id = $waybill_hd->waybill_id;
            $j_add_date = Carbon::now();

            $customer_type = 'customer';
            $bond_bank_id = $request->bank_id ? $request->bank_id : '';
            $total_amount = $request->waybill_difference_after_discount;

            $bond_ref_no = $waybill_hd->waybill_code;
            $bond_notes = '  سند صرف بوليصه رقم' . $waybill_hd->waybill_code;

            $journal_category_id = 12;

            $journal_type = JournalType::where('journal_types_code', $journal_category_id)
                ->where('company_group_id', $company->company_group_id)->first();

            $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)
                ->first();

            $customer_id = $waybill_hd->customer_id;
            $bond_account_id = $waybill_hd->customer->customer_account_id;


            $bond = $bond_controller->addCashBond($payment_terms, $transaction_type, $transaction_id,
                $customer_id, $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no,
                $bond_notes, $bond_account_id, 0, 0, '', $j_add_date);


            $journal_controller = new JournalsController();
            $cost_center_id = 54;
            $cc_voucher_id = $bond->bond_id;
            // $bank_id = $request->bank_id ? $request->bank_id : '';

            if ($request->bank_id) {
                $bank_id = $request->bank_id;
            } else {
                // return back()->with(['error' => 'لا يوجد بنك لاضافه قيد سند الصرف']);
                $bank_id = '';
            }

            $customer_id = $waybill_hd->waybill_truck_id;
            $journal_notes = '  قيد سند صرف البوليصه رقم' . $waybill_hd->waybill_code . 'سند الصرف رقم' . $bond->bond_code;
            $customer_notes = '  قيد سند صرف  للعميل البوليصه رقم' . $waybill_hd->waybill_code;
            $cash_notes = '  قيد سند صرف  لبوليصه رقم' . $waybill_hd->waybill_code;
            $message = $journal_controller->AddCashJournal(56002, $customer_id, $bond_doc_type->system_code,
                $total_amount, 0, $cc_voucher_id, $payment_terms, $bank_id,
                $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);

            if (isset($message)) {
                return back()->with(['error' => $message]);
            }
        }

        ////اضافه فاتوره مرتجع
        if ($request->waybill_status == 41005 && $waybill_hd->invoice && $waybill_hd->customer->cus_type->system_code == 538 &&
            $waybill_hd->waybill_payment_method != 54003) {

            $last_invoice_reference = CompanyMenuSerial::where('branch_id', session('branch')['branch_id'])
                ->where('app_menu_id', 119)->latest()->first();

            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV - ' . session('branch')['branch_id'] . ' - 1';
                CompanyMenuSerial::create([
                    'company_group_id' => $waybill_hd->company_group_id,
                    'company_id' => $waybill_hd->company_id,
                    'app_menu_id' => 119,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'branch_id' => session('branch')['branch_id'],
                    'created_user' => auth()->user()->user_id
                ]);

            }
            $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
                ->where('acc_period_month', Carbon::now()->format('m'))
                ->where('acc_period_is_active', 1)->first();

            $invoice_hd = InvoiceHd::create([
                'company_group_id' => $waybill_hd->company_group_id,
                'company_id' => $waybill_hd->company_id,
                'acc_period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'invoice_date' => Carbon::now(),
                'invoice_due_date' => Carbon::now(),
                'invoice_amount' => $waybill_hd->waybill_total_amount * (-1),
                'invoice_vat_rate' => $waybill_hd->waybill_vat_rate,
                // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
                'invoice_vat_amount' => $waybill_hd->waybill_vat_amount,
                'invoice_discount_total' => 0,
                'invoice_down_payment' => 0,
                'invoice_total_payment' => 0,
                'invoice_notes' => '  فاتوره مرتجع بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
                'invoice_no' => $string_number,
                'created_user' => auth()->user()->user_id,
                'branch_id' => session('branch')['branch_id'],
                'customer_id' => $waybill_hd->customer_id,
                'invoice_is_payment' => 1,
                'invoice_type' => 8, ///فاتوره السياره
                'invoice_status' => 121003,///الحاله فاتوره
                'customer_address' => 'الممكله العربيه السعوديه',
                'customer_name' => $request->waybill_sender_name,
                'customer_phone' => $request->waybill_sender_mobile,
            ]);

            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                new TaxNoElement($waybill_hd->company_tax_no),
                new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();

            $invoice_hd->update(['qr_data' => $qr]);

            $invoice_dt = InvoiceDt::create([
                'company_group_id' => $waybill_hd->company_group_id,
                'company_id' => $waybill_hd->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $waybill_hd->detailsCar->waybill_item_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => $waybill_hd->detailsCar->waybill_qut_received_customer,
                'invoice_item_price' => $waybill_hd->detailsCar->waybill_item_price,
                'invoice_item_amount' => $waybill_hd->detailsCar->waybill_item_amount,
                'invoice_item_vat_rate' => $waybill_hd->waybill_vat_rate,
                'invoice_item_vat_amount' => $waybill_hd->waybill_vat_amount,
                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $waybill_hd->waybill_total_amount,
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => '  فاتوره مرتجع بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now()
            ]);

            $invoice_dt->invoice_reference_no = $waybill_hd->waybill_id;
            $invoice_dt->save();

            $invoice_journal = new JournalsController();
            $total_amount = $invoice_hd->invoice_amount * (-1);
            $cc_voucher_id = $invoice_hd->invoice_id;
            $customer_notes = 'قيد فاتورة مرتجع شحن سياره رقم' . ' ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $vat_notes = '  قيد ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $sales_notes = '  قيد مرتجع للفاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $notes = '  قيد فاتوره مرتجع رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
            $message = $invoice_journal->addSalesInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
                $customer_notes, 95, $vat_notes, $sales_notes, 49, $items_id = [],
                $items_amount = [], $notes);

            if ($message) {
                return back()->with(['error' => $message]);
            }


        }

        if ($waybill_hd->waybill_payment_method == 54001 || $waybill_hd->waybill_payment_method == 54002) {

            $payment_method = SystemCode::where('system_code', $request->waybill_payment_terms)
                ->where('company_group_id', $company->company_group_id)->first();

            // return  $waybill_hd->waybill_payment_terms;

            if ($request->new_waybill_paid_amount > 0) {

                ////////////////////////////
                /// اضافه سند قبض وقيد علي سند القبض
                $bond_controller = new BondsController();
                $transaction_type = 88; ///بوليصه السيارات
                $transaction_id = $waybill_hd->waybill_id;
                $customer_id = $waybill_hd->customer_id;
                $customer_type = 'customer';

                $total_amount = $request->new_waybill_paid_amount;
                $bond_doc_type = SystemCode::where('system_code', 58002)
                    ->where('company_group_id', $company->company_group_id)->first(); ////ايرادات مبيعات
                //  return $bond_doc_type;
                $bond_ref_no = $waybill_hd->waybill_code;
                $bond_notes = '  سداد بوليصه رقم ' . ' ' . $waybill_hd->waybill_code . ' ' . 'بواسطه' . ' ' . $waybill_hd->waybill_sender_name;
                $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id,
                    $customer_id, $customer_type, '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

                $invoice_hd = $waybill_hd->invoice;
                $invoice_hd->bond_code = $bond->bond_id;
                $invoice_hd->bond_date = Carbon::now();
                $invoice_hd->invoice_total_payment = $invoice_hd->invoice_total_payment + $request->new_waybill_paid_amount;
                $invoice_hd->save();

                $bond_journal = new JournalsController();
                $cc_voucher_id = $bond->bond_id;
                $journal_category_id = 4; ////سند قبض بوليصه سياره
                $cost_center_id = 53;
                $account_type = 56002;
                $journal_notes = ' سند قبض رقم ' . $bond->bond_code . 'بوليصه' . ' ' . $waybill_hd->waybill_code . ' ' . $waybill_hd->waybill_sender_name;
                $payment_method_terms = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $bond->bond_method_type)->first();

                $customer_notes = '  قيض  رقم' . $bond->bond_code . 'بوليصه' . ' ' . $waybill_hd->waybill_code . ' ' . $waybill_hd->waybill_sender_name;
                $sales_notes = '  قيض  رقم' . $bond->bond_code . 'بوليصه' . ' ' . $waybill_hd->waybill_code . ' ' . $waybill_hd->waybill_sender_name;
                // return $payment_method_terms;
                $message = $bond_journal->AddCaptureJournal($account_type, $customer_id, $bond_doc_type->system_code, $total_amount,
                    $cc_voucher_id, $payment_method_terms, $bank_id = '', $journal_category_id,
                    $cost_center_id, $journal_notes, $customer_notes, $sales_notes);

                if ($message) {
                    return back()->with(['error' => $message]);
                }
            }

        }

        \DB::commit();

        if (request()->qr == 'create_2') {
            $request->session()->put('waybill_hd', $waybill_hd);
            $request->session()->put('waybill_dt', $waybill_dt);
            $request->session()->put('waybill_item', $waybill_dt->item);
            $request->session()->put('waybill_status', $waybill_hd->status);

            return redirect()->route('Waybill.create_car2');

        } else {
            return redirect()->route('Waybill.edit_car', $waybill_hd->waybill_id)
                ->with(['success' => 'تم تحديث البوليصه']);

            //  return redirect()->route('WaybillCar')->with(['success' => 'تم تحديث البوليصه']);
        }
    }

    public
    function export()
    {
        $company = session('company') ? session('company') : auth()->user()->company;


        $way_pills = WaybillHd::where('company_id', $company->company_id)
            ->where('waybill_type_id', 4)->get();


        if (request()->created_date_from) {

            // return explode('',json_decode());
            $query = WaybillHd::where('waybill_type_id', 4)->where('company_id', $company->company_id);
            $way_pills = $query->get();

            if (request()->created_date_from && request()->created_date_to) {
                $query = $query->whereDate('created_date', ' >= ', request()->created_date_from)
                    ->whereDate('created_date', ' <= ', request()->created_date_to);
                $way_pills = $query->get();
            }

            if (request()->customers_id) {
                $query = $query->whereIn('customer_id', request()->customers_id);
                $way_pills = $query->get();
            }

            if (request()->statuses_id) {
                $query = $query->whereIn('waybill_status', request()->statuses_id);
                $way_pills = $query->get();

            }

            if (request()->expected_date_from && request()->expected_date_to) {
                $query = $query->whereDate('waybill_delivery_expected', ' >= ', request()->expected_date_from)
                    ->whereDate('waybill_delivery_expected', ' <= ', request()->expected_date_to);
                $way_pills = $query->get();
            }

        }

        return Excel::download(new \App\Exports\WayBillExports($way_pills), 'way_bills.xlsx');
    }


//    public
//    function addTrip($start_date, $end_date, $truck_id, $rad_count, $trip_hd_fees_1,
//                     $loc_from, $loc_to, $waybill_id, $waybill_code)
//    {
//        $company = session('company') ? session('company') : auth()->user()->company;
//        $branch = session('branch');
//
//        $last_trip_hd_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
//            ->where('company_id', $company->company_id)
//            ->where('app_menu_id', 104)->latest()->first();
//
//        if (isset($last_trip_hd_serial)) {
//            $last_trip_hd_serial_no = $last_trip_hd_serial->serial_last_no;
//            $array_number = explode('-', $last_trip_hd_serial_no);
//            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
//            $string_trip_hd_number = implode('-', $array_number);
//            $last_trip_hd_serial->update(['serial_last_no' => $string_trip_hd_number]);
//        } else {
//            $string_trip_hd_number = 'TRP-' . session('branch')['branch_id'] . '-1';
//            CompanyMenuSerial::create([
//                'company_group_id' => $company->company_group_id,
//                'company_id' => $company->company_id,
//                'app_menu_id' => 104,
//                'branch_id' => session('branch')['branch_id'],
//                'acc_period_year' => Carbon::now()->format('y'),
//                'serial_last_no' => $string_trip_hd_number,
//                'created_user' => auth()->user()->user_id
//            ]);
//        }
//
//        $trip_hd_status = SystemCode::where('system_code', 39002)
//            ->where('company_id', $company->company_id)->first();
//
//        $waybill_status = SystemCode::where('system_code', 41006)
//            ->where('company_id', $company->company_id)->first();
//
//        $truck = Trucks::where('truck_id', $truck_id)->first();
//
//        // return $start_date;
//        $trip = TripHd::create([
//            'trip_hd_code' => $string_trip_hd_number,
//            'company_group_id' => $company->company_group_id,
//            'company_id' => $company->company_id,
//            'branch_id' => session('branch')['branch_id'],
//            'trip_hd_date' => Carbon::now(),
//            'trip_hd_start_date' => $start_date,
//            'trip_hd_started_date' => Carbon::now(),
//            'trip_hd_end_date' => $end_date,
//            'truck_id' => $truck_id,
//            'driver_id' => $truck->truck_driver_id,
//            'driver_mobil' => $truck->driver->emp_work_mobile,
//            'driver_rad_count' => $rad_count,
//            'trip_line_hd_id' => 11,
//            'truck_meter_start' => 0,
//            'truck_meter_end' => 100,
//            'trip_hd_distance' => 100,
//            'trip_hd_fees_1' => $trip_hd_fees_1,
//            'trip_hd_fees_2' => 0,
////            'trip_hd_started_date' => $request->trip_hd_started_date,
////            'trip_hd_ended_date' => $request->trip_hd_ended_date,
//            'trip_hd_status' => $trip_hd_status->system_code_id,
//            'created_user' => auth()->user()->user_id
//        ]);
//
//
//        $string_trip_dt_number = $trip->trip_hd_code . ' - 1';
//
//        TripDt::create([
//            'trip_hd_id' => $trip->trip_hd_id,
//            'company_group_id' => $company->company_group_id,
//            'company_id' => $company->company_id,
//            'branch_id' => session('branch')['branch_id'],
//            'trip_hd_code' => $string_trip_dt_number,
//            'trip_dt_serial' => 1,
//            'trip_dt_loc_from' => $loc_from,
//            'trip_dt_loc_to' => $loc_to,
//            'waybill_transit_loc_1' => $loc_to,
//            'trip_dt_start_date' => $trip->trip_hd_start_date,
//
//            'trip_dt_end_date' => $trip->trip_hd_end_date,
//            'waybill_id' => $waybill_id,
//            'trip_waybill_status' => SystemCode::where('company_id', $company->company_id)->where('system_code', 39001)
//                ->where('company_id', $company->company_id)->first()->system_code_id,
//
//        ]);
//
//        $waybill = WaybillHd::where('waybill_id', $waybill_id)->first();
//
//        $waybill->update([
//            'waybill_trip_id' => $trip->trip_hd_id,
//            'waybill_transit_loc_1' => $loc_to,
//            'waybill_truck_id' => $trip->truck_id,
//            'waybill_status' => $waybill_status->system_code_id,
//            'waybill_trip_status' => $trip_hd_status->system_code_id
//        ]);
//
//        $bond = Bond::where('bond_ref_no', $waybill_code)->first();
//
//        $bond->update([
//            'bond_ref_no' => $trip->trip_hd_code
//
//        ]);
//
//        if ($waybill->journal_dt_id) {
//            $journal_dt = JournalDt::where('journal_dt_id', $waybill->journal_dt_id)->first();
//            $journal_dt->update(['cc_car_id' => $waybill->waybill_truck_id]);
//        }
//
//    }

    public
    function createBack($id)
    {
        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $waybill_hd = WaybillHd::find($id);

        $branch = session('branch');
        $last_waypill_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 88)->latest()->first();

        if (isset($last_waypill_serial)) {
            $last_waypill_serial_no = $last_waypill_serial->serial_last_no;
            $array_number = explode('-', $last_waypill_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_waybill_number = implode('-', $array_number);
            $last_waypill_serial->update(['serial_last_no' => $string_waybill_number]);
        } else {
            $string_waybill_number = 'CAR - ' . session('branch')['branch_id'] . ' - 1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 88,
                'acc_period_year' => Carbon::now()->format('y'),
                'branch_id' => session('branch')['branch_id'],
                'serial_last_no' => $string_waybill_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $waybill_status = SystemCode::where('system_code', 41004)->where('company_group_id', $company->company_group_id)->first();
        $waybill_hd_r = WaybillHd::create([
            'company_group_id' => $waybill_hd->company_group_id,
            'company_id' => $company->company_id,
            'waybill_code' => $string_waybill_number,
            'waybill_type_id' => 4, ////////////////عوده
            'branch_id' => session('branch')['branch_id'],
            'waybill_ticket_no' => $waybill_hd->waybill_ticket_no,
            'waybill_payment_terms' => $waybill_hd->waybill_payment_terms,
            'waybill_payment_method' => $waybill_hd->waybill_payment_method,
            'waybill_paid_amount' => $waybill_hd->waybill_paid_amount,
            'waybill_status' => $waybill_status->system_code_id,
            'customer_id' => $waybill_hd->customer_id,

            'customer_contract' => $waybill_hd->customer_contract,
            'created_user' => auth()->user()->user_id,
            'waybill_create_user' => auth()->user()->user_id,
            'waybill_loc_from' => $waybill_hd->waybill_loc_to,
            'waybill_transit_loc_1' => $waybill_hd->waybill_loc_to,
            'waybill_loc_to' => $waybill_hd->waybill_loc_from,
            'waybill_sender_name' => $waybill_hd->waybill_sender_name,
            'waybill_sender_mobile' => $waybill_hd->waybill_sender_mobile,
            'waybill_sender_mobile_code' => $waybill_hd->waybill_sender_mobile_code,
            'waybill_receiver_name' => $waybill_hd->waybill_receiver_name,
            'waybill_receiver_mobile' => $waybill_hd->waybill_receiver_mobile,
            'waybill_receiver_mobile_code' => $waybill_hd->waybill_receiver_mobile_code,
            'waybill_driver_id' => $waybill_hd->waybill_driver_id,

            'waybill_truck_id' => $waybill_hd->waybill_truck_id,
            'waybill_load_date' => $waybill_hd->waybill_load_date,
            'waybill_unload_date' => $waybill_hd->waybill_unload_date,
            'waybill_vat_rate' => $waybill_hd->waybill_vat_rate,
            'waybill_vat_amount' => $waybill_hd->waybill_vat_amount, ///customer
            'waybill_total_amount' => $waybill_hd->waybill_total_amount, ///customer
            'waybill_delivery_expected' => $waybill_hd->waybill_delivery_expected,
            'waybill_trip_status' => SystemCode::where('system_code', 39001)->first()->system_code_id,

            'waybill_add_amount' => $waybill_hd->waybill_add_amount,
            'waybill_discount_amount' => $waybill_hd->waybill_discount_amount,
            'waybill_return_no' => $waybill_hd->waybill_id,
            'waybill_return' => $waybill_hd->waybill_return
        ]);

        $waybill_hd->waybill_return_no = $waybill_hd_r->waybill_id;
        $waybill_hd->save();

        $waybill_hd_r->statusM()->attach($waybill_status->system_code_id, ['status_date' => Carbon::now()]);

        $customer = Customer::where('customer_id', $waybill_hd->customer_id)->first();

        if ($customer->cus_type->system_code == 538) {
            if ($waybill_hd_r->waybill_payment_method == 54001 || $waybill_hd_r->waybill_payment_method == 54002) {
                $category = SMSCategory::where('company_id', $waybill_hd->company_id)
                    ->where('sms_name_ar', 'sms waybill tracking')->first();


                if (isset($category) && $category->sms_is_sms) {
                    $mobNo = ' + 966' . substr($waybill_hd_r->waybill_sender_mobile, 1);

                    $parm1 = $waybill_hd->waybill_code;

                    $url = asset('tracking / ' . $waybill_hd_r->waybill_id);
                    // return $url;
                    $shortUrl = SMS\smsQueueController::getShortUrl($url);

                    $Response = SMS\smsQueueController::PushSMS($category, $mobNo, $parm1, null, null, null, $shortUrl);
                }
            }
        }

        $item = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code_id', $waybill_hd->details->waybill_item_id)->first();


        WaybillDt::create([
            'waybill_hd_id' => $waybill_hd_r->waybill_id,
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],

            'waybill_item_id' => $item->system_code_id,
            'waybill_item_vat_rate' => $waybill_hd->waybill_vat_rate,

            'waybill_item_vat_amount' => $waybill_hd->waybill_vat_amount,
            'waybill_car_chase' => $waybill_hd->details->waybill_car_chase,
            'waybill_car_plate' => $waybill_hd->details->waybill_car_plate,
            'waybill_car_desc' => $waybill_hd->details->waybill_car_desc,
            'waybill_car_owner' => $waybill_hd->details->waybill_car_owner,
            'waybill_car_color' => $waybill_hd->details->waybill_car_color,
            'waybill_car_model' => $waybill_hd->details->waybill_car_model,
            'waybill_discount_total' => $waybill_hd->details->waybill_discount_total,
            'waybill_add_amount' => $waybill_hd->details->waybill_add_amount,
            'waybill_distance' => $waybill_hd->details->waybill_distance,

//            customer
            'waybill_item_quantity' => $waybill_hd->details->waybill_item_quantity,
            'waybill_item_unit' => 0,
            'waybill_item_price' => $waybill_hd->details->waybill_item_price,
            'waybill_item_amount' => $waybill_hd->details->waybill_item_price,
            'waybill_total_amount' => $waybill_hd->details->waybill_total_amount,
            'waybill_qut_requried_customer' => $waybill_hd->details->waybill_qut_requried_customer,
            'waybill_qut_received_customer' => $waybill_hd->details->waybill_qut_received_customer,
//           supplier
            'waybill_price_supplier' => $waybill_hd->details->waybill_price_supplier,
            'waybill_vat_amount_supplier' => $waybill_hd->details->waybill_vat_amount_supplier,
            'waybill_amount_supplier' => $waybill_hd->details->waybill_amount_supplier,
            'waybill_qut_requried_supplier' => $waybill_hd->details->waybill_qut_requried_supplier,
            'waybill_qut_received_supplier' => $waybill_hd->details->waybill_qut_received_supplier,
            'waybill_fees_load' => $waybill_hd->details->waybill_fees_load,
            'created_user' => auth()->user()->user_id,
            'waybill_fees_difference' => $waybill_hd->details->waybill_fees_difference
        ]);

        $waybill_hd_r->waybill_invoice_id = $waybill_hd->waybill_invoice_id;
        $waybill_hd_r->save();


        $waybill_hd_r->bond_code = $waybill_hd->bond_code;
        $waybill_hd_r->bond_id = $waybill_hd->bond_id;
        $waybill_hd_r->bond_date = $waybill_hd->bond_date;
        $waybill_hd_r->save();

        DB::commit();

        return redirect()->route('Waybill.edit_car', $waybill_hd_r->waybill_id);
    }


    public
    function getBrandDetails()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $brand = CarRentBrand::where('brand_name_ar', request()->brand_id)
            ->where('company_group_id', $company->company_group_id)->first();
        return response()->json(['data' => $brand->branddt]);
    }

    public
    function getBrandDetailsCarSize()
    {
        $brand_dt = CarRentBrandDt::find(request()->brand_dt_id);
        return response()->json(['data' => $brand_dt->brand_dt_size]);
    }

    public
    function getDiscountTypeByCompany()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $discount_flag = $company->co_disc_arrived_flag;
        if ($discount_flag == 1) {
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }

    public
    function addDaysToDate()
    {
        $date = request()->date;

        $new_date = Carbon::parse($date)->addDays(request()->days_count)->format('Y-m-d\TH:i');
        return response()->json(['data' => $new_date]);
    }

    public
    function checkWaybillByPlateNo()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $waybill_hd = WaybillHd::where('company_group_id', $company->company_group_id)
            ->where('waybill_type_id', 4)
            ->whereHas('details', function ($q) {
                $q->where('waybill_car_plate', request()->waybill_car_plate);
            })->where('waybill_return', 2)->first();

        if (isset($waybill_hd)) {
            if ($waybill_hd->waybill_return_no) {
                return response()->json(['success' => '']);
            } else {
                return response()->json(['error' => 'يوجد بوليصه ذهاب وعوده بنفس رقم اللوحه ولم يتم عمل عوده لها']);
            }
        } else {
            return response()->json(['success' => '']);
        }
    }


    public
    function checkWaybillByPlateChaseNo()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        if (request()->waybill_car_plate) {
            $waybill_hd_1 = WaybillHd::where('company_group_id', $company->company_group_id)
                ->where('waybill_type_id', 4)
                ->whereHas('status', function ($query) {
                    $query->where('system_code', '=', 41004);
                })
                ->whereHas('details', function ($q) {
                    $q->where('waybill_car_plate', request()->waybill_car_plate);
                })->first();

            $waybill_hd_2 = WaybillHd::where('company_group_id', $company->company_group_id)
                ->where('waybill_type_id', 4)
                ->whereHas('status', function ($query) {
                    $query->where('system_code', '=', 41004);
                })
                ->whereHas('waybillCarDts', function ($q) {
                    $q->where('waybill_car_plate', request()->waybill_car_plate);
                })->first();

            if (isset($waybill_hd_1) || isset($waybill_hd_2)) {
                return response()->json(['error' => 'الرقم مسجل سابقا في بوليصه لم يتم شحنها']);
            } else {
                return response()->json(['success' => '']);
            }
        }

        if (request()->waybill_car_chase) {
            $waybill_hd_1 = WaybillHd::where('company_group_id', $company->company_group_id)
                ->where('waybill_type_id', 4)
                ->whereHas('status', function ($query) {
                    $query->where('system_code', '=', 41004);
                })
                ->whereHas('details', function ($q) {
                    $q->where('waybill_car_chase', request()->waybill_car_chase);
                })->first();

            $waybill_hd_2 = WaybillHd::where('company_group_id', $company->company_group_id)
                ->where('waybill_type_id', 4)
                ->whereHas('status', function ($query) {
                    $query->where('system_code', '=', 41004);
                })
                ->whereHas('waybillCarDts', function ($q) {
                    $q->where('waybill_car_chase', request()->waybill_car_chase);
                })->first();

            if (isset($waybill_hd_1) || isset($waybill_hd_2)) {
                return response()->json(['error' => 'الرقم مسجل سابقا في بوليصه لم يتم شحنها']);
            } else {
                return response()->json(['success' => '']);
            }
        }

    }


    public
    function updateCars(Request $request)
    {
        foreach ($request->waybill_dt_id as $k => $waybill_dt_id) {
            $waybillCarDt = waybillDtCar::where('waybill_dt_id', $waybill_dt_id)->first();
            $waybillCarDt->update([
                'waybill_car_chase' => $request->waybill_car_chase[$k],
                'waybill_car_plate' => $request->waybill_car_chase[$k],
                'waybill_car_desc' => $request->waybill_car_desc[$k],
                'waybill_car_owner' => $request->waybill_car_owner[$k],
                'waybill_car_color' => $request->waybill_car_color[$k],
                'waybill_car_model' => $request->waybill_car_model[$k],
            ]);
        }
        $request->session()->flash('cars', true);
        return back();
    }

    public function storePhoto(Request $request)
    {
        $img = $request->image;
        $file = $this->getPhoto($img);

        Attachment::create([
            'attachment_name' => 'waybill-car',
            'attachment_type' => 2,
            'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
            'attachment_file_url' => $file,
            'attachment_data' => Carbon::now(),
            'transaction_id' => $request->waybill_id,
            'app_menu_id' => 88,
            'created_user' => auth()->user()->user_id,
        ]);

        return back()->with(['success' => 'تم اضافه الصوره']);
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Files"), $name);
        return $name;
    }

    public function deleteCar(Request $request)
    {

        $waybill_dt_car = waybillDtCar::find($request->waybill_dt_id);

        $waybill = WaybillHd::where('waybill_id', $waybill_dt_car->waybill_hd_id)->first();

        $total_before_vat = ($waybill->detailsCar->waybill_qut_received_customer * $waybill->detailsCar->waybill_item_price)
            + $waybill->waybill_add_amount - $waybill->waybill_discount_total;
        $vat_amount = $total_before_vat * $waybill->waybill_vat_rate;

        $waybill->waybill_vat_amount = $vat_amount;
        $waybill->waybill_total_amount = $vat_amount + $total_before_vat;
        $waybill->save();

        $waybill->detailsCar->waybill_qut_received_customer = $waybill->detailsCar->waybill_qut_received_customer - 1;
        $waybill->detailsCar->waybill_item_quantity = $waybill->detailsCar->waybill_item_quantity - 1;
        $waybill->waybill_total_amount = $vat_amount + $total_before_vat;
        $waybill->waybill_item_vat_amount = $vat_amount;
        $waybill->waybill_due_amount = $waybill->waybill_total_amount - $waybill->waybill_paid_amount;
        $waybill->detailsCar->save();

        $notes = 'تم حذف السياره بالشاسيه رقم ' . $waybill_dt_car->waybill_car_chase;

        $now = new DateTime();

        Note::create([
            'app_menu_id' => 88,
            'transaction_id' => $waybill->waybill_id,
            'notes_serial' => rand(11111, 99999),
            'notes_data' => $notes,
            'notes_date' => $now->format('Y-m-d'),
            'notes_user_id' => auth()->user()->user_id
        ]);

        $waybill_dt_car->delete();

        return 'success';

    }

}
