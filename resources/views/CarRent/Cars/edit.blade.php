@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">

@endsection
@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active " id="data-grid" role="tabpanel">


                    <form class="card" id="validate-form" action="{{ route('CarRentCars.update',$car->car_id) }}"
                          method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        @method('put')

                        <div style="font-size: 16px ;font-weight: bold" class="card-body">
                            {{--inputs and photo--}}
                            <div  class="font-25">
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
                                                    <div  class="col-md-2">
                                                        <select class="form-control plate_ar_1" name="plate_ar_1" style="font-size: 16px ;font-weight: bold ; color: red"
                                                                onchange="getLetter($(this))">
                                                            @foreach(config('global.car_letters_ar') as $letter )
                                                                <option value="{{$letter}}"
                                                                        @if($car->plate_ar_1 == $letter) selected @endif>{{$letter}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">

                                                        <select class="form-control plate_ar_2" name="plate_ar_2" style="font-size: 16px ;font-weight: bold ; color: red"
                                                                onchange="getLetter($(this))">
                                                            @foreach(config('global.car_letters_ar') as $letter )
                                                                <option value="{{$letter}}"
                                                                        @if($car->plate_ar_2 == $letter) selected @endif>{{$letter}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <select class="form-control plate_ar_3" name="plate_ar_3" style="font-size: 16px ;font-weight: bold ; color: red"
                                                                onchange="getLetter($(this))">
                                                            @foreach(config('global.car_letters_ar') as $letter )
                                                                <option value="{{$letter}}"
                                                                        @if($car->plate_ar_3 == $letter) selected @endif>{{$letter}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="number" class="form-control car_plate_number1" style="font-size: 18px ;font-weight: bold ; color: red"
                                                               name="car_plate_number"
                                                               value="{{$car->car_plate_number}}"
                                                               onkeyup="carNumber($(this))">
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="col-md-6">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_plate_en') </label>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <select class="form-control plate_en_1" name="plate_en_1" style="font-size: 16px ;font-weight: bold ; color: red"
                                                                onchange="getLetterAr($(this))">
                                                            @foreach(config('global.car_letters_en') as $letter )
                                                                <option value="{{$letter}}"
                                                                        @if($car->plate_en_1 == $letter) selected @endif>{{$letter}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <select class="form-control plate_en_2" name="plate_en_2" style="font-size: 16px ;font-weight: bold ; color: red"
                                                                onchange="getLetterAr($(this))">
                                                            @foreach(config('global.car_letters_en') as $letter )
                                                                <option value="{{$letter}}"
                                                                        @if($car->plate_en_2 == $letter) selected @endif>{{$letter}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <select class="form-control plate_en_3" name="plate_en_3" style="font-size: 16px ;font-weight: bold ; color: red"
                                                                onchange="getLetterAr($(this))">
                                                            @foreach(config('global.car_letters_en') as $letter )
                                                                <option value="{{$letter}}"
                                                                        @if($car->plate_en_3 == $letter) selected @endif>{{$letter}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="number" class="form-control car_plate_number2" style="font-size: 18px ;font-weight: bold ; color: red"
                                                               value="{{$car->car_plate_number}}"
                                                               onkeyup="carNumber($(this))">
                                                    </div>
                                                </div>


                                            </div>


                                        </div>


                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_chasi') </label>
                                                    <input type="number" class="form-control"
                                                           name="car_chase"
                                                           id="car_chase" value="{{$car->car_chase}}">

                                                </div>

                                                <div class="col-md-4">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_motor_no') </label>
                                                    <input type="text" class="form-control"
                                                           name="car_motor_no"
                                                           id="car_motor_no" value="{{$car->car_motor_no}}"
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
                                            <div class="row">{{----}}
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_registration_type') </label>
                                                    <select class="form-select form-control "
                                                            name="car_registration_type"
                                                            id="car_registration_type">

                                                        <option value="" > @lang('home.choose')</option>
                                                        @foreach($sys_codes_registration_types as $codes_registration_type)
                                                            <option value="{{$codes_registration_type->system_code_id}}"
                                                            @if($car->car_registration_type == $codes_registration_type->system_code_id)
                                                            selected @endif>
                                                                {{app()->getLocale() == 'ar'
                                                                ? $codes_registration_type->system_code_name_ar
                                                                : $codes_registration_type->system_code_name_en }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_operation_card_no') </label>
                                                    <input type="number" class="form-control "
                                                           name="car_operation_card_no"
                                                           id="car_operation_card_no"
                                                           value="{{$car->car_operation_card_no}}">

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_operation_card_date') </label>
                                                    <input type="date" class="form-control"
                                                           name="car_operation_card_date"
                                                           id="car_operation_card_date"
                                                           value="{{$car->car_operation_card_date}}">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('carrent.car_status_id') </label>
                                                    <select class="form-select form-control" style="font-size: 16px ;font-weight: bold ; color: blue"
                                                            name="car_status_id"
                                                            id="car_status_id">
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($sys_codes_type as $sys_code_type)
                                                            <option value="{{$sys_code_type->system_code_id}}"
                                                                    @if($car->car_status_id) @if($car->car_status_id == $sys_code_type->system_code_id)
                                                                    selected @endif @endif>
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
                                            {{-- not found in model --}}

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_ownership_status') </label>
                                                <select class="form-select form-control"
                                                        name="Property_type"
                                                        aria-label="Default select example" id="car_ownership_status"
                                                        required>
                                                    @foreach($sys_codes_ownership_status as $sys_code_ownership_status)
                                                        <option value="{{$sys_code_ownership_status->system_code_id}}"
                                                                @if($sys_code_ownership_status->system_code_id == $car->car_ownership_status)
                                                                selected
                                                                @endif
                                                        >
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
                                                       value="{{$car->car_purchase_cost}}">

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_ownership') </label>
                                                <input type="text" class="form-control" name="owner_name"
                                                       id="car_ownership" value="{{$car->owner_name}}"
                                                       required>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.sub_company') </label>

                                                <input type="text" class="form-control"
                                                       value="{{app()->getLocale()=='ar'
                                                       ? $car->company->company_name_ar
                                                       : $car->company->company_name_en }}" disabled="">
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
                                                <img src="{{ $car->car_photo_url }}" width="200" height="200">
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

                                        {{--readonly--}}
                                        <input type="text" disabled value="{{ app()->getLocale()=='ar' ?
                                         $car->brand->brand_name_ar : $car->brand->brand_name_en}}"
                                               class="form-control">
                                        {{--<select class="form-select form-control" name="car_brand_id"--}}
                                        {{--aria-label="Default select example" id="car_brand_id">--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--@foreach($car_rent_brands as $car_rent_brand)--}}
                                        {{--<option value="{{$car_rent_brand->brand_id}}"--}}
                                        {{--@if($car->car_brand_id == $car_rent_brand->brand_id) selected @endif>--}}
                                        {{--@if(app()->getLocale() == 'ar')--}}
                                        {{--{{$car_rent_brand->brand_name_ar}}--}}
                                        {{--@else--}}
                                        {{--{{$car_rent_brand->brand_name_en}}--}}
                                        {{--@endif--}}
                                        {{--</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_model') </label>

                                        <input type="text" disabled value="{{ app()->getLocale()=='ar' ?
                                         $car->brandDetails->brand_dt_name_ar : $car->brandDetails->brand_dt_name_en}}"
                                               class="form-control">
                                        {{--<select class="form-select form-control" name="car_brand_dt_id"--}}
                                        {{--aria-label="Default select example" id="car_brand_dt_id" required>--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--@foreach($car_rent_brands_dt as $car_rent_brand_dt)--}}
                                        {{--<option value="{{$car_rent_brand_dt->brand_dt_id}}"--}}
                                        {{--@if($car->car_brand_dt_id == $car_rent_brand_dt->brand_dt_id)--}}
                                        {{--selected @endif>--}}
                                        {{--@if(app()->getLocale() == 'ar')--}}
                                        {{--{{$car_rent_brand_dt->brand_dt_name_ar}}--}}
                                        {{--@else--}}
                                        {{--{{$car_rent_brand_dt->brand_dt_name_en}}--}}
                                        {{--@endif--}}
                                        {{--</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('carrent.car_model_year') </label>
                                        <input type="text" class="form-control" name="car_model_year"
                                               id="car_model_year" value="{{$car->car_model_year}}" disabled="">

                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.car_category') </label>
                                        <input type="text" class="form-control" name="car_category_id"
                                               id="car_category_id" value="{{app()->getLocale()=='ar' ? $car->category->system_code_name_ar :
                                               $car->category->system_code_name_en}}" disabled="">

                                        {{--<select class="form-select form-control is-invalid" name="car_category_id"--}}
                                        {{--aria-label="Default select example" id="car_category_id" required>--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--@foreach($sys_codes_status as $sys_code_status)--}}
                                        {{--<option value="{{$sys_code_status->system_code_id}}"--}}
                                        {{--@if($car->car_category_id == $sys_code_status->system_code_id)--}}
                                        {{--selected @endif>--}}
                                        {{--@if(app()->getLocale() == 'ar')--}}
                                        {{--{{$sys_code_status->system_code_name_ar}}--}}
                                        {{--@else--}}
                                        {{--{{$sys_code_status->system_code_name_en}}--}}
                                        {{--@endif--}}
                                        {{--</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.gear_box_type_id') </label>

                                        <input type="text" class="form-control" name="gear_box_type_id"
                                               id="gear_box_type_id" value="{{app()->getLocale()=='ar' ? $car->boxType->system_code_name_ar :
                                               $car->boxType->system_code_name_en}}" disabled="">

                                        {{--<select class="form-select form-control is-invalid" name="gear_box_type_id"--}}
                                        {{--aria-label="Default select example" id="gear_box_type_id" required>--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--@foreach($sys_codes_status_68 as $sys_code_status_68)--}}
                                        {{--<option value="{{$sys_code_status_68->system_code}}"--}}
                                        {{--@if($car->gear_box_type_id == $sys_code_status_68->system_code_id)--}}
                                        {{--selected @endif>--}}
                                        {{--@if(app()->getLocale() == 'ar')--}}
                                        {{--{{$sys_code_status_68->system_code_name_ar}}--}}
                                        {{--@else--}}
                                        {{--{{$sys_code_status_68->system_code_name_en}}--}}
                                        {{--@endif--}}
                                        {{--</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.engine_type') </label>

                                        <input type="text" class="form-control" name="engine_type"
                                               id="engine_type" value="{{app()->getLocale()=='ar' ? $car->engineType->system_code_name_ar :
                                               $car->engineType->system_code_name_en}}" disabled="">

                                        {{--<select class="form-select form-control is-invalid" name="engine_type"--}}
                                        {{--aria-label="Default select example" id="engine_type" required>--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--@foreach($sys_codes_status_69 as $sys_code_status_69)--}}
                                        {{--<option value="{{$sys_code_status_69->system_code_id}}"--}}
                                        {{--@if($car->engine_type == $sys_code_status_69->system_code_id)--}}
                                        {{--selected @endif>--}}
                                        {{--@if(app()->getLocale() == 'ar')--}}
                                        {{--{{$sys_code_status_69->system_code_name_ar}}--}}
                                        {{--@else--}}
                                        {{--{{$sys_code_status_69->system_code_name_en}}--}}
                                        {{--@endif--}}
                                        {{--</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                    </div>

                                    <div class="col-md-3">
                                        <label for="recipient"
                                               class="col-form-label"> @lang('carrent.fuel_type_id') </label>

                                        <input type="text" class="form-control" name="fuel_type_id"
                                               id="fuel_type_id" value="{{app()->getLocale()=='ar' ? $car->fuelType->system_code_name_ar :
                                               $car->fuelType->system_code_name_en}}" disabled="">

                                        {{--<select class="form-select form-control is-invalid" name="fuel_type_id"--}}
                                        {{--aria-label="Default select example" id="fuel_type_id" required>--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--@foreach($sys_codes_status_70 as $sys_code_status_70)--}}
                                        {{--<option value="{{$sys_code_status_70->system_code}}"--}}
                                        {{--@if($car->fuel_type_id == $sys_code_status_70->system_code_id) selected @endif>--}}
                                        {{--@if(app()->getLocale() == 'ar')--}}
                                        {{--{{$sys_code_status_70->system_code_name_ar}}--}}
                                        {{--@else--}}
                                        {{--{{$sys_code_status_70->system_code_name_en}}--}}
                                        {{--@endif--}}
                                        {{--</option>--}}
                                        {{--@endforeach--}}
                                        {{--</select>--}}
                                    </div>


                                    <div class="col-md-3">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('carrent.car_color') </label>
                                        <input type="text" class="form-control" name="car_color"
                                               id="car_color" value="{{$car->car_color}}" >

                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.oil_type') </label>

                                    <input type="text" class="form-control" name="oil_type"
                                           id="oil_type" value="{{app()->getLocale()=='ar' ? $car->oilType->system_code_name_ar :
                                               $car->oilType->system_code_name_en}}" disabled="">

                                    {{--<select class="form-select form-control is-invalid" name="oil_type"--}}
                                    {{--aria-label="Default select example" id="oil_type" required>--}}
                                    {{--<option value="" selected></option>--}}
                                    {{--@foreach($sys_codes_status_71 as $sys_code_status_71)--}}
                                    {{--<option value="{{$sys_code_status_71->system_code}}"--}}
                                    {{--@if($car->oil_type == $sys_code_status_71->system_code_id) selected @endif>--}}
                                    {{--@if(app()->getLocale() == 'ar')--}}
                                    {{--{{$sys_code_status_71->system_code_name_ar}}--}}
                                    {{--@else--}}
                                    {{--{{$sys_code_status_71->system_code_name_en}}--}}
                                    {{--@endif--}}
                                    {{--</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.oil_change_km') </label>
                                    <input type="number" class="form-control " name="oil_change_km"
                                           id="oil_change_km" value="{{$car->oil_change_km}}" readonly>

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_doors') </label>
                                    <input type="number" class="form-control " name="car_doors"
                                           id="car_doors" value="{{$car->car_doors}}" readonly="">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_passengers') </label>
                                    <input type="number" class="form-control " name="car_passengers"
                                           id="car_passengers" value="{{$car->car_passengers}}" readonly>

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
                                            <option value="{{$codes_insurance_type->system_code_id}}"
                                                    @if($car->insurance_type == $codes_insurance_type->system_code_id)
                                                    selected @endif>
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
                                           id="insurance_document_no"
                                           value="{{$car->insurance_document_no}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.insurance_value') </label>
                                    <input type="number" class="form-control " name="insurance_value" style="font-size: 16px ;font-weight: bold ; color: red"
                                           id="insurance_value" value="{{$car->insurance_value}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.insurance_date_end') </label>
                                    <input type="date" class="form-control " name="insurance_date_end" style="font-size: 16px ;font-weight: bold ; color: red"
                                           id="insurance_date_end" placeholder="@lang('carrent.insurance_date_end')"
                                           value="{{$car->insurance_date_end ? $car->insurance_date_end : ''}}">
                                </div>


                            </div>


                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.car_trucker_status') </label>
                                    <select class="form-select form-control" name="car_trucker_status"
                                            aria-label="Default select example" id="car_trucker_status" required>

                                        @foreach($sys_codes_tracker_status as $sys_codes_tracker_status)
                                            <option value="{{$sys_codes_tracker_status->system_code_id}}"
                                                    @if($car->car_trucker_status)
                                                    @if($car->car_trucker_status ==
                                                        $sys_codes_tracker_status->system_code_id) selected
                                                    @endif
                                                    @endif>
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
                                           id="tracker_serial" value="{{$car->tracker_serial}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.tracker_supplier') </label>
                                    <input type="text" class="form-control " name="tracker_supplier"
                                           id="tracker_supplier" value="{{$car->tracker_supplier}}">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.tracker_install_date') </label>
                                    <input type="date" class="form-control " name="tracker_install_date"
                                           id="tracker_install_date"
                                           value="{{ $car->tracker_install_date }}">
                                </div>


                            </div>


                            <div class="row">

                                <div class="card bline" style="color:red">
                                </div>


                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.car_kilometar') </label>
                                    <input type="number" class="form-control " name="allowedKmPerHour" style="font-size: 16px ;font-weight: bold ; color: blue"
                                           value="{{$car->allowedKmPerHour ? $car->allowedKmPerHour : ''}}">
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.oil_change_km_distance') </label>
                                    <input type="number" class="form-control " name="oil_change_km"
                                           value="{{$car->oil_change_km ? $car->oil_change_km : ''}}">
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.the_amount_of_fuel_present') </label>
                                    <input type="text" class="form-control " name="availableFuel"
                                           value="{{$car->availableFuel ? $car->availableFuel : ''}}">
                                </div>

                                <div hidden class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.meter_reading_before') </label>
                                    <input type="number" class="form-control " name="odometer_start"
                                           value="{{$car->odometer_start ? $car->odometer_start : ''}}">
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.oil_change_time') </label>
                                    <input type="date" class="form-control " name="last_oil_change_date"
                                           value="{{$car->last_oil_change_date ? $car->last_oil_change_date : ''}}">
                                </div>


                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label">@lang('home.availability_of_the_reflective_triangle') </label>
                                    <select class="form-control " name="car_Safety_Triangle">
                                        <option value="" > @lang('home.choose')</option>
                                        @foreach($sys_codes_Safety_Triangles as $sys_codes_Safety_Triangle)
                                            <option value="{{$sys_codes_Safety_Triangle->system_code_id}}"
                                                    @if($car->car_Safety_Triangle == $sys_codes_Safety_Triangle->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_ac_status">
                                        <option value="" > @lang('home.choose')</option>
                                        @foreach($sys_codes_ac_statuses as $sys_codes_ac_status)
                                            <option value="{{$sys_codes_ac_status->system_code_id}}"
                                                    @if($car->car_ac_status == $sys_codes_ac_status->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Radio_Stereo_status">
                                        <option value="" > @lang('home.choose')</option>
                                        @foreach($sys_codes_Radio_statuses as $sys_codes_Radio_status)
                                            <option value="{{$sys_codes_Radio_status->system_code_id}}"
                                                    @if($car->car_Radio_Stereo_status == $sys_codes_Radio_status->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Fire_extinguisher">
                                        <option value="" > @lang('home.choose')</option>
                                        @foreach($sys_codes_Fire_extinguishers as $sys_codes_Fire_extinguisher)
                                            <option value="{{$sys_codes_Fire_extinguisher->system_code_id}}"
                                                    @if($car->car_Fire_extinguisher == $sys_codes_Fire_extinguisher->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Screen_status">
                                        <option value="" > @lang('home.choose')</option>
                                        @foreach($sys_codes_Screen_statuses as $sys_codes_Screen_status)
                                            <option value="{{$sys_codes_Screen_status->system_code_id}}"
                                                    @if($car->car_Screen_status == $sys_codes_Screen_status->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Speedometer_status">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Speedometer_statuses as $sys_codes_Speedometer_status)
                                            <option value="{{$sys_codes_Speedometer_status->system_code_id}}"
                                                    @if($car->car_Speedometer_status == $sys_codes_Speedometer_status->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Seats_status">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Seats_statuses as $sys_codes_Seats_status)
                                            <option value="{{$sys_codes_Seats_status->system_code_id}}"
                                                    @if($car->car_Seats_status == $sys_codes_Seats_status->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Spare_Tire_tools">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Spare_Tire_tools as $sys_codes_Spare_Tire_tool)
                                            <option value="{{$sys_codes_Spare_Tire_tool->system_code_id}}"
                                                    @if($car->car_Spare_Tire_tools == $sys_codes_Spare_Tire_tool->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Tires_status">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Tires_statuses as $sys_codes_Tires_status)
                                            <option value="{{$sys_codes_Tires_status->system_code_id}}"
                                                    @if($car->car_Tires_status == $sys_codes_Tires_status->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_Spare_Tire_status">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_Spare_Tire_statuses as $sys_codes_Spare_Tire_status)
                                            <option value="{{$sys_codes_Spare_Tire_status->system_code_id}}"
                                                    @if($car->car_Spare_Tire_status == $sys_codes_Spare_Tire_status->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_First_Aid_Kit">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_First_Aid_Kits as $sys_codes_First_Aid_Kit)
                                            <option value="{{$sys_codes_First_Aid_Kit->system_code_id}}"
                                                    @if($car->car_First_Aid_Kit == $sys_codes_First_Aid_Kit->system_code_id)
                                                    selected @endif>
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
                                    <select class="form-control " name="car_keys_status">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($sys_codes_car_keys_statuses as $sys_codes_car_keys_status)
                                            <option value="{{$sys_codes_car_keys_status->system_code_id}}"
                                                    @if($car->car_keys_status == $sys_codes_car_keys_status->system_code_id)
                                                    selected @endif>
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
    <script>
        $(document).ready(function () {

            //     //    validation to create trucks

            $('form').submit(function () {

                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

        })

        function carNumber(el) {
            if (el.hasClass('car_plate_number1')) {
                el.parent().parent().parent().next().children().children().children().eq(3).val(el.val())
            }

            if (el.hasClass('car_plate_number2')) {
                el.parent().parent().parent().prev().children().children().children().eq(3).val(el.val())
            }
        }

        function getLetter(el) {

            car_letters_ar = [
                'أ', 'ب', 'ح',
                'د', 'ر', 'س',
                'ص', 'ط', 'ع',
                'ق', 'ك', 'ل',
                'م', 'ن', 'ه',
                'و', 'ي'
            ];
            car_letters_en = [
                'a', 'b', 'h',
                'd', 'r', 's',
                's', 't', 'o',
                'k', 'k', 'l',
                'm', 'n', 'h',
                'w', 'y'
            ]

            //var index = car_letters_ar.findIndex(el.val())
            var index = car_letters_ar.indexOf(el.val());

            if (el.hasClass('plate_ar_1')) {
                el.parent().parent().parent().next().children().children().children().first().val(car_letters_en[index])
            }

            if (el.hasClass('plate_ar_2')) {
                el.parent().parent().parent().next().children().children().children().eq(1).val(car_letters_en[index])
            }


            if (el.hasClass('plate_ar_3')) {
                el.parent().parent().parent().next().children().children().children().eq(2).val(car_letters_en[index])
            }


        }

        function getLetterAr(el) {

            car_letters_ar = [
                'أ', 'ب', 'ح',
                'د', 'ر', 'س',
                'ص', 'ط', 'ع',
                'ق', 'ك', 'ل',
                'م', 'ن', 'ه',
                'و', 'ي'
            ];
            car_letters_en = [
                'a', 'b', 'h',
                'd', 'r', 's',
                's', 't', 'o',
                'k', 'k', 'l',
                'm', 'n', 'h',
                'w', 'y'
            ]

            //var index = car_letters_ar.findIndex(el.val())
            var index = car_letters_en.indexOf(el.val());

            if (el.hasClass('plate_en_1')) {
                el.parent().parent().parent().prev().children().children().children().eq(0).val(car_letters_ar[index])
            }

            if (el.hasClass('plate_en_2')) {
                el.parent().parent().parent().prev().children().children().children().eq(1).val(car_letters_ar[index])
            }


            if (el.hasClass('plate_en_3')) {
                el.parent().parent().parent().prev().children().children().children().eq(2).val(car_letters_ar[index])
            }
        }


    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>

        new Vue({
            el: '#app',
            data: {},

        })

    </script>
@endsection
