@extends('Layouts.master')
@section('style')
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

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
                                        @lang('invoice.add_new_invoice')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Invoices.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.companies')</label>
                                                    <select class="form-control" v-model="company_id" name="company_id"
                                                            @change="getAccountPeriodsOfCompany()" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($companies as $company)
                                                            <option value="{{ $company->company_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{ $company->company_name_ar }}
                                                                @else
                                                                    {{ $company->company_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.account_periods')</label>
                                                    <select class="form-control" v-model="acc_period_id"
                                                            name="acc_period_id" required>
                                                        <option value="">@lang('home.choose')</option>

                                                        @foreach($accounts_periods as $accounts_period)
                                                            <option value="{{ $accounts_period->acc_period_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{ $accounts_period->acc_period_name_ar }}
                                                                @else
                                                                    {{ $accounts_period->acc_period_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('home.created_date')</label>
                                                    <input id="date" type="text" class="form-control"
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
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"> @lang('invoice.customer_name') </label>
                                                    <select class="form-select form-control" name="customer_id"
                                                            id="customer_id" required>
                                                        <option value="" selected>@lang('home.choose')</option>
                                                        @foreach($customers as $customer)
                                                            <option value="{{$customer->customer_id }}">
                                                                @if(app()->getLocale() == 'ar')
                                                                    {{ $customer->customer_name_full_ar }}
                                                                @else
                                                                    {{ $customer->customer_name_full_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('invoice.invoice_due_date')</label>
                                                    <input type="date" class="form-control" name="invoice_due_date"
                                                           id="invoice_due_date"
                                                           placeholder="@lang('invoice.invoice_due_date')" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>@lang('home.invoice_notes')</label>
                                                    <textarea class="form-control" name="invoice_notes"></textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-condensed" id="add_table">
                                            <thead class="bg-blue font-whait ">
                                            <tr>
                                                <th style="width:150px"
                                                    class="text-center">@lang('invoice.item_type')</th>
                                                                                                <th style="width:400px"
                                                    class="text-center">@lang('invoice.item_notes')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('invoice.item_qut')</th>
                                                <th style="width:130px"
                                                    class="text-center">@lang('invoice.item_price')</th>
                                                <th style="width:120px"
                                                    class="text-center">@lang('invoice.item_unit')</th>
                                                <th style="width:140px"
                                                    class="text-center">@lang('invoice.item_amount')</th>
                                                <th style="width:90px" class="text-center">@lang('invoice.ratio')</th>
                                                <th style="width:130px" class="text-center">@lang('invoice.vat')</th>
                                                <th style="width:130px" class="text-center">@lang('invoice.total')</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr id="add_clone" class="clone" v-for="(invoice,index) in invoices">
                                                <td>
                                                    <select class="form-control is-invalid type"  name="invoice_item_id[]"
                                                     v-model="invoices[index]['invoice_item_id']" required>
                                                        <option value="">@lang('home.choose')</option>
                                                        @foreach($sys_codes_item as $sys_code_item)
                                                            <option value="{{ $sys_code_item->system_code_id }}">
                                                                @if(app()->getLocale()=='ar')
                                                                    {{$sys_code_item->system_code_name_ar}}
                                                                @else
                                                                    {{$sys_code_item->system_code_name_en}}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="text"
                                                           class="form-control  no-arabic numbers-only"
                                                           name="invoice_item_notes[]">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           v-model="invoices[index]['invoice_item_quantity']"
                                                           @keyup="getTotals(index)"
                                                           class="form-control  no-arabic is-invalid numbers-only"
                                                           style="width: 150px" step="0.01"
                                                           name="invoice_item_quantity[]" value="0.000" required>
                                                </td>
                                                <td>
                                                    <input type="number" v-model="invoices[index]['invoice_item_price']"
                                                           class="form-control  no-arabic is-invalid numbers-only"
                                                           style="width: 120px" step="0.001"
                                                           @keyup="getTotals(index)"
                                                           name="invoice_item_price[]" value="0.000" required >
                                                </td>
                                                <td>
                                                    <select class="form-control type is-invalid" name="invoice_item_unit[]"
                                                    v-model="invoices[index]['invoice_item_unit']" required>
                                                        <option value=""></option>
                                                        @foreach($sys_codes_unit as $sys_code_unit)
                                                            <option value="{{ $sys_code_unit->system_code_id }}" required>
                                                                @if(app()->getLocale()=='ar')
                                                                    {{ $sys_code_unit->system_code_name_ar }}
                                                                @else
                                                                    {{ $sys_code_unit->system_code_name_en }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="text" v-model="invoices[index]['invoice_item_amount']"
                                                           class="form-control no-arabic numbers-only factor"
                                                           name="invoice_item_amount[]" value="0.00" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" @keyup="getTotals(index)"
                                                           v-model="invoices[index]['invoice_item_vat_rate']"
                                                           class="form-control no-arabic  numbers-only"
                                                           id="invoice_item_vat_rate"
                                                           name="invoice_item_vat_rate[]" value="15" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number"
                                                           v-model="invoices[index]['invoice_item_vat_amount']"
                                                           class="form-control no-arabic numbers-only amount"
                                                           name="invoice_item_vat_amount[]" value="0.00" readonly>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" v-model="invoices[index]['invoice_total_amount']"
                                                           class="form-control no-arabic numbers-only amount"
                                                           style="width: 150px"
                                                           name="invoice_total_amount[]" value="0.00" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" @click="addRow()"
                                                            class="btn btn-circle btn-icon-only red-flamingo">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button" @click="removeRow(index)" v-if="index>0"
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
                                                <td></td>

                                                <td><input type="decimal " readonly class="form-control"
                                                    name="total_Item_Amount" v-model="totalItemAmount"></td>
                                                <td></td>
                                                <td><input type="number" readonly class="form-control"
                                                           name="invoice_vat_amount" v-model="totalVatAmount"></td>
                                                <td><input type="number" readonly class="form-control" step=".01"
                                                           name="invoice_amount" v-model="totalAmount"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mt-2"  id="create_inv" type="submit">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

<script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>



@section('scripts')
    <script>
        $(document).ready(function () {
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)

            var cloneRow = $('#add_clone').clone();
            cloneRow.removeAttr('id');
            $('.remove-row', cloneRow).removeClass('hidden');
            var tableClone = $('#add_table tbody');
            var startCounter = ('tbody tr', tableClone).length;
            tableClone.on('click', '.add_row', function () {
                var row = cloneRow.clone();
                startCounter += 1;
                $('input,select', row).each((i, e) => {
                    var el = $(e);
                    var name = el.attr('name') || "";
                    name = name.replaceAll(/\d/ig, startCounter);
                    el.attr('name', name);
                });
                tableClone.append(row)
            })
            tableClone.on('click', '.remove-row', function (e) {
                var row = $(this).closest('tr');
                row.remove();
            })

            $('#invoice_item_vat_rate').val(15)
            $('#invoice_item_vat_rate').keyup(function () {
                if ($('#invoice_item_vat_rate').val().length < 2) {
                    $('#invoice_item_vat_rate').addClass('is-invalid')
                    $('#app').attr('disabled', 'disabled')
                } else {
                    $('#invoice_item_vat_rate').removeClass('is-invalid')
                    $('#app').removeAttr('disabled', 'disabled')
                }
            });



        })

    </script>




    <script>

        new Vue({
            el: '#app',
            data: {
                company_id: '',
                acc_period_id: '',
                accounts_period: {},
                customer_id: '',
                invoices: [{
                        'waybill_id':'','invoice_item_id':'','invoice_item_unit':'','invoice_item_quantity': 0.00, 'invoice_item_price': 0.000,
                        'invoice_item_amount': 0, 'invoice_item_vat_rate': 15.00,
                       'invoice_item_vat_amount': 0.00, 'invoice_total_amount': 0.00
                }],
                waybill_id:''
            },
            methods: {
                addRow() {
                    this.invoices.push({
                        'waybill_id':'','invoice_item_id':'','invoice_item_unit':'','invoice_item_quantity': 0.00, 'invoice_item_price': 0.000,
                        'invoice_item_amount': 0.00, 'invoice_item_vat_rate': 15.00,
                        'invoice_item_vat_amount': 0.00, 'invoice_total_amount': 0.00
                    })
                },
                removeRow(index) {
                    this.invoices.splice(index, 1)
                },
                getTotals(index) {

                    var x = this.invoices[index]['invoice_item_quantity']
                        * this.invoices[index]['invoice_item_price']

                    this.invoices[index]['invoice_item_amount'] = x.toFixed(2)

                    var y = this.invoices[index]['invoice_item_vat_rate'] * x / 100

                    this.invoices[index]['invoice_item_vat_amount'] = y.toFixed(2)

                    var z = x + y;

                    this.invoices[index]['invoice_total_amount'] = z.toFixed(2)

                },

                getwaybillinfo(index) {


                            $.ajax({
                                type: 'GET',
                                data: {waybill_id: this.invoices[index]['waybill_id']},

                                url: '{{ route("cargo3-getwaybillinfo") }}'
                            }).then(response => {
                                console.log(response.data)

                                this.invoices[index]['invoice_item_id'] = response.data.waybill_item_id
                                this.invoices[index]['invoice_item_unit'] = response.data.waybill_item_unit
                                this.invoices[index]['invoice_item_price'] = response.data.waybill_item_price
                                this.invoices[index]['invoice_item_quantity'] = response.data.waybill_item_quantity
                                this.invoices[index]['invoice_item_amount'] = response.data.waybill_item_amount
                                this.invoices[index]['invoice_item_vat_rate'] = response.data.waybill_item_vat_rate
                                this.invoices[index]['invoice_item_vat_amount'] = response.data.waybill_item_vat_amount
                                this.invoices[index]['invoice_total_amount'] = response.data.waybill_total_amount

                             })

                },


                getAccountPeriodsOfCompany() {
                    $.ajax({
                        type: 'GET',
                        data: {company_id: this.company_id},
                        url: '{{ route("api.company.accountPeriod") }}'
                    }).then(response => {
                        this.accounts_period = response.data
                    })
                },
            },
            computed: {
                totalItemAmount: function () {
                    var sum_total_item_amount = 0
                    this.invoices.forEach(e => {
                        sum_total_item_amount += parseFloat(e.invoice_item_amount);
                    });
                    return sum_total_item_amount.toFixed(2)
                },
                totalVatAmount: function () {
                    var sum_total_vat_amount = 0
                    this.invoices.forEach(e => {
                        sum_total_vat_amount += parseFloat(e.invoice_item_vat_amount);
                    });
                    return sum_total_vat_amount.toFixed(2)
                },
                totalAmount: function () {
                    var sum_total_amount = 0
                    this.invoices.forEach(e => {
                        sum_total_amount += parseFloat(e.invoice_total_amount);
                    });
                    return sum_total_amount.toFixed(2)
                },
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
@endsection

