<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LocationsRequest;
use App\Http\Requests\Api\PaginateRequest;
use App\Http\Requests\Api\RateRequest;
use App\Http\Requests\Api\WayBillRequest;
use App\Http\Resources\Api\LiteListResource;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Api\WayBillResource;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Locations;
use App\Models\PriceListDt;
use App\Models\PriceListHd;
use App\Models\SystemCode;
use App\Models\WaybillHd;
use App\Repositories\Eloquent\WayBillRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class WayBillsController extends Controller
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
    public function all(PaginateRequest $request)
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
            ['created_user', $user->user_id],
            ['waybill_type_id', 1],
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
            'done' => WayBillResource::collection($done->take(10)),
            'progress' => WayBillResource::collection($progress->take(10)),
            'pending' => WayBillResource::collection($pending->take(10)),
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
        $columns = ['company_group_id', 'waybill_type_id', 'created_user'];
        $operand = ['=', '=', '='];
        $column_values = [$user->company_group_id, '1', auth()->user()->user_id];

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
            'data' => WayBillResource::collection($data),
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
    public function create()
    {
        $user = auth()->user();
        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $user->company_group_id)
            ->where('system_code_filter', 'waybill')->get();
        $product_types = SystemCode::where('sys_category_id', 28)
            ->where('system_code_filter', 'waybill')
            ->where('company_group_id', $user->company_group_id)->get();
        $locations = Locations::where('company_group_id', $user->company_group_id)
            ->where('user_id', auth()->user()->id)->get();

        foreach ($product_types as $product_type) {
            // get item price for suppliers
            $price_list_hd_ids = PriceListHd::where('customer_id', 176) // issue
            ->where('price_list_status', 1)
                ->pluck('price_list_id')->toArray();

            $item = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                ->where('item_id', $product_type->system_code_id)->first();
            $product_type->unit_price = $item ? $item->max_fees : 0;
        }
        $customer = Customer::find($user->parent_id);
        $data = [
            'vat' => $customer ? $customer->customer_vat_rate : 0.15,
            'locations' => LiteListResource::collection($locations),
            'payment_methods' => LiteListResource::collection($payment_methods),
            'product_types' => LiteListResource::collection($product_types),
        ];
        return responseSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WayBillResource $request
     * @return JsonResponse
     */
    public function store(WayBillRequest $request)
    {
        try {
            $user = auth()->user();
            $company = Company::findOrFail(29);
            $waybill_status_code = SystemCode::where('company_group_id', auth()->user()->company_group_id)
                ->where('system_code', 41001)->firstOrFail(); //حاله الطلب امر تحميل
            $waybill_unit_code = SystemCode::where('company_group_id', auth()->user()->company_group_id)
                ->where('system_code', 95)->firstOrFail(); // الوحده باللتر
            $waybill_station_start_code = SystemCode::where('company_group_id', auth()->user()->company_group_id)
                ->where('system_code', 95002)->firstOrFail(); // محطه التحميل الرياض

            $location = Locations::findOrFail($request->user_location_id);

            $price = 0;
            $supplier = Customer::where('customer_id', 176)->firstOrFail(); // get aramco supplier

            // get item price for suppliers
            $price_list_hd_ids = PriceListHd::where('customer_id', 176)
                ->where('price_list_status', 1)
                ->pluck('price_list_id')->toArray();
            $supplier_item = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                ->where('item_id', $request->product_type_id)->first();
            $items_supplier_max_fees = $supplier_item ? $supplier_item->max_fees : 0;
            $items_supplier_coast_fees = $supplier_item ? $supplier_item->cost_fees : 0;

            $waybill_fees_load = 0;
            if (auth()->user()->parent_id) {
                $customer = Customer::where('customer_id', auth()->user()->parent_id)->firstOrFail();
                // get item price for suppliers
                $price_list_hd_ids = PriceListHd::where('customer_id', $customer->customer_id) // issue
                ->where('price_list_status', 1)
                    ->pluck('price_list_id')->toArray();

                $item = PriceListDt::whereIn('price_list_id', $price_list_hd_ids)
                    ->where('item_id', $request->product_type_id)->first(); // issue
                $waybill_fees_load = $item ? $item->max_fees : 0;
            }

            $item_amount = $request->quantity * $items_supplier_max_fees;
            $item_vat_amount = ($customer->customer_vat_rate) * $item_amount;

            $item_supplier_amount = $request->quantity * $items_supplier_coast_fees;

            $details_data = [
//                'waybill_hd_id' => $waybill_hd->waybill_id,
                'company_group_id' => $user->company_group_id,
                'company_id' => 29,
                'branch_id' => $waybill_station_start_code->branch_id,
                'created_user' => auth()->user()->user_id,

                'waybill_item_id' => $request->product_type_id ? $request->product_type_id : null,
                'waybill_item_vat_rate' => $customer->customer_vat_rate,
                'waybill_item_vat_amount' => $item_vat_amount,

//            issue
                'waybill_item_unit' => $waybill_unit_code->system_code_id,
                'waybill_item_price' => $items_supplier_max_fees,
                'waybill_item_amount' => $item_amount,
                'waybill_total_amount' => $item_amount + $item_vat_amount,

                'waybill_item_quantity' => $request->quantity ? $request->quantity : null,
                'waybill_qut_requried_customer' => $request->quantity, // same quantity
                'waybill_qut_received_customer' => $request->quantity,

//           supplier issue
                'waybill_price_supplier' => $items_supplier_coast_fees,
                'waybill_vat_amount_supplier' => $supplier->customer_vat_rate * $item_supplier_amount,
                'waybill_amount_supplier' => $item_supplier_amount,

                'waybill_qut_requried_supplier' => $request->quantity, // same quantity
                'waybill_qut_received_supplier' => $request->quantity,
            ]; // waybill details
            $details_fees_data = null;
            $waybill_fees_vat_amount = 0;
            if ($waybill_fees_load != 0) {
                $waybill_fees_vat_amount = ($customer->customer_vat_rate) * $waybill_fees_load;  // calculate

                $item_unit = SystemCode::where('system_code', 93)->where('company_group_id', $company->company_group_id)
                    ->first();
                $system_code_service = SystemCode::where('system_code', 541)->where('company_group_id', $company->company_group_id)
                    ->first();

                $details_fees_data = [
//                'waybill_hd_id' => $waybill_hd->waybill_id,
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => $waybill_station_start_code->branch_id,
                    'created_user' => auth()->user()->user_id,
                    'waybill_item_quantity' => 1,

                    'waybill_item_id' => $system_code_service->system_code_id, // خدمات شحن,
                    'waybill_item_vat_rate' => $customer->customer_vat_rate,
                    'waybill_item_vat_amount' => $waybill_fees_vat_amount,

//            issue
                    'waybill_item_unit' => $item_unit->system_code_id,
                    'waybill_item_price' => $waybill_fees_load,
                    'waybill_fees_load' => $waybill_fees_load,
                    'waybill_total_amount' => $waybill_fees_load + $waybill_fees_vat_amount,

                ]; // waybill details
            }


            $data = [
                'company_group_id' => $user->company_group_id,
                'company_id' => 29,
                'branch_id' => $waybill_station_start_code->branch_id,
                'created_user' => auth()->user()->user_id,
                'waybill_create_user' => auth()->user()->user_id,

//                'waybill_code' => $string_number,
                'waybill_type_id' => 1,

                // supplier info
                'supplier_id' => $supplier->customer_id,  // id = 176 aramco
                'waybill_sender_name' => $supplier->customer_name_full_ar,
                'waybill_sender_company' => $supplier->customer_company,
                'waybill_sender_address' => $supplier->customer_address_1,
                'waybill_sender_phone' => $supplier->customer_phone,
                'waybill_sender_mobile' => $supplier->customer_mobile,
                'waybill_sender_mobile_code' => $supplier->customer_mobile_code,

                // customer info
                'customer_id' => isset($customer) ? $customer->customer_id : null, // from parent login user
                'waybill_receiver_company' => isset($customer) ? $customer->customer_company : null,
                'waybill_receiver_address' => isset($customer) ? $customer->customer_address_1 : null,
                'waybill_receiver_phone' => isset($customer) ? $customer->customer_phone : null,
                'waybill_receiver_mobile_code' => isset($customer) ? $customer->customer_mobile_code : null,

                'waybill_status' => $waybill_status_code->system_code_id, //حاله الطلب امر تحميل

                'waybill_loc_from' => $waybill_station_start_code->system_code_id,  // default ryadi
                'waybill_load_date' => $request->date,
                'waybill_unload_date' => $request->date ? $request->date : null,
                'waybill_loc_to' => $location->city_id,  // get from locations
                'waybill_delivery_expected' => $request->date ? $request->date : null,

                'waybill_vat_rate' => $customer->customer_vat_rate,
                'waybill_vat_amount' => $item_vat_amount + $waybill_fees_vat_amount, ///customer
                'waybill_total_amount' => $item_amount + $item_vat_amount + $waybill_fees_load + $waybill_fees_vat_amount, ///customer

                'waybill_fees_total' => $waybill_fees_load + $waybill_fees_vat_amount,

                'user_location_id' => $request->user_location_id,

                'details_data' => $details_data,  // this is waybilldt data
                'details_fees_data' => $details_fees_data  // this is waybilldt fees data
            ]; // waybill header & details
//            dd($data);
            $this->repo->create($data);
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
    public function cancel($id)
    {
        try {
            $user = auth()->user();
            $record = $this->repo->findOrFail($id);
            $waybill_status_code = SystemCode::where('company_id', 29)
                ->where('company_group_id', $user->company_group_id)
                ->where('system_code', 41001)->first(); //حاله الطلب امر تحميل
            if (!$waybill_status_code) {  // if status code is not carriage request
                return responseFail(__('messages.not_authorize_to_make_it'));
            }

            $cancel_code = SystemCode::where('company_id', 29)
                ->where('company_group_id', $user->company_group_id)
                ->where('system_code', 41005)->first();

            $this->repo->update([
                'waybill_status' => $cancel_code->system_code_id
            ], $record);
            return responseSuccess([], __('messages.canceled_successfully'));
        } catch (\Exception $e) {
            Log::error('waybill cancel', [$e->getMessage()]);
            return responseFail(__('messages.wrong_data'));
        }
    }

    /**
     * @param LocationsRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function setRate(RateRequest $request, $id): JsonResponse
    {
        try {
            $record = $this->repo->findOrFail($id);
            $this->repo->update($request->validated(), $record);
            return responseSuccess([], __('messages.added_successfully'));
        } catch (\Exception $e) {
            Log::error('waybill cancel', [$e->getMessage()]);
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
        return responseSuccess(WayBillResource::make($record));
    }

}
