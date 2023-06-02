<?php

namespace App\Http\Controllers;


use App\InvoiceQR\InvoiceDateElement;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\Models\Bond;
use App\Models\CompanyMenuSerial;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\WaybillDt;
use App\Models\StoreItem;
use Carbon\Carbon;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Branch;
use App\Models\MaintenanceCar;
use App\Models\Reports;
use App\Models\SystemCode;
use App\Models\SystemCodecode;
use App\Models\Customer;
use App\Models\WaybillHd;
use App\Models\Employee;
use App\Models\Trucks;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RepotsController extends Controller
{
    //

    public function indexaccount(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get() ;
        $accounts = Account::where('company_group_id', $company->company_group_id)->where('acc_level', $company->companyGroup->accounts_levels_number)
        ->get();

        $report_acc_lits = SystemCode::where('sys_category_id', 93)
        ->where('company_group_id', $company->company_group_id)->get();

        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        $customers = Customer::whereIn('customer_category',[1,2,9] )
        ->where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('customer_category', 1)
        ->where('company_group_id', $company->company_group_id)->get();



        $sys_codes_waybill_status_codes = SystemCode::whereIn('system_code', ['41001', '41004'])
            ->where('company_group_id', $company->company_group_id)->pluck('system_code_id')
            ->toArray();
        $data = request()->all();
        $sys_codes_waybill_status = SystemCode::where('sys_category_id', 41)
            ->where('company_group_id', $company->company_group_id)->get();

        $way_pills_all = WaybillHd::where('company_id', $company->company_id)
            ->where('waybill_type_id', 4)->get();

        $way_pills = WaybillHd::where('company_id', $company->company_id)
            ->where('waybill_type_id', 4)->sortable()->paginate();

       
       

        $total = array_sum($way_pills->pluck('waybill_total_amount')->toArray());
        $total_vat = array_sum($way_pills->pluck('waybill_vat_amount')->toArray());

        $total_all = $way_pills_all->sum('waybill_total_amount');
        $total_vat_all = $way_pills_all->sum('waybill_vat_amount');

        

        $report_url_acc_51 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '93051')->get();

        $report_url_acc_52 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '93052')->get();

        $report_url_acc_53 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '93053')->get();

        $report_url_acc_54 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '93054')->get();

        $report_acc_customer = Reports::where('company_id', $company->company_id)
        ->where('report_code', '93059')->get();


        return view('Reports.Accounts.index', compact('companies', 'way_pills', 'customers',
            'sys_codes_waybill_status', 'total', 'total_vat', 'total_all', 'total_vat_all',
             'data','branch_lits','report_acc_lits','report_acc_customer','accounts',
             'report_url_acc_51', 'report_url_acc_52','report_url_acc_53','report_url_acc_54',
            'accountL','suppliers'));
    }



///////////////////////// تقارير السندات


