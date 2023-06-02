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


                    <div class="card-body">
                        {{--inputs and photo--}}
                        <div class="font-25">
                            @lang('carrent.add_cars_details')
                        </div>
                        <div class="row">
                            <div class="col-md-9">

                                <div class="mb-3">
                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.car_rent_model_code') </label>
                                            <input type="text" class="form-control"
                                                   name="car_rent_model_code"
                                                   value="{{ $car_model->car_rent_model_code }}" readonly>
                                        </div>


                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('carrent.car_purchase_date') </label>
                                            <input type="text" class="form-control"
                                                   name="car_purchase_date"
                                                   id="car_purchase_date" required
                                                   value="{{ $car_model->car_purchase_date }}" readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.car_qty') </label>
                                            <input type="number" class="form-control" name="car_qty"
                                                   id="car_qty" value="{{ $car_model->car_qty }}" readonly>

                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.car_status') </label>
                                            <input type="text" class="form-control" name="car_rent_model_status"
                                                   value="{{app()->getLocale()=='ar'
                                                   ? $car_model->status->system_code_name_ar
                                                   : $car_model->status->system_code_name_en}}" readonly>

                                        </div>


                                    </div>


                                    <div class="mb-3">
                                        <div class="row">


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_brand') </label>

                                                <input type="text" class="form-control"
                                                       name="car_brand_id"
                                                       value="{{app()->getLocale()=='ar'
                                                       ? $car_model->brand->brand_name_ar
                                                       : $car_model->brand->brand_name_en}}" readonly>

                                            </div>

                                            {{--<div class="col-md-3">--}}
                                            {{--<label for="recipient"--}}
                                            {{--class="col-form-label"> @lang('carrent.car_model') </label>--}}
                                            {{--<select class="form-select form-control is-invalid"--}}
                                            {{--name="car_model_id"--}}
                                            {{--aria-label="Default select example" id="car_model_id"--}}
                                            {{--required>--}}
                                            {{--<option value="" selected></option>--}}
                                            {{--@foreach($car_rent_brands_dt as $car_rent_brand_dt)--}}
                                            {{--<option value="{{$car_rent_brand_dt->brand_dt_id}}">--}}
                                            {{--@if(app()->getLocale() == 'ar')--}}
                                            {{--{{$car_rent_brand_dt->brand_dt_name_ar}}--}}
                                            {{--@else--}}
                                            {{--{{$car_rent_brand_dt->brand_dt_name_en}}--}}
                                            {{--@endif--}}
                                            {{--</option>--}}
                                            {{--@endforeach--}}
                                            {{--</select>--}}
                                            {{--</div>--}}


                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_model_year') </label>
                                                <input type="number" class="form-control"
                                                       name="car_model_year"
                                                       id="car_model_year" readonly
                                                       value="{{ $car_model->car_model_year }}">

                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_category') </label>

                                                <input type="text" class="form-control"
                                                       name="car_category_id"
                                                       id="car_category_id" readonly
                                                       value="{{app()->getLocale() == 'ar'
                                                       ? $car_model->category->system_code_name_ar
                                                       : $car_model->category->system_code_name_en }}">
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('carrent.car_ownership_status') </label>


                                            <input type="text" class="form-control"
                                                   name="car_purchase_cost"
                                                   id="car_purchase_cost" readonly
                                                   value="{{app()->getLocale()=='ar'
                                                   ? $car_model->PropertyType->system_code_name_ar
                                                   : $car_model->PropertyType->system_code_name_en}}">

                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.car_purchase_value') </label>
                                            <input type="number" class="form-control"
                                                   name="car_purchase_cost"
                                                   id="car_purchase_cost" readonly
                                                   value="{{  $car_model->car_purchase_cost}}">

                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('carrent.car_ownership') </label>
                                            <input type="text" class="form-control" name="owner_name"
                                                   id="owner_name" readonly
                                                   value="{{  $car_model->owner_name}}">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('carrent.sub_company') </label>

                                            <input type="text" class="form-control" name="owner_name"
                                                   id="owner_name" readonly
                                                   value="{{app()->getLocale() == 'ar'
                                                   ? $car_model->company->company_name_ar
                                                   : $car_model->company->company_name_en}}">

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
                                            <img src="{{asset( $car_model->car_photo_url)}}" width="200" height="200">
                                            <input type="file" id="dropify-event"
                                                   name="car_photo_url">
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
                                    {{--نتاكد منها انها داخله في الcreatre--}}
                                    @if($car_model->boxType)
                                        <input type="text" class="form-control"
                                               value="{{app()->getLocale()=='ar'
                                                ? $car_model->boxType->system_code_name_ar
                                                : $car_model->boxType->system_code_name_en }}"
                                               disabled name="gear_box_type_id">
                                    @endif

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.engine_type') </label>

                                    <input type="text" class="form-control"
                                           value="{{app()->getLocale()=='ar'
                                           ? $car_model->engineType->system_code_name_ar
                                           : $car_model->engineType->system_code_name_en }}"
                                           disabled name="engine_type">

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.fuel_type_id') </label>
                                    @if( $car_model->fuelType)
                                        <input type="text" class="form-control"
                                               value="{{app()->getLocale()=='ar'
                                               ? $car_model->fuelType->system_code_name_ar
                                               : $car_model->fuelType->system_code_name_en }}"
                                               disabled name="fuel_type_id">
                                    @endif

                                </div>


                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_color') </label>
                                    <input type="number" class="form-control" name="car_color" disabled
                                           id="car_color" value="{{ $car_model->car_color }}">

                                </div>

                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-3">
                                <label for="recipient"
                                       class="col-form-label"> @lang('carrent.oil_type') </label>

                                @if( $car_model->oilType)
                                    <input type="number" class="form-control"
                                           name="oil_type" disabled id="car_color"
                                           value="{{ app()->getLocale() == 'ar'
                                           ? $car_model->oilType->system_code_name_ar
                                           : $car_model->oilType->system_code_name_en }}">
                                @endif

                            </div>


                            <div class="col-md-3">
                                <label for="recipient-name"
                                       class="col-form-label"> @lang('carrent.oil_change_km') </label>
                                <input type="number" class="form-control" name="oil_change_km"
                                       id="oil_change_km" value="{{ $car_model->oil_change_km }}" disabled>

                            </div>

                            <div class="col-md-3">
                                <label for="recipient-name"
                                       class="col-form-label"> @lang('carrent.car_doors') </label>
                                <input type="number" class="form-control" name="car_doors"
                                       id="car_doors" value="{{ $car_model->car_doors }}" disabled>
                            </div>

                            <div class="col-md-3">
                                <label for="recipient-name"
                                       class="col-form-label"> @lang('carrent.car_passengers') </label>
                                <input type="number" class="form-control" name="car_passengers"
                                       id="car_passengers" value="{{ $car_model->car_passengers }}" disabled>

                            </div>

                        </div>


                    </div>

                    @php
                        $i=1;
                    @endphp
                    <form action="{{ route('CarRentCars.store') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $car_model->car_rent_model_id }}" name="car_rent_model_id">
                        @for($i ; $i<=$counter ; $i++)

                            <div class="card">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.car_plate_ar') </label>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <select class="form-control plate_ar_1" name="plate_ar_1[]"
                                                            onchange="getLetter($(this))" required>
                                                        @foreach(config('global.car_letters_ar') as $letter )
                                                            <option value="{{$letter}}">{{$letter}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <select class="form-control plate_ar_2" name="plate_ar_2[]"
                                                            onchange="getLetter($(this))" required>
                                                        @foreach(config('global.car_letters_ar') as $letter )
                                                            <option value="{{$letter}}">{{$letter}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-md-2">

                                                    <select type="text" class="form-control plate_ar_3" required
                                                            name="plate_ar_3[]" onchange="getLetter($(this))">
                                                        @foreach(config('global.car_letters_ar') as $letter )
                                                            <option value="{{$letter}}">{{$letter}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="number" class="form-control car_plate_number1"
                                                           name="car_plate_number[]" required
                                                           onkeyup="carNumber($(this))">
                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.car_plate_en') </label>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <select class="form-control plate_en_1" name="plate_en_1[]"
                                                            onchange="getLetterAr($(this))" required>
                                                        @foreach(config('global.car_letters_en') as $letter )
                                                            <option value="{{$letter}}">{{$letter}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <select class="form-control plate_en_2" name="plate_en_2[]"
                                                            onchange="getLetterAr($(this))" required>
                                                        @foreach(config('global.car_letters_en') as $letter )
                                                            <option value="{{$letter}}">{{$letter}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-md-2">

                                                    <select type="text" class="form-control plate_en_3"
                                                            name="plate_en_3[]" onchange="getLetterAr($(this))"
                                                            required>
                                                        @foreach(config('global.car_letters_en') as $letter )
                                                            <option value="{{$letter}}">{{$letter}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <input type="number" class="form-control car_plate_number2"
                                                           onkeyup="carNumber($(this))" required>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>@lang('carrent.car_chasi')</label>
                                            <input type="text" class="form-control" name="car_chase[]">
                                        </div>

                                        <div class="col-md-2">
                                            <label>@lang('carrent.car_motor_no')</label>
                                            <input type="text" class="form-control" name="car_motor_no[]">
                                        </div>

                                        <div class="col-md-2">
                                            <label>@lang('carrent.car_operation_card_no')</label>
                                            <input type="text" class="form-control" name="car_operation_card_no[]">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name"> @lang('carrent.car_color') </label>
                                            <input type="text" class="form-control" name="car_color[]"
                                                   id="car_color" placeholder="@lang('carrent.car_color')"
                                                   value="{{$car_model->car_color}}">

                                        </div>

                                        <div class="col-md-3">
                                            <label for="recipient"> @lang('carrent.car_kilometar') </label>
                                            <input type="number" class="form-control " name="allowedKmPerHour[]"
                                                   value="{{$car_model->carRentCars->first()->allowedKmPerHour}}">
                                        </div>


                                        <div class="col-md-2">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('carrent.insurance_type') </label>
                                            <select class="form-select form-control" name="insurance_type[]"
                                                    aria-label="Default select example" id="insurance_type">
                                                <option value=""> @lang('home.choose')</option>
                                                @foreach($sys_codes_insurance_types as $codes_insurance_type)
                                                    <option value="{{$codes_insurance_type->system_code_id}}"
                                                            @if($car_model->carRentCars->first()->insurance_type == $codes_insurance_type->system_code_id)
                                                            selected @endif>
                                                        {{app()->getLocale() == 'ar'
                                                        ? $codes_insurance_type->system_code_name_ar
                                                        : $codes_insurance_type->system_code_name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.insurance_document_no') </label>
                                            <input type="number" class="form-control " name="insurance_document_no[]"
                                                   id="insurance_document_no" value="{{$car_model->carRentCars
                                                   ->first()->insurance_document_no}}">

                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.insurance_value') </label>
                                            <input type="number" class="form-control " name="insurance_value[]"
                                                   value="{{$car_model->carRentCars->first()->insurance_value}}">

                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('carrent.insurance_date_end') </label>
                                            <input type="date" class="form-control " name="insurance_date_end[]"
                                                   id="insurance_date_end" value="{{$car_model->carRentCars->first()
                                                   ->insurance_date_end}}"
                                                   placeholder="@lang('carrent.insurance_date_end')">
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient"
                                                   class="col-form-label"> @lang('carrent.car_trucker_status') </label>
                                            <select class="form-select form-control" name="car_trucker_status[]"
                                                    aria-label="Default select example" id="car_trucker_status"
                                                    required>
                                                @foreach($sys_codes_tracker_status as $sys_codes_tracker_st)
                                                    <option value="{{$sys_codes_tracker_st->system_code_id}}"
                                                            @if($car_model->carRentCars->first()->car_trucker_status == $sys_codes_tracker_st->system_code_id)
                                                            selected @endif>
                                                        @if(app()->getLocale() == 'ar')
                                                            {{$sys_codes_tracker_st->system_code_name_ar}}
                                                        @else
                                                            {{$sys_codes_tracker_st->system_code_name_en}}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('carrent.tracker_serial') </label>
                                            <input type="number" class="form-control " name="tracker_serial[]"
                                                   id="tracker_serial"
                                                   value="{{$car_model->carRentCars->first()->tracker_serial}}">

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <hr>

                        @endfor
                        <button type="submit" class="btn btn-primary">@lang('home.save')</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script>


        //     //    validation to create trucks

        $('form').submit(function () {

            $('#submit').css('display', 'none')
            $('.spinner-border').css('display', 'block')

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
