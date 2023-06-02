<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Http\Controllers\Store\StoreSalesController;
use App\Models\Account;
use App\Models\Bond;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\MaintenanceCard;
use App\Models\MaintenanceCardDetails;
use App\Models\MaintenanceCar;
use App\Models\Trucks;
use App\Models\SystemCode;
use App\Models\Customer;
use App\Models\StoreItem;
use App\Models\CompanyMenuSerial;
use App\Models\Employee;
use App\Models\MaintenanceTechnicians;
use App\Models\Purchase;
use App\Models\Reports;
use App\Models\PurchaseDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\InvoiceQR\QRDataGenerator;
use App\InvoiceQR\SellerNameElement;
use App\InvoiceQR\TaxAmountElement;
use App\InvoiceQR\TaxNoElement;
use App\InvoiceQR\TotalAmountElement;
use App\InvoiceQR\InvoiceDateElement;

class MaintenanceCardController extends Controller
{
    public function __construct()
    {
        set_time_limit(8000000);
    }


    public function index()
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $cards_customer_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 27)->get();
        $mntns_cards_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 48)->get();
        $mntns_cards_category = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 49)->get();
        $mntns_cards_item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $mntns_cards_item_id_external = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 47)->get();
        $car_list = MaintenanceCar::where('company_id', '=', $company->company_id)->get();
        $maintenance_card_q = MaintenanceCard::where('mntns_cards_status', SystemCode::where('system_code', 50001)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $maintenance_card_o = MaintenanceCard::where('mntns_cards_status', SystemCode::where('system_code', 50002)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $maintenance_card_c = MaintenanceCard::where('mntns_cards_status', SystemCode::where('system_code', 50003)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $maintenance_card_cancel = MaintenanceCard::where('mntns_cards_status', SystemCode::where('system_code', 50004)->where('company_group_id', $company->company_group_id)->first()->system_code_id)->count(); ///جاهزه
        $cards_status = SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', '=', 50)->get();
        $maintenance_card_all = MaintenanceCard::where('company_group_id', $company->company_group_id)->count(); ///

//       $maintenance_card = MaintenanceCard::where('company_id', request()->company_id)->orderBy('created_date', 'desc')->get();

        $query = DB::table('maintenance_cards_hd')
            ->join('companies', 'maintenance_cards_hd.company_id', '=', 'companies.company_id')
            ->join('system_codes as type', 'maintenance_cards_hd.mntns_cards_type', '=', 'type.system_code_id')
            ->join('system_codes as status', 'maintenance_cards_hd.mntns_cards_status', '=', 'status.system_code_id')
            ->join('customers', 'maintenance_cards_hd.customer_id', '=', 'customers.customer_id')
            ->join('accounts', 'customers.customer_account_id', '=', 'accounts.acc_id')
            ->join('system_codes as customer_status', 'customers.customer_type', '=', 'customer_status.system_code_id')
            ->join('maintenance_cars', 'maintenance_cards_hd.mntns_cars_id', '=', 'maintenance_cars.mntns_cars_id')
            ->join('trucks', 'maintenance_cars.car_cost_center', '=', 'trucks.truck_id');


        if (request()->mntns_cars_id || request()->company_id || request()->mntns_card_mobile || request()->mntns_card_no || request()->mntns_card_status) {
            $mntns_cars_id = request()->mntns_cars_id;
            $company_id = request()->company_id;
            $mntns_card_mobile = request()->mntns_card_mobile;
            $mntns_card_no = request()->mntns_card_no;
            $mntns_card_status = request()->mntns_card_status;

            $maintenance_card = $query
                ->when($mntns_cars_id, function ($query) use ($mntns_cars_id) {
                    return $query->where('maintenance_cars.mntns_cars_type', 'like', '%' . $mntns_cars_id . '%');
                })
                ->when($company_id, function ($query) use ($company_id) {
                    return $query->where('maintenance_cards_hd.company_id', $company_id);
                })
                ->when($mntns_card_mobile, function ($query) use ($mntns_card_mobile) {
                    return $query->where('maintenance_cards_hd.customer_mobile', 'like', '%' . $mntns_card_mobile . '%');
                })
                ->when($mntns_card_no, function ($query) use ($mntns_card_no) {
                    return $query->where('maintenance_cards_hd.mntns_cards_no', 'like', '%' . $mntns_card_no . '%');
                })
                ->when($mntns_card_status, function ($query) use ($mntns_card_status) {
                    return $query->where('maintenance_cards_hd.mntns_cards_status', '=', $mntns_card_status);
                })
                ->select('companies.company_name_ar', 'companies.company_name_en',
                    'type.system_code_name_ar as type_system_code_name_ar', 'type.system_code_name_en as type_system_code_name_en',
                    'customers.customer_name_full_ar', 'customers.customer_name_full_en', 'customer_status.system_code_name_ar as customer_system_code_name_ar',
                    'customer_status.system_code_name_en as customer_system_code_name_en', 'maintenance_cards_hd.created_date', 'customers.customer_mobile',
                    'trucks.truck_name', 'trucks.truck_plate_no', 'status.system_code_name_ar as status_system_code_name_ar',
                    'status.system_code_name_en as status_system_code_name_en', 'maintenance_cards_hd.mntns_cards_due_amount',
                    'maintenance_cards_hd.uuid', 'maintenance_cards_hd.mntns_cards_no', 'maintenance_cards_hd.customer_id',
                    'maintenance_cards_hd.mntns_cards_id', 'customers.customer_account_id', 'maintenance_cards_hd.mntns_cards_payment_amount',
                    'maintenance_cards_hd.company_group_id', 'accounts.acc_name_ar', 'accounts.acc_name_en', 'accounts.acc_code')
                ->orderBy("maintenance_cards_hd.mntns_cards_id", "asc")->paginate();
        } else {

            $maintenance_card = $query->where('maintenance_cards_hd.company_id', session('company')['company_id'])
                ->select('companies.company_name_ar', 'companies.company_name_en',
                    'type.system_code_name_ar as type_system_code_name_ar', 'type.system_code_name_en as type_system_code_name_en',
                    'customers.customer_name_full_ar', 'customers.customer_name_full_en', 'customer_status.system_code_name_ar as customer_system_code_name_ar',
                    'customer_status.system_code_name_en as customer_system_code_name_en', 'maintenance_cards_hd.created_date', 'customers.customer_mobile',
                    'trucks.truck_name', 'trucks.truck_plate_no', 'status.system_code_name_ar as status_system_code_name_ar',
                    'status.system_code_name_en as status_system_code_name_en', 'maintenance_cards_hd.mntns_cards_due_amount',
                    'maintenance_cards_hd.uuid', 'maintenance_cards_hd.mntns_cards_no', 'maintenance_cards_hd.customer_id',
                    'maintenance_cards_hd.mntns_cards_id', 'customers.customer_account_id', 'maintenance_cards_hd.mntns_cards_payment_amount',
                    'maintenance_cards_hd.company_group_id', 'accounts.acc_name_ar', 'accounts.acc_name_en', 'accounts.acc_code')
                ->orderBy("maintenance_cards_hd.mntns_cards_id", "asc")->paginate();
        }
//
//        if ($request->ajax()) {
//            return Datatables::of($maintenance_card)
//                ->addIndexColumn()
//                ->addColumn('action', function ($row) {
//                    return (string)view('Maintenance.MaintenanceCard.Actions.actions', compact('row'));
//                })
//                ->addColumn('company', function ($row) {
//                    if (\Lang::getLocale() == 'ar') {
//                        return $row->company_name_ar;
//                    } else {
//                        return $row->company_name_en;
//                    }
//                })
//                ->addColumn('card_no', function ($row) {
//                    return $row->mntns_cards_no;
//                })
//                ->addColumn('card_type', function ($row) {
//                    if (\Lang::getLocale() == 'ar') {
//                        return $row->type_system_code_name_ar;
//                    } else {
//                        return $row->type_system_code_name_en;
//                    }
//                })
//                ->addColumn('customer', function ($row) {
//
//                    if (\Lang::getLocale() == 'ar') {
//                        return $row->customer_name_full_ar ? $row->customer_name_full_ar : '';
//                    } else {
//                        return $row->customer_name_full_en ? $row->customer_name_full_en : '';
//                    }
//
//                })
//                ->addColumn('customer_type', function ($row) {
//                    if (\Lang::getLocale() == 'ar') {
//                        return $row->customer_system_code_name_ar ? $row->customer_system_code_name_ar : '';
//                    } else {
//                        return $row->customer_system_code_name_en ? $row->customer_system_code_name_en : '';
//                    }
//                })
//                ->addColumn('card_date', function ($row) {
//                    return $row->created_date;
//                })
//                ->addColumn('customer_mobile', function ($row) {
//                    return $row->customer_mobile;
//                })
//                ->addColumn('car', function ($row) {
//                    return $row->mntns_cars_type ? $row->mntns_cars_type : '';
//                })
//                ->addColumn('truckname', function ($row) {
//                    // return $row->truck_code ? $row->truck_code : '';
//                    return 'truck';
//                })
//                ->addColumn('status', function ($row) {
//                    if (\Lang::getLocale() == 'ar') {
//                        return $row->status_system_code_name_ar;
//                    } else {
//                        return $row->status_system_code_name_en;
//                    }
//                })
//                ->addColumn('payment', function ($row) {
//                    return $row->mntns_cards_payment_amount;
//                })
//                ->addColumn('due', function ($row) {
//                    return $row->mntns_cards_due_amount;
//                })
//                ->rawColumns(['action'])
//                ->make(true);
//        }


        return view('Maintenance.MaintenanceCard.index', compact('companies', 'cards_customer_type', 'mntns_cards_type', 'mntns_cards_category',
            'mntns_cards_item_disc_type', 'mntns_cards_item_id_external', 'maintenance_card_o', 'maintenance_card_c', 'maintenance_card_cancel',
            'maintenance_card_q', 'maintenance_card_all', 'car_list', 'maintenance_card', 'cards_status'));

    }

    public function create(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $cards_customer_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 27)->get();
        $mntns_cards_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 48)->get();
        $mntns_cards_category = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 49)->get();
        $mntns_cards_item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $mntns_cards_item_id_external = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', '47')->get();

        return view('Maintenance.MaintenanceCard.create', compact('cards_customer_type', 'mntns_cards_type', 'mntns_cards_category',
            'mntns_cards_item_disc_type', 'mntns_cards_item_id_external'));
    }

    public function getCarListByCustomerId(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $car_list = MaintenanceCar::where('company_id', '=', $company->company_id)
            ->where('customer_id', '=', $request->customer_id)
            ->with('brand')->get();

        return response()->json(['status' => 200, 'data' => $car_list]);

    }

    public function getCustomerByType(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customer = Customer::where('company_group_id', '=', $company->company_group_id)->where('customer_type', '=', $request->customer_type)->get();
        return response()->json(['status' => 200, 'data' => $customer]);

    }

    public function getPartByWarehousesType(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $part = StoreItem::where('company_id', '=', $company->company_id)->where('item_category', '=', $request->warehouses_type_id)->get();
        return response()->json(['status' => 200, 'data' => $part]);

    }

    public function getItemModal(Request $request)
    {
        $view = view('Maintenance.MaintenanceCard.form.add_item');
        return response()->json(['success' => true, 'view' => $view->renderSections()]);
    }

    public function cardCreate(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $cards_customer_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 27)->get();
        $mntns_cards_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 48)->get();
        $mntns_cards_category = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 49)->get();
        $mntns_cards_item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();
        $mntns_cards_item_id_external = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', '47')->get();
        $view = view('Maintenance.MaintenanceCard.form.add_card', compact('cards_customer_type', 'mntns_cards_type', 'mntns_cards_category',
            'mntns_cards_item_disc_type', 'mntns_cards_item_id_external'));
        return response()->json(['success' => true, 'view' => $view->renderSections()]);
    }

    public function cardStore(Request $request)
    {
        $rules = [
            'mntns_cards_type' => 'required',
            'mntns_cards_category' => 'required',
            'mntns_cards_customer_type' => 'required',
            'customer_id' => 'required',
            'mntns_cars_id' => 'required',
            'mntns_cars_meter' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $branch = session('branch');
        $company = session('company') ? session('company') : auth()->user()->company;
        $system_status_code = SystemCode::where('company_id', auth()->user()->company->company_id)->where('system_code', 50001)->first();

        //gnerate mntns cards no
        $current_serial = CompanyMenuSerial::where('company_id', auth()->user()->company->company_id)->where('branch_id', '=', $branch->branch_id)->where('app_menu_id', 71);
        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم كرت الصيانة يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'MNT-' . $branch->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);

        \DB::beginTransaction();
        $maintenance_card = new MaintenanceCard();

        $maintenance_card->uuid = \DB::raw('NEWID()');
        $maintenance_card->mntns_cards_no = $new_serial;
        $maintenance_card->company_group_id = $company->company_group_id;
        $maintenance_card->company_id = $company->company_id;
        $maintenance_card->branch_id = $branch->branch_id;
        $maintenance_card->customer_id = $request->customer_id;
        $maintenance_card->mntns_cards_customer_type = $request->mntns_cards_customer_type;
        $maintenance_card->mntns_cards_status = $system_status_code->system_code_id;
        $maintenance_card->mntns_cards_type = $request->mntns_cards_type;
        $maintenance_card->mntns_cards_category = $request->mntns_cards_category;

        $maintenance_card->mntns_cars_id = $request->mntns_cars_id;
        $maintenance_card->mntns_cars_meter = $request->mntns_cars_meter;
        $maintenance_card->mntns_cards_notes = $request->mntns_cards_notes;
        $maintenance_card->mntns_cards_payment_amount = 0;
        $maintenance_card->created_user = auth()->user()->user_id;

        $maintenance_card_save = $maintenance_card->save();
        if (!$maintenance_card_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);


        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $maintenance_card->refresh()->uuid]);

    }

    public function cardUpdate(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $rules = [
            'card_uuid' => 'required|exists:maintenance_cards_hd,uuid',
            'mntns_cards_type' => 'required',
            'mntns_cards_category' => 'required',
            'mntns_cars_meter' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $card = MaintenanceCard::where('uuid', '=', $request->card_uuid)->first();

        $card->mntns_cards_type = $request->mntns_cards_type;
        $card->mntns_cards_category = $request->mntns_cards_category;
        $card->mntns_cards_status = $request->mntns_cards_status;
        $card->mntns_cars_meter = $request->mntns_cars_meter;
        $card->mntns_cards_notes = $request->mntns_cards_notes;

        $card_save = $card->save();

        $card_car = MaintenanceCar::where('company_group_id', $company->company_group_id)->where('mntns_cars_id', $card->mntns_cars_id)->first();
        $status_id = \App\Models\SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 30)
            ->where('system_code', 131)->first()->system_code_id;
        $status_close = \App\Models\SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 30)
            ->where('system_code', 80)->first()->system_code_id;
        $status_card = \App\Models\SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 50)
            ->where('system_code_id', $card->mntns_cards_status)->first();

        // return  $card_car->car_cost_center ;
        // return  $status_card->system_code ;
        $truck = Trucks::where('truck_id', '=', $card_car->car_cost_center)->first();

        if ($truck) {
            if ($status_card->system_code == 50002 || $status_card->system_code == 50001) {
                $truck->update([
                    'truck_status' => $status_id,
                ]);

            } else {
                $truck->update([
                    'truck_status' => $status_close,
                ]);

            }
        }


        if (!$card_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح',]);

    }

    public function itemStore(Request $request)
    {
        switch ($request->item_type) {
            case 'internal':
                return MaintenanceCardController::saveInternalItem($request);
                break;
            case 'external':
                return MaintenanceCardController::saveExternalItem($request);
                break;
            case 'part':
                return MaintenanceCardController::savePartItem($request);
                break;
        }

    }

    public function itemDelete(Request $request)
    {
        $rules = [
            'uuid' => 'required|exists:maintenance_cards_dt,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $maintenance_card_details = MaintenanceCardDetails::where('uuid', '=', $request->uuid)->first();
        $maintenance_card_details->isdeleted = 1;
        $maintenance_card_details->deleted_by = auth()->user()->user_id;
        $maintenance_card_details_save = $maintenance_card_details->save();
        if (!$maintenance_card_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $close_journalHd = SystemCode::where('company_group_id', $maintenance_card_details->company_group_id)
            ->where('system_code', 905)->firstOrFail()['system_code_id'];

        if (isset($maintenance_card_details->invoiceDt)) {
            $maintenance_card_details->invoiceDt->invoiceHd()->update([
                'invoice_status' => SystemCode::where('company_group_id', $maintenance_card_details->company_group_id)
                    ->where('system_code', 121005)->firstOrFail()['system_code_id']
            ]);
            $maintenance_card_details->invoiceDt->invoiceHd->journalHd()->update([
                'journal_status' => $close_journalHd
            ]);
        }
        $update_total = MaintenanceCardController::updateCardTotal($maintenance_card_details->card);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $total = [

            'total_mntns_cards_disc_amount' => $maintenance_card_details->card->internalSumDisc(),
            'total_mntns_cards_vat_amount' => $maintenance_card_details->card->internalSumVat(),
            'total_mntns_cards_amount' => $maintenance_card_details->card->internalSumTotal(),

            'total_external_mntns_cards_vat_amount' => $maintenance_card_details->card->externalSumVat(),
            'total_external_mntns_cards_amount' => $maintenance_card_details->card->externalSumTotal(),

            'total_part_mntns_cards_disc_amount' => $maintenance_card_details->card->partSumDisc(),
            'total_part_mntns_cards_vat_amount' => $maintenance_card_details->card->partSumVat(),
            'total_part_mntns_cards_amount' => $maintenance_card_details->card->partSumTotal(),


            'total_cards_disc_amount' => $maintenance_card_details->card->internalSumDisc() + $maintenance_card_details->card->partSumDisc(),
            'total_cards_vat_amount' => $maintenance_card_details->card->internalSumVat() + $maintenance_card_details->card->externalSumVat() + $maintenance_card_details->card->partSumVat(),
            'total_cards_amount' => $maintenance_card_details->card->internalSumTotal() + $maintenance_card_details->card->externalSumTotal() + $maintenance_card_details->card->partSumTotal(),
            'mntns_cards_due_amount' => $maintenance_card_details->card->mntns_cards_due_amount,


        ];
        return \Response::json(['success' => true, 'msg' => 'تمت الحذف بنجاح', 'data' => $maintenance_card_details, 'total' => $total]);

    }

    public function saveInternalItem(Request $request)
    {
        $rules = [
            'mntns_cards_id' => 'required|exists:maintenance_cards_hd,mntns_cards_id',
            'internal_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $company = session('company') ? session('company') : auth()->user()->company;

        $intenal_data = json_decode($request->internal_table_data, true);

        $is_added_befor = MaintenanceCardDetails::where('mntns_cards_id', $request->mntns_cards_id)->where('mntns_cards_item_id', '=', $intenal_data['mntns_cards_item_id'])->where('isdeleted', '=', 0);
        // if($is_added_befor->count() > 0)
        //{
        //   return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا العنصر مسبقا..!' ]);
        // }

        \DB::beginTransaction();
        $maintenance_card_details = new MaintenanceCardDetails();

        $maintenance_card_details->uuid = \DB::raw('NEWID()');
        $maintenance_card_details->mntns_cards_id = $request->mntns_cards_id;
        $maintenance_card_details->company_group_id = $company->company_group_id;
        $maintenance_card_details->company_id = $company->company_id;
        $maintenance_card_details->branch_id = auth()->user()->user_default_branch_id;
        $maintenance_card_details->customer_id = $maintenance_card_details->card->customer_id;
        $maintenance_card_details->mntns_cars_id = $maintenance_card_details->card->mntns_cars_id;
        $maintenance_card_details->mntns_cards_item_type = 535;
        $maintenance_card_details->mntns_cards_item_id = $intenal_data['mntns_cards_item_id'];
        $maintenance_card_details->mntns_cards_item_qty = 1;
        $maintenance_card_details->mntns_cards_item_price = floatval($intenal_data['mntns_type_value']);
        $maintenance_card_details->mntns_cards_item_hours = floatval($intenal_data['mntns_type_hours']);
        $maintenance_card_details->mntns_cards_item_amount = floatval($intenal_data['mntns_type_value']);
        $maintenance_card_details->mntns_cards_disc_type = $intenal_data['mntns_cards_item_disc_type'];
        $maintenance_card_details->mntns_cards_disc_value = floatval($intenal_data['mntns_cards_item_disc_value']);
        $maintenance_card_details->mntns_cards_disc_amount = floatval($intenal_data['mntns_cards_item_disc_amount']);
        $maintenance_card_details->mntns_cards_vat_value = floatval($intenal_data['vat_id']);
        $maintenance_card_details->mntns_cards_vat_amount = floatval($intenal_data['vat_value']);
        $maintenance_card_details->mntns_cards_amount = floatval($intenal_data['total_afte_vat']);
        $maintenance_card_details->created_user = auth()->user()->user_id;

        $maintenance_card_details_save = $maintenance_card_details->save();

        if (!$maintenance_card_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = MaintenanceCardController::updateCardTotal($maintenance_card_details->card);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }


        $maintenance_card_details = MaintenanceCardDetails::where('mntns_cards_dt_id', '=', $maintenance_card_details->getKey())->first();
        $total = [
            'total_mntns_cards_amount' => $maintenance_card_details->card->internalSumTotal(),
            'total_mntns_cards_disc_amount' => $maintenance_card_details->card->internalSumDisc(),
            'total_mntns_cards_vat_amount' => $maintenance_card_details->card->internalSumVat(),
            'mntns_cards_due_amount' => $maintenance_card_details->card->mntns_cards_due_amount,
            'total_cards_disc_amount' => $maintenance_card_details->card->internalSumDisc() + $maintenance_card_details->card->partSumDisc(),
            'total_cards_vat_amount' => $maintenance_card_details->card->internalSumVat() + $maintenance_card_details->card->externalSumVat() + $maintenance_card_details->card->partSumVat(),
            'total_cards_amount' => $maintenance_card_details->card->internalSumTotal() + $maintenance_card_details->card->externalSumTotal() + $maintenance_card_details->card->partSumTotal(),
        ];
        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $maintenance_card_details->uuid, 'total' => $total]);

    }

    public function saveExternalItem(Request $request)
    {
        $rules = [
            'mntns_cards_id' => 'required|exists:maintenance_cards_hd,mntns_cards_id',
            'external_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }
        try {
            $company = session('company') ? session('company') : auth()->user()->company;

            $external_data = json_decode($request->external_table_data, true);

            $is_added_befor = MaintenanceCardDetails::where('mntns_cards_id', $request->mntns_cards_id)
                ->where('mntns_cards_item_id', '=', $external_data['mntns_cards_item_id'])->where('isdeleted', '=', 0);
            //  if($is_added_befor->count() > 0)
            // {
            //   return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا العنصر مسبقا..!' ]);
            // }

            DB::beginTransaction();
            $maintenance_card_details = new MaintenanceCardDetails();
            $maintenance_card_details->uuid = \DB::raw('NEWID()');
            $maintenance_card_details->mntns_cards_id = $request->mntns_cards_id;
            $maintenance_card_details->company_group_id = $company->company_group_id;
            $maintenance_card_details->company_id = $company->company_id;
            $maintenance_card_details->branch_id = auth()->user()->user_default_branch_id;
            $maintenance_card_details->customer_id = $maintenance_card_details->card->customer_id;
            $maintenance_card_details->mntns_cars_id = $maintenance_card_details->card->mntns_cars_id;
            $maintenance_card_details->mntns_cards_item_type = 536;
            $maintenance_card_details->mntns_cards_item_id = $external_data['supplier_id'];
            $maintenance_card_details->mntns_cards_item_qty = 1;
            $maintenance_card_details->mntns_cards_item_price = floatval($external_data['mntns_type_value']);
            $maintenance_card_details->mntns_cards_item_hours = floatval($external_data['mntns_type_hours']);
            $maintenance_card_details->mntns_cards_item_amount = floatval($external_data['mntns_type_value']);
            $maintenance_card_details->mntns_cards_disc_type = 0;
            $maintenance_card_details->mntns_cards_disc_value = 0;
            $maintenance_card_details->mntns_cards_disc_amount = 0;
            $maintenance_card_details->mntns_cards_vat_value = floatval($external_data['vat_id']);
            $maintenance_card_details->mntns_cards_vat_amount = floatval($external_data['vat_value']);
            $maintenance_card_details->mntns_cards_amount = floatval($external_data['total_afte_vat']);
            $maintenance_card_details->created_user = auth()->user()->user_id;
            $maintenance_card_details->mntns_cards_item_notes = $external_data['mntns_cards_item_notes'];
            $maintenance_card_details->invoice_date_external = $external_data['invoice_date_external'];
            $maintenance_card_details->invoice_no_external = $external_data['invoice_no_external'];
            // $maintenance_card_details->invoice_vat_no_external = $external_data['customer_tax_no'];
            // $maintenance_card_details->invoice_supllier_external = $external_data['customer_name'];
            // $maintenance_card_details->invoice_pay_meth_external = $external_data['payment_tems'];

            $maintenance_card_details_save = $maintenance_card_details->save();

            if (!$maintenance_card_details_save) {
                return \Response::json(['success' => false, 'msg' => __('messages.wrong_data')]);
            }

            $update_total = MaintenanceCardController::updateCardTotal($maintenance_card_details->card);

            if (!$update_total['success']) {
                return \Response::json(['success' => false, 'msg' => __('messages.wrong_data')]);
            }

            $maintenance_card_details = MaintenanceCardDetails::where('mntns_cards_dt_id', '=', $maintenance_card_details->getKey())->first();
            $total = [

                'total_mntns_cards_amount' => $maintenance_card_details->card->externalSumTotal(),
                'total_mntns_cards_vat_amount' => $maintenance_card_details->card->externalSumVat(),
                'mntns_cards_due_amount' => $maintenance_card_details->card->mntns_cards_due_amount,
                'total_cards_disc_amount' => $maintenance_card_details->card->internalSumDisc() + $maintenance_card_details->card->partSumDisc(),
                'total_cards_vat_amount' => $maintenance_card_details->card->internalSumVat() + $maintenance_card_details->card->externalSumVat() + $maintenance_card_details->card->partSumVat(),
                'total_cards_amount' => $maintenance_card_details->card->internalSumTotal() + $maintenance_card_details->card->externalSumTotal() + $maintenance_card_details->card->partSumTotal(),
            ];

            // make invoice
            $this->savePurchaseInvoice($request, $maintenance_card_details->mntns_cards_dt_id);
            DB::commit();
            return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $maintenance_card_details->uuid, 'total' => $total]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('maintenance saveExternalItem', [$e]);
            //  return  Log::error('maintenance saveExternalItem', [$e]);
            return \Response::json(['success' => false, 'msg' => __('messages.wrong_data')]);
        }

    }

    public function savePurchaseInvoice(Request $request, $card_dt_id)
    {
        try {
            DB::beginTransaction();
            $company = session('company') ? session('company') : auth()->user()->company;

            $last_invoice_reference = CompanyMenuSerial::where('company_id', $company->company_id)
                ->where('app_menu_id', 120)->latest()->first();


            if (isset($last_invoice_reference)) {
                $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                $array_number = explode('-', $last_invoice_reference_number);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_number = implode('-', $array_number);
                $last_invoice_reference->update(['serial_last_no' => $string_number]);
            } else {
                $string_number = 'INV-P-' . session('branch')['branch_id'] . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 120,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'serial_last_no' => $string_number,
                    'created_user' => auth()->user()->user_id
                ]);
            }

            $external_data = (object)json_decode($request->external_table_data, true);
            $card = MaintenanceCard::where('uuid', '=', $request->card_uuid)->first();
            $invoice_hd = InvoiceHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
//            'acc_period_id' => $request->acc_period_id,
                'invoice_date' => Carbon::now(),
                'invoice_due_date' => $external_data->invoice_date_external,
                //'invoice_amount_b' => $request->invoice_amount - $request->invoice_vat_amount,
                'invoice_amount' => $external_data->total_afte_vat,
                'invoice_vat_rate' => $external_data->vat_per < 1 ? $external_data->vat_per * 100 : $external_data->vat_per,
                // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
                'invoice_vat_amount' => $external_data->vat_value,
//            'invoice_discount_total' => $request->invoice_discount,
                'invoice_down_payment' => 0,
                'invoice_total_payment' => 0,
                'invoice_notes' => $external_data->mntns_cards_item_notes . ' ' . 'لكارت صيانه رقم' . ' ' . $card->mntns_cards_no,
                'invoice_no' => $string_number,
                'created_user' => auth()->user()->user_id,
                'branch_id' => session('branch')['branch_id'],
                'customer_id' => $external_data->supplier_id,
                'customer_name' => $external_data->customer_name,
                'customer_address' => $external_data->customer_address,
                'customer_tax_no' => $external_data->customer_tax_no,
                'customer_phone' => $external_data->customer_phone,

//            'po_number' => $request->po_number,
                'payment_tems' => $external_data->payment_tems,
                'gr_number' => $external_data->invoice_no_external,
                'supply_date' => $external_data->invoice_date_external,
                'invoice_is_payment' => 0,
                'invoice_type' => 11
            ]);
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $external_data->account_id,
                'invoice_item_unit' => 93, // عدد ساعات
                'invoice_item_amount' => $external_data->total_befor_vat,
                'invoice_item_quantity' => $external_data->mntns_type_hours,
                'invoice_item_price' => $external_data->mntns_type_value,
                'invoice_reference_no' => $card_dt_id,

                'invoice_item_vat_rate' => $external_data->vat_per < 1 ? $external_data->vat_per * 100 : $external_data->vat_per,

                'invoice_item_vat_amount' => $external_data->vat_value,

                'invoice_discount_type' => 1,
                'invoice_discount_amount' => 0,
                'invoice_discount_total' => 0,
                'invoice_total_amount' => $external_data->total_afte_vat,
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $external_data->mntns_cards_item_notes,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => $external_data->invoice_date_external,
                'item_account_id' => $external_data->account_id
            ]);

            $qr = QRDataGenerator::fromArray([
                new SellerNameElement($company->company_group_ar),
                new TaxNoElement($company->company_tax_no),
                new InvoiceDateElement(Carbon::now()->timezone('Asia/Riyadh')->toDateTimeString()),
                new TotalAmountElement($invoice_hd->invoice_amount),
                new TaxAmountElement($invoice_hd->invoice_vat_amount)
            ])->toBase64();

            $invoice_hd->update(['qr_data' => $qr]);


            $journal_controller = new JournalsController();

            $total_amount = $invoice_hd->invoice_amount;
            $vat_amount = $invoice_hd->invoice_vat_amount;
            $cost_center_id = 120;
            $cc_voucher_id = $invoice_hd->invoice_id;
            $vat_notes = 'ضريبه فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . 'لكارت صيانه رقم' . ' ' . $card->mntns_cards_no;
            $supplier_notes = ' فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . 'لكارت صيانه رقم' . ' ' . $card->mntns_cards_no;
            $notes = ' فاتوره مشتريات رقم ' . ' ' . $invoice_hd->invoice_no . ' ' . 'لكارت صيانه رقم' . ' ' . $card->mntns_cards_no;
            $journal_category_id = 57;
            $cc_car_id = $card->car->car_cost_center;
            $invoice_dts = $invoice_hd->invoiceDetails;

            $customer = Customer::where('customer_id', $invoice_hd->customer_id)->first();
            if (($customer->cus_type->system_code) == 539) {
//    قيد فاتوره مشتريات مورد علي الحساب
                $journal_controller->addSupplierPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                    $cc_voucher_id, $vat_notes, $supplier_notes,
                    $journal_category_id, $notes,
                    $invoice_dts, $cc_car_id);

            } else {
////    قيد فاتوره مشتريات مورد افراد
                $journal_controller->addSupplierCashPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                    $cc_voucher_id, $vat_notes, $supplier_notes,
                    $journal_category_id, $notes,
                    $invoice_dts, $cc_car_id);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('maintenance savePurchaseInvoice', [$e]);
            throw $e;
        }
    }


    public function savePartItem(Request $request)
    {
        $rules = [
            'mntns_cards_id' => 'required|exists:maintenance_cards_hd,mntns_cards_id',
            'part_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }


        $company = session('company') ? session('company') : auth()->user()->company;

        $intenal_data = json_decode($request->part_table_data, true);

        $is_added_befor = MaintenanceCardDetails::where('mntns_cards_id', $request->mntns_cards_id)->where('mntns_cards_item_id', '=', $intenal_data['mntns_cards_item_id'])->where('isdeleted', '=', 0);
        //  if($is_added_befor->count() > 0)
        // {
        //    return \Response::json(['success' => false, 'msg' => 'تم اضافة هذا العنصر مسبقا..!' ]);
        // }

        \DB::beginTransaction();
        $maintenance_card_details = new MaintenanceCardDetails();


        $maintenance_card_details->uuid = \DB::raw('NEWID()');
        $maintenance_card_details->mntns_cards_id = $request->mntns_cards_id;
        $maintenance_card_details->company_group_id = $company->company_group_id;
        $maintenance_card_details->company_id = $company->company_id;
        $maintenance_card_details->branch_id = auth()->user()->user_default_branch_id;
        $maintenance_card_details->customer_id = $maintenance_card_details->card->customer_id;
        $maintenance_card_details->mntns_cars_id = $maintenance_card_details->card->mntns_cars_id;
        $maintenance_card_details->mntns_cards_item_type = 537;
        $maintenance_card_details->mntns_cards_item_id = $intenal_data['mntns_cards_item_id'];
        $maintenance_card_details->store_category_type = $intenal_data['store_category_type'];
        $maintenance_card_details->mntns_cards_item_qty = floatval($intenal_data['mntns_cards_item_qty']);
        $maintenance_card_details->mntns_cards_item_price = floatval($intenal_data['mntns_cards_item_price']);
        $maintenance_card_details->mntns_cards_item_hours = floatval($intenal_data['mntns_type_hours']);
        $maintenance_card_details->mntns_cards_item_amount = floatval($intenal_data['mntns_type_value']);
        $maintenance_card_details->mntns_cards_disc_type = $intenal_data['mntns_cards_item_disc_type'];
        $maintenance_card_details->mntns_cards_disc_value = floatval($intenal_data['mntns_cards_item_disc_value']);
        $maintenance_card_details->mntns_cards_disc_amount = floatval($intenal_data['mntns_cards_item_disc_amount']);
        $maintenance_card_details->mntns_cards_vat_value = floatval($intenal_data['vat_id']);
        $maintenance_card_details->mntns_cards_vat_amount = floatval($intenal_data['vat_value']);
        $maintenance_card_details->mntns_cards_amount = floatval($intenal_data['total_afte_vat']);
        $maintenance_card_details->created_user = auth()->user()->user_id;

        $maintenance_card_details_save = $maintenance_card_details->save();

        if (!$maintenance_card_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = MaintenanceCardController::updateCardTotal($maintenance_card_details->card);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $maintenance_card_details = MaintenanceCardDetails::where('mntns_cards_dt_id', '=', $maintenance_card_details->getKey())->first();
        $total = [

            'total_mntns_cards_amount' => $maintenance_card_details->card->partSumTotal(),
            'total_mntns_cards_disc_amount' => $maintenance_card_details->card->partSumDisc(),
            'total_mntns_cards_vat_amount' => $maintenance_card_details->card->partSumVat(),
            'mntns_cards_due_amount' => $maintenance_card_details->card->mntns_cards_due_amount,
            'total_cards_disc_amount' => $maintenance_card_details->card->internalSumDisc() + $maintenance_card_details->card->partSumDisc(),
            'total_cards_vat_amount' => $maintenance_card_details->card->internalSumVat() + $maintenance_card_details->card->externalSumVat() + $maintenance_card_details->card->partSumVat(),
            'total_cards_amount' => $maintenance_card_details->card->internalSumTotal() + $maintenance_card_details->card->externalSumTotal() + $maintenance_card_details->card->partSumTotal(),
        ];

        \DB::commit();

        /////////// اصدار فاتوره بيع للصنف من المستودع عنداضافه الصنف

        $card = MaintenanceCard::where('uuid', '=', $maintenance_card_details->card->card_uuid)->first();
        $vou_type = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '62006')->first();
        $branch_id = $maintenance_card_details->branch_id;
        // $sales_invoice = MaintenanceCardController::storeItemIndividually($card, $vou_type);
        // if (!$sales_invoice->getData()->success) {
        //     return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        // }
//return $maintenance_card_details->branch_id ;
        switch ($vou_type->system_code) {
            case '62006':

                $qty_field = 'store_vou_qnt_o';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 6565);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم المبيعات يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'S-m-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                break;

            default:
                abort(404);
        }


        //\DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch_id;

        $purchase->store_category_type = $maintenance_card_details->item->item_category;
        $purchase->store_vou_type = $vou_type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $maintenance_card_details->card->customer->customer_id;
        $purchase->store_acc_name = $maintenance_card_details->card->car->mntns_cars_type;
        $purchase->store_acc_tax_no = $maintenance_card_details->card->mntns_cards_no;
        $purchase->store_vou_pay_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', 57001)->first()->system_code_id;
        $purchase->store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', 125001)->first()->system_code_id;
        $purchase->store_vou_notes = $maintenance_card_details->card->mntns_cards_notes;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;

        $purchase_save = $purchase->save();

        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);

        //store part item


        $purcahse_details = new PurchaseDetails();


        $item_data_set[] = [
            'uuid' => \DB::raw('NEWID()'),
            'store_hd_id' => $purchase->store_hd_id,
            'company_group_id' => $purchase->company_group_id,
            'company_id' => $purchase->company_id,
            'branch_id' => $purchase->branch_id,

            'store_category_type' => $purchase->store_category_type,
            'store_vou_type' => $vou_type->system_code_id,
            'store_vou_date' => Carbon::now(),
            'created_user' => auth()->user()->user_id,
            'store_acc_no' => $purchase->store_acc_no,

            'store_vou_item_id' => $intenal_data['mntns_cards_item_id'],
            'store_vou_qnt_o' => floatval($intenal_data['mntns_cards_item_qty']),
            'store_vou_loc' => $maintenance_card_details->partItem->item_location,
            'store_vou_item_price_cost' => floatval($maintenance_card_details->partItem->item_price_cost),
            'store_vou_item_price_unit' => floatval($maintenance_card_details->partItem->item_price_cost),
            'store_vou_item_total_price' => floatval($maintenance_card_details->mntns_cards_item_amount),

            'store_vou_disc_type' => $maintenance_card_details->mntns_cards_disc_type,
            'store_voue_disc_value' => floatval($maintenance_card_details->mntns_cards_disc_value),
            'store_vou_disc_amount' => floatval($maintenance_card_details->mntns_cards_disc_amount),

            'store_vou_vat_rate' => floatval($maintenance_card_details->mntns_cards_vat_value),
            'store_vou_vat_amount' => floatval($maintenance_card_details->mntns_cards_vat_amount),
            'store_vou_price_net' => floatval($maintenance_card_details->mntns_cards_amount),

        ];


        //update item details when type equle enter Receipt
        if ($vou_type->system_code == '62006') {
            $store_item = StoreItem::where('item_id', $intenal_data['mntns_cards_item_id'])->first();
            info($maintenance_card_details->item_id);
            if ($store_item->item_balance < $maintenance_card_details->mntns_cards_item_qty) {
                return \Response::json(['success' => false, 'msg' => 'الكمية الحالية غير كافية']);
            }
            $store_item->item_balance = $store_item->item_balance - $maintenance_card_details->mntns_cards_item_qty;
            $store_item->last_price_sales = $store_item->item_price_sales;
            $store_item->item_price_sales = (floatval($maintenance_card_details->mntns_cards_item_price)) / 2;
            $store_item->updated_user = auth()->user()->user_id;
            $store_item->updated_date = Carbon::now();

            $store_item_save = $store_item->save();

            if (!$store_item_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }
        }

        $purcahse_details_save = $purcahse_details->insert($item_data_set);

        if (!$purcahse_details_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $update_total = StoreSalesController::updateHeaderTotal($purchase);


        /////////// اصدار فاتوره بيع للصنف من المستودع عنداضافه الصنف انتهاء

        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $maintenance_card_details->uuid, 'total' => $total]);

    }

    public function updateCardTotal($card)
    {
        $card = $card;
        $card->mntns_cards_internal_repairs = $card->internalSumTotal();
        $card->mntns_cards_outside_repairs = $card->externalSumTotal();
        $card->mntns_cards_spare_parts = $card->partSumTotal();
        $card->mntns_cards_discount = $card->internalSumDisc() + $card->partSumDisc();
        $card->mntns_cards_vat_amount = $card->internalSumVat() + $card->externalSumVat() + $card->partSumVat();
        $card->mntns_cards_total_amount = $card->cardSumTotal();
        $card->mntns_cards_due_amount = $card->cardSumTotal() - $card->mntns_cards_payment_amount;

        $card_save = $card->save();

        if (!$card_save) {
            return ['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام'];
        }
        return ['success' => true, 'msg' => 'تمت الحذف بنجاح'];
    }



    // public function store(Request $request)
    // {
    //     return $request->internal_table_data;
    //     $rules = [
    //         'mntns_cards_type' => 'required',
    //         'mntns_cards_category' => 'required',
    //         'mntns_cards_customer_type'  => 'required',
    //         'customer_id'  => 'required',
    //         'mntns_cars_id'  => 'required',
    //         'mntns_cars_meter'  => 'required',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails())
    //     {
    //         return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all()) ]);
    //     }

    //     $company = session('company') ? session('company') : auth()->user()->company;
    //     //return $company;
    //     \DB::beginTransaction();
    //     $maintenance_card = new MaintenanceCard();

    //     $maintenance_card->uuid = \DB::raw('NEWID()');
    //     $maintenance_card->mntns_cards_no = $request->mntns_cards_no ;
    //     $maintenance_card->company_group_id = $company->company_group_id ;
    //     $maintenance_card->company_id = $company->company_id ;
    //     $maintenance_card->branch_id = auth()->user()->user_default_branch_id ;
    //     $maintenance_card->customer_id = $request->customer_id ;
    //     $maintenance_card->mntns_cards_customer_type = $request->mntns_cards_customer_type ;
    //     $maintenance_card->mntns_cards_status = 530 ;
    //     $maintenance_card->mntns_cards_type = $request->mntns_cards_type ;
    //     $maintenance_card->mntns_cards_category = $request->mntns_cards_category ;

    //     $maintenance_card->mntns_cards_start_date = $request->mntns_cards_start_date ;
    //     $maintenance_card->mntns_cards_closed_date = $request->mntns_cards_closed_date ;
    //     $maintenance_card->mntns_cars_id = $request->mntns_cars_id ;
    //     $maintenance_card->mntns_cars_meter = $request->mntns_cars_meter ;
    //     $maintenance_card->mntns_cards_notes = $request->mntns_cards_notes ;
    //     //$maintenance_card->mntns_cards_invoice_no = $request->mntns_cards_invoice_no ;
    //     $maintenance_card->mntns_cards_internal_repairs = $request->mntns_cards_internal_repairs ;
    //     $maintenance_card->mntns_cards_outside_repairs = $request->mntns_cards_outside_repairs ;
    //     //$maintenance_card->mntns_cards_spare_parts = $request->mntns_cards_spare_parts ;

    //     $maintenance_card->mntns_cards_discount = $request->mntns_cards_discount ;
    //     $maintenance_card->mntns_cards_vat_amount = $request->mntns_cards_vat_amount ;
    //     $maintenance_card->mntns_cards_total_amount = $request->mntns_cards_internal_repairs + $request->mntns_cards_outside_repairs + $request->mntns_cards_spare_parts ;
    //     $maintenance_card->mntns_cards_payment_amount = 0 ;
    //     $maintenance_card->mntns_cards_due_amount = $request->mntns_cards_internal_repairs + $request->mntns_cards_outside_repairs + $request->mntns_cards_spare_parts ;
    //     $maintenance_card->created_user = auth()->user()->user_id ;


    //     $maintenance_card_save = $maintenance_card->save() ;

    //     if(!$maintenance_card_save)
    //     {
    //         //return redirect()->route('maintenance-card.create')->with(['warning' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
    //         return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
    //     }


    //     $intenal_data = json_decode($request->internal_table_data, true);
    //     $external_data = json_decode($request->external_table_data, true);
    //     $part_data = json_decode($request->part_table_data, true);
    //     if(count($intenal_data)>0)
    //     {
    //         $maintenance_card_details = new MaintenanceCardDetails();
    //         $internal_data_set = [];

    //         foreach ($intenal_data as  $i => $d)
    //         {
    //             $internal_data_set[] = [
    //                 'uuid' => \DB::raw('NEWID()'),
    //                 'mntns_cards_id' => $maintenance_card->mntns_cards_id,
    //                 'company_group_id' => $company->company_group_id ,
    //                 'company_id' =>  $company->company_id ,
    //                 'branch_id' =>  auth()->user()->user_default_branch_id,
    //                 'customer_id' => $request->customer_id,
    //                 'mntns_cars_id' => $request->mntns_cars_id ,
    //                 'mntns_cards_item_type' => 535,
    //                 'mntns_cards_item_id' =>  $d['mntns_cards_item_id'],
    //                 'mntns_cards_item_qty' => 1,
    //                 'mntns_cards_item_price' => floatval($d['mntns_type_value']),
    //                 'mntns_cards_item_hours' => floatval($d['mntns_type_hours']),
    //                 'mntns_cards_item_amount' => floatval($d['mntns_type_value']),
    //                 'mntns_cards_disc_type' => $d['mntns_cards_item_disc_type'],
    //                 'mntns_cards_disc_value' => floatval($d['mntns_cards_item_disc_value']),
    //                 'mntns_cards_disc_amount' => floatval($d['mntns_cards_item_disc_amount'] ),
    //                 'mntns_cards_vat_value' => floatval($d['vat_id']),
    //                 'mntns_cards_vat_amount' => floatval($d['vat_value']),
    //                 'mntns_cards_amount' => floatval($d['total_afte_vat']),
    //                 'created_user' =>  auth()->user()->user_id,
    //             ];
    //         }

    //         $maintenance_card_details_save = $maintenance_card_details->insert($internal_data_set);

    //         if(!$maintenance_card_details_save)
    //         {
    //             return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
    //         }
    //     }

    //     if(count($external_data)>0)
    //     {
    //         $maintenance_card_details = new MaintenanceCardDetails();
    //         $external_data_set = [];

    //         foreach ($external_data as  $i => $d)
    //         {
    //             $external_data_set[] = [
    //                 'uuid' => \DB::raw('NEWID()'),
    //                 'mntns_cards_id' => $maintenance_card->mntns_cards_id,
    //                 'company_group_id' => $company->company_group_id ,
    //                 'company_id' =>  $company->company_id ,
    //                 'branch_id' =>  auth()->user()->user_default_branch_id,
    //                 'customer_id' => $request->customer_id,
    //                 'mntns_cars_id' => $request->mntns_cars_id ,
    //                 'mntns_cards_item_type' => 536,
    //                 'mntns_cards_item_id' =>  $d['mntns_cards_item_id'],
    //                 'mntns_cards_item_qty' => 1,
    //                 'mntns_cards_item_price' => floatval($d['mntns_type_value']),
    //                 'mntns_cards_item_hours' => floatval($d['mntns_type_hours']),
    //                 'mntns_cards_item_amount' => floatval($d['mntns_type_value']),
    //                 'mntns_cards_disc_type' => 0,
    //                 'mntns_cards_disc_value' => 0,
    //                 'mntns_cards_disc_amount' => 0 ,
    //                 'mntns_cards_vat_value' => floatval($d['vat_id']),
    //                 'mntns_cards_vat_amount' => floatval($d['vat_value']),
    //                 'mntns_cards_amount' => floatval($d['total_afte_vat']),
    //                 'created_user' =>  auth()->user()->user_id,
    //             ];
    //         }

    //         $maintenance_card_details_save = $maintenance_card_details->insert($external_data_set);

    //         if(!$maintenance_card_details_save)
    //         {
    //             return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام' ]);
    //         }
    //     }

    //     \DB::commit();
    //     return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح' ]);
    //     //return redirect()->route('maintenance-card.index')->with(['success' => 'تم اضافة الكرت بنجاح']);


    // }


    public function edit(Request $request, $uuid)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $cards_customer_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 27)->get();
        $mntns_cards_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 48)->get();
        $mntns_cards_category = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 49)->get();
        $mntns_cards_item_disc_type = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 51)->get();

        $mntns_cards_item_id_external = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', '47')->get();
        $suppliers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 1)->get();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        $accounts = Account::where('company_group_id', $company->company_group_id)->where('acc_level', $company->companyGroup->accounts_levels_number)
            ->where('main_type_id', 'like', '4' . '%')->get();

        $customer_list = Customer::where('company_group_id', $company->company_group_id)->get();
        $car_list = MaintenanceCar::where('company_id', '=', $company->company_id)
            ->with('brand')->get();
        $cards_status = SystemCode::where('company_id', $company->company_id)->where('sys_category_id', '=', 50)->get();
        // return $cards_status ;
        //->where('system_code','!=',50003)
        $card = MaintenanceCard::where('uuid', $uuid)->first();

        //       انواع الايرادات
        $system_code_types = SystemCode::where('sys_category_id', 58)
            ->where('company_group_id', $company->company_group_id)->get();

