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
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#receiving1-grid" data-toggle="tab" style="font-size: 16px ;font-weight: bold"
                           class="nav-link active">@lang('home.new_receiving')</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#receiving2-grid"
                                            style="font-size: 16px ;font-weight: bold"
                                            data-toggle="tab">@lang('home.receiving_from_order_request')</a></li>


                </ul>
                <div class="header-action"></div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">


            <div class="tab-content mt-3">

                {{--اذن استلام--}}
                <div class="tab-pane fade show active" id="receiving1-grid" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('stations-storePurchaseReceiving.storeNewReceiving')}}"
                                          method="post">
                                        @csrf
                                        <div class="card-body">
                                            <h3 class="card-title">@lang('home.new_receiving')</h3>
                                            <div class="row">

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.branch')</label>
                                                        <input type="text" class="form-control" readonly value="{{app()->getLocale()=='ar' ?
                                                session('branch')['branch_name_ar'] : session('branch')['branch_name_en']}}">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('purchase.item_category')</label>
                                                        <input type="text" class="form-control" readonly value="{{app()->getLocale()=='ar' ?
                                                $store_category_type->system_code_name_ar : $store_category_type->system_code_name_en}}">

                                                        <input type="hidden"
                                                               value="{{$store_category_type->system_code_id}}"
                                                               name="store_category_type">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('home.date')</label>
                                                        <input type="text" class="form-control" readonly id="date">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">

                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('purchase.vendor') </label>
                                                        <select class="selectpicker" name="store_acc_no"
                                                                id="store_acc_no" required>
                                                            <option value="" selected> choose</option>
                                                            @foreach($vendor_list as $vendor)
                                                                <option value="{{$vendor->customer_id}}"> {{ $vendor->getCustomerName() }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('purchase.item_code') </label>
                                                        <select class="selectpicker show-tick form-control"
                                                                data-live-search="true"
                                                                name="item_id" id="item_id" v-model="item_id"
                                                                @change="getItemDetails()">
                                                            <option value="" selected> choose</option>
                                                            @foreach($itemes as $item)
                                                                <option value="{{$item->item_id}}">
                                                                    {{$item->item_code}}
                                                                    - {{$item->item_name_e}}  </option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label">   @lang('purchase.qty') </label>
                                                        <input type="number" class="form-control"
                                                               name="store_vou_qnt_i_r"
                                                               id="store_vou_qnt_i_r" v-model="store_vou_qnt_i_r">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('purchase.unit_price') </label>
                                                        <input type="number" class="form-control"
                                                               name="store_vou_item_price_unit"
                                                               id="store_vou_item_price_unit"
                                                               v-model="store_vou_item_price_unit"
                                                               step=".0001">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('purchase.total') </label>
                                                        <input type="number" class="form-control"
                                                               name="store_vou_item_total_price"
                                                               id="store_vou_item_total_price" step="0.01"
                                                               v-model="store_vou_item_total_price" readonly>
                                                    </div>
                                                </div>


                                                <div class="col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                    </div>
                                                </div>


                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('purchase.store_vou_vat_rate') </label>
                                                        <input type="number" class="form-control"
                                                               name="store_vou_vat_rate"
                                                               id="store_vou_vat_amount" readonly
                                                               v-model="store_vou_vat_rate" step=".0001">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('purchase.store_vou_vat_amount') </label>
                                                        <input type="number" class="form-control"
                                                               name="store_vou_vat_amount"
                                                               step=".0001"
                                                               id="store_vou_vat_amount" v-model="store_vou_vat_amount"
                                                               readonly>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label for="recipient-name"
                                                               class="col-form-label"> @lang('purchase.store_vou_price_net') </label>
                                                        <input type="number" class="form-control"
                                                               name="store_vou_price_net"
                                                               step=".0001"
                                                               id="store_vou_price_net" v-model="store_vou_price_net"
                                                               readonly>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-primary">@lang('home.save')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--استيراد من امر استلام--}}
                <div class="tab-pane fade" id="receiving2-grid" role="tabpanel">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('stations-storePurchaseReceiving.storeReceivingFromOrder')}}"
                                          method="post">
                                        @csrf

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">

                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('home.purchase_order') </label>
                                                <select class="selectpicker" name="store_hd_id"
                                                        id="store_hd_id" required v-model="store_hd_id"
                                                        @change="getStoreHd()">
                                                    <option value="" selected> choose</option>
                                                    @foreach($purchase_orders as $purchase_order)
                                                        <option value="{{$purchase_order->store_hd_id}}"> {{ $purchase_order->store_hd_code }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        <div class="row mb-3" v-if="item_table">
                                            <div class="col-md-4">
                                                <label>@lang('home.category_type')</label>
                                                <input type="text" class="form-control" readonly
                                                       :value="store_hd.store_category_name">
                                            </div>
                                            <div class="col-md-4">
                                                <label>@lang('home.supplier_name')</label>
                                                <input type="text" class="form-control" readonly
                                                       :value="store_hd.store_acc_name">
                                            </div>
                                            <div class="col-md-4">
                                                <label>@lang('home.supplier_tax_number')</label>
                                                <input type="text" class="form-control" readonly
                                                       :value="store_hd.store_acc_tax_no">
                                            </div>
                                        </div>

                                        <table class="table table-bordered card_table" v-if="item_table">
                                            <tbody>

                                            <tr>
                                                <th class="ctd table-active"
                                                    style="width:25%"> @lang('purchase.item_code') </th>
                                                <th class="ctd table-active">@lang('purchase.item_name')</th>
                                                <th class="ctd table-active"> @lang('purchase.qty')</th>
                                                <th class="ctd table-active"> @lang('purchase.unit_price')</th>
                                                <th class="ctd table-active">@lang('purchase.store_vou_price_net')</th>

                                                <th class="ctd table-active"
                                                    style="width:10%"> @lang('purchase.disc_type') </th>
                                                <th class="ctd table-active"
                                                    style="width:10%"> @lang('purchase.disc') </th>
                                                <th class="ctd table-active"
                                                    style="width:10%"> @lang('purchase.total_disc') </th>

                                                <th class="ctd table-active"> @lang('purchase.store_vou_vat_amount')</th>
                                                <th class="ctd table-active"> @lang('purchase.store_vou_price_net') </th>
                                                <!-- <th class="ctd table-active">Action</th> -->
                                            </tr>

                                            <tr>
                                                <input type="hidden" name="store_hd_id" :value="store_hd.store_hd_id">
                                                <td class="ctd"> @{{store_hd.item_code}}</td>
                                                <td class="ctd">@{{store_hd.item_name_e}}</td>
                                                <td class="ctd"> @{{ store_hd.store_vou_qnt_p }}</td>
                                                <td class="ctd"> @{{ store_hd.store_vou_item_price_unit }}</td>
                                                <td class="ctd"> @{{ store_hd.store_vou_item_total_price}}</td>
                                                <td class="ctd"> @{{ store_hd.discType }}</td>

                                                <td class="ctd"> @{{ store_hd.store_voue_disc_value }}</td>
                                                <td class="ctd">@{{ store_hd.store_vou_disc_amount }}</td>
                                                <td class="ctd">@{{ store_hd.store_vou_vat_amount }}</td>
                                                <td class="ctd">@{{ store_hd.store_vou_price_net }}</td>

                                            </tr>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="1"
                                                    class="ctd table-active"> @lang('purchase.total_amount') </td>
                                                <td colspan="1" class="ctd table-active">
                                                    <div id="total_sum_div"> @{{store_hd.store_vou_item_total_price }}
                                                    </div>
                                                </td>
                                                <td colspan="1"
                                                    class="ctd table-active"> @lang('purchase.total_disc_amount') </td>
                                                <td colspan="2" class="ctd table-active">
                                                    <div id="total_sum_vat_div">@{{ store_hd.store_vou_desc }}</div>
                                                </td>
                                                <td colspan="1"
                                                    class="ctd table-active"> @lang('purchase.total_vat_amount') </td>
                                                <td colspan="2" class="ctd table-active">
                                                    <div id="total_sum_vat_div"> @{{ store_hd.store_vou_vat_amount}}
                                                    </div>
                                                </td>
                                                <td colspan="2"
                                                    class="ctd table-active"> @lang('purchase.total_net_amount') </td>
                                                <td colspan="2" class="ctd table-active">
                                                    <div id="total_sum_net_div"> @{{ store_hd.store_vou_total }}</div>
                                                </td>
                                            </tr>

                                            </tfoot>

                                        </table>


                                        <button class="btn btn-primary" type="submit">@lang('home.save')</button>
                                    </form>
                                </div>
                            </div>
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
        $(document).ready(function () {

            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' +
                (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
            ;
            $('#date').val(output)
        })
    </script>


    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                item_id: '',
                item: {},
                store_vou_qnt_i_r: 0,
                store_vou_item_price_unit: 0,
                store_vou_vat_rate: 15,
                store_hd_id: '',
                store_hd: {},
                item_table: false
            },
            methods: {
                getItemDetails() {
                    this.store_vou_item_price_unit = 0
                    $.ajax({
                        type: 'GET',
                        data: {item_id: this.item_id},
                        url: '{{ route("stations-storePurchaseRequest.getItemDetails") }}'
                    }).then(response => {
                        this.store_vou_item_price_unit = response.data.item_price_cost
                    });
                },
                getStoreHd() {
                    this.store_hd = {}
                    this.item_table = false
                    $.ajax({
                        type: 'GET',
                        data: {store_hd_id: this.store_hd_id},
                        url: '{{ route("stations-storePurchaseReceiving.getStoreHd") }}'
                    }).then(response => {
                        this.store_hd = response.data
                        this.item_table = true
                    });
                }
            },
            computed: {
                store_vou_item_total_price: function () {
                    var x = this.store_vou_item_price_unit * this.store_vou_qnt_i_r
                    return x.toFixed(2)
                },
                store_vou_vat_amount: function () {
                    var y = this.store_vou_item_total_price * (this.store_vou_vat_rate / 100)
                    return y.toFixed(2)
                },
                store_vou_price_net: function () {
                    var z = this.store_vou_item_total_price * (1 + this.store_vou_vat_rate / 100)
                    return z.toFixed(2)
                }
            }
        });
    </script>

@endsection