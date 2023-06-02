<div>
    <!-- Do what you can, with what you have, where you are. - Theodore Roosevelt -->


    <form action="{{route('employee-requests-store-evaluation-request')}}" method="post"
          id="employee_evaluation_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


        {{---------------employee evaluation تقييم الموظف---------------------------------------}}
        <div id="employee-evaluation-form" style="display: none">

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
                                           value="{{$stringNumber}}" readonly>

                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.user_name')</label>
                                <div class="form-group multiselect_div">
                                    <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                auth()->user()->user_name_ar : auth()->user()->user_name_en}}" readonly>
                                </div>
                            </div>

                            {{--<div class="col-lg-4 col-md-12">--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="recipient-name"--}}
                            {{--class="col-form-label"--}}
                            {{--style="text-decoration: underline;">@lang('home.employee_name')</label>--}}
                            {{--<div class="form-group multiselect_div">--}}
                            {{--<select class="selectpicker" data-live-search="true"--}}
                            {{--name="emp_id" v-model="emp_id"--}}
                            {{--@change="getEmployee()" required>--}}
                            {{--<option value="">@lang('home.choose')</option>--}}
                            {{--@foreach($employees as $employee)--}}
                            {{--<option value="{{ $employee->emp_id }}">{{ app()->getLocale()=='ar' ?--}}
                            {{--$employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.evaluation_type')</label>
                                    <select class="form-control" name="item_type" @change="getEvaluationForm()"
                                            v-model="evaluation_type">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($employeeEvaluationTypes as $employee_evaluation_type)
                                            <option value="{{$employee_evaluation_type->system_code}}">
                                                {{ app()->getLocale() == 'ar' ?
                                             $employee_evaluation_type->system_code_name_ar :
                                             $employee_evaluation_type->system_code_name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        {{--تقييم لمقابله شخصيه--}}
                        <div v-if="evaluate_show1">
                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.employee')</label>
                                    <div class="form-group">
                                        <select name="emp_id" class="form-control" v-model="per_emp_id"
                                                @change="getPerEmployee()" required>
                                            <option>@lang('home.choose')</option>
                                            @foreach($perEmployees as $per_employee)
                                                <option value="{{ $per_employee->emp_id }}">
                                                    {{app()->getLocale() == 'ar' ? $per_employee->emp_name_full_ar :
                                                     $per_employee->emp_name_full_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.name')</label>
                                    <div class="form-group">
                                        @if(app()->getLocale()=='ar')
                                            <input type="text" :value="per_employee.emp_name_full_ar"
                                                   class="form-control" readonly>
                                        @else
                                            <input type="text" :value="per_employee.emp_name_full_en"
                                                   class="form-control" readonly>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.nationality')</label>
                                    <div class="form-group">
                                        @if(app()->getLocale()=='ar')
                                            <input type="text" :value="per_employee.nationality_name_ar"
                                                   class="form-control" readonly>
                                        @else
                                            <input type="text" :value="per_employee.nationality_name_en"
                                                   class="form-control" readonly>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.job')</label>
                                    <div class="form-group">
                                        <select name="item_emp_job" class="form-control" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->jobs as $job)
                                                <option value="{{ $job->job_id }}">
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
                                        <select name="item_emp_division" class="form-control" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->divisions as $division)
                                                <option value="{{ $division->division_id }}">
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
                                        <input type="text" name="item_emp_certificate" class="form-control" required>
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
                                        @foreach($interviewEvaluations as $k=>$interview_evaluation)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="item_evaluation[]"
                                                           value="{{ $interview_evaluation->system_code_id }}">
                                                    {{ app()->getLocale()=='ar' ? $interview_evaluation->system_code_name_ar :
                                                 $interview_evaluation->system_code_name_en}}
                                                </td>

                                                <td>
                                                    <input type="hidden" value="0" name="item_excellent[{{$k}}]">
                                                    <input type="checkbox" name="item_excellent[{{$k}}]" value="1"
                                                           onclick="checkEvaluation({{$interview_evaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_good[{{$k}}]">
                                                    <input type="checkbox" name="item_good[{{$k}}]" value="1"
                                                           onclick="checkEvaluation({{$interview_evaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_middle[{{$k}}]">
                                                    <input type="checkbox" name="item_middle[{{$k}}]" value="1"
                                                           onclick="checkEvaluation({{$interview_evaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_weak[{{$k}}]">
                                                    <input type="checkbox" name="item_weak[{{$k}}]" value="1"
                                                           onclick="checkEvaluation({{$interview_evaluation->system_code_id}})"
                                                           class="sev_check{{$interview_evaluation->system_code_id}}">
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
                                        <input type="radio" value="1" name="item_result">
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_result">
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
                                        <input type="radio" value="1" name="item_recommendation">
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation">
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{--تقييم تحت التجربه--}}
                        <div v-if="evaluate_show2">
                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.employee')</label>
                                    <div class="form-group">
                                        <select name="emp_id" class="form-control" v-model="per_emp_id"
                                                @change="getPerEmployee()" required>
                                            <option>@lang('home.choose')</option>
                                            @foreach($perEmployees as $per_employee)
                                                <option value="{{ $per_employee->emp_id }}">
                                                    {{app()->getLocale() == 'ar' ? $per_employee->emp_name_full_ar :
                                                     $per_employee->emp_name_full_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.name')</label>
                                    <div class="form-group">
                                        @if(app()->getLocale()=='ar')
                                            <input type="text" :value="per_employee.emp_name_full_ar"
                                                   class="form-control" readonly>
                                        @else
                                            <input type="text" :value="per_employee.emp_name_full_en"
                                                   class="form-control" readonly>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.nationality')</label>
                                    <div class="form-group">
                                        @if(app()->getLocale()=='ar')
                                            <input type="text" :value="per_employee.nationality_name_ar"
                                                   class="form-control" readonly>
                                        @else
                                            <input type="text" :value="per_employee.nationality_name_en"
                                                   class="form-control" readonly>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.job')</label>
                                    <div class="form-group">
                                        <select name="item_emp_job" class="form-control" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->jobs as $job)
                                                <option value="{{ $job->job_id }}">
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
                                        <select name="item_emp_division" class="form-control" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($company->divisions as $division)
                                                <option value="{{ $division->division_id }}">
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
                                        <input type="text" name="item_emp_certificate" class="form-control" required>
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
                                    @foreach($employeeEvaluations as $employee_evaluation)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="item_evaluation[]"
                                                       value="{{ $employee_evaluation->system_code_id }}">
                                                {{ app()->getLocale()=='ar' ? $employee_evaluation->system_code_name_ar :
                                             $employee_evaluation->system_code_name_en}}
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_excellent[{{$k}}]">
                                                <input type="checkbox" name="item_excellent[{{$k}}]" value="1"
                                                       onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                       @click="calculateTotal(10,$event)"
                                                       class="sev_check2{{$employee_evaluation->system_code_id}}">
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_very_good[{{$k}}]">
                                                <input type="checkbox" name="item_very_good[{{$k}}]" value="1"
                                                       onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                       @click="calculateTotal(8,$event)"
                                                       class="sev_check2{{$employee_evaluation->system_code_id}}">
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_good[{{$k}}]">
                                                <input type="checkbox" name="item_good[{{$k}}]" value="1"
                                                       onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                       @click="calculateTotal(6,$event)"
                                                       class="sev_check2{{$employee_evaluation->system_code_id}}">
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_middle[{{$k}}]">
                                                <input type="checkbox" name="item_middle[{{$k}}]" value="1"
                                                       onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                       @click="calculateTotal(4,$event)"
                                                       class="sev_check2{{$employee_evaluation->system_code_id}}">
                                            </td>
                                            <td>
                                                <input type="hidden" value="0" name="item_weak[{{$k}}]">
                                                <input type="checkbox" name="item_weak[{{$k}}]" value="1"
                                                       onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                       @click="calculateTotal(2,$event)"
                                                       class="sev_check2{{$employee_evaluation->system_code_id}}">
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
                                        <input type="radio" value="1" name="item_recommendation">
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation">
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
                                        <input type="radio" value="1" name="item_recommendation_hr">
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation_hr">
                                    </div>
                                </div>
                            </div>

                            {{--<div class="row">--}}
                            {{--<div class="col-md-6">--}}
                            {{--<p>@lang('home.ceo_recommendation')</p>--}}
                            {{--</div>--}}

                            {{--<div class="col-md-6 d-flex">--}}
                            {{--<div class="col-md-6">--}}
                            {{--<label>@lang('home.yes')</label>--}}
                            {{--<input type="radio" value="0" name="item_recommendation_3">--}}
                            {{--</div>--}}

                            {{--<div class="col-md-6">--}}
                            {{--<label>@lang('home.no')</label>--}}
                            {{--<input type="radio" value="1" name="item_recommendation_3">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                        </div>


                        {{--تقييم موظف--}}
                        <div v-if="evaluate_show3">
                            <div class="row clearfix">

                                <div class="col-md-4">
                                    <label class="col-form-label">@lang('home.employee')</label>
                                    <div class="form-group">
                                        <select name="emp_id" class="form-control" v-model="emp_id"
                                                @change="getEmployee()" required>
                                            <option>@lang('home.choose')</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->emp_id }}">
                                                    {{app()->getLocale() == 'ar' ? $employee->emp_name_full_ar :
                                                     $employee->emp_name_full_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>


                            {{--بيانات الموظف--}}
                            {{$slot}}

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
                                        @foreach($employeeEvaluations as $employee_evaluation)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="item_evaluation[]"
                                                           value="{{ $employee_evaluation->system_code_id }}">

                                                    {{ app()->getLocale()=='ar' ? $employee_evaluation->system_code_name_ar :
                                                 $employee_evaluation->system_code_name_en}}
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_excellent[{{$k}}]">
                                                    <input type="checkbox" name="item_excellent[{{$k}}]" value="1"
                                                           onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                           @click="calculateTotal(10,$event)"
                                                           class="sev_check2{{$employee_evaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_very_good[{{$k}}]">
                                                    <input type="checkbox" name="item_very_good[{{$k}}]" value="1"
                                                           onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                           @click="calculateTotal(8,$event)"
                                                           class="sev_check2{{$employee_evaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_good[{{$k}}]">
                                                    <input type="checkbox" name="item_good[{{$k}}]" value="1"
                                                           onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                           @click="calculateTotal(6,$event)"
                                                           class="sev_check2{{$employee_evaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_middle[{{$k}}]">
                                                    <input type="checkbox" name="item_middle[{{$k}}]" value="1"
                                                           onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                           @click="calculateTotal(4,$event)"
                                                           class="sev_check2{{$employee_evaluation->system_code_id}}">
                                                </td>
                                                <td>
                                                    <input type="hidden" value="0" name="item_weak[{{$k}}]">
                                                    <input type="checkbox" name="item_weak[{{$k}}]" value="1"
                                                           onclick="checkEmployeeEvaluation({{$employee_evaluation->system_code_id}})"
                                                           @click="calculateTotal(2,$event)"
                                                           class="sev_check2{{$employee_evaluation->system_code_id}}">
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
                                        <input type="radio" value="1" name="item_recommendation">
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation">
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
                                        <input type="radio" value="1" name="item_recommendation_hr">
                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('home.no')</label>
                                        <input type="radio" value="0" name="item_recommendation_hr">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                </div>

            </div>

            <div class="row">
                <button class="btn btn-primary" type="submit">
                    @lang('home.save')</button>
            </div>


        </div>

    </form>
</div>