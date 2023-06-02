<?php

namespace App\Http\Controllers\CarRent;

use App\Enums\EnumSetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRent\Brand\BrandDtRequest;
use App\Http\Requests\CarRent\Brand\BrandRequest;
use App\Models\CarRentBrand;
use App\Models\Company;
use Illuminate\Http\Request;

class CarRentBrandController extends Controller
{
    public function index(Request $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        $companies = Company::where('company_group_id', $company->company_group_id)->get();

        $records = CarRentBrand::latest('brand_id');
        if ($request->input('query')) {
            $records->where('brand_name_ar', 'like', '%' . $request->input('query') . '%')
                ->orWhere('brand_name_en', 'like', '%' . $request->input('query') . '%');
        }
//        if ($request->company_id) {
//            $records->where('company_id', $request->company_id);
//        }
        $records = $records->paginate(EnumSetting::Paginate);

        return view('CarRent.Brands.index', compact('records', 'companies'));
    }

    public function create()
    {
        return view('CarRent.Brands.create');
    }

    public function store(BrandRequest $request)
    {
        $company = session('company') ? session('company') : auth()->user()->company;
        CarRentBrand::create($request->except('_token', '_method') + [
                'company_id' => $company->company_id,
                'company_group_id' => $company->company_group_id,
            ]);
        return redirect(route('brands.index'));
    }

    public function edit($id)
    {
        $record = CarRentBrand::findOrFail($id);
        return view('CarRent.Brands.edit', compact('record'));
    }

    public function update(BrandRequest $request, $id)
    {
        $record = CarRentBrand::findOrFail($id);
        $record->update($request->except('_method', '_token'));
        return redirect()->route('brands.index');
    }
}
