<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\AccounPeriod;
use App\Models\Bond;
use App\Models\CarRentContract;
use App\Models\CompanyMenuSerial;
use App\Models\Customer;
use App\Models\InvoiceDt;
use App\Models\InvoiceHd;
use App\Models\JournalDt;
use App\Models\JournalHd;
use App\Models\JournalType;
use App\Models\MaintenanceCard;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\StoreAccBranch;
use App\Models\SystemCode;
use App\Models\TripHd;
use App\Models\Trucks;
use App\Models\WaybillHd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalsController extends Controller
{
    //سند اقبض
    public function AddCaptureJournal($account_type, $customer_id, $doc_type, $amount_debit, $cc_voucher_id,
                                      $payment_method, $bank_id, $journal_category_id, $cost_center_id,
                                      $journal_notes, $customer_notes, $sales_notes)
    {
        ////$account_type نوع الحساب من ال system code ( system code ابعت ال)
        /// $doc_type انواع الايرادات(system code ابعت ال)
        /// $customer_id (id بتاع العميل سواء مورد او موظف او عميل)
        /// $cc_voucher_id (السند id )
        /// $payment_method (object from system code)
        /// $bank_id (id of bank if exists)
        $company = session('company') ? session('company') : auth()->user()->company;

        \DB::beginTransaction();


        $journal_type = SystemCode::where('system_code', 803)
            ->where('company_group_id', $company->company_group_id)->first();
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();
        if (!isset($journal_category)) {
            return 'لا يوجد حساب للايراد';
        }
//        dd($journal_category_id,$journal_category);
        $string_number_journal = $this->getSerial($journal_type);

        if ($cost_center_id == 53) {
            $bond = Bond::where('bond_id', $cc_voucher_id)->first();
        }

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => Carbon::now(),
            'journal_hd_credit' => $amount_debit,
            'journal_hd_debit' => $amount_debit,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes
        ]);

        //return $payment_method;
        ////////////الطرف الاول
        ///الفرع
        /////نقدي وفيزا
        //  return $payment_method;

        if ($payment_method->system_code_acc_id) {
            $account_id_1 = $payment_method->system_code_acc_id;
        } else {
            return 'لا يوجد رقم حساب لطريقه السداد';
        }

        $cost_center_type_id = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ///فرع

        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_1,
            'journal_dt_debit' => $amount_debit,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_debit,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $sales_notes
        ]);


        /////الطرف الثاني
        ////عميل
        if ($account_type == 56002) {
            $cc_customer_id = $customer_id;
        }
        ///مورد
        if ($account_type == 56001) {
            $cc_supplier_id = $customer_id;
        }
        //// موظف
        if ($account_type == 56003) {
            $cc_employee_id = $customer_id;
        }

        /////مورد او عميل
        if ($account_type == 56002 || $account_type == 56001) {
            $cost_center_type_id_2 = SystemCode::where('system_code', $account_type)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
            $account_id_2 = Customer::where('customer_id', $customer_id)->first()->customer_account_id;
        } else {
            $account_id_2 = SystemCode::where('system_code', $doc_type)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;
        }

        if (!isset($account_id_2)) {
            return 'لا يوجد رقم حساب مدرج';
        }

        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_2,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_debit,
            'journal_dt_balance' => $amount_debit,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
            'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
            'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
            'cost_center_type_id' => $cost_center_type_id_2,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $customer_notes
        ]);
        $bond->journal_hd_id = $journal_hd->journal_hd_id;
        $bond->save();

        \DB::commit();

    }


    public function updateCaptureJournal($amount_debit, $cc_voucher_id, $cost_center_id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        if ($cost_center_id == 53) {
            $bond = Bond::where('bond_id', $cc_voucher_id)->first();

            if ($bond->bond_method_type == 57001 || $bond->bond_method_type == 57002 || $bond->bond_method_type == 57005 || $bond->bond_method_type == 57006
                || $bond->bond_method_type == 57003 || $bond->bond_method_type == 57004) {
                $bond_method = SystemCode::where('system_code', $bond->bond_method_type)
                    ->where('company_group_id', $company->company_group_id)->first();
                $account_id_1 = $bond_method->system_code_acc_id;
            }

            $bond->journalCapture->update([
                'journal_user_update_id' => auth()->user()->user_id,
                'journal_hd_credit' => $amount_debit,
                'journal_hd_debit' => $amount_debit,
                'journal_notes' => $bond->bond_notes
            ]);

            $bond->journalCapture->journalDetails[0]->update([
                'journal_dt_debit' => $amount_debit,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $amount_debit,
                'account_id' => $account_id_1,
            ]);

            $bond->journalCapture->journalDetails[1]->update([
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_debit,
                'journal_dt_balance' => $amount_debit,
            ]);
        }

        // return $bond->journalCapture;
    }


//////////////////////////////////////////////////

    //سند  الصرف approved
    public function AddCashApprovJournal($account_type, $customer_id, $doc_type, $amount_total, $vat_amount, $cc_voucher_id,
                                         $payment_method, $bank_id, $journal_category_id, $cost_center_id, $journal_notes,
                                         $customer_notes, $cash_notes, $j_add_date)
    {
        ////$account_type نوع الحساب من ال system code ( system code ابعت ال)
        /// $doc_type انواع المصروفات(system code ابعت ال)
        /// $customer_id (id بتاع العميل سواء مورد او موظف او عميل)
        /// $cc_voucher_id (للسند id )
        ///  (object from system code)
        /// $bank_id (id of bank if exists)
        /// cost_center_id (id from application menu)
        /// $payment_method

        $company = session('company') ? session('company') : auth()->user()->company;
        \DB::begintransaction();
        $bond = Bond::find($cc_voucher_id);
        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_type = SystemCode::where('system_code', 804)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => $bond->branch_id,
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $j_add_date,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes,
        ]);

        ////الطرف الاول (من)
        $account_type_system_code = SystemCode::where('system_code', $account_type)
            ->where('company_group_id', $company->company_group_id)->first();///للطرف الاول فقط

        $cost_center_type_id = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ///فرع
        //


        if ($journal_category_id == 12) {
            if ($bond->transaction_type == 88) {
                $waybill_car = WaybillHd::find($bond->transaction_id);
            }

            ////في حاله بوليصه السياره سند الصرف
            if (isset($waybill_car)) {
                if ($waybill_car->status->system_code == 41005) {
                    $account_id_2 = $waybill_car->customer->customer_account_id;
                    $cc_customer_id = $waybill_car->customer->customer_id;
                    $account_type_system_code = SystemCode::where('system_code', 56002)
                        ->where('company_group_id', $company->company_group_id)->first();
                } else {
                    $account_id_2 = JournalType::where('journal_types_code', $journal_category_id)
                        ->where('company_group_id', $company->company_group_id)->first()->account_id_debit;
                    $cc_car_id = $customer_id;
                    $account_type_system_code = SystemCode::where('system_code', 56004)
                        ->where('company_group_id', $company->company_group_id)->first();
                }
            } else {
                $account_id_2 = JournalType::where('journal_types_code', $journal_category_id)
                    ->where('company_group_id', $company->company_group_id)->first()->account_id_debit;
                $cc_car_id = $customer_id;
                $account_type_system_code = SystemCode::where('system_code', 56004)
                    ->where('company_group_id', $company->company_group_id)->first();

            }
        } else {
            ////عميل
            if ($account_type == 56002) {
                $cc_customer_id = $customer_id;
            }
            ///مورد
            if ($account_type == 56001) {
                $cc_supplier_id = $customer_id;
            }
            //// موظف
            if ($account_type == 56003) {
                $cc_employee_id = $customer_id;
            }

            ////فرع
            if ($account_type == 56005) {
                $cc_branch_id = $customer_id;
            }

            ////سياره
            if ($account_type == 56004) {
                $cc_car_id = $customer_id;
            }

            $account_id_2 = SystemCode::where('system_code', $doc_type)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;

            /////مورد او عميل
            if ($account_type == 56002 || $account_type == 56001) {
                $account_id_2 = Customer::where('customer_id', $customer_id)->first()->customer_account_id;
            }


            if ($journal_category_id == 51 && $journal_category_id == 52) { ////سياره
                $account_id_2 = JournalType::where('journal_types_code', $journal_category_id)
                    ->where('company_group_id', $company->company_group_id)->first()->account_id_credit;
            }

        }

        if ($journal_category_id == 51) {
            $total_amount = $amount_total - $vat_amount;
        }


        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => $bond->branch_id,
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category_id == 51 ? $journal_category->account_id_debit : $account_id_2,
            'journal_dt_debit' => $journal_category_id == 51 ? $total_amount : $amount_total - $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total - $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
            'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
            'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
            'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
            'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
            // 'cc_branch_id' => $request->cc_branch_id[$k] ? $request->cc_branch_id[$k] : null,
            'cost_center_type_id' => $account_type_system_code ? $account_type_system_code->system_code_id : null,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $customer_notes,
            'journal_dt_date' => $j_add_date,
        ]);


        if ($vat_amount > 0) {
            if ($journal_category_id == 51) { ///في حاله الرحيله طرف الضريبه (الي)
                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => $bond->branch_id,
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $company->co_vat_paid,
                    'journal_dt_debit' => $vat_amount,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_branch_id' => $bond->branch_id,
                    'cost_center_type_id' => $cost_center_type_id,
                    'cost_center_id' => $cost_center_id,////from application menu
                    'cc_voucher_id' => $cc_voucher_id,
                    'journal_dt_notes' => $customer_notes,
                    'journal_dt_date' => $j_add_date,
                ]);
            } else {
                /////حساب ضريبه مدفوعه(من)

                if ($doc_type == 562) {
                    $vat_account_id = $company->co_vat_collect;
                } else {
                    $vat_account_id = $company->co_vat_paid;
                }

                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => $bond->branch_id,
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $vat_account_id,
                    'journal_dt_debit' => $vat_amount,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_branch_id' => $bond->branch_id,
                    'cost_center_type_id' => $cost_center_type_id,
                    'cost_center_id' => $cost_center_id,////from application menu
                    'cc_voucher_id' => $cc_voucher_id,
                    'journal_dt_notes' => $customer_notes,
                    'journal_dt_date' => $j_add_date,
                ]);
            }
        }


        ////////////الطرف الثالث(الي)
        ///الفرع
        /////نقدي وفيزا
        if ($payment_method->system_code_acc_id) {
            $account_id_1 = $payment_method->system_code_acc_id;
        } else {
            return 'لا يوجد حساب لطريقه السداد';
        }


        if ($journal_category_id == 51) {
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $bond->branch_id,
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $account_id_1,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total,
                'journal_dt_balance' => $amount_total,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_branch_id' => $bond->branch_id,
                'cost_center_type_id' => $cost_center_type_id,
                'cost_center_id' => $cost_center_id,////from application menu
                'cc_voucher_id' => $cc_voucher_id,
                'journal_dt_notes' => $cash_notes,
                'journal_dt_date' => $j_add_date,
            ]);
        } else {
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => $bond->branch_id,
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $account_id_1,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total,
                'journal_dt_balance' => $amount_total,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_branch_id' => $bond->branch_id,
                'cost_center_type_id' => $cost_center_type_id,
                'cost_center_id' => $cost_center_id,////from application menu
                'cc_voucher_id' => $cc_voucher_id,
                'journal_dt_notes' => $cash_notes,
                'journal_dt_date' => $j_add_date,
            ]);
        }

        if ($bond->transaction_type == 104 && $payment_method->system_code == 57006) {

            $cc_car_id = TripHd::where('trip_hd_id', $bond->transaction_id)->first()->truck_id;
            $cost_center_type_id = SystemCode::where('system_code', 56004)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

            $journal_hd->journalDetails[0]->update([
                'cc_branch_id' => null,
                'cc_car_id' => $cc_car_id,
                'cost_center_type_id' => $cost_center_type_id,
            ]);


            if (isset($journal_hd->journalDetails[2])) {
                $journal_hd->journalDetails[2]->update([
                    'cc_branch_id' => null,
                    'cc_car_id' => $cc_car_id,
                    'cost_center_type_id' => $cost_center_type_id,
                ]);
            } else {
                $journal_hd->journalDetails[1]->update([
                    'cc_branch_id' => null,
                    'cc_car_id' => $cc_car_id,
                    'cost_center_type_id' => $cost_center_type_id,
                ]);
            }
        }

        if ($cost_center_id == 54 || $cost_center_id == 81) {
            $bond = Bond::where('bond_id', $cc_voucher_id)->first();
            $bond->journal_hd_id = $journal_hd->journal_hd_id;
            $bond->save();
        }

        \DB::commit();

    }




