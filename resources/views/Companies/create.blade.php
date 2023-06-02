@extends('Layouts.master')

@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">
                @include('Includes.form-errors')
                <form class="card" action="{{ route('company.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="company_group_id" value="{{ $company_group->company_group_id }}">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="text-center display-5">
                                        @if(app()->getLocale() == 'ar')
                                            {{ $company_group->company_group_ar }}
                                        @else
                                            {{ $company_group->company_group_en }} @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type="file" id="dropify-event"
                                   name="company_logo">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_ar')</label>
                                    <input type="text" class="form-control is-invalid"
                                           placeholder="@lang('home.name_ar')"
                                           value="{{ old('company_name_ar') }}"
                                           name="company_name_ar"
                                           oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                           id="company_name_ar">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_en')</label>
                                    <input type="text" class="form-control is-invalid"
                                           value="{{ old('company_name_en') }}" id="company_name_en"
                                           name="company_name_en"
                                           oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                           placeholder="@lang('home.name_en')">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.email_address')</label>
                                    <input type="email" name="co_email" class="form-control is-invalid"
                                           placeholder="example@mail.com" id="co_email"
                                           value="{{ old('co_email') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.responsible_person')</label>
                                    <input type="text" class="form-control is-invalid" name="co_responsible_person"
                                           id="co_responsible_person"
                                           value="{{ old('co_responsible_person') }}" placeholder="person">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.mobile_number')</label>
                                    <input type="number" name="co_mobile_number" class="form-control is-invalid"
                                           placeholder="011929292838" id="co_mobile_number"
                                           value="{{ old('co_mobile_number') }}">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.phone_number')</label>
                                    <input type="number" class="form-control is-invalid" name="co_phone_no"
                                           placeholder="0019292928" id="co_phone_no"
                                           value="{{ old('co_phone_no') }}">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.tax_number')</label>
                                    <input type="number" class="form-control is-invalid" id="company_tax_no"
                                           name="company_tax_no"
                                           value="{{ old('company_tax_no') }}" placeholder="39393">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.address')</label>
                                    <input type="text" class="form-control is-invalid" name="co_address"
                                           id="co_address"
                                           value="{{ old('co_address') }}" placeholder="123 st street">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.postal_code')</label>
                                    <input type="text" class="form-control is-invalid" placeholder="02929"
                                           id="company_postal_code"
                                           value="{{ old('company_postal_code') }}" name="company_postal_code">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.postal_box')</label>
                                    <input type="text" class="form-control is-invalid" name="company_postal_box"
                                           id="company_postal_box"
                                           value="{{ old('company_postal_box') }}" placeholder="02020">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.commercial_register')</label>
                                    <input type="text" class="form-control is-invalid" placeholder="929292"
                                           id="company_register"
                                           value="{{ old('company_register') }}" name="company_register">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.companies_number')</label>
                                    <input type="text" class="form-control is-invalid" name="co_branches_no"
                                           id="co_branches_no"
                                           value="{{ old('co_branches_no') }}" placeholder="2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.employees')</label>
                                    <input type="text" class="form-control is-invalid" name="co_emp_no"
                                           id="co_emp_no"
                                           value="{{ old('co_emp_no') }}" placeholder="2">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.open_date')</label>
                                    <input type="date" class="form-control is-invalid" id="co_open_date"
                                           value="{{ old('co_open_date') }}" name="co_open_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.end_date')</label>
                                    <input type="date" class="form-control is-invalid" name="co_end_date"
                                           value="{{ old('co_end_date') }}" id="co_end_date">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.status')</label>
                                    <select class="form-control" name="co_is_active">
                                        <option value="1" selected>on</option>
                                        <option value="0">off</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" id="submit" class="btn btn-primary">@lang('home.save')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        //    validation to create modal
        $('#company_name_en').keyup(function () {
            if ($('#company_name_en').val().length < 3) {
                $('#company_name_en').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_name_en').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#company_name_ar').keyup(function () {
            if ($('#company_name_ar').val().length < 3) {
                $('#company_name_ar').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_name_ar').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_email').keyup(function () {
            if (!validEmail($('#co_email').val())) {
                $('#co_email').addClass('is-invalid')
                $('#submit').attr('disabled', 'disabled')
            } else {
                $('#co_email').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_responsible_person').keyup(function () {
            if ($('#co_responsible_person').val().length < 3) {
                $('#co_responsible_person').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#co_responsible_person').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_mobile_number').keyup(function () {
            if ($('#co_mobile_number').val().length < 11) {
                $('#co_mobile_number').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#co_mobile_number').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_phone_no').keyup(function () {
            if ($('#co_phone_no').val().length < 11) {
                $('#co_phone_no').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#co_phone_no').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#company_tax_no').keyup(function () {
            if ($('#company_tax_no').val().length < 3) {
                $('#company_tax_no').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_tax_no').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_address').keyup(function () {
            if ($('#co_address').val().length < 3) {
                $('#co_address').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#co_address').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#company_postal_code').keyup(function () {
            if ($('#company_postal_code').val().length < 3) {
                $('#company_postal_code').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_postal_code').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#company_postal_box').keyup(function () {
            if ($('#company_postal_box').val().length < 3) {
                $('#company_postal_box').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_postal_box').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#company_register').keyup(function () {
            if ($('#company_register').val().length < 3) {
                $('#company_register').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_register').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_branches_no').keyup(function () {
            if ($('#co_branches_no').val() <= 0) {
                $('#co_branches_no').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#co_branches_no').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_emp_no').keyup(function () {
            if ($('#co_emp_no').val() <= 0) {
                $('#co_emp_no').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#co_emp_no').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#co_open_date').change(function () {
            $('#co_open_date').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');

        });

        $('#co_end_date').change(function () {

            $('#co_end_date').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');
        });

        function validEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    </script>
@endsection
