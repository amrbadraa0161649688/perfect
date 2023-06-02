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
            <div class="card">
                <div class="card-body">
                    <div class="card-header">


                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#exampleModal">

                                <a href="{{route('employee-requests-create')}}" class="btn btn-primary">
                                    <i class="fe fe-plus mr-2"></i>@lang('home.add_new_request')
                                </a>
                            </button>
                        </div>

                        <div hidden class="col-md-4">
                            @if(session('company_group'))
                                <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                {{ session('company_group')['company_group_ar'] }} @else
                                {{ session('company_group')['company_group_en']}} @endif" readonly>
                            @else
                                <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                {{ auth()->user()->companyGroup->company_group_ar }} @else
                                {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                            @endif
                        </div>


                    </div>

                    <div class="card-body">

                        <form action="">

                            <div class="row">
                                {{--رقم الطلب--}}
                                <div class="col-md-4">
                                    <label>@lang('home.request_code')</label>
                                    <input type="text" name="emp_request_code" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.request_type')</label>
                                    <select name="emp_request_type_id[]" class="selectpicker" multiple
                                            data-live-search="true" required>
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($request_types as $request_type)
                                            <option value="{{ $request_type->system_code_id }}"
                                                    @if(request()->emp_request_type_id)
                                                    @foreach(request()->emp_request_type_id as $type_id)
                                                    @if($request_type->system_code_id == $type_id) selected @endif
                                                    @endforeach
                                                    @endif
                                            >{{app()->getLocale() == 'ar' ?
                                 $request_type->system_code_name_ar : $request_type->system_code_name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.employee')</label>
                                    <select name="emp_id[]" class="selectpicker" multiple data-live-search="true">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->emp_id }}"
                                                    @if(request()->emp_id)
                                                    @foreach(request()->emp_id as $emp_id)
                                                    @if($employee->emp_id == $emp_id) selected @endif
                                                    @endforeach
                                                    @endif
                                            >{{app()->getLocale() == 'ar' ?
                                 $employee->emp_name_full_ar : $employee->emp_name_full_en}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <label>@lang('home.sub_employee')</label>
                                    <select name="sub_emp_id[]" class="selectpicker" multiple data-live-search="true">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->emp_id }}"
                                                    @if(request()->sub_emp_id)
                                                    @foreach(request()->sub_emp_id as $sub_emp_id)
                                                    @if($employee->emp_id == $sub_emp_id) selected @endif
                                                    @endforeach
                                                    @endif
                                            >{{app()->getLocale() == 'ar' ?
                                 $employee->emp_name_full_ar : $employee->emp_name_full_en}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-md-4">
                                    <label>@lang('home.request_start_date')</label>
                                    <input type="date" name="emp_request_start_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label>@lang('home.request_end_date')</label>
                                    <input type="date" name="emp_request_end_date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary mt-4">
                                        @lang('home.search')
                                        <i class="fa fa-search fa-fw"></i>
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>

                </div>
            </div>

            <div class="card">

                <div class="card-body">

                    <div class="row clearfix">
                        <div class="col-md-12">

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                        <thead style="background-color: #ece5e7">
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.request_code')</th>
                                            <th>@lang('home.request_type')</th>
                                            <th>@lang('home.employee_name')</th>
                                            <th>@lang('home.request_date')</th>
                                            <th>@lang('home.status')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $employee_requests as $k=>$employee_request)
                                            <tr>
                                                <td>{{$k +1}}
                                                    '====>' {{ $employee_request->requestType->system_code }}</td>
                                                <td>{{$employee_request->emp_request_code }}</td>
                                                <td>
                                                    @if($employee_request->requestType)
                                                        {{app()->getLocale() == 'ar' ? $employee_request->requestType->system_code_name_ar
                                             : $employee_request->requestType->system_code_name_en}}
                                                    @endif</td>
                                                <td>{{app()->getLocale() == 'ar' ? $employee_request->employee->emp_name_full_ar
                                        : $employee_request->employee->emp_name_full_en}}</td>
                                                <td>{{$employee_request->emp_request_date}}</td>
                                                <td>
                                                    @if($employee_request->emp_request_status == 2)
                                                        تحت التنفيذ
                                                    @elseif($employee_request->emp_request_status == 0)
                                                        لم يتم الموافقه علي الطلب
                                                    @elseif($employee_request->emp_request_status == 1)
                                                        تم الموافقه علي الطلب
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($employee_request->requestType->system_code == 504)
                                                        <a class="btn btn-primary btn-sm"
                                                           href="{{route('employee.direct.request.edit' ,
                                                     $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46004)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-medical-insurance' ,
                                                     $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46005)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-hand-over' ,
                                                     $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46009)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-panel-action',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46006)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-ancestors-request',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                    @elseif($employee_request->requestType->system_code == 46010)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-stop-working-request',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46007)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-job-assignment-request',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46008)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-job-leave-request',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46003)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-evaluation-request',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                    @elseif($employee_request->requestType->system_code == 46011)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-resignation-request',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @elseif($employee_request->requestType->system_code == 46012)
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit-reckoning-request',
                                                            $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                    @else
                                                        <a class="btn btn-primary btn-sm" href="{{route('employee-requests-edit' ,
                                                           $employee_request->emp_request_id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endif


                                                    <form action="{{ route('employee-requests-delete',$employee_request->emp_request_id) }}"
                                                          method="post" class="d-inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>

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

        </div>
    </div>

@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection
