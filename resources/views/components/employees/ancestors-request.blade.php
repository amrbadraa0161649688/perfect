<div>
{{--طلب سلفه--}}
<!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
    <form action="{{ route('employee-requests-store-ancestors-request') }}" method="post" id="ancestors_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


        {{----------------ancestors request------------------------------------------------------------------------------------}}
        <div id="ancestors-form" style="display: none">

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

                            {{--اجمالي السلفه السابقه--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.total_previous_ancestors')</label>
                                    <input type="number" :value="employee.total_ancestors"
                                           class="form-control" readonly>
                                </div>
                            </div>


                            {{--اجمالي السلفه الجديده--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.total_ancestors')</label>
                                    <input type="number" name="item_value"
                                           class="form-control">
                                </div>
                            </div>

                            {{--موظف كفيل اول--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.sponsor_first')</label>
                                    <div class="form-group multiselect_div">
                                        <select class="selectpicker" data-live-search="true"
                                                name="sponsor_id_1" v-model="emp_id_s1"
                                                @change="getEmployee_s1()" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->emp_id }}"
                                                >
                                                    {{ app()->getLocale()=='ar' ?
                                                                $employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{--موظف كفيل ثاني--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.sponsor_second')</label>
                                    <div class="form-group multiselect_div">
                                        <select class="selectpicker" data-live-search="true"
                                                name="sponsor_id_2" v-model="emp_id_s2"
                                                @change="getEmployee_s2()" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->emp_id }}">{{ app()->getLocale()=='ar' ?
                                                                $employee->emp_name_full_ar : $employee->emp_name_full_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            {{--الفتره المحاسبيه--}}
                            {{--<div class="col-lg-4 col-md-12">--}}
                            {{--<div class="form-group">--}}
                            {{--<label for="recipient-name"--}}
                            {{--class="col-form-label"--}}
                            {{--style="text-decoration: underline;">@lang('home.account_periods')</label>--}}
                            {{--<select name="" class="form-control">--}}
                            {{--<option value="">@lang('home.value')</option>--}}
                            {{--@foreach($accountPeriods as $acc_period)--}}
                            {{--<option value="{{ $acc_period->acc_period_id }}">--}}
                            {{--{{ app()->getLocale()=='ar' ? $acc_period->acc_period_name_ar :--}}
                            {{--$acc_period->acc_period_name_en}}--}}
                            {{--</option>--}}
                            {{--@endforeach--}}
                            {{--</select>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            {{--من تاريخ--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.form')</label>
                                    <input type="date" class="form-control" name="item_start_date">
                                </div>
                            </div>

                            {{--الي تاريخ--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.to')</label>
                                    <input type="date" class="form-control" name="item_end_date">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                {{--بيانات الكفيل الاول--}}
                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">
                            {{--الوظيفه--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.job')</label>
                                    @if(app()->getLocale()=='ar')
                                        <input class="form-control" type="text" id=""
                                               :value="job_s1.job_name_ar"
                                               required readonly>
                                    @else
                                        <input class="form-control" type="text" id=""
                                               :value="job_s1.job_name_ar"
                                               required readonly>
                                    @endif
                                </div>
                            </div>

                            {{--تاريخ التعيين--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.work_start_date')</label>
                                    <input class="form-control" type="date"
                                           name="emp_work_start_date"
                                           :value="employee_s1.emp_work_start_date" readonly>
                                </div>
                            </div>

                            {{--الراتب--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.salary')</label>
                                    <input class="form-control" type="text"
                                           :value="employee_s1.basic_salary"
                                           required readonly>
                                </div>
                            </div>

                            {{--الحاله--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.social_status')</label>
                                    @if(app()->getLocale()=='ar')
                                        <input class="form-control" type="text"
                                               :value="social_status_s1.system_code_name_ar"
                                               required readonly>
                                    @else
                                        <input class="form-control" type="text"
                                               :value="social_status_s1.system_code_name_en"
                                               required readonly>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                {{--بيانات الكفيل الثاني--}}
                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">
                            {{--الوظيفه--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.job')</label>
                                    @if(app()->getLocale()=='ar')
                                        <input class="form-control" type="text" id=""
                                               :value="job_s2.job_name_ar"
                                               required readonly>
                                    @else
                                        <input class="form-control" type="text" id=""
                                               :value="job_s2.job_name_ar"
                                               required readonly>
                                    @endif
                                </div>
                            </div>

                            {{--تاريخ التعيين--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.work_start_date')</label>
                                    <input class="form-control" type="date"
                                           name="emp_work_start_date"
                                           :value="employee_s2.emp_work_start_date" readonly>
                                </div>
                            </div>

                            {{--الراتب--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.salary')</label>
                                    <input class="form-control" type="text"
                                           :value="employee_s2.basic_salary"
                                           required readonly>
                                </div>
                            </div>

                            {{--الحاله--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('home.social_status')</label>
                                    @if(app()->getLocale()=='ar')
                                        <input class="form-control" type="text"
                                               :value="social_status_s2.system_code_name_ar"
                                               required readonly>
                                    @else
                                        <input class="form-control" type="text"
                                               :value="social_status_s2.system_code_name_en"
                                               required readonly>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                {{--بيانات الموظف--}}
                {{ $slot }}


                <div class="card-footer">
                    <button class="btn btn-primary" type="submit"
                            id="submit_ancestors">@lang('home.save')</button>
                </div>


            </div>
        </div>
    </form>
</div>