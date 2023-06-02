@extends('Layouts.master')

@section('content')

    <form action="{{ route('employee-requests-update-hand-over',$employee_request->emp_request_id) }}" method="post"
          id="hand_over_request">
        @csrf
        @method('put')

        <div id="hand-over-form">

            {{--بيانات الطلب--}}
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>


                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.request_date')</label>
                                <div class="form-group">

                                    <input type="text" class="form-control"
                                           value="{{$employee_request->created_date}}"
                                           readonly>

                                </div>
                            </div>

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

            {{--بيانات الموظف--}}
            <x-employees.employee-data
                    :employeeRequest="$employee_request">

            </x-employees.employee-data>


            {{--تفاصيل العهده--}}
            <div class="card">
                <div class="card-body demo-card">
                    <div class="card-header">
                        @lang('home.hand_over_details')
                    </div>

                    <div class="row clearfix">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.item_data')</th>
                                        <th>@lang('home.item_qunt')</th>
                                        <th>@lang('home.item_value')</th>
                                        <th>@lang('home.item_status')</th>
                                        <th>@lang('home.item_notes')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee_request->requestDetails as $k=>$request_detail)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>
                                                <input type="text" name="item_id" value="{{ app()->getLocale()=='ar' ?
                                                 $request_detail->item->system_code_name_ar :
                                                 $request_detail->item->system_code_name_en}}" class="form-control"
                                                       readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="item_value" class="form-control"
                                                       value="{{$request_detail->item_qunt}}"
                                                       readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="item_qunt" class="form-control"
                                                       value="{{$request_detail->item_value}}"
                                                       readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="item_status" class="form-control"
                                                       value="{{ app()->getLocale()=='ar' ?
                                                 $request_detail->status->system_code_name_ar :
                                                 $request_detail->status->system_code_name_en}}"
                                                       readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="item_notes" class="form-control"
                                                       value="{{$request_detail->item_notes}}"
                                                       readonly>
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <label>موافقه المدير المسؤول</label>
                            <div class="form-group">
                                @if($employee_request->emp_request_status == 2)
                                    <select name="emp_request_status" class="form-control" required>
                                        <option value="">@lang('home.choose')</option>
                                        <option value="0">@lang('home.refuse_request')</option>
                                        <option value="1">@lang('home.accept_request')</option>
                                    </select>
                                @else
                                    <input name="emp_request_approved" class="form-control"
                                           value="@if($employee_request->emp_request_status == 0)
                                           @lang('home.refuse_request')
                                           @elseif($employee_request->emp_request_status == 1)
                                           @lang('home.accept_request')
                                           @endif" readonly>

                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <label>موافقه الموارد البشريه </label>
                            <div class="form-group">
                                @if($employee_request->emp_request_hr_approver == 2)
                                    <select name="emp_request_hr_approver" class="form-control" required>
                                        <option value="">@lang('home.choose')</option>
                                        <option value="0">@lang('home.refuse_request')</option>
                                        <option value="1">@lang('home.accept_request')</option>
                                    </select>
                                @else
                                    <input name="emp_request_hr_approver" class="form-control"
                                           value="@if($employee_request->emp_request_hr_approver == 0)
                                           @lang('home.refuse_request')
                                           @elseif($employee_request->emp_request_hr_approver == 1)
                                           @lang('home.accept_request')
                                           @endif" readonly>

                                @endif
                            </div>
                        </div>
                        @if($employee_request->emp_request_status == 2)
                            <div class="col-lg-6 col-md-6">
                                <button type="submit" class="btn btn-primary" style="margin-top: 25px">
                                    @lang('home.update')</button>
                            </div>
                        @endif
                    </div>

                </div>

            </div>


        </div>

    </form>

@endsection