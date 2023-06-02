<div>

{{--تكليف مهمه عمل--}}
<!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
    <form action="{{ route('employee-requests-store-job-assignment-request') }}" method="post"
          id="job_assignment_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">

        <div id="job-assignment-form" style="display: none">

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

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.company')</label>
                                <div class="form-group">
                                    <select name="company_id" class="form-control" v-model="jobAssignment_company_id"
                                            @change="getBranches()">
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
                                <label>@lang('home.branch')</label>
                                <div class="form-group">
                                    <select name="item_loc_id" class="form-control">
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
                                    <textarea class="form-control" name="item_notes">
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
                                           v-model="jobAssignment_start_date">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.to')</label>
                                <div class="form-group">

                                    <input type="date" name="item_end_date" class="form-control"
                                           @change="getDiffDateJobAssignment()"
                                           v-model="jobAssignment_end_date">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.stop_working_days_count')</label>
                                <div class="form-group">

                                    <input type="number" name="item_qunt" class="form-control"
                                           v-model="jobAssignment_item_qunt">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">

                            {{--قيمه سلفه للمهمه--}}
                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.predecessor_value_for_assignment')</label>
                                <div class="form-group">
                                    <input class="form-control" name="item_value_1" type="number">
                                </div>
                            </div>

                            {{--قيمه تذكره سفر--}}
                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.travel_ticket_value')</label>
                                <div class="form-group">
                                    <input class="form-control" name="item_value_2" type="number">
                                </div>
                            </div>

                            {{--قيمه التاشيرات--}}
                            <div class="col-lg-4 col-md-12">

                                <label>@lang('home.value_of_the_visa')</label>
                                <div class="form-group">
                                    <input class="form-control" name="item_value_3" type="number">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{--بيانات الموظف--}}
            {{$slot}}


            <div class="card-footer">
                <button class="btn btn-primary" type="submit"
                        id="submit_hand_over">@lang('home.save')</button>
            </div>

        </div>
    </form>
</div>
