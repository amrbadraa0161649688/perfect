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
                        <form class="card" action="{{ route('Bonds-cash.store') }}" method="post">
                            @csrf
                            <div class="card-body">

                                <div class="font-25">
                                    @lang('home.add_new_cash')
                                </div>

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
                                </div>

                                <div class="row">
                                    {{--النشاط--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.bonds_activity')</label>
                                            @if(isset($invoice))
                                                <input type="text" disabled="" value="@lang('home.invoices')"
                                                       class="form-control">
                                                <input type="hidden" name="transaction_type" value="73">
                                            @elseif(isset($trip))
                                                <input type="text" disabled="" value="@lang('home.trips')"
                                                       class="form-control">
                                                <input type="hidden" name="transaction_type" value="104">

                                            @elseif(isset($sales_inv))
                                                <input type="text" disabled="" value="{{__('purchases')}}"
                                                       class="form-control">
                                                <input type="hidden" name="transaction_type" value="65">

                                            @elseif(isset($store_transfer))
                                                <input type="text" disabled="" value="{{__('purchases transfer')}}"
                                                       class="form-control">
                                                <input type="hidden" name="transaction_type" value="67">
                                            @else

                                                <input type="hidden" name="transaction_type"
                                                       v-model="transaction_type">

                                                <select class="form-control"
                                                        v-model="app_menu_id"
                                                        @change="addTransactionType();getDeservedValue(); getMaintenanceCardList();
                                                     reference_number =''">
                                                    <option value="" selected>@lang('home.choose')</option>
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

                                            @if(isset($invoice))
                                                <input type="text" disabled="" value="{{$invoice->invoice_no}}"
                                                       class="form-control">

                                                <input type="hidden" value="{{$invoice->invoice_no}}"
                                                       class="form-control" name="bond_ref_no">
                                            @elseif(isset($trip))
                                                <input type="text" disabled="" value="{{$trip->trip_hd_code}}"
                                                       class="form-control">

                                                <input type="hidden" value="{{$trip->trip_hd_code}}"
                                                       class="form-control" name="bond_ref_no">

                                            @elseif(isset($sales_inv))
                                                <input type="text" disabled="" value="{{$sales_inv->store_hd_code}}"
                                                       class="form-control">

                                                <input type="hidden" value="{{$sales_inv->store_hd_code}}"
                                                       class="form-control" name="bond_ref_no">

                                            @elseif(isset($store_transfer))
                                                <input type="text" disabled="" value="{{$store_transfer->store_hd_code}}"
                                                       class="form-control">

                                                <input type="hidden" value="{{$store_transfer->store_hd_code}}"
                                                       class="form-control" name="bond_ref_no">

                                            @else
                                                <input type="text" class="form-control" v-model="reference_number"
                                                       @change="getDeservedValue()" name="bond_ref_no"
                                                       :required="ref_number_required" v-if="app_menu_id != 71">

                                                <select class="form-control" v-if="app_menu_id == 71"
                                                        v-model="reference_number"
                                                        name="bond_ref_no" @change="getMaintenanceDeservedValue()">
                                                    <option value="">@lang('home.choose')</option>
                                                    <option v-for="maintenance_dt in maintenance_card_dts"
                                                            :value="maintenance_dt.mntns_cards_dt_id">
                                                        @{{ maintenance_dt.mntns_cards_amount }} =>
                                                        @{{ maintenance_dt.mntns_cards_no}}
                                                    </option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    {{--القيمه المستحقه--}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.deserved_value')</label>
                                            <input type="text" class="form-control" disabled=""
                                                   v-model="deserved_value">
                                            <small class="text text-danger" v-if="app_error_message">
                                                @{{app_error_message}}
                                            </small>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    {{--نوع الحساب--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.account_type')</label>
                                            @if(isset($invoice))
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56002)
                                                        ->where('company_group_id',$company->company_group_id)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56002)
                                                   ->where('company_group_id',$company->company_group_id)
                                                   ->first()->system_code_name_en}}">
                                                <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56002)
                                                    ->where('company_group_id',$company->company_group_id)
                                            ->first()->system_code_id}}" name="account_type">
                                            @elseif(isset($trip))
                                                <input type="text" class="form-control" disabled=""
                                                       value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56004)
                                                        ->where('company_group_id',$company->company_group_id)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56004)
                                                   ->where('company_group_id',$company->company_group_id)
                                                   ->first()->system_code_name_en}}">
                                                <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56004)
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
                                        <div class="form-group multiselect_div mt-4">
                                            <div class="form-group multiselect_div">
                                                <input type="hidden" name="customer_type" v-model="customer_type">

                                                @if(isset($invoice))
                                                    <label class="form-label">@lang('home.customers')</label>

                                                    <input type="text" disabled="" class="form-control"
                                                           value="{{app()->getLocale()=='ar' ? $invoice->customer->customer_name_full_ar :
                                             $invoice->customer->customer_name_full_en }}">
                                                    <input type="hidden" name="customer_id"
                                                           value="{{ $invoice->customer->customer_id }}">
                                                @elseif(isset($trip))
                                                    <label class="form-label">@lang('home.truck')</label>

                                                    <input type="text" disabled="" class="form-control"
                                                           value="{{ $trip->truck->truck_code .
                                                              $trip->truck->truck_name }}">


                                                    <input type="hidden" name="bond_car_id"
                                                           value="{{ $trip->truck->truck_id }}">
                                                @else

                                                    <label v-if="trip_active">@lang('home.truck')</label>

                                                    <v-autocomplete v-if="customers.length > 0"
                                                                    required
                                                                    v-model="customer_id"
                                                                    name="customer_id"
                                                                    :items="customers"
                                                                    item-value="customer_id"
                                                                    @if(app()->getLocale()=='ar') item-text="customer_name_full_ar"
                                                                    @else item-text="customer_name_full_en" @endif
                                                                    label="@lang('home.customers')"
                                                                    @change="getCustomerAccount()"
                                                    ></v-autocomplete>

                                                    <v-autocomplete v-else-if="employees.length > 0"
                                                                    required
                                                                    v-model="emp_id"
                                                                    name="emp_id"
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
                                                                    name="branch_id"
                                                                    :items="branches"
                                                                    item-value="branch_id"
                                                                    @if(app()->getLocale()=='ar') item-text="branch_name_ar"
                                                                    @else item-text="branch_name_en" @endif
                                                                    label="@lang('home.branches')"
                                                                    @change="getRelatedAccount()"
                                                    ></v-autocomplete>

                                                    <input type="text" readonly v-else-if="trip_active"
                                                           class="form-control"
                                                           :value="this.trip_truck.truck_code + this.trip_truck.truck_name">

                                                    <v-autocomplete v-else-if="cars.length > 0"
                                                                    required
                                                                    v-model="bond_car_id"
                                                                    :items="cars"
                                                                    item-value="truck_id"
                                                                    item-text="truck_name"
                                                                    label="@lang('home.truck')"
                                                                    @change="getRelatedAccount()"

                                                    ></v-autocomplete>

                                                    <v-autocomplete label="@lang('home.choose')"
                                                                    v-else></v-autocomplete>

                                                    <input type="hidden" name="bond_car_id"
                                                           v-model="bond_car_id">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{--رقم الحساب --}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.account_code')</label>
                                            @if(isset($invoice))
                                                <input type="text" disabled="" class="form-control"
                                                       value="{{ $invoice->customer->account->acc_code .' '. $invoice->customer->account->acc_name_ar}}">
                                            @elseif(isset($trip))
                                                <input type="text" disabled=""
                                                       :value="related_account_obj.acc_name_ar"
                                                       class="form-control"
                                                       required>
                                                <input type="hidden" class="form-control" v-model="related_account"
                                                       name="bond_acc_id" required>
                                            @else
                                                <input type="hidden" class="form-control" v-model="account_number"
                                                       name="bond_acc_id" required>

                                                <input type="text" disabled="" class="form-control"
                                                       :value="account_number_obj.acc_name_ar"
                                                       name="">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{--انواع المصروفات--}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.cash_types')</label>
                                            <select class="selectpicker" data-live-search="true"
                                                    name="bond_doc_type" v-model="system_code_type" required
                                                    @change="getRelatedAccount()">
                                                @foreach($system_code_types as $system_code)
                                                    <option value="{{$system_code->system_code_id}}">
                                                        {{ app()->getLocale()=='ar' ?
                                                    $system_code->system_code_name_ar :     $system_code->system_code_name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--طرق الدفع--}}
                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.payment_method')</label>
                                            <select class="form-control" v-model="payment_method_code"
                                                    @change="validInputs()" name="bond_method_type" required>
                                                <ooption value="">@lang('home.choose')</ooption>
                                                @foreach($payment_methods as $payment_method)
                                                    <option value="{{ $payment_method->system_code }}">{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{--رقم العمليه--}}
                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.process_number')</label>
                                            <input type="text" class="form-control" :disabled="process_number_valid"
                                                   v-model="process_number" name="process_number"
                                                   :required="!process_number_valid">
                                        </div>
                                    </div>

                                    {{--البنك--}}
                                    <div class="col-sm-6 col-md-3">
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
                                    {{--الحساب المرتبط--}}
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label class="form-label">@lang('home.related_account')</label>
                                            <input type="text" disabled=""
                                                   :value="related_account_obj.acc_name_ar"
                                                   class="form-control"
                                                   required>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    {{--القيمه--}}
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.value')</label>
                                            <input type="number" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   v-model="bond_amount_credit"
                                                   :readonly="Object.keys(maintenance_card).length > 0"
                                                   name="bond_amount_credit" value="0.00" step="0.01" required>
                                        </div>
                                    </div>

                                    {{--نسبه الضريبه--}}
                                    <div class="col-sm-6 col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.vat_rate')</label>
                                            <input type="text" class="form-control"
                                                   style="font-size: 16px ;font-weight: bold"
                                                   v-model="bond_vat_rate"
                                                   :readonly="Object.keys(maintenance_card).length > 0"
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
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <label class="form-label">@lang('home.notes')</label>
                                        <textarea class="form-control" name="bond_notes"
                                                  style="font-size: 16px ;font-weight: bold"
                                                  placeholder="@lang('home.notes')"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mb-3"
                                                id="submit">@lang('home.save')</button>

                                        <div class="spinner-border" role="status" style="display: none">
                                            <span class="sr-only">Loading...</span>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-right">

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
            console.log(output)
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
                deserved_value: 0,
                app_menu_id: '',
                reference_number: '',
                app_error_message: '',
                system_code_id: '',

                employees: [],
                customers: [],
                branches: [],
                cars: [],

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

                bond_amount_credit: 0,
                bond_vat_rate: 0,
                maintenance_card_dts: {},
                account_number_obj: {},
                related_account_obj: {},
                maintenance_card: {},
                trip_active: false,
                trip_truck: {},
                transaction_type: '',
                bond_car_id: ''


            },
            methods: {
                addTransactionType() {
                    if (this.app_menu_id == 71) {
                        this.transaction_type = 71
                    }
                    if (this.app_menu_id == 73) {
                        this.transaction_type = 73
                    }
                    if (this.app_menu_id == 104) {
                        this.transaction_type = 104
                    }
                },
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
                getMaintenanceCardList() {
                    this.maintenance_card_dts = {}
                    if (this.app_menu_id == 71) {
                        $.ajax({
                            type: 'GET',
                            url: '{{ route("Bonds-capture.getMaintenanceCardList") }}'
                        }).then(response => {
                            this.maintenance_card_dts = response.data
                        })
                    }
                },
                getMaintenanceDeservedValue() {
                    this.deserved_value = 0
                    this.bond_amount_credit = 0
                    this.bond_vat_rate = 0
                    this.maintenance_card = {}

                    $.ajax({
                        type: 'GET',
                        data: {reference_number: this.reference_number},
                        url: '{{ route("Bonds-capture.getMaintenanceCardDtValue") }}'
                    }).then(response => {
                        this.maintenance_card = response.data
                        this.deserved_value = this.maintenance_card.mntns_cards_amount
                        this.bond_amount_credit = this.maintenance_card.mntns_cards_item_amount
                        this.bond_vat_rate = this.maintenance_card.mntns_cards_vat_value
                    })

                },

                getDeservedValue() {
                    this.deserved_value = 0
                    this.bond_amount_credit = 0
                    this.bond_vat_rate = 0
                    this.maintenance_card = {}

                    this.app_error_message = ''
                    this.bond_car_id = ''
                    this.customer_id = ''
                    this.trip_truck = {}
                    this.trip_active = false

                    if (this.reference_number && this.app_menu_id && this.app_menu_id != 71 && this.app_menu_id != 104) {
                        $.ajax({
                            type: 'GET',
                            data: {app_menu_id: this.app_menu_id, reference_number: this.reference_number},
                            url: '{{ route("Bonds-capture.getDeservedValue") }}'
                        }).then(response => {
                            if (response.status == 500) {
                                this.app_error_message = response.message
                            } else {
                                this.deserved_value = response.data
                                this.system_code_id = response.system_code_id
                                this.customer_id = response.customer.customer_id
                                this.account_number = response.customer.customer_account_id
                                this.getAccountList()
                            }

                        })
                    }

                    if (this.app_menu_id == 104 && this.reference_number) { ///بيان الترحيل
                        $.ajax({
                            type: 'GET',
                            data: {trip_hd_code: this.reference_number},
                            url: '{{ route("Bonds-capture.GetTripHd") }}'
                        }).then(response => {
                            this.system_code_id = response.system_code.system_code_id
                            this.trip_truck = response.truck
                            this.trip_active = true
                            this.bond_car_id = this.trip_truck.truck_id
                            // this.account_number = response.system_code.system_code_acc_id
                        });
                    }

                },
                getRelatedtruck() {
                    this.bond_car_id = ''
                    $.ajax({
                        type: 'GET',
                        data: {system_code_id: this.system_code_id},
                        url: '{{ route("Bonds-capture.getAccountList") }}'
                    }).then(response => {
                        this.cars = response.cars
                        this.bond_car_id = response.cars.truck_id
                    })
                },

                getAccountList() {
                    this.customers = []
                    this.employees = []
                    this.branches = []
                    this.cars = []
                    this.bond_car_id = ''
                    // this.account_number = ''
                    this.account_number = ''
                    this.account_number_obj = {}
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
                        } else if (response.suppliers) {
                            this.customers = response.suppliers
                        } else if (response.cars) {
                            this.cars = response.cars
                            this.bond_car_id = response.cars.truck_id
                        }

                    })
                },
                getCustomerAccount() {
                    this.account_number = ''
                    this.account_number_obj = {}
                    //عميل او مورد
                    if (this.customer_id) {
                        $.ajax({
                            type: 'GET',
                            data: {customer_id: this.customer_id},
                            url: '{{ route("Bonds-capture.getCustomerAccount") }}'
                        }).then(response => {
                            this.account_number = response.data
                            this.account_number_obj = response.account
                        })
                    }

                },
                getRelatedAccount() {

                    this.related_account = ''
                    this.related_account_obj = ''

                    if (this.emp_id || this.branch_id || this.bond_car_id) {
                        this.account_number = ''
                        this.account_number_obj = {}
                    }

                    if (this.system_code_type) {
                        $.ajax({
                            type: 'GET',
                            data: {system_code_id: this.system_code_type},
                            url: '{{ route("Bonds-capture.getRelatedAccount") }}'
                        }).then(response => {
                            this.related_account = response.data
                            this.related_account_obj = response.account
                            if (this.emp_id || this.branch_id || this.bond_car_id) {
                                this.account_number = response.data
                                this.account_number_obj = response.account
                            }

                            if (this.app_menu_id == 104) {
                                this.account_number_obj = response.account
                                this.account_number = response.account.acc_id
                            }

                        })
                    }

                }
            },
            computed: {
                bond_vat_amount: function () {
                    if (this.bond_amount_credit || this.bond_vat_rate) {
                        return parseFloat(this.bond_vat_rate) * parseFloat(this.bond_amount_credit)
                    } else {
                        return 0;
                    }

                },
                bond_amount_total: function () {
                    return parseFloat(this.bond_amount_credit) + this.bond_vat_amount
                },
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
                    if (this.customer_id) {
                        return 'customer'
                    }
                    if (this.branch_id) {
                        return 'branch'
                    }
                    if (this.bond_car_id) {
                        return 'car'
                    }
                }
            }
        });
    </script>
@endsection
