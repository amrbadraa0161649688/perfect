@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                {{-- Basic information --}}
                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">

                    {{-- Form To Create Customer--}}
                    <form class="card" id="validate-form"
                          action="{{ route('CarRentModel.update' ,$car_model->car_rent_model_id) }}" method="post"
                          enctype="multipart/form-data" id="submit_user_form">
                        @csrf
                        @method('put')

                        <div class="card-body">
                            {{--inputs and photo--}}
                            <div class="font-25">
                                @lang('carrent.add_car_model')
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
                                                       value="{{$car_model->car_rent_model_code}}"
                                                       disabled >
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_purchase_date') </label>
                                                <input type="date" class="form-control"
                                                       name="car_purchase_date"
                                                       id="car_purchase_date" required
                                                       value="{{$car_model->car_purchase_date}}" readonly>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_qty') </label>
                                                <input type="number" class="form-control" name="car_qty"
                                                       id="car_qty" value="{{$car_model->car_qty}}" disabled="" readonly>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('carrent.car_status') </label>
                                                <select class="form-select form-control"
                                                        name="car_rent_model_status"
                                                        id="car_rent_model_status" required readonly="">
                                                    @foreach($sys_codes_type as $sys_code_type)
                                                        <option value="{{$sys_code_type->system_code_id}}"
                                                                @if($car_model->car_rent_model_status == $sys_code_type->system_code_id)
                                                                selceted @endif>
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
                                                            name="car_brand_id"
                                                            aria-label="Default select example" id="car_brand_id" disabled>
                                                        @foreach($car_rent_brands as $car_rent_brand)
                                                            <option value="{{$car_rent_brand->brand_id}}"
                                                                    @if($car_model->car_brand_id == $car_rent_brand->brand_id)
                                                                    selected @endif>
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
                                                            required disabled>
                                                        @foreach($car_rent_brands_dt as $car_rent_brand_dt)
                                                            <option value="{{$car_rent_brand_dt->brand_dt_id}}"
                                                                    @if($car_model->car_brand_dt_id == $car_rent_brand_dt->brand_dt_id)
                                                                    selected @endif>
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
                                                    <input type="number" class="form-control"
                                                           name="car_model_year"
                                                           id="car_model_year" required
                                                           value="{{$car_model->car_model_year}}" readonly>

                                                </div>


                                                <div class="col-md-3">
                                                    <label for="recipient"
                                                           class="col-form-label"> @lang('carrent.car_category') </label>
                                                    <select class="form-select form-control"
                                                            name="car_category_id"
                                                            aria-label="Default select example" id="car_category_id"
                                                            required disabled>
                                                        @foreach($sys_codes_status as $sys_code_status)
                                                            <option value="{{$sys_code_status->system_code_id}}"
                                                                    @if($car_model->car_category_id == $sys_code_status->system_code_id)
                                                                    selected @endif>
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
                                                        aria-label="Default select example" id="Property_type" required disabled>
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
                                                       value="{{$car_model->car_purchase_cost}}" readonly>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.car_ownership') </label>
                                                <input type="text" class="form-control" name="owner_name"
                                                       id="owner_name" value="{{$car_model->owner_name}}"
                                                       required readonly>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient"
                                                       class="col-form-label"> @lang('carrent.sub_company') </label>
                                                <select class="form-select form-control"
                                                        name="company_id" required disabled>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->company_id }}" @if($car_model->company_id ==
                                                         $company->company_id) selected @endif>
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
                                                <img src="{{ asset($car_model->car_photo_url)}}" width="200" height="200">
                                                {{--<input type="file" id="dropify-event"--}}
                                                       {{--name="car_photo_url">--}}
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
                                                aria-label="Default select example" id="gear_box_type_id" required disabled>
                                            @foreach($sys_codes_status_68 as $sys_code_status_68)
                                                <option value="{{$sys_code_status_68->system_code_id}}"
                                                        @if($car_model->gear_box_type_id == $sys_code_status_68->system_code_id)
                                                        selected @endif>
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
                                                aria-label="Default select example" id="engine_type" required disabled>

                                            @foreach($sys_codes_status_69 as $sys_code_status_69)
                                                <option value="{{$sys_code_status_69->system_code_id}}"
                                                        @if($car_model->engine_type == $sys_code_status_69->system_code_id)
                                                        selected @endif>
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
                                                aria-label="Default select example" id="fuel_type_id" required disabled>
                                            @foreach($sys_codes_status_70 as $sys_code_status_70)
                                                <option value="{{$sys_code_status_70->system_code_id}}"
                                                        @if($car_model->fuel_type_id == $sys_code_status_70->system_code_id)
                                                        selected @endif>
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
                                        <input type="number" class="form-control" name="car_color"
                                               id="car_color" value="{{$car_model->car_color}}" readonly>

                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('carrent.oil_type') </label>
                                    <select class="form-select form-control" name="oil_type"
                                            aria-label="Default select example" id="oil_type" required disabled>

                                        @foreach($sys_codes_status_71 as $sys_code_status_71)
                                            <option value="{{$sys_code_status_71->system_code_id}}"
                                                    @if($car_model->oil_type == $sys_code_status_71->system_code_id)
                                                    selected @endif>
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
                                           id="oil_change_km" value="{{$car_model->oil_change_km}}" readonly>

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_doors') </label>
                                    <input type="number" class="form-control" name="car_doors"
                                           id="car_doors" value="{{$car_model->car_doors}}" readonly>

                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('carrent.car_passengers') </label>
                                    <input type="number" class="form-control" name="car_passengers"
                                           id="car_passengers" value="{{$car_model->car_passengers}}" readonly>

                                </div>

                            </div>


                            {{--<div class="row">--}}

                                {{--<div class="card bline" style="color:red">--}}
                                {{--</div>--}}


                            {{--</div>--}}


                            <div class="card-footer">
                                {{--<button type="submit" class="btn btn-primary mr-2"--}}
                                        {{--data-bs-dismiss="modal" id="submit">@lang('carrent.save')</button>--}}
                                {{--<div class="spinner-border" role="status" style="display: none">--}}
                                    {{--<span class="sr-only">Loading...</span>--}}
                                {{--</div>--}}
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
            $('form').submit(function () {
                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')
            })
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {},

        })

    </script>
@endsection
