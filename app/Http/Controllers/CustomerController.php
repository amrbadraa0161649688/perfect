<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\UsersApp\Add;
use App\Http\Resources\UserJobResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use App\Models\Employee;
use App\Models\Account;
use App\Models\Job;
use App\Models\User;
use App\Models\UserBranch;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;

use Yajra\DataTables\Facades\DataTables;


class CustomerController extends Controller
{
    //


    public function index(Request $request)
    {
        $main_companies = CompanyGroup::get();
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        if ($request->ajax()) {

            $data = Customer::whereIn('customer_category', [2,3,4,5,6,9])->where('company_group_id', $company->company_group_id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('cus_type', function ($row) {

                //   if (\Lang::getLocale() == 'ar') {
                //      return $row->cus_type->system_code_name_ar;
                //  } else {
                //      return $row->cus_type->system_code_name_en;
                //  }
                //})
                ->addColumn('photo', function ($row) {

                    return view('Customers.Actions.photo', compact('row'));
                })
                ->addColumn('action', function ($row) {
                    return (string)view('Customers.Actions.actions', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $main_companies = CompanyGroup::get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        return view('Customers.index', compact('main_companies', 'companies'));
    }


    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 27)->get();
        $sys_codes_status = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 26)->get();
        $sys_customer_category = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 132)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        return view('Customers.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_countries',
            'sys_codes_nationality_country', 'companies','accountL','sys_customer_category'));

    }


    public function store(Request $request)
    {
        // $photo = $this->getPhoto($request->customer_photo);
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
            'customer_category' =>  $request->customer_category,
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
            'customer_vat_rate' =>  $request->customer_vat_rate,
        ]);

        DB::commit();
//        return $employee ;
        return redirect()->route('customers');
//        } catch (Throwable $e) {
//            //DB::rollBack();
//            return response([
//                'error' => $e->getMessage()
//            ], 400);
//            // abort('500', 'an error happened');
//        }


    }

    public function edit($id)

    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 27)->get();
        $sys_codes_status = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 26)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();
        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();
        $sys_customer_category = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 132)->get();

        $customer = Customer::find($id);
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $customers = customer::get();


        $accountL = Account::where('company_group_id', $company->company_group_id)
        ->where('acc_level', 5)->get();

        return view('Customers.edit', compact('customer', 'sys_codes_type', 'sys_codes_status', 'sys_codes_countries',

            'sys_codes_nationality_country', 'companies', 'customers','accountL','sys_customer_category'));

    }

    public function update(Request $request, $id)
    {

        $customer = Customer::find($id);
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
            'customer_category' =>  $request->customer_category,
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


        return redirect()->route('customers')->with(['success' => 'تم تحديث بيانات العميل']);


    }



    //public function getPhoto($photo)
    //{
    //   $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
    //   $photo->move(public_path("Employees"), $name);
    //  return $name;
    // }

}
