<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tajeer\TajeerAPIController;
use App\Http\Controllers\General\BondsController;
use App\Http\Controllers\General\JournalsController;
use App\Models\AccounPeriod;
use App\Models\Attachment;
use App\Models\Bond;
use App\Models\Branch;
use App\Models\CarPriceListDt;
use App\Models\CarPriceListHd;
use App\Models\CarRentAccident;
use App\Models\CarRentBrandDt;
use App\Models\CarRentCars;
use App\Models\CarRentContract;
use App\Models\Company;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\Note;
use App\Models\PriceListDt;
use App\Models\SystemCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarRentController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;

        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $branches = Branch::where('company_group_id', $company->company_group_id)->get();
        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5)->get();
        $brand_dts = CarRentBrandDt::where('company_group_id', $company->company_group_id)->get();
        $sys_codes_tracker_status = SystemCode::where('sys_category_id', 33)->where('company_group_id', $company->company_group_id)->get();

        $contract_types = SystemCode::where('sys_category_id', 65)
            ->where('company_id', $company->company_id)->get();

        $contract_statuses = SystemCode::where('sys_category_id', 136)
            ->where('company_id', $company->company_id)->get();

        $con_open_code = SystemCode::where('system_code', 13601)
            ->where('company_group_id', $company->company_group_id)->first();
        $con_late_code = SystemCode::where('system_code', 13603)
            ->where('company_group_id', $company->company_group_id)->first();
        $con_close_code = SystemCode::where('system_code', 13602)
            ->where('company_group_id', $company->company_group_id)->first();

        $contracts = CarRentContract::where('company_group_id', $company->company_group_id);

        $contract_open_count = CarRentContract::where('company_group_id', $company->company_group_id);
        $contract_today_count = CarRentContract::where('company_group_id', $company->company_group_id);
        $contract_late_count = CarRentContract::where('company_group_id', $company->company_group_id);
        $contract_close_count = CarRentContract::where('company_group_id', $company->company_group_id);

        if (request()->company_id) {
            $contracts->whereIn('company_id', request()->company_id);

            $contract_open_count->whereIn('company_id', request()->company_id);
            $contract_today_count->whereIn('company_id', request()->company_id);
            $contract_late_count->whereIn('company_id', request()->company_id);
            $contract_close_count->whereIn('company_id', request()->company_id);

            if (request()->branch_id) {
                $contracts->whereIn('branch_id', request()->branch_id);
                $contract_open_count->whereIn('branch_id', request()->branch_id);
                $contract_today_count->whereIn('branch_id', request()->branch_id);
                $contract_late_count->whereIn('branch_id', request()->branch_id);
                $contract_close_count->whereIn('branch_id', request()->branch_id);
            }
            if (request()->contract_code) {
                $contracts->where('contract_code', 'like', '%' . request()->contract_code . '%');
            }
            if (request()->contract_status) {
                $contracts->whereIn('contract_status', request()->contract_status);
            }
            if (request()->contractTypeCode) {
                $contracts->whereIn('contractTypeCode', request()->contractTypeCode);
            }

            if (request()->customers_id) {
                $contracts->whereIn('customer_id', request()->customers_id);
            }

            if (request()->customer_dt) {
                $customer_ids = Customer::Where('customer_mobile', 'like', '%' . request()->customer_mobile . '%')
                    ->where('customer_category', 5)
                    ->orWhere('customer_identity', 'like', '%' . request()->customer_identity . '%')->pluck('customer_id')->toArray();
                $contracts = $contracts->whereIn('customer_id', $customer_ids);
            }
            if (request()->tamm_status) {
                $contracts->where('tamm_status', request()->tamm_status);
            }

            if (request()->contractStartDate_from && request()->contractStartDate_to) {
                $contracts->whereDate('contractStartDate', '>=', request()->contractStartDate_from)
                    ->whereDate('contractStartDate', '<=', request()->contractStartDate_to);

                $contract_open_count->whereDate('contractStartDate', '>=', request()->contractStartDate_from)
                    ->whereDate('contractStartDate', '<=', request()->contractStartDate_to);
                $contract_today_count->whereDate('contractStartDate', '>=', request()->contractStartDate_from)
                    ->whereDate('contractStartDate', '<=', request()->contractStartDate_to);
                $contract_late_count->whereDate('contractStartDate', '>=', request()->contractStartDate_from)
                    ->whereDate('contractStartDate', '<=', request()->contractStartDate_to);
                $contract_close_count->whereDate('contractStartDate', '>=', request()->contractStartDate_from)
                    ->whereDate('contractStartDate', '<=', request()->contractStartDate_to);
            }

            if (request()->contractEndDate) {
                $contracts->whereDate('contractEndDate', '=', request()->contractEndDate);

                $contract_open_count->whereDate('contractEndDate', '>=', request()->contractEndDate);
                $contract_today_count->whereDate('contractEndDate', '>=', request()->contractEndDate);
                $contract_late_count->whereDate('contractEndDate', '>=', request()->contractEndDate);
                $contract_close_count->whereDate('contractEndDate', '>=', request()->contractEndDate);
            }

            if (request()->closed_datetime_from && request()->closed_datetime_to) {
                $contracts->whereDate('closed_datetime', '>=', request()->closed_datetime_from)
                    ->whereDate('closed_datetime', '<=', request()->closed_datetime_to);

                $contract_open_count->whereDate('closed_datetime', '>=', request()->closed_datetime_from)
                    ->whereDate('closed_datetime', '<=', request()->closed_datetime_from);
                $contract_today_count->whereDate('closed_datetime', '>=', request()->closed_datetime_from)
                    ->whereDate('closed_datetime', '<=', request()->closed_datetime_from);
                $contract_late_count->whereDate('closed_datetime', '>=', request()->closed_datetime_from)
                    ->whereDate('closed_datetime', '<=', request()->closed_datetime_from);
                $contract_close_count->whereDate('closed_datetime', '>=', request()->closed_datetime_from)
                    ->whereDate('closed_datetime', '<=', request()->closed_datetime_from);
            }


            if (request()->full_car_plate) {
                $cars_ids = CarRentCars::where('full_car_plate', 'like', '%' . request()->full_car_plate . '%')->pluck('car_id')->toArray();
                $contracts = $contracts->whereIn('car_id', $cars_ids);
            }

            if (request()->brand_dt) {
                $cars_ids = CarRentCars::whereIn('car_brand_dt_id', request()->brand_dt)->pluck('car_id')->toArray();
                $contracts = $contracts->whereIn('car_id', $cars_ids);
            }
            if (request()->tammExternalAuthorizationCountries) {
                $contracts->where('tammExternalAuthorizationCountries', request()->tammExternalAuthorizationCountries);
            }
            if (request()->car_trucker_status) {
                $cars_ids = CarRentCars::whereIn('car_trucker_status', request()->car_trucker_status)->pluck('car_id')->toArray();
                $contracts = $contracts->whereIn('car_id', $cars_ids);

            }

            if (request()->balance_from) {
                $contracts->where('contract_balance', '>=', request()->balance_from);
            }
            if (request()->balance_to) {
                $contracts->where('contract_balance', '<=', request()->balance_to);
            }
            if (request()->tamm_enddate_hejri) {
                $contracts->whereDate('tamm_enddate_hejri', request()->tamm_enddate_hejri);
            }
        } else {
            $contracts->where('branch_id', $branch->branch_id);
        }

        $contracts = $contracts->latest()->paginate(EnumSetting::Paginate);
        $contract_open_count = $contract_open_count->whereIn('contract_status', SystemCode::whereIn('system_code', [13601])->where('company_group_id', $company->company_group_id)->pluck('system_code_id'))->count();
        $contract_today_count = $contract_today_count->whereDate('contractEndDate', today())->count();
        $contract_late_count = $contract_late_count->whereIn('contract_status', SystemCode::whereIn('system_code', [13603])->where('company_group_id', $company->company_group_id)->pluck('system_code_id'))->count();
        $contract_close_count = $contract_close_count->whereIn('contract_status', SystemCode::whereIn('system_code', [13602])->where('company_group_id', $company->company_group_id)->pluck('system_code_id'))->count();

        return view('CarRent.Contract.index', compact('contracts', 'contract_types', 'contract_statuses', 'companies',
            'customers', 'branches', 'brand_dts', 'sys_codes_tracker_status', 'contract_close_count', 'contract_late_count', 'contract_today_count',
            'contract_open_count', 'con_close_code', 'con_late_code', 'con_open_code', 'company'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $customers = Customer::where('company_group_id', $company->company_group_id)
            ->where('customer_category', 5)
            ->where('customer_status', SystemCode::where('system_code', 26001)
                ->where('company_id', $company->company_id)->first()->system_code_id)
            ->where('customer_account_id', '!=', 0)->get();

        $contract_types = SystemCode::where('sys_category_id', 65)
            ->where('company_group_id', $company->company_group_id)->get();

        $payment_methods = SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $company->company_group_id)->get();

        $rent_policies = SystemCode::where('sys_category_id', 124)
            ->where('company_group_id', $company->company_group_id)->get();

        $countries = SystemCode::where('sys_category_id', 12)
            ->where('company_group_id', $company->company_group_id)->get();

        $branches = $company->branches;

        $current_date = Carbon::now()->format('Y-m-d\TH:i');
        $customer_id = request()->customer_id;

        return view('CarRent.Contract.create1', compact('company', 'customers', 'contract_types',
            'payment_methods', 'branches', 'rent_policies', 'current_date', 'customer_id', 'countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ac' => 'required',
            // 'additionalCoverageCost' => 'required',
            'extraKmCost' => 'required',
            'rentDayCost' => 'required',
            'rentHourCost' => 'required',
            'driverFarePerDay' => 'required',
//            'vehicleTransferCost' => 'required',
            'car_in_another_city' => 'required',
            'fireExtinguisher' => 'required',
            'firstAidKit' => 'required',
            'keys' => 'required',
            'radioStereo' => 'required',
            'screen' => 'required',
            'spareTire' => 'required',
            'speedometer' => 'required',
            'tires' => 'required',
            'availableFuel' => 'required',
            'odometerReading' => 'required',
            'paymentMethodCode' => 'required',
            'paid' => 'required',
            'discount' => 'required',
            'allowedLateHours' => 'required',
            'contractTypeCode' => 'required',
            'contractEndDate' => 'required',
            'contractStartDate' => 'required',
//            'rentPolicyId' => 'required',
            'fullFuelCost' => 'required',
            'car_id' => 'required',
            'customer_id' => 'required|exists:customers,customer_id',
            'sketchInfo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

        ]);

        DB::beginTransaction();
        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;

        $customer = Customer::find($request->customer_id);

        if ($request->driver_id) {
            $driver = Customer::find($request->driver_id);
        }

        $car = CarRentCars::find($request->car_id);

        $last_car_contract_serial = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 44)->latest()->first();

        if (isset($last_car_contract_serial)) {
            $last_car_contract_serial_no = $last_car_contract_serial->serial_last_no;
            $array_number = explode('-', $last_car_contract_serial_no);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number = implode('-', $array_number);
            $last_car_contract_serial->update(['serial_last_no' => $string_number]);
        } else {
            $string_number = 'C-' . session('branch')['branch_id'] . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $branch->branch_id,
                'app_menu_id' => 44,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);
        }

        if ($request->sketchInfo) {
            $sketchInfo = $this->getPhoto($request->sketchInfo);
        }

