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

        select[readonly] {
            pointer-events: none;
        }
    </style>

@endsection

@section('content')
    <div class="section-body mt-3" id="app">
        <v-app>

            <div class="container-fluid">
                <div class="row clearfix">

                    <div class="col-lg-12">

                        <form class="card" action="{{ route('bonds-capture-detailed.store') }}" method="post">
                            @csrf
                            <div class="card-body">

                                <div class="font-25">
                                    @lang('home.add_new_capture')
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


                                    {{-- ااجمالي شامل الضريبه--}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">@lang('home.total_value')</label>
                                            <input type="text" class="form-control"
                                                   v-model="net_value"
                                                   name="" required readonly>
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-8 col-md-8">
                                        <label class="form-label">@lang('home.notes')</label>
                                        <textarea class="form-control" name="bond_notes"
                                                  style="font-size: 16px ;font-weight: bold" required
                                                  placeholder="@lang('home.notes')"></textarea>
                                    </div>
                                </div>
                                <div class="card-footer text-right">

                                </div>

                            </div>

                            <div class="card">

                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th class="pl-0" style="width: 300px">@lang('home.revenue_types')</th>
                                                <th class="pl-0" style="width: 300px">@lang('home.related_account')</th>
                                                <th class="pl-0" style="width: 50px">@lang('home.account_type')</th>
                                                <th class="pl-0" style="width: 150px">@lang('home.account_name')</th>
                                                <th class="pl-0" style="width: 150px">@lang('home.invoices')</th>
                                                <th class="pl-0" style="width: 150px">@lang('home.process_number')</th>
                                                <th colspan="1" style="width: 350px">@lang('home.notes')</th>

                                                <th class="pr-0" style="width: 100px">@lang('home.value')</th>
                                                <th class="pr-0"></th>
                                            </tr>
                                            </thead>

                                            <tr v-for="(bond_dt,index) in bond_dts">

                                                {{--انواع الايرادات--}}
                                                <td>
                                                    <div class="form-group">

                                                        <select class="form-control" data-live-search="true"
                                                                name="bond_doc_type[]" required
                                                                v-model="bond_dts[index]['bond_doc_type']"
                                                                @change="getRelatedAccount(index)">
                                                            @foreach($system_code_types as $system_code)
                                                                <option value="{{$system_code->system_code_id}}">
                                                                    {{ app()->getLocale()=='ar' ?
                                                                $system_code->system_code_name_ar :     $system_code->system_code_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>

                                                {{--الحساب المرتبط--}}
                                                <td>
                                                    <div class="form-group">
                                                        @if(app()->getLocale()=='ar')
                                                            <input type="text" name="" class="form-control" readonly
                                                                   :value="bond_dts[index]['acc_name_ar'] + bond_dts[index]['acc_code']">
                                                        @else
                                                            <input type="text" name="" class="form-control" readonly
                                                                   :value="bond_dts[index]['acc_name_en'] + bond_dts[index]['acc_code']">
                                                        @endif
                                                        <input type="hidden" name="bond_acc_id[]"
                                                               v-model="bond_dts[index]['acc_id']">
                                                    </div>
                                                </td>

                                                <td class="col-md-1">
                                                    {{--نوع الحساب--}}

                                                    <select class="form-control" required
                                                            name="account_type[]" @change="getAccountList(index)"
                                                            v-model="bond_dts[index]['account_type']">
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($account_types as $account_type)
                                                            <option value="{{ $account_type->system_code_id }}">
                                                                {{ app()->getLocale()=='ar' ? $account_type->system_code_name_ar :
                                                                    $account_type->system_code_name_en }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                {{--اسم الحساب--}}
                                                <td class="col-sm-6 col-md-2">
                                                    <div class="form-group multiselect_div">
                                                        <div class="form-group multiselect_div">

                                                            <v-autocomplete
                                                                    required
                                                                    :items="bond_dts[index]['customers']"
                                                                    item-value="customer_id"
                                                                    v-model="bond_dts[index]['customer_id']"
                                                                    @if(app()->getLocale()=='ar') item-text="customer_name_full_ar"
                                                                    @else item-text="customer_name_full_en"
                                                                    @endif
                                                                    @change="getInvoiceList(index,bond_dts[index]['customer_id'])"
                                                                    label="@lang('home.customers')"
                                                                    v-if="bond_dts[index]['customers'].length > 0">
                                                            </v-autocomplete>


                                                            <v-autocomplete
                                                                    required
                                                                    :items="bond_dts[index]['suppliers']"
                                                                    item-value="customer_id"
                                                                    v-model="bond_dts[index]['supplier_id']"
                                                                    @if(app()->getLocale()=='ar') item-text="customer_name_full_ar"
                                                                    @else item-text="customer_name_full_en"
                                                                    @endif
                                                                    @change="getInvoiceList(index,bond_dts[index]['supplier_id'])"
                                                                    label="@lang('home.suppliers')"
                                                                    v-else-if="bond_dts[index]['suppliers'].length > 0"></v-autocomplete>


                                                            <v-autocomplete
                                                                    required
                                                                    :items="bond_dts[index]['employees']"
                                                                    item-value="emp_id"
                                                                    v-model="bond_dts[index]['emp_id']"
                                                                    @if(app()->getLocale()=='ar') item-text="emp_name_full_ar"
                                                                    @else item-text="emp_name_full_en"
                                                                    @endif  v-else-if="bond_dts[index]['employees'].length > 0"
                                                                    label="@lang('home.employees')"
                                                            ></v-autocomplete>


                                                            <v-autocomplete
                                                                    required
                                                                    :items="bond_dts[index]['branches']"
                                                                    item-value="branch_id"
                                                                    v-model="bond_dts[index]['branch_id']"
                                                                    @if(app()->getLocale()=='ar') item-text="branch_name_ar"
                                                                    @else item-text="branch_name_en" @endif
                                                                    v-else-if="bond_dts[index]['branches'].length > 0"
                                                                    label="@lang('home.branches')"
                                                            ></v-autocomplete>


                                                            <v-autocomplete
                                                                    v-else-if="bond_dts[index]['cars'].length > 0"
                                                                    required
                                                                    :items="bond_dts[index]['cars']"
                                                                    v-model="bond_dts[index]['car_id']"
                                                                    item-value="truck_id"
                                                                    item-text="truck_name"
                                                                    label="@lang('home.truck')"

                                                            ></v-autocomplete>


                                                            <v-autocomplete label="@lang('home.choose')"
                                                                            v-else></v-autocomplete>


                                                            <input type="hidden" name="customer_id[]"
                                                                   :value="bond_dts[index]['customer_id']">

                                                            <input type="hidden" name="supplier_id[]"
                                                                   :value="bond_dts[index]['supplier_id']">


                                                            <input type="hidden" name="bond_emp_id[]"
                                                                   :value="bond_dts[index]['emp_id']">

                                                            <input type="hidden" name="bond_branch_id[]"
                                                                   :value="bond_dts[index]['branch_id']">

                                                            <input type="hidden" name="bond_car_id[]"
                                                                   :value="bond_dts[index]['car_id']">
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <select class="form-control"
                                                                name="transaction_id[]"
                                                                :readonly="bond_dts[index]['invoice_active']"
                                                                :required="!bond_dts[index]['invoice_active']"
                                                                v-model="bond_dts[index]['invoice_id']"
                                                                @change="getInvoiceDeservedValue(index,bond_dts[index]['invoice_id'])">
                                                            <option value="0">@lang('home.choose')</option>
                                                            <option v-for="invoice,index in bond_dts[index]['invoices']"
                                                                    :value="invoice.invoice_id">
                                                                @{{invoice.invoice_no}}
                                                            </option>
                                                        </select>

                                                    </div>
                                                </td>


                                                <td>
                                                    <input type="number" class="form-control" name="bond_check_no[]"
                                                           v-model="bond_dts[index]['bond_check_no']"
                                                           :readonly="process_number_valid">
                                                </td>
                                                {{--الملاحظات--}}
                                                <td colspan="1">

                                                    <input type="text" class="form-control" name="bond_notes_dt[]"
                                                           v-model="bond_dts[index]['notes']" required>
                                                </td>

                                                {{--اجمالي القيمه--}}
                                                <td class="pr-0">
                                                    <input type="number" class="form-control"
                                                           name="bond_amount_debit[]"
                                                           step="0.01"
                                                           required v-model="bond_dts[index]['bond_amount_debit']">
                                                </td>

                                                <th class="pr-0">
                                                    <button type="button" class="btn btn-icon"
                                                            @click="addRow(index)">
                                                        <i class="fa fa-plus"></i></button>
                                                    <button type="button" class="btn btn-icon"
                                                            @click="subRow(index)" v-if="index>0">
                                                        <i class="fa fa-minus"></i></button>
                                                </th>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2">
                                                    <label> الاجمالي</label>
                                                    {{--اجمالي القيمه--}}
                                                    <input type="text" name="bond_amount_total" class="form-control"
                                                           readonly v-model="net_value">
                                                </td>


                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2 col-md-2">
                                <button type="submit" class="btn btn-primary text-white"
                                        id="submit">@lang('home.save')</button>
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

\
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
                bank_valid: true,
                process_number_valid: true,
                bank: '',
                process_number: '',
                payment_method_code: '',
                bond_dts: [{
                    'bond_doc_type': '',
                    'acc_name_ar': '',
                    'acc_name_en': '',
                    'acc_id': '',
                    'acc_code': '',
                    'account_type': '',
                    'customers': [],
                    'suppliers': [],
                    'employees': [],
                    'cars': [],
                    'branches': [],
                    'customer_id': 0,
                    'emp_id': 0,
                    'branch_id': 0,
                    'car_id': 0,
                    'supplier_id': 0,
                    'bond_check_no': 0,
                    'customer_type': '',
                    'bond_amount_debit': 0,
                    'notes': '',
                    'invoices': {},
                    'invoice_active': true,
                    'invoice_id': 0,

                }],
            },
            methods: {
                validInputs() {
                    //التقدي
                    if (this.payment_method_code == 57001) {
                        this.bank_valid = true
                        this.process_number_valid = true
                        this.bank = ''
                        this.process_number = ''
                        Object.entries(this.bond_dts).forEach(([key, val]) => {
                            val.bond_check_no = 0
                        });
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
                addRow(index) {
                    this.bond_dts.push({
                        'bond_doc_type': this.bond_dts[index]['bond_doc_type'],
                        'acc_name_ar': this.bond_dts[index]['acc_name_ar'],
                        'acc_name_en': this.bond_dts[index]['acc_name_en'],
                        'acc_id': this.bond_dts[index]['acc_id'],
                        'acc_code': this.bond_dts[index]['acc_code'],
                        'account_type': this.bond_dts[index]['account_type'],
                        'customers': this.bond_dts[index]['customers'],
                        'suppliers': this.bond_dts[index]['suppliers'],
                        'employees': this.bond_dts[index]['employees'],
                        'cars': this.bond_dts[index]['cars'],
                        'branches': this.bond_dts[index]['branches'],
                        'customer_id': this.bond_dts[index]['customer_id'],
                        'emp_id': this.bond_dts[index]['emp_id'],
                        'branch_id': this.bond_dts[index]['branch_id'],
                        'car_id': this.bond_dts[index]['car_id'],
                        'supplier_id': this.bond_dts[index]['supplier_id'],
                        'bond_check_no': 0,
                        'customer_type': this.bond_dts[index]['customer_type'],
                        'bond_amount_debit': 0,
                        'notes': '',
                        'invoices': this.bond_dts[index]['invoices'],
                        'invoice_active': this.bond_dts[index]['invoice_active'],
                        'invoice_id': 0,
                    })
                },
                subRow(index) {
                    this.bond_dts.splice(index, 1)
                },
                getRelatedAccount(index) {
                    $.ajax({
                        type: 'GET',
                        data: {system_code_id: this.bond_dts[index]['bond_doc_type']},
                        url: '{{ route("Bonds-capture.getRelatedAccount") }}'
                    }).then(response => {
                        this.bond_dts[index]['acc_name_ar'] = response.account.acc_name_ar
                        this.bond_dts[index]['acc_name_en'] = response.account.acc_name_en
                        this.bond_dts[index]['acc_id'] = response.account.acc_id
                        this.bond_dts[index]['acc_code'] = response.account.acc_code
                    })
                },
                getAccountList(index) {
                    this.bond_dts[index]['customers'] = []
                    this.bond_dts[index]['employees'] = []
                    this.bond_dts[index]['branches'] = []
                    this.bond_dts[index]['cars'] = []
                    this.bond_dts[index]['suppliers'] = []


                    this.bond_dts[index]['customer_id'] = 0
                    this.bond_dts[index]['emp_id'] = 0
                    this.bond_dts[index]['branch_id'] = 0
                    this.bond_dts[index]['car_id'] = 0
                    this.bond_dts[index]['supplier_id'] = 0


                    $.ajax({
                        type: 'GET',
                        data: {system_code_id: this.bond_dts[index]['account_type']},
                        url: '{{ route("Bonds-capture.getAccountList") }}'
                    }).then(response => {
                        if (response.employees) {
                            this.bond_dts[index]['employees'] = response.employees
                            this.bond_dts[index]['invoice_active'] = true
                            this.bond_dts[index]['bond_amount_debit'] = 0
                        } else if (response.customers) {
                            this.bond_dts[index]['customers'] = response.customers
                            this.bond_dts[index]['invoice_active'] = false
                            this.bond_dts[index]['bond_amount_debit'] = 0
                        } else if (response.branches) {
                            this.bond_dts[index]['branches'] = response.branches
                            this.bond_dts[index]['invoice_active'] = true
                            this.bond_dts[index]['bond_amount_debit'] = 0
                        } else if (response.cars) {
                            this.bond_dts[index]['cars'] = response.cars
                            this.bond_dts[index]['invoice_active'] = true
                            this.bond_dts[index]['bond_amount_debit'] = 0
                        } else if (response.suppliers) {
                            this.bond_dts[index]['suppliers'] = response.suppliers
                            this.bond_dts[index]['invoice_active'] = false
                            this.bond_dts[index]['bond_amount_debit'] = 0
                        }

                    })
                },
                getInvoiceList(index, id) {
                    this.bond_dts[index]['invoices'] = {}
                    this.bond_dts[index]['bond_amount_debit'] = 0
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: id},
                        url: '{{ route("bonds-capture-detailed.getCustomerInvoices") }}'
                    }).then(response => {
                        this.bond_dts[index]['invoices'] = response.data
                    })
                },
                getInvoiceDeservedValue(index, id) {
                    this.bond_dts[index]['bond_amount_debit'] = 0
                    $.ajax({
                        type: 'GET',
                        data: {invoice_id: id},
                        url: '{{ route("bonds-capture-detailed.getInvoiceDeservedValue") }}'
                    }).then(response => {
                        this.bond_dts[index]['bond_amount_debit'] = response.data
                    })
                }

            },
            computed: {
                net_value: function () {
                    let total_net = 0;
                    Object.entries(this.bond_dts).forEach(([key, val]) => {
                        total_net += parseFloat(val.bond_amount_debit)
                    });
                    return total_net.toFixed(2);
                }
            }
        });
    </script>
@endsection
