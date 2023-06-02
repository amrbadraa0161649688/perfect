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
                                                                                    <div class="col-md-3">
                                                                                        <div class="font-25" bold>
                                                                                            @lang('invoice.inv_ds')
                                                                                        </div>
                                                                                </div>
                                                                                <div class="col-md-3"  >
                                                                                            <input type="decimal"
                                                                                v-model="invoice_item_price_all"
                                                                                class="form-control  no-arabic  is-invalid numbers-only"
                                                                                style="font-size: 16px ;font-weight: bold" readonly

                                                                                name="invoice_item_price_all" value="0.000" required >
                                                                                </div>    

                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('Invoices.sales.store') }}" method="post">
                                @csrf

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                                                    <div class="mb-3">
                                                                                

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
                                                       
                                                                <div class="row">
                                                                   
                                                                                <div class="col-md-3">
                                                                                    <div class="col-3 col-md-3 col-lg-3">

                                                                                            <img class="w50 mr-2" src="{{ asset('assets/images/diesel.png') }}" >

                                                                                
                                                                                    </div>
                                                                                </div>
                                                                               
                                                                                                                        
                                                                  
                                                                           
                                                                </div>
                                                    
                                              
                                        <div class="row">
                                                    <div class="col-md-12">
                                                        <table  class="table table-bordered table-condensed" id="add_table">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th hidden
                                                                    style="font-size: 16px ;font-weight: bold;width: 100px "
                                                                            class="text-center ">@lang('invoice.item_type')</th>
                                                                            <th
                                                                            style="font-size: 16px ;font-weight: bold;width: 90px "
                                                                            class="text-center">@lang('invoice.item_qut')</th>
                                                                    <th hidden style="font-size: 14px ;font-weight: bold;width: 100px "
                                                                        class="text-center">@lang('invoice.item_price')</th>
                                                                    
                                                                    <th
                                                                    style="font-size: 16px ;font-weight: bold;width: 100px;color:blue "
                                                                            class="text-center" hidden>@lang('invoice.item_price') </th>
                                                                            
                                                                    <th  class="text-center" style="font-size: 14px ;font-weight: bold;width: 90px "  > @lang('invoice.total') </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                <tr id="invoice_item_id" class="clone"  v-for="(invoice,index) in invoices">
                                                                    <td hidden>
                                                                    
                                                                        <input type="number"
                                                                            v-model="invoices[index]['invoice_item_id']"
                                                                                @change="getPriceList(index),getTotals(index)"  @keyup="getTotals(index)" 
                                                                            class="form-control decimal  no-arabic is-invalid numbers-only" step="0.00001"
                                                                            style="width: 90px"
                                                                            name="invoice_item_id[]" value="0.00" required>


                                                                    </td>

                                                                    <td>
                                                                        <input type="decimal"
                                                                            v-model="invoices[index]['invoice_item_quantity']"
                                                                                @change="getPriceList(index),getTotals(index)"  @keyup="getPriceList(index),getTotals(index)" 
                                                                            class="form-control decimal  no-arabic is-invalid numbers-only" step="0.00001" autocomplete="off"
                                                                            style="font-size: 20px ;font-weight: bold;height: 90px"
                                                                            name="invoice_item_quantity[]" value="0.00" required>
                                                                    </td>
                                                                    <td hidden>
                                                                        <input type="decimal"
                                                                            v-model="invoices[index]['invoice_item_price']"
                                                                                class="form-control  no-arabic  is-invalid numbers-only"
                                                                            style="width: 100px" readonly
                                                                            @keyup="getTotals(index)"
                                                                            name="invoice_item_price[]" value="0.000" required >
                                                                    </td>
                                                                    <td>
                                                                        <input hidden type="text"
                                                                            class="form-control  no-arabic numbers-only"
                                                                            name="invoice_item_notes[]">

                                                                            <input type="decimal"
                                                                            name="invoice_total_amount_N[]" autocomplete="off"
                                                                            v-model="invoices[index]['invoice_total_amount_N']"

                                                                            @keyup="getQUNT(index)" @change="getQUNT(index),getTotals(index)"
                                                                            class="form-control decimal  no-arabic is-invalid numbers-only" step="0.01"
                                                                            style="font-size: 20px ;font-weight: bold;height: 90px"
                                                                                value="0.00" required>

                                                                    </td>
                                                                    <td hidden>
                                                                        <button  type="button" @click="addRow()"
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


                                 
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                        <td>@lang('invoice.vat_amount')</td>
                                                        <td><input type="number" readonly class="form-control"  style="font-size: 16px ;font-weight: bold" 
                                                                name="invoice_vat_amount" v-model="totalVatAmount"></td>


                                                        <td>@lang('invoice.total2')</td>
                                                        <td><input type="number" readonly class="form-control"  style="font-size: 16px ;font-weight: bold" 
                                                                name="invoice_amount" v-model="totalAmount"></td>
                                                    
                               


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
<!-- Scripts-->
<script src="assets/js/jquery.min.js" ></script>
<script src="https://cdn.rtlcss.com/bootstrap/v4.5.3/js/bootstrap.bundle.min.js" integrity="sha384-40ix5a3dj6/qaC7tfz0Yr+p9fqWLzzAXiwxVLt9dw7UjQzGYw6rWRhFAnRapuQyK" crossorigin="anonymous"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/fonts/fontAwesome/js/fontawesome.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                company_id: 29,
                acc_period_id: '',
                accounts_period: {},
                customer_id: 2,
                invoice_item_price_all:'',
                invoices: [{

                    'invoice_item_id': '{{$selected_type_id}}', 'invoice_item_price': 0.000, 'invoice_item_quantity': '',
                    'invoice_item_amount': 0, 'invoice_item_vat_rate': 15.00, 'invoice_item_unit': 95,

                    'invoice_item_vat_amount': 0.00, 'invoice_total_amount': 0.00,'invoice_total_amount_N' :''
                }],
                item_id: ''
            },
            methods: {
                addRow() {
                    this.invoices.push({
                        'invoice_item_id': '70003', 'invoice_item_price': 0.000, 'invoice_item_quantity': 0,
                        'invoice_item_amount': 0.00, 'invoice_item_vat_rate': 15.00, 'invoice_item_unit': 95,
                        'invoice_item_vat_amount': 0.00, 'invoice_total_amount': 0.00,'invoice_total_amount_N' :''
                    })
                },
                removeRow(index) {
                    this.invoices.splice(index, 1)
                },

                getQUNT(index) {

                    var x = this.invoices[index]['invoice_total_amount_N'] / 115 * 100
                          //  this.invoices[index]['invoice_item_amount']  = x.toFixed(4)
                         //   this.invoices[index]['invoice_item_amount'] = x.toFixed(4)
                         $.ajax({
                        type: 'GET',
                        data: {
                            loc_from: 29, loc_to: 29, customer_id: 2,
                            item_id: this.invoices[index]['invoice_item_id']
                        },
                        url: '{{ route("cargoD-getprice") }}'
                    }).then(response => {

                        this.invoices[index]['invoice_item_price'] = response.data.max_fees
                        this.invoice_item_price_all= response.data.max_fees
                    })

                            var y = x / this.invoices[index]['invoice_item_price'] 
                            this.invoices[index]['invoice_item_quantity'] = y.toFixed(4)    
                            var z = this.invoices[index]['invoice_total_amount_N'] - x ;

                    this.invoices[index]['invoice_item_vat_amount'] = z.toFixed(4)

                           
                            this.invoices[index]['invoice_total_amount'] =this.invoices[index]['invoice_total_amount_N'] 

                      
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
                        url: '{{ route("cargoD-getprice") }}'
                    }).then(response => {

                        this.invoices[index]['invoice_item_price'] = response.data.max_fees
                        this.invoice_item_price_all= response.data.max_fees
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

