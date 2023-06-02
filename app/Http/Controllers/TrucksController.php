<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceCar;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use App\Models\Employee;
use App\Models\Trucks;
use App\Models\Customer;
use App\Models\Attachment;
use App\Models\Note;
use App\Models\Reports;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Yajra\DataTables\Facades\DataTables;

class TrucksController extends Controller
{
    //
    public function index(Request $request)
    {
        $main_companies = CompanyGroup::get();

        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 30)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $branch = Branch::where('company_group_id', $company->company_group_id)->get();
        $sys_truck_type = SystemCode::where('sys_category_id', 29)->where('company_group_id', $company->company_group_id)->get();


        if ($request->ajax()) {

            $data = Trucks::where('company_group_id', $company->company_group_id)->get();

            if (request()->company_id) {
                $data = Trucks::whereIn('company_id', request()->company_id)->get();
                //  $query_count = TripHd::whereIn('branch_id', request()->branch_id);
            }


            if (request()->sys_truck_types) {
                $data = Trucks::whereIn('company_id', request()->company_id)
                    ->whereIn('truck_type', request()->sys_truck_types)->get();
                //  $query_count = TripHd::whereIn('branch_id', request()->branch_id);
            }

            if (request()->sys_codes_statuss) {
                $data = Trucks::whereIn('company_id', request()->company_id)
                    ->whereIn('truck_status', request()->sys_codes_statuss)->get();
                //  $query_count = TripHd::whereIn('branch_id', request()->branch_id);
            }

            if (request()->loc_branch) {
                $data = Trucks::whereIn('company_id', request()->company_id)
                    ->whereIn('branch_id', request()->loc_branch)->get();

            }


            if (request()->loc_from) {

                $data = Trucks::whereIn('company_id', request()->company_id)->whereIn('truck_last_starting_location', request()->loc_from)->get();

            }

            if (request()->loc_to) {
                $data = Trucks::whereIn('company_id', request()->company_id)->whereIn('truck_last_end_location', request()->loc_to)->get();

            }

            if (request()->truck_code_no) {
                $data = Trucks::whereIn('company_id', request()->company_id)
                    ->where('truck_code', request()->truck_code_no)->get();

            }

            if (request()->truck_plate_no_1) {
                $data = Trucks::whereIn('company_id', request()->company_id)
                    ->where('truck_plate_no', 'like', '%' . request()->truck_plate_no_1 . '%')->get();

            }

            if (request()->employees) {
                $data = Trucks::whereIn('company_id', request()->company_id)
                    ->whereIn('truck_driver_id', request()->employees)->get();

            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->status->system_code_name_ar;
                    } else {
                        return $row->status->system_code_name_en;
                    }
                })
                ->addColumn('truck_types', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->truckType->system_code_name_ar;
                    } else {
                        return $row->truckType->system_code_name_en;
                    }
                })
                ->addColumn('company_name', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return $row->company->company_name_ar;
                    } else {
                        return $row->company->company_name_en;
                    }
                })
                ->addColumn('driver_name', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return optional($row->driver)->emp_name_full_ar;
                    } else {
                        return optional($row->driver)->emp_name_full_en;
                    }
                })
                ->addColumn('driver_id', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return optional($row->driver)->emp_identity;
                    } else {
                        return optional($row->driver)->emp_identity;
                    }
                })
                ->addColumn('driver_mobile', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return optional($row->driver)->emp_private_mobile;
                    } else {
                        return optional($row->driver)->emp_private_mobile;
                    }
                })
                ->addColumn('photo', function ($row) {

                    return view('Trucks.Actions.photo', compact('row'));
                })
                ->addColumn('branch_truck', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return optional($row->branch_truck)->branch_name_ar;
                    } else {
                        return optional($row->branch_truck)->branch_name_en;
                    }
                })
                ->addColumn('branch_truck_from', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return optional($row->branch_truck_from)->branch_name_ar;
                    } else {
                        return optional($row->branch_truck_from)->branch_name_en;
                    }
                })
                ->addColumn('branch_truck_to', function ($row) {

                    if (\Lang::getLocale() == 'ar') {
                        return optional($row->branch_truck_to)->branch_name_ar;
                    } else {
                        return optional($row->branch_truck_to)->branch_name_en;
                    }
                })
                ->addColumn('status_date', function ($row) {


                    return optional($row->updated_date)->format('Y-m-d');


                })
                ->addColumn('action', function ($row) {
                    return (string)view('Trucks.Actions.actions', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $query_count = DB::table('Trucks')->where('company_group_id', $company->company_group_id);
        $trucks_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '34001')->get();

        $all_trucks = Trucks::where('company_group_id', $company->company_group_id)->count();

        $ready_truck = $query_count->where('truck_status', SystemCode::where('system_code', 80)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $loaded_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 82)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///محمله
        $book_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 81)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count();////محجوزه
        $mntns_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 131)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ////صيانه
        $sales_truck = DB::table('Trucks')->where('company_group_id', $company->company_group_id)->where('truck_status', SystemCode::where('system_code', 134)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ////صيانه


        $ready_truck_p = $all_trucks > 0 ? number_format(($ready_truck / $all_trucks) * 100, 2) : 0; ////جاهزه

        $loaded_truck_p = $all_trucks > 0 ? number_format(($loaded_truck / $all_trucks) * 100, 2) : 0; ////محمله

        $mntns_truck_p = $all_trucks > 0 ? number_format(($mntns_truck / $all_trucks) * 100, 2) : 0; ////////صيانه

        return view('Trucks.index', compact('main_companies', 'companies', 'all_trucks', 'ready_truck', 'mntns_truck', 'loaded_truck', 'book_truck','sales_truck'
            , 'branch', 'trucks_report', 'loaded_truck_p', 'ready_truck_p', 'mntns_truck_p', 'sys_truck_type', 'sys_codes_location', 'sys_codes_status', 'employees'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $suppliers = Customer::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('company_group_id', $company->company_group_id)
            ->whereDoesntHave('truck')->get();
        $sys_codes_type = SystemCode::where('sys_category_id', 29)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 30)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_manufactuer = SystemCode::where('sys_category_id', 32)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_ownership_status = SystemCode::where('sys_category_id', 31)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_status = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();
        $plate_types = SystemCode::where('sys_category_id', 137)->where('company_group_id', $company->company_group_id)->get();

        $trucks = Trucks::get();
        return view('Trucks.create', compact('sys_codes_type', 'sys_codes_status', 'sys_codes_location',
            'sys_codes_ownership_status', 'sys_codes_manufactuer', 'sys_codes_ownership_status', 'sys_codes_tracker_status',
            'companies', 'suppliers', 'employees', 'trucks', 'plate_types'));

    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $photo = $this->getPhoto($request->truck_photo);

            $request->validate([
                'truck_sales_value' => 'required|max:8|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/ ',
                'truck_purchase_value' => 'required|max:8|regex:/^-?[0-9]+(?:\.[0-9]{1,2})?$/ ',
            ]);

            $truck = trucks::create([
                'company_group_id' => auth()->user()->company_group_id,
                'company_id' => auth()->user()->company_id,
                'truck_code' => $request->truck_code,
                'truck_name' => $request->truck_name,
                'truck_sticker' => $request->truck_sticker,
                'truck_type' => $request->truck_type,
                'truck_plate_no' => $request->truck_plate_no,
                'truck_chassis_no' => $request->truck_chassis_no,
                'truck_model' => $request->truck_model,
                'truck_driver_id' => $request->truck_driver_id,
                'truck_driver_eceived' => $request->truck_driver_eceived,
                'truck_purchase_value' => $request->truck_purchase_value,
                'truck_purchase_date' => $request->truck_purchase_date,
                'truck_depreciation_ratio' => $request->truck_depreciation_ratio,
                'truck_depreciation_years' => $request->truck_depreciation_years,
                'truck_sales_value' => $request->truck_sales_value,
                'truck_sales_date' => $request->truck_sales_date,
                'truck_status' => $request->truck_status,
                'truck_last_starting_location' => $request->truck_last_starting_location,
                'truck_last_starting_date' => $request->truck_last_starting_date,
                'truck_last_end_location' => $request->truck_last_end_location,
                'truck_last_end_date' => $request->truck_last_end_date,
                'truck_oil_changed_km' => $request->truck_oil_changed_km,
                'truck_oil_last_changed' => $request->truck_oil_last_changed,
                'truck_oil_changed_date' => $request->truck_oil_changed_date,
                'truck_account_id' => $request->truck_account_id,
                'truck_photo' => 'trucks/' . $photo,
                'truck_ownership_status' => $request->truck_ownership_status,
                'truck_rent_amount' => $request->truck_rent_amount,
                'truck_load_weight' => $request->truck_load_weight,
                'truck_driver_delivery' => $request->truck_driver_delivery,
                'trucker_status' => $request->trucker_status,
                'truck_seller' => $request->truck_seller,
                'truck_supplier' => $request->truck_supplier,
                'truck_ownership' => $request->truck_ownership,
                'truck_ownership_id' => $request->truck_ownership_id,
                'truck_manufactuer_company' => $request->truck_manufactuer_company,
                'rightLetter' => $request->rightLetter,
                'middleLetter' => $request->middleLetter,
                'leftLetter' => $request->leftLetter,
                'plate_number' => $request->plate_number,
                'plateTypeId' => $request->plateTypeId
            ]);
            $this->createOrUpdateMaintenanceCar($request->all() +
                [
                    'truck_id' => $truck->truck_id,
                    'company_group_id' => $truck->company_group_id,
                    'company_id' => $truck->company_id,
                ]);

            DB::commit();
//        return $employee ;
            return redirect()->route('Trucks');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('truck store', [$e]);
            return back()->with(['error' => __('messages.wrong_data')]);
        }
    }

    /**
     * to create maintenance car when store or update
     * @param $data
     * @return void
     * @throws \Throwable
     */
    public function createOrUpdateMaintenanceCar($data)
    {
        try {
            $customer = Customer::findOrFail($data['truck_ownership_id']);
            MaintenanceCar::updateOrCreate([
                'car_cost_center' => $data['truck_id'],
            ], [
                'uuid' => \DB::raw('NEWID()'),
                'company_group_id' => $data['company_group_id'],
                'company_id' => $data['company_id'],
                'customer_id' => $data['truck_ownership_id'],
                'mntns_cars_brand_id' => $data['truck_type'],
                'mntns_cars_plate_no' => $data['truck_plate_no'],
                'mntns_cars_chasie_no' => $data['truck_chassis_no'],
                'mntns_cars_type' => $data['truck_name'],
                'mntns_cars_model' => $data['truck_model'],
//            'mntns_cars_color' => $data[''],
//            'mntns_cars_meter' => $data[''],
                'mntns_cars_owner' => $customer->customer_name_full_ar,
                'mntns_cars_driver' => $data['truck_driver_id'],
                'mntns_cars_mobile_no' => $customer->customer_mobile,
                'mntns_cars_address' => $customer->customer_address_en,
                'mntns_cars_vat_no' => $customer->customer_vat_no,
                'created_user' => auth()->user()->user_id,
            ]);
        } catch (\Throwable $e) {
            throw $e;
        }
    }


    public function edit($id)

    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $sys_codes_type = SystemCode::where('sys_category_id', 29)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_status = SystemCode::where('sys_category_id', 30)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_manufactuer = SystemCode::where('sys_category_id', 32)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_ownership_status = SystemCode::where('sys_category_id', 31)->where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_status = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();

        $plate_types = SystemCode::where('sys_category_id', 137)->where('company_group_id', $company->company_group_id)->get();
        $truck = Trucks::find($id);
