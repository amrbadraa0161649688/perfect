<div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
    <form action="{{route('employee-requests-store-reckoning-request')}}" method="post" id="reckoning_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


        {{----------------تصفيه حساب--------------------------------------}}
        <div id="reckoning-form" style="display: none">

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
                                                @change="getEmployee(); getVacationEmployee()" required>
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
                    </div>
                </div>


            </div>

            {{--بيانات الموظف--}}
            {{$slot}}

            <div class="card">

                <div class="row">
                    <div class="card-body demo-card">
                        <div class="row clearfix">


                            <div class="col-md-3">
                                <label>@lang('home.vacation_start_date')</label>
                                <div class="form-group">
                                    <input class="form-control" type="text" readonly
                                           :value="last_vacation.emp_request_start_date">
                                    <small class="text-danger" v-if="error_message">
                                        @{{ error_message }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label>@lang('home.vacation_end_date')</label>
                                <div class="form-group">
                                    <input class="form-control" type="text" readonly
                                           :value="last_vacation.emp_request_end_date">
                                    <small class="text-danger" v-if="error_message">
                                        @{{ error_message }}
                                    </small>
                                </div>

                            </div>


                            <div class="col-md-2">
                                <label>@lang('home.days')</label>
                                <div class="form-group">
                                    <input class="form-control" type="number" readonly :value="days">
                                </div>

                            </div>

                            <div class="col-md-2">
                                <label>@lang('home.months')</label>
                                <div class="form-group">
                                    <input class="form-control" type="number" :value="months" readonly>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label>@lang('home.years')</label>
                                <div class="form-group">
                                    <input class="form-control" type="number" :value="months" readonly>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <table class="table table-bordered" v-for="item in items">
                                {{--<p>@{{ index+1 }} عهده </p>--}}
                                <thead class="thead-light table-bordered">
                                <tr>
                                    <th>@lang('home.item_data')</th>
                                    <th>@lang('home.item_status')</th>
                                    <th>@lang('home.item_qunt')</th>
                                    <th>@lang('home.item_value')</th>
                                    <th>@lang('home.item_notes')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item_dt in item.details">
                                    <td>
                                        @if(app()->getLocale() == 'ar')
                                            @{{ item_dt.item_name_ar }}
                                        @else
                                            @{{ item_dt.item_name_en }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(app()->getLocale() == 'ar')
                                            @{{ item_dt.item_status_name_ar }}
                                        @else
                                            @{{ item_dt.item_status_name_en }}
                                        @endif
                                    </td>

                                    <td>@{{ item_dt.item_qunt }}</td>
                                    <td>@{{ item_dt.item_value }}</td>
                                    <td>@{{ item_dt.item_notes }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <hr>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">@lang('home.save')</button>
                    </div>
                </div>
            </div>


        </div>
    </form>
</div>