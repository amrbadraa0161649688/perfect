<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->
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
                                               value="{{ $employeeRequest->employee->branch->branch_name_ar }}"
                                               readonly>
                                    @else
                                        <input class="form-control" type="text"
                                               value="{{ $employeeRequest->employee->branch->branch_name_en }}"
                                               readonly>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.job')</label>
                                    <input class="form-control" type="text" id=""
                                           value="{{app()->getLocale()=='ar' ?
                                                    $employeeRequest->employee->contractActive->job->job_name_ar :
                                                    $employeeRequest->employee->contractActive->job->job_name_en }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.direct_manager')</label>
                                    <input type="text" readonly class="form-control"
                                           name="manager_id"
                                           value="{{app()->getLocale()=='ar' ?  $employeeRequest->employee->manager->emp_name_full_ar :
                                               $employeeRequest->employee->manager->emp_name_full_en }}">
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.division')</label>
                                    <input class="form-control" type="text" id=""

                                           value="{{app()->getLocale()=='ar' ?
                                                    $employeeRequest->employee->contractActive->job->division->division_name_en :
                                                    $employeeRequest->employee->contractActive->job->division->division_name_en }}"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.address')</label>
                                    <input class="form-control address" type="text" id=""
                                           value="{{ $employeeRequest->employee->emp_current_address }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('home.job_date')</label>
                                    <input class="form-control address" type="text" id=""
                                           value="{{ $employeeRequest->employee->emp_direct_date }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.work_start_date')</label>

                                    <input class="form-control address" type="text" id=""
                                           value="{{ $employeeRequest->employee->emp_work_start_date }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.employee_balance')</label>
                                    <input class="form-control" type="text"
                                           name="days_available"
                                           value="{{$employeeRequest->employee->emp_vacation_balance}}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.salary')</label>
                                    <input class="form-control" type="text"
                                           value="{{ $employeeRequest->employee->basicSalary }}"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.work_start_date')</label>
                                    @if(app()->getLocale()=='ar')
                                        <input type="text" class="form-control" readonly
                                               value="{{$employeeRequest->employee->nationality->system_code_name_ar }}">
                                    @else
                                        <input type="text" class="form-control" readonly
                                               value="{{$employeeRequest->employee->nationality->system_code_name_en }}">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.birth_date')</label>
                                    <input type="text" class="form-control" readonly
                                           value="{{$employeeRequest->employee->emp_birthday}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.id_number')</label>
                                    <input type="text" class="form-control" readonly
                                           value="{{$employeeRequest->employee->emp_identity}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.contract_start_date')</label>
                                    <input type="text" class="form-control" readonly
                                           value="{{$employeeRequest->employee->contractActive->emp_contract_start_date}}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.contract_end_date')</label>
                                    <input type="text" class="form-control" readonly
                                           value="{{$employeeRequest->employee->contractActive->emp_contract_end_date}}">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>