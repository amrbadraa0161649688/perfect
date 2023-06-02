<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Http\Resources\MaintenanceCardCheckResource;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\CarRentBrand;
use App\Models\CarRentBrandDt;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\MaintenanceCard;
use App\Models\MaintenanceCardDetails;
use App\Models\MaintenanceType;
use App\Models\SystemCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MaintenanceCar;

use Lang;
use Illuminate\Support\Facades\Input;


class MaintenanceCardCheckController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $data = request()->all();

        $query = MaintenanceCard::where('company_group_id', $company->company_group_id)
            ->whereHas('cardType', function ($query) {
                $query->where('system_code', 480001);
            })
            ->select('branch_id', 'mntns_cards_payment_amount', 'mntns_cars_id', 'mntns_cards_status',
                'mntns_cards_type', 'mntns_cards_id', 'mntns_cards_no', 'customer_id', 'mntns_cards_total_amount',
                'mntns_cards_due_amount');

        if (request()->query->count() > 1) {
            if (count(request()->branch_id) > 1) {
                $query = $query->whereIn('branch_id', request()->branch_id);
            }

            if (count(request()->mntns_cards_type) > 1) {
                $query = $query->whereIn('mntns_cards_type', request()->mntns_cards_type);
            }

            if (request()->created_date_from) {
                $query = $query->whereDate('created_date', request()->created_date_from);
            }

            if (request()->created_date_to) {
                $query = $query->whereDate('created_date', request()->created_date_to);
            }

            if (count(request()->mntns_cards_status) > 1) {
                $query = $query->whereIn('mntns_cards_status', request()->mntns_cards_status);
            }

            if (count(request()->mntns_cards_item_id) > 1) {
                $query = $query->whereHas('details', function ($q) {
                    $q->whereIn('mntns_cards_item_id', request()->mntns_cards_item_id);
                });
            }
        } else {
            $query = $query->where('branch_id', session('branch')['branch_id']);
        }


        $maintenance_cards_all = $query->get();

        $maintenance_cards_all_1 = $maintenance_cards_all->count();

//         طلب صيانه
        $maintenance_cards_all_2 = $maintenance_cards_all->where('mntns_cards_status', '=', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 50001)->first()->system_code_id)->count();

//        تحت التنفيذ
        $maintenance_cards_all_3 = $maintenance_cards_all->where('mntns_cards_status', '=', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 50002)->first()->system_code_id)->count();


//        الكروت المغلقه
        $maintenance_cards_all_4 = $maintenance_cards_all->where('mntns_cards_status', '=', SystemCode::where('company_group_id', $company->company_group_id)
            ->where('system_code', 50003)->first()->system_code_id)->count();


        $maintenance_cards = $query->latest()->paginate();


        $maintenance_types = DB::table('maintenance_types')->where('mntns_type_category', request()->mntns_cards_type)
            ->select('mntns_type_id', 'mntns_type_name_ar', 'mntns_type_name_en')->get();

        $branches = $company->branches;

        $statuses = SystemCode::where('sys_category_id', 50)->where('company_group_id', $company->company_group_id)
            ->select('system_code_id', 'system_code_name_ar', 'system_code_name_en')->get();

        $mntns_cards_type = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 48)->where('system_code_filter', 'services')
            ->select('system_code_id', 'system_code', 'system_code_name_ar', 'system_code_name_en')->get();

