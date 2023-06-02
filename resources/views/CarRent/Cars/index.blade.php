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
                <div class="col-lg-3 col-6">
                    <div class="card">
                        <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id}}"
                           class="my_sort_cut text-muted">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.all_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$cars->total()}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        @php
                            $statuses= '';
                                foreach($car_depose_status as $status){
                                    $statuses .= '&car_status_id[]='.$status;
                                }
                        @endphp
                        <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.$statuses}}"
                           class="my_sort_cut text-muted">
                            <div class="card-body">
                                <h6>{{__('home.car_depose_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$car_depose_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        @php
                            $statuses= '';
                                foreach($car_ready_status as $status){
                                    $statuses .= '&car_status_id[]='.$status;
                                }
                        @endphp
                        <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.$statuses}}"
                           class="my_sort_cut text-muted">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.car_ready_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$car_ready_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="card">
                        @php
                            $statuses= '';
                                foreach($car_rent_status as $status){
                                    $statuses .= '&car_status_id[]='.$status;
                                }
                        @endphp
                        <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.$statuses}}"
                           class="my_sort_cut text-muted">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.car_rent_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$car_rent_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        @php
                            $statuses= '';
                                foreach($car_moving_status as $status){
                                    $statuses .= '&car_status_id[]='.$status;
                                }
                        @endphp
                        <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.$statuses}}"
                           class="my_sort_cut text-muted">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.car_moving_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$car_moving_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        @php
                            $statuses= '';
                                foreach($car_maintenance_status as $status){
                                    $statuses .= '&car_status_id[]='.$status;
                                }
                        @endphp
                        <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.$statuses}}"
                           class="my_sort_cut text-muted">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.car_maintenance_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$car_maintenance_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        @php
                            $statuses= '';
                                foreach($car_other_status as $status){
                                    $statuses .= '&car_status_id[]='.$status;
                                }
                        @endphp
                        <a href="{{route('CarRentCars').'?company_id[]='.$company->company_id.$statuses}}"
                           class="my_sort_cut text-muted">
                            <div class="card-body">
                                <h6 style="font-size: 16px ;font-weight: bold ">{{__('home.car_other_count')}}</h6>
                                <h3 class="pt-2"><span class="counter">{{$car_other_count}}</span></h3>
                                <span class="text-danger mr-2"><i
                                        class="fa fa-car"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        @include('Includes.form-errors')
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="row">
                                {{--                                    <div class="col-md-3">--}}
                                {{--                                        <label> @lang('home.company_group')</label>--}}
                                {{--                                        @if(auth()->user()->user_type_id  == 1)--}}
                                {{--                                            <input type="text" class="form-control" value="{{app()->getLocale()=='ar' ? session('company_group')['company_group_ar'] :--}}
                                {{--                                             session('company_group')['company_group_en'] }}" readonly>--}}
                                {{--                                        @else--}}
                                {{--                                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')--}}
                                {{--                                    {{ auth()->user()->companyGroup->company_group_ar }} @else--}}
                                {{--                                    {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>--}}
                                {{--                                        @endif--}}
                                {{--                                    </div>--}}

                                <div class="col-md-3">
                                    <label>@lang('home.companies')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="company_id[]" data-actions-box="true" required>

                                        @foreach($companies as $company)
                                            <option value="{{$company->company_id}}"
                                                    @if(request()->company_id)
                                                        @foreach(request()->company_id  as $company_id)
                                                            @if($company_id == $company->company_id) selected @endif
                                                    @endforeach @elseif(session('company')['company_id'] == $company->company_id) selected @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$company->company_name_ar}}
                                                @else
                                                    {{$company->company_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>@lang('home.branches')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]" data-actions-box="true">

                                        @foreach($branches as $branch)
                                            <option value="{{$branch->branch_id}}"
                                                    @if(request()->branch_id)
                                                        @foreach(request()->branch_id  as $branch_id)
                                                            @if($branch_id == $branch->branch_id) selected @endif
                                                    @endforeach @elseif(session('branch')['branch_id'] == $branch->branch_id) selected @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$branch->branch_name_ar}}
                                                @else
                                                    {{$branch->branch_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label> @lang('carrent.car_plate')</label>
                                    <input type="text" name="full_car_plate" class="form-control"
                                           placeholder="أأأ 52545"
                                           @if(request()->car_rent_model_code)  value="{{request()->car_rent_model_code}}"
                                        @endif>
                                </div>

                                <div class="col-md-3">
                                    <label>@lang('carrent.car_brand')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="brand_id[]" data-actions-box="true">

                                        @foreach($brands as $brand)
                                            <option value="{{$brand->brand_id}}"
                                                    @if(request()->brand_id)
                                                        @foreach(request()->brand_id as $brand_s)
                                                            @if($brand_s  ==  $brand->brand_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$brand->brand_name_ar}}
                                                @else
                                                    {{$brand->brand_name_en}}
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-md-3">
                                    <label>@lang('carrent.car_model')</label>
                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="brand_dt[]" data-actions-box="true">

                                        @foreach($brand_dts as $brand_dt)
                                            <option value="{{$brand_dt->brand_dt_id}}"
                                                    @if(request()->brand_dt)
                                                        @foreach(request()->brand_dt as $brand_dt_s)
                                                            @if($brand_dt_s  ==  $brand_dt->brand_dt_id) selected @endif
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

                                <div class="col-md-3">
                                    <label>@lang('carrent.car_trucker_status')</label>
                                    <select name="car_trucker_status[]"
                                            class="selectpicker" multiple data-live-search="true"
                                            data-actions-box="true"
                                            aria-label="Default select example">
                                        @foreach($sys_codes_tracker_statuses as $sys_codes_tracker_status)
                                            <option value="{{$sys_codes_tracker_status->system_code_id}}"
                                                    @if(request()->car_trucker_status)
                                                        @foreach(request()->car_trucker_status as $sys_tracker_status)
                                                            @if($sys_tracker_status  ==  $sys_codes_tracker_status->system_code_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_codes_tracker_status->system_code_name_ar}}
                                                @else
                                                    {{$sys_codes_tracker_status->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label> @lang('carrent.car_chasi')</label>
                                    <input type="text" name="car_chase" class="form-control"
                                           placeholder="13468787"
                                           @if(request()->car_chase)  value="{{request()->car_chase}}"
                                        @endif>
                                </div>

                                <div class="col-md-3">
                                    <label> @lang('carrent.car_motor_no')</label>
                                    <input type="text" name="car_motor_no" class="form-control"
                                           placeholder="251351535"
                                           @if(request()->car_motor_no)  value="{{request()->car_motor_no}}"
                                        @endif>
                                </div>

                                <div class="col-md-3">
                                    <label>@lang('carrent.car_status')</label>
                                    <select name="car_status_id[]"
                                            class="selectpicker" multiple data-live-search="true"
                                            data-actions-box="true"
                                            aria-label="Default select example" id="car_category_id">
                                        @foreach($sys_codes_status as $sys_code_status)
                                            <option value="{{$sys_code_status->system_code_id}}"
                                                    @if(request()->car_category_id)
                                                        @foreach(request()->car_category_id as $car_category_id)
                                                            @if($car_category_id  ==  $sys_code_status->system_code_id) selected @endif
                                                @endforeach @endif>
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_status->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_status->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label> @lang('carrent.car_model_year')</label>
                                    <input type="text" name="car_model_year" class="form-control"
                                           placeholder="2020"
                                           @if(request()->car_model_year)  value="{{request()->car_model_year}}"
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
                <div style="font-size: 14px ;font-weight: bold" class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover ">
                                <thead>
                                <tr class="red"
                                    style="background-color: #ece5e7; font-size: 16px ; font-style: inherit ">
                                    <th>@lang('home.branch')</th>
                                    <th>@lang('home.logo')</th>
                                    <th>@lang('carrent.car_rent_model_code')</th>
                                    <th>@lang('carrent.car_plate')</th>
                                    <th>@lang('carrent.car_brand')</th>
                                    <th>@lang('carrent.car_model')</th>
                                    <th>@lang('carrent.car_model_year')</th>

                                    <th>@lang('home.color')</th>

                                    <th>@lang('carrent.car_motor_no')</th>
                                    <th>@lang('home.meter_reading_before')</th>

                                    <th>@lang('carrent.car_trucker_status')</th>
                                    <th>@lang('carrent.car_status')</th>

                                    <th>@lang('home.edit')</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cars as $car)
                                    <tr>

                                        <th>{{$car->branch ? $car->branch->name : ''}}</th>
                                        <td>
                                            @if($car->brand->brand_logo_url)
                                                <a href="{{$car->brand->brand_logo_url}}" target="_blank">
                                                    <img class="avatar avatar-blue"
                                                         src="{{$car->brand->brand_logo_url}}"></a>
                                            @endif
                                        </td>
                                        <th>{{$car->model->car_rent_model_code}}</th>

                                        <th style="font-size: 14px ;font-weight: bold ;color: blue">{{$car->full_car_plate}}</th>

                                        <th style="font-size: 14px ;font-weight: bold ">{{app()->getLocale() == 'ar'
                                                ? $car->brand->brand_name_ar
                                                : $car->brand->brand_name_en}}
                                        </th>

                                        <th>{{app()->getLocale() == 'ar'
                                                ? $car->brandDetails->brand_dt_name_ar
                                                : $car->brandDetails->brand_dt_name_en}}
                                        </th>

                                        <th style="font-size: 14px ;font-weight: bold  ;color: red">{{$car->car_model_year}}</th>
                                        <th>{{$car->car_color}}</th>

                                        <th>{{$car->car_motor_no}}</th>
                                        <th style="font-size: 16px ;font-weight: bold">{{$car->odometer_start}}</th>


                                        <th>@if($car->truckerStatus)
                                                {{app()->getLocale() == 'ar'
                                                    ? $car->truckerStatus->system_code_name_ar
                                                    : $car->truckerStatus->system_code_name_ar}}
                                            @endif
                                        </th>
                                        <th style="font-size: 14px ;font-weight: bold ;color: blue">@if($car->status)
                                                {{app()->getLocale() == 'ar'
                                                    ? $car->status->system_code_name_ar
                                                    : $car->status->system_code_name_en}}
                                            @endif
                                        </th>
                                        <th>
                                            <a href="{{route('CarRentCars.edit' , $car->car_id )}}"
                                               class="btn btn-danger btn-sm"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-eye text-center"></i>
                                            </a>
                                        </th>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $cars->links() }}
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

