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


class SupplierController extends Controller
{
    //


    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if ($request->ajax()) {

            $data = Customer::where('customer_category', 1)->where('company_group_id', $company->company_group_id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('cus_type', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->cus_type->system_code_name_ar;
                    } else {
                        return $row->cus_type->system_code_name_en;
                    }
                })
                ->addColumn('photo', function ($row) {

                    return view('customers.Actions.photo', compact('row'));
                })
                ->addColumn('status', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->status->system_code_name_ar;
                    } else {
                        return $row->status->system_code_name_en;
                    }
                })
                ->addColumn('action', function ($row) {
                    return (string)view('Suppliers.Actions.actions', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $main_companies = CompanyGroup::get();
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();


        return view('Suppliers.index', compact('main_companies', 'companies'));
    }

    public function create(Request $request)
    {
        $main_companies = CompanyGroup::get();
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $accountL = Account::where('company_group_id', auth()->user()->company_group_id)
            ->where('acc_level', 5)->get();


        $sys_codes_type = SystemCode::where('sys_category_id', 27)->where('company_group_id', auth()->user()->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 26)->where('company_group_id', auth()->user()->company_group_id)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();
        $sys_codes_reasons_leaving = SystemCode::where('sys_category_id', 23)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_job_identity = SystemCode::where('sys_category_id', 22)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_social_status = SystemCode::where('sys_category_id', 20)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_religion = SystemCode::where('sys_category_id', 21)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_sponsor_names = SystemCode::where('sys_category_id', 13)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();


        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $employees = Employee::get();
        return view('Suppliers.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_countries', 'sys_codes_reasons_leaving',
            'sys_codes_job_identity', 'sys_codes_social_status', 'sys_codes_religion', 'sys_codes_sponsor_names',
            'sys_codes_nationality_country', 'companies', 'employees', 'accountL'));

    }


    public function store(Request $request)
    {
        // $photo = $this->getPhoto($request->customer_photo);
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
            'customer_category' => 1,
            'customer_company' => $request->customer_company,
            'customer_job' => $request->customer_job,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_mobile_code' => $request->customer_mobile_code,
            'customer_mobile' => $request->customer_mobile,
            'customer_address_1' => $request->customer_address_1,
            'customer_address_2' => $request->customer_address_2,
            'customer_vat_no' => $request->customer_vat_no,
            'customer_account_id' => $request->customer_account_id,
            'customer_credit_limit' => $request->customer_credit_limit,
            'customer_status' => $request->customer_status,
            'customer_vat_rate' =>  $request->customer_vat_rate,
            'customer_photo' => 'test'
            // 'Employees/' . $photo,
        ]);

        DB::commit();
//        return $employee ;
        return redirect()->route('suppliers');
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

        $sys_codes_type = SystemCode::where('sys_category_id', 27)->where('company_group_id', 13)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 26)->where('company_group_id', 13)->get();
        $sys_codes_countries = SystemCode::where('sys_category_id', 12)->get();
        $sys_codes_reasons_leaving = SystemCode::where('sys_category_id', 23)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_job_identity = SystemCode::where('sys_category_id', 22)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_social_status = SystemCode::where('sys_category_id', 20)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_religion = SystemCode::where('sys_category_id', 21)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_sponsor_names = SystemCode::where('sys_category_id', 13)->where('company_id', 29)->where('company_group_id', 13)->get();
        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $customer = Customer::find($id);
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $customers = customer::get();
        $accountL = Account::where('company_group_id', auth()->user()->company_group_id)
            ->where('acc_level', 5)->get();


        return view('Suppliers.edit', compact('customer', 'sys_codes_type', 'sys_codes_status', 'sys_codes_countries',
            'sys_codes_reasons_leaving', 'sys_codes_job_identity', 'sys_codes_social_status', 'accountL',
            'sys_codes_religion', 'sys_codes_sponsor_names', 'sys_codes_nationality_country', 'companies', 'customers'));

    }

    public function update(Request $request, $id)
    {

        $customer = Customer::find($id);
        //  $photo = $this->getPhoto($request->customer_photo);

        if ($request->customer_photo) {

            // $photo = $this->getPhoto($request->customer_photo);
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
            'customer_category' => 1,
            'customer_company' => $request->customer_company,
            'customer_job' => $request->customer_job,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_mobile_code' => $request->customer_mobile_code,
            'customer_mobile' => $request->customer_mobile,
            'customer_address_1' => $request->customer_address_1,
            'customer_address_2' => $request->customer_address_2,
            'customer_vat_no' => $request->customer_vat_no,
            'customer_account_id' => $request->customer_account_id,
            'customer_credit_limit' => $request->customer_credit_limit,
            'customer_status' => $request->customer_status,
            'customer_photo' => 'test'
            //isset($photo) ? 'Employees/' . $photo : $customer->customer_photo,


        ]);


        return redirect()->route('suppliers')->with(['success' => 'تم تحديث بيانات العميل']);


    }



    //  public function getPhoto($photo)
    //  {
    //      $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
    //      $photo->move(public_path("Employees"), $name);
    //      return $name;
    //  }

}