//return $car->plate_ar_1;
        $con = CarRentContract::create([
            'contract_code' => $string_number,
            'contract_status' => SystemCode::where('system_code', 13601)->where('company_group_id', $company->company_group_id)
                ->first()->system_code_id,
            'company_group_id' => $company->companyGroup->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $request->customer_id,
            'c_personAddress' => $request->c_personAddress,
            'c_email' => $request->c_email,
            'c_mobile' => $request->c_mobile,
            'c_birthDate' => $customer->customer_birthday,
            'c_idTypeCode' => $customer->id_type_code,
            'c_idNumber' => $request->c_idNumber,
            'c_hijriBirthDate' => $customer->customer_birthday_hijiri,//from customer

            'workingBranchId' => session('branch')['branch_code'],

            'receiveBranchId' => $request->receiveBranchCode,
            'returnBranchId' => $request->returnBranchCode,
            'extendedCoverageId' => $request->extendedCoverageCode,
            'actualReturnBranchId' => $request->actualReturnBranchCode,

            'extraKmCost' => $request->extraKmCost, //from form
            'rentDayCost' => $request->rentDayCost, //from form
            'rentHourCost' => $request->rentHourCost,//from form
            'fullFuelCost' => $request->fullFuelCost, // from form
            'driverFarePerDay' => $request->driverFarePerDay ? $request->driverFarePerDay : 0, //from form
            'driverFarePerHour' => $request->driverFarePerHour ? $request->driverFarePerHour : 0, //required if contract type hourly
            'vehicleTransferCost' => $request->car_in_another_city, //from form

            'internationalAuthorizationCost' => $request->internationalAuthorizationCost ? $request->internationalAuthorizationCost : 0, //from form
            'discount' => $request->discount, //from form
            'paid' => $request->paid, //from form
            'extraDriverCost' => $request->extraDriverCost ? $request->extraDriverCost : 0, //from form
            'paymentMethodCode' => $request->paymentMethodCode, // مطلوبه لو القيمه اكبر من صفر //from form
            'additionalCoverageCost' => 0, ///optional

            'car_id' => $request->car_id,
            'plateNumber' => $car->car_plate_number,

            'firstChar' => $car->plate_ar_1,
            'secondChar' => $car->plate_ar_2,
            'thirdChar' => $car->plate_ar_3,

            'ac' => $request->ac, //from car required
            'carSeats' => $request->carSeats, ///from car required
            'fireExtinguisher' => $request->fireExtinguisher, ///from car required
            'firstAidKit' => $request->firstAidKit, ///from car required
            'keys' => $request->keys,///from car required
            'radioStereo' => $request->radioStereo,///from car required
            'safetyTriangle' => $request->safetyTriangle,///from car required
            'screen' => $request->screen,///from car required
            'spareTire' => $request->spareTire,///from car required
            'spareTireTools' => $request->spareTireTools,///from car required
            'speedometer' => $request->speedometer,///from car required
            'tires' => $request->tires,///from car required
            'availableFuel' => $request->availableFuel,///from car required
            'odometerReading' => $request->odometerReading,///from car required
            'oilChangeKmDistance' => $request->oilChangeKmDistance, ///from car require
            'enduranceAmount' => $request->enduranceAmount ? $request->enduranceAmount : 0, ///from car required
            'fuelTypeCode' => $request->fuelTypeCode, /// from car required

            'oilChangeDate' => Carbon::parse($request->oilChangeDate),
            'oilType' => $request->oilType,
//            'sketchInfo' => $request->sketchInfo, //photo
//            'notes' => $request->notes
//            'other1' => $request->other1,
//            'other2' => $request->other2,

////بيانات السائق الااضافي لو موجود
            'driver_id' => isset($driver) ? $request->driver_id : $customer->customer_id,
            'd_idTypeCode' => isset($driver) ? $driver->id_type_code : $customer->id_type_code,
            'd_personAddress' => isset($driver) ? $request->d_personAddress : $request->c_personAddress,
            'd_idNumber' => isset($driver) ? $request->d_idNumber : $request->c_idNumber,
            'd_birthDate' => isset($driver) ? $driver->customer_birthday : $customer->customer_birthday,

            'rentPolicyId' => SystemCode::where('system_code', 24)->where('company_group_id', $company->company_group_id)
                ->first()->system_code_search,

            'contractStartDate' => Carbon::parse($request->contractStartDate),
            'contractEndDate' => Carbon::parse($request->contractEndDate),
            'authorizationTypeCode' => $request->authorizationTypeCode,
//            'tammExternalAuthorizationCountries' => $request->tammExternalAuthorizationCountries,
//            'carSeatPerDay' => $request->carSeatPerDay,//addtionalServices
//            'disabilitiesAidsPerDay' => $request->disabilitiesAidsPerDay,//addtionalServices
//            'carDelivery' => $request->carDelivery,//addtionalServices
//            'navigationSystemPerDay' => $request->navigationSystemPerDay,//addtionalServices
//            'internetPerDay' => $request->internetPerDay,//addtionalServices
            'allowedKmPerHour' => $request->contractTypeCode == 65002 ? $request->allowedKmPerHour : 0, // required if contract type is per hour
            'allowedKmPerDay' => $request->contractTypeCode == 65001 ? $request->allowedKmPerDay : 0, //required if contract type is per day
//            'allowedKmPerDay' => $request->allowedKmPerDay, //required if contract type is per day
            'contractTypeCode' => $request->contractTypeCode, ////system code of contract type
            'allowedLateHours' => $request->allowedLateHours,
            'contract_vat_rate' => $customer->customer_vat_rate * 100, // integer number
            'contract_vat_amout' => $customer->customer_vat_rate * $request->total_before_discount,
            'contract_total_discount' => ($request->discount / 100) * ($request->total_before_discount * (1 + $customer->customer_vat_rate)),
            'contract_net_amount' => $request->final_total,
            'contract_total_payment' => $request->paid,
            'contract_balance' => $request->contract_balance,

            'created_user' => auth()->user()->user_id,
            'days_count' => $request->days_count,
            'sketchInfo' => isset($sketchInfo) ? 'RentContract/' . $sketchInfo : 0,
            'created_at' => Carbon::now(),
            'allow_hr_to_day' => $request->allow_hr_to_day,

            'price_list_id' => $request->price_list_id,

//            'taam_status' => $request->taam_status,
//            'taam_date' => $request->taam_date,
//            'taam_location' => $request->taam_location,

            'tammExternalAuthorizationCountries' => $request->tammExternalAuthorizationCountries,
            'tamm_enddate_hejri' => $request->tamm_enddate_hejri,
            'taam_number' => $request->tamm_number,
        ]);

        if ($request->contractType == 65002) {
            $allowedKmPerHour = $request->allowedKmPerHour;
        } else {
            $allowedKmPerHour = "";
        }
        if ($request->contractType == 65001) {
            $allowedKmPerDay = $request->allowedKmPerDay;
        } else {
            $allowedKmPerDay = "";
        }
        //   $contract_controller = new \App\Http\Controllers\Api\CarRentController();

