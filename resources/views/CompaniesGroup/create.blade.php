@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}

@endsection
@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

                @include('Includes.form-errors')

                <form class="card" action="{{ route('mainCompanies.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h3 class="card-title">@lang('home.add_company')</h3>
                        <div class="row">
                            <input type="file" id="dropify-event"
                                   name="company_group_logo">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_ar')</label>
                                    <input type="text" class="form-control is-invalid"
                                           placeholder="@lang('home.company_name_ar')"
                                           value="{{ old('company_group_ar') }}"
                                           name="company_group_ar"
                                           oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                           id="company_group_ar">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.name_en')</label>
                                    <input type="text" class="form-control is-invalid"
                                           name="company_group_en"
                                           oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                           placeholder="@lang('home.company_name_en')"
                                           value="{{ old('company_group_en') }}" id="company_group_en">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.email_address')</label>
                                    <input type="email" name="main_email" class="form-control is-invalid"
                                           id="main_email"
                                           value="{{ old('main_email') }}" placeholder="example@mail.com">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.responsible_person')</label>
                                    <input type="text" class="form-control is-invalid" name="responsible_person"
                                           placeholder="@lang('home.responsible_person')" id="responsible_person"
                                           value="{{ old('responsible_person') }}">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.mobile_number')</label>
                                    <input type="number" name="mobile_number" class="form-control is-invalid"
                                           id="mobile_number"
                                           placeholder="000818818" value="{{ old('mobile_number') }}">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.phone_number')</label>
                                    <input type="number" class="form-control is-invalid" name="phone_no"
                                           placeholder="0099827727"
                                           value="{{ old('phone_no') }}" id="phone_no">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.tax_number')</label>
                                    <input type="number" class="form-control is-invalid" name="tax_number"
                                           placeholder="987363635"
                                           value="{{ old('tax_number') }}" id="tax_number">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.address')</label>
                                    <input type="text" class="form-control is-invalid" placeholder="132 El Street"
                                           name="main_address" id="main_address"
                                           value="{{ old('main_address') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.postal_code')</label>
                                    <input type="text" class="form-control is-invalid" placeholder="1234"
                                           value="{{ old('postal_code') }}" name="postal_code" id="postal_code">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.postal_box')</label>
                                    <input type="text" class="form-control is-invalid" name="postal_box"
                                           placeholder="64646" value="{{ old('postal_box') }}" id="postal_box">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.commercial_register')</label>
                                    <input type="text" class="form-control is-invalid" placeholder="1234"
                                           id="commercial_register"
                                           value="{{ old('commercial_register') }}" name="commercial_register">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.companies_number')</label>
                                    <input type="text" class="form-control is-invalid" name="companys_number"
                                           id="companys_number"
                                           placeholder="7" value="{{ old('companys_number') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.open_date')</label>
                                    <input type="date" class="form-control is-invalid" id="open_date"
                                           value="{{ old('open_date') }}" name="open_date">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.end_date')</label>
                                    <input type="date" class="form-control is-invalid" name="end_date" id="end_date"
                                           value="{{ old('end_date') }}">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">@lang('home.status')</label>
                                    <select class="form-control" name="c_group_is_active">
                                        <option value="1">on</option>
                                        <option value="0">off</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" id="submit" class="btn btn-primary">@lang('home.add_company')</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        //    validation to create modal
        $('#company_group_en').keyup(function () {
            if ($('#company_group_en').val().length < 3) {
                $('#company_group_en').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_group_en').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#company_group_ar').keyup(function () {
            if ($('#company_group_ar').val().length < 3) {
                $('#company_group_ar').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#company_group_ar').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#main_email').keyup(function () {
            if (!validEmail($('#main_email').val())) {
                $('#main_email').addClass('is-invalid')
                $('#submit').attr('disabled', 'disabled')
            } else {
                $('#main_email').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#responsible_person').keyup(function () {
            if ($('#responsible_person').val().length < 3) {
                $('#responsible_person').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#responsible_person').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#mobile_number').keyup(function () {
            if ($('#mobile_number').val().length < 11) {
                $('#mobile_number').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#mobile_number').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#phone_no').keyup(function () {
            if ($('#phone_no').val().length < 11) {
                $('#phone_no').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#phone_no').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#tax_number').keyup(function () {
            if ($('#tax_number').val().length < 3) {
                $('#tax_number').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#tax_number').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#main_address').keyup(function () {
            if ($('#main_address').val().length < 3) {
                $('#main_address').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#main_address').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#postal_code').keyup(function () {
            if ($('#postal_code').val().length < 3) {
                $('#postal_code').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#postal_code').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#postal_box').keyup(function () {
            if ($('#postal_box').val().length < 3) {
                $('#postal_box').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#postal_box').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#commercial_register').keyup(function () {
            if ($('#commercial_register').val().length < 3) {
                $('#commercial_register').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#commercial_register').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        $('#open_date').change(function () {
            $('#open_date').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');

        });

        $('#end_date').change(function () {

            $('#end_date').removeClass('is-invalid');
            $('#submit').removeAttr('disabled');
        });

        $('#companys_number').keyup(function () {
            if ($('#companys_number').val() <= 0) {
                $('#companys_number').addClass('is-invalid');
                $('#submit').attr('disabled', 'disabled');
            } else {
                $('#companys_number').removeClass('is-invalid');
                $('#submit').removeAttr('disabled');
            }
        });

        function validEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    </script>
@endsection