public function indexbond(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get() ;

        $report_acc_lits = SystemCode::where('sys_category_id', 94)
        ->where('company_group_id', $company->company_group_id)->get();

        $bond_type_lits = SystemCodecode::where('sys_category_id', 57)
        ->where('company_group_id', $company->company_group_id)->get();

        $bond_type_s = SystemCodecode::where('sys_category_id', 59)
        ->where('company_group_id', $company->company_group_id)->get();

        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        $customers = Customer::where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('customer_category', 1)
        ->where('company_group_id', $company->company_group_id)->get();

        $report_url_bond_p = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94001')->get();

        $report_url_bond_r = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94002')->get();

        $report_url_bond_branch = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94004')->get();

        $report_url_way_branch = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94003')->get();

        $report_url_bond = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94005')->get();

        $report_url_bond_cus = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94006')->get();

        $report_url_bond_s = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94008')->get();



        $data = request()->all();

        
        $report_url_bond_today = Reports::where('company_id', $company->company_id)
        ->where('report_code', '94007')->get();
        $flag = 0;
        if (auth()->user()->user_type_id != 1) {
            foreach (session('job')->permissions as $job_permission) {
                if ($job_permission->app_menu_id == 53 && $job_permission->permission_update) {
                    $flag += 1;
                }
            }
        } else {
            $flag += 1;
        }
        return view('Reports.bond.index', compact('companies', 'customers','bond_type_s','report_url_bond_s','report_url_bond_p',
         'data','branch_lits','report_acc_lits','bond_type_lits','report_url_bond_cus','report_url_way_branch','report_url_bond_branch',
            'accountL','suppliers','report_url_bond_r','report_url_bond','report_url_bond_today','flag'));
    }
    



    ////////////////////////// تقارير مبيعات السيارات

    public function indexsalescar(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get() ;

        $store_lits = SystemCode::where('sys_category_id', 55)
        ->where('company_id',  $company->company_id)->get();

        $item_lits = StoreItem::where('company_id', $company->company_id)->get() ;

        
        $status_lits = SystemCodecode::where('sys_category_id', 120)
        ->where('company_group_id', $company->company_group_id)->get();

        $bond_type_lits = SystemCodecode::where('sys_category_id', 57)
        ->where('company_group_id', $company->company_group_id)->get();

        $report_acc_lits = SystemCode::where('sys_category_id', 103)
        ->where('company_id',  $company->company_id)->get();


        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        $customers = Customer::whereIN('customer_category', [9,2,1])
        ->where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('customer_category', 1)
        ->where('company_group_id', $company->company_group_id)->get();

        $report_url_ins = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86001')->get();

        $report_url_ins_r = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86002')->get();

        $report_url_inv = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86003')->get();

        $report_url_inv_r = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86004')->get();

        $report_url_item = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86005')->get();
        $report_url_items = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86006')->get();
       

        $report_url_location = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86009')->get();

        $report_url_97 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86097')->get();
        $report_url_98 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86098')->get();
        $report_url_99 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '86099')->get();

        $data = request()->all();

        return view('Reports.Salescar.index', compact('companies', 'customers', 
             'item_lits','branch_lits','report_acc_lits', 'bond_type_lits',
            'accountL','suppliers','data','store_lits', 'report_url_location',
        'report_url_ins','report_url_ins_r','report_url_inv','report_url_inv_r',
        'report_url_item','report_url_items', 'status_lits',
    'report_url_97','report_url_98','report_url_99'));
    }



    ////////////////////////// تقارير المستودعات

    public function indexstore(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get() ;

        $store_lits = SystemCode::where('sys_category_id', 55)
        ->where('company_id',  $company->company_id)->get();

        $item_lits = StoreItem::where('company_id', $company->company_id)->get() ;

        $cars_list = MaintenanceCar::where('company_group_id', '=', $company->company_group_id)
        ->with('brand')->get();


        $bond_type_lits = SystemCodecode::where('sys_category_id', 57)
        ->where('company_group_id', $company->company_group_id)->get();

        $report_acc_lits = SystemCode::where('sys_category_id', 103)
        ->where('company_id',  $company->company_id)->get();


        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        $customers = Customer::whereIN('customer_category', [9,2,1])
        ->where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('customer_category', 1)
        ->where('company_group_id', $company->company_group_id)->get();

        $report_url_ins = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10301')->get();

        $report_url_ins_r = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10302')->get();

        $report_url_inv = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10303')->get();

        $report_url_inv_r = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10304')->get();

        $report_url_item = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10305')->get();
        $report_url_items = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10306')->get();
        

$report_url_items_sales = Reports::where('company_id', $company->company_id)
->where('report_code', '10307')->get();

        $report_url_cars = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10308')->get();
        $report_url_location = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10309')->get();

        $report_url_97 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10397')->get();
        $report_url_98 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10398')->get();
        $report_url_99 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '10399')->get();

        $data = request()->all();

        return view('Reports.Store.index', compact('companies', 'customers', 
             'item_lits','branch_lits','report_acc_lits', 'bond_type_lits',
            'accountL','suppliers','data','store_lits', 'report_url_location', 'report_url_items_sales' ,
        'report_url_ins','report_url_ins_r','report_url_inv','report_url_inv_r',
        'report_url_item','report_url_items','cars_list','report_url_cars',
    'report_url_97','report_url_98','report_url_99'));
    }




    ///////////////////////// تقارير كروت الصيانة


public function indexmntns(Request $request)
{
    $company = session('company') ? session('company') : auth()->user()->company;
    $companies = Company::where('company_group_id', $company->company_group_id)->get();
    $branch_lits = Branch::where('company_id', $company->company_id)->get() ;
    $car_lits = MaintenanceCar::where('company_id', $company->company_id)->get();
    $report_acc_lits = SystemCode::where('sys_category_id', 102)
    ->where('company_group_id', $company->company_group_id)->get();

    $bond_type_lits = SystemCodecode::where('sys_category_id', 57)
    ->where('company_group_id', $company->company_group_id)->get();

    $card_type_ids = SystemCodecode::where('sys_category_id', 50)
    ->where('company_group_id', $company->company_group_id)->get();

    $accountL = Account::where('company_group_id', $company->company_group_id)
    ->where('acc_level', 5)->get();

    $customers = Customer::where('customer_category', 2)
    ->where('company_group_id', $company->company_group_id)->get();

    $suppliers = Customer::where('customer_category', 1)
    ->where('company_group_id', $company->company_group_id)->get();

    $report_url_mntns = Reports::where('company_id', $company->company_id)
    ->where('report_code', '102001')->get();
    $report_url_mntns_cus = Reports::where('company_id', $company->company_id)
    ->where('report_code', '102002')->get();
    $report_url_mntns_car = Reports::where('company_id', $company->company_id)
    ->where('report_code', '102004')->get();

    $data = request()->all();

  
    return view('Reports.Mntns.index', compact('companies', 'customers', 'report_url_mntns_car',
     'data','branch_lits','report_acc_lits','bond_type_lits','car_lits',
        'accountL','suppliers','report_url_mntns' , 'card_type_ids','report_url_mntns_cus'));
}


