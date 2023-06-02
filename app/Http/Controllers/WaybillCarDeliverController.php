<?php

namespace App\Http\Controllers;

use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Http\Resources\WaybillCarInvoiceResource;
use App\Models\Company;
use App\Models\Customer;
use App\Models\SystemCode;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WaybillCarDeliverController extends Controller
{
    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('company_group_id', $company->company_group_id)->get();
        $sys_codes_payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();
        return view('Waybill.CarsDeliver.create', compact('companies', 'customers', 'sys_codes_payment_methods'));
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        $company = Company::find($request->company_id);
        $waybill_status = SystemCode::where('system_code', 41008)->where('company_group_id', $company->company_group_id)
            ->first();

        if ($request->customer_type_code == 538) {
////////////////////////////
            /// اضافه سند قبض وقيد علي سند القبض
            $payment_method = SystemCode::where('system_code_id', $request->payment_method_id)
                ->where('company_group_id', $company->company_group_id)->first();

            $bond_controller = new BondsController();
            $transaction_type = 88; ///بوليصه السيارات
            $transaction_id = '';
            $customer_id = $request->customer_id;
            $customer_type = 'customer';

            $total_amount = $request->total;
            $bond_doc_type = SystemCode::where('system_code', 58002)
                ->where('company_group_id', $company->company_group_id)->first(); ////ايرادات مبيعات

            $bond_ref_no = '';
            $bond_notes = '  سداد بوليصه';
            $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id,
                $customer_id, $customer_type, '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);


            $bond_journal = new JournalsController();
            $cc_voucher_id = $bond->bond_id;
            $journal_category_id = 4; ////سند قبض بوليصه سياره
            $cost_center_id = 53;
            $account_type = 56002;
            $journal_notes = ' سند قبد رقم ' . $bond->bond_code;
            $payment_method_terms = SystemCode::where('company_group_id', $company->company_group_id)->where('system_code', $bond->bond_method_type)->first();

            $customer_notes = '  قيد  رقم' . $bond->bond_code;
            $sales_notes = 'قيد  رقم';
            // return $payment_method_terms;
            $message = $bond_journal->AddCaptureJournal($account_type, $customer_id, $bond_doc_type->system_code, $total_amount,
                $cc_voucher_id, $payment_method_terms, $bank_id = '', $journal_category_id,
                $cost_center_id, $journal_notes, $customer_notes, $sales_notes);

            if ($message) {
                Session::flash('error', $message);
                Session::save();
                return redirect()->route('WaybillCarDeliver.create');
            }
        }

//        return $request->customer_type_code;
        foreach ($request->waybill_id as $waybill_id) {

            $waybill = WaybillHd::find($waybill_id);

            if ($request->customer_type_code == 538) {
                if ($waybill->invoice) {
                    $invoice_hd = $waybill->invoice;
                    $invoice_hd->bond_code = $bond->bond_id;
                    $invoice_hd->bond_date = $bond->bond_date;
                    $invoice_hd->invoice_total_payment = $invoice_hd->invoice_total_payment + $waybill->waybill_due_amount;
                    $invoice_hd->save();
                }

                $waybill->update([
                    'waybill_due_amount' => 0,
                    'waybill_paid_amount' => $waybill->waybill_paid_amount + $waybill->waybill_due_amount,
                    'bond_code' => $bond->bond_code,
                    'bond_id' => $bond->bond_id,
                    'bond_date' => $bond->bond_date,
                ]);
            }


            $waybill->waybill_status = $waybill_status->system_code_id;
            $waybill->waybill_delivery_user = auth()->user()->user_idd;
            $waybill->waybill_delivery_date = Carbon::now();
            $waybill->receiver_name = $request->receiver_name;
            $waybill->receiver_id = $request->receiver_id;
            $waybill->save();
            $waybill->statusM()->attach($waybill_status->system_code_id, ['status_date' => Carbon::now()]);

//            if ($waybill->waybillId) {
//                $naql_controller = new NaqlWayAPIController();
//                $naql_controller->updateWaybill($waybill);
//            }
        }

        DB::commit();
        return redirect()->route('WaybillCar');
    }

    public function getCustomerWaybills()
    {
        $company = Company::find(request()->company_id);

        $customer = Customer::where('customer_id', request()->customer_id)->first();

        if ($customer->cus_type->system_code == 539) {
            $waybills = WaybillHd::where('customer_id', request()->customer_id)
                ->where('waybill_type_id', 4)->where('company_id', request()->company_id)
                ->whereDate('waybill_load_date', '>=', Carbon::parse(request()->from_date)->format('Y-m-d'))
                ->whereDate('waybill_load_date', '<=', Carbon::parse(request()->to_date)->format('Y-m-d'))
                ->where('waybill_payment_method', 54003)
                ->where('waybill_status', SystemCode::where('system_code', 41007)->where('company_group_id', $company->company_group_id)->first()->system_code_id)
                ->get();
        } elseif ($customer->cus_type->system_code == 538) {
            $waybills = WaybillHd::where('customer_id', request()->customer_id)
                ->where('waybill_type_id', 4)->where('company_id', request()->company_id)
                ->whereDate('waybill_load_date', '>=', Carbon::parse(request()->from_date)->format('Y-m-d'))
                ->whereDate('waybill_load_date', '<=', Carbon::parse(request()->to_date)->format('Y-m-d'))
                ->where('waybill_status', SystemCode::where('system_code', 41007)->where('company_group_id', $company->company_group_id)->first()->system_code_id)
                ->get();
        } else {
            $waybills = [];
        }


        return response()->json(['data' => WaybillCarInvoiceResource::collection($waybills), 'customer_type_code' => $customer->cus_type->system_code]);
    }
}
