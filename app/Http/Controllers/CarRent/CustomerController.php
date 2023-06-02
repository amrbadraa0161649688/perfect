<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRent\CustomerStoreRequest;
use App\Http\Requests\CarRent\CustomerUpdateRequest;
use App\Models\Account;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomersBlock;
use App\Models\Note;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();

        $sys_code_classifications = SystemCode::where('sys_category_id', 122)->where('company_id', $company->company_id)->get();
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->where('company_id', $company->company_id)->get();

        $customer_individual_system_code = SystemCode::where('system_code', 538)->where('company_id', $company->company_id)->first();
        $customer_company_system_code = SystemCode::where('system_code', 539)->where('company_id', $company->company_id)->first();
        $customer_government_system_code = SystemCode::where('system_code', 540)->where('company_id', $company->company_id)->first();

        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5);
        $customer_all_count = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5);
        $customer_individual_count = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5);
        $customer_company_count = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5);
        $customer_government_count = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5);
        $customer_baned_count = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5);

        if (request()->customer_name_full) {
            $customers->where('customer_name_full_ar', 'like', '%' . request()->customer_name_full . '%')
                ->orWhere('customer_name_full_en', 'like', '%' . request()->customer_name_full . '%');

            $customer_all_count->where('customer_name_full_ar', 'like', '%' . request()->customer_name_full . '%')
                ->orWhere('customer_name_full_en', 'like', '%' . request()->customer_name_full . '%');
            $customer_individual_count->where('customer_name_full_ar', 'like', '%' . request()->customer_name_full . '%')
                ->orWhere('customer_name_full_en', 'like', '%' . request()->customer_name_full . '%');
            $customer_company_count->where('customer_name_full_ar', 'like', '%' . request()->customer_name_full . '%')
                ->orWhere('customer_name_full_en', 'like', '%' . request()->customer_name_full . '%');
            $customer_government_count->where('customer_name_full_ar', 'like', '%' . request()->customer_name_full . '%')
                ->orWhere('customer_name_full_en', 'like', '%' . request()->customer_name_full . '%');
            $customer_baned_count->where('customer_name_full_ar', 'like', '%' . request()->customer_name_full . '%')
                ->orWhere('customer_name_full_en', 'like', '%' . request()->customer_name_full . '%');
        }

        if (request()->customer_identity) {
            $customers->where('customer_identity', 'like', '%' . request()->customer_identity . '%');

            $customer_all_count->where('customer_identity', 'like', '%' . request()->customer_identity . '%');
            $customer_individual_count->where('customer_identity', 'like', '%' . request()->customer_identity . '%');
            $customer_company_count->where('customer_identity', 'like', '%' . request()->customer_identity . '%');
            $customer_government_count->where('customer_identity', 'like', '%' . request()->customer_identity . '%');
            $customer_baned_count->where('customer_identity', 'like', '%' . request()->customer_identity . '%');
        }
        if (request()->customer_mobile) {
            $customers->where('customer_mobile', 'like', '%' . request()->customer_mobile . '%');

            $customer_all_count->where('customer_mobile', 'like', '%' . request()->customer_mobile . '%');
            $customer_individual_count->where('customer_mobile', 'like', '%' . request()->customer_mobile . '%');
            $customer_company_count->where('customer_mobile', 'like', '%' . request()->customer_mobile . '%');
            $customer_government_count->where('customer_mobile', 'like', '%' . request()->customer_mobile . '%');
            $customer_baned_count->where('customer_mobile', 'like', '%' . request()->customer_mobile . '%');
        }

        if (request()->customer_nationality) {
            $customers->whereIn('customer_nationality', request()->customer_nationality);

            $customer_all_count->whereIn('customer_nationality', request()->customer_nationality);
            $customer_individual_count->whereIn('customer_nationality', request()->customer_nationality);
            $customer_company_count->whereIn('customer_nationality', request()->customer_nationality);
            $customer_government_count->whereIn('customer_nationality', request()->customer_nationality);
            $customer_baned_count->whereIn('customer_nationality', request()->customer_nationality);
        }
        if (request()->customer_classification) {
            $customers->whereIn('customer_classification', request()->customer_classification);

            $customer_all_count->whereIn('customer_classification', request()->customer_classification);
            $customer_individual_count->whereIn('customer_classification', request()->customer_classification);
            $customer_company_count->whereIn('customer_classification', request()->customer_classification);
            $customer_government_count->whereIn('customer_classification', request()->customer_classification);
            $customer_baned_count->whereIn('customer_classification', request()->customer_classification);
        }
        if (request()->customer_type) {
            $customers->whereIn('customer_type', request()->customer_type);

            $customer_all_count->whereIn('customer_type', request()->customer_type);
            $customer_individual_count->whereIn('customer_type', request()->customer_type);
            $customer_company_count->whereIn('customer_type', request()->customer_type);
            $customer_government_count->whereIn('customer_type', request()->customer_type);
            $customer_baned_count->whereIn('customer_type', request()->customer_type);
        }

        $customers = $customers->paginate(EnumSetting::Paginate);
        $customer_all_count = $customer_all_count->count();
        $customer_individual_count = $customer_individual_count->whereIn('customer_type', SystemCode::whereIn('system_code', [538])->where('company_group_id', $company->company_group_id)->pluck('system_code_id'))->count();
        $customer_company_count = $customer_company_count->whereIn('customer_type', SystemCode::whereIn('system_code', [539])->where('company_group_id', $company->company_group_id)->pluck('system_code_id'))->count();
        $customer_government_count = $customer_government_count->whereIn('customer_type', SystemCode::whereIn('system_code', [540])->where('company_group_id', $company->company_group_id)->pluck('system_code_id'))->count();
        $customer_baned_count = $customer_baned_count->whereIn('customer_type', SystemCode::whereIn('system_code', [540])->where('company_group_id', $company->company_group_id)->pluck('system_code_id'))->count();

        return view('CarRent.customers.index', compact('customers', 'companies', 'branches'
            , 'sys_codes_nationality_country', 'sys_code_classifications', 'sys_codes_type'
            , 'customer_all_count', 'customer_individual_count', 'customer_company_count', 'customer_government_count',
            'customer_baned_count', 'customer_individual_system_code', 'customer_company_system_code', 'customer_government_system_code'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 26)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $sys_code_classifications = SystemCode::where('sys_category_id', 122)->where('company_id', $company->company_id)->get();
        $sys_code_identity_types = SystemCode::where('sys_category_id', 66)->where('company_id', $company->company_id)->get();

        $accountL = Account::where('company_group_id', $company->company_group_id)
            ->where('acc_level', 5)->get();

        $path = request()->path;
        session()->put('redirect_path', request()->path);

        if (request()->route()->getName() == 'car-rent.customers.all_create') {
            return view('CarRent.customers.all_create', compact('sys_codes_type', 'sys_codes_status',
                'sys_codes_nationality_country', 'customers', 'sys_code_classifications', 'sys_code_identity_types', 'path', 'accountL'));
        }

        return view('CarRent.customers.create', compact('sys_codes_type', 'sys_codes_status',
            'sys_codes_nationality_country', 'customers', 'sys_code_classifications', 'sys_code_identity_types', 'path'));
    }

    public function store(CustomerStoreRequest $request)
    {
//        return $request->all();
        $company = session('company') ? session('company') : auth()->user()->company;
        $type_code = SystemCode::where('system_code', $request->id_type_code)
            ->where('company_group_id', $company->company_group_id)->first();

        $type_customer = SystemCode::where('system_code_id', $request->customer_type)
            ->where('company_group_id', $company->company_group_id)->first();

        $customer_status = SystemCode::where('system_code', 26001) //فعال
        ->where('company_group_id', $company->company_group_id)->first();
        $customer = Customer::create($request->except('path', '_token', 'id_type_code', 'customer_age') + [
                'company_group_id' => $company->company_group_id,
                'id_type_code' => $type_code->system_code_filter,
                'customer_status' => $customer_status->system_code_id,
                'customer_account_id' => $type_customer->system_code_acc_id,
                'customer_category' => 5,
                'customer_credit_limit' => 5000,
//                'customer_vat_rate' => $request->customer_vat_rate ? $request->customer_vat_rate : 15,
                'created_user' => auth()->user()->id,
            ]);
        if ($request->path == 'car-rent.create') {
            return redirect(route('car-rent.create') . '?customer_id=' . $customer->customer_id);
        } else {
            return redirect(route('car-rent.customers.show', $customer->customer_id));
        }
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('sys_category_id', 27)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 26)->where('company_group_id', $company->company_group_id)->get();

        $sys_codes_nationality_country = SystemCode::where('sys_category_id', 12)->get();
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $sys_code_classifications = SystemCode::where('sys_category_id', 122)->where('company_id', $company->company_id)->get();
        $sys_code_identity_types = SystemCode::where('sys_category_id', 66)->where('company_id', $company->company_id)->get();

        $attachments = Attachment::where('transaction_id', $customer->customer_id)->where('app_menu_id', 32)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)->where('company_group_id', $company->company_group_id)->get();
        $notes = Note::where('transaction_id', $customer->customer_id)->where('app_menu_id', 32)->get();


        $accountL = Account::where('company_group_id', $company->company_group_id)
            ->where('acc_level', 5)->get();

        $path = request()->path;
        session()->put('redirect_path', $path);

        if (request()->route()->getName() == 'car-rent.customers.edit') {
            return view('CarRent.customers.all_edit', compact('customer', 'sys_codes_type', 'sys_codes_status'
                , 'sys_codes_nationality_country', 'sys_code_classifications', 'sys_code_identity_types', 'customers', 'path'
                , 'attachments', 'attachment_types', 'notes', 'accountL'));
        }

        return view('CarRent.customers.edit', compact('customer', 'sys_codes_type', 'sys_codes_status'
            , 'sys_codes_nationality_country', 'sys_code_classifications', 'sys_code_identity_types', 'customers', 'path'
            , 'attachments', 'attachment_types', 'notes'));
    }

    public function update(CustomerUpdateRequest $request, $id)
    {
//        return $request->all();
        $company = session('company') ? session('company') : auth()->user()->company;

        $type_code = SystemCode::where('system_code', $request->id_type_code)
            ->where('company_group_id', $company->company_group_id)->first();

        $customer = Customer::find($id);
        $customer->update([
//            'id_type_code' => $type_code->system_code_filter,

            'company_group_id' => $company->company_group_id,
            'customer_name_full_ar' => $request->customer_name_1_ar . ' ' . $request->customer_name_2_ar . ' ' . $request->customer_name_3_ar . ' ' . $request->customer_name_4_ar,
            'customer_name_full_en' => $request->customer_name_1_en . ' ' . $request->customer_name_2_en . ' ' . $request->customer_name_3_en . ' ' . $request->customer_name_4_en,

            'customer_name_1_ar' => $request->customer_name_1_ar??$customer->customer_name_1_ar,
            'customer_name_2_ar' => $request->customer_name_2_ar??$customer->customer_name_2_ar,
            'customer_name_3_ar' => $request->customer_name_3_ar??$customer->customer_name_3_ar,
            'customer_name_4_ar' => $request->customer_name_4_ar??$customer->customer_name_4_ar,
            'customer_name_1_en' => $request->customer_name_1_en??$customer->customer_name_1_en,
            'customer_name_2_en' => $request->customer_name_2_en??$customer->customer_name_2_en,
            'customer_name_3_en' => $request->customer_name_3_en??$customer->customer_name_3_en,
            'customer_name_4_en' => $request->customer_name_4_en??$customer->customer_name_4_en,
            'customer_nationality' => $request->customer_nationality??$customer->customer_nationality,
            'customer_identity' => $request->customer_identity??$customer->customer_identity,
            'customer_gender' => $request->customer_gender??$customer->customer_gender,
            'customer_birthday' => $request->customer_birthday??$customer->customer_birthday,
            'customer_birthday_hijiri' => $request->customer_birthday_hijiri??$customer->customer_birthday_hijiri,
            'customer_type' => $request->customer_type??$customer->customer_type,
//            'customer_category' =>  $request->customer_category,
            'customer_company' => $request->customer_company??$customer->customer_company,
            'customer_job' => $request->customer_job??$customer->customer_job,
            'customer_email' => $request->customer_email??$customer->customer_email,
            'customer_phone' => $request->customer_phone??$customer->customer_phone,
            'customer_mobile_code' => $request->customer_mobile_code??$customer->customer_mobile_code,
            'customer_mobile' => $request->customer_mobile??$customer->customer_mobile,
            'customer_address_1' => $request->customer_address_1??$customer->customer_address_1,
            'customer_address_2' => $request->customer_address_2??$customer->customer_address_2,
            'customer_address_en' => $request->customer_address_en??$customer->customer_address_en,
            'postal_box' => $request->postal_box??$customer->postal_box,
            'postal_code' => $request->postal_code??$customer->postal_code,
            'customer_account_id' => $request->customer_account_id??$customer->customer_account_id,
            'customer_vat_no' => $request->customer_vat_no??$customer->customer_vat_no,
            'customer_credit_limit' => $request->customer_credit_limit??$customer->customer_credit_limit,
            'customer_status' => $request->customer_status??$customer->customer_status,

            'build_no' => $request->build_no??$customer->build_no,
            'unit_no' => $request->unit_no??$customer->unit_no,
            'customer_addition_rate' => $request->addition_per??$customer->customer_addition_rate,
            'customer_discount_rate' => $request->discount_per??$customer->customer_discount_rate,
            'customer_vat_rate' => $request->vat_per??$customer->customer_vat_rate,

            'updated_user' => auth()->user()->id,

            'customer_ref_no' => $request->customer_ref_no??$customer->customer_ref_no,
            'customer_phone_home' => trim($request->customer_phone_home)??$customer->customer_phone_home,
        ]);
        if ($request->path == 'car-rent.create') {
            return redirect(route('car-rent.create') . '?customer_id=' . $id);
        } else {
            return redirect()->route('car-rent.customers.index');
        }

    }

    public function blockCustomer($id)
    {

        $customer = Customer::find($id);

        return view('CarRent.customers.block', compact('customer'));
    }

    public function blockStore(Request $request)
    {
        if ($request->customer_block_status == 0) {
            $request->validate([
                'customer_unblock_notes' => 'required'
            ]);
        }

        if ($request->customer_block_status == 1) {
            $request->validate([
                'customer_block_notes' => 'required'
            ]);
        }


        $company = session('company') ? session('company') : auth()->user()->company;
        $customer = Customer::where('customer_id', $request->customer_id)->first();

        if ($customer->customerBlock) {
            $customer->customerBlock->update([
                'customer_block_status' => $request->customer_block_status,
                'enddate_unblock' => Carbon::now(),
                'customer_unblock_notes' => $request->customer_unblock_notes ? $request->customer_unblock_notes : '',
                'user_unblock' => $request->customer_block_status == 0 ? auth()->user()->user_id : '',
            ]);
            return back()->with(['error' => 'تم الغاء حظر العميل']);
        } else {
            CustomersBlock::create([
                'company_group_id' => $company->company_group_id,
                'customer_identity' => $customer->customer_identity,
                'customer_id' => $customer->customer_id,
                'customer_name_full_ar' => $customer->customer_name_full_ar,
                'customer_name_full_en' => $customer->customer_name_full_en,
                'customer_mobile' => $customer->customer_mobile,
                'customer_block_status' => $request->customer_block_status,
                'user_block' => $request->customer_block_status == 1 ? auth()->user()->user_id : '',
                'startdate_block' => Carbon::now(),
                'customer_block_notes' => $request->customer_block_notes ? $request->customer_block_notes : '',
            ]);
            return back()->with(['error' => 'تم حظر العميل']);
        }


    }

    public function getDifferenceDate()
    {
        $from_date = Carbon::createFromFormat('Y-m-d', request()->customer_birthday);

        $end_date = Carbon::now();

        $age = $end_date->diff($from_date);

        return response()->json(['data' => $age->y]);
    }
}
