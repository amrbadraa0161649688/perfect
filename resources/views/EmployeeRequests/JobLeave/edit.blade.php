@extends('Layouts.master')

@section('content')

    {{--اخلاء طرف--}}
    <form action="{{ route('employee-requests-update-job-leave-request',$employee_request->emp_request_id) }}"
          method="post" id="job_leave_request">
        @csrf
        @method('put')


        <div id="job-leave-form">

            {{--بيانات الطلب--}}
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>


                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">

                            <div class="col-lg-3 col-md-12">
                                <label>@lang('home.request_date')</label>
                                <div class="form-group">

                                    <input type="text" class="form-control"
                                           value="{{$employee_request->created_date}}"
                                           readonly>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-12">
                                <label>@lang('home.request_code')</label>
                                <div class="form-group">

                                    <input type="text" class="form-control"
                                           name="emp_request_code"
                                           value="{{$employee_request->emp_request_code}}" readonly>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-12">
                                <label>@lang('home.user_name')</label>
                                <div class="form-group multiselect_div">
                                    <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                $employee_request->user->user_name_ar : $employee_request->user->user_name_en}}"
                                           readonly>
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-12">
                                <label>@lang('home.leave_job_reasons')</label>
                                <select class="form-control" name="item_reasons"
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    <option value="">@lang('home.choose')</option>
                                    @foreach($stop_working_reasons as $stopWorkingReason)
                                        <option value="{{ $stopWorkingReason->system_code_id }}"
                                                @if($employee_request->jobLeaveDetails->first()->item_reasons ==
                                                 $stopWorkingReason->system_code_id) selected @endif>
                                            {{ app()->getLocale() == 'ar' ? $stopWorkingReason->system_code_name_ar :
                                         $stopWorkingReason->system_code_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                {{--بيانات الموظف--}}

                <x-employees.employee-data
                        :employeeRequest="$employee_request">

                </x-employees.employee-data>

                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12">

                        <div class="card">
                            <div class="card-body">
                                <table class="table">

                                    <thead>
                                    <tr>
                                        <th>@lang('home.name')</th>
                                        <th>@lang('home.department')</th>
                                        <th>@lang('home.in_his_custody')</th>
                                        <th>@lang('home.not_in_his_custody')</th>
                                        <th>@lang('home.notes')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($employee_request->jobLeaveDetails as $job_detail)
                                        <tr>
                                            <td>
                                                <input type="hidden"
                                                       value="{{ $job_detail->emp_request_dt_id }}"
                                                       name="item_id[]">
                                                {{app()->getLocale()=='ar' ? $job_detail->itemLeaveWork->system_code_name_ar :
                                                    $job_detail->itemLeaveWork->system_code_name_en }}
                                            </td>

                                            <td>{{app()->getLocale()=='ar' ? $job_detail->itemLeaveWork->system_code_search :
                                            $job_detail->itemLeaveWork->system_code_search }}</td>

                                            <td>
                                                <label>@lang('home.in_his_custody')</label>
                                                <input type="checkbox" name="item_status[]"
                                                       value="1"
                                                       class="subject-list{{$job_detail->itemLeaveWork->system_code_id}}"
                                                       onclick="chooseItem({{$job_detail->itemLeaveWork->system_code_id}})"
                                                       @if($job_detail->item_status == 1) checked @endif
                                                       @if($employee_request->emp_request_approved != 2) disabled @endif>
                                            </td>
                                            <td>
                                                <label>@lang('home.not_in_his_custody')</label>
                                                <input type="checkbox" name="item_status[]"
                                                       value="0"
                                                       class="subject-list{{$job_detail->itemLeaveWork->system_code_id}}"
                                                       onclick="chooseItem({{$job_detail->itemLeaveWork->system_code_id}})"
                                                       @if($job_detail->item_status == 0) checked @endif
                                                       @if($employee_request->emp_request_approved != 2) disabled @endif>
                                            </td>
                                            <td>
                                                <textarea class="form-control" name="item_notes[]"
                                                          @if($employee_request->emp_request_approved != 2) disabled @endif>
                                                    {{ $job_detail->item_notes ?
                                                    $job_detail->item_notes : '' }}
                                                </textarea>
                                            </td>

                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4">
                                <p>اخلاء الطرف</p>
                            </div>
                            <div class="col-md-4">
                                <label>يخلي طرفه</label>
                                <input type="radio" name="item_result"
                                       @if($employee_request->jobLeaveDetails->first()->item_result == 1)
                                       checked @endif
                                       @if($employee_request->emp_request_approved != 2) disabled @endif>
                            </div>
                            <div class="col-md-4">
                                <label>لا يخلي طرفه</label>
                                <input type="radio" name="item_result"
                                       @if($employee_request->jobLeaveDetails->first()->item_result == 0)
                                       checked @endif
                                       @if($employee_request->emp_request_approved != 2) disabled @endif>
                            </div>
                        </div>
                    </div>
                </div>

                {{--الموافقات--}}
                <div class="card">
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
                                                <input class="form-control" value="@lang('home.not_accept') " readonly>
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
                                                <input class="form-control" value=" @lang('home.not_accept')" readonly>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>  @lang('home.ceo_accept')</label>
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
                                                <input class="form-control" value="@lang('home.not_accept')" readonly>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>


                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('home.reason_for_rejection_or_approval_of_the_direct_manager')</label>
                                        <textarea class="form-control" name="manager_notes" required
                                                  @if($employee_request->emp_request_approved != 2) readonly @endif>
                                            {{ $employee_request->jobAssignmentDetails->manager_notes ?
                                             $employee_request->jobAssignmentDetails->manager_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('home.reason_for_rejection_or_approval_of_the_hr')</label>
                                        <textarea class="form-control" name="hr_notes" required
                                                  @if($employee_request->emp_request_hr_approver != 2) readonly @endif>
                                            {{ $employee_request->jobAssignmentDetails->hr_notes ?  $employee_request->jobAssignmentDetails->hr_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('home.reason_for_rejection_or_approval_of_the_cEO')</label>
                                        <textarea class="form-control" name="ceo_notes" required
                                                  @if($employee_request->emp_request_status != 2) readonly @endif>
                                            {{ $employee_request->jobAssignmentDetails->ceo_notes ?
                                             $employee_request->jobAssignmentDetails->ceo_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

                @if($employee_request->emp_request_approved == 2)
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit"
                                id="submit_ancestors">@lang('home.save')</button>
                    </div>
                @endif


            </div>
        </div>
    </form>

@endsection


@section('scripts')

    <script>
        function chooseItem(id) {
            $('.subject-list' + id).on('change', function () {
                $('.subject-list' + id).not(this).prop('checked', false);
            });

        }
    </script>

@endsection