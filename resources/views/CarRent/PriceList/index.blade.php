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

    <div class="container-fluid">


        <div class="section-body mt-3" id="app">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        @include('Includes.form-errors')
                    </div>
                    <div style="font-size: 16px ;font-weight: bold " class="card-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-3  mb-3">
                                    <label>@lang('home.company_group')</label>
                                    @if(auth()->user()->user_type_id  == 1)
                                        <input type="text" class="form-control" value="{{app()->getLocale()=='ar' ? session('company_group')['company_group_ar'] :
                session('company_group')['company_group_en'] }}" readonly>
                                    @else
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                {{ auth()->user()->companyGroup->company_group_ar }} @else
                                {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                    @endif
                                </div>


                                <div class="col-md-3  mb-3">
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

                                <div class="col-md-3  mb-3">
                                    <label>@lang('home.branch')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]" data-actions-box="true">

                                        @foreach($branches as $branch)
                                            <option value="{{$branch->branch_id}}"
                                                    @if(request()->branch_id)
                                                        @foreach(request()->branch_id  as $branch_id)
                                                            @if($branch_id == $branch->branch_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$branch->branch_name_ar}}
                                                @else
                                                    {{$branch->branch_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3  mb-3">
                                    <label>@lang('home.car_models')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="car_rent_model_id[]" data-actions-box="true">
                                        @foreach($models as $model)
                                            <option value="{{$model->car_rent_model_id}}"
                                                    @if(request()->car_rent_model_id)
                                                        @foreach(request()->car_rent_model_id as $car_rent_model_id)
                                                            @if($car_rent_model_id  ==  $model->car_rent_model_id) selected @endif
                                                @endforeach @endif>
                                                {{$model->car_rent_model_code}},
                                                {{$model->car_model_year}},
                                                {{$model->brand?$model->brand['brand_name_'.app()->getLocale()]:''}},
                                                {{$model->brandDetails?$model->brandDetails['brand_dt_name_'.app()->getLocale()]:''}}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                {{--                                <div class="col-md-3  mb-3">--}}
                                {{--                                    <label> @lang('home.name')</label>--}}
                                {{--                                    <input type="text" name="customer_name_full" class="form-control"--}}
                                {{--                                           placeholder="Name"--}}
                                {{--                                           @if(request()->customer_name_full)  value="{{request()->customer_name_full}}"--}}
                                {{--                                        @endif>--}}
                                {{--                                </div>--}}

                                <div class="col-md-3  mb-3">
                                    <label>@lang('home.customer')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customer_id[]" data-actions-box="true">

                                        @foreach($customers as $customer)
                                            <option value="{{$customer->customer_id}}"
                                                    @if(request()->customer_id)
                                                        @foreach(request()->customer_id  as $customer_id)
                                                            @if($customer_id == $customer->customer_id) selected @endif
                                                @endforeach @endif>
                                                {{$customer['customer_name_full_'.app()->getLocale()]}}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3  mb-3">
                                    <label>@lang('home.customer_type')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="customer_type[]" data-actions-box="true">

                                        @foreach($sys_codes_type as $sys_code_type)
                                            <option value="{{$sys_code_type->system_code_id}}"
                                                    @if(request()->customer_type)
                                                        @foreach(request()->customer_type  as $sys_type)
                                                            @if($sys_type == $sys_code_type->system_code_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_type->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_type->system_code_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
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
                            <a href="{{ route('CarRentPriceList.create') }}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_price_list')
                            </a>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                <thead>
                                <tr class="red" style="background-color: #ece5e7">

                                    <th>@lang('home.price_list_serial')</th>
                                    <th hidden>@lang('home.company')</th>
                                    <th>@lang('home.branches')</th>
                                    <th>@lang('home.car_models')</th>
                                    <th>@lang('home.customer_category')</th>
                                    <th>@lang('home.customer')</th>
                                    <th>@lang('home.from')</th>
                                    <th>@lang('home.to')</th>
                                    <th>@lang('home.status')</th>


                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($car_price_lists as $car_price_list)
                                    <tr>

                                        <td>{{$car_price_list->rent_list_code}}</td>
                                        <td hidden>{{app()->getLocale() == 'ar'
                                ? $car_price_list->company->company_name_ar
                                : $car_price_list->company->company_name_en}}
                                        </td>
                                        <td style="font-size: 16px ;font-weight: bold ">
                                            {{--{{ dd($car_price_list->branches) }}--}}
                                            @if($car_price_list->price_branches)
                                                @foreach (json_decode($car_price_list->price_branches) as $price_branch)
                                                    @if($price_branch != null)

                                                        {{app()->getLocale() == 'ar'
                                                        ? App\Models\Branch::where('branch_id',$price_branch)
                                                        ->first()->branch_name_ar
                                                        : App\Models\Branch::where('branch_id',$price_branch)
                                                        ->first()->branch_name_en
                                                        }} ,
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td style="font-size: 16px ;font-weight: bold ">
                                            @foreach($car_price_list->priceListDetails as $price_list_dt)
                                                ({{$price_list_dt->model['car_model_year']}},
                                                {{$price_list_dt->brand?$price_list_dt->brand['brand_name_'.app()->getLocale()]:''}}
                                                ,
                                                {{$price_list_dt->brandDt?$price_list_dt->brandDt['brand_dt_name_'.app()->getLocale()]:''}}
                                                ),
                                            @endforeach
                                        </td>
                                        <td style="font-size: 16px ;font-weight: bold ">
                                            @if($car_price_list->customerType)
                                                {{app()->getLocale() == 'ar'
                                        ? $car_price_list->customerType->system_code_name_ar
                                        : $car_price_list->customerType->system_code_name_en}}
                                            @endif

                                        </td>
                                        <td style="font-size: 16px ;font-weight: bold ">@if($car_price_list->customer)
                                                {{app()->getLocale() == 'ar'
                                        ? $car_price_list->customer->customer_name_full_ar
                                        : $car_price_list->customer->customer_name_full_en}}
                                            @endif
                                        </td>
                                        <td style="font-size: 16px ;font-weight: bold ; color: blue">{{$car_price_list->rent_list_start_date}}</td>
                                        <td style="font-size: 16px ;font-weight: bold ; color: blue">{{$car_price_list->rent_list_end_date}}</td>
                                        <td style="font-size: 16px ;font-weight: bold ; color: red">
                                            @if($car_price_list->rent_list_status == 1)
                                                @lang('home.active')
                                            @else
                                                @lang('home.not_active')
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('CarRentPriceList.edit',$car_price_list->rent_list_id) }}"
                                               class="btn btn-primary btn-sm"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6">
                                    {{ $car_price_lists->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $car_price_lists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection
