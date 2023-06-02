@extends('Layouts.master')

@section('style')
    {{--<link rel="stylesheet"--}}
    {{--href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">--}}
    {{--<style>--}}
    {{--.bootstrap-select {--}}
    {{--width: 100% !important;--}}
    {{--}--}}
    {{--</style>--}}
    <style type="text/css">
        .ctd {
            text-align: center;

        }

        .full {
            padding-left: 40%;
        }
    </style>

@endsection


@section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#edit-grid" data-toggle="tab" style="font-size: 16px ;font-weight: bold"
                           class="nav-link @if(request()->qr == 'applications') active @endif">@lang('home.trip_info')</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#bonds-cash-grid"
                                            style="font-size: 16px ;font-weight: bold"
                                            data-toggle="tab">@lang('home.bonds_cash')</a></li>


                    <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                            style="font-size: 18px ;font-weight: bold"
                                            data-toggle="tab">@lang('home.notes')  </a></li>

                    <li class="nav-item"><a class="nav-link" href="#entitlement-journals-grid"
                                            style="font-size: 18px ;font-weight: bold"
                                            data-toggle="tab">{{__('Entitlement Journals')}}  </a></li>

                    <li class="nav-item"><a class="nav-link" href="#cars-grid"
                                            style="font-size: 18px ;font-weight: bold"
                                            data-toggle="tab">@lang('home.trip_car_details')  {{$trip->tripdts->count()}} </a>
                    </li>

                </ul>
                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">


            <div class="tab-content mt-3">

                {{-- edit form --}}
                <div class="tab-pane fade show active" id="edit-grid" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('Trips.update' , $trip->trip_hd_id)}}" method="post">
                                        @csrf
                                        @method('put')
                                        <div class="row clearfix">

                                            {{-- كود الرحلة --}}

                                            <div class="col-lg-2 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px"
                                                           class="control-label">@lang('home.trip_number')</label>
                                                    <input type="text" class="form-control text-center"
                                                           style="font-size: 16px"
                                                           value="{{$trip->trip_hd_code}}"
                                                           readonly>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px"
                                                           class="control-label">@lang('home.trip_status')</label>


                                                    @if($trip->status->system_code == 39001)
                                                        <select class="form-control" name="trip_hd_status">
                                                            @foreach($trip_statuses aS $status)
                                                                <option value="{{$status->system_code_id}}"
                                                                        @if($trip->status->system_code_id==$status->system_code_id)
                                                                        selected @endif>
                                                                    {{app()->getLocale() == 'ar' ? $status->system_code_name_ar :
                                                                    $status->system_code_name_en}}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    @else
                                                        <input type="text" class="form-control text-center"
                                                               value="{{app()->getLocale() == 'ar' ?
                                                       $trip->status->system_code_name_ar :
                                                       $trip->status->system_code_name_en }}"
                                                               readonly>
                                                    @endif
                                                </div>
                                            </div>


                                            {{-- كود الشاحنه --}}
                                            <div class="col-lg-4 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px"
                                                           class="control-label">@lang('home.truck_code')</label>
                                                    <input type="text" name="truck_code" class="form-control"
                                                           value="{{$trip->truck->truck_code .' => '. $trip->truck->truck_name .' => '. $trip->truck->truck_plate_no}}"
                                                           readonly>
                                                </div>
                                            </div>

                                            {{-- رقم التوثيق --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone-ex" style="font-size: 16px"
                                                           class="control-label">@lang('home.trip_id_naql')</label>
                                                    <input type="text" value="{{$trip->trip_id}}"
                                                           style="font-size: 16px"
                                                           class="form-control" readonly>

                                                </div>
                                            </div>

                                            {{--نوع الناقله--}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone-ex" style="font-size: 16px"
                                                           class="control-label">@lang('home.truck_type')</label>
                                                    @if(app()->getLocale()=='ar')
                                                        <input type="text" id=""
                                                               style="font-size: 16px"
                                                               value="{{$trip->truck->truckType->system_code_name_ar}}"
                                                               class="form-control" readonly>
                                                    @else
                                                        <input type="text" style="font-size: 16px"
                                                               value="{{$trip->truck->truckType->system_code_name_en}}"
                                                               class="form-control" readonly>
                                                    @endif
                                                </div>
                                            </div>


                                            {{-- تاريخ الانشاء --}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="ssn" style="font-size: 16px"
                                                           class="control-label">@lang('home.trip_start_date')</label>
                                                    <input type="text" id="" name="trip_hd_date"
                                                           class="form-control"
                                                           readonly
                                                           value="{{ date('d-m-y H:I', strtotime($trip->trip_hd_date) )}}">

                                                </div>
                                            </div>

                                            {{-- كود السائق --}}
                                            <div hidden class="col-lg-1 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.driver_code')</label>
                                                    <input type="text" name="driver_code"
                                                           class="form-control" value="{{$trip->driver ?
                                                       $trip->driver->emp_code : ''}}"
                                                           readonly>
                                                </div>
                                            </div>

                                            {{-- اسم السائق--}}
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.driver_name')</label>
                                                    <input type="text" name="driver_id"
                                                           style="font-size: 14px"
                                                           class="form-control" @if($trip->driver) value="{{
                                                       app()->getLocale()== 'ar'
                                                       ? $trip->driver->emp_code.' => '.$trip->driver->emp_name_full_ar
                                                       : $trip->driver->emp_code.' => '.$trip->driver->emp_name_full_en}}"
                                                           @endif readonly>
                                                </div>
                                            </div>
                                            {{--اسم السائق--}}
                                            <div hidden class="col-lg-3 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    @if($trip->driver)
                                                        <input type="hidden" value="{{$trip->driver->emp_id}}"
                                                               name="driver_id" id="driver_id">
                                                    @endif
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.driver_name')</label>

                                                </div>
                                            </div>


                                            {{-- رقم الجوال --}}
                                            <div class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.phone_number')</label>
                                                    @if($trip->driver)
                                                        <input type="number"
                                                               value="{{$trip->driver->emp_private_mobile}}"
                                                               name="driver_mobil"
                                                               class="form-control"
                                                               style="font-size: 16px">
                                                    @endif
                                                </div>
                                            </div>
                                            {{--رقم النسخه --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="issueNumber" style="font-size: 16px"
                                                           class="control-label">@lang('home.issue_number')</label>
                                                    @if($trip->driver)
                                                        <input type="number" id="issueNumber" name="issueNumber"
                                                               value="{{$trip->driver->issueNumber}}"
                                                               class="form-control"
                                                               style="font-size: 16px">
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- اسم المستخدم --}}
                                            @if($trip->user)
                                                <div class="col-lg-2 col-md-3 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="product-key"
                                                               class="control-label">@lang('home.user_name')</label>
                                                        <input type="text" id="" class="form-control"
                                                               value="{{app()->getLocale() == 'ar'
                                                       ? $trip->user->user_name_ar
                                                       : $trip->user->user_name_en}}" readonly>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- حالة الرحله --}}
                                            <div hidden class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="phone" style="font-size: 16px"
                                                           class="control-label">@lang('home.status')</label>
                                                    <input type="text" style="font-size: 16px"
                                                           value="{{app()->getLocale()=='ar'
                                                ? $trip->truck->status->system_code_name_ar
                                                : $trip->truck->status->system_code_name_en}}"
                                                           class="form-control" readonly>
                                                </div>
                                            </div>

                                            {{-- خط السير --}}
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.trip_line')</label>
                                                    @if(($trip->status->system_code == 39001 || $trip->status->system_code == 39002) && $edit_flag==1)
                                                        <select class="form-control" name="trip_line_hd_id"
                                                                v-model="trip_line_hd_id" @change="getTripline()">
                                                            @foreach($trip_lines as $trip_line)
                                                                <option value="{{$trip_line->trip_line_hd_id}}">
                                                                    {{$trip_line->trip_line_desc}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <input type="text" id="" class="form-control"
                                                               style="font-size: 16px"
                                                               value="{{$trip->tripLine->trip_line_code. ' => ' .$trip->tripLine->trip_line_desc}}"
                                                               readonly>
                                                    @endif
                                                </div>
                                            </div>

                                            {{--نوع خط السير --}}
                                            <div class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('trucks.trip_line_type')</label>
                                                    <input type="text" id="" class="form-control"
                                                           style="font-size: 16px"
                                                           value="{{app()->getLocale()=='ar' ? $trip->tripLine->tripLineTypeT->system_code_name_ar
                                                        : $trip->tripLine->tripLineTypeT->system_code_name_en}}"
                                                           readonly>
                                                </div>
                                            </div>

                                            {{-- المسافة  --}}
                                            <div class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.distance')</label>
                                                    <input type="text" name="trip_hd_distance" class="form-control"
                                                           style="font-size: 16px"
                                                           value="{{$trip->trip_hd_distance}}"
                                                           readonly>
                                                </div>
                                            </div>

                                            {{--الوقت --}}
                                            <div class="col-lg-2 col-md-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                           class="control-label">@lang('home.time')</label>
                                                    <input type="text" id="" class="form-control"
                                                           style="font-size: 16px"
                                                           value="{{$trip->tripLine->trip_line_time}}" readonly>
                                                </div>
                                            </div>

                                            {{-- تاريخ الانطلاق --}}
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.lunch_date')</label>
                                                    <input type="text" id=""
                                                           value="{{date('d-m-y H:I', strtotime($trip->trip_hd_start_date))}}"
                                                           name="trip_hd_start_date" class="form-control" readonly>
                                                </div>
                                            </div>

                                            {{-- تاريخ الوصول --}}
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.arrival_date')</label>
                                                    <input type="text" id="" name="trip_hd_end_date"
                                                           class="form-control"
                                                           value="{{date('d-m-y H:I', strtotime($trip->trip_hd_end_date))}}"
                                                           readonly>
                                                </div>
                                            </div>


                                            {{-- عداد الانطلاق --}}
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.lunch_counter')</label>
                                                    <input type="number" id="" name="truck_meter_start"
                                                           value="{{$trip->truck_meter_start}}"
                                                           class="form-control" readonly>
                                                </div>
                                            </div>

                                            {{-- عداد الوصور --}}
                                            <div class="col-lg-2 col-md-5 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.arrival_counter')</label>
                                                    <input type="number" id="" class="form-control"
                                                           name="truck_meter_end"
                                                           value="{{$trip->truck_meter_end}}"
                                                           readonly>
                                                </div>
                                            </div>

                                            {{-- المصروف --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.cost_fees')</label>
                                                    {{--<small style="font-size: 15px">{{$trip->trip_hd_fees_1}}</small>--}}
                                                    @if(($trip->status->system_code == 39001 || $trip->status->system_code == 39002) && $edit_flag == 1)
                                                        <input type="text" id="" class="form-control"
                                                               name="trip_hd_fees_1"
                                                               style="font-size: 16px" v-model="trip_hd_fees_1">
                                                    @else
                                                        <input type="text" id="" class="form-control"
                                                               name="trip_hd_fees_1"
                                                               style="font-size: 16px" v-model="trip_hd_fees_1"
                                                               @if($trip->tripLine->tripLineTypeT->system_code != 126004 &&
                                                               $trip->tripLine->tripLineTypeT->system_code != 126005
                                                               && $trip->tripLine->tripLineTypeT->system_code != 126006) readonly
                                                                @endif>
                                                    @endif

                                                </div>
                                            </div>

                                            {{-- مكافأة الطريق --}}
                                            <div class="col-lg-2 col-md-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="product-key" style="font-size: 16px"
                                                           class="control-label">@lang('home.road_reward')</label>
                                                    {{--<small style="font-size: 15px">{{$trip->trip_hd_fees_2}}</small>--}}
                                                    @if($trip->tripLine->tripLineTypeT->system_code == 126004 || $trip->tripLine->tripLineTypeT->system_code == 126005
                                                    ||$trip->tripLine->tripLineTypeT->system_code == 126006)
                                                        <input type="number" style="font-size: 16px"
                                                               v-model="trip_hd_fees_2" name="trip_hd_fees_2"
                                                               class="form-control">
                                                        {{--<small>trip_hd_fees_2</small>--}}
                                                    @else
                                                        @if(($trip->status->system_code == 39001 || $trip->status->system_code == 39002) && $edit_flag == 1)
                                                            <input type="number" style="font-size: 16px"
                                                                   v-model="trip_hd_fees_2" name="trip_hd_fees_2"
                                                                   class="form-control">

                                                        @else
                                                            <input type="number" style="font-size: 16px"
                                                                   v-model="trip_hd_fees_2" name="trip_hd_fees_2"
                                                                   class="form-control" @if($trip->tripLine->tripLineTypeT->system_code != 126004 && $trip->tripLine->tripLineTypeT->system_code != 126005
                                                                   && $trip->tripLine->tripLineTypeT->system_code != 126006) readonly @endif>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>


                                            @if(isset($note))
                                                <div class="col-lg-4 col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="product-key"
                                                               style="font-size: 16px"
                                                               class="control-label">@lang('home.notes')</label>
                                                        <input type="hidden" name="notes_id"
                                                               value="{{$note->notes_id}}">
                                                        <textarea class="form-control"
                                                                  name="notes_data">{{$note->notes_data }}</textarea>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>

                                        <div class="section-body py-4">
                                            <div class="container-fluid">
                                                <div class="row clearfix">
                                                    <input type="hidden" value="{{$trip->trip_hd_id}}"
                                                           name="trip_hd_id">
                                                    {{--من فرع --}}
                                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="product-key"
                                                                   style="font-size: 16px"
                                                                   class="control-label">@lang('home.from')</label>
                                                            <select class="form-control" data-live-search="true"
                                                                    v-model="loc_from"
                                                                    name="trip_dt_loc_from" @change="getWaybillHd()"
                                                                    required
                                                                    @if($trip->status->system_code == 39004)
                                                                    disabled @endif>
                                                                <option value=""></option>
                                                                @foreach($tripe_lineDls as $tripe_lineDl)
                                                                    <option value="{{$tripe_lineDl->system_code_id}}">
                                                                        @if(app()->getLocale() == 'ar')
                                                                            {{$tripe_lineDl->system_code_name_ar}}
                                                                        @else
                                                                            {{$tripe_lineDl->system_code_name_en}}
                                                                        @endif

                                                                    </option>

                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{--إلى فرع --}}
                                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="product-key"
                                                                   style="font-size: 16px"
                                                                   class="control-label">@lang('home.to')</label>
                                                            <select class="form-control"
                                                                    data-live-search="true"
                                                                    name="trip_dt_loc_to" required
                                                                    v-model="loc_to"
                                                                    @if($trip->status->system_code == 39004)
                                                                    disabled @endif>
                                                                <option value=""></option>
                                                                @foreach($tripe_lineDls as $tripe_lineDl)
                                                                    <option value="{{$tripe_lineDl->system_code_id}}">
                                                                        @if(app()->getLocale() == 'ar')
                                                                            {{$tripe_lineDl->system_code_name_ar}}
                                                                        @else
                                                                            {{$tripe_lineDl->system_code_name_en}}
                                                                        @endif

                                                                    </option>

                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="product-key"
                                                               class="control-label">
                                                            <small style="font-size: 16px ;color: blue"> @lang('home.count')
                                                                :
                                                                {{\App\Models\TripDt::where('trip_hd_id',$trip->trip_hd_id)
                                                                ->latest()->first() ? \App\Models\TripDt::where('trip_hd_id',$trip->trip_hd_id)
                                                                ->latest()->first()->trip_dt_serial : 0}}</small>
                                                        </label>
                                                        <input type="number" name="trip_dt_serial"
                                                               style="font-size: 16px"
                                                               class="form-control" v-model="trip_dt_serial"
                                                               placeholder="رقم البيان للتعديل"
                                                               @keyup="getTripDetails()">
                                                    </div>

                                                    @if($trip->status->system_code != 39004)
                                                        <div class="col-md-1 mt-4">
                                                            <button type="button" class="btn btn-primary"
                                                                    @click="removeOldData()">@lang('home.add')
                                                            </button>
                                                        </div>
                                                    @endif

                                                    <div class="col-md-12">
                                                        <div class="card">

                                                            <div class="card-header">
                                                                <h3 class="card-title">@lang('home.data')</h3>

                                                                <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#exampleModalNew"
                                                                        data-whatever="@mdo"
                                                                        v-if="new_data">@lang('home.add_cars')</button>

                                                                <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#exampleModalOld"
                                                                        data-whatever="@mdo"
                                                                        v-if="!new_data">@lang('home.add_cars')</button>
                                                            </div>

                                                            <div class="alert alert-danger"
                                                                 v-if="trips_count_error_message">@{{
                                                                trips_count_error_message }}
                                                            </div>

                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table text-nowrap mb-0">
                                                                        <thead>
                                                                        <tr>
                                                                            <th style="font-size: 16px ;font-weight: bold">@lang('home.waybill_number')</th>
                                                                            <th style="font-size: 16px ;font-weight: bold">@lang('home.plate_number')</th>
                                                                            <th style="font-size: 16px ;font-weight: bold">@lang('home.car_type')</th>
                                                                            <th style="font-size: 16px ;font-weight: bold">@lang('home.customer')</th>
                                                                            <th style="font-size: 16px ;font-weight: bold">@lang('home.owner')</th>
                                                                            <th style="font-size: 16px ;font-weight: bold">@lang('home.to')</th>

                                                                            <th style="font-size: 16px ;font-weight: bold">@lang('home.value')</th>
                                                                            <th style="font-size: 16px ;font-weight: bold">{{__('fees')}}</th>
                                                                            <th></th>

                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        <tr v-for="old_trip,index in old_trips"
                                                                            v-if="!new_data">
                                                                            <input type="hidden" name="trip_dt_id[]"
                                                                                   :value="old_trips[index]['trip_dt_id']">
                                                                            <td>
                                                                                <select class="form-control" disabled=""
                                                                                        style="font-size: 16px ;color: blue"
                                                                                        @if($trip->status->system_code == 39004)
                                                                                        disabled @endif
                                                                                        v-model="old_trips[index]['waybill_id']"
                                                                                        @change="getWaybillData(index)">
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    <option v-for="way_bill in waybills"
                                                                                            :value="way_bill.waybill_id">
                                                                                        @{{way_bill.waybill_code }}

                                                                                    </option>
                                                                                </select>
                                                                                <input type="hidden"
                                                                                       name="waybill_id_old[]"
                                                                                       v-model="old_trips[index]['waybill_id']">
                                                                            </td>

                                                                            <td style="font-size: 16px">
                                                                                @{{old_trips[index].waybill_car_plate}}
                                                                            </td>
                                                                            <td style="color: red">

                                                                                @{{old_trips[index]['waybill_car_desc']}}

                                                                            </td>
                                                                            <td>
                                                                                @{{old_trips[index].customer_name_full_ar}}
                                                                            </td>
                                                                            <td>
                                                                                @{{old_trips[index].waybill_car_owner}}
                                                                            </td>
                                                                            <td style="color: blue">
                                                                                @{{old_trips[index].loc_to_name}}
                                                                            </td>

                                                                            <td>
                                                                                @{{old_trips[index].waybill_total_amount}}
                                                                            </td>

                                                                            <td>
                                                                                @{{old_trips[index].waybill_fees_total}}
                                                                            </td>

                                                                            <td>
                                                                                @if($trip->status->system_code != 39004)
                                                                                    <button type="button"
                                                                                            class="btn btn-primary btn-sm"
                                                                                            @click="deleteOldRow(index)">
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </button>

                                                                                    <button type="button"
                                                                                            class="btn btn-primary btn-sm"
                                                                                            @click="addOldRow()">
                                                                                        <i class="fa fa-plus"></i>
                                                                                    </button>
                                                                                @endif
                                                                            </td>
                                                                        </tr>

                                                                        <tr v-for="trip,index in trips"
                                                                            v-if="new_data">

                                                                            <input type="hidden" name="waybill_id[]"
                                                                                   :value="trips[index]['waybill_id']">
                                                                            <td>
                                                                                <select class="form-control" disabled=""
                                                                                        v-model="trips[index]['waybill_id']"
                                                                                        @change="getWaybillDataNew(index)">
                                                                                    <option value="">@lang('home.choose')</option>
                                                                                    <option v-for="way_bill in way_bills"
                                                                                            :value="way_bill.waybill_id">
                                                                                        @{{way_bill.waybill_code}}
                                                                                    </option>
                                                                                </select>

                                                                            </td>
                                                                            <td>
                                                                                @{{trips[index].waybill_car_plate}}
                                                                            </td>

                                                                            <td>
                                                                                @{{trips[index].waybill_car_desc}}
                                                                            </td>
                                                                            <td>
                                                                                @if(app()->getLocale() == 'ar')
                                                                                    @{{trips[index].customer_name_full_ar}}
                                                                                @else
                                                                                    @{{trips[index].customer_name_full_en}}
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @{{trips[index].waybill_car_owner}}
                                                                            </td>
                                                                            <td>
                                                                                <template
                                                                                        v-if="trips[index]['loc_to_name']">
                                                                                    @{{trips[index].loc_to_name}}
                                                                                </template>
                                                                            </td>
                                                                            <td>
                                                                                {{--<template--}}
                                                                                {{--v-if="trips[index]['payment']">--}}
                                                                                {{--@if(app()->getLocale() == 'ar')--}}
                                                                                {{--@{{trips[index]['payment'].system_code_name_ar}}--}}
                                                                                {{--@else--}}
                                                                                {{--@{{trips[index]['payment'].system_code_name_en}}--}}
                                                                                {{--@endif--}}
                                                                                {{--</template>--}}

                                                                            </td>
                                                                            <td>
                                                                                @{{trips[index].waybill_total_amount}}
                                                                            </td>

                                                                            <td>
                                                                                @{{trips[index].waybill_fees_total}}
                                                                            </td>

                                                                            <td>
                                                                                <button type="button"
                                                                                        @click="addRow()"
                                                                                        class="btn btn-circle btn-icon-only red-flamingo">
                                                                                    <i class="fa fa-plus"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                        @click="removeRow(index)"
                                                                                        v-if="index>0"
                                                                                        class="btn btn-circle btn-icon-only yellow-gold">
                                                                                    <i class="fa fa-minus"></i>
                                                                                </button>
                                                                            </td>

                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            @if($trip->status->system_code != 39004)
                                                                <button type="submit" id="submit"
                                                                        class="btn btn-primary">@lang('home.save')</button>

                                                                <div class="spinner-border" role="status" style="display: none">
                                                                    <span class="sr-only">Loading...</span>
                                                                </div>
                                                            @endif

                                                            <a href="{{config('app.telerik_server')}}?rpt={{$trip->report_url_trip->report_url}}&trip_id={{$trip->trip_hd_id}}&lang=ar&skinName=bootstrap"
                                                               class="btn btn-primary"
                                                               style="display: inline-block; !important;" id="print"
                                                               target="_blank">
                                                                @lang('home.print')</a>
                                                            <a class="btn btn-primary" href="{{route('Trips' )}}"
                                                               id="back">
                                                                @lang('home.back')</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </form>


                                    <div id="exampleModalNew" class="modal fade full" tabindex="-1" role="dialog"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content" style="width:250%">
                                                <div class="modal-header" style="text-align:right">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="text-align:right">
                                                    <div class="card">

                                                        <div class="card-header">
                                                            <h3 class="card-title">اختيار بوالص الشحن للرحلة
                                                                <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#exampleModal"
                                                                        data-whatever="@mdo">@lang('home.add_cars')</button>
                                                            </h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label style="font-size: 16px">@lang('home.waybill_code') </label>
                                                                    <input type="text" class="form-control"
                                                                           v-model="waybill_code"
                                                                           style="font-size: 16px ;font-weight: bold">
                                                                    <small style="color: green">يمكن البحث بكتابه رقم
                                                                        البوليصه
                                                                    </small>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label style="font-size: 16px ;font-weight: bold">@lang('trucks.car_loc_from')</label>
                                                                    <input type="text" class="form-control"
                                                                           style="font-size: 16px ;font-weight: bold"
                                                                           v-model="waybill_loc_from">
                                                                    <small style="color: green">يمكن البحث بكتابه اسم
                                                                        الفرع
                                                                    </small>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label style="font-size: 16px ;font-weight: bold">@lang('trucks.car_loc_to')</label>
                                                                    <input type="text" class="form-control"
                                                                           style="font-size: 16px"
                                                                           v-model="waybill_loc_to">
                                                                </div>
                                                            </div>

                                                            <div class="alert alert-danger"
                                                                 v-if="trips_count_error_message">@{{
                                                                trips_count_error_message }}
                                                            </div>

                                                            <div class="table-responsive">
                                                                <table class="table text-nowrap mb-0">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>
                                                                            {{--<input type="checkbox" id="select_all"--}}
                                                                            {{--@click="addAllRows()"--}}
                                                                            {{--v-model="select_all">--}}
                                                                        </th>
                                                                        <th style="font-size: 16px">@lang('home.waybill_number')</th>
                                                                        <th style="font-size: 16px">@lang('home.plate_number')</th>

                                                                        <th style="font-size: 16px">@lang('home.from')</th>
                                                                        <th style="font-size: 16px">@lang('home.to')</th>
                                                                        <th style="font-size: 16px">@lang('home.transit')</th>
                                                                        <th style="font-size: 16px">@lang('home.car_type')</th>
                                                                        <th style="font-size: 16px">@lang('home.customer')</th>
                                                                        <th style="font-size: 16px">@lang('home.owner')</th>
                                                                        <th style="font-size: 16px">@lang('home.value')</th>
                                                                        <th style="font-size: 16px">{{__('fees')}}</th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>


                                                                    <tr v-for="waybill,index in filteredWaybills3">
                                                                        {{--<input type="hidden" name="waybill_id[]"--}}
                                                                        {{--:value="waybill.waybill_id">--}}
                                                                        <td>
                                                                            <input type="checkbox"
                                                                                   v-model="waybill_id"
                                                                                   @click="addRowFomModal(waybill,$event,waybill.waybill_id)"
                                                                                   :value="waybill.waybill_id">
                                                                        </td>
                                                                        <td style="font-size: 16px ;color: blue">
                                                                            @{{
                                                                            waybill.waybill_code }}
                                                                        </td>
                                                                        <td style="font-size: 14px">
                                                                            @{{
                                                                            waybill.waybill_car_plate }}
                                                                        </td>
                                                                        <td style="color: blue">
                                                                            @{{ waybill.loc_from_name }}
                                                                        </td>

                                                                        <td style="color: blue">
                                                                            @{{ waybill.loc_to_name }}
                                                                        </td>

                                                                        <td>
                                                                            @{{ waybill.loc_transit_name }}
                                                                        </td>
                                                                        <td style="color: red">@{{
                                                                            waybill.waybill_car_desc }}
                                                                        </td>

                                                                        <td>
                                                                            @if(app()->getLocale() == 'ar')
                                                                                @{{waybill.customer_name_full_ar}}
                                                                            @else
                                                                                @{{waybill.customer_name_full_en}}
                                                                            @endif
                                                                        </td>
                                                                        <td>@{{ waybill.waybill_car_owner }}</td>


                                                                        <td>
                                                                            @{{ waybill.waybill_total_amount }}
                                                                        </td>
                                                                        <td>
                                                                            @{{ waybill.waybill_fees_total }}
                                                                        </td>

                                                                        <!-- Modal content-->
                                                                        <div class="modal-content" id="show_model"
                                                                             hidden=true>
                                                                            <div class="modal-header"
                                                                                 style="background-color: firebrick">
                                                                                <button type="button" class="close"
                                                                                        data-dismiss="modal">
                                                                                    &times;
                                                                                </button>
                                                                                <h3 class="modal-title" align="center"
                                                                                    style="color: whitesmoke;"><i
                                                                                            class="fa fa-warning"
                                                                                            style="color: yellow;"></i> @lang('home.confirm_update')
                                                                                </h3>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <b align="center">@lang('home.confirm')</b>

                                                                                <p>فرع الوصول للرحله يختلف عن فرع وصول
                                                                                    بوليصه الشحن هل انت
                                                                                    متاكد من الاضافه</p>

                                                                                <button type="button"
                                                                                        @click="confirmUpdate()"
                                                                                        class="btn btn-danger yes">@lang('home.yes')</button>
                                                                                <button type="button"
                                                                                        class="btn btn-default"
                                                                                        @click="unconfirmUpdate()">@lang('home.no')</button>

                                                                            </div>
                                                                        </div>


                                                                    </tr>


                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade bd-example-modal-lg" id="exampleModalOld" tabindex="-1"
                                         role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h3 class="card-title">@lang('home.data')
                                                                <button type="button" class="btn btn-primary"
                                                                        data-toggle="modal"
                                                                        data-target="#exampleModal"
                                                                        data-whatever="@mdo">@lang('home.add_cars')</button>
                                                            </h3>
                                                        </div>
                                                        <div class="alert alert-danger"
                                                             v-if="trips_count_error_message">@{{
                                                            trips_count_error_message }}
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table text-nowrap mb-0">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>@lang('home.waybill_number')</th>
                                                                        <th>@lang('home.plate_number')</th>
                                                                        <th>@lang('home.car_type')</th>
                                                                        <th>@lang('home.customer')</th>
                                                                        <th>@lang('home.owner')</th>
                                                                        <th>@lang('home.from')</th>
                                                                        <th>@lang('home.to')</th>
                                                                        <th>@lang('home.loc_transit')</th>
                                                                        <th>@lang('home.payment_method')</th>
                                                                        <th>@lang('home.value')</th>
                                                                        <th>{{__('fees')}}</th>
                                                                        <th></th>

                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    <tr v-for="old_trip,index in old_trips"
                                                                        v-if="!new_data">
                                                                        <input type="hidden" name="trip_dt_id[]"
                                                                               :value="old_trips[index]['trip_dt_id']">
                                                                        <td>
                                                                            <select class="form-control"
                                                                                    @if($trip->status->system_code == 39004)
                                                                                    disabled @endif
                                                                                    v-model="old_trips[index]['waybill_id']"
                                                                                    @change="getWaybillData(index)">
                                                                                <option value="">@lang('home.choose')</option>
                                                                                <option v-for="way_bill in waybills"
                                                                                        :value="way_bill.waybill_id">
                                                                                    @{{way_bill.waybill_code }}
                                                                                    -
                                                                                </option>
                                                                            </select>
                                                                            <input type="hidden" name="waybill_id_old[]"
                                                                                   v-model="old_trips[index]['waybill_id']">
                                                                        </td>
                                                                        <td>
                                                                            @{{old_trips[index].waybill_car_plate}}
                                                                        </td>
                                                                        <td>

                                                                            @{{old_trips[index]['waybill_car_desc']}}

                                                                        </td>
                                                                        <td>
                                                                            @if(app()->getLocale() == 'ar')
                                                                                @{{old_trips[index].customer_name_full_ar}}
                                                                            @else
                                                                                @{{old_trips[index][.customer_name_full_en}}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @{{old_trips[index].waybill_car_owner}}
                                                                        </td>
                                                                        <td>
                                                                            @{{old_trips[index].loc_from_name}}
                                                                        </td>

                                                                        <td>
                                                                            @{{old_trips[index].loc_to_name}}
                                                                        </td>
                                                                        <td>
                                                                            @{{old_trips[index].loc_transit_name}}
                                                                        </td>
                                                                        <td>
                                                                            <template
                                                                                    v-if="old_trips[index]['payment_method_name_ar']">
                                                                                @{{old_trips[index].payment_method_name_ar}}

                                                                            </template>

                                                                        </td>

                                                                        <td>
                                                                            @{{old_trips[index].waybill_total_amount}}
                                                                        </td>

                                                                        <td>
                                                                            @{{old_trips[index].waybill_fees_total}}
                                                                        </td>

                                                                        <td>
                                                                            @if($trip->status->system_code != 39004)
                                                                                <button type="button"
                                                                                        class="btn btn-primary btn-sm"
                                                                                        @click="deleteOldRow(index)"
                                                                                        v-if="old_trips[index]['branch_id'] == this.branch_id">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>

                                                                                <button type="button"
                                                                                        class="btn btn-primary btn-sm"
                                                                                        @click="addOldRow()">
                                                                                    <i class="fa fa-plus"></i>
                                                                                </button>
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">

                    <div class="card-title">
                        <a href="{{ url('bonds-add/cash/create?trip_id='.$trip->trip_hd_id) }}"
                           class="btn btn-primary text-white">@lang('home.add_bond')</a>
                    </div>

                    <div class="card-body">
                        <div class="row card">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.bonds_number')</th>
                                        <th>@lang('home.bonds_date')</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.branch')</th>
                                        <th>@lang('home.bonds_account')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th></th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bonds_cash as $bond)
                                        <tr>
                                            <td>{{ $bond->bond_code }}</td>
                                            <td>{{ $bond->created_date }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
$bond->company->company_name_ar :
$bond->company->company_name_en }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
$bond->branch->branch_name_ar :
$bond->branch->branch_name_en }}</td>
                                            <td>{{ $bond->bond_acc_id }}</td>

                                            <td>
                                                {{--{{ app()->getLocale() == 'ar' ? \App\Models\SystemCode::where('company_group_id', $bond->company_group_id)->where('system_code',$bond->bond_method_type)--}}
                                                {{--->first()->system_code_name_ar :--}}
                                                {{--\App\Models\SystemCode::where('company_group_id', $bond->company_group_id)->where('system_code',$bond->bond_method_type)--}}
                                                {{--->first()->system_code_name_en }}--}}
                                            </td>

                                            <td>{{ $bond->bond_amount_credit }}</td>
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment_trip->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-print"></i></a>

                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment_trip_all->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-print"></i></a>

                                                <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-eye"></i></a>
                                            </td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
