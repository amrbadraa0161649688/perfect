<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRent\Brand\BrandDtRequest;
use App\Models\Branch;
use App\Models\CarRentBrand;
use App\Models\CarRentBrandDt;
use App\Models\Company;
use Illuminate\Http\Request;

class CarRentBrandDtController extends Controller
{
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();
        $brands = CarRentBrand::get();

        $records = CarRentBrandDt::latest('brand_dt_id');
        if ($request->input('query')) {
            $records->where('brand_dt_name_ar', 'like', '%' . $request->input('query') . '%')
                ->orWhere('brand_dt_name_en', 'like', '%' . $request->input('query') . '%');
        }
//        if ($request->company_id) {
//            $records->where('company_id', $request->company_id);
//        }
        if ($request->brand_id) {
            $records->whereIn('brand_id', $request->brand_id);
        }
        $records = $records->paginate(EnumSetting::Paginate);

        return view('CarRent.BrandDts.index', compact('records', 'companies', 'brands'));
    }

    public function create()
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $brands = CarRentBrand::get();
        return view('CarRent.BrandDts.create', compact('brands'));
    }

    public function store(BrandDtRequest $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        CarRentBrandDt::create($request->except('_token', '_method') + [
                'company_id' => $company->company_id,
                'company_group_id' => $company->company_group_id,
            ]);
        return redirect(route('brandDts.index'));
    }

    public function edit($id)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $brands = CarRentBrand::get();
        $record = CarRentBrandDt::findOrFail($id);
        return view('CarRent.BrandDts.edit', compact('record','brands'));
    }

    public function update(BrandDtRequest $request, $id)
    {
        $record = CarRentBrandDt::findOrFail($id);
        $record->update($request->except('_method', '_token'));
        return redirect(route('brandDts.index'));
    }
}
