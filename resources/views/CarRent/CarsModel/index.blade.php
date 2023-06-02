@extends('Layouts.master')


@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="card-body">

        <div class="container-fluid">


            <div class="section-body mt-3" id="app">
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            @include('Includes.form-errors')
                        </div>
                        <div style="font-size: 14px ;font-weight: bold" class="card-body">
                            <form action="">
                                <div class="row">

                                    <div class="col-md-3 mb-3">
                                        @if(auth()->user()->user_type_id  == 1)
                                            <label> @lang('home.company_group')</label>
                                            <input type="text" class="form-control" value="{{app()->getLocale()=='ar' ? session('company_group')['company_group_ar'] :
                                             session('company_group')['company_group_en'] }}" readonly>
                                        @else
                                            <label> @lang('home.company_group')</label>
                                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                    {{ auth()->user()->companyGroup->company_group_ar }} @else
                                    {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                        @endif
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>@lang('home.companies')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="company_id[]" data-actions-box="true" required>

                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}"
                                                        @if(request()->company_id)
                                                            @foreach(request()->company_id  as $company_id)
                                                                @if($company_id == $company->company_id) selected @endif
                                                    @endforeach @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$company->company_name_ar}}
                                                    @else
                                                        {{$company->company_name_en}}
                                                    @endif
                                                </option>

                                            @endforeach

                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>@lang('carrent.car_brand')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="brand_hd[]" data-actions-box="true">

                                            @foreach($brand_hds as $brand_hd)
                                                <option value="{{$brand_hd->brand_id}}"
                                                        @if(request()->brand_hd)
                                                            @foreach(request()->brand_hd as $brand_h)
                                                                @if($brand_h  ==  $brand_hd->brand_id) selected @endif
                                                    @endforeach @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$brand_hd->brand_name_ar}}
                                                    @else
                                                        {{$brand_hd->brand_name_en}}
                                                    @endif
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label>@lang('home.car_model')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="brand_dt[]" data-actions-box="true">

                                            @foreach($brand_dts as $brand_dt)
                                                <option value="{{$brand_dt->brand_dt_id}}"
                                                        @if(request()->brand_dt)
                                                            @foreach(request()->brand_dt as $brand_d)
                                                                @if($brand_d  ==  $brand_dt->brand_dt_id) selected @endif
                                                    @endforeach @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$brand_dt->brand_dt_name_ar}}
                                                    @else
                                                        {{$brand_dt->brand_dt_name_en}}
                                                    @endif
                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    {{--تاريخ الشراء--}}
                                    <div class="col-md-3 mb-3">
                                        <label>@lang('carrent.car_purchase_date_from')</label>
                                        <input type="date" class="form-control" name="car_purchase_date_from"
                                               @if(request()->car_purchase_date_from) value="{{request()->car_purchase_date_from}}"
                                            @endif>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>@lang('carrent.car_purchase_date_to')</label>
                                        <input type="date" class="form-control" name="car_purchase_date_to"
                                               @if(request()->car_purchase_date_to) value="{{request()->car_purchase_date_to}}" @endif>
                                    </div>

                                    {{--<div class="col-md-3 mb-3">--}}
                                    {{--<label>@lang('home.type')</label>--}}
                                    {{--<select name="car_category_id[]"--}}
                                    {{--class="selectpicker" multiple data-live-search="true"--}}
                                    {{--data-actions-box="true"--}}
                                    {{--aria-label="Default select example" id="car_category_id">--}}
                                    {{--@foreach($sys_codes_status as $sys_code_status)--}}
                                    {{--<option value="{{$sys_code_status->system_code_id}}"--}}
                                    {{--@if(request()->car_category_id)--}}
                                    {{--@foreach(request()->car_category_id as $car_category_id)--}}
                                    {{--@if($car_category_id  ==  $sys_code_status->system_code_id) selected @endif--}}
                                    {{--@endforeach @endif>--}}
                                    {{--@if(app()->getLocale() == 'ar')--}}
                                    {{--{{$sys_code_status->system_code_name_ar}}--}}
                                    {{--@else--}}
                                    {{--{{$sys_code_status->system_code_name_en}}--}}
                                    {{--@endif--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--</div>--}}

                                    <div class="col-md-3 mb-3">
                                        <label> @lang('carrent.car_model_year')</label>
                                        <input type="text" name="car_model_year" class="form-control"
                                               placeholder="2020"
                                               @if(request()->car_model_year)  value="{{request()->car_model_year}}"
                                            @endif>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label> @lang('carrent.car_rent_model_code')</label>
                                        <input type="text" name="car_rent_model_code" class="form-control"
                                               placeholder="CAR-MODEL-6-12"
                                               @if(request()->car_rent_model_code)  value="{{request()->car_rent_model_code}}"
                                            @endif>
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                            <i class="fa fa-search"></i></button>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary">
                                <a href="{{ route('CarRentModel.create') }}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('carrent.add_car_model')
                                </a>
                            </button>
                        </div>
                        <div style="font-size: 14px ;font-weight: bold" class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                    <thead>
                                    <tr class="red"
                                        style="background-color: #ece5e7;font-size: 18px;font-style: inherit bold">
                                        <th>@lang('carrent.car_rent_model_code')</th>
                                        <th>@lang('home.logo')</th>
                                        <th>@lang('carrent.car_brand')</th>
                                        <th>@lang('home.car_model')</th>
                                        <th>@lang('carrent.car_model_year')</th>
                                        <th>@lang('carrent.car_category')</th>
                                        <th>@lang('carrent.car_purchase_date')</th>
                                        <th>@lang('carrent.car_status')</th>
                                        <th>@lang('carrent.car_qty')</th>
                                        <th>@lang('home.show')</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cars_model as $car_model)
                                        <tr>
                                            <td>{{ $car_model->car_rent_model_code }}</td>
                                            <td>
                                            @if($car_model->brand->brand_logo_url)
                                                <a href="{{$car_model->brand->brand_logo_url}}" target="_blank">
                                                    <img class="avatar avatar-blue"
                                                         src="{{$car_model->brand->brand_logo_url}}"></a>
                                            @endif
                                        </td>
                                            <td>{{ app()->getLocale() == 'ar'
                                        ? $car_model->brand->brand_name_ar
                                        : $car_model->brand->brand_name_en }}</td>

                                            <td>{{app()->getLocale()=='ar'  ?
                                $car_model->brandDetail->brand_dt_name_ar  :
                                $car_model->brandDetail->brand_dt_name_en }}</td>

                                            <td>{{ $car_model->car_model_year }}</td>

                                            <td>{{app()->getLocale()=='ar'
                                        ? $car_model->category->system_code_name_ar
                                        : $car_model->category->system_code_name_en }}
                                            </td>

                                            <td>{{Carbon\Carbon::parse($car_model->car_purchase_date)->format('Y-m-d')  }}</td>

                                            <td>{{ app()->getLocale() == 'ar'
                                        ? $car_model->status->system_code_name_ar
                                        : $car_model->status->system_code_name_en}}</td>

                                            <td>{{ $car_model->car_qty }}</td>
                                            <th>
                                                <a href="{{route('CarRentModel.edit' , $car_model->car_rent_model_id )}}"
                                                   class="btn btn-danger btn-sm"
                                                   title="@lang('home.edit')">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $cars_model->links() }}
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection

