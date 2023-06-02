<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountCompany;
use App\Models\Branch;
use App\Models\CompaniesMenuReport;
use App\Models\Company;
use App\Models\CompanyGroup;
use App\Models\Customer;
use App\Models\FinFormAccount;
use App\Models\FinFormSubType;
use App\Models\FinFormSubTypeDt;
use App\Models\Reports;
use App\Models\Employee;
use App\Models\GL\GlDetail;
use App\Models\GL\GlHeader;
use App\Models\SystemCode;
use App\Models\TrialBalanceDetail;
use App\Models\TrialBalanceHeader;
use App\Models\Trucks;
use App\Models\Views\AccountTree;
use App\Models\Views\OpeningVoucher;
use App\Models\Views\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GlController extends Controller
{
    public $sum_debit = 0;
    public $sum_credit = 0;

    public $balance_debit = 0;
    public $balance_credit = 0;

    public function __construct()
    {
        set_time_limit(8000000);
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $cost_center_types = SystemCode::where('sys_category_id', 56)
            ->where('company_group_id', $company->company_group_id)->get();
        $report_acc_customer = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93059')->get();
        $report_acc_employee = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93057')->get();
        $report_acc_customer_agent = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93058')->get();
        $report_acc_branch = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93056')->get();
        $report_acc_supplier = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93055')->get();
        $report_acc_employee_solaf = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93053')->get();
        $report_acc_employee_rateb = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93054')->get();
        $report_acc_vat_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93052')->get();

        $report_waybill_ajel_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93011')->get();
            $report_waybill_journal_report = Reports::where('company_id', $company->company_id)
            ->where('report_code', '93012')->get();
        $loc_lits = SystemCode::where('sys_category_id', 34)
            ->where('company_group_id', $company->company_group_id)->get();

        $report_acc_lists = SystemCode::where('sys_category_id', 93)
            ->where('company_group_id', $company->company_group_id)->get();
        $accounts = Account::where('company_group_id', $company->company_group_id)->where('acc_level', $company->companyGroup->accounts_levels_number)
            ->get();

        $form_types = FinFormSubType::where('company_group_id', $company->company_group_id)->get();

        return view('Reports.Accounts.index', compact('company', 'companies', 'report_acc_customer', 'report_acc_customer_agent', 'report_acc_employee',
            'report_acc_lists', 'cost_center_types', 'form_types', 'accounts', 'report_acc_branch', 'loc_lits', 'report_waybill_ajel_report', 'report_waybill_journal_report',
            'report_acc_supplier', 'report_acc_employee_solaf', 'report_acc_employee_rateb', 'report_acc_vat_report'));
    }

    public function getAccounts()
    {
        $accounts_ids = AccountCompany::whereIn('company_id', json_decode(request()->company_id))
            ->where('acc_level', request()->acc_level)->pluck('acc_code')->toArray();

        $accounts = Account::whereIn('acc_id', $accounts_ids)->get();

        if (count($accounts) > 0) {
            return response()->json(['data' => $accounts]);
        } else {
            return response()->json(['status' => 500,
                'message' => 'لا يوجد حسابات للمستوي للشركه الفرعيه التي قمت باختيارها']);
        }

    }

    public function getFormSubTypes()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        if (request()->report_id == 93007) {
            $form_type = SystemCode::where('system_code', 146001)
                ->where('company_group_id', $company->company_group_id)->first();
        } elseif (request()->report_id == 93008) {
            $form_type = SystemCode::where('system_code', 146002)
                ->where('company_group_id', $company->company_group_id)->first();
        }

        $form_sub_types = FinFormSubType::where('form_type_id', $form_type->system_code_id)->get();
        return response()->json(['data' => $form_sub_types]);
    }

    public function store(Request $request)
    {

        $current_year = Carbon::parse($request->from_date)->format('Y');
        $to_year = Carbon::parse($request->to_date)->format('Y');
        $company = session('company') ? session('company') : auth()->user()->company;

        // if ($current_year != $to_year) {
        //     return back()->with(['error' => 'التاريخ من والي غير متوافقين']);
        // }

        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);


        $companies_id = json_decode($request->company_id);


        if ($request->report_id == 93002) { ///// تقرير الاستاذ التفصيلي

            $gl_header = GlHeader::where('created_user', auth()->user()->user_id)->first();

            if (isset($gl_header)) {
                $gl_header->glDetails()->delete();
                $gl_header->delete();
            }


            \DB::beginTransaction();

            $gl_header = GlHeader::create([
                'company_group_id' => $company->company_group_id,
                'company_id' => json_encode($companies_id),
                'from_date' => Carbon::parse($request->from_date)->format('d-m-Y'),
                'to_date' => Carbon::parse($request->to_date)->format('d-m-Y'),
                'acc_level' => $request->acc_level,
                'acc_id' => $request->acc_id,
                'created_user' => auth()->user()->user_id
            ]);

            $account = Account::find(request()->acc_id);

            //return Carbon::parse($request->from_date)->format('d-m-Y');

            // if (Carbon::parse($request->from_date)->format('d-m-Y') != '01-01-' . $current_year) {

            //     $start = '01-01-' . $current_year;
            //     $end = Carbon::parse($request->from_date)->subDays(1);

            //     if ($request->acc_level == 4) {
            //         $acc_natures = AccountTree::where('id4', request()->acc_id)->get();
            //     } else {
            //         $acc_natures = AccountTree::where('id5', request()->acc_id)->get();
            //     }

            //     foreach ($acc_natures as $acc_nature) {

            //         $this->sum_credit = 0;
            //         $this->sum_debit = 0;

            //         $query = Voucher::where('id5', $acc_nature->id5);

            //         $journals = $query->whereIn('company_id', $companies_id)
            //             ->where(function ($q) use ($start, $end, $current_year) {
            //                 return $q->where('journal_date', '<=', $end->toDateString())
            //                     ->where('journal_date', '>=', $start)
            //                     ->where('system_code', '!=', 901)
            //                     ->orWhere(function ($qu) use ($current_year) {
            //                         return $qu->where('system_code', 901)
            //                             ->whereYear('journal_date', $current_year);
            //                     });
            //             });

            //         $this->sum_credit = $journals->sum('credit');
            //         $this->sum_debit = $journals->sum('debit');

            //         if ($acc_nature->nature == 'Debit') {
            //             $balance = $this->sum_debit - $this->sum_credit;
            //             if ($balance >= 0) {
            //                 $acc_nature['debit'] = $balance;
            //                 $acc_nature['credit'] = 0;
            //             } else {
            //                 $acc_nature['debit'] = 0;
            //                 $acc_nature['credit'] = $balance;
            //             }
            //         } else {
            //             $balance = $this->sum_credit - $this->sum_debit;
            //             if ($balance >= 0) {
            //                 $acc_nature['debit'] = 0;
            //                 $acc_nature['credit'] = $balance;
            //             } else {
            //                 $acc_nature['debit'] = abs($balance);
            //                 $acc_nature['credit'] = 0;
            //             }
            //         }

            //     }

            //     if ($balance != 0) {
            //         GlDetail::create([
            //             'gl_header_id' => $gl_header->gl_header_id,
            //             'company_group_id' => $company->company_group_id,
            //             'company_group_ar' => $company->companyGroup->company_group_ar,
            //             'company_group_en' => $company->companyGroup->company_group_en,
            //             //                        'company_id' => json_encode($request->company_id),
            //             //                        'company_name_ar' => json_encode($companies_name_ar),
            //             //                        'company_name_en' => json_encode($companies_name_en),
            //             'id1' => $acc_nature->id1, 'id2' => $acc_nature->id2,
            //             'id3' => $acc_nature->id3, 'id4' => $acc_nature->id4,
            //             'id5' => $acc_nature->id5, 'nature' => $account->nature,
            //             'code1' => $acc_nature->code1, 'code2' => $acc_nature->code2,
            //             'code3' => $acc_nature->code3, 'code4' => $acc_nature->code4,
            //             'code5' => $acc_nature->code5, 'name_ar1' => $acc_nature->name_ar1,
            //             'name_ar2' => $acc_nature->name_ar2, 'name_ar3' => $acc_nature->name_ar3,
            //             'name_ar4' => $acc_nature->name_ar4, 'name_ar5' => $acc_nature->name_ar5,
            //             'name_en1' => $acc_nature->name_en1, 'name_en2' => $acc_nature->name_en2,
            //             'name_en3' => $acc_nature->name_en3, 'name_en4' => $acc_nature->name_en4,
            //             'name_en5' => $acc_nature->name_en5, 'level1' => $acc_nature->level1,
            //             'level2' => $acc_nature->level2, 'level3' => $acc_nature->level3,
            //             'level4' => $acc_nature->level4, 'level5' => $acc_nature->level5,
            //             'journal_date' => $request->from_date,
            //             'debit' => $acc_nature->debit,
            //             'credit' => $acc_nature->credit, 'balance' => $balance,
            //             'notes' => 'رصيد سابق'
            //         ]);
            //     }


            // } else {

            if ($request->acc_level == 4) {

                $acc_natures_2 = AccountTree::where('id4', request()->acc_id)->get();

                foreach ($acc_natures_2 as $acc_nature_2) {

                    $this->sum_credit = 0;
                    $this->sum_debit = 0;

                    $query = Voucher::where('id5', $acc_nature_2->id5);
                    $from_date = $request->from_date;

                    $journals = $query->whereIn('company_id', $companies_id)
                        ->whereIn('system_code', [901, 903])
                        ->where("journal_date", '<', $from_date)
                        ->orderBy('journal_date', 'ASC')->get();

                    $this->sum_credit = $journals->sum('credit');
                    $this->sum_debit = $journals->sum('debit');


                    if ($acc_nature_2->nature == 'Debit' || $acc_nature_2->nature == 'debit') {
                        $balance = $this->sum_debit - $this->sum_credit;
                        if ($balance >= 0) {
                            $acc_nature_2['debit'] = $balance;
                            $acc_nature_2['credit'] = 0;
                        } else {
                            $acc_nature_2['debit'] = 0;
                            $acc_nature_2['credit'] = $balance;
                        }
                    } else {
                        $balance = $this->sum_credit - $this->sum_debit;
                        if ($balance >= 0) {
                            $acc_nature_2['debit'] = 0;
                            $acc_nature_2['credit'] = $balance;
                        } else {
                            $acc_nature_2['debit'] = abs($balance);
                            $acc_nature_2['credit'] = 0;
                        }
                    }

                    if ($balance != 0) {
                        GlDetail::create([
                            'gl_header_id' => $gl_header->gl_header_id,
                            'company_group_id' => $company->company_group_id,
                            'company_group_ar' => $company->companyGroup->company_group_ar,
                            'company_group_en' => $company->companyGroup->company_group_en,
                            'id1' => $acc_nature_2->id1, 'id2' => $acc_nature_2->id2,
                            'id3' => $acc_nature_2->id3, 'id4' => $acc_nature_2->id4,
                            'id5' => $acc_nature_2->id5, 'nature' => $acc_nature_2->nature,
                            'code1' => $acc_nature_2->code1, 'code2' => $acc_nature_2->code2,
                            'code3' => $acc_nature_2->code3, 'code4' => $acc_nature_2->code4,
                            'code5' => $acc_nature_2->code5, 'name_ar1' => $acc_nature_2->name_ar1,
                            'name_ar2' => $acc_nature_2->name_ar2, 'name_ar3' => $acc_nature_2->name_ar3,
                            'name_ar4' => $acc_nature_2->name_ar4, 'name_ar5' => $acc_nature_2->name_ar5,
                            'name_en1' => $acc_nature_2->name_en1, 'name_en2' => $acc_nature_2->name_en2,
                            'name_en3' => $acc_nature_2->name_en3, 'name_en4' => $acc_nature_2->name_en4,
                            'name_en5' => $acc_nature_2->name_en5, 'level1' => $acc_nature_2->level1,
                            'level2' => $acc_nature_2->level2, 'level3' => $acc_nature_2->level3,
                            'level4' => $acc_nature_2->level4, 'level5' => $acc_nature_2->level5,
                            'journal_date' => $request->from_date,
                            // 'journal_date' => $journal->journal_date,
                            'notes' => 'رصيد سابق', 'debit' => 0,
                            'credit' => 0, 'balance' => $balance,
                            'journal_type_id' => $acc_nature_2->journal_type_id,
                            'journal_hd_code' => $acc_nature_2->journal_hd_code,
                            'cost_center_id' => $acc_nature_2->cost_center_id,
                            'cc_supplier_id' => $acc_nature_2->cc_supplier_id ? $acc_nature_2->cc_supplier_id : '',
                            'cc_customer_id' => $acc_nature_2->cc_customer_id ? $acc_nature_2->cc_customer_id : '',
                            'cc_employee_id' => $acc_nature_2->cc_employee_id ? $acc_nature_2->cc_employee_id : '',
                            'cc_car_id' => $acc_nature_2->cc_car_id ? $acc_nature_2->cc_car_id : '',
                            'cc_branch_id' => $acc_nature_2->cc_branch_id ? $acc_nature_2->cc_branch_id : '',
                            'cc_voucher_id' => $acc_nature_2->cc_voucher_id ? $acc_nature_2->cc_voucher_id : '',
                        ]);
                    }

                }

            } else {
                $acc_natures_2 = AccountTree::where('id5', request()->acc_id)->get();

                foreach ($acc_natures_2 as $acc_nature_2) {

                    $this->sum_credit = 0;
                    $this->sum_debit = 0;

                    $query = Voucher::where('id5', $acc_nature_2->id5);
                    $from_date = $request->from_date;
                    $journals = $query->whereIn('company_id', $companies_id)
                        ->whereIn('system_code', [901, 903])
                        ->where("journal_date", '<', $from_date)
                        ->orderBy('journal_date', 'ASC');


                    $this->sum_credit = $journals->sum('credit');
                    $this->sum_debit = $journals->sum('debit');


                    if ($acc_nature_2->nature == 'Debit' || $acc_nature_2->nature == 'debit') {
                        $balance = $this->sum_debit - $this->sum_credit;
                        if ($balance >= 0) {
                            $acc_nature_2['debit'] = $balance;
                            $acc_nature_2['credit'] = 0;
                        } else {
                            $acc_nature_2['debit'] = 0;
                            $acc_nature_2['credit'] = $balance;
                        }
                    } else {
                        $balance = $this->sum_credit - $this->sum_debit;
                        if ($balance >= 0) {
                            $acc_nature_2['debit'] = 0;
                            $acc_nature_2['credit'] = $balance;
                        } else {
                            $acc_nature_2['debit'] = abs($balance);
                            $acc_nature_2['credit'] = 0;
                        }
                    }

                }

                if ($balance != 0) {
                    GlDetail::create([
                        'gl_header_id' => $gl_header->gl_header_id,
                        'company_group_id' => $company->company_group_id,
                        'company_group_ar' => $company->companyGroup->company_group_ar,
                        'company_group_en' => $company->companyGroup->company_group_en,
                        'id1' => $acc_nature_2->id1, 'id2' => $acc_nature_2->id2,
                        'id3' => $acc_nature_2->id3, 'id4' => $acc_nature_2->id4,
                        'id5' => $acc_nature_2->id5, 'nature' => $acc_nature_2->nature,
                        'code1' => $acc_nature_2->code1, 'code2' => $acc_nature_2->code2,
                        'code3' => $acc_nature_2->code3, 'code4' => $acc_nature_2->code4,
                        'code5' => $acc_nature_2->code5, 'name_ar1' => $acc_nature_2->name_ar1,
                        'name_ar2' => $acc_nature_2->name_ar2, 'name_ar3' => $acc_nature_2->name_ar3,
                        'name_ar4' => $acc_nature_2->name_ar4, 'name_ar5' => $acc_nature_2->name_ar5,
                        'name_en1' => $acc_nature_2->name_en1, 'name_en2' => $acc_nature_2->name_en2,
                        'name_en3' => $acc_nature_2->name_en3, 'name_en4' => $acc_nature_2->name_en4,
                        'name_en5' => $acc_nature_2->name_en5, 'level1' => $acc_nature_2->level1,
                        'level2' => $acc_nature_2->level2, 'level3' => $acc_nature_2->level3,
                        'level4' => $acc_nature_2->level4, 'level5' => $acc_nature_2->level5,
                        'journal_date' => $request->from_date,
                        // 'journal_date' => $journal->journal_date,
                        'notes' => 'رصيد سابق', 'debit' => 0,
                        'credit' => 0, 'balance' => $balance,
                        'journal_type_id' => $acc_nature_2->journal_type_id,
                        'journal_hd_code' => $acc_nature_2->journal_hd_code,
                        'cost_center_id' => $acc_nature_2->cost_center_id,
                        'cc_supplier_id' => $acc_nature_2->cc_supplier_id ? $acc_nature_2->cc_supplier_id : '',
                        'cc_customer_id' => $acc_nature_2->cc_customer_id ? $acc_nature_2->cc_customer_id : '',
                        'cc_employee_id' => $acc_nature_2->cc_employee_id ? $acc_nature_2->cc_employee_id : '',
                        'cc_car_id' => $acc_nature_2->cc_car_id ? $acc_nature_2->cc_car_id : '',
                        'cc_branch_id' => $acc_nature_2->cc_branch_id ? $acc_nature_2->cc_branch_id : '',
                        'cc_voucher_id' => $acc_nature_2->cc_voucher_id ? $acc_nature_2->cc_voucher_id : '',
                    ]);
                }
            }

            // }

            $query_trans = $request->acc_level == 4 ? Voucher::where('id4', request()->acc_id)
                : Voucher::where('id5', request()->acc_id);

            $trans = $query_trans->whereIn('company_id', $companies_id)
                ->where('system_code', 903)
                ->whereBetween('journal_date', [Carbon::parse($request->from_date)->toDateString(),
                    Carbon::parse($request->to_date)->toDateString()])
                ->orderBy('journal_date', 'ASC')->orderBy('journal_dt_id', 'ASC')->get();

            foreach ($trans as $tran) {
                //  return $tran;
                $company_trans = Company::where('company_id', $tran->company_id)->first();

                GlDetail::create([
                    'gl_header_id' => $gl_header->gl_header_id,
                    'company_group_id' => $company_trans->company_group_id,
                    'company_group_ar' => $company_trans->companyGroup->company_group_ar,
                    'company_group_en' => $company_trans->companyGroup->company_group_en,
                    'company_id' => $company_trans->company_id,
                    'company_name_ar' => $company_trans->company_name_ar,
                    'company_name_en' => $company_trans->company_name_en,
                    'id1' => $tran->id1, 'id2' => $tran->id2,
                    'id3' => $tran->id3, 'id4' => $tran->id4,
                    'id5' => $tran->id5, 'nature' => $tran->nature,
                    'code1' => $tran->code1, 'code2' => $tran->code2,
                    'code3' => $tran->code3, 'code4' => $tran->code4,
                    'code5' => $tran->code5, 'name_ar1' => $tran->name_ar1,
                    'name_ar2' => $tran->name_ar2, 'name_ar3' => $tran->name_ar3,
                    'name_ar4' => $tran->name_ar4, 'name_ar5' => $tran->name_ar5,
                    'name_en1' => $tran->name_en1, 'name_en2' => $tran->name_en2,
                    'name_en3' => $tran->name_en3, 'name_en4' => $tran->name_en4,
                    'name_en5' => $tran->name_en5, 'level1' => $tran->level1,
                    'level2' => $tran->level2, 'level3' => $tran->level3,
                    'level4' => $tran->level4, 'level5' => $tran->level5,
                    'journal_date' => $tran->journal_date,
                    'notes' => $tran->notes, 'debit' => $tran->debit,
                    'credit' => $tran->credit, 'balance' => $tran->balance,
                    'journal_type_id' => $tran->journal_type_id,
                    'journal_hd_code' => $tran->journal_hd_code,
                    'journal_hd_id' => $tran->journal_hd_id,
                    'cost_center_id' => $tran->cost_center_id,
                    'cc_supplier_id' => $tran->cc_supplier_id ? $tran->cc_supplier_id : '',
                    'cc_customer_id' => $tran->cc_customer_id ? $tran->cc_customer_id : '',
                    'cc_employee_id' => $tran->cc_employee_id ? $tran->cc_employee_id : '',
                    'cc_car_id' => $tran->cc_car_id ? $tran->cc_car_id : '',
                    'cc_branch_id' => $tran->cc_branch_id ? $tran->cc_branch_id : '',
                    'cc_voucher_id' => $tran->cc_voucher_id ? $tran->cc_voucher_id : ''
                ]);
            }

            $details = GlDetail::where('gl_header_id', $gl_header->gl_header_id)
                ->orderBy('gl_detail_id', 'ASC')->get();


            foreach ($details->groupBy('id5') as $k => $detail) {

                if ($account->nature == 'Debit' || $account->nature == 'debit') {
                    $total = $balance;
                } else {
                    $total = $balance;
                }

                foreach ($detail as $dt) {
                    $account = Account::find($request->acc_id);

                    if ($account->nature == 'Debit' || $account->nature == 'debit') {
                        $total = $total + ($dt->debit - $dt->credit);
                    } else {
                        $total = $total + ($dt->credit - $dt->debit);
                    }
                    if ($dt->journal_hd_code > '0') {
                        $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);
                    }

                }
            }


            $report_url_5 = CompaniesMenuReport::where('report_code', 93002)
                ->where('company_group_id', $company->company_group_id)->first();
            $report_url_4 = CompaniesMenuReport::where('report_code', 93001)
                ->where('company_group_id', $company->company_group_id)->first();

            \DB::commit();

