<div>

    {{--تامين طبي--}}

    <form action="{{route('employee-requests-store-medical-insurance')}}" method="post" id="medical_request">
        @csrf

        <input type="hidden" class="emp_request_type_id" name="emp_request_type_id">


        {{----------------medical request------------------------------------------------------------------------------------}}
        <div id="medical-insurance-form" style="display: none">

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

                            {{--نوع الطلب--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.request_type')</label>
                                    <select name="item_type" class="form-control" required>
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($insuranceTypes as $insurance_type)
                                            <option value="{{$insurance_type->system_code_id}}">
                                                {{app()->getLocale() == 'en'  ?
                                                $insurance_type->system_code_name_en :
                                                 $insurance_type->system_code_name_ar}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--فئه التامين--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.insurance_category')</label>
                                    <select name="item_category" class="form-control">
                                        <option value="">@lang('home.choose')</option>
                                        @foreach($insuranceCategories as $insurance_category)
                                            <option value="{{$insurance_category->system_code_id}}">
                                                {{app()->getLocale() == 'en'  ?
                                                $insurance_category->system_code_name_en :
                                                 $insurance_category->system_code_name_ar}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--الملاحظات--}}
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label for="recipient-name"
                                           class="col-form-label"
                                           style="text-decoration: underline;">@lang('home.notes')</label>
                                    <textarea class="form-control" name="emp_request_notes"
                                              placeholder="@lang('home.notes')">

                                    </textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{--بيانات الموظف--}}
            {{$slot}}

            <div class="card">
                <div class="card-body demo-card">
                    <div class="card-header">
                        @lang('home.persons_added_to_insurance')
                    </div>
                    <div class="row clearfix">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.name')</th>
                                        <th>@lang('home.birth_date')</th>
                                        <th>@lang('home.item_relation')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr v-for="item,index in items_insurance">
                                        <td>@{{index+1}}</td>
                                        <td>
                                            <input type="text" name="item_name_ar[]"
                                                   class="form-control" placeholder="@lang('home.name')"
                                                   v-model="items_insurance[index]['item_name_ar']"
                                                   :required="items_insurance[index]['item_required']" required>
                                        </td>
                                        <td>
                                            <input type="date" name="item_date[]"
                                                   class="form-control" placeholder="@lang('home.birth_date')"
                                                   v-model="items_insurance[index]['item_date']"
                                                   :required="items_insurance[index]['item_required']" required>
                                        </td>
                                        <td>
                                            <input type="text" name="item_relation[]"
                                                   class="form-control" placeholder="@lang('home.item_relation')"
                                                   v-model="items_insurance[index]['item_relation']"
                                                   :required="items_insurance[index]['item_required']" required>
                                        </td>

                                        <td>
                                            <button type="button" @click="addInsuranceRow()"
                                                    class="btn btn-circle btn-icon-only red-flamingo">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" @click="removeRow(index)" v-if="index>0"
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

                <div class="card-footer">
                    <button class="btn btn-primary" type="submit"
                            id="submit_medical_insurance">@lang('home.save')</button>
                </div>
            </div>
        </div>
    </form>
</div>