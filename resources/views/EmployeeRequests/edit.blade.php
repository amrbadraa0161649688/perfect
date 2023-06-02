@extends('Layouts.master')
@section('style')



@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-md-12">
                    <form action="{{route('employee-requests-update',$id)}}" method="post">
                        @csrf
                        @method('put')

                        <div class="card">

                            <div class="card-body demo-card">
                                <div class="row clearfix">
                                    <div class="col-lg-4 col-md-12">
                                        <label>@lang('home.request_type')</label>
                                        <div class="form-group">
                                            @if(app()->getLocale()=='ar')
                                                <input type="text" readonly class="form-control"
                                                       :value="request_type.system_code_name_ar">
                                            @else
                                                <input type="text" readonly class="form-control"
                                                       :value="request_type.system_code_name_en">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div id="vacation-form">
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
                                                       :value="employee_request.emp_request_code" readonly>

                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.request_date')</label>
                                            <div class="form-group multiselect_div">
                                                <input type="text" class="form-control" id=""
                                                       :value="employee_request.emp_request_date" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.user_name')</label>
                                            <div class="form-group multiselect_div">
                                                <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                auth()->user()->user_name_ar : auth()->user()->user_name_en}}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.vacation_type')</label>
                                            <div class="form-group ">

                                                <select id="" name="vacation_type" class="form-control"
                                                        v-model="vacation_type"
                                                        :disabled="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1">
                                                    <option value=""
                                                            selected>@lang('home.choose_vacation_type')</option>
                                                    @foreach($vacation_types as $vacation_type)
                                                        <option value="{{ $vacation_type->system_code_id }}">
                                                            {{ app()->getLocale()=='ar' ? $vacation_type->system_code_name_ar :
                                                             $vacation_type->system_code_name_en }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.substitute_employee')</label>
                                            <div class="form-group multiselect_div">
                                                <select id="" name="sub_emp_id" class="form-control"
                                                        v-model="sub_emp_id"
                                                        :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >
                                                    <option value="">@lang('home.choose_employee')</option>
                                                    @foreach($alter_employees as $alter_employee)
                                                        <option value="{{ $alter_employee->emp_id }}">{{app()->getLocale()=='ar' ?
                                                 $alter_employee->emp_name_full_ar : $alter_employee->emp_name_full_en }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            {{--//empty--}}
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.vacation_start_date')</label>
                                            <div class="form-group">
                                                <input type="date" name="emp_request_start_date" class="form-control"
                                                       v-model="start_date" @change="getDaysCount()"
                                                       :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.vacation_end_date')</label>
                                            <div class="form-group">
                                                <input type="date" name="emp_request_end_date" class="form-control"
                                                       v-model="end_date" @change="getDaysCount()"
                                                       :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.days_vacation')</label>
                                            <div class="form-group">
                                                <input type="number" name="emp_request_days" v-model="days_count"
                                                       class="form-control" readonly
                                                       :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.address_while_on_vacation')</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                       :value="employee_request.vacation_address"
                                                       name="vacation_address"
                                                       :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.phone')</label>
                                            <div class="form-group ">
                                                <input type="number" name="vacation_phone" class="form-control"
                                                       :value="employee_request.vacation_phone"
                                                       :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-12">
                                            <label>@lang('home.email')</label>
                                            <div class="form-group">
                                                <input type="email" name="" class="form-control"
                                                       :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <label>@lang('home.notes')</label>
                                            <div class="form-group">
                                                <textarea class="form-control" name="emp_request_notes"
                                                          :readonly="employee_request.emp_request_status == 0 || employee_request.emp_request_status == 1"
                                                >@{{employee_request.emp_request_notes}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card" id="">

                                <div class="card-header">
                                    <h3 class="card-title">@lang('home.employee_data')</h3>
                                </div>
                                {{----------------------emp_data--------------------------------------------------------------------------------------}}
                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.employee_name')</label>
                                                @if(app()->getLocale()=='ar')
                                                    <input type="text" class="form-control"
                                                           :value="employee.emp_name_full_ar" readonly>
                                                @else
                                                    <input type="text" class="form-control"
                                                           :value="employee.emp_name_full_en" readonly>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.branch')</label>
                                                @if(app()->getLocale()=='ar')
                                                    <input class="form-control" type="text" id="" name=""
                                                           :value="branch.branch_name_ar" readonly>
                                                @else
                                                    <input class="form-control" type="text" id="" name=""
                                                           :value="branch.branch_name_en" readonly>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.job')</label>
                                                @if(app()->getLocale()=='ar')
                                                    <input class="form-control" type="text" id=""
                                                           :value="job.job_name_ar"
                                                           readonly>
                                                @else
                                                    <input class="form-control" type="text" id=""
                                                           :value="job.job_name_ar"
                                                           readonly>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.division')</label>
                                                @if(app()->getLocale()=='ar')
                                                    <input class="form-control" type="text" id=""
                                                           :value="division.division_name_ar" required readonly>
                                                @else
                                                    <input class="form-control" type="text" id=""
                                                           :value="division.division_name_en" required readonly>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.address')</label>
                                                <input class="form-control" type="text" id=""
                                                       :value="employee.address" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.job_date')</label>
                                                <input class="form-control" type="date" name="emp_direct_date"
                                                       :value="employee.emp_work_start_date"
                                                       required readonly>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.last_vacation')</label>
                                                <input class="form-control" type="date" name="issue_date"
                                                       :value="employee.last_vacation_date" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.employee_balance')</label>
                                                <input class="form-control" type="text" name="days_available"
                                                       :value="employee_request.vacatio_balance_day" required readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.salary')</label>
                                                <input class="form-control" type="text" :value="employee.basic_salary"
                                                       required readonly>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            {{--------------////emp_data------------------------------------------------------------------------------------------}}

                            <div class="card">
                                <div class="card-body demo-card">
                                    <div class="row clearfix">
                                        <div class="col-lg-6 col-md-12">
                                            <label>@lang('home.manager')</label>
                                            <div class="form-group">
                                                @if(app()->getLocale() == 'ar')
                                                    <input class="form-control" name="emp_request_manager_id"
                                                           :value="manager.emp_name_full_ar" readonly
                                                           :readonly=" employee_request.emp_request_status == 1"
                                                    >
                                                @else
                                                    <input class="form-control" name="emp_request_manager_id"
                                                           :value="manager.emp_name_full_en" readonly
                                                           :readonly="employee_request.emp_request_status == 1"
                                                    >
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-12">
                                            <label>@lang('home.approved')</label>
                                            <div class="form-group">
                                                <select name="emp_request_status" class="form-control" required
                                                        v-model="emp_request_approved"
                                                        :readonly=" employee_request.emp_request_status == 1"
                                                >
                                                    <option value="0">@lang('home.refuse_request')</option>
                                                    <option value="1">@lang('home.accept_request')</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <label>@lang('home.request_reason')</label>
                                            <div class="form-group">
                                                <textarea class="form-control" name="emp_request_reason"
                                                          :readonly=" employee_request.emp_request_status == 1"
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button class="btn btn-primary" type="submit" id="submit"
                                        v-if="employee_request.emp_request_status == 0">
                                    @lang('home.save')</button>
                                <div class="spinner-border text-primary" role="status"
                                     style="display: none">
                                    <span class="sr-only">Loading...</span>
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
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                start_date: '',
                end_date: '',
                days_count: '',
                // emp_id: '',
                days_available: '',
                branch: {},
                job: {},
                division: {},
                employee: {},
                emp_request_approved: '',
                employee_request: {},
                request_type: {},
                sub_emp_id: '',
                vacation_type: '',
                manager: {},
                request_id: '{{$id}}',

            },
            mounted() {
                this.getEmployeeRequest()
            },
            methods: {
                getEmployeeRequest() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.request_id},
                        url: '{{ route('get-employee-request') }}'
                    }).then(response => {
                        this.employee_request = response.data
                        this.days_count = response.data.emp_request_days
                        this.emp_request_approved = response.data.emp_request_approved
                        this.end_date = response.data.emp_request_end_date
                        this.start_date = response.data.emp_request_start_date
                        this.request_type = response.request_type
                        this.employee = response.employee
                        this.branch = this.employee.branch
                        this.job = this.employee.job
                        this.division = this.employee.division
                        this.sub_emp_id = response.data.sub_emp_id
                        this.manager = response.manager
                        this.vacation_type = response.data.vacation_type
                    })
                },
                {{--getDaysCount() {--}}
                    {{--if (this.start_date && this.end_date) {--}}
                        {{--$.ajax({--}}
                            {{--type: 'GET',--}}
                            {{--data: {start_date: this.start_date, end_date: this.end_date},--}}
                            {{--url: '{{ route('employee-requests-getDays') }}'--}}
                        {{--}).then(response => {--}}
                            {{--this.days_count = response.data--}}
                        {{--})--}}
                    {{--}--}}
                {{--},--}}
                {{--getEmployee() {--}}
                    {{--$.ajax({--}}
                        {{--type: 'GET',--}}
                        {{--data: {emp_id: this.emp_id},--}}
                        {{--url: '{{ route('get-employee') }}'--}}
                    {{--}).then(response => {--}}
                        {{--this.days_available = response.days_available--}}

                    {{--})--}}
                {{--}--}}
            }
        });
    </script>

@endsection