////////////////////////////////////////////////////


    //سند  الصرف
    public function AddCashJournal($account_type, $customer_id, $doc_type, $amount_total, $vat_amount, $cc_voucher_id,
                                   $payment_method, $bank_id, $journal_category_id, $cost_center_id, $journal_notes,
                                   $customer_notes, $cash_notes, $j_add_date)
    {
        ////$account_type نوع الحساب من ال system code ( system code ابعت ال)
        /// $doc_type انواع المصروفات(system code ابعت ال)
        /// $customer_id (id بتاع العميل سواء مورد او موظف او عميل)
        /// $cc_voucher_id (للسند id )
        ///  (object from system code)
        /// $bank_id (id of bank if exists)
        /// cost_center_id (id from application menu)
        /// $payment_method

        $company = session('company') ? session('company') : auth()->user()->company;

        $bond = Bond::find($cc_voucher_id);

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_type = SystemCode::where('system_code', 804)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => $bond->branch_id,
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $j_add_date,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes,
        ]);

        ///للطرف الاول فقط

        $cost_center_type_id = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ///فرع
        //

        if ($journal_category_id == 12) {
            if ($bond->transaction_type == 88) {
                $waybill_car = WaybillHd::find($bond->transaction_id);
            }

            ////في حاله بوليصه السياره سند الصرف
            if (isset($waybill_car)) {
                if ($waybill_car->status->system_code == 41005) {
                    $account_id_2 = $waybill_car->customer->customer_account_id;
                    $cc_customer_id = $waybill_car->customer->customer_id;
                    $account_type_system_code = SystemCode::where('system_code', 56002)
                        ->where('company_group_id', $company->company_group_id)->first();
                } else {
                    $account_id_2 = JournalType::where('journal_types_code', $journal_category_id)
                        ->where('company_group_id', $company->company_group_id)->first()->account_id_debit;
                    $cc_car_id = $customer_id;
                    $account_type_system_code = SystemCode::where('system_code', 56004)
                        ->where('company_group_id', $company->company_group_id)->first();
                }
            } else {
                $account_id_2 = JournalType::where('journal_types_code', $journal_category_id)
                    ->where('company_group_id', $company->company_group_id)->first()->account_id_debit;
                $cc_car_id = $customer_id;
                $account_type_system_code = SystemCode::where('system_code', 56004)
                    ->where('company_group_id', $company->company_group_id)->first();

            }
        } else {
            ////عميل
            if ($account_type == 56002) {
                $cc_customer_id = $customer_id;
            }
            ///مورد
            if ($account_type == 56001) {
                $cc_supplier_id = $customer_id;
            }
            //// موظف
            if ($account_type == 56003) {
                $cc_employee_id = $customer_id;
            }

            ////فرع
            if ($account_type == 56005) {
                $cc_branch_id = $customer_id;
            }

            ////سياره
            if ($account_type == 56004) {
                $cc_car_id = $customer_id;
            }

            if (isset($account_id_2)) {
                $account_id_2 = SystemCode::where('system_code', $doc_type)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;
            }

            /////مورد او عميل
            if ($account_type == 56002 || $account_type == 56001) {
                $account_id_2 = Customer::where('customer_id', $customer_id)->first()->customer_account_id;
            }


            if ($journal_category_id == 51 || $journal_category_id == 52) { ////سياره
                $account_id_2 = JournalType::where('journal_types_code', $journal_category_id)
                    ->where('company_group_id', $company->company_group_id)->first()->account_id_credit;
            }

        }


        if ($journal_category_id == 51) {
            $total_amount = $amount_total - $vat_amount;
        }

        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category_id == 51 ? $journal_category->account_id_debit : $account_id_2,
            'journal_dt_debit' => $journal_category_id == 51 ? $total_amount : $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
            'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
            'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
            'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
            'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
            // 'cc_branch_id' => $request->cc_branch_id[$k] ? $request->cc_branch_id[$k] : null,
            'cost_center_type_id' => isset($account_type_system_code) ? $account_type_system_code->system_code_id : $cost_center_type_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $customer_notes
        ]);


        if ($vat_amount > 0) {
            if ($journal_category_id == 51) { ///في حاله الرحيله طرف الضريبه (الي)
                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $company->co_vat_paid,
                    'journal_dt_debit' => $vat_amount,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_branch_id' => session('branch')['branch_id'],
                    'cost_center_type_id' => $cost_center_type_id,
                    'cost_center_id' => $cost_center_id,////from application menu
                    'cc_voucher_id' => $cc_voucher_id,
                    'journal_dt_notes' => $customer_notes
                ]);
            } else {
                /////حساب ضريبه مدفوعه(من)
                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $company->co_vat_paid,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $vat_amount,
                    'journal_dt_balance' => $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_branch_id' => session('branch')['branch_id'],
                    'cost_center_type_id' => $cost_center_type_id,
                    'cost_center_id' => $cost_center_id,////from application menu
                    'cc_voucher_id' => $cc_voucher_id,
                    'journal_dt_notes' => $customer_notes
                ]);
            }
        }


        ////////////الطرف الثالث(الي)
        ///الفرع
        /////نقدي وفيزا
        if ($payment_method->system_code_acc_id) {
            $account_id_1 = $payment_method->system_code_acc_id;
        } else {
            return 'لا يوجد حساب لطريقه السداد';
        }


        if ($journal_category_id == 51) {

            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $account_id_1,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total,
                'journal_dt_balance' => $amount_total,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_branch_id' => session('branch')['branch_id'],
                'cost_center_type_id' => $cost_center_type_id,
                'cost_center_id' => $cost_center_id,////from application menu
                'cc_voucher_id' => $cc_voucher_id,
                'journal_dt_notes' => $cash_notes
            ]);
        } else {
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $account_id_1,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total - $vat_amount,
                'journal_dt_balance' => $amount_total - $vat_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_branch_id' => session('branch')['branch_id'],
                'cost_center_type_id' => $cost_center_type_id,
                'cost_center_id' => $cost_center_id,////from application menu
                'cc_voucher_id' => $cc_voucher_id,
                'journal_dt_notes' => $cash_notes
            ]);
        }


        if ($cost_center_id == 54 || $cost_center_id == 81) {
            $bond = Bond::where('bond_id', $cc_voucher_id)->first();
            $bond->journal_hd_id = $journal_hd->journal_hd_id;
            $bond->save();
        }


    }

    public function updateCashJournal($doc_type, $amount_total, $vat_amount, $cc_voucher_id, $payment_method, $bank_id)
    {

        // $doc_type  نوع المصروف
        $bond = Bond::find($cc_voucher_id);
        $bond->journalCash->update([
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total
        ]);


        if ($bond->customer_type == 'customer') {
            $account_type_system_code = 56002;
        } elseif ($bond->customer_type == 'supplier') {
            $account_type_system_code = 56001;
        } elseif ($bond->customer_type == 'employee') {
            $account_type_system_code = 56003;
        } elseif ($bond->customer_type == 'car') {
            $account_type_system_code = 56004;
        }

        return $account_type_system_code;

        $account_type = SystemCode::where('system_code', $account_type_system_code)
            ->where('company_group_id', $bond->company_group_id)->first()->system_code;

        /////مورد او عميل
        if ($account_type == 56002 || $account_type == 56001) {
            $account_id_2 = $bond->bond_acc_id;
        } else {
            //  return $doc_type;
            $account_id_2 = SystemCode::where('system_code', $doc_type)
                ->where('company_group_id', $bond->company_group_id)->first()->system_code_acc_id;
        }


        $bond->journalCash->journalDetails[0]->update([
            'account_id' => $account_id_2,
            'journal_dt_debit' => $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
        ]);


        /////نقدي وفيزا
        if ($payment_method->system_code == 57001 || $payment_method->system_code == 57002) {
            $account_id_1 = $payment_method->system_code_acc_id;
        }
        ///بنك
        if ($payment_method->system_code == 57005) {
            $bank = SystemCode::where('system_code_id', $bank_id)->first();
            if ($bank->system_code_acc_id) {
                $account_id_1 = $bank->system_code_acc_id;
            } else {
                return 'لا يوجد حساب للبنك';
            }

        }

        if (isset($bond->journalCash->journalDetails[2])) {
            $bond->journalCash->journalDetails[1]->update([
                'account_id' => $account_id_1,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total - $vat_amount,
                'journal_dt_balance' => $amount_total - $vat_amount,
            ]);

            $bond->journalCash->journalDetails[2]->update([
                'account_id' => $account_id_1,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $vat_amount,
                'journal_dt_balance' => $vat_amount,
            ]);
        } else {
            $bond->journalCash->journalDetails[1]->update([
                'account_id' => $account_id_1,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total,
                'journal_dt_balance' => $amount_total,
            ]);
        }

        return $bond->journalCash;

    }

    /// فاتوره بوليصه شحن سياره 
    public function addWaybillInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
                                             $cost_center_id, $vat_notes, $sales_notes, $journal_category_id,
                                             $items_id, $items_amount, $notes)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $journal_type = SystemCode::where('system_code', 808)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_Group_id', $company->company_group_id)->first();

        $string_number_journal = $this->getSerial($journal_type);
        //  return $journal_category_type;
        if (isset($journal_category)) {
            if ($cost_center_id == 119) { ///فاتوره
                $car_invoice = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();

                $vat_amount = WaybillHd::whereIn('waybill_id', $items_id)->sum('waybill_vat_amount');
                $vat_account_id = $car_invoice->company->co_vat_collect;
                $journal_hd_date = $car_invoice->supply_date;
            }

            $journal_hd = JournalHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_code' => $string_number_journal,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_status' => $journal_status->system_code_id,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_user_update_id' => auth()->user()->user_id,
                'journal_hd_date' => isset($journal_hd_date) ? $journal_hd_date : Carbon::now(),
                'journal_hd_credit' => $total_amount,
                'journal_hd_debit' => $total_amount,
                'journal_category_id' => $journal_category->journal_types_id,
                'journal_hd_notes' => $notes,
            ]);
        }

//////الطرف الاول العميل
        $customer = Customer::where('customer_id', $customer_id)->first();


        if (!$customer->customer_account_id) {
            return 'لا يوجد حساب للعميل';
        }

        $cost_center_type_id = SystemCode::where('system_code', 56002)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
        ////عميل
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $customer->customer_account_id,
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_customer_id' => $customer_id,
            'journal_dt_notes' => $customer_notes,
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);


        /////الطرف الثاني حساب الضريبه
        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

        $cost_center_type_id_car = SystemCode::where('system_code', 56004)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;


        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $vat_account_id,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $vat_amount,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);
        /////فاتوره بوالص شحن السيارات
        if (isset($car_invoice)) {
            ////الطرف الثالث
            foreach ($items_id as $item_id) { ////بوالص السيارات
                $waybill_car = WaybillHd::where('waybill_id', $item_id)->first();
                $item = SystemCode::where('system_code_id', $waybill_car->detailsCar->waybill_item_id)
                    ->where('company_group_id', $company->company_group_id)->first();

                if ($item->system_code_acc_id) {
                    // $item->system_code_acc_id;
                    $journal_dt = JournalDt::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'journal_type_id' => $journal_type->system_code_id,
                        'journal_hd_id' => $journal_hd->journal_hd_id,
                        'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                        'journal_dt_date' => $journal_hd->journal_hd_date,
                        'journal_status' => $journal_status->system_code_id,
                        'account_id' => $item->system_code_acc_id,
                        'journal_dt_debit' => 0,
                        'journal_dt_credit' => $waybill_car->waybill_total_amount - $waybill_car->waybill_vat_amount,
                        'journal_dt_balance' => $waybill_car->waybill_total_amount - $waybill_car->waybill_vat_amount,
                        'journal_user_entry_id' => auth()->user()->user_id,
                        // 'journal_dt_notes' => 'ايراد بوليصه شحن سياره رقم' . $waybill_car->waybill_code,
                        'journal_dt_notes' => $sales_notes,
                        'cost_center_type_id' => $cost_center_type_id_car,
                        'cost_center_id' => $cost_center_id,
                        'cc_voucher_id' => $cc_voucher_id,
                        'cc_car_id' => $waybill_car->waybill_truck_id ? $waybill_car->waybill_truck_id : ''
                    ]);

                    $waybill_car->journal_dt_id = $journal_dt->journal_dt_id;
                    $waybill_car->save();
                } else {
                    return 'لا يوجد حساب للعنصر الذي تم اختياره برجاء اضافه الحساب للبوليصه' . ' ' . $waybill_car->waybill_code;
                }
            }

        }

        if (isset($car_invoice)) {
            $car_invoice->journal_hd_id = $journal_hd->journal_hd_id;
            $car_invoice->save();
        }


    }


    //فواتير المبيعات
    public function addInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
                                      $cost_center_id, $vat_notes, $sales_notes, $journal_category_id,
                                      $items_id, $items_amount, $notes)
    {
        //////$total_amount total of invoice
        /// $invoice_cost_center_id id in application menu
        /// $customer_notes ملاحظات خاصه بطرف العميل في التفاصيل بتاعه القيد
        /// $vat_notes ملاحظات خاصه بطرف الضريبه في التفاصيل بتاعه القيد
        /// $sales_notes ملاحظات خاصه بطرف الايرادات في التفاصيل بتاعه القيد
        /// $journal_category_id النوع من جدول ال journal types
        ///

        $company = session('company') ? session('company') : auth()->user()->company;
        $branch = session('branch');
        $journal_type = SystemCode::where('system_code', 808)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_Group_id', $company->company_group_id)->first();

        $string_number_journal = $this->getSerial($journal_type);
        //  return $journal_category_type;
        if (isset($journal_category)) {
            if ($cost_center_id == 73 || $cost_center_id == 106 || $cost_center_id == 119) { ///فاتوره ضريبيه
                $invoice = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();
                $vat_amount = $invoice->invoice_vat_amount;
                $vat_account_id = $invoice->company->co_vat_collect;

                $journal_hd_date = $invoice->supply_date;
            }

            if ($cost_center_id == 65) { /////فاتوره بيع المستودعات
                $sales_invoice = Purchase::where('store_hd_id', $cc_voucher_id)->first();
                $vat_amount = $sales_invoice->store_vou_vat_amount;
                $vat_account_id = $sales_invoice->company->co_vat_collect;
            }

            if ($cost_center_id == 83) { /////فاتوره بيع سياره (مبيعات السيارات)
                $sales_car_invoice = Sales::where('store_hd_id', $cc_voucher_id)->first();
                $vat_amount = $sales_car_invoice->store_vou_vat_amount;
                $vat_account_id = $sales_car_invoice->company->co_vat_collect;
            }

            if ($cost_center_id == 2000) {
                $car_invoice = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();
                // $vat_amount = $car_invoice->invoice_vat_amount;

                $vat_amount = WaybillHd::whereIn('waybill_id', $items_id)->sum('waybill_vat_amount');
                $vat_account_id = $car_invoice->company->co_vat_collect;
                $journal_hd_date = $car_invoice->supply_date;
            }

            if ($cost_center_id == 71) {
                $maintenance_invoice = MaintenanceCard::find($cc_voucher_id);
                $vat_amount = $maintenance_invoice->mntns_cards_vat_amount;
                $vat_account_id = $maintenance_invoice->company->co_vat_collect;
            }

            if ($cost_center_id == 46) { ///فاتوره عقد التاجير
                $invoice_contract = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();
                $vat_amount = $invoice_contract->invoice_vat_amount;
                $vat_account_id = $invoice_contract->company->co_vat_collect;
                $journal_hd_date = $invoice_contract->supply_date;
                //return $invoice_contract->company->co_vat_collect;
            }

            $journal_hd = JournalHd::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_code' => $string_number_journal,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_status' => $journal_status->system_code_id,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_user_update_id' => auth()->user()->user_id,
                'journal_hd_date' => isset($journal_hd_date) ? $journal_hd_date : Carbon::now(),
                'journal_hd_credit' => $total_amount,
                'journal_hd_debit' => $total_amount,
                'journal_category_id' => $journal_category->journal_types_id,
                'journal_hd_notes' => $notes,
            ]);


