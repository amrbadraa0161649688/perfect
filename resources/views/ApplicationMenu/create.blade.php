@extends('Layouts.master')

@section('content')

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

                @include('Includes.form-errors')
                <form class="card" action="{{ route('applicationMenu.store') }}" method="post">
                    @csrf
                    <div class="card-header bold"> @lang('home.add_application_menu') </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.name_ar') </label>
                                    <input type="text" class="form-control"
                                           name="app_menu_name_ar" oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                           id="app_menu_name_ar" placeholder="@lang('home.name_ar')">
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.name_en') </label>
                                    <input type="text" class="form-control"
                                           name="app_menu_name_en" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                           id="app_menu_name_en" placeholder="@lang('home.name_en')">
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.order') </label>
                                    <input type="number" class="form-control" name="app_menu_order"
                                           id="app_menu_order" placeholder="@lang('home.order')">
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.code') </label>
                                    <input type="number" class="form-control" name="app_menu_code"
                                           id="app_menu_code" placeholder="@lang('home.code')">
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col md 12">
                                    <label for="message-text"
                                           class="col-form-label"> @lang('home.icon')</label>
                                    <input type="text" class="form-control" name="app_menu_icon"
                                           id="app_menu_icon" placeholder="@lang('home.icon')">
                                </div>
                                {{--<div class="col md 6">--}}
                                {{--<label for="message-text" class="col-form-label"> @lang('home.icon')</label>--}}
                                {{--<input type="text" class="form-control" name="app_menu_color" id="recipient-name"--}}
                                {{--placeholder="@lang('home.color')">--}}
                                {{--</div>--}}
                            </div>
                            <input type="hidden" class="form-control" name="app_id"
                                   value="{{$application->app_id}}" id="recipient-name">

                            <label for="message-text"
                                   class="col-form-label"> @lang('home.application_status')</label>
                            <select class="form-select form-control" name="app_menu_is_active"
                                    aria-label="Default select example" id="app_menu_is_active">
                                <option value="1" selected>On</option>
                                <option value="0">Off</option>
                            </select>


                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-secondary mr-2" id="submit">@lang('home.save')</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>

        if ($('#app_menu_name_en').val().length == 0) {
            $('#app_menu_name_en').addClass('is-invalid')
            $('#submit').attr('disabled', 'disabled')
        } else {
            $('#app_menu_name_en').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');
        }

        if ($('#app_menu_name_ar').val().length == 0) {
            $('#app_menu_name_ar').addClass('is-invalid')
            $('#submit').attr('disabled', 'disabled')
        } else {
            $('#app_menu_name_ar').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');
        }

        if ($('#app_menu_icon').val().length == 0) {
            $('#app_menu_icon').addClass('is-invalid')
            $('#submit').attr('disabled', 'disabled')
        } else {
            $('#app_menu_icon').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');
        }


        if ($('#app_menu_order').val().length == 0) {
            $('#app_menu_order').addClass('is-invalid')
            $('#submit').attr('disabled', 'disabled')
        } else {
            $('#app_menu_order').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');
        }

        if ($('#app_menu_code').val().length == 0) {
            $('#app_menu_code').addClass('is-invalid')
            $('#submit').attr('disabled', 'disabled')
        } else {
            $('#app_menu_code').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');
        }


        //    validation to create modal
        $('#app_menu_name_en').keyup(function () {
            if ($('#app_menu_name_en').val().length < 3) {
                $('#app_menu_name_en').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#app_menu_name_en').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#app_menu_name_ar').keyup(function () {
            if ($('#app_menu_name_ar').val().length < 3) {
                $('#app_menu_name_ar').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#app_menu_name_ar').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#app_menu_icon').keyup(function () {
            if ($('#app_menu_icon').val().length < 3) {
                $('#app_menu_icon').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#app_menu_icon').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#app_menu_order').keyup(function () {
            if ($('#app_menu_order').val().length < 3) {
                $('#app_menu_order').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#app_menu_order').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#app_menu_code').keyup(function () {
            if ($('#app_menu_code').val().length < 3) {
                $('#app_menu_code').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#app_menu_code').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

    </script>
@endsection
