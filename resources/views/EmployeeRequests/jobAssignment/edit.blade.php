@extends('Layouts.master')

@section('content')

    <div id="app">
        <form action="{{ route('employee-requests-update-job-assignment-request',$employee_request->emp_request_id) }}"
              method="post" id="job_assignment_request">
            @csrf
            @method('put')

            <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">

            <div id="job-assignment-form">

                {{--بيانات الطلب--}}
                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">@lang('home.request_data')</h3>
                    </div>


                    <div class="row clearfix">
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

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.from')</label>
                                    <div class="form-group">
                                        <select name="company_id" class="form-control"
                                                v-model="jobAssignment_company_id"
                                                @change="getBranches()" @if($employee_request->emp_request_status != 2)
                                                disabled @endif>>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}">
                                                    {{ app()->getLocale() == 'ar' ? $company->company_name_ar :
                                                     $company->company_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.from')</label>
                                    <div class="form-group">
                                        <select name="item_loc_id" class="form-control"
                                                v-model="item_loc_id" @if($employee_request->emp_request_status != 2)
                                                disabled @endif>>
                                            <option value="">@lang('home.choose')</option>
                                            <option v-for="branch in branches" :value="branch.branch_id">
                                                @if(app()->getLocale()=='ar')
                                                    @{{ branch.branch_name_ar }}
                                                @else
                                                    @{{ branch.branch_name_en }}
                                                @endif
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.notes')</label>
                                    <div class="form-group">
                                    <textarea class="form-control" name="item_notes"
                                              @if($employee_request->emp_request_status != 2) disabled @endif>>
                                        {{ $employee_request->jobAssignmentDetails->item_notes }}
                                    </textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="row clearfix">

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.from')</label>
                                    <div class="form-group">

                                        <input type="date" name="item_start_date" class="form-control"
                                               @change="getDiffDateJobAssignment()"
                                               @if($employee_request->emp_request_status != 2) readonly @endif
                                               v-model="jobAssignment_start_date">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.to')</label>
                                    <div class="form-group">

                                        <input type="date" name="item_end_date" class="form-control"
                                               @change="getDiffDateJobAssignment()"
                                               @if($employee_request->emp_request_status != 2) readonly @endif
                                               v-model="jobAssignment_end_date">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.stop_working_days_count')</label>
                                    <div class="form-group">

                                        <input type="number" name="item_qunt" class="form-control"
                                               v-model="jobAssignment_item_qunt"
                                               @if($employee_request->emp_request_status != 2)
                                               readonly @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">

                                {{--قيمه سلفه للمهمه--}}
                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.predecessor_value_for_assignment')</label>
                                    <div class="form-group">
                                        <input class="form-control" name="item_value_1"
                                               value="{{$employee_request->jobAssignmentDetails->item_value_1}}"
                                               type="number"
                                               @if($employee_request->emp_request_status != 2) readonly @endif>
                                    </div>
                                </div>

                                {{--قيمه تذكره سفر--}}
                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.travel_ticket_value')</label>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="item_value_2"
                                               value="{{$employee_request->jobAssignmentDetails->item_value_2}}"
                                               @if($employee_request->emp_request_status != 2) readonly @endif>
                                    </div>
                                </div>

                                {{--قيمه التاشيرات--}}
                                <div class="col-lg-4 col-md-12">

                                    <label>@lang('home.value_of_the_visa')</label>
                                    <div class="form-group">
                                        <input class="form-control" name="item_value_3"
                                               value="{{$employee_request->jobAssignmentDetails->item_value_3}}"
                                               type="number"
                                               @if($employee_request->emp_request_status != 2) readonly @endif>
                                    </div>
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
                <div class="card">
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
                                            {{ $employee_request->jobAssignmentDetails->manager_notes ?
                                             $employee_request->jobAssignmentDetails->manager_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('home.reason_for_rejection_or_approval_of_the_hr')</label>
                                        <textarea class="form-control" name="hr_notes" required
                                                  @if($employee_request->emp_request_hr_approver != 2) readonly @endif>
                                            {{ $employee_request->jobAssignmentDetails->hr_notes ?  $employee_request->jobAssignmentDetails->hr_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('home.reason_for_rejection_or_approval_of_the_cEO')</label>
                                        <textarea class="form-control" name="ceo_notes" required
                                                  @if($employee_request->emp_request_status != 2) readonly @endif>
                                            {{ $employee_request->jobAssignmentDetails->ceo_notes ?
                                             $employee_request->jobAssignmentDetails->ceo_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

                @if($employee_request->emp_request_approved == 2)
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit"
                                id="submit_ancestors">@lang('home.save')</button>
                    </div>
                @endif

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
                jobAssignment_company_id: "",
                jobAssignment_end_date: "",
                jobAssignment_start_date: "",
                jobAssignment_item_qunt: '',
                branches: {},
                item_loc_id: ''
            },
            mounted() {
                this.jobAssignment_company_id = this.employee_request.company_id
                this.jobAssignment_end_date = this.employee_request.item_end_date
                this.jobAssignment_start_date = this.employee_request.item_start_date
                this.jobAssignment_item_qunt = this.employee_request.item_qunt

                this.getBranches()

                this.item_loc_id = this.employee_request.item_loc_id
            },
            methods: {
                getDiffDateJobAssignment() {
                    if (this.jobAssignment_start_date && this.jobAssignment_end_date) {
                        $.ajax({
                            type: 'GET',
                            data: {start_date: this.jobAssignment_start_date, end_date: this.jobAssignment_end_date},
                            url: '{{ route('requests.diffDate') }}'
                        }).then(response => {
                            this.jobAssignment_item_qunt = response.days
                        })
                    }
                },
                getBranches() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.jobAssignment_company_id},
                        url: '{{ route('job-assignment.getBranches') }}'
                    }).then(response => {
                        this.branches = response.data
                    })
                },
            }
        })
    </script>

@endsection