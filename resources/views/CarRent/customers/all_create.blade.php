@extends('Layouts.master')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
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
                <div class="header-action"></div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                    {{-- Form To Create Customer--}}
                    <form class="card" id="validate-form" action="{{ route('car-rent.customers.store') }}"
                          method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="path" value="{{isset($path)?$path:old('path')}}">
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('customer.add_new_customer')
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    {{--نوع الهويه--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient"
                                               class="col-form-label">@lang('home.identity_type')<span
                                                class="text-danger">*</span></label>
                                        <select class="selectpicker form-control @error('id_type_code') is-invalid @enderror"
                                                name="id_type_code" id="id_type_code"
                                                data-live-search="true"
                                                v-model="id_type_code">
                                            @foreach($sys_code_identity_types as $sys_code_identity_type)
                                                <option value="{{ $sys_code_identity_type->system_code}}">
                                                    {{ app()->getLocale() == 'ar'
                                                    ?  $sys_code_identity_type->system_code_name_ar
                                                    : $sys_code_identity_type->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_type_code')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    {{--رقم الهويه--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.identity') <span class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control @error('customer_identity') is-invalid @enderror"
                                               name="customer_identity" v-model="customer_identity"
                                               @change="getCustomer()"
                                               id="customer_identity" placeholder="@lang('home.identity')"
                                               required>
                                        <small class="text-danger" v-if="error_identity">@{{ error_identity }}</small>
                                        @error('customer_identity')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    {{--نوع العميل--}}
                                    <div class="col-md-3 form-group">
                                        <label class="col-form-label">@lang('home.customer_type')<span
                                                class="text-danger">*</span></label>
                                        <select class="selectpicker form-control @error('customer_type') is-invalid @enderror"
                                                name="customer_type" data-live-search="true" required>
                                            {{--                                            <option value="" selected></option>--}}
                                            @foreach($sys_codes_type as $sys_code_type)
{{--                                                @if($sys_code_type->system_code_id == 2397)--}}
                                                    <option @if($sys_code_type->system_code_id == 2397) selected
                                                            @endif value="{{$sys_code_type->system_code_id}}">
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_code_type->system_code_name_ar}}
                                                        @else
                                                            {{$sys_code_type->system_code_name_en}}
                                                        @endif
                                                    </option>
{{--                                                @endif--}}
                                            @endforeach
                                        </select>
                                        @error('customer_type')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--تصنيف العميل--}}
                                    <div class="col-md-3 form-group">
                                        <label class="col-form-label">@lang('home.customer_classification')<span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="selectpicker form-control @error('customer_classification') is-invalid @enderror"
                                            name="customer_classification" data-live-search="true" required>
                                            {{--                                            <option value="">@lang('home.choose')</option>--}}
                                            @foreach($sys_code_classifications as $sys_code_classification)
{{--                                                @if($sys_code_classification->system_code_id == 2862)--}}
                                                    <option
                                                        @if($sys_code_classification->system_code_id == 2862) selected
                                                        @endif value="{{ $sys_code_classification->system_code_id }}">
                                                        {{ app()->getLocale() == 'ar' ?  $sys_code_classification->system_code_name_ar
                                                        : $sys_code_classification->system_code_name_en }}</option>
{{--                                                @endif--}}
                                            @endforeach
                                        </select>
                                        @error('customer_classification')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    {{--الاسم كامل عربي--}}
                                    <div class="col-md-6 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_ar') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_name_full_ar') is-invalid @enderror"
                                               disabled=""
                                               v-model="customer_name_full_ar"
                                               id="customer_name_full_ar" placeholder="@lang('home.name_ar')"
{{--                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"--}}
                                               required>
                                        @error('customer_name_full_ar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror

                                        <input type="hidden" class="form-control "
                                               name="customer_name_full_ar"
                                               v-model="customer_name_full_ar">
                                    </div>

                                    {{--الاسم كامل انجليزي--}}
                                    <div class="col-md-6 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_en') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_name_full_en') is-invalid @enderror"
                                               disabled=""
                                               v-model="customer_name_full_en"
                                               id="customer_name_full_en" placeholder="@lang('home.name_en')"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                               required>
                                        @error('customer_name_full_en')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror

                                        <input type="hidden" class="form-control "
                                               name="customer_name_full_en"
                                               v-model="customer_name_full_en">
                                    </div>
                                </div>

                                {{--تفاصيل الاسم عربي--}}
                                <div class="row">

                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.first_name_ar') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_name_1_ar') is-invalid @enderror"
                                               autocomplete="off"
                                               name="customer_name_1_ar"
                                               placeholder="@lang('home.first_name_ar')"
                                               v-model="customer_name_1_ar">
{{--                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"--}}
                                               required>
                                        @error('customer_name_1_ar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.second_name_ar') </label>
                                        <input type="text"
                                               class="form-control @error('customer_name_2_ar') is-invalid @enderror"
                                               autocomplete="off"
                                               name="customer_name_2_ar"
                                               id="customer_name_2_ar"
                                               placeholder="@lang('home.second_name_ar')"
                                               v-model="customer_name_2_ar">
{{--                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">--}}
                                        @error('customer_name_2_ar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.third_name_ar') </label>
                                        <input type="text"
                                               class="form-control @error('customer_name_3_ar') is-invalid @enderror"
                                               autocomplete="off"
                                               name="customer_name_3_ar"
                                               v-model="customer_name_3_ar"
                                               id="customer_name_3_ar" placeholder="@lang('home.third_name_ar')">
{{--                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');">--}}
                                        @error('customer_name_3_ar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.fourth_name_ar') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_name_4_ar') is-invalid @enderror"
                                               autocomplete="off"
                                               name="customer_name_4_ar"
                                               id="customer_name_4_ar" v-model="customer_name_4_ar"
                                               placeholder="@lang('home.fourth_name_ar')"
                                               required>
                                        @error('customer_name_4_ar')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                </div>

                                {{--تفاصيل الاسم انجليزي--}}
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.first_name_en') </label>
                                        <input type="text"
                                               class="form-control @error('customer_name_1_en') is-invalid @enderror"
                                               name="customer_name_1_en"
                                               autocomplete="off"
                                               placeholder="@lang('home.first_name_en')"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                        >
                                        @error('customer_name_1_en')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.second_name_en') </label>
                                        <input type="text"
                                               class="form-control @error('customer_name_2_en') is-invalid @enderror"
                                               name="customer_name_2_en" autocomplete="off"
                                               id="customer_name_2_en" v-model="customer_name_2_en"
                                               placeholder="@lang('home.second_name_en')"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                        >
                                        @error('customer_name_2_en')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.third_name_en') </label>
                                        <input type="text"
                                               class="form-control @error('customer_name_3_en') is-invalid @enderror"
                                               name="customer_name_3_en"
                                               autocomplete="off"
                                               id="customer_name_3_en" placeholder="@lang('home.third_name_en')"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                               v-model="customer_name_3_en">
                                        @error('customer_name_3_en')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.fourth_name_en') </label>
                                        <input type="text"
                                               class="form-control @error('customer_name_4_en') is-invalid @enderror"
                                               name="customer_name_4_en"
                                               autocomplete="off"
                                               id="customer_name_4_en" v-model="customer_name_4_en"
                                               placeholder="@lang('home.fourth_name_en')"
                                        >
                                        @error('customer_name_4_en')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    {{--تاريخ الميلاد ميلادي--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.customer_birthday') <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control @error('customer_birthday') is-invalid @enderror"
                                               name="customer_birthday"
                                               id="customer_birthday" v-model="customer_birthday"
                                               @change="getBirthdayDate() ; getDifferenceDate()"
                                               placeholder="@lang('home.customer_birthday')"
                                               required>
                                        @error('customer_birthday')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--تاريخ الميلاد هجري--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.customer_birthday_hijiri') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_birthday_hijiri') is-invalid @enderror"
                                               name="customer_birthday_hijiri"
                                               id="customer_birthday_hijiri"
                                               placeholder="@lang('home.customer_birthday_hijiri')"
                                               v-model="customer_birthday_hijiri"
                                               required>
                                        @error('customer_birthday_hijiri')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--السن--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_age') <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="customer_age"
                                               class="form-control @error('customer_age') is-invalid @enderror"
                                               v-model="customer_age"
                                               placeholder="@lang('home.customer_age')" readonly>
                                        <small class="text-danger" v-if="age_error">@{{age_error}}</small>
                                        @error('customer_age')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                </div>


                                <div class="row">


                                    {{--رقم الجوال--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.private_mobile')
                                            <span class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control @error('customer_mobile') is-invalid @enderror"
                                               autocomplete="off" v-model="customerMobile" name="customer_mobile"
                                               id="customer_mobile" placeholder="@lang('customer.private_mobile')"
                                               required>
                                        <small class="text-danger" v-if="mobile_error">@{{ mobile_error }}</small>
                                        @error('customer_mobile')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    {{--كود الدوله--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.country_code') <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="selectpicker form-select form-control @error('customer_mobile_code') is-invalid @enderror"
                                            name="customer_mobile_code"
                                            id="customer_mobile_code" data-live-search="true" required>
                                            {{--                                            <option value="" selected></option>--}}
                                            @foreach($sys_codes_nationality_country as $sys_code_nationality_country)
                                                <option v-if="id_type_code == 66001"
                                                        @if($sys_code_nationality_country->system_code_id == 25) selected
                                                        @endif value="{{ $sys_code_nationality_country->system_code_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{$sys_code_nationality_country->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_nationality_country->system_code_name_en}}
                                                    @endif

                                                </option>
                                                <option v-else
                                                        value="{{ $sys_code_nationality_country->system_code_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{$sys_code_nationality_country->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_nationality_country->system_code_name_en}}
                                                    @endif

                                                </option>
                                            @endforeach

                                        </select>
                                        @error('customer_mobile_code')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    {{--جنس العميل --}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.customer_gender') <span
                                                class="text-danger">*</span></label>
                                        <select class="selectpicker form-control @error('customer_gender') is-invalid @enderror"
                                                name="customer_gender" data-live-search="true"
                                                {{--                                                v-model="customer_gender"--}}
                                                id="customer_gender" required>
                                            <option value="1">@lang('home.male')</option>
                                            <option value="2">@lang('home.female')</option>
                                        </select>
                                        @error('customer_gender')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror

                                    </div>
                                    {{--الجنسيه--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.nationality') <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="selectpicker form-select form-control @error('customer_nationality') is-invalid @enderror"
                                            name="customer_nationality" data-live-search="true"
                                            id="customer_nationality" required>
                                            {{--                                            <option value="" selected></option>--}}
                                            @foreach($sys_codes_nationality_country as $sys_code_nationality_country)
                                                <option v-if="id_type_code == 66001"
                                                        @if($sys_code_nationality_country->system_code_id == 25) selected
                                                        @endif  value="{{ $sys_code_nationality_country->system_code_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{$sys_code_nationality_country->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_nationality_country->system_code_name_en}}
                                                    @endif

                                                </option>
                                                <option v-else
                                                        value="{{ $sys_code_nationality_country->system_code_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{$sys_code_nationality_country->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_nationality_country->system_code_name_en}}
                                                    @endif

                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_nationality')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                    {{--تابع لعميل شركات --}}
                                    <div hidden class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.customer_ref_no') </label>
                                        <select
                                            class="selectpicker form-select form-control @error('customer_ref_no') is-invalid @enderror"
                                            name="customer_ref_no" data-live-search="true"
                                            id="customer_mobile_code">
                                            <option value="" selected></option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->customer_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{$customer->customer_name_full_ar}}
                                                    @else
                                                        {{$customer->customer_name_full_en}}
                                                    @endif

                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_ref_no')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.system_code_acc_id') </label>
                                        <select class="form-control selectpicker"  data-live-search="true" name="customer_account_id"
                                                id="customer_account_id" >
                                            <option value="" selected>@lang('home.choose')</option>
                                            @foreach($accountL as $accountLs)
                                                <option value="{{$accountLs->acc_id}}"
                                                        @if($customer->customer_account_id == $accountLs->acc_id)
                                                            selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $accountLs->acc_name_ar }}
                                                    @else
                                                        {{ $accountLs->acc_name_en }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    {{--حاله العميل--}}
                                    {{--<div class="col-md-4">--}}
                                    {{--<label for="recipient"--}}
                                    {{--class="col-form-label"> @lang('home.status') </label>--}}
                                    {{--<select class="selectpicker form-select form-control is-invalid" name="customer_status"--}}
                                    {{--aria-label="Default select example" id="customer_status"--}}
                                    {{--required>--}}
                                    {{--@foreach($sys_codes_status as $sys_code_status)--}}
                                    {{--<option value="{{$sys_code_status->system_code_id}}">--}}
                                    {{--@if(app()->getLocale() == 'ar')--}}
                                    {{--{{$sys_code_status->system_code_name_ar}}--}}
                                    {{--@else--}}
                                    {{--{{$sys_code_status->system_code_name_en}}--}}
                                    {{--@endif--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--</div>--}}
                                </div>

                                <div class="row">
                                    {{--عنوان المنزل--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_address_home') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_address_1') is-invalid @enderror"
                                               name="customer_address_1"
                                               id="customer_address_1" autocomplete="off"
                                               placeholder="@lang('home.customer_address_home')"
                                               required>
                                        @error('customer_address_1')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--تليفون المنزل--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.phone_home') <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control @error('customer_phone_home') is-invalid @enderror"
                                               name="customer_phone_home"
                                               id="customer_phone_home" autocomplete="off"
                                               placeholder="@lang('home.phone_home')" required>
                                        @error('customer_phone_home')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--الوظيفه--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.job') </label>
                                        <input type="text"
                                               class="form-control @error('customer_job') is-invalid @enderror"
                                               name="customer_job" autocomplete="off"
                                               id="customer_job" placeholder="@lang('home.job')"
                                        >
                                        @error('customer_job')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row">


                                    {{--عنوان العمل--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_address_job') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_address_2') is-invalid @enderror"
                                               name="customer_address_2"
                                               autocomplete="off"
                                               id="customer_address_2" placeholder="@lang('home.customer_address_job')"
                                               required>
                                        @error('customer_address_2')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--تليفون العمل--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.work_mobile') </label>
                                        <input type="number"
                                               class="form-control @error('customer_phone') is-invalid @enderror"
                                               name="customer_phone"
                                               autocomplete="off"
                                               id="customer_phone" placeholder="@lang('customer.work_mobile')">
                                        @error('customer_phone')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--جهه العمل--}}
                                    <div class="col-md-4 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.company') </label>
                                        <input type="text"
                                               class="form-control @error('customer_company') is-invalid @enderror"
                                               name="customer_company"
                                               autocomplete="off"
                                               id="customer_company" placeholder="@lang('home.company')"
                                        >
                                        @error('customer_company')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                </div>


                                <div class="row">
                                    {{--ايميل العميل--}}
                                    <div class="col-md-6 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.email_work') </label>
                                        <input type="email"
                                               class="form-control @error('customer_email') is-invalid @enderror"
                                               name="customer_email"
                                               id="customer_email" placeholder="@lang('customer.email_work')">
                                        @error('customer_email')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--الرقم الضريبي--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.vat_no') </label>
                                        <input type="number"
                                               class="form-control @error('customer_vat_no') is-invalid @enderror"
                                               name="customer_vat_no"
                                               autocomplete="off"
                                               id="customer_vat_no" placeholder="@lang('customer.vat_no')">
                                        @error('customer_vat_no')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>


                                    {{--نسبه الضريبه--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_vat_rate') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_vat_rate') is-invalid @enderror"
                                               name="customer_vat_rate"
                                               id="customer_vat_rate" placeholder="@lang('home.customer_vat_rate')"
                                               value="15" required>
                                        @error('customer_vat_rate')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

{{--                                    --}}{{-- الحد الائتماني--}}
{{--                                    <div class="col-md-3 form-group">--}}
{{--                                        <label for="recipient"--}}
{{--                                               class="col-form-label"> @lang('customer.lemet_balance') </label>--}}
{{--                                        <input type="number" readonly--}}
{{--                                               class="form-control @error('customer_credit_limit') is-invalid @enderror"--}}
{{--                                               name="customer_credit_limit"--}}
{{--                                               autocomplete="off" value="5000"--}}
{{--                                               id="customer_credit_limit" placeholder="@lang('customer.lemet_balance')"--}}
{{--                                               required>--}}
{{--                                        @error('customer_credit_limit')--}}
{{--                                        <div class="invalid-feedback">--}}
{{--                                            {{$message}}--}}
{{--                                        </div>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}

                                </div>

                                <div class="row">

                                </div>

                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        id="create_emp" :disabled="disable_button"
                                    {{--                                        :disabled="validateTypeCode || validate || validateCustomerMobile || validateCustomerAge"--}}
                                >@lang('home.save')</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">

        $(function () {
            $("#customer_birthday_hijiri").hijriDatePicker();

            $.validator.addMethod("checkCustomerIdentity", function (value, element) {
                var id_type = $('#id_type_code').val();
                if (id_type == 66001) {
                    var first_num = String(value)[0]
                    console.log(first_num)
                    if (Number(first_num) != 1) {
                        return false
                    }
                }
                return true
            });

            $.validator.addMethod("exactLength", function (value, element, param) {
                return this.optional(element) || value.length == param;
            });

            $('#validate-form').validate({
                rules: {
                    id_type_code: {
                        required: true,
                    },
                    customer_identity: {
                        required: true,
                        minlength: 10,
                        checkCustomerIdentity: true,
                    },
                    customer_mobile: {
                        required: true,
                        exactLength: 10
                    },
                    customer_name_1_ar: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    customer_name_4_ar: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    customer_birthday: {
                        required: true,
                        date: true,
                    },
                    customer_birthday_hijiri: {
                        required: true,
                    },
                    customer_age: {
                        required: true,
                        min: 21
                    },
                    customer_mobile_code: {
                        required: true,
                    },
                    customer_gender: {
                        required: true,
                    },
                    customer_nationality: {
                        required: true,
                    },
                    customer_address_1: {
                        required: true,
                    },
                    customer_address_2: {
                        required: true,
                    },
                    customer_phone_home: {
                        required: true,
                        exactLength: 10
                    }
                },
                messages: {
                    id_type_code: {
                        required: '{{__('messages.required' ,['attr' => __('home.identity_type')])}}',
                    },
                    customer_identity: {
                        required: '{{__('messages.required' ,['attr' => __('home.identity')])}}',
                        minlength: '{{__('messages.minlength', ['num' => 10 ,'attr' => __('home.identity')])}}',
                        checkCustomerIdentity: '{{__('messages.checkCustomerIdentity', ['num' => 2 ,'attr' => __('home.identity')])}}',
                    },
                    customer_mobile: {
                        required: '{{__('messages.required' ,['attr' => __('customer.private_mobile')])}}',
                        exactLength: '{{__('messages.exactLength', ['num' => 10 ,'attr' => __('home.private_mobile')])}}',
                    },
                    customer_age: {
                        required: '{{__('messages.required' ,['attr' => __('home.customer_age')])}}',
                        min: '{{__('messages.min', ['num' => 21 ,'attr' => __('home.customer_age')])}}'
                    },
                    customer_name_1_ar: {
                        required: '{{__('messages.required' ,['attr' => __('home.first_name_ar')])}}',
                        maxlength: '{{__('messages.maxlength', ['num' => 50 ,'attr' => __('home.first_name_ar')])}}',
                        minlength: '{{__('messages.minlength', ['num' => 2 ,'attr' => __('home.first_name_ar')])}}'
                    },
                    customer_name_4_ar: {
                        required: '{{__('messages.required' ,['attr' => __('home.fourth_name_ar')])}}',
                        maxlength: '{{__('messages.maxlength', ['num' => 50 ,'attr' => __('home.fourth_name_ar')])}}',
                        minlength: '{{__('messages.minlength', ['num' => 2 ,'attr' => __('home.fourth_name_ar')])}}'
                    },
                    customer_birthday: {
                        required: '{{__('messages.required' ,['attr' => __('home.customer_birthday')])}}',
                    },
                    customer_birthday_hijiri: {
                        required: '{{__('messages.required' ,['attr' => __('home.customer_birthday_hijiri')])}}',
                    },
                    customer_mobile_code: {
                        required: '{{__('messages.required' ,['attr' => __('customer.country_code')])}}',
                    },
                    customer_gender: {
                        required: '{{__('messages.required' ,['attr' => __('home.customer_gender')])}}',
                    },
                    customer_nationality: {
                        required: '{{__('messages.required' ,['attr' => __('home.nationality')])}}',
                    },
                    customer_address_1: {
                        required: '{{__('messages.required' ,['attr' => __('home.customer_address_home')])}}',
                    },
                    customer_address_2: {
                        required: '{{__('messages.required' ,['attr' => __('home.customer_address_job')])}}',
                    },
                    customer_phone_home: {
                        required: '{{__('messages.required' ,['attr' => __('home.phone_home')])}}',
                        exactLength: '{{__('messages.exactLength', ['num' => 10 ,'attr' => __('home.phone_home')])}}',
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                    $(element).addClass('is-valid');
                }
            });
        });
    </script>
    <script>
        new Vue({
            el: '#app',
            data: {
                customer_name_1_ar: '',
                customer_name_2_ar: '',
                customer_name_3_ar: '',
                customer_name_4_ar: '',

                customer_name_1_en: '',
                customer_name_2_en: '',
                customer_name_3_en: '',
                customer_name_4_en: '',
                full_ar: true,
                full_en: true,
                customer_birthday: '',
                customer_birthday_hijiri: '',
                customer_age: '',
                id_type_code: '',
                error_identity: '',
                customer_identity: '',
                mobile_error: '',
                customerMobile: '',
                age_error: '',
            },
            mounted() {
                $('#customer_birthday_hijiri').on("dp.change", (e) => {
                    this.customer_birthday_hijiri = $('#customer_birthday_hijiri').val()
                    this.getBirthdayDateGeorgian()
                });
            },
            methods: {
                getCustomer() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_identity: this.customer_identity},
                        url: '{{ route("api.carRent.customer") }}'
                    }).then(response => {
                        if (response.data != null) {
                            this.error_identity = '{{__('messages.identity_already_exists')}} - ' + response.data;
                            this.disable_button = true
                        } else {
                            this.error_identity = ''
                            this.disable_button = false
                        }
                    })
                },
                getBirthdayDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.customer_birthday},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.customer_birthday_hijiri = response.data
                    })
                },
                getDifferenceDate() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_birthday: this.customer_birthday},
                        url: '{{ route("car-rent.customers.getDifferenceDate") }}'
                    }).then(response => {
                        this.customer_age = response.data
                    })
                },
                getBirthdayDateGeorgian() {
                    if (this.customer_birthday_hijiri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.customer_birthday_hijiri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.customer_birthday = response.data
                            this.getDifferenceDate()
                        })
                    }
                },

            },
            computed: {
                validateTypeCode: function () {
                    if (this.id_type_code == 66001) {
                        var first_num = String(this.customer_identity)[0]
                        if (Number(first_num) != 1) {
                            this.error_identity = 'رقم الهويه لا يبداء بواحد'
                            return true
                        }
                        if (this.customer_identity.length < 10) {
                            this.error_identity = 'رقم الهويه يجب ان يزيد عن 10 حروف'
                            return true
                        }
                        this.error_identity = ''
                        return false
                    } else {
                        if (this.customer_identity.length < 10) {
                            this.error_identity = 'رقم الهويه يجب ان يزيد عن 10 حروف'
                            return true
                        }
                        this.error_identity = ''
                        return false
                    }
                },
                customer_name_full_ar: function () {
                    // `this` points to the vm instance
                    var str = this.customer_name_1_ar + ' ' + this.customer_name_2_ar + ' ' + this.customer_name_3_ar + ' ' + this.customer_name_4_ar
                    if (str.trim().length > 0) {
                        this.full_ar = false;
                    } else {
                        this.full_ar = true
                    }
                    return str;
                },
                customer_name_full_en: function () {
                    // `this` points to the vm instance
                    this.full_en = false
                    var str = this.customer_name_1_en + ' ' + this.customer_name_2_en + ' ' + this.customer_name_3_en + ' ' + this.customer_name_4_en
                    if (str.trim().length > 0) {
                        this.full_en = false;
                    } else {
                        this.full_en = true
                    }
                    return str;
                },
                validateCustomerMobile: function () {
                    if (this.customerMobile.length > 10) {
                        this.mobile_error = 'رقم التليفون غير صحيح'
                        return true
                    } else {
                        this.mobile_error = ''
                        return false
                    }
                },
                validateCustomerAge: function () {
                    if (this.customer_age < 21) {
                        this.age_error = ' سن العميل اقل من 21 سنة لا يمكن اضافة العميل '
                        return true
                    } else {
                        this.age_error = ''
                        return false
                    }
                },
            }
        })
    </script>
@endsection
