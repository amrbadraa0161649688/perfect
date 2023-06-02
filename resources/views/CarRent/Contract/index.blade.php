@extends('Layouts.master')


@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <style>
        .bootstrap-select {
            width: 100% !important;
        }

        .card-collapsed > :not(.card-header):not(.card-status) {
            height: 8rem !important;
            overflow: hidden !important;
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="card-body">
        <div class="container-fluid">

            <div class="section-body mt-3" id="app">
                <div class="row">
                    @include('Includes.form-errors')

                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <a href="{{route('car-rent.index').'?company_id[]='.$company->company_id.'&contract_status[]='.$con_open_code->system_code_id}}"
                               class="my_sort_cut text-muted">
                                <div class="card-body">
                                    <h6>{{__('home.contract_open_count')}}</h6>
                                    <h3 class="pt-2"><span class="counter">{{$contract_open_count}}</span></h3>
                                    <span class="text-danger mr-2">
                                    <i class="fa fa-file"></i>
                                </span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <a href="{{route('car-rent.index').'?company_id[]='.$company->company_id.'&contractEndDate='.date('Y-m-d')}}"
                               class="my_sort_cut text-muted">
                                <div class="card-body">
                                    <h6>{{__('home.contract_today_count')}}</h6>
                                    <h3 class="pt-2"><span class="counter">{{$contract_today_count}}</span></h3>
                                    <span class="text-danger mr-2"><i
                                                class="fa fa-file"></i></span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <a href="{{route('car-rent.index').'?company_id[]='.$company->company_id.'&contract_status[]='.$con_late_code->system_code_id}}"
                               class="my_sort_cut text-muted">
                                <div class="card-body">
                                    <h6>{{__('home.contract_late_count')}}</h6>
                                    <h3 class="pt-2"><span class="counter">{{$contract_late_count}}</span></h3>
                                    <span class="text-danger mr-2"><i
                                                class="fa fa-file"></i></span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <a href="{{route('car-rent.index').'?company_id[]='.$company->company_id.'&contract_status[]='.$con_close_code->system_code_id}}"
                               class="my_sort_cut text-muted">
                                <div class="card-body">
                                    <h6>{{__('home.contract_close_count')}}</h6>
                                    <h3 class="pt-2"><span class="counter">{{$contract_close_count}}</span></h3>
                                    <span class="text-danger mr-2"><i
                                                class="fa fa-file"></i></span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card  card-collapsed">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.filters')</h3>

                            <div class="card-options">
                                <a href="#" class="card-options-collapse"
                                   data-toggle="card-collapse"><i
                                            class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-fullscreen"
                                   data-toggle="card-fullscreen"><i
                                            class="fe fe-maximize"></i></a>
                                <a href="#" class="card-options-remove"
                                   data-toggle="card-remove"><i
                                            class="fe fe-x"></i></a>
                            </div>
                        </div>
                        <div style="font-size: 16px ;font-weight: bold;"
                             class="card-body">
                            <form action="">

                                <div class="row">

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
                                    {{--تاريخ الإنشاء--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.contractStartDate_from')</label>
                                        <input type="date" class="form-control" name="contractStartDate_from"
                                               @if(request()->contractStartDate_from) value="{{request()->contractStartDate_from}}"
                                                @endif>
                                    </div>
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.contractStartDate_to')</label>
                                        <input type="date" class="form-control" name="contractStartDate_to"
                                               @if(request()->contractStartDate_to) value="{{request()->contractStartDate_to}}" @endif>
                                    </div>

                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.contract_code')</label>
                                        <input type="text" class="form-control" name="contract_code"
                                               @if(request()->contract_code) value="{{request()->contract_code}}"
                                                @endif>
                                    </div>

                                    {{--الحاله--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.contract_status')</label>
                                        <select name="contract_status[]" class="selectpicker" multiple
                                                data-live-search="true">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($contract_statuses as $contract_status)
                                                <option value="{{$contract_status->system_code_id}}"
                                                        @if(request()->contract_status)
                                                        @foreach(request()->contract_status as $contract_s)
                                                        @if($contract_status->system_code_id  ==  $contract_s) selected @endif
                                                        @endforeach @endif>
                                                    {{app()->getLocale() == "ar"
                                                        ? $contract_status->system_code_name_ar
                                                        : $contract_status->system_code_name_en
                                                    }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    {{--حالةالتعاقد--}}
                                    <div class="col-sm-4 col-md-3  mb-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.contract_type')</label>
                                            <select name="contractTypeCode[]"
                                                    class="selectpicker" multiple data-live-search="true">
                                                <option value="">@lang('home.choose') </option>
                                                @foreach($contract_types as $contract_type)
                                                    <option value="{{$contract_type->system_code}}">
                                                        {{app()->getLocale() == "ar"
                                                            ? $contract_type->system_code_name_ar
                                                            : $contract_type->system_code_name_en
                                                        }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--العملاء--}}
                                    <div class="col-md-3  mb-3">
                                        {{-- customers  --}}
                                        <label>@lang('home.customers')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="customers_id[]">
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->customer_id }}"
                                                        @if(request()->customers_id) @foreach(request()->customers_id as
                                                     $customer_id) @if($customer->customer_id == $customer_id) selected @endif @endforeach @endif>
                                                    {{app()->getLocale()=='ar' ? $customer->customer_name_full_ar
                                                : $customer->customer_name_full_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--نظام تم--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.tamm_status')</label>
                                        <select class="form-control" name="tamm_status" required>
                                            <option value="">@lang('home.choose')</option>
                                            <option value="1"
                                                    @if(request()->tamm_status == 1) selected @endif >@lang('home.tamm')</option>
                                            <option value="0"
                                                    @if(request()->tamm_status == 0) selected @endif>@lang('home.not_tamm')</option>

                                        </select>
                                    </div>

                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.identity')/@lang('customer.customer_mobile')</label>
                                        <input type="text" class="form-control" name="customer_dt"
                                               @if(request()->customer_dt) value="{{request()->customer_dt}}"
                                                @endif>
                                    </div>

                                    {{--تاريخ الإنشاء--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.contractStartDate_from')</label>
                                        <input type="date" class="form-control" name="contractStartDate_from"
                                               @if(request()->contractStartDate_from) value="{{request()->contractStartDate_from}}"
                                                @endif>
                                    </div>
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.contractStartDate_to')</label>
                                        <input type="date" class="form-control" name="contractStartDate_to"
                                               @if(request()->contractStartDate_to) value="{{request()->contractStartDate_to}}" @endif>
                                    </div>

                                    {{--تاريخ التسليم--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.closed_datetime_from')</label>
                                        <input type="date" class="form-control" name="closed_datetime_from"
                                               @if(request()->closed_datetime_from) value="{{request()->closed_datetime_from}}"
                                                @endif>
                                    </div>
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.closed_datetime_to')</label>
                                        <input type="date" class="form-control" name="closed_datetime_to"
                                               @if(request()->closed_datetime_to) value="{{request()->closed_datetime_to}}" @endif>
                                    </div>

                                    {{--رقم اللوحه--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('carrent.car_plate')</label>
                                        <input type="text" class="form-control" name="full_car_plate"
                                               @if(request()->full_car_plate) value="{{request()->full_car_plate}}" @endif>
                                    </div>

                                    {{--نوع السياره الموديل--}}
                                    <div class="col-md-3  mb-3">
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

                                    {{--صلاحية سير السياره--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.tammexternalAuthorization')</label>
                                        <select class="form-control" name="tammExternalAuthorizationCountries" required>
                                            <option value="">@lang('home.choose')</option>
                                            <option value="1"
                                                    @if(request()->tammExternalAuthorizationCountries == 1) selected @endif >@lang('home.inside_country')</option>
                                            <option value="0"
                                                    @if(request()->tammExternalAuthorizationCountries == 0) selected @endif>@lang('home.outside_country')</option>

                                        </select>
                                    </div>
                                    {{--حالة جهاز التتبع--}}
                                    <div class="col-md-3  mb-3">

                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_trucker_status') </label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                data-actions-box="true" name="car_trucker_status[]">

                                            @foreach($sys_codes_tracker_status as $sys_codes_tracker_status)
                                                <option value="{{$sys_codes_tracker_status->system_code_id}}"
                                                        @if(request()->car_trucker_status)
                                                        @foreach(request()->car_trucker_status as $car_trucker)
                                                        @if($car_trucker  ==  $sys_codes_tracker_status->system_code_id) selected @endif
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


                                    {{--رصيد من وإلي--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.balance_from')</label>
                                        <input type="number" class="form-control" name="balance_from"
                                               @if(request()->balance_from) value="{{request()->balance_from}}"
                                                @endif>
                                    </div>
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.balance_to')</label>
                                        <input type="number" class="form-control" name="balance_to"
                                               @if(request()->balance_to) value="{{request()->balance_to}}" @endif>
                                    </div>

                                    {{--تاريخ إنتهاء التفويض--}}
                                    <div class="col-md-3  mb-3">
                                        <label>@lang('home.tamm_enddate_hejri')</label>
                                        <input type="date" class="form-control" name="tamm_enddate_hejri"
                                               @if(request()->tamm_enddate_hejri) value="{{request()->tamm_enddate_hejri}}" @endif>
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
                                <a href="{{ route('car-rent.create') }}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_rent_contract')
                                </a>
                            </button>
                            <button type="button" class="btn btn-warning m-2">
                                <a class="btn btn-warning"
                                   href="{{ route('car-rent.update.status.balance') }}">@lang('home.update_status_balance')</a>
                            </button>
                            <button type="button" class="btn btn-info m-2">

                                <a type="button" class="btn btn-info" data-toggle="modal"
                                   data-target="#makeInvoice">@lang('home.make_invoice')
                                </a>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="makeInvoice" tabindex="-1" role="dialog"
                                 aria-labelledby="makeInvoice" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('car-rent.make.invoice')}}" method="get">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.month')</label>
                                                        <select class="form-control" name="month"
                                                                required>
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach(range(1,12) as $month)
                                                                @if($month < \Carbon\Carbon::now()->month)
                                                                    <option value="{{$month}}">
                                                                        {{$month}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">@lang('home.close')</button>
                                                <button type="submit"
                                                        class="btn btn-primary">@lang('home.Submit')</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="font-size: 14px ;font-weight: bold " class="card-body">
                            <div style="font-size: 14px ;font-weight: bold " class="table-responsive">
                                <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                    <thead>
                                    <tr class="red"
                                        style="background-color: #ece5e7;font-size: 14px;font-style: inherit;font-weight: bold">
                                        <th>@lang('home.contract_code')</th>
                                        {{--                                        <th>@lang('home.price_list_serial')</th>--}}

                                        <th>@lang('home.branches')</th>
                                        <th>@lang('customer.customer_name')</th>
                                        <th>@lang('customer.private_mobile')</th>
                                        <th>@lang('home.identity')</th>

                                        <th>@lang('carrent.car_plate')</th>
                                        <th>@lang('carrent.car_model')</th>
                                        <th>@lang('home.tamm_status')</th>
                                        {{--                                        <th>@lang('home.tamm_enddate_hejri')</th>--}}
                                        {{--                                         <th>@lang('home.tammexternalAuthorization')</th>--}}
                                        <th>@lang('carrent.car_trucker_status')</th>
                                        <th>@lang('home.created_date')</th>
                                        <th>@lang('home.closed_datetime')</th>

                                        <th>@lang('home.contract_balance')</th>
                                        {{--  <<<<<<< HEAD --}}
                                        {{--                                         <th>@lang('home.contract_type')</th>--}}


                                        {{-- ======= --}}
                                        {{--                                        <th>@lang('home.contract_type')</th>--}}
                                        <th> الحاله</th>
                                        <th>الاجراءات</th>
                                        {{-- >>>>>>> origin/nagy --}}

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($contracts as $contract)
                                        <tr>
                                            <td><a href="{{ route('car-rent.edit',$contract->contract_id)  }}"
                                                   title="@lang('home.edit')">
                                                    {{$contract->contract_code}}
                                                </a></td>

                                            {{--                                            <td>{{$contract->carPriceListHd?$contract->carPriceListHd->rent_list_code:''}}</td>--}}

                                            <td>
                                                @if($contract->branch)
                                                    {{ app()->getLocale() == 'ar'
                                                    ? $contract->branch->branch_name_ar
                                                    : $contract->branch->branch_name_en }}
                                                @endif
                                            </td>
                                            <td>{{$contract->customer->customer_name_full_ar}}</td>
                                            <td>{{$contract->c_mobile}}</td>

                                            <td>{{$contract->c_idNumber}}</td>

                                            <td style="font-size: 15px ;font-weight: bold ;color : red ">
                                                {{$contract->car->full_car_plate}}
                                            </td>
                                            <td>
                                                @if($contract->car->brand)
                                                    {{app()->getLocale() == 'ar'
                                                 ? $contract->car->brandDetails->brand_dt_name_ar
                                                 : $contract->car->brandDetails->brand_dt_name_en}}
                                                @endif
                                            </td>
                                            <td style="font-size: 12px ;font-weight: bold ">@if($contract->tamm_status == 1)
                                                    @lang('home.tamm')
                                                @else
                                                    @lang('home.not_tamm')
                                                @endif</td>
                                            {{--                                            <td>{{$contract->tamm_enddate_hejri}}</td>--}}
                                            {{--                                           <td>@if($contract->tammExternalAuthorizationCountries == 1)--}}
                                            {{--                                                   @lang('home.inside_country')--}}
                                            {{--                                             @else--}}
                                            {{--                                                 @lang('home.outside_country')--}}
                                            {{--                                         @endif</td>--}}
                                            <th>
                                                @if($contract->car->truckerStatus)
                                                    {{app()->getLocale() == 'ar'
                                                        ? $contract->car->truckerStatus->system_code_name_ar
                                                        : $contract->car->truckerStatus->system_code_name_ar}}
                                                @endif
                                            </th>

                                            {{-- <<<<<<< HEAD --}}

                                            {{--                                            <td>--}}
                                            {{--                                                @if($contract->contractType)--}}
                                            {{--                                                    {{ app()->getLocale() == 'ar'--}}
                                            {{--                                                    ? $contract->contractType->system_code_name_ar--}}
                                            {{--                                                    : $contract->contractType->system_code_name_en }}--}}
                                            {{--                                                @endif--}}
                                            {{--                                            </td>--}}
                                            {{--  <th>--}}

                                            {{--                                                <a href="{{ route('car-rent.edit',$contract->contract_id)  }}"--}}
                                            {{--                                                   class="btn btn-primary btn-sm"--}}
                                            {{--                                                   title="@lang('home.edit')">--}}
                                            {{--                                                    <i class="fa fa-edit text-center"></i>--}}
                                            {{--                                                </a>--}}
                                            {{-- =======--}}
                                            <td style="font-size: 15px ;font-weight: bold ;color : blue ">{{date('Y-m-d',strtotime($contract->created_at))}}</td>
                                            <td style="font-size: 15px ;font-weight: bold ;color : blue">
                                                @if($contract->closed_datetime)
                                                    {{ $contract->closed_datetime > 0
                                                    ?  date('Y-m-d',strtotime($contract->closed_datetime))
                                                        : '' }}
                                                @endif
                                            </td>
                                            <td style="font-size: 17px ;font-weight: bold  ">{{$contract->contract_balance}}</td>
                                            {{--                                            <td>--}}
                                            {{--                                                @if($contract->contractType)--}}
                                            {{--                                                    {{ app()->getLocale() == 'ar'--}}
                                            {{--                                                    ? $contract->contractType->system_code_name_ar--}}
                                            {{--                                                    : $contract->contractType->system_code_name_en }}--}}
                                            {{--                                                @endif--}}
                                            {{--                                            </td>--}}
                                            <th>
                                                @if($contract->status)
                                                    {{app()->getLocale() == 'ar'
                                                        ? $contract->status->system_code_name_ar
                                                        : $contract->status->system_code_name_ar}}
                                                @endif
                                                {{-->>>>>>> origin/nagy--}}
                                            </th>

                                            <td>
                                                <div class="input-group-append">
                                                    <button data-toggle="dropdown" type="button"
                                                            class="btn btn-primary dropdown-toggle"
                                                            aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         x-placement="bottom-end"
                                                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(283px, 35px, 0px);">
                                                        <button class="dropdown-item"
                                                                onclick="saveContractWeb('{{$contract->contract_id}}')">
                                                            توثيق العقد
                                                        </button>
                                                        <a href="https://tajeerstg.tga.gov.sa/#/public-contract/{{$contract->contract_Number}}/{{$contract->contract_Token}}"
                                                           class="dropdown-item" target="_blank"
                                                           title="  ابرام العقد">
                                                            ابرام العقد
                                                        </a>
                                                        <button class="dropdown-item"
                                                                onclick="crearContractWeb('{{$contract->contract_id}}')">
                                                            تم ابرام العقد
                                                        </button>
                                                        <div class="dropdown-divider"></div>
                                                        <button class="dropdown-item"
                                                                onclick="cancelContract('{{$contract->contract_id}}')">
                                                            الغاء العقد
                                                        </button>
                                                        <button class="dropdown-item"
                                                                onclick="suspendContract('{{$contract->contract_id}}')">
                                                            تعليق العقد
                                                        </button>
                                                        <button class="dropdown-item"
                                                                onclick="closeContract('{{$contract->contract_id}}')">
                                                            اغلاق العقد
                                                        </button>

                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item"
                                                           href="{{ route('api.car-rent.contract.getContractPDF',$contract->contract_id)}}"
                                                           target="_blank">طباعة</a>
                                                        <a class="dropdown-item"
                                                           href="{{ route('api.car-rent.contract.getSummarizedContractPDF',$contract->contract_id)}}"
                                                           target="_blank"> طباعة العقد المختصر</a>

                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ $contracts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script
            src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">

        function saveContractWeb($id) {
            url = "{{ route('api.car-rent.contract.SaveContract') }}"
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $id,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function (data) {

                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                } else {
                    toastr.warning(data.msg);
                }

            });

        }

        function crearContractWeb($id) {
            url = "{{ route('api.car-rent.contract.CreateContractWeb') }}"
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $id,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function (data) {

                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                } else {
                    toastr.warning(data.msg);
                }

            });

        }

        function cancelContract($id) {
            url = "{{ route('api.car-rent.contract.cancelContract') }}"
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $id,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function (data) {

                if (data.success) {
                    toastr.success(data.msg);
                    //location.reload();
                } else {
                    toastr.warning(data.msg);
                }

            });

        }

        function closeContract($id) {
            url = "{{ route('api.car-rent.contract.CloseContract') }}"
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $id,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function (data) {

                if (data.success) {
                    toastr.success(data.msg);
                    //location.reload();
                } else {
                    toastr.warning(data.msg);
                }

            });

        }

        function suspendContract($id) {
            url = "{{ route('api.car-rent.contract.SuspendContract') }}"
            $.ajax({
                type: 'post',
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $id,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function (data) {

                if (data.success) {
                    toastr.success(data.msg);
                    //location.reload();
                } else {
                    toastr.warning(data.msg);
                }

            });

        }
    </script>
@endsection

