@extends('Layouts.master2')
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
                            <form action="{{ route('Invoices.sales.store') }}" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <label for="recipient-name"
                                                           class="form-label"
                                                           hidden> @lang('invoice.customer_name') </label>

                                                    <select class="form-select form-control" name="customer_id"
                                                            hidden id="customer_id">
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


                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-9">
                                        <table class="table table-bordered table-condensed" id="add_table">
                                            <thead class="bg-blue font-whait ">
                                            <tr>
                                                <th
                                                        style="width: 90px"
                                                        class="text-center ">@lang('invoice.item_type')</th>
                                                 <th style="width:220px"
                                                    class="text-center">@lang('invoice.item_notes')</th>
                                                <th
                                                style="width: 90px"
                                                        class="text-center">@lang('invoice.item_qut')</th>
                                                <th
                                                style="width: 90px"
                                                        class="text-center" hidden>@lang('invoice.item_price') </th>

                                                <th style="width: 10px" >  </th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <tr id="invoice_item_id" class="clone"  v-for="(invoice,index) in invoices"
                                            >
                                                <td>
                                                   {{-- <div class="d-flex">

                                                        @foreach($sys_codes_item as $sys_code_item)
                                                             <input type="radio" name="invoice_item_id[]"
                                                                 value="{{ $sys_code_item->system_code_id }}"
                                                                @change="getPriceList(index)"

                                                                       v-model="invoices[index]['invoice_item_id']">
                                                                <label class="form-check-label" for="flexCheckDefault">
                                                                    <img src="{{ $sys_code_item->system_code_url }}"
                                                                    width="320" height="120">
                                                                </label>

                                                        @endforeach

                                                    </div> --}}




                                                    <select class="form-select form-control is-invalid invoices[index]['invoice_item_id']"
                                                    style="width: 90px" name="invoice_item_id[]"
                                                    id="invoice_item_id"
                                                    v-model="invoices[index]['invoice_item_id']"
                                                    @change="getPriceList(index)" required>

                                                    <option value="">@lang('home.choose')</option>
                                                    @foreach($sys_codes_item as $sys_code_item)
                                                    <option value="{{ $sys_code_item->system_code }}">
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
                                                           class="form-control decimal  no-arabic is-invalid numbers-only" step="0.01"
                                                           style="width: 90px"
                                                           name="invoice_item_quantity[]" value="0.00" required>
                                                </td>
                                                <td>
                                                    <input type="decimal"
                                                           v-model="invoices[index]['invoice_item_price']"
                                                             class="form-control  no-arabic  is-invalid numbers-only"
                                                           style="width: 90px"
                                                           @keyup="getTotals(index)"
                                                           name="invoice_item_price[]" value="0.000" required >
                                                </td>

                                                <td>
                                                    <button type="button" @click="addRow()"
                                                            class="btn btn-circle btn-icon-only red-flamingo"
                                                            style="width: 20px">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <button type="button" @click="removeRow(index)" v-if="index>0"
                                                            class="btn btn-circle btn-icon-only yellow-gold"
                                                            style="width: 20px">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </td>
                                            </tr>


                                            </tr>
                                            </tbody>
                                        </table>
                                        <td>@lang('invoice.vat_amount')</td>
                                        <td><input type="number" readonly class="form-control"
                                                   name="invoice_vat_amount" v-model="totalVatAmount"></td>


                                        <td>@lang('invoice.total2')</td>
                                        <td><input type="number" readonly class="form-control"
                                                   name="invoice_amount" v-model="totalAmount"></td>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-primary mt-2" id="submit" type="submit">
                                            <i class="fa fa-check"></i>
                                            @lang('home.save')</button>
                                        <div class="spinner-border" role="status" style="display: none">
                                            <span class="sr-only">Loading...</span>

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

    <script type="text/javascript"></script>
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

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                company_id: 29,
                acc_period_id: '',
                accounts_period: {},
                customer_id: 2,
                invoices: [{

                    'invoice_item_id': '', 'invoice_item_price': 0.000, 'invoice_item_quantity': '',
                    'invoice_item_amount': 0, 'invoice_item_vat_rate': 15.00, 'invoice_item_unit': 95,

                    'invoice_item_vat_amount': 0.00, 'invoice_total_amount': 0.00
                }],
                item_id: ''
            },
            methods: {
                addRow() {
                    this.invoices.push({
                        'invoice_item_id': '', 'invoice_item_price': 0.000, 'invoice_item_quantity': 0,
                        'invoice_item_amount': 0.00, 'invoice_item_vat_rate': 15.00, 'invoice_item_unit': 95,
                        'invoice_item_vat_amount': 0.00, 'invoice_total_amount': 0.00
                    })
                },
                removeRow(index) {
                    this.invoices.splice(index, 1)
                },
                getTotals(index) {

                    var x = this.invoices[index]['invoice_item_quantity']
                        * this.invoices[index]['invoice_item_price']

                    this.invoices[index]['invoice_item_amount'] = x.toFixed(4)

                    var y = this.invoices[index]['invoice_item_vat_rate'] * x / 100

                    this.invoices[index]['invoice_item_vat_amount'] = y.toFixed(4)

                    var z = x + y;

                    this.invoices[index]['invoice_total_amount'] = z.toFixed(4)

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

                getPriceList(index) {
                    $.ajax({
                        type: 'GET',
                        data: {
                            loc_from: 29, loc_to: 29, customer_id: 2,
                            item_id: this.invoices[index]['invoice_item_id']
                        },
                        url: '{{ route("cargo4-getprice") }}'
                    }).then(response => {

                        this.invoices[index]['invoice_item_price'] = response.data.max_fees
                    })

                },
            },
            computed: {
                totalItemAmount: function () {
                    var sum_total_item_amount = 0
                    this.invoices.forEach(e => {
                        sum_total_item_amount += parseFloat(e.invoice_item_amount);
                    });
                    return sum_total_item_amount.toFixed(4)
                },
                totalVatAmount: function () {
                    var sum_total_vat_amount = 0
                    this.invoices.forEach(e => {
                        sum_total_vat_amount += parseFloat(e.invoice_item_vat_amount);
                    });
                    return sum_total_vat_amount.toFixed(4)
                },
                totalAmount: function () {
                    var sum_total_amount = 0
                    this.invoices.forEach(e => {
                        sum_total_amount += parseFloat(e.invoice_total_amount);
                    });
                    return sum_total_amount.toFixed(4)
                },
            }
        });
    </script>
@endsection

