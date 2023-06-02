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
                {{-- Form To Create EmployeeContracts--}}
                <form class="card" id="validate-form" action="{{route('employees-contracts-store')}}"
                      method="post"
                      enctype="multipart/form-data" id="submit_user_form">
                    @csrf

                    <div class="card-body">

                        <input type="hidden" name="emp_id" value="{{$employee->emp_id}}">

                        <div class="md-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label "> @lang('home.name') </label>
                                    <input type="text" class="form-control" name="emp_name_full_ar"
                                           id="emp_name_full_ar" value=" {{$employee->emp_name_full_ar}}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient-name"
                                           class="col-form-label "> @lang('home.emp_code') </label>
                                    <input type="text" class="form-control" name="emp_code"
                                           id="emp_code" value=" {{$employee->emp_code}}" readonly>
                                </div>
                            </div>
                        </div>


                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label "> @lang('home.contract_type') </label>
                                    <select class=" form-select form-control  is-invalid"
                                            name="emp_contract_type_id" id="emp_contract_type_id"
                                            placeholder="@lang('home.contract_type')" required>
                                        <option value="">choose</option>
                                        @foreach($contracts_types as $contract_type)
                                            <option value="{{$contract_type->system_code_id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$contract_type->system_code_name_ar}}
                                                @else
                                                    {{$contract_type->system_code_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.sub_company') </label>
                                    <select class="form-select form-control" :class="{'is-invalid' : company_class}"
                                            name="emp_contract_company_id" @change="getBranches()"
                                            id="emp_default_company_id" v-model="company_id">
                                        @foreach($companies as $company)
                                            <option value="{{ $company->company_id }}"
                                                    @if($company->company_id == $employee->emp_default_company_id)
                                                    selected @endif>
                                                @if(app()->getLocale()=='ar') {{ $company->company_name_ar }}
                                                @else {{ $company->company_name_en }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.branches') </label>
                                    <select class="form-select form-control is-invalid" name="emp_contract_branch_id"
                                            id="emp_contract_branch_id" required>
                                        <option value="">choose</option>
                                        <option v-for="branch in branches" :value="branch.branch_id">
                                            @if(app()->getLocale()=='ar')
                                                @{{ branch.branch_name_ar }}
                                            @else
                                                @{{  branch.branch_name_en }}
                                            @endif
                                        </option>

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.job') </label>
                                    <select type="text" class="form-control is-invalid"
                                            name="emp_contract_job_id" id="emp_contract_job_id"
                                            placeholder="@lang('home.job')" required>
                                        <option value="">Choose</option>
                                        <option v-for="job in jobs" :value="job.job_id">
                                            @if(app()->getLocale() == 'ar')
                                                @{{ job.job_name_ar}}
                                            @else
                                                @{{ job.job_name_en}}
                                            @endif
                                        </option>
                                    </select>

                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.contract_start_date') </label>
                                    <input type="date" class="form-control is-invalid"
                                           name="emp_contract_start_date" id="emp_contract_start_date"
                                           placeholder="@lang('home.contract_start_date')" required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.contract_end_date') </label>
                                    <input type="date" class="form-control is-invalid"
                                           name="emp_contract_end_date" id="emp_contract_end_date"
                                           placeholder="@lang('home.contract_end_date')" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.contract_work_hours') </label>
                                    <input type="number" min="1" class="form-control is-invalid"
                                           name="emp_contract_work_hours" id="emp_contract_work_hours"
                                           placeholder="@lang('home.contract_work_hours')" required>
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                {{--<div class="col-md-4">--}}
                                {{--<label for="recipient"--}}
                                {{--class="col-form-label"> @lang('home.contract_ticket_type') </label>--}}
                                {{--<select class="form-select form-control is-invalid"--}}
                                {{--name="emp_contract_ticket_type" id="emp_contract_ticket_type"--}}
                                {{--placeholder="@lang('home.contract_ticket_type')" required>--}}

                                {{--<option value="">choose</option>--}}
                                {{--<option value="1">Type1</option>--}}
                                {{--<option value="2">Type2</option>--}}

                                {{--</select>--}}
                                {{--</div>--}}
                                <div class="col-md-6">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.contract_notes') </label>
                                    <textarea class="form-control is-invalid"
                                              name="emp_contract_notes" id="emp_contract_notes"
                                              placeholder="@lang('home.contract_notes')" required>

                                    </textarea>
                                </div>

                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.manager') </label>
                                    <select class="form-select form-control is-invalid"
                                            name="emp_contract_manager_id" id="emp_contract_manager_id"
                                            placeholder="@lang('home.manager')" required>

                                        <option value="">choose</option>
                                        @foreach($employees as $employee)
                                            <option value="{{$employee->emp_id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{  $employee->emp_name_full_ar }}
                                                @else
                                                    {{  $employee->emp_name_full_en }}
                                                @endif
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient"
                                           class="col-form-label"> @lang('home.emp_contract_is_active') </label>
                                    <input type="checkbox" class="text-center is-invalid"
                                           name="emp_contract_is_active" id="emp_contract_is_active"
                                           placeholder="@lang('home.contract_is_active')">
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

            $('#emp_contract_start_date').change(function () {
                $('#emp_contract_start_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            $('#emp_contract_end_date').change(function () {
                $('#emp_contract_end_date').removeClass('is-invalid')
                $('#create_emp').removeAttr('disabled', 'disabled')
            });

            $('#emp_contract_branch_id').change(function () {
                if (!$('#emp_contract_branch_id').val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_contract_job_id').change(function () {
                if (!$('#emp_contract_job_id').val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_contract_company_id').change(function () {
                if (!$('#emp_contract_company_id').val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_contract_type_id').change(function () {
                if (!$('#emp_contract_type_id').val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_contract_company_id').change(function () {
                if (!$('#emp_contract_company_id').val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_contract_work_hours').keyup(function () {
                if (!$('#emp_contract_work_hours').val() > 0) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })

            $('#emp_contract_manager_id').keyup(function () {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })


            $('#emp_contract_notes').keyup(function () {
                if (!$('#emp_contract_notes').val().length > 3) {
                    $(this).addClass('is-invalid')
                    $('#create_emp').attr('disabled', 'disabled')
                } else {
                    $(this).removeClass('is-invalid')
                    $('#create_emp').removeAttr('disabled', 'disabled')
                }
            })


        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">

        $(function () {

            $("#emp_hijri_start_date").hijriDatePicker();
            $("#emp_hijri_end_date").hijriDatePicker();
            $("#emp_birthday_hijiri").hijriDatePicker();

        });


    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                company_id: '',
                branches: {},
                jobs: {},
                company_class: true
            },
            methods: {
                getBranches() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.branches") }}'
                    }).then(response => {
                        this.branches = response.data
                        this.jobs = response.jobs
                        this.company_class = false
                    })

                }
            }
        });
    </script>

@endsection