//        return  $con->c_hijriBirthDate;
//        return $contract_controller->saveContract($request->c_personAddress, $request->c_email, $request->c_mobile, 1, $request->c_idNumber,
//            '12004959', $request->extraKmCost, $request->rentDayCost, $request->rentHourCost, $request->fullFuelCost,
//            $request->driverFarePerDay, $request->driverFarePerHour, $request->vehicleTransferCost, $request->internationalAuthorizationCost,
//            $request->discount, $request->paid, $request->paymentMethodCode, $request->additionalCoverageCost, $car->full_car_plate,
//            $request->oilChangeKmDistance, $request->enduranceAmount, $request->fuelTypeCode, $con->oilChangeDate->timestamp,
//            $request->oilType, $request->ac, $request->carSeats, $request->fireExtinguisher, $request->firstAidKit, $request->keys, $request->radioStereo,
//            $request->safetyTriangle, $request->screen, $request->spareTire, $request->spareTireTools, $request->speedometer,
//            $request->tires, $request->availableFuel, $request->odometerReading, $request->workingBranchId, $request->rentPolicyId,
//            $con->d_idTypeCode, $con->d_personAddress, $con->d_idNumber, $request->extendedCoverageId, $allowedKmPerHour, $request->receiveBranchId,
//            $request->returnBranchId, $allowedKmPerDay, $request->contractTypeCode, $request->allowedLateHours
//        );


        /////سند القبض
        $transaction_id = $con->contract_id;
        $this->addBondWithJournal($transaction_id);
        $total_before_discount = $request->total_before_discount;

        $customer_for_inv = Customer::find($request->customer_id);

//        if ($customer_for_inv->cus_type->system_code == 538) {
//            /////الفاتوره
////            $this->addInvoiceWithJournal($transaction_id, $total_before_discount);
//        }

        $car_status = SystemCode::where('system_code', 123003)->where('company_group_id', $company->company_group_id)->first();
        $car->car_status_id = $car_status->system_code_id;
        $car->save();

        if ($request->the_total_value_of_adding_drivers > 0) {

            $bond_addition = new BondsController();
            $payment_method = SystemCode::where('system_code_id', $con->paymentMethodCode)->first();
//return $payment_method;
            $transaction_type = 44;

            $customer_id = $con->driver_id;
            $customer_type = 'customer';
//        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';
            $bond_vat_rate = $con->contract_vat_rate / 100;
            $bond_vat_amount = $bond_vat_rate * $request->the_total_value_of_adding_drivers;
            $total_amount = $request->the_total_value_of_adding_drivers + $bond_vat_amount;
            $bond_doc_type = SystemCode::where('system_code', 600002)
                ->where('company_group_id', $company->company_group_id)->first(); ////سائق اضافي
            $bond_ref_no = $con->contract_code;
            $bond_notes = ' سند اضافه عقد تاجير سائق اضافي';


            $bond_addition->addAdditionBond($payment_method, $transaction_type, $transaction_id, $customer_id, $customer_type,
                '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes,
                $bond_vat_amount, $bond_vat_rate);
        }

        if ($request->car_in_another_city > 0) {

            $bond_addition = new BondsController();
            $payment_method = SystemCode::where('system_code_id', $con->paymentMethodCode)->first();
//return $payment_method;
            $transaction_type = 44;

            $customer_id = $con->customer_id;
            $customer_type = 'customer';
//        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';
            $bond_vat_rate_c = $con->contract_vat_rate / 100;
            $bond_vat_amount_c = $bond_vat_rate_c * $request->car_in_another_city;
            $total_amount_c = $request->car_in_another_city + $bond_vat_amount_c;
            $bond_doc_type = SystemCode::where('system_code', 600003)
                ->where('company_group_id', $company->company_group_id)->first(); ////سائق اضافي
            $bond_ref_no = $con->contract_code;
            $bond_notes = 'سند اضافه عقد تاجير تسليم سياره في مدينه اخري';


            $bond_addition->addAdditionBond($payment_method, $transaction_type, $transaction_id, $customer_id, $customer_type,
                '', $total_amount_c, $bond_doc_type, $bond_ref_no, $bond_notes,
                $bond_vat_amount_c, $bond_vat_rate_c);


        }

        DB::commit();
        return redirect()->route('car-rent.contractComplete', $con->contract_id);
        //return back()->with(['success' => 'تم الاضافه']);
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $contract = CarRentContract::find($id);

        if (request()->ajax()) {
            $contract = CarRentContract::find(request()->contract_id);
            return response()->json(['data' => $contract]);
        }

        $attachments = Attachment::where('transaction_id', $contract->contract_id)->where('app_menu_id', 44)
            ->where('attachment_type', '!=', 2)->get();

        $photos_attachments = Attachment::where('transaction_id', $contract->contract_id)->where('app_menu_id', 44)
            ->where('attachment_type', '=', 2)->get();

        $attachment_types = SystemCode::where('sys_category_id', 11)->where('company_group_id', $company->company_group_id)->get();
        $notes = Note::where('transaction_id', $contract->contract_id)->where('app_menu_id', 44)->get();

        $bonds_cash = Bond::where('bond_ref_no', $contract->contract_code)->where('bond_type_id', 2)->latest()->get();
        $bonds_capture = Bond::where('bond_ref_no', $contract->contract_code)->where('bond_type_id', 1)->latest()->get();
        $bonds_addition = Bond::where('bond_ref_no', $contract->contract_code)->where('bond_type_id', 3)->latest()->get();
        $bonds_discount = Bond::where('bond_ref_no', $contract->contract_code)->where('bond_type_id', 4)->latest()->get();

