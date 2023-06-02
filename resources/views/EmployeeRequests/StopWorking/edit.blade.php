@extends('Layouts.master')

@section('content')
    <div id="app">
        <form action="{{route('employee-requests-update-stop-working-request',$employee_request->emp_request_id)}}"
              method="post"
              id="">
            @csrf

            @method('put')

            <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


            {{----------------stop working request------------------------------------------------------------------------------------}}
            <div id="stop-working-form">

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

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.from')</label>
                                    <div class="form-group">

                                        <input type="date" name="item_start_date" class="form-control"
                                               @change="getDiffDate()"
                                               @if($employee_request->emp_request_status != 2) disabled @endif
                                               v-model="stopWorking_start_date">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.to')</label>
                                    <div class="form-group">

                                        <input type="date" name="item_end_date" class="form-control"
                                               @change="getDiffDate()"
                                               @if($employee_request->emp_request_status != 2) disabled @endif
                                               v-model="stopWorking_end_date">
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.stop_working_days_count')</label>
                                    <div class="form-group">

                                        <input type="number" name="item_qunt" class="form-control"
                                               @if($employee_request->emp_request_status != 2) disabled @endif
                                               v-model="stopWorking_item_qunt">
                                    </div>
                                </div>

                            </div>


                            <div class="row clearfix">
                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.account_periods')</label>
                                    <div class="form-group">
                                        <select name="item_period_id" class="form-control"
                                                @if($employee_request->emp_request_status != 2) disabled @endif>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($account_periods as $account_period)
                                                <option value="{{ $account_period->acc_period_id }}"
                                                        @if($employee_request->stopWorkingDetails->item_period_id ==
                                                         $account_period->acc_period_id) selected @endif>
                                                    {{ app()->getLocale() ? $account_period->acc_period_name_ar :
                                                     $account_period->acc_period_name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.stop_working_reasons')</label>
                                    <select class="form-control" name="item_reasons"
                                            @if($employee_request->emp_request_status != 2) disabled @endif>
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($stop_working_reasons as $stopWorkingReason)
                                            <option value="{{ $stopWorkingReason->system_code_id }}" @if($employee_request->stopWorkingDetails->item_reasons ==
                                                     $stopWorkingReason->system_code_id) selected @endif>
                                                {{ app()->getLocale() == 'ar' ? $stopWorkingReason->system_code_name_ar :
                                             $stopWorkingReason->system_code_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label>@lang('home.item_notes')</label>
                                    <div class="form-group">
                                    <textarea class="form-control" name="item_notes"
                                              @if($employee_request->emp_request_status != 2) disabled @endif>
                                        {{ $employee_request->stopWorkingDetails->item_notes }}
                                    </textarea>
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('home.manager_accept')</label>
                                        @if($employee_request->emp_request_status == 2)
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


                                <div class="col-md-6">
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

                            </div>


                            <div class="row clearfix">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('home.reason_for_rejection_or_approval_of_the_direct_manager')</label>
                                        <textarea class="form-control" name="manager_notes" required
                                                  @if($employee_request->emp_request_approved != 2) readonly @endif>
                                            {{ $employee_request->stopWorkingDetails->manager_notes ?
                                             $employee_request->stopWorkingDetails->manager_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('home.reason_for_rejection_or_approval_of_the_hr')</label>
                                        <textarea class="form-control" name="hr_notes" required
                                                  @if($employee_request->emp_request_hr_approver != 2) readonly @endif>
                                            {{ $employee_request->stopWorkingDetails->hr_notes ?  $employee_request->stopWorkingDetails->hr_notes
                                                  :''}}</textarea>
                                    </div>
                                </div>


                            </div>


                        </div>
                    </div>

                    <div class="row">
                        <div class="card-body demo-card">
                            <div class="row clearfix">
                                <button class="btn btn-primary" type="submit">@lang('home.save')</button>
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
                employee_request:{!! $employee_request->stopWorkingDetails !!},
                stopWorking_item_qunt: '',
                stopWorking_start_date: '',
                stopWorking_end_date: '',
            },
            mounted() {
                this.stopWorking_item_qunt = this.employee_request.item_qunt
                this.stopWorking_start_date = this.employee_request.item_start_date
                this.stopWorking_end_date = this.employee_request.item_end_date
            },
            methods: {
                getDiffDate() {
                    if (this.stopWorking_start_date && this.stopWorking_end_date) {
                        $.ajax({
                            type: 'GET',
                            data: {start_date: this.stopWorking_start_date, end_date: this.stopWorking_end_date},
                            url: '{{ route('requests.diffDate') }}'
                        }).then(response => {
                            this.stopWorking_item_qunt = response.days
                        })
                    }

                },
            }
        })
    </script>
@endsection