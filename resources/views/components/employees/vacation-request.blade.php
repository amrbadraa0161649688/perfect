<div>
    {{--طلب اجازه--}}
    <form action="{{route('employee-requests-store')}}" method="post" id="vacation_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">
        {{----------------vacation_request------------------------------------------------------------------------------------}}
        <div id="vacation-form" style="display: none">
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
                                       value="{{$stringNumber}}" readonly>

                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.add_new_request')</label>
                            <div class="form-group multiselect_div">
                                <input type="text" class="form-control" id="vacation_request_date"
                                       readonly>
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
                                <select id="" name="vacation_type" class="form-control">
                                    <option value=""
                                            selected>@lang('home.choose_vacation_type')</option>
                                    @foreach($vacationTypes as $vacation_type)
                                        <option value="{{ $vacation_type->system_code }}">
                                            {{ app()->getLocale()=='ar' ? $vacation_type->system_code_name_ar :
                                             $vacation_type->system_code_name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label for="recipient-name"
                                   class="col-form-label"
                                   style="text-decoration: underline;">@lang('home.substitute_employee')</label>
                            <div class="form-group multiselect_div">
                                <select class="selectpicker" data-live-search="true"
                                        id="sub_emp_id" name="sub_emp_id">
                                    <option value="" selected>@lang('home.choose_employee')</option>
                                    @foreach($alterEmployees as $alter_employee)
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
                                       v-model="start_date" @change="getDaysCount()">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.vacation_end_date')</label>
                            <div class="form-group">
                                <input type="date" name="emp_request_end_date" class="form-control"
                                       v-model="end_date" @change="getDaysCount()">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.days_count')</label>
                            <div class="form-group">
                                <input type="number" name="emp_request_days" v-model="days_count"
                                       class="form-control" readonly required>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.address_while_on_vacation')</label>
                            <div class="form-group">
                                <input type="text" class="form-control" name="vacation_address">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.phone')</label>
                            <div class="form-group ">
                                <input type="number" name="vacation_phone" class="form-control"
                                       placeholder="@lang('home.phone')">
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <label>@lang('home.email')</label>
                            <div class="form-group">
                                <input type="email" name="" class="form-control"
                                       placeholder="@lang('home.email')">
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            <label>@lang('home.notes')</label>
                            <div class="form-group">
                                <textarea class="form-control" name="emp_request_notes"></textarea>
                            </div>
                        </div>
                    </div>
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
                                <label for="recipient-name"
                                       class="col-form-label"
                                       style="text-decoration: underline;">@lang('home.employee_name')</label>
                                <div class="form-group multiselect_div">
                                    <select class="selectpicker" data-live-search="true"
                                            name="emp_id" v-model="emp_id"
                                            @change="getEmployee() ; getVacation2()">
                                        <option value="" selected>@lang('home.choose_employee')</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->emp_id }}">{{ app()->getLocale()=='ar' ?
                                                $employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('home.branch')</label>
                                @if(app()->getLocale()=='ar')
                                    <input class="form-control" type="text" id="" name=""
                                           :value="branch.branch_name_ar" required readonly>
                                @else
                                    <input class="form-control" type="text" id="" name=""
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
                                <input class="form-control" type="text" name="emp_direct_date"
                                       :value="employee.emp_work_start_date"
                                       required readonly>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('home.last_vacation')</label>
                                <input class="form-control" type="text" name="issue_date"
                                       :value="employee.last_vacation_date" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('home.employee_balance')</label>
                                <input class="form-control" type="text" name="days_available"
                                       v-model="days_available" required readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('home.balance_before_last_request')</label>
                                <input class="form-control" type="text" :value="days_count2"
                                       readonly>
                                <small class="text-danger" v-if="vacation_message">@{{ vacation_message
                                    }}
                                </small>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary" id="submit" type="submit">@lang('home.save')</button>
                <div class="spinner-border text-primary" role="status" style="display: none">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </form>
</div>