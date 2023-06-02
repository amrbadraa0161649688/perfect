<div>
    <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
    {{--بيانات الموظف--}}
    <div class="card">
        <div class="card-body demo-card">
            <div class="row clearfix">

                {{--بيانات الموظف--}}

                <div class="card-header">
                    <h3 class="card-title">@lang('home.employee_data')</h3>
                </div>

                <div class="card" id="">
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
                                    @if(app()->getLocale()=='ar')
                                        <input type="text" readonly class="form-control"
                                               :value="manager.name_ar">
                                    @else
                                        <input type="text" readonly class="form-control"
                                               :value="manager.name_en">
                                    @endif
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
                                           :value="employee.emp_current_address" readonly>
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

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.work_start_date')</label>
                                    @if(app()->getLocale()=='ar')
                                        <input type="text" class="form-control" readonly
                                               :value="nationality.system_code_name_ar">
                                    @else
                                        <input type="text" class="form-control"
                                               :value="nationality.system_code_name_en">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.birth_date')</label>
                                    <input type="text" class="form-control" readonly
                                           :value="employee.emp_birthday">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.id_number')</label>
                                    <input type="text" class="form-control" readonly
                                           :value="employee.emp_identity">
                                </div>
                            </div>


                            <div class="col-md-3">

                                <div class="form-group">
                                    <label>@lang('home.contract_start_date')</label>
                                    <input type="text" class="form-control" readonly :value="employee.contract_start_date">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.contract_end_date')</label>
                                    <input type="text" class="form-control" readonly :value="employee.contract_end_date">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
