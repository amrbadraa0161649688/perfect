<div>
{{--تسليم عهده--}}
<!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <form action="{{ route('employee-requests-store-hand-over') }}" method="post" id="hand_over_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">

        <div id="hand-over-form" style="display: none">

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
                    </div>
                </div>
            </div>


            {{$slot}}


            {{--تفاصيل العهده--}}
            <div class="card">
                <div class="card-body demo-card">
                    <div class="card-header">
                        @lang('home.hand_over_details')
                    </div>

                    <div class="row clearfix">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.item_data')</th>
                                        <th>@lang('home.item_qunt')</th>
                                        <th>@lang('home.item_value')</th>
                                        <th>@lang('home.item_status')</th>
                                        <th>@lang('home.item_notes')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item,index in hand_over">
                                        <td></td>
                                        <td>
                                            <select class="form-control" v-model="hand_over[index]['item_id']"
                                                    required name="item_id[]">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($handOverItems as $item)
                                                    <option value="{{ $item->system_code_id}}">{{ app()->getLocale()=='ar' ?
                                                      $item->system_code_name_ar : $item->system_code_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="item_qunt[]" class="form-control"
                                                   v-model="hand_over[index]['item_qunt']" required>
                                        </td>
                                        <td>
                                            <input type="number" name="item_value[]" class="form-control"
                                                   v-model="hand_over[index]['item_value']" required>
                                        </td>
                                        <td>
                                            <select class="form-control" v-model="hand_over[index]['item_status']"
                                                    name="item_status[]">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($handOverStatuses as $status)
                                                    <option value="{{ $status->system_code_id}}">{{ app()->getLocale()=='ar' ?
                                                      $status->system_code_name_ar : $status->system_code_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="item_notes[]" class="form-control"
                                                   v-model="hand_over[index]['item_notes']" required>
                                        </td>

                                        <td>
                                            <button type="button" @click="addHandOverRow()"
                                                    class="btn btn-circle btn-icon-only red-flamingo">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" v-if="index>0" @click="removeHandOverRow(index)"
                                                    class="btn btn-circle btn-icon-only yellow-gold">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary" type="submit"
                        id="submit_hand_over">@lang('home.save')</button>
            </div>

        </div>

    </form>
</div>