//        return $contract->contract_id;
        $invoices = InvoiceHd::where('invoice_type', 14)
            ->whereHas('invoiceDetails', function ($query) use ($contract) {
                $query->where('invoice_reference_no', (string)$contract->contract_id);
            })->latest()->get();


//        $invoices = $invoices_hd->invoiceDetails->invoice_reference_no == $contract->contrct_id;
        $total_amount = array_sum($invoices->pluck('invoice_amount')->toArray());
        return view('CarRent.Contract.edit', compact('contract', 'attachments', 'attachment_types',
            'notes', 'bonds_cash', 'invoices', 'total_amount', 'bonds_addition', 'bonds_discount', 'bonds_capture',
            'id', 'photos_attachments'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch') ? session('branch') : auth()->user()->defaultBranch;
        //  return $request->all();
        $con = CarRentContract::find($id);
        $cont_status = SystemCode::where('system_code', 13604)
            ->where('company_group_id', $con->company->company_group_id)->first();

        $con->update([
            'odometerclosed' => $request->odometerclosed,
            'total_km' => $request->total_km,
            'total_km_count' => $request->total_km_count,
            'total_km_cost' => $request->total_km_cost,
            'total_hr_count' => $request->total_hr_count,
            'total_hour_cost' => $request->total_hour_cost,
            'closed_datetime' => Carbon::now(),
            'closed_user' => auth()->user()->user_id,
            'contract_status' => $cont_status->system_code_id,
            'contract_amount' => $request->contract_amount,
            'contract_net_amount' => $request->contract_net_amount,
            'days_count' => $request->days_count,
            'contract_vat_amout' => $request->contract_vat_amout,
            'contract_balance' => $request->total_due,

            'actualReturnBranchId' => $branch->branch_code,
        ]);

        $diff_amount = $con->contract_net_amount - $con->paid;


        $sys_code_status = SystemCode::where('system_code', 123001)->
        where('company_group_id', $company->company_group_id)->first(); //حالة السياره جاهزه

        $con->car->car_status_id = $sys_code_status->system_code_id;
        $con->car->odometer_start = $con->odometerclosed;
        $con->car->branch_id = $branch->branch_id;

        $con->car->save();

        $con->refresh();

        if ($request->add_bond_cash == 1) { /////سند الصرف
            if ($diff_amount < 0) {

                $bond_controller = new BondsController();
                $payment_method = $con->paymentMethod;
                $customer_id = $con->customer->customer_id;
                $customer_type = 'customer';
                $transaction_id = $con->contract_id;
                $bond_bank_id = '';
                $total_amount = $con->paid - $con->contract_net_amount;
                $bond_notes = 'سند صرف عقد تاجير سياره';

                $journal_category_id = 16;

                $bond_doc_type = SystemCode::where('system_code', 100001)
                    ->where('company_group_id', $con->company->company_group_id)
                    ->first();

                $bond_ref_no = $con->contract_code;
                $bond_account_id = $bond_doc_type->system_code_acc_id;
                $bond_car_id = $con->car_id;
                $j_add_date = Carbon::now();

                $bond = $bond_controller->addCashBond($payment_method, 44, $transaction_id, $customer_id, $customer_type,
                    $bond_bank_id, $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes, $bond_account_id,
                    0, 0, $bond_car_id, $j_add_date);


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


                $journal_notes = '  سند صرف عقد تاجير سياره رقم' . $bond->bond_code . 'سند الصرف رقم' . $bond->bond_code;
                $customer_notes = ' اضافه قيد سند صرف  للعميل عقد تاجير سياره رقم' . $bond->bond_code;
                $cash_notes = ' اضافه قيد سند صرف  عقد تاجير سياره رقم' . $bond->bond_code;

                $message = $journal_controller->AddCashJournal(56002, $customer_id, $bond_doc_type->system_code,
                    $total_amount, 0, $cc_voucher_id, $payment_method, $bank_id,
                    $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes, $j_add_date);


                $con->paid = $con->contract_net_amount;
                $con->save();


                if (isset($message)) {
                    return back()->with(['error' => $message]);
                }

            }
        }

        // last invoice create
//        $this->addInvoiceWithJournalWhenCloseContract($con->contract_id);

        $this->addInvoiceWithJournalWhenCloseContract($request, $id);

        if ($request->add_bond_capture) {
            return redirect('bonds-add/capture/create?contract_id=' . $con->contract_id);
        }

        DB::commit();

        return back()->with(['success' => 'تم اغلاق العقد']);
    }

    public function getSketchInfo($id, Request $request)
    {
        $car_contract = CarRentCars::find($id);
        $sketch_info = [];
        foreach ($request->col_key as $k => $col_key) {
            $sketch_info = [
                "type" => $col_key, "x" => $request->left_col[$k], "y" => $request->top_col[$k]
            ];

            $sketch_info = "[{\"type\":\"$col_key\",\"x\":$request->left_col[$k],\"y\":$request->left_col[$k]}";
        }

    }

    public function getPriceList()
    {
        ///customer_id , car_id
        $customer = Customer::find(request()->customer_id);

        if ($customer->cus_type->system_code == 538) { ///افراد
            $car = CarRentCars::where('car_id', request()->car_id)->first();
//            return request()->customer_id;
            $price_list_dts = CarPriceListDt::where('car_model_id', $car->model->car_rent_model_id)
                ->whereHas('priceListHd', function ($query) {
                    $query->where('rent_list_status', 1)
                        ->where('rent_list_start_date', '<', Carbon::now())
                        ->where('rent_list_end_date', '>', Carbon::now())
                        ->where('customer_id', request()->customer_id)
                        ->where('customer_type_id', SystemCode::where('system_code', 538)->first()->system_code_id);
                })->first();

            if (isset($price_list_dts)) {
                return response()->json(['data' => $price_list_dts]);
            } else {
                return response()->json(['message' => 'لا يوجد قائمه بالاسعار للعميل برجاء مراجعه البيانات']);
            }
        }

        if ($customer->cus_type->system_code == 539) { ///شركات

            $car = CarRentCars::where('car_id', request()->car_id)->first();

            $price_list_dts = CarPriceListDt::where('car_model_id', $car->model->car_rent_model_id)
                ->whereHas('priceListHd', function ($query) {
                    $query->where('rent_list_status', 1)
                        ->where('rent_list_start_date', '<', Carbon::now())
                        ->where('rent_list_end_date', '>', Carbon::now())
                        ->where('customer_id', request()->customer_id);
                })->first();

            if (isset($price_list_dts)) {
                return response()->json(['data' => $price_list_dts]);
            } else {
                return response()->json(['message' => 'لا يوجد قائمه بالاسعار للعميل برجاء مراجعه البيانات']);
            }


        }
    }

    public function getDifferenceDate()
    {
        $from_date = Carbon::parse(request()->contractStartDate);
        $end_date = Carbon::parse(request()->contractEndDate);

        if ($end_date < $from_date) {
            return response()->json(['status' => 500, 'message' => 'تاريخ النهايه قبل تاريخ البدايه']);
        }

        $rent_period = $end_date->diffInDays($from_date);

        return response()->json(['data' => $rent_period]);
    }


    public function getContractEndDate()
    {
        $from_date = Carbon::parse(request()->contractStartDate);
        $end_date = $from_date->addDays(request()->rent_period);
        return response()->json(['data' => $end_date->format('Y-m-d\TH:i')]);

    }

    public function getRentPolicyTaxRate()
    {
        $system_code_rent_policy = SystemCode::where('system_code_id', request()->rentPolicyId)->first();
        return response()->json(['data' => $system_code_rent_policy->system_code_tax_perc]);
    }

    public function contractComplete($id)
    {
        $contract = CarRentContract::find($id);
        return view('CarRent.Contract.contractComplete', compact('contract'));
    }

    public function addBondWithJournal($transaction_id)
    {

        // $bond_method_type_id payment method id from contract
        //$transaction_type id from application menu
        // $transaction_id contract id

        DB::beginTransaction();
        $contract = CarRentContract::find($transaction_id);
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond_controller = new BondsController();

        $payment_method = SystemCode::where('system_code_id', $contract->paymentMethodCode)->first();
//return $payment_method;
        $transaction_type = 44;

        $customer_id = $contract->customer_id;
        $customer_type = 'customer';
//        $bond_bank_id = $request->bond_bank_id ? $request->bond_bank_id : '';
        $total_amount = $contract->paid;
        $bond_doc_type = SystemCode::where('system_code', 100001)
            ->where('company_group_id', $company->company_group_id)->first();
        $bond_ref_no = $contract->contract_code;
        $bond_notes = 'سند قبض عقد تاجير';
        $bond = $bond_controller->addBond($payment_method, $transaction_type, $transaction_id, $customer_id,
            $customer_type, '', $total_amount, $bond_doc_type, $bond_ref_no, $bond_notes);

        //return $bond;

        $cost_center_id = 53;
        $cc_voucher_id = $bond->bond_id;
        //$payment_terms = SystemCode::where('system_code', 57001)->first();
        $journal_category_id = 8;


//        if ($request->bond_bank_id) {
//            $bank_id = $request->bond_bank_id;
//        } else {
//            $bank_id = '';
//        }

        $journal_notes = ' اضافه قيد سند قبض رقم' . $bond->bond_code;
        $customer_notes = ' اضافه قيد سند قبض  للعميل رقم' . $bond->bond_code;
        $cash_notes = ' اضافه قيد سند قبض  رقم' . $bond->bond_code;
        $journal_controller = new JournalsController();
        $message = $journal_controller->AddCaptureJournal(56002, $customer_id, $bond_doc_type,
            $total_amount, $cc_voucher_id, $payment_method, '',
            $journal_category_id, $cost_center_id, $journal_notes, $customer_notes, $cash_notes);

        if (isset($message)) {
            return back()->with(['error' => $message]);
        }

        DB::commit();
    }

    public function addInvoiceWithJournal($transaction_id, $total_before_discount)
    {
        DB::beginTransaction();

        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 73)->latest()->first();

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
                'app_menu_id' => 73,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $contract = CarRentContract::find($transaction_id);

        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'acc_period_id' => $account_period->acc_period_id,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => Carbon::now(),
            //'invoice_amount_b' => $request->invoice_amount - $request->invoice_vat_amount,
            'invoice_amount' => $contract->paid,
            'invoice_vat_rate' => $contract->contract_vat_rate,
            'invoice_vat_amount' => $contract->contract_vat_amout,
            'invoice_total_payment' => $contract->paid,
            'invoice_notes' => 'فاتوره تاجير سيارات',
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $contract->customer_id,
            'customer_name' => $contract->customer->customer_name_full_ar,
            'customer_address' => $contract->customer->customer_address_1,
            'customer_phone' => $contract->customer->customer_phone,
            'invoice_type' => 14, ///فاتوره التاجير
            'invoice_status' => 121004, //مسدده
            'invoice_is_payment' => 1
        ]);

        $invoice_item_2 = SystemCode::where('system_code', 28099)->where('company_group_id', $company->company_group_id)
            ->first();

        // when contract didnot have invoice before & have discount from price list.
        if ($contract->discount && $contract->invoiceDts()->count() <= 0) {
            $invoice_item = SystemCode::where('system_code', 580)->where('company_group_id', $company->company_group_id)
                ->first();

            $invoice_discount_total = ($contract->days_count * $contract->rentDayCost) * ($contract->discount / 100);

            $invoice_item_vat_amount = (($contract->days_count * $contract->rentDayCost) - $invoice_discount_total)
                * ($contract->contract_vat_rate / 100);

            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $invoice_item->system_code_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => $contract->days_count,
                'invoice_item_price' => $contract->rentDayCost,
                'invoice_item_vat_rate' => $contract->contract_vat_rate,
                'invoice_item_vat_amount' => $invoice_item_vat_amount,
                'invoice_discount_amount' => $contract->discount, // نسبة الخصم
                'invoice_discount_total' => $invoice_discount_total,//قيمة الخصم
                'invoice_total_amount' => ($contract->days_count * $contract->rentDayCost) + $invoice_item_vat_amount - $invoice_discount_total,
                'invoice_item_amount' => ($contract->days_count * $contract->rentDayCost) - $invoice_discount_total, //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
                'invoice_reference_no' => $contract->contract_id,
                'invoice_from_date' => Carbon::parse($contract->contractStartDate), //
                'invoice_to_date' => Carbon::parse($contract->contractEndDate), //
//              'invoice_reference_no' => $waybill->waybill_code
            ]);
        }

        $invoice_item_price = $total_before_discount - ($contract->days_count * $contract->rentDayCost);

        $invoice_item_vat_amount = (($contract->days_count * $contract->rentDayCost) - $invoice_discount_total)
            * ($contract->contract_vat_rate / 100);

        if ($invoice_item_price > 0) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $invoice_item_2->system_code_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => 1,
                'invoice_item_price' => $invoice_item_price,
                'invoice_item_vat_rate' => $contract->contract_vat_rate,
                'invoice_item_vat_amount' => $invoice_item_vat_amount,
                'invoice_discount_amount' => $contract->discount, // نسبة الخصم
                'invoice_discount_total' => ($contract->discount / 100) * $invoice_item_price,//قيمة الخصم
                'invoice_total_amount' => $invoice_item_price + $invoice_item_vat_amount - ($contract->discount / 100) * $invoice_item_price,
                'invoice_item_amount' => $invoice_item_price - (($contract->discount / 100) * $invoice_item_price), //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
                'invoice_reference_no' => $contract->contract_id,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now(),