$bond->userCreated->user_name_en }}</td>
                                            <td>
                                                @if($bond->journalCash)
                                                    <a href="{{ route('journal-entries.show',$bond->journalCash->journal_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        @lang('home.journal_details')
                                                        {{$bond->journalCash->journal_hd_code}}
                                                    </a>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- notes part --}}
                <div class="tab-pane fade" id="notes-grid" role="tabpanel">

                    <div class="row">
                        <div class="col-lg-12">
                            <x-files.form-notes>
                                <input type="hidden" name="transaction_id"
                                       value="{{ $trip->trip_hd_id }}">
                                <input type="hidden" name="app_menu_id" value="104">
                            </x-files.form-notes>

                            <x-files.notes>
                                @foreach($notes as $note)
                                    <tr>
                                        <td>
                                            <div class="badge text-gray text-wrap" style="width: 400px;">
                                                <input type="text" class="form-control" name="note_data"
                                                       value=" {{ $note->notes_data }}">

                                            </div>
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
                {{--end notes part--}}

                {{-- cars part --}}
                <div class="tab-pane fade" id="cars-grid" role="tabpanel">

                    <div class="card-title">
                        <h6> بيانات السيارات المدرجه بالرحله</h6>
                    </div>

                    <div class="card-body">
                        <div class="row card">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th style="font-size: 16px">@lang('home.waybill_number')</th>

                                        <th style="font-size: 16px">@lang('home.trip_start_date')</th>
                                        <th style="font-size: 16px">@lang('home.plate_number')</th>
                                        <th style="font-size: 16px">@lang('home.car_type')</th>
                                        <th style="font-size: 16px">@lang('home.customer')</th>
                                        <th style="font-size: 16px">@lang('home.owner')</th>
                                        <th style="font-size: 16px">@lang('home.from')</th>
                                        <th style="font-size: 16px">@lang('home.to')</th>
                                        <th style="font-size: 16px">@lang('home.loc_transit')</th>
                                        <th style="font-size: 16px">@lang('home.payment_method')</th>
                                        <th style="font-size: 16px">@lang('home.id_naql')</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($trip_car_dt as $trip_car_dts)
                                        <tr>
                                            <td>{{ $trip_car_dts->trip_dt_serial }}</td>
                                            <td>
                                                <a href="{{ route('Waybill.edit_car',$trip_car_dts->waybill->waybill_id) }}"
                                                   class="btn btn-link btn-sm"
                                                   target="_blank">
                                                    {{ $trip_car_dts->waybill->waybill_code }}
                                                </a>
                                            </td>
                                            <td>{{ date('d-m-y', strtotime($trip_car_dts->waybill->waybill_load_date)) }}</td>
                                            <td>{{ $trip_car_dts->waybill->details->waybill_car_plate }}</td>
                                            <td>{{ $trip_car_dts->waybill->details->waybill_car_desc }}</td>
                                            <td>{{ $trip_car_dts->waybill->customer->customer_name_full_ar }}</td>
                                            <td>{{ $trip_car_dts->waybill->details->waybill_car_owner }}</td>
                                            <td>{{ $trip_car_dts->waybill->locfrom->system_code_name_ar }}</td>
                                            <td>{{ $trip_car_dts->waybill->locTo->system_code_name_ar }}</td>
                                            <td>{{ $trip_car_dts->waybill->LocTransit->system_code_name_ar }}</td>
                                            <td>{{ $trip_car_dts->waybill->paymentmethod->system_code_name_ar }}</td>
                                            <td>{{ $trip_car_dts->waybill->waybillId }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{--end cars  part--}}


                {{--JOURNAL PART--}}
                <div class="tab-pane fade" id="entitlement-journals-grid" role="tabpanel">

                    <div class="card-title">
                        <h6> {{__('Entitlement Journals')}}</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table_e2">
                            <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th> @lang('home.restriction_number')  </th>
                                    <th> @lang('home.date')  </th>


                                    <th>@lang('home.branch') </th>
                                    <th>@lang('home.daily_accounts_type') </th>
                                    <th style="width:150px">@lang('home.notes') </th>

                                    <th>@lang('home.debit') </th>
                                    <th>@lang('home.credit') </th>
                                    <th>@lang('home.user') </th>
                                    <th>@lang('home.restriction_account_status') </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($entitlement_journals as $journal)
                                    <tr>
                                        <td>
                                            <a href="{{ route('journal-entries.show',$journal->journal_hd_id) }}"
                                               class="btn btn-primary btn-sm">
                                                {{ $journal->journal_hd_code }}
                                            </a>
                                        </td>
                                        <td>{{$journal->journal_hd_date}}</td>

                                        <td>{{  app()->getLocale()=='ar' ? $journal->branch->branch_name_ar :
$journal->branch->branch_name_en}}</td>
                                        <td>{{ app()->getLocale()=='ar' ? $journal->journalType->system_code_name_ar :
$journal->journalType->system_code_name_en }}</td>
                                        <td>{{$journal->journal_hd_notes}}</td>

                                        <td>{{$journal->journal_hd_debit }}</td>
                                        <td>{{$journal->journal_hd_credit }}</td>
                                        <td>
                                            {{ app()->getLocale()=='ar' ? $journal->user->user_name_ar :
                                            $journal->user->user_name_en }}
                                        </td>
                                        <td>
