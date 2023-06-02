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

    <div id="app">
        <div class="section-body mt-3">
            <div class="container-fluid">

                <div class="tab-content mt-3">
                    {{-- dATA --}}
                    <div class="tab-pane fade active show"
                         id="data-grid" role="tabpanel">

                        <form action="{{route('Bonds-cash.update',$bond->bond_id)}}" method="post">
                            @csrf
                            @method('put')
                            <div class="row clearfix">
                                <div class="col-lg-12">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.bond_code')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{$bond->bond_code}}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.sub_company')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ app()->getLocale()=='ar' ? $bond->company->company_name_ar :
                                           $bond->company->company_name_en }}">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.branch')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ app()->getLocale()=='ar' ? $bond->
                                           branch->branch_name_ar : $bond->branch->branch_name_en }}">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.created_date')</label>
                                                    <input type="text" value="{{ $bond->bond_date }}"
                                                           class="form-control"
                                                           disabled="">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.user')</label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                                $bond->userCreated->user_name_en }}">
                                                </div>
                                            </div>

                                            @if($bond->bond_ref_no)
                                                {{--النشاط--}}
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.bonds_activity')</label>
                                                        <input type="text" disabled=""
                                                               @if($bond->transactionType) value="{{ app()->getLocale()=='ar' ?
                                     $bond->transactionType->app_menu_name_ar : $bond->transactionType->app_menu_name_en }}"
                                                               @endif    class="form-control">

                                                    </div>
                                                </div>

                                                {{--الرقم المرجعي--}}
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.reference_number')</label>
                                                        <input type="text" class="form-control" disabled=""
                                                               value="{{ $bond->bond_ref_no }}" name="bond_ref_no"
                                                               required>
                                                    </div>
                                                </div>

                                            @endif


                                            {{--نوع الحساب--}}
                                            <div class="col-sm-3 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.account_type')</label>
                                                    @if(($bond->customer_type == 'customer' || $bond->customer_type == 'supplier') && $bond->bond_ref_no)
                                                        @if($bond->customer && $bond->customer->cus_type)
                                                            <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                 $bond->customer->cus_type->system_code_name_ar : $bond->customer->cus_type->system_code_name_en }}">
                                                        @endif
                                                    @elseif($bond->customer_type == 'employee' && $bond->bond_ref_no)
                                                        <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'موظف' : 'employee'}}">
                                                    @elseif($bond->customer_type == 'car' && $bond->bond_ref_no)
                                                        <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'شاحنه' : 'truck'}}">
                                                    @elseif($bond->customer_type == 'branch' && $bond->bond_ref_no)
                                                        <input type="text" disabled="" class="form-control" value="{{app()->getLocale()=='ar' ?
                                          'فرع' : 'branch'}}">
                                                    @elseif(!$bond->bond_ref_no)

                                                        <select class="form-control" required
                                                                @change="getAccountList()" name="account_type"
                                                                v-model="system_code">
                                                            <option value="">@lang('home.choose')</option>
                                                            @foreach($account_types as $account_type)
                                                                <option value="{{ $account_type->system_code }}">
                                                                    {{ app()->getLocale()=='ar' ? $account_type->system_code_name_ar :
                                                                         $account_type->system_code_name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-3 col-md-4">
                                                <div class="form-group">

                                                    @if(($bond->customer_type == 'customer' || $bond->customer_type == 'supplier') && $bond->bond_ref_no )
                                                        <label class="form-label">@lang('home.customer')</label>

                                                        <input type="text" disabled="" class="form-control" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->customer_name_full_ar :  $bond->customer->customer_name_full_en }}">
                                                    @elseif($bond->customer_type == 'employee' && $bond->bond_ref_no)
                                                        <label class="form-label">@lang('home.employee')</label>

                                                        <input type="text" class="form-control" disabled="" value="{{ app()->getLocale()=='ar' ?
                                         $bond->customer->emp_name_full_ar : $bond->customer->emp_name_full_en }}">

                                                    @elseif($bond->customer_type == 'branch' && $bond->bond_ref_no)
                                                        <label class="form-label">@lang('home.branch')</label>

                                                        <input type="text" class="form-control" disabled="" value="{{ app()->getLocale()=='ar' ?
                                         $bond->branch->branch_name_ar : $bond->branch->branch_name_en }}">
                                                    @elseif($bond->customer_type == 'car' && $bond->bond_ref_no)
                                                        <label class="form-label">@lang('home.truck')</label>

                                                        <input type="text" disabled="" class="form-control"
                                                               value="{{$bond->truck ? $bond->truck->truck_code .$bond->truck->truck_name : ''  }}">
                                                    @elseif(!$bond->bond_ref_no)

                                                        <label class="form-label">@lang('home.customer')</label>

                                                        <select class="form-control" v-model="customer_id"
                                                                name="customer_id" @change="getCustomerAccount()"
                                                                v-if="customers.length > 0">
                                                            <option value=""></option>
                                                            <option v-for="customer in customers"
                                                                    :value="customer.customer_id">
                                                                @{{customer.customer_name_full_ar}}
                                                            </option>
                                                        </select>

                                                        <select class="form-control" v-model="customer_id"
                                                                name="customer_id" @change="getRelatedAccount()"
                                                                v-else-if="employees.length > 0">
                                                            <option value=""></option>
                                                            <option v-for="employee in employees"
                                                                    :value="employee.emp_id">
                                                                @{{employee.emp_name_full_ar}}
                                                            </option>
                                                        </select>

                                                        <select class="form-control" v-model="customer_id"
                                                                name="customer_id" @change="getRelatedAccount()"
                                                                v-else-if="branches.length > 0">
                                                            <option value=""></option>
                                                            <option v-for="branch in branches"
                                                                    :value="branch.branch_id">
                                                                @{{branch.branch_name_ar}}
                                                            </option>
                                                        </select>


                                                        <select class="form-control"
                                                                name="bond_car_id" @change="getRelatedAccount()"
                                                                v-else-if="cars.length > 0" v-model="bond_car_id">
                                                            <option value=""></option>
                                                            <option v-for="car in cars"
                                                                    :value="car.truck_id">
                                                                @{{car.truck_name}}
                                                            </option>
                                                        </select>


                                                        <select class="form-control" v-else>
                                                            <option></option>
                                                        </select>

                                                    @endif
                                                </div>
                                            </div>

                                            {{-- رقم الحساب --}}
                                            <div class="col-sm-3 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.account_code')</label>
                                                    @if($bond->account && $bond->bond_ref_no)
                                                        @if(app()->getLocale()=='ar')
                                                            <input type="text" class="form-control" disabled=""
                                                                   name="bond_acc_id" value="{{ $bond->account->acc_code
                                            ? $bond->account->acc_code .$bond->account->acc_name_ar : ''  }}">
                                                        @else
                                                            <input type="text" class="form-control" disabled=""
                                                                   name="bond_acc_id" value="{{ $bond->account->acc_code
                                            ? $bond->account->acc_code .$bond->account->acc_name_en : ''  }}">
                                                        @endif
                                                    @else
                                                        <input type="text" class="form-control" disabled=""
                                                               v-if="account_number_obj"
                                                               :value="account_number_obj.acc_code + account_number_obj.acc_name_ar">
                                                    @endif
                                                </div>
                                            </div>

                                            @if($bond->journalCash)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.journal')</label>
                                                        <a href="{{route('journal-entries.show',$bond->journalCash->journal_hd_id)}}"
                                                           class="btn btn-primary btn-block">{{$bond->journalCash->journal_hd_code}}</a>
                                                    </div>
                                                </div>
                                            @endif

                                            {{--انواع المصروفات--}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.cash_types')</label>
                                                    <select class="form-control" data-live-search="true"
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


                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.related_account')</label>
                                                    <input type="hidden" v-model="related_account_obj.acc_id"
                                                           name="bond_acc_id">

                                                    <input type="text" disabled="" class="form-control"
                                                           :value="related_account_obj.acc_name_ar"
                                                           name="">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.payment_method')</label>
                                                    <select class="form-control" v-model="payment_method_code"
                                                            @change="validInputs()" name="bond_method_type" required
                                                            :disabled="payment_disabled">
                                                        <ooption value="">@lang('home.choose')</ooption>
                                                        @foreach($payment_methods as $payment_method)
                                                            <option value="{{ $payment_method->system_code }}"
                                                                    @if($bond->bond_method_type == $payment_method->system_code)
                                                                    selected @endif>{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            {{--رقم العمليه--}}
                                            <div class="col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.process_number')</label>
                                                    <input type="text" class="form-control" name="bond_check_no"
                                                           v-model="process_number" :disabled="process_number_valid"
                                                           :required="!process_number_valid">
                                                </div>
                                            </div>

                                            {{--البنك--}}
                                            <div class="col-sm-6 col-md-6">
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

                                            {{--القيمه بدون الضريبه--}}
                                            <div class="col-sm-3 col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.value')</label>
                                                    <input type="text" class="form-control"
                                                           id="bond_amount_with_out_vat" readonly
                                                           value="{{ $bond->bond_amount_credit - $bond->bond_vat_amount}}">
                                                </div>
                                            </div>

                                            {{--نسبه اضريبه--}}
                                            <div class="col-sm-3 col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.vat_rate')</label>
                                                    <input type="text" class="form-control"
                                                           id="bond_vat_rate" readonly
                                                           value="{{ $bond->bond_vat_rate}}" name="bond_vat_rate">
                                                </div>
                                            </div>

                                            {{--قيمه اضريبه--}}
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.vat_amount')</label>
                                                    <input type="text" class="form-control" id="bond_vat_amount"
                                                           readonly
                                                           value="{{ $bond->bond_vat_amount}}"
                                                           name="bond_vat_amount">
                                                </div>
                                            </div>

                                            {{-- الاجمالي شامل الضريبه--}}
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">@lang('home.total_value')</label>
                                                    <input type="text" class="form-control"
                                                           id="bond_amount_credit" readonly
                                                           value="{{ $bond->bond_amount_credit}}"
                                                           name="bond_amount_credit">
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-6">
                                                <label class="form-label">@lang('home.notes')</label>
                                                <textarea class="form-control" name="bond_notes">
                                           {{ $bond->bond_notes ? $bond->bond_notes : '' }}
                                           </textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-primary">{{__('edit')}}</button>
                                </div>
                            </div>
                        </form>

                        @if(!$bond->journalCash && $flag == 1)
                            <div class="row mb-5">
                                <div class="col-md-12">
                                    <form action="{{route('Bonds-cash.approveOneBond')}}" method="post"
                                          style="float:left">
                                        <input type="hidden" name="bond_id" value="{{$bond->bond_id}}">
                                        <button type="submit" class="btn btn-primary btn-lg">{{__('approve')}}</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#bond_amount_with_out_vat').keyup(function () {
                $('#bond_vat_amount').val($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val())
                $('#bond_amount_credit').val(parseFloat($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val()) + parseFloat($('#bond_amount_with_out_vat').val()))
            });

            $('#bond_vat_rate').keyup(function () {
                $('#bond_vat_amount').val($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val())
                $('#bond_amount_credit').val(parseFloat($('#bond_amount_with_out_vat').val() * $('#bond_vat_rate').val()) + parseFloat($('#bond_amount_with_out_vat').val()))
            })
        })

    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                bond: {},
                bond_id: '',
                payment_method_code: '',
                process_number_valid: true,
                process_number: '',
                payment_disabled: true,
                bank_valid: true,
                bank: '',
                system_code_type: '',
                system_code: '',
                customers: [],
                employees: [],
                branches: [],
                cars: [],
                bond_car_id: '',
                // this.account_number = ''
                account_number: '',
                account_number_obj: {},
                related_account_obj: {},
                related_account: '',
                customer_id: ''
            },
            mounted() {
                this.bond_id = {!! $id !!}
                    this.getBond()
            }
            ,
            methods: {
                getBond() {
                    this.payment_disabled = true
                    if (this.bond_id) {
                        $.ajax({
                            type: 'GET',
                            data: {bond_id: this.bond_id},
                            url: ''
                        }).then(response => {

                            this.bond = response.data
                            this.payment_method_code = this.bond.bond_method_type

                            this.customer_id = response.data.customer_id

                            this.system_code_type = this.bond.bond_doc_type
                            this.account_number_obj = response.account

                            this.getRelatedAccount()
                            this.system_code = response.account_type
                            this.getAccountList()

                            if (this.payment_method_code == 57001 || this.payment_method_code == 57002 || this.payment_method_code == 57003 || this.payment_method_code == 57004 || this.payment_method_code == 57005 || this.payment_method_code == 57006) {
                                this.payment_disabled = false
                            } else {
                                this.payment_disabled = true
                            }

                            this.process_number = this.bond.bond_check_no
                            if (this.process_number && this.payment_method_code == 57002 || this.process_number && this.payment_method_code == 57003) {
                                this.process_number_valid = false
                            } else {
                                this.process_number_valid = true
                            }
                            this.bank = this.bond.bond_bank_id
                            this.system_code_type = this.bond.bond_doc_type
                        })
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
                getAccountList() {
                    this.customers = []
                    this.employees = []
                    this.branches = []
                    this.cars = []
                    this.bond_car_id = ''

                    $.ajax({
                        type: 'GET',
                        data: {system_code: this.system_code},
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
                getRelatedAccount() {
                    this.related_account = ''
                    this.related_account_obj = ''

                    if (this.emp_id || this.branch_id || this.car_id) {
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
                            if (this.emp_id || this.branch_id || this.car_id) {
                                this.account_number = response.data
                                this.account_number_obj = response.account
                            }

                        })
                    }

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

            }

        })
        ;
    </script>
@endsection