//            "{{config('app.telerik_server')}}?rpt={{$invoice->report_url_car->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
            if ($request->acc_level == 4) {
                return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
                    $report_url_4->report_url . '&id=' . $gl_header->gl_header_id . '&lang=ar&skinName=bootstrap']);
            } else {
                return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
                    $report_url_5->report_url . '&id=' . $gl_header->gl_header_id . '&lang=ar&skinName=bootstrap']);
            }


//            return response()->json(['data' => $gl_header->gl_header_id]);
        } elseif ($request->report_id == 93001) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $report_id = $request->report_id;
            return $this->generalReport($request->acc_level, $request->acc_id, $companies_id, $from_date, $to_date, $report_id);
        } elseif ($request->report_id == 93006) { ////ميزان المراجعه

            $level = $request->acc_level;
            $company = session('company') ? session('company') : auth()->user()->company;
            $company_group = CompanyGroup::where('company_group_id', $company->company_group_id)->first();
            $report_id = $request->report_id;
            $is_zero = $request->is_zero ? $request->is_zero : 0;
            return $trial_balance = $this->storeTrialBalance($level, $company_group->company_group_id, $companies_id,
                $request->from_date, $request->to_date, $report_id, $is_zero);
        } elseif ($request->report_id == 93005) { ////تقرير مركز تحليلي

            ////////////////////

            //  return $request->all();
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $cost_center_id = $request->cost_center_id;
            $cc_supplier_id = $request->cc_supplier_id ? $request->cc_supplier_id : '';
            $cc_customer_id = $request->cc_customer_id ? $request->cc_customer_id : '';
            $cc_employee_id = $request->cc_employee_id ? $request->cc_employee_id : '';
            $cc_car_id = $request->cc_car_id ? $request->cc_car_id : '';
            $cc_branch_id = $request->cc_branch_id ? $request->cc_branch_id : '';

            $companies_id = json_decode($request->company_id);
            $accs_id_all = json_decode($request->acc_id_dt);

            $gl_header_id = $this->storeCostCenterBalance($companies_id, $from_date, $to_date, $accs_id_all,
                $cost_center_id, $current_year, $cc_supplier_id,
                $cc_customer_id, $cc_employee_id, $cc_car_id, $cc_branch_id);


            $report_url = CompaniesMenuReport::where('report_code', $request->report_id)
                ->where('company_group_id', $company->company_group_id)->first();

//            "{{config('app.telerik_server')}}?rpt={{$invoice->report_url_car->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"


            return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
                $report_url->report_url . '&id=' . $gl_header_id . '&lang=ar&skinName=bootstrap']);

        } elseif ($request->report_id == 93008) { ////تقرير مركز تحليلي بالحساب

            ////////////////////

            //  return $request->all();
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $cost_center_id = $request->cost_center_id;
            $cc_supplier_id = $request->cc_supplier_id ? $request->cc_supplier_id : '';
            $cc_customer_id = $request->cc_customer_id ? $request->cc_customer_id : '';
            $cc_employee_id = $request->cc_employee_id ? $request->cc_employee_id : '';
            $cc_car_id = $request->cc_car_id ? $request->cc_car_id : '';
            $cc_branch_id = $request->cc_branch_id ? $request->cc_branch_id : '';

            $companies_id = json_decode($request->company_id);
            $accs_id_all = json_decode($request->acc_id_dt);
            $accin_id_all = implode(',', request()->input('acc_id_dts', []));
//            $acc_id_all = $request->acc_id_dt;

            //  implode(',',request()->input('acc_id_dts',[]))
            $gl_header_id = $this->storeCostCenterAccBalance($companies_id, $from_date, $to_date, $accs_id_all,
                $cost_center_id, $current_year, $cc_supplier_id,
                $cc_customer_id, $cc_employee_id, $cc_car_id, $cc_branch_id);


            $report_url = CompaniesMenuReport::where('report_code', $request->report_id)
                ->where('company_group_id', $company->company_group_id)->first();

            //            "{{config('app.telerik_server')}}?rpt={{$invoice->report_url_car->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"


            return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
                $report_url->report_url . '&id=' . $gl_header_id . '&acc_id=' . $accin_id_all . '&lang=ar&skinName=bootstrap']);


        } elseif ($request->report_id == 93007) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $id = $this->storeProfitsAndLossesBalance($request->fin_sub_type_id, $companies_id, $from_date, $to_date);
            $report_url = CompaniesMenuReport::where('report_code', $request->report_id)
                ->where('company_group_id', $company->company_group_id)->first();

            return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
                $report_url->report_url . '&id=' . $id . '&lang=ar&skinName=bootstrap']);

        } elseif ($request->report_id == 93013) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            // $id = $this->storeProfitsAndLossesBalance($request->fin_sub_type_id, $companies_id, $from_date, $to_date);
            $report_url = CompaniesMenuReport::where('report_code', $request->report_id)
                ->where('company_group_id', $company->company_group_id)->first();

            return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
                $report_url->report_url . '&date_from=' . $from_date . '&date_to=' . $to_date . '&id=' . $company->company_id . '&lang=ar&skinName=bootstrap']);


        } else {
            return response()->json(['status' => 500, 'message' => 'لا يمكن اضافه هذا النوع']);
        }


    }


    ////ميزان المراجعه
    public function storeTrialBalance($level, $company_group_id, $companies_id, $from_date, $to_date,
                                      $report_id, $is_zero)
    {

//        $accounts = Account::where('acc_level', $level)
//            ->where('company_group_id', $company_group_id)->get();


        $user = Auth::user();

        \DB::beginTransaction();

        if (TrialBalanceHeader::Where('user_id', $user->user_id)->exists()) {
            $tr_header = TrialBalanceHeader::Where('user_id', $user->user_id)->first();
            TrialBalanceDetail::Where('trial_balance_header_id', $tr_header->id)->delete();
            $tr_header->delete();
        }

        $header = TrialBalanceHeader::create([
            'user_id' => $user->user_id,
            'level' => $level,
            'is_zero' => $is_zero,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'company_group_id' => $company_group_id,
            'company_id' => json_encode($companies_id)
        ]);

        DB::table('accounts')->orderBy('acc_id')->where('acc_level', $level)
            ->where('company_group_id', $company_group_id)->chunk(5, function ($accounts) use (
                $header, $from_date,
                $to_date, $companies_id
            ) {
                foreach ($accounts as $account) {
                    $detail = TrialBalanceDetail::create([
                        'trial_balance_header_id' => $header->id,
                        'account_id' => $account->acc_id,
                        'account_name' => $account->acc_name_ar,
                        'code' => $account->acc_code,
                        'level' => $account->acc_level,
                        'nature' => $account->nature,
                        'main_type_id' => $account->acc_type,
                    ]);

                    $this->getOpening($account, $from_date, $detail, $companies_id);

                    $this->getTransaction($account, $from_date, $to_date, $detail, $companies_id);

                    $this->setBalance($detail, $account);
                }
            });

        $report_url = CompaniesMenuReport::where('report_code', $report_id)
            ->where('company_group_id', $company_group_id)->first();

        \DB::commit();

        //  return response()->json(['data' => $report_url->report_url . '?header_id=' . $header->id]);
        // return response()->json(['data' => 'report_url' . '?header_id=' . $header->id]);
        return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
            $report_url->report_url . '&id=' . $header->id . '&lang=ar&skinName=bootstrap']);

    }


    function getOpening($account, $from_date, $detail, $companies_id)
    {

        $year = Carbon::createFromFormat('Y-m-d', $from_date)->year;
        $op_builder = null;

        $op_builder = OpeningVoucher::where('account_id', $account->acc_id)
            ->where('journal_date', $from_date)
            ->whereIn('company_id', $companies_id);

        $voucher_builder = Voucher::where('account_id', $account->acc_id)
            ->whereIn('company_id', $companies_id);


        if ($from_date == $year . '-01-01') {
            if ($op_builder->exists()) {
                //get the OP
                //calculate Op balances
                $op = $op_builder->get();

                $detail->update([
                    'opening_balance_debit' => $op->sum('debit'),
                    'opening_balance_credit' => $op->sum('credit'),
                    'opening_balance_sign' => $op->sum('balance')
                ]);
            }
        } else {

            $start_open = $year . '-01-01';
            $end_open = Carbon::createFromFormat('Y-m-d', $from_date)->subDay();
            $end_open = $end_open->format('Y-m-d');

            if ($voucher_builder->whereBetween('journal_date', [$start_open, $end_open])->exists()) {

                $op = $voucher_builder->whereBetween('journal_date', [$start_open, $end_open])->get();

                // $sum_balance = $op->sum('balance');
//                return $sum_balance;


                if ($account->nature == 'Debit' || $account->nature == 'debit') {

                    $sum_balance = $op->sum('debit') - $op->sum('credit');

                } else {
                    $sum_balance = $op->sum('credit') - $op->sum('debit');
                }

                $this->sum_debit = 0;
                $this->sum_debit = 0;

                if ($account->nature == 'Debit' || $account->nature == 'debit') {
                    if ($sum_balance >= 0) {
                        $this->sum_debit = $sum_balance;
                        $this->sum_credit = 0;
                    } else {
                        $this->sum_debit = 0;
                        $this->sum_credit = ABS($sum_balance);
                    }
                } else {
                    if ($sum_balance >= 0) {
                        $this->sum_debit = 0;
                        $this->sum_credit = $sum_balance;
                    } else {
                        $this->sum_debit = ABS($sum_balance);
                        $this->sum_credit = 0;
                    }
                }

                $detail->update([
                    'opening_balance_debit' => $this->sum_debit,
                    'opening_balance_credit' => $this->sum_credit,
                    'opening_balance_sign' => $sum_balance
                ]);
            }
        }

    }


    function getTransaction($account, $from_date, $to_date, $detail, $companies_id)
    {
        $voucher_builder = Voucher::where('account_id', $account->acc_id)
            ->whereIn('company_id', $companies_id);

        $trans_builder = Voucher::where('account_id', $account->acc_id)
            ->where('system_code', '=', 903)
            ->whereIn('company_id', $companies_id);


        if ($voucher_builder->whereBetween('journal_date', [$from_date, $to_date])->exists()) {

            $trans = $trans_builder->whereBetween('journal_date', [$from_date, $to_date])->get();

            $trans_debit = $trans->sum('debit');
            $trans_credit = $trans->sum('credit');

            if ($account->nature == 'Debit' || $account->nature == 'debit') {

                $sum_balance = $trans_debit - $trans_credit;

            } else {
                $sum_balance = $trans_credit - $trans_debit;
            }

            $detail->update([
                'trans_debit' => $trans_debit,
                'trans_credit' => $trans_credit,
                'trans_balance_sign' => $sum_balance
            ]);

            $this->sum_debit = 0;
            $this->sum_credit = 0;

            if ($account->nature == 'Debit' || $account->nature == 'debit') {
                if ($sum_balance >= 0) {
                    $sum_debit = $sum_balance;
                    $sum_credit = 0;
                } else {
                    $sum_debit = 0;
                    $sum_credit = ABS($sum_balance);
                }
            } else {
                if ($sum_balance >= 0) {
                    $sum_debit = 0;
                    $sum_credit = $sum_balance;
                } else {
                    $sum_debit = ABS($sum_balance);
                    $sum_credit = 0;
                }
            }

            $detail->update([
                'trans_balance_debit' => $sum_debit,
                'trans_balance_credit' => $sum_credit,
            ]);
        }

    }


    function setBalance($detail, $account)
    {
        $detail = $detail->refresh();

        $this->balance_debit = 0;
        $this->balance_credit = 0;

        if ($account->nature = 'Debit' || $account->nature == 'debit') {
            $final_balance = ($detail->opening_balance_debit + $detail->trans_balance_debit)
                - ($detail->opening_balance_credit + $detail->trans_balance_credit);

            if ($final_balance > 0) {
                $this->balance_debit = $final_balance;
                $this->balance_credit = 0;
            } else {
                $this->balance_debit = 0;
                $this->balance_credit = ABS($final_balance);
            }
        } else {
            $final_balance = ($detail->opening_balance_credit + $detail->trans_balance_credit)
                - ($detail->opening_balance_debit + $detail->trans_balance_debit);
            if ($final_balance > 0) {
                $this->balance_debit = 0;
                $this->balance_credit = $final_balance;
            } else {
                $this->balance_debit = ABS($final_balance);
                $this->balance_credit = 0;
            }
        }
        $detail->update([
            'balance_debit' => $this->balance_debit,
            'balance_credit' => $this->balance_credit
        ]);
    }


    public function getCostCenterDts()
    {
        $company = session('company') ? session('company') : auth()->user()->company;

        if (request()->cost_center_id == 56005) {
            $branches = DB::table('branches')->whereIn('company_id', json_decode(request()->company_id))->get();
            return response()->json(['branches' => $branches]);
        }

        if (request()->cost_center_id == 56002) { ///عميل
            $customers = DB::table('customers')->whereIn('customer_category', [2, 3, 9])
                ->where('company_group_id', $company->company_group_id)->get();
            return response()->json(['customers' => $customers]);
        }


        if (request()->cost_center_id == 56001) { //مورد
            $suppliers = DB::table('customers')->where('customer_category', 1)
                ->where('company_group_id', $company->company_group_id)->get();
            return response()->json(['suppliers' => $suppliers]);
        }

        if (request()->cost_center_id == 56003) { //موظف
            $employees = DB::table('employees')->where('company_group_id', $company->company_group_id)->get();
            return response()->json(['employees' => $employees]);
        }

        if (request()->cost_center_id == 56004) { ///سياره
            $trucks = DB::table('trucks')->whereIn('company_id', json_decode(request()->company_id))->get();
            return response()->json(['trucks' => $trucks]);
        }
    }


    public function storeCostCenterBalance($companies_id, $from_date, $to_date, $acc_id_all,
                                           $cost_center_id, $current_year, $cc_supplier_id,
                                           $cc_customer_id, $cc_employee_id, $cc_car_id, $cc_branch_id)
    {

        // return $cc_customer_id;
        $gl_header = GlHeader::where('created_user', auth()->user()->user_id)->first();

        if (isset($gl_header)) {
            $gl_header->glDetails()->delete();
            $gl_header->delete();
        }

        $company = session('company') ? session('company') : auth()->user()->company;

        $gl_header = GlHeader::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => json_encode($companies_id),
            'from_date' => Carbon::parse($from_date)->format('d-m-Y'),
            'to_date' => Carbon::parse($to_date)->format('d-m-Y'),
            'created_user' => auth()->user()->user_id
        ]);