//       انواع الايرادات
        $system_code_types = SystemCode::where('sys_category_id', 58)
            ->where('company_group_id', $company->company_group_id)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        return view('Maintenance.MaintenanceCardCheck.index', compact('maintenance_cards', 'branches',
            'maintenance_types', 'statuses', 'data', 'mntns_cards_type', 'maintenance_cards_all_1', 'maintenance_cards_all_2',
            'maintenance_cards_all_3', 'maintenance_cards_all_4', 'system_code_types', 'payment_methods', 'banks'));


    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $mntns_cards_main_type = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 48)->where('system_code_filter', 'Mntns')
            ->select('system_code_id', 'system_code', 'system_code_name_ar', 'system_code_name_en')->get();

        $mntns_cards_item_disc_type = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 51)->get();
        $mntns_cards_type = MaintenanceType::where('company_group_id', $company->company_group_id)->get();
        $selected_type_id = $mntns_cards_main_type->where('system_code', 480001)->first()->system_code_id;
        $colors = SystemCode::where('sys_category_id', 149)->get();
        $mnts_cars = MaintenanceCar::get();
        $brands = CarRentBrand::select('brand_id', 'brand_name_ar', 'brand_name_en')->get();
        $status = SystemCode::where('system_code', 50001)->where('company_group_id', $company->company_group_id)->first();
        $customers = Customer::select('customer_id', 'customer_name_full_ar')->latest()->get();
        $users = User::where('company_group_id', $company->company_group_id)->select('user_id', 'user_name_ar')->get();
        return view('Maintenance.MaintenanceCardCheck.create', compact('mntns_cards_type', 'status',
            'selected_type_id', 'customers', 'brands', 'mnts_cars', 'colors', 'mntns_cards_item_disc_type',
            'mntns_cards_main_type', 'users'));
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $last_card_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
            ->where('app_menu_id', 58)->latest()->first();
        if (isset($last_card_serial)) {
            $last_bonds_serial_no = $last_card_serial->serial_last_no;
            $array_number = explode('-', $last_bonds_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_card_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'MNTS-E-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'app_menu_id' => 58,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        $mntns_cards_type = SystemCode::where('system_code', 480001)->where('company_group_id', $company->company_group_id)->first();
        $maintenance_card = new MaintenanceCard();
        $maintenance_card->uuid = \DB::raw('NEWID()');

        $maintenance_card->mntns_cards_no = $string_number;
        $maintenance_card->company_group_id = $company->company_group_id;
        $maintenance_card->company_id = $company->company_id;
        $maintenance_card->branch_id = $branch->branch_id;
        $maintenance_card->customer_id = $request->customer_id;
        $maintenance_card->mntns_cards_status = $request->mntns_cards_status;
        $maintenance_card->mntns_cards_type = $mntns_cards_type->system_code_id;
        $maintenance_card->mntns_cards_vat_amount = $request->mntns_cards_vat_amount;
        $maintenance_card->mntns_cards_total_amount = $request->mntns_cards_total_amount;
        $maintenance_card->mntns_cards_due_amount = $request->mntns_cards_total_amount;
        $maintenance_card->mntns_cards_customer_type = $request->mntns_cards_customer_type;
        $maintenance_card->updated_user = $request->updated_user;
        $maintenance_card->mntns_cards_category = SystemCode::where('system_code', 529)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
        if ($request->mntns_cars_id) {
            $maintenance_card->mntns_cars_id = $request->mntns_cars_id;
            $maintenance_card->mntns_cars_meter = $request->mntns_cars_meter;
        } else {
            $MaintenanceCar = MaintenanceCar::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'mntns_cars_plate_no' => $request->mntns_cars_plate_no,
                'mntns_cars_chasie_no' => $request->mntns_cars_plate_no,
                'customer_id' => $request->customer_id,
                'mntns_cars_brand_id' => $branch->mntns_cars_brand_id,
                'mntns_cars_type' => $branch->mntns_cars_type,
                'mntns_cars_vat_no' => 0,
                'mntns_cars_meter' => $request->mntns_cars_meter,
                'mntns_cars_color' => $request->mntns_cars_color,
//                'mntns_cars_owner' => '',
                'mntns_cars_model' => $request->mntns_cars_model,
//                'mntns_cars_driver' => '',
                'created_user' => auth()->user()->user_id,
            ]);

            $maintenance_card->mntns_cars_id = $MaintenanceCar->mntns_cars_id;
        }

        $maintenance_card->mntns_cards_notes = $request->mntns_cards_notes;
        $maintenance_card->mntns_cards_payment_amount = 0;
        $maintenance_card->created_user = auth()->user()->user_id;
        $maintenance_card->save();

        foreach ($request->mntns_cards_item_id as $k => $mntns_cards_item_id) {
            MaintenanceCardDetails::create([
                'uuid' => \DB::raw('NEWID()'),
                'mntns_cards_id' => $maintenance_card->mntns_cards_id,
                'company_group_id' => $maintenance_card->company_group_id,
                'company_id' => $maintenance_card->company_id,
                'branch_id' => $maintenance_card->branch_id,
                'customer_id' => $maintenance_card->customer_id,
                'mntns_cars_id' => $maintenance_card->mntns_cars_id,
                'mntns_cards_item_type' => 535,
                'mntns_cards_item_id' => $mntns_cards_item_id,
                'mntns_cards_item_qty' => 1,
                'mntns_cards_item_price' => $request->mntns_type_value[$k],
                'mntns_cards_item_amount' => $request->mntns_type_value[$k],
                'mntns_cards_disc_type' => $request->mntns_cards_item_disc_type[$k],
                'mntns_cards_disc_amount' => $request->mntns_cards_item_disc_amount[$k], /////////نسبه او قيمه الخصم
                'mntns_cards_disc_value' => $request->mntns_cards_item_disc_value[$k], //////////////الخصم من الاجمالي
                'mntns_cards_vat_value' => $request->vat_rate[$k],
                'mntns_cards_vat_amount' => $request->vat_value[$k],
                'mntns_cards_amount' => $request->total_after_vat[$k],
                'created_user' => auth()->user()->user_id
            ]);
        }

        DB::commit();

        return redirect()->route('maintenanceCardCheck');
    }

    public function edit($id)
    {
        $card = MaintenanceCard::where('mntns_cards_id', $id)->first();

        if (request()->ajax()) {
            return response()->json(['data' => new MaintenanceCardCheckResource($card)]);
        }
        $company = session('company') ? session('company') : auth()->user()->company;
//       انواع الايرادات
        $system_code_types = SystemCode::where('sys_category_id', 58)
            ->where('company_group_id', $company->company_group_id)->get();
        $mntns_cards_main_type = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 48)->where('system_code_filter', 'Mntns')
            ->select('system_code_id', 'system_code', 'system_code_name_ar', 'system_code_name_en')->get();
        $statuses = SystemCode::where('sys_category_id', 50)->where('company_group_id', $company->company_group_id)
            ->select('system_code_id', 'system_code_name_ar', 'system_code_name_en')->get();
        $mnts_cars = MaintenanceCar::get();
        $brands = CarRentBrand::select('brand_id', 'brand_name_ar', 'brand_name_en')->get();
        $colors = SystemCode::where('sys_category_id', 149)->get();
        $users = User::where('company_group_id', $company->company_group_id)->select('user_id', 'user_name_ar')->get();
        $mntns_cards_type = MaintenanceType::where('company_group_id', $company->company_group_id)->get();
        $mntns_cards_item_disc_type = SystemCode::where('company_group_id', $company->company_group_id)
            ->where('sys_category_id', '=', 51)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $bonds_capture = Bond::where('transaction_type', 58)->where('transaction_id', $card->mntns_cards_id)->latest()->get();
        $photos_attachments = Attachment::where('transaction_id', $card->mntns_cards_id)->where('app_menu_id', 58)
            ->where('attachment_type', '=', 2)->get();
        return view('Maintenance.MaintenanceCardCheck.edit', compact('id', 'mntns_cards_main_type',
            'statuses', 'mnts_cars', 'brands', 'colors', 'users', 'mntns_cards_type', 'mntns_cards_item_disc_type',
            'card', 'system_code_types', 'payment_methods', 'banks', 'bonds_capture', 'photos_attachments'));

    }

    public function update($id, Request $request)
    {
        $maintenance_card = MaintenanceCard::where('mntns_cards_id', $id)->first();

        $maintenance_card->mntns_cards_status = $request->mntns_cards_status;
        $maintenance_card->mntns_cards_vat_amount = $request->mntns_cards_vat_amount;
        $maintenance_card->mntns_cards_total_amount = $request->mntns_cards_total_amount;
        $maintenance_card->mntns_cards_due_amount = $request->mntns_cards_total_amount;
        $maintenance_card->mntns_cards_customer_type = $request->mntns_cards_customer_type;
        $maintenance_card->updated_user = $request->updated_user;
        $maintenance_card->mntns_cars_id = $request->mntns_cars_id;
        $maintenance_card->mntns_cars_meter = $request->mntns_cars_meter;
        $maintenance_card->save();
        foreach ($request->mntns_cards_dt_id as $k => $mntns_cards_dt_id) {
            if ($mntns_cards_dt_id != 0) {
                $maintenance_card = MaintenanceCardDetails::where('mntns_cards_dt_id', $mntns_cards_dt_id)->first();
                $maintenance_card->update([
                    'customer_id' => $maintenance_card->customer_id,
                    'mntns_cars_id' => $maintenance_card->mntns_cars_id,
                    'mntns_cards_item_type' => 535,
                    'mntns_cards_item_id' => $request->mntns_cards_item_id[$k],
                    'mntns_cards_item_qty' => 1,
                    'mntns_cards_item_price' => $request->mntns_type_value[$k],
                    'mntns_cards_item_amount' => $request->mntns_type_value[$k],
                    'mntns_cards_disc_type' => $request->mntns_cards_item_disc_type[$k],
                    'mntns_cards_disc_amount' => $request->mntns_cards_item_disc_amount[$k], /////////نسبه او قيمه الخصم
                    'mntns_cards_disc_value' => $request->mntns_cards_item_disc_value[$k], //////////////الخصم من الاجمالي
                    'mntns_cards_vat_value' => $request->vat_rate[$k],
                    'mntns_cards_vat_amount' => $request->vat_value[$k],
                    'mntns_cards_amount' => $request->total_after_vat[$k],
                ]);
            } else {
                MaintenanceCardDetails::create([
                    'uuid' => \DB::raw('NEWID()'),
                    'mntns_cards_id' => $maintenance_card->mntns_cards_id,
                    'company_group_id' => $maintenance_card->company_group_id,
                    'company_id' => $maintenance_card->company_id,
                    'branch_id' => $maintenance_card->branch_id,
                    'customer_id' => $maintenance_card->customer_id,
                    'mntns_cars_id' => $maintenance_card->mntns_cars_id,
                    'mntns_cards_item_type' => 535,
                    'mntns_cards_item_id' => $request->mntns_cards_item_id[$k],
                    'mntns_cards_item_qty' => 1,
                    'mntns_cards_item_price' => $request->mntns_type_value[$k],
                    'mntns_cards_item_amount' => $request->mntns_type_value[$k],
                    'mntns_cards_disc_type' => $request->mntns_cards_item_disc_type[$k],
                    'mntns_cards_disc_amount' => $request->mntns_cards_item_disc_amount[$k], /////////نسبه او قيمه الخصم
                    'mntns_cards_disc_value' => $request->mntns_cards_item_disc_value[$k], //////////////الخصم من الاجمالي
                    'mntns_cards_vat_value' => $request->vat_rate[$k],
                    'mntns_cards_vat_amount' => $request->vat_value[$k],
                    'mntns_cards_amount' => $request->total_after_vat[$k],
                    'created_user' => auth()->user()->user_id
                ]);
            }
        }

        return redirect()->route('maintenanceCardCheck');

    }

    public function delete(Request $request)
    {
        $item = MaintenanceCardDetails::where('mntns_cards_dt_id', $request->mntns_cards_dt_id)->first();

        $item->card->mntns_cards_discount = $item->card->mntns_cards_discount - $item->mntns_cards_disc_value;
        $item->card->mntns_cards_vat_amount = $item->card->mntns_cards_vat_amount - $item->mntns_cards_vat_amount;
        $item->card->mntns_cards_total_amount = $item->card->mntns_cards_total_amount - $item->mntns_cards_amount;
        $item->card->save();

        $item->delete();
    }

    public function getCustomerData()
    {
        $customer = Customer::find(request()->customer_id);
        return response()->json(['data' => $customer]);
    }

    public function getMaintenanceTypes()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $maintenance_types = DB::table('maintenance_types')->where('mntns_type_category', request()->mntns_cards_type)
            ->select('mntns_type_id', 'mntns_type_name_ar', 'mntns_type_name_en')->get();

        $assets = DB::table('assets')->where('company_group_id', $company->company_group_id)->where('asset_category_id', request()->mntns_cards_type)
            ->select('asset_name_ar', 'asset_name_en', 'asset_id')->get();

        return response()->json(['data' => $maintenance_types, 'mntns_cars' => $assets]);

    }

    public function getBrandDts()
    {
        $brand_dts = CarRentBrandDt::where('brand_id', request()->brand_id)->get();
        return response()->json(['data' => $brand_dts]);
    }

    public function getMntnsCarDt()
    {
        $mnts_car = MaintenanceCar::find(request()->mntns_cars_id);
        return response()->json(['data' => $mnts_car]);
    }

    public function getMaintenanceType()
    {
        $maintenance_type = MaintenanceType::find(request()->mntns_cards_item_id);
        return response()->json(['data' => $maintenance_type]);
    }


    ///سند قبض
    public function addBondWithJournal(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        //return $request->all();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('system_code', $request->bond_method_type)->where('company_group_id', $company->company_group_id)->first();
        $transaction_type = 58;
        $transaction_id = $request->transaction_id;
        $card = MaintenanceCard::where('mntns_cards_id', $request->transaction_id)->first();
        $customer_id = $card->customer_id;
        $customer_type = $request->customer_type;
        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';

        $total_amount = $request->bond_amount_credit;
        $bond_doc_type = SystemCode::where('system_code_id', $request->bond_doc_type)->first();
        $bond_ref_no = $request->bond_ref_no;
        $bond_notes = $request->bond_notes ? $request->bond_notes : '';
        $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

//
        $journal_controller = new JournalsController();
        $cost_center_id = 53;
        $cc_voucher_id = $bond->bond_id;

        $journal_category_id = 7;

        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = ' اضافه قيد سند قبض رقم' . $bond->bond_code;
        $customer_notes = ' اضافه قيد سند قبض  للعميل رقم' . $bond->bond_code;
        $cash_notes = ' اضافه قيد سند قبض  رقم' . $bond->bond_code;

        $message = $journal_controller->AddCaptureJournal(56002, $customer_id, $bond_doc_type,
            $total_amount, $cc_voucher_id, $payment_method, $bank_id,
            $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes);

        if (isset($message)) {
            return back()->with(['error' => $message]);
        }


        $card->update([
            'mntns_cards_payment_amount' => $card->mntns_cards_payment_amount + $request->bond_amount_credit,
            'mntns_cards_due_amount' => $card->mntns_cards_due_amount - $request->bond_amount_credit
        ]);


        \DB::commit();

        return back()->with(['success' => 'تم اضافه السند']);

    }

    public function storePhoto(Request $request)
    {
        $img = $request->image;
        $file = $this->getPhoto($img);

        Attachment::create([
            'attachment_name' => 'maintenance-card-check',
            'attachment_type' => 2,
            'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
            'attachment_file_url' => $file,
            'attachment_data' => Carbon::now(),
            'transaction_id' => $request->mntns_cards_id,
            'app_menu_id' => 58,
            'created_user' => auth()->user()->user_id,
        ]);

        return back()->with(['success' => 'تم اضافه الصوره']);
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("MaintenanceCardCheck"), $name);
        return $name;
    }




