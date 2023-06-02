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
                                        @lang('waybill.edit_waybill')
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

                    {{-- Form To Create Waybill--}}
                    <form class="card" id="validate-form" action="{{ route('Waybill.update',$waybill_id) }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="card-body">
                            {{--inputs data--}}
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('trucks.sub_company') </label>
                                            <input type="text" class="form-control" :value="company.company_name_ar"
                                                   readonly>
                                        </div>

                                        <div class="col-md-3">
                                            {{-- حاله الشحنه --}}
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_status') </label>
                                            <select class="form-select form-control" name="waybill_status"
                                                    v-model="waybill_status"
                                                    id="waybill_status" required onchange="addPropReq()">
                                                <option value="" selected>@lang('home.choose')</option>
                                                @foreach($sys_codes_waybill_status as $sys_code_waybill_status)
                                                    <option value="{{$sys_code_waybill_status->system_code_id}}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_waybill_status->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_waybill_status->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('waybill.waybill_no')</label>
                                                <input type="text" class="form-control" name="waybill_code"
                                                       v-model="waybill_code"
                                                       id="waybill_code" readonly>
                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('home.date')</label>
                                                <input type="text" class="form-control" name="waybill_date"

                                                       id="waybill_date" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="col-form-label">@lang('home.user')</label>
                                                <input type="text" readonly class="form-control"
                                                       value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                                       @else {{ auth()->user()->user_name_en }} @endif">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        {{--اسم المورد--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="recipient-name"
                                                       class="col-form-label"
                                                       style="text-decoration: underline;"> @lang('trucks.truck_supplier') </label>
                                                <select class="form-select form-control" name="supplier_id"
                                                        id="supplier_id" v-model="supplier_id"
                                                        @change="getPhoneNumber()"
                                                        :class="{'is-invalid' : supplier_id }">
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

                                        {{--محطه الشحن--}}
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.waybill_from') </label>
                                            <select class="form-select form-control" name="waybill_loc_from"
                                                    id="waybill_loc_from" v-model="waybill_loc_from"
                                                    :class="{'is-invalid' : waybill_loc_from}">
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

                                        {{--تاريخ التحميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_loaded') </label>
                                            <input type="datetime-local" class="form-control" name="waybill_load_date"
                                                   id="waybill_load_date" v-model="waybill_load_date"
                                                   :class="{'is-invalid' : waybill_load_date}"
                                                   placeholder="@lang('waybill.waybill_date_loaded')">
                                        </div>

                                    </div>

                                    <div class="row">

                                        {{--السائق--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_driver') </label>


                                            <input type="hidden" name="waybill_driver_id" :value="driver.emp_id">

                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control" readonly
                                                       :value="driver.emp_name_full_ar">
                                            @else
                                                <input type="text" class="form-control" readonly
                                                       :value="driver.emp_name_full_en">
                                            @endif
                                            <small class="text-danger" v-if="error_driver">@{{ error_driver }}</small>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.issue_number') </label>
                                            <input type="text" class="form-control"
                                                   :value="driver.issueNumber"
                                                   name="issueNumber" :readonly="error_driver ? true : false">
                                            <small class="text-danger" v-if="error_driver">@{{ error_driver }}</small>
                                        </div>


                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.identity') </label>
                                            <input type="text" class="form-control"
                                                   :value="driver.emp_identity"
                                                   name="emp_identity" :readonly="error_driver ? true : false">

                                            <small class="text-danger" v-if="error_driver">@{{ error_driver }}</small>
                                        </div>

                                        {{--الشاحنه--}}
                                        <div class="col-md-5">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_truck')
                                                <span class="text-success">
                                                    {{ $way_bill->truck->truck_code }} //
                                                    {{ $way_bill->truck->truck_name }}
                                                    // {{ $way_bill->truck->truck_plate_no}}

                                                </span></label>
                                            <select class="form-select form-control"
                                                    name="waybill_truck_id" id="waybill_truck_id"
                                                    @change="getDriver()" v-model="waybill_truck_id">
                                                @foreach($trucks as $truck)
                                                    <option value="{{$truck->truck_id}}"
                                                            @if($way_bill->waybill_truck_id == $truck->truck_id)
                                                            selected @endif>
                                                        {{ $truck->truck_code}} //
                                                        {{ $truck->truck_name}} //
                                                        {{ $truck->truck_plate_no}}

                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>

                                    <div class="row">

                                        {{-- رقم التذكره--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_ticket_no') </label>
                                            <input type="number" class="form-control" v-model="waybill_ticket_no"
                                                   name="waybill_ticket_no" id="waybill_ticket_no"
                                                   :class="{'is-invalid' : waybill_ticket_no}"
                                                   placeholder="@lang('waybill.waybill_ticket_no')">
                                        </div>

                                        {{--الكميه المطلوبه--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_qut_request') </label>
                                            <input type="number" class="form-control"
                                                   v-model="waybill_qut_requried_supplier"
                                                   :class="{'is-invalid' : waybill_qut_requried_supplier}"
                                                   name="waybill_qut_requried_supplier"
                                                   id="waybill_qut_requried_supplier" step="0.01"
                                                   placeholder="@lang('waybill.waybill_qut_request')">
                                        </div>

                                        {{--الكميه المشحونه--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_qut') </label>
                                            <input type="number" class="form-control"
                                                   :class="{'is-invalid'  : waybill_qut_received_supplier}"
                                                   name="waybill_qut_received_supplier"
                                                   v-model="waybill_qut_received_supplier"
                                                   id="waybill_qut_received_supplier"
                                                   placeholder="@lang('waybill.waybill_qut')" step="0.01">
                                        </div>

                                        {{--تاريخ التفريغ--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_receved') </label>
                                            <input type="datetime-local" class="form-control" name="waybill_unload_date"
                                                   id="waybill_unload_date" v-model="waybill_unload_date"
                                                   :class="{'is-invalid' : waybill_unload_date}"
                                                   placeholder="@lang('waybill.waybill_date_receved')">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_item') </label>
                                            <select class="form-select form-control waybill_item_id"
                                                    name="waybill_item_id" :class="{'is-invalid' : waybill_item_id}"
                                                    id="waybill_item_id" required v-model="waybill_item_id">
                                                <option value="" selected></option>
                                                @foreach($sys_codes_item as $sys_code_item)
                                                    <option value="{{$sys_code_item->system_code_id}}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_item->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_item->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        {{--الكميه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   step="0.01"> @lang('waybill.waybill_qut_actual') </label>
                                            <input type="number" class="form-control" step="0.01" readonly
                                                   v-model="qut_actual" id="waybill_item_quantity_supplier"
                                                   placeholder="@lang('waybill.waybill_qut_actual')">
                                        </div>

                                        {{--سعر الوحده للمورد--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_price') </label>
                                            <input type="number" class="form-control" v-model="waybill_price_supplier"
                                                   name="waybill_price_supplier" id="waybill_price_supplier"
                                                   :class="{'is-invalid' : waybill_price_supplier}" step="0.0001"
                                                   placeholder="@lang('waybill.waybill_price')">
                                        </div>

                                        {{--نسبه الضريبه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat') </label>
                                            <input type="number" class="form-control"
                                                   id="waybill_item_vat_rate_supplier"  step="0.01"
                                                   :class="{'is-invalid' : waybill_item_vat_rate}"
                                                   name="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                   placeholder="@lang('waybill.waybill_vat')">

                                        </div>

                                        {{--اجمالي الضريبه للمورد--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   step="0.01"> @lang('waybill.waybill_vat_amount') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_vat_amount_supplier" readonly
                                                   v-model="waybill_vat_amount_supplier"
                                                   placeholder="@lang('waybill.waybill_vat_amount')">

                                        </div>

                                        {{-- الاجمالي --}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_total') </label>
                                            <input type="number" class="form-control" step="0.01" readonly
                                                   name="waybill_amount_supplier" v-model="waybill_amount_supplier"
                                                   placeholder="@lang('waybill.waybill_total')">

                                        </div>

                                    </div>


                                    <div class="card bline" style="color:red">
                                    </div>


                                    {{--العميل--}}
                                    <div class="row">

                                        {{-- العميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                            > @lang('waybill.customer_name')
                                                <span class="text-red">
                                                 
                                                    {{  optional($way_bill->customer)->customer_name_full_ar }}
                                                    

                                                </span></label>
                                            <select name="customer_id"
                                                    class="selectpicker" data-live-search="true"
                                                    id="customer_id" v-model="customer_id"
                                                    @change="getPhoneNumber2()"
                                                    :class="{'is-invalid' : customer_id}">
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


                                        {{--محطه التفريغ--}}
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   style="text-decoration: underline;"> @lang('waybill.waybill_to') </label>
                                            <select class="form-select form-control" name="waybill_loc_to"
                                                    id="waybill_loc_to" v-model="waybill_loc_to"
                                                    :class="{'is-invalid' : waybill_loc_to}">
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

                                        {{--تاريخ الوصول المتوقع--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_expected') </label>
                                            <input type="datetime-local" class="form-control"
                                                   name="waybill_delivery_expected"
                                                   :class="{'is-invalid' : waybill_delivery_expected}"
                                                   id="waybill_delivery_expected" v-model="waybill_delivery_expected"
                                                   placeholder="@lang('waybill.waybill_date_expected')">
                                        </div>
                                    </div>

                                    <div class="row">

                                        {{--العقد--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.customer_contract') </label>
                                            <input type="text" class="form-control" v-model="customer_contract"
                                                   name="customer_contract" id="customer_contract"
                                                   :class="{'is-invalid' : customer_contract}"
                                                   placeholder="@lang('waybill.customer_contract')">
                                        </div>

                                        {{--الكميه المطلوبه للعميل--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"
                                                   step="0.01"> @lang('waybill.waybill_qut_request') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_qut_requried_customer"
                                                   id="waybill_qut_requried_customer"
                                                   :class="{'is-invalid': waybill_qut_requried_customer}"
                                                   v-model="waybill_qut_requried_customer" step="0.01"
                                                   placeholder="@lang('waybill.waybill_qut_request')">
                                        </div>

                                        {{--الكميه المستلمه--}}
                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_qut_receved') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_qut_received_customer"
                                                   id="waybill_qut_received_customer"
                                                   v-model="waybill_qut_received_customer"
                                                   :class="{'is-invalid' : waybill_qut_received_customer}" step="0.01"
                                                   placeholder="@lang('waybill.waybill_qut_receved')">
                                        </div>

                                        {{--تاريخ التسليم--}}
                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('waybill.waybill_date_end') </label>
                                            <input type="datetime-local" class="form-control"
                                                   name="waybill_delivery_date"
                                                   :class="{'is-invalid' : waybill_delivery_date}"
                                                   id="waybill_delivery_date" v-model="waybill_delivery_date"
                                                   placeholder="@lang('waybill.waybill_date_end')">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_item') </label>
                                            <select class="form-select form-control waybill_item_id" name="truck_type"
                                                    id="truck_type" v-model="waybill_item_id"
                                                    :class="{'is-invalid' : waybill_item_id}">
                                                <option value="" selected></option>
                                                @foreach($sys_codes_item as $sys_code_item)
                                                    <option value="{{$sys_code_item->system_code_id}}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_item->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_item->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        {{--الكميه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_qut_actual') </label>
                                            <input type="number" class="form-control" step="0.01"
                                                   v-model="waybill_item_quantity"
                                                   name="waybill_item_quantity" id="waybill_item_quantity" readonly
                                                   placeholder="@lang('waybill.waybill_qut_actual')">

                                        </div>


                                        {{--سعر الوحده--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_price') </label>
                                            <input type="number" class="form-control"
                                                   :class="{'is-invalid' : waybill_item_price}"
                                                   name="waybill_item_price" v-model="waybill_item_price"
                                                   id="waybill_item_price" placeholder="@lang('waybill.waybill_price')"
                                                   step="0.0001">
                                        </div>

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybil_item_unit') </label>
                                            <select class="form-select form-control is-invalid" name="waybill_item_unit"
                                                    id="waybill_item_unit" v-model="waybill_item_unit">
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

                                        <div class="col-md-1">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat') </label>
                                            <input type="number" class="form-control"
                                                   name="waybill_item_vat_rate"
                                                   :class="{'is-invalid' : waybill_item_vat_rate}"  step="0.01"
                                                   id="waybill_item_vat_rate" v-model="waybill_item_vat_rate"
                                                   placeholder="@lang('waybill.waybill_vat')">

                                        </div>

                                        {{--اجمالي الضريبه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat_amount') </label>
                                            <input type="number" class="form-control" step="0.01" readonly
                                                   v-model="waybill_item_vat_amount"
                                                   name="waybill_item_vat_amount" id="waybill_item_vat_amount"
                                                   placeholder="@lang('waybill.waybill_vat_amount')">

                                        </div>

                                        {{--الاجمالي شامل الضريبه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_total') </label>
                                            <input type="number" class="form-control" step="0.01" readonly
                                                   v-model="waybill_sub_total_amount"
                                                   name="waybill_sub_total_amount" id="waybill_sub_total_amount"
                                                   placeholder="@lang('waybill.waybill_total')">

                                        </div>

                                    </div>

                                    {{--اسعار الاضافات--}}
                                    <div class="row">

                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_load') </label>
                                            <input type="number" class="form-control" v-model="waybill_fees_load"
                                                   name="waybill_fees_load" id="waybill_fees_load"
                                                   :class="{'is-invalid' : waybill_fees_load}" step="0.0001"
                                                   placeholder="@lang('waybill.waybill_fees_load')">

                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_fees_wait') </label>
                                            <input type="number" class="form-control" name="waybill_fees_wait"
                                                   id="waybill_fees_wait" v-model="waybill_fees_wait"
                                                   :class="{'is-invalid' : waybill_fees_wait}" step="0.0001"
                                                   placeholder="@lang('waybill.waybill_fees_wait')">

                                        </div>

                                        {{--فروقات التحميل--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_differences') </label>
                                            <input type="number" class="form-control" name="waybill_fees_difference"
                                                   id="waybill_fees_difference" v-model="waybill_fees_difference"
                                                   :class="{'is-invalid' : waybill_fees_difference}" step="0.0001"
                                                   placeholder="@lang('waybill.waybill_differences')">

                                        </div>

                                        {{--قيمه الضريبه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_vat') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="waybill_item_vat_rate" step="0.01"
                                                   name="waybill_item_vat_rate" id="waybill_item_vat_rate"
                                                   placeholder="@lang('waybill.waybill_vat_rate')">

                                        </div>

                                        {{--اجمالي القيمه شامله الضريبه--}}
                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.waybill_total') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="waybill_total_fees_amount" step="0.01"
                                                   name="waybill_total_fees_amount" id="waybill_total_fees_amount"
                                                   placeholder="@lang('waybill.waybill_total')">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('waybill.total') </label>
                                            <input type="number" class="form-control" readonly
                                                   v-model="waybill_total_amount" step="0.01"
                                                   name="waybill_total_amount"
                                                   placeholder="@lang('waybill.total')">

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
                                                   id="waybill_sender_mobile" v-model="waybill_sender_mobile"
                                                   placeholder="@lang('waybill.sender_p_mobile')" required>
                                            <small v-if="sender_phone_error" class="text-danger">@{{
                                                sender_phone_error }}
                                            </small>
                                        </div>


                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="form-label"> @lang('waybill.receiver_p_mobile') </label>
                                            <input type="number" class="form-control is-invalid"
                                                   name="waybill_receiver_mobile" autocomplete="off"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   v-model="waybill_receiver_mobile"
                                                   id="waybill_receiver_mobile"
                                                   placeholder="@lang('waybill.receiver_p_mobile')"
                                                   required>
                                            <small v-if="receiver_phone_error" class="text-danger">@{{
                                                receiver_phone_error }}
                                            </small>
                                        </div>


                                    </div>


                                    <div class="card bline" style="color:red">
                                    </div>

                                    <div class="row">

                                        <button class="btn btn-primary mr-1 ml-1" type="submit" :disabled="button_dis">
                                            @lang('home.save')</button>


                                        @if($way_bill->http_status != 200)
                                            <button type="button" class="btn btn-primary mr-1 ml-1"
                                                    id="waybill{{$way_bill->waybill_id}}"
                                                    onclick="createTrip1('{{$way_bill->waybill_id}}')">
                                                توثيق الحمولة
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary mr-1 ml-1" disabled>
                                                تم توثيق الحمولة
                                            </button>

                                        @endif

                                        @if($way_bill->http_status == 200 && !$way_bill->cancel_status)
                                            <button type="button" class="btn btn-primary mr-1 ml-1"
                                                    onclick="cancelWaybill('{{$way_bill->waybill_id}}')">
                                                الغاء الوثيقه
                                            </button>
                                        @endif

                                        @if($way_bill->cancel_status ==200)
                                            <button type="button" class="btn btn-primary mr-1 ml-1" disabled="">
                                                تم الغاء الوثيقه
                                            </button>
                                        @endif


                                        @if($way_bill->http_status == 200)
                                            <button type="button" class="btn btn-primary mr-1 ml-1"
                                                    onclick="printWaybill('{{$way_bill->waybill_id}}')">
                                                طباعه
                                            </button>
                                        @endif

                                    </div>


                                    <div class="spinner-border" role="status" style="display: none">
                                        <span class="sr-only">Loading...</span>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        function createTrip1(tripId) {
            $('#waybill' + tripId).prop('disabled', 'true')
            url = '{{ route('api.Waybill.createTrip') }}';
            $.ajax({
                type: 'POST',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': tripId,
                    },

            }).done(function (data) {
                $('#waybill' + tripId).removeAttr('disabled')
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function cancelWaybill(waybill_id) {
            url = '{{ route('api.Waybill.cancelWaybill') }}';
            $.ajax({
                type: 'PUT',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': waybill_id,
                    },

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function printWaybill(waybill_id) {
            //window.open("https://www.google.com");
            url = '{{ route('api.Waybill.printWaybill') }}';
            $.ajax({
                type: 'GET',
                url: url,
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        'id': waybill_id,
                    },

            }).done(function (data) {

                if (data.success) {
                    window.open(data.msg);
                    console.log(data.msg)
                    //  location.reload();
                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        $(document).ready(function () {
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#waybill_date').val(output)

            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

        })

        function addPropReq() {
            if ($('#waybill_status').val() == 41001) {
                $('#supplier_id').prop('required', true)
                $('#supplier_id').addClass('is-invalid')
                $('#waybill_date_loaded').prop('required', true)
                $('#waybill_date_loaded').addClass('is-invalid')
                $('#waybill_loc_from').prop('required', true)
                $('#waybill_loc_from').addClass('is-invalid')
                $('#waybill_truck_id').prop('required', true)
                $('#waybill_truck_id').addClass('is-invalid')
                $('#waybill_driver_id').prop('required', true)
                $('#waybill_driver_id').addClass('is-invalid')
                $('#waybill_qut_requried_supplier').prop('required', true)
                $('#waybill_qut_requried_supplier').addClass('is-invalid')
                $('#waybill_qut_requried_customer').prop('required', true)
                $('#waybill_qut_requried_customer').addClass('is-invalid')
                $('.waybill_item_id').prop('required', true)
                $('.waybill_item_id').addClass('is-invalid')

                /////////remove required and is-invalid class
                $('#waybill_ticket_no').removeClass('is-invalid')
                $('#waybill_ticket_no').prop('required', false)
                $('#waybill_qut_received_supplier').removeClass('is-invalid')
                $('#waybill_qut_received_supplier').prop('required', false)
                $('#waybill_unload_date').removeClass('is-invalid')
                $('#waybill_unload_date').prop('required', false)
                $('#waybill_price_supplier').removeClass('is-invalid')
                $('#waybill_price_supplier').prop('required', false)
                $('#customer_id').removeClass('is-invalid')
                $('#customer_id').prop('required', false)
                $('#waybill_delivery_expected').removeClass('is-invalid')
                $('#waybill_delivery_expected').prop('required', false)
                $('#waybill_qut_received_customer').removeClass('is-invalid')
                $('#waybill_qut_received_customer').prop('required', false)
                $('#waybill_delivery_date').removeClass('is-invalid')
                $('#waybill_delivery_date').prop('required', false)
                $('#waybill_fees_load').removeClass('is-invalid')
                $('#waybill_fees_load').prop('required', false)
                $('#waybill_fees_wait').removeClass('is-invalid')
                $('#waybill_fees_wait').prop('required', false)
                $('#waybill_fees_difference').removeClass('is-invalid')
                $('#waybill_fees_difference').prop('required', false)

                $('#waybill_item_vat_rate').prop('required', false)
                $('#waybill_item_vat_rate').removeClass('is-invalid')
                $('#waybill_item_price').prop('required', false)
                $('#waybill_item_price').removeClass('is-invalid')
                $('#waybill_item_vat_rate_supplier').prop('required', false)
                $('#waybill_item_vat_rate_supplier').removeClass('is-invalid')


            }

            if ($('#waybill_status').val() == 41002) {
                $('#supplier_id').prop('required', true)
                $('#supplier_id').addClass('is-invalid')
                $('#waybill_date_loaded').prop('required', true)
                $('#waybill_date_loaded').addClass('is-invalid')
                $('#waybill_loc_from').prop('required', true)
                $('#waybill_loc_from').addClass('is-invalid')
                $('#waybill_truck_id').prop('required', true)
                $('#waybill_truck_id').addClass('is-invalid')
                $('#waybill_driver_id').prop('required', true)
                $('#waybill_driver_id').addClass('is-invalid')

                $('#waybill_qut_received_customer').prop('required', true)
                $('#waybill_qut_received_customer').addClass('is-invalid')
                $('#waybill_qut_requried_customer').prop('required', true)
                $('#waybill_qut_requried_customer').addClass('is-invalid')
                $('#waybill_qut_received_supplier').prop('required', true)
                $('#waybill_qut_received_supplier').addClass('is-invalid')
                $('#waybill_qut_requried_supplier').prop('required', true)
                $('#waybill_qut_requried_supplier').addClass('is-invalid')
                $('#customer_id').prop('required', true)
                $('#customer_id').addClass('is-invalid')

                $('.waybill_item_id').prop('required', true)
                $('.waybill_item_id').addClass('is-invalid')
                $('#waybill_loc_from').prop('required', true)
                $('#waybill_loc_from').addClass('is-invalid')

                /////////remove required and is-invalid class
                $('#waybill_ticket_no').removeClass('is-invalid')
                $('#waybill_ticket_no').prop('required', false)
                $('#waybill_unload_date').removeClass('is-invalid')
                $('#waybill_unload_date').prop('required', false)
                $('#waybill_price_supplier').removeClass('is-invalid')
                $('#waybill_price_supplier').prop('required', false)
                $('#waybill_delivery_expected').removeClass('is-invalid')
                $('#waybill_delivery_expected').prop('required', false)
                $('#waybill_delivery_date').removeClass('is-invalid')
                $('#waybill_delivery_date').prop('required', false)
                $('#waybill_fees_load').removeClass('is-invalid')
                $('#waybill_fees_load').prop('required', false)
                $('#waybill_fees_wait').removeClass('is-invalid')
                $('#waybill_fees_wait').prop('required', false)
                $('#waybill_fees_difference').removeClass('is-invalid')
                $('#waybill_fees_difference').prop('required', false)
                $('#waybill_item_vat_rate').prop('required', false)
                $('#waybill_item_vat_rate').removeClass('is-invalid')
                $('#waybill_item_price').prop('required', false)
                $('#waybill_item_price').removeClass('is-invalid')
                $('#waybill_item_vat_rate_supplier').prop('required', false)
                $('#waybill_item_vat_rate_supplier').removeClass('is-invalid')

            }

            if ($('#waybill_status').val() == 41003) {
                $('#supplier_id').prop('required', true)
                $('#supplier_id').addClass('is-invalid')
                $('#waybill_date_loaded').prop('required', true)
                $('#waybill_date_loaded').addClass('is-invalid')
                $('#waybill_loc_from').prop('required', true)
                $('#waybill_loc_from').addClass('is-invalid')
                $('#waybill_qut_requried_customer').prop('required', true)
                $('#waybill_qut_requried_customer').addClass('is-invalid')
                $('#waybill_qut_received_customer').prop('required', true)
                $('#waybill_qut_received_customer').addClass('is-invalid')
                $('#waybill_qut_requried_supplier').prop('required', true)
                $('#waybill_qut_requried_supplier').addClass('is-invalid')
                $('#waybill_qut_received_supplier').prop('required', true)
                $('#waybill_qut_received_supplier').addClass('is-invalid')
                $('#waybill_truck_id').prop('required', true)
                $('#waybill_truck_id').addClass('is-invalid')
                $('#waybill_driver_id').prop('required', true)
                $('#waybill_driver_id').addClass('is-invalid')
                $('#waybill_unload_date').prop('required', true)
                $('#waybill_unload_date').addClass('is-invalid')
                $('#customer_id').prop('required', true)
                $('#customer_id').addClass('is-invalid')

                $('#waybill_ticket_no').prop('required', true)
                $('#waybill_ticket_no').addClass('is-invalid')
                $('#waybill_item_price').prop('required', true)
                $('#waybill_item_price').addClass('is-invalid')
                $('#waybill_item_vat_rate').prop('required', true)
                $('#waybill_item_vat_rate').addClass('is-invalid')
                $('#waybill_item_vat_rate_supplier').prop('required', true)
                $('#waybill_item_vat_rate_supplier').addClass('is-invalid')
                $('#waybill_fees_difference').prop('required', true)
                $('#waybill_fees_difference').addClass('is-invalid')
                $('#waybill_fees_wait').prop('required', true)
                $('#waybill_fees_wait').addClass('is-invalid')
                $('#waybill_fees_load').prop('required', true)
                $('#waybill_fees_load').addClass('is-invalid')
                $('#waybill_price_supplier').prop('required', true)
                $('#waybill_price_supplier').addClass('is-invalid')
                $('#waybill_qut_requried_supplier').prop('required', true)
                $('#waybill_qut_requried_supplier').addClass('is-invalid')
                $('.waybill_item_id').prop('required', true)
                $('.waybill_item_id').addClass('is-invalid')


                /////////remove required and is-invalid class
                $('#waybill_delivery_expected').removeClass('is-invalid')
                $('#waybill_delivery_expected').prop('required', false)
                $('#waybill_delivery_date').removeClass('is-invalid')
                $('#waybill_delivery_date').prop('required', false)

            }

            if ($('#waybill_status').val() == 41004) {

                $('#supplier_id').prop('required', true)
                $('#supplier_id').addClass('is-invalid')
                $('#waybill_date_loaded').prop('required', true)
                $('#waybill_date_loaded').addClass('is-invalid')
                $('#waybill_loc_from').prop('required', true)
                $('#waybill_loc_from').addClass('is-invalid')
                $('#waybill_driver_id').prop('required', true)
                $('#waybill_driver_id').addClass('is-invalid')
                $('#waybill_truck_id').prop('required', true)
                $('#waybill_truck_id').addClass('is-invalid')
                $('#customer_id').prop('required', true)
                $('#customer_id').addClass('is-invalid')

                $('#waybill_ticket_no').prop('required', true)
                $('#waybill_ticket_no').addClass('is-invalid')
                $('#waybill_qut_received_customer').prop('required', true)
                $('#waybill_qut_received_customer').addClass('is-invalid')
                $('#waybill_qut_required_customer').prop('required', true)
                $('#waybill_qut_required_customer').addClass('is-invalid')
                $('#waybill_qut_requried_supplier').prop('required', true)
                $('#waybill_qut_requried_supplier').addClass('is-invalid')
                $('#waybill_qut_received_supplier').prop('required', true)
                $('#waybill_qut_received_supplier').addClass('is-invalid')
                $('#waybill_delivery_date').prop('required', true)
                $('#waybill_delivery_date').addClass('is-invalid')
                $('#waybill_fees_difference').prop('required', true)
                $('#waybill_fees_difference').addClass('is-invalid')
                $('#waybill_fees_wait').prop('required', true)
                $('#waybill_fees_wait').addClass('is-invalid')
                $('#waybill_fees_load').prop('required', true)
                $('#waybill_fees_load').addClass('is-invalid')
                $('#waybill_item_price').prop('required', true)
                $('#waybill_item_price').addClass('is-invalid')
                $('#waybill_loc_to').prop('required', true)
                $('#waybill_loc_to').addClass('is-invalid')
                $('#waybill_delivery_expected').prop('required', true)
                $('#waybill_delivery_expected').addClass('is-invalid')
                $('#waybill_price_supplier').prop('required', true)
                $('#waybill_price_supplier').addClass('is-invalid')
                $('#waybill_item_vat_rate').prop('required', true)
                $('#waybill_item_vat_rate').addClass('is-invalid')
                $('#waybill_item_vat_rate_supplier').prop('required', true)
                $('#waybill_item_vat_rate_supplier').addClass('is-invalid')
                $('.waybill_item_id').prop('required', true)
                $('.waybill_item_id').addClass('is-invalid')
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                way_bill: {},
                waybill_id:{!! $waybill_id !!},
                waybill_qut_requried_supplier: 0.00,
                waybill_qut_received_supplier: 0.00,
                waybill_item_id: '',


                company_id: '',
                waybill_status: '',
                supplier_id: '',
                waybill_loc_from: '',
                waybill_driver_id: '',
                waybill_load_date: '',
                waybill_ticket_no: '',
                waybill_truck_id: '',

                trucks: {},
                company: {},

                waybill_price_supplier: 0,
                waybill_item_vat_rate: 0,
                waybill_qut_requried_customer: 0,
                waybill_qut_received_customer: 0,
                waybill_item_price: 0.00,
                waybill_fees_wait: 0.00,
                waybill_fees_load: 0.00,
                waybill_fees_difference: 0.00,
                waybill_unload_date: '',
                customer_id: '',
                waybill_loc_to: '',
                waybill_delivery_expected: '',
                customer_contract: '',
                waybill_delivery_date: '',
                waybill_item_unit: '',
                waybill_code: '',
                driver: {},
                truck_id: '',
                error_driver: '',

                waybill_sender_mobile: '',
                waybill_receiver_mobile: '',
                button_dis: false


            },
            mounted() {
                this.getWayPill()

            },
            methods: {
                getPhoneNumber() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.supplier_id},
                        url: '{{ route("Waybill.getPhoneNumber") }}'
                    }).then(response => {
                        this.waybill_sender_mobile = response.data

                    })
                },
                getPhoneNumber2() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.customer_id},
                        url: '{{ route("Waybill.getPhoneNumber") }}'
                    }).then(response => {
                        this.waybill_receiver_mobile = response.data

                    })
                },
                getDriver() {
                    $.ajax({
                        type: 'GET',
                        data: {truck_id: this.waybill_truck_id},
                        url: '{{ route("api.waybill.truck.driver") }}'
                    }).then(response => {
                        if (response.status == 200) {
                            this.driver = response.data
                            this.error_driver = ''
                        } else {
                            this.error_driver = 'لا يوجد سائق للشاحنه'
                            this.driver = {}
                        }

                    })


                },
                getWayPill() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.waybill_id},
                        url: ''
                    }).then(response => {
                        this.way_bill = response.data
                        this.waybill_sender_mobile = this.way_bill.waybill_sender_mobile
                        this.waybill_receiver_mobile = this.way_bill.waybill_receiver_mobile
                        this.company_id = this.way_bill.company_id
                        this.company = this.way_bill.company
                        this.waybill_code = this.way_bill.waybill_code
                        this.waybill_qut_requried_supplier = this.way_bill.details_diesel[0].waybill_qut_requried_supplier ?
                            this.way_bill.details_diesel[0].waybill_qut_requried_supplier : 0
                        this.waybill_qut_received_supplier = this.way_bill.details_diesel[0].waybill_qut_received_supplier ?
                            this.way_bill.details_diesel[0].waybill_qut_received_supplier : ''
                        this.waybill_item_id = this.way_bill.details_diesel[0].waybill_item_id
                        this.waybill_status = this.way_bill.waybill_status
                        this.supplier_id = this.way_bill.supplier_id
                        this.waybill_loc_from = this.way_bill.waybill_loc_from
                        this.waybill_driver_id = this.way_bill.waybill_driver_id
                        this.driver = response.driver
                        this.customer_id = this.way_bill.customer_id ? this.way_bill.customer_id : ''
                        this.waybill_delivery_date = this.way_bill.waybill_delivery_date ? this.way_bill.waybill_delivery_date : ''
                        this.customer_contract = this.way_bill.customer_contract ? this.way_bill.customer_contract : ''
                        this.waybill_delivery_expected = this.way_bill.waybill_delivery_expected ? this.way_bill.waybill_delivery_expected : ''
                        this.waybill_loc_to = this.way_bill.waybill_loc_to ? this.way_bill.waybill_loc_to : ''
                        this.waybill_load_date = this.way_bill.waybill_load_date ? this.way_bill.waybill_load_date : ''
                        this.waybill_item_vat_rate = this.way_bill.waybill_vat_rate ? this.way_bill.waybill_vat_rate : 0
                        this.waybill_ticket_no = this.way_bill.waybill_ticket_no ? this.way_bill.waybill_ticket_no : ''
                        this.waybill_truck_id = this.way_bill.waybill_truck_id ? this.way_bill.waybill_truck_id : '',
                            this.waybill_ticket_no = this.way_bill.waybill_ticket_no ? this.way_bill.waybill_ticket_no : '',
                            this.waybill_unload_date = this.way_bill.waybill_unload_date ? this.way_bill.waybill_unload_date : '',
                            this.waybill_price_supplier = this.way_bill.details_diesel[0].waybill_price_supplier
                                ? this.way_bill.details_diesel[0].waybill_price_supplier : 0,

                            this.waybill_qut_requried_customer = this.way_bill.details_diesel[0].waybill_qut_requried_customer
                                ? this.way_bill.details_diesel[0].waybill_qut_requried_customer : 0,

                            this.waybill_qut_received_customer = this.way_bill.details_diesel[0].waybill_qut_received_customer
                                ? this.way_bill.details_diesel[0].waybill_qut_received_customer : '',

                            this.waybill_item_price = this.way_bill.details_diesel[0].waybill_item_price
                                ? this.way_bill.details_diesel[0].waybill_item_price : 0.00,

                            this.waybill_item_unit = this.way_bill.details_diesel[0].waybill_item_unit

                        this.waybill_fees_load = this.way_bill.details_diesel[1].waybill_fees_load
                            ? this.way_bill.details_diesel[1].waybill_fees_load : 0.00,

                            this.waybill_fees_wait = this.way_bill.details_diesel[1].waybill_fees_wait
                                ? this.way_bill.details_diesel[1].waybill_fees_wait : 0.00,

                            this.waybill_fees_difference = this.way_bill.details_diesel[1].waybill_fees_difference
                                ? this.way_bill.details_diesel[1].waybill_fees_difference : 0.00,

                            this.getTrucks()
                    })
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
            },
            computed: {
                //customer
                waybill_item_vat_amount: function () {
                    var x = parseFloat(this.waybill_item_vat_rate) * parseFloat(this.waybill_item_quantity) *
                        parseFloat(this.waybill_item_price)
                    return x.toFixed(2)
                },
                waybill_sub_total_amount: function () {
                    var y = parseFloat(this.waybill_item_vat_amount)
                        + (parseFloat(this.waybill_item_quantity) * parseFloat(this.waybill_item_price))
                    return y.toFixed(2)
                },
                waybill_item_quantity: function () {
                    if (this.waybill_qut_received_customer) {
                        return this.waybill_qut_received_customer
                    }
                    else if (this.waybill_qut_requried_customer) {
                        return this.waybill_qut_requried_customer
                    }
                },
                waybill_total_fees_amount: function () {
                    var a = parseFloat(this.waybill_fees_wait) +
                        parseFloat(this.waybill_fees_load) + parseFloat(this.waybill_fees_difference);
                    var t = (parseFloat(this.waybill_item_vat_rate)) * a + a
                    return t.toFixed(2)
                },
                waybill_total_amount: function () {
                    var z = parseFloat(this.waybill_total_fees_amount) + parseFloat(this.waybill_sub_total_amount)
                    return z.toFixed(2)
                },
                //supplier
                qut_actual: function () {
                    if (this.waybill_qut_received_supplier) {
                        return this.waybill_qut_received_supplier
                    } else if (this.waybill_qut_requried_supplier) {
                        return this.waybill_qut_requried_supplier
                    }
                },
                waybill_vat_amount_supplier: function () {
                    var x1 = (parseFloat(this.waybill_item_vat_rate)) * parseFloat(this.waybill_price_supplier) *
                        parseFloat(this.qut_actual)
                    return x1.toFixed(2)
                },
                waybill_amount_supplier: function () {
                    var y1 = parseFloat(this.waybill_vat_amount_supplier) +
                        (parseFloat(this.qut_actual) * parseFloat(this.waybill_price_supplier))
                    return y1.toFixed(2)
                },
                sender_phone_error: function () {
                    if (this.waybill_sender_mobile.length > 10) {
                        this.button_dis = true
                        return 'رقم الجوال اكبر من 10 ارقام'

                    } else {
                        this.button_dis = false
                        return ''
                    }
                },
                receiver_phone_error: function () {
                    console.log(this.waybill_receiver_mobile)
                    if (this.waybill_receiver_mobile.length > 10) {
                        this.button_dis = true
                        return 'رقم الجوال اكبر من 10 ارقام'

                    } else {
                        this.button_dis = false
                        return ''
                    }
                }

            }
        })
    </script>
@endsection
