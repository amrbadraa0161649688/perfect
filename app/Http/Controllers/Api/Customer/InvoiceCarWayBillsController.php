<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WayBillResource;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\PriceListDt;
use App\Models\PriceListHd;
use App\Models\SystemCode;
use App\Models\WaybillDt;
use App\Models\waybillDtCar;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceCarWayBillsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param $data
     * @return true
     */
    public function __invoke($data): bool
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $waybill_hd = WaybillHd::find($data['waybill_id']);
            $payment_method = SystemCode::where('company_group_id', $user->company_group_id)
                ->where('system_code', 54003)->first();
//        اضافه فاتوره وسند في حاله الدفع علي الحساب
            $waybill_payment_method = session('waybill_hd') ? session('waybill_hd')['waybill_payment_method'] : $payment_method->system_code;

            if ($waybill_payment_method == 54001 || $waybill_payment_method == 54002) {
                $last_invoice_reference = CompanyMenuSerial::where('branch_id', $branch->branch_id)
                    ->where('app_menu_id', 119)->latest()->first();

                if (isset($last_invoice_reference)) {
                    $last_invoice_reference_number = $last_invoice_reference->serial_last_no;
                    $array_number = explode('-', $last_invoice_reference_number);
                    $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
                    $string_number = implode('-', $array_number);
                    $last_invoice_reference->update(['serial_last_no' => $string_number]);
                } else {
                    $string_number = 'INV-' . session('branch')['branch_id'] . '-1';
                    CompanyMenuSerial::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'app_menu_id' => 119,
                        'acc_period_year' => Carbon::now()->format('y'),
                        'branch_id' => session('branch')['branch_id'],
                        'serial_last_no' => $string_number,
                        'created_user' => auth()->user()->user_id
                    ]);

                }
                $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
                    ->where('acc_period_month', Carbon::now()->format('m'))
                    ->where('acc_period_is_active', 1)->first();

                $invoice_hd = InvoiceHd::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'acc_period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'invoice_date' => Carbon::now(),
                    'invoice_due_date' => Carbon::now(),
                    'invoice_amount' => $request->waybill_total_amount,
                    'invoice_vat_rate' => $waybill_hd->waybill_vat_rate,
                    // $request->invoice_vat_amount / ($request->invoice_amount - $request->invoice_vat_amount),
                    'invoice_vat_amount' => $waybill_hd->waybill_vat_amount,
                    'invoice_discount_total' => 0,
                    'invoice_down_payment' => 0,
                    'invoice_total_payment' => 0,
                    'invoice_notes' => '  فاتوره  بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
                    'invoice_no' => $string_number,
                    'created_user' => auth()->user()->user_id,
                    'branch_id' => session('branch')['branch_id'],
                    'customer_id' => session('waybill_hd') ? session('waybill_hd')['customer_id'] : $request->customer_id,
                    'invoice_is_payment' => 1,
                    'invoice_type' => 9, ///فاتوره السياره
                    'invoice_status' => 121003,
                    'customer_address' => 'الممكله العربيه السعوديه',
                    'customer_name' => $request->waybill_sender_name,
                    'customer_phone' => $request->waybill_sender_mobile,
                ]);

                $qr = QRDataGenerator::fromArray([
                    new SellerNameElement($invoice_hd->companyGroup->company_group_ar),
                    new TaxNoElement($company->company_tax_no),
                    new InvoiceDateElement(Carbon::now()->toIso8601ZuluString()),
                    new TotalAmountElement($invoice_hd->invoice_amount),
                    new TaxAmountElement($invoice_hd->invoice_vat_amount)
                ])->toBase64();

                $invoice_hd->update(['qr_data' => $qr]);

                $invoice_dt = InvoiceDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'invoice_id' => $invoice_hd->invoice_id,
                    'invoice_item_id' => $item->system_code_id,
                    'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                    'invoice_item_quantity' => $request->waybill_qut_received_customer,
                    'invoice_item_price' => $request->waybill_item_price,
                    'invoice_item_amount' => $request->waybill_sub_total_amount,
                    'invoice_item_vat_rate' => $request->waybill_vat_rate,
                    'invoice_item_vat_amount' => $request->waybill_vat_amount,
                    'invoice_discount_type' => 1,
                    'invoice_discount_amount' => 0,
                    'invoice_discount_total' => 0,
                    'invoice_total_amount' => $request->waybill_total_amount,
                    'created_user' => auth()->user()->user_id,
                    'invoice_item_notes' => '  فاتوره  بوليصه شحن سياره رقم' . ' ' . $waybill_hd->waybill_code,
                    'invoice_from_date' => Carbon::now(),
                    'invoice_to_date' => Carbon::now()
                ]);

                $waybill_hd->waybill_invoice_id = $invoice_hd->invoice_id;
                $waybill_hd->save();

                $invoice_dt->invoice_reference_no = $waybill_hd->waybill_id;
                $invoice_dt->save();

                $invoice_journal = new JournalsController();
                $total_amount = $invoice_hd->invoice_amount;
                $cc_voucher_id = $invoice_hd->invoice_id;
                $items_id[] = $waybill_hd->waybill_id;
                $customer_notes = 'قيد فاتورة شحن سياره رقم' . ' ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
                $vat_notes = '  قيد ضريبه محصلة للقاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
                $sales_notes = '  قيد ايراد للفاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
                $notes = '  قيد فاتوره رقم ' . $invoice_hd->invoice_no . ' بوليصة شحن ' . $waybill_hd->waybill_code;
                $message = $invoice_journal->addWaybillInvoiceJournal($total_amount, $invoice_hd->customer_id, $cc_voucher_id,
                    $customer_notes, 119, $vat_notes, $sales_notes, 40, $items_id,
                    $items_amount = [], $notes);
                if ($message) {
                    return back()->with(['error' => $message]);
                }
                if ($request->waybill_paid_amount > 0) {

                    //////////////////////////
                    /// اضافه سند قبض وقيد علي سند القبض
                    $bond_controller = new BondsController();
                    $transaction_type = 88; ///بوليصه السايرات
                    $transaction_id = $waybill_hd->waybill_id;
                    $customer_id = $waybill_hd->customer_id;
                    $customer_type = 'customer';
                    $total_amount = $request->waybill_paid_amount;
                    $bond_doc_type = SystemCode::where('system_code', 58002)
                        ->where('company_group_id', $company->company_group_id)->first(); ////ايرادات مبيعات
                    // return $bond_doc_type;
                    $bond_ref_no = $waybill_hd->waybill_code;
                    $bond_notes = '  سداد بوليصه رقم ' . ' ' . $waybill_hd->waybill_code . ' ' . 'بواسطه' . ' ' . $waybill_hd->waybill_sender_name;

                    $payment_method = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $waybill_payment_terms->system_code)->first();

                    $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id,
                        $customer_id, $customer_type, '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

                    $invoice_hd->bond_code = $bond->bond_id;
                    $invoice_hd->bond_date = Carbon::now();
                    $invoice_hd->invoice_total_payment = $request->waybill_paid_amount;
                    $invoice_hd->save();

                    $waybill_hd->bond_code = $bond->bond_code;
                    $waybill_hd->bond_id = $bond->bond_id;
                    $waybill_hd->bond_date = $bond->bond_date;
                    $waybill_hd->save();

                    $bond_journal = new JournalsController();
                    $cc_voucher_id = $bond->bond_id;
                    $journal_category_id = 4; ////سند قبض بوليصه سياره
                    $cost_center_id = 53;
                    $account_type = 56002;
                    $bank_id = $request->bank_id ? $request->bank_id : '';
                    $journal_notes = '  قيد  سند القبض رقم ' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
                    $payment_method_terms = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $bond->bond_method_type)->first();
                    //return $request->waybill_payment_terms;
                    $customer_notes = '  قيض عميل  سند رقم' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
                    $sales_notes = '  قيض ايرادات  سند رقم' . $bond->bond_code . ' ' . 'بوليصة شحن' . ' ' . $waybill_hd->waybill_code;
                    //return $payment_method_terms;
                    $message1 = $bond_journal->AddCaptureJournal($account_type, $customer_id, $bond_doc_type->system_code, $total_amount,
                        $cc_voucher_id, $payment_method_terms, $bank_id, $journal_category_id,
                        $cost_center_id, $journal_notes, $customer_notes, $sales_notes);

                    if (isset($message1)) {
                        return back()->with(['error' => $message1]);
                    }

                }
            }
            ////////////////////اضافه سند صرف لمصروف الطريق
            if ($request->waybill_fees_load > 0) {
                $payment_terms = SystemCode::where('system_code', 57001)
                    ->where('company_group_id', $company->company_group_id)->first(); ///الدفع نقدي
                $trip = TripHd::where('trip_hd_id', $waybill_hd->waybill_trip_id)->first();

                $bond_controller = new BondsController();
                $transaction_type = 88;
                $transaction_id = $waybill_hd->waybill_id;
                $bond_car_id = $waybill_hd->waybill_truck_id;
                $j_add_date = Carbon::parse($request->waybill_load_date)->toDateString();

                $customer_type = 'car';
                $bond_bank_id = $request->bank_id ? $request->bank_id : '';
                $total_amount = $request->waybill_fees_load;
                ///مصاريف للسائق
                $bond_ref_no = $waybill_hd->waybill_code;
                $bond_notes = '  سند صرف مصروف الطريق بوليصه  رقم' . $waybill_hd->waybill_code;

                $journal_category_id = 12;

                $journal_type = JournalType::where('journal_types_code', $journal_category_id)
                    ->where('company_group_id', $company->company_group_id)->first();

                $bond_doc_type = SystemCode::where('system_code_id', $journal_type->bond_type_id)->where('company_group_id', $company->company_group_id)
                    ->first();

                if ($journal_type->bond_type_id) {
                    $bond_account_id = $journal_type->account_id_debit;

                } else {
                    return back()->with(['error' => 'لا يوجد نشاط مضاف لهذا النوع من القيود']);
                }


                $bond = $bond_controller->addCashBond($payment_terms, $transaction_type, $transaction_id,
                    '', $customer_type, $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no,
                    $bond_notes, $bond_account_id, 0, 0, $bond_car_id, $j_add_date);


                $journal_controller = new JournalsController();
                $cost_center_id = 54;
                $cc_voucher_id = $bond->bond_id;
                // $bank_id = $request->bank_id ? $request->bank_id : '';

                if ($request->bank_id) {
                    $bank_id = $request->bank_id;
                } else {
                    // return back()->with(['error' => 'لا يوجد بنك لاضافه قيد سند الصرف']);
                    $bank_id = '';
                }

                $customer_id = $waybill_hd->waybill_truck_id;
                $journal_notes = ' اضافه قيد سند صرف البوليصه رقم' . $waybill_hd->waybill_code . 'سند الصرف رقم' . $bond->bond_code;
                $customer_notes = ' اضافه قيد سند صرف  للعميل البوليصه رقم' . $waybill_hd->waybill_code;
                $cash_notes = ' اضافه قيد سند صرف  لبوليصه رقم' . $waybill_hd->waybill_code;
                $message = $journal_controller->AddCashJournal(56002, $customer_id, $bond_doc_type->system_code,
                    $total_amount, 0, $cc_voucher_id, $payment_terms, $bank_id,
                    $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::error('waybill store', [$e]);
            return false;
        }
    }
}
