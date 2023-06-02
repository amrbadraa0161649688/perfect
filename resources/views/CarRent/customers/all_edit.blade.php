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
                <ul class="nav nav-tabs page-header-tab">
                    <li class="nav-item  active ">
                        <a class="nav-link" href="#data-grid" data-toggle="tab">@lang('home.basic_information')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#attachments-grid"
                                            data-toggle="tab">@lang('home.files')</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                            data-toggle="tab">@lang('home.notes')</a>
                    </li>
                </ul>
                <div class="header-action">
                    @if(session('redirect_path'))
                        <a href="{{route(session('redirect_path')).'?customer_id='.$customer->customer_id}}"
                           class="btn btn-primary mr-2"
                           id="create_emp">@lang('home.back')</a>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade active show " id="data-grid" role="tabpanel">

                    {{-- Form To Update Customer--}}
                    <form class="card" id="validate-form"
                          action="{{ route('car-rent.customers.update' ,$customer->customer_id) }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        @method('put')
                        <input type="hidden" name="path" value="{{$path}}">
                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('customer.changed_customer')
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    {{--نوع العميل--}}
                                    <div class="col-md-3">
                                        <label class="col-form-label">@lang('home.customer_type')</label>
                                        <select class="selectpicker form-control" name="customer_type"
                                                data-live-search="true" required>
                                            @foreach($sys_codes_type as $sys_code_type)
                                                <option value="{{$sys_code_type->system_code_id}}"
                                                        @if($customer->customer_type == $sys_code_type->system_code_id) selected @endif>
                                                    {{app()->getLocale() == 'ar'
                                                    ? $sys_code_type->system_code_name_ar
                                                    : $sys_code_type->system_code_name_en}}

                                                </option>
                                            @endforeach
                                        </select>
                                        {{--                                        <input type="text" class="form-control" value="@if($customer->cus_type){{app()->getLocale() == 'ar'--}}
                                        {{--                                         ? $customer->cus_type->system_code_name_ar--}}
                                        {{--                                         : $customer->cus_type->system_code_name_en}}@else @endif" >--}}
                                    </div>

                                    {{--تصنيف العميل--}}
                                    <div class="col-md-3">
                                        <label class="col-form-label">@lang('home.customer_classification')</label>
                                        <select class="selectpicker form-control" name="customer_classification"
                                                data-live-search="true">
                                            @foreach($sys_code_classifications as $sys_code_classification)
                                                <option value="{{ $sys_code_classification->system_code_id }}"
                                                        @if($customer->customer_classification == $sys_code_classification->system_code_id) selected @endif>
                                                    {{ app()->getLocale() == 'ar'
                                                    ? $sys_code_classification->system_code_name_ar
                                                    : $sys_code_classification->system_code_name_en }}

                                                </option>
                                            @endforeach
                                        </select>

                                        {{--                                        <input type="text" class="form-control" value="@if($customer->classifications){{app()->getLocale() == 'ar'--}}
                                        {{--                                         ? $customer->classifications->system_code_name_ar--}}
                                        {{--                                         : $customer->classifications->system_code_name_en}}@else @endif" >--}}

                                    </div>


                                    {{--نوع الهويه--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.identity_type') </label>
                                        <select class="selectpicker form-control" name="id_type_code"
                                                data-live-search="true">
                                            <option>@lang('home.identity_type')</option>
                                            @foreach($sys_code_identity_types as $sys_code_identity_type)
                                                <option value="{{ $sys_code_identity_type->system_code_id }}"
                                                        @if($customer->id_type_code == $sys_code_identity_type->system_code_id) selected @endif>
                                                    {{ app()->getLocale() == 'ar'
                                                    ?  $sys_code_identity_type->system_code_name_ar
                                                    : $sys_code_identity_type->system_code_name_en }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{--                                        <input type="text" class="form-control" value="@if($customer->TypeCode){{app()->getLocale() == 'ar'--}}
                                        {{--                                         ? $customer->TypeCode->system_code_name_ar--}}
                                        {{--                                         : $customer->TypeCode->system_code_name_en}}@else @endif" >--}}
                                    </div>

                                    {{--رقم الهويه--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.identity') </label>
                                        <input type="number" class="form-control"
                                               name="customer_identity"
                                               id="customer_identity"
                                               value="{{$customer->customer_identity}}"
                                               required>
                                    </div>


                                </div>

                                <div class="row">
                                    {{--الاسم كامل عربي--}}
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_ar') </label>
                                        <input type="text" class="form-control"
                                               name="customer_name_full_ar" disabled=""
                                               id="customer_name_full_ar"
                                               value="{{$customer->customer_name_full_ar}}"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                               required>
                                    </div>

                                    {{--الاسم كامل انجليزي--}}
                                    <div class="col-md-6">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.name_en') </label>
                                        <input type="text" class="form-control"
                                               name="customer_name_full_en" disabled=""
                                               id="customer_name_full_en"
                                               value="{{$customer->customer_name_full_en}}"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                               required>
                                    </div>
                                </div>

                                {{--تفاصيل الاسم عربي--}}
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.first_name_ar') </label>
                                        <input type="text" class="form-control"
                                               name="customer_name_1_ar" autocomplete="off"
                                               value="{{$customer->customer_name_1_ar}}"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.second_name_ar') </label>
                                        <input type="text" class="form-control" name="customer_name_2_ar"
                                               autocomplete="off" id="customer_name_2_ar"
                                               value="{{$customer->customer_name_2_ar}}"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.third_name_ar') </label>
                                        <input type="text" class="form-control" name="customer_name_3_ar"
                                               autocomplete="off"
                                               id="customer_name_3_ar"
                                               value="{{$customer->customer_name_3_ar}}"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.fourth_name_ar') </label>
                                        <input type="text" class="form-control"
                                               name="customer_name_4_ar" autocomplete="off"
                                               id="customer_name_4_ar"
                                               value="{{$customer->customer_name_4_ar}}"
                                               oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                        >
                                    </div>

                                </div>

                                {{--تفاصيل الاسم انجليزي--}}
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.first_name_en') </label>
                                        <input type="text" class="form-control " name="customer_name_1_en"
                                               value="{{$customer->customer_name_1_en}}"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                        >
                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.second_name_en') </label>
                                        <input type="text" class="form-control"
                                               name="customer_name_2_en"
                                               id="customer_name_2_en"
                                               value="{{$customer->customer_name_2_en}}"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.third_name_en') </label>
                                        <input type="text" class="form-control" name="customer_name_3_en"
                                               id="customer_name_3_en"
                                               value="{{$customer->customer_name_3_en}}"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                        >
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.fourth_name_en') </label>
                                        <input type="text" class="form-control " name="customer_name_4_en"
                                               id="customer_name_4_en"
                                               value="{{$customer->customer_name_4_en}}"
                                               oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                        >
                                    </div>


                                </div>

                                <div class="row">
                                    {{--تاريخ الميلاد ميلادي--}}
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.customer_birthday') </label>
                                        <input type="date" class="form-control" name="customer_birthday"
                                               id="customer_birthday"
                                               @change="getBirthdayDate() ; getDifferenceDate()"
                                               v-model="customer_birthday"
                                        >
                                    </div>


                                    {{--تاريخ الميلاد هجري--}}
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.customer_birthday_hijiri') </label>
                                        <input type="date" class="form-control " name="customer_birthday_hijiri" id="customer_birthday_hijiri"
                                               v-model="customer_birthday_hijiri">
                                    </div>
                                    {{--السن--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_age') </label>
                                        <input type="number" class="form-control"
                                               v-model="customer_age"
                                               disabled="" value="{{$customer->age}}">
                                    </div>
                                    {{--نسبه الضريبه--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_vat_rate') </label>
                                        <input type="text" class="form-control" name="customer_vat_rate"
                                               id="customer_vat_rate" value="{{$customer->customer_vat_rate}}"
                                        >
                                    </div>
                                </div>

                                <div class="row">
                                    {{--رقم الجوال--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.private_mobile') <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control @error('customer_mobile') is-invalid @enderror"
                                               name="customer_mobile"
                                               id="customer_mobile"
                                               value="{{old('customer_mobile')?old('customer_mobile'):$customer->customer_mobile}}"
                                        >
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
                                            name="customer_mobile_code" data-live-search="true"
                                            id="customer_mobile_code" required>

                                            @foreach($sys_codes_nationality_country as $sys_code_nationality_country_a)
                                                <option value="{{ $sys_code_nationality_country_a->system_code_id }}"
                                                        @if($customer->customer_mobile_code == $sys_code_nationality_country_a->system_code_id) selected @endif>
                                                    @if(app()->getLocale()=='ar')
                                                        {{$sys_code_nationality_country_a->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_nationality_country_a->system_code_name_en}}
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
                                        <select
                                            class="selectpicker form-control @error('customer_gender') is-invalid @enderror"
                                            name="customer_gender" data-live-search="true"
                                            id="customer_gender" required>
                                            <option value="1">@lang('home.male')</option>
                                            <option value="2">@lang('home.female')</option>
                                        </select>
                                        @error('customer_gender')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                        {{-- <input type="text" class="form-control" --}}
                                        {{-- @if($customer->customer_gender == 1)--}}
                                        {{--  value="@lang('home.male')"--}}
                                        {{-- @elseif($customer->customer_gender == 0)--}}
                                        {{--     value="@lang('home.female')"--}}
                                        {{-- @else value="" @endif>--}}

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
                                            <option value="" selected></option>
                                            @foreach($sys_codes_nationality_country as $sys_code_nationality_country_a)
                                                <option value="{{ $sys_code_nationality_country_a->system_code_id }}"
                                                        @if($customer->customer_nationality == $sys_code_nationality_country_a->system_code_id) selected @endif>
                                                    @if(app()->getLocale()=='ar')
                                                        {{$sys_code_nationality_country_a->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_nationality_country_a->system_code_name_en}}
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


                                </div>

                                <div class="row">

                                    {{--تليفون العمل--}}
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('customer.work_mobile') </label>
                                        <input type="number" class="form-control" name="customer_phone"
                                               id="customer_phone" value="{{$customer->customer_phone}}">

                                    </div>
                                    {{--عنوان العمل--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_address_job') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_address_2') is-invalid @enderror"
                                               name="customer_address_2"
                                               id="customer_address_2" value="{{$customer->customer_address_2}}"
                                        >
                                        @error('customer_address_2')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    {{--الوظيفه--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.job') </label>
                                        <input type="text" class="form-control" name="customer_job"
                                               id="customer_job" value="{{$customer->customer_job}}"
                                        >
                                    </div>

                                    {{--جهه العمل--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.company') </label>
                                        <input type="text" class="form-control" name="customer_company"
                                               id="customer_company" value="{{$customer->customer_company}}"
                                        >
                                    </div>

                                </div>


                                <div class="row">

                                    {{--تليفون المنزل--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.phone_home') <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                               class="form-control @error('customer_phone_home') is-invalid @enderror"
                                               name="customer_phone_home"
                                               id="customer_phone_home"
                                               value="{{$customer->customer_phone_home}}">
                                        @error('customer_phone_home')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror

                                    </div>

                                    {{--عنوان المنزل--}}
                                    <div class="col-md-3 form-group">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.customer_address_home') <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('customer_address_1') is-invalid @enderror"
                                               name="customer_address_1"
                                               id="customer_address_1"
                                               value="{{$customer->customer_address_1}}">
                                        @error('customer_address_1')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>

                                    {{--ايميل العميل--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.email_work') </label>
                                        <input type="email" class="form-control" name="customer_email"
                                               id="customer_email" value="{{$customer->customer_email}}">
                                    </div>

                                    {{--الرقم الضريبي--}}
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.tax_number') </label>
                                        <input type="number" class="form-control" name="customer_vat_no"
                                               id="customer_vat_no" value="{{$customer->customer_vat_no}}"
                                        >
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.system_code_acc_id') </label>
                                        <select class="form-control selectpicker" data-live-search="true"
                                                name="customer_account_id"
                                                id="customer_account_id">
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
                                    <div class="col-md-4">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('home.status') </label>
                                        <select class="selectpicker form-select form-control" name="customer_status"
                                                aria-label="Default select example" id="customer_status"
                                                data-live-search="true"
                                                required>
                                            @foreach($sys_codes_status as $sys_code_status_a)
                                                <option value="{{$sys_code_status_a->system_code_id}}"
                                                        @if($customer->customer_status == $sys_code_status_a->system_code_id) selected @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_status_a->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_status_a->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--تابع لعميل شركات --}}
                                    <div hidden class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.customer_ref_no') </label>
                                        <select class="selectpicker form-select form-control" name="customer_ref_no"
                                                id="customer_mobile_code" data-live-search="true" required>
                                            @foreach($customers as $customer_a)
                                                <option value="{{ $customer->customer_id }}"
                                                        @if($customer->customer_ref_no == $customer_a->customer_id) selected @endif>
                                                    @if(app()->getLocale()=='ar')
                                                        {{$customer_a->customer_name_full_ar}}
                                                    @else
                                                        {{$customer_a->customer_name_full_en}}
                                                    @endif

                                                </option>
                                            @endforeach

                                        </select>
                                    </div>

                                    {{-- الحد الائتماني--}}
                                    <div class="col-md-4">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('customer.lemet_balance') </label>
                                        <input type="text" class="form-control" name="customer_credit_limit"
                                               id="customer_credit_limit" value="{{$customer->customer_credit_limit}}">
                                    </div>
                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        id="create_emp">@lang('home.save')</button>
                            </div>
                        </div>
                    </form>

                </div>

                {{------------Practical_attachment_grid---------------------------------------------------------------}}
                <div class="tab-pane fade " id="attachments-grid" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-header">
                                    <div class="card-body">

                                        <div class="md-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6 class="text-center mt-4">@lang('home.files')</h6>
                                                </div>

                                                {{----}}
                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('home.name') </label>
                                                    <input type="text" class="form-control" name="emp_name_full_ar"
                                                           id="emp_name_full_ar"
                                                           value=" {{app()->getLocale() == 'ar'
                                                            ? $customer->customer_name_full_ar
                                                            : $customer->customer_name_full_en}}"
                                                    >
                                                </div>
                                                {{----}}
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">


                            <x-files.form>
                                <input type="hidden" name="transaction_id" value="{{$customer->customer_id}}">
                                <input type="hidden" name="app_menu_id" value="32">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.attachment_type')</label>
                                        <select class="selectpicker form-control" name="attachment_type"
                                                data-live-search="true" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($attachment_types as $attachment_type)
                                                <option value="{{ $attachment_type->system_code }}">
                                                    {{app()->getLocale()=='ar'
                                                     ? $attachment_type->system_code_name_ar
                                                     : $attachment_type->system_code_name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </x-files.form>
                            <x-files.attachment>
                                @foreach($attachments as $attachment)
                                    <tr>
                                        <td>{{ $attachment->attachmentType?$attachment->attachmentType['system_code_name_'.app()->getLocale()]:'N/A'}}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                        <td>{{ $attachment->issue_date_hijri }}</td>
                                        <td>{{ $attachment->expire_date_hijri }}</td>
                                        <td>{{ $attachment->copy_no }}</td>
                                        <td class="badge text-gray text-wrap"
                                            style="max-width: 10rem;"> {{ substr($attachment->attachment_data,0,50) }} </td>
                                        <td>{{ $attachment->userCreated->user_name_ar }}</td>
                                        <td>{{ $attachment->created_at }}</td>
                                        <td>
                                            <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}"
                                               class="m-1">
                                                <i class="fa fa-download fa-lg"></i></a>
                                            <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                               target="_blank"><i
                                                    class="fa fa-eye text-info fa-lg"></i></a>
                                            <form
                                                action="{{ route('employees-attachment.delete',$attachment->attachment_id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-sm btn-icon on-default button-remove"
                                                        type="submit" data-original-title="Remove"><i
                                                        class="icon-trash text-danger" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                @endforeach

                            </x-files.attachment>


                        </div>
                    </div>

                </div>

                {{------------Practical_notes_grid--------------------------------------------------------------------}}
                <div class="tab-pane fade " id="notes-grid" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-header">
                                    <div class="card-body">

                                        <div class="md-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6 class="text-center mt-4">@lang('home.notes')</h6>
                                                </div>

                                                {{--name--}}
                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label "> @lang('home.name') </label>
                                                    <input type="text" class="form-control" name="emp_name_full_ar"
                                                           id="emp_name_full_ar"
                                                           value=" {{app()->getLocale() == 'ar'
                                                            ? $customer->customer_name_full_ar
                                                            : $customer->customer_name_full_en}}"
                                                    >
                                                </div>
                                                {{--name--}}
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">

                            <x-files.form-notes>

                                <input type="hidden" name="transaction_id" value="{{$customer->customer_id}}">
                                <input type="hidden" name="app_menu_id" value="32">


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
    </div>

@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>


    <script>


        $(function () {
            $("#customer_birthday_hijiri").hijriDatePicker();
        });


        $(document).ready(function () {

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
        })
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
                customer_birthday: '',
                customer_birthday_hijiri: '',
                issue_date: '',
                expire_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
                customer_age: '',
            },
            mounted() {
                this.customer_birthday_hijiri = '{{$customer->customer_birthday_hijiri}}'
                $('#customer_birthday_hijiri').on("dp.change", (e) => {
                    this.customer_birthday_hijiri = $('#customer_birthday_hijiri').val()
                    this.getBirthdayDateGeorgian()
                });
                this.customer_birthday = '{{$customer->customer_birthday}}'
                this.customer_age = '{{$customer->age}}'
                this.getBirthdayDate()
            },
            methods: {
                getBirthdayDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.customer_birthday},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.customer_birthday_hijiri = response.data
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
                getDifferenceDate() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_birthday: this.customer_birthday},
                        url: '{{ route("car-rent.customers.getDifferenceDate") }}'
                    }).then(response => {
                        this.customer_age = response.data
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

                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },
            },
            computed: {
                customer_name_full_ar: function () {
                    // `this` points to the vm instance
                    return this.customer_name_1_ar + ' ' + this.customer_name_2_ar + ' ' + this.customer_name_3_ar + ' ' + this.customer_name_4_ar

                },

                customer_name_full_en: function () {
                    // `this` points to the vm instance
                    return this.customer_name_1_en + ' ' + this.customer_name_2_en + ' ' + this.customer_name_3_en + ' ' + this.customer_name_4_en

                },
            }
        })


        $.validator.addMethod("exactLength", function (value, element, param) {
            return this.optional(element) || value.length == param;
        });

        $('#validate-form').validate({
            rules: {
                customer_mobile: {
                    required: true,
                    exactLength: 10
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
                customer_mobile: {
                    required: '{{__('messages.required' ,['attr' => __('customer.private_mobile')])}}',
                    exactLength: '{{__('messages.exactLength', ['num' => 10 ,'attr' => __('home.private_mobile')])}}',
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

    </script>

@endsection
