<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->

    <form action="{{route('employee-requests-store-resignation-request')}}" method="post" id="resignation_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


        {{----------------تقديم استقاله--------------------------------}}
        <div id="resignation-form" style="display: none">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">@lang('home.request_data')</h3>
                </div>


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
                                            <option value="{{ $employee->emp_id }}">
                                                {{ app()->getLocale()=='ar'
                                                ? $employee->emp_name_full_ar
                                                : $employee->emp_name_full_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row clearfix">
                        {{--الملاحظات--}}
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="recipient-name"
                                       class="col-form-label"
                                       style="text-decoration: underline;">@lang('home.notes')</label>
                                <textarea class="form-control" name="emp_request_notes"
                                          placeholder="@lang('home.notes')" required>

                                    </textarea>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label for="recipient-name"
                                       class="col-form-label"
                                       style="text-decoration: underline;">@lang('home.reasons_list')</label>
                                <select class="form-control" name="item_reasons" required>
                                    <option value="">@lang('home.choose')</option>
                                    @foreach($stopWorkingReasons as $item)
                                        <option value="{{ $item->system_code_id }}">
                                            {{ app()->getLocale()=='ar' ? $item->system_code_name_ar
                                            : $item->system_code_name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{--بيانات الموظف--}}
            {{$slot}}

            <div class="card-footer">
                <button class="btn btn-primary" type="submit">@lang('home.save')</button>
            </div>


        </div>
    </form>
</div>