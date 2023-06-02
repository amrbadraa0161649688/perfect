<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CarWayBillRequest;
use App\Http\Requests\Api\LocationsRequest;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\WayBillDtCarPricingRequest;
use App\Http\Requests\Api\WayBillDtCarRequest;
use App\Http\Resources\Api\CarWayBillResource;
use App\Http\Resources\Api\LiteListResource;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Api\WayBillResource;
use App\Models\Attachment;
use App\Models\Branch;
use App\Models\CarRentBrand;
use App\Models\CarRentBrandDt;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\Locations;
use App\Models\PriceListDt;
use App\Models\PriceListHd;
use App\Models\SystemCode;
use App\Models\WaybillDt;
use App\Models\waybillDtCar;
use App\Models\WaybillHd;
use App\Repositories\Eloquent\WayBillRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CarWayBillsController extends Controller
{
    protected $repo;

    /**
     * @param WayBillRepository $repo
     */
    public function __construct(WayBillRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param PaginateRequest $request
     * @return JsonResponse
     */
    public function all(Request $request)
    {
        $user = auth()->user();
        $status_pending = SystemCode::whereIn('system_code', [41001])
            ->where('company_group_id', $user->company_group_id)
            ->pluck('system_code_id')->toArray();
        $status_progress = SystemCode::whereIn('system_code', [41003, 41004, 41006])
            ->where('company_group_id', $user->company_group_id)
            ->pluck('system_code_id')->toArray();
        $status_done = SystemCode::whereIn('system_code', [41007, 41008])
            ->where('company_group_id', $user->company_group_id)
            ->pluck('system_code_id')->toArray();

        $conditions = [
            ['company_group_id', $user->company_group_id],
            ['waybill_create_user', $user->user_id],
            ['waybill_type_id', $request->type ?? 4],
        ];

        $done = $this->repo->findWhereInWithOrderBy('waybill_status', $status_done, 'desc', 'created_date', $conditions);
        $progress = $this->repo->findWhereInWithOrderBy('waybill_status', $status_progress, 'desc', 'created_date', $conditions);
        $pending = $this->repo->findWhereInWithOrderBy('waybill_status', $status_pending, 'desc', 'created_date', $conditions);
        return responseSuccess([
            'meta' => [
                'total_done' => count($done),
                'total_progress' => count($progress),
                'total_pending' => count($pending),
                'per_page' => 10,
            ],
            'done' => CarWayBillResource::collection($done->take(10)),
            'progress' => CarWayBillResource::collection($progress->take(10)),
            'pending' => CarWayBillResource::collection($pending->take(10)),
            'user' => UserResource::make(auth()->user()),
        ]);
    }

    /**
     * @param PaginateRequest $request
     * @return JsonResponse
     */
    public function index(PaginateRequest $request)
    {
        $user = auth()->user();
        $request->merge(['field' => 'waybill_id']);
//        $request->merge(['limit' => $request->page]);
        $request->merge(['offset' => $request->page]);
        $request->merge(['sort' => 'desc']);
        $columns = ['company_group_id', 'waybill_type_id', 'waybill_create_user'];
        $operand = ['=', '=', '='];
        $column_values = [$user->company_group_id, $request->type ?? 4, $user->user_id];

        if ($request->has('waybill_status')) {
            if ($request->waybill_status == 0) {
                // pending
                $statuses = SystemCode::whereIn('system_code', [41001])
                    ->where('company_group_id', $user->company_group_id)
                    ->pluck('system_code_id')->toArray();
            }
            if ($request->waybill_status == 1) {
                // in progress
                $statuses = SystemCode::where('system_code', [41003, 41004, 41006])
                    ->where('company_group_id', $user->company_group_id)
                    ->pluck('system_code_id')->toArray();
            }
            if ($request->waybill_status == 2) {
                // done
                $statuses = SystemCode::where('system_code', [41007, 41008])
                    ->where('company_group_id', $user->company_group_id)
                    ->pluck('system_code_id')->toArray();
            }

            $columns[] = ['waybill_status'];
            $operand[] = ['='];
            $column_values[] = $statuses;
        }

        $request->merge(['columns' => $columns]);
        $request->merge(['operand' => $operand]);
        $request->merge(['column_values' => $column_values]);

        $input = $this->repo->inputs($request->all());
        $model = new WaybillHd();
        $columns = Schema::getColumnListing($model->getTable());

        if (count($input["columns"]) < 1 || (count($input["columns"]) != count($input["column_values"])) || (count($input["columns"]) != count($input["operand"]))) {
            $wheres = [];
        } else {
            $wheres = $this->repo->whereOptions($input, $columns);
        }
        $data = $this->repo->Paginate($input, $wheres);

        return responseSuccess([
            'data' => CarWayBillResource::collection($data),
            'user' => UserResource::make(auth()->user()),
            'meta' => [
                'total' => $data->count(),
                'per_page' => 10,
                'currentPage' => $input["offset"],
                'lastPage' => $input["paginate"] != "false" ? $data->lastPage() : 1,
            ],
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $sys_codes_location = [];
        $sys_codes_methods = null;

        if ($request->type == 5) {
            $sys_codes_methods = SystemCode::where('company_group_id', $user->company_group_id)
                ->where('sys_category_id', 57)->where('system_code_filter', 'waybill')->get();
        }
        $sys_codes_location = SystemCode::where('sys_category_id', 34)->where('system_code_status', true)
            ->where('company_group_id', $user->company_group_id)->get();
        $cars = waybillDtCar::where('company_group_id', $user->company_group_id)
            ->where('created_user', $user->user_id)
            ->select('waybill_dt_id', 'company_group_id', 'waybill_car_plate', 'waybill_car_model', 'waybill_car_color',
                'waybill_car_owner', 'waybill_car_desc', 'created_user')->latest()->get();
        $customer = Customer::find($user->parent_id);
        $data = [
            'vat' => $customer ? $customer->customer_vat_rate : 0.15,
            'locations' => LiteListResource::collection($sys_codes_location),
            'payment_methods' => $sys_codes_methods ? LiteListResource::collection($sys_codes_methods) : [],
            'cars' => $cars,
        ];
        return responseSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WayBillResource $request
     * @return JsonResponse
     */
    public function store(CarWayBillRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $customer = Customer::where('customer_id', $user->parent_id)->first();
            $company = Company::find(48);
            $branch = Branch::find(1070);
            $waybill_status_code = SystemCode::where('system_code', 41001)
                ->where('company_group_id', $user->company_group_id)->first(); // بوليصه

            $car = waybillDtCar::find($request->car_id);
            $item_id = SystemCode::find($car->waybill_item_id);
            $active_price_list = PriceListHd::where('customer_id', $user->parent_id) // issue
            ->where('price_list_status', '=', 1)
                ->where('price_list_start_date', '<', Carbon::now())
                ->where('price_list_end_date', '>', Carbon::now())->latest()->first();
            if ($active_price_list && $item_id) {
                $item = PriceListDt::where('price_list_id', $active_price_list->price_list_id)
                    ->where('loc_from', $request->location_from)
                    ->where('loc_to', $request->location_to)
                    ->where('item_id', $item_id->system_code)
                    ->latest()->first();
            }

            $last_waypill_serial = CompanyMenuSerial::where('branch_id', $branch->branch_id)
                ->where('app_menu_id', 88)->latest()->first();
            if (isset($last_waypill_serial)) {
                $last_waypill_serial_no = $last_waypill_serial->serial_last_no;
                $array_number = explode('-', $last_waypill_serial_no);
                $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                $string_waybill_number = implode('-', $array_number);
                $last_waypill_serial->update(['serial_last_no' => $string_waybill_number]);
            } else {
                $string_waybill_number = 'CAR-' . $branch->branch_id . '-1';
                CompanyMenuSerial::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'app_menu_id' => 88,
                    'acc_period_year' => Carbon::now()->format('y'),
                    'branch_id' => $branch->branch_id,
                    'serial_last_no' => $string_waybill_number,
                    'created_user' => $user->parent_id
                ]);
            }

            $waybill_fees_vat_amount = $request->vat * ($request->waybill_fees_load);
            $payment_method = SystemCode::where('company_group_id', $user->company_group_id)
                ->where('system_code', 54003)->first();
            $waybill_payment_terms = SystemCode::where('company_group_id', $user->company_group_id)
                ->where('system_code', 54001)->first();
            $active_price_list = PriceListHd::where('customer_id', $user->parent_id) // issue
            ->where('price_list_status', '=', 1)
                ->where('price_list_start_date', '<', Carbon::now())
                ->where('price_list_end_date', '>', Carbon::now())->latest()->first();


            $waybill_hd = WaybillHd::create([
                'company_group_id' => $user->company_group_id,
                'company_id' => $company->company_id,
                'waybill_code' => $string_waybill_number,
                'waybill_type_id' => $request->type,
                'branch_id' => $branch->branch_id,

                'waybill_status' => $waybill_status_code->system_code_id,
                'waybill_payment_method' => 54001, // mada payment method
                'waybill_payment_terms' => 57003, // دفع عند الشحـن

                'customer_id' => $user->parent_id, // ask
                'customer_contract' => $active_price_list->price_list_code ?? null,
                'created_user' => $user->parent_id,
                'waybill_create_user' => $user->user_id,

                'waybill_loc_from' => $request->location_from,
                'waybill_loc_to' => $request->location_to,
                'waybill_transit_loc_1' => $request->location_to,
                'waybill_loc_paid' => $request->location_from,

                // for location map when door to door
                'waybill_loc_from_name' => json_encode($request->loc_from) ?? null,
                'waybill_loc_to_name' => json_encode($request->loc_to) ?? null,

                'waybill_sender_name' => !$request->same_owner ? $request->owner_name : $user->user_name_en,
                'waybill_sender_mobile' => $user->user_mobile,
                'waybill_sender_mobile_code' => !$request->same_owner ? $request->owner_national : $user->user_identity,

                'waybill_receiver_name' => !$request->same_recipient ? $request->recipient_name : $user->user_name_en,
                'waybill_receiver_mobile' => !$request->same_recipient ? $request->recipient_phone : $user->user_mobile,
                'waybill_receiver_mobile_code' => $user->user_identity,

                'waybill_load_date' => $request->date,
                'waybill_delivery_expected' => $item ? Carbon::parse($request->date)->addDays($item->distance_time)->format('d-m-Y') : null,
                'waybill_vat_rate' => $request->vat,
                'waybill_vat_amount' => $request->vat_amount,
                'waybill_total_amount' => $request->total,

                'waybill_add_amount' => 0,
                'waybill_discount_amount' => 0,
                'waybill_paid_amount' => 0,
                'waybill_ticket_no' => 000,
            ]);
//dd($request->all());
            $waybill_hd->statusM()->attach($waybill_status_code->system_code_id, ['status_date' => Carbon::now()]);


            $waybill_dt = WaybillDt::create([
                'waybill_hd_id' => $waybill_hd->waybill_id,
                'company_group_id' => $user->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,

                'waybill_item_id' => $car ? $car->waybill_item_id : null,
                'waybill_item_vat_rate' => $request->vat ? $request->vat : null,
                'waybill_item_vat_amount' => $request->vat_amount ? $request->vat_amount : null,
                'waybill_item_price' => $request->total - $request->vat_amount,
                'waybill_item_amount' => $request->total - $request->vat_amount,
                'waybill_total_amount' => $request->total,
                'waybill_add_amount' => 0,
                'waybill_discount_total' => 0,
                'waybill_qut_received_customer' => 1,
                'waybill_item_quantity' => 1,

                'waybill_car_chase' => $car->waybill_car_chase,
                'waybill_car_plate' => $car->waybill_car_plate,
                'waybill_car_desc' => $car->waybill_car_desc,
                'waybill_car_owner' => $car->waybill_car_owner,
                'waybill_car_color' => $car->waybill_car_color,
                'waybill_car_model' => $car->waybill_car_model,
//            customer
                'waybill_item_unit' => $car->waybill_item_unit,

//           supplier
                'created_user' => $user->user_id,
            ]);
            $car->update([
                'waybill_hd_id' => $waybill_hd->waybill_id,
            ]);

//        اضافه فاتوره وسند في حاله الدفع علي الحساب
//            $waybill_payment_method = session('waybill_hd') ? session('waybill_hd')['waybill_payment_method'] : $payment_method->system_code;
//
//            if ($waybill_payment_method == 54001 || $waybill_payment_method == 54002) {
//                $last_invoice_reference = CompanyMenuSerial::where('branch_id', $branch->branch_id)
//                    ->where('app_menu_id', 119)->latest()->first();
//
//                if (isset($last_invoice_reference)) {
//                    $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
//                    $array_number = explode('-', $last_invoice_reference_number);
//                    $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
//                    $string_number = implode('-', $array_number);
//                    $last_invoice_reference->update(['serial_last_no' => $string_number]);
//                } else {
//                    $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
//                    CompanyMenuSerial::create([
//                        'company_group_id' => $company->company_group_id,
//                        'company_id' => $company->company_id,
//                        'app_menu_id' => 119,
//                        'acc_period_year' => Carbon::now()->format('y'),
//                        'branch_id' => session('branch')['branch_id'],
//                        'serial_last_no' => $string_number,
//                        'created_user' => auth()->user()->user_id
//                    ]);
//
//                }
//                $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
//                    ->where('acc_period_month', Carbon::now()->format('m'))
//                    ->where('acc_period_is_active', 1)->first();
//
//                $invoice_hd = InvoiceHd::create([
//                    'company_group_id' => $company->company_group_id,
//                    'company_id' => $company->company_id,
//                    'acc_period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
//                    'invoice_date' => Carbon::now(),
//                    'invoice_due_date' => Carbon::now(),
//                    'invoice_amount' => $request->waybill_total_amount,
//                    'invoice_vat_rate' => $waybill_hd->waybill_vat_rate,
//                    // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
//                    'invoice_vat_amount' => $waybill_hd->waybill_vat_amount,
//                    'invoice_discount_total' => 0,
//                    'invoice_down_payment' => 0,
//                    'invoice_total_payment' => 0,
//                    'invoice_notes' => '  فاتوره  بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
//                    'invoice_no' => $string_number,
//                    'created_user' => auth()->user()->user_id,
//                    'branch_id' => session('branch')['branch_id'],
//                    'customer_id' => session('waybill_hd') ? session('waybill_hd')['customer_id'] : $request->customer_id,
//                    'invoice_is_payment' => 1,
//                    'invoice_type' => 9, ///فاتوره السياره
//                    'invoice_status' => 121003,
//                    'customer_address' => 'الممكله العربيه السعوديه',
//                    'customer_name' => $request->waybill_sender_name,
//                    'customer_phone' => $request->waybill_sender_mobile,
//                ]);
//
//                $qr = QRDataGenerator::fromArray([
//                    new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
//                    new TaxNoElement($company->company_tax_no),
//                    new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
//                    new TotalAmountElement($invoice_hd->invoice_amount),
//                    new TaxAmountElement($invoice_hd->invoice_vat_amount)
//                ])->toBase64();
//
//                $invoice_hd->update(['qr_data' => $qr]);
//
//                $invoice_dt = InvoiceDt::create([
//                    'company_group_id' => $company->company_group_id,
//                    'company_id' => $company->company_id,
//                    'branch_id' => session('branch')['branch_id'],
//                    'invoice_id' => $invoice_hd->invoice_id,
//                    'invoice_item_id' => $item->system_code_id,
//                    'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
//                    'invoice_item_quantity' => $request->waybill_qut_received_customer,
//                    'invoice_item_price' => $request->waybill_item_price,
//                    'invoice_item_amount' => $request->waybill_sub_total_amount,
//                    'invoice_item_vat_rate' => $request->waybill_vat_rate,
//                    'invoice_item_vat_amount' => $request->waybill_vat_amount,
//                    'invoice_discount_type' => 1,
//                    'invoice_discount_amount' => 0,
//                    'invoice_discount_total' => 0,
//                    'invoice_total_amount' => $request->waybill_total_amount,
//                    'created_user' => auth()->user()->user_id,
//                    'invoice_item_notes' => '  فاتوره  بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
//                    'invoice_from_date' => Carbon::now(),
//                    'invoice_to_date' => Carbon::now()
//                ]);
//
//                $waybill_hd->waybill_invoice_id = $invoice_hd->invoice_id;
//                $waybill_hd->save();
//
//                $invoice_dt->invoice_reference_no = $waybill_hd->waybill_id;
//                $invoice_dt->save();
//
//                $invoice_journal = new JournalsController();
//                $total_amount = $invoice_hd->invoice_amount;
//                $cc_voucher_id = $invoice_hd->invoice_id;
//                $items_id[] = $waybill_hd->waybill_id;
//                $customer_notes = 'قيد فاتورة شحن سياره رقم' . ' ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
//                $vat_notes = '  قيد ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
//                $sales_notes = '  قيد ايراد للفاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
//                $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
//                $message = $invoice_journal->addWaybillInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
//                    $customer_notes, 119, $vat_notes, $sales_notes, 40, $items_id,
//                    $items_amount = [], $notes);
//                if ($message) {
//                    return back()->with(['error' => $message]);
//                }
//                if ($request->waybill_paid_amount > 0) {
//
//                    ////////////////////////////
//                    /// اضافه سند قبض وقيد علي سند القبض
//                    $bond_controller = new BondsController();
//                    $transaction_type = 88; ///بوليصه السايرات
//                    $transaction_id = $waybill_hd->waybill_id;
//                    $customer_id = $waybill_hd->customer_id;
//                    $customer_type = 'customer';
//                    $total_amount = $request->waybill_paid_amount;
//                    $bond_doc_type = SystemCode::where('system_code', 58002)
//                        ->where('company_group_id', $company->company_group_id)->first(); ////ايرادات مبيعات
//                    // return $bond_doc_type;
//                    $bond_ref_no = $waybill_hd->waybill_code;
//                    $bond_notes = '  سداد بوليصه رقم ' . ' ' . $waybill_hd->waybill_code . ' ' . 'بواسطه' . ' ' . $waybill_hd->waybill_sender_name;
//
//                    $payment_method = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $waybill_payment_terms->system_code)->first();
//
//                    $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id,
//                        $customer_id, $customer_type, '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);
//
//                    $invoice_hd->bond_code = $bond->bond_id;
//                    $invoice_hd->bond_date = Carbon::now();
//                    $invoice_hd->invoice_total_payment = $request->waybill_paid_amount;
//                    $invoice_hd->save();
//
//                    $waybill_hd->bond_code = $bond->bond_code;
//                    $waybill_hd->bond_id = $bond->bond_id;
//                    $waybill_hd->bond_date = $bond->bond_date;
//                    $waybill_hd->save();
//
//                    $bond_journal = new JournalsController();
//                    $cc_voucher_id = $bond->bond_id;
//                    $journal_category_id = 4; ////سند قبض بوليصه سياره
//                    $cost_center_id = 53;
//                    $account_type = 56002;
//                    $bank_id = $request->bank_id ? $request->bank_id : '';
//                    $journal_notes = '  قيد  سند القبض رقم ' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
//                    $payment_method_terms = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $bond->bond_method_type)->first();
//                    //return $request->waybill_payment_terms;
//                    $customer_notes = '  قيض عميل  سند رقم' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
//                    $sales_notes = '  قيض ايرادات  سند رقم' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
//                    //return $payment_method_terms;
//                    $message1 = $bond_journal->AddCaptureJournal($account_type, $customer_id, $bond_doc_type->system_code, $total_amount,
//                        $cc_voucher_id, $payment_method_terms, $bank_id, $journal_category_id,
//                        $cost_center_id, $journal_notes, $customer_notes, $sales_notes);
//
//                    if (isset($message1)) {
//                        return back()->with(['error' => $message1]);
//                    }
//
//                }
//            }
//            ////////////////////اضافه سند صرف لمصروف الطريق
//            if ($request->waybill_fees_load > 0) {
//                $payment_terms = SystemCode::where('system_code', 57001)
//                    ->where('company_group_id', $company->company_group_id)->first(); ///الدفع نقدي
//                $trip = TripHd::where('trip_hd_id', $waybill_hd->waybill_trip_id)->first();
//
//                $bond_controller = new BondsController();
//                $transaction_type = 88;
//                $transaction_id = $waybill_hd->waybill_id;
//                $bond_car_id = $waybill_hd->waybill_truck_id;
//                $j_add_date = Carbon::parse($request->waybill_load_date)->toDateString();
//
//                $customer_type = 'car';
//                $bond_bank_id = $request->bank_id ? $request->bank_id : '';
//                $total_amount = $request->waybill_fees_load;
//                ///مصاريف للسائق
//                $bond_ref_no = $waybill_hd->waybill_code;
//                $bond_notes = '  سند صرف مصروف الطريق بوليصه  رقم' . $waybill_hd->waybill_code;
//
//                $journal_category_id = 12;
//
//                $journal_type = JournalType::where('journal_types_code', $journal_category_id)
//                    ->where('company_group_id', $company->company_group_id)->first();
//
//                $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)->where('company_group_id', $company->company_group_id)
//                    ->first();
//
//                if ($journal_type->bond_type_id) {
//                    $bond_account_id = $journal_type->account_id_debit;
//
//                } else {
//                    return back()->with(['error' => 'لا يوجد نشاط مضاف لهذا النوع من القيود']);
//                }
//
//
//                $bond = $bond_controller->addCashBond($payment_terms, $transaction_type, $transaction_id,
//                    '', $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no,
//                    $bond_notes, $bond_account_id, 0, 0, $bond_car_id, $j_add_date);
//
//
//                $journal_controller = new JournalsController();
//                $cost_center_id = 54;
//                $cc_voucher_id = $bond->bond_id;
//                // $bank_id = $request->bank_id ? $request->bank_id : '';
//
//                if ($request->bank_id) {
//                    $bank_id = $request->bank_id;
//                } else {
//                    // return back()->with(['error' => 'لا يوجد بنك لاضافه قيد سند الصرف']);
//                    $bank_id = '';
//                }
//
//                $customer_id = $waybill_hd->waybill_truck_id;
//                $journal_notes = ' اضافه قيد سند صرف البوليصه رقم' . $waybill_hd->waybill_code . 'سند الصرف رقم' . $bond->bond_code;
//                $customer_notes = ' اضافه قيد سند صرف  للعميل البوليصه رقم' . $waybill_hd->waybill_code;
//                $cash_notes = ' اضافه قيد سند صرف  لبوليصه رقم' . $waybill_hd->waybill_code;
//                $message = $journal_controller->AddCashJournal(56002, $customer_id, $bond_doc_type->system_code,
//                    $total_amount, 0, $cc_voucher_id, $payment_terms, $bank_id,
//                    $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);
//            }

            DB::commit();
//            $this->repo->create($data);
            return responseSuccess([], __('messages.added_successfully'));
        } catch (\Exception $e) {
            Log::error('waybill store', [$e]);
            return responseFail(__('messages.wrong_data'));
        }
    }

    /**
     * @param LocationsRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function createCar()
    {
        try {
            $user = auth()->user();
            $brands = CarRentBrand::with('branddt')->get();
            $plate_type = SystemCode::where('sys_category_id', 147)
                ->where('company_group_id', $user->company_group_id)->get();
            $data = [
                'brands' => LiteListResource::collection($brands),
                'plate_type' => LiteListResource::collection($plate_type),
            ];
            return responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('waybill car create car', [$e->getMessage()]);
            return responseFail(__('messages.wrong_data'));
        }
    }

    /**
     * @param LocationsRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function storeCar(WayBillDtCarRequest $request)
    {
        try {
            $brand = CarRentBrand::find($request->waybill_car_model);
            $brand_dt = CarRentBrandDt::find($request->waybill_car_type);
            $item = SystemCode::where('system_code', $brand_dt->brand_dt_size)->first();
            $user = auth()->user();
            $record = waybillDtCar::create([
                'waybill_hd_id' => 1,
                'company_group_id' => 28,
                'company_id' => 48,
                'branch_id' => 1070,
                'waybill_car_desc' => $brand->name . '/' . $brand_dt->name,
                'waybill_item_id' => $item->system_code_id,
                'waybill_item_unit' => $request->plate_car_type,
                'waybill_car_chase' => $request->waybill_car_chase,
                'waybill_car_plate' => $request->plate_name,
                'waybill_car_model' => $request->model_type,
                'waybill_car_color' => $request->waybill_car_color,
                'waybill_car_owner' => $request->same_owner ? $user->user_name : $request->owner_name,
                'created_user' => auth()->user()->user_id,
            ]);
            return responseSuccess($record, __('messages.added_successfully'));
        } catch (\Exception $e) {
            Log::error('waybill car store car', [$e->getMessage()]);
            return responseFail(__('messages.wrong_data'));
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $record = $this->repo->findOrFail($id);
        return responseSuccess(CarWayBillResource::make($record));
    }


    /**
     * @return JsonResponse
     */
    public function pricing(WayBillDtCarPricingRequest $request)
    {
        try {
            $price = 0;
            $expected_date = null;
            $user = auth()->user();
            $active_price_list = PriceListHd::where('customer_id', $user->parent_id) // issue
            ->where('price_list_status', '=', 1)
                ->where('price_list_start_date', '<', Carbon::now())
                ->where('price_list_end_date', '>', Carbon::now())->latest()->first();

            if ($request->type == 4) {
                $car = waybillDtCar::find($request->car_id);
                $item_id = SystemCode::find($car->waybill_item_id);
                if ($active_price_list && $item_id) {
                    $item = PriceListDt::where('price_list_id', $active_price_list->price_list_id)
                        ->where('loc_from', $request->location_from)
                        ->where('loc_to', $request->location_to)
                        ->where('item_id', $item_id->system_code)
                        ->latest()->first();
                    $price = $item ? (int)$item->max_fees : 0;
                    $expected_date = $item ? Carbon::parse($request->date)->addDays($item->distance_time)->format('d-m-Y') : null;
                }
            } elseif ($active_price_list && $request->type == 5) {
                $item = PriceListDt::where('price_list_id', $active_price_list->price_list_id)
                    ->where('item_id', 64005)
                    ->latest()->first();

                $distance = \KMLaravel\GeographicalCalculator\Facade\GeoFacade::setOptions(['units' => ['km']])
                    ->setPoint($request->location_from)//[30.051221639862604, 31.349604173090388]
                    ->setPoint($request->location_to)//[30.139878603753758, 31.718312745811566]
                    ->getDistance();
                $price = $distance['1-2']['km'] * ($item ? (int)$item->max_fees : 0);
                $expected_date = $item ? Carbon::parse($request->date)->addMinutes($distance['1-2']['km'] * $item->distance_time)->format('d-m-Y') : null;
            }
            $data = [
                'price' => floor($price),
                'expected_date' => $expected_date
            ];
            return responseSuccess($data);
        } catch (\Exception $e) {
            Log::error('waybill car pricing car', [$e->getMessage()]);
            return responseFail(__('messages.wrong_data'));
        }

    }


    public function cancelWaybill($id)
    {
        $user = auth()->user();
        $record = WaybillHd::findOrFail($id);
        $waybill_status_code = SystemCode::where('system_code', 41001)
            ->where('company_group_id', $user->company_group_id)->first(); // بوليصه
        if ($waybill_status_code->system_code_id != $record->waybill_status) {
            return responseFail(__('messages.wrong_data'));
        }
        $record->cancel_status = 200;
        $record->waybill_status = 41005;
        $record->save();
        return responseSuccess($record, __('messages.added_successfully'));
    }


    public function storePhoto(Request $request)
    {
        $img = $request->image;
        $file = $this->getPhoto($img);

        Attachment::create([
            'attachment_name' => 'waybill-car',
            'attachment_type' => 88,
            'issue_date' => Carbon::now(),
            'attachment_file_url' => $file,
            'attachment_data' => Carbon::now(),
            'transaction_id' => $request->order_id,
            'app_menu_id' => 44,
            'created_user' => auth()->user()->user_id,
        ]);

        return back()->with(['success' => 'تم اضافه الصوره']);
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("carWaybill"), $name);
        return $name;
    }
}
