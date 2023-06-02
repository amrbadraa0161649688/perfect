@extends('Layouts.master')

@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection



@section('content')
    <div class="section-body py-3" id="app">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-md-6">
                    @if(session('company_group'))
                        <input type="text" class="form-control" readonly value="{{ app()->getLocale()=='ar' ?
                     session('company_group')['company_group_ar'] : session('company_group')['company_group_en']}}">
                    @else
                        <input type="text" class="form-control" readonly value="{{ app()->getLocale()=='ar' ?
                     auth()->user()->companyGroup->company_group_ar  :     auth()->user()->companyGroup->company_group_en}}">
                    @endif
                </div>
                <div class="col-md-6">
                    <form action="">
                        <select class="form-control" onchange="this.form.submit()" name="company_id">
                            <option value="">@lang('home.choose')</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->company_id }}"
                                        @if($company->company_id == request()->company_id) selected @endif>
                                    {{ app()->getLocale()=='ar' ? $company->company_name_ar : $company->company_name_en }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                </div>
            </div>

            {{--   جدول الاضافات --}}
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> @lang('home.add_ons') </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form action="{{ route('employees-variables-setting.storeAdd_ons') }}" method="post">
                                    @csrf
                                    <table class="table table-bordered mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.add_ons_type')</th>
                                            <th>@lang('home.salary_type')</th>
                                            <th>@lang('home.calculation_method')</th>
                                            <th>@lang('home.factor')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($variables_add_ons as $variable_add_ons)
                                            <tr>
                                                <td></td>
                                                <td>{{ $variable_add_ons->systemCodeType->system_code_name_ar }}</td>
                                                <td>{{ $variable_add_ons->systemCodeSalaryType->system_code_name_ar }}</td>
                                                <td>{{ $variable_add_ons->systemCodeMethod->system_code_name_ar }}</td>
                                                <td>{{ $variable_add_ons->emp_variables_factor }}</td>
                                                <td>
                                                    <a href="{{ route('employees-variables-setting.editAdd_ons',$variable_add_ons->emp_variables_type_id) }}"
                                                       class="btn btn-icon"
                                                       title="@lang('home.edit')">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach

                                        @if(auth()->user()->user_type_id != 1)
                                            @foreach(session('job')->permissions as $job_permission)
                                                @if($job_permission->app_menu_id == 10 && $job_permission->permission_add)
                                                    <tr v-for="(emp_variable,index) in emp_variables_adds">
                                                        <th scope="row">1</th>
                                                        <td>
                                                            <select class="form-control" required
                                                                    v-model="emp_variables_adds[index]['emp_variables_type_code']"
                                                                    name="emp_variables_type_code[]">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($system_codes_add_ons as $system_code_add_ons)
                                                                    <option value="{{ $system_code_add_ons->system_code_id }}">{{ $system_code_add_ons->system_code_name_ar }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" required
                                                                    v-model="emp_variables_adds[index]['emp_variables_salary_type']"
                                                                    name="emp_variables_salary_type[]">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($system_codes_salary_types as $system_codes_type_adds)
                                                                    <option value="{{ $system_codes_type_adds->system_code_id }}">
                                                                        {{ $system_codes_type_adds->system_code_name_ar }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="emp_variables_method[]"
                                                                    required
                                                                    v-model="emp_variables_adds[index]['emp_variables_method']">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($system_codes_methods as $system_codes_method_adds)
                                                                    <option value="{{ $system_codes_method_adds->system_code_id }}">
                                                                        {{ $system_codes_method_adds->system_code_name_ar }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" required step=any class="form-control"
                                                                   v-model="emp_variables_adds[index]['emp_variables_factor']"
                                                                   name="emp_variables_factor[]"
                                                                   style="width:60px"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary"
                                                                    @click="addRowAdds()">
                                                                <i class="fa fa-plus"></i></button>
                                                            <button type="button" class="btn btn-primary"
                                                                    @click="subRowAdds(index)" v-if="index > 0">
                                                                <i class="fa fa-minus"></i></button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr v-for="(emp_variable,index) in emp_variables_adds">
                                                <th scope="row">1</th>
                                                <td>
                                                    <select class="form-control" required
                                                            v-model="emp_variables_adds[index]['emp_variables_type_code']"
                                                            name="emp_variables_type_code[]">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($system_codes_add_ons as $system_code_add_ons)
                                                            <option value="{{ $system_code_add_ons->system_code_id }}">{{ $system_code_add_ons->system_code_name_ar }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" required
                                                            v-model="emp_variables_adds[index]['emp_variables_salary_type']"
                                                            name="emp_variables_salary_type[]">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($system_codes_salary_types as $system_codes_type_adds)
                                                            <option value="{{ $system_codes_type_adds->system_code_id }}">
                                                                {{ $system_codes_type_adds->system_code_name_ar }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="emp_variables_method[]" required
                                                            v-model="emp_variables_adds[index]['emp_variables_method']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($system_codes_methods as $system_codes_method_adds)
                                                            <option value="{{ $system_codes_method_adds->system_code_id }}">
                                                                {{ $system_codes_method_adds->system_code_name_ar }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" step=any class="form-control" required
                                                           v-model="emp_variables_adds[index]['emp_variables_factor']"
                                                           name="emp_variables_factor[]"
                                                           style="width:60px"></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"
                                                            @click="addRowAdds()">
                                                        <i class="fa fa-plus"></i></button>

                                                    <button type="button" class="btn btn-danger"
                                                            @click="subRowAdds(index)" v-if="index > 0">
                                                        <i class="fa fa-minus"></i></button>
                                                </td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>

                                    @if(auth()->user()->user_type_id != 1)
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 10 && $job_permission->permission_add)
                                                <div class="row mt-3">
                                                    <div class="col-md-6 offset-2">
                                                        <button type="submit"
                                                                class="btn btn-lg btn-primary">@lang('home.add')</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="row mt-3">
                                            <div class="col-md-6 offset-2">
                                                <button type="submit"
                                                        class="btn btn-lg btn-primary">@lang('home.add')</button>
                                            </div>
                                        </div>
                                    @endif


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--   جدول الحسميات --}}
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> @lang('home.discounts') </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form action="{{ route('employees-variables-setting.storeDiscount') }}" method="post">
                                    @csrf
                                    <table class="table table-bordered mb-0">
                                        <thead style="background-color: #ece5e7">
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('home.add_ons_type')</th>
                                            <th>@lang('home.salary_type')</th>
                                            <th>@lang('home.calculation_method')</th>
                                            <th>@lang('home.factor')</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($variables_discounts as $variable_discount)
                                            <tr>
                                                <td></td>
                                                <td>{{ $variable_discount->systemCodeType->system_code_name_ar }}</td>
                                                <td>{{ $variable_discount->systemCodeSalaryType->system_code_name_ar }}</td>
                                                <td>{{ $variable_discount->systemCodeMethod->system_code_name_ar }}</td>
                                                <td>{{ $variable_discount->emp_variables_factor }}</td>
                                                <td>
                                                    <a class="btn btn-icon"
                                                       href="{{route('employees-variables-setting.editDiscount',$variable_discount->emp_variables_type_id)}}"
                                                       title="@lang('home.edit')">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if(auth()->user()->user_type_id != 1)
                                            @foreach(session('job')->permissions as $job_permission)
                                                @if($job_permission->app_menu_id == 10 && $job_permission->permission_add)
                                                    <tr v-for="(emp_variable_sub,index) in emp_variables_subs">
                                                        <th scope="row">1</th>
                                                        <td>
                                                            <select class="form-control" required
                                                                    name="emp_variables_type_code[]"
                                                                    v-model="emp_variables_subs[index]['emp_variables_type_code']">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($system_codes_discounts as $system_codes_discount)
                                                                    <option value="{{ $system_codes_discount->system_code_id }}">
                                                                        {{ $system_codes_discount->system_code_name_ar }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" required
                                                                    name="emp_variables_salary_type[]"
                                                                    v-model="emp_variables_subs[index]['emp_variables_salary_type']">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($system_codes_salary_types as $system_codes_type_adds)
                                                                    <option value="{{ $system_codes_type_adds->system_code_id }}">
                                                                        {{ $system_codes_type_adds->system_code_name_ar }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="emp_variables_method[]"
                                                                    required
                                                                    v-model="emp_variables_subs[index]['emp_variables_method']">
                                                                <option value="">@lang('home.choose')</option>
                                                                @foreach($system_codes_methods as $system_codes_method_adds)
                                                                    <option value="{{ $system_codes_method_adds->system_code_id }}">
                                                                        {{ $system_codes_method_adds->system_code_name_ar }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" step=any class="form-control"
                                                                   name="emp_variables_factor[]" required
                                                                   v-model="emp_variables_subs[index]['emp_variables_factor']"
                                                                   style="width:60px"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary"
                                                                    onclick="addRowSubs(index)">
                                                                <i class="fa fa-plus"></i></button>
                                                            <button type="button" class="btn btn-danger"
                                                                    @click="subRowSubs(index)" v-if="index>0">
                                                                <i class="fa fa-minus"></i></button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr v-for="(emp_variable_sub,index) in emp_variables_subs">
                                                <th scope="row">1</th>
                                                <td>
                                                    <select class="form-control" required
                                                            name="emp_variables_type_code[]"
                                                            v-model="emp_variables_subs[index]['emp_variables_type_code']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($system_codes_discounts as $system_codes_discount)
                                                            <option value="{{ $system_codes_discount->system_code_id }}">
                                                                {{ $system_codes_discount->system_code_name_ar }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" required
                                                            name="emp_variables_salary_type[]"
                                                            v-model="emp_variables_subs[index]['emp_variables_salary_type']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($system_codes_salary_types as $system_codes_type_adds)
                                                            <option value="{{ $system_codes_type_adds->system_code_id }}">
                                                                {{ $system_codes_type_adds->system_code_name_ar }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="emp_variables_method[]" required
                                                            v-model="emp_variables_subs[index]['emp_variables_method']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($system_codes_methods as $system_codes_method_adds)
                                                            <option value="{{ $system_codes_method_adds->system_code_id }}">
                                                                {{ $system_codes_method_adds->system_code_name_ar }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" step=any class="form-control"
                                                           name="emp_variables_factor[]" required
                                                           v-model="emp_variables_subs[index]['emp_variables_factor']"
                                                           style="width:60px"></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"
                                                            @click="addRowSubs(index)">
                                                        <i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-danger"
                                                            @click="subRowSubs(index)" v-if="index>0">
                                                        <i class="fa fa-minus"></i></button>
                                                </td>
                                            </tr>
                                        @endif

                                        </tbody>
                                    </table>

                                    @if(auth()->user()->user_type_id != 1)
                                        @foreach(session('job')->permissions as $job_permission)
                                            @if($job_permission->app_menu_id == 10 && $job_permission->permission_add)
                                                <div class="row mt-3">
                                                    <div class="col-md-6 offset-2">
                                                        <button type="submit"
                                                                class="btn btn-lg btn-primary">@lang('home.add')</button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="row mt-3">
                                            <div class="col-md-6 offset-2">
                                                <button type="submit"
                                                        class="btn btn-lg btn-primary">@lang('home.add')</button>
                                            </div>
                                        </div>
                                    @endif

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">   {{__('payable salaries')}} </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('employees-variables-setting.storeSalariesAccount')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        {{__('Calculation of payable salaries')}}
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('home.accounts')</label>
                                        <select class="selectpicker" data-live-search="true" name="co_salary_account">
                                            <option value="">{{__('choose')}}</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->acc_id}}"
                                                        @if($company_auth->co_salary_account == $account->acc_id) selected @endif>
                                                    {{app()->getLocale() =='ar' ?
                                        $account->acc_name_ar : $account->acc_name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <button type="submit" class="btn btn-primary">@lang('home.save')</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">   {{__('salaries types')}} </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{route('employees-variables-setting.storeSalaryTypesAccounts')}}"
                                  method="post">
                                @csrf
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('type')}}</th>
                                        <th scope="col">{{__('accounts')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($salary_types as $k=>$salary_type)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{app()->getLocale() == 'ar' ? $salary_type->system_code_name_ar : $salary_type->system_code_name_en}}
                                                <input type="hidden" name="system_code_id[]"
                                                       value="{{$salary_type->system_code_id}}">
                                            </td>
                                            <td>
                                                <select class="selectpicker" data-live-search="true"
                                                        name="system_code_acc_id[]">
                                                    <option value="">{{__('choose')}}</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->acc_id}}"
                                                                @if($salary_type->system_code_acc_id == $account->acc_id) selected @endif>
                                                            {{app()->getLocale() =='ar' ?
                                                $account->acc_name_ar : $account->acc_name_en}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <button type="submit" class="btn btn-primary">{{__('save')}}</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(".modal form input").click(function (event) {
            event.stopPropagation();
        });

        $(".modal form select").click(function (event) {
            event.stopPropagation();
        });

        $(".exampleApplication").each(function () {
            $(this).click(function (event) {
                event.stopPropagation();
            })
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                companies: {},
                company_group_id: '',
                emp_variables_adds: [{
                    'emp_variables_type_code': '',
                    'emp_variables_salary_type': '', 'emp_variables_method': '', 'emp_variables_factor': 0
                }],
                emp_variables_subs: [{
                    'emp_variables_type_code': '',
                    'emp_variables_salary_type': '', 'emp_variables_method': '', 'emp_variables_factor': 0
                }],
            },
            methods: {
                addRowAdds() {
                    this.emp_variables_adds.push({
                        'emp_variables_type_code': '', 'emp_variables_salary_type': '',
                        'emp_variables_method': '', 'emp_variables_factor': 0
                    })
                },
                subRowAdds(index) {
                    this.emp_variables_adds.splice(index, 1)
                },
                addRowSubs() {
                    this.emp_variables_subs.push({
                        'emp_variables_type_code': '', 'emp_variables_salary_type': '',
                        'emp_variables_method': '', 'emp_variables_factor': 0
                    })
                },
                subRowSubs(index) {
                    this.emp_variables_subs.splice(index, 1)
                },
                getCompanies() {
                    $.ajax({
                        type: 'GET',
                        data: {id: this.company_group_id},
                        url: '{{ route("api.company-group.companies") }}'
                    }).then(response => {
                        this.companies = response.data
                    })

                }
            }
        });
    </script>
@endsection
