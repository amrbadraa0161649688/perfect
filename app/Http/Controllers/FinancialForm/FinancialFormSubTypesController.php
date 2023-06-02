<?php

namespace App\Http\Controllers\FinancialForm;

use App\Http\Controllers\Controller;
use App\Models\FinFormSubType;
use App\Models\SystemCode;
use Illuminate\Http\Request;

class FinancialFormSubTypesController extends Controller
{
    public function index()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $form_types = SystemCode::where('sys_category_id', 146)->where('company_group_id', $company->company_group_id)
            ->get();
        $fin_sub_types = FinFormSubType::latest()->get();
        return view('FinancialForm.FormSubTypes.index', compact('form_types', 'fin_sub_types'));
    }

    public function store(Request $request)
    {

        $company = session('company') ? session('company') : auth()->user()->company;
        FinFormSubType::create([
            'company_group_id' => $company->company_group_id,
            'company_id' => $company->company_id,
            'form_type_id' => $request->form_type_id,
            'form_name_ar' => $request->form_name_ar,
            'form_name_en' => $request->form_name_en,
        ]);

        return back()->with(['success' => 'تم الاضافه']);
    }

    public function update(Request $request, $id)
    {
        $form_sub_type = FinFormSubType::find($id);

        $form_sub_type->update([
            'form_type_id' => $request->form_type_id,
            'form_name_ar' => $request->form_name_ar,
            'form_name_en' => $request->form_name_en,
        ]);

        return back()->with(['success' => 'تم التعديل']);
    }

    public function delete($id)
    {
        $form_sub_type = FinFormSubType::find($id);
        if (count($form_sub_type->formSubTypeDts) == 0) {
            $form_sub_type->delete();
            return back()->with('success', 'تم الحذف');
        } else {
            return back()->with('error', 'لا يمكن الحذف');
        }
    }
}
