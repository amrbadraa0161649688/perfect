@extends('Layouts.master')

@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
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

                <div class="header-action"></div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Form To Create Customer--}}
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="row clearfix">

                                        {{-- كود الرحلة --}}

                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.trip_number')</label>
                                                <input type="text" class="form-control text-center"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{$trip->trip_hd_code}}"
                                                       readonly>
                                            </div>

                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.trip_status')</label>
                                                <input type="text" class="form-control text-center"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{app()->getLocale() == 'ar' ?
                                                       $trip->status->system_code_name_ar :
                                                       $trip->status->system_code_name_en }}"
                                                       readonly>
                                            </div>
                                        </div>
                                        {{-- تاريخ الانشاء --}}
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="ssn" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.trip_start_date')</label>
                                                <input type="text" id="" name="trip_hd_date" class="form-control"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       readonly value="{{ $trip->trip_hd_date }}">

                                            </div>
                                        </div>
                                        {{-- اسم المستخدم --}}
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key"
                                                       class="control-label">@lang('home.user_name')</label>
                                                <input type="text" id="" class="form-control"
                                                       value="{{app()->getLocale() == 'ar'
                                                       ? auth()->user()->user_name_ar
                                                       : auth()->user()->user_name_en}}" readonly>
                                            </div>
                                        </div>

                                        {{-- كود الشاحنه --}}
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.truck_code')</label>
                                                <input type="text" name="truck_code" class="form-control"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{$trip->truck->truck_code .' => '. $trip->truck->truck_name}}"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{-- رقم الشاحنه --}}
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone-ex" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.truck_plate')</label>
                                                <input type="text" value="{{$trip->truck->truck_plate_no}}"
                                                       class="form-control" style="font-size: 16px ;font-weight: bold"
                                                       readonly>

                                            </div>
                                        </div>

                                        {{--نوع الناقله--}}
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone-ex" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.truck_type')</label>
                                                @if(app()->getLocale()=='ar')
                                                    <input type="text" id=""
                                                           value="{{$trip->truck->truckType->system_code_name_ar}}"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold" readonly>
                                                @else
                                                    <input type="text"
                                                           value="{{$trip->truck->truckType->system_code_name_en}}"
                                                           class="form-control"
                                                           style="font-size: 16px ;font-weight: bold" readonly>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- حالة الرحله --}}
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label for="phone" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.status')</label>
                                                <input type="text" value="{{app()->getLocale()=='ar' 
                                                ? $trip->truck->status->system_code_name_ar
                                                : $trip->truck->status->system_code_name_en}}"
                                                       class="form-control" style="font-size: 16px ;font-weight: bold"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{-- كود السائق --}}
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.driver_code')</label>
                                                <input type="text" name="driver_code"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       class="form-control" value="{{$trip->driver->emp_code}}"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{-- خط السير --}}
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.trip_line')</label>
                                                <input type="text" id="" class="form-control"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{$trip->tripLine->trip_line_code. ' => ' .$trip->tripLine->trip_line_desc}}"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{--{{dd( $trip->tripLine)}}--}}
                                        {{-- اسم السائق--}}
                                        <div class="col-lg-3 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.driver_name')</label>
                                                <input type="text" name="driver_id"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       class="form-control" value="{{
                                                       app()->getLocale()== 'ar'
                                                       ? $trip->driver->emp_name_full_ar
                                                       : $trip->driver->emp_name_full_en}}" readonly>
                                            </div>
                                        </div>

                                        {{-- رقم الجوال --}}
                                        <div class="col-lg-2 col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.phone_number')</label>
                                                <input type="number" value="{{$trip->driver->emp_private_mobile}}"
                                                       name="driver_mobil"
                                                       class="form-control" style="font-size: 16px ;font-weight: bold"
                                                       readonly>
                                            </div>
                                        </div>
                                        {{--رقم النسخه --}}
                                        <div class="col-lg-1 col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label for="issueNumber" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.issue_number')</label>
                                                <input type="number" id="issueNumber" name="issueNumber"
                                                       value="{{$trip->driver->issueNumber}}"
                                                       class="form-control" style="font-size: 16px ;font-weight: bold"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{--نوع خط السير --}}
                                        <div class="col-lg-2 col-md-2 col-sm-12">

                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('trucks.trip_line_type')</label>
                                                <input type="text" id="" class="form-control"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{app()->getLocale()=='ar' ? $trip->tripLine->tripLineTypeT->system_code_name_ar
                                                        : $trip->tripLine->tripLineTypeT->system_code_name_en}}"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{-- المسافة  --}}
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.distance')</label>
                                                <input type="text" name="trip_hd_distance" class="form-control"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{$trip->tripLine->trip_line_distance}}"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{--الوقت --}}
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.time')</label>
                                                <input type="text" id="" class="form-control"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{$trip->tripLine->trip_line_time}}" readonly>
                                            </div>
                                        </div>

                                        {{-- تاريخ الانطلاق --}}
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.lunch_date')</label>
                                                <input type="text" id="" style="font-size: 16px ;font-weight: bold"
                                                       value="{{$trip->trip_hd_start_date}}"
                                                       name="trip_hd_start_date" class="form-control" readonly>
                                            </div>
                                        </div>

                                        {{-- تاريخ الوصول --}}
                                        <div class="col-lg-2 col-md-2 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.arrival_date')</label>
                                                <input type="text" id="" name="trip_hd_end_date" class="form-control"
                                                       value="{{$trip->trip_hd_end_date}}"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{-- عداد الانطلاق --}}
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.lunch_counter')</label>
                                                <input type="number" id="" name="truck_meter_start"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       value="{{$trip->truck_meter_start}}"
                                                       class="form-control" readonly>
                                            </div>
                                        </div>

                                        {{-- عداد الوصور --}}
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.arrival_counter')</label>
                                                <input type="number" id="" class="form-control" name="truck_meter_end"
                                                       value="{{$trip->truck_meter_end}}"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       readonly>
                                            </div>
                                        </div>

                                        {{-- المصروف --}}
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.cost_fees')</label>


                                                {{--خدمي او خلافه--}}
                                                <input type="text" id="" class="form-control" name="trip_hd_fees_1"
                                                       v-model="trip_hd_fees_1"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       @if($trip->tripLine->tripLineTypeT->system_code != 126004 &&
                                                        $trip->tripLine->tripLineTypeT->system_code != 126005
                                                           && $trip->tripLine->tripLineTypeT->system_code != 126006) readonly @endif>
                                            </div>
                                        </div>

                                        {{-- مكافأة الطريق --}}
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.road_reward')</label>
                                                {{--سطحه خاصه--}}
                                                <input type="number"
                                                       name="trip_hd_fees_2" v-model="trip_hd_fees_2"
                                                       v-if="trip_line_type_code == 126004"
                                                       class="form-control" style="font-size: 16px ;font-weight: bold"
                                                       @if($trip->tripLine->tripLineTypeT->system_code != 126004 &&
                                                        $trip->tripLine->tripLineTypeT->system_code != 126005
                                                           && $trip->tripLine->tripLineTypeT->system_code != 126006) readonly @endif>

                                                {{--خدمي او خلافه--}}
                                                <input type="text" id="" class="form-control" name="trip_hd_fees_2"
                                                       value="{{$trip->trip_hd_fees_2}}"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       @if($trip->tripLine->tripLineTypeT->system_code != 126004 &&
                                                        $trip->tripLine->tripLineTypeT->system_code != 126005
                                                           && $trip->tripLine->tripLineTypeT->system_code != 126006) readonly
                                                       @endif v-else>
                                            </div>
                                        </div>


                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- table To add trip details--}}
                <form action="{{ route('Trips.storeDetails') }}" method="post">
                    @csrf
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row clearfix">

                                        {{--سطحه خاصه--}}
                                        <input type="hidden" name="trip_hd_fees_2"
                                               v-model="trip_hd_fees_2" v-if="trip_line_type_code == 126004">

                                        {{--خدمي او خلافه--}}
                                        <input type="hidden" name="trip_hd_fees_2"
                                               value="{{$trip->trip_hd_fees_2}}" v-else>

                                        <input type="hidden" name="trip_hd_fees_1" value="{{$trip->trip_hd_fees_1}}">

                                        <input type="hidden" value="{{$trip->trip_hd_id}}" name="trip_hd_id">
                                        {{--من فرع --}}
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.from')</label>
                                                <select class="selectpicker" data-live-search="true"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        @change="getWaybillHd()" v-model="loc_from"
                                                        name="trip_dt_loc_from" required>
                                                    <option value=""
                                                            style="font-size: 16px ;font-weight: bold"></option>
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
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.to')</label>
                                                <select class="selectpicker" data-live-search="true"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        name="trip_dt_loc_to" v-model="loc_to" required>
                                                    <option value=""
                                                            style="font-size: 16px ;font-weight: bold"></option>
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

                                        {{--بيان رقم --}}
                                        <div class="col-lg-1 col-md-1 col-sm-12">
                                            <div class="form-group">
                                                <label for="product-key" style="font-size: 16px ;font-weight: bold"
                                                       class="control-label">@lang('home.data_number')</label>
                                                <input type="number" class="form-control" name="trip_dt_serial"
                                                       value="{{session('count')}}"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="section-body py-4">
                        <div class="container-fluid">
                            <div class="row clearfix">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">@lang('home.data')
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#exampleModal"
                                                        data-whatever="@mdo">@lang('home.add_cars')</button>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-danger" v-if="trips_count_error_message">@{{
                                                trips_count_error_message }}
                                            </div>
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
                                                        <th>{{__('fees')}}</th>
                                                        <th></th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <tr v-for="trip,index in trips">
                                                        <input type="hidden" name="waybill_id[]"
                                                               :value="trips[index]['waybill_id']">
                                                        <td style="font-size: 16px ;color: blue ;font-weight: bold">

                                                            <select class="form-control"
                                                                    style="font-size: 16px ;color: blue ;font-weight: bold"
                                                                    @change="getWaybillData($event.target.value,index)"
                                                                    v-model="trips[index]['waybill_id']">
                                                                <option value="">@lang('home.choose')</option>
                                                                <option v-for="way_bill in way_bills"
                                                                        :value="way_bill.waybill_id">
                                                                    @{{way_bill.waybill_code}}
                                                                </option>
                                                            </select>
                                                        </td>

                                                        <td style="font-size: 16px ;font-weight: bold">@{{
                                                            trips[index].waybill_car_plate }}
                                                        </td>
                                                        <td style="color: red">
                                                            @{{trips[index].waybill_car_desc }}
                                                        </td>
                                                        <td>
                                                            @{{trips[index].customer_name_full_ar}}

                                                        </td>
                                                        <td>@{{ trips[index].waybill_car_owner }}</td>

                                                        <td style="color: blue">
                                                            @{{ trips[index].loc_to_name }}
                                                        </td>

                                                        <td>

                                                            @{{ trips[index]['waybill_total_amount'] }}
                                                        </td>
                                                        <td>
                                                            @{{ trips[index]['waybill_fees_total'] }}
                                                        </td>

                                                        <td>
                                                            <button type="button" @click="addRow()"
                                                                    class="btn btn-circle btn-icon-only red-flamingo">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                            <button type="button" @click="removeRow(index)"
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
                                        {{--<button class="btn btn-primary" type="submit" id="submit">--}}
                                        {{--@lang('home.save')</button>--}}

                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#confirmModal" id="button">
                                            @lang('home.save')
                                        </button>

                                        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{__('Are You Sure To confirm Add Waybills')}}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{__('close')}}
                                                        </button>
                                                        <button type="submit" id="submit"
                                                                class="btn btn-primary">{{__('save')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="{{config('app.telerik_server')}}?rpt={{$trip->report_url_trip->report_url}}&trip_id={{$trip->trip_hd_id}}&lang=ar&skinName=bootstrap"
                                           class="btn btn-primary"
                                           style="display: inline-block; !important;" id="print"
                                           target="_blank">
                                            @lang('home.print')</a>

                                        <a class="btn btn-primary" href="{{route('Trips' )}}" id="back">
                                            @lang('home.back')</a>

                                        <div class="spinner-border" role="status" style="display: none">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>

                <div id="exampleModal" class="modal fade full" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" style="width:250%">
                            <div class="modal-header" style="text-align:right">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="text-align:right">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"> اختيار بوالص الشحن للرحلة
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#exampleModal"
                                                    data-whatever="@mdo">@lang('home.add_cars')</button>
                                        </h3>
                                    </div>
                                    <div class="card-body">


                                        <div class="row">
                                            <div class="col-md-4">
                                                <label style="font-size: 16px ;font-weight: bold">@lang('home.waybill_code') </label>
                                                <input type="text" class="form-control" v-model="waybill_code"
                                                       style="font-size: 16px ;font-weight: bold">
                                                <small style="color: green">يمكن البحث بكتابه رقم البوليصه</small>
                                            </div>

                                            <div class="col-md-4">
                                                <label style="font-size: 16px ;font-weight: bold">@lang('home.from') </label>
                                                <input type="text" class="form-control" v-model="waybill_loc_from"
                                                       style="font-size: 16px ;font-weight: bold">
                                                <small style="color: green">يمكن البحث بكتابه اسم الفرع</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label style="font-size: 16px ;font-weight: bold">@lang('home.to')</label>
                                                <input type="text" class="form-control" v-model="waybill_loc_to"
                                                       style="font-size: 16px ;font-weight: bold">
                                                <small v-if="error_messagess" class="text-danger">@{{ error_messagess
                                                    }}
                                                </small>
                                            </div>
                                        </div>

                                        <template v-for="waybill,index in filteredWaybills3">
                                            <!-- Modal content-->
                                            <div class="modal-content" :id="'show_model'+waybill.waybill_id"
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

                                                    <p>فرع الوصول للرحله يختلف عن فرع وصول بوليصه الشحن هل انت
                                                        متاكد من الاضافه</p>

                                                    <button type="button"
                                                            @click="confirmUpdate(waybill.waybill_id)"
                                                            class="btn btn-danger yes">@lang('home.yes')</button>
                                                    <button type="button" class="btn btn-default"
                                                            @click="unconfirmUpdate(waybill.waybill_id)">@lang('home.no')</button>

                                                </div>
                                            </div>
                                        </template>

                                        <div class="alert alert-danger" v-if="trips_count_error_message">@{{
                                            trips_count_error_message }}
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table text-nowrap mb-0">
                                                <thead>
                                                <tr>
                                                    <td>
                                                        {{--<input type="checkbox" id="select_all"--}}
                                                        {{--@click="addAllRows()" v-model="select_all"--}}
                                                        {{--:disabled="checkbox_disabled">--}}
                                                    </td>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.waybill_number')</th>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.plate_number')</th>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.from')</th>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.to')</th>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.transit')</th>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.car_type')</th>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.customer')</th>
                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.owner')</th>

                                                    <th style="font-size: 16px ;font-weight: bold">@lang('home.value')</th>
                                                    <th>{{__('fees')}}</th>

                                                </tr>
                                                </thead>
                                                <tbody>

                                                <tr v-for="waybill,index in filteredWaybills3">
                                                    {{--<input type="hidden" name="waybill_id[]"--}}
                                                    {{--:value="waybill.waybill_id">--}}
                                                    <td>
                                                        <input type="checkbox" :disabled="checkbox_disabled"
                                                               v-model="waybill_id" :id="'checkbox'+waybill.waybill_id"
                                                               @click="addRowFomModal(waybill,
                                                                       $event,waybill.waybill_id)"
                                                               :value="waybill.waybill_id">
                                                    </td>
                                                    <td style="font-size: 16px ;color: blue ;font-weight: bold">@{{
                                                        waybill.waybill_code }}
                                                    </td>
                                                    <td style="font-size: 14px ;font-weight: bold">@{{
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
                                                    <td style="color: red">@{{ waybill.waybill_car_desc }}
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
                                                </tr>

                                                </tbody>
                                            </table>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
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


            $('#show_model').prop('hidden', true)
            $('#select_all').click(function () {
                if ($('#select_all').prop('checked') == true) {
                    $('input:checkbox').prop('checked', true);
                } else {
                    $('input:checkbox').prop('checked', false);
                }
            });

            $('form').submit(function () {
                $('#button').css('display', 'none')
                $('#submit').attr('disabled', 'true')
                $('#back').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#waybill_date').val(output)
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                trips: [],
                trip_id: '{{$trip->trip_hd_id}}',
                way_bills: [],
                loc_from: '',
                loc_to: '',
                select_all: false,
                checkbox_disabled: false,
                waybill_id: [],
                waybill_loc_to: '',
                waybill_loc_from: '',
                error_messagess: '',
                show_model: false,

                waybill_id_s: '',
                waybill_code_s: '',
                waybill_dt_s: '',
                loc_to_s: '',
                loc_from_s: '',
                loc_transit_s: '',
                waybill_total_amount_s: '',
                waybill_fees_total_s: '',
                customer_s: '',
                payment_s: '',
                waybill_code: '',
                trips_count_error_message: "",
                trip_line_type_code: '{{$trip->tripLine->tripLineTypeT->system_code}}',
                trip_hd_fees_old: '{{$trip->trip_hd_fees_2}}',
                trip_hd_fees_1: '{{$trip->trip_hd_fees_1}}'
            },

            methods: {
                getWaybillHd() {
                    this.way_bills = []
                    this.trips = []
                    $.ajax({
                        type: 'GET',
                        data: {loc_from: this.loc_from, trip_id: this.trip_id, loc_to: this.loc_to},
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
                            'waybill_fees_total': '',
                        })
                    } else {
                        this.trips_count_error_message = 'غير مسموح باضافه اكتر من عدد 8 بوالص في البيان الواحد'
                    }

                },
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
                },
                removeRow(index) {
                    this.trips.splice(index, 1)
                },
                addRowFomModal(waybill, event, id) {
                    console.log('#show_model' + id)
                    $('#show_model' + id).prop('hidden', true)
                    this.waybill_id_s = '',
                        this.waybill_code_s = '',
                        this.waybill_car_plate_s = '',
                        this.waybill_car_desc_s = '',
                        this.customer_name_full_ar_s = '',
                        this.waybill_car_owner_s = '',
                        this.loc_to_name_s = '',
                        this.waybill_total_amount_s = ''
                    this.waybill_fees_total_s = ''


                    if (this.loc_to != waybill.loc_to_id && event.target.checked) {
                        this.checkbox_disabled = true

                        $('#show_model' + id).prop('hidden', false)

                        this.waybill_id_s = waybill.waybill_id,
                            this.waybill_code_s = waybill.waybill_code,
                            this.waybill_car_plate_s = waybill.waybill_car_plate,
                            this.waybill_car_desc_s = waybill.waybill_car_desc,
                            this.customer_name_full_ar_s = waybill.customer_name_full_ar,
                            this.waybill_car_owner_s = waybill.waybill_car_owner,
                            this.loc_to_name_s = waybill.loc_to_name,
                            this.waybill_total_amount_s = waybill.waybill_total_amount
                        this.waybill_fees_total_s = waybill.waybill_fees_total

                    } else {
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

                        } else {
                            this.trips.splice(this.trips.indexOf(id), 1)
                        }

                    }

                },
                confirmUpdate(id) {
                    this.trips_count_error_message = ''
                    if (this.trips.length < 8) {
                        this.trips.push({
                            'waybill_id': this.waybill_id_s,
                            'waybill_code': this.waybill_code_s,
                            'waybill_car_plate': this.waybill_car_plate_s,
                            'waybill_car_desc': this.waybill_car_desc_s,
                            'customer_name_full_ar': this.customer_name_full_ar_s,
                            'waybill_car_owner': this.waybill_car_owner_s,
                            'loc_to_name': this.loc_to_name_s,
                            'waybill_total_amount': this.waybill_total_amount_s,
                            'waybill_fees_total': this.waybill_fees_total_s,

                        })

                    } else {
                        this.trips_count_error_message = 'غير مسموح باضافه اكتر من عدد 8 بوالص في البيان الواحد'
                    }

                    $('#show_model' + id).prop('hidden', true)
                    this.checkbox_disabled = false
                },

                unconfirmUpdate(id) {
                    this.checkbox_disabled = false
                    $('#show_model' + id).prop('hidden', true)
                    $('#checkbox' + id).prop('checked', false);
                },

                getWaybillData(waybill_id, index) {
                    $.ajax({
                        type: 'GET',
                        data: {waybill_id: waybill_id},
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

                    })
                }
            },
            computed: {
                trip_hd_fees_2: function () {
                    let total = 0;
                    Object.entries(this.trips).forEach(([key, val]) => {
                        if (val.waybill_fees_total) {
                            total += (parseFloat(val.waybill_fees_total))
                        } else {
                            total += 0
                        }

                    });
                    return (parseFloat(this.trip_hd_fees_old) + total).toFixed(2);
                },
                filteredWaybills: function () {
                    return this.way_bills.filter(waybill => {
                        return waybill.loc_from_name.match(this.waybill_loc_from)
                    })
                },
                filteredWaybills2: function () {
                    return this.filteredWaybills.filter(waybill => {
                        return waybill.loc_to_name.match(this.waybill_loc_to)
                    })
                },
                filteredWaybills3: function () {
                    return this.filteredWaybills2.filter(waybill => {
                        return waybill.waybill_code.match(this.waybill_code)
                    })
                },
            }

        })

    </script>
@endsection