//         if (Carbon::parse($from_date)->format('d-m-Y') != '01-01-' . $current_year) {

//             $start = '01-01-' . $current_year;
//             $end = Carbon::parse($from_date)->subDays(1);

//             $this->sum_credit = 0;
//             $this->sum_debit = 0;


//             if ($cost_center_id == 56001) { //مورد
//                 $supplier = Customer::where('customer_id', $cc_supplier_id)->first();
//                 $query = Voucher::where(function ($q) use ($cc_supplier_id, $supplier) {
//                     return $q->where([
//                         ['cc_supplier_id', $cc_supplier_id],
//                         ['account_id', $supplier->supplier_account_id]
//                     ])
//                         ->orWhere([
//                             ['cc_customer_id', $cc_supplier_id],
//                             ['account_id', $supplier->supplier_account_id]
//                         ]);
//                 });
//             }

//             if ($cost_center_id == 56002) { //عميل
//                 $customer = Customer::where('customer_id', $cc_customer_id)->first();
//                 $query = Voucher::where(function ($q) use ($cc_customer_id, $customer) {
//                     return $q->where([
//                         ['cc_customer_id', $cc_customer_id],
//                         ['account_id', $customer->customer_account_id]
//                     ])
//                         ->orWhere([
//                             ['cc_supplier_id', $cc_customer_id],
//                             ['account_id', $customer->customer_account_id]
//                         ]);
//                 });

