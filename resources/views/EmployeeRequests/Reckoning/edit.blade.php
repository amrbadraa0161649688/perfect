@extends('Layouts.master')
@section('content')

    <form action="{{route('employee-requests-update-reckoning-request',$employee_request->emp_request_id)}}"
          method="post">
        @csrf
        @method('put')


        {{----------------تصفيه حساب--------------------------------------}}
        <div>

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>


                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.request_code')</label>
                                <div class="form-group">

                                    <input type="text" class="form-control"
                                           name="emp_request_code"
                                           value="{{$employee_request->emp_request_code}}" readonly>

                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.user_name')</label>
                                <div class="form-group multiselect_div">
                                    <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                $employee_request->user->user_name_ar : $employee_request->user->user_name_en}}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.employee_name')</label>
                                    <div class="form-group multiselect_div">
                                        <div class="form-group multiselect_div">
                                            <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                $employee_request->employee->emp_name_full_ar :
                                                 $employee_request->employee->emp_name_full_en}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>

            {{--بيانات الموظف--}}
            {{--بيانات الموظف--}}
            <x-employees.employee-data
                    :employeeRequest="$employee_request">
            </x-employees.employee-data>

            <div class="card">

                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">


                            @if(isset($last_vacation))
                                <div class="col-md-3">
                                    <label>@lang('home.vacation_start_date')</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" readonly
                                               value="{{ $last_vacation->emp_request_start_date }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label>@lang('home.vacation_end_date')</label>
                                    <div class="form-group">
                                        <input class="form-control" type="text" readonly
                                               value="{{ $last_vacation->emp_request_end_date }}">
                                    </div>

                                </div>

                            @else
                                <div class="col-md-6">
                                    <p class="alert alert-danger" style="margin-top: 25px">
                                        لا يوجد اجازات سابقه للموظف
                                    </p>
                                </div>
                            @endif

                            <div class="col-md-2">
                                <label>@lang('home.days')</label>
                                <div class="form-group">
                                    <input class="form-control" type="number" readonly value="{{$answer_in_days->d}}">
                                </div>

                            </div>

                            <div class="col-md-2">
                                <label>@lang('home.months')</label>
                                <div class="form-group">
                                    <input class="form-control" type="number" value="{{$answer_in_days->m}}" readonly>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label>@lang('home.years')</label>
                                <div class="form-group">
                                    <input class="form-control" type="number" value="{{$answer_in_days->y}}" readonly>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            @foreach($items as $item)
                                <table class="table table-bordered">

                                    <thead class="thead-light table-bordered">
                                    <tr>
                                        <th>@lang('home.item_data')</th>
                                        <th>@lang('home.item_status')</th>
                                        <th>@lang('home.item_qunt')</th>
                                        <th>@lang('home.item_value')</th>
                                        <th>@lang('home.item_notes')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($item->handOverDetails as $item_detail)
                                        <tr>
                                            <td>
                                                {{ app()->getLocale() == 'ar' ?
                                                $item_detail->item->system_code_name_ar :
                                                $item_detail->item->system_code_name_en}}
                                            </td>
                                            <td>
                                                {{ app()->getLocale() == 'ar' ?
                                                $item_detail->status->system_code_name_ar :
                                                $item_detail->status->system_code_name_en}}
                                            </td>

                                            <td>{{ $item_detail->item_qunt}}</td>
                                            <td>{{ $item_detail->item_value}}</td>
                                            <td>{{ $item_detail->item_notes}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endforeach
                            <hr>
                        </div>
                    </div>


                </div>

                <div class="card">

                    {{--الموافقات--}}
                    <div class="row">
                        <div class="card-body demo-card">
                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('home.manager_accept')</label>
                                        @if($employee_request->emp_request_approved == 2)
                                            <select class="form-control" name="emp_request_approved" required>
                                                <option value="">@lang('home.choose')</option>
                                                <option value="1">@lang('home.accept')</option>
                                                <option value="0">@lang('home.not_accept')</option>
                                            </select>
                                        @else
                                            @if($employee_request->emp_request_approved == 1)
                                                <input class="form-control" value="@lang('home.accept')" readonly>
                                            @elseif($employee_request->emp_request_approved == 0)
                                                <input class="form-control" value="@lang('home.not_accept') "
                                                       readonly>
                                            @endif
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> @lang('home.hr_accept')</label>
                                        @if($employee_request->emp_request_hr_approver == 2)
                                            <select class="form-control" name="emp_request_hr_approver" required>
                                                <option value="">@lang('home.choose')</option>
                                                <option value="1">@lang('home.accept')</option>
                                                <option value="0">@lang('home.not_accept')</option>
                                            </select>
                                        @else
                                            @if($employee_request->emp_request_hr_approver == 1)
                                                <input class="form-control" value="@lang('home.accept')" readonly>
                                            @elseif($employee_request->emp_request_hr_approver == 0)
                                                <input class="form-control" value=" @lang('home.not_accept')"
                                                       readonly>
                                            @endif
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('home.ceo_accept')</label>
                                        @if($employee_request->emp_request_status == 2)
                                            <select class="form-control" name="emp_request_status" required>
                                                <option value="">@lang('home.choose')</option>
                                                <option value="1">@lang('home.accept')</option>
                                                <option value="0">@lang('home.not_accept')</option>
                                            </select>
                                        @else
                                            @if($employee_request->emp_request_status == 1)
                                                <input class="form-control" value="@lang('home.accept')" readonly>
                                            @elseif($employee_request->emp_request_status == 0)
                                                <input class="form-control" value="@lang('home.not_accept') "
                                                       readonly>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>


                @if($employee_request->emp_request_status == 2)
                    <div class="row">
                        <button class="btn btn-primary" type="submit">
                            @lang('home.save')</button>
                    </div>
                @endif

            </div>
        </div>


        </div>
    </form>

@endsection