//                'invoice_reference_no' => $waybill->waybill_code
            ]);
        }


        $journal_controller = new JournalsController();

        $total_amount = $invoice_hd->invoice_amount;
        $customer_id = $invoice_hd->customer_id;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $customer_notes = '  قيد علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $vat_notes = '  قيد ضريبه علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $sales_notes = '  قيد مشتريات علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $notes = '  قيد علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $items_id = [];
        $items_amount = [];

        $journal_controller->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id,
            $customer_notes, 46, $vat_notes, $sales_notes, 42,
            $items_id, $items_amount, $notes);

        DB::commit();
    }

    public function addInvoiceWithJournalWhenCloseContract($request, $contract_id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 73)->latest()->first();

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
                'app_menu_id' => 73,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $contract = CarRentContract::find($contract_id);

        if (count($contract->invoiceDts) > 0) {
            $invoice_dt = InvoiceDt::where('invoice_reference_no', $contract->contract_id)->latest()->first();
            $date = Carbon::parse($invoice_dt->invoice_to_date)->addDays(1);
            $from_date = $date;
        } else {
            $date = Carbon::parse($contract->contractStartDate);
            $from_date = Carbon::parse($contract->contractStartDate)->format('d-m-Y');
        }

        $to_date = Carbon::now();

        $days_count = $date->diffInDays($to_date);

        $contract_days_price = $days_count * $contract->rentDayCost;

        $total_km_cost = $request->total_km_cost;

        $total_hour_cost = $request->total_hour_cost;


        $contract_vat = (($contract_days_price + $total_km_cost + $total_hour_cost) - $request->discount) * ($contract->contract_vat_rate / 100);

        $total = $contract_days_price + $total_km_cost + $total_hour_cost - $request->discount + $contract_vat
            + $request->contract_total_add;

        $invoice_item = SystemCode::where('system_code', 580)->where('company_group_id', $company->company_group_id)
            ->first();


        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'acc_period_id' => $account_period->acc_period_id,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => Carbon::now(),
            //'invoice_amount_b' => $request->invoice_amount - $request->invoice_vat_amount,
            'invoice_amount' => $contract_days_price + $total_km_cost + $total_hour_cost + $request->contract_total_add,
            'invoice_vat_rate' => $contract->contract_vat_rate,
            'invoice_vat_amount' => $contract_vat,
            'invoice_discount_total' => $request->discount,
            'invoice_total' => $total,
            'invoice_total_payment' => $contract->invoice_total_payment,
            'invoice_notes' => 'فاتوره تاجير سيارات',
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $contract->customer_id,
            'customer_name' => $contract->customer->customer_name_full_ar,
            'customer_address' => $contract->customer->customer_address_1,
            'customer_phone' => $contract->customer->customer_phone,
            'invoice_type' => 14, ///فاتوره التاجير
            'invoice_status' => 121003, //فاتوره
            'invoice_is_payment' => 0
        ]);


        $discount_1 = $contract_days_price * ($contract->discount / 100);
        $vat_amount_1 = ($contract_days_price - $discount_1) * ($contract->contract_vat_rate / 100);
        $total_1 = $contract_days_price - $discount_1 + $vat_amount_1;

        InvoiceDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'invoice_id' => $invoice_hd->invoice_id,
            'invoice_item_id' => $invoice_item->system_code_id,
            'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
            'invoice_item_quantity' => $days_count,
            'invoice_item_price' => $contract->rentDayCost,
            'invoice_item_vat_rate' => $contract->contract_vat_rate,
            'invoice_discount_amount' => $contract->discount, // نسبه الخصم
            'invoice_item_vat_amount' => $vat_amount_1,
            'invoice_discount_total' => $discount_1,//قيمة الخصم
            'invoice_total_amount' => $total_1,
            'invoice_item_amount' => $contract_days_price - $discount_1, //القيمه بدون الضريبه
            'created_user' => auth()->user()->user_id,
            'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
            'invoice_reference_no' => $contract->contract_id,
            'invoice_from_date' => $from_date,
            'invoice_to_date' => $to_date,
        ]);

        if ($total_km_cost > 0) {
            $discount_2 = $total_km_cost * ($contract->discount / 100);
            $vat_amount_2 = ($total_km_cost - $discount_2) * ($contract->contract_vat_rate / 100);
            $total_2 = $total_km_cost - $discount_2 + $vat_amount_2;
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $invoice_item->system_code_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => $request->total_km_count,
                'invoice_item_price' => $request->extraKmCost,
                'invoice_item_vat_rate' => $contract->contract_vat_rate,
                'invoice_discount_amount' => $contract->discount, // نسبه الخصم
                'invoice_item_vat_amount' => $vat_amount_2,
                'invoice_discount_total' => $discount_2,//قيمة الخصم
                'invoice_total_amount' => $total_2,
                'invoice_item_amount' => $total_km_cost - $discount_2, //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
                'invoice_reference_no' => $contract->contract_id,
                'invoice_from_date' => $from_date,
                'invoice_to_date' => $to_date,
            ]);
        }


        if ($total_hour_cost > 0) {

            $discount_3 = $total_hour_cost * ($contract->discount / 100);
            $vat_amount_3 = ($total_hour_cost - $discount_3) * ($contract->contract_vat_rate / 100);
            $total_3 = $total_hour_cost - $discount_3 + $vat_amount_3;

            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $invoice_item->system_code_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => $request->total_hr_count,
                'invoice_item_price' => $request->rentHourCost,
                'invoice_item_vat_rate' => $contract->contract_vat_rate,
                'invoice_discount_amount' => $contract->discount, // نسبه الخصم
                'invoice_item_vat_amount' => $vat_amount_3,
                'invoice_discount_total' => $discount_3,//قيمة الخصم
                'invoice_total_amount' => $total_3,
                'invoice_item_amount' => $total_hour_cost - $discount_3, //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
                'invoice_reference_no' => $contract->contract_id,
                'invoice_from_date' => $from_date,
                'invoice_to_date' => $to_date,
            ]);
        }

        $bonds_addition = Bond::where('bond_ref_no', $contract->contract_code)->where('bond_type_id', 3)->latest()->get();

        foreach ($bonds_addition as $bond_addition) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $bond_addition->bond_doc_type,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => 1,
                'invoice_item_price' => $bond_addition->bond_amount_debit,
                'invoice_item_vat_rate' => 0,
                'invoice_item_vat_amount' => 0,
                'invoice_discount_amount' => 0, // نسبة الخصم
                'invoice_discount_total' => 0,//قيمة الخصم
                'invoice_total_amount' => $bond_addition->bond_amount_debit,
                'invoice_item_amount' => $bond_addition->bond_amount_debit, //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
                'invoice_reference_no' => $contract->contract_id,
                'invoice_from_date' => $from_date,
                'invoice_to_date' => $to_date,
            ]);
        }

        $bonds_discount = Bond::where('bond_ref_no', $contract->contract_code)->where('bond_type_id', 4)->latest()->get();

        foreach ($bonds_discount as $bond_discount) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $bond_discount->bond_doc_type,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => 1,
                'invoice_item_price' => ($bond_discount->bond_amount_debit - $bond_discount->bond_vat_amount) * (-1), ///القيمه بدون الضريبه
                'invoice_item_vat_rate' => $bond_discount->bond_vat_rate,
                'invoice_item_vat_amount' => $bond_discount->bond_vat_amount * (-1),
                'invoice_discount_amount' => 0, // نسبه الخصم
                'invoice_discount_total' => 0,//قيمة الخصم
                'invoice_total_amount' => ($bond_discount->bond_amount_debit) * (-1),
                'invoice_item_amount' => ($bond_discount->bond_amount_debit - $bond_discount->bond_vat_amount) * (-1), //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
                'invoice_reference_no' => $contract->contract_id,
                'invoice_from_date' => $from_date,
                'invoice_to_date' => $to_date,
            ]);
        }

        foreach ($contract->carAccidents->where('accidentType.system_code', '!=', 129002) as $car_accident) {
            InvoiceDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'invoice_id' => $invoice_hd->invoice_id,
                'invoice_item_id' => $car_accident->car_accident_type_id,
                'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
                'invoice_item_quantity' => 1,
                'invoice_item_price' => $car_accident->car_accident_amount, ///القيمه بدون الضريبه
                'invoice_item_vat_rate' => 0,
                'invoice_item_vat_amount' => 0,
                'invoice_discount_amount' => 0, // نسبه الخصم
                'invoice_discount_total' => 0,//قيمة الخصم
                'invoice_total_amount' => $car_accident->car_accident_amount,
                'invoice_item_amount' => $car_accident->car_accident_amount, //القيمه بدون الضريبه
                'created_user' => auth()->user()->user_id,
                'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
                'invoice_reference_no' => $contract->contract_id,
                'invoice_from_date' => Carbon::now(),
                'invoice_to_date' => Carbon::now(),
            ]);
        }

        return $invoice_hd;

