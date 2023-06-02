<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarRentContractResource;
use App\Models\AccounPeriod;
use App\Models\CarRentContract;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->whereHas('cus_type', function ($query) {
                $query->where('system_code', 539);
            })->get();
        $branches = $company->branches;
        $invoices = InvoiceHd::where('invoice_type', 14)->where('branch_id', session('branch')['branch_id']);

        $data = request()->all();

        if (request()->branch_id) {
            $invoices->where('invoice_type', 14)->whereIn('branch_id', request()->branch_id);

            if (request()->customers_id) {
                $invoices->where('invoice_type', 14)->whereIn('customer_id', request()->customers_id);
            }

            if (request()->from_date && request()->to_date) {
                $invoices->where('invoice_type', 14)->whereDate('created_date', '>=', request()->from_date)
                    ->whereDate('created_date', '<=', request()->to_date);
            }

            if (request()->due_date_from && request()->due_date_to) {
                $invoices->where('invoice_type', 14)->whereDate('invoice_due_date', '>=', request()->due_date_from)
                    ->whereDate('invoice_due_date', '<=', request()->due_date_to);

            }

            if (request()->statuses) {
                $invoices->where('invoice_type', 14)->whereIn('invoice_status', request()->statuses);
            }

        }

        if (request()->invoice_code) {
            $invoices = $invoices->where('invoice_type', 14)->where('company_id', $company->company_id)
                ->where('invoice_no', 'like', '%' . request()->invoice_code . '%');
        }
        $invoices = $invoices->paginate(EnumSetting::Paginate);

        return view('CarRent.Invoices.index', compact('customers', 'branches', 'invoices', 'data'));
    }


    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->whereHas('cus_type', function ($query) {
                $query->where('system_code', 539);
            })->get();
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        return view('CarRent.Invoices.create', compact('customers', 'companies'));
    }

    public function store(Request $request)
    {
        //return $request->all();
        $company = session('company') ? session('company') : auth()->user()->company;
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $contracts = CarRentContract::whereIn('contract_id', $request->contract_id)->get();

        foreach ($contracts as $contract) {
            $contracts_vat_amount[] = $contract->contract_vat_rate * $contract->totalDailyCost;
        }

        $last_invoice_reference = CompanyMenuSerial::where('branch_id', session('branch')['branch_id'])
            ->where('app_menu_id', 46)->latest()->first();

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
                'branch_id' => session('branch')['branch_id'],
                'app_menu_id' => 46,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }
        $invoice_vat_rate = array_sum($request->invoice_vat_amount) /
            (array_sum($request->invoice_amount) - array_sum($request->invoice_vat_amount));


        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'acc_period_id' => $account_period->acc_period_id,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => Carbon::now(),
            'invoice_amount' => array_sum($request->invoice_amount),
            'invoice_vat_amount' => array_sum($request->invoice_vat_amount),
            'invoice_vat_rate' => $invoice_vat_rate * 100,
            'invoice_total_payment' => array_sum($request->invoice_amount),
            'invoice_notes' => $request->invoice_notes,
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $contract->customer_id,
            'customer_name' => $contract->customer->customer_name_full_ar,
            'customer_address' => $contract->customer->customer_address_1,
            'customer_phone' => $contract->customer->customer_phone,
            'invoice_type' => 14, ///فاتوره التاجير
            'invoice_status' => 121001, ///مسوده
            'invoice_is_payment' => 1
        ]);

        $invoice_item = SystemCode::where('system_code', 580)->where('company_group_id', $company->company_group_id)
            ->first();

        foreach ($request->contract_id as $k => $contract_id) {

            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $invoice_item->system_code_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => $request->days_count[$k],
                'invoice_item_price' => $contract->rentDayCost,
                'invoice_item_vat_rate' => $contract->contract_vat_rate,
                'invoice_item_vat_amount' => $request->invoice_vat_amount[$k],
                'invoice_discount_amount' => 0, // نسبة الخصم
                'invoice_discount_total' => 0,//قيمة الخصم
                'invoice_total_amount' => $request->invoice_amount[$k],////شامله الضريبه
                'invoice_item_amount' => $request->invoice_item_amount[$k], //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => $request->invoice_item_notes[$k],
                'invoice_reference_no' => $contract->contract_id,

                'invoice_from_date' => Carbon::parse($request->from_date), //
                'invoice_to_date' => Carbon::parse($request->to_date), //
            ]);

        }

        return redirect()->route('car-rent.invoices');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company_group = session('company_group') ? session('company_group') : auth()->user()->companyGroup;
        $invoice = InvoiceHd::find($id);
        $companies = Company::where('company_id', $invoice->company_id)
            ->where('company_group_id', $company_group->company_group_id)->first();

        return view('Invoices.Cars.show', compact('invoice', 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCustomerContracts()
    {

        $customer = Customer::find(request()->customer_id);
        $opened_contracts = $customer->contracts->where('closed_datetime', null);

        $closed_contracts = $customer->contracts->where('closed_datetime', '!=', null);
//return $opened_contracts;
        return response()->json(['data' => CarRentContractResource::collection($opened_contracts),
            'closed_contracts' => CarRentContractResource::collection($closed_contracts)]);
    }

    public function getDifferenceDate()
    {

        $from_date = Carbon::parse(request()->from_date);
        $to_date = Carbon::parse(request()->to_date);
        $days_count = $from_date->diffInDays($to_date);

        return response()->json(['data' => $days_count]);
    }
}