//             }


//             if ($cost_center_id == 56003) { //موظف
//                 $query = Voucher::where('cc_employee_id', $cc_employee_id);
//             }

//             if ($cost_center_id == 56004) { //سياره
//                 $query = Voucher::where('cc_car_id', $cc_car_id);
//             }

//             if ($cost_center_id == 56005) { //فرع
//                 $query = Voucher::where('cc_branch_id', $cc_branch_id);
//             }


//             $journals = $query->whereIn('company_id', $companies_id)
//                 ->where(function ($q) use ($start, $end, $current_year) {
//                     return $q->where('journal_date', '<=', $end->toDateString())
//                         ->where('journal_date', '>=', $start)
//                         ->where('system_code', '!=', 901)
//                         ->orWhere(function ($qu) use ($current_year) {
//                             return $qu->where('system_code', 901)
//                                 ->whereYear('journal_date', $current_year);
//                         });
//                 })->get();


//             $this->sum_credit = $journals->sum('credit');
//             $this->sum_debit = $journals->sum('debit');

//             foreach ($journals as $journal) {

//                 $balance = $this->sum_credit - $this->sum_debit;

//                 if ($balance >= 0) {
//                     $journal['debit'] = 0;
//                     $journal['credit'] = $balance;
//                 } else {
//                     $journal['debit'] = abs($balance);
//                     $journal['credit'] = 0;
//                 }
//             }

