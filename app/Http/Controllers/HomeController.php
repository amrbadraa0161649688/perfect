<?php

namespace App\Http\Controllers;

use App\Models\CarRentCars;
use App\Models\CarRentContract;
use App\Models\CarRentMovement;
use App\Models\Employee;

use App\Models\Trucks;
use App\Models\User;
use App\Models\WaybillHd;
use App\Models\WaybillDt;
use App\Models\SystemCode;
use App\Models\Company;
use App\Models\Branch;
use Carbon\Carbon;
use DateTime;
use Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
/////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////الشاشه النظام الاساسيه لجميع الموظفين
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        // $data_emp1 = $this->employeeDashboard1();
        $employees = User::where('user_id', auth()->user()->user_id)->get();


        return view('index', compact('employees'));
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////الشاشه النظام الاساسيه لدخول مبيعات الجوال المحطات
    public function sales()
    {
        return view('index2');
    }

    public function store()
    {
        return view('index3');
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه  للاداره
    public function main()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $data_emp1 = $this->employeeDashboard1();

        // $employee_controller = new EmployeeDashboardController();
        //  $emp_gen = $employee_controller->maleFemalePercentage();
        //  $emp_nationality = $employee_controller->employeeNationality();

        //  $truck_status = $employee_controller->truckPercentage();

        $emp_gen = $this->maleFemalePercentage();
        $emp_nationality = $this->employeeNationality1();
        $truck_status = $this->truckPercentage();

        $system_code_saudi = SystemCode::where('system_code', 25)->first();
        $system_code_end = SystemCode::where('system_code', 118)
            ->where('company_group_id', $company->company_group_id)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();

        $data_sales = $this->salesDashboard1();
        $data_sales_amount = $this->salesDashboard2();
        return view('Dashboard.main', compact('data_emp1', 'emp_gen', 'emp_nationality', 'employees_saudi',
            'employees_non_saudi', 'truck_status', 'data_sales', 'data_sales_amount'));
    }

    public function employeeNationality()
    {


        $company = session('company') ? session('company') : auth()->user()->company;

        $app = app();
        $data = $app->make('stdClass');

        $system_code_saudi = SystemCode::where('system_code', 25)->first();


        $date = new Datetime();
        $month = $date->format('m');
        $year = $date->format('Y');

        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }


        $data->columns = [
            ['data1'],
            ['data2'],
        ];


        foreach ($months as $k => $month) {
//                return  $columns_sm[--$k];
            $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
                ->whereMonth('emp_work_start_date', '=', $month)
                ->whereYear('emp_work_start_date', '=', $year)
                ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

            $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
                ->whereMonth('emp_work_start_date', '=', $month)
                ->whereYear('emp_work_start_date', '=', $year)
                ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();

            array_push($data->columns[0], $employees_saudi);
            array_push($data->columns[1], $employees_non_saudi);

        }

        $data->type = 'bar';

        $data->groups = [
            ['data1', 'data2']
        ];

        $colors = $app->make('stdClass');
        // الرمادي
        $colors->data1 = '#434A54';

        $colors->data2 = '#006400';
        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'سعودي';
        $object_names->data2 = 'غير سعودي';
        $data->names = $object_names;

        return $data;
    }

    public function employeeDashboard1()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $departments = $company->departments;
        $names = $departments->pluck('department_name_ar')->toArray();

        $app = app();
        $object_names = $app->make('stdClass');

        foreach ($names as $k => $name) {
            $x = 'data' . ++$k;
            $object_names->{$x} = $name;
        }


        $colors = $app->make('stdClass');

        $colors_array = ['#434A54', '#006400'];


        $date = new DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');

        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }

        foreach ($departments as $k => $department) {
            $x = 'data' . ++$k;
            $colors->{$x} = $colors_array[array_rand($colors_array)];
        }

        $data = $app->make('stdClass');


        foreach ($departments as $k => $department) {

            $this->i = $k;
            $columns_sm[] = ['data' . ++$k];

            foreach ($months as $key => $month) {
//                return  $columns_sm[--$k];
                $employees_count = Employee::whereHas('contractActive', function ($query) use ($department, $month, $year) {
                    $query->whereMonth('created_date', '=', $month)
                        ->whereYear('created_date', '=', $year)
                        ->whereHas('job.department', function ($query2) use ($department) {
                            $query2->where('department_id', '=', $department->department_id);
                        });
                })->count();

                array_push($columns_sm[$this->i], $employees_count);
            }

        }


        $data->columns = $columns_sm;

        $data->type = 'bar';
        $data->colors = $colors;
        $data->names = $object_names;


        return $data;
    }

    public function maleFemalePercentage()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $app = app();
        $data = $app->make('stdClass');

        $system_code_male = SystemCode::where('system_code', 487)
            ->where('company_group_id', $company->company_group_id)->first();

        $system_code_female = SystemCode::where('system_code', 488)
            ->where('company_group_id', $company->company_group_id)->first();

        $system_code_end = SystemCode::where('system_code', 118)
            ->where('company_group_id', $company->company_group_id)->first();

        $employees_male = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_gender', $system_code_male->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();


        $employees_female = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_gender', $system_code_female->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();

        //     $employees_male = 30;

        //   $employees_female = 10;

        $employee_total = $employees_male + $employees_female;


        $employees_male_perc = $employee_total > 0 ? number_format(($employees_male / $employee_total) * 100, 2) : 0;;
        $employees_female_perc = $employee_total > 0 ? number_format(($employees_female / $employee_total) * 100, 2) : 0;;


        $data->columns = [
            ['data1', $employees_male],
            ['data2', $employees_female],
        ];

        $data->type = 'donut';

        $colors = $app->make('stdClass');
        $colors->data1 = '#434A54';
        $colors->data2 = '#2E8B57';
        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'ذكر';
        $object_names->data2 = 'آنثي';
        $data->names = $object_names;

        return $data;

    }

    public function salesDashboard1()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        // $departments = $company->departments;
        // $names = $departments->pluck('department_name_ar')->toArray();

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 10)->whereIn('system_code', ['100002', '100004'])->where('company_group_id', $company->company_group_id)
            ->get();

        $names = $sys_codes_sales_types->pluck('system_code_name_ar')->toArray();


        $app = app();
        $object_names = $app->make('stdClass');

        foreach ($names as $k => $name) {
            $x = 'data' . ++$k;
            $object_names->{$x} = $name;
        }


        $colors = $app->make('stdClass');

        $colors_array = ['#434A54', '#006400'];


        $date = new DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');

        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }

        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {
            $x = 'data' . ++$k;
            $colors->{$x} = $colors_array[array_rand($colors_array)];
        }

        $data = $app->make('stdClass');


        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {


            $this->i = $k;
            $columns_sm[] = ['data' . ++$k];


            foreach ($months as $key => $month) {
//               return  $columns_sm[--$k];

                $sales_count = WaybillHd::whereHas('waybillActive', function ($query) use ($sys_codes_sales_type, $month, $year) {
                    $query->whereMonth('created_date', '=', $month)
                        ->whereYear('created_date', '=', $year)
                        ->where('waybill_type_id', '=', $sys_codes_sales_type->system_code_emp_id);

                })->where('company_group_id', $company->company_group_id)->count();

                array_push($columns_sm[$this->i], $sales_count);
            }
        }

        //   $employees_count = Employee::whereHas('contractActive', function ($query) use ($department, $month, $year) {
        //       $query->whereMonth('created_date', '=', $month)
        //           ->whereYear('created_date', '=', $year)
        //         ->whereHas('job.department', function ($query2) use ($department) {
        //             $query2->where('department_id', '=', $department->department_id);
        //          });
        //  })->count();

        //     array_push($columns_sm[$this->i], $employees_count);
        //  }

        //  }


        $data->columns = $columns_sm;

        $data->type = 'bar';
        $data->colors = $colors;
        $data->names = $object_names;


        return $data;


    }

    public function salesDashboard2()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        // $departments = $company->departments;
        // $names = $departments->pluck('department_name_ar')->toArray();

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 10)->whereIn('system_code', ['100002', '100004'])->where('company_group_id', $company->company_group_id)
            ->get();

        $names = $sys_codes_sales_types->pluck('system_code_name_ar')->toArray();


        $app = app();
        $data = $app->make('stdClass');

        $date = new DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');

        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }


        $data->columns = [
            ['data1'],
            ['data2'],
            ['data3'],
        ];


        foreach ($months as $key => $month) {
//               return  $columns_sm[--$k];

            $sales_count = WaybillHd::whereHas('waybillActive', function ($query) use ($sys_codes_sales_types, $month, $year) {
                $query->whereMonth('created_date', '=', $month)
                    ->whereYear('created_date', '=', $year)
                    ->where('waybill_type_id', '=', 1);

            })->where('company_group_id', $company->company_group_id)->get();

            $sum_balance = $sales_count->sum('waybill_total_amount');

            $sales_count_1 = WaybillDt::whereHas('waybillActive', function ($query) use ($sys_codes_sales_types, $month, $year) {
                $query->whereMonth('created_date', '=', $month)
                    ->whereYear('created_date', '=', $year)
                    ->where('waybill_item_id', '=', 541);

            })->where('company_group_id', $company->company_group_id)->get();
            $sum_balance_1 = $sales_count_1->sum('waybill_total_amount');

            $sales_count_2 = WaybillHd::whereHas('waybillActive', function ($query) use ($sys_codes_sales_types, $month, $year) {
                $query->whereMonth('created_date', '=', $month)
                    ->whereYear('created_date', '=', $year)
                    ->where('waybill_type_id', '=', 2);

            })->where('company_group_id', $company->company_group_id)->get();
            $sum_balance_2 = $sales_count_2->sum('waybill_total_amount');


            array_push($data->columns[0], number_format($sum_balance, 0, '.', ''));
            array_push($data->columns[1], number_format($sum_balance_1, 0, '.', ''));
            array_push($data->columns[2], number_format($sum_balance_2, 0, '.', ''));
        }


        $data->type = 'bar';


        $colors = $app->make('stdClass');
        // الرمادي
        $colors->data1 = '#434A54';

        $colors->data2 = '#006400';

        $colors->data3 = '#006400';

        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'نقل محروقات';
        $object_names->data2 = 'اجور النقل ';
        $object_names->data3 = 'نقل بضائع  ';
        $data->names = $object_names;

        return $data;
    }

    public function employeeNationality1()
    {
        //////////// system code = 25 for Saudi
//                columns:
//                [
//                    // each columns data
//                    ['data1', 11, 8, 15, 18, 19, 17, 34, 23],
//                    ['data2', 7, 7, 5, 7, 9, 12, 22, 12]
//                ],
//                type: 'bar', // default type of chart
//                groups: [
//                   ['data1', 'data2']
//                ],
//                colors: {
//                    'data1': '#1b6079',
//                    'data2': '#fed284',
//                },
//                names: {
//        // name of each serie
//                    'data1': 'Male',
//                    'data2': 'Female'
//                }

        $company = session('company') ? session('company') : auth()->user()->company;

        $app = app();
        $data = $app->make('stdClass');

        $system_code_saudi = SystemCode::where('system_code', 25)->first();
        $system_code_end = SystemCode::where('system_code', 118)
            ->where('company_group_id', $company->company_group_id)->first();

        $date = new DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');

        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }


        $data->columns = [
            ['data1'],
            ['data2'],
        ];


        foreach ($months as $k => $month) {
//                return  $columns_sm[--$k];
            $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
                ->whereMonth('emp_work_start_date', $month)
                ->whereYear('emp_work_start_date', $year)
                ->where('emp_nationality', $system_code_saudi->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();

            $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
                ->whereMonth('emp_work_start_date', $month)
                ->whereYear('emp_work_start_date', $year)
                ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();

            array_push($data->columns[0], $employees_saudi);
            array_push($data->columns[1], $employees_non_saudi);

        }


        $data->type = 'bar';

        $data->groups = [
            ['data1', 'data2']
        ];

        $colors = $app->make('stdClass');
        // الرمادي
        $colors->data1 = '#434A54';

        $colors->data2 = '#006400';
        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'سعودي';
        $object_names->data2 = 'غير سعودي';
        $data->names = $object_names;

        return $data;
    }

    public function truckPercentage()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $app = app();
        $data = $app->make('stdClass');

        $system_code_male = SystemCode::where('system_code', 487)
            ->where('company_group_id', $company->company_group_id)->first();

        $system_code_female = SystemCode::where('system_code', 488)
            ->where('company_group_id', $company->company_group_id)->first();
        $query_count = DB::table('Trucks')->where('company_group_id', $company->company_group_id);
        $all_trucks = Trucks::where('company_group_id', $company->company_group_id)->count();
        $ready_truck = $query_count->where('truck_status', SystemCode::where('system_code', 80)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $loaded_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 82)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///محمله
        $book_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 81)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();////محجوزه
        $mntns_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 131)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ////صيانه


        $ready_truck_p = $all_trucks > 0 ? number_format(($ready_truck / $all_trucks) * 100, 2) : 0; ////جاهزه

        $loaded_truck_p = $all_trucks > 0 ? number_format(($loaded_truck / $all_trucks) * 100, 2) : 0; ////محمله

        $mntns_truck_p = $all_trucks > 0 ? number_format(($mntns_truck / $all_trucks) * 100, 2) : 0; ////////صيانه


        $data->columns = [
            ['data1', $ready_truck],
            ['data2', $loaded_truck],
            ['data3', $mntns_truck],

        ];

        $data->type = 'donut';

        $colors = $app->make('stdClass');
        //
        $colors->data1 = '#2E8B57';
        // الرمادي
        $colors->data2 = '#434A54';
        // الاخضى الغامق
        $colors->data3 = '#006400';
        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'جاهزه';
        $object_names->data2 = 'محمله';
        $object_names->data3 = 'صيانه';

        $data->names = $object_names;

        return $data;

    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه  نقليات السيارات
    public function main1()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $data_emp1 = $this->nakliattDashboard1();

        // $employee_controller = new EmployeeDashboardController();
        //  $emp_gen = $employee_controller->maleFemalePercentage();
        //  $emp_nationality = $employee_controller->employeeNationality();

        //  $truck_status = $employee_controller->truckPercentage();

        $emp_gen = $this->nakliattDashboard1();
        $emp_nationality = $this->employeeNationality1();
        $truck_status = $this->truckmain1();

        $system_code_saudi = SystemCode::where('system_code', 25)->first();
        $system_code_end = SystemCode::where('system_code', 118)
            ->where('company_group_id', $company->company_group_id)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->where('emp_status', '!=', $system_code_end->system_code_id)->count();

        $data_sales = $this->nakliattDashboard2();
        $data_sales_amount = $this->nakliattDashboard1();
        $all_trucks = Trucks::where('company_group_id', $company->company_group_id)->count();
        $query_count = DB::table('Trucks')->where('company_group_id', $company->company_group_id);
        $ready_truck = $query_count->where('truck_status', SystemCode::where('system_code', 80)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $loaded_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 82)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///محمله
        $road_truck = DB::table('trip_hd')->where('company_group_id', $company->company_group_id)->where('trip_hd_status', SystemCode::where('system_code', 39002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();////محجوزه
        $mntns_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 131)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ////صيانه

        $waybills = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41004)->first()->system_code_id)->count();//بوليصه عميل

        $waybills_late = WaybillHd::where('waybill_type_id', 4)->where('company_group_id', $company->company_group_id)
            ->whereDoesntHave('trip')->whereDate('waybill_delivery_expected', '<=', Carbon::now()->addDays(2))->get(); ///متاخره عن الشحن
        $waybills_late_c = $waybills_late->count();

        $arrived_waybills = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41007)->first()->system_code_id)->count(); ///وصلت
        $waybills_road = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41006)->first()->system_code_id)->count();// بالطريق
        $waybills_total = $waybills + $waybills_road + $arrived_waybills;

        return view('Dashboard.main1', compact('data_emp1', 'emp_gen', 'emp_nationality', 'employees_saudi',
            'employees_non_saudi', 'truck_status', 'data_sales', 'data_sales_amount', 'companies', 'branches', 'all_trucks',
            'ready_truck', 'loaded_truck', 'road_truck', 'mntns_truck', 'waybills', 'waybills_late_c', 'arrived_waybills', 'waybills_total'));
    }

    public function nakliattDashboard1()

    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $app = app();
        $data = $app->make('stdClass');

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 41)->whereIn('system_code', ['41004', '41006', '41007'])->where('company_group_id', $company->company_group_id)
            ->get();

        $waybills = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41004)->first()->system_code_id)->count();//بوليصه عميل

        $waybills_late = WaybillHd::where('waybill_type_id', 4)->where('company_group_id', $company->company_group_id)
            ->whereDoesntHave('trip')->whereDate('waybill_delivery_expected', '<=', Carbon::now()->addDays(2))->get(); ///متاخره عن الشحن
        $waybills_late_c = $waybills_late->count();

        $waybills_road = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41006)->first()->system_code_id)->count();//بوليصه عميل

        $arrived_waybills = DB::table('waybill_hd')->where('waybill_status', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 41007)->first()->system_code_id)->count(); ///وصلت


        $data->columns = [
            ['data1', $waybills],
            ['data2', $waybills_late_c],
            ['data3', $waybills_road],
            ['data4', $arrived_waybills],
        ];

        $data->type = 'donut';

        $colors = $app->make('stdClass');
        $colors->data1 = '#92aacc';
        $colors->data2 = '#d03104';
        $colors->data3 = '#edb052';
        $colors->data4 = '#bda2b8';
        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'بوليصه';
        $object_names->data2 = 'متاخرة ';
        $object_names->data3 = 'في الطريق';
        $object_names->data4 = 'وصلت';
        $data->names = $object_names;

        return $data;

    }

    public function nakliattDashboard2()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        // $departments = $company->departments;
        // $names = $departments->pluck('department_name_ar')->toArray();

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 10)->whereIn('system_code', ['100002', '100004'])->where('company_group_id', $company->company_group_id)
            ->get();

        $names = $sys_codes_sales_types->pluck('system_code_name_ar')->toArray();


        $app = app();
        $data = $app->make('stdClass');

        $date = new DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');

        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }


        $data->columns = [
            ['data1'],
            ['data2'],
            ['data3'],
        ];


        foreach ($months as $key => $month) {
//               return  $columns_sm[--$k];

            $sales_count = WaybillHd::whereHas('waybillActives', function ($query) use ($sys_codes_sales_types, $month, $year) {
                $query->whereMonth('created_date', '=', $month)
                    ->whereYear('created_date', '=', $year)
                    ->where('waybill_type_id', '=', 4);

            })->where('company_group_id', $company->company_group_id)->count();

            // $sum_balance = $sales_count->sum('waybill_total_amount');

            $sales_count_1 = WaybillDt::whereHas('waybillActive', function ($query) use ($sys_codes_sales_types, $month, $year) {
                $query->whereMonth('created_date', '=', $month)
                    ->whereYear('created_date', '=', $year)
                    ->where('waybill_item_id', '=', 541);

            })->where('company_group_id', $company->company_group_id)->get();
            $sum_balance_1 = $sales_count_1->sum('waybill_total_amount');

            $sales_count_2 = WaybillHd::whereHas('waybillActives', function ($query) use ($sys_codes_sales_types, $month, $year) {
                $query->whereMonth('created_date', '=', $month)
                    ->whereYear('created_date', '=', $year)
                    ->where('waybill_type_id', '=', 40);

            })->where('company_group_id', $company->company_group_id)->get();
            $sum_balance_2 = $sales_count_2->sum('waybill_total_amount');

            array_push($data->columns[0], $sales_count);

            // array_push($data->columns[0],  number_format($sum_balance, 0, '.', ''));
            array_push($data->columns[1], number_format($sum_balance_1, 0, '.', ''));
            array_push($data->columns[2], number_format($sum_balance_2, 0, '.', ''));
        }


        $data->type = 'bar';


        $colors = $app->make('stdClass');
        // الرمادي
        $colors->data1 = '#434A54';

        $colors->data2 = '#006400';

        //  $colors->data3 = '#006400';

        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'نقل سيارات';
        $object_names->data2 = 'نقل سطحات';
        $object_names->data3 = 'نقل بضائع  ';
        $data->names = $object_names;

        return $data;
    }

    public function truckmain1()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $app = app();
        $data = $app->make('stdClass');

        $system_code_male = SystemCode::where('system_code', 487)
            ->where('company_group_id', $company->company_group_id)->first();

        $system_code_female = SystemCode::where('system_code', 488)
            ->where('company_group_id', $company->company_group_id)->first();
        $query_count = DB::table('Trucks')->where('company_group_id', $company->company_group_id);
        $all_trucks = Trucks::where('company_group_id', $company->company_group_id)->count();
        $ready_truck = $query_count->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 80)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $loaded_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 82)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///محمله
        $book_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 81)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();////محجوزه
        $mntns_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 131)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ////صيانه


        $ready_truck_p = $all_trucks > 0 ? number_format(($ready_truck / $all_trucks) * 100, 2) : 0; ////جاهزه

        $loaded_truck_p = $all_trucks > 0 ? number_format(($loaded_truck / $all_trucks) * 100, 2) : 0; ////محمله

        $mntns_truck_p = $all_trucks > 0 ? number_format(($mntns_truck / $all_trucks) * 100, 2) : 0; ////////صيانه


        $data->columns = [
            ['data1', $ready_truck],
            ['data2', $loaded_truck],
            ['data3', $mntns_truck],

        ];

        $data->type = 'donut';

        $colors = $app->make('stdClass');
        //
        $colors->data1 = '#2E8B57';
        // الرمادي
        $colors->data2 = '#79589c';
        // الاخضى الغامق
        $colors->data3 = '#d03104';
        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'جاهزه';
        $object_names->data2 = 'محمله';
        $object_names->data3 = 'صيانه';

        $data->names = $object_names;

        return $data;

    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه مبيعات الجوال محروقات
    public function main2()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data_emp1 = $this->employeeDashboard1();

        $employee_controller = new EmployeeDashboardController();
        $emp_gen = $employee_controller->maleFemalePercentage();
        $emp_nationality = $employee_controller->employeeNationality();

        $truck_status = $employee_controller->truckPercentage();

        $system_code_saudi = SystemCode::where('system_code', 25)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();

        $data_sales = $this->salesDashboard1();
        $data_sales_amount = $this->salesDashboard2();
        return view('Dashboard.main2', compact('data_emp1', 'emp_gen', 'emp_nationality', 'employees_saudi',
            'employees_non_saudi', 'truck_status', 'data_sales', 'data_sales_amount'));
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه نقليات البضائع
    public function main3()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data_emp1 = $this->employeeDashboard1();

        $employee_controller = new EmployeeDashboardController();
        $emp_gen = $employee_controller->maleFemalePercentage();
        $emp_nationality = $employee_controller->employeeNationality();

        $truck_status = $employee_controller->truckPercentage();

        $system_code_saudi = SystemCode::where('system_code', 25)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();

        $data_sales = $this->salesDashboard1();
        $data_sales_amount = $this->salesDashboard2();
        return view('Dashboard.main3', compact('data_emp1', 'emp_gen', 'emp_nationality', 'employees_saudi',
            'employees_non_saudi', 'truck_status', 'data_sales', 'data_sales_amount'));
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه  للمستودعات
    public function main4()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data_emp1 = $this->employeeDashboard1();

        $employee_controller = new EmployeeDashboardController();
        $emp_gen = $employee_controller->maleFemalePercentage();
        $emp_nationality = $employee_controller->employeeNationality();

        $truck_status = $employee_controller->truckPercentage();

        $system_code_saudi = SystemCode::where('system_code', 25)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();

        $data_sales = $this->salesDashboard1();
        $data_sales_amount = $this->salesDashboard2();
        return view('Dashboard.main4', compact('data_emp1', 'emp_gen', 'emp_nationality', 'employees_saudi',
            'employees_non_saudi', 'truck_status', 'data_sales', 'data_sales_amount'));
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه مبيعات السيارات
    public function main5()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data_emp1 = $this->employeeDashboard1();

        $employee_controller = new EmployeeDashboardController();
        $emp_gen = $employee_controller->maleFemalePercentage();
        $emp_nationality = $employee_controller->employeeNationality();

        $truck_status = $employee_controller->truckPercentage();

        $system_code_saudi = SystemCode::where('system_code', 25)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();

        $data_sales = $this->salesDashboard1();
        $data_sales_amount = $this->salesDashboard2();
        return view('Dashboard.main5', compact('data_emp1', 'emp_gen', 'emp_nationality', 'employees_saudi',
            'employees_non_saudi', 'truck_status', 'data_sales', 'data_sales_amount'));
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه  للصيانه
    public function main6()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data_emp1 = $this->employeeDashboard1();

        $employee_controller = new EmployeeDashboardController();
        $emp_gen = $employee_controller->maleFemalePercentage();
        $emp_nationality = $employee_controller->employeeNationality();

        $truck_status = $employee_controller->truckPercentage();

        $system_code_saudi = SystemCode::where('system_code', 25)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();

        $data_sales = $this->salesDashboard1();
        $data_sales_amount = $this->salesDashboard2();
        return view('Dashboard.main6', compact('data_emp1', 'emp_gen', 'emp_nationality', 'employees_saudi',
            'employees_non_saudi', 'truck_status', 'data_sales', 'data_sales_amount'));
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////// الشاشه الرئيسيه لتاجير السيارات
    public function main7(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();

        $con_open_code = SystemCode::where('system_code', 13601)->where('company_group_id', $company->company_group_id)->first();
        $con_late_code = SystemCode::where('system_code', 13603)->where('company_group_id', $company->company_group_id)->first();
        $open_conts = CarRentContract::where('contract_status', $con_open_code->system_code_id);
        $late_conts = CarRentContract::where('contract_status', $con_late_code->system_code_id);
        $today_conts = CarRentContract::whereDate('contractEndDate', today());

        $car_ready_code = SystemCode::where('system_code', 123001)->where('company_group_id', $company->company_group_id)->first();
        $car_rent_code = SystemCode::where('system_code', 123003)->where('company_group_id', $company->company_group_id)->first();
        $ready_cars = CarRentCars::where('car_status_id', $car_ready_code->system_code_id);
        $rent_cars = CarRentCars::where('car_status_id', $car_rent_code->system_code_id);

        $movements = CarRentMovement::latest();

        if ($request->company_id) {
            $ready_cars->whereIn('company_id', $request->company_id);
            $rent_cars->whereIn('company_id', $request->company_id);
            $open_conts->whereIn('company_id', $request->company_id);
            $late_conts->whereIn('company_id', $request->company_id);
            $today_conts->whereIn('company_id', $request->company_id);
            $movements->whereIn('company_id', $request->company_id);
        }
        if ($request->branch_id) {
            $ready_cars->whereIn('branch_id', $request->branch_id);
            $rent_cars->whereIn('branch_id', $request->branch_id);
            $open_conts->whereIn('branch_id', $request->branch_id);
            $late_conts->whereIn('branch_id', $request->branch_id);
            $today_conts->whereIn('branch_id', $request->branch_id);
            $movements->whereIn('car_movement_branch_open', $request->branch_id);
        }
        $ready_cars = $ready_cars->count();
        $rent_cars = $rent_cars->count();
        $open_conts = $open_conts->count();
        $late_conts = $late_conts->count();
        $today_conts = $today_conts->count();
        $movements = $movements->count();

        $data_contracts = $this->contractDashboard1();
        $data_contracts_amount = $this->contractDashboard2();
        $contract_status = $this->contractStatusPercentage();
        $car_status = $this->carStatusPercentage();
//        dd($data_contracts,$data_contracts_amount,$contract_status,$car_status);
        return view('Dashboard.main7', compact('companies', 'branches', 'ready_cars', 'rent_cars',
            'open_conts', 'late_conts', 'today_conts', 'data_contracts', 'data_contracts_amount', 'contract_status',
            'car_status', 'car_ready_code', 'car_rent_code','company','con_open_code','con_late_code', 'movements'));
    }

    public function contractDashboard1()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 136)
            ->where('company_group_id', $company->company_group_id)->get();

        $names = $sys_codes_sales_types->pluck('system_code_name_ar')->toArray();
        $app = app();
        $object_names = $app->make('stdClass');
        foreach ($names as $k => $name) {
            $x = 'data' . ++$k;
            $object_names->{$x} = $name;
        }
        $colors = $app->make('stdClass');
        $colors_array = ['#434A54', '#006400'];
        $date = new DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');
        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }
        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {
            $x = 'data' . ++$k;
            $colors->{$x} = $colors_array[array_rand($colors_array)];
        }
        $data = $app->make('stdClass');
        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {
            $this->i = $k;
            $columns_sm[] = ['data' . ++$k];
            foreach ($months as $key => $month) {
                $sales_count = CarRentContract::where('contract_status', $sys_codes_sales_type->system_code_id)
                    ->whereMonth('created_at', '=', $month)
                    ->whereYear('created_at', '=', $year)
                    ->where('company_group_id', $company->company_group_id)->count();

                array_push($columns_sm[$this->i], $sales_count);
            }
        }
        $data->columns = $columns_sm;
        $data->type = 'bar';
        $data->colors = $colors;
        $data->names = $object_names;
        return $data;
    }

    public function contractDashboard2()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 136)
            ->where('company_group_id', $company->company_group_id)->get();

        $names = $sys_codes_sales_types->pluck('system_code_name_ar')->toArray();


        $app = app();
        $data = $app->make('stdClass');

        $date = new DateTime();
        $month = $date->format('m');
        $year = $date->format('Y');

        for ($i = 1; $i <= $month; $i++) {
            $months[] = $i;
        }


        $data->columns = [
            ['data1'],
            ['data2'],
            ['data3'],
        ];


        foreach ($months as $key => $month) {
//               return  $columns_sm[--$k];

            $sum_balance = CarRentContract::whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->where('company_group_id', $company->company_group_id)->sum('contract_amount');
            $sum_balance_1 = CarRentContract::whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->where('company_group_id', $company->company_group_id)->sum('contract_amount');
            $sum_balance_2 = CarRentContract::whereMonth('created_at', '=', $month)
                ->whereYear('created_at', '=', $year)
                ->where('company_group_id', $company->company_group_id)->sum('contract_amount');

            array_push($data->columns[0], number_format($sum_balance, 0, '.', ''));
            array_push($data->columns[1], number_format($sum_balance_1, 0, '.', ''));
            array_push($data->columns[2], number_format($sum_balance_2, 0, '.', ''));
        }


        $data->type = 'bar';


        $colors = $app->make('stdClass');
        // الرمادي
        $colors->data1 = '#434A54';

        $colors->data2 = '#006400';

        $colors->data3 = '#006400';

        $data->colors = $colors;

        $object_names = $app->make('stdClass');
        $object_names->data1 = 'نقل محروقات';
        $object_names->data2 = 'اجور النقل ';
        $object_names->data3 = 'نقل بضائع  ';
        $data->names = $object_names;

        return $data;
    }

    public function contractStatusPercentage()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 136)
            ->where('company_group_id', $company->company_group_id)->get();

        $names = $sys_codes_sales_types->pluck('system_code_name_ar')->toArray();
        $app = app();
        $object_names = $app->make('stdClass');
        foreach ($names as $k => $name) {
            $x = 'data' . ++$k;
            $object_names->{$x} = $name;
        }
        $colors = $app->make('stdClass');
        $colors_array = ['#434A54', '#006400'];
        $date = new DateTime();
        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {
            $x = 'data' . ++$k;
            $colors->{$x} = $colors_array[array_rand($colors_array)];
        }
        $data = $app->make('stdClass');
        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {
            $this->i = $k;
            $columns_sm[] = ['data' . ++$k];
            $sales_count = CarRentContract::where('contract_status', $sys_codes_sales_type->system_code_id)
                ->where('company_group_id', $company->company_group_id)->count();

            array_push($columns_sm[$this->i], $sales_count);
        }
        $data->columns = $columns_sm;
        $data->type = 'donut';
        $data->colors = $colors;
        $data->names = $object_names;
        return $data;
    }

    public function carStatusPercentage()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $sys_codes_sales_types = SystemCode::where('sys_category_id', 123)
            ->where('company_group_id', $company->company_group_id)->get();

        $names = $sys_codes_sales_types->pluck('system_code_name_ar')->toArray();
        $app = app();
        $object_names = $app->make('stdClass');
        foreach ($names as $k => $name) {
            $x = 'data' . ++$k;
            $object_names->{$x} = $name;
        }
        $colors = $app->make('stdClass');
        $colors_array = ['#434A54', '#006400', '#ffc107'];
        $date = new DateTime();
        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {
            $x = 'data' . ++$k;
            $colors->{$x} = $colors_array[array_rand($colors_array)];
        }
        $data = $app->make('stdClass');
        foreach ($sys_codes_sales_types as $k => $sys_codes_sales_type) {
            $this->i = $k;
            $columns_sm[] = ['data' . ++$k];
            $sales_count = CarRentCars::where('car_status_id', $sys_codes_sales_type->system_code_id)
                ->where('company_group_id', $company->company_group_id)->count();

            array_push($columns_sm[$this->i], $sales_count);
        }
        $data->columns = $columns_sm;
        $data->type = 'donut';
        $data->colors = $colors;
        $data->names = $object_names;
        return $data;
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////
}
