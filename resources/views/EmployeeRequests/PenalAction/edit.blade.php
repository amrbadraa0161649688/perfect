@extends('Layouts.master')

@section('content')
    <div id="app">
        <!-- Knowing is not enough; we must apply. Being willing is not enough; we must do. - Leonardo da Vinci -->
        <form action="{{ route('employee-requests-update-panel-action',$employee_request->emp_request_id) }}"
              method="post">
            @csrf
            @method('put')

            <div id="panel-action-form">

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
                            </div>

                            <div class="row clearfix">
                                {{--تاريخ اخر جزاء--}}
                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.last_panel_action_date')</label>
                                    <div class="form-group">

                                        <input type="text" class="form-control"
                                               name="last_panel_action_date"
                                               value="{{ $employee_request->employee->lastPanelActionDate }}" readonly>

                                    </div>
                                </div>

                                {{--تارخ الجزاء الجديد--}}
                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.panel_action_date')</label>
                                    <div class="form-group">

                                        <input type="date" class="form-control"
                                               @if($employee_request->emp_request_status != 2)
                                               readonly @endif
                                               value="{{ $employee_request->panelActionDetails->item_start_date }}"
                                               required>

                                    </div>
                                </div>

                                {{--سبب الجزاء--}}
                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.panel_action_reasons')</label>
                                    <div class="form-group">
                                        <select class="form-control" name="item_reasons"
                                                v-model="item_reasons" @if($employee_request->emp_request_status != 2)
                                                readonly @endif
                                                @change="validatePanelActionForm()">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($panel_action_reasons as $panel_action_reason)
                                                <option value="{{ $panel_action_reason->system_code }}"
                                                        @if($employee_request->panelActionDetails->item_reasons ==
                                                         $panel_action_reason->system_code_id) selected @endif>
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
                                               @if($employee_request->emp_request_status != 2)
                                               readonly @endif
                                               value="{{$employee_request->panelActionDetails->item_qunt}}"
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
                                               value="{{$employee_request->panelActionDetails->item_date}}"
                                               :required="panel_action_date_required"
                                               @if($employee_request->emp_request_status != 2)
                                               readonly @endif
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
                                                           value="{{ $employee_request->employee->branch->branch_name_ar }}"
                                                           readonly>
                                                @else
                                                    <input class="form-control" type="text"
                                                           value="{{ $employee_request->employee->branch->branch_name_en }}"
                                                           readonly>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.job')</label>
                                                <input class="form-control" type="text" id=""
                                                       value="{{app()->getLocale()=='ar' ?
                                                    $employee_request->employee->contractActive->job->job_name_ar :
                                                    $employee_request->employee->contractActive->job->job_name_en }}"
                                                       readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.direct_manager')</label>
                                                <input type="text" readonly class="form-control"
                                                       name="manager_id"
                                                       value="{{app()->getLocale()=='ar' ?  $employee_request->employee->manager->emp_name_full_ar :
                                               $employee_request->employee->manager->emp_name_full_en }}">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.division')</label>
                                                <input class="form-control" type="text" id=""

                                                       value="{{app()->getLocale()=='ar' ?
                                                    $employee_request->employee->contractActive->job->division->division_name_en :
                                                    $employee_request->employee->contractActive->job->division->division_name_en }}"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.address')</label>
                                                <input class="form-control address" type="text" id=""
                                                       value="{{ $employee_request->employee->emp_current_address }}"
                                                       readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.job_date')</label>
                                                <input class="form-control address" type="text" id=""
                                                       value="{{ $employee_request->employee->emp_direct_date }}"
                                                       readonly>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.work_start_date')</label>

                                                <input class="form-control address" type="text" id=""
                                                       value="{{ $employee_request->employee->emp_work_start_date }}"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.employee_balance')</label>
                                                <input class="form-control" type="text"
                                                       name="days_available"
                                                       value="{{$employee_request->employee->emp_vacation_balance}}"
                                                       readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.salary')</label>
                                                <input class="form-control" type="text"
                                                       value="{{ $employee_request->employee->basicSalary }}"
                                                       readonly>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.work_start_date')</label>
                                                @if(app()->getLocale()=='ar')
                                                    <input type="text" class="form-control" readonly
                                                           value="{{$employee_request->employee->nationality->system_code_name_ar }}">
                                                @else
                                                    <input type="text" class="form-control" readonly
                                                           value="{{$employee_request->employee->nationality->system_code_name_en }}">
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.birth_date')</label>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$employee_request->employee->emp_birthday}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>@lang('home.id_number')</label>
                                                <input type="text" class="form-control" readonly
                                                       value="{{$employee_request->employee->emp_identity}}">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <label>@lang('home.approved')</label>
                                            <div class="form-group">
                                                @if($employee_request->emp_request_status == 2)
                                                    <select name="emp_request_status" class="form-control" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        <option value="0">@lang('home.refuse_request')</option>
                                                        <option value="1">@lang('home.accept_request')</option>
                                                    </select>
                                                @else
                                                    <input name="emp_request_approved" class="form-control"
                                                           value="@if($employee_request->emp_request_status == 0)
                                                           @lang('home.refuse_request')
                                                           @elseif($employee_request->emp_request_status == 1)
                                                           @lang('home.accept_request')
                                                           @endif" readonly>

                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label>موافقه الموارد البشريه </label>
                                            <div class="form-group">
                                                @if($employee_request->emp_request_hr_approver == 2)
                                                    <select name="emp_request_hr_approver" class="form-control"
                                                            required>
                                                        <option value="">@lang('home.choose')</option>
                                                        <option value="0">@lang('home.refuse_request')</option>
                                                        <option value="1">@lang('home.accept_request')</option>
                                                    </select>
                                                @else
                                                    <input name="emp_request_hr_approver" class="form-control"
                                                           value="@if($employee_request->emp_request_hr_approver == 0)
                                                           @lang('home.refuse_request')
                                                           @elseif($employee_request->emp_request_hr_approver == 1)
                                                           @lang('home.accept_request')
                                                           @endif" readonly>

                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="card-footer">
                                @if($employee_request->emp_request_status == 2)
                                    <button class="btn btn-primary" type="submit"
                                            id="submit_medical_insurance">@lang('home.save')</button>
                                @endif

                            </div>


                        </div>
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
                item_reasons: {!! $employee_request->panelActionDetails->itemReasons->system_code !!},
                panel_action_days_required: false,
                panel_action_date_required: false
            },
            methods: {
                validatePanelActionForm() {
                    this.panel_action_days_required = false
                    this.panel_action_date_required = false
                    if (this.item_reasons == 108003) {
                        this.panel_action_days_required = true
                    }
                    if (this.item_reasons == 108007) {
                        this.panel_action_date_required = true
                    }
                }
            }
        })
    </script>
@endsection