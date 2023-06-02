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


                @include('Includes.form-errors')
                {{-- Form To Create EmployeeExperience--}}
                <form class="card" id="validate-form" action="{{route('employee-experience-store')}}"
                      method="post"
                      enctype="multipart/form-data" id="submit_certificates_form">
                    @csrf

                    <div class="card-body">

                        <input type="hidden" name="emp_id" value="{{$employee->emp_id}}">


                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label "> @lang('home.country') </label>
                                    <select class=" form-select form-control  is-invalid"
                                            name="emp_experience_country" id="emp_experience_country"
                                            placeholder="@lang('home.country')" required>
                                        <option value="">choose</option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->system_code_id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$country->system_code_name_ar}}
                                                @else
                                                    {{$country->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.job') </label>
                                    <input type="text" class="form-control is-invalid"
                                           name="emp_experience_job"
                                           id="emp_experience_job"
                                           placeholder="@lang('home.job')" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.experience_company') </label>
                                    <input type="text" class="form-control is-invalid"
                                           name="emp_experience_company"
                                           id="emp_experience_company"
                                           placeholder="@lang('home.experience_company')" required>
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.from') </label>
                                    <input type="date" class="form-control is-invalid"
                                           name="emp_experience_start_date" id="emp_experience_start_date"
                                           placeholder="@lang('home.from')" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.to') </label>
                                    <input type="date" class="form-control is-invalid"
                                           name="emp_experience_end_date" id="emp_experience_end_date"
                                           placeholder="@lang('home.to')" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.period') </label>
                                    <input type="number" class="form-control is-invalid"
                                           name="emp_experience_period" id="emp_experience_period"
                                           placeholder="@lang('home.period')" required>
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.salary') </label>
                                    <input type="number" step="0.01" class="form-control is-invalid"
                                           name="emp_experience_salary" id="emp_experience_salary"
                                           placeholder="@lang('home.salary')" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.reason_leaving') </label>
                                    <input type="text" class="form-control is-invalid"
                                           name="emp_experience_leave_reason" id="emp_experience_leave_reason"
                                           placeholder="@lang('home.reason_leaving')" required>
                                </div>
                            </div>
                        </div>


                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-6">


                                    <div class="card">

                                        <div class="card-body">
                                            <input type="file" id="dropify-event"
                                                   name="emp_experience_file_url" required>
                                        </div>
                                    </div>


                                </div>


                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2"
                                    id="create_emp">@lang('home.save')</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
    <script>


        $(document).ready(function () {

            $('#emp_experience_start_date').change(function () {
                $('#emp_experience_start_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            $('#emp_experience_end_date').change(function () {
                $('#emp_experience_end_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });


            $('#emp_experience_country').change(function () {
                if (!$('#emp_experience_country').val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_experience_job').keyup(function () {
                if ($('#emp_experience_job').val().length < 3) {
                    $('#emp_experience_job').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_experience_job').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_experience_company').keyup(function () {
                if ($('#emp_experience_company').val().length < 3) {
                    $('#emp_experience_company').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_experience_company').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });

            $('#emp_experience_period').keyup(function () {
                if (!$('#emp_experience_period').val() > 0) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })


            $('#emp_experience_salary').keyup(function () {
                if (!$('#emp_experience_salary').val() > 0) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_experience_leave_reason').keyup(function () {
                if ($('#emp_experience_leave_reason').val().length < 3) {
                    $('#emp_experience_leave_reason').addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $('#emp_experience_leave_reason').removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            });


        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>

@endsection
