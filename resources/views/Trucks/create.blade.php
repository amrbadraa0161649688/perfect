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
                    <form class="card" id="validate-form" action="{{ route('Trucks.store') }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf

                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('trucks.add_new_truck')
                            </div>
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_no') </label>
                                                <input type="text" class="form-control"
                                                       v-bind:class="{ 'is-invalid' : full_ar }"
                                                       name="truck_code"
                                                       id="truck_code" placeholder="@lang('trucks.truck_no')" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_name') </label>
                                                <input type="text" class="form-control"
                                                       v-bind:class="{ 'is-invalid' : full_en }"
                                                       name="truck_name"
                                                       id="truck_name" placeholder="@lang('trucks.truck_name')"
                                                       required>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_type') </label>
                                                <select class="form-select form-control is-invalid" name="truck_type"
                                                        id="truck_type" required>
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
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_status') </label>
                                                <select class="form-select form-control is-invalid" name="truck_status"
                                                        aria-label="Default select example" id="truck_status" required>
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


                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.rightLetter') </label>

                                                <select class="form-control plate_ar_1" name="rightLetter"
                                                        required v-model="rightLetter">
                                                    @foreach(config('global.car_letters_ar') as $letter )
                                                        <option value="{{$letter}}">{{$letter}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.middleLetter') </label>
                                                <select class="form-control plate_ar_2" name="middleLetter"
                                                        required v-model="middleLetter">
                                                    @foreach(config('global.car_letters_ar') as $letter )
                                                        <option value="{{$letter}}">{{$letter}}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.leftLetter') </label>
                                                <select type="text" class="form-control plate_ar_3" required
                                                        name="leftLetter" v-model="leftLetter">
                                                    @foreach(config('global.car_letters_ar') as $letter )
                                                        <option value="{{$letter}}">{{$letter}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.plate_number') </label>
                                                <input type="number" class="form-control car_plate_number1"
                                                       name="plate_number" required v-model="plate_number">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.plateType') </label>
                                                <select class="form-control" name="plateTypeId" required>
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($plate_types as $plate_type)
                                                        <option value="{{$plate_type->system_code_id}}">
                                                            {{app()->getLocale()=='ar' ? $plate_type->system_code_name_ar :
                                                            $plate_type->system_code_name_en}}
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
                                                       class="col-form-label"> @lang('trucks.truck_plate') </label>
                                                <input type="text" class="form-control is-invalid" name="truck_plate_no"
                                                       id="truck_plate_no" placeholder="@lang('trucks.truck_plate')"
                                                       v-model="truck_plate_no" readonly>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_chasi') </label>
                                                <input type="text" class="form-control" name="truck_chassis_no"
                                                       id="truck_chassis_no" placeholder="@lang('trucks.truck_chasi')">
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_manufactuer_company') </label>
                                                <select class="form-select form-control"
                                                        name="truck_manufactuer_company"
                                                        aria-label="Default select example"
                                                        id="truck_manufactuer_company">
                                                    <option value="" selected></option>
                                                    @foreach($sys_codes_manufactuer as $sys_code_manufactuer)
                                                        <option value="{{$sys_code_manufactuer->system_code_id}}">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{$sys_code_manufactuer->system_code_name_ar}}
                                                            @else
                                                                {{$sys_code_manufactuer->system_code_name_en}}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_model') </label>
                                                <input type="number" class="form-control" name="truck_model"
                                                       id="truck_model" placeholder="@lang('trucks.truck_model')">

                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.truck_ownership_status') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="truck_ownership_status"
                                                        aria-label="Default select example" id="truck_ownership_status"
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
                                                       class="col-form-label"> @lang('trucks.truck_rent_amount') </label>
                                                <input type="number" class="form-control" name="truck_rent_amount"
                                                       id="truck_rent_amount"
                                                       placeholder="@lang('trucks.truck_rent_amount')">

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('trucks.truck_ownership') </label>
                                                <select class="form-select form-control" name="truck_ownership_id"
                                                        id="truck_ownership_id">
                                                    <option value="" selected>choose</option>
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
                                            
                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('trucks.sub_company') </label>
                                                <select class="form-select form-control is-invalid"
                                                        name="company_id"
                                                        id="company_id"
                                                        required>
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
                                                <h3 class="card-title">@lang('trucks.add_photo')</h3>
                                            </div>
                                            <div class="card-body">
                                                <input type="file" id="dropify-event"
                                                       name="truck_photo">
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>


                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_supplier') </label>
                                        <select class="form-select form-control" name="truck_supplier"
                                                id="truck_supplier">
                                            <option value="" selected>choose</option>
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

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_purchase_date') </label>
                                        <input type="date" class="form-control" name="truck_purchase_date"
                                               id="truck_purchase_date"
                                               placeholder="@lang('trucks.truck_purchase_date')">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_purchase_value') </label>
                                        <input type="number" class="form-control" name="truck_purchase_value" value='0'
                                               id="truck_purchase_value"
                                               placeholder="@lang('trucks.truck_purchase_value')">

                                    </div>

                                    <div class="col-md-2">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_depreciation_ratio') </label>
                                        <input type="number" class="form-control" name="truck_depreciation_ratio"
                                               id="truck_depreciation_ratio"
                                               placeholder="@lang('trucks.truck_depreciation_ratio')">

                                    </div>

                                    <div class="col-md-1">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_depreciation_years') </label>
                                        <input type="number" class="form-control" name="truck_depreciation_years"
                                               id="truck_depreciation_years"
                                               placeholder="@lang('trucks.truck_depreciation_years')">

                                    </div>

                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_seller') </label>
                                        <select class="form-select form-control" name="truck_seller"
                                                id="truck_seller">
                                            <option value="" selected>choose</option>
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

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_sales_date') </label>
                                        <input type="date" class="form-control" name="truck_sales_date"
                                               id="truck_sales_date" placeholder="@lang('trucks.truck_sales_date')">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_sales_value') </label>
                                        <input type="number" class="form-control" name="truck_sales_value" value='0'
                                               id="truck_sales_value" placeholder="@lang('trucks.truck_sales_value')">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.trucker_status') </label>
                                        <select class="form-select form-control" name="trucker_status"
                                                id="trucker_status">
                                            <option value="" selected></option>
                                            @foreach($sys_codes_tracker_status as $sys_code_tracker_status)
                                                <option value="{{$sys_code_tracker_status->system_code_id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$sys_code_tracker_status->system_code_name_ar}}
                                                    @else
                                                        {{$sys_code_tracker_status->system_code_name_en}}
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
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_driver') </label>
                                        <select class="form-select form-control" name="truck_driver_id"
                                                id="truck_driver_id">
                                            <option value="" selected>choose</option>
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->emp_id }}">

                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $employee->emp_name_full_ar }}
                                                    @else
                                                        {{ $employee->emp_name_full_en }}
                                                    @endif

                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_driver_eceived') </label>
                                        <input type="date" class="form-control" name="truck_driver_eceived"
                                               id="truck_driver_eceived"
                                               placeholder="@lang('trucks.truck_driver_eceived')">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('trucks.truck_driver_delivery') </label>
                                        <input type="date" class="form-control" name="truck_driver_delivery"
                                               id="truck_driver_delivery"
                                               placeholder="@lang('trucks.truck_driver_delivery')">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('trucks.truck_load_weight') </label>
                                        <input type="number" class="form-control" name="truck_load_weight"
                                               id="truck_load_weight" placeholder="@lang('trucks.truck_load_weight')">

                                    </div>


                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="submit">@lang('trucks.save')</button>
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
                rightLetter: '',
                middleLetter: '',
                leftLetter: '',
                plate_number: '',

                full_ar: true,
                full_en: true,


            },
            computed: {
                truck_plate_no: function () {
                    return this.rightLetter + ' ' + this.middleLetter + ' ' + this.leftLetter + ' ' + this.plate_number
                }
            }


        })

    </script>
@endsection
