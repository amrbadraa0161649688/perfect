@extends('Layouts.master')
@section('content')

    <form action="{{route('employee-requests-update-resignation-request',$employee_request->emp_request_id)}}"
          method="post">
        @csrf
        @method('put')

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


        {{----------------تقديم استقاله--------------------------------}}
        <div id="resignation-form">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>


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

                    <div class="row clearfix">
                        {{--الملاحظات--}}
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="recipient-name"
                                       class="col-form-label"
                                       style="text-decoration: underline;">@lang('home.notes')</label>
                                <textarea class="form-control" name="emp_request_notes"
                                          placeholder="@lang('home.notes')" required
                                          @if($employee_request->emp_request_status != 2) readonly @endif>
                                    {{ $employee_request->emp_request_notes }}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="recipient-name"
                                       class="col-form-label"
                                       style="text-decoration: underline;">@lang('home.reasons_list')</label>
                                <select class="form-control" name="item_reasons" required
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    <option value="">@lang('home.choose')</option>
                                    @foreach($stop_working_reasons as $item)
                                        <option value="{{ $item->system_code_id }}"
                                                @if($employee_request->resignationDetails->item_reasons ==
                                                $item->system_code_id) selected @endif>
                                            {{ app()->getLocale()=='ar' ? $item->system_code_name_ar
                                            : $item->system_code_name_en }}
                                        </option>
                                    @endforeach
                                </select>
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


            {{--الموافقات--}}

            <div class="card">

                <div class="card-body demo-card">
                    <div class="row clearfix">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('home.manager_accept')</label>
                                @if($employee_request->emp_request_status == 2)
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
                                @if($employee_request->emp_request_status == 2)
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
                                            {{ $employee_request->ancestorsRequestDetails->manager_notes ?
                                             $employee_request->ancestorsRequestDetails->manager_notes
                                                  :''}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('home.reason_for_rejection_or_approval_of_the_hr')</label>
                                <textarea class="form-control" name="hr_notes" required
                                          @if($employee_request->emp_request_hr_approver != 2) readonly @endif>
                                            {{ $employee_request->ancestorsRequestDetails->hr_notes ?  $employee_request->ancestorsRequestDetails->hr_notes
                                                  :''}}</textarea>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('home.reason_for_rejection_or_approval_of_the_cEO')</label>
                                <textarea class="form-control" name="ceo_notes" required
                                          @if($employee_request->emp_request_status != 2) readonly @endif>
                                            {{ $employee_request->ancestorsRequestDetails->ceo_notes ?
                                             $employee_request->ancestorsRequestDetails->ceo_notes
                                                  :''}}</textarea>
                            </div>
                        </div>

                    </div>


                </div>
                @if($employee_request->emp_request_status == 2)
                    <div class="row">
                        <div class="card-body demo-card">
                            <div class="row clearfix">
                                <button class="btn btn-primary" type="submit">@lang('home.save')</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>


        </div>
    </form>

@endsection