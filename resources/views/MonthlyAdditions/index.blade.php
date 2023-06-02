@extends('Layouts.master')
@section('content')
    <div class="section-body py-3">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> @lang('home.monthly_additions') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    @if(auth()->user()->user_type_id != 1)
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 11 && $job_permission->permission_add)
                                                <a href="{{ route('monthly-additions.create') }}"
                                                   class="btn btn-primary">@lang('home.add')</a>
                                            @endif
                                        @endforeach
                                    @else
                                        <a href="{{ route('monthly-additions.create') }}"
                                           class="btn btn-primary">@lang('home.add')</a>

                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <form action="">
                                        <select class="form-control" onchange="this.form.submit()" name="company_id">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->company_id }}" @if(request()->company_id
                                                == $company->company_id) selected @endif>{{ app()->getLocale()=='ar' ?
                                             $company->company_name_ar : $company->company_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">

                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead style="background-color: #ece5e7">
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.account_period')</th>
                                        <th>@lang('home.total_value')</th>
                                        <th>@lang('home.user_name')</th>
                                        <th>@lang('home.updated_date')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($monthly_additions as $k=>$monthly_addition)
                                        <tr>

                                            <td>{{ $k+1 }}</td>
                                            <td>
                                                @if(app()->getLocale()=='ar')
                                                    {{ $monthly_addition->company->company_name_ar }}
                                                @else  {{ $monthly_addition->company->company_name_en }} @endif
                                            </td>
                                            <td>
                                                @if(app()->getLocale()=='ar')
                                                    {{ $monthly_addition->AccountPeriod->acc_period_name_ar }}
                                                @else  {{ $monthly_addition->AccountPeriod->acc_period_name_en }} @endif
                                            </td>
                                            <td>{{ $monthly_addition->totalValue }}</td>
                                            <td>
                                                @if(app()->getLocale()=='ar')
                                                    {{ $monthly_addition->userCreated->user_name_ar }}
                                                @else  {{ $monthly_addition->userCreated->user_name_en }} @endif
                                            </td>
                                            <td>{{ $monthly_addition->created_at }}</td>
                                            <td>
                                                <button type="button" class="btn btn-icon js-sweetalert"
                                                        id="addition{{ $monthly_addition->emp_variables_id }}"
                                                        title="@lang('home.show')" data-type="confirm"
                                                        onclick="show(this)">
                                                    <i class="fa fa-eye text-danger"></i>
                                                </button>

                                                <a class="btn btn-icon" href="{{ route('monthly-additions.edit',$monthly_addition->emp_variables_id ) }}"
                                                        title="" data-type="confirm">
                                                            <i class="fa fa-edit"></i>
                                             </a>

                                                      

                                                <a href="{{config('app.telerik_server')}}?rpt={{$monthly_addition->report_url_var_c->report_url}}&comp_id={{$monthly_addition->emp_variables_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                           
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        @foreach($monthly_additions as $k=>$monthly_addition)
            <div class="section-body section-sub-application mt-3"
                 id="app-addition{{ $monthly_addition->emp_variables_id }}"
                 style="display:none">
                <div class="container-fluid">
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-options">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-vcenter table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('home.employee')</th>
                                                <th>@lang('home.employee_variables_type')</th>
                                                <th>@lang('home.days')</th>
                                                <th>@lang('home.hours')</th>
                                                <th>@lang('home.minutes')</th>
                                                <th>@lang('home.salary')</th>
                                                <th>@lang('home.factor')</th>
                                                <th>@lang('home.credit')</th>
                                                <th>@lang('home.notes')</th>
                                                <th>@lang('home.created_user')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($monthly_addition->employeeVariableDetails as $k=>$detail)
                                                <tr>
                                                    <td>{{ $k+1 }}</td>
                                                    <td>@if ($detail->employee)
                                                        {{app()->getLocale()=='ar' ?
                                                         $detail->employee->emp_name_full_ar : $detail->employee->emp_name_full_en }}
                                                         @endif
                                                        
                                                        </td>
                                                    <td> @if ($detail->EmployeeVariableType)
                                                        {{ app()->getLocale()=='ar' ? $detail->EmployeeVariableType->system_code_name_ar :
                                                          $detail->EmployeeVariableType->system_code_name_en }}
                                                          @endif
                                                    </td>
                                                    <td>{{ $detail->emp_variables_days ? $detail->emp_variables_days : 0 }}</td>
                                                    <td>{{ $detail->emp_variables_hours ? $detail->emp_variables_hours : 0 }}</td>
                                                    <td>{{ $detail->emp_variables_minutes ? $detail->emp_variables_minutes : 0 }}</td>
                                                    <td>{{ $detail->emp_variables_salary }}</td>
                                                    <td>{{ $detail->emp_variables_factor }}</td>
                                                    <td>{{ $detail->emp_variables_credit }}</td>
                                                    <td>{{ $detail->emp_variables_notes }}</td>
                                                    <td>{{app()->getLocale()=='ar' ?
                                                 $detail->userCreated->user_name_ar : $detail->userCreated->user_name_en }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection

@section('scripts')
    <script>
        function show(el) {
            var x = el.id;
            $("#app-" + x).css("display", "block");
            $("#app-" + x).siblings().css('display', 'none')
        }
    </script>

@endsection
