@extends('Layouts.master')
@section('style')

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>

@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">

            @include('Includes.form-errors')
            @include('Includes.flash-messages')
            <div class="row clearfix">
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body" >
                            <h6> عدد الموظفين </h6>
                            <h3 class="pt-2"><span class="counter">{{$all_employeess}}</span></h3>
                            <span><span class="text-danger mr-2"><i
                                            class="fa fa-users"></i> 
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 font-weight: bold>علي رأس العمل </h6>
                            <h3 class="pt-2"><span class="counter">{{ $top_employees }}</span></h3>
                            <span><span class="text-success mr-2"><i class="fa fa-users"></i> {{$all_employeess_p}} % </span> السعوده</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> أجـازة براتب</h6>
                            <h3 class="pt-2"><span class="counter">{{ $vacation_employees }}</span></h3>
                            <span><span class="text-success ms-1"><i class="fa fa-users"></i> {{$all_employeess_v}} %</span font-size:7px> </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> أجازة بدون راتب</h6>
                            <h3 class="pt-2"><span class="counter">{{ $vacation_no_salary }}</span></h3>
                            <span><span class="text-success mr-1"><i class="fa fa-users"></i> {{$all_employeess_v_no}} %  </span> </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> موقوفين</h6>
                            <h3 class="pt-2"><span class="counter">{{$stopped_employees}}</span></h3>
                            <span><span class="text-danger mr-1"><i class="fa fa-users"></i>{{$all_employeess_s}} % </span > </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="card" style="background: #B0352F;">
                        <div class="card-body">
                            <h6 style="color: white"> منتهي الخدمة</h6>
                            <h3 style="color: white" class="pt-2"><span class="counter">{{$end_employees}}</span></h3>
                            <span><span style="color: white" class="text mr-1"><i class="fa fa-users"></i> {{$all_employeess_end}} % </span>   </span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-12">
                <div class="card">
                    <div class="card-body">
                        <form action="">
                            <div class="row">

                                <div class="col-md-4">
                                    @if(session('job'))
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 8 && $job_permission->permission_add)
                                                <div class="header-action">
                                                    <a href="{{route('employees.create')}}" class="btn btn-primary">
                                                        <i class="fe fe-plus mr-2"></i>@lang('home.add_employee')
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach

                                    @else
                                        @if(auth()->user()->user_type_id == 1)
                                            <div class="header-action">
                                                <a href="{{route('employees.create')}}" class="btn btn-primary">
                                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_employee')
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div hidden class="col-md-4">

                                    @if(session('company_group'))
                                        <input type="text" class="form-control"
                                               value="@if(app()->getLocale()=='ar')
                                               {{ session('company_group')['company_group_ar'] }} @else
                                               {{ session('company_group')['company_group_en'] }} @endif"
                                               readonly>
                                    @else
                                        <input type="text" class="form-control"
                                               value="@if(app()->getLocale()=='ar')
                                               {{ auth()->user()->companyGroup->company_group_ar }} @else
                                               {{ auth()->user()->companyGroup->company_group_en }} @endif"
                                               readonly>
                                    @endif

                                </div>

                                <div class="col-md-4">

                                    <select class="selectpicker" multiple data-live-search="true"
                                            data-actions-box="true"
                                            name="company_id[]" required>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->company_id }}"
                                                    @if(request()->company_id)
                                                    @foreach(request()->company_id as $company_id)
                                                    @if($company->company_id == $company_id) selected @endif
                                                    @endforeach
                                                    @endif>
                                                {{app()->getLocale()=='ar' ? $company->company_name_ar
                                             : $company->company_name_en }}</option>
                                        @endforeach
                                    </select>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label>@lang('home.emp_code')</label>
                                    <input class="form-control" type="text" name="emp_code_full"
                                           value="{{request()->emp_code_full ? request()->emp_code_full : '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label>@lang('home.name')</label>
                                    <input class="form-control" type="text" name="emp_name_full"
                                           value="{{request()->emp_name_full ? request()->emp_name_full : '' }}">
                                </div>

                                <div class="col-md-3">
                                    <label>@lang('home.private_mobile')</label>
                                    <input class="form-control" type="number" name="emp_private_mobile"
                                           value="{{request()->emp_private_mobile ? request()->emp_private_mobile : '' }}">
                                </div>

                                <div class="col-md-3">
                                    <label>@lang('home.identity')</label>
                                    <input class="form-control" type="number" name="emp_identity"
                                           value="{{request()->emp_identity ? request()->emp_identity : '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">

                                <div class="col-md-3">
                                    <label>@lang('home.status')</label>

                                    <select class="selectpicker" multiple data-live-search="true"
                                            data-actions-box="true"
                                            name="emp_status[]">
                                        @foreach($sys_codes_emp_status as $status)
                                            <option value="{{$status->system_code_id}}"
                                                    @if(request()->emp_status)
                                                    @foreach(request()->emp_status as $status_id)
                                                    @if($status->system_code_id == $status_id) selected @endif
                                                    @endforeach
                                                    @endif
                                            >
                                                {{app()->getLocale() == 'ar' ? $status->system_code_name_ar : $status->system_code_name_en}}
                                            </option>
                                        @endforeach

                                    </select>


                                </div>
                                <div class="col-md-3">
                                    <label>@lang('home.nationality')</label>

                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="emp_nationality[]">
                                        @foreach($sys_codes_nationality_country as $nationality)
                                            <option value="{{$nationality->system_code_id}}"
                                                    @if(request()->emp_nationality)
                                                    @foreach(request()->emp_nationality as $nationality_id)
                                                    @if($nationality->system_code_id == $nationality_id) selected @endif
                                                    @endforeach
                                                    @endif

                                            >
                                                {{app()->getLocale() == 'ar' ? $nationality->system_code_name_ar : $nationality->system_code_name_en}}
                                            </option>

                                        @endforeach
                                    </select>


                                </div>
                                <div class="col-md-3">
                                    <label>@lang('home.jobs')</label>

                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="job_id[]">
                                        @foreach($jobs as $job)
                                            <option value="{{ $job->job_id }}"
                                                    @if(request()->job_id)
                                                    @foreach(request()->job_id as $job_id)
                                                    @if($job->job_id == $job_id) selected @endif
                                                    @endforeach
                                                    @endif

                                            >
                                                {{ app()->getLocale()=='ar' ? $job->job_name_ar : $job->job_name_en }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>
                                <div class="col-md-3">

                                    <label>@lang('home.branch')</label>

                                    <select class="selectpicker" multiple data-live-search="true"
                                            name="branch_id[]">
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->branch_id}}"
                                                    @if(request()->branch_id)
                                                    @foreach(request()->branch_id as $branch_id)
                                                    @if($branch->branch_id == $branch_id) selected @endif
                                                    @endforeach
                                                    @endif

                                            >
                                                {{ app()->getLocale()=='ar' ? $branch->branch_name_ar : $branch->branch_name_en }}
                                            </option>
                                        @endforeach


                                    </select>


                                </div>

                            </div>

                            <div class="row mb-3">

                                <div class="col-md-3">
                                    <label>@lang('home.from')</label>
                                    <input type="date" class="form-control" name="from_date"
                                           @if(request()->from_date) value="{{request()->from_date}}" @endif>

                                </div>

                                <div class="col-md-3">
                                    <label>@lang('home.to')</label>
                                    <input type="date" class="form-control" name="to_date"
                                           @if(request()->to_date) value="{{request()->to_date}}" @endif>
                                </div>

                                <div class="col-md-3">
                                    <button class="btn btn-primary mt-4" type="submit">
                                        <i class="fa fa-search mr-1 ml-1"></i> @lang('home.search')
                                    </button>

                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>


        </div>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <div class="card-options">
                            <form action="{{route('employees.export')}}">
                                @if(request()->to_date && request()->from_date)
                                    <input type="hidden" class="form-control" name="to_date"
                                           value="{{request()->to_date}}">

                                    <input type="hidden" class="form-control" name="to_date"
                                           value="{{request()->to_date}}">
                                @endif
                                @if(request()->emp_name_full)
                                    <input type="hidden" class="form-control" name="emp_code_full"
                                           value="{{request()->emp_coide_full}}">
                                @endif

                                @if(request()->emp_name_full)
                                    <input type="hidden" class="form-control" name="emp_name_full"
                                           value="{{request()->emp_name_full}}">
                                @endif

                                @if(request()->emp_private_mobile)
                                    <input type="hidden" class="form-control" name="emp_private_mobile"
                                           value="{{request()->emp_private_mobile}}">
                                @endif

                                @if(request()->emp_identity)
                                    <input type="hidden" class="form-control" name="emp_identity"
                                           value="{{request()->emp_identity}}">
                                @endif


                                @if(request()->branch_id)
                                    @foreach(request()->branch_id as $branch_id)
                                        <input type="hidden" name="branch_id[]" value="{{ $branch_id }}">
                                    @endforeach
                                @endif

                                @if(request()->job_id)
                                    @foreach(request()->job_id as $job_id)
                                        <input type="hidden" name="job_id[]" value="{{ $job_id }}">
                                    @endforeach
                                @endif

                                @if(request()->emp_nationality)
                                    @foreach(request()->emp_nationality as $emp_nationality)
                                        <input type="hidden" name="emp_nationality[]" value="{{ $emp_nationality }}">
                                    @endforeach
                                @endif

                                @if(request()->emp_status)
                                    @foreach(request()->emp_status as $emp_status)
                                        <input type="hidden" name="emp_status[]" value="{{ $emp_status }}">
                                    @endforeach
                                @endif

                                @if(request()->company_id)
                                    @foreach(request()->company_id as $company_id)
                                        <input type="hidden" name="company_id[]" value="{{ $company_id }}">
                                    @endforeach
                                @endif

                                <button type="submit" class="btn btn-primary">@lang('home.export_sheet')</button>
                            </form>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive table_e2">
                            <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                <thead style="background-color: #ece5e7">
                                <tr>
                                    <th>No</th>
                                    <th></th>
                                    <th>@lang('home.emp_code')</th>
                                    <th>@lang('home.name_ar')</th>
                                    <th>@lang('home.sub_company')</th>
                                    <th>@lang('home.branch')</th>
                                    <th>@lang('home.status')</th>
                                    <th>@lang('home.job')</th>
                                    <th>@lang('home.work_start_date')</th>
                                    <th>@lang('home.private_mobile')</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($employees as $k=>$employee)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td>
                                        <div class="avatar avatar-blue" data-toggle="tooltip"
                                                     data-placement="top"
                                                     title="" data-original-title="Avatar Name">
                                                    <img class="avatar avatar-blue"
                                                         src="{{ $employee->emp_photo_url}}">
                                                </div>
                                           
                                                </td>
                                        <td>{{ $employee->emp_code }}</td>
                                        <td>{{ app()->getLocale()=='ar' ? $employee->emp_name_full_ar : $employee->emp_name_full_en }}</td>
                                        <td>{{ app()->getLocale()=='ar' ? $employee->company->company_name_ar : $employee->company->company_name_en  }}</td>
                                        <td>{{ app()->getLocale()=='ar' ? $employee->branch->branch_name_ar : $employee->branch->branch_name_en  }}</td>
                                        <td>{{ app()->getLocale()=='ar' ? $employee->status->system_code_name_ar : $employee->status->system_code_name_en  }}</td>
                                        <td>@if($employee->contractActive)
                                                {{ app()->getLocale()=='ar' ? $employee->contractActive->job->job_name_ar : $employee->contractActive->job->job_name_en }}
                                            @else
                                                لا يوجد عقد ساري
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-y', strtotime($employee->emp_work_start_date)) }}</td>
                                        <td>{{ $employee->emp_private_mobile }}</td>
                                        <td>
                                            @if(auth()->user()->user_type_id != 1)
                                                @foreach(session('job')->permissions as $job_permission)
                                                    @if($job_permission->app_menu_id == 8 && $job_permission->permission_add)
                                                        <a class="btn btn-icon"
                                                           href="{{route('employees.edit',$employee->emp_id)}}"
                                                           title="@lang('home.edit')">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif

                                            @if(auth()->user()->user_type_id == 1)
                                                <a class="btn btn-primary btn-sm"
                                                   href="{{route('employees.edit',$employee->emp_id)}}"
                                                   title="@lang('home.edit')">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                            
                                            <a href="{{config('app.telerik_server')}}?rpt={{$employee->report_url_info->report_url}}&comp_id={{$employee->emp_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="fa fa-id-card"></i>
                                                    </a>
                                                   

                                                    @foreach($emp_report as $emp_reports)
                                                            <a href="{{config('app.telerik_server')}}?rpt={{$emp_reports->report_url}}&id={{$employee->emp_id}}&lang=ar&skinName=bootstrap"
                                                            class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="fa fa-file-text"></i>
                                                    </a>
                                                            @endforeach 
                                                          
                                                            
                                            @if(auth()->user()->user_type_id == 1)
                                                <form action="{{route('employees.delete',$employee->emp_id)}}"
                                                      method="post" style="display: inline-block"
                                                      id="form{{$employee->emp_id}}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm"
                                                            id="submit{{$employee->emp_id}}"
                                                            onclick="deleteEmp('{{$employee->emp_id}}')"><i
                                                                class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->user_type_id != 1)
                                                @foreach(session('job')->permissions as $job_permission)
                                                    @if($job_permission->app_menu_id == 8 && $job_permission->permission_delete)
                                                        <form action="{{route('employees.delete',$employee->emp_id)}}"
                                                              method="post" style="display: inline-block"
                                                              id="form{{$employee->emp_id}}">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="button" id="submit{{$employee->emp_id}}"
                                                                    onclick="deleteEmp('{{$employee->emp_id}}')"
                                                                    class="btn btn-danger btn-sm"><i
                                                                        class="fa fa-trash-o"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>