////////////////////////

        $journal_controller = new JournalsController();

        $total_amount = $invoice_hd->invoice_amount;
        $customer_id = $invoice_hd->customer_id;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $customer_notes = '  قيد علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $vat_notes = '  قيد ضريبه علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $sales_notes = '  قيد مشتريات علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $notes = '  قيد علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $items_id = [];
        $items_amount = [];

        $journal_controller->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id,
            $customer_notes, 46, $vat_notes, $sales_notes, 42,
            $items_id, $items_amount, $notes);


    }

    public function addInvoiceWithJournalMonthly($transaction_id, $total_net, $days_count, $from_date, $to_date)
    {
        DB::beginTransaction();

        $company = session('company') ? session('company') : auth()->user()->company;
        $last_invoice_reference = CompanyMenuSerial::where('company_id', $company->company_id)
            ->where('app_menu_id', 73)->latest()->first();

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
                'app_menu_id' => 73,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number,
                'created_user' => auth()->user()->user_id
            ]);

        }

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $contract = CarRentContract::find($transaction_id);

        $invoice_item_price = $contract->rentDayCost;

        $invoice_item_discount_amount = ($contract->discount / 100) * $invoice_item_price;

        $invoice_item_vat_amount = ($invoice_item_price - $invoice_item_discount_amount)
            * ($contract->contract_vat_rate / 100);

