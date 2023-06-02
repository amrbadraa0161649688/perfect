<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\SystemCode;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\CompanyGroup;
use App\Models\SystemCodeCategory;
use App\Models\Employee;
use App\Models\Account;
use App\Models\Job;
use App\Models\User;
use App\Models\UserBranch;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;

class StoreClientController extends Controller
{
    //
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $user_data = [ 'company' => $company ,'branch'=> session('branch') ];
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
      
        return view('store.client.index', compact('companies','branch_list','user_data'));
    }

    public function data(Request $request)
    {
        //return request()->search['warehouses_type'];
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branch_list = Branch::where('company_id', $company->company_id)->get() ;
        
        $view = view('store.client.data', compact('company','companies','branch_list'));
        return \Response::json([ 'view' => $view->render(), 'success' => true ]);
    }

    public function dataTable(Request $request,$companyId)
    {
        info('data table');
        $company_id = (isset(request()->company_id) ? request()->company_id: auth()->user()->company->company_id );
        $company = Company::where('company_id', $company_id)->first();
        $customer = Customer::where('company_group_id', $company->company_group_id)->where('customer_category', 9)->get();
       
        return Datatables::of($customer)
            ->addIndexColumn()
            
            ->addColumn('account_id', function ($row) {

                if (\Lang::getLocale() == 'ar') {
                    return $row->account_id->acc_name_ar;
                } else {
                    return $row->account_id->acc_name_en;
                }
            })
            


            ->addColumn('action', function ($row) {
                info($row->item_id);
                return (string)view('store.client.Actions.actions', compact('row'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 27)->get();
        $sys_codes_status = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 26)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        return view('store.client.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_countries',
            'sys_codes_nationality_country', 'companies','accountL'));

    }

    public function store(Request $request)
    {
        $photo = $request->customer_photo;
        $customer = customer::create([
            'company_group_id' => auth()->user()->company_group_id,
            'customer_name_full_ar' => $request->customer_name_full_ar,
            'customer_name_full_en' => $request->customer_name_full_en,
            'customer_name_1_ar' => $request->customer_name_1_ar,
            'customer_name_2_ar' => $request->customer_name_2_ar,
            'customer_name_3_ar' => $request->customer_name_3_ar,
            'customer_name_4_ar' => $request->customer_name_4_ar,
            'customer_name_1_en' => $request->customer_name_1_en,
            'customer_name_2_en' => $request->customer_name_2_en,
            'customer_name_3_en' => $request->customer_name_3_en,
            'customer_name_4_en' => $request->customer_name_4_en,
            'customer_nationality' => $request->customer_nationality,
            'customer_identity' => $request->customer_identity,
            'customer_gender' => $request->customer_gender,
            'customer_birthday' => $request->customer_birthday,
            'customer_birthday_hijiri' => $request->customer_birthday_hijiri,
            'customer_type' => $request->customer_type,
            'customer_category' => 9,
            'customer_company' => $request->customer_company,
            'customer_job' => $request->customer_job,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_mobile_code' => '00966',
            'customer_mobile' => $request->customer_mobile,
            'customer_address_1' => $request->customer_address_1,
            'customer_address_2' => $request->customer_address_2,
            'customer_address_en' => $request->customer_address_en,
            'postal_box' => $request->postal_box,
            'postal_code' => $request->postal_code,
            'customer_account_id' => $request->customer_account_id,
            'customer_vat_no' => $request->customer_vat_no,
            'customer_credit_limit' => $request->customer_credit_limit,
            'customer_status' => $request->customer_status,
            'customer_photo' => 'Employees/' . $photo,
            'build_no'=> $request->build_no,
            'unit_no'=> $request->unit_no,

            'customer_addition_rate'=> $request->addition_per,
            'customer_discount_rate'=> $request->discount_per,
            'customer_vat_rate'=> $request->vat_per,
        ]);

        \DB::commit();

        return redirect()->route('store-client.index');

    }


    public function edit($customer_id)

    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 27)->get();
        $sys_codes_status = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 26)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();
        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $customer = Customer::find($customer_id);
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $customers = customer::get();

        
        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        return view('Store.client.edit', compact('customer', 'sys_codes_type', 'sys_codes_status', 'sys_codes_countries',

            'sys_codes_nationality_country', 'companies', 'customers','accountL'));

    }

    public function update(Request $request, $customer_id)
    {

        $customer = Customer::find($customer_id);
        //  $photo = $this->getPhoto($request->customer_photo);

        if ($request->customer_photo) {

            // $photo = $this->getPhoto($request->customer_photo);
            $photo = $request->customer_photo;
        }

        $customer->update([

            'company_group_id' => auth()->user()->company_group_id,
            'customer_name_full_ar' => $request->customer_name_1_ar . ' ' . $request->customer_name_2_ar . ' ' . $request->customer_name_3_ar . ' ' . $request->customer_name_4_ar,
            'customer_name_full_en' => $request->customer_name_1_en . ' ' . $request->customer_name_2_en . ' ' . $request->customer_name_3_en . ' ' . $request->customer_name_4_en,

            'customer_name_1_ar' => $request->customer_name_1_ar,
            'customer_name_2_ar' => $request->customer_name_2_ar,
            'customer_name_3_ar' => $request->customer_name_3_ar,
            'customer_name_4_ar' => $request->customer_name_4_ar,
            'customer_name_1_en' => $request->customer_name_1_en,
            'customer_name_2_en' => $request->customer_name_2_en,
            'customer_name_3_en' => $request->customer_name_3_en,
            'customer_name_4_en' => $request->customer_name_4_en,
            'customer_nationality' => $request->customer_nationality,
            'customer_identity' => $request->customer_identity,
            'customer_gender' => $request->customer_gender,
            'customer_birthday' => $request->customer_birthday,
            'customer_birthday_hijiri' => $request->customer_birthday_hijiri,
            'customer_type' => $request->customer_type,
            'customer_category' =>  9,
            'customer_company' => $request->customer_company,
            'customer_job' => $request->customer_job,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_mobile_code' => '00966',
            'customer_mobile' => $request->customer_mobile,
            'customer_address_1' => $request->customer_address_1,
            'customer_address_2' => $request->customer_address_2,
            'customer_address_en' => $request->customer_address_en,
            'postal_box' => $request->postal_box,
            'postal_code' => $request->postal_code,
            'customer_account_id' => $request->customer_account_id,
            'customer_vat_no' => $request->customer_vat_no,
            'customer_credit_limit' => $request->customer_credit_limit,
            'customer_status' => $request->customer_status,
            'customer_photo' => isset($photo) ? 'Employees/' . $photo : $customer->customer_photo,
            'build_no'=> $request->build_no,
            'unit_no'=> $request->unit_no,
            'customer_addition_rate'=> $request->addition_per,
            'customer_discount_rate'=> $request->discount_per,
            'customer_vat_rate'=> $request->vat_per,

        ]);
        \DB::commit();

        return redirect()->route('store-client.index');


    }

}
