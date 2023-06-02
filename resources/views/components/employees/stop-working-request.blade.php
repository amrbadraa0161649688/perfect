<div>

    {{--ايقاف عمل--}}
    <!-- He who is contented is rich. - Laozi -->

    <form action="{{route('employee-requests-store-stop-working-request')}}" method="post" id="stop_working_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


        {{----------------medical request------------------------------------------------------------------------------------}}
        <div id="stop-working-form" style="display: none">

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

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.from')</label>
                                <div class="form-group">

                                    <input type="date" name="item_start_date" class="form-control"
                                           @change="getDiffDate()"
                                           v-model="stopWorking_start_date">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.to')</label>
                                <div class="form-group">

                                    <input type="date" name="item_end_date" class="form-control"
                                           @change="getDiffDate()"
                                           v-model="stopWorking_end_date">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.stop_working_days_count')</label>
                                <div class="form-group">

                                    <input type="number" name="item_qunt" class="form-control"
                                           v-model="stopWorking_item_qunt">
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.account_periods')</label>
                                <div class="form-group">
                                    <select name="item_period_id" class="form-control">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($accountPeriods as $account_period)
                                            <option value="{{ $account_period->acc_period_id }}">
                                                {{ app()->getLocale() ? $account_period->acc_period_name_ar :
                                                 $account_period->acc_period_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.stop_working_reasons')</label>
                                <select class="form-control" name="item_reasons">
                                    <option value="">@lang('home.choose')</option>
                                    @foreach($stopWorkingReasons as $stopWorkingReason)
                                        <option value="{{ $stopWorkingReason->system_code_id }}">
                                            {{ app()->getLocale() == 'ar' ? $stopWorkingReason->system_code_name_ar :
                                         $stopWorkingReason->system_code_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <label>@lang('home.item_notes')</label>
                                <div class="form-group">
                                    <textarea class="form-control" name="item_notes">
                                    </textarea>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            {{$slot}}

            <div class="card-footer">
                <button class="btn btn-primary" type="submit">@lang('home.save')</button>
            </div>

        </div>
    </form>
</div>