@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
@endsection


@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">

                    {{-- Form To Create Customer--}}
                    <form class="card" id="validate-form"
                          action="{{ route('CarRentModel.store') }}" method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf

                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('carrent.add_car_model')
                            </div>

                            @include('Includes.form-errors')
                            <div class="row">
                                <div class="col-md-9">

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_rent_model_code') </label>
                                                <input type="text" class="form-control"
                                                       name="car_rent_model_code" value="{{$string_number}}"
                                                       placeholder="@lang('carrent.car_rent_model_code')" readonly>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_purchase_date') </label>
                                                <input type="date" class="form-control"
                                                       name="car_purchase_date"
                                                       id="car_purchase_date" required
                                                       placeholder="@lang('carrent.car_purchase_date')"
                                                       value="{{old('car_purchase_date')}}">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_qty') </label>
                                                <input type="number" class="form-control" name="car_qty"
                                                       id="car_qty" placeholder="@lang('carrent.car_qty')" required
                                                       value="{{old('car_qty')}}">

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_status') </label>
                                                <select class="form-select form-control"
                                                        name="car_rent_model_status"
                                                        id="car_rent_model_status" required
                                                        value="{{old('car_rent_model_status')}}">
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


                                        <div class="mb-3">
                                            <div class="row">


                                                <div class="col-md-3">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_brand') </label>
                                                    <select class="form-select form-control"
                                                            name="car_brand_id" v-model="car_brand_id"
                                                            @change="getBrandDetails()"
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
                                                    <select class="form-select form-control"
                                                            name="car_brand_dt_id"
                                                            aria-label="Default select example" id="car_brand_dt_id"
                                                            required value="{{old('car_brand_dt_id')}}">

                                                        <option :value="brand_dt.brand_dt_id"
                                                                v-for="brand_dt in brand_dts">
                                                            @if(app()->getLocale() == 'ar')
                                                                @{{brand_dt.brand_dt_name_ar}}
                                                            @else
                                                                @{{brand_dt.brand_dt_name_en}}
                                                            @endif
                                                        </option>
                                                    </select>
                                                </div>


                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_model_year') </label>
                                                    <input type="number" class="form-control"
                                                           name="car_model_year"
                                                           id="car_model_year" required
                                                           placeholder="@lang('carrent.car_model_year')"
                                                           value="{{old('car_model_year')}}">

                                                </div>


                                                <div class="col-md-3">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_category') </label>
                                                    <select class="form-select form-control"
                                                            name="car_category_id"
                                                            aria-label="Default select example" id="car_category_id"
                                                            required value="{{old('car_category_id')}}">
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
                                    </div>

                                    <div class="mb-3">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_ownership_status') </label>
                                                <select class="form-select form-control" name="Property_type"
                                                        aria-label="Default select example" id="Property_type" required
                                                        value="{{old('Property_type')}}">
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
                                                <input type="number" class="form-control"
                                                       name="car_purchase_cost"
                                                       id="car_purchase_cost"
                                                       placeholder="@lang('carrent.car_purchase_value')"
                                                       value="{{old('car_purchase_cost')}}">

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_ownership') </label>
                                                <input type="text" class="form-control" name="owner_name"
                                                       id="owner_name" placeholder="@lang('carrent.car_ownership')"
                                                       required value="{{old('owner_name')}}">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.sub_company') </label>
                                                <select class="form-select form-control"
                                                        name="company_id" required value="{{old('company_id')}}">
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
                                                       name="car_photo_url" value="{{ old('car_photo_url') }}" required>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>


                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.gear_box_type_id') </label>
                                        <select class="form-select form-control" name="gear_box_type_id"
                                                aria-label="Default select example" id="gear_box_type_id" required
                                                value="{{old('gear_box_type_id')}}">
                                            <option value="" selected></option>
                                            @foreach($sys_codes_status_68 as $sys_code_status_68)
                                                <option value="{{$sys_code_status_68->system_code_id}}">
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
                                        <select class="form-select form-control" name="engine_type"
                                                aria-label="Default select example" id="engine_type" required
                                                value="{{old('engine_type')}}">
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
                                        <select class="form-select form-control" name="fuel_type_id"
                                                aria-label="Default select example" id="fuel_type_id" required
                                                value="{{old('fuel_type_id')}}">
                                            <option value="" selected></option>
                                            @foreach($sys_codes_status_70 as $sys_code_status_70)
                                                <option value="{{$sys_code_status_70->system_code_id}}">
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
                                        <input type="text" class="form-control" name="car_color"
                                               id="car_color" placeholder="@lang('carrent.car_color')"
                                               value="{{old('car_color')}}">

                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.oil_type') </label>
                                    <select class="form-select form-control" name="oil_type"
                                            aria-label="Default select example" id="oil_type" required
                                            value="{{old('oil_type')}}">
                                        <option value="" selected></option>
                                        @foreach($sys_codes_status_71 as $sys_code_status_71)
                                            <option value="{{$sys_code_status_71->system_code_id}}">
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
                                    <input type="number" class="form-control" name="oil_change_km"
                                           id="oil_change_km" placeholder="@lang('carrent.oil_change_km')"
                                           value="{{old('oil_change_km')}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_doors') </label>
                                    <input type="number" class="form-control" name="car_doors"
                                           id="car_doors" placeholder="@lang('carrent.car_doors')"
                                           value="{{old('car_doors')}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_passengers') </label>
                                    <input type="number" class="form-control" name="car_passengers"
                                           id="car_passengers" placeholder="@lang('carrent.car_passengers')"
                                           value="{{old('car_passengers')}}">

                                </div>

                            </div>


                            <div class="row">

                                <div class="card bline" style="color:red">
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.insurance_type') </label>
                                    <select class="form-select form-control" name="insurance_type"
                                            aria-label="Default select example" id="insurance_type">
                                        <option value=""> @lang('home.choose')</option>
                                        @foreach($sys_codes_insurance_types as $codes_insurance_type)
                                            <option value="{{$codes_insurance_type->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $codes_insurance_type->system_code_name_ar
                                                : $codes_insurance_type->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.insurance_document_no') </label>
                                    <input type="number" class="form-control " name="insurance_document_no"
                                           id="insurance_document_no">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.insurance_value') </label>
                                    <input type="number" class="form-control " name="insurance_value">

                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.insurance_date_end') </label>
                                    <input type="date" class="form-control " name="insurance_date_end"
                                           id="insurance_date_end" placeholder="@lang('carrent.insurance_date_end')">
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.car_trucker_status') </label>
                                    <select class="form-select form-control" name="car_trucker_status"
                                            aria-label="Default select example" id="car_trucker_status" required>

                                        @foreach($sys_codes_tracker_status as $sys_codes_tracker_status)
                                            <option value="{{$sys_codes_tracker_status->system_code_id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$sys_codes_tracker_status->system_code_name_ar}}
                                                @else
                                                    {{$sys_codes_tracker_status->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.tracker_serial') </label>
                                    <input type="number" class="form-control " name="tracker_serial"
                                           id="tracker_serial">

                                </div>


                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.car_kilometar') </label>
                                    <input type="number" class="form-control " name="allowedKmPerHour"
                                           value="{{old('allowedKmPerHour')}}">
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.oil_change_km_distance') </label>
                                    <input type="number" class="form-control " name="oil_change_km"
                                           value="{{old('oil_change_km')}}">
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.the_amount_of_fuel_present') </label>
                                    <input type="text" class="form-control " name="availableFuel"
                                           value="{{old('availableFuel')}}">
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.meter_reading_before') </label>
                                    <input type="number" class="form-control " name="odometer_start"
                                           value="{{old('odometer_start')}}">
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.oil_change_time') </label>
                                    <input type="date" class="form-control " name="last_oil_change_date"
                                           value="{{old('last_oil_change_date')}}">
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.availability_of_the_reflective_triangle') </label>
                                    <select class="form-control " name="car_Safety_Triangle"
                                            value="{{old('car_Safety_Triangle')}}">
                                        <option value=""> @lang('home.choose')</option>
                                        @foreach($sys_codes_Safety_Triangles as $sys_codes_Safety_Triangle)
                                            <option value="{{$sys_codes_Safety_Triangle->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Safety_Triangle->system_code_name_ar
                                                : $sys_codes_Safety_Triangle->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.conditioning_status')</label>
                                    <select class="form-control " name="car_ac_status" value="{{old('car_ac_status')}}">
                                        <option value=""> @lang('home.choose')</option>
                                        @foreach($sys_codes_ac_statuses as $sys_codes_ac_status)
                                            <option value="{{$sys_codes_ac_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_ac_status->system_code_name_ar
                                                : $sys_codes_ac_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.radio_status')</label>
                                    <select class="form-control " name="car_Radio_Stereo_status"
                                            value="{{old('car_Radio_Stereo_status')}}">
                                        <option value=""> @lang('home.choose')</option>
                                        @foreach($sys_codes_Radio_statuses as $sys_codes_Radio_status)
                                            <option value="{{$sys_codes_Radio_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Radio_status->system_code_name_ar
                                                : $sys_codes_Radio_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.availability_of_a_fire_extinguisher')</label>
                                    <select class="form-control " name="car_Fire_extinguisher"
                                            value="{{old('car_Fire_extinguisher')}}">
                                        <option value=""> @lang('home.choose')</option>
                                        @foreach($sys_codes_Fire_extinguishers as $sys_codes_Fire_extinguisher)
                                            <option value="{{$sys_codes_Fire_extinguisher->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Fire_extinguisher->system_code_name_ar
                                                : $sys_codes_Fire_extinguisher->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.inside_screen_status')</label>
                                    <select class="form-control " name="car_Screen_status"
                                            value="{{old('car_Screen_status')}}">
                                        <option value=""> @lang('home.choose')</option>
                                        @foreach($sys_codes_Screen_statuses as $sys_codes_Screen_status)
                                            <option value="{{$sys_codes_Screen_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Screen_status->system_code_name_ar
                                                : $sys_codes_Screen_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.speedometer_status')</label>
                                    <select class="form-control " name="car_Speedometer_status"
                                            value="{{old('car_Speedometer_status')}}">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Speedometer_statuses as $sys_codes_Speedometer_status)
                                            <option value="{{$sys_codes_Speedometer_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Speedometer_status->system_code_name_ar
                                                : $sys_codes_Speedometer_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.interior_upholstery_case')</label>
                                    <select class="form-control " name="car_Seats_status"
                                            value="{{old('car_Seats_status')}}">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Seats_statuses as $sys_codes_Seats_status)
                                            <option value="{{$sys_codes_Seats_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Seats_status->system_code_name_ar
                                                : $sys_codes_Seats_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.spare_tire_equipment')</label>
                                    <select class="form-control " name="car_Spare_Tire_tools"
                                            value="{{old('car_Spare_Tire_tools')}}">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Spare_Tire_tools as $sys_codes_Spare_Tire_tool)
                                            <option value="{{$sys_codes_Spare_Tire_tool->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Spare_Tire_tool->system_code_name_ar
                                                : $sys_codes_Spare_Tire_tool->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.wheel_condition')</label>
                                    <select class="form-control " name="car_Tires_status"
                                            value="{{old('car_Tires_status')}}">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Tires_statuses as $sys_codes_Tires_status)
                                            <option value="{{$sys_codes_Tires_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Tires_status->system_code_name_ar
                                                : $sys_codes_Tires_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.spare_wheel_condition')</label>
                                    <select class="form-control " name="car_Spare_Tire_status"
                                            value="{{old('car_Spare_Tire_status')}}">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Spare_Tire_statuses as $sys_codes_Spare_Tire_status)
                                            <option value="{{$sys_codes_Spare_Tire_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_Spare_Tire_status->system_code_name_ar
                                                : $sys_codes_Spare_Tire_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.first_aid_bag_case')</label>
                                    <select class="form-control " name="car_First_Aid_Kit"
                                            value="{{old('car_First_Aid_Kit')}}">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_First_Aid_Kits as $sys_codes_First_Aid_Kit)
                                            <option value="{{$sys_codes_First_Aid_Kit->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_First_Aid_Kit->system_code_name_ar
                                                : $sys_codes_First_Aid_Kit->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.key_case')</label>
                                    <select class="form-control " name="car_keys_status"
                                            value="{{old('car_keys_status')}}">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_car_keys_statuses as $sys_codes_car_keys_status)
                                            <option value="{{$sys_codes_car_keys_status->system_code_id}}">
                                                {{app()->getLocale() == 'ar'
                                                ? $sys_codes_car_keys_status->system_code_name_ar
                                                : $sys_codes_car_keys_status->system_code_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2"
                                        data-bs-dismiss="modal" id="submit">@lang('carrent.save')</button>
                                <div class="spinner-border" role="status" style="display: none">
                                    <span class="sr-only">Loading...</span>


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
    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
    <script>
        $(document).ready(function () {

            //     //    validation to create trucks

            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

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
                car_brand_id: '',
                brand_dts: {}
            },
            methods: {
                getBrandDetails() {
                    $.ajax({
                        type: 'GET',
                        data: {car_brand_id: this.car_brand_id},
                        url: '{{ route("api.get-brand-details") }}'
                    }).then(response => {
                        this.brand_dts = response.data
                    })
                },
            }


        })

    </script>
@endsection
