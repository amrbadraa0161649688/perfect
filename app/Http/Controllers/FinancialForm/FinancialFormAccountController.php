<?php

namespace App\Http\Controllers\FinancialForm;

use App\Http\Controllers\Controller;
use App\Models\FinFormAccount;
use App\Models\FinFormSubType;
use App\Models\FinFormSubTypeDt;
use App\Models\SystemCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialFormAccountController extends Controller
{
    public function index($id)
    {
        $form_accounts = DB::table('fin_form_account')
            ->join('accounts', 'accounts.acc_id', '=', 'fin_form_account.acc_account_id')
            ->where('fin_sub_type_dt_id', $id)
            ->orderBy('acc_order')->get();
        $form_sub_type_dt = FinFormSubTypeDt::find($id);
        return view('FinancialForm.FormAccounts.index', compact('form_accounts', 'form_sub_type_dt'));
    }

    public function create($id)
    {
        $fin_sub_type_dt = FinFormSubTypeDt::find($id);
        return view('FinancialForm.FormAccounts.create', compact('fin_sub_type_dt'));
    }

    public function getAccountsList()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $accounts = DB::table('accounts')
            ->where('company_group_id', $company->company_group_id)
            ->where('acc_level', request()->acc_level)->get();
        return response()->json(['data' => $accounts]);
    }

    public function store(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $form_type = SystemCode::find($request->form_type_id);
        $form_sub_type = FinFormSubType::find($request->fin_sub_type_id);
        $form_sub_type_dt = FinFormSubTypeDt::find($request->fin_sub_type_dt_id);

        $fin_form_account = FinFormAccount::where('fin_sub_type_dt_id', $request->fin_sub_type_dt_id)->latest()->first();

        if (isset($fin_form_account)) {
            $account_order = $fin_form_account->acc_order + 1;
        } else {
            $account_order = 1;
        }

        FinFormAccount::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'form_type_id' => $request->form_type_id,
            'form_type_name_ar' => $form_type->system_code_name_ar,
            'form_type_name_en' => $form_type->system_code_name_en,
            'fin_sub_type_id' => $request->fin_sub_type_id,
            'form_sub_type_name_ar' => $form_sub_type->form_sub_type_name_ar,
            'form_sub_type_name_en' => $form_sub_type->form_sub_type_name_en,
            'fin_sub_type_dt_id' => $request->fin_sub_type_dt_id,
            'form_sub_type_dt_name_ar' => $form_sub_type_dt->form_sub_type_dt_name_ar,
            'form_sub_type_dt_name_en' => $form_sub_type_dt->form_sub_type_dt_name_en,
            'acc_account_id' => $request->acc_account_id,
            'sign' => $request->sign,
            'acc_order' => $account_order
        ]);

        return \Redirect::route('financial-account.index', [$form_sub_type_dt->fin_sub_type_dt_id])->with(['تم الاضافه']);
    }

    public function edit($id)
    {
        $fin_account = FinFormAccount::find($id);
        if (request()->ajax()) {
            return response()->json(['data' => $fin_account, 'acc_level' => $fin_account->account->acc_level,
                'account' => $fin_account->account]);
        }
        $fin_sub_type_dt = $fin_account->finFormSubTypeDt;
        return view('FinancialForm.FormAccounts.edit', compact('fin_sub_type_dt', 'id'));
    }

    public function update(Request $request, $id)
    {
//        return $request->all();
        $fin_account = FinFormAccount::find($id);
        $fin_account->update([
            'acc_account_id' => $request->acc_account_id,
            'sign' => $request->sign,
        ]);
        return redirect()->route('financial-account.index', $fin_account->fin_sub_type_dt_id)->with(['success' => 'تم التعديل']);
    }

    public function addAccOrder(Request $request)
    {

        if ($request->order == 'up') {

            $fin_form_account = FinFormAccount::find($request->fin_form_acc_id);
            if ($fin_form_account->acc_order > 1) {
                $fin_form_acc_old = FinFormAccount::where('acc_order', $fin_form_account->acc_order - 1)
                    ->where('fin_sub_type_dt_id', $fin_form_account->fin_sub_type_dt_id)->first();

                $fin_form_acc_old->acc_order = $fin_form_acc_old->acc_order + 1;
                $fin_form_acc_old->save();
                $fin_form_account->acc_order = $fin_form_account->acc_order - 1;
                $fin_form_account->save();
            } else {
                return back()->with(['error' => 'لا يمكن تعديل الترتيب']);
            }
        } else {
            $fin_form_account = FinFormAccount::find($request->fin_form_acc_id);
            $fin_sub_type_dt = FinFormSubTypeDt::find($fin_form_account->fin_sub_type_dt_id);

            if ($fin_form_account->acc_order < $fin_sub_type_dt->finFormAccounts->count()) {
                $fin_form_acc_old = FinFormAccount::where('acc_order', $fin_form_account->acc_order + 1)
                    ->where('fin_sub_type_dt_id', $fin_form_account->fin_sub_type_dt_id)->first();
                $fin_form_acc_old->acc_order = $fin_form_acc_old->acc_order - 1;
                $fin_form_acc_old->save();
                $fin_form_account->acc_order = $fin_form_account->acc_order + 1;
                $fin_form_account->save();
            } else {
                return back()->with(['error' => 'لا يمكن تعديل الترتيب']);
            }

        }

        return back();
    }


    public function delete($id)
    {
        $form_account = FinFormAccount::find($id);
        $form_account->delete();
        return back()->with('success', 'تم الحذف');

    }
}
