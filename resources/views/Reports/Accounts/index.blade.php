@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap');

        .v-application {
            font-family: "Cairo" !important;
        }

        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')

    <div class="section-body mt-3" id="app">
        <v-app>
            <div class="container-fluid">
                <div class="row clearfix">

                    <div class="col-lg-12">
                        <form class="card">

                            <div class="card-body">
                                <h3 class="card-title">@lang('home.account_statement')</h3>
                                <div class="row">

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.companies')</label>
                                            <select class="selectpicker is-invalid" multiple data-live-search="true"
                                                    name="company_id[]" data-actions-box="true"
                                                    v-model="company_id" required @change="getAccounts();
                                                    getCostCenterDts()">
                                                @foreach($companies as $company)
                                                    <option value="{{$company->company_id}}">{{ app()->getLocale()=='ar' ?
                                                 $company->company_name_ar : $company->company_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.company_group')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   value="{{app()->getLocale()=='ar' ?
                                                $company->companyGroup->company_group_ar :
                                                 $company->companyGroup->company_group_en}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.user')</label>
                                            <input type="text" class="form-control" placeholder="Username"
                                                   value="{{ app()->getLocale() == 'ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}" disabled="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        {{-- اختيار التقرير   --}}
                                        <label>@lang('reports.report_select')</label>
                                        <select class="form-control" data-live-search="true"
                                                name="report_id" required v-model="report_id"
                                                @change="validateInputs(); getAccounts();getFormSubTypes()">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($report_acc_lists as $report_acc_lit)
                                                <option value="{{ $report_acc_lit->system_code }}"
                                                        @if(request()->statuses_id) @foreach(request()->statuses_id as
                                                     $status_id) @if($report_acc_lit->system_code_id == $status_id)
                                                        selected @endif @endforeach @endif>
                                                    {{app()->getLocale()=='ar' ? $report_acc_lit->system_code_name_ar
                                                 : $report_acc_lit->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-3" :hidden="!form_types">
                                        <label>{{__('اختيار القائمه')}}</label>
                                        <select class="form-control" name="fin_sub_type_id"
                                                v-model="fin_sub_type_id">
                                            <option value="">@lang('home.choose')</option>
                                            {{--@foreach($form_types as $form_type)--}}
                                            {{--<option value="{{$form_type->fin_sub_type_id}}">--}}
                                            {{--{{app()->getLocale()=='ar' ? $form_type->form_name_ar :--}}
                                            {{--$form_type->form_name_en}}--}}
                                            {{--</option>--}}
                                            {{--@endforeach--}}
                                            <option v-for="formType in formTypes" :value="formType.fin_sub_type_id">
                                                @{{ formType.form_name_ar }}
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.from')</label>
                                            <input type="date" class="form-control" name="from_date"
                                                   value="{{old('from_date')}}" required v-model="from_date">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.to')</label>
                                            <input type="date" class="form-control" name="to_date"
                                                   value="{{ old('to_date') }}" required v-model="to_date">
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6" :hidden="!acc_level_required">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.account_levels')</label>
                                            <select name="acc_level" class="form-control" v-model="acc_level"
                                                    @change="getAccounts();validateInputs()"
                                                    :required="acc_level_required">
                                                <option value="">@lang('home.choose')</option>
                                                <option value="0" v-if="general_report">0</option>
                                                <option value="1" v-if="trial_level || general_report">1</option>
                                                <option value="2" v-if="trial_level || general_report">2</option>
                                                <option value="3" v-if="trial_level || general_report">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6" :hidden="!acc_id_required">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.accounts')</label>
                                            <v-autocomplete
                                                    :required="acc_id_required"
                                                    v-model="acc_id"
                                                    name="acc_id"
                                                    :items="accounts"
                                                    item-value="acc_id"
                                                    item-text="acc_name_ar"
                                                    label="@lang('home.accounts')"></v-autocomplete>

                                        </div>
                                    </div>

                                    <div class="col-md-4" :hidden="!is_acc_required_2">

                                        <label>@lang('reports.branch_select')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                data-actions-box="true"
                                                name="loc_from[]" id="loc_from">
                                            @foreach($loc_lits as $loc_lit)
                                                <option value="{{ $loc_lit->system_code_id }}">
                                                    @if(app()->getLocale()=='ar')
                                                        {{$loc_lit->system_code_name_ar}}
                                                    @else
                                                        {{$loc_lit->system_code_name_en}}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-12">
                                        <button class="btn btn-primary mt-4" type="submit"
                                                :hidden="!icon_serarch_report"><i
                                                    class="fa fa-search"></i>@lang('home.search')
                                        </button>

                                    </div>

                                    <div class="col-sm-2 col-md-2" :hidden="!is_zero_required">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.is_zero')</label>
                                            <input type="checkbox" name="is_zero">
                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-md-6"
                                         :hidden="!cost_center_required">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.cost_center_type')</label>
                                            <select class="form-control" name="cost_center_id"
                                                    v-model="cost_center_id"
                                                    @change="getCostCenterDts()"
                                                    :required="cost_center_required">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($cost_center_types as $cost_center_type)
                                                    <option value="{{$cost_center_type->system_code}}">
                                                        {{app()->getLocale()=='ar' ?
                                                        $cost_center_type->system_code_name_ar :
                                                        $cost_center_type->system_code_name_en}}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>


                                    <div class="customers col-md-6" v-if="customers.length > 0"
                                         :hidden="!cost_center_required">
                                        {{--العملاء--}}
                                        <label for="recipient-name"
                                               class="form-label">
                                            @lang('home.customers')</label>

                                        <v-autocomplete
                                                :required="cost_center_required"
                                                v-model="cc_customer_id"
                                                name="cc_customer_id"
                                                :items="customers"
                                                item-value="customer_id"
                                                item-text="customer_name_full_ar"
                                                label="@lang('home.customers')"
                                        ></v-autocomplete>


                                    </div>

                                    <div class="suppliers col-md-6" v-else-if="suppliers.length > 0"
                                         :hidden="!cost_center_required">
                                        {{--الموردين--}}
                                        <label class="form-label">@lang('home.suppliers')</label>
                                        <v-autocomplete
                                                :required="cost_center_required"
                                                v-model="cc_supplier_id"
                                                name="cc_supplier_id"
                                                :items="suppliers"
                                                item-value="customer_id"
                                                item-text="customer_name_full_ar"
                                                label="@lang('home.customers')"
                                        ></v-autocomplete>


                                    </div>

                                    <div class="employees col-md-6" v-else-if="employees.length > 0"
                                         :hidden="!cost_center_required">
                                        {{--الموظفين--}}
                                        <label for="recipient-name"
                                               class="form-label">
                                            @lang('home.employees')</label>
                                        <v-autocomplete
                                                :required="cost_center_required"
                                                v-model="cc_employee_id"
                                                name="cc_employee_id"
                                                :items="employees"
                                                item-value="emp_id"
                                                item-text="emp_name_full_ar"
                                                label="@lang('home.employees')"
                                        ></v-autocomplete>


                                    </div>


                                    <div class="cars col-md-6" v-else-if="cars.length > 0"
                                         :hidden="!cost_center_required">
                                        <label class="form-label">@lang('home.cars')</label>
                                        <v-autocomplete
                                                :required="cost_center_required"
                                                v-model="cc_car_id"
                                                name="cc_car_id"
                                                :items="cars"
                                                item-value="truck_id"
                                                item-text="truck_name"
                                                label="@lang('home.cars')"
                                        ></v-autocomplete>


                                    </div>

                                    <div class="branches col-md-6" v-else-if="branches.length > 0"
                                         :hidden="!cost_center_required">
                                        <label class="form-label">@lang('home.branches')</label>
                                        <select class="form-control" name="cc_branch_id"
                                                v-model="cc_branch_id"
                                                :required="cost_center_required">
                                            <option value="">@lang('home.choose')</option>
                                            <option :value="branch.branch_id"
                                                    v-for="branch in branches">
                                                @if(app()->getLocale()=='ar')
                                                    @{{ branch.branch_name_ar }}
                                                @else
                                                    @{{ branch.branch_name_en }}
                                                @endif
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-md-6" v-else="" :hidden="!cost_center_required">
                                        <label class="form-label">@lang('home.cost_center_related')</label>
                                        <select class="form-control" name=""
                                                :required="cost_center_required">
                                            <option value="">@lang('home.choose')</option>
                                        </select>
                                    </div>


                                </div>

                                {{--الحسابات لمراكز التكلفه--}}
                                <div class="col-md-6" :hidden="!is_acc_required">
                                    {{-- acc_id  --}}
                                    <label :hidden="!is_acc_required">@lang('home.accounts')</label>
                                    <select class="selectpicker" multiple data-live-search="true" v-model="acc_id_dt"
                                            name="acc_id_dt[]" data-actions-box="true">
                                        @foreach($accounts as $accountss)
                                            <option value="{{ $accountss->acc_id }}"
                                                    @if(!request()->acc_id_dt) selected @endif
                                            >
                                                {{app()->getLocale()=='ar' ? $accountss->acc_name_ar
                                            : $accountss->acc_name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="card-footer text-center">
                                    <div class="col-md-10" v-if="error_message">
                                        <div class="alert alert-danger">
                                            <p>@{{ error_message }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right" :hidden="!icon_report">

                                    {{--<button type="button" @click="getReport()" id="submit" v-if="!submit_data"--}}
                                    {{--class="btn btn-primary">@lang('home.add_account_statement')</button> --}}

                                    <button type="button" @click="getReport()" id="submit"
                                            class="btn btn-primary">@lang('home.add_account_statement')</button>

                                    <div class="spinner-border" role="status" v-if="submit_data">
                                        <span class="sr-only">Loading...</span>
                                    </div>

                                </div>
                                <div class="row">

                                    @foreach($report_acc_branch as $report_acc_branch_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_branch_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه ارصده الصناديق</a>

                                        </div>
                                    @endforeach

                                    @foreach($report_acc_supplier as $report_acc_supplier_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_supplier_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه ميزان مراجعه الموردين</a>

                                        </div>
                                    @endforeach

                                    @foreach($report_acc_customer as $report_acc_customer_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_customer_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه ميزان مراجعه العملاء</a>

                                        </div>
                                    @endforeach

                                    @foreach($report_acc_customer_agent as $report_acc_customer_agent_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_customer_agent_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه أعمار ذمم العملاء</a>
                                        </div>
                                    @endforeach

                                    @foreach($report_acc_employee as $report_acc_employee_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_employee_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه ميزان مراجعه الموظفين</a>
                                        </div>
                                    @endforeach




                                    @foreach($report_acc_employee_rateb as $report_acc_employee_rateb_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_employee_rateb_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&acc_id_5={{implode(',',request()->input('acc_id_dt',[]))}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            > طباعه ميزان مراجعه الموظفين بالحساب</a>
                                        </div>
                                    @endforeach

                                    @foreach($report_waybill_ajel_report as $report_waybill_ajel_report_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-2" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_waybill_ajel_report_a->report_url}}&id={{implode(',',request()->input('loc_from',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه تقرير الاجل للفروع</a>
                                        </div>
                                    @endforeach
                                    @foreach($report_waybill_journal_report as $report_waybill_journal_report_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-2" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_waybill_journal_report_a->report_url}}&id={{implode(',',request()->input('loc_from',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه تقرير ايرادات الفرع</a>
                                        </div>
                                    @endforeach
                                    @foreach($report_acc_employee_solaf as $report_acc_employee_solaf_a)
                                        <div hidden class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_employee_solaf_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه السلف الموظفين</a>
                                        </div>
                                    @endforeach

                                    @foreach($report_acc_vat_report as $report_acc_vat_report_a)
                                        <div class="col-sm-3 col-xs-3 col-md-3 col-lg-2" :hidden="!showReport_required">
                                            <a href="{{config('app.telerik_server')}}?rpt={{$report_acc_vat_report_a->report_url}}&id={{implode(',',request()->input('company_id',[]))}}&date_from={{request()->from_date}}&date_to={{request()->to_date}}&lang=ar&skinName=bootstrap"
                                               class="btn btn-warning btn-block" id='showReport' target="_blank"
                                            >طباعه الاقرار الضريبي</a>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </v-app>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                company_id: [],
                acc_level: '',
                accounts: [],
                error_message: '',
                from_date: '',
                to_date: '',
                report_id: '',
                acc_id: '',
                acc_id_dt: [],
                // acc_id_dt: 0,
                submit_data: false,
                acc_level_required: false,
                acc_id_required: false,
                trial_level: false,
                is_zero_required: false,
                is_zero: 0,
                showReport: 0,
                is_acc_required: false,
                is_acc_required_2: false,
                showReport_required: true,
                cost_center_required: false,
                form_types_required: false,
                icon_serarch_report: false,
                icon_report: false,
                branches: [],
                customers: [],
                suppliers: [],
                employees: [],
                cars: [],
                cost_center_id: '',
                cc_customer_id: '',
                cc_branch_id: '',
                cc_car_id: '',
                cc_employee_id: '',
                cc_supplier_id: '',
                form_types: false,
                formTypes: [],
                fin_sub_type_id: '',
                general_report: false

            },
            methods: {
                getAccounts() {
                    this.error_message = ''
                    this.accounts = []

                    // if (this.report_id == 93001 && this.acc_level == 0) {
                    //     this.acc_id_required = false
                    // }

                    console.log(this.acc_level, this.report_id)
                    if (this.acc_level && this.company_id.length > 0 && this.report_id) {
                        if (this.report_id == 93002 || (this.report_id == 93001 && this.acc_level > 0)) {
                            $.ajax({
                                type: 'GET',
                                data: {company_id: JSON.stringify(this.company_id), acc_level: this.acc_level},
                                url: '{{ route("gl.get-company-accounts") }}'
                            }).then(response => {
                                if (response.status == 500) {
                                    this.error_message = response.message
                                } else {
                                    this.accounts = response.data
                                }

                            })
                        }
                    }
                },
                getReport() {
                    this.submit_data = true
                    this.error_message = ''
                    $.ajax({
                        type: 'POST',
                        data: {
                            _token: '{{csrf_token()}}',
                            company_id: JSON.stringify(this.company_id),
                            acc_level: this.acc_level,
                            from_date: this.from_date, to_date: this.to_date,
                            acc_id: this.acc_id, report_id: this.report_id,
                            cost_center_id: this.cost_center_id,
                            cc_customer_id: this.cc_customer_id, cc_supplier_id: this.cc_supplier_id,
                            cc_branch_id: this.cc_branch_id, cc_car_id: this.cc_car_id,
                            cc_employee_id: this.cc_employee_id,
                            fin_sub_type_id: this.fin_sub_type_id,
                            acc_id_dt: JSON.stringify(this.acc_id_dt),
                            acc_id_dts: this.acc_id_dt

                        },
                        url: '{{ route("gl.store") }}'
                    }).then(response => {
                        if (response.status == 500) {
                            this.error_message = response.message
                            this.submit_data = false
                        } else {
                            console.log(response.data)
                            this.submit_data = false
                            window.open(response.data, "_blank");
                        }

                    })
                },
                validateInputs() {
                    this.is_zero = 0;
                    this.is_acc_required = false;
                    this.branches = [];
                    this.customers = [];
                    this.suppliers = [];
                    this.employees = [];
                    this.cars = [];
                    this.showReport = true;
                    this.form_types = false;
                    this.showReport_required = false;

                    if (this.report_id == 93002) {  //الاستاذ التفصيلي
                        this.acc_level_required = true
                        this.acc_id_required = true
                        this.trial_level = false
                        this.is_zero_required = false
                        this.form_types_required = false
                        this.cost_center_required = false
                        this.form_types = false
                        this.icon_serarch_report = false
                        this.icon_report = true
                        this.general_report = false
                    }

                    if (this.report_id == 93001) {  //الاستاذ العام
                        this.acc_level_required = true

                        if (this.acc_level == 0) {
                            this.acc_id_required = false
                        } else {
                            this.acc_id_required = true
                        }

                        this.trial_level = false
                        this.is_zero_required = false
                        this.form_types_required = false
                        this.cost_center_required = false
                        this.form_types = false
                        this.icon_serarch_report = false
                        this.icon_report = true
                        this.general_report = true
                    }

                    if (this.report_id == 93013) { ////ايرادات الفروع
                        this.acc_level_required = false
                        this.acc_id_required = false
                        this.trial_level = false
                        this.is_zero_required = false
                        this.showReport_required = false
                        this.cost_center_required = false
                        this.form_types = false
                        this.icon_serarch_report = false
                        this.icon_report = true
                        this.general_report = false
                    }

                    if (this.report_id == 93006) { ////ميزان المراجعه
                        this.acc_level_required = true
                        this.acc_id_required = false
                        this.trial_level = true
                        this.is_zero_required = true
                        this.showReport_required = false
                        this.cost_center_required = false
                        this.form_types = false
                        this.icon_serarch_report = false
                        this.icon_report = true
                        this.general_report = false
                    }

                    if (this.report_id == 93005) { ///المركز التحليلي
                        this.acc_level_required = false
                        this.acc_id_required = false
                        this.trial_level = false
                        this.is_zero_required = false
                        this.is_acc_required = false
                        this.cost_center_required = true
                        this.form_types = false
                        this.icon_serarch_report = false
                        this.icon_report = true
                        this.general_report = false
                    }

                    if (this.report_id == 93008) { /// بالحساب المركز التحليلي
                        this.acc_level_required = false
                        this.acc_id_required = false
                        this.trial_level = false
                        this.is_zero_required = false
                        this.is_acc_required = true
                        this.cost_center_required = true
                        this.form_types = false
                        this.icon_serarch_report = false
                        this.icon_report = true
                        this.general_report = false
                    }


                    if (this.report_id == 93007) { ///القوائم الماليه
                        this.acc_level_required = false
                        this.acc_id_required = false
                        this.trial_level = false
                        this.is_zero_required = false

                        this.cost_center_required = false
                        this.form_types = true
                        this.icon_serarch_report = false
                        this.icon_report = true
                        this.general_report = false
                    }

                    if (this.report_id == 93009) { /// التحليلي
                        this.acc_level_required = false
                        this.acc_id_required = false
                        this.trial_level = false
                        this.is_zero_required = false
                        this.showReport_required = true
                        this.cost_center_required = false
                        this.form_types = false
                        this.is_acc_required = true
                        this.icon_serarch_report = true
                        this.icon_report = false
                        this.general_report = false
                    }

                    if (this.report_id == 93011) { /// الاجل
                        this.acc_level_required = false
                        this.acc_id_required = false
                        this.trial_level = false
                        this.is_acc_required_2 = true
                        this.is_zero_required = false
                        this.showReport_required = true
                        this.cost_center_required = false
                        this.form_types = false
                        this.icon_serarch_report = true
                        this.icon_report = false
                        this.general_report = false
                    }
                    if (this.report_id == 93012) { /// الاجل
                        this.acc_level_required = false
                        this.acc_id_required = false
                        this.trial_level = false
                        this.is_acc_required_2 = true
                        this.is_zero_required = false
                        this.showReport_required = true
                        this.cost_center_required = false
                        this.form_types = false
                        this.icon_serarch_report = true
                        this.icon_report = false
                        this.general_report = false
                    }

                },
                getFormSubTypes() {
                    if (this.report_id == 93007 || this.report_id == 93008) {
                        $.ajax({
                            type: 'GET',
                            data: {report_id: this.report_id},
                            url: '{{route("gl.getFormSubTypes")}}'
                        }).then(response => {
                            this.formTypes = response.data
                        })
                    }

                },
                getCostCenterDts() {
                    this.branches = []
                    this.customers = []
                    this.suppliers = []
                    this.employees = []
                    this.cars = []

                    if (this.company_id.length > 0 && this.cost_center_id) {
                        $.ajax({
                            type: 'GET',
                            data: {company_id: JSON.stringify(this.company_id), cost_center_id: this.cost_center_id},
                            url: '{{ route("api.accounts-reports.costCenterDts") }}'
                        }).then(response => {
                            if (response.branches) {
                                this.branches = response.branches
                            }
                            if (response.customers) {
                                this.customers = response.customers
                            }
                            if (response.suppliers) {
                                this.suppliers = response.suppliers
                            }
                            if (response.employees) {
                                this.employees = response.employees
                            }
                            if (response.trucks) {
                                this.cars = response.trucks
                            }

                        })
                    }
                },
            }
        })
    </script>
@endsection