@extends('Layouts.master')
@section('content')

    <form action="{{route('employee-requests-update-evaluation-request',$employee_request->emp_request_id)}}"
          method="post"
          id="">
        @csrf
        @method('put')

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">

        {{---------------employee evaluation تقييم الموظف---------------------------------------}}
        <div id="employee-evaluation-form">

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
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.evaluation_type')</label>
                                    <input type="text" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                 $employee_request->employeeEvaluation->first()->itemType->system_code_name_ar :
                                  $employee_request->employeeEvaluation->first()->itemType->system_code_name_ar}}"
                                           readonly>
                                </div>
                            </div>

                        </div>

                        {{--تقييم لمقابله شخصيه--}}
                        @if($employee_request->employeeEvaluation->first()->itemType->system_code == 119001)
                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.employee')</label>
                                    <div class="form-group">

                                        <input type="text" class="form-control" readonly
                                               value="{{ app()->getLocale() == 'ar' ? $employee_request->employee->emp_name_full_ar :
                                                     $employee_request->employee->emp_name_full_en  }}">
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.nationality')</label>
                                    <div class="form-group">

                                        <input type="text" : value="{{ app()->getLocale() == 'ar' ?
                                            $employee_request->employee->nationality->system_code_name_ar :
                                                    $employee_request->employee->nationality->system_code_name_en }}"
                                               class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.job')</label>
                                    <div class="form-group">
                                        <select name="item_emp_job" class="form-control" required
                                                @if($employee_request->emp_request_status != 2) disabled @endif>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->jobs as $job)
                                                <option value="{{ $job->job_id }}"
                                                        @if($employee_request->employeeEvaluation->first()->item_emp_job == $job->job_id)
                                                        selected @endif>
                                                    {{ app()->getLocale()=='ar' ? $job->job_name_ar :
                                                    $job->job_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.division')</label>
                                    <div class="form-group">
                                        <select name="item_emp_division" class="form-control" required
                                                @if($employee_request->emp_request_status != 2) disabled @endif>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->divisions as $division)
                                                <option value="{{ $division->division_id }}"
                                                        @if($employee_request->employeeEvaluation->first()->item_emp_division
                                                         == $division->division_id) selected @endif>
                                                    {{ app()->getLocale()=='ar' ? $division->division_name_ar :
                                                    $division->division_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.certificates')</label>
                                    <div class="form-group">
                                        <input type="text" name="item_emp_certificate" class="form-control" required
                                               value="{{$employee_request->employeeEvaluation->first()->item_emp_certificate}}"
                                               @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>

                                {{--جدول التقييمات--}}
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.evaluation_field')</th>
                                            <th>@lang('home.excellent')</th>
                                            <th>@lang('home.good')</th>
                                            <th>@lang('home.med')</th>
                                            <th>@lang('home.poor')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employee_request->employeeEvaluation as $k=>$interview_evaluation)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="item_evaluation[]"
                                                           value="{{ $interview_evaluation->emp_request_dt_id }}">
                                                    {{ app()->getLocale()=='ar' ? $interview_evaluation->itemEvaluation->system_code_name_ar :
                                                 $interview_evaluation->system_code_name_en}}
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_excellent[{{$k}}]">
                                                    <input type="checkbox" name="item_excellent[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_excellent == 1) checked
                                                           @endif
                                                           onclick="checkEvaluation({{$interview_evaluation->itemEvaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                           @if($employee_request->emp_request_status != 2) disabled @endif>
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_good[{{$k}}]">
                                                    <input type="checkbox" name="item_good[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_good == 1) checked
                                                           @endif
                                                           onclick="checkEvaluation({{$interview_evaluation->itemEvaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                           @if($employee_request->emp_request_status != 2) disabled @endif>
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_middle[{{$k}}]">
                                                    <input type="checkbox" name="item_middle[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_middle == 1) checked
                                                           @endif
                                                           onclick="checkEvaluation({{$interview_evaluation->itemEvaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                           @if($employee_request->emp_request_status != 2) disabled @endif>
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_weak[{{$k}}]">
                                                    <input type="checkbox" name="item_weak[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_weak == 1) checked
                                                           @endif
                                                           onclick="checkEvaluation({{$interview_evaluation->itemEvaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                           @if($employee_request->emp_request_status != 2) disabled @endif>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>@lang('home.is_it_possible_to_continue_in_work')</p>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <div class="col-md-6">
                                        <label>@lang('home.yes')</label>
                                        <input type="radio" value="1" name="item_result" @if($employee_request->employeeEvaluation->first()
                                        ->item_result == 1) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_result" @if($employee_request->employeeEvaluation->first()
                                        ->item_result == 0) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>@lang('home.job_recommendation')</p>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <div class="col-md-6">
                                        <label>@lang('home.yes')</label>
                                        <input type="radio" value="1" name="item_recommendation" @if($employee_request->employeeEvaluation->first()
                                        ->item_recommendation == 1) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation" @if($employee_request->employeeEvaluation->first()
                                        ->item_recommendation == 0) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>
                            </div>

                        @endif

                        @if($employee_request->employeeEvaluation->first()->itemType->system_code == 119002)

                            {{--تقييم تحت التجربه--}}

                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.employee')</label>
                                    <div class="form-group">

                                        <input type="text" class="form-control" readonly
                                               value="{{ app()->getLocale() == 'ar' ? $employee_request->employee->emp_name_full_ar :
                                                     $employee_request->employee->emp_name_full_en  }}"
                                               @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.nationality')</label>
                                    <div class="form-group">

                                        <input type="text" : value="{{ app()->getLocale() == 'ar' ?
                                            $employee_request->employee->nationality->system_code_name_ar :
                                                    $employee_request->employee->nationality->system_code_name_en }}"
                                               class="form-control" readonly @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.job')</label>
                                    <div class="form-group">
                                        <select name="item_emp_job" class="form-control" required
                                                @if($employee_request->emp_request_status != 2) disabled @endif>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->jobs as $job)
                                                <option value="{{ $job->job_id }}"
                                                        @if($employee_request->employeeEvaluation->first()->item_emp_job == $job->job_id)
                                                        selected @endif>
                                                    {{ app()->getLocale()=='ar' ? $job->job_name_ar :
                                                    $job->job_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.division')</label>
                                    <div class="form-group">
                                        <select name="item_emp_division" class="form-control" required
                                                @if($employee_request->emp_request_status != 2) disabled @endif>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->divisions as $division)
                                                <option value="{{ $division->division_id }}"
                                                        @if($employee_request->employeeEvaluation->first()->item_emp_division
                                                         == $division->division_id) selected @endif>
                                                    {{ app()->getLocale()=='ar' ? $division->division_name_ar :
                                                    $division->division_name_en}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.certificates')</label>
                                    <div class="form-group">
                                        <input type="text" name="item_emp_certificate" class="form-control" required
                                               value="{{$employee_request->employeeEvaluation->first()->item_emp_certificate}}"
                                               @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>

                            </div>

                            {{--جدول التقييمات--}}
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.evaluation_field')</th>
                                        <th>@lang('home.excellent')
                                            <span>10</span>
                                        </th>
                                        <th>@lang('home.very_good')
                                            <span>8</span>
                                        </th>
                                        <th>@lang('home.good')
                                            <span>6</span>
                                        </th>
                                        <th>@lang('home.med')
                                            <span>4</span>
                                        </th>
                                        <th>@lang('home.poor')
                                            2
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employee_request->employeeEvaluation as $interview_evaluation)
                                        <tr>

                                            <td>
                                                <input type="hidden" name="item_evaluation[]"
                                                       value="{{ $interview_evaluation->emp_request_dt_id }}">
                                                {{ app()->getLocale()=='ar' ? $interview_evaluation->itemEvaluation->system_code_name_ar :
                                             $interview_evaluation->system_code_name_en}}
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_excellent[{{$k}}]">
                                                <input type="checkbox" name="item_excellent[{{$k}}]" value="1"
                                                       @if($interview_evaluation->item_excellent == 1) checked
                                                       @endif
                                                       onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                       ->system_code_id}})"
                                                       class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                       @if($employee_request->emp_request_status != 2) disabled @endif>
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_very_good[{{$k}}]">
                                                <input type="checkbox" name="item_very_good[{{$k}}]" value="1"
                                                       @if($interview_evaluation->item_good == 1) checked
                                                       @endif
                                                       onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                       ->system_code_id}})"
                                                       class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                       @if($employee_request->emp_request_status != 2) disabled @endif>
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_good[{{$k}}]">
                                                <input type="checkbox" name="item_good[{{$k}}]" value="1"
                                                       @if($interview_evaluation->item_good == 1) checked
                                                       @endif
                                                       onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                       ->system_code_id}})"
                                                       class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                       @if($employee_request->emp_request_status != 2) disabled @endif>
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_middle[{{$k}}]">
                                                <input type="checkbox" name="item_middle[{{$k}}]" value="1"
                                                       @if($interview_evaluation->item_middle == 1) checked
                                                       @endif
                                                       onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                       ->system_code_id}})"
                                                       class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                       @if($employee_request->emp_request_status != 2) disabled @endif>
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_weak[{{$k}}]">
                                                <input type="checkbox" name="item_weak[{{$k}}]" value="1"
                                                       @if($interview_evaluation->item_weak == 1) checked
                                                       @endif
                                                       onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                       ->system_code_id}})"
                                                       class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}"
                                                       @if($employee_request->emp_request_status != 2) disabled @endif>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>@lang('home.manager_recommendation')</p>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <div class="col-md-6">
                                        <label>@lang('home.yes')</label>
                                        <input type="radio" value="1" name="item_recommendation" @if($employee_request->employeeEvaluation->first()
                                        ->item_recommendation == 1) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation"
                                               @if($employee_request->employeeEvaluation->first()
                                               ->item_recommendation == 0) checked @endif
                                               @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>@lang('home.employee_recommendation')</p>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <div class="col-md-6">
                                        <label>@lang('home.yes')</label>
                                        <input type="radio" value="1" name="item_recommendation_hr"
                                               @if($employee_request->employeeEvaluation->first()
                                               ->item_recommendation_hr == 1) checked @endif
                                               @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation_hr"
                                               @if($employee_request->employeeEvaluation->first()
                                                    ->item_recommendation_hr == 0) checked @endif
                                               @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>
                            </div>


                        @endif

                        @if($employee_request->employeeEvaluation->first()->itemType->system_code == 119003)

                            {{--تقييم موظف--}}
                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.employee')</label>

                                    <div class="form-group multiselect_div">
                                        <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                $employee_request->employee->emp_name_full_ar :
                                                 $employee_request->employee->emp_name_full_en}}" readonly>
                                    </div>

                                </div>

                            </div>

                            {{--بيانات الموظف--}}
                            <x-employees.employee-data
                                    :employeeRequest="$employee_request">

                            </x-employees.employee-data>

                            <div class="row clearfix">
                                {{--جدول التقييمات--}}
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>@lang('home.evaluation_field')</th>
                                            <th>@lang('home.excellent')
                                                <span>10</span>
                                            </th>
                                            <th>@lang('home.very_good')
                                                <span>8</span>
                                            </th>
                                            <th>@lang('home.good')
                                                <span>6</span>
                                            </th>
                                            <th>@lang('home.med')
                                                <span>4</span>
                                            </th>
                                            <th>@lang('home.poor')
                                                2
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($employee_request->employeeEvaluation as $k=>$interview_evaluation)
                                            <tr>

                                                <td>
                                                    <input type="hidden" name="item_evaluation[]"
                                                           value="{{ $interview_evaluation->emp_request_dt_id }}">
                                                    {{ app()->getLocale()=='ar' ? $interview_evaluation->itemEvaluation->system_code_name_ar :
                                                 $interview_evaluation->system_code_name_en}}
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_excellent[{{$k}}]">
                                                    <input type="checkbox" name="item_excellent[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_excellent == 1) checked
                                                           @endif
                                                           @if($employee_request->emp_request_status != 2) disabled @endif
                                                           onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                           ->system_code_id}})"
                                                           class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_very_good[{{$k}}]">
                                                    <input type="checkbox" name="item_very_good[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_very_good == 1) checked
                                                           @endif
                                                           @if($employee_request->emp_request_status != 2) disabled @endif
                                                           onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                           ->system_code_id}})"
                                                           class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_good[{{$k}}]">
                                                    <input type="checkbox" name="item_good[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_good == 1) checked
                                                           @endif
                                                           @if($employee_request->emp_request_status != 2) disabled @endif
                                                           onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                           ->system_code_id}})"
                                                           class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_middle[{{$k}}]">
                                                    <input type="checkbox" name="item_middle[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_middle == 1) checked
                                                           @endif
                                                           @if($employee_request->emp_request_status != 2) disabled @endif
                                                           onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                           ->system_code_id}})"
                                                           class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_weak[{{$k}}]">
                                                    <input type="checkbox" name="item_weak[{{$k}}]" value="1"
                                                           @if($interview_evaluation->item_weak == 1) checked
                                                           @endif
                                                           @if($employee_request->emp_request_status != 2) disabled @endif
                                                           onclick="checkEmployeeEvaluation({{$interview_evaluation->itemEvaluation
                                                           ->system_code_id}})"
                                                           class="sev_check2{{$interview_evaluation->itemEvaluation->system_code_id}}">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>@lang('home.manager_recommendation')</p>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <div class="col-md-6">
                                        <label>@lang('home.yes')</label>
                                        <input type="radio" value="1" name="item_recommendation" @if($employee_request->employeeEvaluation->first()
                                               ->item_recommendation == 1) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation" @if($employee_request->employeeEvaluation->first()
                                               ->item_recommendation == 0) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <p>@lang('home.employee_recommendation')</p>
                                </div>

                                <div class="col-md-6 d-flex">
                                    <div class="col-md-6">
                                        <label>@lang('home.yes')</label>
                                        <input type="radio" value="1" name="item_recommendation_hr" @if($employee_request->employeeEvaluation->first()
                                               ->item_recommendation_hr == 1) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation_hr" @if($employee_request->employeeEvaluation->first()
                                               ->item_recommendation_hr == 0) checked @endif
                                        @if($employee_request->emp_request_status != 2) disabled @endif>
                                    </div>
                                </div>
                            </div>


                        @endif

                        {{--الموافقات--}}
                        <div class="row">
                            <div class="card-body demo-card">
                                <div class="row clearfix">

                                    <div class="col-md-6">
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
                                                    <input class="form-control" value="@lang('home.not_accept') "
                                                           readonly>
                                                @endif
                                            @endif
                                        </div>
                                    </div>


                                    <div class="col-md-6">
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

                                </div>


                                <div class="row clearfix">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('home.reason_for_rejection_or_approval_of_the_direct_manager')</label>
                                            <textarea class="form-control" name="manager_notes" required
                                                      @if($employee_request->emp_request_approved != 2) readonly @endif>
                                            {{ $employee_request->employeeEvaluation->first()->manager_notes ?
                                             $employee_request->employeeEvaluation->first()->manager_notes
                                                  :''}}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('home.reason_for_rejection_or_approval_of_the_hr')</label>
                                            <textarea class="form-control" name="hr_notes" required
                                                      @if($employee_request->emp_request_hr_approver != 2) readonly @endif>
                                            {{ $employee_request->employeeEvaluation->first()->hr_notes ?
                                             $employee_request->employeeEvaluation->first()->hr_notes
                                                  :''}}</textarea>
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
        </div>
    </form>
@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script>

        function checkEvaluation(id) {
            $('.sev_check' + id).click(function () {
                $('.sev_check' + id).not(this).prop('checked', false);
            });
        }

        function checkEmployeeEvaluation(id) {
            $('.sev_check2' + id).click(function () {
                $('.sev_check2' + id).not(this).prop('checked', false);
            });
        }
    </script>
@endsection