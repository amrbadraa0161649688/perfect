<div>
    <!-- He who is contented is rich. - Laozi -->
    {{--اخلاء طرف--}}
    <form action="{{ route('employee-requests-store-job-leave-request') }}" method="post" id="job_leave_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">

        <div id="job-leave-form" style="display: none">

            {{--بيانات الطلب--}}
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>


                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">

                            <div class="col-lg-3 col-md-12">
                                <label>@lang('home.request_code')</label>
                                <div class="form-group">

                                    <input type="text" class="form-control"
                                           name="emp_request_code"
                                           value="{{$stringNumber}}" readonly>

                                </div>
                            </div>

                            <div class="col-lg-3 col-md-12">
                                <label>@lang('home.user_name')</label>
                                <div class="form-group multiselect_div">
                                    <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                                                auth()->user()->user_name_ar : auth()->user()->user_name_en}}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-12">
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

                            <div class="col-lg-3 col-md-12">
                                <label>@lang('home.leave_job_reasons')</label>
                                <select class="form-control" name="item_reasons">
                                    <option value="">@lang('home.choose')</option>
                                    @foreach($stopWorkingReasons as $stopWorkingReason)
                                        <option value="{{ $stopWorkingReason->system_code_id }}">
                                            {{ app()->getLocale() == 'ar' ? $stopWorkingReason->system_code_name_ar :
                                         $stopWorkingReason->system_code_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                {{--بيانات الموظف--}}
                {{$slot}}

                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12">

                        <div class="card">
                            <div class="card-body">
                                <table class="table">

                                    <thead>
                                    <tr>
                                        <th>@lang('home.name')</th>
                                        <th>@lang('home.department')</th>
                                        <th>@lang('home.in_his_custody')</th>
                                        <th>@lang('home.not_in_his_custody')</th>
                                        <th>@lang('home.notes')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($systemCodeItems as $system_code_item)
                                        <tr>
                                            <td>
                                                <input type="hidden" value="{{ $system_code_item->system_code_id }}"
                                                       name="item_id[]">
                                                {{app()->getLocale()=='ar' ? $system_code_item->system_code_name_ar :
                                                    $system_code_item->system_code_name_en }}
                                            </td>

                                            <td>{{app()->getLocale()=='ar' ? $system_code_item->system_code_search :
                                            $system_code_item->system_code_search }}</td>

                                            <td>
                                                <label>@lang('home.in_his_custody')</label>
                                                <input type="checkbox" name="item_status[]" value="1"
                                                       class="subject-list{{$system_code_item->system_code_id}}"
                                                       onclick="chooseItem({{$system_code_item->system_code_id}})">
                                            </td>
                                            <td>
                                                <label>@lang('home.not_in_his_custody')</label>
                                                <input type="checkbox" name="item_status[]" value="0"
                                                       class="subject-list{{$system_code_item->system_code_id}}"
                                                       onclick="chooseItem({{$system_code_item->system_code_id}})">
                                            </td>
                                            <td>
                                                <textarea class="form-control" name="item_notes[]">
                                                </textarea>
                                            </td>

                                        </tr>
                                    @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4">
                                <p>اخلاء الطرف</p>
                            </div>
                            <div class="col-md-4">
                                <label>يخلي طرفه</label>
                                <input type="radio" name="item_result">
                            </div>
                            <div class="col-md-4">
                                <label>لا يخلي طرفه</label>
                                <input type="radio" name="item_result">
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="submit btn btn-primary">@lang('home.save')</button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </form>
</div>