//////الطرف الاول العميل
            $customer = Customer::where('customer_id', $customer_id)->first();


            if (!$customer->customer_account_id) {
                return 'لا يوجد حساب للعميل';
            }

            $cost_center_type_id = SystemCode::where('system_code', 56002)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
            ////عميل
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $customer->customer_account_id,
                'journal_dt_debit' => $total_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $total_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_customer_id' => $customer_id,
                'journal_dt_notes' => $customer_notes,
                'cost_center_type_id' => $cost_center_type_id,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
            ]);


            /////الطرف الثاني حساب الضريبه
            $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

            $cost_center_type_id_car = SystemCode::where('system_code', 56004)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id;


            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $vat_account_id,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $vat_amount,
                'journal_dt_balance' => $vat_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $vat_notes,
                'cost_center_type_id' => $cost_center_type_id_branch,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => session('branch')['branch_id']
            ]);

            if (isset($invoice)) {
                //// الطرف الرابع عناصر الفاتوره

                if ($cost_center_id == 73) {
                    foreach ($items_id as $k => $item_id) {
                        $item = SystemCode::where('system_code_id', $item_id)
                            ->where('company_group_id', $company->company_group_id)->first();

                        if ($item->account) {
                            $sales_notes = $item->account->acc_name_ar . ' ' . 'فاتوره المبيعات رقم' . ' ' . $invoice->invoice_no;

                            JournalDt::create([
                                'company_group_id' => $company->company_group_id,
                                'company_id' => $company->company_id,
                                'branch_id' => session('branch')['branch_id'],
                                'journal_type_id' => $journal_type->system_code_id,
                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                                'journal_dt_date' => $journal_hd->journal_hd_date,
                                'journal_status' => $journal_status->system_code_id,
                                'account_id' => $item->system_code_acc_id,
                                //'account_id' => 62,
                                'journal_dt_debit' => 0,
                                'journal_dt_credit' => $items_amount[$k],
                                'journal_dt_balance' => $items_amount[$k],
                                'journal_user_entry_id' => auth()->user()->user_id,
                                'journal_dt_notes' => $sales_notes,
                                'cc_branch_id' => session('branch')['branch_id'],
                                'cost_center_type_id' => $cost_center_type_id_branch,
                                'cost_center_id' => $cost_center_id,
                                'cc_voucher_id' => $cc_voucher_id,
                            ]);
                        } else {
                            return 'لا يوجد رقم حساب';
                        }
                    }
                }

//                فاتوره نقل صغير
                if ($cost_center_id == 119) {
                    foreach ($items_id as $k => $item_id) {

                        $item = SystemCode::where('system_code_id', WaybillHd::where('waybill_id', $item_id)->first()->details
                            ->waybill_item_id)
                            ->where('company_group_id', $company->company_group_id)
                            ->first();

                        if ($item->account) {
                            $sales_notes = $item->account->acc_name_ar . ' ' . 'فاتوره نقل صغير رقم' . ' ' . $invoice->invoice_no;

                            JournalDt::create([
                                'company_group_id' => $company->company_group_id,
                                'company_id' => $company->company_id,
                                'branch_id' => session('branch')['branch_id'],
                                'journal_type_id' => $journal_type->system_code_id,
                                'journal_hd_id' => $journal_hd->journal_hd_id,
                                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                                'journal_dt_date' => $journal_hd->journal_hd_date,
                                'journal_status' => $journal_status->system_code_id,
                                'account_id' => $item->system_code_acc_id,
                                //'account_id' => 62,
                                'journal_dt_debit' => 0,
                                'journal_dt_credit' => $items_amount[$k],
                                'journal_dt_balance' => $items_amount[$k],
                                'journal_user_entry_id' => auth()->user()->user_id,
                                'journal_dt_notes' => $sales_notes,
                                'cc_car_id' => WaybillHd::where('waybill_id', $item_id)->first()->waybill_truck_id,
                                'cost_center_type_id' => $cost_center_type_id_car,
                                'cost_center_id' => $cost_center_id,
                                'cc_voucher_id' => $cc_voucher_id,
                            ]);
                        } else {
                            return 'لا يوجد رقم حساب';
                        }
                    }
                }

                if ($cost_center_id == 106) { ///فاتوره محاسبيه الحساب من invoiceD
                    foreach ($items_id as $k => $item_id) {
                        $item = $invoice->invoiceDetails->where('invoice_item_id', $item_id)->first();
                        JournalDt::create([
                            'company_group_id' => $company->company_group_id,
                            'company_id' => $company->company_id,
                            'branch_id' => session('branch')['branch_id'],
                            'journal_type_id' => $journal_type->system_code_id,
                            'journal_hd_id' => $journal_hd->journal_hd_id,
                            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                            'journal_dt_date' => $journal_hd->journal_hd_date,
                            'journal_status' => $journal_status->system_code_id,
                            'account_id' => $item->item_account_id,
                            //'account_id' => 62,
                            'journal_dt_debit' => 0,
                            'journal_dt_credit' => $items_amount[$k] - $item->invoice_item_vat_amount,
                            'journal_dt_balance' => $items_amount[$k] - $item->invoice_item_vat_amount,
                            'journal_user_entry_id' => auth()->user()->user_id,
                            'journal_dt_notes' => $sales_notes,
                            'cc_branch_id' => session('branch')['branch_id'],
                            'cost_center_type_id' => $cost_center_type_id_branch,
                            'cost_center_id' => $cost_center_id,
                            'cc_voucher_id' => $cc_voucher_id,
                        ]);
                    }
                }
            }

            /////////////////عقد التاجير يتضاف
            /////الطرف الثالث
//            $sales_invoice_category_type = SystemCode::where('system_code_id', $sales_invoice->store_category_type)
//                ->where('company_group_id', $company->company_group_id)->first();
            if (isset($sales_invoice)) {
                $sales_invoice_category_branch = StoreAccBranch::where('branch_id', $sales_invoice->branch_id)
                    ->where('store_category_type_id', $sales_invoice->store_category_type)
                    ->where('journal_type_code', 41)->first();

                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $sales_invoice_category_branch->acc_id_2,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $total_amount - $vat_amount,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $sales_notes,
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => session('branch')['branch_id']
                ]);
            }

            if (isset($sales_car_invoice)) {

                $sales_car_invoice_category_branch = StoreAccBranch::where('branch_id', $sales_car_invoice->branch_id)
                    ->where('store_category_type_id', $sales_car_invoice->store_category_type)
                    ->where('journal_type_code', 75)->first();

                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $sales_car_invoice_category_branch->acc_id_2,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $total_amount - $vat_amount,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $sales_notes,
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => session('branch')['branch_id']
                ]);
            }

            if (isset($invoice_contract)) {
                $system_code_contract = SystemCode::where('company_group_id', $company->company_group_id)
                    ->where('system_code', 580)->first();
                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $system_code_contract->system_code_acc_id,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $total_amount - $vat_amount,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $sales_notes,
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => session('branch')['branch_id']
                ]);
            }

            //return $sales_invoice_category_type;


            /////فاتوره بوالص شحن السيارات
            if (isset($car_invoice)) {
                ////الطرف الثالث
                foreach ($items_id as $item_id) { ////بوالص السيارات
                    $waybill_car = WaybillHd::where('waybill_id', $item_id)->first();
                    $item = SystemCode::where('system_code_id', $waybill_car->detailsCar->waybill_item_id)
                        ->where('company_group_id', $company->company_group_id)->first();

                    if ($item->system_code_acc_id) {
                        // $item->system_code_acc_id;
                        $journal_dt = JournalDt::create([
                            'company_group_id' => $company->company_group_id,
                            'company_id' => $company->company_id,
                            'branch_id' => session('branch')['branch_id'],
                            'journal_type_id' => $journal_type->system_code_id,
                            'journal_hd_id' => $journal_hd->journal_hd_id,
                            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                            'journal_dt_date' => $journal_hd->journal_hd_date,
                            'journal_status' => $journal_status->system_code_id,
                            'account_id' => $item->system_code_acc_id,
                            'journal_dt_debit' => 0,
                            'journal_dt_credit' => $waybill_car->waybill_total_amount - $waybill_car->waybill_vat_amount,
                            'journal_dt_balance' => $waybill_car->waybill_total_amount - $waybill_car->waybill_vat_amount,
                            'journal_user_entry_id' => auth()->user()->user_id,
                            // 'journal_dt_notes' => 'ايراد بوليصه شحن سياره رقم' . $waybill_car->waybill_code,
                            'journal_dt_notes' => $sales_notes,
                            'cost_center_type_id' => $cost_center_type_id_car,
                            'cost_center_id' => $cost_center_id,
                            'cc_voucher_id' => $cc_voucher_id,
                            'cc_car_id' => $waybill_car->waybill_truck_id ? $waybill_car->waybill_truck_id : ''
                        ]);

                        $waybill_car->journal_dt_id = $journal_dt->journal_dt_id;
                        $waybill_car->save();
                    } else {
                        return 'لا يوجد حساب للعنصر الذي تم اختياره برجاء اضافه الحساب للبوليصه' . ' ' . $waybill_car->waybill_code;
                    }
                }

            }

            //كارت الصيانه
            if (isset($maintenance_invoice)) {
                $item = SystemCode::where('system_code_id', $maintenance_invoice->mntns_cards_type)
                    ->where('company_group_id', $company->company_group_id)->first();
                if ($item->system_code_acc_id) {
                    JournalDt::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'journal_type_id' => $journal_type->system_code_id,
                        'journal_hd_id' => $journal_hd->journal_hd_id,
                        'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                        'journal_dt_date' => $journal_hd->journal_hd_date,
                        'journal_status' => $journal_status->system_code_id,
                        'account_id' => $item->system_code_acc_id,
                        // 'account_id' => 199,
                        'journal_dt_debit' => 0,
                        'journal_dt_credit' => $total_amount - $vat_amount,
                        'journal_dt_balance' => $total_amount - $vat_amount,
                        'journal_user_entry_id' => auth()->user()->user_id,
                        'journal_dt_notes' => $sales_notes,
                        'cost_center_type_id' => $cost_center_type_id_branch,
                        'cost_center_id' => $cost_center_id,
                        'cc_voucher_id' => $cc_voucher_id,
                        'cc_branch_id' => session('branch')['branch_id']
                    ]);
                } else {
                    return 'لا يوجد حساب للعنصر الذي تم اختياره برجاء اضافه الحساب';
                }
            }


            if (isset($invoice)) {
                $invoice->journal_hd_id = $journal_hd->journal_hd_id;
                $invoice->invoice_voucher_date = $journal_hd->journal_hd_date;
                $invoice->invoice_voucher_by = auth()->user()->user_id;
                $invoice->save();
            }

            if (isset($sales_invoice)) {
                $sales_invoice->journal_hd_id = $journal_hd->journal_hd_id;
                $sales_invoice->save();
            }

            if (isset($car_invoice)) {
                $car_invoice->journal_hd_id = $journal_hd->journal_hd_id;
                $car_invoice->save();
            }

            if (isset($invoice_contract)) {
                $invoice_contract->journal_hd_id = $journal_hd->journal_hd_id;
                $invoice_contract->save();
            }

            if (isset($maintenance_invoice)) {
                $maintenance_invoice->journal_hd_id = $journal_hd->journal_hd_id;
                $maintenance_invoice->save();
            }
            // return $journal_hd;
        }
    }

///////////////////////////////////////////

    // تحديث قيد فاتوره بوليصه شحن السيارات
    public function updateWaybillInvoiceJournal($total_amount, $vat_amount,
                                                $cost_center_id, $cc_voucher_id,
                                                $items_id, $sales_notes)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        $journal_type = SystemCode::where('system_code', 808)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $cost_center_type_id_car = SystemCode::where('system_code', 56004)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

        $cc_voucher = InvoiceHd::find($cc_voucher_id);
        $journal_hd = $cc_voucher->journalHdCars;
        $journal_hd->update([
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
        ]);
        $journal_hd->journalDetails[0]->update([
            'journal_dt_debit' => $total_amount,
            'journal_dt_balance' => $total_amount,
        ]);
        $journal_hd->journalDetails[1]->update([
            'journal_dt_credit' => $vat_amount,
            'journal_dt_balance' => $vat_amount,
        ]);

        foreach ($journal_hd->journalDetails as $k => $journal_dt) {

            $waybill_car = WaybillHd::where('journal_dt_id', $journal_dt->journal_dt_id)->first();

            if (isset($waybill_car)) {
                $waybill_car->journal_dt_id = null;
                $waybill_car->save();
            }

            if ($k > 1) {
                $journal_dt->delete();
            }
        }


        foreach ($items_id as $item_id) {
            $waybill_car = WaybillHd::where('waybill_id', $item_id)->first();
            $item = SystemCode::where('system_code_id', $waybill_car->detailsCar->waybill_item_id)->first();

            if ($item->system_code_acc_id) {
                $journal_dt = JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $item->system_code_acc_id,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $total_amount - $vat_amount,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    // 'journal_dt_notes' => 'ايراد بوليصه شحن سياره رقم' . $waybill_car->waybill_code,
                    'journal_dt_notes' => $sales_notes . ' بوليصه رقم' . ' ' . $waybill_car->waybill_code,
                    // 'journal_dt_notes' => $sales_notes,
                    'cost_center_type_id' => $cost_center_type_id_car,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_car_id' => $waybill_car->waybill_truck_id ? $waybill_car->waybill_truck_id : ''
                ]);

                $waybill_car->journal_dt_id = $journal_dt->journal_dt_id;
                $waybill_car->save();
            } else {
                return 'لا يوجد حساب للعنصر الذي تم اختياره برجاء اضافه الحساب';
            }
        }


    }


