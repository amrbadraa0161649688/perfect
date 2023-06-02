<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Trucks;
use App\Models\SystemCode;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;


class EmployeeDashboardController extends Controller
{

    public $i = 0;

    public function index()
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

        $colors_array = ['#004660', '#09536e', '#1b6079', '#34738a'];


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


        $data_gender = $this->maleFemalePercentage();

        $data_nationality = $this->employeeNationality();


        $system_code_saudi = SystemCode::where('system_code', 25)->first();

        $employees_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

        $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
            ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();


        return view('Dashboard.employee', compact('data', 'data_gender', 'data_nationality', 'employees_saudi',
            'employees_non_saudi'));
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
//
//        $employees_male = DB::table('employees')->where('company_group_id', $company->company_group_id)
//            ->where('emp_gender', $system_code_male->system_code_id)->count();
//
//
//        $employees_female = DB::table('employees')->where('company_group_id', $company->company_group_id)
//            ->where('emp_gender', $system_code_female->system_code_id)->count();

        $employees_male = 30;

        $employees_female = 10;

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

    public function employeeNationality()
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
                ->whereMonth('created_date', '<=' , $month)
                ->whereYear('created_date','<=' , $year)
                ->where('emp_nationality', $system_code_saudi->system_code_id)->count();

            $employees_non_saudi = DB::table('employees')->where('company_group_id', $company->company_group_id)
                ->whereMonth('created_date','<=' , $month)
                ->whereYear('created_date','<=' , $year)
                ->where('emp_nationality', '!=', $system_code_saudi->system_code_id)->count();

            array_push($data->columns[0], $employees_saudi);
            array_push($data->columns[1], $employees_non_saudi);

        }

        $data->type = 'bar';

        $data->groups = [
            [ 'data1', 'data2']
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



//columns: [// each columns data
//['data1', 11, 8, 15, 18, 1, 17],
//['data2', 22, 3, 25, 27, 17, 18],
//['data3', 17, 18, 21, 28, 21, 27],
//['data4', 11, 15, 4, 22, 12, 25],],



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

}