//        $total = $invoice_item_price + $invoice_item_vat_amount - $invoice_item_discount_amount;
        $invoice_item = SystemCode::where('system_code', 580)->where('company_group_id', $company->company_group_id)
            ->first();

        $invoice_hd = InvoiceHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'acc_period_id' => $account_period->acc_period_id,
            'invoice_date' => Carbon::now(),
            'invoice_due_date' => Carbon::now(),
            //'invoice_amount_b' => $request->invoice_amount - $request->invoice_vat_amount,
            'invoice_amount' => $total_net,
            'invoice_vat_rate' => $contract->contract_vat_rate,
            'invoice_vat_amount' => $invoice_item_vat_amount,
            'invoice_total_payment' => $contract->invoice_total_payment,
            'invoice_notes' => 'فاتوره تاجير سيارات',
            'invoice_no' => $string_number,
            'created_user' => auth()->user()->user_id,
            'branch_id' => session('branch')['branch_id'],
            'customer_id' => $contract->customer_id,
            'customer_name' => $contract->customer->customer_name_full_ar,
            'customer_address' => $contract->customer->customer_address_1,
            'customer_phone' => $contract->customer->customer_phone,
            'invoice_type' => 14, ///فاتوره التاجير
            'invoice_status' => 121003, //فاتوره
            'invoice_is_payment' => 0
        ]);

        InvoiceDt::create([

            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'invoice_id' => $invoice_hd->invoice_id,
            'invoice_item_id' => $invoice_item->system_code_id,
            'invoice_item_unit' => SystemCode::where('system_code', 93)->first()->system_code_id,
            'invoice_item_quantity' => $days_count,
            'invoice_item_price' => $invoice_item_price,
            'invoice_item_vat_rate' => $contract->contract_vat_rate,
            'invoice_item_vat_amount' => $invoice_item_vat_amount,
            'invoice_discount_amount' => $contract->discount, // نسبة الخصم
            'invoice_discount_total' => $invoice_item_discount_amount,//قيمة الخصم
            'invoice_total_amount' => $total_net,
            'invoice_item_amount' => $invoice_item_price - $invoice_item_discount_amount, //القيمه بدون الضريبه
            'created_user' => auth()->user()->user_id,
            'invoice_item_notes' => 'فاتوره تاجير سيارات' . ' ' . $contract->contract_code,
            'invoice_reference_no' => $contract->contract_id,
            'invoice_from_date' => $from_date,
            'invoice_to_date' => Carbon::parse($to_date),
        ]);


        $journal_controller = new JournalsController();

        $total_amount = $invoice_hd->invoice_amount;
        $customer_id = $invoice_hd->customer_id;
        $cc_voucher_id = $invoice_hd->invoice_id;
        $customer_notes = '  قيد علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $vat_notes = '  قيد ضريبه علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $sales_notes = '  قيد  فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $notes = '  قيد علي فاتوره المبيعات رقم ' . $invoice_hd->invoice_no;
        $items_id = [];
        $items_amount = [];

        $journal_controller->addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id,
            $customer_notes, 46, $vat_notes, $sales_notes, 42,
            $items_id, $items_amount, $notes);

        DB::commit();
    }

    public function getContract()
    {
        $contract = CarRentContract::find(request()->contract_id);
        return response()->json(['data' => $contract]);
    }

    public function SaveContract(Request $request)
    {

        $carContract = CarRentContract::where('contract_id', '=', $request->id)->first();
        $contractRequest = new TajeerAPIController();
        $data = $contractRequest->SaveContract($carContract);
        $contract_web_status = SystemCode::where('company_id', '=', $carContract->company_id)->where('system_code', '=', 13607)->first();
        if ($data['statusCode'] == 200) {
            $contract_web_id = $data['body']->id;
            $contractNumber = $data['body']->contractNumber;
            $token = $data['body']->token;

            $carContract->http_status = 200;
            $carContract->contract_web_id = $contract_web_id;
            $carContract->contract_Number = $contractNumber;
            $carContract->contract_Token = $token;
            $carContract->status_web_id = $contract_web_status->system_code_id;
            $carContract->http_response = json_encode($data['body']);

            $carContract_update = $carContract->update();

            if (!$carContract_update) {
                return \Response::json(['success' => false, 'msg' => ' 1حدثت مشكلة']);
            }

            return \Response::json(['success' => true, 'msg' => 'تم التوثيق بنجاح']);
        }

        // $carContract->http_status = $data['statusCode'];
        // $carContract->status_web_id = 2;
        // $carContract->http_response = json_encode($data['body']);
        // $carContract_update = $carContract->update();
        // //return $data['body'];
        // if (!$carContract_update) {
        //     return \Response::json(['success' => false, 'msg' => 'لم تكتمل عملية التوثيق']);
        // }

        return \Response::json(['success' => false, 'msg' => '3حدثت مشكلة']);
    }

    public function CreateContractWeb(Request $request)
    {
        $carContract = CarRentContract::where('contract_id', '=', $request->id)->first();
        $contract_web_status = SystemCode::where('company_id', '=', $carContract->company_id)->where('system_code', '=', 13608)->first();
        $carContract->status_web_id = $contract_web_status->system_code_id;
        $carContract_update = $carContract->update();
        if (!$carContract_update) {
            return \Response::json(['success' => false, 'msg' => ' 1حدثت مشكلة']);
        }
        return \Response::json(['success' => true, 'msg' => 'تم تحديث حالة العقد الى مبرم']);
    }

    public function getContractPDF($id)
    {
        $carContract = CarRentContract::where('contract_id', '=', $id)->first();
        $printContract = new TajeerAPIController();
        $data = $printContract->getContractPDF($carContract);

        if ($data['statusCode'] == 200) {
            $file_name = 'Contract-' . $carContract->contract_code . '.pdf';
            return response()->make($data['body'], 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $file_name . '"'
            ]);
        }

        if ($data['statusCode'] == 400) {
            return 'حدث مشكلة';
            return \Response::json(['error' => true, 'msg' => 'حدث مشكلة']);
        }
    }

    public function getSummarizedContractPDF($id)
    {
        $carContract = CarRentContract::where('contract_id', '=', $id)->first();
        $printContract = new TajeerAPIController();
        $data = $printContract->getSummarizedContractPDF($carContract);

        if ($data['statusCode'] == 200) {
            $file_name = 'Contract-' . $carContract->contract_code . '.pdf';
            return response()->make($data['body'], 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $file_name . '"'
            ]);
        }

        if ($data['statusCode'] == 400) {
            return 'حدث مشكلة';
            return \Response::json(['error' => true, 'msg' => 'حدث مشكلة']);
        }
    }

    public function CancelContract(Request $request)
    {
        $carContract = CarRentContract::where('contract_id', '=', $request->id)->first();
        $contractRequest = new TajeerAPIController();
        $data = $contractRequest->CancelContract($carContract);
        //info($data);
        if ($data['statusCode'] == 200) {

            $contract_web_status = SystemCode::where('company_id', '=', $carContract->company_id)->where('system_code', '=', 13609)->first();
            $carContract->status_web_id = $contract_web_status->system_code_id;
            $carContract_update = $carContract->update();
            if (!$carContract_update) {
                return \Response::json(['success' => false, 'msg' => ' 1حدثت مشكلة']);
            }
            return \Response::json(['success' => true, 'msg' => 'تم الغاء العقد']);
        }

        return \Response::json(['success' => false, 'msg' => '3حدثت مشكلة']);

    }

    public function CloseContract(Request $request)
    {
        $carContract = CarRentContract::where('contract_id', '=', $request->id)->first();
        $contractRequest = new TajeerAPIController();
        $data = $contractRequest->CloseContract($carContract);
        //info($data);
        if ($data['statusCode'] == 200) {
            $contract_web_status = SystemCode::where('company_id', '=', $carContract->company_id)->where('system_code', '=', 13604)->first();
            $carContract->status_web_id = $contract_web_status->system_code_id;
            $carContract_update = $carContract->update();
            if (!$carContract_update) {
                return \Response::json(['success' => false, 'msg' => ' 1حدثت مشكلة']);
            }
            return \Response::json(['success' => true, 'msg' => 'تم اغلاق العقد']);
        }

        return \Response::json(['success' => false, 'msg' => '3حدثت مشكلة']);

    }

    public function SuspendContract(Request $request)
    {
        $carContract = CarRentContract::where('contract_id', '=', $request->id)->first();
        $contractRequest = new TajeerAPIController();
        $data = $contractRequest->SuspendContract($carContract);
        info($data);
        if ($data['statusCode'] == 200) {
            $contract_web_status = SystemCode::where('company_id', '=', $carContract->company_id)->where('system_code', '=', 13602)->first();
            $carContract->status_web_id = $contract_web_status->system_code_id;
            $carContract_update = $carContract->update();
            if (!$carContract_update) {
                return \Response::json(['success' => false, 'msg' => ' 1حدثت مشكلة']);
            }
            return \Response::json(['success' => true, 'msg' => 'تم تعليق العقد']);
        }

        return \Response::json(['success' => false, 'msg' => '3حدثت مشكلة']);

    }

    public function UpdateStatusAndBalance()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