//تحديث قيد الفاتوره
    public function updateInvoiceJournal($total_amount, $vat_amount,
                                         $cost_center_id, $cc_voucher_id,
                                         $items_id, $sales_notes)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if ($cost_center_id == 65) { //فاتوره بيع
            $cc_voucher = Purchase::where('store_hd_id', $cc_voucher_id)->first();
            $journal_hd = $cc_voucher->journalHd;
        }

        if ($cost_center_id == 73) {
            $cc_voucher = InvoiceHd::find($cc_voucher_id);
            $journal_hd = $cc_voucher->journalHdDiesel;
            $net_amount = $total_amount - $vat_amount - $cc_voucher->invoiceDetails[1]->invoice_item_amount;
        } else {
            $net_amount = $total_amount - $vat_amount;
        }

        if ($cost_center_id == 119) {
            $cc_voucher = InvoiceHd::find($cc_voucher_id);
            $journal_hd = $cc_voucher->journalHdCars;
        }

        if ($cost_center_id == 119 || $cost_center_id == 73) {

            $journal_hd->update([
                'journal_hd_credit' => $total_amount,
                'journal_hd_debit' => $total_amount,
            ]);

            ////العميل
            if (isset($journal_hd->journalDetails[0])) {
                $journal_hd->journalDetails[0]->update([
                    'journal_dt_debit' => $total_amount,
                    'journal_dt_balance' => $total_amount,
                ]);
            }

            ////الضريبه
            if (isset($journal_hd->journalDetails[1])) {
                $journal_hd->journalDetails[1]->update([
                    'journal_dt_credit' => $vat_amount,
                    'journal_dt_balance' => $vat_amount,
                ]);
            }
//        $net_amount = $total_amount - $vat_amount;

            if (isset($journal_hd->journalDetails[2])) {
                $journal_hd->journalDetails[2]->update([
                    'journal_dt_credit' => $net_amount,
                    'journal_dt_balance' => $net_amount,
                ]);
            }
        } else if ($cost_center_id == 73 && isset($journal_hd->journalDetails[3])) {
            $journal_hd->journalDetails[3]->update([
                'journal_dt_credit' => $cc_voucher->invoiceDetails[1]->invoice_item_amount,
                'journal_dt_balance' => $cc_voucher->invoiceDetails[1]->invoice_item_amount,
            ]);
        } elseif ($cost_center_id == 2000) { ///////////////في حاله الابديت علي الفاتوره لبوليصه (الخارجيه) السياره
            $journal_type = SystemCode::where('system_code', 808)
                ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
            $journal_status = SystemCode::where('system_code', 903)
                ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
            $cost_center_type_id_car = SystemCode::where('system_code', 56004)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

            $cc_voucher = InvoiceHd::find($cc_voucher_id);
            $journal_hd = $cc_voucher->journalHdCars;
            $journal_hd->update([
                'journal_hd_credit' => $total_amount,
                'journal_hd_debit' => $total_amount,
            ]);
            $journal_hd->journalDetails[0]->update([
                'journal_dt_debit' => $total_amount,
                'journal_dt_balance' => $total_amount,
            ]);
            $journal_hd->journalDetails[1]->update([
                'journal_dt_credit' => $vat_amount,
                'journal_dt_balance' => $vat_amount,
            ]);

            foreach ($journal_hd->journalDetails as $k => $journal_dt) {

                $waybill_car = WaybillHd::where('journal_dt_id', $journal_dt->journal_dt_id)->first();

                if (isset($waybill_car)) {
                    $waybill_car->journal_dt_id = null;
                    $waybill_car->save();
                }

                if ($k > 1) {
                    $journal_dt->delete();
                }
            }


            foreach ($items_id as $item_id) {
                $waybill_car = WaybillHd::where('waybill_id', $item_id)->first();
                $item = SystemCode::where('system_code_id', $waybill_car->detailsCar->waybill_item_id)->first();

                if ($item->system_code_acc_id) {
                    $journal_dt = JournalDt::create([
                        'company_group_id' => $company->company_group_id,
                        'company_id' => $company->company_id,
                        'branch_id' => session('branch')['branch_id'],
                        'journal_type_id' => $journal_type->system_code_id,
                        'journal_hd_id' => $journal_hd->journal_hd_id,
                        'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                        'journal_dt_date' => $journal_hd->journal_hd_date,
                        'journal_status' => $journal_status->system_code_id,
                        'account_id' => $item->system_code_acc_id,
                        'journal_dt_debit' => 0,
                        'journal_dt_credit' => $waybill_car->waybill_total_amount - $waybill_car->waybill_vat_amount,
                        'journal_dt_balance' => $waybill_car->waybill_total_amount - $waybill_car->waybill_vat_amount,
                        'journal_user_entry_id' => auth()->user()->user_id,
                        // 'journal_dt_notes' => 'ايراد بوليصه شحن سياره رقم' . $waybill_car->waybill_code,
                        'journal_dt_notes' => $sales_notes . ' بوليصه رقم' . ' ' . $waybill_car->waybill_code,
                        // 'journal_dt_notes' => $sales_notes,
                        'cost_center_type_id' => $cost_center_type_id_car,
                        'cost_center_id' => $cost_center_id,
                        'cc_voucher_id' => $cc_voucher_id,
                        'cc_car_id' => $waybill_car->waybill_truck_id ? $waybill_car->waybill_truck_id : ''
                    ]);

                    $waybill_car->journal_dt_id = $journal_dt->journal_dt_id;
                    $waybill_car->save();
                } else {
                    return 'لا يوجد حساب للعنصر الذي تم اختياره برجاء اضافه الحساب';
                }
            }

        } else {
            /////في حاله فاتوره البيع طرف الايراد
            if (isset($journal_hd->journalDetails[2])) {
                $journal_hd->journalDetails[2]->update([
                    'journal_dt_credit' => $net_amount,
                    'journal_dt_balance' => $net_amount,
                ]);
            }
        }

    }


////////////////قيد المشتريات
    public
    function addPurchasingJournal($total_amount, $vat_amount, $supplier_id, $purchasing_notes,
                                  $cost_center_id, $cc_voucher_id, $vat_notes, $supplier_notes,
                                  $journal_category_id, $notes)
    {
        /////$purchasing_notes بيان لطرف المشتريات
        /// $cost_center_id id in application menu
        /// $cc_voucher_id id ال للي هضيف عليه القيد
        /// $vat_notes بيان الضريبه
        /// $supplier_notes البيان الخاص بالمورد
        /// $journal_category_id from journal types table
        /// $acc_purchases  رقم الحساب للنشاط من ال system code
        if ($cost_center_id == 64) {///اذن استلام
            $cc_voucher = Purchase::where('store_hd_id', $cc_voucher_id)->first();

        }

        if ($cost_center_id == 120) {////فواتير بوالص ديزل
            $cc_voucher = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();
        }

        if ($cost_center_id == 81) {////اذن دخول سيارات
            $cc_voucher = Sales::where('store_hd_id', $cc_voucher_id)->first();
        }

        $company = session('company') ? session('company') : auth()->user()->company;
        \DB::beginTransaction();


        $journal_type = SystemCode::where('system_code', 807)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مشتريات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', 35)
            ->where('company_group_id', $company->company_group_id)->first();
        $string_number_journal = $this->getSerial($journal_type);
        $journal_hd = JournalHd::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => Carbon::now(),
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $notes,
        ]);

        $cc_voucher->update(['journal_hd_id' => $journal_hd->journal_hd_id]);

        /////////طرف اول المشتريات(من) acc_purchasing
        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع

        $journal_type_account = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        if ($cost_center_id == 120) {
            $purchasing_account = SystemCode::where('system_code_id', $cc_voucher->invoiceDetail
                ->invoice_item_id)->first();
            // return $purchasing_account;
            if ($purchasing_account->system_code_acc_id_2) {
                JournalDt::create([
                    'company_group_id' => $cc_voucher->company_group_id,
                    'company_id' => $cc_voucher->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $purchasing_account->system_code_acc_id_2,
                    'journal_dt_debit' => $total_amount - $vat_amount,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $purchasing_notes,
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => session('branch')['branch_id']
                ]);
            } else {
                return 'لا يوجد حساب للمشتريات  برجاء اضافه الحساب';
            }

        } elseif ($cost_center_id == 64) {
//واستيراد امر شراء //اذن استلام جديد
            $purchase = Purchase::where('store_hd_id', $cc_voucher_id)->first();

//            $purchase_category_type = SystemCode::where('system_code_id', $purchase->store_category_type)->where('company_group_id',
//                $company->company_group_id)->first();

            $purchase_category_type = StoreAccBranch::where('branch_id', $purchase->branch_id)
                ->where('store_category_type_id', $purchase->store_category_type)
                ->where('journal_type_code', 35)->first();

            if (!isset($purchase_category_type)) {
                return 'لا يوجد حساب للمستودع';
            }

            if ($purchase_category_type->acc_id_1) {
                JournalDt::create([
                    'company_group_id' => $cc_voucher->company_group_id,
                    'company_id' => $cc_voucher->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $purchase_category_type->acc_id_1,
                    'journal_dt_debit' => $total_amount - $vat_amount,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $purchasing_notes,
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => session('branch')['branch_id']
                ]);
            } else {
                return 'لا يوجد حساب للمستودع  برجاء اضافه الحساب';
            }
        } elseif ($cost_center_id == 81) {
//واستيراد امر شراء //اذن استلام جديد
            $sales = Sales::where('store_hd_id', $cc_voucher_id)->first();

//            $purchase_category_type = SystemCode::where('system_code_id', $purchase->store_category_type)->where('company_group_id',
//                $company->company_group_id)->first();


            $sales_category_type = StoreAccBranch::where('branch_id', $sales->branch_id)
                ->where('store_category_type_id', $sales->store_category_type)
                ->where('journal_type_code', 71)->first();

//            return $purchase_category_type;

            if ($sales_category_type->acc_id_1) {
                JournalDt::create([
                    'company_group_id' => $cc_voucher->company_group_id,
                    'company_id' => $cc_voucher->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $sales_category_type->acc_id_1,
                    'journal_dt_debit' => $total_amount - $vat_amount,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $purchasing_notes,
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => session('branch')['branch_id']
                ]);
            } else {
                return 'لا يوجد حساب للمستودع  برجاء اضافه الحساب';
            }
        } else {
            if ($journal_type_account->account_id_debit) {
                JournalDt::create([
                    'company_group_id' => $cc_voucher->company_group_id,
                    'company_id' => $cc_voucher->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $journal_type_account->account_id_debit,
                    'journal_dt_debit' => $total_amount - $vat_amount,
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => $total_amount - $vat_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $purchasing_notes,
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => session('branch')['branch_id']
                ]);
            } else {
                return 'لا يوجد حساب للمشتريات  برجاء اضافه الحساب';
            }
        }


        //////طرف تاني ضريبه مدفوع(من)
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $cc_voucher->company->co_vat_paid,
            'journal_dt_debit' => $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);

/////حساب المورد(الي)

        $cost_center_type_id_supplier = SystemCode::where('system_code', 56001)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد
        if ($cost_center_id == 120) {
            $supplier = Customer::where('customer_id', $supplier_id)->first();
            $cost_center_type_id_supplier = SystemCode::where('system_code', 56001)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد
            if ($supplier->customer_account_id) {
                JournalDt::create([
                    'company_group_id' => $cc_voucher->company_group_id,
                    'company_id' => $cc_voucher->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $supplier->customer_account_id,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $total_amount,
                    'journal_dt_balance' => $total_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_supplier_id' => $supplier_id,
                    'journal_dt_notes' => $supplier_notes,
                    'cost_center_type_id' => $cost_center_type_id_supplier,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                ]);
            } else {
                return 'لا يوجد حساب تابع للمورد';
            }
        } else {
            $purchase = Purchase::where('store_hd_id', $cc_voucher_id)->first();
            $supplier = Customer::where('customer_id', $purchase->$supplier_id)->first();


            $cc_voucher_cus_type = SystemCode::where('system_code_id', $purchase->customer->customer_type)
                ->first()->system_code;

            $cc_voucher_method = SystemCode::where('system_code', $purchase->store_vou_pay_type)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;


            if ($cc_voucher_cus_type == 539) {
                $cc_supplier_id_v = $supplier_id;
                $cc_branch_id_v = '0';
                $cost_center_type_v = $cost_center_type_id_supplier;
                $account_id_v = $purchase->customer->customer_account_id;
                JournalDt::create([
                    'company_group_id' => $cc_voucher->company_group_id,
                    'company_id' => $cc_voucher->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $account_id_v,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $total_amount,
                    'journal_dt_balance' => $total_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_supplier_id' => $cc_supplier_id_v,
                    'journal_dt_notes' => $supplier_notes,
                    'cost_center_type_id' => $cost_center_type_v,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => $cc_branch_id_v
                ]);
            } else {

                $cc_supplier_id_v = 0;
                $cc_branch_id_v = session('branch')['branch_id'];
                $cost_center_type_v = $cost_center_type_id_branch;
                $account_id_v = $cc_voucher_method;
                JournalDt::create([
                    'company_group_id' => $cc_voucher->company_group_id,
                    'company_id' => $cc_voucher->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $cc_voucher_method,
                    'journal_dt_debit' => 0,
                    'journal_dt_credit' => $total_amount,
                    'journal_dt_balance' => $total_amount,
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'cc_supplier_id' => $cc_supplier_id_v,
                    'journal_dt_notes' => $supplier_notes,
                    'cost_center_type_id' => $cost_center_type_v,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                    'cc_branch_id' => $cc_branch_id_v
                ]);


                // } else {
                //     return 'لا يوجد حساب تابع للمورد';
            }
        }

        \DB::commit();

    }

