@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
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
                    <form class="card" id="validate-form" action="{{ route('CarRentModel.store') }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf

                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('carrent.add_car')
                            </div>
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_plate_ar') </label>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="plate_ar_1"
                                                               v-model="plate_ar_1" id="plate_ar_1">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="plate_ar_2"
                                                               v-model="plate_ar_2" id="plate_ar_2">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="plate_ar_3"
                                                               v-model="plate_ar_3" id="plate_ar_3">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="car_plate_number"
                                                               v-model="car_plate_number" id="car_plate_number">
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="col-md-6">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_plate_en') </label>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="plate_en_1"
                                                               v-model="plate_en_1" id="plate_en_1">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="plate_en_2"
                                                               v-model="plate_en_2" id="plate_en_2">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="plate_en_3"
                                                               v-model="plate_en_3" id="plate_en_3">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control"
                                                               v-model="car_plate_number" id="car_plate_number">
                                                    </div>
                                                </div>


                                            </div>


                                        </div>


                                        <div class="mb-3">
                                            <div class="row">


                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_chasi') </label>
                                                    <input type="number" class="form-control is-invalid"
                                                           name="car_chase"
                                                           id="car_chase" placeholder="@lang('carrent.car_chasi')">

                                                </div>

                                                <div class="col-md-4">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_motor_no') </label>
                                                    <input type="text" class="form-control is-invalid"
                                                           name="car_motor_no"
                                                           id="car_motor_no" placeholder="@lang('carrent.car_motor_no')"
                                                           required>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.platetype') </label>
                                                    <select class="form-select form-control "
                                                            name="platetype"
                                                            id="platetype">

                                                        <option value="" > @lang('home.choose')</option>
                                                        @foreach($sys_platetype_statuses as $sys_platetype_status)
                                                            <option value="{{$sys_platetype_status->system_code_id}}"
                                                                    @if($car->platetype == $sys_platetype_status->system_code_id)
                                                                        selected @endif>
                                                                {{app()->getLocale() == 'ar'
                                                                ? $sys_platetype_status->system_code_name_ar
                                                                : $sys_platetype_status->system_code_name_en }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                            </div>
                                        </div>


                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_registration_type') </label>
                                                    <select class="form-select form-control is-invalid"
                                                            name="car_registration_type"
                                                            id="car_registration_type">
                                                        <option value="" selected></option>
                                                        @foreach($sys_codes_type as $sys_code_type)
                                                            <option value="{{$sys_code_type->system_code_id}}">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{$sys_code_type->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_type->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_operation_card_no') </label>
                                                    <input type="number" class="form-control is-invalid"
                                                           name="car_operation_card_no"
                                                           id="car_operation_card_no"
                                                           placeholder="@lang('carrent.car_operation_card_no')">

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_operation_card_date') </label>
                                                    <input type="date" class="form-control is-invalid"
                                                           name="car_operation_card_date"
                                                           id="car_operation_card_date"
                                                           placeholder="@lang('carrent.car_operation_card_date')"
                                                           onchange="getDateBirthday()">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_status_id') </label>
                                                    <select class="form-select form-control is-invalid"
                                                            name="car_status_id"
                                                            id="car_status_id">
                                                        <option value="" selected></option>
                                                        @foreach($sys_codes_type as $sys_code_type)
                                                            <option value="{{$sys_code_type->system_code_id}}">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{$sys_code_type->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_type->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                            </div>
                                        </div>


                                    </div>


                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_ownership_status') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="car_ownership_status"
                                                        aria-label="Default select example" id="car_ownership_status"
                                                        required>
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_ownership_status as $sys_code_ownership_status)
                                                        <option value="{{$sys_code_ownership_status->system_code_id}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_ownership_status->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_ownership_status->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_purchase_value') </label>
                                                <input type="number" class="form-control is-invalid"
                                                       name="car_purchase_value"
                                                       id="car_purchase_value"
                                                       placeholder="@lang('carrent.car_purchase_value')">

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_ownership') </label>
                                                <input type="text" class="form-control is-invalid" name="car_ownership"
                                                       id="car_ownership" placeholder="@lang('carrent.car_ownership')"
                                                       required>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.sub_company') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="company_id"
                                                        id="company_id"
                                                        @change="getBranches()" v-model="company_id" required>
                                                    <option value="" selected>Choose</option>
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
                                                <input type="file" id="dropify-event"
                                                       name="car_photo">
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
                                        <select class="form-select form-control is-invalid" name="car_brand_id"
                                                aria-label="Default select example" id="car_brand_id">
                                            <option value="" selected></option>
                                            @foreach($car_rent_brands as $car_rent_brand)
                                                <option value="{{$car_rent_brand->brand_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$car_rent_brand->brand_name_ar}}
                                                    @else
                                                        {{$car_rent_brand->brand_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_model') </label>
                                        <select class="form-select form-control is-invalid" name="car_model_id"
                                                aria-label="Default select example" id="car_model_id" required>
                                            <option value="" selected></option>
                                            @foreach($car_rent_brands_dt as $car_rent_brand_dt)
                                                <option value="{{$car_rent_brand_dt->brand_dt_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$car_rent_brand_dt->brand_dt_name_ar}}
                                                    @else
                                                        {{$car_rent_brand_dt->brand_dt_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('carrent.car_model_year') </label>
                                        <input type="number" class="form-control is-invalid" name="car_model_year"
                                               id="car_model_year" placeholder="@lang('carrent.car_model_year')">

                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_category') </label>
                                        <select class="form-select form-control is-invalid" name="car_category_id"
                                                aria-label="Default select example" id="car_category_id" required>
                                            <option value="" selected></option>
                                            @foreach($sys_codes_status as $sys_code_status)
                                                <option value="{{$sys_code_status->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_status->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_status->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.gear_box_type_id') </label>
                                        <select class="form-select form-control is-invalid" name="gear_box_type_id"
                                                aria-label="Default select example" id="gear_box_type_id" required>
                                            <option value="" selected></option>
                                            @foreach($sys_codes_status_68 as $sys_code_status_68)
                                                <option value="{{$sys_code_status_68->system_code}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_status_68->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_status_68->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.engine_type') </label>
                                        <select class="form-select form-control is-invalid" name="engine_type"
                                                aria-label="Default select example" id="engine_type" required>
                                            <option value="" selected></option>
                                            @foreach($sys_codes_status_69 as $sys_code_status_69)
                                                <option value="{{$sys_code_status_69->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_status_69->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_status_69->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.fuel_type_id') </label>
                                        <select class="form-select form-control is-invalid" name="fuel_type_id"
                                                aria-label="Default select example" id="fuel_type_id" required>
                                            <option value="" selected></option>
                                            @foreach($sys_codes_status_70 as $sys_code_status_70)
                                                <option value="{{$sys_code_status_70->system_code}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_status_70->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_status_70->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('carrent.car_color') </label>
                                        <input type="number" class="form-control is-invalid" name="car_color"
                                               id="car_color" placeholder="@lang('carrent.car_color')">

                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.oil_type') </label>
                                    <select class="form-select form-control is-invalid" name="oil_type"
                                            aria-label="Default select example" id="oil_type" required>
                                        <option value="" selected></option>
                                        @foreach($sys_codes_status_71 as $sys_code_status_71)
                                            <option value="{{$sys_code_status_71->system_code}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_status_71->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_status_71->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.oil_change_km') </label>
                                    <input type="number" class="form-control is-invalid" name="oil_change_km"
                                           id="oil_change_km" placeholder="@lang('carrent.oil_change_km')">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_doors') </label>
                                    <input type="number" class="form-control is-invalid" name="car_doors"
                                           id="car_doors" placeholder="@lang('carrent.car_doors')">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_passengers') </label>
                                    <input type="number" class="form-control is-invalid" name="car_passengers"
                                           id="car_passengers" placeholder="@lang('carrent.car_passengers')">

                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.insurance_type') </label>
                                    <select class="form-select form-control is-invalid" name="insurance_type"
                                            aria-label="Default select example" id="insurance_type" required>
                                        <option value="" selected></option>
                                        @foreach($sys_codes_status_71 as $sys_code_status_71)
                                            <option value="{{$sys_code_status_71->system_code}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_status_71->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_status_71->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.insurance_document_no') </label>
                                    <input type="number" class="form-control is-invalid" name="insurance_document_no"
                                           id="insurance_document_no"
                                           placeholder="@lang('carrent.insurance_document_no')">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.insurance_value') </label>
                                    <input type="number" class="form-control is-invalid" name="insurance_value"
                                           id="insurance_value" placeholder="@lang('carrent.insurance_value')">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.insurance_date_end') </label>
                                    <input type="date" class="form-control is-invalid" name="insurance_date_end"
                                           id="insurance_date_end" placeholder="@lang('carrent.insurance_date_end')"
                                           onchange="getDateBirthday()">
                                </div>


                            </div>


                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.car_trucker_status') </label>
                                    <select class="form-select form-control is-invalid" name="car_trucker_status"
                                            aria-label="Default select example" id="car_trucker_status" required>
                                        <option value="" selected></option>
                                        @foreach($sys_codes_status_71 as $sys_code_status_71)
                                            <option value="{{$sys_code_status_71->system_code}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_code_status_71->system_code_name_ar}}
                                                @else
                                                    {{$sys_code_status_71->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.tracker_serial') </label>
                                    <input type="number" class="form-control is-invalid" name="tracker_serial"
                                           id="tracker_serial" placeholder="@lang('carrent.tracker_serial')">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.tracker_supplier') </label>
                                    <input type="number" class="form-control is-invalid" name="tracker_supplier"
                                           id="tracker_supplier" placeholder="@lang('carrent.tracker_supplier')">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.tracker_install_date') </label>
                                    <input type="date" class="form-control is-invalid" name="tracker_install_date"
                                           id="tracker_install_date" placeholder="@lang('carrent.tracker_install_date')"
                                           onchange="getDateBirthday()">
                                </div>


                            </div>


                            <div class="row">

                                <div class="card bline" style="color:red">
                                </div>


                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="submit">@lang('carrent.save')</button>
                                <div class="spinner-border" role="status" style="display: none">
                                    <span class="sr-only">Loading...</span>


                                </div>
                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
    <script>
        $(document).ready(function () {

            //     //    validation to create trucks

            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })


            $('#customer_mobile').keyup(function () {
                if ($('#customer_mobile').val().length < 9) {
                    $('#customer_mobile').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#customer_mobile').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });


            $('#customer_credit_limit').keyup(function () {
                if ($('#customer_credit_limit').val().length < 4) {
                    $('#customer_credit_limit').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#customer_credit_limit').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });


            $('#customer_mobile_code').change(function () {
                if (!$('#customer_mobile_code').val()) {
                    $('#customer_mobile_code').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#customer_mobile_code').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });


            $('#customer_type').change(function () {
                if (!$('#customer_type').val()) {
                    $('#customer_type').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#customer_type').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">


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
                company_id: '',
                branches: {}
            },


        })

    </script>
@endsection
