@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
            font-size: 16px;
            color: #000000;
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <style lang="">
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap');

        .v-application {
            font-family: "Cairo" !important;
        }
    </style>

@endsection


@section('content')
    @php
        if(session('waybill_hd')){
        session()->forget('waybill_hd');
        }
    @endphp

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">

                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-1">
                                </div>
                                <div class="col-md-3">
                                    <div class="font-25">
                                        @lang('waybill.add_new_waybillcar')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-body mt-6" id="app">
        <v-app>
            <div class="container-fluid">
                <div class="tab-content mt-6">

                    {{-- Basic information --}}
                    <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                        {{-- Form To Create Waybill--}}
                        <form class="card" id="validate-form" action="{{ route('Waybill.store_car') }}"
                              method="post" enctype="multipart/form-data" id="form">
                            @csrf

                            <div class="card-body">
                                {{--inputs data--}}
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">

                                            <div class="col-md-3">
                                                {{-- حاله الشحنه --}}
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_status') </label>
                                                <input type="text" readonly class="form-control" value="{{
                                            $sys_code_waybill_status->system_code_name_ar}}">

                                                <input type="hidden" class="form-control" v-model="waybill_status"
                                                       name="waybill_status">
                                            </div>


                                            <div class="col-md-3">
                                                <label hidden for="recipient"
                                                       class="form-label"> @lang('trucks.sub_company') </label>
                                                <select hidden class="form-select form-control"
                                                        name="company_id" id="company_id"
                                                        v-model="company_id">
                                                    <option value="" selected>@lang('home.choose')</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->company_id }}">
                                                            @if(app()->getLocale()=='ar')
                                                                {{ $company->company_name_ar }}
                                                            @else
                                                                {{ $company->company_name_en }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <div class="form-group">
                                                    <label class="form-label">@lang('trucks.sub_company')</label>
                                                    <input type="text" readonly class="form-control"
                                                           value="@if(app()->getLocale()=='ar') {{ auth()->user()->company->company_name_ar }}
                                                           @else {{ auth()->user()->company->company_name_en }} @endif">
                                                </div>


                                            </div>


                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('waybill.created_date')</label>
                                                    <input type="text" class="form-control" name="waybill_date"
                                                           id="waybill_date" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" readonly class="form-control"
                                                           value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                           @else {{ auth()->user()->user_name_en }} @endif">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            {{-- العميل--}}
                                            <div style="font-size: 15px ;font-weight: bold ;color : blue "
                                                 class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> @lang('waybill.customer_name') </label>
                                                <div class="form-group multiselect_div">
                                                    <div class="form-group multiselect_div">
                                                        <select class="selectpicker" data-live-search="true"
                                                                name="customer_id" id="customer_id"
                                                                style="color:black"
                                                                @change="getcustomertype(); getContractsList();getPriceList();getCountWaybillsDaily();
                                                             reValidAdditions(); getWaybillLocPaid()"
                                                                v-model="customer_id">
                                                            <option value="" selected>@lang('home.choose')</option>
                                                            @foreach($customers as $customer)
                                                                <option value="{{$customer->customer_id }}">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        {{ $customer->customer_name_full_ar }}
                                                                    @else
                                                                        {{ $customer->customer_name_full_en }}
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            {{-- رقم العقد للعميل--}}
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.customer_contract') </label>
                                                <select class="form-control" name="customer_contract"
                                                        id="customer_contract" v-model="customer_contract"
                                                        style="color:black"
                                                        @change="getPriceList()" required>
                                                    <option>@lang('home.choose')</option>
                                                    <option v-for="contract in contracts_list"
                                                            :value="contract.price_list_id">
                                                        @{{contract.price_list_code}}
                                                    </option>
                                                </select>
                                                <small class="text-danger" v-if="contract_error">@{{contract_error}}
                                                </small>
                                            </div>

                                            {{-- رقم التذكره--}}
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.approved_no') </label>
                                                <input type="text" class="form-control" v-model="waybill_ticket_no"
                                                       autocomplete="off" :required="waybill_ticket_no_required"
                                                       name="waybill_ticket_no" id="waybill_ticket_no"
                                                       placeholder="@lang('waybill.approved_no')">
                                            </div>


                                            {{-- نوع  للعميل--}}

                                            <div class="col-md-3">
                                                <label class="form-label">@lang('waybill.customer_type')</label>

                                                @if(app()->getLocale() == 'ar')
                                                    <input type="text" class="form-control" readonly
                                                           name="customer_type"
                                                           :value="customer_type_ar">
                                                @else
                                                    <input type="text" class="form-control" readonly
                                                           :value="customer_type_en">
                                                @endif

                                            </div>
                                        </div>


                                        <div class="row">

                                            {{--brand--}}
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> {{__('Car Type')}} </label>
                                                <select class="selectpicker" data-live-search="true" name="brand_id"
                                                        v-model="brand_id" @change="getBrandDtName()"
                                                        id="brand_id" style="color:black">
                                                    <option value="">{{__('Choose')}}</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{$brand->system_code_name_ar}}">{{app()->getLocale() == 'ar' ?
                                                    $brand->system_code_name_ar : $brand->system_code_name_ar}}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            {{--brand_dt--}}
                                            <div hidden class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> {{__('Car Type Dt')}} </label>
                                                <select class="form-control" name="brand_dt_id" v-model="brand_dt_id"
                                                        id="brand_dt_id"
                                                        style="color:black"
                                                        @change="getBrandDtSize()" id="brand_dt_id">
                                                    <option value=""></option>
                                                    <option v-for="brand_dt in brand_dts"
                                                            v-bind:value="{ id: brand_dt.brand_dt_id, text: brand_dt.brand_dt_name_ar }">
                                                        @{{ brand_dt.brand_dt_name_ar }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.transport_type') </label>
                                                <select class="form-select form-control"
                                                        name="waybill_item_id"
                                                        style="color:black"
                                                        id="waybill_item_id" required onchange="addPropReq()"
                                                        @change="getCountWaybillsDaily(); getPriceList()"
                                                        v-model="waybill_item_id">
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_shipping as $sys_code_shipping)
                                                        <option value="{{$sys_code_shipping->system_code}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_shipping->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_shipping->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            {{--تاريخ التحميل--}}
                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="form-label"> @lang('waybill.waybill_date_loaded') </label>

                                                <input type="datetime-local" class="form-control"
                                                       name="waybill_load_date"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       v-model="waybill_load_date"
                                                       @change="getCountWaybillsDaily() ; addDaysToDate()"
                                                       id="waybill_date_loaded" value="{{$current_date}}"
                                                       placeholder="@lang('waybill.waybill_date_loaded')" required>
                                            </div>

                                            {{--محطه الشحن--}}
                                            <div style="font-size: 15px ;font-weight: bold ;color : blue "
                                                 class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> @lang('waybill.loc_car_from') </label>
                                                <div class="form-group multiselect_div">
                                                    <div class="form-group multiselect_div">
                                                        <select class="selectpicker" data-live-search="true"
                                                                name="waybill_loc_from" id="waybill_loc_from"
                                                                style="color:black"
                                                                @change="getPriceList();getCountWaybillsDaily(); reValidAdditions(); getWaybillLocPaid()"
                                                                v-model="waybill_loc_from" required>
                                                            <option value="" selected>@lang('home.choose')</option>
                                                            @foreach($sys_codes_location as $sys_code_location)
                                                                <option value="{{ $sys_code_location->system_code_id }}">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        {{ $sys_code_location->system_code_name_ar }}
                                                                    @else
                                                                        {{ $sys_code_location->system_code_name_ar }}
                                                                    @endif

                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div style="font-size: 15px ;font-weight: bold ;color : blue "
                                                 class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> @lang('waybill.loc_car_to') </label>
                                                <div class="form-group multiselect_div">
                                                    <div class="form-group multiselect_div">
                                                        <select class="selectpicker" data-live-search="true"
                                                                name="waybill_loc_to"
                                                                style="font-size: 16px ;font-weight: bold;color:black"
                                                                id="waybill_loc_to"
                                                                @change="getPriceList();getCountWaybillsDaily();reValidAdditions();getWaybillLocPaid()"
                                                                v-model="waybill_loc_to" required>
                                                            <option value="" selected>@lang('home.choose')</option>
                                                            @foreach($sys_codes_location as $sys_code_location)
                                                                <option value="{{ $sys_code_location->system_code_id }}">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        {{ $sys_code_location->system_code_name_ar }}
                                                                    @else
                                                                        {{ $sys_code_location->system_code_name_ar }}@endif

                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" name="waybill_distance" v-model="waybill_distance">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> {{__('Days Count')}} </label>
                                                <input type="number" v-model="days_count" class="form-control"
                                                       @change="addDaysToDate()">
                                            </div>


                                            {{--تاريخ الوصول المتوقع--}}
                                            <div style="font-size: 15px ;font-weight: bold ;color : blue "
                                                 class="col-md-3">
                                                <label for="recipient"
                                                       class="form-label"> @lang('waybill.waybill_date_expected') </label>
                                                <input type="datetime-local" class="form-control is-invalid"
                                                       name="waybill_delivery_expected"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       v-model="waybill_delivery_expected"
                                                       id="waybill_delivery_expected"
                                                       placeholder="@lang('waybill.waybill_date_expected')"
                                                       required>
                                            </div>


                                            {{--الكميه المطلوبه--}}
                                            <div hidden class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_qut_request') </label>
                                                <input type="text" class="form-control"
                                                       v-model="waybill_qut_requried_supplier"
                                                       name="waybill_qut_requried_supplier"
                                                       id="waybill_qut_requried_supplier"
                                                       placeholder="@lang('waybill.waybill_qut_request')">
                                            </div>

                                            {{--الكميه المشحونه--}}
                                            <div hidden class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_qut') </label>
                                                <input type="text" class="form-control"
                                                       name="waybill_qut_received_supplier"
                                                       v-model="waybill_qut_received_supplier"
                                                       id="waybill_qut_received_supplier"
                                                       placeholder="@lang('waybill.waybill_qut')">
                                            </div>

                                            {{-- - تاريخ وصول الرحله--}}
                                            <div hidden class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('waybill.waybill_date_receved') </label>
                                                <input type="datetime-local" class="form-control"
                                                       name="waybill_unload_date"
                                                       id="waybill_unload_date"
                                                       placeholder="@lang('waybill.waybill_date_receved')">
                                            </div>
                                        </div>

                                        <div class="row">
                                            {{--الكميه--}}
                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_qut_actual') </label>
                                                <input type="number" class="form-control" readonly step="0.01"
                                                       v-model="qut_actual" id="waybill_item_quantity_supplier"
                                                       placeholder="@lang('waybill.waybill_qut_actual')">
                                            </div>
                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybil_item_unit') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="waybill_item_unit" style="color:black"
                                                        id="waybill_item_unit">
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_unit as $sys_code_unit)
                                                        <option value="{{$sys_code_unit->system_code_id}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_unit->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_unit->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            {{--نسبه الضريبه--}}
                                            <div hidden class="col-md-1">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_vat') </label>
                                                <input type="number" class="form-control" step="0.01"
                                                       id="waybill_item_vat_rate_supplier"
                                                       name="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                       placeholder="@lang('waybill.waybill_vat')">

                                            </div>

                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_vat_amount') </label>
                                                <input type="number" class="form-control" step="0.01"
                                                       name="waybill_vat_amount_supplier" readonly
                                                       v-model="waybill_vat_amount_supplier"
                                                       placeholder="@lang('waybill.waybill_vat_amount')">

                                            </div>

                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_total') </label>
                                                <input type="number" class="form-control" readonly step="0.01"
                                                       name="waybill_amount_supplier" v-model="waybill_amount_supplier"
                                                       placeholder="@lang('waybill.waybill_total')">

                                            </div>

                                        </div>


                                        <div class="card bline" style="color:red">
                                        </div>


                                        {{--العميل--}}
                                        <div class="row">


                                            <div class="col-md-6 col-lg-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row" v-if="waybill_return_s">
                                                            <div class="col-md-6">
                                                                <label>ذهاب</label>
                                                                <input type="radio" value="1" name="waybill_return"
                                                                       v-model="waybill_return">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label> ذهاب وعوده</label>
                                                                <input type="radio" value="2" name="waybill_return"
                                                                       v-model="waybill_return">
                                                            </div>

                                                        </div>
                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_chase') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_chase" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       :disabled="lock_data_538"
                                                                       id="waybill_car_chase"
                                                                       v-model="waybill_car_chase"
                                                                       @change="getplateno()"
                                                                       placeholder="@lang('waybill.car_chase')"
                                                                       required>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_plate') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_plate" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       :disabled="lock_data_538"
                                                                       @change="getWaybillByPlateNumber()"
                                                                       id="waybill_car_plate"
                                                                       v-model="waybill_car_plate"
                                                                       placeholder="@lang('waybill.car_plate')"
                                                                       required>

                                                                <small style="color:red;font-weight: bold"
                                                                       v-if="car_plate_message">@{{
                                                                    car_plate_message }}
                                                                </small>

                                                            </div>
                                                        </div>

                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_desc') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_desc" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       id="waybill_car_desc" :disabled="lock_data_538"
                                                                       v-model="waybill_car_desc"
                                                                       placeholder="@lang('waybill.car_desc')" required>

                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_owner') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_owner" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       :disabled="lock_data_538"
                                                                       onchange="waybillCarOwner()"
                                                                       id="waybill_car_owner"
                                                                       v-model="waybill_car_owner"
                                                                       placeholder="@lang('waybill.car_owner')"
                                                                       required>

                                                            </div>


                                                        </div>
                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_color') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_color" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       :disabled="lock_data_538"
                                                                       id="waybill_car_color"
                                                                       v-model="waybill_car_color"
                                                                       placeholder="@lang('waybill.car_color')"
                                                                       required>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_model') </label>
                                                                <input type="number" class="form-control is-invalid"
                                                                       name="waybill_car_model" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       :disabled="lock_data_538"
                                                                       onchange="waybillCarModel()"
                                                                       id="waybill_car_model"
                                                                       v-model="waybill_car_model"
                                                                       placeholder="@lang('waybill.car_model')"
                                                                       required>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="card" name='aaaaaaaa'>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>@lang('home.is_the_sender_the_same_receiver')</label>
                                                                <input type="checkbox" name="same_data" value="1"
                                                                       v-model="same_data"
                                                                       @change="receiverSenderData()">
                                                            </div>
                                                        </div>


                                                        {{--رقم الهويه للشاحن والمستلم--}}
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('home.sender_identity') </label>
                                                                <input type="number" class="form-control is-invalid"
                                                                       name="waybill_sender_mobile_code"
                                                                       autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       id="waybill_sender_mobile_code"
                                                                       v-model="customer_identity"
                                                                       @change="getSenderInfo();validateInputs()"
                                                                       placeholder="@lang('home.sender_identity')"
                                                                       required>

                                                                <small v-if="identity_message_s" class="text-danger">@{{
                                                                    identity_message_s }}
                                                                </small>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('home.receiver_identity') </label>
                                                                <input type="number" class="form-control is-invalid"
                                                                       name="waybill_receiver_mobile_code"
                                                                       autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       id="waybill_receiver_mobile_code"
                                                                       v-model="receiver_identity"
                                                                       @keyup="validateInputs()"
                                                                       placeholder="@lang('home.receiver_identity')"
                                                                       required :disabled="lock_data_538">
                                                                <small v-if="identity_message_r" class="text-danger">@{{
                                                                    identity_message_r }}
                                                                </small>

                                                            </div>

                                                        </div>

                                                        {{--رقم الجوال للشاحن والمستلم--}}
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.sender_p_mobile') </label>
                                                                <input type="number" class="form-control is-invalid"
                                                                       name="waybill_sender_mobile" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       @keyup="validateInputs()"
                                                                       id="waybill_sender_mobile"
                                                                       v-model="customer_mobile"
                                                                       placeholder="@lang('waybill.sender_p_mobile')"
                                                                       required :disabled="lock_data_538">
                                                                <small v-if="mobile_message_s" class="text-danger">@{{
                                                                    mobile_message_s }}
                                                                </small>

                                                                <small v-if="sender_message" class="text-danger">@{{
                                                                    sender_message }}
                                                                </small>
                                                            </div>


                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.receiver_p_mobile') </label>
                                                                <input type="number" class="form-control is-invalid"
                                                                       name="waybill_receiver_mobile" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       @keyup="validateInputs()"
                                                                       v-model="receiver_mobile"
                                                                       id="waybill_receiver_mobile"
                                                                       placeholder="@lang('waybill.receiver_p_mobile')"
                                                                       required :disabled="lock_data_538">

                                                                <small v-if="mobile_message_r" class="text-danger">@{{
                                                                    mobile_message_r }}
                                                                </small>
                                                            </div>


                                                        </div>

                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.sender_name') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_sender_name" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       :disabled="lock_data_538"
                                                                       id="waybill_sender_name" v-model="customer_name"
                                                                       placeholder="@lang('waybill.sender_name')"
                                                                       required>

                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.receiver_name') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_receiver_name" autocomplete="off"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       :disabled="lock_data_538"
                                                                       v-model="receiver_name"
                                                                       id="waybill_receiver_name"
                                                                       placeholder="@lang('waybill.receiver_name')"
                                                                       required>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">

                                            {{--طريقه الدفع--}}

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.pay_method') </label>

                                                {{--شركات--}}
                                                <select class="form-select form-control waybill_payment_method"
                                                        name="waybill_payment_method" onchange="addPropReq()"
                                                        id="waybill_payment_method" required
                                                        style="font-size: 16px ;font-weight: bold;color:black"
                                                        v-model="waybill_payment_method"
                                                        @change="validatePaid(); reValidAdditions(); getDiscountTypeByCompany(); getWaybillLocPaid()"
                                                        v-if="customer_type_obj.system_code==539"
                                                        :disabled="waybill_payment_method_valid">
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_payment_methods as $sys_code_payment_methods)
                                                        <option value="{{$sys_code_payment_methods->system_code}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_payment_methods->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_payment_methods->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach


                                                </select>


                                                {{--افراد--}}
                                                <select class="form-select form-control waybill_payment_method"
                                                        name="waybill_payment_method" onchange="addPropReq()"
                                                        id="waybill_payment_method" required
                                                        style="font-size: 16px ;font-weight: bold;color:black"
                                                        v-model="waybill_payment_method"
                                                        @change="validatePaid();reValidAdditions() ; getDiscountTypeByCompany(); getWaybillLocPaid()"
                                                        v-else-if="customer_type_obj.system_code==538"
                                                        :disabled="waybill_payment_method_valid">
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_payment_methods_n as $sys_code_payment_methods_n)
                                                        <option value="{{$sys_code_payment_methods_n->system_code}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_payment_methods_n->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_payment_methods_n->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select class="form-select form-control"
                                                        style="color:black"
                                                        name="waybill_payment_method" onchange="addPropReq()"
                                                        @change="validatePaid();reValidAdditions();getDiscountTypeByCompany(); getWaybillLocPaid()"
                                                        id="waybill_payment_method" required
                                                        :disabled="waybill_payment_method_valid"
                                                        v-model="waybill_payment_method"
                                                        v-else>

                                                    @foreach($sys_codes_payment_methods as $sys_code_payment_methods)
                                                        <option value="{{$sys_code_payment_methods->system_code}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_payment_methods->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_payment_methods->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>


                                            </div>


                                            {{--الكميه المطلوبه للعميل--}}
                                            <div hidden class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_qut_request') </label>
                                                <input type="text" class="form-control"
                                                       name="waybill_qut_requried_customer"
                                                       id="waybill_qut_requried_customer"
                                                       v-model="waybill_qut_requried_customer"
                                                       placeholder="@lang('waybill.waybill_qut_request')">
                                            </div>

                                            {{--الكميه المستلمه--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.car_no') </label>
                                                <input type="text" class="form-control"
                                                       name="waybill_qut_received_customer"
                                                       id="waybill_qut_received_customer"
                                                       onchange="waybillCarOwner();waybillCarModel()"
                                                       @change="getColorA()"
                                                       v-model="waybill_qut_received_customer"
                                                       placeholder="@lang('waybill.car_no')" required>
                                            </div>


                                            {{--سعر الوحده--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label is-invalid"> @lang('waybill.waybill_price') </label>
                                                <input type="number" class="form-control" step="0"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_item_price" v-model="waybill_item_price_d"
                                                       id="waybill_item_price"
                                                       placeholder="@lang('waybill.waybill_price')"
                                                       required readonly>
                                                <small v-if="error_messagess" class="text-danger">@{{ error_messagess }}
                                                </small>
                                            </div>


                                            {{--سعر الاضافات--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.add_amount') </label>
                                                <input type="number" class="form-control" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_add_amount" v-model="waybill_add_amount"
                                                       @keyup="validateadd()" min="0"
                                                       id="waybill_add_amount"
                                                       placeholder="@lang('waybill.add_amount')">
                                                <small v-if="add_error_messages" class="text-danger">@{{
                                                    add_error_messages
                                                    }}
                                                </small>
                                            </div>


                                            {{--سعر الخصومات--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.disc_amount') </label>


                                                <input type="number" class="form-control" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_discount_total"
                                                       v-model="waybill_discount_total_customer"
                                                       id="waybill_discount_total" min="0"
                                                       :disabled="discount_flag"
                                                       placeholder="@lang('waybill.disc_amount')"
                                                       v-if="customer_discount_rate > 0">

                                                <input type="number" class="form-control" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_discount_total" v-model="waybill_discount_total"
                                                       id="waybill_discount_total" min="0"
                                                       @keyup="validatedesc()" :disabled="discount_flag"
                                                       placeholder="@lang('waybill.disc_amount')" v-else>

                                                <small v-if="error_messages" class="text-danger">@{{ error_messages }}
                                                </small>
                                            </div>


                                            {{--الاجمالي قبل الضريبه --}}

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.total') </label>
                                                <input type="number" class="form-control" readonly
                                                       style="font-size: 16px ;font-weight: bold"
                                                       v-model="waybill_sub_total_amount"
                                                       name="waybill_sub_total_amount"
                                                       placeholder="@lang('waybill.total')" step="0.01">

                                            </div>


                                            {{--تاريخ التسليم--}}
                                            <div hidden class="col-md-3">
                                                <label for="recipient"
                                                       class="form-label"> @lang('waybill.waybill_date_end') </label>
                                                <input type="datetime-local" class="form-control"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_delivery_date"
                                                       id="waybill_delivery_date"
                                                       placeholder="@lang('waybill.waybill_date_end')">
                                            </div>
                                        </div>


                                        <div class="row">


                                            {{--الضريبه--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_vat') </label>
                                                <input type="number" class="form-control"
                                                       name="waybill_vat_rate"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       id="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                       placeholder="@lang('waybill.waybill_vat')" readonly>

                                            </div>

                                            {{--قيمه الضريبه--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_vat_amount') </label>
                                                <input type="number" class="form-control" readonly
                                                       v-model="waybill_item_vat_amount" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_vat_amount" id="waybill_item_vat_amount"
                                                       placeholder="@lang('waybill.waybill_vat_rate')">

                                            </div>

                                            {{--اجمالي القيمه شامله الضريبه--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_total') </label>
                                                <input type="number" class="form-control" readonly
                                                       v-model="waybill_total_amount" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_total_amount" id="waybill_total_amount"
                                                       placeholder="@lang('waybill.waybill_total')">

                                            </div>

                                            {{--الكميه--}}
                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_qut_actual') </label>
                                                <input type="number" class="form-control"
                                                       v-model="waybill_item_quantity"
                                                       name="waybill_item_quantity" id="waybill_item_quantity" readonly
                                                       placeholder="@lang('waybill.waybill_qut_actual')" step="0.01">

                                            </div>

                                            {{--المسدد --}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.payment_amount') </label>
                                                <input type="number" class="form-control" step="0.0001"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_paid_amount" v-model="waybill_paid_amount"
                                                       id="waybill_paid_amount" min="0"
                                                       @keyup="validatePaid()"
                                                       :disabled="paid_disabled"
                                                       placeholder="@lang('waybill.payment_amount')">
                                                <small v-if="error_message" class="text-danger">@{{ error_message }}
                                                </small>
                                            </div>

                                            {{--اجمالي   hلمتبقي--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.net_amount') </label>
                                                <input type="number" class="form-control" readonly
                                                       v-model="waybill_due_amount" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_due_amount" id="waybill_due_amount"
                                                       placeholder="@lang('waybill.net_amount')">

                                            </div>


                                            {{--طريقه السداد--}}

                                            {{--في حاله السداد لايساوي دفع علي الحساب--}}
                                            <div class="col-md-2" v-if="show_payment_terms">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.pay_type') </label>
                                                <select class="form-select form-control"
                                                        style="font-size: 16px ;font-weight: bold;color:black"
                                                        name="waybill_payment_terms" @change="validateInputs()"
                                                        id="waybill_payment_terms" v-model="waybill_payment_terms"
                                                        :required="payment_terms_r">
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_payment_type as $sys_code_payment_type)
                                                        <option value="{{$sys_code_payment_type->system_code}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_payment_type->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_payment_type->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{--ملاحظات --}}
                                            <div class="col-md-6">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.customer_notes') </label>
                                                <input type="text" class="form-control "
                                                       name="waybill_car_notes" autocomplete="off"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       id="waybill_car_notes"
                                                       placeholder="@lang('waybill.customer_notes')">

                                            </div>


                                            <div class="col-md-6">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> @lang('waybill.waybill_loc_paid') </label>

                                                <div class="form-group multiselect_div">
                                                    <select class="form-control" data-live-search="true"
                                                            name="waybill_loc_paid"
                                                            style="font-size: 16px ;font-weight: bold;color:black"
                                                            id="waybill_loc_paid"
                                                            v-model="waybill_loc_paid" required>

                                                        @foreach($sys_codes_location as $sys_code_location)
                                                            <option value="{{ $sys_code_location->system_code_id }}">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $sys_code_location->system_code_name_ar }}
                                                                @else
                                                                    {{ $sys_code_location->system_code_name_ar }}@endif

                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>


                                            {{--البنك--}}
                                            {{--<div class="col-sm-6 col-md-4" :hidden="bank_valid">--}}
                                            {{--<div class="form-group">--}}
                                            {{--<label class="form-label">@lang('home.bank')</label>--}}
                                            {{--<select class="form-control" name="bank_id"--}}
                                            {{--v-model="bank_id" :required="bank_valid">--}}
                                            {{--<option value="">@lang('home.choose')</option>--}}
                                            {{--@foreach($banks as $bank)--}}
                                            {{--<option value="{{ $bank->system_code_id }}">--}}
                                            {{--{{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :--}}
                                            {{--$bank->system_code_name_en }}--}}
                                            {{--</option>--}}
                                            {{--@endforeach--}}
                                            {{--</select>--}}
                                            {{--</div>--}}
                                            {{--</div>--}}


                                        </div>


                                        <div class="row">

                                            {{--الشاحنه--}}
                                            <div class="col-md-4" id="truck_data" hidden>
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_truck') </label>

                                                <select class="form-control" style="color:black"
                                                        name="waybill_truck_id" id="waybill_truck_id"
                                                        @change="getDriver();getCountWaybillsDaily()"
                                                        v-model="truck_id">

                                                    @foreach($trucks as $truck)
                                                        <option value="{{$truck->truck_id }}">
                                                            {{ $truck->truck_code .' '.$truck->truck_name.' '.$truck->truck_plate_no}}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>


                                            {{--السائق--}}
                                            <div hidden class="col-md-4" id="trip_driver">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_driver') </label>

                                                <input type="hidden" name="waybill_driver_id"
                                                       v-model="waybill_driver_id">
                                                @if(app()->getLocale() == 'ar')
                                                    <input type="text" class="form-control" readonly
                                                           id="emp_name_full_ar"
                                                           :value="driver.emp_name_full_ar">
                                                    <small class="text-danger" v-if="driver_error">
                                                        @{{ driver_error }}
                                                    </small>
                                                @else
                                                    <input type="text" class="form-control" readonly
                                                           :value="driver.emp_name_full_en" id="emp_name_full_en">
                                                    <small class="text-danger" v-if="driver_error">
                                                        @{{ driver_error }}
                                                    </small>
                                                @endif
                                            </div>

                                            {{--عدد ردود السائق اليوميه--}}
                                            <div hidden class="col-md-2" id="driver_rad">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.deriver_trip') </label>
                                                <input type="number" class="form-control" readonly
                                                       v-model="count" name="driver_rad"
                                                       placeholder="@lang('waybill.deriver_trip')">
                                            </div>


                                            {{--اجره الطريق--}}
                                            <div hidden class="col-md-2" id="driver_fees">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.driver_car_fees') </label>
                                                <input type="number" class="form-control" v-model="waybill_fees_load"
                                                       name="waybill_fees_load" id="waybill_fees_load" step="0.01"
                                                       placeholder="@lang('waybill.driver_car_fees')">

                                                <small class="text-danger" v-if="price_error">@{{ price_error}}</small>

                                            </div>


                                        </div>


                                        {{--اسعار الاضافات--}}
                                        <div class="row">

                                            {{--اجره الانتظار--}}
                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_fees_wait') </label>
                                                <input type="number" class="form-control" name="waybill_fees_wait"
                                                       id="waybill_fees_wait" v-model="waybill_fees_wait" step="0.01"
                                                       placeholder="@lang('waybill.waybill_fees_wait')">

                                            </div>

                                            {{-- فروقات التحميل--}}
                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('waybill.waybill_differences') </label>
                                                <input type="number" class="form-control" name="waybill_fees_difference"
                                                       id="waybill_fees_difference" v-model="waybill_fees_difference"
                                                       placeholder="@lang('waybill.waybill_differences')" step="0.01">

                                            </div>


                                        </div>


                                        <div class="card bline" style="color:red">
                                        </div>

                                        <div class="modal fade" id="myModal"
                                             role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                         style="background-color: firebrick">
                                                        <button type="button" class="close"
                                                                data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                        <h3 class="modal-title" align="center"
                                                            style="color: whitesmoke;"><i
                                                                    class="fa fa-warning"
                                                                    style="color: yellow;"></i> @lang('home.confirm_save')
                                                        </h3>
                                                    </div>
                                                    <div class="modal-body" style="font-size: 16px ;font-weight: bold">
                                                        <b align="center">@lang('home.confirm')</b>

                                                        <p id="waybill_text"></p>
                                                        <button type="submit" id="modal_button"
                                                                class="btn btn-danger yes">@lang('home.yes')</button>

                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">@lang('home.no')</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="row" v-if="cars_count>1">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">@lang('waybill.car_chase')</th>
                                                {{--<th scope="col">@lang('waybill.car_plate')</th>--}}
                                                <th scope="col">@lang('waybill.car_desc')</th>
                                                <th scope="col">@lang('waybill.car_desc')</th>
                                                <th scope="col">@lang('waybill.car_owner')</th>
                                                <th scope="col">@lang('waybill.car_color') </th>
                                                <th scope="col">@lang('waybill.car_color') </th>
                                                <th scope="col">@lang('waybill.car_model')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-if="chase_error">
                                                <td></td>
                                                <td colspan="3"><p style="color:red;font-weight:bold;font-size:16px">
                                                        @{{chase_error}}</p></td>
                                                <td colspan="4"></td>
                                            </tr>
                                            <tr v-for="i,index in cars_count">
                                                <td scope="row">@{{i++}}</td>
                                                <td>
                                                    <input type="text" class="form-control is-invalid"
                                                           name="waybill_car_chase_arr[]" autocomplete="off"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           onchange="getWaybillD2($(this))"
                                                           @change="checkChaseDuplicate(index)"
                                                           :disabled="lock_data_538" value="0"
                                                           v-model="waybill_car_chase_arr[index]"
                                                           placeholder="@lang('waybill.car_chase')" required>
                                                </td>
                                                <td><input type="text" name="waybill_car_desc_arr[]" required
                                                           class="form-control" v-model="type_list[index]"></td>
                                                <td>

                                                    <v-autocomplete
                                                            :items="typesList"
                                                            v-model="type_list[index]"
                                                            item-value="system_code_name_ar"
                                                            item-text="system_code_name_ar">
                                                        {{--<template v-slot:selection="data">--}}
                                                        {{--@{{data.item.system_code_name_ar}}--}}
                                                        {{--@{{data.item.system_code_search}}--}}
                                                        {{--</template>--}}
                                                    </v-autocomplete>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control is-invalid"
                                                           name="waybill_car_owner_arr[]" autocomplete="off"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           :disabled="lock_data_538"
                                                           placeholder="@lang('waybill.car_owner')" required>
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="waybill_car_color_arr[]" required
                                                           v-model="color_list[index]"></td>
                                                <td>

                                                    <v-autocomplete
                                                            v-model="color_list[index]"
                                                            :items="colorsList"
                                                            item-value="system_code_name_ar"
                                                            item-text="system_code_name_ar">
                                                    </v-autocomplete>

                                                </td>

                                                <td>
                                                    <input type="number" class="form-control is-invalid"
                                                           name="waybill_car_model_arr[]" autocomplete="off"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           :disabled="lock_data_538"
                                                           placeholder="@lang('waybill.car_model')" required>
                                                </td>

                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <button class="btn btn-primary" type="button" id="submit"
                                        :disabled="disable_button || disable_button2 || disable_button_add
                                            ||disable_button_2
                                            || sender_disable_button || lock_data_car_model || chase_error.length > 0"
                                        onclick="confirmUpdate()">
                                    @lang('home.save')</button>

                                <div class="spinner-border" role="status" style="display: none">
                                    <span class="sr-only">Loading...</span>
                                </div>

                                <a href="{{ route('WaybillCar') }}" class="btn btn-primary"
                                   style="display: inline-block; !important;"
                                   id="back">
                                    @lang('home.back')</a>
                            </div>


                        </form>


                    </div>

                </div>
            </div>
        </v-app>
    </div>


@endsection

@section('scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>


    <script>

        function confirmUpdate() {
            $('#myModal').modal('show')

            if ($('#waybill_item_price').val() <= 0 || $('#waybill_total_amount').val() <= 0) {
                $('#waybill_text').text('القيم غير صحيحه لا يمكن الحفظ');
                $('#modal_button').attr('disabled', 'disabled')
            } else {
                $('#waybill_text').text('هل انت متاكد من حفظ بوليصة الشحن  ' + ' ' + ' و طريقه السداد ' + ' ' + '<<<' + $('#waybill_payment_method option:selected').text() + '>>>' + ' بقيمه سداد ' + '<<<' + $('#waybill_paid_amount').val() + '>>>' + ' ريال')
                $('#modal_button').removeAttr('disabled')
            }
        }

        // function getDesc() {
        //     $('#brand_id').change(function () {
        //         $('#waybill_car_desc').val($('#brand_id option:selected').text().trim() + ' ' + $('#brand_dt_id option:selected').text().trim())
        //     })
        //
        //     $('#brand_dt_id').change(function () {
        //         $('#waybill_car_desc').val($('#brand_id option:selected').text().trim() + ' ' + $('#brand_dt_id option:selected').text().trim())
        //     })
        // }

        function waybillCarOwner() {
            $('input[name^="waybill_car_owner_arr"]').each(function () {
                if ($(this).val().length == 0) {
                    $(this).val($('#waybill_car_owner').val());
                }
            });
        }

        function waybillCarColor() {
            $('input[name^="waybill_car_color_arr"]').each(function () {
                if ($(this).val().length == 0) {
                    $(this).val($('#waybill_car_color').val());
                }
            });
        }

        function waybillCarModel() {
            $('input[name^="waybill_car_model_arr"]').each(function () {
                if ($(this).val().length == 0) {
                    $(this).val($('#waybill_car_model').val());
                }
            });
        }

        // function waybillCarDesc() {
        //     $('input[name^="waybill_car_desc_arr"]').each(function () {
        //         if ($(this).val().length == 0) {
        //             $(this).val($('#waybill_car_desc').val());
        //         }
        //     });
        // }


        function getWaybillD(el) {
            var val = el.val();
            $.ajax({
                type: 'GET',
                url: "{{ route('car-checkWaybillByPlateChaseNo') }}",
                data: {waybill_car_plate: val},
                success: function (data) {
                    if (data.error) {
                        $("<span>الرقم مسجل سابقا في بوليصه لم يتم شحنها</span>").insertAfter(el)
                    } else {
                        el.next("span").remove();
                    }
                }
            });
        }

        function getWaybillD2(el) {
            var val = el.val();
            $.ajax({
                type: 'GET',
                url: "{{ route('car-checkWaybillByPlateChaseNo') }}",
                data: {waybill_car_chase: val},
                success: function (data) {
                    if (data.error) {
                        $("<span>الرقم مسجل سابقا في بوليصه لم يتم شحنها</span>").insertAfter(el)
                    } else {
                        el.next("span").remove();
                    }
                }
            });
        }


        $(document).ready(function () {

            $('form').keydown(function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });


            $('form').submit(function () {
                $('#submit').css('display', 'none')
                $('#modal_button').css('display', 'none')
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

        function addPropReq() {

            if ($('#waybill_item_id').val() > 0) {

                $('.waybill_item_id').prop('readonly', true)
            }

            if ($('#waybill_item_id').val() == 64006 || $('#waybill_item_id').val() == 64005) {

                $('#truck_data').prop('hidden', false)
                $('#trip_driver').prop('hidden', false)
                $('#driver_rad').prop('hidden', false)
                $('#driver_fees').prop('hidden', false)

            } else {

                $('#truck_data').prop('hidden', true)
                $('#trip_driver').prop('hidden', true)
                $('#driver_rad').prop('hidden', true)
                $('#driver_fees').prop('hidden', true)
            }

            if ($('#waybill_status').val() == 41001) {
                $('#waybill_fees_load').prop('required', false)
                $('#waybill_fees_load').removeClass('is-invalid')

                $('#waybill_truck_id').prop('required', false)
                $('#waybill_truck_id').removeClass('is-invalid')
            }

            if ($('#waybill_status').val() == 41004 && $('#waybill_item_id').val() == 64005) {
                $('#waybill_fees_load').prop('required', true)
                $('#waybill_fees_load').addClass('is-invalid')

                $('#waybill_truck_id').prop('required', true)
                $('#waybill_truck_id').addClass('is-invalid')
            }

            if ($('#waybill_item_id').val() != 64005 && $('#waybill_item_id').val() != 64006) {


                $('#waybill_paid_amount').prop('required', true)
                $('#waybill_paid_amount').addClass('is-invalid')

                $('#waybill_truck_id').prop('required', false)
                $('#truck_data').prop('hidden', true)

            }


            // $('#waybill_payment_terms').prop('required', false)
            // $('#waybill_payment_terms').removeClass('is-invalid')


            $('#waybill_paid_amount').prop('required', false)
            $('#waybill_paid_amount').removeClass('is-invalid')

        }

        if ($('#waybill_status').val() == 41001) {


            $('#customer_id').prop('required', true)
            $('#customer_id').addClass('is-invalid')

            $('#waybill_date_loaded').prop('required', true)
            $('#waybill_date_loaded').addClass('is-invalid')

            $('#waybill_delivery_expected').addClass('is-invalid')
            $('#waybill_delivery_expected').prop('required', false)

            $('#waybill_item_id').prop('required', true)
            $('#waybill_item_id').addClass('is-invalid')

            $('#waybill_loc_from').prop('required', true)
            $('#waybill_loc_from').addClass('is-invalid')

            $('#waybill_loc_to').prop('required', true)
            $('#waybill_loc_to').addClass('is-invalid')

            $('#waybill_payment_method').prop('required', true)
            $('#waybill_payment_method').addClass('is-invalid')

            $('#waybill_item_price').prop('required', true)
            $('#waybill_item_price').addClass('is-invalid')

            $('#waybill_qut_received_customer').prop('required', true)
            $('#waybill_qut_received_customer').addClass('is-invalid')

            $('#waybill_item_price').prop('required', true)
            $('#waybill_item_price').addClass('is-invalid')
            // $('#waybill_qut_requried_customer').prop('required', true)
            //  $('#waybill_qut_requried_customer').addClass('is-invalid')
            // $('.waybill_item_id').prop('required', true)
            // $('.waybill_item_id').addClass('is-invalid')

            /////////remove required and is-invalid class
            $('#waybill_ticket_no').removeClass('is-invalid')
            $('#waybill_ticket_no').prop('required', false)


        }

        if ($('#waybill_status').val() == 41004) {

            $('#waybill_date_loaded').prop('required', true)
            $('#waybill_date_loaded').addClass('is-invalid')
            $('#waybill_loc_from').prop('required', true)
            $('#waybill_loc_from').addClass('is-invalid')

            $('#customer_id').prop('required', true)
            $('#customer_id').addClass('is-invalid')

            // $('#waybill_ticket_no').addClass('is-invalid')

            $('#waybill_item_price').prop('required', true)
            $('#waybill_item_price').addClass('is-invalid')
            $('#waybill_loc_to').prop('required', true)
            $('#waybill_loc_to').addClass('is-invalid')
            $('#waybill_delivery_expected').prop('required', true)
            $('#waybill_delivery_expected').addClass('is-invalid')

            $('#waybill_item_vat_rate').prop('required', true)
            $('#waybill_item_vat_rate').addClass('is-invalid')

            $('.waybill_item_id').prop('required', true)
            $('.waybill_item_id').addClass('is-invalid')

            $('#waybill_payment_method').prop('required', true)
            $('#waybill_payment_method').addClass('is-invalid')

            $('#waybill_qut_received_customer').prop('required', true)
            $('#waybill_qut_received_customer').addClass('is-invalid')

            $('#waybill_item_price').prop('required', true)
            $('#waybill_item_price').addClass('is-invalid')

        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                company_id: '',
                trucks: {},
                waybill_item_id: '',
                waybill_item_vat_rate: 0,
                waybill_status: 41004,
                waybill_payment_terms: '',
                disable_button: false,
                error_message: '',

                //suppli3er
                waybill_qut_requried_supplier: 1,
                waybill_qut_received_supplier: 1,
                waybill_price_supplier: 0,
                waybill_driver_id: '',
                //customer
                customer_id: '',
                customer_type_ar: '',
                customer_type_en: '',
                waybill_qut_received_customer: 1,
                waybill_qut_requried_customer: 1,
                waybill_item_price: 0,
                waybill_discount_total: 0,
                waybill_payment_method: '',
                waybill_add_amount: 0,
                waybill_paid_amount: 0,

                waybill_fees_difference: 0,
                waybill_fees_wait: 0,
                waybill_fees_load: 0,
                waybill_loc_from: '{{$sys_codes_loc_session ? $sys_codes_loc_session->system_code_id : ''}}',
                waybill_loc_to: '',
                item_id: '',
                truck_id: '',
                driver: {},
                customer_type_obj: {},
                count: '',
                bank_valid: true,
                error_messages: '',
                error_messagess: '',
                paid_disabled: false,
                bank_id: '',
                bank_list: true,

                contracts_list: {},
                contract_error: '',
                customer_contract: '',
                show_payment_terms: true,

                customer_name: '',
                customer_mobile: '',
                customer_identity: '',

                waybill_car_chase: '',
                waybill_car_plate: '',
                waybill_car_desc: '',
                waybill_car_model: '',
                waybill_car_color: '',

                receiver_name: '',
                receiver_mobile: '',
                receiver_identity: '',
                same_data: false,
                waybill_distance: '',
                waybill_ticket_no_required: false,
                waybill_car_owner: '',
                waybill_load_date: '{{$current_date}}',
                driver_error: '',
                price_error: '',
                waybill_delivery_expected: '',
                waybill_ticket_no: '000',
                waybill_return: 1,
                price_factor: 1,
                waybill_payment_method_valid: false,
                sender_disable_button: false,
                sender_block_flag: 0,
                sender_message: '',
                brand_id: '',
                brand_dts: [],
                brand_dt_id: '',
                add_error_messages: '',
                discount_flag: false,
                disable_button_add: false,
                days_count: '',
                date: '',
                lock_data_538: false,
                car_plate_message: '',
                colorsList: [],
                color_list: [],
                typesList: [],
                type_list: [],
                waybill_loc_paid: '{{$sys_codes_loc_session ? $sys_codes_loc_session->system_code_id : ''}}',
                payment_terms_r: false,
                waybill_car_chase_arr: [],
                chase_error: '',
                customer_discount_rate: 0
            },
            mounted() {
                this.getColorTypesList()
            },
            methods: {
                getWaybillLocPaid() {
                    if (this.waybill_payment_method == 54001 && this.waybill_loc_from) {
                        this.waybill_loc_paid = this.waybill_loc_from
                    }

                    else if (this.waybill_payment_method == 54002 && this.waybill_loc_to) {
                        this.waybill_loc_paid = this.waybill_loc_to

                    } else {
                        this.waybill_loc_paid = this.waybill_loc_from
                    }

                },
                getplateno() {
                    $.ajax({
                        type: 'GET',
                        url: ''
                    }).then(response => {
                        this.waybill_car_plate = this.waybill_car_chase

                    })
                },

                getColorTypesList() {
                    $.ajax({
                        type: 'GET',
                        url: ''
                    }).then(response => {
                        this.colorsList = response.colorsList
                        this.typesList = response.typesList
                    })
                },
                receiverSenderData() {

                    if (this.same_data) {
                        this.receiver_name = this.customer_name
                        this.receiver_mobile = this.customer_mobile
                        this.receiver_identity = this.customer_identity
                    } else {
                        this.receiver_name = ''
                        this.receiver_mobile = ''
                        this.receiver_identity = ''
                    }
                },
                validateInputs() {

                    if (this.customer_identity.length == 10) {
                        this.lock_data_538 = false
                    }

                    if (this.waybill_payment_terms == 57005) { ///دفع علي الحساب
                        this.bank_valid = false
                    } else {
                        this.bank_valid = true
                        this.bank_id = ''
                    }
                },
                reValidAdditions() {
                    this.waybill_add_amount = 0;
                    this.waybill_discount_total = 0;
                },
                validateadd() {
                    this.add_error_messages = ''
                    this.disable_button_add = false

                    if (this.waybill_add_amount < 0) {
                        this.disable_button_add = true
                        this.add_error_messages = '  المبلغ غير مسموح بة'
                    } else {
                        this.disable_button_add = false
                        this.add_error_messages = ''
                    }
                },
                validatedesc() {
                    this.disable_button = false
                    this.error_messages = ''
                    if (this.waybill_discount_total > this.waybill_fees_difference || this.waybill_discount_total < 0) {

                        this.error_messages = 'الخصم غير مسموح بة'
                    } else {
                        this.error_messages = ''
                        if (this.waybill_status == 41004 && this.waybill_payment_method == 54001) {
                            if (this.waybill_paid_amount != this.waybill_total_amount || this.waybill_paid_amount == 0) {
                                this.disable_button = true
                                this.error_messages = 'المسدد لا يساوي الاجمالي'
                            } else {
                                this.disable_button = false
                                this.error_messages = ''
                            }
                        } else {
                            this.disable_button = false
                            this.error_messages = ''
                        }
                    }
                },
                validatePaid() {
                    this.error_message = ''

                    if (this.waybill_payment_method == 54003) {
                        this.paid_disabled = true
                        this.waybill_paid_amount = 0
                    } else {
                        this.paid_disabled = false
                        this.show_payment_terms = true
                    }

                    if (this.waybill_paid_amount > 0) {
                        this.payment_terms_r = true
                    } else {
                        this.payment_terms_r = false
                    }

                    if (this.waybill_paid_amount > this.waybill_total_amount) {
                        this.disable_button = true
                        this.error_message = 'المسدد اكبر من الاجمالي'
                    } else if (this.waybill_status == 41004 && this.waybill_payment_method == 54001) {
                        if (this.waybill_paid_amount != this.waybill_total_amount || this.waybill_paid_amount == 0) {
                            this.disable_button = true
                            this.error_message = 'المسدد لا يساوي الاجمالي'
                        } else {
                            this.disable_button = false
                            this.error_message = ''
                        }
                    } else {
                        this.disable_button = false
                        this.error_message = ''
                    }

                },
                getTrucks() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.trucks") }}'
                    }).then(response => {
                        this.trucks = response.data
                    })
                }
                ,
                getDriver() {
                    this.driver_error = ''
                    this.driver = {}
                    this.waybill_driver_id = ''
                    $.ajax({
                        type: 'GET',
                        data: {truck_id: this.truck_id},
                        url: '{{ route("api.waybill.truck.driver") }}'
                    }).then(response => {
                        if (response.status == 500) {
                            this.driver_error = 'لا يوجد سائق للشاحنه'
                        } else {
                            this.driver = response.data
                            this.waybill_driver_id = response.data.emp_id
                        }

                    })
                }
                ,
                getDiscountTypeByCompany() {
                    this.discount_flag = false
                    if (this.waybill_payment_method == 54002) {
                        $.ajax({
                            type: 'GET',
                            url: '{{ route("Waybill.getDiscountTypeByCompany") }}'
                        }).then(response => {
                            this.discount_flag = response.data
                        });
                    }
                },

                getcustomertype() {
                    this.waybill_item_price = '',
                        this.waybill_add_amount = 0,
                        this.bank_list = false
                    this.waybill_ticket_no_required = false
                    this.error_message = ''
                    this.waybill_payment_method_valid = false
                    this.waybill_paid_amount = 0
                    this.lock_data_538 = false
                    this.waybill_car_chase = 0
                    this.waybill_car_plate = 0

                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.customer_id},
                        url: '{{ route("api.waybill.customer.type") }}'
                    }).then(response => {
                        this.customer_type_ar = response.data.system_code_name_ar
                        this.customer_type_en = response.data.system_code_name_en
                        this.customer_discount_rate = response.customer_discount_rate


                        this.paid_disabled = false

                        this.customer_type_obj = response.data
                        this.waybill_item_vat_rate = response.customer_vat_rate
                        //شركات
                        if (response.data.system_code == 539) {
                            this.customer_name = response.customer_name
                            this.customer_mobile = response.customer_mobile
                            this.customer_identity = response.customer_identity
                            this.waybill_car_owner = response.customer_name

                            this.waybill_ticket_no_required = false
                            this.waybill_payment_method = 54003
                            this.waybill_payment_method_valid = true
                            this.show_payment_terms = false

                            if (this.waybill_payment_method == 54003) {
                                this.waybill_paid_amount = 0
                                this.paid_disabled = true
                            }
                            if (this.waybill_item_price > 0) {
                                if (this.waybill_status == 41001 && this.waybill_payment_method == 54001) {
                                    if (this.waybill_paid_amount != this.waybill_total_amount) {
                                        this.disable_button = true
                                        this.error_message = 'المسدد لا يساوي ااجمالي'
                                    } else {
                                        this.disable_button = false
                                        this.error_messagess = ''
                                    }
                                }


                            }
                        } else {

                            if (response.customer_mobile.length == 10 || response.customer_identity.length == 10) {
                                this.customer_name = response.customer_name
                                this.customer_mobile = response.customer_mobile
                                this.customer_identity = response.customer_identity
                                this.waybill_car_owner = response.customer_name
                            } else {
                                this.lock_data_538 = true
                                this.customer_name = ''
                                this.customer_mobile = ''
                                this.customer_identity = ''
                                this.waybill_car_owner = ''
                            }

                            this.show_payment_terms = true
                            this.waybill_payment_method = 54001
                            this.waybill_payment_terms = 57001
                            this.bank_id = ''
                            this.bank_list = true
                            // if(this.waybill_payment_method != )
                            this.error_message = 'المسدد لا يساوي ااجمالي'
                        }
                    })
                }
                ,
                getCountWaybillsDaily() {
                    if (this.waybill_item_id == 64001 || this.waybill_item_id == 64002 || this.waybill_item_id == 64003
                        || this.waybill_item_id == 64004 || this.waybill_item_id == 64007) {
                        //  this.waybill_item_price = 0,
                        //  this.waybill_discount_total = 0,
                        //  this.waybill_add_amount = 0,
                        // this.waybill_paid_amount = 0,
                        this.count = 0
                        this.waybill_driver_id = ''
                        this.driver = {}
                        this.truck_id = ''
                        this.driver_error = ''
                    }

                    if (this.waybill_item_id == 64006 && this.truck_id && this.customer_id && this.waybill_load_date
                        && this.waybill_loc_from && this.waybill_loc_to) {
                        // this.waybill_loc_from = ''
                        //  this.waybill_loc_to = ''
                        //  this.waybill_item_price = 0
                        //  this.waybill_discount_total = 0
                        //  this.waybill_add_amount = 0
                        //  this.waybill_paid_amount = 0
                        this.count = 0
                        this.driver_error = ''
                        this.price_error = ''
                        this.waybill_fees_load = 0


                        $.ajax({
                            type: 'GET',
                            data: {
                                // waybill_driver_id: this.waybill_driver_id,
                                truck_id: this.truck_id,
                                waybill_load_date: this.waybill_load_date,
                                customer_id: this.customer_id,
                                waybill_loc_to: this.waybill_loc_to,
                                waybill_loc_from: this.waybill_loc_from
                            },

                            url: '{{ route("api.waybill.getCountWaybillsDaily") }}'
                        }).then(response => {
                            if (response.status == 500) {
                                if (response.driver_message) {
                                    this.driver_error = response.driver_message
                                }
                                if (response.price_message) {
                                    this.price_error = response.price_message
                                }
                            } else {
                                this.count = parseInt(response.data) + 1
                                this.waybill_fees_load = parseInt(response.waybill_fees_load)
                            }

                        })
                    }

                }
                ,
                getSenderInfo() {
                    this.customer_name = ''
                    this.customer_mobile = ''
                    this.waybill_car_chase = 0
                    this.waybill_car_plate = 0
                    this.waybill_car_desc = ''
                    this.waybill_car_model = ''
                    this.waybill_car_owner = ''
                    this.waybill_car_color = ''
                    this.sender_disable_button = false
                    this.sender_block_flag = 0

                    $.ajax({
                        type: 'GET',
                        data: {
                            sender_id: this.customer_identity,
                            customer_mobile: this.waybill_sender_mobile,
                        },
                        url: '{{ route("car-getSenderInfo") }}'
                    }).then(response => {

                        this.customer_name = response.sender_info.waybill_sender_name
                        this.customer_mobile = response.sender_info.waybill_sender_mobile

                        this.waybill_car_chase = response.sender_car.waybill_car_chase
                        this.waybill_car_plate = response.sender_car.waybill_car_plate

                        if (this.waybill_car_plate) {
                            this.getWaybillByPlateNumber();
                        }

                        this.waybill_car_desc = response.sender_car.waybill_car_desc
                        this.waybill_car_model = response.sender_car.waybill_car_model
                        this.waybill_car_owner = response.sender_car.waybill_car_owner
                        this.waybill_car_color = response.sender_car.waybill_car_color
                        this.sender_block_flag = response.sender_block_flag


                        if (this.sender_block_flag == 1) {
                            this.sender_disable_button = true
                            this.sender_message = 'هذا العميل محظور'
                        } else {
                            this.sender_disable_button = false
                            this.sender_message = ''
                        }

                    })


                }
                ,
                getContractsList() {
                    this.contract_error = ''
                    this.customer_contract = ''
                    this.contracts_list = {}
                    $.ajax({
                        type: 'GET',
                        data: {
                            customer_id: this.customer_id,
                        },
                        url: '{{ route("car-getContractsList") }}'
                    }).then(response => {
                        if (response.status == 500) {
                            this.contract_error = 'لا يوجد عقود متوفره للعميل'
                            this.disable_button = true
                        } else {
                            this.contracts_list = response.contracts_list
                            this.customer_contract = response.contracts_first
                            this.disable_button = false
                        }

                    })

                }
                ,
                getPriceList() {
                    this.waybill_item_price = 0
                    this.waybill_fees_difference = 0
                    this.error_messagess = ''
                    this.disable_button = false
                    this.price_factor = 0

                    if (this.customer_contract && this.waybill_loc_to && this.waybill_loc_from && this.waybill_item_id) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                waybill_loc_from: this.waybill_loc_from,
                                waybill_loc_to: this.waybill_loc_to,
                                price_list_id: this.customer_contract,
                                item_id: this.waybill_item_id,
                            },
                            url: '{{ route("car-getprice") }}'
                        }).then(response => {
                            this.waybill_item_price = response.max_fees
                            this.waybill_fees_difference = response.max_fees - response.min_fees
                            this.waybill_distance = response.distance
                            this.price_factor = response.price_factor

                            if (this.waybill_item_price > 0) {
                                if (this.waybill_status == 41004 && this.waybill_payment_method == 54001) {
                                    if (parseFloat(this.waybill_paid_amount) != parseFloat(this.waybill_total_amount)) {
                                        this.disable_button = true
                                        this.error_message = 'المسدد لا يساوي ااجمالي'
                                    } else {
                                        this.disable_button = false
                                        this.error_messagess = ''
                                    }
                                } else {
                                    this.disable_button = false
                                    this.error_messagess = ''
                                }

                            } else if (this.waybill_item_price <= 0) {

                                this.disable_button = true
                                this.error_messagess = 'يجب تحديد سعر الشحن'
                            }

                        })
                    }
                }
                ,
                getBrandDetails() {
                    if (this.brand_dt_id.text) {
                        this.waybill_car_desc = this.brand_id + ' ' + this.brand_dt_id.text
                    } else {
                        this.waybill_car_desc = this.brand_id
                    }

                    $.ajax({
                        type: 'GET',
                        data: {
                            brand_id: this.brand_id,
                        },
                        url: '{{ route("car-getBrandDetails") }}'
                    }).then(response => {
                        this.brand_dts = response.data
                    });
                }
                ,
                getBrandDtName() {
                    this.waybill_car_desc = this.brand_id

                },
                getBrandDtSize() {
                    this.waybill_car_desc = this.brand_id + ' ' + this.brand_dt_id.text

                    if (this.brand_id) {
                        this.waybill_car_desc = this.brand_id + ' ' + this.brand_dt_id.text
                    } else {
                        this.waybill_car_desc = this.brand_dt_id.text
                    }

                    $.ajax({
                        type: 'GET',
                        data: {
                            brand_dt_id: this.brand_dt_id.id,
                        },
                        url: '{{ route("car-getBrandDetailsCarSize") }}'
                    }).then(response => {
                        this.waybill_item_id = response.data
                    });
                },
                addDaysToDate() {
                    if (this.days_count && this.waybill_load_date) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                date: this.waybill_load_date,
                                days_count: this.days_count,
                            },
                            url: '{{ route("car-addDaysToDate") }}'
                        }).then(response => {
                            this.waybill_delivery_expected = response.data
                        });
                    }
                },
                getWaybillByPlateNumber() {
                    this.car_plate_message = ''
                    $.ajax({
                        type: 'GET',
                        data: {
                            waybill_car_plate: this.waybill_car_plate,
                        },
                        url: '{{ route("car-checkWaybillByPlateNo") }}'
                    }).then(response => {
                        if (response.error) {
                            this.car_plate_message = response.error
                        } else {
                            this.car_plate_message = ''
                        }

                    });
                },
                getColorA() {
                    var y;
                    var x;
                    for (y = 0; y < this.color_list.length; y++) {
                        if (!this.color_list[y]) {
                            this.color_list[y] = this.waybill_car_color;
                        }
                    }

                    for (x = 0; x < this.type_list.length; x++) {
                        if (!this.type_list[x]) {
                            this.type_list[x] = this.waybill_car_desc;
                        }
                    }

                },
                checkChaseDuplicate(index) {
                    // if (this.waybill_car_chase_arr.indexOf(this.waybill_car_chase_arr[index]) != -1) {
                    //     console.log('7')
                    // }
                    this.chase_error = ''
                    var count = 0;
                    for (var k = 0; k < this.waybill_car_chase_arr.length; k++) {
                        if (k != index) {
                            if (this.waybill_car_chase_arr[index] == this.waybill_car_chase_arr[k] || this.waybill_car_chase_arr[index] == '') {
                                count += 1;
                            }
                        }
                    }

                    if (count == 0) {
                        this.chase_error = ''
                    } else {
                        this.chase_error = 'يوجد رقم مكرر او قيمه غير صحيحه'
                    }

                },

            },
            computed:
                {
                    waybill_discount_total_customer: function () {
                        if (this.customer_discount_rate > 0) {
                            var x = parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price2)
                            return x * this.customer_discount_rate;
                        } else {
                            return 0;
                        }
                    },
                    disable_button_2: function () {
                        if (parseFloat(this.waybill_discount_total) > parseFloat(this.waybill_fees_difference) || parseFloat(this.waybill_discount_total) < 0) {
                            return true;
                        } else {
                            return false;
                        }

                    },
                    waybill_return_s: function () {
                        if (this.waybill_item_id == 64005 || this.waybill_item_id == 64006) {
                            return false;
                        } else {
                            return true;
                        }
                    }
                    ,
                    waybill_item_price_d: function () {
                        if (this.waybill_return == 1) {
                            return this.waybill_item_price;
                        }
                        if (this.waybill_return == 2) {
                            return this.waybill_item_price * this.price_factor
                        }
                    }
                    ,
                    waybill_item_price2: function () {
                        var q = parseFloat(this.waybill_item_price_d)
                        return q.toFixed(2)
                    }
                    ,

                    waybill_item_vat_amount: function () {
                        if (this.customer_discount_rate > 0) {
                            var x = parseFloat(this.waybill_item_vat_rate) * ((parseFloat(this.waybill_item_quantity) *
                                parseFloat(this.waybill_item_price2)) + parseFloat(this.waybill_add_amount) - parseFloat(this.waybill_discount_total_customer))
                        } else {
                            var x = parseFloat(this.waybill_item_vat_rate) * ((parseFloat(this.waybill_item_quantity) *
                                parseFloat(this.waybill_item_price2)) + parseFloat(this.waybill_add_amount) - parseFloat(this.waybill_discount_total))
                        }

                        return x.toFixed(2)
                    }
                    ,
                    waybill_sub_total_amount: function () {
                        if (this.customer_discount_rate > 0) {
                            var y = parseFloat(this.waybill_add_amount)
                                + (parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price2))
                                - parseFloat(this.waybill_discount_total_customer)
                        } else {
                            var y = parseFloat(this.waybill_add_amount)
                                + (parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price2))
                                - parseFloat(this.waybill_discount_total)
                        }

                        return y.toFixed(2)
                    }
                    ,
                    waybill_item_quantity: function () {
                        if (this.waybill_qut_received_customer) {
                            return this.waybill_qut_received_customer
                        }
                        else if (this.waybill_qut_requried_customer) {
                            return this.waybill_qut_requried_customer
                        }
                    }
                    ,
                    waybill_total_fees_amount: function () {
                        var a = parseFloat(this.waybill_fees_wait) +
                            parseFloat(this.waybill_fees_load) + parseFloat(this.waybill_fees_difference);
                        var t = (parseFloat(this.waybill_item_vat_rate)) * a + a
                        return t.toFixed(2)
                    }
                    ,
                    waybill_total_amount: function () {
                        var z = parseFloat(this.waybill_item_vat_amount) + parseFloat(this.waybill_sub_total_amount)
                        return z.toFixed(2)
                    }
                    ,
                    waybill_due_amount: function () {
                        var r = parseFloat(this.waybill_item_vat_amount) + parseFloat(this.waybill_sub_total_amount)
                            - parseFloat(this.waybill_paid_amount)
                        return r.toFixed(2)
                    }
                    ,

                    //supplier
                    qut_actual: function () {
                        if (this.waybill_qut_received_supplier) {
                            return this.waybill_qut_received_supplier
                        } else if (this.waybill_qut_requried_supplier) {
                            return this.waybill_qut_requried_supplier
                        }
                    }
                    ,
                    waybill_vat_amount_supplier: function () {
                        var x1 = (parseFloat(this.waybill_item_vat_rate)) * parseFloat(this.waybill_price_supplier) *
                            parseFloat(this.qut_actual)
                        return x1.toFixed(2)
                    }
                    ,
                    waybill_amount_supplier: function () {
                        var y1 = parseFloat(this.waybill_vat_amount_supplier) +
                            (parseFloat(this.qut_actual) * parseFloat(this.waybill_price_supplier))
                        return y1.toFixed(2)
                    }
                    ,
                    mobile_message_s: function () {
                        //sender
                        if (this.customer_mobile.length != 10) {
                            return 'الرقم يجب ان يكون 10 ارقام'
                        } else {
                            return ''
                        }
                    },
                    identity_message_s: function () {
                        if (this.customer_identity.length != 10) {
                            return 'الرقم يجب ان يكون 10 ارقام'
                        } else {
                            return ''
                        }
                    },
                    mobile_message_r: function () {
                        //receiver
                        if (this.receiver_mobile.length != 10) {
                            return 'الرقم يجب ان يكون 10 ارقام'
                        } else {
                            return ''
                        }
                    },
                    identity_message_r: function () {
                        if (this.receiver_identity.length != 10) {
                            return 'الرقم يجب ان يكون 10 ارقام'
                        } else {
                            return ''
                        }
                    },
                    disable_button2: function () {
                        if (this.mobile_message_s || this.identity_message_s || this.mobile_message_r
                            || this.identity_message_r || this.waybill_item_price_d <= 0 || !this.customer_contract
                            || !this.waybill_load_date || !this.waybill_delivery_expected) {
                            // this.error_messagess = 'الرقم يجب ان يكون 10 ارقام'
                            return true
                        } else {
                            // this.error_messagess = ''
                            return false
                        }

                    },

                    lock_data_car_model: function () {
                        if (this.waybill_car_model.length != 4) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                    cars_count: function () {
                        this.type_list.length = this.waybill_qut_received_customer;
                        this.color_list.length = this.waybill_qut_received_customer;
                        this.waybill_car_chase_arr.length = this.waybill_qut_received_customer;
                        return parseInt(this.waybill_qut_received_customer);
                    },
                }
        })
    </script>

@endsection




