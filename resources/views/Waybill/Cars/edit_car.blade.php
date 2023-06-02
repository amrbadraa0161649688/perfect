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
    @php
        if(session('waybill_hd')){
        session()->forget('waybill_hd');
        }
    @endphp

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">

                <div style="font-size: 16px ;font-weight: bold" class="card">
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
                {{--<div class="tab-pane fade show active" id="data-grid" role="tabpanel">--}}
                <div class="card">
                    <div class="section-body">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-between align-items-center">
                                <ul class="nav nav-tabs page-header-tab">
                                    <li class="nav-item">
                                        <a href="#form-grid" data-toggle="tab"
                                           style="font-size: 18px ;font-weight: bold"
                                           class="nav-link @if(!Session::has('cars') ) active @endif">@lang('home.update_form')</a>
                                    </li>

                                    <li class="nav-item"><a class="nav-link" href="#files-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">@lang('home.files')</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">@lang('home.notes')</a></li>

                                    <li class="nav-item"><a class="nav-link" href="#bonds-cash-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">@lang('home.bonds_cash')</a></li>

                                    <li class="nav-item"><a class="nav-link" href="#bonds-capture-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">@lang('home.bonds_capture')</a></li>

                                    <li class="nav-item"><a class="nav-link" href="#trips-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">@lang('home.trips')</a></li>

                                    <li class="nav-item"><a class="nav-link" href="#invoices-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">@lang('home.invoices')</a></li>

                                    <li class="nav-item"><a class="nav-link @if(Session::has('cars') ) active @endif"
                                                            href="#cars-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">@lang('home.cars')</a></li>

                                    <li class="nav-item"><a class="nav-link"
                                                            href="#photos-grid"
                                                            style="font-size: 18px ;font-weight: bold"
                                                            data-toggle="tab">{{__('Take Photo')}}</a></li>
                                </ul>
                                <div class="header-action"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--</div>--}}


                <div class="tab-content mt-3">
                    {{-- Form To Create Waybill--}}
                    <div class="tab-pane fade  @if(!Session::has('cars') )show active @endif" id="form-grid"
                         role="tabpanel">
                        <form class="card" id="validate-form"
                              action="{{ route('Waybill.update_car',$waybill_hd->waybill_id) }}"
                              method="post" enctype="multipart/form-data" id="form">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                {{--inputs data--}}
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="row">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" disabled=""
                                                       style="font-size: 18px ;font-weight: bold"
                                                       value="{{$waybill_hd->waybill_code}}" v-if="waybill_code">
                                            </div>
                                            @if($waybill_hd->invoice)

                                                <div class="col-md-1">
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{app()->getLocale() == 'ar' ?
                                                   \App\Models\SystemCode::where('system_code',$waybill_hd->invoice->invoice_status)
                                                   ->first()->system_code_name_ar :
                                                   \App\Models\SystemCode::where('system_code',$waybill_hd->invoice->invoice_status)
                                                   ->first()->system_code_name_en }}">
                                                </div>

                                                <div class="col-md-1">
                                                    <input hidden type="text" class="form-control" disabled=""
                                                           value="{{$waybill_hd->invoice ? $waybill_hd->invoice->invoice_no : 'لا يوجد فاتوره مضافه'}}">
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$waybill_hd->invoiceno->report_url_car->report_url}}&id={{$waybill_hd->invoiceno->invoice_id}}&lang=ar&skinName=bootstrap"
                                                       class=" btn btn-primary btn-sm btn-info"
                                                       style="font-size: 14px ;font-weight: bold" target="_blank">


                                                        {{$waybill_hd->invoiceno->invoice_no}}
                                                    </a>


                                                </div>
                                            @endif

                                            @if($waybill_hd->trip)
                                                <div class="col-md-3">
                                                    <a href="{{ route('Trips.edit',$waybill_hd->waybill_trip_id) }}"
                                                       class="btn btn-link btn-sm"
                                                       target="_blank"
                                                       style="font-size: 18px ;font-weight: bold;color:red;text-decoration: underline;">
                                                        {{  $waybill_hd->trip->trip_hd_code }}
                                                    </a>

                                                </div>

                                                <div class="col-md-1">
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{app()->getLocale()=='ar' ? $waybill_hd->trip->status->system_code_name_ar :
                                                                        $waybill_hd->trip->status->system_code_name_en}}">
                                                </div>
                                            @endif

                                            @if($waybill_hd->truck)

                                                <div hidden class="col-md-1">
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ $waybill_hd->truck->truck_code }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{  $waybill_hd->truck->truck_code .' => '.$waybill_hd->truck->truck_name.' => '.$waybill_hd->truck->truck_plate_no }}">
                                                </div>
                                                <div hidden class="col-md-1">
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ $waybill_hd->truck->truck_plate_no }}">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">

                                            <div class="col-md-3">
                                                {{-- حاله الشحنه --}}
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_status') </label>
                                                @if($waybill_hd->status->system_code==41005)
                                                    <input type="text" class="form-control"
                                                           value="{{app()->getLocale() == 'ar' ? $waybill_hd->status->system_code_name_ar :
                                                  $waybill_hd->status->system_code_name_en}}" disabled="">
                                                @elseif($waybill_hd->status->system_code==41006)
                                                    <input type="text" class="form-control"
                                                           value="{{app()->getLocale() == 'ar' ? $waybill_hd->status->system_code_name_ar :
                                                  $waybill_hd->status->system_code_name_en}}" disabled="">
                                                @else
                                                    <select class="form-select form-control" name="waybill_status"
                                                            id="waybill_status" required onchange="addPropReq()"
                                                            v-model="waybill_status">
                                                        @foreach($sys_codes_waybill_status as $sys_code_waybill_status)
                                                            <option value="{{$sys_code_waybill_status->system_code}}">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{$sys_code_waybill_status->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_waybill_status->system_code_name_en}}
                                                                @endif
                                                            </option>

                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>


                                            <div class="col-md-3">
                                                <label hidden for="recipient"
                                                       class="form-label"> @lang('trucks.sub_company') </label>
                                                <select hidden class="form-select form-control"
                                                        name="company_id" id="company_id"
                                                        v-model="company_id" disabled="">
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
                                                           value="@if(app()->getLocale()=='ar') {{  $waybill_hd->userCreated->user_name_ar }}
                                                           @else {{ $waybill_hd->userCreated->user_name_en }} @endif">
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
                                                            id="supplier_id" disabled="">
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
                                                        name="customer_id" id="customer_id"
                                                        :disabled="waybill_st || user_permission"
                                                        @change="getcustomertype();getContractsList()"
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


                                            {{-- رقم العقد للعميل--}}
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.customer_contract') </label>

                                                <select class="form-control" name="customer_contract"
                                                        id="customer_contract" v-model="customer_contract"
                                                        style="color:black"
                                                        @change="getPriceList()" required v-if="customer_change">
                                                    <option>@lang('home.choose')</option>
                                                    <option v-for="contract in contracts_list"
                                                            :value="contract.price_list_id">
                                                        @{{contract.price_list_code}}
                                                    </option>
                                                </select>

                                                <small class="text-danger" v-if="contract_error">@{{contract_error}}
                                                </small>

                                                <input type="text" class="form-control"
                                                       autocomplete="off" disabled=""
                                                       name="customer_contract" id="customer_contract"
                                                       v-model="customer_contract" v-if="!customer_change">


                                            </div>

                                            {{-- رقم التذكره--}}
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.approved_no') </label>
                                                <input type="text" class="form-control"
                                                       autocomplete="off"
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
                                                    <input type="text" class="form-control"
                                                           style="font-size: 16px ;font-weight: bold" readonly
                                                           :value="customer_type_en">
                                                @endif

                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.transport_type') </label>
                                                <select class="form-select form-control waybill_item_id"
                                                        style="font-size: 16px ;font-weight: bold"
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
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> @lang('waybill.loc_car_from') </label>
                                                <select class="form-select form-control" name="waybill_loc_from"
                                                        id="waybill_loc_from"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        v-model="waybill_loc_from"
                                                        @if($waybill_hd->invoice || $waybill_hd->trip)
                                                        disabled @endif @change="getPriceList();getWaybillLocPaid()">
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

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> @lang('waybill.loc_car_to') </label>
                                                <select class="form-select form-control" name="waybill_loc_to"
                                                        id="waybill_loc_to"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        v-model="waybill_loc_to"
                                                        @change="getPriceList();getWaybillLocPaid()"
                                                        @if($waybill_hd->invoice || $waybill_hd->trip)
                                                        disabled @endif>>
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

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;"> {{__('location car transit')}} </label>
                                                <input type="text" readonly class="form-control"
                                                       @if($waybill_hd->LocTransit)
                                                       value="{{app()->getLocale()=='ar' ? $waybill_hd->LocTransit->system_code_name_ar :
                                                       $waybill_hd->LocTransit->system_code_name_en}}" @endif>
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
                                                       placeholder="@lang('waybill.waybill_qut_actual')"
                                                       readonly="">
                                            </div>
                                            <div hidden class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybil_item_unit') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="waybill_item_unit"
                                                        id="waybill_item_unit" disabled="">
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
                                                       v-model="waybill_vat_amount_supplier" disabled
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
                                                        @if($waybill_hd->waybill_type_id == 8)
                                                            <div class="col-md-6 m-2 text-white"
                                                                 style="background-color:#004660;border: solid 1px #004660;border-radius: 10px">
                                                                <label class="bold font-18">
                                                                    {{\App\Models\WaybillHd::where('waybill_id',$waybill_hd->waybill_return_no)->first()->waybill_code}}
                                                                    عوده</label>
                                                                <input type="radio" checked disabled>
                                                            </div>

                                                            @if($waybill_hd->waybillReturn)
                                                                <div class="col-md-6 m-2 text-white"
                                                                     style="background-color:#004660;border: solid 1px #004660;border-radius: 10px">
                                                                    <input type="text" class="form-control" readonly
                                                                           value="{{ $waybill_hd->waybillReturn->waybill_code }}">
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="col-md-4">
                                                                <label>ذهاب</label>
                                                                <input type="radio" value="1" name="radio"
                                                                       @if($waybill_hd->waybill_return == 1) checked
                                                                       @endif disabled>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label> ذهاب وعوده</label>
                                                                <input type="radio" value="2" name="radio"
                                                                       @if($waybill_hd->waybill_return == 2) checked
                                                                       @endif disabled>
                                                            </div>
                                                            @if($waybill_hd->waybillReturn)
                                                                <div class="col-md-4 text-white"
                                                                     style="background-color:#004660;border: solid 1px #004660;border-radius: 10px">
                                                                    <input type="text" class="form-control" readonly
                                                                           value="{{ $waybill_hd->waybillReturn->waybill_code }}">
                                                                </div>
                                                            @endif

                                                            <input type="hidden" name="waybill_return"
                                                                   value="{{$waybill_hd->waybill_return}}">
                                                        @endif


                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.car_chase') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   name="waybill_car_chase"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   @if($waybill_hd->invoice) @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005 || $waybill_hd->status->system_code == 41008)
                                                                   readonly @endif

                                                                   @if($waybill_hd->status->system_code == 41007 && $waybill_hd->customer->cus_type->system_code == 538)
                                                                   readonly @endif
                                                                   id="waybill_car_chase"
                                                                   v-model="waybill_car_chase"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.car_plate') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   name="waybill_car_plate"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   @if($waybill_hd->invoice) @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005 || $waybill_hd->status->system_code == 41008)
                                                                   readonly @endif
                                                                   @if($waybill_hd->status->system_code == 41007 && $waybill_hd->customer->cus_type->system_code == 538)
                                                                   readonly @endif
                                                                   id="waybill_car_plate"
                                                                   v-model="waybill_car_plate"
                                                                   required>

                                                        </div>
                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.car_desc') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   name="waybill_car_desc" id="waybill_car_desc"
                                                                   @if($waybill_hd->invoice)  @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005 || $waybill_hd->status->system_code == 41008)
                                                                   readonly @endif
                                                                   @if($waybill_hd->status->system_code == 41007 && $waybill_hd->customer->cus_type->system_code == 538)
                                                                   readonly @endif
                                                                   value="{{$waybill_dt->waybill_car_desc}}"
                                                                   required>

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.car_owner') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   name="waybill_car_owner"
                                                                   id="waybill_car_owner"
                                                                   @if($waybill_hd->status->system_code == 41005 || $waybill_hd->status->system_code == 41008)
                                                                   readonly @endif
                                                                   @if($waybill_hd->invoice)  @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41007 && $waybill_hd->customer->cus_type->system_code == 538)
                                                                   readonly @endif
                                                                   value="{{$waybill_dt->waybill_car_owner}}"
                                                                   required>

                                                        </div>


                                                    </div>
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.car_color') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   name="waybill_car_color"
                                                                   id="waybill_car_color"
                                                                   @if($waybill_hd->status->system_code == 41005 || $waybill_hd->status->system_code == 41008)
                                                                   readonly @endif
                                                                   @if($waybill_hd->invoice)  @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41007 && $waybill_hd->customer->cus_type->system_code == 538)
                                                                   readonly @endif
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
                                                                   @if($waybill_hd->invoice)  @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003 || $waybill_hd->status->system_code == 41008)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005)
                                                                   readonly @endif
                                                                   @if($waybill_hd->status->system_code == 41007 && $waybill_hd->customer->cus_type->system_code == 538)
                                                                   readonly @endif
                                                                   value="{{$waybill_dt->waybill_car_model}}"
                                                                   required>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="card">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.sender_name') </label>

                                                            <input type="text" class="form-control is-invalid"
                                                                   name="waybill_sender_name"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   id="waybill_sender_name"
                                                                   @if($waybill_hd->invoice && $waybill_hd->customer)

                                                                   @if($waybill_hd->invoice->invoice_status != 121002 || $waybill_hd->invoice->invoice_status != 121003
                                                                  && $waybill_hd->customer->cus_type->system_code == 539)
                                                                   readonly
                                                                   @endif
                                                                   @endif
                                                                   @if($waybill_hd->status->system_code == 41007)
                                                                   readonly @endif
                                                                   @if($waybill_hd->trip) @if($waybill_hd->trip->status->system_code != 39001)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005)
                                                                   readonly @endif
                                                                   @if($waybill_hd->trip || $waybill_hd->invoice)
                                                                   readonly
                                                                   @endif
                                                                   v-model="customer_name"
                                                                   placeholder="@lang('waybill.sender_name')"
                                                                   required>

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.receiver_name') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   name="waybill_receiver_name"
                                                                   id="waybill_receiver_name"
                                                                   @if($waybill_hd->invoice && $waybill_hd->customer) @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003 && $waybill_hd->customer->cus_type->system_code != 539)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->invoice || $waybill_hd->trip) readonly
                                                                   @endif
                                                                   @if($waybill_hd->status->system_code == 41008)
                                                                   readonly
                                                                   @endif
                                                                   @if($waybill_hd->trip && $waybill_hd->customer) @if($waybill_hd->trip->status->system_code != 39001
                                                               && $waybill_hd->customer->cus_type->system_code != 538)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005)
                                                                   readonly @endif
                                                                   value="{{$waybill_hd->waybill_receiver_name}}"
                                                                   required>

                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.sender_p_mobile') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   name="waybill_sender_mobile"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   id="waybill_sender_mobile"
                                                                   @if($waybill_hd->invoice && $waybill_hd->customer)
                                                                   @if($waybill_hd->invoice->invoice_status != 121002 || $waybill_hd->invoice->invoice_status != 121003
                                                                   && $waybill_hd->customer->cus_type->system_code == 539)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->invoice)  @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41007)
                                                                   readonly @endif
                                                                   @if($waybill_hd->trip) @if($waybill_hd->trip->status->system_code != 39001)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005)
                                                                   readonly @endif
                                                                   @if($waybill_hd->trip || $waybill_hd->invoice)
                                                                   readonly
                                                                   @endif
                                                                   v-model="customer_mobile"
                                                                   required>

                                                        </div>


                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('waybill.receiver_p_mobile') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   name="waybill_receiver_mobile"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   id="waybill_receiver_mobile"
                                                                   @if($waybill_hd->invoice || $waybill_hd->trip) readonly
                                                                   @endif
                                                                   @if($waybill_hd->invoice && $waybill_hd->customer) @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003 && $waybill_hd->customer->cus_type->system_code != 539)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->trip && $waybill_hd->customer) @if($waybill_hd->trip->status->system_code != 39001
                                                                && $waybill_hd->customer->cus_type->system_code != 538)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41008)
                                                                   readonly
                                                                   @endif
                                                                   @if($waybill_hd->status->system_code == 41005)
                                                                   readonly @endif
                                                                   value="{{$waybill_hd->waybill_receiver_mobile}}"
                                                                   required>

                                                        </div>


                                                    </div>


                                                    {{--رقم الهويه للشاحن والمستلم--}}
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('home.sender_identity') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   name="waybill_sender_mobile_code"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   id="waybill_sender_mobile_code"
                                                                   @if($waybill_hd->invoice && $waybill_hd->customer)
                                                                   @if($waybill_hd->invoice->invoice_status != 121002 || $waybill_hd->invoice->invoice_status != 121003
                                                                   &&  $waybill_hd->customer->cus_type->system_code == 539)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->invoice) @if($waybill_hd->invoice->invoice_status != 121001
                                                               && $waybill_hd->waybill_payment_method==54003)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41007)
                                                                   readonly @endif
                                                                   @if($waybill_hd->trip) @if($waybill_hd->trip->status->system_code != 39001)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005)
                                                                   readonly @endif
                                                                   @if($waybill_hd->trip || $waybill_hd->invoice)
                                                                   readonly
                                                                   @endif
                                                                   v-model="customer_identity"
                                                                   placeholder="@lang('home.sender_identity')"
                                                                   required>

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="recipient-name"
                                                                   class="form-label"> @lang('home.receiver_identity') </label>
                                                            <input type="text" class="form-control is-invalid"
                                                                   style="font-size: 16px ;font-weight: bold"
                                                                   name="waybill_receiver_mobile_code"
                                                                   id="waybill_receiver_mobile_code"
                                                                   @if($waybill_hd->invoice && $waybill_hd->customer) @if($waybill_hd->invoice->invoice_status != 121001
                                                                && $waybill_hd->waybill_payment_method==54003 && $waybill_hd->customer->cus_type->system_code != 539)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41008)  readonly
                                                                   @endif
                                                                   @if($waybill_hd->invoice || $waybill_hd->trip) readonly
                                                                   @endif
                                                                   @if($waybill_hd->trip && $waybill_hd->customer) @if($waybill_hd->trip->status->system_code != 39001
                                                                && $waybill_hd->customer->cus_type->system_code != 538)
                                                                   readonly @endif @endif
                                                                   @if($waybill_hd->status->system_code == 41005)
                                                                   readonly @endif
                                                                   value="{{ $waybill_hd->waybill_receiver_mobile_code }}"
                                                                   placeholder="@lang('home.receiver_identity')"
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
                                                        style="font-size: 16px ;font-weight: bold"
                                                        name="waybill_payment_method"
                                                        id="waybill_payment_method" required
                                                        v-model="waybill_payment_method"
                                                        disabled="">
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
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_qut_received_customer"
                                                       id="waybill_qut_received_customer" readonly=""
                                                       v-model="waybill_qut_received_customer"
                                                       placeholder="@lang('waybill.car_no')">
                                            </div>


                                            {{--سعر الوحده--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_price') </label>
                                                <input type="number" class="form-control" step="0.01"
                                                       :readonly="waybill_st || user_permission"
                                                       name="waybill_item_price" v-model="waybill_item_price"
                                                       id="waybill_item_price"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       placeholder="@lang('waybill.waybill_price')">
                                                <small v-if="error_messagess" class="text-danger">@{{
                                                    error_messagess }}
                                                </small>
                                            </div>


                                            {{--سعر الاضافات--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.add_amount') </label>
                                                <input type="number" class="form-control" step="0.01"
                                                       @keyup="validateadd()"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       @if($waybill_hd->invoice)  @if($waybill_hd->invoice->invoice_status != 121001
                                                             && $waybill_hd->waybill_payment_method==54003)
                                                       readonly @endif @endif

                                                       @if($waybill_hd->trip) @if($waybill_hd->waybill_payment_method != 54003 && !auth()->user()->additionRols == 13)
                                                       readonly @endif @endif

                                                       @if($waybill_hd->status->system_code == 41005)
                                                       readonly @endif
                                                       @if($waybill_hd->waybill_type_id == 8)
                                                       readonly @endif
                                                       name="waybill_add_amount" v-model="waybill_add_amount"
                                                       id="waybill_add_amount" @change="validateadd()" min="0">
                                                <small v-if="error_messages" class="text-danger">@{{ error_messages
                                                    }}
                                                </small>
                                            </div>


                                            {{--سعر الخصومات--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.disc_amount') </label>
                                                <input type="number" class="form-control" step="0.01" min="0"
                                                       @keyup="validatedesc()"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       @change="validatedesc()"
                                                       @if($waybill_hd->invoice)  @if($waybill_hd->invoice->invoice_status != 121001
                                                  && $waybill_hd->waybill_payment_method==54003)
                                                       readonly @endif @endif
                                                       @if($waybill_hd->trip) @if($waybill_hd->waybill_payment_method != 54003 && !auth()->user()->additionRols == 13)
                                                       readonly @endif @endif
                                                       @if($waybill_hd->status->system_code == 41005)
                                                       readonly @endif
                                                       @if($waybill_hd->waybill_type_id == 8)
                                                       readonly @endif
                                                       name="waybill_discount_total"
                                                       v-model="waybill_discount_total"
                                                       id="waybill_discount_total" @change="validatedesc()" min="0">
                                                <small v-if="error_messages" class="text-danger">@{{ error_messages
                                                    }}
                                                </small>
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
                                                       id="waybill_delivery_date" disabled=""
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
                                                       name="waybill_vat_rate" step="0.01" min="0"
                                                       id="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                       placeholder="@lang('waybill.waybill_vat')" readonly>

                                            </div>

                                            {{--قيمه الضريبه--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_vat_amount') </label>
                                                <input type="number" class="form-control" readonly
                                                       v-model="waybill_item_vat_amount" step="0.01" min="0"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       name="waybill_vat_amount" id="waybill_item_vat_amount"
                                                       placeholder="@lang('waybill.waybill_vat_rate')">

                                            </div>

                                            {{--اجمالي القيمه شامله الضريبه--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_total') </label>
                                                <input type="number" class="form-control" readonly
                                                       v-model="waybill_total_amount" step="0.01" min="0"
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
                                                       id="waybill_delivery_expected" disabled=""
                                                       value="{{$waybill_hd->waybill_delivery_expected}}">
                                            </div>

                                            {{--طريقه السداد--}}
                                            <div class="col-md-2" v-if="show_payment_terms">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.pay_type') </label>
                                                <select class="form-select form-control waybill_payment_terms"
                                                        name="waybill_payment_terms"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        @if($waybill_hd->waybill_type_id == 8) disabled @endif
                                                        id="waybill_payment_terms" v-model="waybill_payment_terms">

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
                                                <input type="number" class="form-control" step="0.0001" min="0"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       @keyup="validatePaid()" min='0'
                                                       name="new_waybill_paid_amount"
                                                       v-model="new_waybill_paid_amount"
                                                       id="new_waybill_paid_amount" :disabled="validate_paid_amount"
                                                       placeholder="@lang('waybill.payment_amount')">
                                                <small class="text-danger" v-if="error_message">@{{ error_message }}
                                                </small>
                                            </div>

                                            {{--اجمالي   المتبقي--}}
                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.net_amount') </label>
                                                <input type="number" class="form-control" readonly
                                                       v-model="new_waybill_due_amount" step="0.01" min="0"
                                                       name="waybill_due_amount"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       placeholder="@lang('waybill.net_amount')">
                                                <input type="hidden" id="waybill_due_amount"
                                                       value="{{$waybill_hd->due_amount}}">
                                                <small id="error" class="text-danger"></small>
                                            </div>


                                            {{--الخصم عند الالغاء--}}
                                            <div class="col-md-2" style="display:none" id="discount_1">
                                                <label for="recipient-name"
                                                       class="form-label"> {{ __('Discount') }}</label>
                                                <input type="number" class="form-control"
                                                       v-model="waybill_discount_amount_form_paid" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold;display:none"
                                                       name="waybill_discount_amount_form_paid"
                                                       id="waybill_discount_amount_form_paid">

                                            </div>

                                            {{--الفرق بعد الخصم--}}
                                            <div class="col-md-2" style="display:none" id="discount_2">
                                                <label for="recipient-name"
                                                       class="form-label"> {{ __('After Discount') }}</label>
                                                <input type="number" class="form-control"
                                                       v-model="waybill_difference_after_discount" step="0.01"
                                                       style="font-size: 16px ;font-weight: bold;display:none"
                                                       name="waybill_difference_after_discount"
                                                       id="waybill_difference_after_discount">

                                            </div>

                                            <div class="col-md-3" style="display: none"
                                                 id="waybill_receiver_name_2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.receiver_name') </label>
                                                <input type="text" class="form-control is-invalid"
                                                       id="receiver_name" :required="receiver_required"
                                                       name="receiver_name" v-model="receiver_name">
                                            </div>

                                            @if($waybill_hd->status->system_code == 41008 && $waybill_hd->receiver_name)
                                                <div class="col-md-3"
                                                     id="waybill_receiver_name_2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('waybill.receiver_name') </label>
                                                    <input type="text" class="form-control"
                                                           id="receiver_name" readonly
                                                           name="receiver_name"
                                                           value="{{$waybill_hd->receiver_name}}">

                                                </div>
                                            @endif


                                            <div class="col-md-3" style="display: none"
                                                 id="waybill_receiver_mobile_code_2">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('home.receiver_identity') </label>
                                                <input type="number" class="form-control  is-invalid"
                                                       id="receiver_id" :required="receiver_required"
                                                       name="receiver_id" v-model="receiver_id">

                                            </div>

                                            @if($waybill_hd->status->system_code== 41008 && $waybill_hd->receiver_id)
                                                <div class="col-md-3"
                                                     id="waybill_receiver_mobile_code_2">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('home.receiver_identity') </label>
                                                    <input type="number" class="form-control"
                                                           id="receiver_id" readonly
                                                           name="receiver_id" value="{{$waybill_hd->receiver_id}}">

                                                </div>
                                            @endif

                                            

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="form-label"
                                                       style="text-decoration: underline;">@lang('waybill.waybill_loc_paid') </label>
                                                <select class="form-select form-control" name="waybill_loc_paid"
                                                        id="waybill_loc_paid"
                                                        style="font-size: 16px ;font-weight: bold"
                                                        @if($waybill_hd->trip) @if($waybill_hd->waybill_payment_method != 54003 && !auth()->user()->additionRols == 13)
                                                        disabled @endif @endif
                                                        v-model="waybill_loc_paid">
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

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.customer_notes') </label>
                                                <input type="text" class="form-control is-invalid"
                                                       style="font-size: 16px ;font-weight: bold"
                                                       @if($waybill_hd->trip) @if($waybill_hd->waybill_payment_method != 54003 && !auth()->user()->additionRols == 13)
                                                       readonly @endif @endif
                                                       name="waybill_car_notes" id="waybill_car_notes"
                                                       value="{{$waybill_dt->waybill_car_notes}}">

                                            </div>

                                        </div>


                                        <div class="row">
                                            {{--الشاحنه--}}
                                            <div @if($waybill_dt->item) @if($waybill_dt->item->system_code != 64005) hidden
                                                 @endif @endif  class="col-md-4" id="truck_data">
                                                <label for="recipient-name"
                                                       class="form-label"> @lang('waybill.waybill_truck') </label>
                                                <select class="form-control" data-live-search="true" disabled=""
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
                                                <input type="number" class="form-control"
                                                       v-model="waybill_fees_load"
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
                                                       id="waybill_fees_wait" v-model="waybill_fees_wait"
                                                       step="0.01"
                                                       placeholder="@lang('waybill.waybill_fees_wait')" disabled="">

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
                                                       disabled="">

                                            </div>


                                        </div>


                                        <div class="card bline" style="color:red">
                                        </div>


                                        @if($waybill_hd->invoice && $waybill_hd->invoice->invoice_status == 121003 &&
                                        $waybill_hd->status->system_code != 41008)
                                            <button class="btn btn-primary" type="button" id="submit"
                                                    onclick="confirmUpdate()"
                                                    v-if="edit_button" :disabled="disable_button || disable_button_2">
                                                @lang('home.save')
                                            </button>

                                            <div class="spinner-border" role="status" style="display: none">
                                                <span class="sr-only">Loading...</span>
                                            </div>

                                        @elseif($waybill_hd->trip && $waybill_hd->invoice && $waybill_hd->customer
                                        && $waybill_hd->status->system_code != 41008)
                                            @if($waybill_hd->customer->cus_type->system_code == 538)
                                                <button class="btn btn-primary" type="button" id="submit"
                                                        onclick="confirmUpdate()"
                                                        v-if="edit_button"
                                                        :disabled="disable_button || disable_button_2">
                                                    @lang('home.save')
                                                </button>

                                                <div class="spinner-border" role="status" style="display: none">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            @endif


                                        @elseif($waybill_hd->customer && $waybill_hd->status->system_code != 41008)
                                            @if($waybill_hd->customer->cus_type->system_code == 539)
                                                <button class="btn btn-primary" type="button" id="submit"
                                                        onclick="confirmUpdate()"
                                                        :disabled="disable_button || disable_button_2">@lang('home.save')
                                                </button>

                                                <div class="spinner-border" role="status" style="display: none">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            @endif


                                            <a href="{{ route('WaybillCar') }}" class="btn btn-primary"
                                               style="display: inline-block; !important;"
                                               id="back">
                                                @lang('home.exit')</a>

                                        @elseif($waybill_hd->status->system_code == 41008 && $waybill_hd->waybill_return == 2 && $waybill_hd->waybill_return_no == null)
                                            <a href="{{route('Waybill.create_back_car',$waybill_hd->waybill_id)}}"
                                               class="btn btn-success" onclick="disableButton()"
                                               id="go_back">{{__('Car Back')}}</a>
                                        @endif

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


                                        @if($waybill_hd->status->system_code == 41008 )

                                            <a href="{{config('app.telerik_server')}}?rpt={{$waybill_hd->report_url_waybill_exit->report_url}}&id={{$waybill_hd->waybill_id}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-primary"
                                               style="display: inline-block; !important; margin-right: 1px"
                                               id="print"
                                               target="_blank">
                                                اذن تسليم</a>

                                        @endif

                                        <a href="{{ route('WaybillCar') }}" class="btn btn-primary"
                                           type="submit" style="display: inline-block;margin-right: 1px"
                                           id="back">
                                            @lang('home.exit')</a>

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
                                            <td>
                                                @if($attachment->attachmentType_2)
                                                    {{ app()->getLocale()=='ar' ?
                                             $attachment->attachmentType_2->system_code_name_ar :
                                              $attachment->attachmentType_2->system_code_name_en}}
                                                @endif
                                            </td>
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
                                                <div class="badge text-gray text-wrap" style="width: 100%;">
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

                    {{--start  bond  part--}}
                    <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.bonds_number')</th>
                                            <th>@lang('home.bonds_date')</th>

                                            <th>@lang('home.branch')</th>

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


                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>

                                                <td>{{ app()->getLocale() == 'ar' ? $bond->paymentMethod->system_code_name_ar :
                                              $bond->paymentMethod->system_code_name_en }}</td>
                                                <td style="font-size: 16px ;font-weight: bold">{{ $bond->bond_amount_credit }}</td>
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


                    <div class="tab-pane fade" id="bonds-capture-grid" role="tabpanel">
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.bonds_number')</th>
                                            <th>@lang('home.bonds_date')</th>

                                            <th>@lang('home.branch')</th>

                                            <th>@lang('home.payment_method')</th>
                                            <th>@lang('home.value')</th>
                                            <th></th>
                                            <th>@lang('home.user')</th>
                                            <th>@lang('home.journal')</th>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bonds_capture as $bond)
                                            <tr>
                                                <td>{{ $bond->bond_code }}</td>
                                                <td>{{ $bond->created_date }}</td>


                                                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>

                                                <td>
                                                    @if($bond->bond_method_type)
                                                        {{ app()->getLocale() == 'ar' ? \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                        ->first()->system_code_name_ar :
                                                      \App\Models\SystemCode::where('system_code',$bond->bond_method_type)
                                                        ->first()->system_code_name_en }}
                                                    @endif
                                                </td>
                                                <td style="font-size: 16px ;font-weight: bold">{{ $bond->bond_amount_debit }}</td>
                                                <td>
                                                    <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_receipt->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.print')"><i
                                                                class="fa fa-print"></i></a>

                                                    <a href="{{ route('Bonds-capture.show',$bond->bond_id) }}"
                                                       class="btn btn-primary btn-sm"
                                                       title="@lang('home.show')"><i
                                                                class="fa fa-eye"></i></a>
                                                </td>
                                                <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                                <td>

                                                    @if($bond->journalBondInvoiceSales)
                                                        <a href="{{ route('journal-entries.show',$bond->journalBondInvoiceSales
                                                        ->journal_hd_id) }}"
                                                           class="btn btn-primary btn-sm">
                                                            @lang('home.journal_details')
                                                            {{$bond->journalBondInvoiceSales->journal_hd_code}}
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
                    {{--end bond part--}}


                    <div class="tab-pane fade" id="trips-grid" role="tabpanel">
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.trip_number')</th>
                                            <th>@lang('home.branch')</th>
                                            <th>@lang('home.trip_start_date')</th>
                                            <th>@lang('home.truck')</th>
                                            <th>@lang('home.driver_name')</th>
                                            <th style="width: 50%">@lang('home.trip_line')</th>
                                            <th style="width: 50%">@lang('home.lunch_date')</th>
                                            <th style="width: 50%">@lang('home.arrival_date')</th>
                                            <th>@lang('home.status')</th>
                                            <th style="color: red">@lang('home.waybills_number')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($waybill_hd->trip_details as $trip_detail)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('Trips.edit',$trip_detail->trip->trip_hd_id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        {{ $trip_detail->trip->trip_hd_code }}
                                                    </a>
                                                </td>
                                                <td>{{app()->getLocale() == 'ar'
                                        ? $trip_detail->trip->branch->branch_name_ar
                                        : $trip_detail->trip->branch->branch_name_en}}</td>

                                                <td style="font-size: 14px ;font-weight: bold">{{ date('d-m-y H:I', strtotime($trip_detail->trip->trip_hd_start_date)) }}</td>

                                                <td style="font-size: 14px ;font-weight: bold">@if($trip_detail->trip->truck){{$trip_detail->trip->truck->truck_name}}@endif</td>

                                                <td style="font-size: 14px ;font-weight: bold">@if($trip_detail->trip->driver) {{app()->getLocale() == 'ar'
                                        ? $trip_detail->trip->driver->emp_name_full_ar
                                        : $trip_detail->trip->driver->emp_name_full_en}} @endif</td>

                                                <td>
                                                    {{ $trip_detail->trip->tripLine ? $trip_detail->trip->tripLine->trip_line_desc : '' }}
                                                </td>

                                                <td style="font-size: 14px ;font-weight: bold;">@if($trip_detail->trip->trip_hd_started_date){{ date('d-m-y H:I', strtotime($trip_detail->trip->trip_hd_started_date)) }} @else
                                                        لم يتم انطلاق الرحله @endif</td>

                                                <td>@if($trip_detail->trip->trip_hd_ended_date){{ date('d-m-y H:I', strtotime($trip_detail->trip->trip_hd_ended_date)) }} @else
                                                        لم يتم وصول الرحله @endif</td>

                                                <td>
                                                    <span class="tag tag-success">
                                                                    @if($trip_detail->trip->status)
                                                            {{   app()->getLocale() == 'ar'
                                                       ? $trip_detail->trip->status->system_code_name_ar
                                                       : $trip_detail->trip->status->system_code_name_en}}
                                                        @endif
                                                </span>

                                                </td>
                                                <td style="color: red">{{$trip_detail->trip->tripdts->count()}}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="invoices-grid" role="tabpanel">
                        <div class="card-body">
                            <div class="row card">
                                <div class="table-responsive table_e2">
                                    <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>{{__('invoice number')}}</th>
                                            <th>{{__('Invoice Value')}}</th>
                                            <th>{{__('Invoice Type')}}</th>
                                            <th>{{__('Invoice Date')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($waybill_hd->invoiceDts as $invoice_dt)
                                            <tr>
                                                <td>
                                                    <a class="btn btn-link" target="_blank"
                                                       href="{{route('invoices.show',$invoice_dt->invoiceHd->invoice_id)}}">
                                                        @if($invoice_dt->invoiceHd->invoice_type == 8)
                                                            <a class="btn btn-link" target="_blank"
                                                               href="{{route('invoices-credit.show',$invoice_dt->invoiceHd->invoice_id)}}">
                                                                {{$invoice_dt->invoiceHd->invoice_no}}
                                                            </a>
                                                        @else
                                                            <a class="btn btn-link" target="_blank"
                                                               href="{{route('invoices.Cars.show',$invoice_dt->invoiceHd->invoice_id)}}">
                                                                {{$invoice_dt->invoiceHd->invoice_no}}
                                                            </a>
                                                        @endif
                                                    </a>
                                                </td>
                                                <td>{{$invoice_dt->invoiceHd->invoice_amount}}</td>
                                                <td>
                                                    @if($invoice_dt->invoiceHd->invoice_type == 9)
                                                        <p>فاتوره سياره</p>
                                                    @endif

                                                    @if($invoice_dt->invoiceHd->invoice_type == 8)
                                                        <p>فاتوره مرتجع</p>
                                                    @endif

                                                </td>
                                                <td>{{$invoice_dt->invoiceHd->invoice_date}}</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade @if(\Session::has('cars')) active show @endif" id="cars-grid"
                         role="tabpanel">
                        <div class="card-body">
                            <div class="row card">
                                <form action="{{route('Waybill.car-updateCars')}}" method="post">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">@lang('waybill.car_chase')</th>
                                            <th scope="col">@lang('waybill.car_desc')</th>
                                            <th scope="col">@lang('waybill.car_owner')</th>
                                            <th scope="col">@lang('waybill.car_color') </th>
                                            <th scope="col">@lang('waybill.car_model')</th>
                                            <th scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($waybill_hd->waybillCarDts as $k=>$waybillCarDt)
                                            @csrf
                                            <input type="hidden" value="{{$waybillCarDt->waybill_dt_id}}"
                                                   name="waybill_dt_id[]">
                                            <tr>
                                                <td>{{++$k}}</td>
                                                <td><input type="text" value="{{$waybillCarDt->waybill_car_chase}}"
                                                           class="form-control" name="waybill_car_chase[]"
                                                           @if($waybill_hd->status->system_code == 41008) readonly @endif>
                                                </td>
                                                <td><input type="text" value="{{$waybillCarDt->waybill_car_desc}}"
                                                           class="form-control" name="waybill_car_desc[]"
                                                           @if($waybill_hd->status->system_code == 41008) readonly @endif>
                                                </td>
                                                <td><input type="text" value="{{$waybillCarDt->waybill_car_owner}}"
                                                           class="form-control" name="waybill_car_owner[]"
                                                           @if($waybill_hd->status->system_code == 41008) readonly @endif>
                                                </td>
                                                <td><input type="text" value="{{$waybillCarDt->waybill_car_color}}"
                                                           class="form-control" name="waybill_car_color[]"
                                                           @if($waybill_hd->status->system_code == 41008) readonly @endif>
                                                </td>
                                                <td><input type="text" value="{{$waybillCarDt->waybill_car_model}}"
                                                           class="form-control" name="waybill_car_model[]"
                                                           @if($waybill_hd->status->system_code == 41008) readonly @endif>
                                                </td>

                                                <td>
                                                    @if($waybill_hd->status->system_code != 41008)
                                                        <button type="button" class="btn btn-primary"
                                                                @click="deleteCar('{{$waybillCarDt->waybill_dt_id}}')">
                                                            <i class="fa fa-trash"></i></button>
                                                    @endif
                                                </td>
                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>

                                    @if($waybill_hd->status->system_code != 41008)
                                        <button type="submit" class="btn btn-primary">@lang('home.save')</button>
                                    @endif

                                </form>
                            </div>
                        </div>
                    </div>

                    {{--   take photos  --}}
                    <div class="tab-pane fade" id="photos-grid" role="tabpanel">
                        <div class="card-body">
                            <div class="row card">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <form action="{{route('Waybill.storePhoto')}}" method="post"
                                          enctype="multipart/form-data" class="m-3">
                                        @csrf
                                        <h5>{{__('Take Photo')}}</h5>
                                        <input type="hidden" name="waybill_id" value="{{$waybill_hd->waybill_id}}">
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
                                                     src="{{ asset('Files/'.$photo_attachment->attachment_file_url) }}"
                                                     alt="">
                                            </a>
                                            <div class="d-flex align-items-center px-2">
                                                <img class="avatar avatar-md mr-3"
                                                     src="{{ asset('Files/'.$photo_attachment->attachment_file_url) }}"
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
    </div>
@endsection

@section('scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script>
        function disableButton() {
            $('#go_back').css("pointer-events", "none");
        }

        function confirmUpdate() {
            $('#myModal').modal('show')
            if ($('#waybill_item_price').val() <= 0 || $('#waybill_total_amount').val() <= 0) {
                $('#waybill_text').text('القيم غير صحيحه لا يمكن الحفظ');
                $('#modal_button').attr('disabled', 'disabled')
            } else {
                $('#waybill_text').text('هل انت متاكد من حفظ بوليصة الشحن  ' + ' ' + ' و طريقه السداد ' + ' ' + '<<<' + $('#waybill_payment_terms option:selected').text() + '>>>' + ' بقيمه سداد ' + '<<<' + $('#new_waybill_paid_amount').val() + '>>>' + ' ريال')
            }
        }

        $(document).ready(function () {


            $('#modal_button').click(function () {
                $('#modal_button').css('display', 'none')
            });

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

            $('#waybill_status').change(function () {
                if ($('#waybill_status').val() == 41005) {
                    $('#waybill_discount_amount_form_paid').css('display', 'block');
                    $('#waybill_difference_after_discount').css('display', 'block');
                    $('#discount_1').css('display', 'block');
                    $('#discount_2').css('display', 'block');
                } else {
                    $('#waybill_discount_amount_form_paid').val('');
                    $('#waybill_difference_after_discount').val('');


                    $('#waybill_discount_amount_form_paid').css('display', 'none');
                    $('#waybill_difference_after_discount').css('display', 'none');
                    $('#discount_1').css('display', 'none');
                    $('#discount_2').css('display', 'none');
                }

                if ($('#waybill_status').val() == 41008) {
                    $('#waybill_receiver_name_2').css('display', 'block')
                    $('#waybill_receiver_mobile_code_2').css('display', 'block')
                } else {
                    $('#waybill_receiver_name_2').css('display', 'none')
                    $('#waybill_receiver_mobile_code_2').css('display', 'none')
                }

                if ($('#waybill_status').val() == 41008 && $('#waybill_payment_method').val() == 54002) {
                    if (parseFloat($('#new_waybill_paid_amount').val()) != parseFloat($('#waybill_due_amount').val())) {
                        $('#submit').prop('disabled', 'true')
                        $('#error').text('المسدد غير صحيح')

                    } else {
                        $('#submit').removeAttr('disabled')
                        $('#error').text('')
                    }
                } else {
                    $('#submit').removeAttr('disabled')
                    $('#error').text('')
                }
            });


            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('#back').css('display', 'block')
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

            if ($('#waybill_item_id').val() == 64005) {

                $('#truck_data').prop('hidden', false)

                $('#trip_driver').prop('hidden', false)

                $('#driver_rad').prop('hidden', false)

                $('#driver_fees').prop('hidden', false)


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


            if ($('#waybill_item_id').val() != 64005) {

                $('#truck_data').prop('hidden', true)
                $('#trip_driver').prop('hidden', true)
                $('#driver_rad').prop('hidden', true)
                $('#driver_fees').prop('hidden', true)

            }

            if ($('#waybill_payment_method').val() == 54001) {

                $('#waybill_payment_terms').prop('required', true)
                $('#waybill_payment_terms').addClass('is-invalid')

                $('#waybill_paid_amount').prop('required', true)
                $('#waybill_paid_amount').addClass('is-invalid')

            }


            if ($('#waybill_payment_method').val() != 54001) {

                $('#waybill_payment_terms').prop('required', false)
                $('#waybill_payment_terms').removeClass('is-invalid')

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

                //  $('#waybill_loc_to').prop('required', true)
                //  $('#waybill_loc_to').addClass('is-invalid')

                $('#waybill_payment_method').prop('required', true)
                $('#waybill_payment_method').addClass('is-invalid')

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

                $('#waybill_ticket_no').addClass('is-invalid')

                $('#waybill_item_price').prop('required', true)
                $('#waybill_item_price').addClass('is-invalid')
                // $('#waybill_loc_to').prop('required', true)
                // $('#waybill_loc_to').addClass('is-invalid')
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
        }


    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                waybill_hd:{!! $waybill_hd !!},
                waybill_dt:{!! $waybill_dt !!},
                waybill_item:{!! $waybill_item !!},
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

                item_id: '',
                truck_id: '',
                driver: {},
                customer_type_obj: {},
                count: '',
                waybill_car_chase: '',
                waybill_car_plate: '',
                waybill_code: true,

                disable_button: false,
                error_message: '',
                error_messages: '',
                error_messagess: '',
                error_messagea: '',

                edit_button: true,
                update_form: true,
                new_waybill_paid_amount: 0,
                show_payment_terms: true,

                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                receiver_name: '',
                receiver_id: '',
                waybill_discount_amount_form_paid: 0,
                waybill_status: '{{$waybill_status}}',
                waybill_loc_paid: '',
                contract_error: '',
                customer_contract: '',
                contracts_list: {},
                waybill_st: true,
                customer_change: false,
                customer_identity: '',
                customer_mobile: '',
                customer_name: '',
                user_permission: '{{$user_permission}}'

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


                this.company_id = this.waybill_hd.company_id
                this.waybill_item_id = this.waybill_item.system_code
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
                this.waybill_loc_paid = this.waybill_hd.waybill_loc_paid
                this.truck_id = this.waybill_hd.waybill_truck_id
                this.waybill_discount_total = this.waybill_dt.waybill_discount_total
                this.waybill_payment_method = this.waybill_hd.waybill_payment_method
                this.waybill_payment_terms = this.waybill_hd.waybill_payment_terms
                this.waybill_car_chase = this.waybill_dt.waybill_car_chase
                this.waybill_car_plate = this.waybill_dt.waybill_car_plate
                this.customer_contract = this.waybill_hd.customer_contract
                this.waybill_item_vat_rate = this.waybill_hd.waybill_vat_rate

                this.customer_identity = this.waybill_hd.waybill_sender_mobile_code
                this.customer_mobile = this.waybill_hd.waybill_sender_mobile
                this.customer_name = this.waybill_hd.waybill_sender_name


                if (this.waybill_payment_method == 54003 && !this.waybill_hd.waybill_invoice_id) {
                    this.waybill_st = false
                } else {
                    this.waybill_st = true
                }
                // this.waybill_item_vat_amount = this.waybill_hd.waybill_vat_amount

///methods
                if (this.waybill_payment_method == 54003) {
                    this.new_waybill_paid_amount == true
                    this.show_payment_terms = false
                }
                this.getcustomertype()
                if (this.truck_id) {
                    this.getDriver()
                    this.getCountWaybillsDaily()
                }
                this.getTrucks()

            }
            ,
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
                getContractsList() {
                    this.contract_error = ''
                    this.customer_contract = ''
                    this.contracts_list = {}
                    this.customer_change = true
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
                deleteCar(id) {
                    $.ajax({
                        type: 'POST',
                        data: {"_token": "{{ csrf_token() }}", waybill_dt_id: id},
                        url: '{{ route('Waybill.delete_car') }}'
                    }).then(response => {
                        window.location.reload()
                    })
                }
                ,
                validateadd() {
                    this.disable_button = false
                    this.error_message = ''
                    if (this.waybill_add_amount == '' || this.waybill_add_amount < 0) {

                        this.disable_button = true
                        this.error_messages = '  المبلغ غير مسموح بة'
                    } else {
                        this.disable_button = false
                        this.error_messagea = ''
                        this.error_messages = ''
                    }
                }
                ,

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
                validatePaid() {
                    this.disable_button = false
                    this.error_message = ''

                    if (this.waybill_payment_method == 54003) {
                        this.new_waybill_paid_amount = 0
                    }

                    if (parseFloat(this.new_waybill_paid_amount) > parseFloat(this.waybill_due_amount)) {
                        this.disable_button = true
                        this.error_message = 'المسدد اكبر من الاجمالي'
                    }

                    else if (this.waybill_status == 41004 && this.waybill_payment_method == 54001) {
                        if (this.new_waybill_paid_amount != this.waybill_total_amount || this.new_waybill_paid_amount == 0) {
                            this.disable_button = true
                            this.error_message = 'المسدد لا يساوي الاجمالي'
                        }
                    } else if (this.waybill_status == 41008 && this.waybill_payment_method == 54002) {
                        if (this.new_waybill_paid_amount != this.waybill_total_amount || this.new_waybill_paid_amount == 0) {
                            this.disable_button = true
                            this.error_message = 'المسدد لا يساوي الاجمالي'
                        }
                    }
                }
                ,
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
                    $.ajax({
                        type: 'GET',
                        data: {truck_id: this.truck_id},
                        url: '{{ route("api.waybill.truck.driver") }}'
                    }).then(response => {
                        this.driver = response.data
                        this.waybill_driver_id = response.data.emp_id
                        this.getCountWaybillsDaily()
                    })
                }
                ,
                getcustomertype() {
                    this.customer_name = ''
                    this.customer_mobile = ''
                    this.customer_identity = ''
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.customer_id},
                        url: '{{ route("api.waybill.customer.type") }}'
                    }).then(response => {
                        this.customer_type_ar = response.data.system_code_name_ar
                        this.customer_type_en = response.data.system_code_name_en

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
                    })
                }
                ,
                getCountWaybillsDaily() {
                    if (this.waybill_item_id == 64001 || this.waybill_item_id == 64002 || this.waybill_item_id == 64003
                        || this.waybill_item_id == 64004) {
                        this.count = ''
                        this.waybill_driver_id = ''
                        this.driver = {}
                        this.truck_id = ''
                    }

                    if (this.waybill_item_id == 64005 && this.truck_id) {
                        $.ajax({
                            type: 'GET',
                            data: {waybill_driver_id: this.waybill_driver_id},
                            url: '{{ route("api.waybill.getCountWaybillsDaily") }}'
                        }).then(response => {
                            this.count = parseInt(response.data) + 1
                        })
                    }

                }
                ,
                getPriceList() {
                    this.waybill_item_price = 0
                    this.waybill_fees_difference = 0
                    this.error_messagess = ''
                    this.disable_button = false

                    if (this.customer_contract && this.waybill_loc_to && this.waybill_loc_from && this.waybill_item_id) {
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
                            this.waybill_distance = response.distance

                            if (this.waybill_item_price > 0) {
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

                            } else if (this.waybill_item_price <= 0) {
                                this.disable_button = true
                                this.error_messagess = 'يجب تحديد سعر الشحن'
                            }

                        })
                    }
                }


            }
            ,
            computed:
                {
                    disable_button_2: function () {
                        if (parseFloat(this.waybill_discount_total) > parseFloat(this.waybill_fees_difference) || parseFloat(this.waybill_discount_total) < 0) {
                            return true;
                        } else {
                            return false;
                        }
                    },
                    waybill_item_vat_amount: function () {
                        var x = parseFloat(this.waybill_item_vat_rate) * ((parseFloat(this.waybill_item_quantity) *
                            parseFloat(this.waybill_item_price)) + parseFloat(this.waybill_add_amount) - parseFloat(this.waybill_discount_total))
                        return x.toFixed(2)
                    }
                    ,
                    waybill_sub_total_amount: function () {
                        var y = parseFloat(this.waybill_add_amount)
                            + (parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price))
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
                    // if (this.waybill_payment_method == 54001 || this.waybill_payment_method == 54002) {
                    //     if (this.waybill_status == 41007) {
                    //         return true;
                    //     } else {
                    //         return false;
                    //     }
                    // }
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
                waybill_difference_after_discount: function () {
                    return this.waybill_paid_amount - this.waybill_discount_amount_form_paid
                }
                ,
                receiver_required: function () {
                    if (this.waybill_status == 41008) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        })
    </script>

@endsection