@endsection

@section('scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>

        function deleteEmp(id) {

            var proceed = confirm("هل انت متاكد من الحذف");
            if (proceed) {
                $('#form' + id).submit();
            }
        }

        $(document).ready(function () {

            function show(el) {
                var x = el.id;
                $("#app-" + x).css("display", "block");
                $("#app-" + x).siblings().css('display', 'none')
            }

            $('#user_mobile_search').keyup(function () {
                if ($('#user_mobile_search').val().length >= 10) {
                    $('#search_form').submit()
                }
            });


            //    validation to create modal
            $('#user_name_ar').keyup(function () {
                if ($('#user_name_ar').val().length < 3) {
                    $('#user_name_ar').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_ar').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_name_en').keyup(function () {
                if ($('#user_name_en').val().length < 3) {
                    $('#user_name_en').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_en').removeClass('is-invalid')
                    $('#create_user').removeAttr('disabled');
                }
            });


            $('#user_email').keyup(function () {
                if (!validEmail($('#user_email').val())) {
                    $('#user_email').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_email').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });


            $('#user_mobile').keyup(function () {
                if ($('#user_mobile').val().length < 11) {
                    $('#user_mobile').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_mobile').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_code').keyup(function () {
                if ($('#user_code').val().length < 3) {
                    $('#user_code').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_code').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_password').keyup(function () {
                if ($('#user_password').val().length < 6) {
                    $('#user_password').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_password').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_start_date').change(function () {
                $('#user_start_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            $('#user_end_date').change(function () {
                $('#user_end_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            $('#company_group_id').change(function () {
                if (!$('#company_group_id').val()) {
                    $('#company_group_id').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#company_group_id').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#company_id').change(function () {
                if (!$('#company_id').val()) {
                    $('#company_id').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#company_id').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            function validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        })
    </script>
@endsection