<span class="tag tag-success">
{{ app()->getLocale()=='ar' ? $journal->journalStatus->system_code_name_ar :
$journal->journalStatus->system_code_name_en }}
</span>
                                        </td>
                                        <td>

                                            @if(auth()->user()->user_type_id != 1)
                                                @foreach(session('job')->permissions as $job_permission)
                                                    @if($job_permission->app_menu_id == 33 && $job_permission->permission_update)
                                                        <a href="{{ route('journal-entries.edit',$journal->journal_hd_id) }}"
                                                           class="btn btn-primary btn-sm" title="Edit">
                                                            <i class="fa fa-edit"></i></a>
                                                    @endif
                                                @endforeach
                                            @else
                                                <a href="{{ route('journal-entries.edit',$journal->journal_hd_id) }}"
                                                   class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i></a>
                                            @endif


                                            <a href="{{route('journal-entries.show',$journal->journal_hd_id)}}"
                                               class="btn btn-danger btn-sm" title="show">
                                                <i class="fa fa-eye"></i></a>
                                            <a href="{{config('app.telerik_server')}}?rpt={{$journal->report_url_journal->report_url}}&id={{$journal->journal_hd_id}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-primary btn-sm" title="Print"
                                               target="_blank">
                                                <i class="fa fa-print"></i></a>
                                            </a>

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

    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {

            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            $('#add_note').click(function () {
                var display = $("#add_note_form").css("display");
                if (display == 'none') {
                    $('#add_note_form').css('display', 'block')
                } else {
                    $('#add_note_form').css('display', 'none')
                }
            });

            $('#select_all').click(function () {
                console.log("bb")
                if ($('#select_all').prop('checked') == true) {
                    $('input:checkbox').prop('checked', true);
                } else {
                    $('input:checkbox').prop('checked', false);
                }
            });

        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                trip_hd_id:{!! $trip->trip_hd_id !!},
                trips: [],
                trip_dt_serial: 1,
                old_trips: [],
                loc_from: '',
                loc_to: '',
                way_bills: [],
                new_data: false,
                select_all: false,
                waybill_id: [],
                waybill_loc_from: '',
                waybill_loc_to: '',
                waybill_code: '',
                trips_count_error_message: '',
                branch_id: '{{session('branch')['branch_id']}}',
                isLoaded: false,
                fees_add_old: 0,
                trip_line_hd_id: '{{$trip->trip_line_hd_id}}',
                trip_line_time: '{{$trip->trip_hd_time}}',
                trip_line_distance: '{{$trip->trip_hd_distance}}',
                trip_hd_fees_1: '{{$trip->trip_hd_fees_1}}',
                trip_hd_fees_2: '{{$trip->trip_hd_fees_2}}',
                // trip_hd_fees_20: 0,
                trip_line: {}
            },
            mounted() {
// this.getTripDetails()
            },
            methods:
                {
                    getTripline() {
                        this.trip_line = {}
                        $.ajax({
                            type: 'GET',
                            data: {trip_line_hd_id: this.trip_line_hd_id},
                            url: '{{ route("api.Trips.getTripLine") }}'
                        }).then(response => {
                            this.trip_line = response.data
                            // this.trip_hd_fees_1 = this.trip_line.trip_line_fess_1
                            // this.trip_hd_fees_2 = this.trip_line.trip_line_fees_2
                        })

                    },
                    removeOldData() {
                        this.loc_from = ''
                        this.loc_to = ''
                        this.trip_dt_serial = ''
                        this.new_data = !this.new_data
                    },
                    getTripDetails() {
                        this.new_data = false
                        this.isLoaded = false
                        $.ajax({
                            type: 'GET',
                            data: {trip_hd_id: this.trip_hd_id, trip_dt_serial: this.trip_dt_serial},
                            url: '{{ route("api.Trips.getTripDts") }}'
                        }).then(response => {
                            if (response.status == 200) {
                                this.isLoaded = true
                                this.old_trips = response.data
                                this.waybills = response.waybills
                                this.loc_from = response.trip_loc_from
                                this.loc_to = response.trip_loc_to
                            } else {
                                this.isLoaded = false
                            }

                        })
                    }
                    ,
                    deleteOldRow(index) {
                        if (this.old_trips[index]['trip_dt_id']) {
                            $.ajax({
                                type: 'DELETE',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    trip_dt_id: this.old_trips[index]['trip_dt_id'],
                                },
                                url: '{{ route('api.Trips.deleteTripDt') }}'
                            }).then(response => {
// console.log(response)
                                this.trip_hd_fees_1 = parseFloat(this.trip_hd_fees_1) - parseFloat(this.old_trips[index]['waybill_fees_total'])
                                this.old_trips.splice(index, 1);
                            })
                        } else {

                            this.trip_hd_fees_1 = parseFloat(this.trip_hd_fees_1) - parseFloat(this.old_trips[index]['waybill_fees_total'])
                            this.old_trips.splice(index, 1);
                        }


                    },
                    addOldRow() {
                        if (this.old_trips.length < 8) {
                            this.old_trips.push({
                                'trip_dt_id': '',
                                'customer_name_full_ar': "",
                                'loc_from_name': "",
                                'loc_to_id': "",
                                'loc_to_name': "",
                                'loc_transit_name': "",
                                'payment_method_name_ar': "",
                                'waybill_car_desc': "",
                                'waybill_car_owner': "",
                                'waybill_car_plate': "",
                                'waybill_code': "",
                                'waybill_id': "",
                                'waybill_total_amount': '',
                                'waybill_fees_total': 0,
                            });
                        } else {
                            this.trips_count_error_message = 'غير مسموح باضافه اكتر من عدد 8 بوالص في البيان الواحد'
                        }

                    },
                    getWaybillHd() {
                        $.ajax({
                            type: 'GET',
                            data: {loc_from: this.loc_from, trip_id: this.trip_hd_id, loc_to: this.loc_to},
                            url: '{{ route("api.Trips.getWaybillHd") }}'
                        }).then(response => {
                            this.way_bills = response.data
                        })
                    },
                    addRow() {
                        this.trips_count_error_message = ''
                        if (this.trips.length < 8) {
                            this.trips.push({
                                'waybill_id': '',
                                'waybill_code': '',
                                'waybill_car_plate': '',
                                'waybill_car_desc': '',
                                'customer_name_full_ar': '',
                                'waybill_car_owner': '',
                                'loc_to_name': '',
                                'waybill_total_amount': '',
                                'waybill_fees_total': 0,
                            })
                        } else {
                            this.trips_count_error_message = 'غير مسموح باضافه اكتر من عدد 8 بوالص في البيان الواحد'
                        }
                    }
                    ,
                    removeRow(index) {
                        this.trips.splice(index, 1)
                        this.trip_hd_fees_1 = parseFloat(this.trip_hd_fees_1) - parseFloat(this.trips[index]['waybill_fees_total'])
                    }
                    ,
                    getWaybillData(index) {
                        $.ajax({
                            type: 'GET',
                            data: {waybill_id: this.old_trips[index]['waybill_id']},
                            url: '{{ route("api.Trips.getWaybillDataOld") }}'
                        }).then(response => {
                            this.old_trips[index]['customer_name_full_ar'] = response.waybill_car.waybill_code
                            this.old_trips[index]['loc_from_name'] = response.waybill_car.waybill_total_amount
                            this.old_trips[index]['loc_to_id'] = response.waybill_car.loc_to_id
                            this.old_trips[index]['loc_to_name'] = response.waybill_car.loc_to_name
                            this.old_trips[index]['loc_transit_name'] = response.waybill_car.loc_transit_name
                            this.old_trips[index]['payment_method_name_ar'] = response.waybill_car.payment_method_name_ar
                            this.old_trips[index]['waybill_car_desc'] = response.waybill_car.waybill_car_desc
                            this.old_trips[index]['waybill_car_owner'] = response.waybill_car.waybill_car_owner
                            this.old_trips[index]['waybill_car_plate'] = response.waybill_car.waybill_car_plate
                            this.old_trips[index]['waybill_code'] = response.waybill_car.waybill_code
                            this.old_trips[index]['waybill_id'] = response.waybill_car.waybill_id
                            this.old_trips[index]['waybill_total_amount'] = response.waybill_car.waybill_total_amount
                            this.old_trips[index]['waybill_fees_total'] = response.waybill_car.waybill_fees_total ? response.waybill_car.waybill_fees_total : 0

                            this.trip_hd_fees_1 = parseFloat(this.trip_hd_fees_1) + parseFloat(this.old_trips[index]['waybill_fees_total'])
                        })
                    }
                    ,
                    getWaybillDataNew(index) {
                        console.log('i')
                        $.ajax({
                            type: 'GET',
                            data: {waybill_id: this.trips[index]['waybill_id']},
                            url: '{{ route("api.Trips.getWaybillData") }}'
                        }).then(response => {
                            this.trips[index]['waybill_id'] = response.waybill_car.waybill_id
                            this.trips[index]['waybill_code'] = response.waybill_car.waybill_code
                            this.trips[index]['waybill_car_plate'] = response.waybill_car.waybill_car_plate
                            this.trips[index]['waybill_car_desc'] = response.waybill_car.waybill_car_desc
                            this.trips[index]['customer_name_full_ar'] = response.waybill_car.customer_name_full_ar
                            this.trips[index]['waybill_car_owner'] = response.waybill_car.waybill_car_owner
                            this.trips[index]['waybill_total_amount'] = response.waybill_car.waybill_total_amount
                            this.trips[index]['loc_to_name'] = response.waybill_car.loc_to_name
                            this.trips[index]['waybill_fees_total'] = response.waybill_car.waybill_fees_total

                            this.trip_hd_fees_1 = parseFloat(this.trip_hd_fees_1) + parseFloat(this.trips[index]['waybill_fees_total'])
                        })
                    }
                    ,
                    addAllRows() {
                        if (!this.select_all) {
                            var count = this.filteredWaybills3.length
                            for (id = 0; id < count; id++) {
                                this.trips.push({
                                    'waybill_id': this.filteredWaybills3[id]['waybill_id'],
                                    'waybill_code': this.filteredWaybills3[id]['waybill_code'],
                                    'waybill_car_plate': this.filteredWaybills3[id]['waybill_car_plate'],
                                    'waybill_car_desc': this.filteredWaybills3[id]['waybill_car_desc'],
                                    'customer_name_full_ar': this.filteredWaybills3[id]['customer_name_full_ar'],
                                    'waybill_car_owner': this.filteredWaybills3[id]['waybill_car_owner'],
                                    'loc_to_name': this.filteredWaybills3[id]['loc_to_name'],
                                    'waybill_total_amount': this.filteredWaybills3[id]['waybill_total_amount'],
                                    'waybill_fees_total': this.filteredWaybills3[id]['waybill_fees_total'],
// 'payment': this.filteredWaybills3[id]['payment'],
// 'loc_to': this.filteredWaybills3[id]['loc_to']
                                })
                            }
                        } else {
                            this.trips = []
                        }
                    }
                    ,
                    addRowFomModal(waybill, event, id) {

                        if (event.target.checked) {
                            this.trips.push({
                                'waybill_id': waybill.waybill_id,
                                'waybill_code': waybill.waybill_code,
                                'waybill_car_plate': waybill.waybill_car_plate,
                                'waybill_car_desc': waybill.waybill_car_desc,
                                'customer_name_full_ar': waybill.customer_name_full_ar,
                                'waybill_car_owner': waybill.waybill_car_owner,
                                'loc_to_name': waybill.loc_to_name,
                                'waybill_total_amount': waybill.waybill_total_amount,
                                'waybill_fees_total': waybill.waybill_fees_total,
                            })

                            var el = this.trips.indexOf(id)
                            console.log(this.trips)

                            this.trip_hd_fees_1 = parseFloat(this.trip_hd_fees_1) + parseFloat(waybill.waybill_fees_total)

                        } else {
                            this.trip_hd_fees_1 = parseFloat(this.trip_hd_fees_1) - parseFloat(waybill.waybill_fees_total)
                            this.trips.splice(this.trips.indexOf(id), 1)
                        }
                    }
                    ,
                }
            ,
            computed: {
                // trip_hd_fees_2: function () {
                //     let total = 0;
                //     if (this.trips.length > 0) {
                //         Object.entries(this.trips).forEach(([key, val]) => {
                //             total += (parseFloat(val.waybill_fees_total))
                //         });
                //     }
                //
                //     if (this.isLoaded == true) {
                //         Object.entries(this.old_trips).forEach(([key, val]) => {
                //             total += (parseFloat(val.waybill_fees_total))
                //         });
                //     }
                //
                //     return total.toFixed(2);
                // },
                filteredWaybills: function () {
                    return this.way_bills.filter(waybill => {
                        return waybill.loc_from_name.match(this.waybill_loc_from)
                    })
                }
                ,
                filteredWaybills2: function () {
                    return this.filteredWaybills.filter(waybill => {
                        return waybill.loc_to_name.match(this.waybill_loc_to)
                    })
                }
                ,
                filteredWaybills3: function () {
                    return this.filteredWaybills2.filter(waybill => {
                        return waybill.waybill_code.match(this.waybill_code)
                    })
                }
                ,
            }
        })
    </script>
@endsection
