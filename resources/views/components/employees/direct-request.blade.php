<div>
    <form action="{{route('employee-requests-store')}}" method="post" id="direct_request">
        @csrf

        {{----------------direct_request------------------------------------------------------------------------------------}}
        <div id="direct-work-form" style="display: none">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>
                <div class="card-body demo-card">
                    <div class="row clearfix">

                        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.request_code')</label>
                            <div class="form-group">

                                <input type="text" class="form-control"
                                       name="emp_request_code"
                                       value="{{$stringNumber}}" readonly>

                            </div>
                        </div>


                        <div class="col-lg-4 col-md-12">
                            <div class="form-group">
                                <label for="recipient-name"
                                       class="col-form-label"
                                       style="text-decoration: underline;">@lang('home.employee_name')</label>
                                <div class="form-group multiselect_div">
                                    <select class="selectpicker" data-live-search="true"
                                            name="emp_id" v-model="emp_id"
                                            @change="getEmployee()">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->emp_id }}">{{ app()->getLocale()=='ar' ?
                                                                $employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.requests_vacation')</label>
                            <div class="form-group ">
                                <select id="" name="emp_request_id" class="form-control"
                                        @change="getVacation()" v-model="vacation_id">
                                    <option value=""
                                            selected>@lang('home.choose_request')</option>
                                    <option v-for="pending_vacation in pending_vacations"
                                            :value="pending_vacation.emp_request_id">
                                        @{{pending_vacation.emp_request_date}} _
                                        @{{pending_vacation.emp_request_code}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{--بيانات الأجازه--}}

                        <div class="col-lg-4 col-md-12">
                            {{--//empty--}}
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.vacation_start_date')</label>
                            <div class="form-group">
                                <input type="date" name="emp_request_start_date" class="form-control"
                                       readonly :value="vacation.emp_request_start_date">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.vacation_end_date')</label>
                            <div class="form-group">
                                <input type="date" name="emp_request_end_date" class="form-control"
                                       readonly :value="vacation.emp_request_end_date">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.days_count')</label>
                            <div class="form-group">
                                <input type="number" name="emp_request_days"
                                       class="form-control" readonly :value="vacation.vacation_days">
                            </div>
                        </div>

                        {{--بيانات الموظف--}}

                        <div class="card" id="">

                            <div class="card-header">
                                <h3 class="card-title">@lang('home.employee_data')</h3>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.branch')</label>
                                            @if(app()->getLocale()=='ar')
                                                <input class="form-control" type="text"
                                                       id=""
                                                       name=""
                                                       :value="branch.branch_name_ar" required readonly>
                                            @else
                                                <input class="form-control" type="text"
                                                       id=""
                                                       name=""
                                                       :value="branch.branch_name_en" required readonly>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.job')</label>
                                            @if(app()->getLocale()=='ar')
                                                <input class="form-control" type="text" id=""
                                                       :value="job.job_name_ar"
                                                       required readonly>
                                            @else
                                                <input class="form-control" type="text" id=""
                                                       :value="job.job_name_ar"
                                                       required readonly>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.direct_manager')</label>
                                            {{--@if(app()->getLocale()=='ar')--}}
                                                {{--<input type="text" readonly class="form-control"--}}
                                                       {{--:value="manager.name_ar" v-if="Object.keys(manager).length > 0">--}}
                                            {{--@else--}}
                                                {{--<input type="text" readonly class="form-control"--}}
                                                       {{--:value="manager.name_en" v-if="Object.keys(manager).length > 0">--}}
                                            {{--@endif--}}
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.division')</label>
                                            @if(app()->getLocale()=='ar')
                                                <input class="form-control" type="text"
                                                       id=""
                                                       :value="division.division_name_ar" required
                                                       readonly>
                                            @else
                                                <input class="form-control" type="text"
                                                       id=""
                                                       :value="division.division_name_en" required
                                                       readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.address')</label>
                                            <input class="form-control address" type="text" id=""
                                                   :value="employee.address" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.job_date')</label>
                                            <input class="form-control" type="date"
                                                   name="emp_direct_date"
                                                   :value="employee.emp_direct_date"
                                                   required readonly>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.work_start_date')</label>
                                            <input class="form-control" type="date"
                                                   name="emp_work_start_date"
                                                   :value="employee.emp_work_start_date" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.employee_balance')</label>
                                            <input class="form-control" type="text"
                                                   name="days_available"
                                                   v-model="days_available" required readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('home.salary')</label>
                                            <input class="form-control" type="text"
                                                   :value="employee.basic_salary"
                                                   required readonly>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.direct_date')</label>
                            <div class="form-group">

                                <input type="date" name="emp_direct_date" class="form-control"
                                       v-model="direct_date" @change="getDaysCount2()" required>

                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>عدد ايام الاجازه الفعلي</label>
                            <div class="form-group">
                                <input type="text" readonly :value="days_count" class="form-control"
                                       name="actual_vacation_days">
                            </div>
                        </div>


                        <div class="col-lg-4 col-md-12">
                            <button class="btn btn-primary mt-4 mr-3 ml-3" id="submit2"
                                    type="submit">@lang('home.save')</button>
                            <div class="spinner-border text-primary" role="status"
                                 style="display: none">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </form>
</div>