//        $contract_statues_close = SystemCode::where('system_code', 13604)
//            ->where('company_id', $company->company_id)->first();
        $contract_statues_late = SystemCode::where('system_code', 13603)
            ->where('company_id', $company->company_id)->first();
        $contracts = CarRentContract::where('company_group_id', $company->company_group_id)
            ->whereNull('odometerclosed')->get(); // ->whereDate('contractEndDate', '<', now())

        foreach ($contracts as $contract) {
            $TotalDailyCost = $contract->actual_days_count * $contract->rentDayCost;
            $TotalVat = ($contract->actual_days_count * $contract->rentDayCost) * ($contract->contract_vat_rate / 100);
            $contract_net_amount = $TotalDailyCost + $TotalVat - $contract->contract_total_discount + $contract->contract_total_add;
//            $total_km_cost = $contract->total_km_count * $contract->extraKmCost;
//            $total_hour_cost = $contract->rentHourCost * $contract->total_hr_count;
            $contract->update([
                'contract_status' => $contract_statues_late->system_code_id,
                'contract_balance' => $contract_net_amount - $contract->paid,
                'contract_amount' => $TotalDailyCost,
                'contract_net_amount' => $contract_net_amount,
                'days_count' => $contract->actual_days_count,
//                'contractEndDate' => now(),
            ]);

        }
        return back()->with(['success' => __('messages.updated_successfully')]);
    }

    public function makeInvvoices(Request $request)
    {
        try {
            DB::beginTransaction();
            $company = session('company') ? session('company') : auth()->user()->company;
            $contracts = CarRentContract::where('company_group_id', $company->company_group_id)
                ->whereNull('odometerclosed')
                ->whereMonth('contractStartDate', '<=', $request->month)
                ->whereDoesntHave('invoiceDts', function ($q) use ($request) {
                    $q->whereMonth('invoice_to_date', '=', $request->month);
                })
                ->get();


            $days_end = Carbon::now()->month($request->month)->daysInMonth;

            $full_date = $days_end . '-' . $request->month . '-' . Carbon::now()->format('Y');

            foreach ($contracts as $contract) {
                if (count($contract->invoiceDts) > 0) {
                    $invoice_dt = InvoiceDt::where('invoice_reference_no', $contract->contract_id)->latest()->first();
                    $date = Carbon::parse($invoice_dt->invoice_to_date)->addDays(1);
                    $from_date = $date;
                } else {
                    $date = Carbon::parse($contract->contractStartDate);
                    $from_date = Carbon::parse($contract->contractStartDate)->format('d-m-Y');
                }
                $days_count = $date->diffInDays($full_date);
                $to_date = $full_date;

                $TotalDailyCost = $days_count * $contract->rentDayCost;

                $discount_amount = $TotalDailyCost * ($contract->discount / 100);

                $total_with_discount = $TotalDailyCost - $discount_amount;

                $this->addInvoiceWithJournalMonthly($contract->contract_id, $total_with_discount, $days_count, $from_date, $to_date);


                $contract_statues_late = SystemCode::where('system_code', 13603)
                    ->where('company_group_id', $company->company_group_id)->first();

//                $total_km_cost = $contract->total_km_count * $contract->extraKmCost;
//
//                $total_hour_cost = $contract->rentHourCost * $contract->total_hr_count;
                $contract->update([
                    'contract_status' => Carbon::now()->gt($contract->contractEndDate) ? $contract_statues_late->system_code_id : $contract->contract_status,
                    'days_count' => $contract->actual_days_count,
                ]);
            }
            DB::commit();
            return back()->with(['success' => __('messages.updated_successfully')]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with(['error' => __('messages.wrong_data')]);
        }
    }

    public function storePhoto(Request $request)
    {
        $img = $request->image;
        $file = $this->getPhoto($img);

        Attachment::create([
            'attachment_name' => 'waybill-car',
            'attachment_type' => 2,
            'issue_date' => Carbon::now(),
//            'expire_date' => $request->expire_date,
//            'issue_date_hijri' => $request->issue_date_hijri,
//            'expire_date_hijri' => $request->expire_date_hijri,
//            'copy_no' => $request->copy_no,
            'attachment_file_url' => $file,
            'attachment_data' => Carbon::now(),
            'transaction_id' => $request->contract_id,
            'app_menu_id' => 44,
            'created_user' => auth()->user()->user_id,
        ]);

        return back()->with(['success' => 'تم اضافه الصوره']);
    }

    public function getPhoto($photo)
    {
        $name = rand(11111, 99999) . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path("RentContract"), $name);
        return $name;
    }
}
