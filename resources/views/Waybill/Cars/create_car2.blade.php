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
        <div class="container-fluid">


            <div class="tab-content mt-6">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                    <div class="card" v-if="update_form">
                        <div class="section-body">
                            <div class="container-fluid">
                                <div class="d-flex justify-content-between align-items-center">
                                    <ul class="nav nav-tabs page-header-tab">
                                        <li class="nav-item">
                                            <a href="#form-grid" data-toggle="tab"
                                               class="nav-link active">@lang('home.update_form')</a>
                                        </li>

                                        <li class="nav-item"><a class="nav-link" href="#files-grid"
                                                                style="font-size: 18px ;font-weight: bold"
                                                                data-toggle="tab">@lang('home.files')</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                                style="font-size: 18px ;font-weight: bold"
                                                                data-toggle="tab">@lang('home.notes')</a></li>
                                    </ul>
                                    <div class="header-action"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-content mt-3">
                        {{--update form part--}}
                        <div class="tab-pane fade show active" id="form-grid" role="tabpanel">
                            {{-- Form To Create Waybill--}}
                            <form class="card" id="validate-form"
                                  action="{{ route('Waybill.update_car',$waybill_hd->waybill_id) }}?qr=create_2"
                                  method="post" enctype="multipart/form-data" id="form" v-if="update_form">
                                @csrf
                                @method('put')

                                <h1>@lang('home.update_form')</h1>

                                <div class="card-body">
                                    {{--inputs data--}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" disabled=""
                                                           style="font-size: 16px ;font-weight: bold"
                                                           value="{{$waybill_hd->waybill_code}}" v-if="waybill_code">
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-3">
                                                    {{-- حاله الشحنه --}}
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_status') </label>
                                                    <select class="form-select form-control" name="waybill_status"
                                                            id="waybill_status" disabled="">
                                                        @foreach($sys_codes_waybill_status as $sys_code_waybill_status)
                                                            <option value="{{$sys_code_waybill_status->system_code}}"
                                                                    @if($waybill_hd->status->system_code == $sys_code_waybill_status->system_code)
                                                                    selected @endif>
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{$sys_code_waybill_status->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_waybill_status->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label hidden for="recipient"
                                                           class="form-label"> @lang('trucks.sub_company') </label>
                                                    <select hidden class="form-select form-control"
                                                            name="company_id" id="company_id"
                                                            v-model="company_id" readonly="">
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
                                                {{--اسم المورد--}}
                                                <div hidden class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="form-label"
                                                               style="text-decoration: underline;"> @lang('trucks.truck_supplier') </label>
                                                        <select class="form-select form-control" name="supplier_id"
                                                                id="supplier_id" readonly="">
                                                            <option value="" selected>@lang('home.choose')</option>
                                                            @foreach($suppliers as $supplier)
                                                                <option value="{{$supplier->customer_id }}">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        {{ $supplier->customer_name_full_ar }}
                                                                    @else
                                                                        {{ $supplier->customer_name_full_en }}
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- العميل--}}
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"
                                                           style="text-decoration: underline;"> @lang('waybill.customer_name') </label>
                                                    <select class="form-select form-control" data-live-search="true"
                                                            name="customer_id" id="customer_id" disabled=""
                                                            @change="getcustomertype()" v-model="customer_id">
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


                                                {{-- رقم العقد للعميل--}}
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.customer_contract') </label>
                                                    <input type="text" class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           autocomplete="off" readonly=""
                                                           name="customer_contract" id="customer_contract"
                                                           value="{{ $waybill_hd->customer_contract }}">
                                                </div>

                                                {{-- رقم التذكره--}}
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.approved_no') </label>
                                                    <input type="text" class="form-control"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           autocomplete="off" readonly=""
                                                           name="waybill_ticket_no" id="waybill_ticket_no"
                                                           value="{{ $waybill_hd->waybill_ticket_no }}">
                                                </div>


                                                {{-- نوع  للعميل--}}

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('waybill.customer_type')</label>

                                                    @if(app()->getLocale() == 'ar')
                                                        <input type="text" class="form-control" readonly
                                                               name="customer_type"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               :value="customer_type_ar">
                                                    @else
                                                        <input type="text" class="form-control" readonly
                                                               :value="customer_type_en">
                                                    @endif

                                                </div>
                                            </div>


                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.transport_type') </label>
                                                    <select class="form-select form-control waybill_item_id"
                                                            name="waybill_item_id" id="waybill_item_id"
                                                            v-model="waybill_item_id" disabled="">
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

                                                {{--محطه الشحن--}}
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"
                                                           style="text-decoration: underline;"> @lang('waybill.loc_car_from') </label>
                                                    <select class="form-select form-control" name="waybill_loc_from"
                                                            id="waybill_loc_from" @change="getPriceList()"
                                                            v-model="waybill_loc_from" disabled="">
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

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"
                                                           style="text-decoration: underline;"> @lang('waybill.loc_car_to') </label>
                                                    <select class="form-select form-control" name="waybill_loc_to"
                                                            id="waybill_loc_to" @change="getPriceList()"
                                                            v-model="waybill_loc_to" disabled="">
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

                                                {{--تاريخ التحميل--}}
                                                <div class="col-md-3">
                                                    <label for="recipient"
                                                           class="form-label"> @lang('waybill.waybill_date_loaded') </label>
                                                    <input type="datetime-local" class="form-control" readonly
                                                           name="waybill_load_date" id="waybill_date_loaded"
                                                           style="font-size: 16px ;font-weight: bold"
                                                           value="{{$waybill_hd->waybill_load_date}}">
                                                </div>

                                                {{--الكميه المطلوبه--}}
                                                <div hidden class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_qut_request') </label>
                                                    <input type="text" class="form-control"
                                                           v-model="waybill_qut_requried_supplier"
                                                           name="waybill_qut_requried_supplier"
                                                           id="waybill_qut_requried_supplier" readonly
                                                           placeholder="@lang('waybill.waybill_qut_request')">
                                                </div>

                                                {{--الكميه المشحونه--}}
                                                <div hidden class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_qut') </label>
                                                    <input type="text" class="form-control"
                                                           name="waybill_qut_received_supplier" readonly=""
                                                           v-model="waybill_qut_received_supplier"
                                                           id="waybill_qut_received_supplier"
                                                           placeholder="@lang('waybill.waybill_qut')">
                                                </div>

                                                {{-- - تاريخ وصول الرحله--}}
                                                <div hidden class="col-md-3">
                                                    <label for="recipient"
                                                           class="form-label"> @lang('waybill.waybill_date_receved') </label>
                                                    <input type="datetime-local" class="form-control"
                                                           name="waybill_unload_date"
                                                           id="waybill_unload_date" readonly=""
                                                           placeholder="@lang('waybill.waybill_date_receved')">
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{--الكميه--}}
                                                <div hidden class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_qut_actual') </label>
                                                    <input type="number" class="form-control" readonly step="0.01"
                                                           v-model="qut_actual" id="waybill_item_quantity_supplier"
                                                           placeholder="@lang('waybill.waybill_qut_actual')">
                                                </div>

                                                <div hidden class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybil_item_unit') </label>
                                                    <select class="form-select form-control is-invalid"
                                                            name="waybill_item_unit"
                                                            id="waybill_item_unit" readonly="">
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
                                                {{--سعر الوحده للمورد--}}
                                                <div hidden class="col-md-1">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_price') </label>
                                                    <input type="number" class="form-control"
                                                           v-model="waybill_price_supplier"
                                                           name="waybill_price_supplier" id="waybill_price_supplier"
                                                           placeholder="@lang('waybill.waybill_price')" step="0.0001"
                                                           readonly>
                                                </div>

                                                {{--نسبه الضريبه--}}
                                                <div hidden class="col-md-1">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_vat') </label>
                                                    <input type="number" class="form-control" step="0.01"
                                                           id="waybill_item_vat_rate_supplier" readonly
                                                           name="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                           placeholder="@lang('waybill.waybill_vat')">

                                                </div>

                                                <div hidden class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_vat_amount') </label>
                                                    <input type="number" class="form-control" step="0.01"
                                                           name="waybill_vat_amount_supplier" readonly
                                                           v-model="waybill_vat_amount_supplier"
                                                           placeholder="@lang('waybill.waybill_vat_amount')">

                                                </div>

                                                <div hidden class="col-md-2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.waybill_total') </label>
                                                    <input type="number" class="form-control" readonly step="0.01"
                                                           name="waybill_amount_supplier"
                                                           v-model="waybill_amount_supplier"
                                                           placeholder="@lang('waybill.waybill_total')">

                                                </div>

                                            </div>


                                            <div class="card bline" style="color:red">
                                            </div>


                                            {{--العميل--}}
                                            <div class="row">

                                                <div class="col-md-6 col-lg-6">
                                                    <div class="card">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>ذهاب</label>
                                                                <input type="radio" value="1" name="radio"
                                                                       v-model="waybill_return" disabled>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label> ذهاب وعوده</label>
                                                                <input type="radio" value="2" name="radio"
                                                                       v-model="waybill_return" disabled>
                                                            </div>

                                                            <input type="hidden" v-model="waybill_return"
                                                                   name="waybill_return">

                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_chase') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_chase"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       id="waybill_car_chase"
                                                                       @change="getWaybillByPlateNumber2()"
                                                                       v-model="waybill_car_chase"
                                                                       required>
                                                                <small style="color:red;font-weight: bold"
                                                                       v-if="car_chase_message">@{{
                                                                    car_chase_message }}
                                                                </small>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_plate') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_plate"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       @change="getWaybillByPlateNumber()"
                                                                       id="waybill_car_plate"
                                                                       v-model="waybill_car_plate"
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
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       name="waybill_car_desc" id="waybill_car_desc"
                                                                       value="{{$waybill_dt->waybill_car_desc}}"
                                                                       required>

                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_owner') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_owner"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       id="waybill_car_owner"
                                                                       value="{{$waybill_dt->waybill_car_owner}}"
                                                                       required>

                                                            </div>


                                                        </div>

                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_color') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_color"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       id="waybill_car_color"
                                                                       value="{{$waybill_dt->waybill_car_color}}"
                                                                       required>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="recipient-name"
                                                                       class="form-label"> @lang('waybill.car_model') </label>
                                                                <input type="text" class="form-control is-invalid"
                                                                       name="waybill_car_model"
                                                                       style="font-size: 16px ;font-weight: bold"
                                                                       id="waybill_car_model"
                                                                       value="{{$waybill_dt->waybill_car_model}}"
                                                                       required>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="card">
                                                        <div class="card">
                                                            {{--رقم الهويه للشاحن والمستلم--}}
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="recipient-name"
                                                                           class="form-label"> @lang('home.sender_identity') </label>
                                                                    <input type="text" class="form-control is-invalid"
                                                                           name="waybill_sender_mobile_code"
                                                                           style="font-size: 16px ;font-weight: bold"
                                                                           id="waybill_sender_mobile_code_r"
                                                                           v-model="customer_identity"
                                                                           @keyup="validateInputs()"
                                                                           placeholder="@lang('home.sender_identity')"
                                                                           required>

                                                                    <small v-if="identity_message_s"
                                                                           class="text-danger">@{{
                                                                        identity_message_s }}
                                                                    </small>


                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="recipient-name"
                                                                           class="form-label"> @lang('home.receiver_identity') </label>
                                                                    <input type="text" class="form-control is-invalid"
                                                                           name="waybill_receiver_mobile_code"
                                                                           style="font-size: 16px ;font-weight: bold"
                                                                           id="waybill_receiver_mobile_code_r"
                                                                           v-model="receiver_identity"
                                                                           @keyup="validateInputs()"
                                                                           placeholder="@lang('home.receiver_identity')"
                                                                           required>

                                                                    <small v-if="identity_message_r"
                                                                           class="text-danger">@{{
                                                                        identity_message_r }}
                                                                    </small>

                                                                </div>

                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="recipient-name"
                                                                           class="form-label"> @lang('waybill.sender_p_mobile') </label>
                                                                    <input type="text" class="form-control is-invalid"
                                                                           name="waybill_sender_mobile"
                                                                           style="font-size: 16px ;font-weight: bold"
                                                                           @keyup="validateInputs()"
                                                                           id="waybill_sender_mobile_r"
                                                                           v-model="customer_mobile"
                                                                           required>
                                                                    <small v-if="mobile_message_s" class="text-danger">
                                                                        @{{
                                                                        mobile_message_s }}
                                                                    </small>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <label for="recipient-name"
                                                                           class="form-label"> @lang('waybill.receiver_p_mobile') </label>
                                                                    <input type="text" class="form-control is-invalid"
                                                                           name="waybill_receiver_mobile"
                                                                           style="font-size: 16px ;font-weight: bold"
                                                                           v-model="receiver_mobile"
                                                                           id="waybill_receiver_mobile_r"
                                                                           @keyup="validateInputs()"
                                                                           required>
                                                                    <small v-if="mobile_message_r" class="text-danger">
                                                                        @{{
                                                                        mobile_message_r }}
                                                                    </small>

                                                                </div>


                                                            </div>

                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    <label for="recipient-name"
                                                                           class="form-label"> @lang('waybill.sender_name') </label>
                                                                    <input type="text" class="form-control is-invalid"
                                                                           name="waybill_sender_name"
                                                                           style="font-size: 16px ;font-weight: bold"
                                                                           id="waybill_sender_name"
                                                                           :value="customer_name"
                                                                           placeholder="@lang('waybill.sender_name')"
                                                                           required>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="recipient-name"
                                                                           class="form-label"> @lang('waybill.receiver_name') </label>
                                                                    <input type="text" class="form-control is-invalid"
                                                                           name="waybill_receiver_name"
                                                                           style="font-size: 16px ;font-weight: bold"
                                                                           id="waybill_receiver_name"
                                                                           value="{{$waybill_hd->waybill_receiver_name}}"
                                                                           required>

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
                                                        <select class="form-select form-control"
                                                                name="waybill_payment_method"
                                                                style="font-size: 16px ;font-weight: bold"
                                                                id="waybill_payment_method" required
                                                                v-model="waybill_payment_method"
                                                                disabled="">
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


                                                    </div>


                                                    {{--الكميه المطلوبه للعميل--}}
                                                    <div hidden class="col-md-3">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_qut_request') </label>
                                                        <input type="text" class="form-control"
                                                               name="waybill_qut_requried_customer"
                                                               id="waybill_qut_requried_customer" readonly=""
                                                               v-model="waybill_qut_requried_customer"
                                                               placeholder="@lang('waybill.waybill_qut_request')">
                                                    </div>

                                                    {{--الكميه المستلمه--}}
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.car_no') </label>
                                                        <input type="text" class="form-control"
                                                               name="waybill_qut_received_customer"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_qut_received_customer" readonly=""
                                                               v-model="waybill_qut_received_customer"
                                                               placeholder="@lang('waybill.car_no')">
                                                    </div>


                                                    {{--سعر الوحده--}}
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_price') </label>
                                                        <input type="number" class="form-control" readonly=""
                                                               name="waybill_item_price" v-model="waybill_item_price_d"
                                                               id="waybill_item_price"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               placeholder="@lang('waybill.waybill_price')">
                                                    </div>


                                                    {{--سعر الاضافات--}}
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.add_amount') </label>
                                                        <input type="number" class="form-control" step="0.01"
                                                               readonly=""
                                                               name="waybill_add_amount" v-model="waybill_add_amount"
                                                               id="waybill_add_amount" @keyup="validatePaid()" min="0"
                                                               @change="validateadd()"
                                                               style="font-size: 16px ;font-weight: bold">
                                                    </div>


                                                    {{--سعر الخصومات--}}
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.disc_amount') </label>
                                                        <input type="number" class="form-control" step="0.01"
                                                               name="waybill_discount_total"
                                                               v-model="waybill_discount_total"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_discount_total" readonly="">
                                                    </div>


                                                    {{--الاجمالي قبل الضريبه --}}

                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.total') </label>
                                                        <input type="number" class="form-control" readonly
                                                               v-model="waybill_sub_total_amount"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               name="waybill_sub_total_amount"
                                                               placeholder="@lang('waybill.total')" step="0.01">

                                                    </div>


                                                    {{--تاريخ التسليم--}}
                                                    <div hidden class="col-md-3">
                                                        <label for="recipient"
                                                               class="form-label"> @lang('waybill.waybill_date_end') </label>
                                                        <input type="datetime-local" class="form-control"
                                                               name="waybill_delivery_date"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_delivery_date" readonly=""
                                                               value="{{$waybill_hd->waybill_delivery_date}}">
                                                    </div>
                                                </div>


                                                <div class="row">


                                                    {{--الضريبه--}}
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_vat') </label>
                                                        <input type="number" class="form-control"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               name="waybill_vat_rate" step="0.01"
                                                               id="waybill_item_vat_rate"
                                                               v-model="waybill_item_vat_rate"
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

                                                    {{--تاريخ الوصول المتوقع--}}
                                                    <div class="col-md-6">
                                                        <label for="recipient"
                                                               class="form-label"> @lang('waybill.waybill_date_expected') </label>
                                                        <input type="datetime-local" class="form-control"
                                                               name="waybill_delivery_expected"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_delivery_expected" readonly=""
                                                               value="{{$waybill_hd->waybill_delivery_expected}}">
                                                    </div>

                                                    {{--طريقه السداد--}}
                                                    <div class="col-md-2" v-if="show_payment_terms">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.pay_type') </label>
                                                        <select class="form-select form-control"
                                                                name="waybill_payment_terms"
                                                                id="waybill_payment_terms"
                                                                v-model="waybill_payment_terms">

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


                                                    {{--البنك--}}
                                                    {{--<div class="col-sm-6 col-md-4">--}}
                                                    {{--<div class="form-group">--}}
                                                    {{--<label class="form-label">@lang('home.bank')</label>--}}
                                                    {{--<select class="form-control" name="bank_id"--}}
                                                    {{--:disabled="bank_valid">--}}
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

                                                    {{--الكميه--}}


                                                    <div hidden class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_qut_actual') </label>
                                                        <input type="number" class="form-control"
                                                               v-model="waybill_item_quantity"
                                                               name="waybill_item_quantity" id="waybill_item_quantity"
                                                               readonly
                                                               placeholder="@lang('waybill.waybill_qut_actual')"
                                                               step="0.01">

                                                    </div>

                                                    {{--المسدد --}}
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.payment_amount') @{{
                                                            waybill_paid_amount }} </label>
                                                        <input type="number" class="form-control" step="0.0001"
                                                               @keyup="validatePaid()"
                                                               name="new_waybill_paid_amount"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               v-model="new_waybill_paid_amount"
                                                               id="new_waybill_paid_amount"
                                                               :disabled="validate_paid_amount"
                                                               placeholder="@lang('waybill.payment_amount')">
                                                        <small class="text-danger" v-if="new_error_message">@{{
                                                            new_error_message }}
                                                        </small>
                                                    </div>

                                                    {{--اجمالي   hلمتبقي--}}
                                                    <div class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.net_amount') </label>
                                                        <input type="number" class="form-control" readonly
                                                               v-model="new_waybill_due_amount" step="0.01"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               name="waybill_due_amount" id="waybill_due_amount"
                                                               placeholder="@lang('waybill.net_amount')">

                                                    </div>
                                                    {{--ملاحظات --}}
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.customer_notes') </label>
                                                        <input type="text" class="form-control"
                                                               name="waybill_car_notes" autocomplete="off"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_car_notes"
                                                               v-model="waybill_car_notes"
                                                               placeholder="@lang('waybill.customer_notes')">

                                                    </div>

                                                </div>

                                                <div class="row">


                                                    {{--الشاحنه--}}
                                                    <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                                         @endif @endif  class="col-md-4" id="truck_data">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_truck') </label>
                                                        <select class="form-control" data-live-search="true" readonly=""
                                                                name="waybill_truck_id" id="waybill_truck_id"
                                                                @change="getDriver()" v-model="truck_id">

                                                            @foreach($trucks as $truck)
                                                                <option value="{{$truck->truck_id }}">
                                                                    {{ $truck->truck_code}} //
                                                                    {{ $truck->truck_name}} //
                                                                    {{ $truck->truck_plate_no}}
                                                                </option>
                                                            @endforeach


                                                        </select>
                                                    </div>


                                                    {{--السائق--}}
                                                    <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                                         @endif @endif   class="col-md-4" id="trip_driver">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_driver') </label>

                                                        <input type="hidden" name="waybill_driver_id"
                                                               v-model="waybill_driver_id">
                                                        @if(app()->getLocale() == 'ar')
                                                            <input type="text" class="form-control" readonly
                                                                   id="emp_name_full_ar"
                                                                   :value="driver.emp_name_full_ar">
                                                        @else
                                                            <input type="text" class="form-control" readonly
                                                                   :value="driver.emp_name_full_en"
                                                                   id="emp_name_full_en">
                                                        @endif
                                                    </div>

                                                    {{--عدد ردود السائق اليوميه--}}
                                                    <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                                         @endif @endif class="col-md-2" id="driver_rad">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.deriver_trip') </label>
                                                        <input type="number" class="form-control" readonly
                                                               v-model="count"
                                                               placeholder="@lang('waybill.deriver_trip')">
                                                    </div>


                                                    {{--اجره الطريق--}}
                                                    <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                                         @endif @endif   class="col-md-2" id="driver_fees">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.driver_car_fees') </label>
                                                        <input type="number" class="form-control"
                                                               v-model="waybill_fees_load"
                                                               name="waybill_fees_load" id="waybill_fees_load"
                                                               step="0.01"
                                                               placeholder="@lang('waybill.driver_car_fees')"
                                                               readonly="">

                                                    </div>


                                                </div>

                                                {{--اسعار الاضافات--}}
                                                <div class="row">

                                                    {{--اجره الانتظار--}}
                                                    <div hidden class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_fees_wait') </label>
                                                        <input type="number" class="form-control"
                                                               name="waybill_fees_wait"
                                                               id="waybill_fees_wait" v-model="waybill_fees_wait"
                                                               step="0.01"
                                                               placeholder="@lang('waybill.waybill_fees_wait')"
                                                               readonly="">

                                                    </div>

                                                    {{-- فروقات التحميل--}}
                                                    <div hidden class="col-md-2">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.waybill_differences') </label>
                                                        <input type="number" class="form-control"
                                                               name="waybill_fees_difference"
                                                               id="waybill_fees_difference"
                                                               v-model="waybill_fees_difference"
                                                               placeholder="@lang('waybill.waybill_differences')"
                                                               step="0.01"
                                                               readonly="">

                                                    </div>


                                                </div>


                                                <div class="card bline" style="color:red">
                                                </div>


                                                <button class="btn btn-primary" type="button" id="submit"
                                                        v-if="save_disabled"
                                                        onclick="confirmUpdate2()"
                                                        :disabled="new_disable_button || disable_button_3">
                                                    @lang('home.save')</button>

                                                <button class="btn btn-primary" type="button" id="submit"
                                                        :disabled="new_disable_button"
                                                        style="margin-right: 1px"
                                                        onclick="confirmUpdate2()"
                                                        v-if="edit_button">
                                                    @lang('home.edit')</button>

                                                <button class="btn btn-primary" type="button" v-if="button_duplicate"
                                                        @click="removeInputs()" id="duplicate"
                                                        style="margin-right: 1px">
                                                    @lang('home.duplicate')</button>
                                                @if($waybill_hd->detailsCar->waybill_item_quantity > 1)
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$waybill_hd->report_url_waybill_co->report_url}}&id={{$waybill_hd->waybill_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary"
                                                       style="display: inline-block; !important; margin-right: 1px"
                                                       id="print"
                                                       target="_blank">
                                                        @lang('home.print')</a>

                                                @else
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$waybill_hd->report_url_waybill->report_url}}&id={{$waybill_hd->waybill_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary"
                                                       style="display: inline-block; !important; margin-right: 1px"
                                                       id="print"
                                                       target="_blank">
                                                        @lang('home.print')</a>
                                                @endif

                                                <a href="{{ route('WaybillCar') }}" class="btn btn-primary"
                                                   type="submit" style="display: inline-block;margin-right: 1px"
                                                   id="back">
                                                    @lang('home.exit')</a>

                                                <a href="{{ route('Waybill.create_car') }}" class="btn btn-primary"
                                                   type="button" style="display: inline-block;margin-right: 1px"
                                                   id="back">
                                                    {{__('add new waybill')}}</a>

                                                <div class="spinner-border" role="status" style="display: none">
                                                    <span class="sr-only">Loading...</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="myModal2"
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
                                                <button type="submit" id="modal_button2"
                                                        class="btn btn-danger yes">@lang('home.yes')</button>
                                                <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">@lang('home.no')</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>

                        {{-- files part --}}
                        <div class="tab-pane fade" id="files-grid" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">

                                    <x-files.form>
                                        <input type="hidden" name="transaction_id"
                                               value="{{ $waybill_hd->waybill_id }}">
                                        <input type="hidden" name="app_menu_id" value="88">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>@lang('home.attachment_type')</label>
                                                <select class="form-control" name="attachment_type" required>
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($attachment_types as $attachment_type)
                                                        <option value="{{ $attachment_type->system_code_id }}">{{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
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
                                         $attachment->attachmentType_2->system_code_name_ar :
                                          $attachment->attachmentType_2->system_code_name_en}}</td>
                                                <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                                <td>{{ $attachment->issue_date_hijri }}</td>
                                                <td>{{ $attachment->expire_date_hijri }}</td>
                                                <td>{{ $attachment->copy_no }}</td>
                                                <td>
                                                    <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                        <i class="fa fa-download fa-2x"></i></a>
                                                    <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                                       target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-info"
                                                                                            style="font-size:20px"></i></a>
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
                        {{--end files part--}}


                        {{-- notes part --}}
                        <div class="tab-pane fade" id="notes-grid" role="tabpanel">

                            <div class="row">
                                <div class="col-lg-12">
                                    <x-files.form-notes>
                                        <input type="hidden" name="transaction_id"
                                               value="{{ $waybill_hd->waybill_id }}">
                                        <input type="hidden" name="app_menu_id" value="88">
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
                        {{--end notes part--}}
                    </div>

                    {{--repeat form part--}}
                    <form class="card" id="validate-form" action="{{ route('Waybill.store_car') }}"
                          method="post" enctype="multipart/form-data" id="form" v-if="repeat_form">
                        @csrf
                        <h1>تكرار بوليصه الشحن</h1>
                        <div class="card-body">
                            {{--inputs data--}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" disabled=""
                                                   value="{{$waybill_hd->waybill_code}}" v-if="waybill_code">
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">
                                            {{-- حاله الشحنه --}}
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_status') </label>
                                            <select class="form-select form-control" name="waybill_status"
                                                    id="waybill_status"
                                                    disabled="">
                                                @foreach($sys_codes_waybill_status as $sys_code_waybill_status)
                                                    <option value="{{$sys_code_waybill_status->system_code}}"
                                                            @if($waybill_hd->status->system_code == $sys_code_waybill_status->system_code)
                                                            selected @endif>
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_waybill_status->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_waybill_status->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
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
                                        {{--اسم المورد--}}
                                        <div hidden class="col-md-3">
                                            <div class="form-group">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> @lang('trucks.truck_supplier') </label>
                                                <select class="form-select form-control" name="supplier_id"
                                                        id="supplier_id">
                                                    <option value="" selected>@lang('home.choose')</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{$supplier->customer_id }}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $supplier->customer_name_full_ar }}
                                                            @else
                                                                {{ $supplier->customer_name_full_en }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- العميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.customer_name') </label>
                                            <select class="form-select form-control" data-live-search="true"
                                                    name="customer_id" id="customer_id" :value="waybill_hd.customer_id"
                                                    @change="getcustomertype();reValidAdditions()" disabled>
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


                                        {{-- رقم العقد للعميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.customer_contract') </label>
                                            <input type="text" class="form-control"
                                                   autocomplete="off" v-model="customer_contract"
                                                   name="customer_contract" id="customer_contract"
                                                   readonly="">


                                        </div>

                                        {{-- رقم التذكره--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.approved_no') </label>
                                            <input type="text" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   autocomplete="off"
                                                   name="waybill_ticket_no" id="waybill_ticket_no"
                                                   value="{{ $waybill_hd->waybill_ticket_no }}">
                                        </div>


                                        {{-- نوع  للعميل--}}

                                        <div class="col-md-3">
                                            <label class="form-label">@lang('waybill.customer_type')</label>

                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control" readonly name="customer_type"
                                                       :value="customer_type_ar">
                                            @else
                                                <input type="text" class="form-control" readonly
                                                       :value="customer_type_en">
                                            @endif

                                        </div>
                                    </div>


                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.transport_type') </label>
                                            <select class="form-select form-control waybill_item_id"
                                                    name="waybill_item_id" @change="getPriceList()"
                                                    id="waybill_item_id" required
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

                                        {{--محطه الشحن--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.loc_car_from') </label>
                                            <select class="form-select form-control" name="waybill_loc_from"
                                                    id="waybill_loc_from"
                                                    v-model="waybill_loc_from"
                                                    @change="getPriceList();reValidAdditions()">
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

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.loc_car_to') </label>
                                            <select class="form-select form-control" name="waybill_loc_to"
                                                    id="waybill_loc_to"
                                                    v-model="waybill_loc_to"
                                                    @change="getPriceList();reValidAdditions()">
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

                                        {{--تاريخ التحميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="form-label"> @lang('waybill.waybill_date_loaded') </label>
                                            <input type="datetime-local" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   name="waybill_load_date" id="waybill_date_loaded"
                                                   value="{{$waybill_hd->waybill_load_date}}">
                                        </div>

                                        {{--الكميه المطلوبه--}}
                                        <div hidden class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_qut_request') </label>
                                            <input type="text" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   v-model="waybill_qut_requried_supplier"
                                                   name="waybill_qut_requried_supplier"
                                                   id="waybill_qut_requried_supplier"
                                                   placeholder="@lang('waybill.waybill_qut_request')">
                                        </div>

                                        {{--الكميه المشحونه--}}
                                        <div hidden class="col-md-3">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_qut') </label>
                                            <input type="text" class="form-control"
                                                   name="waybill_qut_received_supplier"
                                                   v-model="waybill_qut_received_supplier"
                                                   id="waybill_qut_received_supplier"
                                                   placeholder="@lang('waybill.waybill_qut')">
                                        </div>

                                        {{-- - تاريخ وصول الرحله--}}
                                        <div hidden class="col-md-3">
                                            <label for="recipient"
                                                   class="form-label"> @lang('waybill.waybill_date_receved') </label>
                                            <input type="datetime-local" class="form-control" name="waybill_unload_date"
                                                   id="waybill_unload_date"
                                                   placeholder="@lang('waybill.waybill_date_receved')">
                                        </div>
                                    </div>

                                    <div class="row">

                                        {{--الكميه--}}
                                        <div hidden class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_qut_actual') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   v-model="qut_actual" id="waybill_item_quantity_supplier"
                                                   placeholder="@lang('waybill.waybill_qut_actual')" readonly>
                                        </div>
                                        <div hidden class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybil_item_unit') </label>
                                            <select class="form-select form-control is-invalid" name="waybill_item_unit"
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
                                        {{--سعر الوحده للمورد--}}
                                        <div hidden class="col-md-1">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_price') </label>
                                            <input type="number" class="form-control" v-model="waybill_price_supplier"
                                                   name="waybill_price_supplier" id="waybill_price_supplier" readonly
                                                   placeholder="@lang('waybill.waybill_price')" step="0.0001">
                                        </div>

                                        {{--نسبه الضريبه--}}
                                        <div hidden class="col-md-1">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_vat') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   id="waybill_item_vat_rate_supplier"
                                                   name="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                   placeholder="@lang('waybill.waybill_vat')" readonly>

                                        </div>

                                        <div hidden class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_vat_amount') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   name="waybill_vat_amount_supplier" readonly
                                                   v-model="waybill_vat_amount_supplier"
                                                   placeholder="@lang('waybill.waybill_vat_amount')">

                                        </div>

                                        <div hidden class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_total') </label>
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

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>ذهاب</label>
                                                        <input type="radio" value="1"
                                                               name="radio"
                                                               v-model="waybill_return" disabled>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label> ذهاب وعوده</label>
                                                        <input type="radio" value="2"
                                                               name="radio"
                                                               v-model="waybill_return" disabled>

                                                    </div>

                                                    <input type="hidden" v-model="waybill_return"
                                                           name="waybill_return">

                                                </div>

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.car_chase') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_car_chase"
                                                               @change="getWaybillByPlateNumber2()"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_car_chase" v-model="waybill_car_chase"
                                                               required>
                                                        <small style="color:red;font-weight: bold"
                                                               v-if="car_chase_message">@{{
                                                            car_chase_message }}
                                                        </small>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.car_plate') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_car_plate"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               @change="getWaybillByPlateNumber()"
                                                               id="waybill_car_plate" v-model="waybill_car_plate"
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
                                                               style="font-size: 16px ;font-weight: bold"
                                                               name="waybill_car_desc" id="waybill_car_desc"
                                                               value="{{$waybill_dt->waybill_car_desc}}" required>

                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.car_owner') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_car_owner"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_car_owner"
                                                               value="{{$waybill_dt->waybill_car_owner}}" required>

                                                    </div>


                                                </div>
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.car_color') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_car_color"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_car_color"
                                                               value="{{$waybill_dt->waybill_car_color}}" required>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.car_model') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_car_model"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_car_model"
                                                               value="{{$waybill_dt->waybill_car_model}}" required>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="card">

                                                {{--رقم الهويه للشاحن والمستلم--}}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('home.sender_identity') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_sender_mobile_code"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_sender_mobile_code_r"
                                                               v-model="customer_identity"
                                                               @keyup="validateInputs()"
                                                               placeholder="@lang('home.sender_identity')" required>

                                                        <small v-if="identity_message_s" class="text-danger">@{{
                                                            identity_message_s }}
                                                        </small>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('home.receiver_identity') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_receiver_mobile_code"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_receiver_mobile_code_r"
                                                               v-model="receiver_identity"
                                                               @keyup="validateInputs()"
                                                               placeholder="@lang('home.receiver_identity')"
                                                               required>

                                                        <small v-if="identity_message_r" class="text-danger">@{{
                                                            identity_message_r }}
                                                        </small>

                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.sender_p_mobile') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_sender_mobile"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               @keyup="validateInputs()"
                                                               id="waybill_sender_mobile_r" v-model="customer_mobile"
                                                               required>
                                                        <small v-if="mobile_message_s" class="text-danger">@{{
                                                            mobile_message_s }}
                                                        </small>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.receiver_p_mobile') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_receiver_mobile"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               v-model="receiver_mobile"
                                                               id="waybill_receiver_mobile_r"
                                                               @keyup="validateInputs()"
                                                               required>
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
                                                               name="waybill_sender_name"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_sender_name" :value="customer_name"
                                                               placeholder="@lang('waybill.sender_name')" required>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="recipient-name"
                                                               class="form-label"> @lang('waybill.receiver_name') </label>
                                                        <input type="text" class="form-control is-invalid"
                                                               name="waybill_receiver_name"
                                                               style="font-size: 16px ;font-weight: bold"
                                                               id="waybill_receiver_name"
                                                               value="{{$waybill_hd->waybill_receiver_name}}" required>

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
                                            <select class="form-select form-control"
                                                    name="waybill_payment_method" onchange="addPropReq()"
                                                    id="waybill_payment_method" required
                                                    v-model="waybill_payment_method"
                                                    v-if="customer_type_obj.system_code==539" disabled>
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


                                            <select class="form-select form-control waybill_payment_method"
                                                    name="waybill_payment_method"
                                                    id="waybill_payment_method" required
                                                    v-model="waybill_payment_method"
                                                    v-else-if="customer_type_obj.system_code==538" disabled>
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
                                                    name="waybill_payment_method" onchange="addPropReq()"
                                                    id="waybill_payment_method" required readonly=""
                                                    v-model="waybill_payment_method" disabled
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
                                                   class="form-label"> @lang('waybill.waybill_qut_request') </label>
                                            <input type="text" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold"
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
                                                   style="font-size: 16px ;font-weight: bold"
                                                   name="waybill_qut_received_customer"
                                                   id="waybill_qut_received_customer"
                                                   v-model="waybill_qut_received_customer"
                                                   placeholder="@lang('waybill.car_no')" readonly>
                                        </div>


                                        {{--سعر الوحده--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_price') </label>
                                            <input type="number" class="form-control" step="0"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   name="waybill_item_price" v-model="waybill_item_price_d"
                                                   id="waybill_item_price" placeholder="@lang('waybill.waybill_price')"
                                                   readonly>
                                            <small v-if="error_messagess" class="text-danger">@{{ error_messagess }}
                                            </small>
                                        </div>


                                        {{--سعر الاضافات--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.add_amount') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   name="waybill_add_amount" v-model="waybill_add_amount" min=0
                                                   id="waybill_add_amount" placeholder="@lang('waybill.add_amount')">
                                        </div>


                                        {{--سعر الخصومات--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.disc_amount') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   name="waybill_discount_total" v-model="waybill_discount_total" min=0
                                                   id="waybill_discount_total"
                                                   @keyup="validatedesc()"
                                                   placeholder="@lang('waybill.disc_amount')">
                                            <small v-if="error_messages" class="text-danger">@{{ error_messages }}
                                            </small>
                                        </div>


                                        {{--الاجمالي قبل الضريبه --}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.total') </label>
                                            <input type="number" class="form-control" readonly
                                                   style="font-size: 16px ;font-weight: bold"
                                                   v-model="waybill_sub_total_amount" name="waybill_sub_total_amount"
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
                                                   value="{{$waybill_hd->waybill_delivery_date}}">
                                        </div>
                                    </div>


                                    <div class="row">

                                        {{--الضريبه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_vat') </label>
                                            <input type="number" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   name="waybill_vat_rate" step="0.01"
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


                                        {{--تاريخ الوصول المتوقع--}}
                                        <div class="col-md-6">
                                            <label for="recipient"
                                                   class="form-label"> @lang('waybill.waybill_date_expected') </label>
                                            <input type="datetime-local" class="form-control"
                                                   name="waybill_delivery_expected"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   id="waybill_delivery_expected"
                                                   value="{{$waybill_hd->waybill_delivery_expected}}">
                                        </div>

                                        {{--طريقه السداد--}}
                                        <div class="col-md-2" v-if="show_payment_terms">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.pay_type') </label>
                                            <select class="form-select form-control"
                                                    name="waybill_payment_terms"
                                                    id="waybill_payment_terms" v-model="waybill_payment_terms" required>

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

                                        {{--الكميه--}}
                                        <div hidden class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_qut_actual') </label>
                                            <input type="number" class="form-control" v-model="waybill_item_quantity"
                                                   name="waybill_item_quantity" id="waybill_item_quantity" readonly
                                                   placeholder="@lang('waybill.waybill_qut_actual')" step="0.01">

                                        </div>

                                        {{--المسدد --}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.payment_amount') </label>
                                            <input type="number" class="form-control" step="0.0001"
                                                   name="waybill_paid_amount" v-model="waybill_paid_amount"
                                                   id="waybill_paid_amount" style="font-size: 16px ;font-weight: bold"
                                                   @keyup="validatePaid()"
                                                   placeholder="@lang('waybill.payment_amount')">
                                            <small v-if="error_message" class="text-danger">@{{ error_message }}</small>
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

                                        {{--ملاحظات --}}
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.customer_notes') </label>
                                            <input type="text" class="form-control "
                                                   name="waybill_car_notes" autocomplete="off"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   id="waybill_car_notes"
                                                   v-model="waybill_car_notes"
                                                   placeholder="@lang('waybill.customer_notes')">

                                        </div>


                                    </div>


                                    <div class="row">


                                        {{--الشاحنه--}}
                                        <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                             @endif @endif  class="col-md-4" id="truck_data">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_truck') </label>
                                            <select class="form-control" data-live-search="true"
                                                    name="waybill_truck_id" id="waybill_truck_id"
                                                    @change="getDriver()" v-model="truck_id">

                                                @foreach($trucks as $truck)
                                                    <option value="{{$truck->truck_id }}">
                                                        {{ $truck->truck_code}} //
                                                        {{ $truck->truck_name}} //
                                                        {{ $truck->truck_plate_no}}
                                                    </option>
                                                @endforeach


                                            </select>
                                        </div>


                                        {{--السائق--}}
                                        <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                             @endif @endif   class="col-md-4" id="trip_driver">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_driver') </label>

                                            <input type="hidden" name="waybill_driver_id" v-model="waybill_driver_id">
                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control" readonly id="emp_name_full_ar"
                                                       :value="driver.emp_name_full_ar">
                                            @else
                                                <input type="text" class="form-control" readonly
                                                       :value="driver.emp_name_full_en" id="emp_name_full_en">
                                            @endif
                                        </div>

                                        {{--عدد ردود السائق اليوميه--}}
                                        <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                             @endif @endif class="col-md-2" id="driver_rad">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.deriver_trip') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="count"
                                                   placeholder="@lang('waybill.deriver_trip')">
                                        </div>


                                        {{--اجره الطريق--}}
                                        <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                             @endif @endif   class="col-md-2" id="driver_fees">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.driver_car_fees') </label>
                                            <input type="number" class="form-control" v-model="waybill_fees_load"
                                                   name="waybill_fees_load" id="waybill_fees_load" step="0.01"
                                                   placeholder="@lang('waybill.driver_car_fees')">

                                        </div>


                                    </div>


                                    {{--اسعار الاضافات--}}
                                    <div class="row">

                                        {{--اجره الانتظار--}}
                                        <div hidden class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_fees_wait') </label>
                                            <input type="number" class="form-control" name="waybill_fees_wait"
                                                   id="waybill_fees_wait" v-model="waybill_fees_wait" step="0.01"
                                                   placeholder="@lang('waybill.waybill_fees_wait')">

                                        </div>

                                        {{-- فروقات التحميل--}}
                                        <div hidden class="col-md-2">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.waybill_differences') </label>
                                            <input type="number" class="form-control" name="waybill_fees_difference"
                                                   id="waybill_fees_difference" v-model="waybill_fees_difference"
                                                   placeholder="@lang('waybill.waybill_differences')" step="0.01">

                                        </div>


                                    </div>


                                    <div class="card bline" style="color:red">
                                    </div>

                                    <button class="btn btn-primary " type="button" id="submit" v-if="save_disabled"
                                            onclick="confirmUpdate()"
                                            :disabled="disable_button || disable_button_2 || lock_data || disable_button_3">
                                        @lang('home.save')</button>


                                    <button class="btn btn-primary" type="button" id="submit"
                                            onclick="confirmUpdate()" v-if="edit_button">
                                        @lang('home.edit')</button>

                                    <button class="btn btn-primary" type="button" v-if="button_duplicate"
                                            @click="removeInputs()" id="duplicate">
                                        @lang('home.duplicate')</button>

                                    @if($waybill_hd->detailsCar->waybill_item_quantity > 1)
                                        <a href="{{config('app.telerik_server')}}?rpt={{$waybill_hd->report_url_waybill_co->report_url}}&id={{$waybill_hd->waybill_id}}&lang=ar&skinName=bootstrap"
                                           class="btn btn-primary"
                                           style="display: inline-block; !important; margin-right: 1px"
                                           id="print"
                                           target="_blank">
                                            @lang('home.print')</a>

                                    @else
                                        <a href="{{config('app.telerik_server')}}?rpt={{$waybill_hd->report_url_waybill->report_url}}&id={{$waybill_hd->waybill_id}}&lang=ar&skinName=bootstrap"
                                           class="btn btn-primary"
                                           style="display: inline-block; !important; margin-right: 1px"
                                           id="print"
                                           target="_blank">
                                            @endif


                                            <a href="{{ route('WaybillCar') }}" class="btn btn-primary"
                                               style="display: inline-block; !important;"
                                               id="back">
                                                @lang('home.exit')</a>

                                            <a href="{{ route('Waybill.create_car') }}" class="btn btn-primary"
                                               type="button"
                                               id="back">
                                                {{__('add new waybill')}}</a>

                                            <div class="spinner-border" role="status" style="display: none">
                                                <span class="sr-only">Loading...</span>
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
                                                        <div class="modal-body"
                                                             style="font-size: 16px ;font-weight: bold">
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
                        </div>
                    </form>
                </div>

            </div>
        </div>
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
                $('#waybill_text').text('هل انت متاكد من حفظ بوليصة الشحن ' + ' ' + ' و طريقه السداد ' + ' ' + '<<<' + $('#waybill_payment_method option:selected').text() + '>>>' + ' بقيمه سداد ' + '<<<' + $('#waybill_paid_amount').val() + '>>>' + ' ريال')
            }
        }

        function confirmUpdate2() {
            $('#myModal2').modal('show')
            if ($('#waybill_item_price').val() <= 0 || $('#waybill_total_amount').val() <= 0) {
                $('#waybill_text').text('القيم غير صحيحه لا يمكن الحفظ');
                $('#modal_button').attr('disabled', 'disabled')
            } else {
                $('#waybill_text').text('هل انت متاكد من حفظ بوليصة الشحن  ' + ' ' + ' و طريقه السداد ' + ' ' + '<<<' + $('#waybill_payment_method option:selected').text() + '>>>' + ' بقيمه سداد ' + '<<<' + $('#new_waybill_paid_amount').val() + '>>>' + ' ريال')
            }
        }

        $(document).ready(function () {

            $('body').keypress(function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });

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

            // $('#waybill_car_model').keyup(function () {
            //     if ($('#waybill_car_model').val().length != 4) {
            //
            //     }
            // }


            $('#modal_button').click(function () {
                $('#modal_button').css('display', 'none')
            });
            $('#modal_button2').click(function () {
                $('#modal_button2').css('display', 'none')
            });


            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('#back').css('display', 'none')
                $('#duplicate').css('display', 'none')
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

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                waybill_hd: {},
                waybill_dt: {},
                waybill_item: '{{$waybill_item->system_code}}',
                waybill_status: '{{$waybill_status->system_code}}',
                company_id: '',
                trucks: {},
                waybill_item_id: '',
                waybill_item_vat_rate: 0,

                //suppli3er
                waybill_qut_requried_supplier: 1,
                waybill_qut_received_supplier: 1,
                waybill_price_supplier: 0,
                waybill_driver_id: '',
                //customer
                customer_id: '',
                customer_name: '',
                customer_mobile: 0,
                customer_type_ar: '',
                customer_type_en: '',

                waybill_qut_received_customer: 1,
                waybill_qut_requried_customer: 1,

                waybill_item_price: 0,
                waybill_discount_total: 0,
                waybill_payment_terms: '',
                waybill_payment_method: '',
                waybill_add_amount: 0,
                waybill_paid_amount: 0,

                waybill_fees_difference: 0,
                waybill_fees_wait: 0,
                waybill_fees_load: 0,
                waybill_loc_from: '',
                waybill_loc_to: '',
                waybill_transit_loc_1: '',

                item_id: '',
                truck_id: '',
                driver: {},
                customer_type_obj: {},
                count: '',
                waybill_car_chase: 0,
                waybill_car_plate: 0,
                button_duplicate: true,
                save_disabled: false,
                waybill_code: true,

                disable_button: false,
                new_disable_button: false,
                error_message: '',
                new_error_message: '',

                edit_button: true,
                update_form: true,
                repeat_form: false,
                new_waybill_paid_amount: 0,
                show_payment_terms: true,
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                error_messagess: '',

                error_messages: '',
                customer_contract: '',
                waybill_return: 1,
                price_factor: 1,
                disable_button_2: false,
                waybill_car_notes: '',
                mobile_message_s: '',
                identity_message_s: '',
                mobile_message_r: '',
                identity_message_r: '',
                receiver_mobile: '',
                receiver_identity: '',
                customer_identity: '',
                lock_data: false,
                car_plate_message: '',
                car_chase_message: '',
            },
            mounted() {
                $('#issue_date_hijri').on("dp.change", (e) => {
                    this.issue_date_hijri = $('#issue_date_hijri').val()
                    this.getGeorgianDate()
                });

                $('#expire_date_hijri').on("dp.change", (e) => {
                    this.expire_date_hijri = $('#expire_date_hijri').val()
                    this.getGeorgianDate2()
                });

                this.waybill_hd = {!! $waybill_hd !!}
                    this.waybill_dt = {!! $waybill_dt !!}
                    this.waybill_item = {{$waybill_item->system_code}}
                    this.waybill_status = {{$waybill_status->system_code}}
                console.log(this.waybill_hd)
                this.company_id = this.waybill_hd.company_id
                this.waybill_item_id = this.waybill_item
                this.waybill_qut_requried_supplier = this.waybill_dt.waybill_qut_requried_supplier
                this.waybill_qut_received_supplier = this.waybill_dt.waybill_qut_received_supplier
                this.waybill_price_supplier = this.waybill_dt.waybill_price_supplier
                this.waybill_driver_id = this.waybill_hd.waybill_driver_id
                this.customer_id = this.waybill_hd.customer_id
                this.waybill_qut_received_customer = this.waybill_dt.waybill_qut_received_customer
                this.waybill_qut_requried_customer = this.waybill_dt.waybill_qut_requried_customer
                this.waybill_item_price = this.waybill_dt.waybill_item_price
                this.waybill_add_amount = this.waybill_dt.waybill_add_amount
                this.waybill_paid_amount = this.waybill_hd.waybill_paid_amount
                this.waybill_fees_difference = this.waybill_dt.waybill_fees_difference
                this.waybill_fees_wait = this.waybill_dt.waybill_fees_wait
                this.waybill_fees_load = this.waybill_dt.waybill_fees_load
                this.waybill_loc_from = this.waybill_hd.waybill_loc_from
                this.waybill_loc_to = this.waybill_hd.waybill_loc_to
                this.waybill_transit_loc_1 = this.waybill_hd.waybill_loc_from
                this.truck_id = this.waybill_hd.waybill_truck_id
                this.waybill_discount_total = this.waybill_dt.waybill_discount_total
                this.waybill_payment_method = this.waybill_hd.waybill_payment_method
                this.waybill_payment_terms = this.waybill_hd.waybill_payment_terms
                this.waybill_car_chase = this.waybill_dt.waybill_car_chase
                this.waybill_car_plate = this.waybill_dt.waybill_car_plate
                this.waybill_car_notes = this.waybill_dt.waybill_car_notes
                this.customer_contract = this.waybill_hd.customer_contract
                this.waybill_return = this.waybill_hd.waybill_return
                this.receiver_mobile = this.waybill_hd.waybill_receiver_mobile
                this.receiver_identity = this.waybill_hd.waybill_receiver_mobile_code

                this.customer_identity = this.waybill_hd.waybill_sender_mobile_code
                this.customer_mobile = this.waybill_hd.waybill_sender_mobile
                this.customer_name = this.waybill_hd.waybill_sender_name


                if (this.waybill_status == 41004 && this.waybill_payment_method == 54001) {
                    if (this.waybill_paid_amount != this.waybill_total_amount || this.waybill_paid_amount == 0) {
                        this.disable_button = true
                        this.error_message = 'المسدد لا يساوي الاجمالي'
                    }
                }

                if (this.waybill_payment_method == 54003) {
                    this.show_payment_terms = false
                }
                ///methods
                this.getcustomertype()
                this.validateInputs()
                this.getTrucks()
            },
            methods: {
                getWaybillByPlateNumber() {
                    this.car_plate_message = ''
                    $.ajax({
                        type: 'GET',
                        data: {
                            waybill_car_plate: this.waybill_car_plate,
                        },
                        url: '{{ route("car-checkWaybillByPlateChaseNo") }}'
                    }).then(response => {
                        if (response.error) {
                            this.car_plate_message = response.error
                        } else {
                            this.car_plate_message = ''
                        }

                    });
                },

                getWaybillByPlateNumber2() {
                    this.car_chase_message = ''
                    $.ajax({
                        type: 'GET',
                        data: {
                            waybill_car_chase: this.waybill_car_chase,
                        },
                        url: '{{ route("car-checkWaybillByPlateChaseNo") }}'
                    }).then(response => {
                        if (response.error) {
                            this.car_chase_message = response.error
                        } else {
                            this.car_chase_message = ''
                        }

                    });
                },
                reValidAdditions() {
                    this.waybill_add_amount = 0;
                    this.waybill_discount_total = 0;
                },
                validateadd() {
                    this.error_message = ''
                    if (this.waybill_add_amount == '' || this.waybill_add_amount < 0) {

                        this.disable_button = true
                        this.error_messages = '  المبلغ غير مسموح بة'

                    }
                },
                validatedesc() {
                    this.disable_button = false
                    this.error_message = ''
                    if (this.waybill_discount_total > this.waybill_fees_difference || this.waybill_discount_total == '' || this.waybill_add_amount == '') {
                        this.error_messages = 'الخصم غير مسموح بة'

                    } else {
                        this.error_messages = ''
                        if (this.waybill_status == 41004 && this.waybill_payment_method == 54001) {
                            if (this.waybill_paid_amount != this.waybill_total_amount || this.waybill_paid_amount == 0) {
                                this.disable_button = true
                                this.error_message = 'المسدد لا يساوي الاجمالي'
                            }
                        } else {
                            this.disable_button = false
                            this.error_message = ''
                            this.error_messages = ''
                        }
                    }
                },
                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },
                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                },
                validatePaid() {
                    this.disable_button_2 = false
                    this.error_message = ''
                    this.new_error_message = ''
                    this.new_disable_button = false

                    if (this.waybill_item_price == null) {
                        this.new_waybill_paid_amount = 0
                        this.save_disabled = true
                        this.disable_button_2 = true
                        this.new_disable_button = true
                        this.error_messagess = 'يجب تحديد سعر '
                    }

                    if (this.waybill_payment_method == 54003 && this.waybill_item_price > 0) {
                        this.new_waybill_paid_amount = 0
                        this.show_payment_terms = false
                    }

                    if (this.new_waybill_paid_amount > this.waybill_due_amount) {
                        this.new_disable_button = true
                        this.new_error_message = 'المسدد اكبر من الاجمالي'

                    } else if (this.waybill_status == 41004 && this.waybill_payment_method == 54001 && this.waybill_item_price > 0) {
                        if (parseFloat(this.new_waybill_paid_amount) != parseFloat(this.waybill_total_amount) || this.new_waybill_paid_amount == 0) {
                            this.new_disable_button = true
                            this.new_error_message = 'المسدد لا يساوي الاجمالي'
                        }

                        if (parseFloat(this.waybill_paid_amount) != parseFloat(this.waybill_total_amount)
                            || this.waybill_paid_amount == 0 || this.waybill_item_price == null) {
                            this.disable_button_2 = true
                            this.error_message = 'المسدد لا يساوي الاجمالي'
                        }
                    } else if (this.waybill_item_price == null) {
                        this.new_disable_button = true
                        this.error_messagess = 'يجب تحديد سعر '
                    } else {
                        this.disable_button_2 = false
                        this.new_error_message = false
                        this.error_message = ''
                        this.new_error_message = ''

                    }
                },
                removeInputs() {
                    this.waybill_car_chase = 0
                    this.waybill_car_plate = 0
                    this.button_duplicate = false
                    this.save_disabled = true
                    this.waybill_code = false
                    this.edit_button = false
                    this.update_form = false
                    this.repeat_form = true
                },
                getTrucks() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.trucks") }}'
                    }).then(response => {
                        this.trucks = response.data
                    })
                },
                getDriver() {
                    $.ajax({
                        type: 'GET',
                        data: {truck_id: this.truck_id},
                        url: '{{ route("api.waybill.truck.driver") }}'
                    }).then(response => {
                        this.driver = response.data
                        this.waybill_driver_id = response.data.emp_id
                        this.getCountWaybillsDaily()
                    })
                },
                getcustomertype() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.customer_id},
                        url: '{{ route("api.waybill.customer.type") }}'
                    }).then(response => {
                        this.customer_type_ar = response.data.system_code_name_ar
                        this.customer_type_en = response.data.system_code_name_en

                        this.waybill_item_vat_rate = response.customer_vat_rate

                        if (this.customer_id != this.waybill_hd.customer_id) {
                            this.customer_name = response.customer_name
                            this.customer_mobile = response.customer_mobile
                            this.customer_identity = response.customer_identity
                        } else {
                            this.customer_identity = this.waybill_hd.waybill_sender_mobile_code
                            this.customer_mobile = this.waybill_hd.waybill_sender_mobile
                            this.customer_name = this.waybill_hd.waybill_sender_name
                        }

                        this.customer_type_obj = response.data
                        if (response.data.system_code == 539) {
                            // this.waybill_payment_method = 54003
                            // this.waybill_payment_terms = 57005
                            this.disable_button = false
                            this.error_message = ''
                        } else {
                            // this.waybill_payment_method = 54001
                            if (this.waybill_paid_amount != this.waybill_total_amount) {
                                this.disable_button = true
                                this.error_message = 'المسدد لا يساوي ااجمالي'
                            }

                        }

                        this.validateInputs();
                    })
                },

                getCountWaybillsDaily() {
                    if (this.waybill_item_id == 64001 || this.waybill_item_id == 64002 || this.waybill_item_id == 64003
                        || this.waybill_item_id == 64004) {
                        //  this.waybill_loc_from = '',
                        // this.waybill_loc_to = '',
                        this.waybill_item_price = 0,
                            this.waybill_discount_total = 0,
                            this.waybill_add_amount = 0,
                            this.waybill_paid_amount = 0,
                            this.count = ''
                        this.waybill_driver_id = ''
                        this.driver = {}
                        this.truck_id = ''
                    }

                    if (this.waybill_item_id == 64005 && this.truck_id) {
                        //    this.waybill_loc_from = ''
                        //  this.waybill_loc_to = ''
                        // this.waybill_item_price = 0
                        //this.waybill_discount_total= 0
                        //this.waybill_add_amount= 0
                        this.waybill_paid_amount = 0

                        $.ajax({
                            type: 'GET',
                            data: {waybill_driver_id: this.waybill_driver_id},
                            url: '{{ route("api.waybill.getCountWaybillsDaily") }}'
                        }).then(response => {
                            this.count = parseInt(response.data) + 1
                        })
                    }

                },
                getPriceList() {
                    this.waybill_item_price = 0
                    this.waybill_fees_difference = 0
                    this.error_messagess = ''
                    this.disable_button = false
                    this.error_messages = ''
                    this.price_factor = 0

                    if (this.waybill_loc_to && this.waybill_loc_from && this.waybill_item_id) {
                        $.ajax({
                            type: 'GET',
                            data: {
                                waybill_loc_from: this.waybill_loc_from,
                                waybill_loc_to: this.waybill_loc_to,
                                price_list_id: this.customer_contract,
                                item_id: this.waybill_item_id,
                                waybill_id: this.waybill_hd.waybill_id
                            },
                            url: '{{ route("car-getprice") }}'
                        }).then(response => {

                            this.waybill_item_price = response.max_fees
                            this.waybill_fees_difference = response.max_fees - response.min_fees
                            this.price_factor = response.price_factor

                            if (this.waybill_discount_total > this.waybill_fees_difference) {
                                this.error_messages = 'الخصم غير مسموح بة'

                            }
                            // this.waybill_distance = response.distance

                            if (this.waybill_item_price > 0) {
                                console.log('not zero')
                                if (this.waybill_status == 41004 && this.waybill_payment_method == 54001) {
                                    if (this.waybill_paid_amount != this.waybill_total_amount) {
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

                            } else if (this.waybill_item_price <= 0 || this.waybill_item_price == null) {
                                this.disable_button = true
                                this.error_messagess = 'يجب تحديد سعر الشحن'
                            }

                        })
                    }


                },
                validateInputs() {
                    this.mobile_message_s = ''
                    this.identity_message_s = ''
                    this.mobile_message_r = ''
                    this.identity_message_r = ''
                    this.lock_data = false

                    if (this.customer_mobile.length != 10) {
                        this.mobile_message_s = 'الرقم يجب ان يكون 10 ارقام'
                        this.lock_data = true
                    }

                    if (this.customer_identity.length != 10) {
                        this.identity_message_s = 'الرقم يجب ان يكون 10 ارقام'
                        this.lock_data = true
                    }

                    if (this.receiver_mobile.length != 10) {
                        this.mobile_message_r = 'الرقم يجب ان يكون 10 ارقام'
                        this.lock_data = true
                    }

                    if (this.receiver_identity.length != 10) {
                        this.identity_message_r = 'الرقم يجب ان يكون 10 ارقام'
                        this.lock_data = true
                    }

                    if (this.waybill_payment_terms == 57005) { ///دفع علي الحساب
                        this.bank_valid = false
                    } else {
                        this.bank_valid = true
                        this.bank_id = ''
                    }
                }

            },
            computed:
                {
                    disable_button_3: function () {
                        if (parseFloat(this.waybill_discount_total) > parseFloat(this.waybill_fees_difference) || parseFloat(this.waybill_discount_total) < 0) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                    waybill_item_price_d: function () {

                        if (this.waybill_return == 1) {
                            return this.waybill_item_price
                        }

                        else if (this.waybill_return == 2) {
                            return this.waybill_item_price * this.price_factor
                        }

                        else (!this.waybill_return)
                        {
                            return this.waybill_item_price
                        }
                    },
                    waybill_item_price2: function () {
                        var q = parseFloat(this.waybill_item_price_d)
                        return q.toFixed(2)
                    },
                    waybill_item_vat_amount: function () {
                        var x = parseFloat(this.waybill_item_vat_rate) * ((parseFloat(this.waybill_item_quantity) *
                            parseFloat(this.waybill_item_price2)) + parseFloat(this.waybill_add_amount) - parseFloat(this.waybill_discount_total))
                        return x.toFixed(2)
                    }
                    ,
                    waybill_sub_total_amount: function () {
                        var y = parseFloat(this.waybill_add_amount)
                            + (parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price2))
                            - parseFloat(this.waybill_discount_total)

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
                        if (this.waybill_paid_amount != z) {

                            if (this.waybill_status == 41004 && this.waybill_payment_method == 54001 && this.waybill_item_price_d > 0) {
                                this.error_message = 'المسدد لا يساوي الاجمالي'
                                this.disable_button = true
                            } else {
                                this.error_message = ''
                                this.disable_button = false

                            }
                        } else {
                            this.error_message = ''
                            this.disable_button = false
                        }
                        return z.toFixed(2)
                    }
                    ,

                    waybill_due_amount: function () {
                        var r = parseFloat(this.waybill_item_vat_amount) + parseFloat(this.waybill_sub_total_amount)
                            - parseFloat(this.waybill_paid_amount)
                        return r.toFixed(2)
                    }
                    ,


                    new_waybill_due_amount: function () {
                        var r = parseFloat(this.waybill_item_vat_amount) + parseFloat(this.waybill_sub_total_amount)
                            - parseFloat(this.new_waybill_paid_amount) - parseFloat(this.waybill_paid_amount)
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
                    validate_paid_amount: function () {
                        if (this.waybill_payment_method != 54003) {
                            if (this.waybill_due_amount == 0) {
                                return true
                            } else {
                                return false
                            }
                        }
                        else {
                            return true
                        }
                    }
                    ,
                    disable_button2: function () {
                        if (this.customer_mobile.length != 10 || this.receiver_mobile.length != 10 || this.customer_identity.length != 10 || this.receiver_identity.length != 10 || this.waybill_item_price_d <= 0) {
                            this.error_messagess = 'الرقم يجب ان يكون 10 ارقام'
                            return true
                        } else {

                            this.error_messagess = ''
                            return false
                        }

                    },

                }
        })
    </script>

@endsection
