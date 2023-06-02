<?php

namespace App\Http\Controllers;

use App\Models\AssetsM;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrailersController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data = request()->all();
        $query = AssetsM::where('asset_type', SystemCode::where('sys_category_id', 150)->where('company_group_id', $company->company_group_id)
            ->where('system_code', 150003)->first()->system_code_id);

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();

        if (request()->company_id) {
            $query->whereIn('company_id', request()->company_id);
        }

        if (request()->branch_id) {
            $query->whereIn('branch_id', request()->branch_id);
        }

        if (request()->asset_code) {
            $query->where('asset_code', 'like', '%' . request()->asset_code . '%');
        }

        if (request()->asset_serial) {
            $query->where('asset_serial', 'like', '%' . request()->asset_serial . '%');
        }

        $trailers = $query->paginate();

        return view('Trailers.index', compact('trailers', 'companies', 'branches','data'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('company_group_id', $company->company_group_id)->get();
        $sys_code_type = SystemCode::where('sys_category_id', 150)->where('company_group_id', $company->company_group_id)
            ->where('system_code', 150003)->first();
        $sys_codes_manufactuer = SystemCode::where('sys_category_id', 32)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_ownership_status = SystemCode::where('sys_category_id', 31)->where('company_group_id', $company->company_group_id)->get();

        return view('Trailers.create', compact('suppliers', 'sys_code_type',
            'sys_codes_manufactuer', 'sys_codes_ownership_status', 'companies'));
    }

    public function store(Request $request)
    {

//        , [asset_category_id]
        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;

        $last_trailer_serial = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 48)->latest()->first();

        if (isset($last_trailer_serial)) {
            $last_trailer_serial_no = $last_trailer_serial->serial_last_no;
            $array_number = explode('-', $last_trailer_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_trailer_serial->update(['serial_last_no' => $string_number]);

        } else {
            $string_number = 'T-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'app_menu_id' => 48,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $asset = AssetsM::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'asset_code' => $string_number,
            'asset_name_ar' => $request->asset_name_ar,
            'asset_name_en' => $request->asset_name_ar,
            'asset_type' => $request->asset_type, /////from system code table
            'asset_serial' => $request->asset_serial,
            'asset_model' => $request->asset_model,
            'asset_status' => $request->asset_status,
            'asset_owner' => $request->asset_owner, ///from customers table
            'asset_manufacture' => $request->asset_manufacture, ///from system code table
            'asset_owner_status' => $request->asset_owner_status //from system code table
        ]);

        if ($request->image) {
            $img = $request->image;
            $file = $this->getPhoto($img);

            Attachment::create([
                'attachment_name' => 'trailers',
                'attachment_type' => 2,
                'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
                'attachment_file_url' => $file,
                'attachment_data' => Carbon::now(),
                'transaction_id' => $asset->asset_id,
                'app_menu_id' => 48,
                'created_user' => auth()->user()->user_id,
            ]);
        }

        DB::commit();
        return redirect()->route('Trailers');
    }

    public function edit($id)
    {
        $trailer = AssetsM::find($id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('company_group_id', $company->company_group_id)->get();
        $sys_code_type = SystemCode::where('sys_category_id', 150)->where('company_group_id', $company->company_group_id)
            ->where('system_code', 150003)->first();
        $sys_codes_manufactuer = SystemCode::where('sys_category_id', 32)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_ownership_status = SystemCode::where('sys_category_id', 31)->where('company_group_id', $company->company_group_id)->get();
        $attachment = Attachment::where('transaction_id', $trailer->asset_id)->where('attachment_type', 2)
            ->where('app_menu_id', 48)->first();
        return view('Trailers.edit', compact('trailer', 'companies', 'suppliers',
            'sys_code_type', 'sys_codes_manufactuer', 'sys_codes_ownership_status', 'attachment'));

    }

    public function update($id, Request $request)
    {
        $trailer = AssetsM::find($id);

        $trailer->update([
            'asset_name_ar' => $request->asset_name_ar,
            'asset_name_en' => $request->asset_name_ar,
            'asset_type' => $request->asset_type, /////from system code table
            'asset_serial' => $request->asset_serial,
            'asset_model' => $request->asset_model,
            'asset_status' => $request->asset_status,
            'asset_owner' => $request->asset_owner, ///from customers table
            'asset_manufacture' => $request->asset_manufacture, ///from system code table
            'asset_owner_status' => $request->asset_owner_status //from system code table
        ]);

        if ($request->image) {
            $attachment = Attachment::where('attachment_id', $trailer->asset_id)->where('attachment_type', 2)
                ->where('app_menu_id', 48)->first();

            if (isset($attachment)) {
                $attachment->delete();
            }

            $img = $request->image;
            $file = $this->getPhoto($img);

            Attachment::create([
                'attachment_name' => 'trailers',
                'attachment_type' => 2,
                'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
                'attachment_file_url' => $file,
                'attachment_data' => Carbon::now(),
                'transaction_id' => $trailer->asset_id,
                'app_menu_id' => 48,
                'created_user' => auth()->user()->user_id,
            ]);
        }

        return redirect()->route('Trailers');
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("Trailers"), $name);
        return $name;
    }

}