////////////تحديث قيدالمشتريات
    public
    function updatePurchasingJournal($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id)
    {

        if ($cost_center_id == 64) { ////اذن استلام
            $cc_voucher = Purchase::where('store_hd_id', $cc_voucher_id)->first();
        }

        if ($cost_center_id == 120) {////فواتير بوالص ديزل
            $cc_voucher = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();
        }


        if ($cc_voucher->journalHd) {
            $journal_hd = $cc_voucher->journalHd;
        }

        if ($cc_voucher->journalPurchase) {
            $journal_hd = $cc_voucher->journalPurchase;
        }

        $journal_hd->update([
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
        ]);

        if ($cost_center_id == 64) {
            $journal_hd->update([
                'journal_hd_date' => $cc_voucher->vou_datetime
            ]);
        }


        $journal_hd->journalDetails[0]->update([
            'journal_dt_debit' => $total_amount - $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount - $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
        ]);

        $journal_hd->journalDetails[1]->update([
            'journal_dt_debit' => $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $vat_amount,
        ]);

        $journal_hd->journalDetails[2]->update([
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
        ]);
    }


/////قيد مرتجع المشتريات
    public
    function addReturnPurchasingOrder($total_amount, $vat_amount, $supplier_id, $customer_id,
                                      $supplier_notes, $cost_center_id, $cc_voucher_id,
                                      $journal_category_id,
                                      $purchasing_notes, $vat_notes, $notes)
    {
        /// $journal_category_id from journal types table

        if ($cost_center_id == 66 || $cost_center_id == 95) { //مرتجع مورد او عميل
            $cc_voucher = Purchase::where('store_hd_id', $cc_voucher_id)->first();
        }

        if ($cost_center_id == 84) { //مرتجع مورد
            $cc_voucher = Sales::where('store_hd_id', $cc_voucher_id)->first();
        }


        $company = session('company') ? session('company') : auth()->user()->company;
        $journal_type = SystemCode::where('system_code', 811)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرتجع مشتريات

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $string_number_journal = $this->getSerial($journal_type);

        $journal_hd = JournalHd::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => Carbon::now(),
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $notes,
        ]);

        $cc_voucher->update(['journal_hd_id' => $journal_hd->journal_hd_id]);


        if ($supplier_id) {
            $supplier = Customer::where('customer_id', $supplier_id)->first();
            $account_id = $supplier->customer_account_id;
            $cost_center_type_id = SystemCode::where('system_code', 56001)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد
        }
        if ($customer_id) {
            $customer = Customer::where('customer_id', $customer_id)->first();
            $account_id = $customer->customer_account_id;
            $cost_center_type_id = SystemCode::where('system_code', 56002)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
        }


        /////من حساب مورد او عميل (debit)
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id,
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_supplier_id' => $supplier_id ? $supplier_id : '',
            'cc_customer_id' => $customer_id ? $customer_id : '',
            'journal_dt_notes' => $supplier_notes,
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);

////الي حساب مردود المشتريات (credit
        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع
        $journal_type_account = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        if ($cost_center_id == 66) {
            $return_supplier = Purchase::where('store_hd_id', $cc_voucher_id)->first();
//            $return_supplier_category = SystemCode::where('system_code_id', $return_supplier->store_category_type)
//                ->where('company_group_id', $company->company_group_id)->first();
//

            $return_supplier_category = StoreAccBranch::where('branch_id', $return_supplier->branch_id)
                ->where('journal_type_code', 46)->where('store_category_type_id', $return_supplier->store_category_type)
                ->first();

            $account_id = $return_supplier_category->acc_id_1;

        } elseif ($cost_center_id == 84) {
            $return_supplier_c = Sales::where('store_hd_id', $cc_voucher_id)->first();

            $return_supplier_category_c = StoreAccBranch::where('branch_id', $return_supplier_c->branch_id)
                ->where('journal_type_code', 73)->where('store_category_type_id', $return_supplier_c->store_category_type)
                ->first();

            $account_id = $return_supplier_category_c->acc_id_1;
        } else {
            $account_id = $journal_type_account->account_id_credit;
        }

        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount - $vat_amount,
            'journal_dt_balance' => $total_amount - $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $purchasing_notes,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);

        ////الي حساب ضريبه مدفوعه(credit)///
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $cc_voucher->company->co_vat_paid,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $vat_amount,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);
    }

//////////////تحديث قيد مرتجع المشتريات
    public
    function updateReturnPurchasingOrder($total_amount, $vat_amount, $cc_voucher_id, $cost_center_id)
    {
        if ($cost_center_id == 66) { //مرتجع مورد
            $cc_voucher = Purchase::where('store_hd_id', $cc_voucher_id)->first();
        }

        if ($cc_voucher->journalHd) {
            $journal_hd = $cc_voucher->journalHd;

            $journal_hd->update([
                'journal_hd_credit' => $total_amount,
                'journal_hd_debit' => $total_amount,
            ]);

            $journal_hd->journalDetails[0]->update([
                'journal_dt_debit' => $total_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $total_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
            ]);

            $journal_hd->journalDetails[1]->update([
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $total_amount - $vat_amount,
                'journal_dt_balance' => $total_amount - $vat_amount,
            ]);

            $journal_hd->journalDetails[2]->update([
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $vat_amount,
                'journal_dt_balance' => $vat_amount,
            ]);

        }
    }


    ////////////////قيد فاتوره المرتجع
    public function addSalesInvoiceJournal($total_amount, $customer_id, $cc_voucher_id, $customer_notes,
                                           $cost_center_id, $vat_notes, $sales_notes, $journal_category_id,
                                           $items_id, $items_amount, $notes)
    {
//////$total_amount total of invoice
        /// $invoice_cost_center_id id in application menu
        /// $customer_notes ملاحظات خاصه بطرف العميل في التفاصيل بتاعه القيد
        /// $vat_notes ملاحظات خاصه بطرف الضريبه في التفاصيل بتاعه القيد
        /// $sales_notes ملاحظات خاصه بطرف الايرادات في التفاصيل بتاعه القيد
        /// $journal_category_id النوع من جدول ال journal types
        $company = session('company') ? session('company') : auth()->user()->company;

        \DB::beginTransaction();


        if ($cost_center_id == 107) {
            $journal_type = SystemCode::where('system_code', 808)////قيد مبيعات
            ->where('company_group_id', $company->company_group_id)->first();
        } else {
            $journal_type = SystemCode::where('system_code', 62007)
                ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرتجع مبيعات
        }


        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category_type = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $string_number_journal = $this->getSerial($journal_type);

        if ($cost_center_id == 95) { /////////فاتوره مرتجع
            $car_invoice = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();
            $vat_amount = $car_invoice->invoice_vat_amount;
            $vat_account_id = $car_invoice->company->co_vat_collect;
        }

        if ($cost_center_id == 107) { /////////اشعار خصم
            $return_invoice = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();
            $vat_amount = $return_invoice->invoice_vat_amount;
            $vat_account_id = $return_invoice->company->co_vat_collect;
        }

        if ($cost_center_id == 94) {
            $customer_invoice_r = Purchase::where('store_hd_id', $cc_voucher_id)->first();
            $vat_amount = $customer_invoice_r->store_vou_vat_amount;
            $vat_account_id = $customer_invoice_r->company->co_vat_collect;
        }


        if ($cost_center_id == 84) {
            $customer_car_invoice_r = Sales::where('store_hd_id', $cc_voucher_id)->first();
            $vat_amount = $customer_car_invoice_r->store_vou_vat_amount;
            $vat_account_id = $customer_car_invoice_r->company->co_vat_collect;
        }

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => isset($return_invoice) ? $return_invoice->invoice_date : Carbon::now(),
            'journal_hd_credit' => abs($total_amount),
            'journal_hd_debit' => abs($total_amount),
            'journal_category_id' => $journal_category_id,
            'journal_hd_notes' => $notes,
        ]);


        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

//        if (!$journal_category_type->account_id_credit) {
//            return 'لا يوجد رقم حساب لطرف الايراد';
//        }

        if (isset($car_invoice)) {

            $item = SystemCode::where('system_code_id',
                InvoiceDt::where('invoice_id', $car_invoice->invoice_id)->first()->invoice_item_id)->first();

            if (!$item->system_code_acc_id) {
                return 'لا يوجد رقم حساب لطرف الايراد';
            }
            /////رقم الحساب من الsystem code
/////الطرف الاول حساب المصروف
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $item->system_code_acc_id_2,
                'journal_dt_debit' => $total_amount - $vat_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $total_amount - $vat_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $sales_notes,
                'cost_center_type_id' => $cost_center_type_id_branch,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => session('branch')['branch_id']
            ]);
        }

        if (isset($customer_invoice_r)) {
            //الحساب من نوع المستودع
//            $store_type = SystemCode::where('system_code_id', $customer_invoice_r->store_category_type)
//                ->where('company_group_id', $customer_invoice_r->company_group_id)
//                ->first();

            $store_type = StoreAccBranch::where('branch_id', $customer_invoice_r->branch_id)
                ->where('journal_type_code', 48)
                ->where('store_category_type_id', $customer_invoice_r->store_category_type)->first();

            if (!$store_type->acc_id_2) {
                return 'لا يوجد رقم حساب للمستودع';
            }

            /////الطرف الاول حساب الايراد
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $store_type->acc_id_2,
                'journal_dt_debit' => $total_amount - $vat_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $total_amount - $vat_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $sales_notes,
                'cost_center_type_id' => $cost_center_type_id_branch,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => session('branch')['branch_id']
            ]);

        }

        if (isset($customer_car_invoice_r)) {
            //الحساب من نوع المستودع
//            $store_type = SystemCode::where('system_code_id', $customer_invoice_r->store_category_type)
//                ->where('company_group_id', $customer_invoice_r->company_group_id)
//                ->first();

            $store_type = StoreAccBranch::where('branch_id', $customer_car_invoice_r->branch_id)
                ->where('journal_type_code', 77)
                ->where('store_category_type_id', $customer_car_invoice_r->store_category_type)->first();

            if (!$store_type->acc_id_2) {
                return 'لا يوجد رقم حساب للمستودع';
            }

            /////الطرف الاول حساب الايراد
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $store_type->acc_id_2,
                'journal_dt_debit' => $total_amount - $vat_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $total_amount - $vat_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $sales_notes,
                'cost_center_type_id' => $cost_center_type_id_branch,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => session('branch')['branch_id']
            ]);

        }

        if (isset($return_invoice)) { //////////////اشعار خصم/////الحساب من جدول الinvoiceDt
            foreach ($items_id as $k => $item_id) {
                $item = $return_invoice->invoiceDetails->where('invoice_item_id', $item_id)->first();
                if (!$item->item_account_id) {
                    return 'برجاء اضافه حساب للخدمه';
                }

                JournalDt::create([
                    'company_group_id' => $company->company_group_id,
                    'company_id' => $company->company_id,
                    'branch_id' => session('branch')['branch_id'],
                    'journal_type_id' => $journal_type->system_code_id,
                    'journal_hd_id' => $journal_hd->journal_hd_id,
                    'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                    'journal_dt_date' => $journal_hd->journal_hd_date,
                    'journal_status' => $journal_status->system_code_id,
                    'account_id' => $item->item_account_id,
                    //'account_id' => 62,
                    'journal_dt_debit' => abs($items_amount[$k]) - abs($item->invoice_item_vat_amount),
                    'journal_dt_credit' => 0,
                    'journal_dt_balance' => abs($items_amount[$k]),
                    'journal_user_entry_id' => auth()->user()->user_id,
                    'journal_dt_notes' => $sales_notes,
                    'cc_branch_id' => session('branch')['branch_id'],
                    'cost_center_type_id' => $cost_center_type_id_branch,
                    'cost_center_id' => $cost_center_id,
                    'cc_voucher_id' => $cc_voucher_id,
                ]);
            }
        }

        /////الطرف الثاني حساب الضريبه
        if (!$vat_account_id) {
            return 'لا يوجد رقم حساب للضريبه للشركه';
        }

        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $vat_account_id,
            'journal_dt_debit' => abs($vat_amount),
            'journal_dt_credit' => 0,
            'journal_dt_balance' => abs($vat_amount),
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);


        //////الطرف الثالث العميل
        $customer = Customer::where('customer_id', $customer_id)->first();
        $cost_center_type_id = SystemCode::where('system_code', 56002)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
        ////عميل
        if (!$customer->customer_account_id) {
            return 'لا يوجد رقم حساب للعميل';
        }

        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $customer->customer_account_id,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => abs($total_amount),
            'journal_dt_balance' => abs($total_amount),
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_customer_id' => $customer_id,
            'journal_dt_notes' => $customer_notes,
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);

        if (isset($return_invoice)) {
            $return_invoice->journal_hd_id = $journal_hd->journal_hd_id;
            $return_invoice->invoice_voucher_date = $journal_hd->journal_hd_date;
            $return_invoice->invoice_voucher_by = auth()->user()->user_id;
            $return_invoice->save();
        }
        if (isset($customer_invoice_r)) {
            $customer_invoice_r->journal_hd_id = $journal_hd->journal_hd_id;
            $customer_invoice_r->save();
        }

        if (isset($car_invoice)) {
            $car_invoice->journal_hd_id = $journal_hd->journal_hd_id;
            $car_invoice->save();
        }

        \DB::commit();

    }

    ///////////////تحديث قيد فاتوره المرتجع
    public function updateSalesInvoiceJournal($cc_voucher_id, $total_amount, $vat_amount)
    {
        $customer_invoice_r = Purchase::find($cc_voucher_id);

        $customer_invoice_r->journalHd->update([
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
        ]);

        $customer_invoice_r->journalHd->journalDetails[0]->update([
            'journal_dt_debit' => $total_amount - $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount - $vat_amount,
        ]);

        $customer_invoice_r->journalHd->journalDetails[1]->update([
            'journal_dt_debit' => $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $vat_amount,
        ]);


        $customer_invoice_r->journalHd->journalDetails[2]->update([
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
        ]);
    }


    /////القيد الاضافي فاتوره البيع من المستودع
    public function addStoreJournalsInvoice($cost_center_id, $cc_voucher_id, $total_amount,
                                            $journal_category_id, $notes, $cost_notes)
    {

        $company = session('company') ? session('company') : auth()->user()->company;


        $journal_type = SystemCode::where('system_code', 808)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);
        $sales_invoice = Purchase::where('store_hd_id', $cc_voucher_id)->first();


        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => isset($journal_hd_date) ? $journal_hd_date : Carbon::now(),
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category_id,
            'journal_hd_notes' => $notes,
        ]);

        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

        $store_acc_branch = StoreAccBranch::where('branch_id', $sales_invoice->branch_id)
            ->where('journal_type_code', 61)
            ->where('store_category_type_id', $sales_invoice->store_category_type)->first();
