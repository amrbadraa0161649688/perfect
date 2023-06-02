@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }

        .form-section {
            display: none;
        }

        .form-section.current {
            display: inherit;
        }

        .parsley-errors-list {
            margin: 2px 0 3px;
            padding: 0;
            list-style-type: none;
            color: red;
            font-size: 15px;
        }
    </style>
@endsection
@section('content')

    <style>

        #image-container {

            width: 1000px !important;
            height: 500px !important;
            position: relative !important;
            background-color: beige;
        }

        .star-icon {
            position: absolute;
        }

        body {
            background: #eee;
        }

        #regForm {
            background-color: #ffffff;
            margin: 0px auto;
            font-family: "Cairo";
            padding: 40px;
            border-radius: 10px
        }

        h1 {
            text-align: center
        }

        input {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            font-family: "Cairo";
            border: 1px solid #aaaaaa
        }

        input.invalid {
            background-color: #ffdddd
        }

        .tab {
            display: none
        }

        button {
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            font-family: "Cairo";
            cursor: pointer
        }

        button:hover {
            opacity: 0.8
        }

        #prevBtn {
            background-color: #bbbbbb
        }

        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5
        }

        .step.active {
            opacity: 1
        }

        .step.finish {
            background-color: #4CAF50
        }

        .all-steps {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px
        }

        .thanks-message {
            display: none
        }

        .container {
            display: block;
            /*position: relative;*/
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        /*.container input[type="radio"] {*/
        /*position: absolute;*/
        /*opacity: 0;*/
        /*cursor: pointer*/
        /*}*/

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
            border-radius: 50%
        }

        .container:hover input ~ .checkmark {
            background-color: #ccc
        }

        .container input:checked ~ .checkmark {
            background-color: #2196F3
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none
        }

        .container input:checked ~ .checkmark:after {
            display: block
        }

        .container .checkmark:after {
            top: 9px;
            left: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white
        }
    </style>

    <div class="container mt-5" id="app">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12">
                <form action="{{route('car-rent.store')}}" method="post" class="contact-form"
                      enctype="multipart/form-data">
                    @csrf
                    <h1 id="register">@lang('home.rent_data')</h1>
                    <div class="all-steps" id="all-steps">
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>
                        <span class="step"></span>
                    </div>
                    {{-- رسالة الخطاء--}}
                    <div class="row">
                        <div class="col-md-10 m-auto">
                            <div class="alert alert-danger" v-if="list_price_error">
                                <p class="text-center"> @{{ list_price_error }}</p>
                            </div>

                        </div>
                    </div>

                    <div class="form-section">


                        <h2 class="text-center">@lang('home.start')</h2>
                        {{-- بيانات المستأجر --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="row">


                                    <div class="col-sm-6 col-md-6">

                                    </div>


                                    <div class="col-md-12">
                                        <p style="font-weight: bold;font-size: 25px; display: block;">
                                            @lang('home.renter_data')
                                        </p>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customer_name')</label>

                                            <select class="selectpicker"
                                                    data-live-search="true"
                                                    name="customer_id" v-model="customer_id"
                                                    v-on:change="getCustomer();getCars() ;editCustomerValid()"
                                                    required>
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->customer_id}}" @if(isset($customer_id) && $customer_id == $customer->customer_id) selected @endif>
                                                        @if($customer->customer_identity)
                                                            {{app()->getLocale() == 'ar'
                                                                  ? $customer->customer_name_full_ar . ' ==> ' . $customer->customer_identity . ' ==> ' . $customer->customer_phone
                                                                  : $customer->customer_name_full_en . ' ==> ' . $customer->customer_identity . ' ==> ' . $customer->customer_phone}}
                                                        @else
                                                            {{app()->getLocale() == 'ar'
                                                             ? $customer->customer_name_full_ar . ' ==> ' . 'لا يوجد رقم هويه للعميل'
                                                             : $customer->customer_name_full_en . ' ==> ' . 'There is not ID number For Customer '}}
                                                        @endif

                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback d-block" v-if="customer_error">
                                                @{{this.customer_error}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3 col-md-3">

                                    </div>

                                    <div class="col-sm-3 col-md-3">
                                        <div class="form-group">

                                            <a class="btn btn-primary mt-4"
                                               href="{{ url('car-rent/customers/create?path='.Route::current()->getName()) }}"
                                               style="color:#ffffff"
                                               v-if="create_customer">@lang('home.add_customer')</a>


                                            <a class="btn btn-primary mt-4"
                                               :href="'customers/'+customer_id+'/edit?path=' + '{{Route::current()->getName()}}'"
                                               {{--:href="'{{ url("car-rent/customers/".customer_id."/edit?path=".Route::current()->getName()) }}'"--}}
                                               style="color:#ffffff"
                                               v-else-if="edit_customer">@lang('home.edit_customer')</a>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.id_number')</label>
                                            <input type="text" class="form-control" name="c_idNumber"
                                                   :value="customer.customer_identity" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.id_type')</label>

                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       :value="customer_type_code.system_code_name_ar" readonly
                                                       v-if="customer_type_code">

                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       value="" required readonly
                                                       v-else>

                                            @else
                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       :value="customer_type_code.system_code_name_en" readonly
                                                       v-if="customer_type_code">

                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       value="" required readonly
                                                       v-else>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.mobile_number')</label>
                                            <input type="number" class="form-control" name="c_mobile"
                                                   placeholder="@lang('home.mobile_number')"
                                                   :value="customer.customer_mobile" readonly>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.email')</label>
                                            <input type="text" class="form-control" name="c_email"
                                                   placeholder="Email"
                                                   :value="customer.customer_email" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.address')</label>
                                            <input type="text" class="form-control" name="c_personAddress"
                                                   placeholder="City" :value="customer.customer_address_1" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6"></div>


                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label
                                                        class="form-label">@lang('home.is_this_additional_driver')</label>
                                                </div>

                                                <div class="col-md-1">
                                                    <label class="form-label">@lang('home.yes')</label>
                                                    <input type="radio" name="additional_driver"
                                                           id="additional_driver_yes" required
                                                           value="1" v-model="additional_driver"
                                                           @change="toggleAdditionalDriver()"
                                                           onchange="extraDriver($(this))">
                                                </div>

                                                <div class="col-md-1">
                                                    <label class="form-label">@lang('home.no')</label>
                                                    <input type="radio" name="additional_driver"
                                                           id="additional_driver_no"
                                                           value="0" v-model="additional_driver"
                                                           @change="toggleAdditionalDriver()"
                                                           onchange="extraDriver($(this))">
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <div class="row">
                                                <div class="col-md-4">{{--هل االموفوض هو نفسه المستأجر--}}
                                                    <label
                                                        class="form-label">@lang('home.is_the_commissioner_the_same_as_the_lessee')</label>
                                                </div>

                                                <div class="col-md-1">
                                                    <label class="form-label">@lang('home.yes')</label>
                                                    <input type="radio" name="commissioner"
                                                           id="commissioner_yes" required
                                                           value="1" v-model="commissioner_selected"
                                                           @change="toggleCommissioner()">
                                                </div>

                                                <div class="col-md-1">
                                                    <label class="form-label">@lang('home.no')</label>
                                                    <input type="radio" name="commissioner"
                                                           id="commissioner_no"
                                                           value="0" v-model="commissioner_selected"
                                                           @change="toggleCommissioner()">
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- بيانات السائق الاضافي --}}
                        <div class="card " id="additional_driver" v-show="show_additional_driver">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-sm-6 col-md-6">

                                    </div>

                                    <div class="col-md-12">
                                        <p style="font-weight: bold;font-size: 25px; display: block;">
                                            @lang('home.additional_driver_data')
                                        </p>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customer_name')</label>

                                            <select class="selectpicker"
                                                    data-live-search="true"
                                                    name="driver_id" v-model="driver_id"
                                                    v-on:change="getDriver()"
                                                    name="extra_driver_id" :required="driver_required">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($customers as $driver)
                                                    <option value="{{$driver->customer_id}}">

                                                        @if($driver->customer_identity)
                                                            {{app()->getLocale() == 'ar'
                                                                    ? $driver->customer_name_full_ar . ' ==> ' . $driver->customer_identity
                                                                    : $driver->customer_name_full_en . ' ==> ' . $driver->customer_identity}}
                                                        @else
                                                            {{app()->getLocale() == 'ar'
                                                             ? $driver->customer_name_full_ar . ' ==> ' . 'لا يوجد رقم هويه للسائق'
                                                             : $driver->customer_name_full_en . ' ==> ' . 'There is not ID number For Driver '}}
                                                        @endif

                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback d-block" v-if="driver_error">
                                                @{{this.driver_error}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3 col-md-3">

                                    </div>

                                    <div class="col-sm-3 col-md-3">
                                        <div class="form-group">

                                            <a class="btn btn-primary mt-4"
                                               href="{{ url('car-rent/customers/create?path='.Route::current()->getName()) }}"
                                               style="color:#ffffff" v-if="!edit_driver">@lang('home.add_customer')</a>


                                            <a class="btn btn-primary mt-4"
                                               :href="'customers/'+customer_id+'/edit/?path={{Route::current()->getName()}}'"
                                               style="color:#ffffff"
                                               v-if="edit_driver">@lang('home.edit_customer')</a>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.id_number')</label>
                                            <input type="number" class="form-control" name="d_idNumber"
                                                   :value="driver.customer_identity" :required="driver_required"
                                                   readonly>

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.id_type')</label>
                                            <input type="hidden" name="d_idTypeCode">
                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       :value="driver_type_code.system_code_name_ar" readonly
                                                       :required="driver_required" v-if="driver_type_code">

                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       value="" :required="driver_required" readonly
                                                       v-else>
                                            @else
                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       :value="driver_type_code.system_code_name_en" readonly
                                                       :required="driver_required" v-if="driver_type_code">

                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       value="" :required="driver_required" readonly
                                                       v-else>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.mobile_number')</label>
                                            <input type="number" class="form-control" name="driver_mobile"
                                                   placeholder="@lang('home.mobile_number')"
                                                   :value="driver.customer_mobile" :required="driver_required">
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.email')</label>
                                            <input type="text" class="form-control" name="driver_email"
                                                   placeholder="Email"
                                                   :value="driver.customer_email">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.address')</label>
                                            <input type="text" class="form-control" name="d_personAddress"
                                                   placeholder="City" :value="driver.customer_address_1"
                                                   :required="driver_required" readonly>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- بيانات المفوض --}}
                        <div class="card" style=" display: none;" id="commissioner_data" v-show="show_commissioner">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-sm-6 col-md-6">

                                    </div>


                                    <div class="col-md-12">
                                        <p style="font-weight: bold;font-size: 25px; display: block;">
                                            @lang('home.commissioner_data')
                                        </p>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customer_name')</label>

                                            <select class="selectpicker"
                                                    data-live-search="true"
                                                    v-model="commissioner_id"
                                                    v-on:change="getCommissioner()"
                                                    name="commissioner_id" :required="commissioner_required">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($customers as $commissioner)
                                                    <option value="{{$commissioner->customer_id}}">

                                                        @if($commissioner->customer_identity)
                                                            {{app()->getLocale() == 'ar'
                                                                    ? $commissioner->customer_name_full_ar . ' ==> ' . $commissioner->customer_identity
                                                                    : $commissioner->customer_name_full_en . ' ==> ' . $commissioner->customer_identity }}
                                                        @else
                                                            {{app()->getLocale() == 'ar'
                                                             ? $commissioner->customer_name_full_ar . ' ==> ' . 'لا يوجد رقم هويه للمفوض'
                                                             : $commissioner->customer_name_full_en . ' ==> ' . 'There is not ID number For Commissioner '}}
                                                        @endif

                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback d-block" v-if="commissioner_error">
                                                @{{this.commissioner_error}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3 col-md-3">

                                    </div>

                                    <div class="col-sm-3 col-md-3">
                                        <div class="form-group">

                                            <a class="btn btn-primary mt-4"
                                               href="{{ url('car-rent/customers/create?path='.Route::current()->getName()) }}"
                                               style="color:#ffffff"
                                               v-if="!edit_commissioner">@lang('home.add_customer')</a>

                                            <a class="btn btn-primary mt-4"
                                               :href="'customers/'+customer_id+'/edit/'"
                                               style="color:#ffffff"
                                               v-else-if="edit_commissioner">@lang('home.edit_customer')</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.id_number')</label>
                                            <input type="text" class="form-control" name="commissioner_id_number"
                                                   :value="commissioner.customer_identity"
                                                   :required="commissioner_required">

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.id_type')</label>
                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       :value="commissioner_type_code.system_code_name_ar" readonly
                                                       :required="commissioner_required" v-if="commissioner_type_code">

                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       value="" :required="commissioner_required" readonly
                                                       v-else>
                                            @else
                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       :value="commissioner_type_code.system_code_name_en" readonly
                                                       :required="commissioner_required" v-if="commissioner_type_code">
                                                <input type="text" class="form-control" name="c_idTypeCode"
                                                       value="" :required="commissioner_required" readonly
                                                       v-else>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.mobile_number')</label>
                                            <input type="number" class="form-control" name="commissioner_mobile"
                                                   placeholder="@lang('home.mobile_number')"
                                                   :value="commissioner.customer_mobile"
                                                   :required="commissioner_required">
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.email')</label>
                                            <input type="text" class="form-control" name="commissioner_email"
                                                   placeholder="Email"
                                                   :value="commissioner.customer_email">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.address')</label>
                                            <input type="text" class="form-control" name="commissioner_person_address"
                                                   placeholder="City" :value="commissioner.customer_address_1"
                                                   :required="commissioner_required">
                                        </div>
                                    </div>


                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.rent_type')</label>
                                            <select class="form-control custom-select">
                                                <option value="">Germany</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>


                    <div class="form-section">
                        <h2 class="text-center">@lang('home.car_data')</h2>

                        {{--بيانات السياره--}}
                        <div class="section-body py-4">
                            <div class="container-fluid">
                                <div class="row">
                                    {{--<button @click="show_cars = !show_cars"--}}
                                    {{--class="btn btn-primary">@lang('home.show_cars')</button>--}}
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-12">
                                        <div class="card card-collapsed">
                                            <div class="card-header">
                                                <h3 class="card-title">@lang('home.cars_list')</h3>
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
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table text-nowrap mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>@lang('home.plate_number')</th>
                                                            <th>@lang('home.brand')</th>
                                                            <th>@lang('home.model')</th>
                                                            <th>@lang('carrent.car_model_year')</th>
                                                            <th>@lang('home.color')</th>
                                                            <th>@lang('home.last_odometer')</th>
                                                            <th>@lang('home.tracker_status')</th>
                                                            <th>@lang('home.daily_price')</th>
                                                            <th>@lang('home.monthly_price')</th>
                                                            <th>@lang('home.rent_type')</th>
                                                        </tr>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><input type="text" class="form-control"
                                                                       v-model="plate_number"></th>
                                                            <th><input type="text" class="form-control" v-model="brand">
                                                            </th>
                                                            <th><input type="text" class="form-control"
                                                                       v-model="car_model"></th>
                                                            <th><input type="text" class="form-control"
                                                                       v-model="car_model_year"></th>

                                                            <th><input type="text" class="form-control"
                                                                       v-model="car_color"></th>
                                                            <th></th>
                                                            <th><input type="text" class="form-control"
                                                                       v-model="tracker_status"></th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr v-for="car,index in cars">
                                                            <td>
                                                                <input type="radio" name="car_id"
                                                                       @click="getSelectedCar(index)"
                                                                       style="width:20px" :value="car.car_id" required>
                                                                @{{car.car_rent_model_id}}
                                                            </td>
                                                            <td>@{{ car.plate_number }}</td>
                                                            <td>
                                                                @if(app()->getLocale() == 'ar')
                                                                    @{{ car.brand_name_ar }}
                                                                @else
                                                                    @{{ car.brand_name_en }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(app()->getLocale() == 'ar')
                                                                    @{{ car.model_name_ar }}
                                                                @else
                                                                    @{{ car.model_name_en }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(app()->getLocale() == 'ar')
                                                                    @{{ car.category_name_ar }}
                                                                @else
                                                                    @{{ car.category_name_en }}
                                                                @endif
                                                            </td>
                                                            <td>@{{ car.color }}</td>
                                                            <td>@{{ car.last_odometer }}</td>
                                                            <td>
                                                                @if(app()->getLocale() == 'ar')
                                                                    @{{ car.tracker_status_ar }}
                                                                @else
                                                                    @{{ car.tracker_status_en }}
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @{{ car.daily_price }}
                                                            </td>


                                                            <td>
                                                                @{{ car.monthly_price }}
                                                            </td>
                                                            <td>
                                                                @if(app()->getLocale() == 'ar')
                                                                    @{{ car.rent_type_name_ar }}
                                                                @else
                                                                    @{{ car.rent_type_name_en }}
                                                                @endif
                                                            </td>

                                                        </tr>

                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.car_data')
                                    </p>
                                </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.car_model')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   placeholder="@lang('home.car_model')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.model_name_ar"
                                                   @else
                                                       :value="car.model_name_en"
                                                @endif
                                            >
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.manufacturing_year')</label>
                                            <input type="text" class="form-control" readonly
                                                   placeholder="@lang('home.manufacturing_year')"
                                                   :value="car.car_model_year">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.color')</label>
                                            <input type="text" class="form-control" readonly
                                                   placeholder="@lang('home.color')" :value="car.color">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.passengers_number')</label>
                                            <input type="text" class="form-control" name="carSeats"
                                                   placeholder="@lang('home.passengers_number')"
                                                   :value="car.car_passengers" required readonly>

                                            {{--<input type="text" class="form-control" name="carSeats"--}}
                                            {{--placeholder="@lang('home.passengers_number')"--}}
                                            {{--:value="'6'" readonly>--}}
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.operating_card_number')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.operating_card_number')"
                                                   readonly
                                                   :value="car.car_operation_card_no">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.operating_card_expiring_date')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="Home Address"
                                                   readonly
                                                   :value="car.car_operation_card_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.register_type')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.register_type')"
                                                   readonly
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_registration_type_ar"
                                                   @else
                                                       :value="car.car_registration_type_en"
                                                @endif>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.available_kilometers')</label>
                                            <input type="number" class="form-control" name="allowedKmPerHour"
                                                   placeholder="@lang('home.available_kilometers')" readonly=""
                                                   :value="car.allowed_km_per_hour">

                                            {{--<input type="number" class="form-control" name="allowedKmPerHour"--}}
                                            {{--placeholder="@lang('home.available_kilometers')"--}}
                                            {{--:value="'10'" id="allowedKmPerHour">--}}
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.full_fuel_tank_value_in_sar')</label>
                                            <input type="number" class="form-control" name="fullFuelCost"
                                                   step=".0001" required
                                                   value="0">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--بيانات التأمين--}}
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.insurance_data')
                                    </p>
                                </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.insurance_policy_number')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   placeholder="@lang('home.insurance_policy_number')"
                                                   :value="car.insurance_document_no">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.insurance_type')</label>
                                            <input type="text" class="form-control" readonly
                                                   placeholder="@lang('home.insurance_type')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.insurance_type_ar"
                                                   @else
                                                       :value="car.insurance_type_en"
                                                @endif
                                            >
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.insurance_expiry_date')</label>
                                            <input type="text" class="form-control"
                                                   readonly
                                                   :value="car.insurance_date_end">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        {{-- ///// مش موجود ////--}}
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.endurance_amount')</label>
                                            <input type="number" class="form-control" name="enduranceAmount"
                                                   required
                                                   placeholder="@lang('home.endurance_amount')"
                                                   :value="car.endurance_amount" readonly>
                                            {{--<input type="number" class="form-control" name="enduranceAmount"--}}
                                            {{--step=".0001" required--}}
                                            {{--placeholder="@lang('home.endurance_amount')"--}}
                                            {{--:value="0" readonly>--}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        {{--///// مش موجود ////--}}
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.extra_services')</label>
                                            <input type="number" class="form-control"
                                                   placeholder="@lang('home.extra_services')" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{-- ///// مش موجود ////--}}
                                            <label class="form-label">@lang('home.extra_services_in_day')</label>
                                            <input type="number" class="form-control"
                                                   placeholder="@lang('home.extra_services_in_day')" value="0">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{--الحالة الفنيه--}}
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.technical_condition_of_the_car')
                                    </p>
                                </h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.fuel_type')</label>

                                            <input type="hidden" name="fuelTypeCode" :value="car.fuel_type_id">
                                            <input type="text" class="form-control" disabled=""
                                                   placeholder="@lang('home.fuel_type')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.fuel_type_name_ar"
                                                   @else
                                                       :value="car.fuel_type_name_en"
                                                   @endif readonly>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.oil_change_km_distance')</label>
                                            <input type="number" class="form-control" name="oilChangeKmDistance"
                                                   placeholder="@lang('home.oil_change_km_distance')"
                                                   :value="car.oilChangeKmDistance" required readonly="">
                                            {{--<input type="number" class="form-control" name="oilChangeKmDistance"--}}
                                            {{--placeholder="@lang('home.oil_change_km_distance')"--}}
                                            {{--:value="'3333'" readonly>--}}

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">

                                            <label class="form-label">@lang('home.the_amount_of_fuel_present')</label>
                                            <input type="number" class="form-control" name="availableFuel"
                                                   placeholder="@lang('home.the_amount_of_fuel_present')"
                                                   :value="car.available_fuel" required readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.meter_reading_before')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.meter_reading_before')"
                                                   name="odometerReading" required readonly
                                                   :value="car.odometer_start">
                                            {{--<input type="text" class="form-control"--}}
                                            {{--placeholder="@lang('home.meter_reading_before')"--}}
                                            {{--name="odometerReading" required--}}
                                            {{--:value="'2222'" readonly>--}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.call_date')</label>
                                            <input type="date" class="form-control"
                                                   placeholder="@lang('home.call_date')"
                                                   :value="car.last_oil_change_date">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.oil_type')</label>
                                            <input type="hidden" name="oil_type_id" :value="car.oil_type_id">
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.oil_type')"
                                                   name="oilType" readonly
                                                   @if(app()->getLocale() == 'ar') :value="car.oil_type_ar"
                                                   @else  :value="car.oil_type_en" @endif>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.oil_change_time')</label>
                                            <input type="date" class="form-control" name="oilChangeDate"
                                                   :value="car.last_oil_change_date" readonly>

                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="form-label">@lang('home.availability_of_the_reflective_triangle')</label>
                                            <input type="hidden" name="safetyTriangle"
                                                   :value="car.car_Safety_Triangle_id" required>
                                            <input type="text" class="form-control"
                                                   placeholder=" @lang('home.availability_of_the_reflective_triangle')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Safety_Triangle_ar" @else
                                                       :value="car.car_Safety_Triangle_en"
                                                   @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.daily_price')</label>

                                            <input class="form-control" type="text" required name="test"
                                                   :value="car.daily_price" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.conditioning_status')</label>
                                            <input type="hidden" name="ac" :value="car.car_ac_status_id">
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.conditioning_status') "
                                                   @if(app()->getLocale() == 'ar') :value="car.car_ac_status_ar"
                                                   @else :value="car.car_ac_status_en" @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label
                                                class="form-label">@lang('home.availability_of_a_fire_extinguisher')</label>
                                            <input type="hidden" name="fireExtinguisher"
                                                   :value="car.car_Fire_extinguisher" required>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.availability_of_a_fire_extinguisher')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Fire_extinguisher == 0 ? 'Not Available' :'Available'"
                                                   @else
                                                       :value="car.car_Fire_extinguisher == 0 ? 'متوفرة' : 'غير متوفرة'"
                                                   @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.radio_status')</label>
                                            <input type="hidden" name="radioStereo"
                                                   :value="car.car_Radio_Stereo_status_id">
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.radio_status')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Radio_Stereo_status_ar" @else
                                                       :value="car.car_Radio_Stereo_status_en" @endif
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.inside_screen_status')</label>
                                            <input type="hidden" name="screen" :value="car.car_Screen_status_id"
                                                   required>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.inside_screen_status')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Screen_status_ar" @else
                                                       :value="car.car_Screen_status_ar"
                                                   @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.speedometer_status')</label>
                                            <input type="hidden" name="speedometer"
                                                   :value="car.car_Speedometer_status_id" required>

                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.speedometer_status')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Speedometer_status_ar" @else
                                                       :value="car.car_Speedometer_status_ar"
                                                   @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.interior_upholstery_case')</label>
                                            <input type="hidden" :value="car.car_Seats_status">
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.interior_upholstery_case')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Seats_status_ar"
                                                   @else
                                                       :value="car.car_Seats_status_en"
                                                   @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.spare_tire_equipment')</label>
                                            <input type="hidden" name="spareTireTools" required
                                                   :value="car.car_Spare_Tire_tools_id">
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.spare_tire_equipment')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Spare_Tire_tools_ar" @else
                                                       :value="car.car_Spare_Tire_tools_en" @endif
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.wheel_condition')</label>
                                            <input type="hidden" name="tires" :value="car.car_Tires_status_id">
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.wheel_condition')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Tires_status_ar" @else
                                                       :value="car.car_Tires_status_en"
                                                   @endif readonly>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.spare_wheel_condition')</label>
                                            <input type="hidden" name="spareTire" :value="car.car_Spare_Tire_status_id"
                                                   required>

                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.spare_wheel_condition')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_Spare_Tire_status_ar"
                                                   @else
                                                       :value="car.car_Spare_Tire_status_en"
                                                   @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">

                                            <label class="form-label">@lang('home.first_aid_bag_case')</label>
                                            {{--<input type="hidden" name="firstAidKit" :value="car.car_First_Aid_Kit_id"--}}
                                            {{--required>--}}

                                            <input type="hidden" name="firstAidKit" :value="'8'"
                                                   required>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.first_aid_bag_case')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_First_Aid_Kit_ar" @else
                                                       :value="car.car_First_Aid_Kit_en"
                                                   @endif readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.key_case')</label>
                                            {{--<input type="hidden" name="keys" :value="car.car_keys_status_id" required>--}}
                                            <input type="hidden" name="keys" :value="'5'" required>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.key_case')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.car_keys_status_ar" @else
                                                       :value="car.car_keys_status_en" @endif
                                                   readonly>
                                        </div>
                                    </div>


                                    <a href="{{ route('car.rent.sketch-info') }}" target="_blank"
                                       class="btn btn-block btn-success">
                                        @lang('home.sketch_info')</a>

                                </div>


                            </div>
                        </div>

                    </div>


                    <div class="form-section">

                        {{--بيانات العقد--}}
                        <h2 class="text-center">@lang('home.contract_details')</h2>

                        {{--بيانات العقد--}}
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.contract_details')
                                    </p>
                                </h3>
                                <div class="row">
                                    {{--<div class="col-sm-6 col-md-12">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.contract_number')</label>--}}
                                    {{--<input type="text" class="form-control" disabled=""--}}
                                    {{--placeholder="Company" value="Creative Code Inc.">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.rent_policy')</label>
                                            <select name="rentPolicyId" class="form-control" required
                                                    v-model="rentPolicyId" @change="getRentPolicyTaxRate()">

                                                @foreach($rent_policies as $rent_policy)
                                                    <option value="{{$rent_policy->system_code_id}}">
                                                        {{
                                                        app()->getLocale() == 'ar'
                                                        ? $rent_policy->system_code_name_ar
                                                        : $rent_policy->system_code_name_en
                                                        }}
                                                    </option>
                                                @endforeach

                                                {{--<option v-for="policy in policies" :value="policy.id">--}}
                                                {{--@{{ policy.id }} => @{{ policy.earlyReturnPolicy }}--}}
                                                {{--</option>--}}
                                            </select>
                                        </div>
                                    </div>
                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.contact_created_date')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="20-20-2021">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.contract_created_place')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="city" value="{{app()->getLocale() == 'ar'
                                                   ? session('branch')['branch_name_ar']
                                                   :session('branch')['branch_name_en']}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.contract_start_date_time')</label>
                                            <input type="datetime-local" class="form-control" name="contractStartDate"
                                                   v-model="contractStartDate" @change="getDifferenceDate()">


                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.contract_end_date_time')</label>
                                            <input type="datetime-local" class="form-control" name="contractEndDate"
                                                   v-model="contractEndDate" @change="getDifferenceDate()">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.contract_type')</label>
                                            {{--<select name="contractTypeCode" class="form-control" id="contractTypeCode"--}}
                                            {{--onchange="contractType()" required v-model="contractTypeCode"--}}
                                            {{--readonly="">--}}
                                            {{--@foreach($contract_types as $contract_type)--}}
                                            {{--<option value="{{$contract_type->system_code}}">--}}
                                            {{--{{app()->getLocale() == "ar"--}}
                                            {{--? $contract_type->system_code_name_ar--}}
                                            {{--: $contract_type->system_code_name_en--}}
                                            {{--}}--}}
                                            {{--</option>--}}
                                            {{--@endforeach--}}
                                            {{--</select>--}}

                                            @if(app()->getLocale()=='ar')
                                                <input type="text" readonly :value="rent_type.system_code_name_ar"
                                                       class="form-control">
                                            @else

                                                <input type="text" readonly :value="rent_type.system_code_name_en"
                                                       class="form-control">
                                            @endif
                                            <input type="hidden" name="contractTypeCode" id="contractTypeCode"
                                                   v-model="rent_type.system_code"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.rent_type')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="rent type">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.contract_status')</label>
                                            <input type="text" class="form-control"
                                                   value="@lang('home.active')" readonly>


                                            <input type="hidden" name="contract_status"
                                                   value="1">
                                            {{--<select class="form-control" name="contract_status" required>--}}
                                            {{--<option value="">@lang('home.choose')</option>--}}
                                            {{--<option value="1" selected>@lang('home.active')</option>--}}
                                            {{--<option value="0">@lang('home.not_active')</option>--}}

                                            {{--</select>--}}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.contract_period')</label>
                                            <input type="number" class="form-control"
                                                   v-model="rent_period" @change="getContractEndDate()" required
                                                   min="1" name="discount_value">
                                            <small class="text-danger" v-if="date_message">@{{ date_message }}</small>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{--بيانات المؤجر--}}
                        <div class="card">
                            <div class="card-body">

                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.lessor_data')
                                    </p>
                                </h3>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.full_name')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   value="{{app()->getLocale() == 'ar' ?
                                                                   $company->company_name_ar
                                                                   :$company->company_name_en}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.commercial_registration_data')</label>
                                            <input type="text" class="form-control"
                                                   value="{{$company->company_register}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.phone')</label>
                                            <input type="text" class="form-control"
                                                   value="{{$company->co_phone_no}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.email')</label>
                                            <input type="text" class="form-control"
                                                   value="{{$company->co_email}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.tax_registration_number')</label>
                                            <input type="text" class="form-control"
                                                   value="{{$company->company_tax_no}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.license_category')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="type">
                                        </div>
                                    </div>

                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.working_branch_id')</label>--}}
                                    {{--<select name="workingBranchId" class="form-control">--}}
                                    {{--@foreach($branches as $branch)--}}
                                    {{--<option value="{{$branch->branch_id}}"--}}
                                    {{--@if($branch->branch_id == session('branch')['branch_id'])--}}
                                    {{--selected @endif>--}}
                                    {{--{{app()->getLocale() == 'ar' ?--}}
                                    {{--$branch->branch_name_ar : $branch->branch_name_en}}--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.receive_branch_id')</label>
                                            <select name="receiveBranchId" class="form-control">
                                                @foreach($branches as $branch)
                                                    <option value="{{$branch->branch_id}}"
                                                            @if($branch->branch_id == session('branch')['branch_id'])
                                                                selected @endif>
                                                        {{app()->getLocale() == 'ar' ?
                                                        $branch->branch_name_ar : $branch->branch_name_en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.return_branch_id')</label>
                                            <select name="returnBranchId" class="form-control">
                                                @foreach($branches as $branch)
                                                    <option value="{{$branch->branch_id}}"
                                                            @if($branch->branch_id == session('branch')['branch_id'])
                                                                selected @endif>
                                                        {{app()->getLocale() == 'ar' ?
                                                        $branch->branch_name_ar : $branch->branch_name_en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{--بيانات التاجير--}}
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.rent_data')
                                    </p>
                                </h3>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.rent_period')</label>
                                            <input type="text" class="form-control" v-model="rent_period"
                                                   readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.daily_cost')</label>
                                            <input type="text" class="form-control" name="rentDayCost"
                                                   readonly v-model="daily_price">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.free_kilometers_per_day')</label>
                                            <input type="text" class="form-control" name="allowedKmPerDay"
                                                   readonly :value="car.extra_kilometer" id="allowedKmPerDay">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.extra_kilometers_cost_in_sar')</label>
                                            <input type="text" class="form-control" name="extraKmCost"
                                                   readonly :value="car.extra_kilometer_price" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.delay_hours_count')</label>
                                            <input type="text" class="form-control" name=""
                                                   readonly :value="car.hours_to_day">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.allowed_delay_hours')</label>
                                            <input type="text" class="form-control" name="allowedLateHours"
                                                   readonly :value="car.extra_hour" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.delay_hours_cost')</label>
                                            <input type="text" class="form-control" name="rentHourCost"
                                                   readonly :value="car.extra_hour_price" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.total_delay_cost')</label>
                                            <input type="text" class="form-control" v-model="total_delay_cost" readonly>
                                        </div>
                                    </div>

                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.exit_location')</label>--}}
                                    {{--<select class="form-control">--}}
                                    {{--@foreach($branches as $branch)--}}
                                    {{--<option value="{{$branch->branch_id}}"--}}
                                    {{--@if($branch->branch_id == session('branch')['branch_id'])--}}
                                    {{--selected @endif>--}}
                                    {{--{{app()->getLocale() == 'ar' ?--}}
                                    {{--$branch->branch_name_ar : $branch->branch_name_en}}--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}

                                    {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.arrival_location')</label>--}}
                                    {{--<select class="form-control">--}}
                                    {{--@foreach($branches as $branch)--}}
                                    {{--<option value="{{$branch->branch_id}}"--}}
                                    {{--@if($branch->branch_id == session('branch')['branch_id'])--}}
                                    {{--selected @endif>--}}
                                    {{--{{app()->getLocale() == 'ar' ?--}}
                                    {{--$branch->branch_name_ar : $branch->branch_name_en}}--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.driver_salary_per_day')</label>
                                            <input type="text" class="form-control" name="driverFarePerDay"
                                                   readonly id="driverFarePerDay"
                                                   v-model="driverFarePerDay">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.driver_salary_per_hour')</label>
                                            <input type="text" class="form-control" name="driverFarePerHour"
                                                   readonly :value="(parseFloat(car.extra_driver) / 12).toFixed(2)"
                                                   id="driverFarePerHour">
                                        </div>
                                    </div>

                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.cost_delivering_car_in_another_city')</label>--}}
                                    {{--<input type="text" class="form-control" name="vehicleTransferCost"--}}
                                    {{--placeholder="35" value="0" required>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                </div>
                            </div>
                        </div>

                        {{-- البيانات الماليه للعقد --}}

                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.Contract_financial_information')
                                    </p>
                                </h3>
                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.daily_cost')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   placeholder="3 days" v-model="daily_price">
                                        </div>
                                    </div>
                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.the_total_value_of_the_extra_kilometres')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="120" value="0" name="extra_kilometres"--}}
                                    {{--v-model="extra_kilometres">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.the_value_of_the_international_authorization_in_sar')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--name="internationalAuthorizationCost"--}}
                                    {{--placeholder="300" value="230">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="form-label">@lang('home.the_value_of_delivering_the_car_in_another_city_in_sar')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="0.4" value="0" name="car_in_another_city"
                                                   v-model="car_in_another_city">
                                        </div>
                                    </div>
                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.spare_parts_value')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="34" value="0" name="spare_parts_value"--}}
                                    {{--v-model="spare_parts_value">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.oil_change_value')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="35" value="0" name="oil_change_value"--}}
                                    {{--v-model="oil_change_value">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.car_damage_assessment')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="34" value="0" name="car_damage_assessment"--}}
                                    {{--v-model="car_damage_assessment">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.supplementary_service')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="35" value="0" name="supplementary_service"--}}
                                    {{--v-model="supplementary_service">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.number_of_additional_drivers')</label>
                                            <input type="text" class="form-control"
                                                   v-model="number_of_additional_drivers"
                                                   placeholder="0" name="number_of_additional_drivers"
                                            >
                                        </div>
                                    </div>

                                    {{--//////////////////////////////////////--}}

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label
                                                class="form-label">@lang('home.the_total_value_of_adding_drivers')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="35" value="0" name="the_total_value_of_adding_drivers"
                                                   v-model="the_total_value_of_adding_drivers" readonly>
                                        </div>
                                    </div>

                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.total_rental_value_per_day')</label>--}}
                                    {{--<input type="text" class="form-control"--}}
                                    {{--placeholder="35">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.total_before_discount')</label>
                                            <input type="text" class="form-control"
                                                   name="total_before_discount"
                                                   placeholder="0" v-model="total_before_discount" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">

                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.discount_value')</label>
                                            <input type="text" class="form-control" name="discount" readonly
                                                   placeholder="10% percentage" required v-model="discount_percentage">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.discount_amount')</label>
                                            <input type="text" class="form-control" name="discount_amount" readonly
                                                   required v-model="discount_amount">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.added_tax')</label>
                                            <input type="text" class="form-control"
                                                   name="contract_vat_rate" readonly=""
                                                   placeholder="300" v-model="added_tax">
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.added_tax_amount')</label>
                                            <input type="text" class="form-control" readonly
                                                   name="contract_vat_amount" v-model="contract_vat_amount">
                                        </div>
                                    </div>


                                    {{--<div class="col-sm-6 col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.extraDriverCost')</label>--}}
                                    {{--<input type="text" class="form-control" name="extraDriverCost"--}}
                                    {{--id="extraDriverCost" value="0"--}}
                                    {{--placeholder="300">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}


                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.final_total')</label>
                                            <input type="text" class="form-control"
                                                   name="final_total"
                                                   placeholder="300" v-model="final_total" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.paid_up')</label>
                                            <input type="number" class="form-control" name="paid"
                                                   required v-model="paid_up" min="1">
                                            <small class="text-danger" v-if="paid_message">@{{ paid_message }}</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.remaining_cost')</label>
                                            <input type="text" class="form-control" name="contract_balance"
                                                   placeholder="300" v-model="remain_cost" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.payment_method')</label>
                                            <select class="form-control" name="paymentMethodCode"
                                                    required>
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($payment_methods as $payment_method)
                                                    <option value="{{$payment_method->system_code_id}}">
                                                        {{app()->getLocale() == "ar"
                                                          ? $payment_method->system_code_name_ar
                                                          : $payment_method->system_code_name_en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--<div class="col-sm-2 col-md-2">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.extended_coverage')</label>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-1 col-md-1">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<input class="form-control mt-2" type="checkbox" name="extended_coverage"--}}
                                    {{--v-model="extended_coverage" value="1"--}}
                                    {{--@click="show_extended_coverage = !show_extended_coverage">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-9 col-md-9">--}}
                                    {{--<div class="form-group">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}

                                    {{--<div class="col-sm-6 col-md-6" v-if="show_extended_coverage">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.extended_coverage_id')</label>--}}
                                    {{--<select class="form-control" name="extendedCoverageId">--}}
                                    {{--<option value="">@lang('home.choose')</option>--}}
                                    {{--<option v-for="extended_coverage in extended_coverages"--}}
                                    {{--:value="extended_coverage.id ">--}}

                                    {{--@{{ extended_coverage.extendedCoverageName }}--}}

                                    {{--</option>--}}
                                    {{--</select>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-sm-6 col-md-6" v-if="show_extended_coverage">--}}
                                    {{--<div class="form-group">--}}
                                    {{--<label class="form-label">@lang('home.additional_coverage_cost')</label>--}}
                                    {{--<input type="number" class="form-control" name="additionalCoverageCost">--}}
                                    {{--</div>--}}
                                    {{--</div>--}}


                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="form-section">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">
                                    <p style="font-weight: bold;font-size: 25px;">
                                        @lang('home.contract_summary')
                                    </p>
                                </h3>
                                <div class="row">

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.customer_name')</label>
                                            <input type="text" class="form-control"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="customer.customer_name_full_ar"
                                                   @else
                                                       :value="customer.customer_name_full_en"
                                                   @endif
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.id_number')</label>
                                            <input type="text" class="form-control" name="c_idNumber"
                                                   :value="customer.customer_identity" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.rent_period')</label>
                                            <input type="text" class="form-control" v-model="rent_period" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.daily_cost')</label>
                                            <input type="text" class="form-control" name="rentDayCost"
                                                   readonly v-model="daily_price">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.final_total')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="300" v-model="final_total" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.remaining_cost')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="300" v-model="remain_cost" readonly="">
                                        </div>
                                    </div>
                                    {{--بيانات السياره--}}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.car_model')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   placeholder="@lang('home.car_model')"
                                                   @if(app()->getLocale() == 'ar')
                                                       :value="car.model_name_ar"
                                                   @else
                                                       :value="car.model_name_en"
                                                @endif>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.color')</label>
                                            <input type="text" class="form-control" readonly
                                                   placeholder="@lang('home.color')" :value="car.color">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.meter_reading_before')</label>
                                            <input type="text" class="form-control"
                                                   placeholder="@lang('home.meter_reading_before')"
                                                   name="odometerReading" required
                                                   :value="car.odometer_start" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.free_kilometers_per_day')</label>
                                            <input type="text" class="form-control" name="allowedKmPerDay"
                                                   readonly :value="car.extra_kilometer" id="allowedKmPerDay">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.delay_hours_count')</label>
                                            <input type="text" class="form-control" name="allow_hr_to_day"
                                                   readonly :value="car.hours_to_day">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.plate_number')</label>
                                            <input type="text" class="form-control" name=""
                                                   readonly :value="car.plate_number">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.sketch_info')</label>
                                            <input type="file" class="form-control" name="sketchInfo">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="form-navigation">
                        <div class="row">

                            <div class="col-md-4">
                                <button type="button" class="next btn btn-danger "
                                        style="float: right !important; background-color: #17a2b8 !important;"
                                        :disabled="disable_button || price_disabled">@lang('home.next')
                                </button>
                            </div>


                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger text-center " style="margin-right: 40%!important;
                                          background-color: #bd2130 !important;"
                                        onclick="refreshPage()">@lang('home.refresh')
                                </button>
                            </div>

                            <div class="col-md-4">
                                <button type="button" class="previous btn btn-info float-left"
                                        style="float: left !important;">@lang('home.previous')
                                </button>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-success"
                                style="float: right !important; background-color: #10d83e !important;"
                                :disabled="disable_button || price_disabled">@lang('home.submit')
                        </button>


                    </div>

                    <div id="text-message" style="display: none;" class="alert alert-success">
                        <h1> تم ارسال البيانات </h1>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script>

        function refreshPage() {
            window.location.reload()
        }

        var today = '{{$current_date}}';
        //$('#contractStartDate')[0].setAttribute('min', today);
        //document.getElementsByName("contractStartDate")[0].setAttribute('min', today);
        // document.getElementsByName("contractEndDate")[0].setAttribute('min', today);

        $(function () {

            var $sections = $('.form-section');

            function navigateTo(index) {
                $sections.removeClass('current').eq(index).addClass('current');
                $('.form-navigation .previous').toggle(index > 0);
                var atTheEnd = index >= $sections.length - 1;
                $('.form-navigation .next').toggle(!atTheEnd);
                $('.form-navigation [type=submit]').toggle(atTheEnd);

            }

            function curIndex() {
                return $sections.index($sections.filter('.current'));
            }

            $('.form-navigation .previous').click(function () {
                navigateTo(curIndex() - 1);
            });

            $('.form-navigation .next').click(function () {
                $('.contact-form').parsley().whenValidate({
                    group: 'block-' + curIndex()
                }).done(function () {
                    navigateTo(curIndex() + 1);
                })
            })

            $sections.each(function (index, section) {
                $(section).find(':input').attr('data-parsley-group',
                    'block-' + index);
            });

            navigateTo(0)

        })

        function extraDriver(el) {
            if (el.val() == 1) {
                $('#extraDriverCost').prop('required', true)
            } else {
                $('#extraDriverCost').prop('required', false)
            }
        }

        // function contractType() {

        if ($('#contractTypeCode').val() == 65002) { //hourly with driver
            $('#driverFarePerHour').prop('required', true) /// hourly with driver
            $('#allowedKmPerHour').prop('required', true) /// if contract type per hour

            $('#allowedKmPerDay').prop('required', false)
            $('#driverFarePerDay').prop('required', false)

        } else if ($('#contractTypeCode').val() == 65001) {
            $('#allowedKmPerDay').prop('required', true) //// required if contract type per day
            $('#driverFarePerDay').prop('required', true) /// required if contract type per day with driver

            $('#driverFarePerHour').prop('required', false)
            $('#allowedKmPerHour').prop('required', false)

        } else {
            $('#driverFarePerHour').prop('required', false)
            $('#allowedKmPerHour').prop('required', false)

            $('#allowedKmPerDay').prop('required', false)
            $('#driverFarePerDay').prop('required', false)
        }
        // }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                contractStartDate: '{{$current_date}}',
                customer_id: '',
                driver_id: '',
                commissioner_id: '',
                customer: {},
                driver: {},
                commissioner: {},
                additional_driver: '',
                commissioner_selected: '',
                show_additional_driver: false,
                show_commissioner: false,
                driver_required: false,
                commissioner_required: false,
                cars: {},
                plate_number: '',
                brand: '',
                car_model: '',
                car_category: '',
                car_model_year: '',
                car_color: '',
                last_odometer_c: 0,
                tracker_status: '',
                isLoaded: false,
                car: {},
                show_cars: false,
                policies: {},
                branches: {},
                extended_coverages: {},
                extended_coverage: '',
                show_extended_coverage: false,
                commissioner_type_code: {},
                driver_type_code: {},
                customer_type_code: {},
                rent_period: 0,
                daily_price: '',
                discount_percentage: 0,
                added_tax: 0,
                paid_up: 0,
                // price_list: {},
                list_price_error: '',
                contractEndDate: '',
                disable_button: false,
                date_message: '',
                rentPolicyId: '',
                // extra_kilometres: 0,
                car_in_another_city: 0,
                // spare_parts_value: 0,
                // oil_change_value: 0,
                // car_damage_assessment: 0,
                // supplementary_service: 0,
                // number_of_additional_drivers: 0,
                price_disabled: false,
                edit_customer: false,
                create_customer: true,
                contractTypeCode: '',
                rent_type: {},
                number_of_additional_drivers: 0,
                driverFarePerDay: 0,

                customer_error: '',
                customer_valid: 1,
                commissioner_error: '',
                commissioner_valid: 1,
                driver_error: '',
                driver_valid: 1,
            },

            mounted() {
                // this.getCars()
                this.getPolicies()
                this.getBranches()
                this.getExtendedCoverage()
                this.customer_id = '{{request()->customer_id}}'
                if(this.customer_id){
                    this.getCustomer()
                    this.getCars()
                    this.editCustomerValid()
                }
            },
            methods: {
                editCustomerValid() {
                    if (this.customer_id) {
                        this.edit_customer = true
                        this.create_customer = false
                    } else {
                        this.edit_customer = false
                        this.create_customer = true
                    }
                },
                getRentPolicyTaxRate() {
                    $.ajax({
                        type: 'GET',
                        data: {rentPolicyId: this.rentPolicyId},
                        url: '{{ route("car-rent.contract.getRentPolicyTaxRate") }}'
                    }).then(response => {
                        this.added_tax = response.data
                    })
                },
                {{--getPriceList() {--}}
                    {{--this.list_price_error = ''--}}
                    {{--if (this.customer_id && Object.keys(this.car).length > 0) {--}}
                    {{--$.ajax({--}}
                    {{--type: 'GET',--}}
                    {{--data: {customer_id: this.customer_id, car_id: this.car.car_id},--}}
                    {{--url: '{{ route("api.carRent.getPriceList") }}'--}}
                    {{--}).then(response => {--}}
                    {{--if (response.data) {--}}
                    {{--this.price_list = response.data--}}
                    {{--} else if (response.message) {--}}
                    {{--this.list_price_error = response.message--}}
                    {{--}--}}
                    {{--})--}}
                    {{--}--}}
                    {{--},--}}
                toggleAdditionalDriver() {

                    if (this.additional_driver == 0) {
                        this.show_additional_driver = false
                        this.driver_required = false
                        this.number_of_additional_drivers = 0
                        this.driver = {}
                        this.driver_id = ''
                    }

                    if (this.additional_driver == 1) {
                        this.show_additional_driver = true
                        this.driver_required = true
                        this.number_of_additional_drivers = 1
                    }

                },

                toggleCommissioner() {

                    if (this.commissioner_selected == 0) {
                        this.show_commissioner = true
                        this.commissioner_required = true

                    }

                    if (this.commissioner_selected == 1) {
                        this.show_commissioner = false
                        this.commissioner_required = false
                        this.commissioner = {}
                        this.commissioner_id = ''
                    }

                },

                getCustomer() {
                    this.customer = {}
                    this.customer_type_code = {}
                    if (this.customer_id) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id},
                            url: '{{ route("api.carRent.customer") }}'
                        }).then(response => {
                            if (response.data.active_attachment <= 1) {
                                this.customer_error = '{{__('messages.attachment_not_active')}}'
                                this.customer_valid = 0
                                this.disable_button = true
                            }
                            if (response.data.check_credit_limit > 0) {
                                this.customer_error = '{{__('messages.check_credit_limit_not_active')}}'
                                this.customer_valid = 0
                                this.disable_button = true
                            }
                            if (this.customer_valid) {
                                this.customer = response.data
                                this.customer_type_code = response.id_type
                                this.customer_valid = 1
                                this.disable_button = false
                            }
                        })
                    }

                },

                getDriver() {

                    this.driver = {}
                    this.driver_type_code = {}
                    if (this.driver_id) {
                        $.ajax({
                            type: 'GET',
                            data: {driver_id: this.driver_id},
                            url: '{{ route("api.carRent.customer") }}'
                        }).then(response => {
                            if (response.data.active_attachment <= 0) {
                                this.driver_error = '{{__('messages.attachment_not_active')}}'
                                this.driver_valid = 0
                                this.disable_button = true
                            }
                            if (this.customer_valid) {
                                this.driver = response.data
                                this.driver_type_code = response.id_type
                                this.driver_valid = 1
                                this.disable_button = false
                            }
                        })
                    }
                },
                getCommissioner() {

                    this.commissioner = {}
                    this.commissioner_type_code = {}
                    if (this.commissioner_id) {
                        $.ajax({
                            type: 'GET',
                            data: {commissioner_id: this.commissioner_id},
                            url: '{{ route("api.carRent.customer") }}'
                        }).then(response => {
                            if (response.data.active_attachment <= 0) {
                                this.commissioner_error = '{{__('messages.attachment_not_active')}}'
                                this.commissioner_valid = 0
                                this.disable_button = true
                            }
                            if (this.customer_valid) {
                                this.commissioner = response.data
                                this.commissioner_type_code = response.id_type
                                this.commissioner_valid = 1
                                this.disable_button = false
                            }
                        })
                    }
                },

                getDifferenceDate() {
                    this.rent_period = 0
                    this.date_message = ''
                    this.disable_button = false
                    if (this.contractEndDate && this.contractStartDate) {
                        $.ajax({
                            type: 'GET',
                            data: {contractEndDate: this.contractEndDate, contractStartDate: this.contractStartDate},
                            url: '{{ route("car-rent.contract.getDifferenceDate") }}'
                        }).then(response => {
                            console.log(response.message)
                            if (response.status == 500) {
                                console.log('a')
                                this.date_message = response.message
                                this.disable_button = true
                            } else {
                                console.log('b')
                                this.rent_period = response.data
                            }

                        })
                    }
                },

                getContractEndDate() {
                    if (this.rent_period && this.contractStartDate) {
                        $.ajax({
                            type: 'GET',
                            data: {rent_period: this.rent_period, contractStartDate: this.contractStartDate},
                            url: '{{ route("car-rent.contract.getContractEndDate") }}'
                        }).then(response => {
                            this.contractEndDate = response.data
                        })
                    }
                },
                getCars() {
                    this.cars = []
                    if (this.customer_id) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id},
                            url: '{{ route("api.carRent.getCars") }}'
                        }).then(response => {
                            console.log(response.data)
                            this.cars = response.data
                            this.cars = response.data
                            this.isLoaded = true
                        })
                    }

                },
                getSelectedCar(index) {
                    this.price_disabled = false
                    console.log(index)
                    this.car = this.cars[index]
                    this.daily_price = this.car.daily_price
                    this.rent_type = this.car.rent_type
                    this.driverFarePerDay = this.car.extra_driver

                    if (this.daily_price == 0) {
                        this.price_disabled = true
                    } else {
                        this.price_disabled = false
                    }
                    this.discount_percentage = this.car.discount_value

                    // this.getPriceList()
                },

                getPolicies() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("api.carRent.getAllPolicies") }}'
                    }).then(response => {
                        this.policies = response.data
                    })
                },

                getBranches() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("api.carRent.getAllBranches") }}'
                    }).then(response => {
                        this.branches = response.data
                    })
                },

                getExtendedCoverage() {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("api.carRent.getExtendedCoverage") }}'
                    }).then(response => {
                        this.extended_coverages = response.data
                    })
                },


            },
            computed: {
                // disablePaidUp: function () {
                //     if (this.paid_up > 0) {
                //         return false;
                //     } else {
                //         return true;
                //     }
                // },
                the_total_value_of_adding_drivers: function () {
                    return this.driverFarePerDay * this.rent_period * this.number_of_additional_drivers
                },
                edit_driver: function () {
                    if (this.driver_id) {
                        return true
                    } else {
                        return false
                    }
                },
                edit_commissioner: function () {
                    if (this.commissioner_id) {
                        return true
                    } else {
                        return false
                    }
                },
                filteredCars: function () {
                    if (this.isLoaded == true) {
                        return this.cars.filter(car => {
                            return car.plate_number.match(this.plate_number)
                        })
                    }
                },
                filteredCars2: function () {
                    if (this.isLoaded == true) {
                        return this.filteredCars.filter(car => {
                            return car.brand_name_ar.match(this.brand)
                        })
                    }
                },
                filteredCars3: function () {
                    if (this.isLoaded == true) {
                        return this.filteredCars2.filter(car => {
                            return car.model_name_ar.match(this.car_model)
                        })
                    }
                },
                filteredCars4: function () {
                    if (this.isLoaded == true) {
                        return this.filteredCars3.filter(car => {
                            return car.model_name_ar.match(this.car_model)
                        })
                    }
                },
                filteredCars5: function () {
                    if (this.isLoaded == true) {
                        return this.filteredCars4.filter(car => {
                            return car.car_model_year.match(this.car_model_year)
                        })
                    }
                },
                filteredCars6: function () {
                    if (this.isLoaded == true) {
                        return this.filteredCars5.filter(car => {
                            return car.color.match(this.car_color)
                        })
                    }
                },
                // filteredCars7: function () {
                //     if (this.isLoaded == true) {
                //         return this.filteredCars6.filter(car => {
                //             return car.last_odometer.match(this.last_odometer_c)
                //         })
                //     }
                // },
                filteredCars7: function () {
                    if (this.isLoaded == true) {
                        return this.filteredCars6.filter(car => {
                            return car.tracker_status_ar.match(this.tracker_status)
                        })
                    }
                },
                total_delay_cost: function () {
                    var a = this.daily_price * this.rent_period
                    return a.toFixed(2);
                },
                total_before_discount: function () {
                    var b = (this.daily_price * this.rent_period)
                        + parseFloat(this.car_in_another_city)
                        + parseFloat(this.the_total_value_of_adding_drivers)
                    return b.toFixed(2)
                },
                final_total: function () {

                    var discount_amount = ((this.discount_percentage / 100) * this.total_before_discount).toFixed(2);

                    var total_with_discount = (this.total_before_discount - discount_amount).toFixed(2);

                    var vat_amount = ((this.added_tax / 100) * total_with_discount).toFixed(2);

                    var final = parseFloat(total_with_discount) + parseFloat(vat_amount);

                    return final.toFixed()
                },
                paid_message: function () {
                    if (parseFloat(this.paid_up) < parseFloat(this.final_total)) {
                        return 'المدفوع أقل من التكلفة الاجماليه';
                    } else {
                        return '';
                    }
                },
                remain_cost: function () {
                    var x = parseFloat(this.final_total) - parseFloat(this.paid_up);
                    return x.toFixed(2)
                },
                contract_vat_amount: function () {
                    var discount_amount = (this.discount_percentage / 100) * this.total_before_discount;

                    var total_with_discount = this.total_before_discount - discount_amount.toFixed(2);

                    var vat_amount = (this.added_tax / 100) * total_with_discount;

                    // var z = parseFloat(this.added_tax / 100) * this.total_before_discount;
                    return vat_amount.toFixed(2)
                },
                discount_amount: function () {
                    // var d = parseFloat(this.discount_percentage / 100) * (this.total_before_discount + this.contract_vat_amount)
                    var d = parseFloat(this.discount_percentage / 100) * (this.total_before_discount)
                    return d.toFixed(2)
                }
            }

        });


    </script>

@endsection
