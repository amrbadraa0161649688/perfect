@extends('Layouts.master')

@section('content')
    <div id="app">
        <form action="{{ route('employee-requests-update-ancestors-request',$employee_request->emp_request_id) }}"
              method="post" id="ancestors_request">
            @csrf
            @method('put')
            <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


            {{----------------ancestors request------------------------------------------------------------------------------------}}
            <div id="ancestors-form">

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


                                {{--اجمالي السلفه الجديده--}}
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"
                                               style="text-decoration: underline;">@lang('home.total_ancestors')</label>
                                        <input type="number" name="item_value"
                                               @if($employee_request->emp_request_status != 2) readonly @endif
                                               value="{{ $employee_request->ancestorsRequestDetails->item_value }}"
                                               class="form-control">
                                    </div>
                                </div>

                                {{--موظف كفيل اول--}}
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"
                                               style="text-decoration: underline;">@lang('home.sponsor_first')</label>
                                        <div class="form-group multiselect_div">

                                            <select class="form-control" data-live-search="true"
                                                    name="sponsor_id_1" v-model="emp_id_s1"
                                                    @if($employee_request->emp_request_status != 2) readonly @endif
                                                    @change="getEmployee_s1()" required>
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->emp_id }}"
                                                            @if($employee_request->ancestorsRequestDetails->sponsor_id_1 == $employee->emp_id)
                                                            selected @endif>
                                                        {{ app()->getLocale()=='ar' ?
                                                                        $employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{--موظف كفيل ثاني--}}
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"
                                               style="text-decoration: underline;">@lang('home.sponsor_second')</label>
                                        <div class="form-group multiselect_div">
                                            <select class="form-control" data-live-search="true"
                                                    name="sponsor_id_2" v-model="emp_id_s1"
                                                    @if($employee_request->emp_request_status != 2) readonly @endif
                                                    @change="getEmployee_s2()" required>
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->emp_id }}"
                                                            @if($employee_request->ancestorsRequestDetails->sponsor_id_2 == $employee->emp_id)
                                                            selected @endif>
                                                        {{ app()->getLocale()=='ar' ?
                                                                        $employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                {{--الفتره المحاسبيه--}}
                                {{--<div class="col-lg-4 col-md-12">--}}
                                {{--<div class="form-group">--}}
                                {{--<label for="recipient-name"--}}
                                {{--class="col-form-label"--}}
                                {{--style="text-decoration: underline;">@lang('home.account_periods')</label>--}}
                                {{--<select name="" class="form-control">--}}
                                {{--<option value="">@lang('home.value')</option>--}}
                                {{--@foreach($accountPeriods as $acc_period)--}}
                                {{--<option value="{{ $acc_period->acc_period_id }}">--}}
                                {{--{{ app()->getLocale()=='ar' ? $acc_period->acc_period_name_ar :--}}
                                {{--$acc_period->acc_period_name_en}}--}}
                                {{--</option>--}}
                                {{--@endforeach--}}
                                {{--</select>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                {{--من تاريخ--}}
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"
                                               style="text-decoration: underline;">@lang('home.from')</label>
                                        <input type="date" class="form-control" name="item_start_date"
                                               @if($employee_request->emp_request_status != 2) readonly @endif
                                               value="{{ $employee_request->ancestorsRequestDetails->item_start_date }}">
                                    </div>
                                </div>

                                {{--الي تاريخ--}}
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <label for="recipient-name"
                                               class="col-form-label"
                                               style="text-decoration: underline;">@lang('home.to')</label>
                                        <input type="date" class="form-control" name="item_end_date"
                                               @if($employee_request->emp_request_status != 2) readonly @endif
                                               value="{{ $employee_request->ancestorsRequestDetails->item_start_date }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    {{--بيانات الكفيل الاول--}}
                    <div class="row">
                        <div class="card-body demo-card">
                            <div class="row clearfix">
                                {{--الوظيفه--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.job')</label>
                                        @if(app()->getLocale()=='ar')
                                            <input class="form-control" type="text" id=""
                                                   :value="job_s1.job_name_ar"
                                                   required readonly>
                                        @else
                                            <input class="form-control" type="text" id=""
                                                   :value="job_s1.job_name_ar"
                                                   required readonly>
                                        @endif
                                    </div>
                                </div>

                                {{--تاريخ التعيين--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.work_start_date')</label>
                                        <input class="form-control" type="date"
                                               name="emp_work_start_date"
                                               :value="employee_s1.emp_work_start_date" readonly>
                                    </div>
                                </div>

                                {{--الراتب--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.salary')</label>
                                        <input class="form-control" type="text"
                                               :value="employee_s1.basic_salary"
                                               required readonly>
                                    </div>
                                </div>

                                {{--الحاله--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.social_status')</label>
                                        @if(app()->getLocale()=='ar')
                                            <input class="form-control" type="text"
                                                   :value="social_status_s1.system_code_name_ar"
                                                   required readonly>
                                        @else
                                            <input class="form-control" type="text"
                                                   :value="social_status_s1.system_code_name_en"
                                                   required readonly>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>


                    {{--بيانات الكفيل الثاني--}}
                    <div class="row">
                        <div class="card-body demo-card">
                            <div class="row clearfix">
                                {{--الوظيفه--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.job')</label>
                                        @if(app()->getLocale()=='ar')
                                            <input class="form-control" type="text" id=""
                                                   :value="job_s2.job_name_ar"
                                                   required readonly>
                                        @else
                                            <input class="form-control" type="text" id=""
                                                   :value="job_s2.job_name_ar"
                                                   required readonly>
                                        @endif
                                    </div>
                                </div>

                                {{--تاريخ التعيين--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.work_start_date')</label>
                                        <input class="form-control" type="date"
                                               name="emp_work_start_date"
                                               :value="employee_s2.emp_work_start_date" readonly>
                                    </div>
                                </div>

                                {{--الراتب--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.salary')</label>
                                        <input class="form-control" type="text"
                                               :value="employee_s2.basic_salary"
                                               required readonly>
                                    </div>
                                </div>

                                {{--الحاله--}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.social_status')</label>
                                        @if(app()->getLocale()=='ar')
                                            <input class="form-control" type="text"
                                                   :value="social_status_s2.system_code_name_ar"
                                                   required readonly>
                                        @else
                                            <input class="form-control" type="text"
                                                   :value="social_status_s2.system_code_name_en"
                                                   required readonly>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>


                    {{--بيانات الموظف--}}
                    <x-employees.employee-data
                            :employeeRequest="$employee_request">

                    </x-employees.employee-data>


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
                    </div>


                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit"
                                id="submit_ancestors">@lang('home.save')</button>
                    </div>


                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    <script>
        new Vue({
            el: '#app',
            data: {
                employee_request:{!! $employee_request->ancestorsRequestDetails !!},
                emp_id_s1: '',
                employee_s1: {},
                job_s1: {},
                social_status_s1: {},

                emp_id_s2: '',
                employee_s2: {},
                job_s2: {},
                social_status_s2: {}
            },
            mounted() {
                this.emp_id_s1 = this.employee_request.sponsor_id_1
                this.emp_id_s2 = this.employee_request.sponsor_id_2
                this.getEmployee_s1()
                this.getEmployee_s2()
            },
            methods: {
                getEmployee_s1() {
                    this.employee_s1 = {}
                    this.job_s1 = {}
                    this.social_status_s1 = {}

                    if (this.emp_id_s1) {
                        $.ajax({
                            type: 'GET',
                            data: {emp_id: this.emp_id_s1},
                            url: '{{ route('get-employee') }}'
                        }).then(response => {
                            this.employee_s1 = response.employee
                            this.job_s1 = this.employee_s1.job
                            this.social_status_s1 = this.employee_s1.emp_social_status
                        })
                    }
                },

                getEmployee_s2() {

                    this.employee_s2 = {}
                    this.job_s2 = {}
                    this.social_status_s2 = {}

                    if (this.emp_id_s2) {
                        $.ajax({
                            type: 'GET',
                            data: {emp_id: this.emp_id_s2},
                            url: '{{ route('get-employee') }}'
                        }).then(response => {
                            this.employee_s2 = response.employee
                            this.job_s2 = this.employee_s2.job
                            this.social_status_s2 = this.employee_s2.emp_social_status
                        })
                    }
                },
                //////////////////
            }
        })
    </script>

@endsection