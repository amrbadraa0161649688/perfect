@extends('Layouts.master')

@section('content')
    <div id="direct-work-form">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">@lang('home.request_data')</h3>
            </div>
            <div class="card-body demo-card">
                <div class="row clearfix">


                    {{--بيانات الأجازه--}}

                    <div class="col-lg-4 col-md-12">
                        {{--//empty--}}
                    </div>

                    <form action="{{ route('employee.direct.request.update',$employee_request->emp_request_id) }}"
                          method="post" class="row">
                        @csrf
                        @method('put')
                        <input type="hidden" name="emp_id" value="{{$employee_request->employee->emp_id}}">
                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.vacation_start_date')</label>
                            <div class="form-group">
                                <input type="date" name="emp_request_start_date" class="form-control"
                                       readonly value="{{$employee_request->emp_request_start_date}}">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.vacation_end_date')</label>
                            <div class="form-group">
                                <input type="date" name="emp_request_end_date" class="form-control"
                                       readonly value="{{$employee_request->emp_request_end_date}}">
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.days_vacation')</label>
                            <div class="form-group">
                                <input type="number" name="emp_request_days"
                                       class="form-control" readonly value="{{ $employee_request->emp_request_days }}">
                            </div>
                        </div>

                        {{--بيانات الموظف--}}
                        <div class="card-header">
                            <h3 class="card-title">@lang('home.employee_data')</h3>
                        </div>

                        <div class="card" id="">
                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.name')</label>

                                            <input class="form-control" type="text"
                                                   value="{{app()->getLocale()=='ar' ?
                                                $employee_request->employee->emp_name_full_ar :
                                                $employee_request->employee->emp_name_full_en }}"
                                                   readonly>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.branch')</label>

                                            <input class="form-control" type="text"
                                                   value="{{app()->getLocale()=='ar' ?
                                                $employee_request->employee->branch->branch_name_ar :
                                                $employee_request->employee->branch->branch_name_en }}"
                                                   readonly>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.job')</label>
                                            @if($employee_request->employee->contractActive)
                                                <input class="form-control" type="text" id=""
                                                       value="{{app()->getLocale()=='ar' ?
                                                    $employee_request->employee->contractActive->job->job_name_ar :
                                                    $employee_request->employee->contractActive->job->job_name_en }}"
                                                       readonly>
                                            @else
                                                <input class="form-control" type="text" value="لا يوجد عقد فعال"
                                                       readonly="">
                                            @endif

                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.division')</label>

                                            @if( $employee_request->employee->contractActive)
                                                <input class="form-control" type="text" id=""
                                                       value="{{app()->getLocale()=='ar' ?
                                                    $employee_request->employee->contractActive->job->division->division_name_en :
                                                    $employee_request->employee->contractActive->job->division->division_name_en }}"
                                                       readonly>
                                            @else
                                                <input class="form-control" type="text" value="لا يوجد عقد فعال"
                                                       readonly="">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.address')</label>
                                            <input class="form-control address" type="text" id=""
                                                   value="{{$employee_request->employee->emp_current_address}}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.job_date')</label>
                                            <input class="form-control" type="date"
                                                   value="{{$employee_request->employee->emp_work_start_date}}"
                                                   readonly>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.work_start_date')</label>
                                            <input class="form-control" type="date"
                                                   name="emp_work_start_date"
                                                   value="{{$employee_request->employee->emp_work_start_date}}"
                                                   readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.employee_balance')</label>
                                            <input class="form-control" type="text"
                                                   name="days_available"
                                                   value="{{$employee_request->employee->emp_vacation_balance}}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.salary')</label>
                                            <input class="form-control" type="text"
                                                   value="{{ $employee_request->employee->basicSalary }}"
                                                   readonly>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.direct_date')</label>
                            <div class="form-group">

                                <input type="date" name="emp_direct_date" class="form-control"
                                       value="{{ $employee_request->emp_direct_date }}"
                                       @if($employee_request->emp_request_status == 0
                                                     || $employee_request->emp_request_status == 1) readonly @endif>

                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>عدد ايام الاجازه الفعلي</label>
                            <div class="form-group">
                                <input type="text" readonly class="form-control"
                                       name="actual_vacation_days"
                                       value="{{ $employee_request->vacation_days }}">
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body demo-card">
                                <div class="row clearfix">
                                    @if($employee_request->employee->manager)
                                        <div class="col-lg-6 col-md-12">
                                            <label>@lang('home.manager')</label>
                                            <div class="form-group">

                                                <input type="text" readonly class="form-control"
                                                       name="manager_id"
                                                       value="{{app()->getLocale()=='ar' ?  $employee_request->employee->manager->emp_name_full_ar :
                                               $employee_request->employee->manager->emp_name_full_en }}">

                                            </div>

                                        </div>
                                    @endif

                                    <div class="col-lg-6 col-md-12">
                                        <label>@lang('home.approved')</label>
                                        <div class="form-group">
                                            @if($employee_request->emp_request_status  == 2 || $employee_request->emp_request_status == 0)
                                                <select name="emp_request_approved" class="form-control" required>
                                                    <option>@lang('home.choose')</option>
                                                    <option value="0"
                                                            @if($employee_request->emp_request_status == 0) selected @endif>
                                                        @lang('home.refuse_request')</option>
                                                    <option value="1">@lang('home.accept_request')</option>
                                                </select>
                                            @else
                                                <input name="emp_request_approved" class="form-control"
                                                       value="@if($employee_request->emp_request_status == 1)
                                                       @lang('home.accept_request')
                                                       @endif" readonly>

                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-md-8">
                                        <label>@lang('home.request_reason')</label>
                                        <div class="form-group">
                                            <textarea class="form-control" name="emp_request_reason"
                                                      @if($employee_request->emp_request_status == 0
                                                      || $employee_request->emp_request_status == 1) readonly @endif></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4">

                                        @if($employee_request->emp_request_status == 2 || $employee_request->emp_request_status == 0)
                                            <button class="btn btn-primary mt-4 mr-3 ml-3" id="submit"
                                                    type="submit">@lang('home.save')</button>
                                            <div class="spinner-border text-primary" role="status"
                                                 style="display: none">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
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
@endsection