//        انواع المصروفات
        $system_code_types_2 = SystemCode::where('sys_category_id', 59)
            ->where('company_group_id', $company->company_group_id)->get();

        $banks = SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $company->company_group_id)->get();

        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();

        $bonds_cash = Bond::where('bond_ref_no', $card->mntns_cards_no)
            ->where('bond_type_id', 2)->latest()->get();

        $bonds_capture = Bond::where('bond_ref_no', $card->mntns_cards_no)
            ->where('bond_type_id', 1)->latest()->get();

        $purchases = Purchase::where('store_acc_tax_no', $card->mntns_cards_no)->where('company_group_id', $card->company_group_id)->latest()->get();
        return view('Maintenance.MaintenanceCard.edit', compact('card', 'cards_customer_type', 'mntns_cards_type', 'mntns_cards_category',
            'mntns_cards_item_disc_type', 'suppliers', 'customer_list', 'car_list', 'cards_status', 'payment_methods', 'accounts',
            'system_code_types', 'banks', 'payment_methods', 'system_code_types_2', 'company', 'bonds_cash', 'bonds_capture', 'purchases'));
    }


    public function closeCard(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $rules = [
            'card_uuid' => 'required|exists:maintenance_cards_hd,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        \DB::beginTransaction();
        $card = MaintenanceCard::where('uuid', '=', $request->card_uuid)->first();
        $company = $card->company;

        $qr = QRDataGenerator::fromArray([
            new SellerNameElement($company->companyGroup->company_group_ar),
            new TaxNoElement($company->company_tax_no),
            new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
            new TotalAmountElement($card->mntns_cards_total_amount),
            new TaxAmountElement($card->mntns_cards_vat_amount)
        ])->toBase64();
//return $card->branch_id ;
        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('branch_id', '=', $card->branch_id)->where('app_menu_id', 7171);
        if (!$current_serial->count()) {
            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم كرت الصيانة يرجي التواصل مع مدير النظام']);
        }
        $current_serial = $current_serial->first();
        $new_serial = 'INV-' . $card->branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);

        $card->mntns_cards_status = SystemCode::where('system_code', '=', 50003)->where('company_id',
            $company->company_id)->first()->system_code_id;

        $card->closed_date = Carbon::now();
        $card->closed_user = auth()->user()->user_id;
        $card->invoice_code = $new_serial;
        $card->qr_data = $qr;

        $card_save = $card->save();

        $current_serial->serial_last_no = $new_serial;
        $current_serial->save();

        $card_car = MaintenanceCar::where('company_group_id', $company->company_group_id)->where('mntns_cars_id', $card->mntns_cars_id)->first();
        $status_id = \App\Models\SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 30)
            ->where('system_code', 131)->first()->system_code_id;
        $status_close = \App\Models\SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 30)
            ->where('system_code', 80)->first()->system_code_id;
        $status_card = \App\Models\SystemCode::where('company_group_id', $company->company_group_id)->where('sys_category_id', 50)
            ->where('system_code_id', $request->mntns_cards_status)->first();

        //  return  $card_car->car_cost_center ;
        $truck = Trucks::where('truck_id', '=', $card_car->car_cost_center)->first();

        if (isset($truck->truck_id)) {
            $truck->update([
                'truck_status' => $status_close,
            ]);
        }
        if (!$card_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

//        قيد فاتوره مبيعات
        $invoice_journal = new JournalsController();
        $customer_notes = 'صرف  كارت الصيانه رقم' . $card->mntns_cards_no;
        $vat_notes = 'ضريبه قيمه مضافه  كارت الصيانه رقم' . $card->mntns_cards_no;
        $sales_notes = 'صرف  كارت الصيانه رقم' . $card->mntns_cards_no;
        $notes = 'صرف  كارت الصيانه رقم' . $card->mntns_cards_no;
        $items_id = [];
        $items_amount = [];
        $message = $invoice_journal->addInvoiceJournal($card->mntns_cards_total_amount, $card->customer_id, $card->mntns_cards_id,
            $customer_notes, 71, $vat_notes, $sales_notes, 43, $items_id, $items_amount,
            $notes);


        if (isset($message)) {
            return ['error' => true, 'msg' => $message];
        }

        //return ['success' => true, 'msg' => 'تم  الاغلاق بنجاح'];

        $vou_type = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', '62006')->first();

        /////////// اصدار فاتوره بيع للصنف من المستودع عند اغلاق الكارت

        $sales_invoice = MaintenanceCardController::storeIndividually($card, $vou_type);

        if (!$sales_invoice->getData()->success) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }


        //////////////////
        // return $sales_invoice = MaintenanceCardController::storeAll($card,$vou_type);

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح',]);
    }

    public function addTech(Request $request)
    {
        $rules = [
            'uuid' => 'required|exists:maintenance_cards_dt,uuid',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }

        $maintenance_card_details = MaintenanceCardDetails::where('uuid', '=', $request->uuid)->first();
        $tech_list = Employee::where('company_group_id', $maintenance_card_details->company_group_id)->where('emp_category', 486)->get();

        $view = view('Maintenance.MaintenanceCard.form.add_tech', compact('maintenance_card_details', 'tech_list'));
        return response()->json(['success' => true, 'view' => $view->render()]);
    }

    public function saveTech(Request $request)
    {
        $rules = [

            'mntns_cards_dt_id' => 'required|exists:maintenance_cards_dt,mntns_cards_dt_id',
            'mntns_tech_emp_id' => 'required|exists:employees,emp_id',
            'mntns_tech_hours' => 'required',
            'tech_table_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return \Response::json(['success' => false, 'msg' => implode(",", $validator->messages()->all())]);
        }


        $maintenance_card_details = MaintenanceCardDetails::where('mntns_cards_dt_id', '=', $request->mntns_cards_dt_id)->first();
        $tech_data = json_decode($request->tech_table_data, true);
        \DB::beginTransaction();
        $tech = new MaintenanceTechnicians();
        $tech->uuid = \DB::raw('NEWID()');
        $tech->mntns_cards_id = $maintenance_card_details->mntns_cards_id;
        $tech->mntns_cards_dt_id = $request->mntns_cards_dt_id;
        $tech->company_group_id = $maintenance_card_details->company_group_id;
        $tech->company_id = $maintenance_card_details->company_id;
        $tech->branch_id = $maintenance_card_details->branch_id;


        $tech->mntns_tech_hours = floatval($tech_data['mntns_tech_hours']);
        $tech->mntns_tech_emp_id = floatval($tech_data['mntns_tech_emp_id']);
        $tech->created_user = auth()->user()->user_id;
        $tech_save = $tech->save();

        if (!$tech_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => 'تمت العملية بنجاح', 'uuid' => $tech->uuid]);

    }

    public function storeAll($card, $type)
    {

        //$store_vou_ref_before = Purchase::where('uuid','=',$request->store_vou_ref_before)->first();
        $branch_id = $card->branch_id;
        $company = $card->company;
        //gnerate code
        switch ($type->system_code) {
            case '62006':

                $qty_field = 'store_vou_qnt_o';
                $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 65);
                if (!$current_serial->count()) {
                    return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم المبيعات يرجي التواصل مع مدير النظام']);
                }
                $current_serial = $current_serial->first();
                $new_serial = 'S-INV-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                break;

            default:
                abort(404);
        }


        \DB::beginTransaction();
        $purchase = new Purchase();

        $purchase->uuid = \DB::raw('NEWID()');

        $purchase->company_group_id = $company->company_group_id;
        $purchase->company_id = $company->company_id;
        $purchase->branch_id = $branch_id;

        $purchase->store_category_type = $card->mntns_cards_type;
        $purchase->store_vou_type = $type->system_code_id;

        $purchase->store_hd_code = $new_serial;
        $purchase->store_acc_no = $card->customer->customer_id;
        $purchase->store_acc_name = $card->customer->getCustomerName();
        $purchase->store_acc_tax_no = $card->customer->customer_vat_no;
        $purchase->store_vou_pay_type = SystemCode::where('system_code', '=', 57001)->first()->system_code_id;;
        $purchase->store_vou_notes = $card->mntns_cards_notes;
        $purchase->store_vou_date = Carbon::now();
        $purchase->created_user = auth()->user()->user_id;

        $purchase_save = $purchase->save();

        if (!$purchase_save) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        $current_serial->update(['serial_last_no' => $new_serial]);

        //store part item

        $item_list = $card->partDetails;
        $purcahse_details = new PurchaseDetails();
        if ($item_list->count() > 0) {

            $item_data_set = [];

            foreach ($item_list as $i => $d) {

                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $purchase->store_hd_id,
                    'company_group_id' => $purchase->company_group_id,
                    'company_id' => $purchase->company_id,
                    'branch_id' => $purchase->branch_id,

                    'store_category_type' => $purchase->store_category_type,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $purchase->store_acc_no,

                    'store_vou_item_id' => $d->partItem->item_id,
                    'store_vou_qnt_o' => $d->mntns_cards_item_qty,
                    'store_vou_loc' => $d->partItem->item_location,

                    'store_vou_item_price_cost' => floatval($d->partItem->item_price_cost),
                    'store_vou_item_price_unit' => floatval($d->mntns_cards_item_price),
                    'store_vou_item_total_price' => floatval($d->mntns_cards_item_amount),

                    'store_vou_disc_type' => $d->mntns_cards_disc_type,
                    'store_voue_disc_value' => floatval($d->mntns_cards_disc_value),
                    'store_vou_disc_amount' => floatval($d->mntns_cards_disc_amount),

                    'store_vou_vat_rate' => floatval($d->mntns_cards_vat_value),
                    'store_vou_vat_amount' => floatval($d->mntns_cards_vat_amount),
                    'store_vou_price_net' => floatval($d->mntns_cards_amount),

                ];


                //update item details when type equle enter Receipt
                if ($type->system_code == '62006') {
                    $store_item = StoreItem::where('item_id', $d->partItem->item_id)->first();
                    info($d->partItem->item_id);
                    if ($store_item->item_balance < $d->mntns_cards_item_qty) {
                        return \Response::json(['success' => false, 'msg' => 'الكمية الحالية غير كافية']);
                    }
                    $store_item->item_balance = $store_item->item_balance - $d[$qty_field];
                    $store_item->last_price_sales = $store_item->item_price_sales;
                    $store_item->item_price_sales = (floatval($d->mntns_cards_item_price)) / 2;
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                }

            }

            $purcahse_details_save = $purcahse_details->insert($item_data_set);

            if (!$purcahse_details_save) {
                return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
            }

        }

        $update_total = StoreSalesController::updateHeaderTotal($purchase);

        if (!$update_total['success']) {
            return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
        }

        \DB::commit();
        return \Response::json(['success' => true, 'msg' => '1تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);

    }


    public function storeItemIndividually($card, $type)
    {

        //$store_vou_ref_before = Purchase::where('uuid','=',$request->store_vou_ref_before)->first();
        $branch_id = $card->branch_id;
        $company = $card->company;
        $item_list = $card->partDetails;
        //gnerate code
        if ($item_list->count() >= 1) {

            $item_data_set = [];

            foreach ($item_list as $i => $d) {

                switch ($type->system_code) {
                    case '62006':

                        $qty_field = 'store_vou_qnt_o';
                        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 65);
                        if (!$current_serial->count()) {
                            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم المبيعات يرجي التواصل مع مدير النظام']);
                        }
                        $current_serial = $current_serial->first();
                        $new_serial = 'S-INV-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                        break;

                    default:
                        abort(404);
                }


                //\DB::beginTransaction();
                $purchase = new Purchase();

                $purchase->uuid = \DB::raw('NEWID()');

                $purchase->company_group_id = $company->company_group_id;
                $purchase->company_id = $company->company_id;
                $purchase->branch_id = $branch_id;

                $purchase->store_category_type = $d->item->item_category;
                $purchase->store_vou_type = $type->system_code_id;

                $purchase->store_hd_code = $new_serial;
                $purchase->store_acc_no = $card->customer->customer_id;
                $purchase->store_acc_name = $card->customer->getCustomerName();
                $purchase->store_acc_tax_no = $card->customer->customer_vat_no;
                $purchase->store_vou_pay_type = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', 57001)->first()->system_code_id;
                $purchase->store_vou_status = SystemCode::where('company_id', $company->company_id)->where('system_code', '=', 125001)->first()->system_code_id;
                $purchase->store_vou_notes = $card->mntns_cards_notes;
                $purchase->store_vou_date = Carbon::now();
                $purchase->created_user = auth()->user()->user_id;

                $purchase_save = $purchase->save();

                if (!$purchase_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                $current_serial->update(['serial_last_no' => $new_serial]);

                //store part item


                $purcahse_details = new PurchaseDetails();


                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $purchase->store_hd_id,
                    'company_group_id' => $purchase->company_group_id,
                    'company_id' => $purchase->company_id,
                    'branch_id' => $purchase->branch_id,

                    'store_category_type' => $purchase->store_category_type,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $purchase->store_acc_no,

                    'store_vou_item_id' => $d->partItem->item_id,
                    'store_vou_qnt_o' => $d->mntns_cards_item_qty,
                    'store_vou_loc' => $d->partItem->item_location,
                    'store_vou_item_price_cost' => floatval($d->partItem->item_price_cost),
                    'store_vou_item_price_unit' => floatval($d->mntns_cards_item_price),
                    'store_vou_item_total_price' => floatval($d->mntns_cards_item_amount),

                    'store_vou_disc_type' => $d->mntns_cards_disc_type,
                    'store_voue_disc_value' => floatval($d->mntns_cards_disc_value),
                    'store_vou_disc_amount' => floatval($d->mntns_cards_disc_amount),

                    'store_vou_vat_rate' => floatval($d->mntns_cards_vat_value),
                    'store_vou_vat_amount' => floatval($d->mntns_cards_vat_amount),
                    'store_vou_price_net' => floatval($d->mntns_cards_amount),

                ];


                //update item details when type equle enter Receipt
                if ($type->system_code == '62006') {
                    $store_item = StoreItem::where('item_id', $d->partItem->item_id)->first();
                    info($d->partItem->item_id);
                    if ($store_item->item_balance < $d->mntns_cards_item_qty) {
                        return \Response::json(['success' => false, 'msg' => 'الكمية الحالية غير كافية']);
                    }
                    $store_item->item_balance = $store_item->item_balance - $d->mntns_cards_item_qty;
                    $store_item->last_price_sales = $store_item->item_price_sales;
                    $store_item->item_price_sales = (floatval($d->mntns_cards_item_price)) / 2;
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                }

                $purcahse_details_save = $purcahse_details->insert($item_data_set);

                if (!$purcahse_details_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                $update_total = StoreSalesController::updateHeaderTotal($purchase);

                if (!$update_total['success']) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                //\DB::commit();

            }

            return \Response::json(['success' => true, 'msg' => '1تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);
        }

        return \Response::json(['success' => true, 'msg' => '1تمت العملية بنجاح']);

    }


    public function storeIndividually($card, $type)
    {

        //$store_vou_ref_before = Purchase::where('uuid','=',$request->store_vou_ref_before)->first();
        $branch_id = $card->branch_id;
        $company = $card->company;
        $item_list = $card->partDetails;
        //gnerate code
        if ($item_list->count() >= 1) {

            $item_data_set = [];

            foreach ($item_list as $i => $d) {

                switch ($type->system_code) {
                    case '62006':
                        $qty_field = 'store_vou_qnt_o';
                        $current_serial = CompanyMenuSerial::where('company_id', $company->company_id)->where('branch_id', '=', $branch_id)->where('app_menu_id', 65);
                        if (!$current_serial->count()) {
                            return \Response::json(['success' => false, 'msg' => 'لايمكن تحديد رقم المبيعات يرجي التواصل مع مدير النظام']);
                        }
                        $current_serial = $current_serial->first();
                        $new_serial = 'S-INV-' . $branch_id . '-' . (substr($current_serial->serial_last_no, strrpos($current_serial->serial_last_no, '-') + 1) + 1);
                        break;

                    default:
                        abort(404);
                }


                //\DB::beginTransaction();
                $purchase = new Purchase();

                $purchase->uuid = \DB::raw('NEWID()');

                $purchase->company_group_id = $company->company_group_id;
                $purchase->company_id = $company->company_id;
                $purchase->branch_id = $branch_id;

                $purchase->store_category_type = $d->item->item_category;
                $purchase->store_vou_type = $type->system_code_id;

                $purchase->store_hd_code = $new_serial;
                $purchase->store_acc_no = $card->customer->customer_id;
                $purchase->store_acc_name = $card->customer->getCustomerName();
                $purchase->store_acc_tax_no = $card->customer->customer_vat_no;
                $purchase->store_vou_pay_type = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', 57001)->first()->system_code_id;
                $purchase->store_vou_status = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', '=', 125001)->first()->system_code_id;
                $purchase->store_vou_notes = $card->mntns_cards_notes;
                $purchase->store_vou_date = Carbon::now();
                $purchase->created_user = auth()->user()->user_id;

                $purchase_save = $purchase->save();

                if (!$purchase_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                $current_serial->update(['serial_last_no' => $new_serial]);

                //store part item


                $purcahse_details = new PurchaseDetails();


                $item_data_set[] = [
                    'uuid' => \DB::raw('NEWID()'),
                    'store_hd_id' => $purchase->store_hd_id,
                    'company_group_id' => $purchase->company_group_id,
                    'company_id' => $purchase->company_id,
                    'branch_id' => $purchase->branch_id,

                    'store_category_type' => $purchase->store_category_type,
                    'store_vou_type' => $type->system_code_id,
                    'store_vou_date' => Carbon::now(),
                    'created_user' => auth()->user()->user_id,
                    'store_acc_no' => $purchase->store_acc_no,

                    'store_vou_item_id' => $d->partItem->item_id,
                    'store_vou_qnt_o' => $d->mntns_cards_item_qty,
                    'store_vou_loc' => $d->partItem->item_location,
                    'store_vou_item_price_cost' => floatval($d->partItem->item_price_cost),
                    'store_vou_item_price_unit' => floatval($d->mntns_cards_item_price),
                    'store_vou_item_total_price' => floatval($d->mntns_cards_item_amount),

                    'store_vou_disc_type' => $d->mntns_cards_disc_type,
                    'store_voue_disc_value' => floatval($d->mntns_cards_disc_value),
                    'store_vou_disc_amount' => floatval($d->mntns_cards_disc_amount),

                    'store_vou_vat_rate' => floatval($d->mntns_cards_vat_value),
                    'store_vou_vat_amount' => floatval($d->mntns_cards_vat_amount),
                    'store_vou_price_net' => floatval($d->mntns_cards_amount),

                ];


                //update item details when type equle enter Receipt
                if ($type->system_code == '62006') {
                    $store_item = StoreItem::where('item_id', $d->partItem->item_id)->first();
                    info($d->partItem->item_id);
                    if ($store_item->item_balance < $d->mntns_cards_item_qty) {
                        return \Response::json(['success' => false, 'msg' => 'الكمية الحالية غير كافية']);
                    }
                    $store_item->item_balance = $store_item->item_balance - $d->mntns_cards_item_qty;
                    $store_item->last_price_sales = $store_item->item_price_sales;
                    $store_item->item_price_sales = (floatval($d->mntns_cards_item_price)) / 2;
                    $store_item->updated_user = auth()->user()->user_id;
                    $store_item->updated_date = Carbon::now();

                    $store_item_save = $store_item->save();

                    if (!$store_item_save) {
                        return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                    }
                }

                $purcahse_details_save = $purcahse_details->insert($item_data_set);

                if (!$purcahse_details_save) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                $update_total = StoreSalesController::updateHeaderTotal($purchase);

                if (!$update_total['success']) {
                    return \Response::json(['success' => false, 'msg' => 'حدثت مشكلة الرجاء التواصل مع مدير النظام']);
                }

                //\DB::commit();

            }

            return \Response::json(['success' => true, 'msg' => '1تمت العملية بنجاح', 'uuid' => $purchase->refresh()->uuid]);
        }

        return \Response::json(['success' => true, 'msg' => '1تمت العملية بنجاح']);

    }

    ///سند قبض
    public function addBondWithJournal(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        //return $request->all();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('system_code', $request->bond_method_type)->where('company_group_id', $company->company_group_id)->first();
        $transaction_type = 71;
        $transaction_id = $request->transaction_id;
        $card = MaintenanceCard::find($request->transaction_id);
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

    //    سند صرف
    public function addBondWithJournal2(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        // return $request->all();
        \DB::beginTransaction();
        $bond_controller = new BondsController();
        $payment_method = SystemCode::where('system_code', $request->bond_method_type)->where('company_group_id', $company->company_group_id)->first();
        $transaction_type = 64;
        $transaction_id = $request->transaction_id;
        $card = MaintenanceCard::find($request->transaction_id);
        $customer_id = $card->customer_id;
        $customer_type = $request->customer_type;
        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';
        $total_amount = $request->bond_amount_credit + ($request->bond_vat_rate * $request->bond_amount_credit);
        $bond_doc_type = SystemCode::where('system_code_id', $request->bond_doc_type)->first();
        $bond_ref_no = $request->bond_ref_no;
        $bond_notes = $request->bond_notes ? $request->bond_notes : '';
        $bond_account_id = $request->bond_acc_id;
        $j_add_date = $request->bond_acc_id;
        // $bond_vat_amount = $request->bond_vat_amount ? $request->bond_vat_amount : 0;
        $bond_vat_amount = $request->bond_vat_rate * $request->bond_amount_credit;
        $bond_vat_rate = $request->bond_vat_rate ? $request->bond_vat_rate : 0;
        $bond = $bond_controller->addCashBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id,
            $bond_vat_amount, $bond_vat_rate, '', $j_add_date);


        $journal_controller = new JournalsController();
        $cost_center_id = 54;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
        $journal_category_id = 15;


        if ($request->bond_bank_id) {
            $bank_id = $request->bond_bank_id;
        } else {
            $bank_id = '';
        }

        $journal_notes = ' اضافه قيد سند صرف رقم' . $bond->bond_code;
        $customer_notes = ' اضافه قيد سند صرف  للعميل رقم' . $bond->bond_code;
        $cash_notes = ' اضافه قيد سند صرف  رقم' . $bond->bond_code;
        $message = $journal_controller->AddCashJournal(56002, $customer_id, $bond_doc_type,
            $total_amount, $bond_vat_amount, $cc_voucher_id, $payment_method, $bank_id,
            $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes);

        if (isset($message)) {
            return back()->with(['error' => $message]);
        }


        $card->update([
            'mntns_cards_payment_amount' => $card->mntns_cards_payment_amount - $total_amount,
            'mntns_cards_due_amount' => $card->mntns_cards_due_amount + $total_amount
        ]);


        \DB::commit();

        return back()->with(['success' => 'تم اضافه السند']);

    }


    public function storeBond(Request $request)
    {

        //  return $request->all();
        $bond = Bond::find($request->bond_id);
        $maintenance_card = MaintenanceCard::find($request->maintenance_card_id);
        $bond->bond_ref_no = $maintenance_card->mntns_cards_no;
        $bond->transaction_type = 71;
        $bond->transaction_id = $maintenance_card->mntns_cards_id;
        $bond->bond_acc_id = $maintenance_card->customer->customer_account_id;
        $bond->save();

        $maintenance_card->mntns_cards_payment_amount = $maintenance_card->mntns_cards_payment_amount + $bond->bond_amount_debit;
        $maintenance_card->save();
        return back()->with(['success' => 'تم ربط السند']);
    }


}
