<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BondResource;
use App\Models\Account;
use App\Models\Bond;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\InvoiceHd;
use App\Models\MaintenanceCardDetails;
use App\Models\SystemCode;
use App\Models\TripHd;
use App\Models\Trucks;
use App\Models\WaybillHd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BondsCaptureController extends Controller
{
    public function getDeservedValue()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $system_code = SystemCode::where('system_code', 56002)
            ->where('company_id', $company->company_id)->first();
        if (request()->app_menu_id == 73) {
            $invoice = InvoiceHd::where('company_id', $company->company_id)
                ->where('invoice_no', request()->reference_number)->first();

            if (isset($invoice)) {
                $customer = $invoice->customer;
                return response()->json(['data' => ($invoice->invoice_amount - $invoice->invoice_total_payment)
                    , 'customer' => $customer, 'system_code_id' => $system_code->system_code_id]);
            } else {
                return response()->json(['status' => 500, 'message' => 'لايوجد فاتوره بهذا الرقم']);
            }
        }

        if (request()->app_menu_id == 70) {
            $waybill = WaybillHd::where('company_id', $company->company_id)
                ->where('waybill_code', request()->reference_number)->first();

            if (isset($waybill)) {
                $customer = $waybill->customer;
                return response()->json(['data' => ($waybill->waybill_amount - $waybill->waybill_paid_amount),
                    'customer' => $customer, 'system_code_id' => $system_code->system_code_id]);
            } else {
                return response()->json(['status' => 500, 'message' => 'لايوجد بوليصه بهذا الرقم']);
            }
        }
    }

    public function getAccountList()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if (request()->system_code_id) {
            $system_code = SystemCode::where('system_code_id', request()->system_code_id)->first();
        }
        if (request()->system_code) {
            $system_code = SystemCode::where('company_group_id', $company->company_group_id)
                ->where('system_code', request()->system_code)->first();
        }

        if ($system_code->system_code == 56001) {
            ///مورد
            $customers = DB::table('customers')->where('customer_category', 1)
                ->where('company_group_id', $company->company_group_id)->get();
        }
        if ($system_code->system_code == 56002) {
            ///عميل
            $customers = DB::table('customers')->whereIn('customer_category', [2, 3, 4, 5, 6, 7, 8, 9])
                ->where('company_group_id', $company->company_group_id)->get();
        }

        if ($system_code->system_code == 56003) {
            ///موظف
            $employees = DB::table('employees')->where('company_group_id', $company->company_group_id)->get();
        }

        if ($system_code->system_code == 56004) {
            ///سياره
            $cars = DB::table('trucks')->where('company_group_id', $company->company_group_id)->get();
        }

        if ($system_code->system_code == 56005) {
            ///فرع
            $branches = $company->branches;
        }

        if (isset($employees)) {
            return response()->json(['employees' => $employees]);
        } elseif (isset($customers) && $system_code->system_code == 56002) {
            return response()->json(['customers' => $customers]);
        } elseif (isset($customers) && $system_code->system_code == 56001) {
            return response()->json(['suppliers' => $customers]);
        } elseif (isset($branches)) {
            return response()->json(['branches' => $branches]);
        } elseif (isset($cars)) {
            return response()->json(['cars' => $cars]);
        }

    }

    public function getCustomerAccount()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if (request()->emp_id) {
            $system_code = SystemCode::where('system_code_id', request()->system_code_id)
                ->where('company_id', $company->company_id)->first();
            return response()->json(['data' => $system_code->system_code_acc_id, 'account' => Account::where('acc_id',
                $system_code->system_code_acc_id)->first()]);
        }

        if (request()->customer_id) {
            $customer = Customer::where('customer_id', request()->customer_id)->first();
            return response()->json(['data' => $customer->customer_account_id, 'account' => $customer->account]);
        }

    }

    public function getRelatedAccount()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if (request()->system_code_id) {
            $system_code = SystemCode::where('system_code_id', request()->system_code_id)
                ->where('company_group_id', $company->company_group_id)->first();
        }

        if (request()->system_code) {
            $system_code = SystemCode::where('system_code', request()->system_code)
                ->where('company_group_id', $company->company_group_id)->first();
        }

        return response()->json(['data' => $system_code->system_code_acc_id, 'account' => $system_code->account,
            'vat_rate' => $system_code->system_code_tax_perc ? $system_code->system_code_tax_perc : 0]);
    }


    public function getMaintenanceCardList()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        //return $company;
        $maintenance_card_list = DB::table('maintenance_cards_dt')->whereNull('bond_id')
            ->where('mntns_cards_item_type', '=',
                SystemCode::where('system_code', 53002)->first()->system_code_id)
            ->where('maintenance_cards_dt.company_id', '=', $company->company_id)
            ->join('maintenance_cards_hd', 'maintenance_cards_dt.mntns_cards_id', '=',
                'maintenance_cards_hd.mntns_cards_id')->get();
        return response()->json(['data' => $maintenance_card_list]);
    }

    public function getMaintenanceCardDtValue()
    {
        $maintenance_dts = MaintenanceCardDetails::find(request()->reference_number);
        return response()->json(['data' => $maintenance_dts]);
    }

    public function GetTripHd()
    {
        $trip_hd = TripHd::where('trip_hd_code', request()->trip_hd_code)->first();
        $system_code = SystemCode::where('company_group_id', $trip_hd->company_group_id)
            ->where('system_code', 56004)->first();
        // $account=SystemCode::where('company_id',$trip_hd->company_id)->where('system_code',563)->first();
        return response()->json(['system_code' => $system_code, 'truck' => $trip_hd->truck]);
    }


    public function getBond()
    {
        $bond = Bond::where('bond_type_id', 1)->where('customer_id', request()->customer_id)
            ->where('bond_code', 'like', '%' . request()->bond_code . '%')
            ->where('bond_ref_no', null)->first();

        if (isset($bond)) {
            return response()->json(['status' => '200', 'data' => new BondResource($bond)]);
        } else {
            return response()->json(['status' => '500']);
        }
    }

}