///////////////////////// تقارير اتفاقيات شحن السيارات 


public function indexwaybill(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_lits = Branch::where('company_id', $company->company_id)->get() ;
        $employees = Employee::where('company_group_id', $company->company_group_id)->get();

        $trucks = Trucks::where('company_id', $company->company_id)->get();
        
        $report_acc_lits = SystemCode::where('sys_category_id', 99)
        ->where('company_group_id', $company->company_group_id)->get();

        $loc_lits = SystemCode::where('sys_category_id', 34)
        ->where('company_group_id', $company->company_group_id)->get();

        $bond_type_lits = SystemCodecode::where('sys_category_id', 57)
        ->where('company_group_id', $company->company_group_id)->get();


        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        $customers = Customer::where('customer_category', 2)
        ->where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('customer_category', 1)
        ->where('company_group_id', $company->company_group_id)->get();

        $report_url_car_all = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99001')->get();

        $report_url_car_trip = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99002')->get();

        $report_url_car_no_trip = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99003')->get();

        $report_url_paid = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99004')->get();

        $report_url_no_paid = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99005')->get();

        $report_url_waybill_6 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99006')->get();
        $report_url_waybill_7 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99007')->get();


        $report_url_waybill_10 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99010')->get();

        $report_url_waybill_11 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99011')->get();

        $report_url_waybill_12 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99012')->get();

        $report_url_waybill_13 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99013')->get();

        $report_url_waybill_15 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99015')->get();

        $report_url_trip_21 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99021')->get();

        $report_url_trip_22 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99022')->get();

        $report_url_trip_23 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99023')->get();

        $report_url_trip_24 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99024')->get();

        $report_url_trip_25 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99025')->get();
       

        $report_url_trip_26 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99026')->get();
        $report_url_trip_27 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99027')->get();
        $report_url_trip_28 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99028')->get();

        $report_url_trip_29 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99029')->get();

        $report_url_trip_30 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99030')->get();

        $report_url_trip_31 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99031')->get();

        $report_url_trip_32 = Reports::where('company_id', $company->company_id)
        ->where('report_code', '99032')->get();


        $data = request()->all();

      
        return view('Reports.WaybillCars.index', compact('companies', 'customers', 'employees' ,'report_url_trip_27','report_url_trip_28',
         'data','branch_lits','report_acc_lits','bond_type_lits','loc_lits','trucks','report_url_waybill_15', 'report_url_trip_29',
            'accountL','report_url_car_all','report_url_car_trip','report_url_car_no_trip','report_url_paid', 'report_url_no_paid' ,
        'report_url_waybill_6','report_url_waybill_7','report_url_waybill_10','report_url_waybill_11','report_url_waybill_12' ,
         'report_url_waybill_13' ,'report_url_trip_21','report_url_trip_22','report_url_trip_26' , 'report_url_trip_30', 'report_url_trip_31', 'report_url_trip_32',
        'report_url_trip_23','report_url_trip_24','report_url_trip_25'));
    }




    
///////////////////////// تقارير   الموظفين 


public function indexemployee(Request $request)
{
    $company = session('company') ? session('company') : auth()->user()->company;
    $companies = Company::where('company_group_id', $company->company_group_id)->get();
    $branch_lits = Branch::where('company_id', $company->company_id)->get() ;

    $report_acc_lits = SystemCode::where('sys_category_id', 95)
    ->where('company_group_id', $company->company_group_id)->get();

    $loc_lits = SystemCode::where('sys_category_id', 34)
    ->where('company_group_id', $company->company_group_id)->get();

    $emp_doc = SystemCode::where('sys_category_id', 11)
    ->where('company_group_id', $company->company_group_id)->get();

    $bond_type_lits = SystemCodecode::where('sys_category_id', 57)
    ->where('company_group_id', $company->company_group_id)->get();


    $accountL = Account::where('company_group_id', $company->company_group_id)
    ->where('acc_level', 5)->get();

    $all_employees = DB::table('employees')->where('company_group_id', $company->company_group_id)->count();

    $sys_codes_emp_status = SystemCode::where('sys_category_id', 4)
    ->where('company_group_id', $company->company_group_id)->get();

$sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)
    ->where('company_group_id', $company->company_group_id)->get();

$jobs = $company->jobs;

    $report_url_emp_all = Reports::where('company_id', $company->company_id)
    ->where('report_code', '95001')->get();

    $report_url_emp_95002 = Reports::where('company_id', $company->company_id)
    ->where('report_code', '95002')->get();

    $report_url_emp_95003 = Reports::where('company_id', $company->company_id)
    ->where('report_code', '95003')->get();
    

    $data = request()->all();

  
    return view('Reports.Employees.index', compact('companies', 'all_employees',
    'sys_codes_nationality_country','sys_codes_emp_status','jobs','emp_doc',
     'data','branch_lits','report_acc_lits','bond_type_lits','loc_lits',
        'accountL','report_url_emp_all','report_url_emp_95002' , 'report_url_emp_95003' ));
}

}
