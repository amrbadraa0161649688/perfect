<div>
    {{--اجراء جزائي--}}
    <!-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci -->
    <form action="{{ route('employee-requests-store-panel-action') }}" method="post" id="panel_action_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">

        <div id="panel-action-form" style="display: none">

            {{--بيانات الطلب--}}
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

                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.employee_name')</label>
                                    <div class="form-group multiselect_div">
                                        <select class="selectpicker" data-live-search="true"
                                                name="emp_id" v-model="emp_id"
                                                @change="getEmployee()" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->emp_id }}">{{ app()->getLocale()=='ar' ?
                                                                $employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row clearfix">
                            {{--تاريخ اخر جزاء--}}
                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.last_panel_action_date')</label>
                                <div class="form-group">

                                    <input type="text" class="form-control"
                                           name="last_panel_action_date"
                                           :value="employee.last_panel_action_date" readonly>

                                </div>
                            </div>

                            {{--تارخ الجزاء الجديد--}}
                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.panel_action_date')</label>
                                <div class="form-group">

                                    <input type="date" class="form-control"
                                           name="item_start_date" required>

                                </div>
                            </div>

                            {{--سبب الجزاء--}}
                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.panel_action_reasons')</label>
                                <div class="form-group">
                                    <select class="form-control" name="item_reasons"
                                            v-model="item_reasons"
                                            @change="validatePanelActionForm()">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($panelActionReasons as $panel_action_reason)
                                            <option value="{{ $panel_action_reason->system_code }}">
                                                {{app()->getLocale()=='ar' ?
                                            $panel_action_reason->system_code_name_ar  :
                                              $panel_action_reason->system_code_name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--عدد ايام الجزاء--}}
                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.panel_days_count')</label>
                                <div class="form-group">
                                    <input type="number" class="form-control"
                                           :required="panel_action_days_required"
                                           name="item_qunt">
                                    <small>اجباري في حاله السبب خصم ايام</small>
                                </div>
                            </div>

                            {{--تاريخ الايقاف عن العمل--}}
                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.panel_date_stop')</label>
                                <div class="form-group">
                                    <input type="date" class="form-control"
                                           :required="panel_action_date_required"
                                           name="item_date">
                                    <small>اجباري في حاله السبب ايقاف عن العمل</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

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

                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                @lang('home.save')
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </form>
</div>