//             if ($this->sum_credit - $this->sum_debit != null) {
//                 $company_j = Company::where('company_id', $journal->company_id)->first();

//                 GlDetail::create([
//                     'gl_header_id' => $gl_header->gl_header_id,
//                     'company_group_id' => $company->company_group_id,
//                     'company_group_ar' => $company->companyGroup->company_group_ar,
//                     'company_group_en' => $company->companyGroup->company_group_en,
//                     //   'company_id' => $journal->company_id,
//                     //   'company_name_ar' => $company_j->company_name_ar,
//                     //  'company_name_en' => $company_j->company_name_en,
//                     'id1' => $journal->id1, 'id2' => $journal->id2,
//                     'id3' => $journal->id3, 'id4' => $journal->id4,
//                     'id5' => $journal->id5, 'nature' => $journal->account->nature,
//                     'code1' => $journal->code1, 'code2' => $journal->code2,
//                     'code3' => $journal->code3, 'code4' => $journal->code4,
//                     'code5' => $journal->code5, 'name_ar1' => $journal->name_ar1,
//                     'name_ar2' => $journal->name_ar2, 'name_ar3' => $journal->name_ar3,
//                     'name_ar4' => $journal->name_ar4, 'name_ar5' => $journal->name_ar5,
//                     'name_en1' => $journal->name_en1, 'name_en2' => $journal->name_en2,
//                     'name_en3' => $journal->name_en3, 'name_en4' => $journal->name_en4,
//                     'name_en5' => $journal->name_en5, 'level1' => $journal->level1,
//                     'level2' => $journal->level2, 'level3' => $journal->level3,
//                     'level4' => $journal->level4, 'level5' => $journal->level5,
//                     'journal_date' => $end,
// //                    'journal_date' => $journal->journal_date,
//                     'notes' => 'رصيد سابق', 'debit' => $journal->debit,
//                     'credit' => $journal->credit, 'balance' => $balance,
//                     'journal_type_id' => $journal->journal_type_id,
//                     'journal_hd_code' => $journal->journal_hd_code,
//                     'cost_center_id' => $journal->cost_center_id,
//                     'cc_supplier_id' => $journal->cc_supplier_id ? $journal->cc_supplier_id : '',
//                     'cc_customer_id' => $journal->cc_customer_id ? $journal->cc_customer_id : '',
//                     'cc_employee_id' => $journal->cc_employee_id ? $journal->cc_employee_id : '',
//                     'cc_car_id' => $journal->cc_car_id ? $journal->cc_car_id : '',
//                     'cc_branch_id' => $journal->cc_branch_id ? $journal->cc_branch_id : '',
//                     'cc_voucher_id' => $journal->cc_voucher_id ? $journal->cc_voucher_id : '',
//                 ]);
//             }
//         } //
//         else {
        $journals = Voucher::whereIn('company_id', $companies_id)
            ->whereIn('system_code', [901, 903])
            ->where('journal_date', '<', $from_date)
            ->orderBy('journal_date', 'ASC')->orderBy('journal_dt_id', 'ASC');

        if ($cost_center_id == 56001) { ///مورد
            $supplier = Customer::where('customer_id', $cc_supplier_id)->first();
            $journals = $journals->where(function ($q) use ($cc_supplier_id, $supplier) {
                return $q->where([
                    ['cc_supplier_id', $cc_supplier_id],
                    //  ['account_id', $supplier->supplier_account_id]
                ])
                    ->orWhere([
                        ['cc_customer_id', $cc_supplier_id],
                        //       ['account_id', $supplier->supplier_account_id]
                    ]);
            })->get();
        }

        if ($cost_center_id == 56002) { ///عميل
            $customer = Customer::where('customer_id', $cc_customer_id)->first();
            $journals = $journals->where(function ($q) use ($cc_customer_id, $customer) {
                return $q->where([
                    ['cc_customer_id', $cc_customer_id],
                    //    ['account_id', $customer->customer_account_id]
                ])
                    ->orWhere([
                        ['cc_supplier_id', $cc_customer_id],
                        //      ['account_id', $customer->customer_account_id]
                    ]);
            })->get();
        }

        if ($cost_center_id == 56003) { ///موظف
            $journals = $journals->where('cc_employee_id', $cc_employee_id)->get();
        }

        if ($cost_center_id == 56004) { ///سياره
            $journals = $journals->where('cc_car_id', $cc_car_id)->get();
        }

        if ($cost_center_id == 56005) { ///فرع
            $journals = $journals->where('cc_branch_id', $cc_branch_id)->get();
        }

        $this->sum_credit = $journals->sum('credit');
        $this->sum_debit = $journals->sum('debit');


        foreach ($journals as $journal) {

            $balance = $this->sum_credit - $this->sum_debit;
            if ($balance >= 0) {
                $journal['debit'] = 0;
                $journal['credit'] = $balance;
            } else {
                $journal['debit'] = abs($balance);
                $journal['credit'] = 0;
            }
        }


        if ($this->sum_credit - $this->sum_debit != null) {
            GlDetail::create([
                'gl_header_id' => $gl_header->gl_header_id,
                'company_group_id' => $company->company_group_id,
                'company_group_ar' => $company->companyGroup->company_group_ar,
                'company_group_en' => $company->companyGroup->company_group_en,
                //   'company_id' => $journal->company_id,
                //   'company_name_ar' => $company_j->company_name_ar,
                //  'company_name_en' => $company_j->company_name_en,
                'id1' => $journal->id1, 'id2' => $journal->id2,
                'id3' => $journal->id3, 'id4' => $journal->id4,
                'id5' => $journal->id5, 'nature' => $journal->account->nature,
                'code1' => $journal->code1, 'code2' => $journal->code2,
                'code3' => $journal->code3, 'code4' => $journal->code4,
                'code5' => $journal->code5, 'name_ar1' => $journal->name_ar1,
                'name_ar2' => $journal->name_ar2, 'name_ar3' => $journal->name_ar3,
                'name_ar4' => $journal->name_ar4, 'name_ar5' => $journal->name_ar5,
                'name_en1' => $journal->name_en1, 'name_en2' => $journal->name_en2,
                'name_en3' => $journal->name_en3, 'name_en4' => $journal->name_en4,
                'name_en5' => $journal->name_en5, 'level1' => $journal->level1,
                'level2' => $journal->level2, 'level3' => $journal->level3,
                'level4' => $journal->level4, 'level5' => $journal->level5,
                'journal_date' => $from_date,
//                    'journal_date' => $journal->journal_date,
                'notes' => 'رصيد سابق', 'debit' => $journal->debit,
                'credit' => $journal->credit, 'balance' => number_format(floatval($balance), 2, ".", ""),
                'journal_type_id' => $journal->journal_type_id,
                'journal_hd_code' => $journal->journal_hd_code,
                'cost_center_id' => $journal->cost_center_id,
                'cc_supplier_id' => $journal->cc_supplier_id ? $journal->cc_supplier_id : '',
                'cc_customer_id' => $journal->cc_customer_id ? $journal->cc_customer_id : '',
                'cc_employee_id' => $journal->cc_employee_id ? $journal->cc_employee_id : '',
                'cc_car_id' => $journal->cc_car_id ? $journal->cc_car_id : '',
                'cc_branch_id' => $journal->cc_branch_id ? $journal->cc_branch_id : '',
                'cc_voucher_id' => $journal->cc_voucher_id ? $journal->cc_voucher_id : '',
            ]);
        }
        //   }

        $trans_q = Voucher::whereIn('company_id', $companies_id)
            ->whereIn('system_code', [903])
            ->whereBetween('journal_date', [Carbon::parse($from_date)->toDateString(),
                Carbon::parse($to_date)->toDateString()])
            ->orderBy('journal_date', 'ASC')->orderBy('journal_dt_id', 'ASC');


        if ($cost_center_id == 56001) { ///مورد
            $trans = $trans_q->where(function ($q) use ($cc_supplier_id) {
                return $q->where('cc_supplier_id', $cc_supplier_id)
                    ->orWhere('cc_customer_id', $cc_supplier_id);
            })->get();
        }

        if ($cost_center_id == 56002) { ///عميل
            $trans = $trans_q->where(function ($q) use ($cc_customer_id) {
                return $q->where('cc_customer_id', $cc_customer_id)
                    ->orWhere('cc_supplier_id', $cc_customer_id);
            })->get();
        }

        if ($cost_center_id == 56003) { ///موظف
            $trans = $trans_q->where('cc_employee_id', $cc_employee_id)->get();
        }

        if ($cost_center_id == 56004) { ///سياره
            $trans = $trans_q->where('cc_car_id', $cc_car_id)->get();
        }

        if ($cost_center_id == 56005) { ///فرع
            $trans = $trans_q->where('cc_branch_id', $cc_branch_id)->get();
        }


        foreach ($trans as $tran) {
            //  return $tran;
            $company_trans = Company::where('company_id', $tran->company_id)->first();

            GlDetail::create([
                'gl_header_id' => $gl_header->gl_header_id,
                'company_group_id' => $company_trans->company_group_id,
                'company_group_ar' => $company_trans->companyGroup->company_group_ar,
                'company_group_en' => $company_trans->companyGroup->company_group_en,
                'company_id' => $company_trans->company_id,
                'company_name_ar' => $company_trans->company_name_ar,
                'company_name_en' => $company_trans->company_name_en,
                'id1' => $tran->id1, 'id2' => $tran->id2,
                'id3' => $tran->id3, 'id4' => $tran->id4,
                'id5' => $tran->id5, 'nature' => $tran->nature,
                'code1' => $tran->code1, 'code2' => $tran->code2,
                'code3' => $tran->code3, 'code4' => $tran->code4,
                'code5' => $tran->code5, 'name_ar1' => $tran->name_ar1,
                'name_ar2' => $tran->name_ar2, 'name_ar3' => $tran->name_ar3,
                'name_ar4' => $tran->name_ar4, 'name_ar5' => $tran->name_ar5,
                'name_en1' => $tran->name_en1, 'name_en2' => $tran->name_en2,
                'name_en3' => $tran->name_en3, 'name_en4' => $tran->name_en4,
                'name_en5' => $tran->name_en5, 'level1' => $tran->level1,
                'level2' => $tran->level2, 'level3' => $tran->level3,
                'level4' => $tran->level4, 'level5' => $tran->level5,
                'journal_date' => $tran->journal_date,
                'notes' => $tran->notes,
                'debit' => $tran->debit,
                'credit' => $tran->credit,
                'balance' => $tran->balance,
                'journal_type_id' => $tran->journal_type_id,
                'journal_hd_code' => $tran->journal_hd_code,
                'journal_hd_id' => $tran->journal_hd_id,
                'cost_center_id' => $tran->cost_center_id,
                'cc_supplier_id' => $tran->cc_supplier_id ? $tran->cc_supplier_id : '',
                'cc_customer_id' => $tran->cc_customer_id ? $tran->cc_customer_id : '',
                'cc_employee_id' => $tran->cc_employee_id ? $tran->cc_employee_id : '',
                'cc_car_id' => $tran->cc_car_id ? $tran->cc_car_id : '',
                'cc_branch_id' => $tran->cc_branch_id ? $tran->cc_branch_id : '',
                'cc_voucher_id' => $tran->cc_voucher_id ? $tran->cc_voucher_id : ''
            ]);
        }


        $details = GlDetail::where('gl_header_id', $gl_header->gl_header_id)->orderBy('journal_date', 'ASC')->get();


        if ($cost_center_id == 56001) { ///مورد
            $total = 0;
            $supplier = Customer::where('customer_id', $cc_supplier_id)->first();
            foreach ($details as $k => $dt) {

                if ($supplier->account->nature == 'debit' || $supplier->account->nature == 'Debit') {
                    $total = $total + ($dt->debit - $dt->credit);
                } else {
                    $total = $total + ($dt->credit - $dt->debit);
                }

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);

            }
        }

        if ($cost_center_id == 56002) { ///عميل
            $total = 0;
            $customer = Customer::where('customer_id', $cc_customer_id)->first();
            foreach ($details as $k => $dt) {
                if ($customer->account->nature == 'debit' || $customer->account->nature == 'Debit') {
                    $total = $total + ($dt->debit - $dt->credit);
                } else {
                    $total = $total + ($dt->credit - $dt->debit);
                }

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);

            }
        }

        if ($cost_center_id == 56003) { ///موظف
            $total = 0;
            foreach ($details as $k => $dt) {

                $total = $total + ($dt->debit - $dt->credit);

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);
            }
        }

        if ($cost_center_id == 56005) { ///فرع
            $total = 0;
            foreach ($details as $k => $dt) {

                $total = $total + ($dt->debit - $dt->credit);

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);
            }
        }


        return $gl_header_id = $gl_header->gl_header_id;
    }

