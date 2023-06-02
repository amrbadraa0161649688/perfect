<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccounPeriod;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\SystemCode;
use App\Models\SystemCodeCategory;
use App\Models\Customer;
use App\Models\WaybilDt;
use App\Models\WaybillHd;

class WaybillCargoController extends Controller
{
    public function index(Request $request)
    {
        $main_companies = CompanyGroup::get();
        $companies = Company::get();
        if (request()->company_id) {
            $company = Company::find(request()->company_id);
            $companies = Company::where('company_group_id', $company->company_group_id)->get();
        }
        if (request()->company_group_id) {
            $companies = Company::where('company_group_id', request()->company_group_id)->get();
        }

        if ($request->ajax()) {

                $data = WaybillHd::get();
        }

        return view('Waybill.Goods.index_cargo', compact( 'main_companies',  'companies'));
    }

    public function create()
    {
        $sys_codes_item = SystemCode::where('sys_category_id', 28)->where('company_group_id',13)->get();
        $sys_codes_unit = SystemCode::where('sys_category_id', 35)->where('company_group_id',13)->get();
        $suppliers = Customer::get();


        $customers = Customer::get();
        $companies = Company::get();
        return view('Waybill.Goods.create_cargo', compact('companies','suppliers','customers','sys_codes_item','sys_codes_unit'));
    }

    public function store(Request $request)
    {

        $emp_variables_notes = $this->array_remove_null($request->emp_variables_notes);
        $companies = Company::where('company_group_id', auth()->user()->company_group_id)->get();
        $customers = Customer::get();
        $sys_codes_item = SystemCode::where('sys_category_id', 28)->where('company_group_id',13)->get();


        $invoice_hd = WaybillHd::create([
            'company_group_id' => auth()->user()->company_group_id,
            'company_id' => $request->company_id,

            'waybill_code' => $request->waybill_code,

            'branch_id' => $request->branch_id,
            'customer_id' => $request->customer_id,
            'waybill_date' => $request->waybill_date,
            'supplier_id' => $request->supplier_id,
            'waybill_status' => $request->waybill_status,
            'waybill_print_no' => $request->waybill_print_no,
            'waybill_request_no' => $request->waybill_request_no,
            'waybill_description' => $request->waybill_description,
            'waybill_type_id' => $request->waybill_type_id,
            'waybill_loc_from' => $request->waybill_loc_from,
            'waybill_loc_to' => $request->waybill_loc_to,
            'waybill_payment_method' => $request->waybill_payment_method,
            'waybill_payment_terms' => $request->waybill_payment_terms,
            'waybill_delivery_expected' => $request->waybill_delivery_expected,

            'waybill_sender_name' => $request->waybill_sender_name,
            'waybill_sender_company' => $request->waybill_sender_company,
            'waybill_sender_address' => $request->waybill_sender_address,
            'waybill_sender_city' => $request->waybill_sender_city,
            'waybill_sender_phone' => $request->waybill_sender_phone,
            'waybill_sender_mobile' => $request->waybill_sender_mobile,
            'waybill_sender_mobile_code' => $request->waybill_sender_mobile_code,
            'waybill_sender_box_no' => $request->waybill_sender_box_no,
            'waybill_sender_post_code' => $request->waybill_sender_post_code,

            'waybill_receiver_name' => $request->waybill_receiver_name,
            'waybill_receiver_company' => $request->comwaybill_receiver_companypany_id,
            'waybill_receiver_address' => $request->waybill_receiver_address,
            'waybill_receiver_city' => $request->waybill_receiver_city,
            'waybill_receiver_phone' => $request->waybill_receiver_phone,
            'waybill_receiver_mobile' => $request->waybill_receiver_mobile,
            'waybill_receiver_mobile_code' => $request->waybill_receiver_mobile_code,
            'waybill_receiver_box_no' => $request->waybill_receiver_box_no,
            'waybill_receiver_post_code' => $request->waybill_receiver_post_code,

            'waybill_truck_type_id' => $request->waybill_truck_type_id,
            'waybill_amount' => $request->waybill_amount,
            'waybill_add_amount' => $request->waybill_add_amount,
            'waybill_discount_amount' => $request->waybill_discount_amount,
            'waybill_vat_rate' => $request->waybill_vat_rate,
            'waybill_vat_amount' => $request->waybill_vat_amount,
            'waybill_total_amount' => $request->waybill_total_amount,
            'waybill_paid_amount' => $request->waybill_paid_amount,
            'waybill_due_amount' => $request->waybill_due_amount,
            'waybill_invoice_id' => $request->waybill_invoice_id,
            'waybill_create_user' => $request->waybill_create_user,
            'waybill_delivery_user' => $request->waybill_delivery_user,
            'waybill_delivery_date' => $request->waybill_delivery_date,
            'waybill_approved_user' => $request->waybill_approved_user,
            'waybill_approved_date' => $request->waybill_approved_date,

            'created_user' => auth()->user()->user_id
        ]);

        foreach ($waybill_item_id as $k => $waybill_item_id) {
            WaybillDt::create([

                'waybill_hd_id' => $waybill_hd->waybill_id,
                'company_group_id' => auth()->user()->company_group_id,
                'company_id'=> auth()->user()->company_id,
                'branch_id' => $request->branch_id[$k],

                'waybill_item_id' => $request->waybill_item_id[$k],
                'waybill_item_unit' => $request->waybill_item_unit[$k],
                'waybill_item_quantity' => $request->waybill_item_quantity[$k],
                'waybill_item_price' => $request->waybill_item_price[$k],
                'waybill_item_amount' => $request->waybill_item_amount[$k],
                'waybill_add_amount' => $request->waybill_add_amount[$k],
                'waybill_item_vat_rate' => $request->waybill_item_vat_rate[$k],
                'waybill_item_vat_amount' => $request->waybill_item_vat_amount[$k],
                'waybill_discount_type' => $request->waybill_discount_type[$k],
                'waybill_discount_amount' => $request->waybill_discount_amount[$k],
                'waybill_discount_total' => $request->waybill_discount_total[$k],
                'waybill_total_amount' => $request->waybill_total_amount[$k],

                'waybill_item_qut_requried' => $request->waybill_item_qut_requried[$k],
                'waybill_item_qut_received' => $request->waybill_item_qut_received[$k],
                'waybill_item_qut_difference' => $request->waybill_item_qut_difference[$k],
                'waybill_goods_value' => $request->waybill_goods_value[$k],
                'waybill_insurance_status' => $request->waybill_insurance_status[$k],
                'waybill_insurance_value' => $request->waybill_insurance_value[$k],


                'created_user' => auth()->user()->user_id
            ]);
        }

        return redirect()->route('Waybill-add')->with(['success' => 'تمت الاضافه']);
    }



}