//    public function createM()
//    {
//        $company = session('company') ? session('company') : auth()->user()->company;
//
//        $mntns_cards_type = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
//            ->where('sys_category_id', '=', 48)->where('system_code_filter', 'services')
//            ->select('system_code_id', 'system_code', 'system_code_name_ar', 'system_code_name_en')->get();
//
//        $selected_type_id = $mntns_cards_type->where('system_code', 480014)->first()->system_code_id;
//
//
//        $mntns_cards_category = DB::table('system_codes')->where('company_group_id', $company->company_group_id)
//            ->where('sys_category_id', '=', 49)
//            ->select('system_code_id', 'system_code_name_ar', 'system_code_name_en')->get();
//
//        $status = SystemCode::where('system_code', 50001)->where('company_group_id', $company->company_group_id)->first();
//        return view('Maintenance.CarCheck.createM', compact('mntns_cards_type', 'mntns_cards_category', 'status',
//            'selected_type_id'));
//    }
//
//    public function store(Request $request)
//    {
//
//        DB::beginTransaction();
//        $company = session('company') ? session('company') : auth()->user()->company;
//        $branch = session('branch');
//        $last_card_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
//            ->where('app_menu_id', 156)->latest()->first();
//
//        if (isset($last_card_serial)) {
//            $last_bonds_serial_no = $last_card_serial->serial_last_no;
//            $array_number = explode('-', $last_bonds_serial_no);
//            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
//            $string_number = implode('-', $array_number);
//            $last_card_serial->update(['serial_last_no' => $string_number]);
//        } else {
//            $string_number = 'MNTS-S-' . session('branch')['branch_id'] . '-1';
//            CompanyMenuSerial::create([
//                'company_group_id' => $company->company_group_id,
//                'company_id' => $company->company_id,
//                'branch_id' => session('branch')['branch_id'],
//                'app_menu_id' => 156,
//                'acc_period_year' => Carbon::now()->format('y'),
//                'serial_last_no' => $string_number,
//                'created_user' => auth()->user()->user_id
//            ]);
//        }
//
//        $maintenance_hd = MaintenanceCard::create([
//            'mntns_cards_no' => $string_number,
//            'company_group_id' => $company->company_group_id,
//            'company_id' => $company->company_id,
//            'branch_id' => session('branch')['branch_id'],
//            'customer_id' => session('branch')['branch_id'],
//            'mntns_cars_id' => $request->mntns_cars_id,
//            'mntns_cards_status' => $request->mntns_cards_status,
//            'mntns_cards_type' => $request->mntns_cards_type,
//            'mntns_cards_category' => $request->mntns_cards_category,
//            'mntns_cards_start_date' => Carbon::now(),
//            'created_date' => Carbon::now(),
//            'uuid' => \DB::raw('NEWID()'),
//            'mntns_cards_total_amount' => $request->mntns_cards_total_amount,
//            'created_user' => auth()->user()->user_id,
//            'mntns_cards_notes' => $request->mntns_cards_notes,
//        ]);
//
//
//        $card_detail_type = SystemCode::where('system_code_id', 535)->where('company_group_id', $company->company_group_id)
//            ->first();
//
//
//        MaintenanceCardDetails::create([
//            'mntns_cards_id' => $maintenance_hd->mntns_cards_id,
//            'company_group_id' => $company->company_group_id,
//            'company_id' => $company->company_id,
//            'branch_id' => session('branch')['branch_id'],
//            'customer_id' => session('branch')['branch_id'],
//            'mntns_cards_item_id' => $request->mntns_cards_item_id,
//            'mntns_cards_item_qty' => 1,
//            'mntns_cards_item_price' => $request->mntns_cards_total_amount,
//            'mntns_cards_item_amount' => $request->mntns_cards_total_amount,
//            'mntns_cards_disc_type' => $request->mntns_cards_item_disc_type,
//            'mntns_cards_disc_value' => $request->mntns_cards_item_disc_value,
//            'mntns_cards_disc_amount' => $request->mntns_cards_item_disc_amount,
//            'mntns_cards_vat_value' => .15,
//            'mntns_cards_vat_amount' => $request->vat_value,
//            'mntns_cards_amount' => $request->mntns_cards_total_amount,
//            'created_date' => Carbon::now(),
//            'created_user' => auth()->user()->user_id,
//            'mntns_cars_id' => $request->mntns_cars_id,
//            'uuid' => \DB::raw('NEWID()'),
//            'store_category_type' => $request->mntns_cards_category,
//            'mntns_cards_item_type' => $card_detail_type->system_code_id,
//        ]);
//
//        $this->updateTotal($maintenance_hd->mntns_cards_id);
//
//        DB::commit();
//        return redirect()->route('maintenanceCardCheck.create2', $maintenance_hd->mntns_cards_id);
//
//
//    }
//
//    public function create2($id)
//    {
//        $company = session('company') ? session('company') : auth()->user()->company;
//        $maintenance_hd = MaintenanceCard::where('mntns_cards_id', $id)->first();
//        $maintenance_types = MaintenanceType::where('mntns_type_category', $maintenance_hd->mntns_cards_type)
//            ->select('mntns_type_id', 'mntns_type_name_ar', 'mntns_type_name_en',
//                'mntns_type_category')->get();
//
//        $mntns_cards_item_disc_type = SystemCode::where('company_group_id', $company->company_group_id)
//            ->where('sys_category_id', '=', 51)->get();
//
//        $accounts = Account::where('company_group_id', $company->company_group_id)->where('acc_level', $company->companyGroup->accounts_levels_number)
//            ->where('main_type_id', 'like', '4' . '%')->get();
//
//        $suppliers = Customer::where('company_group_id', $company->company_group_id)
//            ->where('customer_category', 1)->get();
//
//        $payment_methods = SystemCode::where('sys_category_id', 57)
//            ->where('company_group_id', $company->company_group_id)->get();
//
//        $statuses = SystemCode::where('sys_category_id', 50)->where('company_group_id', $company->company_group_id)
//            ->select('system_code_id', 'system_code_name_ar', 'system_code_name_en')->get();
//
//        $attachments = Attachment::where('transaction_id', $id)->where('app_menu_id', 156)->get();
//        $notes = Note::where('transaction_id', $id)->where('app_menu_id', 156)->get();
//        $attachment_types = SystemCode::where('sys_category_id', 11)
//            ->where('company_group_id', $company->company_group_id)->get();
//
//
//        return view('Maintenance.CarCheck.create', compact('maintenance_hd', 'maintenance_types',
//            'mntns_cards_item_disc_type', 'accounts', 'suppliers', 'payment_methods', 'statuses',
//            'attachment_types', 'attachments', 'notes'));
//    }
//
//    public function update($id, Request $request)
//    {
//        $maintenance_card = MaintenanceCard::find($id);
//        $maintenance_card->mntns_cards_status = $request->mntns_cards_status;
//        $maintenance_card->save();
//        return back()->with('تم تحديث الكارت');
//    }
//
//    public function storeDetails(Request $request)
//    {
//
//        $company = session('company') ? session('company') : auth()->user()->company;
//        $maintenance_card = MaintenanceCard::find($request->mntns_cards_id);
////        صيانه داخليه
//
//        if ($request->mntns_cards_item_type == 535) {
//            MaintenanceCardDetails::create([
//                'mntns_cards_id' => $maintenance_card->mntns_cards_id,
//                'company_group_id' => $company->company_group_id,
//                'company_id' => $company->company_id,
//                'branch_id' => session('branch')['branch_id'],
//                'customer_id' => session('branch')['branch_id'],
//                'mntns_cards_item_type' => $request->mntns_cards_item_type,
//                'mntns_cards_item_id' => $request->mntns_cards_item_id,
//                'mntns_cards_item_qty' => 1,
//                'mntns_cards_item_price' => $request->mntns_type_value,
//                'mntns_cards_item_hours' => $request->mntns_cards_item_hours,
//                'mntns_cards_item_amount' => $request->mntns_type_value,
//                'mntns_cards_disc_type' => $request->mntns_cards_item_disc_type,
//                'mntns_cards_disc_value' => $request->mntns_cards_item_disc_value,
//                'mntns_cards_disc_amount' => $request->mntns_cards_item_disc_amount,
//                'mntns_cards_vat_value' => .15,
//                'mntns_cards_vat_amount' => $request->vat_value,
//                'mntns_cards_amount' => $request->total_after_vat,
//                'created_date' => Carbon::now(),
//                'created_user' => auth()->user()->user_id,
//                'mntns_cars_id' => 1,
//                'uuid' => \DB::raw('NEWID()')
//            ]);
//        } elseif ($request->mntns_cards_item_type == 536) {
////            صيانه خارجيه
//            MaintenanceCardDetails::create([
//                'mntns_cards_id' => $request->mntns_cards_id,
//                'company_group_id' => $company->company_group_id,
//                'company_id' => $company->company_id,
//                'branch_id' => session('branch')['branch_id'],
//                'customer_id' => session('branch')['branch_id'],
//                'mntns_cars_id' => $maintenance_card->mntns_cars_id,
//                'mntns_cards_item_type' => $request->mntns_cards_item_type,
//                'mntns_cards_item_id' => $request->supplier_id,
//                'mntns_cards_item_qty' => 1,
//                'mntns_cards_item_hours' => $request->mntns_cards_item_hours_external,
//                'mntns_cards_disc_type' => 0,
//                'mntns_cards_disc_value' => 0,
//                'mntns_cards_disc_amount' => 0,
//                'mntns_cards_vat_value' => ($request->external_vat_amount / 100) * $request->mntns_type_value_external,
//                'mntns_cards_amount' => $request->total_after_vat_external,
//                'created_date' => Carbon::now(),
//                'created_user' => auth()->user()->user_id,
//                'invoice_date_external' => $request->invoice_date_external,
//                'invoice_no_external' => $request->invoice_no_external,
//                'mntns_cards_item_notes' => $request->mntns_cards_item_notes,
//                'mntns_cards_vat_amount' => $request->external_vat_amount,
//                'mntns_cards_item_price' => $request->mntns_type_value_external,
//                'mntns_cards_item_amount' => $request->mntns_type_value_external,
//
//            ]);
//        } elseif ($request->mntns_cards_item_type == 537) {
////            قطع غيار
//
//            MaintenanceCardDetails::create([
//                'mntns_cards_id' => $request->mntns_cards_id,
//                'company_group_id' => $company->company_group_id,
//                'company_id' => $company->company_id,
//                'branch_id' => session('branch')['branch_id'],
//                'customer_id' => session('branch')['branch_id'],
//                'mntns_cars_id' => $maintenance_card->mntns_cars_id,
//                'store_category_type' => $maintenance_card->part_mntns_cards_item_id,
//                'mntns_cards_item_type' => $request->mntns_cards_item_type,
//                'mntns_cards_item_id' => $request->part_mntns_cards_item_id,
//                'mntns_cards_item_qty' => $request->part_qty,
//                'mntns_cards_item_price' => $request->part_unit_price,
//                'mntns_cards_disc_type' => $request->part_mntns_cards_item_disc_type,
//                'mntns_cards_disc_value' => $request->part_mntns_cards_item_disc_value,
//                'mntns_cards_disc_amount' => $request->part_mntns_cards_item_disc_amount,
//                'mntns_cards_vat_value' => $request->part_vat_value,
//                'mntns_cards_vat_amount' => .15,
//                'created_date' => Carbon::now(),
//                'created_user' => auth()->user()->user_id,
//                'mntns_cards_amount' => $request->part_total_after_vat,
//                'mntns_cards_item_amount' => $request->part_mntns_type_value,
//                'uuid' => \DB::raw('NEWID()'),
//            ]);
//
//        }
//
//        $this->updateTotal($maintenance_card->mntns_cards_id);
//        DB::beginTransaction();
//
//
//        return back();
//    }
//
//    public function getMaintenanceTypes()
//    {
//        $company = session('company') ? session('company') : auth()->user()->company;
//        $maintenance_types = DB::table('maintenance_types')->where('mntns_type_category', request()->mntns_cards_type)
//            ->select('mntns_type_id', 'mntns_type_name_ar', 'mntns_type_name_en')->get();
//
//        $assets = DB::table('assets')->where('company_group_id', $company->company_group_id)->where('asset_category_id', request()->mntns_cards_type)
//            ->select('asset_name_ar', 'asset_name_en', 'asset_id')->get();
//
//        return response()->json(['data' => $maintenance_types, 'mntns_cars' => $assets]);
//
//    }
//
//    public function getMaintenanceTypeDts()
//    {
//        $mt = MaintenanceType::find(request()->mntns_cards_item_id);
//        return response()->json(['data' => $mt]);
//    }
//
//    public function getStoreItemDt()
//    {
//        $store_item = StoreItem::where('item_id', request()->part_mntns_cards_item_id)->first();
//        return response()->json(['data' => $store_item]);
//    }
//
//    public function deleteItem($id)
//    {
//        $maintenance_dt = MaintenanceCardDetails::find($id);
//        $maintenance_dt->isDeleted = 1;
//        $maintenance_dt->save();
//        return back();
//    }
//
//    public function updateTotal($id)
//    {
//        $maintenance_card = MaintenanceCard::find($id);
//        $maintenance_card->mntns_cards_discount = $maintenance_card->internalSumDisc() + $maintenance_card->partSumDisc();
//        $maintenance_card->mntns_cards_vat_amount = $maintenance_card->internalSumVat() + $maintenance_card->externalSumVat() + $maintenance_card->partSumVat() +
//            $maintenance_card->card_total_vat_from_inv;
//
//        $maintenance_card->mntns_cards_total_amount = $maintenance_card->internalSumTotal() + $maintenance_card->externalSumTotal() + $maintenance_card->partSumTotal()
//            + $maintenance_card->card_total_val_from_inv;
//
//        $maintenance_card->save();
//    }


}