//return $store_acc_branch;
//        من حساب تكلفه البضاعه المباعه
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $store_acc_branch->acc_id_3,
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'journal_dt_notes' => $cost_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);

//الي حساب المخزون
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $store_acc_branch->acc_id_4,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'journal_dt_notes' => $cost_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);

        $sales_invoice->journal_hd_id_1 = $journal_hd->journal_hd_id;
        $sales_invoice->save();

        // return $journal_hd;
    }

    public function updateStoreJournalsInvoice($cc_voucher_id, $total_amount)
    {

        $sales_invoice = Purchase::find($cc_voucher_id);
        $sales_invoice->journalHd2->update([
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
        ]);

        $sales_invoice->journalHd2->journalDetails[0]->update([
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
        ]);

        $sales_invoice->journalHd2->journalDetails[1]->update([
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
        ]);

    }


    /////القيد الاضافي مرتجع مبيعات من المستودع
    public function addStoreJournalsSales($cost_center_id, $cc_voucher_id, $total_amount,
                                          $journal_category_id, $notes, $cost_notes)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $journal_type = SystemCode::where('system_code', 808)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();
        $string_number_journal = $this->getSerial($journal_type);

        $sales_invoice = Purchase::where('store_hd_id', $cc_voucher_id)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => isset($journal_hd_date) ? $journal_hd_date : Carbon::now(),
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $notes,
        ]);

        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;

        $store_acc_branch = StoreAccBranch::where('branch_id', $sales_invoice->branch_id)
            ->where('journal_type_code', 61)
            ->where('store_category_type_id', $sales_invoice->store_category_type)->first();
//return $store_acc_branch;

        //من حساب المخزون
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $store_acc_branch->acc_id_4,
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'journal_dt_notes' => $cost_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);


//        الي حساب تكلفه البضاعه المباعه
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $store_acc_branch->acc_id_3,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'journal_dt_notes' => $cost_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);


        $sales_invoice->journal_hd_id_1 = $journal_hd->journal_hd_id;
        $sales_invoice->save();

        // return $journal_hd;
    }


    public function updateStoreJournalsSales($cc_voucher_id, $total_amount)
    {

        $sales_invoice = Purchase::find($cc_voucher_id);
        $sales_invoice->journalHd2->update([
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
        ]);

        $sales_invoice->journalHd2->journalDetails[0]->update([
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
        ]);

        $sales_invoice->journalHd2->journalDetails[1]->update([
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
        ]);

    }


    public function storeTransferPermission($cost_center_id, $cc_voucher_id, $total_amount,
                                            $journal_category_id, $notes, $cost_notes, $store_vou_ref_before,
                                            $transfer_before_type_code)
    {
        /////$store_vou_ref_before اذن التحويل الي بعمله استيراد
        /// اذن الاستلام لاذن التحويل cc_voucher_id$
        $company = session('company') ? session('company') : auth()->user()->company;


        $journal_type = SystemCode::where('system_code', 808)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مبيعات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);
        if ($cost_center_id == 64) { ////اذن تحويل مستودع من اذن استلام
            $transfer_permission = Purchase::where('store_hd_id', $cc_voucher_id)->first();

            $transfer_before = Purchase::where('store_hd_code', $store_vou_ref_before)
                ->first();
        }


        if ($cost_center_id == 81) { ////اذن تحويل سيارات من اذن دخول
            $transfer_permission = Sales::where('store_hd_id', $cc_voucher_id)->first();

            $transfer_before = Sales::where('store_hd_code', $store_vou_ref_before)
                ->where('store_vou_type', SystemCode::where('system_code', '=', $transfer_before_type_code)
                    ->first()->system_code_id)
                ->first();
        }


        //  return SystemCode::where('system_code', '=', $transfer_before_type_code)->first()->system_code_id;

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => isset($journal_hd_date) ? $journal_hd_date : Carbon::now(),
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $notes,
        ]);

        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;


        $store_acc_branch_source = StoreAccBranch::where('branch_id', $transfer_before->store_vou_ref_1)
            ->where('journal_type_code', $journal_category_id)
            ->where('store_category_type_id', $transfer_before->store_vou_ref_2)->first();


        $store_acc_branch_dest = StoreAccBranch::where('branch_id', $transfer_before->store_vou_ref_3)
            ->where('journal_type_code', $journal_category_id)
            ->where('store_category_type_id', $transfer_before->store_vou_ref_4)->first();


        //   return $transfer_before->store_vou_ref_3;

        //من حساب المخزون
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $store_acc_branch_dest->acc_id_1,
            'journal_dt_debit' => $total_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => $transfer_before->store_vou_ref_3,
            'journal_dt_notes' => $cost_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,


        ]);


        //  return $store_acc_branch_dest;

        //الي حساب المخزون
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $store_acc_branch_source->acc_id_1,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => $transfer_before->store_vou_ref_1,
            'journal_dt_notes' => $cost_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
        ]);


        $transfer_permission->journal_hd_id = $journal_hd->journal_hd_id;
        $transfer_permission->save();

    }


    //////قيد سند صرف تفصيلي
    public function addCashDetailedJournal($cost_center_id, $cc_voucher_id, $amount_total, $journal_category,
                                           $journal_notes, $bond_dts, $vat_amount, $payment_method,
                                           $bank_id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        \DB::beginTransaction();


        $journal_type = SystemCode::where('system_code', 803)
            ->where('company_group_id', $company->company_group_id)->first();
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);
        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => Carbon::now(),
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes,
        ]);

////////////////////الطرف الاول
        foreach ($bond_dts as $bond_dt) {
            if ($bond_dt->customer) {
                $account_id_2 = Customer::where('customer_id', $bond_dt->customer->customer_id)->first()
                    ->customer_account_id;

                if ($bond_dt->customer->customer_category == 1) { ///مورد
                    $account_type_system_code = SystemCode::where('system_code', 56001)
                        ->where('company_group_id', $company->company_group_id)->first();
                    $journal_dt_notes = 'حساب المورد ' . $bond_dt->customer->customer_name_full_ar;

                    $cc_supplier_id = $bond_dt->customer->customer_id;

                } else { /////عميل
                    $account_type_system_code = SystemCode::where('system_code', 56002)
                        ->where('company_group_id', $company->company_group_id)->first();

                    $journal_dt_notes = 'حساب العميل ' . $bond_dt->customer->customer_name_full_ar;

                    $cc_customer_id = $bond_dt->customer->customer_id;
                }

            }


            if ($bond_dt->car) {
                $account_id_2 = Trucks::where('truck_id', $bond_dt->car->truck_id)->first()->truck_account_id;
                if (!$account_id_2) {
                    return 'لا يوجد رقم حساب للشاحنه';
                }
                $account_type_system_code = SystemCode::where('system_code', 56004)////سياره
                ->where('company_group_id', $company->company_group_id)->first();
                $journal_dt_notes = 'حساب السياره ' . $bond_dt->car->truck_name;
                $cc_car_id = $bond_dt->car->truck_id;

            }

            if ($bond_dt->employee) {
                $account_type_system_code = SystemCode::where('system_code', 56003)////موظف
                ->where('company_group_id', $company->company_group_id)->first();
                $journal_dt_notes = 'حساب الموظف ' . $bond_dt->employee->emp_name_full_ar;
                $cc_employee_id = $bond_dt->employee->emp_id;

                $account_id_2 = SystemCode::where('system_code_id', $bond_dt->bond_doc_type)->first()->system_code_acc_id;
                if (!$account_id_2) {
                    return 'لا يوجد رقم حساب لنوع المصروف';
                }

            }


            if ($bond_dt->branch) {
                $account_type_system_code = SystemCode::where('system_code', 56005)////فرع
                ->where('company_group_id', $company->company_group_id)->first();
                $journal_dt_notes = 'حساب الفرع ' . $bond_dt->branch->branch_name_ar;
                $cc_branch_id = $bond_dt->bond_branch_id;

                $account_id_2 = SystemCode::where('system_code_id', $bond_dt->bond_doc_type)->first()->system_code_acc_id;

                if (!$account_id_2) {
                    return 'لا يوجد رقم حساب للفرع';
                }
            }


            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $account_id_2,
                'journal_dt_debit' => $bond_dt->bond_amount_credit - $bond_dt->bond_vat_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $bond_dt->bond_amount_credit - $bond_dt->bond_vat_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
                'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
                'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
                'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
                'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
                // 'cc_branch_id' => $request->cc_branch_id[$k] ? $request->cc_branch_id[$k] : null,
                'cost_center_type_id' => $account_type_system_code ? $account_type_system_code->system_code_id : null,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'journal_dt_notes' => $journal_dt_notes
            ]);

            $bond_dt->journal_hd_id = $journal_hd->journal_hd_id;
            $bond_dt->save();
        }

        $cost_center_type_id = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ///فرع