///////////////////////////////////////////////////////////////////////////////////

    public function storeCostCenterAccBalance($companies_id, $from_date, $to_date, $acc_id_all,
                                              $cost_center_id, $current_year, $cc_supplier_id,
                                              $cc_customer_id, $cc_employee_id, $cc_car_id, $cc_branch_id)
    {

        // return $cc_customer_id;
        $gl_header = GlHeader::where('created_user', auth()->user()->user_id)->first();

        if (isset($gl_header)) {
            $gl_header->glDetails()->delete();
            $gl_header->delete();
        }

        $company = session('company') ? session('company') : auth()->user()->company;

        $gl_header = GlHeader::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => json_encode($companies_id),
            'from_date' => Carbon::parse($from_date)->format('d-m-Y'),
            'to_date' => Carbon::parse($to_date)->format('d-m-Y'),
            'created_user' => auth()->user()->user_id
        ]);


//         if (Carbon::parse($from_date)->format('d-m-Y') != '01-01-' . $current_year) {

//             $start = '01-01-' . $current_year;
//             $end = Carbon::parse($from_date)->subDays(1);

//             $this->sum_credit = 0;
//             $this->sum_debit = 0;


//             if ($cost_center_id == 56001) { //مورد
//                 $supplier = Customer::where('customer_id', $cc_supplier_id)->first();

//                 $query = Voucher::where(function ($q) use ($cc_supplier_id, $supplier) {
//                     return $q->where([
//                         ['cc_supplier_id', $cc_supplier_id],
//                         ['account_id', $supplier->supplier_account_id]
//                     ])
//                         ->orWhere([
//                             ['cc_customer_id', $cc_supplier_id],
//                             ['account_id', $supplier->supplier_account_id]
//                         ]);
//                 });

//             }

//             if ($cost_center_id == 56002) { //عميل
//                 $customer = Customer::where('customer_id', $cc_customer_id)->first();

//                 $query = Voucher::where(function ($q) use ($cc_customer_id, $customer) {
//                     return $q->where([
//                         ['cc_customer_id', $cc_customer_id],
//                         ['account_id', $customer->customer_account_id]
//                     ])
//                         ->orWhere([
//                             ['cc_supplier_id', $cc_customer_id],
//                             ['account_id', $customer->customer_account_id]
//                         ]);
//                 });
//             }

//             if ($cost_center_id == 56003) { //موظف
//                 $query = Voucher::where('cc_employee_id', $cc_employee_id);
//             }

//             if ($cost_center_id == 56004) { //سياره
//                 $query = Voucher::where('cc_car_id', $cc_car_id);
//             }

//             if ($cost_center_id == 56005) { //فرع
//                 $query = Voucher::where('cc_branch_id', $cc_branch_id);
//             }


//             $journals = $query->whereIn('company_id', $companies_id)
//                 ->whereIn('account_id', $acc_id_all)
//                 ->where(function ($q) use ($start, $end, $current_year) {
//                     return $q->where('journal_date', '<=', $end->toDateString())
//                         ->where('journal_date', '>=', $start)
//                         ->where('system_code', '!=', 901)
//                         ->orWhere(function ($qu) use ($current_year) {
//                             return $qu->where('system_code', 901)
//                                 ->whereYear('journal_date', $current_year);
//                         });
//                 })->get();


//             $this->sum_credit = $journals->sum('credit');
//             $this->sum_debit = $journals->sum('debit');


//             foreach ($journals as $journal) {

//                 $balance = number_format(floatval($this->sum_credit), 2, ".", "") - number_format(floatval($this->sum_debit), 2, ".", "");
//                 if ($balance >= 0) {
//                     $journal['debit'] = 0;
//                     $journal['credit'] = $balance;
//                 } else {
//                     $journal['debit'] = abs($balance);
//                     $journal['credit'] = 0;
//                 }

//             }

