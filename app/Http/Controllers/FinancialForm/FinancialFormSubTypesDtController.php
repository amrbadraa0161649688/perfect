<?php

namespace App\Http\Controllers\FinancialForm;

use App\Http\Controllers\Controller;
use App\Models\FinFormSubType;
use App\Models\FinFormSubTypeDt;
use Illuminate\Http\Request;

class FinancialFormSubTypesDtController extends Controller
{
    public function store(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        $fin_sub_type = FinFormSubType::find($request->fin_sub_type_id);
        $fin_form_sub_dt = FinFormSubTypeDt::where('fin_sub_type_id', $fin_sub_type->fin_sub_type_id)->latest()->first();

        if (isset($fin_form_sub_dt)) {
            $account_order = $fin_form_sub_dt->acc_order + 1;
        } else {
            $account_order = 1;
        }

        FinFormSubTypeDt::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'form_type_id' => $fin_sub_type->form_type_id,
            'fin_sub_type_id' => $request->fin_sub_type_id,
            'form_dt_name_ar' => $request->form_dt_name_ar,
            'form_dt_name_en' => $request->form_dt_name_en,
            'acc_order' => $account_order,
            'sign' => $request->sign,
        ]);

        return back()->with('FormSubType', $fin_sub_type->fin_sub_type_id);
    }


    public function update(Request $request, $id)
    {
        $fin_sub_dt = FinFormSubTypeDt::find($id);

        $fin_sub_dt->update([
            'form_dt_name_ar' => $request->form_dt_name_ar,
            'form_dt_name_en' => $request->form_dt_name_en,
            'sign' => $request->sign,
        ]);

        return back()->with('FormSubType', $fin_sub_dt->formSubType->fin_sub_type_id);
    }


    public function addAccOrder(Request $request)
    {
        $fin_form_sub_type_dt = FinFormSubTypeDt::find($request->fin_sub_type_dt_id);
        $fin_sub_type = FinFormSubType::find($fin_form_sub_type_dt->fin_sub_type_id);
        if ($request->order == 'up') {
            if ($fin_form_sub_type_dt->acc_order > 1) {
                $fin_form_sub_type_dt_old = FinFormSubTypeDt::where('acc_order', $fin_form_sub_type_dt->acc_order - 1)
                    ->where('fin_sub_type_id', $fin_form_sub_type_dt->fin_sub_type_id)->first();

                $fin_form_sub_type_dt_old->acc_order = $fin_form_sub_type_dt_old->acc_order + 1;
                $fin_form_sub_type_dt_old->save();

                $fin_form_sub_type_dt->acc_order = $fin_form_sub_type_dt->acc_order - 1;
                $fin_form_sub_type_dt->save();
            } else {
                return back()->with(['error' => 'لا يمكن تعديل الترتيب']);
            }

        } else {
            if ($fin_form_sub_type_dt->acc_order < $fin_sub_type->formSubTypeDts->count()) {
                $fin_form_sub_type_dt_old = FinFormSubTypeDt::where('acc_order', $fin_form_sub_type_dt->acc_order + 1)
                    ->where('fin_sub_type_id', $fin_form_sub_type_dt->fin_sub_type_id)->first();

                $fin_form_sub_type_dt_old->acc_order = $fin_form_sub_type_dt_old->acc_order - 1;
                $fin_form_sub_type_dt_old->save();
                $fin_form_sub_type_dt->acc_order = $fin_form_sub_type_dt->acc_order + 1;
                $fin_form_sub_type_dt->save();
            } else {
                return back()->with(['error' => 'لا يمكن تعديل الترتيب']);
            }

        }

        return back()->with('FormSubType', $fin_sub_type->fin_sub_type_id);
    }

    public function delete($id)
    {
        $form_sub_type_dt = FinFormSubTypeDt::find($id);
        if (count($form_sub_type_dt->finFormAccounts) == 0) {
            $form_sub_type_dt->delete();
            return back()->with('success', 'تم الحذف');
        } else {
            return back()->with('error', 'لا يمكن الحذف');
        }
    }
}