///////////////طرف الضريبه
        /////حساب ضريبه مدفوعه(من)
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $company->co_vat_paid,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $vat_amount,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => 'حساب ضريبه مدفوعه سند صرف تفصيلي'
        ]);


        ////////////الطرف الثالث(الي)
        ///الفرع
        /////نقدي وفيزا
        if ($payment_method->system_code == 57001 || $payment_method->system_code == 57002 || $payment_method->system_code == 57003 || $payment_method->system_code == 57004 || $payment_method->system_code == 57006 || $payment_method->system_code == 57007 || $payment_method->system_code == 57008) {
            $account_id_1 = $payment_method->system_code_acc_id;
        } ///بنك
        elseif ($payment_method->system_code == 57005) {
            $bank = SystemCode::where('system_code_id', $bank_id)->first();
            if ($bank->system_code_acc_id) {
                $account_id_1 = $bank->system_code_acc_id;
            } else {
                return 'لا يوجد حساب للبنك';
            }

        } else {
            return 'اختار طريقه دفع ساريه';
        }


        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_1,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_total - $vat_amount,
            'journal_dt_balance' => $amount_total - $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => 'قيد سند صرف تفصيلي طرف الضريبه'
        ]);


        $bond = Bond::where('bond_id', $cc_voucher_id)->first();
        $bond->journal_hd_id = $journal_hd->journal_hd_id;
        $bond->save();


        \DB::commit();


    }

    public function addCaptureDetailedJournal($cost_center_id, $cc_voucher_id, $amount_total, $journal_category,
                                              $journal_notes, $bond_dts, $payment_method,
                                              $bank_id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;


        $journal_type = SystemCode::where('system_code', 803)
            ->where('company_group_id', $company->company_group_id)->first();
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();
        $string_number_journal = $this->getSerial($journal_type);

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => Carbon::now(),
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes
        ]);

        //////////////////الطرف الاول حسب طريقه الدفع
        /////نقدي وفيزا
        if ($payment_method->system_code == 57001 || $payment_method->system_code == 57002 || $payment_method->system_code == 57003 || $payment_method->system_code == 57004 || $payment_method->system_code == 57006 || $payment_method->system_code == 57007 || $payment_method->system_code == 57008) {
            $account_id_1 = $payment_method->system_code_acc_id;
        } ///بنك
        elseif ($payment_method->system_code == 57005) {
            $bank = SystemCode::where('system_code_id', $bank_id)->first();
            if ($bank->system_code_acc_id) {
                $account_id_1 = $bank->system_code_acc_id;
            } else {
                return 'لا يوجد حساب للبنك';
            }

        } else {
            return 'اختار طريقه دفع ساريه';
        }

        $cost_center_type_id = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ///فرع

        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_1,
            'journal_dt_debit' => $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => 'قيد سند قبض تفصيلي طرف الفرع'
        ]);

        ////////////////////الطرف الثاني
        foreach ($bond_dts as $bond_dt) {
            if ($bond_dt->customer) {
                $account_id_2 = Customer::where('customer_id', $bond_dt->customer->customer_id)->first()
                    ->customer_account_id;

                if ($bond_dt->customer->customer_category == 1) { ///مورد
                    $account_type_system_code = SystemCode::where('system_code', 56001)
                        ->where('company_group_id', $company->company_group_id)->first();
                    $journal_dt_notes = 'حساب المورد ' . $bond_dt->customer->customer_name_full_ar;

                    $cc_supplier_id = $bond_dt->customer->customer_id;

                } else { /////عميل
                    $account_type_system_code = SystemCode::where('system_code', 56002)
                        ->where('company_group_id', $company->company_group_id)->first();

                    $journal_dt_notes = ' العميل ' . $bond_dt->customer->customer_name_full_ar . '' . $bond_dt->bond_ref_no;

                    $cc_customer_id = $bond_dt->customer->customer_id;
                }

            }


            if ($bond_dt->car) {
                $account_id_2 = Trucks::where('truck_id', $bond_dt->car->truck_id)->first()->truck_account_id;
                if (!$account_id_2) {
                    return 'لا يوجد رقم حساب للشاحنه';
                }
                $account_type_system_code = SystemCode::where('system_code', 56004)////سياره
                ->where('company_group_id', $company->company_group_id)->first();
                $journal_dt_notes = 'حساب السياره ' . $bond_dt->car->truck_name;
                $cc_car_id = $bond_dt->car->truck_id;

            }

            if ($bond_dt->employee) {
                $account_type_system_code = SystemCode::where('system_code', 56003)////موظف
                ->where('company_group_id', $company->company_group_id)->first();
                $journal_dt_notes = 'حساب الموظف ' . $bond_dt->employee->emp_name_full_ar;
                $cc_employee_id = $bond_dt->employee->emp_id;

                $account_id_2 = SystemCode::where('system_code_id', $bond_dt->bond_doc_type)->first()->system_code_acc_id;

                if (!$account_id_2) {
                    return 'لا يوجد رقم حساب للمصروف';
                }
            }


            if ($bond_dt->branch) {
                $account_type_system_code = SystemCode::where('system_code', 56005)////فرع
                ->where('company_group_id', $company->company_group_id)->first();
                $journal_dt_notes = 'حساب الفرع ' . $bond_dt->branch->branch_name_ar;
                $cc_branch_id = $bond_dt->bond_branch_id;

                $account_id_2 = SystemCode::where('system_code_id', $bond_dt->bond_doc_type)->first()->system_code_acc_id;

                if (!$account_id_2) {
                    return 'لا يوجد رقم حساب للمصروف';
                }
            }


            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $account_id_2,
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $bond_dt->bond_amount_debit,
                'journal_dt_balance' => $bond_dt->bond_amount_debit,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_customer_id' => isset($cc_customer_id) ? $cc_customer_id : null,
                'cc_supplier_id' => isset($cc_supplier_id) ? $cc_supplier_id : null,
                'cc_employee_id' => isset($cc_employee_id) ? $cc_employee_id : null,
                'cc_branch_id' => isset($cc_branch_id) ? $cc_branch_id : null,
                'cc_car_id' => isset($cc_car_id) ? $cc_car_id : null,
                // 'cc_branch_id' => $request->cc_branch_id[$k] ? $request->cc_branch_id[$k] : null,
                'cost_center_type_id' => $account_type_system_code ? $account_type_system_code->system_code_id : null,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'journal_dt_notes' => $journal_dt_notes
            ]);

            $bond_dt->journal_hd_id = $journal_hd->journal_hd_id;
            $bond_dt->save();
        }


        $bond = Bond::where('bond_id', $cc_voucher_id)->first();
        $bond->journal_hd_id = $journal_hd->journal_hd_id;
        $bond->save();

    }


    ////////////////سند استخقاق
    public function AddEntitlementJournal($account_type, $amount_total, $cc_voucher_id
        , $journal_category_id, $cost_center_id, $journal_notes, $j_add_date)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_type = SystemCode::where('system_code', 801)
            ->where('company_group_id', $company->company_group_id)->first(); //////////قيد محاسبي

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();
        $string_number_journal = $this->getSerial($journal_type);
        /////////////سياره
        $account_type_system_code = SystemCode::where('system_code', $account_type)
            ->where('company_group_id', $company->company_group_id)->first();///للطرف الاول فقط

        $trip = TripHd::where('trip_hd_id', $cc_voucher_id)->first();

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $j_add_date,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes,
        ]);

/////////////من حساب الشاحنه
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_debit,
            'journal_dt_debit' => $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_car_id' => $trip->truck_id,
            'cost_center_type_id' => $account_type_system_code->system_code_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => 'قيد مصروف ديزل للرحله رقم ' . ' ' . $trip->trip_hd_code
        ]);


        /////////////موظف
        $account_type_system_code_employee = SystemCode::where('system_code', 56003)
            ->where('company_group_id', $company->company_group_id)->first();///للطرف الاول فقط

        //////////////// الي حساب السائق
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_credit,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_total,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_employee_id' => $trip->driver_id,
            'cost_center_type_id' => $account_type_system_code_employee->system_code_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => 'قيد استحقاق ديزل للرحله رقم ' . ' ' . $trip->trip_hd_code
        ]);

        //  return $journal_hd->journalDetails;
    }


    public function updateEntitlementJournal($amount_total, $journal_id)
    {
        $journal_hd = JournalHd::find($journal_id);

        $journal_hd->update([
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
        ]);

        $journal_hd->journalDetails[0]->update([
            'journal_dt_debit' => $amount_total,
            'journal_dt_balance' => $amount_total,
        ]);

        $journal_hd->journalDetails[1]->update([
            'journal_dt_credit' => $amount_total,
            'journal_dt_balance' => $amount_total,
        ]);
    }


    public function AddEntitlement2Journal($account_type, $amount_total, $cc_voucher_id, $trip_id
        , $journal_category_id, $cost_center_id, $journal_notes, $j_add_date)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_type = SystemCode::where('system_code', 803)
            ->where('company_group_id', $company->company_group_id)->first(); //////////قيد 

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);
        $trip = TripHd::where('trip_hd_id', $trip_id)->first();

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $j_add_date,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes,
        ]);

        /////////////موظف
        $account_type_system_code_employee = SystemCode::where('system_code', 56003)
            ->where('company_group_id', $company->company_group_id)->first(); ////الموظف
/////////////من حساب السائق
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_debit,
            'journal_dt_debit' => $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_employee_id' => $trip->driver_id,
            'cost_center_type_id' => $account_type_system_code_employee->system_code_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => 'قيد صرف للرحله رقم ' . ' ' . $trip->trip_hd_code
        ]);


        /////////////الصندوق
        $account_type_system_code = SystemCode::where('system_code', $account_type)
            ->where('company_group_id', $company->company_group_id)->first();

        //////////////// الي حساب الصندوق
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_credit,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_total,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $account_type_system_code->system_code_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => 'قيد صرف للرحله رقم ' . ' ' . $trip->trip_hd_code
        ]);

        $bond = Bond::where('bond_id', $cc_voucher_id)->first();
        $bond->journal_hd_id = $journal_hd->journal_hd_id;
        $bond->save();
        //  return $journal_hd->journalDetails;
    }


//    قيد فاتوره مشتريات مورد علي الحساب من كارت الصيانه 
    public
    function addSupplierPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                                                 $cc_voucher_id, $vat_notes, $supplier_notes,
                                                 $journal_category_id, $notes,
                                                 $invoice_dts, $cc_car_id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $cc_voucher = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();


        $journal_type = SystemCode::where('system_code', 807)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مشتريات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();
        $string_number_journal = $this->getSerial($journal_type);
        $journal_hd = JournalHd::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $cc_voucher->supply_date,
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $supplier_notes,
        ]);

        $cc_voucher->update(['journal_hd_id' => $journal_hd->journal_hd_id]);

        $cost_center_type_id_supplier = SystemCode::where('system_code', 56001)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد


        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;


        //        من حساب المصروفات

        foreach ($invoice_dts as $k => $invoice_dt) {
            // if ($invoice_dt->cc_branch_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56005)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع
            // } elseif ($invoice_dt->cc_car_id) {
            $cost_center_type_id_a = SystemCode::where('system_code', 56004)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////سياره
            // } elseif ($invoice_dt->cc_employee_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56003)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////موظف
            // } elseif ($invoice_dt->cc_customer_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56002)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
            // } elseif ($invoice_dt->cc_supplier_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56001)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد
            // } else {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56005)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
            // }


            JournalDt::create([
                'company_group_id' => $cc_voucher->company_group_id,
                'company_id' => $cc_voucher->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $invoice_dt->invoice_item_id,
                'journal_dt_debit' => $invoice_dt->invoice_item_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $invoice_dt->invoice_item_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $supplier_notes,
                'cost_center_type_id' => $cost_center_type_id_a,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => $invoice_dt->cc_branch_id ? $invoice_dt->cc_branch_id : '',
                'cc_car_id' => $cc_car_id,
                'cc_employee_id' => $invoice_dt->cc_employee_id ? $invoice_dt->cc_employee_id : '',
                'cc_customer_id' => $invoice_dt->cc_customer_id ? $invoice_dt->cc_customer_id : '',
                'cc_supplier_id' => $invoice_dt->cc_supplier_id ? $invoice_dt->cc_supplier_id : '',
            ]);
        }

        //        من حساب الضريبه
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $company->co_vat_paid,
            'journal_dt_debit' => $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);


//        الي حساب المورد
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $cc_voucher->customer->customer_account_id,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $supplier_notes,
            'cost_center_type_id' => $cost_center_type_id_supplier,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_supplier_id' => $cc_voucher->customer_id
        ]);

        return $journal_hd;

    }


//    قيد فاتوره مشتريات مورد افراد من كارت الصيانه
    public function addSupplierCashPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                                                            $cc_voucher_id, $vat_notes, $supplier_notes,
                                                            $journal_category_id, $notes,
                                                            $invoice_dts, $cc_car_id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $cc_voucher = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();

        $journal_type = SystemCode::where('system_code', 807)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مشتريات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();
        $string_number_journal = $this->getSerial($journal_type);
        $journal_hd = JournalHd::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $cc_voucher->supply_date,
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $supplier_notes,
        ]);

        $cc_voucher->update(['journal_hd_id' => $journal_hd->journal_hd_id]);

        $cc_voucher_method = SystemCode::where('system_code', $cc_voucher->payment_tems)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;

        $cost_center_type_id_supplier = SystemCode::where('system_code', 56001)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد


        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع

        //        من حساب المصروفات
        foreach ($invoice_dts as $k => $invoice_dt) {
            // if ($invoice_dt->cc_branch_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56005)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع
            // } elseif ($invoice_dt->cc_car_id) {
            $cost_center_type_id_a = SystemCode::where('system_code', 56004)
                ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////سياره
            // } elseif ($invoice_dt->cc_employee_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56003)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////موظف
            // } elseif ($invoice_dt->cc_customer_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56002)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
            // } elseif ($invoice_dt->cc_supplier_id) {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56001)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد
            // } else {
            //     $cost_center_type_id_a = SystemCode::where('system_code', 56005)
            //         ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
            // }


            JournalDt::create([
                'company_group_id' => $cc_voucher->company_group_id,
                'company_id' => $cc_voucher->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $invoice_dt->invoice_item_id,
                'journal_dt_debit' => $invoice_dt->invoice_item_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $invoice_dt->invoice_item_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $supplier_notes,
                'cost_center_type_id' => $cost_center_type_id_a,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => $invoice_dt->cc_branch_id ? $invoice_dt->cc_branch_id : '',
                'cc_car_id' => $cc_car_id,
                'cc_employee_id' => $invoice_dt->cc_employee_id ? $invoice_dt->cc_employee_id : '',
                'cc_customer_id' => $invoice_dt->cc_customer_id ? $invoice_dt->cc_customer_id : '',
                'cc_supplier_id' => $invoice_dt->cc_supplier_id ? $invoice_dt->cc_supplier_id : '',
            ]);
        }

        //        من حساب الضريبه
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $company->co_vat_paid,
            'journal_dt_debit' => $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);


//        الي حساب طريقه الدفع
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $cc_voucher_method,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $supplier_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);

        return $journal_hd;

    }


//    قيد فاتوره مشتريات مورد علي الحساب
    public
    function SupplierPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                                              $cc_voucher_id, $vat_notes, $supplier_notes,
                                              $journal_category_id, $notes,
                                              $invoice_dts)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $cc_voucher = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();


        $journal_type = SystemCode::where('system_code', 807)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مشتريات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();
        $string_number_journal = $this->getSerial($journal_type);
        $journal_hd = JournalHd::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $cc_voucher->supply_date,
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $supplier_notes,
        ]);

        $cc_voucher->update(['journal_hd_id' => $journal_hd->journal_hd_id]);

        $cost_center_type_id_supplier = SystemCode::where('system_code', 56001)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد


        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id;


        //        من حساب المصروفات

        foreach ($invoice_dts as $k => $invoice_dt) {
            if ($invoice_dt->cc_branch_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56005)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع
            } elseif ($invoice_dt->cc_car_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56004)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////سياره
            } elseif ($invoice_dt->cc_employee_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56003)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////موظف
            } elseif ($invoice_dt->cc_customer_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56002)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
            } elseif ($invoice_dt->cc_supplier_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56001)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد
            } else {
                $cost_center_type_id_a = SystemCode::where('system_code', 56005)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
            }


            JournalDt::create([
                'company_group_id' => $cc_voucher->company_group_id,
                'company_id' => $cc_voucher->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $invoice_dt->invoice_item_id,
                'journal_dt_debit' => $invoice_dt->invoice_item_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $invoice_dt->invoice_item_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $supplier_notes,
                'cost_center_type_id' => $cost_center_type_id_a,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => $invoice_dt->cc_branch_id ? $invoice_dt->cc_branch_id : '',
                'cc_car_id' => $invoice_dt->cc_car_id ? $invoice_dt->cc_car_id : '',
                'cc_employee_id' => $invoice_dt->cc_employee_id ? $invoice_dt->cc_employee_id : '',
                'cc_customer_id' => $invoice_dt->cc_customer_id ? $invoice_dt->cc_customer_id : '',
                'cc_supplier_id' => $invoice_dt->cc_supplier_id ? $invoice_dt->cc_supplier_id : '',
            ]);
        }

        //        من حساب الضريبه
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $company->co_vat_paid,
            'journal_dt_debit' => $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);


