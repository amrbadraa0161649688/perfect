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
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"></h3>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="font-25" bold>
                                        تعديل فاتوره المشتريات
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{route('invoices-purchase.update',$id)}}" method="post">
                                @csrf
                                @method('put')
                                <div class="row">

                                    <div class="col-md-3">
                                        <label class="form-label">@lang('home.companies')</label>
                                        @if(app()->getLocale()=='ar')
                                            <input type="text" readonly class="form-control"
                                                   v-model="invoice_hd.company_name_ar">
                                        @else
                                            <input type="text" readonly
                                                   v-model="invoice_hd.company_name_en">
                                        @endif

                                        <input type="hidden" readonly class="form-control"
                                               v-model="invoice_hd.company_id">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">@lang('home.account_periods')</label>
                                        <select class="form-control" v-model="invoice_hd.acc_period_id"
                                                name="acc_period_id">
                                            <option value="">@lang('home.choose')</option>

                                            <option v-for="account_period in accounts_period"
                                                    :value="account_period.acc_period_id">
                                                @if(app()->getLocale()=='ar')
                                                    @{{ account_period.acc_period_name_ar }}
                                                @else
                                                    @{{ account_period.acc_period_name_en }}
                                                @endif
                                            </option>
                                        </select>

                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">@lang('home.created_date')</label>
                                        <input :value="invoice_hd.invoice_date" type="text"
                                               class="form-control"
                                               readonly>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">@lang('home.user')</label>
                                        <input type="text" calss="form-control" readonly
                                               class="form-control"
                                               value="@if(app()->getLocale()=='ar') {{ auth()->user()->user_name_ar }}
                                               @else {{ auth()->user()->user_name_en }} @endif">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"
                                                           style="text-decoration: underline;"> {{__('Supplier Name')}}</label>
                                                    <div class="form-group multiselect_div">
                                                        <div class="form-group multiselect_div">
                                                            <select class="form-control" data-live-search="true"
                                                                    name="supplier_id" id="supplier_id"
                                                                    @change="getSupplierType()"
                                                                    v-model="supplier_id">
                                                                <option value=""
                                                                        selected>@lang('home.choose')</option>
                                                                <option v-for="supplier in suppliers"
                                                                        :value="supplier.customer_id">
                                                                    @if(app()->getLocale() == 'ar')
                                                                        @{{supplier.customer_name_full_ar}}
                                                                    @else
                                                                        @{{supplier.customer_name_full_en}}
                                                                    @endif
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('invoice.invoice_due_date')</label>
                                                    <input type="date" class="form-control" name="invoice_due_date"
                                                           id="invoice_due_date"
                                                           v-model="invoice_hd.invoice_due_date"
                                                           placeholder="@lang('invoice.invoice_due_date')" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>@lang('home.invoice_notes')</label>
                                                    <textarea class="form-control" name="invoice_notes">@{{ invoice_hd.invoice_notes }}</textarea>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> {{__('Supplier Name')}} </label>
                                                    <input type="text" class="form-control is-invalid"
                                                           name="customer_name"
                                                           id="customer_name" v-model="customer_name"
                                                           placeholder="@lang('invoice.customer_name')" required>

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('invoice.customer_address') </label>
                                                    <input type="text" class="form-control is-invalid"
                                                           name="customer_address"
                                                           id="customer_address" v-model="customer_address"
                                                           placeholder="@lang('invoice.customer_address')" required>

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('invoice.customer_tax_no') </label>
                                                    <input type="text" class="form-control is-invalid"
                                                           name="customer_tax_no"
                                                           id="customer_tax_no" v-model="customer_tax_no"
                                                           placeholder="@lang('invoice.customer_tax_no')" required>

                                                </div>
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('invoice.customer_phone') </label>
                                                    <input type="text" class="form-control is-invalid"
                                                           name="customer_phone"
                                                           id="customer_phone" v-model="customer_phone"
                                                           placeholder="@lang('invoice.customer_phone')" required>

                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('invoice.po_number') </label>
                                                    <input type="text" class="form-control "
                                                           name="po_number"
                                                           id="po_number" :value="invoice_hd.po_number"
                                                           placeholder="@lang('invoice.po_number')">

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('invoice.po_supplier') </label>
                                                    <input type="text" class="form-control "
                                                           name="gr_number"
                                                           id="gr_number" :value="invoice_hd.gr_number"
                                                           placeholder="@lang('invoice.gr_number')">

                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('invoice.supply_date')</label>
                                                    <input type="date" class="form-control is-invalid"
                                                           name="supply_date" :value="invoice_hd.supply_date"
                                                           id="supply_date" required
                                                           placeholder="@lang('invoice.supply_date')">
                                                </div>

                                                {{--طرق الدفع--}}
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.payment_method')</label>
                                                        <select class="form-control  is-invalid" id="payment_tems"
                                                                name="payment_tems"
                                                                required v-model="invoice_hd.payment_tems">
                                                            <ooption value="">@lang('home.choose')</ooption>
                                                            <option v-for="payment_method in payment_methods"
                                                                    :value="payment_method.system_code">
                                                                @if(app()->getLocale() == 'ar')
                                                                    @{{payment_method.system_code_name_ar}}
                                                                @else
                                                                    @{{payment_method.system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-condensed"
                                                           id="add_table">
                                                        <thead class="table-primary font-whait">
                                                        <tr>
                                                            <th style="width:150px"
                                                                class="text-center">{{__('accounts')}}</th>
                                                            <th style="width:400px"
                                                                class="text-center">@lang('invoice.item_notes')</th>
                                                            <th style="width:110px"
                                                                class="text-center">@lang('invoice.item_qut')</th>
                                                            <th style="width:130px"
                                                                class="text-center">@lang('invoice.item_price')</th>
                                                            <th style="width:120px"
                                                                class="text-center">@lang('invoice.item_descount')</th>
                                                            <th style="width:120px"
                                                                class="text-center">@lang('invoice.cost_center')</th>

                                                            <th style="width:120px"
                                                                class="text-center">@lang('invoice.cost_center_type')</th>

                                                            <th style="width:140px"
                                                                class="text-center">@lang('invoice.item_amount')</th>
                                                            <th style="width:90px"
                                                                class="text-center">@lang('invoice.ratio')</th>
                                                            <th style="width:130px"
                                                                class="text-center">@lang('invoice.vat')</th>
                                                            <th style="width:130px"
                                                                class="text-center">@lang('invoice.total')</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr id="add_clone" class="clone"
                                                            v-for="(invoice_dt,index) in invoice_dts">
                                                            <input type="hidden" name="invoice_details_id[]"
                                                                   :value="invoice_dt.invoice_details_id">
                                                            <td>
                                                                <select class="form-control type"
                                                                        name="account_id[]"
                                                                        v-model="invoice_dt.item_account_id"
                                                                        required>
                                                                    <option value="">@lang('home.choose')</option>
                                                                    <option v-for="account in accounts"
                                                                            :value="account.acc_id">
                                                                        @if(app()->getLocale()=='ar')
                                                                            @{{account.acc_name_ar}}
                                                                        @else
                                                                            @{{account.acc_name_en}}
                                                                        @endif
                                                                    </option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="text"
                                                                       class="form-control  no-arabic numbers-only"
                                                                       name="invoice_item_notes[]"
                                                                       :value="invoice_dt.invoice_item_notes">
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                       @keyup="getTotals(index)"
                                                                       class="form-control  no-arabic is-invalid numbers-only"
                                                                       style="width: 110px" step="0.0001"
                                                                       v-model="invoice_dt.invoice_item_quantity"
                                                                       name="invoice_item_quantity[]" value="0.000"
                                                                       required>
                                                            </td>

                                                            <td>
                                                                <input type="number"
                                                                       class="form-control  no-arabic is-invalid numbers-only"
                                                                       style="width: 120px" step="0.00001"
                                                                       @keyup="getTotals(index)"
                                                                       v-model="invoice_dt.invoice_item_price"
                                                                       name="invoice_item_price[]" value="0.00000"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                       class="form-control  no-arabic is-invalid numbers-only"
                                                                       style="width: 120px" step="0.0001"
                                                                       @keyup="getTotals(index)"
                                                                       v-model="invoice_dt.invoice_discount_total"
                                                                       name="invoice_discount_total[]"
                                                                       value="0.0000"
                                                                       required>
                                                            </td>
                                                            <td>
                                                                <select class="form-control type "
                                                                        name="cost_center_type_id[]"
                                                                        @change="putPropRequired(index)"
                                                                        v-model="journal_dts[index].cost_center_type_id"
                                                                        required>
                                                                    <option value=""></option>
                                                                    <option v-for="account_type in account_types"
                                                                            :value="account_type.system_code">
                                                                        @if(app()->getLocale()=='ar')
                                                                            @{{account_type.system_code_name_ar }}
                                                                        @else
                                                                            @{{account_type.system_code_name_en }}
                                                                        @endif
                                                                    </option>

                                                                </select>
                                                            </td>

                                                            <td>
                                                                {{--customers--}}
                                                                <select class="form-control"
                                                                        v-show="journal_dts[index]['show_customers']"
                                                                        v-model="journal_dts[index]['cc_customer_id']"
                                                                        :required="journal_dts[index]['cc_customer_required']">
                                                                    <option value="0" selected>اختار</option>
                                                                    <option v-for="customer in customers"
                                                                            :value="customer.customer_id">
                                                                        @{{customer.customer_name_full_ar}}
                                                                    </option>
                                                                </select>

                                                                <input type="hidden" name="cc_customer_id[]"
                                                                       v-model="journal_dts[index]['cc_customer_id']">

                                                                {{--suppliers--}}
                                                                <select class="form-control"
                                                                        v-show="journal_dts[index]['show_suppliers']"
                                                                        :required="journal_dts[index]['cc_supplier_required']"
                                                                        v-model="journal_dts[index]['cc_supplier_id']">
                                                                    <option value="0" selected>اختار</option>
                                                                    <option v-for="supplier in suppliers"
                                                                            :value="supplier.customer_id">
                                                                        @{{supplier.customer_name_full_ar}}
                                                                    </option>
                                                                </select>

                                                                <input type="hidden" name="cc_supplier_id[]"
                                                                       v-model="journal_dts[index]['cc_supplier_id']">


                                                                {{--branches--}}
                                                                <select class="form-control"
                                                                        v-show="journal_dts[index]['show_branches']"
                                                                        :required="journal_dts[index]['cc_branch_required']"
                                                                        v-model="journal_dts[index]['cc_branch_id']">
                                                                    <option value="0" selected>اختار</option>
                                                                    <option v-for="branch in branches"
                                                                            :value="branch.branch_id">
                                                                        @{{branch.branch_name_ar}}
                                                                    </option>
                                                                </select>

                                                                <input type="hidden" name="cc_branch_id[]"
                                                                       v-model="journal_dts[index]['cc_branch_id']">


                                                                {{--trucks--}}
                                                                <select class="form-control"
                                                                        v-show="journal_dts[index]['show_trucks']"
                                                                        :required="journal_dts[index]['cc_trucks_required']"
                                                                        v-model="journal_dts[index]['cc_truck_id']">
                                                                    <option value="0" selected>اختار</option>
                                                                    <option v-for="truck in trucks"
                                                                            :value="truck.truck_id">
                                                                        @{{truck.truck_name}}
                                                                    </option>
                                                                </select>
                                                                <input type="hidden" name="cc_truck_id[]"
                                                                       v-model="journal_dts[index]['cc_truck_id']">

                                                                {{--employees--}}
                                                                <select class="form-control"
                                                                        v-show="journal_dts[index]['show_employees']"
                                                                        :required="journal_dts[index]['cc_employees_required']"
                                                                        v-model="journal_dts[index]['cc_employee_id']">
                                                                    <option value="0" selected>اختار</option>
                                                                    <option v-for="employee in employees"
                                                                            :value="employee.emp_id">
                                                                        @{{employee.emp_name_full_ar}}
                                                                    </option>
                                                                </select>
                                                                <input type="hidden" name="cc_employee_id[]"
                                                                       v-model="journal_dts[index]['cc_employee_id']">

                                                            </td>

                                                            <td>
                                                                <input type="text"
                                                                       v-model="invoice_dt.invoice_item_amount"
                                                                       class="form-control no-arabic numbers-only factor"
                                                                       name="invoice_item_amount[]" value="0.00"
                                                                       readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" @keyup="getTotals(index)"
                                                                       v-model="invoice_dt.invoice_item_vat_rate"
                                                                       class="form-control no-arabic  numbers-only"
                                                                       id="invoice_item_vat_rate"
                                                                       name="invoice_item_vat_rate[]" value="15">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number"
                                                                       v-model="invoice_dt.invoice_item_vat_amount"
                                                                       class="form-control no-arabic numbers-only amount"
                                                                       name="invoice_item_vat_amount[]" value="0.00"
                                                                       readonly>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text"
                                                                       v-model="invoice_dt.invoice_total_amount"
                                                                       class="form-control no-arabic numbers-only amount"
                                                                       style="width: 150px"
                                                                       name="invoice_total_amount[]" value="0.00"
                                                                       readonly>
                                                            </td>
                                                            <td>
                                                                <button type="button" @click="addRow()"
                                                                        class="btn btn-circle btn-icon-only red-flamingo">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                                <button type="button" @click="removeRow(index)"
                                                                        v-if="index>0"
                                                                        class="btn btn-circle btn-icon-only yellow-gold">
                                                                    <i class="fa fa-minus"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>@lang('home.total')</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><input type="decimal " readonly class="form-control"
                                                                       name="invoice_discount"
                                                                       v-model="totalItemdiscount"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><input type="decimal " readonly class="form-control"
                                                                       name="total_Item_Amount"
                                                                       v-model="totalItemAmount"></td>

                                                            <td></td>
                                                            <td><input type="number" readonly class="form-control"
                                                                       name="invoice_vat_amount"
                                                                       v-model="totalVatAmount"></td>
                                                            <td><input type="number" readonly class="form-control"
                                                                       step=".01"
                                                                       name="invoice_amount" v-model="totalAmount">
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mt-2" id="create_inv"
                                                type="submit">
                                            @lang('home.save')</button>
                                    </div>
                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                invoice_id: '',
                company_id: '',
                customer_name: '',
                customer_phone: '',
                customer_address: '',
                customer_tax_no: '',
                accounts_period: [],
                acc_period_id: '',
                supplier_id: '',

                invoice_hd: {},
                payment_methods: [],
                invoice_dts: [],
                journal_dts: [],
                account_types: [],

                customers: [],
                suppliers: [],
                employees: [],
                trucks: [],
                branches: [],
                accounts: [],
            },
            mounted() {
                this.invoice_id = '{{$id}}'
                this.getInvoiceData()
            },
            methods: {
                putPropRequired(index) {
                    //suppliers
                    if (this.journal_dts[index]['cost_center_type_id'] == 56001) {
                        this.journal_dts[index]['show_customers'] = false
                        this.journal_dts[index]['show_suppliers'] = true
                        this.journal_dts[index]['show_employees'] = false
                        this.journal_dts[index]['show_trucks'] = false
                        this.journal_dts[index]['show_branches'] = false


                        this.journal_dts[index]['cc_supplier_required'] = true

                        this.journal_dts[index]['cc_customer_required'] = false
                        this.journal_dts[index]['cc_customer_id'] = ''

                        this.journal_dts[index]['cc_employees_required'] = false
                        this.journal_dts[index]['cc_employees_id'] = false

                        this.journal_dts[index]['cc_branch_id'] = ''
                        this.journal_dts[index]['cc_branch_required'] = false

                        this.journal_dts[index]['cc_truck_id'] = ''
                        this.journal_dts[index]['cc_trucks_required'] = false
                    }

                    //عميل
                    if (this.journal_dts[index]['cost_center_type_id'] == 56002) {

                        this.journal_dts[index]['show_customers'] = true
                        this.journal_dts[index]['show_suppliers'] = false
                        this.journal_dts[index]['show_employees'] = false
                        this.journal_dts[index]['show_trucks'] = false
                        this.journal_dts[index]['show_branches'] = false


                        this.journal_dts[index]['cc_supplier_required'] = false
                        this.journal_dts[index]['cc_supplier_id'] = ''

                        this.journal_dts[index]['cc_customer_required'] = true

                        this.journal_dts[index]['cc_employees_required'] = false
                        this.journal_dts[index]['cc_employees_id'] = false

                        this.journal_dts[index]['cc_branch_id'] = ''
                        this.journal_dts[index]['cc_branch_required'] = false

                        this.journal_dts[index]['cc_truck_id'] = ''
                        this.journal_dts[index]['cc_trucks_required'] = false
                    }

                    //موظف
                    if (this.journal_dts[index]['cost_center_type_id'] == 56003) {
                        this.journal_dts[index]['show_customers'] = false
                        this.journal_dts[index]['show_suppliers'] = false
                        this.journal_dts[index]['show_employees'] = true
                        this.journal_dts[index]['show_trucks'] = false
                        this.journal_dts[index]['show_branches'] = false


                        this.journal_dts[index]['cc_supplier_required'] = false
                        this.journal_dts[index]['cc_supplier_id'] = ''

                        this.journal_dts[index]['cc_customer_required'] = false
                        this.journal_dts[index]['cc_customer_id'] = ''

                        this.journal_dts[index]['cc_employees_required'] = true

                        this.journal_dts[index]['cc_branch_id'] = ''
                        this.journal_dts[index]['cc_branch_required'] = false

                        this.journal_dts[index]['cc_truck_id'] = ''
                        this.journal_dts[index]['cc_trucks_required'] = false
                    }

                    //سياره
                    if (this.journal_dts[index]['cost_center_type_id'] == 56004) {

                        this.journal_dts[index]['show_customers'] = false
                        this.journal_dts[index]['show_suppliers'] = false
                        this.journal_dts[index]['show_employees'] = false
                        this.journal_dts[index]['show_trucks'] = true
                        this.journal_dts[index]['show_branches'] = false


                        this.journal_dts[index]['cc_supplier_required'] = false
                        this.journal_dts[index]['cc_supplier_id'] = ''

                        this.journal_dts[index]['cc_customer_required'] = false
                        this.journal_dts[index]['cc_customer_id'] = ''

                        this.journal_dts[index]['cc_employees_required'] = false
                        this.journal_dts[index]['cc_employee_id'] = ''

                        this.journal_dts[index]['cc_branch_id'] = ''
                        this.journal_dts[index]['cc_branch_required'] = false

                        this.journal_dts[index]['cc_trucks_required'] = true
                    }
///فرع
                    if (this.journal_dts[index]['cost_center_type_id'] == 56005) {
                        this.journal_dts[index]['show_customers'] = false
                        this.journal_dts[index]['show_suppliers'] = false
                        this.journal_dts[index]['show_employees'] = false
                        this.journal_dts[index]['show_trucks'] = false
                        this.journal_dts[index]['show_branches'] = true


                        this.journal_dts[index]['cc_supplier_required'] = false
                        this.journal_dts[index]['cc_supplier_id'] = ''

                        this.journal_dts[index]['cc_customer_required'] = false
                        this.journal_dts[index]['cc_customer_id'] = ''

                        this.journal_dts[index]['cc_employees_required'] = false
                        this.journal_dts[index]['cc_employee_id'] = ''

                        this.journal_dts[index]['cc_branch_required'] = true

                        this.journal_dts[index]['cc_trucks_required'] = false
                        this.journal_dts[index]['truck_id'] = ''

                    }
                },
                getInvoiceData() {
                    $.ajax({
                        type: 'GET',
                        data: {invoice_id: this.invoice_id},
                        url: ''
                    }).then(response => {
                        console.log(response)
                        this.invoice_hd = response.data
                        this.accounts_period = response.account_periods
                        this.suppliers = response.suppliers
                        this.payment_methods = response.payment_methods

                        this.customer_name = this.invoice_hd.customer_name
                        this.customer_phone = this.invoice_hd.customer_phone
                        this.customer_address = this.invoice_hd.customer_address
                        this.customer_tax_no = this.invoice_hd.customer_tax_no
                        this.supplier_id = this.invoice_hd.customer_id

                        this.invoice_dts = response.invoice_dts
                        this.journal_dts = response.journal_dts
                        this.account_types = response.account_types


                        this.customers = response.customers
                        this.suppliers = response.suppliers
                        this.employees = response.employees
                        this.trucks = response.trucks
                        this.branches = response.branches
                        this.accounts = response.accounts

                    })
                },
                getSupplierType() {
                    $.ajax({
                        type: 'GET',
                        data: {customer_id: this.supplier_id},
                        url: '{{ route("api.waybill.customer.type") }}'
                    }).then(response => {
                        this.customer_tax_no = response.customer_tax_no
                        this.customer_address = response.customer_address
                        this.customer_name = response.customer_name
                        this.customer_phone = response.customer_mobile
                    })
                },
                addRow() {
                    this.invoice_dts.push({
                        'invoice_details_id': 0,
                        'invoice_item_notes': '',
                        'invoice_item_quantity': 0,
                        'invoice_item_price': 0,
                        'invoice_discount_total': 0,
                        'invoice_item_amount': 0,
                        'invoice_item_vat_rate': 0,
                        'invoice_item_vat_amount': 0,
                        'invoice_total_amount': 0,
                        'item_account_id': '',
                    });
                    this.journal_dts.push({
                        'journal_dt_id': 0,
                        'cc_branch_id': '',
                        'cc_car_id': '',
                        'cc_employee_id': '',
                        'cc_customer_id': '',
                        'cc_supplier_id': '',
                        'cost_center_type_id': '',
                        'account_id': '',
                        'show_customers': false,
                        'cc_customer_required': false,
                        'show_employees': false,
                        'cc_employees_required': false,
                        'show_suppliers': false,
                        'cc_supplier_required': false,
                        'show_branches': false,
                        'cc_branch_required': false,
                        'show_trucks': false,
                        'cc_trucks_required': false,
                        'cc_truck_id': '',
                    });
                },
                removeRow(index) {
                    this.invoice_dts.splice(index, 1)
                    this.journal_dts.splice(index, 1)
                },
                getTotals(index) {

                    var x = (this.invoice_dts[index]['invoice_item_quantity']
                        * this.invoice_dts[index]['invoice_item_price']) - this.invoice_dts[index]['invoice_discount_total']

                    this.invoice_dts[index]['invoice_item_amount'] = x.toFixed(2)

                    var y = this.invoice_dts[index]['invoice_item_vat_rate'] * x / 100

                    this.invoice_dts[index]['invoice_item_vat_amount'] = y.toFixed(2)

                    var z = x + y;

                    this.invoice_dts[index]['invoice_total_amount'] = z.toFixed(2)

                },
            },
            computed: {
                totalItemAmount: function () {
                    var sum_total_item_amount = 0
                    this.invoice_dts.forEach(e => {
                        sum_total_item_amount += parseFloat(e.invoice_item_amount);
                    });
                    return sum_total_item_amount.toFixed(2)
                },
                totalItemdiscount: function () {
                    var sum_total_item_discount = 0
                    this.invoice_dts.forEach(e => {
                        sum_total_item_discount += parseFloat(e.invoice_discount_total);
                    });
                    return sum_total_item_discount.toFixed(2)
                },
                totalVatAmount: function () {
                    var sum_total_vat_amount = 0
                    this.invoice_dts.forEach(e => {
                        sum_total_vat_amount += parseFloat(e.invoice_item_vat_amount);
                    });
                    return sum_total_vat_amount.toFixed(2)
                },
                totalAmount: function () {
                    var sum_total_amount = 0
                    this.invoice_dts.forEach(e => {
                        sum_total_amount += parseFloat(e.invoice_total_amount);
                    });
                    return sum_total_amount.toFixed(2)
                },
            }
        })
    </script>

@endsection