//        $employees = Employee::where('company_group_id', $company->company_group_id)->get();
        $employees = Employee::where('company_group_id', $company->company_group_id)
            ->whereDoesntHave('truck')->get();

        if ($truck->driver) {
            $employees->push($truck->driver);
        }

        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $suppliers = Customer::where('company_group_id', $company->company_group_id)->get();

        $companies = Company::where('company_group_id', $company->company_group_id)->get();


        $attachments = Attachment::where('transaction_id', $truck->truck_id)->where('app_menu_id', 34)->get();
        $attachment_types = SystemCode::where('sys_category_id', 11)->where('company_group_id', $company->company_group_id)->get();
        $notes = Note::where('transaction_id', $truck->truck_id)->where('app_menu_id', 34)->get();

        if (request()->ajax()) {
            $truck = Trucks::find(request()->truck_id);
            return response()->json(['data' => $truck]);
        }

        return view('Trucks.edit', compact('truck', 'sys_codes_type', 'sys_codes_status', 'sys_codes_location',
            'sys_codes_ownership_status', 'sys_codes_manufactuer', 'sys_codes_ownership_status', 'sys_codes_tracker_status',
            'suppliers', 'customers', 'employees', 'companies', 'attachments', 'attachment_types', 'notes', 'id', 'plate_types'));

    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $truck = Trucks::find($id);
            if ($request->truck_photo) {
                $photo = $this->getPhoto($request->truck_photo);
            }
            $truck->update([

                'company_id' => $request->company_id,

                'truck_code' => $request->truck_code,
                'truck_name' => $request->truck_name,
                'truck_sticker' => $request->truck_sticker,
                'truck_type' => $request->truck_type,
                'truck_plate_no' => $request->truck_plate_no,
                'truck_chassis_no' => $request->truck_chassis_no,
                'truck_model' => $request->truck_model,
                'truck_driver_id' => $request->truck_driver_id,
                'truck_driver_eceived' => $request->truck_driver_eceived,
                'truck_purchase_value' => $request->truck_purchase_value,
                'truck_purchase_date' => $request->truck_purchase_date,
                'truck_depreciation_ratio' => $request->truck_depreciation_ratio,
                'truck_depreciation_years' => $request->truck_depreciation_years,
                'truck_sales_value' => $request->truck_sales_value,
                'truck_sales_date' => $request->truck_sales_date,
                'truck_status' => $request->truck_status,
                'truck_last_starting_location' => $request->truck_last_starting_location,
                'truck_last_starting_date' => $request->truck_last_starting_date,
                'truck_last_end_location' => $request->truck_last_end_location,
                'truck_last_end_date' => $request->truck_last_end_date,
                'truck_oil_changed_km' => $request->truck_oil_changed_km,
                'truck_oil_last_changed' => $request->truck_oil_last_changed,
                'truck_oil_changed_date' => $request->truck_oil_changed_date,
                'truck_account_id' => $request->truck_account_id,
                'trucker_ref_no' => $request->trucker_ref_no,
                'truck_ownership_status' => $request->truck_ownership_status,
                'truck_rent_amount' => $request->truck_rent_amount,
                'truck_load_weight' => $request->truck_load_weight,
                'truck_driver_delivery' => $request->truck_driver_delivery,
                'trucker_status' => $request->trucker_status,
                'truck_seller' => $request->truck_seller,
                'truck_supplier' => $request->truck_supplier,
                'truck_ownership' => $request->truck_ownership,
                'truck_ownership_id' => $request->truck_ownership_id,
                'truck_manufactuer_company' => $request->truck_manufactuer_company,
                'truck_photo' => isset($photo) ? 'trucks/' . $photo : $truck->truck_photo,
                'rightLetter' => $request->rightLetter,
                'middleLetter' => $request->middleLetter,
                'leftLetter' => $request->leftLetter,
                'plate_number' => $request->plate_number,
                'plateTypeId' => $request->plateTypeId

            ]);
            $this->createOrUpdateMaintenanceCar($request->all() + [
                    'truck_id' => $truck->truck_id,
                    'company_group_id' => $truck->company_group_id,
                    'company_id' => $truck->company_id,
                ]);
            DB::commit();
            return redirect()->route('Trucks')->with(['success' => 'تم تحديث بيانات الناقله']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('truck update', [$e]);
            return back()->with(['error' => __('messages.wrong_data')]);
        }
    }


    public function getPhoto($photo)
    {
        // $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        // $photo->move(public_path("trucks"), $name);
        // return $name;
    }
}