//        الي حساب المورد
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $cc_voucher->customer->customer_account_id,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $supplier_notes,
            'cost_center_type_id' => $cost_center_type_id_supplier,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_supplier_id' => $cc_voucher->customer_id
        ]);

        return $journal_hd;

    }


//    قيد فاتوره مشتريات مورد افراد
    public
    function SupplierCashPurchasingInvoiceJournal($total_amount, $vat_amount, $cost_center_id,
                                                  $cc_voucher_id, $vat_notes, $supplier_notes,
                                                  $journal_category_id, $notes,
                                                  $invoice_dts)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $cc_voucher = InvoiceHd::where('invoice_id', $cc_voucher_id)->first();

        $journal_type = SystemCode::where('system_code', 807)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مشتريات
        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل
        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();
        $string_number_journal = $this->getSerial($journal_type);
        $journal_hd = JournalHd::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $cc_voucher->supply_date,
            'journal_hd_credit' => $total_amount,
            'journal_hd_debit' => $total_amount,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $supplier_notes,
        ]);

        $cc_voucher->update(['journal_hd_id' => $journal_hd->journal_hd_id]);

        $cc_voucher_method = SystemCode::where('system_code', $cc_voucher->payment_tems)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;

        $cost_center_type_id_supplier = SystemCode::where('system_code', 56001)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد


        $cost_center_type_id_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع

        //        من حساب المصروفات
        foreach ($invoice_dts as $k => $invoice_dt) {
            if ($invoice_dt->cc_branch_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56005)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////فرع
            } elseif ($invoice_dt->cc_car_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56004)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////سياره
            } elseif ($invoice_dt->cc_employee_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56003)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////موظف
            } elseif ($invoice_dt->cc_customer_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56002)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////عميل
            } elseif ($invoice_dt->cc_supplier_id) {
                $cost_center_type_id_a = SystemCode::where('system_code', 56001)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ////مورد
            } else {
                $cost_center_type_id_a = SystemCode::where('system_code', 56005)
                    ->where('company_group_id', $company->company_group_id)->first()->system_code_id;
            }


            JournalDt::create([
                'company_group_id' => $cc_voucher->company_group_id,
                'company_id' => $cc_voucher->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $invoice_dt->invoice_item_id,
                'journal_dt_debit' => $invoice_dt->invoice_item_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $invoice_dt->invoice_item_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'journal_dt_notes' => $supplier_notes,
                'cost_center_type_id' => $cost_center_type_id_a,
                'cost_center_id' => $cost_center_id,
                'cc_voucher_id' => $cc_voucher_id,
                'cc_branch_id' => $invoice_dt->cc_branch_id ? $invoice_dt->cc_branch_id : '',
                'cc_car_id' => $invoice_dt->cc_car_id ? $invoice_dt->cc_car_id : '',
                'cc_employee_id' => $invoice_dt->cc_employee_id ? $invoice_dt->cc_employee_id : '',
                'cc_customer_id' => $invoice_dt->cc_customer_id ? $invoice_dt->cc_customer_id : '',
                'cc_supplier_id' => $invoice_dt->cc_supplier_id ? $invoice_dt->cc_supplier_id : '',
            ]);
        }

        //        من حساب الضريبه
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $company->co_vat_paid,
            'journal_dt_debit' => $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $vat_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);


//        الي حساب طريقه الدفع
        JournalDt::create([
            'company_group_id' => $cc_voucher->company_group_id,
            'company_id' => $cc_voucher->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $cc_voucher_method,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $total_amount,
            'journal_dt_balance' => $total_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_dt_notes' => $supplier_notes,
            'cost_center_type_id' => $cost_center_type_id_branch,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'cc_branch_id' => session('branch')['branch_id']
        ]);

        return $journal_hd;

    }


    ///////////////سند قبض عهده
    public function AddCaptureSafeJournal($amount_total, $cc_voucher_id,
                                          $payment_method, $bank_id, $journal_category_id,
                                          $cost_center_id, $journal_notes)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        \DB::beginTransaction();

        $journal_type = SystemCode::where('system_code', 803)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();
        $string_number_journal = $this->getSerial($journal_type);

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_Group_id', $company->company_group_id)->first();

        $bond = Bond::where('bond_id', $cc_voucher_id)->first();

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => Carbon::now(),
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes
        ]);


        $cost_center_type_id = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ///فرع

        /////من حساب العهده
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_debit,
            'journal_dt_debit' => $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $journal_notes
        ]);


        if ($payment_method->system_code == 57001 || $payment_method->system_code == 57002
            || $payment_method->system_code == 57003 || $payment_method->system_code == 57004) {
            $account_id_2 = $payment_method->system_code_acc_id;
        }

        ///بنك
        if ($payment_method->system_code == 57005) {
            if ($bank_id) {
                $bank = SystemCode::where('system_code_id', $bank_id)->first();
                if ($bank->system_code_acc_id) {
                    $account_id_2 = $bank->system_code_acc_id;
                } else {
                    return 'لا يوجد حساب للبنك';
                }
            } else {
                return 'لم يتم اختيار البنك للتحويل';
            }

        }

//////////////الي حساب طريقه الدفع
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_2,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_total,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $journal_notes
        ]);
        $bond->journal_hd_id = $journal_hd->journal_hd_id;
        $bond->save();

        \DB::commit();

    }

/////////////تحديث سند قبض العهده
    public function UpdateCaptureSafeJournal($amount_total, $cc_voucher_id)
    {


        $company = session('company') ? session('company') : auth()->user()->company;

        $bond = Bond::where('bond_id', $cc_voucher_id)->first();

        if ($bond->bond_method_type == 57001 || $bond->bond_method_type == 57002
            || $bond->bond_method_type == 57003 || $bond->bond_method_type == 57004) {
            $bond_method = SystemCode::where('system_code', $bond->bond_method_type)
                ->where('company_group_id', $company->company_group_id)->first();
            $account_id_1 = $bond_method->system_code_acc_id;
        }

        ///بنك
        if ($bond->bond_method_type == 57005) {
            if ($bond->bond_bank_id) {
                $bank = SystemCode::where('system_code_id', $bond->bond_bank_id)->first();
                if ($bank->system_code_acc_id) {
                    $account_id_1 = $bank->system_code_acc_id;
                } else {
                    return 'لا يوجد حساب للبنك';
                }
            } else {
                return 'لم يتم اختيار البنك للتحويل';
            }

        }

        $bond->journalCapture->update([
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
        ]);

        $bond->journalCapture->journalDetails[0]->update([
            'journal_dt_debit' => $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
        ]);

        $bond->journalCapture->journalDetails[1]->update([
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_total,
            'journal_dt_balance' => $amount_total,
            'account_id' => $account_id_1
        ]);

    }

    ///////////سند صرف عهده
    public function AddCashSafeJournal($doc_type, $amount_total, $vat_amount, $cc_voucher_id,
                                       $journal_category_id, $cost_center_id, $journal_notes, $j_add_date)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        \DB::begintransaction();


        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_type = SystemCode::where('system_code', 804)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);
        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $j_add_date,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes,
        ]);


        //////////حساب المصروف
        $account_id_1 = SystemCode::where('system_code', $doc_type)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;


        $cost_center_type_id = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_id; ///فرع

///////////////من نوع المصروف
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $account_id_1,
            'journal_dt_debit' => $amount_total - $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total - $vat_amount,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $journal_notes
        ]);

        if ($vat_amount > 0) {
            /////حساب ضريبه مدفوعه(من)
            JournalDt::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'journal_type_id' => $journal_type->system_code_id,
                'journal_hd_id' => $journal_hd->journal_hd_id,
                'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
                'journal_dt_date' => $journal_hd->journal_hd_date,
                'journal_status' => $journal_status->system_code_id,
                'account_id' => $company->co_vat_paid,
                'journal_dt_debit' => $vat_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $vat_amount,
                'journal_user_entry_id' => auth()->user()->user_id,
                'cc_branch_id' => session('branch')['branch_id'],
                'cost_center_type_id' => $cost_center_type_id,
                'cost_center_id' => $cost_center_id,////from application menu
                'cc_voucher_id' => $cc_voucher_id,
                'journal_dt_notes' => $journal_notes
            ]);
        }


        ////////////الي حساب العهده
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_credit,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_total,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $cost_center_type_id,
            'cost_center_id' => $cost_center_id,////from application menu
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $journal_notes
        ]);

        $bond = Bond::find($cc_voucher_id);
        $bond->journal_hd_id = $journal_hd->journal_hd_id;
        $bond->save();

        \DB::commit();
    }

    ///////////////تحديث سند صرف عهده
    public function UpdateCashSafe($cc_voucher_id, $amount_total, $vat_amount, $doc_type)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $bond = Bond::find($cc_voucher_id);
        $bond->journalCash->update([
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total
        ]);

        //////////حساب المصروف
        $account_id_1 = SystemCode::where('system_code', $doc_type)
            ->where('company_group_id', $company->company_group_id)->first()->system_code_acc_id;

        $bond->journalCash->journalDetails[0]->update([
            'account_id' => $account_id_1,
            'journal_dt_debit' => $amount_total - $vat_amount,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total - $vat_amount,
        ]);

        if (isset($bond->journalCash->journalDetails[2])) {
            $bond->journalCash->journalDetails[1]->update([
                'journal_dt_debit' => $vat_amount,
                'journal_dt_credit' => 0,
                'journal_dt_balance' => $amount_total,
            ]);

            $bond->journalCash->journalDetails[2]->update([
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total,
                'journal_dt_balance' => $amount_total,
            ]);
        } else {
            $bond->journalCash->journalDetails[2]->update([
                'journal_dt_debit' => 0,
                'journal_dt_credit' => $amount_total,
                'journal_dt_balance' => $amount_total,
            ]);
        }

    }


    ///////////////////قيد اذن الصرف
    public function externalStoreSalesInv($cc_voucher_id, $journal_category_id, $amount_total, $journal_notes, $j_add_date,
                                          $cost_center_id, $car_notes, $branch_notes, $cc_car_id)
    {

        $company = session('company') ? session('company') : auth()->user()->company;

        $store_sales = Purchase::find($cc_voucher_id);

        $journal_category = JournalType::where('journal_types_code', $journal_category_id)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_type = SystemCode::where('system_code', 804)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_status = SystemCode::where('system_code', 903)
            ->where('company_group_id', $company->company_group_id)->first(); ////قيد مرحل

        $account_period = AccounPeriod::where('acc_period_year', Carbon::now()->format('Y'))
            ->where('acc_period_month', Carbon::now()->format('m'))
            ->where('acc_period_is_active', 1)->first();

        $string_number_journal = $this->getSerial($journal_type);

        $account_type_system_code_branch = SystemCode::where('system_code', 56005)
            ->where('company_group_id', $company->company_group_id)->first();

        $account_type_system_code_car = SystemCode::where('system_code', 56004)
            ->where('company_group_id', $company->company_group_id)->first();

        $journal_hd = JournalHd::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_code' => $string_number_journal,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_status' => $journal_status->system_code_id,
            'journal_user_entry_id' => auth()->user()->user_id,
            'journal_user_update_id' => auth()->user()->user_id,
            'journal_hd_date' => $j_add_date,
            'journal_hd_credit' => $amount_total,
            'journal_hd_debit' => $amount_total,
            'journal_category_id' => $journal_category->journal_types_id,
            'journal_hd_notes' => $journal_notes,
        ]);
////من السياره
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_debit,
            'journal_dt_debit' => $amount_total,
            'journal_dt_credit' => 0,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_car_id' => $cc_car_id,
            // 'cc_branch_id' => $request->cc_branch_id[$k] ? $request->cc_branch_id[$k] : null,
            'cost_center_type_id' => $account_type_system_code_car->system_code_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $car_notes
        ]);

        ////الي الفرع
        JournalDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'branch_id' => session('branch')['branch_id'],
            'journal_type_id' => $journal_type->system_code_id,
            'journal_hd_id' => $journal_hd->journal_hd_id,
            'period_id' => isset($account_period) ? $account_period->acc_period_id : 1,
            'journal_dt_date' => $journal_hd->journal_hd_date,
            'journal_status' => $journal_status->system_code_id,
            'account_id' => $journal_category->account_id_credit,
            'journal_dt_debit' => 0,
            'journal_dt_credit' => $amount_total,
            'journal_dt_balance' => $amount_total,
            'journal_user_entry_id' => auth()->user()->user_id,
            'cc_branch_id' => session('branch')['branch_id'],
            'cost_center_type_id' => $account_type_system_code_branch->system_code_id,
            'cost_center_id' => $cost_center_id,
            'cc_voucher_id' => $cc_voucher_id,
            'journal_dt_notes' => $branch_notes
        ]);

        $store_sales->journal_hd_id = $journal_hd->journal_hd_id;
        $store_sales->save();
    }


    public
    function getSerial($journal_type)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $last_journal_reference = CompanyMenuSerial::where('company_group_id', $company->company_group_id)
            ->where('app_menu_id', 33)->where('journal_type', $journal_type->system_code_id)->latest()->first();
        if (isset($last_journal_reference)) {
            $last_journal_reference_number = $last_journal_reference->serial_last_no;
            $array_number = explode('-', $last_journal_reference_number);
            $array_number[count($array_number) - 1] = $array_number[count($array_number) - 1] + 1;
            $string_number_journal = implode('-', $array_number);
            $last_journal_reference->update(['serial_last_no' => $string_number_journal]);
        } else {
            $string_number_journal = 'J-' . $journal_type->system_code_id . '-1';
            CompanyMenuSerial::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => $company->company_id,
                'branch_id' => session('branch')['branch_id'],
                'app_menu_id' => 33,
                'acc_period_year' => Carbon::now()->format('y'),
                'serial_last_no' => $string_number_journal,
                'created_user' => auth()->user()->user_id,
                'journal_type' => $journal_type->system_code_id
            ]);
        }

        return $string_number_journal;
    }


}