//             if ($this->sum_credit - $this->sum_debit != null) {
//                 GlDetail::create([
//                     'gl_header_id' => $gl_header->gl_header_id,
//                     'company_group_id' => $company->company_group_id,
//                     'company_group_ar' => $company->companyGroup->company_group_ar,
//                     'company_group_en' => $company->companyGroup->company_group_en,
//                     //   'company_id' => $journal->company_id,
//                     //   'company_name_ar' => $company_j->company_name_ar,
//                     //  'company_name_en' => $company_j->company_name_en,
//                     'id1' => $journal->id1, 'id2' => $journal->id2,
//                     'id3' => $journal->id3, 'id4' => $journal->id4,
//                     'id5' => $journal->id5, 'nature' => $journal->account->nature,
//                     'code1' => $journal->code1, 'code2' => $journal->code2,
//                     'code3' => $journal->code3, 'code4' => $journal->code4,
//                     'code5' => $journal->code5, 'name_ar1' => $journal->name_ar1,
//                     'name_ar2' => $journal->name_ar2, 'name_ar3' => $journal->name_ar3,
//                     'name_ar4' => $journal->name_ar4, 'name_ar5' => $journal->name_ar5,
//                     'name_en1' => $journal->name_en1, 'name_en2' => $journal->name_en2,
//                     'name_en3' => $journal->name_en3, 'name_en4' => $journal->name_en4,
//                     'name_en5' => $journal->name_en5, 'level1' => $journal->level1,
//                     'level2' => $journal->level2, 'level3' => $journal->level3,
//                     'level4' => $journal->level4, 'level5' => $journal->level5,
//                     'journal_date' => $end,
// //                    'journal_date' => $journal->journal_date,
//                     'notes' => 'رصيد سابق', 'debit' => $journal->debit,
//                     'credit' => $journal->credit, 'balance' => $balance,
//                     'journal_type_id' => $journal->journal_type_id,
//                     'journal_hd_code' => $journal->journal_hd_code,
//                     'cost_center_id' => $journal->cost_center_id,
//                     'cc_supplier_id' => $journal->cc_supplier_id ? $journal->cc_supplier_id : '',
//                     'cc_customer_id' => $journal->cc_customer_id ? $journal->cc_customer_id : '',
//                     'cc_employee_id' => $journal->cc_employee_id ? $journal->cc_employee_id : '',
//                     'cc_car_id' => $journal->cc_car_id ? $journal->cc_car_id : '',
//                     'cc_branch_id' => $journal->cc_branch_id ? $journal->cc_branch_id : '',
//                     'cc_voucher_id' => $journal->cc_voucher_id ? $journal->cc_voucher_id : '',
//                 ]);
//             }

