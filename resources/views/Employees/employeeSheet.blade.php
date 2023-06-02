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

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
            <div class="card">
                <div class="card-header">
                    <div class="card-options">
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
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
                                            <img class="avatar avatar-blue" src="{{ $employee->emp_photo_url }}">
                                        </div>
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
                                    <td>{{ $employee->emp_work_start_date }}</td>
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
                                            <a class="btn btn-icon"
                                               href="{{route('employees.edit',$employee->emp_id)}}"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-eye"></i>
                                            </a>
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

@endsection