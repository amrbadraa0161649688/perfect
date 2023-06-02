@extends('Layouts.master')

@section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#data-grid" data-toggle="tab"
                           class="nav-link active">@lang('home.data')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#contract-value-grid" data-toggle="tab"
                           class="nav-link">@lang('home.contract_value')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#customer-grid" data-toggle="tab"
                           class="nav-link">@lang('home.customer_data')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#driver-grid" data-toggle="tab"
                           class="nav-link">@lang('home.driver_data')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#car-grid" data-toggle="tab"
                           class="nav-link">@lang('home.car_data')</a>
                    </li>


                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#receipt-grid" data-toggle="tab"
                           class="nav-link">@lang('home.receipt')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " class="nav-link" href="#bonds-cash-grid"
                           data-toggle="tab">@lang('home.bonds_cash')</a></li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " style="font-size: 15px ;font-weight: bold  "
                           href="#discount-bonds-grid" data-toggle="tab"
                           class="nav-link">@lang('home.discount_bonds')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#addition-bonds-grid" data-toggle="tab"
                           class="nav-link">@lang('home.bills_of_exchange')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#invoice-grid" data-toggle="tab"
                           class="nav-link">@lang('invoice.invoices')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#accidents-and-damages-grid"
                           data-toggle="tab"
                           class="nav-link">@lang('home.accidents_and_damages')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " href="#attachments-grid" data-toggle="tab"
                           class="nav-link">@lang('home.attachments')</a>
                    </li>

                    <li class="nav-item">
                        <a style="font-size: 15px ;font-weight: bold  " class="nav-link" href="#notes-grid"
                           data-toggle="tab">@lang('home.notes')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#procedure-grid" data-toggle="tab">@lang('home.procedure')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#photos-grid" data-toggle="tab">{{__('Take Photo')}}</a>
                    </li>

                </ul>

            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div style="font-size: 15px ;font-weight: bold ;color : blue " class="container-fluid">
            <div class="tab-content mt-3">
                {{--البيانات--}}
                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.data')</h3>
                        </div>
                        <div style="font-size: 15px ;font-weight: bold  " class="card-body">

                            <div class="col-md-12">
                                <form id="my-form" action="{{route('car-rent.update' , $contract->contract_id)}}"
                                      method="post">

                                    @method('put')
                                    @csrf()
                                    <div class="row clearfix">

                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone"
                                                       class="control-label"> @lang('home.arrival_counter')</label>
                                                {{--                                                {{$contract->ActualDaysCount}}--}}
                                                {{--                                                @if($contract->status->system_code==13604)--}}
                                                {{--                                                    <input type="text" class="form-control text-center"--}}
                                                {{--                                                           name="odometerclosed"--}}
                                                {{--                                                           readonly>--}}
                                                {{--                                                @else--}}

                                                {{--                                                    <input type="text" class="form-control text-center"--}}
                                                {{--                                                           v-model="arrival_counter" name="odometerclosed">--}}
                                                {{--                                                @endif--}}



                                                @if($contract->status->system_code==13604)
                                                    <input type="number" class="form-control text-center"
                                                           id="odometerclosed"
                                                           name="odometerclosed"
                                                           min="{{$contract->odometerReading}}"
                                                           readonly style="background-color:transparent" required>
                                                @else
                                                    <input type="number" class="form-control text-center" required
                                                           v-model="arrival_counter" name="odometerclosed"
                                                           id="odometerclosed"
                                                           min="{{$contract->odometerReading}}"
                                                           style="background-color:transparent">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone"
                                                       class="control-label"> @lang('home.contract_code')</label>
                                                <input type="text" readonly value="{{ $contract->contract_code }}"
                                                       style="font-size: 16px ;font-weight: bold  "
                                                       class="form-control text-center">
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="tax-id"
                                                       class="control-label">@lang('home.plate_number')</label>
                                                <input type="text" style="font-size: 17px ;font-weight: bold" readonly
                                                       class="form-control text-center"
                                                       value="{{ $contract->car->full_car_plate }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="ssn" class="control-label">@lang('home.name')</label>
                                                <input type="text" readonly value="{{app()->getLocale() == 'ar' ?
                                         $contract->customer->customer_name_full_ar :   $contract->customer->customer_name_full_en}}"
                                                       class="form-control text-center"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.customer_type')</label>
                                                <input type="text" readonly value="{{app()->getLocale()=='ar' ? $contract->customer->cus_type
                                         ->system_code_name_ar : $contract->customer->cus_type
                                         ->system_code_name_en}}" class="form-control text-center"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>


                                        {{--second row--}}

                                        {{--تاريخ بداية العقد--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.contract_start_date')</label>
                                                <input type="date" class="form-control text-center"
                                                       name="contractStartDate"
                                                       readonly value="{{ $contract->contract_start_date_date }}"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>
                                        {{--تاريخ نهاية العقد--}}

                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.contract_end_date')</label>
                                                <input type="date" class="form-control text-center"
                                                       name="contractEndDate"
                                                       readonly value="{{ $contract->contract_end_date_date }}"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>


                                        <div hidden class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.contract_type')</label>
                                                <input type="text" class="form-control text-center"
                                                       name="contractTypeCode"
                                                       readonly value="@if($contract->contractType){{ app()->getLocale() == 'ar'
                                                   ? $contract->contractType->system_code_name_ar
                                                   : $contract->contractType->system_code_name_en}} @else @endif"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>

                                        {{--عدد ايام العقد--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.days_count')</label>
                                                <input type="number" class="form-control text-center"
                                                       readonly v-model="daysCount"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>

                                        {{--عدد الايام الفعلي--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.actual_days_count')</label>
                                                @if($contract->status->system_code == 13604)
                                                    <input type="number" class="form-control text-center"
                                                           readonly value="{{$contract->days_count}}"
                                                           style="font-size: 16px ;font-weight: bold  ">

                                                @else
                                                    <input type="number" class="form-control text-center"
                                                           readonly v-model="actualDaysCount" name="days_count"
                                                           style="font-size: 16px ;font-weight: bold  ">
                                                @endif
                                            </div>
                                        </div>

                                        {{--الايجار اليومي--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.daily_cost')</label>

                                                <input type="number" class="form-control text-center"
                                                       readonly v-model="rentDayCost"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>

                                        {{--اجمالي الايجار اليومي--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.total_daily_cost')</label>

                                                @if($contract->status->system_code == 13604)
                                                    <input type="number"
                                                           class="form-control text-center bg-blue text-white"
                                                           readonly
                                                           value="{{ $contract->rentDayCost  * $contract->days_count}}"
                                                           style="font-size: 18px ;font-weight: bold  ">
                                                @else
                                                    <input type="number"
                                                           class="form-control text-center bg-blue text-white"
                                                           readonly v-model="TotalDailyCost"
                                                           style="font-size: 18px ;font-weight: bold  ">
                                                @endif
                                            </div>
                                        </div>

                                        {{--third row--}}



                                        {{--قراءه اخر عداد--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.departure_counter')</label>
                                                <input type="number" class="form-control text-center"
                                                       readonly name="odometerReading" id="odometerReading"
                                                       value="{{$contract->odometerReading }}"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>


                                        {{--قراءه عداد الوصول--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.arrival_counter')</label>
                                                {{--                                                @if($contract->status->system_code==13604)--}}
                                                {{--                                                    <input type="text" class="form-control text-center"--}}
                                                {{--                                                           name="odometerclosed"--}}
                                                {{--                                                           readonly>--}}
                                                {{--                                                @else--}}
                                                {{--                                                    <input type="number" class="form-control text-center"--}}
                                                {{--                                                           readonly name="" v-model="arrival_counter">--}}
                                                {{--                                                @endif--}}
                                                @if($contract->status->system_code==13604)
                                                    <input type="number" class="form-control text-center"
                                                           name="odometerclosed"
                                                           readonly style="font-size: 16px;font-weight: bold">
                                                @else
                                                    <input type="number" class="form-control text-center" required
                                                           v-model="arrival_counter" name="odometerclosed" readonly
                                                           min="{{$contract->odometerReading}}"
                                                           style="font-size: 16px ;font-weight: bold  ">
                                                @endif

                                            </div>
                                        </div>


                                        {{--كيلو متر مقطوع--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.kilometers_traveled')</label>

                                                @if($contract->status->system_code==13604)
                                                    <input type="number" class="form-control text-center"
                                                           readonly name="total_km" value="{{$contract->total_km}}"
                                                           style="font-size: 16px ;font-weight: bold  ">
                                                @else
                                                    <input type="number" class="form-control text-center"
                                                           readonly name="total_km" v-model="taken_counter"
                                                           style="font-size: 16px ;font-weight: bold  ">
                                                @endif


                                            </div>
                                        </div>


                                        {{--كيلو متر زائد--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.extra_kilometers')</label>

                                                @if($contract->status->system_code==13604)
                                                    <input type="number" class="form-control text-center"
                                                           readonly name="total_km_count"
                                                           value="{{$contract->total_km_count}}"
                                                           style="font-size: 16px ;font-weight: bold  ">
                                                @else
                                                    <input type="number" class="form-control text-center"
                                                           readonly name="total_km_count" v-model="total_km_count"
                                                           style="font-size: 16px ;font-weight: bold  ">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.available_kilometers')</label>
                                                <input type="number" class="form-control text-center"
                                                       readonly name="allowedKmPerDay" v-model="allowedKmPerDay"
                                                       style="font-size: 16px ;font-weight: bold  ">
                                            </div>
                                        </div>
                                        {{--قسه الكيلومترات الزائده--}}
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.extra_kilometers_cost_in_sar')</label>
                                                <input type="number" class="form-control"
                                                       readonly name="extraKmCost" v-model="extraKmCost"
                                                       style="font-size: 16px ;font-weight: bold  "
                                                >
                                            </div>
                                        </div>

                                        {{--اجمالي تكلفه الكيلو مترات الزائده--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.total_extra_kilometers_cost_in_sar')</label>
                                                @if($contract->status->system_code == 13604)
                                                    <input type="number"
                                                           class="form-control text-center bg-blue text-white"
                                                           readonly name="total_km_cost"
                                                           value="{{$contract->total_km_cost}}"
                                                           style="font-size: 18px ;font-weight: bold  ">
                                                @else
                                                    <input type="number"
                                                           class="form-control text-center bg-blue text-white"
                                                           readonly name="total_km_cost" v-model="total_km_cost"
                                                           style="font-size: 18px ;font-weight: bold  ">
                                                @endif
                                            </div>
                                        </div>


                                        {{--fourth row--}}
                                        {{--وقت المغادره--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.start_date_time')</label>
                                                <input type="text" class="form-control text-center"
                                                       readonly name="" style="font-size: 16px ;font-weight: bold  "
                                                       value="{{ $contract->contract_start_date_time }}">
                                            </div>
                                        </div>

                                        {{--وقت الوصول--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.end_date_time')</label>
                                           
                                                       @if($contract->status->system_code == 13604)
                                                       <input type="text" class="form-control text-center"
                                                       style="font-size: 16px ;font-weight: bold  " readonly
                                                        value="{{ $contract->contract_closed_date_time }}">
                                                       
                                                            
                                                        @else
                                                        <input type="text" class="form-control text-center"
                                                        style="font-size: 16px ;font-weight: bold  " readonly
                                                            value="{{  \Carbon\Carbon::now()->format('H:i') }}">
                                                     @endif

                                           
                                           
                                           
                                                    </div>
                                        </div>
                           

                                        {{--ساعات ويحسب يوم--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.hours_to_day')@{{ extraHours
                                                    }} </label>
                                                <input type="number" class="form-control text-center"
                                                       style="font-size: 16px ;font-weight: bold  "
                                                       name="allow_hr_to_day" v-model="allow_hr_to_day"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{--عدد الساعات التأخير--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.allowed_delay_hours')</label>
                                                <input type="number" class="form-control text-center"
                                                       style="font-size: 16px ;font-weight: bold  "
                                                       name="allowedLateHours" v-model="allowedLateHours"
                                                       readonly>
                                            </div>
                                        </div>


                                        {{--عدد الساعات--}}
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.number_of_hours')</label>
                                                @if($contract->status->system_code == 13604)
                                                    <input type="number" class="form-control text-center"
                                                           readonly name="total_hr_count"
                                                           style="font-size: 16px ;font-weight: bold  "
                                                           value="{{$contract->total_hr_count}}">
                                                @else
                                                    <input type="number" class="form-control text-center"
                                                           style="font-size: 16px ;font-weight: bold  "
                                                           readonly name="total_hr_count" v-model="total_hr_count">
                                                @endif
                                            </div>
                                        </div>


                                        {{--فيمة ساعات التأخير--}}
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.extra_hour_price')</label>
                                                <input type="number" class="form-control text-center"
                                                       name="rentHourCost" v-model="rentHourCost"
                                                       style="font-size: 16px ;font-weight: bold  "
                                                       readonly>
                                            </div>
                                        </div>

                                        {{--إجمالي قيمة الساعات--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.total_value_of_hours')</label>
                                                @if($contract->status->system_code == 13604)
                                                    <input type="number"
                                                           class="form-control text-center bg-blue text-white"
                                                           readonly name="total_hour_cost"
                                                           style="font-size: 18px ;font-weight: bold  "
                                                           value="{{$contract->total_hour_cost}}">
                                                @else
                                                    <input type="number"
                                                           class="form-control text-center bg-blue text-white"
                                                           style="font-size: 18px ;font-weight: bold  "
                                                           readonly name="total_hour_cost" v-model="total_hour_cost">
                                                @endif
                                            </div>
                                        </div>

                                        {{--الموطف منشئ العقد--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.created_user_contract')</label>
                                                <input type="text" class="form-control text-center"
                                                       readonly value="{{app()->getLocale() == 'ar'
                                               ? $contract->user->user_name_ar
                                               : $contract->user->user_name_en}}">
                                            </div>
                                        </div>

                                        {{--الحاله في نطام تم--}}
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.tamm_status')</label>
                                                <input type="text"
                                                       class="form-control text-center  bg-blue text-white"
                                                       name="tamm_status"
                                                       readonly
                                                       value="@if($contract->tamm_status == 1) @lang('home.tamm') @else @lang('home.not_tamm') @endif">
                                            </div>
                                        </div>

                                        {{--صلاحية سير السياه في مدينه اخري--}}
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.tammexternalAuthorization')</label>
                                                <input type="text"
                                                       class="form-control text-center  bg-blue text-white"
                                                       name=""
                                                       readonly
                                                       value="@if($contract->tammExternalAuthorizationCity == 1) @lang('home.inside_country')
                                                       @else @lang('home.outside_country')@endif">
                                            </div>
                                        </div>

                                        {{--اسم الفرع--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('home.branch')</label>
                                                <input type="text" class="form-control"
                                                       value="{{app()->getLocale() == 'ar'
                                                   ? session('branch')['branch_name_ar']
                                                   :session('branch')['branch_name_en']}}" readonly>

                                            </div>
                                        </div>

                                        {{--نوع العقد--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('home.contract_type')</label>
                                                <input type="text" class="form-control" name="contractTypeCode"
                                                       value="@if($contract->contractType) {{app()->getLocale() == 'ar'
                                                   ? $contract->contractType->system_code_name_ar
                                                   : $contract->contractType->system_code_name_en}} @else @endif"
                                                       readonly>

                                            </div>
                                        </div>

                                        {{--حالة جهاز التتبع--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('home.tracker_status')</label>
                                                <input type="text" class="form-control" name=""
                                                       value="@if($contract->car->truckerStatus) {{app()->getLocale() == 'ar'
                                                   ? $contract->car->truckerStatus->system_code_name_ar
                                                   : $contract->car->truckerStatus->system_code_name_en}} @else @endif"
                                                       readonly>

                                            </div>
                                        </div>

                                        {{--حالة أيام التأجير--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label">@lang('home.status_of_rent_days')</label>
                                                @if($contract->status->system_code==13604)
                                                    <input v-if="{{$contract->days_count2 - $contract->days_count}} < 0"
                                                           type="number"
                                                           class="form-control text-center text-white"
                                                           readonly
                                                           value="{{$contract->days_count2 - $contract->days_count}}"
                                                           style="font-size: 18px ;font-weight: bold; background: #dc3545!important;">
                                                    <input v-else type="number"
                                                           class="form-control text-center  bg-danger text-white"
                                                           readonly
                                                           value="{{$contract->days_count2 - $contract->days_count}}"
                                                           style="font-size: 18px ;font-weight: bold;">
                                                @else
                                                    <input v-if="days_diff_count < 0" type="number"
                                                           class="form-control text-center bg-danger text-white" name=""
                                                           readonly v-model="days_diff_count"
                                                           style="font-size: 18px ;font-weight: bold;background: #dc3545!important;">
                                                    <input v-else type="number"
                                                           class="form-control text-center bg-danger text-white" name=""
                                                           readonly v-model="days_diff_count"
                                                           style="font-size: 18px ;font-weight: bold">
                                                @endif

                                            </div>
                                        </div>

                                    </div>

                                    <div class="row clearfix"
                                         style="border: 1px solid #001f71;border-radius: 10px;padding: 20px">

                                        {{--اجمالي الايجار اليومي--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.total_rent')</label>


                                                @if($contract->status->system_code == 13604)
                                                    <input type="number"
                                                           class="form-control text-center bg-blue text-white"
                                                           readonly style="font-size: 18px ;font-weight: bold  "
                                                           value="{{ $contract->rentDayCost  * $contract->days_count}}">
                                                @else
                                                    <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                           class="form-control text-center bg-blue text-white"
                                                           readonly v-model="subCost" name="contract_amount">
                                                @endif
                                            </div>
                                        </div>

                                        {{--اجمالي الخصومات--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.total_discounts')</label>
                                                <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                       class="form-control text-center bg-blue text-white"
                                                       name="discount" v-model="discount" readonly>
                                            </div>
                                        </div>

                                        {{--قيمة الضريبه المضافه--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.added_tax')</label>
                                                <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                       class="form-control text-center bg-blue text-white"
                                                       name="contract_vat_amout" v-model="vat_amount" readonly>
                                            </div>
                                        </div>

                                        {{--الإضافات والحوادث--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.extras_and_accidents')</label>
                                                <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                       class="form-control text-center bg-blue text-white"
                                                       name="contract_total_add"
                                                       v-model="contract_total_add" readonly>
                                            </div>
                                        </div>

                                        {{--إجمالي قيمة العقد--}}
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.total_contract_amount')</label>
                                                @if($contract->status->system_code == 13604)

                                                    <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                           class="form-control text-center bg-blue text-white"
                                                           name="contract_net_amount"
                                                           value="{{$contract->contract_net_amount}}" readonly>
                                                @else
                                                    <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                           class="form-control text-center bg-blue text-white"
                                                           name="contract_net_amount" v-model="netActualCost" readonly>
                                                @endif
                                            </div>
                                        </div>

                                        {{--الموطف منشئ العقد--}}
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.closed_user_contract')</label>
                                                <input type="text" class="form-control text-center"
                                                       style="font-size: 18px ;font-weight: bold  "
                                                       readonly value="{{$contract->closer
                                               ? $contract->closer['user_name_'.app()->getLocale()] : ''}}">
                                            </div>
                                        </div>

                                        {{--تاريخ الوصول تاريخ اليوم--}}
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.arrival_contract_car')</label>
                                                <input type="date" class="form-control text-center" name=""
                                                       readonly style="font-size: 18px ;font-weight: bold  "
                                                       value="{{ $contract->closed_datetime?date('Y-m-d', strtotime($contract->closed_datetime)):''  }}">
                                            </div>
                                        </div>

                                        {{--رصيد التسديدات--}}
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.payment_balance')</label>
                                                <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                       class="form-control text-center bg-blue text-white"
                                                       name="paid" v-model="paid" readonly>
                                            </div>
                                        </div>

                                        {{--إجمالي المستحق--}}
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.total_due')</label>
                                                @if($contract->status->system_code==13604)
                                                    <input type="number" style="font-size: 18px ;font-weight: bold  "
                                                           class="form-control text-center bg-blue text-white"
                                                           name="total_due" readonly
                                                           value="{{$contract->contract_net_amount - $contract->paid}}">

                                                @else
                                                    <input type="number" id="total_due"
                                                           style="font-size: 18px ;font-weight: bold  "
                                                           class="form-control text-center bg-blue text-white"
                                                           name="total_due" v-model="total_due" readonly>

                                                @endif
                                            </div>
                                        </div>

                                        <input type="hidden" name="add_bond_cash" id="add_bond_cash">
                                        <input type="hidden" name="add_bond_capture" id="add_bond_capture">

                                    </div>

                                    @if($contract->status->system_code!=13604)
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt--5" style="padding: 1rem;">
                                            <a href="{{ url('bonds-add/capture/create?contract_id='.$contract->contract_id) }}"
                                               class="btn btn-primary"> @lang('home.extension')</a>

                                            <button type="button" class="btn btn-danger"
                                                    v-if="arrival_counter > odometerReading"
                                                    id="submit-contract">@lang('home.close')</button>


                                            <button type="button" class="btn btn-danger" disabled v-else
                                                    id="submit-contract">@lang('home.close')</button>
                                        </div>
                                    @endif
                                </form>


                                {{--<form action="{{route('car-rent.addInvoiceWithJournalWhenCloseContract')}}"--}}
                                      {{--method="post">--}}
                                    {{--@csrf--}}
                                    {{--<input type="hidden" name="contract_id"--}}
                                           {{--value="{{$contract->contract_id}}">--}}
                                    {{--<input type="hidden"--}}
                                           {{--class="form-control text-center mt-2"--}}
                                           {{--style="background-color:transparent"--}}
                                           {{--readonly name="total_km_cost" v-model="total_km_cost">--}}

                                    {{--<input type="hidden"--}}
                                           {{--class="form-control text-center mt-2"--}}
                                           {{--style="background-color:transparent"--}}
                                           {{--readonly name="total_hour_cost" v-model="total_hour_cost">--}}

                                    {{--<input type="hidden" style="font-size: 18px ;font-weight: bold  "--}}
                                           {{--class="form-control text-center bg-blue text-white"--}}
                                           {{--name="discount" v-model="discount" readonly>--}}

                                    {{--<input type="hidden" style="font-size: 18px ;font-weight: bold  "--}}
                                           {{--class="form-control text-center bg-blue text-white"--}}
                                           {{--name="contract_total_add"--}}
                                           {{--v-model="contract_total_add" readonly>--}}

                                    {{--<input type="hidden" class="form-control text-center"--}}
                                           {{--readonly name="total_km_count" v-model="total_km_count"--}}
                                           {{--style="font-size: 16px ;font-weight: bold  ">--}}

                                    {{--<input type="hidden" class="form-control"--}}
                                           {{--readonly name="extraKmCost" v-model="extraKmCost"--}}
                                           {{--style="font-size: 16px ;font-weight: bold  ">--}}

                                    {{--<input type="hidden" class="form-control text-center"--}}
                                           {{--style="font-size: 16px ;font-weight: bold  "--}}
                                           {{--readonly name="total_hr_count" v-model="total_hr_count">--}}

                                    {{--<input type="hidden" class="form-control text-center"--}}
                                           {{--name="rentHourCost" v-model="rentHourCost"--}}
                                           {{--style="font-size: 16px ;font-weight: bold  "--}}
                                           {{--readonly>--}}

                                    {{--<button type="submit"--}}
                                            {{--class="btn btn-danger">@lang('home.close')</button>--}}
                                {{--</form>--}}

                            </div>

                        </div>
                    </div>
                </div>

                {{--قيمة العقد--}}
                <div class="tab-pane fade" id="contract-value-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.contract_value')</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-6">
                                    <input type="text" class="form-control text-center mt-2" readonly
                                           value="@lang('home.car_data')">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.plate_number')">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2"
                                                   style="background-color:transparent"
                                                   value="{{ $contract->car->full_car_plate }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.car_model')">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2"
                                                   style="background-color:transparent"
                                                   value="{{ app()->getLocale()=='ar' ?
                                                   $contract->car->brandDetails->brand_dt_name_ar :
                                                   $contract->car->brandDetails->brand_dt_name_en}}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.color')">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2"
                                                   value="{{ $contract->car->car_color }}"
                                                   style="background-color:transparent">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('carrent.car_model_year')">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2"
                                                   style="background-color:transparent"
                                                   value="{{ $contract->car->car_model_year }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.kilometers_traveled')">
                                        </div>
                                        <div class="col-md-6">
                                            @if($contract->status->system_code==13604)
                                                <input type="number" class="form-control text-center mt-2"
                                                       readonly name="total_km" value="{{$contract->total_km}}"
                                                       style="background-color:transparent">
                                            @else
                                                <input type="number" class="form-control text-center mt-2"
                                                       readonly name="total_km" v-model="taken_counter"
                                                       style="background-color:transparent">
                                            @endif

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.price_list_serial')">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2"
                                                   name="price_list_id"
                                                   id="price_list_id"
                                                   value="{{$contract->carPriceListHd?$contract->carPriceListHd->rent_list_code:''}}"
                                                   readonly style="background-color:transparent">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <input type="text" class="form-control text-center mt-2"
                                           value="@lang('home.contract_value')" readonly>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.type')">
                                        </div>

                                        <div class="col-md-2">
                                            <input type="text" class="form-control text-center mt-2"
                                                   value="@if($contract->contractType) {{ app()->getLocale() == 'ar'
                                            ? $contract->contractType->system_code_name_ar
                                            : $contract->contractType->system_code_name_en}} @else @endif"
                                                   style="background-color:transparent">
                                        </div>

                                        <div class="col-md-2">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.days_count')">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control text-center mt-2"
                                                   value="{{$contract->days_count}}"
                                                   style="background-color:transparent">
                                        </div>

                                        <div class="col-md-2">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.daily_cost')">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control text-center mt-2s"
                                                   value="{{$contract->rentDayCost}}"
                                                   style="background-color:transparent">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.total_daily_cost')">
                                        </div>
                                        <div class="col-md-6">
                                            @if($contract->status->system_code == 13604)
                                                <input type="number"
                                                       class="form-control text-center mt-2"
                                                       readonly style="background-color:transparent"
                                                       value="{{ $contract->rentDayCost  * $contract->days_count}}">
                                            @else
                                                <input type="number" style="background-color:transparent"
                                                       class="form-control text-center mt-2"
                                                       readonly v-model="TotalDailyCost">
                                            @endif

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.extra_kilometers')">
                                        </div>
                                        <div class="col-md-6">
                                            @if($contract->status->system_code == 13604)
                                                <input type="number"
                                                       class="form-control text-center mt-2"
                                                       readonly name="total_km_cost"
                                                       style="background-color:transparent"
                                                       value="{{$contract->total_km_cost}}">
                                            @else
                                                <input type="number"
                                                       class="form-control text-center mt-2"
                                                       style="background-color:transparent"
                                                       readonly name="total_km_cost" v-model="total_km_cost">
                                            @endif

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.extra_hour_price')">
                                        </div>

                                        <div class="col-md-6">
                                            @if($contract->status->system_code == 13604)
                                                <input type="number"
                                                       class="form-control text-center mt-2"
                                                       readonly name="total_hour_cost"
                                                       style="background-color:transparent"
                                                       value="{{$contract->total_hour_cost}}">
                                            @else
                                                <input type="number"
                                                       class="form-control text-center mt-2"
                                                       style="background-color:transparent"
                                                       readonly name="total_hour_cost" v-model="total_hour_cost">
                                            @endif

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control text-center mt-2" readonly
                                                   value="@lang('home.total_rent')">
                                        </div>

                                        <div class="col-md-6">

                                            @if($contract->status->system_code == 13604)
                                                <input type="number"
                                                       class="form-control text-center mt-2"
                                                       name="contract_net_amount"
                                                       style="background-color:transparent"
                                                       value="{{$contract->contract_net_amount}}" readonly>
                                            @else
                                                <input type="number"
                                                       class="form-control text-center mt-2"
                                                       style="background-color:transparent"
                                                       name="contract_net_amount" v-model="netActualCost" readonly>
                                            @endif

                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <input type="text" class="form-control text-center" readonly
                                           value="@lang('home.contract_end_date')">

                                </div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control text-center" readonly
                                           value="@lang('home.arrival_date') ">
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control text-center" readonly
                                           value="@lang('home.departure_counter') ">
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control text-center" readonly
                                           value="@lang('home.arrival_counter')">
                                </div>

                            </div>

                            <div class="row mt-1">
                                <div class="col-md-2">
                                    <input type="text" class="form-control text-center" readonly
                                           value="{{ $contract->contract_end_date_date }}"
                                           style="background-color:transparent">

                                </div>
                                <div class="col-md-1">
                                    <input type="text" class="form-control text-center" readonly
                                           value="{{ $contract->contract_end_date_time }}"
                                           style="background-color:transparent">

                                </div>

                                <div class="col-md-2">
                                    {{--<input type="text" class="form-control text-center"--}}
                                    {{--value="{{ \Carbon\Carbon::now()->format('Y-m-d')  }}">--}}

                                    @if($contract->closed_datetime)
                                        <input type="text" class="form-control text-center"
                                               style="background-color:transparent"
                                               readonly
                                               value="{{ $contract->contract_closed_date_date }}">
                                    @else
                                        <input type="text" class="form-control text-center">
                                    @endif
                                </div>
                                <div class="col-md-1">
                                    {{--<input type="text" class="form-control text-center"--}}
                                    {{--value="{{  \Carbon\Carbon::now()->format('H:i') }}">--}}

                                    @if($contract->closed_datetime)
                                        <input type="text" class="form-control text-center"
                                               value="{{ $contract->contract_closed_date_time }}">
                                    @else
                                        <input type="text" class="form-control text-center">
                                    @endif

                                </div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control text-center"
                                           style="background-color:transparent" readonly
                                           v-model="odometerReading">
                                </div>

                                <div class="col-md-3">


                                    @if($contract->status->system_code==13604)
                                        <input type="text" class="form-control text-center"
                                               name="odometerclosed"
                                               readonly style="background-color:transparent">
                                    @else
                                        <input type="text" class="form-control text-center" required
                                               v-model="arrival_counter" name="odometerclosed"
                                               min="{{$contract->odometerReading}}"
                                               style="background-color:transparent">
                                    @endif
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{--بيانات العميل--}}
                <div class="tab-pane fade" id="customer-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><a
                                        href="{{route('car-rent.customers.show',$contract->customer_id)}}">@lang('home.customer_data')</a>
                            </h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.id_number')</label>
                                    <input type="number" class="form-control" value="{{$contract->c_idNumber}}"
                                           readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.customer_type')</label>
                                    <input type="text" class="form-control" @if(app()->getLocale() == 'ar')
                                    value="{{$contract->customer->cus_type->system_code_name_ar}}"
                                           @else
                                           value="{{$contract->customer->cus_type->system_code_name_en}}"
                                           @endif readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.tamm_enddate_hejri')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->tamm_enddate_hejri}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('customer.point_count')</label>
                                    <input type="number" class="form-control" value="0" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.lemet_balance')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_credit_limit}}" readonly>
                                </div>

                                {{--مش موجود في العملا--}}
                                <div class="col-md-4">
                                    <label>@lang('customer.Customer_Membership_Number')</label>
                                    <input type="number" class="form-control" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('customer.customer_name')</label>
                                    <input type="text" class="form-control" readonly
                                           value="{{$contract->customer->customer_name_full_ar}}">
                                </div>

                                {{--مش موجوجه غي العملا--}}
                                <div class="col-md-4">
                                    <label>@lang('customer.membership')</label>
                                    <input type="number" class="form-control" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.company')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_company}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.nationality')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_nationality}}" readonly>
                                </div>
                                {{--مش محةد في العملا--}}
                                <div class="col-md-4">
                                    <label>@lang('customer.employer')</label>
                                    <input type="number" class="form-control"
                                           value="" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.job')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_job}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.customer_address_home')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_address_1}}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>@lang('home.address_work')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_address_2}}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.work_mobile')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_phone}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.phone_home')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_phone_home}}"
                                           name="customer_phone_home" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.private_mobile')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_mobile}}" name="customer_mobile"
                                           readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.email_work')</label>
                                    <input type="email" class="form-control"
                                           value="{{$contract->customer->customer_email}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.customer_birthday')</label>
                                    <input type="date" class="form-control"
                                           value="{{$contract->customer->customer_birthday}}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.customer_age')</label>
                                    <input type="number" class="form-control" v-model="customer_age" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.lemet_balance')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->customer->customer_credit_limit}}" readonly>
                                </div>

                            </div>


                        </div>
                        <div class="card-footer">
                            <a href="{{route('car-rent.customers.show',$contract->customer_id)}}"
                               class="btn btn-primary mr-2"
                               id="create_emp">@lang('home.customer_info')</a>
                        </div>
                    </div>

                </div>

                {{--بيانات السائق--}}
                <div class="tab-pane fade" id="driver-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.driver_data')</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.id_number')</label>
                                    <input type="number" class="form-control" value="{{$contract->d_idNumber}}"
                                           readonly>
                                </div>

                                {{--<div class="col-md-4">--}}
                                {{--<label>@lang('home.customer_type')</label>--}}
                                {{--<input type="text" class="form-control" @if(app()->getLocale() == 'ar')--}}
                                {{--value="{{$contract->customer->cus_type->system_code_name_ar}}"--}}
                                {{--@else--}}
                                {{--value="{{$contract->customer->cus_type->system_code_name_en}}"--}}
                                {{--@endif readonly>--}}
                                {{--</div>--}}

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('customer.point_count')</label>
                                    <input type="number" class="form-control" value="0" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.lemet_balance')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_credit_limit}}" readonly>
                                </div>

                                {{--مش موجود في العملا--}}
                                <div class="col-md-4">
                                    <label>@lang('customer.Customer_Membership_Number')</label>
                                    <input type="number" class="form-control" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('customer.customer_name')</label>
                                    <input type="text" class="form-control" readonly
                                           value="{{$contract->driver->customer_name_full_ar}}">
                                </div>

                                {{--مش موجوجه غي العملا--}}
                                <div class="col-md-4">
                                    <label>@lang('customer.membership')</label>
                                    <input type="number" class="form-control" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.company')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_company}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.nationality')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_nationality}}" readonly>
                                </div>
                                {{--مش محةد في العملا--}}
                                <div class="col-md-4">
                                    <label>@lang('customer.employer')</label>
                                    <input type="number" class="form-control"
                                           value="" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.job')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_job}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.customer_address_home')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_address_1}}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>@lang('home.address_work')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_address_2}}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.work_mobile')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_phone}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.phone_home')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_phone_home}}"
                                           name="customer_phone_home" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.private_mobile')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_mobile}}" name="customer_mobile"
                                           readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.email_work')</label>
                                    <input type="email" class="form-control"
                                           value="{{$contract->driver->customer_email}}" readonly>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label>@lang('home.customer_birthday')</label>
                                    <input type="date" class="form-control"
                                           value="{{$contract->driver->customer_birthday}}" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.customer_age')</label>
                                    <input type="number" class="form-control" v-model="customer_age" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('customer.lemet_balance')</label>
                                    <input type="text" class="form-control"
                                           value="{{$contract->driver->customer_credit_limit}}" readonly>
                                </div>

                            </div>


                        </div>
                        <div class="card-footer">
                            <a href="{{route('car-rent.customers.show',$contract->driver_id)}}"
                               class="btn btn-primary mr-2"
                               id="create_emp">@lang('home.driver_info')</a>
                        </div>
                    </div>

                </div>

                {{--بيانات السياره--}}
                <div class="tab-pane fade" id="car-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.car_data')</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">

                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('home.plate_number') </label>
                                                    <input type="text" class="form-control text-center"
                                                           style="background-color:transparent" readonly
                                                           value="{{ $contract->car->full_car_plate }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_chasi') </label>
                                                    <input type="number" class="form-control"
                                                           name="car_chase"
                                                           id="car_chase" value="{{$contract->car->car_chase}}">

                                                </div>

                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_operation_card_date') </label>
                                                    <input type="date" class="form-control"
                                                           name="car_operation_card_date"
                                                           id="car_operation_card_date"
                                                           value="{{$contract->car->car_operation_card_date}}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_status_id') </label>

                                                    <input type="text" class="form-control"
                                                           @if($contract->car->status)
                                                           @if(app()->getLocale() == 'ar')
                                                           value="{{$contract->car->status->system_code_name_ar}}"
                                                           @else
                                                           value="{{$contract->car->status->system_code_name_en}}"
                                                            @endif
                                                            @endif
                                                    >

                                                </div>

                                                <div class="col-md-6">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.platetype') </label>

                                                    <input type="text" class="form-control"
                                                           value="{{$contract->car->plateType?$contract->car->plateType['system_code_name_'.app()->getLocale()]:''}}"
                                                    >

                                                </div>

                                            </div>
                                        </div>


                                    </div>


                                </div>


                                <div class="col-md-3">
                                    <div class="mb-3">

                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">@lang('carrent.car_photo')</h3>
                                            </div>
                                            <div class="card-body">
                                                <img src="{{ $contract->car->car_photo_url }}" width="200" height="200">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>


                            <div class="mb-3">
                                <div class="row">


                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_brand') </label>

                                        {{--readonly--}}
                                        <input type="text" disabled value="{{ app()->getLocale()=='ar' ?
                                         $contract->car->brand->brand_name_ar : $contract->car->brand->brand_name_en}}"
                                               class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_model') </label>

                                        <input type="text" disabled value="{{ app()->getLocale()=='ar' ?
                                         $contract->car->brandDetails->brand_dt_name_ar : $contract->car->brandDetails->brand_dt_name_en}}"
                                               class="form-control">
                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('carrent.car_model_year') </label>
                                        <input type="text" class="form-control" name="car_model_year"
                                               id="car_model_year" value="{{$contract->car->car_model_year}}"
                                               disabled="">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_category') </label>
                                        <input type="text" class="form-control" name="car_category_id"
                                               id="car_category_id" value="{{app()->getLocale()=='ar' ? $contract->car->category->system_code_name_ar :
                                               $contract->car->category->system_code_name_en}}" disabled="">
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.gear_box_type_id') </label>

                                        <input type="text" class="form-control" name="gear_box_type_id"
                                               id="gear_box_type_id" value="{{app()->getLocale()=='ar' ? $contract->car->boxType->system_code_name_ar :
                                               $contract->car->boxType->system_code_name_en}}" disabled="">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.engine_type') </label>

                                        <input type="text" class="form-control" name="engine_type"
                                               id="engine_type" value="{{app()->getLocale()=='ar' ? $contract->car->engineType->system_code_name_ar :
                                               $contract->car->engineType->system_code_name_en}}" disabled="">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.fuel_type_id') </label>

                                        <input type="text" class="form-control" name="fuel_type_id"
                                               id="fuel_type_id" value="{{app()->getLocale()=='ar' ? $contract->car->fuelType->system_code_name_ar :
                                               $contract->car->fuelType->system_code_name_en}}" disabled="">

                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('carrent.car_color') </label>
                                        <input type="text" class="form-control" name="car_color"
                                               id="car_color" value="{{$contract->car->car_color}}" readonly>

                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.oil_type') </label>

                                    <input type="text" class="form-control" name="oil_type"
                                           id="oil_type" value="{{app()->getLocale()=='ar' ? $contract->car->oilType->system_code_name_ar :
                                               $contract->car->oilType->system_code_name_en}}" disabled="">

                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.oil_change_km') </label>
                                    <input type="number" class="form-control " name="oil_change_km"
                                           id="oil_change_km" value="{{$contract->car->oil_change_km}}" readonly>

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_doors') </label>
                                    <input type="number" class="form-control " name="car_doors"
                                           id="car_doors" value="{{$contract->car->car_doors}}" readonly="">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_passengers') </label>
                                    <input type="number" class="form-control " name="car_passengers"
                                           id="car_passengers" value="{{$contract->car->car_passengers}}" readonly>

                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.insurance_type') </label>
                                    <input type="text" class="form-control " name="insurance_document_no"
                                           id="insurance_document_no" readonly
                                           @if($contract->car->insuranceType)
                                           @if(app()->getLocale() == 'ar')
                                           value="{{$contract->car->insuranceType->system_code_name_ar}}"
                                           @else
                                           value="{{$contract->car->insuranceType->system_code_name_en}}"
                                            @endif
                                            @endif

                                    >

                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.insurance_document_no') </label>
                                    <input type="number" class="form-control " name="insurance_document_no"
                                           id="insurance_document_no" readonly
                                           value="{{$contract->car->insurance_document_no}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.insurance_value') </label>
                                    <input type="number" class="form-control " name="insurance_value"
                                           id="insurance_value" readonly value="{{$contract->car->insurance_value}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.insurance_date_end') </label>
                                    <input type="date" class="form-control " name="insurance_date_end"
                                           id="insurance_date_end" readonly
                                           value="{{$contract->car->insurance_date_end ? $contract->car->insurance_date_end : ''}}">
                                </div>


                            </div>


                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.car_trucker_status') </label>

                                    <input type="text" readonly class="form-control"
                                           @if($contract->car->truckerStatus)
                                           @if(app()->getLocale() == 'ar')
                                           value=" {{$contract->car->truckerStatus->system_code_name_ar}}"
                                           @else
                                           value=" {{$contract->car->truckerStatus->system_code_name_en}}"
                                            @endif
                                            @endif
                                    >
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.tracker_serial') </label>
                                    <input type="number" readonly class="form-control " name="tracker_serial"
                                           id="tracker_serial" value="{{$contract->car->tracker_serial}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.tracker_supplier') </label>
                                    <input type="text" readonly class="form-control " name="tracker_supplier"
                                           id="tracker_supplier" value="{{$contract->car->tracker_supplier}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.tracker_install_date') </label>
                                    <input type="date" readonly class="form-control " name="tracker_install_date"
                                           id="tracker_install_date"
                                           value="{{ $contract->car->tracker_install_date }}">
                                </div>


                            </div>

                        </div>

                    </div>

                </div>


                {{------------receipt-grid---------سند القبض----------------------------------------------------------}}
                <div class="tab-pane fade" id="receipt-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.receipt')</h3>


                            <a href="{{ url('bonds-add/capture/create?contract_id='.$contract->contract_id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fe fe-plus mr-2"></i> @lang('home.add_new_capture')</a>


                        </div>
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table
                                            class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.bonds_number')</th>
                                            <th>@lang('home.bonds_date')</th>
                                            <th>@lang('home.bonds_type')</th>
                                            <th>@lang('home.branch')</th>
                                            <th>@lang('home.bonds_account')</th>
                                            <th>@lang('home.payment_method')</th>
                                            <th>@lang('home.value')</th>
                                            <th>@lang('home.user')</th>
                                            <th>@lang('home.journal')</th>
                                            <th></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bonds_capture as $bond_capture)
                                            <tr>
                                                <td>{{ $bond_capture->bond_code }}</td>
                                                <td>{{ $bond_capture->created_date }}</td>
                                                {{--<td>{{ app()->getLocale() == 'ar' ?--}}
                                                {{--$bond_capture->company->company_name_ar :--}}
                                                {{--$bond_capture->company->company_name_en }}</td>--}}
                                                <td>{{app()->getLocale() == 'ar'
                                                ? $bond_capture->bondDocType->system_code_name_ar
                                                : $bond_capture->bondDocType->system_code_name_en}}</td>

                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond_capture->branch->branch_name_ar :
                                            $bond_capture->branch->branch_name_en }}</td>
                                                <td>{{ $bond_capture->bond_acc_id }}</td>
                                                <td>{{ app()->getLocale() == 'ar' ? $bond_capture->paymentMethod->system_code_name_ar :
                                              $bond_capture->paymentMethod->system_code_name_en }}</td>
                                                <td>{{ $bond_capture->bond_amount_debit }}</td>
                                                <td>{{ app()->getLocale()=='ar' ? $bond_capture->userCreated->user_name_ar :
                                            $bond_capture->userCreated->user_name_en }}</td>
                                                <td>

                                                    @if($bond_capture->journalCaptureContract)
                                                        <a href="{{ route('journal-entries.show',$bond_capture->journalCaptureContract->journal_hd_id) }}"
                                                           class="btn btn-primary btn-sm">
                                                            @lang('home.journal_details')
                                                            {{$bond_capture->journalCaptureContract->journal_hd_code}}
                                                        </a>
                                                    @endif


                                                </td>
                                                <td>
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$bond_capture->report_url_payment->report_url}}&id={{$bond_capture->bond_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-print"></i></a>
                                                    <a href="{{ route('Bonds-cash.show',$bond_capture->bond_id) }}"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                {{------------cash-grid---------سند الصرف-------------------------------------------------------------}}

                <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.contract_details')</h3>
                        </div>
                        <div class="card-body">

                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table
                                            class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.bonds_number')</th>
                                            <th>@lang('home.bonds_date')</th>
                                            <th>@lang('home.bonds_type')</th>
                                            <th>@lang('home.branch')</th>
                                            <th>@lang('home.bonds_account')</th>
                                            <th>@lang('home.payment_method')</th>
                                            <th>@lang('home.value')</th>
                                            <th>@lang('home.user')</th>
                                            <th>@lang('home.journal')</th>
                                            <th></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bonds_cash as $bond)
                                            <tr>
                                                <td>{{ $bond->bond_code }}</td>
                                                <td>{{ $bond->created_date }}</td>
                                                {{--<td>{{ app()->getLocale() == 'ar' ?--}}
                                                {{--$bond->company->company_name_ar :--}}
                                                {{--$bond->company->company_name_en }}</td>--}}
                                                <td>@if($bond->bondDocType)
                                                        {{app()->getLocale() == 'ar'
                                                                                                        ? $bond->bondDocType->system_code_name_ar
                                                                                                        : $bond->bondDocType->system_code_name_en}}
                                                    @else  @endif

                                                </td>

                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                                <td>{{ $bond->bond_acc_id }}</td>
                                                <td>{{ app()->getLocale() == 'ar' ? $bond->paymentMethod->system_code_name_ar :
                                              $bond->paymentMethod->system_code_name_en }}</td>
                                                <td>{{ $bond->bond_amount_credit }}</td>
                                                <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                                <td>

                                                    @if($bond->journalBondCashRentContract)
                                                        <a href="{{ route('journal-entries.show',$bond->journalBondCashRentContract->journal_hd_id) }}"
                                                           class="btn btn-primary btn-sm">
                                                            @lang('home.journal_details')
                                                            {{$bond->journalBondCashRentContract->journal_hd_code}}
                                                        </a>
                                                    @endif


                                                </td>
                                                <td>
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-print"></i></a>
                                                    <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                {{------------discount-grid---------سند الخصم---------------------------------------------------------}}
                <div class="tab-pane fade" id="discount-bonds-grid" role="tabpanel">
                    <div class="card">

                        <div class="card-header">
                            <a href="{{ url('bonds-add/discount/create?contract_id='.$contract->contract_id) }}"
                               class="btn btn-primary">@lang('home.discount_bond')</a>
                        </div>
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table
                                            class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.bonds_number')</th>
                                            <th>@lang('home.bonds_date')</th>
                                            <th>@lang('home.bonds_type')</th>
                                            <th>@lang('home.branch')</th>
                                            <th>@lang('home.bonds_account')</th>
                                            <th>@lang('home.payment_method')</th>
                                            <th>@lang('home.value')</th>
                                            <th>@lang('home.user')</th>
                                            <th></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bonds_discount as $bond_discount)
                                            <tr>
                                                <td>{{ $bond_discount->bond_code }}</td>
                                                <td>{{ $bond_discount->created_date }}</td>
                                                {{--<td>{{ app()->getLocale() == 'ar' ?--}}
                                                {{--$bond_discount->company->company_name_ar :--}}
                                                {{--$bond_discount->company->company_name_en }}</td>--}}
                                                <td>{{app()->getLocale() == 'ar'
                                                ? $bond_discount->bondDocType->system_code_name_ar
                                                : $bond_discount->bondDocType->system_code_name_en}}</td>


                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond_discount->branch->branch_name_ar :
                                            $bond_discount->branch->branch_name_en }}</td>
                                                <td>{{ $bond_discount->bond_acc_id }}</td>
                                                <td>{{ app()->getLocale() == 'ar' ? $bond_discount->paymentMethod->system_code_name_ar :
                                              $bond_discount->paymentMethod->system_code_name_en }}</td>
                                                <td>{{ $bond_discount->bond_amount_debit }}</td>
                                                <td>{{ app()->getLocale()=='ar' ?
                                                $bond_discount->userCreated->user_name_ar :
                                                $bond_discount->userCreated->user_name_en }}</td>

                                                <td>
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$bond_discount->report_url_payment->report_url}}&id={{$bond_discount->bond_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-print"></i></a>
                                                    <a href="{{route('Bonds-addition.show',$bond_discount->bond_id)}}"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                {{------------addition-grid---------سند الإضافه---------------------------------------------------------}}

                <div class="tab-pane fade" id="addition-bonds-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">

                            <h3 class="card-title">@lang('home.addition_bonds')</h3>

                            <a href="{{ url('bonds-add/addition/create?contract_id='.$contract->contract_id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fe fe-plus mr-2"></i> @lang('home.addition_bonds')</a>


                        </div>
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table
                                            class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.bonds_number')</th>
                                            <th>@lang('home.bonds_date')</th>
                                            <th>@lang('home.bonds_type')</th>
                                            <th>@lang('home.branch')</th>
                                            <th>@lang('home.bonds_account')</th>
                                            <th>@lang('home.payment_method')</th>
                                            <th>@lang('home.value')</th>
                                            <th>@lang('home.user')</th>
                                            <th></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bonds_addition as $bond_addition)
                                            <tr>
                                                <td>{{ $bond_addition->bond_code }}</td>
                                                <td>{{ $bond_addition->created_date }}</td>
                                                {{--<td>{{ app()->getLocale() == 'ar' ?--}}
                                                {{--$bond_addition->company->company_name_ar :--}}
                                                {{--$bond_addition->company->company_name_en }}</td>--}}
                                                <td>{{app()->getLocale() == 'ar'
                                                ? $bond_addition->bondDocType->system_code_name_ar
                                                : $bond_addition->bondDocType->system_code_name_en}}</td>

                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond_addition->branch->branch_name_ar :
                                            $bond_addition->branch->branch_name_en }}</td>
                                                <td>{{ $bond_addition->bond_acc_id }}</td>
                                                <td>{{ app()->getLocale() == 'ar' ?
                                                $bond_addition->paymentMethod->system_code_name_ar :
                                                $bond_addition->paymentMethod->system_code_name_en }}</td>
                                                <td>{{ $bond_addition->bond_amount_debit }}</td>
                                                <td>{{ app()->getLocale()=='ar' ?
                                                $bond_addition->userCreated->user_name_ar :
                                                $bond_addition->userCreated->user_name_en }}</td>

                                                <td>
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$bond_addition->report_url_payment->report_url}}&id={{$bond_addition->bond_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-print"></i></a>
                                                    <a href="{{route('Bonds-addition.show',$bond_addition->bond_id)}}"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                {{------------invoice-grid----------------------------------------------------------------------------}}

                <div class="tab-pane fade" id="invoice-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('invoice.invoices')</h3>
                        </div>
                        <div class="card-body">

                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table
                                            class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">

                                        <thead style="background-color: #ece5e7">
                                        <tr class="red" style="font-size: 16px;font-style: inherit">
                                            <th></th>
                                            <th>@if(app()->getLocale() == 'en')
                                                    @sortablelink('invoice_no','Invoice Number')
                                                @else
                                                    @sortablelink('invoice_no','رقم الفاتوره')
                                                @endif</th>
                                            <th>
                                                @if(app()->getLocale() == 'en')
                                                    @sortablelink('invoice_date','Invoice Date')
                                                @else
                                                    @sortablelink('invoice_date','تاريخ الفاتوره')
                                                @endif
                                            </th>
                                            <th>@lang('invoice.sub_company')</th>
                                            <th>{{__('from')}}</th>
                                            <th>{{__('to')}}</th>
                                            <th>{{__('days count')}}</th>
                                            <th>{{__('day cost')}}</th>
                                            <th>{{__('journal')}}</th>
                                            <th>@lang('invoice.invoice_total')</th>
                                            <th>@lang('invoice.invoice_payment')</th>

                                            <th>@lang('invoice.invoice_status')</th>
                                            <th colspan="2"></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($invoices as $k=>$invoice)
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td>{{ $invoice->invoice_no }}</td>
                                                <td>{{ $invoice->invoice_date }}</td>
                                                <td>{{ app()->getLocale()=='ar' ? $invoice->company->company_name_ar :
                                            $invoice->company->company_name_en }}</td>

                                                <td>{{\Carbon\Carbon::parse($invoice->invoiceDetails[0]->invoice_from_date)->format('d-m-Y')}}</td>
                                                <td>{{\Carbon\Carbon::parse($invoice->invoiceDetails[0]->invoice_to_date)->format('d-m-Y')}}</td>
                                                <td>{{(int)$invoice->invoiceDetails[0]->invoice_item_quantity}}</td>
                                                <td>{{$contract->rentDayCost}}</td>

                                                <td>
                                                    @if($invoice->journalHd)
                                                        <a href="{{route('journal-entries.edit',$invoice->journal_hd_id)}}"
                                                           class="btn btn-link">{{  $invoice->journalHd->journal_code}}</a>
                                                    @endif
                                                </td>
                                                <td>{{number_format($invoice->invoice_total,2 )}}</td>
                                                <td>{{number_format($invoice->invoice_total_payment,2 )}}</td>
                                                <td>{{ $invoice->invoice_is_payment ? 'مدفوعه' : 'غير مدفوعه' }}</td>

                                                <td colspan="2">

                                                    <a href="{{ route('invoices-acc.show',$invoice->invoice_id) }}"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    {{--<a href="{{ url('bonds-add/capture/create?invoice_id='.$invoice->invoice_id) }}"--}}
                                                    {{--class="btn btn-primary btn-sm">--}}
                                                    {{--@lang('home.add_bond')</a>--}}


                                                    <a
                                                            href="{{config('app.telerik_server')}}?rpt={{$invoice->report_url_acc->report_url}}&id={{$invoice->invoice_id}}&lang=ar&skinName=bootstrap"
                                                            title="{trans('Print')}" class="btn btn-primary btn-sm"
                                                            id="showReport" target="_blank">
                                                        {{trans('Print')}}
                                                    </a>


                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="4"><span
                                                        style="text-align:center;font-weight: bold">
                                            @lang('home.total') </span></td>
                                            <td colspan="4"><span
                                                        style="text-align:center;font-weight: bold"> {{ $total_amount }}</span>
                                            </td>
                                        </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="tab-pane fade" id="accidents-and-damages-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.accidents_and_damages')</h3>
                            <a href="{{route('car-accident.create')}}?contract_id={{$contract->contract_id}}"
                               class="btn btn-primary">@lang('home.accidents_and_damages')</a>
                        </div>
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table
                                            class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">

                                        <thead style="background-color: #ece5e7">
                                        <tr class="red" style="font-size: 16px;font-style: inherit">
                                            <th>@lang('home.accident_number')</th>
                                            <th>@lang('home.accident_type')</th>
                                            <th>@lang('home.branch')</th>
                                            <th>@lang('home.date')</th>
                                            <th>@lang('home.insurance_company')</th>
                                            <th>@lang('home.Compensation_amount')</th>
                                            <th>@lang('home.payment_balance')</th>
                                            <th>@lang('home.add_bond')</th>
                                            <th>@lang('home.delete')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($contract->carAccidents as $car_accident)
                                            <tr>
                                                <td>{{$car_accident->car_accident_code}}</td>
                                                <td>{{app()->getLocale()=='ar' ?
                                                $car_accident->accidentType->system_code_name_ar :  $car_accident->accidentType->system_code_name_en}}</td>
                                                <td>{{app()->getLocale()=='ar' ?
                                                $car_accident->branch->branch_name_ar :  $car_accident->branch->branch_name_en}}</td>
                                                <td> {{$car_accident->car_accident_date}}</td>
                                                <td>
                                                    @if(  $car_accident->accidentInsuranceCompany)
                                                        {{app()->getLocale()=='ar' ?
                                                    $car_accident->accidentInsuranceCompany->system_code_name_ar :  $car_accident->accidentInsuranceCompany->system_code_name_en}}</td>
                                                @else
                                                    <p>لا يوجد شركه تامين</p>
                                                @endif
                                                <td>{{$car_accident->car_accident_amount}}</td>
                                                <td>{{$car_accident->car_accident_payment}}</td>
                                                <td>
                                                    <a href="{{url('bonds-add/capture/create?car_accident_id=') }}{{$car_accident->car_accident_id}}"
                                                       class="btn btn-primary btn-sm" title="show">
                                                        <i class="fa fa-plus-square"></i></a>
                                                </td>
                                                <td>
                                                    @if($car_accident->car_accident_payment == 0)
                                                        <form
                                                                action="{{ route('car-accident.delete',$car_accident->car_accident_id) }}"
                                                                method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-primary"
                                                                    type="submit">@lang('home.delete')</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('car-accident.edit',$car_accident->car_accident_id) }}?path=contract"
                                                       class="btn btn-primary btn-sm" title="show">
                                                        <i class="fa fa-eye"></i></a>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                {{------------Practical_attachment_grid---------------------------------------------------------------}}
                <div class="tab-pane fade" id="attachments-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.attachments')</h3>
                        </div>
                        <div class="card-body">

                            <div class="row clearfix">
                                <div class="col-md-12">


                                    <x-files.form>
                                        <input type="hidden" name="transaction_id"
                                               value="{{$contract->contract_id}}">
                                        <input type="hidden" name="app_menu_id" value="44">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('home.attachment_type')</label>
                                                <select class="form-control" name="attachment_type" required>
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($attachment_types as $attachment_type)
                                                        <option value="{{ $attachment_type->system_code }}">
                                                            {{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
                                                     : $attachment_type->system_code_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </x-files.form>


                                    <x-files.attachment>

                                        @foreach($attachments as $attachment)

                                            <tr>
                                                <td>{{ app()->getLocale()=='ar' ?
                                         $attachment->attachmentType->system_code_name_ar :
                                          $attachment->attachmentType->system_code_name_en}}</td>
                                                <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                                <td>{{ $attachment->issue_date_hijri }}</td>
                                                <td>{{ $attachment->expire_date_hijri }}</td>
                                                <td>{{ $attachment->copy_no }}</td>
                                                <td>
                                                    <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                        <i class="fa fa-download fa-2x"></i></a>
                                                    <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                                       target="_blank" class="mr-1 ml-1"><i
                                                                class="fa fa-eye text-info mr-3 ml-3"
                                                                style="font-size:20px"></i></a>

                                                    <form
                                                            action="{{ route('employees-attachment.delete',$attachment->attachment_id) }}"
                                                            method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-icon on-default button-remove"
                                                                type="submit" data-original-title="Remove"><i
                                                                    class="icon-trash" aria-hidden="true"></i>
                                                        </button>

                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="badge text-gray text-wrap" style="width: 400px;">
                                                        {{ $attachment->attachment_data }}</div>
                                                </td>
                                                <td>{{ $attachment->userCreated->user_name_ar }}</td>
                                                <td>{{ $attachment->created_at }}</td>
                                            </tr>

                                        @endforeach

                                    </x-files.attachment>


                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                {{------------Practical_notes_grid--------------------------------------------------------------------}}
                <div class="tab-pane fade" id="notes-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.notes')</h3>
                        </div>
                        <div class="card-body">

                            <div class="row clearfix">

                                <div class="col-md-12">

                                    <x-files.form-notes>

                                        <input type="hidden" name="transaction_id"
                                               value="{{$contract->contract_id}}">
                                        <input type="hidden" name="app_menu_id" value="44">


                                    </x-files.form-notes>


                                    <x-files.notes>
                                        @foreach($notes as $note)
                                            <tr>
                                                <td>
                                                    <div class="badge text-gray text-wrap" style="width: 400px;">
                                                        {{ $note->notes_data }}</div>
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($note->notes_date )) }}</td>
                                                <td>{{ $note->user->user_name_ar }}</td>
                                                <td>{{ $note->notes_serial }}</td>
                                            </tr>
                                        @endforeach
                                    </x-files.notes>


                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="tab-pane fade" id="procedure-grid" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.procedure')</h3>
                        </div>
                        <div class="card-body">

                        </div>

                    </div>

                </div>

                {{--   take photos  --}}
                <div class="tab-pane fade" id="photos-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <form action="{{route('car-rent.storePhoto')}}" method="post"
                                      enctype="multipart/form-data" class="m-3">
                                    @csrf
                                    <h5>{{__('Take Photo')}}</h5>
                                    <input type="hidden" name="contract_id" value="{{$contract->contract_id}}">
                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" name="image"
                                           capture="user" style="visibility:hidden;"
                                           accept="image/*">
                                    <button class="btn btn-primary m-auto" type="submit">@lang('home.save')</button>
                                </form>
                            </div>

                        </div>

                        <div class="row row-cards">

                            @foreach($photos_attachments as $photo_attachment)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card p-3">
                                        <a href="javascript:void(0)" class="mb-3">
                                            <img class="rounded"
                                                 src="{{ asset('RentContract/'.$photo_attachment->attachment_file_url) }}"
                                                 alt="">
                                        </a>
                                        <div class="d-flex align-items-center px-2">
                                            <img class="avatar avatar-md mr-3"
                                                 src="{{ asset('RentContract/'.$photo_attachment->attachment_file_url) }}"
                                                 alt="">
                                            <div>
                                                <div>{{$photo_attachment->userCreated->user_name_ar}}</div>
                                                <small class="d-block text-muted">{{$photo_attachment->issue_date}}</small>
                                            </div>
                                            <div class="ml-auto text-muted">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#submit-contract').click(function () {
                let total_due = $('#total_due').val()
                let odometerclosed = $('#odometerclosed').val()
                let odometerReading = $('#odometerReading').val()
                if (odometerclosed > odometerReading) {
                    if (total_due > 0) {
                        Swal.fire({
                            title: 'هل انت متاكد؟',
                            text: "هل انت متاكد من اضافه سند قبض؟",
                            icon: 'info',
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'ألغاء',
                            confirmButtonText: 'بسند قبض',
                            denyButtonText: `بدون سند قبض`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#add_bond_capture').val(1)
                                $('#my-form').submit()
                            } else if (result.isDenied) {
                                $('#add_bond_capture').val(0)
                                $('#my-form').submit()
                            }
                        })
                        // this.form.submit()
                    } else if (total_due < 0) {
                        Swal.fire({
                            title: 'هل انت متاكد؟',
                            text: "هل انت متاكد من اضافه سند صرف؟",
                            icon: 'info',
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'ألغاء',
                            confirmButtonText: 'بسند صرف',
                            denyButtonText: `بدون سند صرف`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#add_bond_cash').val(1)
                                $('#my-form').submit()
                            } else if (result.isDenied) {
                                $('#add_bond_cash').val(0)
                                $('#my-form').submit()
                            }
                        })
                        // this.form.submit()
                    }
                    // else {
                    //     $('#my-form').submit()
                    // }
                }
            })

            $('#add_files').click(function () {
                var display = $("#add_files_form").css("display");
                if (display == 'none') {
                    $('#add_files_form').css('display', 'block')
                } else {
                    $('#add_files_form').css('display', 'none')
                }

            });

            $('#add_note').click(function () {
                var display = $("#add_note_form").css("display");
                if (display == 'none') {
                    $('#add_note_form').css('display', 'block')
                } else {
                    $('#add_note_form').css('display', 'none')
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                contract_id: '',
                contract: {},
                customer_birthday: '{{$contract->customer->customer_birthday}}',
                customer_age: '',

                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                arrival_counter: 0,
                odometerReading: 0,
                allowedKmPerDay: 0,
                extraKmCost: 0,
                extraHours: 0,
                allow_hr_to_day: 0,
                allowedLateHours: 0,
                actualDaysCount: 0,
                rentHourCost: 0,
                rentDayCost: 0,
                discount: 0,
                contract_vat_rate: 0,
                contract_total_add: 0,
                paid: 0,
                daysCount: 0,
                total_hr_count: 0,

            },

            mounted() {

                this.getDifferenceDate()

                this.contract_id = {!! $id !!}

                    this.getContractData()

                $('#issue_date_hijri').on("dp.change", (e) => {
                    this.issue_date_hijri = $('#issue_date_hijri').val()
                    this.getGeorgianDate()
                });

                $('#expire_date_hijri').on("dp.change", (e) => {
                    this.expire_date_hijri = $('#expire_date_hijri').val()
                    this.getGeorgianDate2()
                });
            },

            methods: {
                getContractData() {
                    if (this.contract_id) {
                        $.ajax({
                            type: 'GET',
                            data: {contract_id: this.contract_id},
                            url: ''
                        }).then(response => {
                            this.contract = response.data
                            this.odometerReading = this.contract.odometerReading
                            this.allowedKmPerDay = this.contract.allowedKmPerDay
                            this.extraKmCost = this.contract.extraKmCost
                            this.extraHours = this.contract.extra_hours
                            this.allow_hr_to_day = this.contract.allow_hr_to_day
                            // this.allow_hr_to_day = 3
                            this.allowedLateHours = this.contract.allowedLateHours
                            this.actualDaysCount = this.contract.actual_days_count
                            this.daysCount = this.contract.days_count2
                            this.rentHourCost = this.contract.rentHourCost
                            this.rentDayCost = this.contract.rentDayCost
                            this.discount = this.contract.contract_total_discount
                            this.total_hr_count = this.contract.total_hr_count
                            this.contract_vat_rate = this.contract.contract_vat_rate / 100
                            this.paid = this.contract.paid
                            this.contract_total_add = this.contract.contract_total_add ?
                                this.contract.contract_total_add : 0;
                        })


                    }
                }
                , getGeorgianDate() {
                    if (this.issue_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.issue_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.issue_date = response.data
                        })
                    }
                }
                , getGeorgianDate2() {
                    if (this.expire_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.expire_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.expire_date = response.data
                        })
                    }
                }
                ,

                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                }
                ,

                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                }
                ,

                getIssueDateHijri() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date_hijri},
                        url: '{{ route("api.getDate2") }}'
                    }).then(response => {
                        this.issue_date = response.data
                    })
                }
                ,

                getDifferenceDate() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_birthday: this.customer_birthday},
                        url: '{{ route("car-rent.customers.getDifferenceDate") }}'
                    }).then(response => {
                        this.customer_age = response.data
                    })
                }
                ,

            },
            computed: {

                taken_counter: function () {
                    var t = parseInt(this.arrival_counter) - parseInt(this.odometerReading);
                    if (t > 0) {
                        return t;
                    } else {
                        return 0;
                    }

                },

                total_km_count: function () {
                    var s = this.taken_counter - parseInt(this.allowedKmPerDay) * parseInt(this.actualDaysCount)
                    if (s > 0) {
                        return s;
                    } else {
                        return 0;
                    }
                },

                total_km_cost: function () {
                    var a = this.total_km_count * parseFloat(this.extraKmCost)
                    return a.toFixed(2)
                },

                // total_hr_count: function () {
                //
                //     var d = parseInt(this.extraHours) - parseInt(this.allowedLateHours);
                //
                //     if (d > 0) {
                //         if (d >= this.allow_hr_to_day) {
                //             this.actualDaysCount = this.actualDaysCount + 1
                //             return 0; ////يزيد  يوم علي عدد ايام العقد
                //         } else {
                //             return d; //////////يتحسب اجالي تكلفه الساعات الزياده
                //         }
                //
                //     } else {
                //         return 0;
                //     }
                // },

                total_hour_cost: function () {
                    return parseFloat(this.rentHourCost) * this.total_hr_count
                },
                TotalDailyCost: function () {
                    return parseFloat(this.rentDayCost) * parseFloat(this.actualDaysCount)
                },
                subCost: function () {
                    var sub = parseFloat(this.TotalDailyCost) + parseFloat(this.total_km_cost) +
                        parseFloat(this.total_hour_cost);
                    return sub.toFixed(2)
                },
                vat_amount: function () {
                    var vat = (parseFloat(this.subCost) - parseFloat(this.discount)) * this.contract_vat_rate;
                    return vat.toFixed(2)
                },
                netActualCost: function () {
                    var t1 = this.subCost;

                    var vat = (t1 - parseFloat(this.discount)) * this.contract_vat_rate;


                    var t = t1 - parseFloat(this.discount) + vat + parseFloat(this.contract_total_add)
                    return t.toFixed(2)
                },
                total_due: function () {
                    return (this.netActualCost - parseFloat(this.paid)).toFixed(2)
                },
                days_diff_count: function () {
                    return (this.daysCount - this.actualDaysCount);
                }
            }
        })

    </script>

@endsection