//         } //
//         else {
        $journals = Voucher::whereIn('company_id', $companies_id)
            ->whereIn('system_code', [901, 903])->whereIn('account_id', $acc_id_all)
            ->where('journal_date', '<', $from_date)
            ->orderBy('journal_date', 'ASC')->orderBy('journal_dt_id', 'ASC');


        if ($cost_center_id == 56001) { ///مورد

            $supplier = Customer::where('customer_id', $cc_supplier_id)->first();
            $journals = $journals->where(function ($q) use ($cc_supplier_id, $supplier) {
                return $q->where([
                    ['cc_supplier_id', $cc_supplier_id],
                    //  ['account_id', $supplier->supplier_account_id]
                ])
                    ->orWhere([
                        ['cc_customer_id', $cc_supplier_id],
                        //       ['account_id', $supplier->supplier_account_id]
                    ]);
            })->get();
        }

        if ($cost_center_id == 56002) { ///عميل

            $customer = Customer::where('customer_id', $cc_customer_id)->first();
            $journals = $journals->where(function ($q) use ($cc_customer_id, $customer) {
                return $q->where([
                    ['cc_customer_id', $cc_customer_id],
                    //  ['account_id', $customer->customer_account_id]
                ])
                    ->orWhere([
                        ['cc_supplier_id', $cc_customer_id],
                        //      ['account_id', $customer->customer_account_id]
                    ]);
            })->get();
        }


        if ($cost_center_id == 56003) { ///موظف
            $journals = $journals->where('cc_employee_id', $cc_employee_id)->get();
        }

        if ($cost_center_id == 56004) { ///سياره
            $journals = $journals->where('cc_car_id', $cc_car_id)->get();
        }

        if ($cost_center_id == 56005) { ///فرع
            $journals = $journals->where('cc_branch_id', $cc_branch_id)->get();
        }

        $this->sum_credit = $journals->sum('credit');
        $this->sum_debit = $journals->sum('debit');

        foreach ($journals as $journal) {
            $balance = $this->sum_credit - $this->sum_debit;
            if ($balance >= 0) {
                $journal['debit'] = 0;
                $journal['credit'] = $balance;
            } else {
                $journal['debit'] = abs($balance);
                $journal['credit'] = 0;
            }
        }

        if ($this->sum_credit - $this->sum_debit != null) {
            GlDetail::create([
                'gl_header_id' => $gl_header->gl_header_id,
                'company_group_id' => $company->company_group_id,
                'company_group_ar' => $company->companyGroup->company_group_ar,
                'company_group_en' => $company->companyGroup->company_group_en,
                //   'company_id' => $journal->company_id,
                //   'company_name_ar' => $company_j->company_name_ar,
                //  'company_name_en' => $company_j->company_name_en,
                'id1' => $journal->id1, 'id2' => $journal->id2,
                'id3' => $journal->id3, 'id4' => $journal->id4,
                'id5' => $journal->id5, 'nature' => $journal->account->nature,
                'code1' => $journal->code1, 'code2' => $journal->code2,
                'code3' => $journal->code3, 'code4' => $journal->code4,
                'code5' => $journal->code5, 'name_ar1' => $journal->name_ar1,
                'name_ar2' => $journal->name_ar2, 'name_ar3' => $journal->name_ar3,
                'name_ar4' => $journal->name_ar4, 'name_ar5' => $journal->name_ar5,
                'name_en1' => $journal->name_en1, 'name_en2' => $journal->name_en2,
                'name_en3' => $journal->name_en3, 'name_en4' => $journal->name_en4,
                'name_en5' => $journal->name_en5, 'level1' => $journal->level1,
                'level2' => $journal->level2, 'level3' => $journal->level3,
                'level4' => $journal->level4, 'level5' => $journal->level5,
                'journal_date' => $from_date,
//                    'journal_date' => $journal->journal_date,
                'notes' => 'رصيد سابق', 'debit' => $journal->debit,
                'credit' => $journal->credit, 'balance' => number_format(floatval($balance), 2, ".", ""),
                'journal_type_id' => $journal->journal_type_id,
                'journal_hd_code' => $journal->journal_hd_code,
                'cost_center_id' => $journal->cost_center_id,
                'cc_supplier_id' => $journal->cc_supplier_id ? $journal->cc_supplier_id : '',
                'cc_customer_id' => $journal->cc_customer_id ? $journal->cc_customer_id : '',
                'cc_employee_id' => $journal->cc_employee_id ? $journal->cc_employee_id : '',
                'cc_car_id' => $journal->cc_car_id ? $journal->cc_car_id : '',
                'cc_branch_id' => $journal->cc_branch_id ? $journal->cc_branch_id : '',
                'cc_voucher_id' => $journal->cc_voucher_id ? $journal->cc_voucher_id : '',
            ]);
        }

        // }
        $trans_q = Voucher::whereIn('company_id', $companies_id)
            ->whereIn('system_code', [903])->whereIn('account_id', $acc_id_all)
            ->whereBetween('journal_date', [Carbon::parse($from_date)->toDateString(),
                Carbon::parse($to_date)->toDateString()])
            ->orderBy('journal_date', 'ASC')->orderBy('journal_dt_id', 'ASC');


        if ($cost_center_id == 56001) { ///مورد
            $trans = $trans_q->where(function ($q) use ($cc_supplier_id) {
                return $q->where('cc_supplier_id', $cc_supplier_id)
                    ->orWhere('cc_customer_id', $cc_supplier_id);
            })->get();
        }

        if ($cost_center_id == 56002) { ///عميل
            $trans = $trans_q->where(function ($q) use ($cc_customer_id) {
                return $q->where('cc_customer_id', $cc_customer_id)
                    ->orWhere('cc_supplier_id', $cc_customer_id);
            })->get();
        }


        if ($cost_center_id == 56003) { ///موظف
            $trans = $trans_q->where('cc_employee_id', $cc_employee_id)->get();
        }

        if ($cost_center_id == 56004) { ///سياره
            $trans = $trans_q->where('cc_car_id', $cc_car_id)->get();
        }

        if ($cost_center_id == 56005) { ///فرع
            $trans = $trans_q->where('cc_branch_id', $cc_branch_id)->get();
        }

        foreach ($trans as $tran) {
            //  return $tran;
            $company_trans = Company::where('company_id', $tran->company_id)->first();
            GlDetail::create([
                'gl_header_id' => $gl_header->gl_header_id,
                'company_group_id' => $company_trans->company_group_id,
                'company_group_ar' => $company_trans->companyGroup->company_group_ar,
                'company_group_en' => $company_trans->companyGroup->company_group_en,
                'company_id' => $company_trans->company_id,
                'company_name_ar' => $company_trans->company_name_ar,
                'company_name_en' => $company_trans->company_name_en,
                'id1' => $tran->id1, 'id2' => $tran->id2,
                'id3' => $tran->id3, 'id4' => $tran->id4,
                'id5' => $tran->id5, 'nature' => $tran->nature,
                'code1' => $tran->code1, 'code2' => $tran->code2,
                'code3' => $tran->code3, 'code4' => $tran->code4,
                'code5' => $tran->code5, 'name_ar1' => $tran->name_ar1,
                'name_ar2' => $tran->name_ar2, 'name_ar3' => $tran->name_ar3,
                'name_ar4' => $tran->name_ar4, 'name_ar5' => $tran->name_ar5,
                'name_en1' => $tran->name_en1, 'name_en2' => $tran->name_en2,
                'name_en3' => $tran->name_en3, 'name_en4' => $tran->name_en4,
                'name_en5' => $tran->name_en5, 'level1' => $tran->level1,
                'level2' => $tran->level2, 'level3' => $tran->level3,
                'level4' => $tran->level4, 'level5' => $tran->level5,
                'journal_date' => $tran->journal_date,
                'notes' => $tran->notes, 'debit' => $tran->debit,
                'credit' => $tran->credit, 'balance' => $tran->balance,
                'journal_type_id' => $tran->journal_type_id,
                'journal_hd_code' => $tran->journal_hd_code,
                'journal_hd_id' => $tran->journal_hd_id,
                'cost_center_id' => $tran->cost_center_id,
                'cc_supplier_id' => $tran->cc_supplier_id ? $tran->cc_supplier_id : '',
                'cc_customer_id' => $tran->cc_customer_id ? $tran->cc_customer_id : '',
                'cc_employee_id' => $tran->cc_employee_id ? $tran->cc_employee_id : '',
                'cc_car_id' => $tran->cc_car_id ? $tran->cc_car_id : '',
                'cc_branch_id' => $tran->cc_branch_id ? $tran->cc_branch_id : '',
                'cc_voucher_id' => $tran->cc_voucher_id ? $tran->cc_voucher_id : ''
            ]);
        }


        $details = GlDetail::where('gl_header_id', $gl_header->gl_header_id)->orderBy('gl_detail_id', 'ASC')->get();


        if ($cost_center_id == 56001) { ///مورد
            $total = 0;
            $supplier = Customer::where('customer_id', $cc_supplier_id)->first();
            foreach ($details as $k => $dt) {

                if ($supplier->account->nature == 'debit' || $supplier->account->nature == 'Debit') {
                    $total = $total + ($dt->debit - $dt->credit);
                } else {
                    $total = $total + ($dt->credit - $dt->debit);
                }

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);

            }
        }

        if ($cost_center_id == 56002) { ///عميل
            $total = 0;
            $customer = Customer::where('customer_id', $cc_customer_id)->first();
            foreach ($details as $k => $dt) {
//                return $details->groupBy('cc_customer_id')

                if ($customer->account->nature == 'debit' || $customer->account->nature == 'Debit') {
                    $total = $total + ($dt->debit - $dt->credit);
                } else {
                    $total = $total + ($dt->credit - $dt->debit);
                }

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);

            }
        }

        if ($cost_center_id == 56003) { ///موظف
            $total = 0;
            foreach ($details as $k => $dt) {

                $total = $total + ($dt->debit - $dt->credit);

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);

            }
        }

        if ($cost_center_id == 56005) { ///فرع
            $total = 0;
            foreach ($details as $k => $dt) {

                $total = $total + ($dt->debit - $dt->credit);

                $dt->update(['balance' => number_format(floatval($total), 2, ".", "")]);

            }
        }


        return $gl_header_id = $gl_header->gl_header_id;
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function storeProfitsAndLossesBalance($fin_sub_type_id, $companies_id, $from_date, $to_date)
    {
        $fin_sub_type = FinFormSubType::where('fin_sub_type_id', $fin_sub_type_id)->first();

        $fin_sub_type->balance_credit = 0;
        $fin_sub_type->balance_debit = 0;
        $fin_sub_type->save();

        $form_sub_types_ids = DB::table('fin_form_sub_type')->where('fin_sub_type_id', $fin_sub_type_id)
            ->whereIn('company_id', $companies_id)->pluck('fin_sub_type_id');

        $form_sub_type_dts_ids = DB::table('fin_form_sub_dt')->whereIn('fin_sub_type_id', $form_sub_types_ids)
            ->pluck('fin_sub_type_dt_id');

        $fin_form_accounts = FinFormAccount::whereIn('fin_sub_type_dt_id', $form_sub_type_dts_ids)
            ->get();

        foreach ($fin_form_accounts->groupBy('fin_sub_type_dt_id') as $k => $fin_form_account) {

            $fin_sub_type_dt = FinFormSubTypeDt::where('fin_sub_type_dt_id', $k)->first();
            $fin_sub_type_dt->balance_credit = 0;
            $fin_sub_type_dt->balance_debit = 0;
            $fin_sub_type_dt->save();

            $total = 0;
            foreach ($fin_form_account as $form_account) {

                $form_account->balance_credit = 0;
                $form_account->balance_debit = 0;
                $form_account->save();

                $journals = Voucher::where('account_id', $form_account->acc_account_id)
                    ->whereBetween('journal_date', [Carbon::parse($from_date)->toDateString(),
                        Carbon::parse($to_date)->toDateString()]);

                if ($form_account->account->nature == 'debit' || $form_account->account->nature == 'Debit') {
                    $form_account->balance_debit = $journals->sum('balance');
                    $form_account->save();

                    if ($form_account->sign == '+') {
                        $total += $journals->sum('balance');
                    } elseif ($form_account->sign == '-') {
                        $total -= $journals->sum('balance');
                    }
                }

                if ($form_account->account->nature == 'credit' || $form_account->account->nature == 'Credit') {
                    $form_account->balance_credit = $journals->sum('balance');
                    $form_account->save();

                    if ($form_account->sign == '+') {
                        $total += $journals->sum('balance');
                    } elseif ($form_account->sign == '-') {
                        $total -= $journals->sum('balance');
                    }
                }
            }

            if ($total < 0) {
                $fin_sub_type_dt->balance_credit = $total;
                $fin_sub_type_dt->save();
            } elseif ($total >= 0) {
                $fin_sub_type_dt->balance_debit = $total;
                $fin_sub_type_dt->save();
            }
        }

        $net_total = 0;
        foreach ($fin_sub_type->formSubTypeDts as $form_sub_type_dt) {
            if ($form_sub_type_dt->sign == '+') {
                $net_total += $form_sub_type_dt->balance_credit + $form_sub_type_dt->balance_debit;
            } elseif ($form_sub_type_dt->sign == '-') {
                $net_total -= $form_sub_type_dt->balance_credit + $form_sub_type_dt->balance_debit;
            }
        }

        if ($net_total >= 0) {
            $fin_sub_type->balance_debit = $net_total;
            $fin_sub_type->save();
        } elseif ($net_total < 0) {
            $fin_sub_type->balance_credit = $net_total;
            $fin_sub_type->save();
        }
        return $fin_sub_type->fin_sub_type_id;

    }


    /////////////تقرير الاستاذ العام
    public function generalReport($acc_level, $acc_id, $companies_id, $from_date, $to_date, $report_id)
    {

        //  \DB::beginTransaction();

        $company = session('company') ? session('company') : auth()->user()->company;

        $gl_header_old = GlHeader::where('created_user', auth()->user()->user_id)->first();

        if (isset($gl_header_old)) {
            $gl_header_old->glDetails()->delete();
            $gl_header_old->delete();
        }


        $gl_header = GlHeader::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => json_encode($companies_id),
            'from_date' => Carbon::parse($from_date),
            'to_date' => Carbon::parse($to_date),
            'acc_level' => $acc_level,
            'created_user' => auth()->user()->user_id
        ]);


        $account_c = $acc_level + 1;

        if ($acc_level == 0) {
            $accounts = Account::where('acc_level', 1)
                ->where('company_group_id', $company->company_group_id)->get();
        } else {
            $accounts = Account::where('acc_level', $account_c)
                ->where('acc_parent', $acc_id)
                ->where('company_group_id', $company->company_group_id)->get();
        }


        foreach ($accounts as $account) {

            $journals = DB::table('v_vouchers')->whereIn('company_id', $companies_id)
                ->where('id' . $account_c, $account->acc_id)
                ->whereBetween('journal_date', [Carbon::parse($from_date)->toDateString(),
                    Carbon::parse($to_date)->toDateString()])->whereIn('system_code', [901, 903])->get();

            $journals_previous = DB::table('v_vouchers')->whereIn('company_id', $companies_id)
                ->where('id' . $account_c, $account->acc_id)
                ->where('journal_date', '<', Carbon::parse($from_date)->toDateString())
                ->whereIn('system_code', [901, 903])->get();

                $trans_debit = $journals_previous->sum('debit' ) ;
                $trans_credit = $journals_previous->sum('credit' ) ;

                if ($account->nature == 'Debit' || $account->nature == 'debit') {

                    $sum_balance =  $trans_debit -  $trans_credit ;
    
                } else {
                    $sum_balance = $trans_credit - $trans_debit;
                }


            GlDetail::create([
                'gl_header_id' => $gl_header->gl_header_id,
                'company_group_id' => $company->company_group_id,
                'company_group_ar' => $company->companyGroup->company_group_ar,
                'company_group_en' => $company->companyGroup->company_group_en,
                'id1' => $account->acc_id,
                'nature' => $account->nature,
                'code1' => $account->acc_code,
                'name_ar1' => $account->acc_name_ar,
                'name_en1' => $account->acc_name_en,
                'level1' => $account->acc_level,
                'journal_date' => $from_date,
                'debit' => number_format(floatval($journals->sum('debit')), 2, ".", ""),
                'credit' => number_format(floatval($journals->sum('credit')), 2, ".", "") ,
                'balance' => number_format(floatval($sum_balance), 2, ".", ""),
            ]);
        }

        //return $gl_header->gl_header_id;

        $report_url = CompaniesMenuReport::where('report_code', $report_id)
            ->where('company_group_id', $company->company_group_id)->first();

        // \DB::commit();

        //  return response()->json(['data' => $report_url->report_url . '?header_id=' . $header->id]);
        // return response()->json(['data' => 'report_url' . '?header_id=' . $header->id]);
        return response()->json(['data' => config('app.telerik_server') . '?rpt=' .
            $report_url->report_url . '&id=' . $gl_header->gl_header_id . '&lang=ar&skinName=bootstrap']);

    }

}
