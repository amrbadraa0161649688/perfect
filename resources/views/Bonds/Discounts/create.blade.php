@extends('Layouts.master')
@section('style')

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">

    <style lang="">
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
                        <form class="card" action="{{ route('Bonds-discount.store') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.sub_company')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   value="{{ app()->getLocale()=='ar' ? $company->company_name_ar : $company->company_name_en }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.branch')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   value="{{ app()->getLocale()=='ar' ? $branch->branch_name_ar : $branch->branch_name_en }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.created_date')</label>
                                            <input type="text" id="date" class="form-control" disabled="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.user')</label>
                                            <input type="text" class="form-control" disabled="" placeholder="Company"
                                                   value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                                        </div>
                                    </div>

                                    {{--النشاط--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.bonds_activity')</label>
                                            @if(isset($contract))
                                                <input type="text" disabled="" value="@lang('home.car_rent')"
                                                       class="form-control">
                                                <input type="hidden" name="transaction_type" value="44">

                                            @else
                                                <select class="form-control" name="transaction_type"
                                                        v-model="app_menu_id" @change="getDeservedValue()">
                                                    <option value=""></option>
                                                    @foreach($applications as $application)
                                                        @foreach($application->applicationMenuVoucher as $application_menu)
                                                            <option value="{{ $application_menu->app_menu_id }}">
                                                                {{ app()->getLocale()=='ar' ?  $application_menu->app_menu_name_ar
                                                                : $application_menu->app_menu_name_en }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    {{--الرقم المرجعي--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.reference_number')</label>
                                            @if(isset($contract))
                                                <input type="text" disabled="" value="{{$contract->contract_code}}"
                                                       class="form-control">

                                                <input type="hidden" value="{{$contract->contract_code}}"
                                                       class="form-control" name="bond_ref_no">
                                            @else
                                                <input type="text" class="form-control" v-model="reference_number"
                                                       @change="getDeservedValue()" name="bond_ref_no"
                                                       :required="ref_number_required">
                                            @endif
                                        </div>
                                    </div>

                                    {{--القيمه المستحقه--}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.deserved_value')</label>
                                            <input type="text" disabled="" value="0" class="form-control">

                                        </div>
                                    </div>

                                    {{--نوع الحساب--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.account_type')</label>
                                            @if(isset($contract))
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_en}}">

                                                <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56002)
                                                ->where('company_group_id',$company->company_group_id)
                                                 ->first()->system_code_id}}" name="account_type">
                                            @else
                                                <select class="form-control" v-model="system_code_id" required
                                                        @change="getAccountList()" name="account_type">
                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($account_types as $account_type)
                                                        <option value="{{ $account_type->system_code_id }}">
                                                            {{ app()->getLocale()=='ar' ? $account_type->system_code_name_ar :
                                                                 $account_type->system_code_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group multiselect_div">
                                            <div class="form-group multiselect_div">
                                                <input type="hidden" name="customer_type" v-model="customer_type">


                                                @if(isset($contract))
                                                    <label class="form-label">@lang('home.customers')</label>
                                                    <input type="text" disabled="" class="form-control"
                                                           value="{{app()->getLocale()=='ar' ? $contract->customer->customer_name_full_ar :
                                                                  $contract->customer->customer_name_full_en }}">
                                                    <input type="hidden" name="customer_id"
                                                           value="{{ $contract->customer->customer_id }}">
                                                @else

                                                    <v-autocomplete v-if="customers.length > 0"
                                                                    required
                                                                    v-model="customer_id"
                                                                    :items="customers"
                                                                    item-value="customer_id"
                                                                    item-text="customer_name_full_ar"
                                                                    label="@lang('home.customers')"
                                                                    @change="getCustomerAccount()"
                                                    ></v-autocomplete>

                                                    <v-autocomplete v-else-if="employees.length > 0"
                                                                    required
                                                                    v-model="emp_id"
                                                                    :items="employees"
                                                                    item-value="emp_id"
                                                                    @if(app()->getLocale()=='ar') item-text="emp_name_full_ar"
                                                                    @else item-text="emp_name_full_en" @endif
                                                                    label="@lang('home.employees')"
                                                                    @change="getRelatedAccount()"
                                                    ></v-autocomplete>

                                                    <v-autocomplete v-else-if="branches.length > 0"
                                                                    required
                                                                    v-model="branch_id"
                                                                    :items="branches"
                                                                    item-value="branch_id"
                                                                    @if(app()->getLocale()=='ar') item-text="branch_name_ar"
                                                                    @else item-text="branch_name_en" @endif
                                                                    label="@lang('home.branches')"
                                                                    @change="getRelatedAccount()"
                                                    ></v-autocomplete>

                                                    <v-autocomplete label="@lang('home.choose')"
                                                                    v-else></v-autocomplete>

                                                    <input type="hidden" name="customer_id"
                                                           v-model="customer_id">

                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                    {{-- قم الحساب --}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.account_code')</label>
                                            @if(isset($contract))
                                                {{--<input type="text" disabled="" class="form-control"--}}
                                                {{--@if(app()->getLocale() == 'ar')--}}
                                                {{--value="{{ $contract->customer->account->acc_code .--}}
                                                {{--$contract->customer->account->acc_name_ar  }} " @else--}}
                                                {{--value="{{ $contract->customer->account->acc_code .--}}
                                                {{--$contract->customer->account->acc_name_en  }}" @endif>--}}

                                                <input type="hidden" :value="related_account"
                                                       name="bond_account_id">

                                                @if(app()->getLocale() == 'ar')
                                                    <input type="text" disabled=""
                                                           :value="account_obj.acc_name_ar"
                                                           class="form-control"
                                                           required>
                                                @else
                                                    <input type="text" disabled=""
                                                           :value="account_obj.acc_name_en"
                                                           class="form-control"
                                                           required>

                                                @endif
                                            @else
                                                <input type="text" class="form-control" v-model="account_number"
                                                       name="bond_acc_id" required>
                                            @endif
                                        </div>
                                    </div>

                                    {{--انواع الخصومات--}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.discount_types')</label>
                                            <select class="selectpicker" data-live-search="true"
                                                    name="bond_doc_type"
                                                    v-model="system_code_type"
                                                    @change="getRelatedAccount()">
                                                @foreach($system_code_types as $system_code)
                                                    <option value="{{$system_code->system_code_id}}">
                                                        {{ app()->getLocale()=='ar' ?
                                                    $system_code->system_code_name_ar :  $system_code->system_code_name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--الحساب المرتبط--}}
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">@lang('home.related_account')</label>

                                            <input type="hidden" :value="related_account"
                                                   name="related_account">

                                            @if(app()->getLocale() == 'ar')
                                                <input type="text" disabled=""
                                                       :value="account_obj.acc_name_ar"
                                                       class="form-control"
                                                       required>
                                            @else
                                                <input type="text" disabled=""
                                                       :value="account_obj.acc_name_en"
                                                       class="form-control"
                                                       required>

                                            @endif
                                        </div>
                                    </div>


                                    {{--طرق الدفع--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.payment_method')</label>


                                            @if(isset($contract))
                                                @if(app()->getLocale() == 'ar')
                                                    <input type="text" readonly class="form-control"
                                                           value="{{\App\Models\SystemCode::where('system_code',57001)->first()->system_code_name_ar}}">
                                                @else
                                                    <input type="text" readonly class="form-control"
                                                           value="{{\App\Models\SystemCode::where('system_code',57001)->first()->system_code_name_en}}">
                                                @endif

                                                <input type="hidden" name="bond_method_type" value="57001">
                                            @else

                                                <select class="form-control" v-model="payment_method_code"
                                                        @change="validInputs()" name="bond_method_type" required>
                                                    <ooption value="">@lang('home.choose')</ooption>
                                                    @foreach($payment_methods as $payment_method)
                                                        <option value="{{ $payment_method->system_code }}">{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    {{--رقم العمليه--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.process_number')</label>
                                            <input type="text" class="form-control" :disabled="process_number_valid"
                                                   v-model="process_number" name="process_number"
                                                   :required="!process_number_valid">
                                        </div>
                                    </div>

                                    {{--البنك--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.bank')</label>
                                            <select class="form-control" name="bond_bank_id" v-model="bank"
                                                    :disabled="bank_valid" :required="!bank_valid">
                                                <option value="">@lang('home.choose')</option>
                                                @foreach($banks as $bank)
                                                    <option value="{{ $bank->system_code_id }}">
                                                        {{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :
                                                         $bank->system_code_name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--القيمه--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.value')</label>
                                            <input type="text" class="form-control"
                                                   v-model="bond_amount_debit"
                                                   name="bond_amount_debit" required>
                                        </div>
                                    </div>

                                    {{--نسبه الضريبه--}}
                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.vat_rate')</label>
                                            <input type="text" class="form-control"
                                                   v-model="bond_vat_rate"
                                                   name="bond_vat_rate">
                                        </div>
                                    </div>

                                    {{--قيمه الضريبه--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.vat_amount')</label>
                                            <input type="text" class="form-control"
                                                   v-model="bond_vat_amount"
                                                   name="bond_vat_amount" readonly>
                                        </div>
                                    </div>

                                    {{-- ااجمالي شامل الضريبه--}}
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.total_value')</label>
                                            <input type="text" class="form-control"
                                                   v-model="bond_amount_total"
                                                   name="bond_amount_total" required readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <label class="form-label">@lang('home.notes')</label>
                                        <textarea class="form-control" name="bond_notes"
                                                  placeholder="@lang('home.notes')"></textarea>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary" id="submit"
                                        :disabled="disable_button">@lang('home.save')</button>
                                <div class="spinner-border" role="status" style="display: none">
                                    <span class="sr-only">Loading...</span>
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
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function () {
            $('form').submit(function () {
                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)

        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                deserved_value: '',
                app_menu_id: '',
                reference_number: '',
                app_error_message: '',
                system_code_id: '',
                employees: [],
                customers: [],
                branches: [],
                branch_id: '',
                emp_id: '',
                customer_id: '',
                account_number: '',
                system_code_type: '',
                related_account: '',
                process_number_valid: true,
                bank_valid: true,
                payment_method_code: '',
                bank: '',
                process_number: '',
                disable_button: false,
                bond_amount_debit: 0,
                bond_vat_rate: 0,
                account_obj: {}

            },
            methods: {
                validInputs() {
                    //التقدي
                    if (this.payment_method_code == 57001) {
                        this.bank_valid = true
                        this.process_number_valid = true
                        this.bank = ''
                        this.process_number = ''
                    }
                    //مدي وفيزا وماستر
                    if (this.payment_method_code == 57002 || this.payment_method_code == 57003
                        || this.payment_method_code == 57004) {
                        this.process_number_valid = false
                        this.bank_valid = true
                        this.bank = ''
                    }
                    //تحويل
                    if (this.payment_method_code == 57005) {
                        this.bank_valid = false
                        this.process_number_valid = false
                    }


                },
                getDeservedValue() {
                    this.disable_button = false
                    this.app_error_message = '';
                    this.deserved_value = ''

                    if (this.reference_number && this.app_menu_id) {
                        $.ajax({
                            type: 'GET',
                            data: {app_menu_id: this.app_menu_id, reference_number: this.reference_number},
                            url: '{{ route("Bonds-capture.getDeservedValue") }}'
                        }).then(response => {
                            if (response.status == 500) {
                                this.app_error_message = response.message
                                this.disable_button = true
                            } else {
                                this.deserved_value = response.data
                                this.system_code_id = response.system_code_id
                                this.customer_id = response.customer.customer_id
                                this.account_number = response.customer.customer_account_id
                                this.getAccountList()
                            }

                        })
                    }

                },
                getAccountList() {
                    this.customers = []
                    this.employees = []
                    this.branches = []
                    // this.account_number = ''
                    $.ajax({
                        type: 'GET',
                        data: {system_code_id: this.system_code_id},
                        url: '{{ route("Bonds-capture.getAccountList") }}'
                    }).then(response => {
                        if (response.employees) {
                            this.employees = response.employees
                        } else if (response.customers) {
                            this.customers = response.customers
                        } else if (response.branches) {
                            this.branches = response.branches
                        }
                    })
                },
                getCustomerAccount() {
                    this.account_number = ''
                    //عميل او مورد
                    if (this.customer_id) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id},
                            url: '{{ route("Bonds-capture.getCustomerAccount") }}'
                        }).then(response => {
                            this.account_number = response.data
                        })
                    }

                },
                getRelatedAccount() {
                    this.related_account = ''
                    if (this.emp_id || this.branch_id) {
                        this.account_number = ''
                    }
                    this.account_obj = {}
                    if (this.system_code_type) {
                        $.ajax({
                            type: 'GET',
                            data: {system_code_id: this.system_code_type},
                            url: '{{ route("Bonds-capture.getRelatedAccount") }}'
                        }).then(response => {
                            this.related_account = response.data
                            this.account_obj = response.account
                            this.bond_vat_rate = response.vat_rate ? response.vat_rate : 0

                            if (this.emp_id || this.branch_id) {
                                this.account_number = response.data
                            }
                        })
                    }

                }
            },
            computed: {
                ref_number_required: function () {
                    if (this.app_menu_id) {
                        return true
                    } else {
                        return false
                    }
                },
                customer_type: function () {
                    if (this.emp_id) {
                        return 'employee'
                    }
                    if (this.branch_id) {
                        return 'branch'
                    }
                    if (this.customer_id) {
                        return 'customer'
                    }
                },
                bond_vat_amount: function () {
                    if (this.bond_amount_debit || this.bond_vat_rate) {
                         var vat = (parseFloat(this.bond_vat_rate) * parseFloat(this.bond_amount_debit)).toFixed(2)
                        return vat
                    } else {
                        return 0;
                    }

                },
                bond_amount_total: function () {
                    return parseFloat(this.bond_amount_debit) + parseFloat(this.bond_vat_amount)
                },
            }
        });
    </script>
@endsection
