@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
    <style type="text/css">
        .ctd {
            text-align: center;

        }

        .full {
            padding-left: 40%;
        }
    </style>

@endsection

@section('content')
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">

                <div class="header-action">

                </div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <form id="item_data_form" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row">

                                            <input type="hidden" class="form-control" name="store_hd_id"
                                                   id="store_hd_id" value="{{ $purchase->store_hd_id }}">
                                            <input type="hidden" class="form-control" name="purchase_uuid"
                                                   id="purchase_uuid" value="{{ $purchase->uuid }}">

                                            <input type="hidden" class="form-control" name="customer_addition_rate"
                                                   id="customer_addition_rate"
                                                   value="{{ $purchase->customer->customer_vat_rate }}">
                                            <input type="hidden" class="form-control" name="customer_discount_rate"
                                                   id="customer_discount_rate"
                                                   value="{{ $purchase->customer->customer_discount_rate }}">
                                            <input type="hidden" class="form-control" name="customer_vat_rate"
                                                   id="customer_vat_rate"
                                                   value="{{ $purchase->customer->customer_vat_rate }}">
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales.inv_no') </label>
                                                <input type="text" class="form-control" name="store_hd_code"
                                                       id="store_hd_code" value="{{ $purchase->store_hd_code }}"
                                                       readonly disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales.warehouse_type') </label>
                                                <select class="form-select form-control" name="store_category_type"
                                                        id="store_category_type" disabled>
                                                    <option value="" selected> choose</option>
                                                    @foreach($warehouses_type_list as $w_t)
                                                        <option value="{{$w_t->system_code}}" {{($purchase->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">

                                                @if($purchase->mntsCard)
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('sales.sales_inv_mntns_no') </label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ $purchase->mntsCard->mntns_cards_no }}">
                                                @elseif(!$purchase->mntsCard && $purchase->mntsCar)
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('sales.sales_inv_car_no') </label>
                                                    <input type="text" class="form-control" disabled=""
                                                           value="{{ $purchase->mntsCar->mntns_cars_plate_no . ' '.$purchase->mntsCar->brand->system_code_name_ar}}">
                                                @else
                                                    <label for="recipient-name"
                                                           class="col-form-label"> @lang('sales.customer_name') </label>
                                                    <select class="selectpicker show-tick form-control"
                                                            data-live-search="true" name="store_acc_no"
                                                            id="store_acc_no" required disabled>
                                                        <option value="" selected> choose</option>
                                                        @foreach($customer as $vendor)
                                                            <option value="{{$vendor->customer_id}}"
                                                                    data-vendorname="{{ $vendor->getCustomerName() }}"
                                                                    data-vendorvat="{{ $vendor->customer_vat_no }}" {{($purchase->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales.inv_date') </label>
                                                <input type="date" class="form-control" name="created_date"
                                                       id="created_date"
                                                       value="{{ $purchase->created_date->format('Y-m-d') }}"
                                                >
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales.customer_name') </label>
                                                <input type="text" class="form-control" name="store_acc_name"
                                                       id="store_acc_name" value="{{$purchase->store_acc_name}}"
                                                >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales.tax_no') </label>
                                                <input type="number" class="form-control" name="store_acc_tax_no"
                                                       id="store_acc_tax_no" value="{{$purchase->store_acc_tax_no}}"
                                                >
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('sales.mobile_no') </label>
                                                <input type="number" class="form-control" name="store_vou_ref_after"
                                                       id="store_vou_ref_after"
                                                       value="{{$purchase->store_vou_ref_after}}">
                                            </div>


                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('sales.payment_method')  </label>
                                                <select class="form-select form-control" name="store_vou_pay_type"
                                                        id="store_vou_pay_type" required>
                                                    <option value="" selected> choose</option>
                                                    @foreach($payemnt_method_list as $p_method)
                                                        <option value="{{$p_method->system_code}}" {{($purchase->store_vou_pay_type == $p_method->system_code_id? 'selected': '' )}}> {{ $p_method->getSysCodeName() }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> {{__('Discount Value')}} </label>
                                                <input type="number" class="form-control " name="vou_discount_rate"
                                                       id="vou_discount_rate" value="">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('sales.note')  </label>
                                                <textarea rows="2" class="form-control" name="store_vou_notes"
                                                          id="store_vou_notes" placeholder="Here can be your note"
                                                          value=""> {{$purchase->store_vou_notes }}</textarea>
                                            </div>

                                            <div class="col-md-3">
                                                <br>
                                                <br>

                                                <!-- <button type="button" onclick="" class="btn btn-primary btn-block">
                                                    <i class="fe fe-save mr-2"></i> تحديث
                                                </button> -->

                                                @if($purchase->store_vou_status == App\Models\SystemCode::where('company_group_id',
                                                    $company->company_group_id)->where('system_code', '=', '125001')->first()->system_code_id)
                                                    <button type="button"
                                                            onclick="genInvoice('{{$purchase->uuid}}')"
                                                            class="btn btn-primary btn-lg" id="export_invoice"
                                                            style="margin-bottom: 10px;margin-right: 10px">
                                                        @lang('purchase.invoice_create')
                                                    </button>
                                                @endif


                                            </div>
                                            <div class="col-md-3">
                                                <br>
                                                <br>

                                                <button type="button" onclick="saveItemRow(2)"
                                                        class="btn btn-primary btn-block"
                                                        @if($purchase->total_bonds_inv != 0 && $purchase->total_bonds_inv  == $purchase->store_vou_total)
                                                        disabled @endif
                                                        @if(round($purchase->store_vou_desc) > 0) disabled @endif>
                                                    <i class="fe fe-save mr-2"></i> تحديث
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <br>
                                <form action="{{ route('store-sales-inv.edit',$purchase->uuid ) }}">
                                    <button type="submit" class="btn btn-primary btn-lg" id="add_bond"
                                            style="margin-bottom: 10px;margin-right: 10px"
                                            @if($purchase->status->system_code != 125002) disabled @endif>
                                        @lang('home.add_bond')
                                    </button>
                                    <input type="hidden" id="total_sum" name="total_sum">
                                    <input type="hidden" id="qr" name="qr" value="bond">
                                </form>
                            </div>
                        </div>

                        <div class="row card">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#search_item_modal">
                                <i class="fe fe-search mr-2"></i> @lang('purchase.show_item')
                            </button>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <div class="table-responsive">
                                        @include('store.sales.inv.table.item_new_table')
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer row">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                @if($purchase->status->system_code != 125001)
                                    <a href="{{config('app.telerik_server')}}?rpt={{$purchase->report_url_inv->report_url}}&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                       title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                       target="_blank">
                                        {{trans('Print')}}

                                    </a>
                                @endif
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{ route('store-sales-inv.index') }}"
                                   class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
            @include('store.search.search_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
                @if(session('company'))
        var company_id =
                {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
                @else
        var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif
    </script>

    <script type="text/javascript">

        function getNum(number) {
            return parseFloat(number).toFixed(2);
        }


        $(document).ready(function () {
            $('#vou_discount_rate').keyup(function () {
                // alert($('#vou_discount_rate').val())
                var vat_ratio = $('#vou_discount_rate').val();
                var total_sum_div = $('#total_sum_div').text();
                $('#total_discount_div').val(parseFloat(vat_ratio).toFixed(2))

                $('#total_sum_vat_div').text(0.15 * (total_sum_div - $('#total_discount_div').val()))
                $('#total_sum_net_div').text(getNum(parseFloat($('#total_sum_vat_div').text()) + parseFloat(total_sum_div) - parseFloat($('#total_discount_div').val())))
            });

            vat_rate = $('#customer_vat_rate').val();
            customer_addition_rate = $('#customer_addition_rate').val();
            customer_discount_rate = $('#customer_discount_rate').val();

            // $('#store_vou_qnt_o').keyup(function () {
            //
            //     var total_sum_div_0 = $('#total_sum_div').text();
            //     var a = parseFloat(total_sum_div_0) + parseFloat($('#store_vou_item_total_price').val());
            //     alert(a)
            //     $('#total_sum_div').text(a)
            // });

            // if ($('#total_sum').val() == 0) {
            //     $('#add_bond').attr('disabled', 'disabled')
            // }

            //vat_rate = 15 / 100;
            //  vat_rate = $('#customer_vat_rate').val();
            //   customer_addition_rate = $('#customer_addition_rate').val();
            //  customer_discount_rate = $('#customer_discount_rate').val();

            $('#store_vou_item_price_unit').keyup(function () {
                $('#store_vou_item_price_unit_vat').val(calculateItemPriceByAdditionRate($('#store_vou_item_price_unit').val(), customer_addition_rate))
            });

            // $('#store_vou_item_price_unit_vat').change(function () {
            //     // alert('f')
            //     $('#store_vou_item_price_unit').val($('#store_vou_item_price_unit_vat').val() / (1 + parseFloat(customer_addition_rate)))
            //
            //     var total_sum_div = $('#total_sum_div').text();
            //     var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
            //     $('#total_sum_div_0').text(a);
            //
            //
            //     var total_sum_vat_div = $('#total_sum_vat_div').text();
            //     var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
            //     $('#total_sum_vat_div_0').text(b);
            //
            //
            //     var total_sum_net_div = $('#total_sum_net_div').text();
            //     var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
            //     $('#total_sum_net_div_0').text(c)
            //
            // });


            $('#item_id').on('change', function () {
                console.log(customer_addition_rate)

                $('#store_vou_qnt_o').val('');
                $('#store_vou_item_total_price').val('');
                $('#store_vou_vat_amount').val('');
                $('#store_vou_price_net').val('');
                $('#store_vou_disc_amount').val('');
                $('#item_balance').val('');
                $('#item_price_cost').val('');
                $('#last_price_cost').val('');

                if ($('#item_id').val() !== '') {
                    item = $('#item_id :selected').data('item');
                    $('#store_vou_item_code').val($('#item_id :selected').data('itemname'));
                    $('#item_balance').val($('#item_id :selected').data('balance'));
                    $('#store_vou_item_price_unit').val(getNum(calculateItemPriceByAdditionRate(item.item_price_sales, customer_addition_rate)));
                    $('#store_vou_item_price_unit_vat').val(getNum(calculateItemPriceByAdditionRate($('#store_vou_item_price_unit').val(), customer_addition_rate)));
                    $('#item_price_cost').val(item.item_price_sales);
                    $('#item_price_sales').val(item.item_price_sales);
                    $('#last_price_cost').val(item.item_price_sales);
                    $('#store_vou_qnt_o').val('');
                    $('#store_vou_item_total_price').val('');

                    $('#discount_rate').val(customer_discount_rate * 100);

                }
            });

            //////////////////////////
            $('#store_vou_qnt_o').keyup(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val()) * getNum($('#store_vou_item_price_unit').val() - getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate))));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(getNum(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val())));
                $('#store_vou_disc_amount').val(getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate)));


                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a.toFixed(2));


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b.toFixed(2));


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))

            });

            $('#store_vou_item_price_unit').keyup(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if (!$('#store_vou_qnt_o').val()) {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val()) * getNum($('#store_vou_item_price_unit').val() - getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate))));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(getNum(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val())));
                $('#store_vou_disc_amount').val(getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate)));

                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a.toFixed(2));


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b.toFixed(2));


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))

            });

            $('#store_vou_item_price_unit_vat').keyup(function () {

                $('#store_vou_item_price_unit').val(getNum($('#store_vou_item_price_unit_vat').val() / (1 + parseFloat(customer_addition_rate))))

                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if (!$('#store_vou_qnt_o').val()) {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }

                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val()) * getNum($('#store_vou_item_price_unit').val() - getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate))));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(getNum(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val())));
                $('#store_vou_disc_amount').val(getNum($('#store_vou_qnt_o').val()) * getNum(calculateItemPriceByDiscountRate($('#store_vou_item_price_unit').val(), customer_discount_rate)));

                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a.toFixed(2));


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b.toFixed(2));


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))

            });

        });

        function calculateItemPriceByAdditionRate($price, $rate) {
            result = parseFloat($price) + (parseFloat($price) * parseFloat($rate));
            return result;
        }

        function calculateItemPriceByDiscountRate($price, $rate) {
            $result = (parseFloat($price) * parseFloat($rate));
            return $result;
        }


        function checkItemInput() {

            if (
                $('#item_id').val() == '' ||
                $('#store_vou_item_code').val() == '' ||
                $('#store_vou_qnt_o').val() == '' ||

                $('#store_vou_item_price_unit').val() == '' ||
                $('#store_vou_item_total_price').val() == '' ||
                $('#invoice_date_external').val() == '') {
                return false;
            }

            if ($('#item_id :selected').data('item').item_price_sales > $('#store_vou_item_price_unit').val()) {
                return 'price';
            }

            return true;

        }

        function saveItemRow(el) {
            if (el == 1) {
                if (!checkItemInput()) {
                    return toastr.warning('لا يوجد بيانات لاضافتها');
                }

                // if (checkItemInput() == 'price') {
                //     return toastr.warning('سعر البيع  للمنتج اقل من سعر التكلفه');
                // }

                tableBody = $("#item_table");
                rowNo = parseFloat($('#item_row_count').val()) + 1; //// ;

                //return  alert(rowNo);
                row_data = {
                    'id': 'tr' + rowNo,
                    'count': rowNo,
                    'store_hd_id': $('#store_hd_id').val(),
                    'store_vou_item_id': parseFloat($('#item_id').val()),
                    'store_vou_item_code': $('#item_id :selected').data('item').item_code,
                    'store_vou_loc': $('#item_id :selected').data('item').item_location,
                    'store_vou_qnt_o': parseFloat($('#store_vou_qnt_o').val()),
                    'store_vou_item_price_cost': $('#item_id :selected').data('item').item_price_cost,
                    'store_vou_item_price_sales': parseFloat($('#last_price_cost').val()),
                    'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                    'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                    'item_balance': parseFloat($('#item_balance').val()),
                    'store_vou_item_total_price': parseFloat($('#store_vou_item_total_price').val()),
                    'last_price_cost': parseFloat($('#last_price_cost').val()),
                    'store_vou_vat_rate': (vat_rate),
                    'store_vou_vat_amount': parseFloat($('#store_vou_vat_amount').val(), 2),
                    'store_vou_price_net': parseFloat($('#store_vou_price_net').val()),
                    'store_vou_disc_amount': parseFloat($('#store_vou_disc_amount').val()),
                    'store_voue_disc_value': parseFloat(customer_discount_rate),
                    'store_vou_disc_type': 533,
                    'item_stor_dt_serial': $("input[name='item_stor_dt_serial[]']:checkbox:checked")
                        .map(function () {
                            return $(this).val();
                        }).get(),
                    'item_stor_dt_serial_n': $("input[name='item_stor_dt_serial_n[]']")
                        .map(function () {
                            return $(this).val();
                        }).get(),
                };

                url = '{{ route('store-item-sales-invnew.store') }}';
                var form = new FormData($('#item_data_form')[0]);
                form.append('item_table_data', JSON.stringify(row_data));
                form.append('item_type', 'invnew');

                var data = form;
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function (data) {
                    if (data.success) {
                        toastr.success(data.msg);
                        updateItemTable(rowNo, row_data, data.uuid, data.total);

                        $('#total_sum_div_0').text(0);
                        $('#total_sum_vat_div_0').text(0);
                        $('#total_sum_net_div_0').text(0)
                        location.reload(true)
                    }
                    else {
                        toastr.warning(data.msg);
                    }
                });

            }

            if (el == 2) {
                row_data = {
                    'store_vou_vat_rate': (15 / 100),
                    'store_vou_vat_amount': parseFloat($('#total_sum_vat_div').text()),
                    'store_vou_price_net': parseFloat($('#total_sum_net_div').text()),
                    'store_vou_desc': parseFloat($('#total_discount_div').val())
                };

                url = '{{ route('store-sales-inv.update') }}';

                var form = new FormData($('#item_data_form')[0]);

                form.append('item_table_data', JSON.stringify(row_data));

                var data = form;
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function (data) {
                    window.open(data, "_self")
                });

            }
        }

        function updateItemTable(rowNo, rowData, uuid, total) {
            uuid = "'" + uuid + "'";
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + rowData['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_qnt_o">' + rowData['store_vou_qnt_o'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_price">' + rowData['store_vou_item_price_unit'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_price">' + '<i class="btn btn-danger fa fa-plus btn-block"></i>' + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_disc_amount">' + rowData['store_vou_disc_amount'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_total_price">' + rowData['store_vou_item_total_price'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_vat_amount">' + rowData['store_vou_vat_amount'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_price_net">' + rowData['store_vou_price_net'] + '</td>' +
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem(' + uuid + ')"><i class="fa fa-trash"></i></button></td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);
            updateTotal(total);
            //reset input
            $('#item_id').val('').change();
            $('#add_item_div').find('input').val('');
            // $('#item_id').selectpicker('refresh');
            $('#store_vou_qnt_o').val('');
            $('#store_vou_item_total_price').val('');
            $('#store_vou_vat_amount').val('');
            $('#store_vou_price_net').val('');
            $('#store_vou_disc_amount').val('');
            $('#item_balance').val('');
            $('#item_price_cost').val('');
            $('#last_price_cost').val('');
        }


        function deleteItem(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-sales.delete')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    console.log('return true');
                    console.log(data.data.uuid);
                    $('#' + data.data.uuid).remove();
                    updateTotal(data.total);

                    $('#total_sum_div_0').text(0);
                    $('#total_sum_vat_div_0').text(0);
                    $('#total_sum_net_div_0').text(0);
                    return 'true';
                }
                else {
                    toastr.warning(data.msg);
                    console.log('return dalse');
                    return 'false';
                }
            })


        }

        function updateTotal(total) {
            $('#total_sum_div').text(total['total_sum']);
            $('#total_sum').val(total['total_sum']);

            $('#total_sum_net_div').text(total['total_sum_net']);
            $('#total_sum_net').val(total['total_sum_net']);

            $('#total_sum_vat_div').text(total['total_sum_vat']);
            $('#total_sum_vat').val(total['total_sum_vat']);
            $('#total_discount_div').val(0);
            $('#vou_discount_rate').val(0);

        }


        function getSearchResult() {
            url = '{{ route('store-item.search') }}'
            $('#searchData').html('');
            var form = $('#search_data_form').serialize();

            $.ajax({
                type: 'GET',
                url: url,
                data: form,
                dataType: 'json',

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    $('#searchData').html(data.view);

                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function genInvoice(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-item-sales-invnew.generate')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    location.reload();
                    $('#add_bond').removeAttr('disabled')
                }
                else {
                    toastr.warning(data.msg);
                }
            });

        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                item_serial: 0,
                item_id: '',
                items: [],
                item_stor_dt_serial: [],
                disable_button: false,
                item_stor_dt_serial_n: [{'item_serial': ''}]
            },
            methods: {
                getItems() {
                    $.ajax({
                        type: 'GET',
                        data: {item_id: this.item_id},
                        url: '{{ route("store-sales-inv.getItems") }}'
                    }).then(response => {
                        console.log(response)
                        this.items = response.data
                    });
                },
                addRow() {
                    this.item_stor_dt_serial_n.push({'item_serial': ''})
                },
                supRow(index) {
                    this.item_stor_dt_serial_n.splice(index, 1)
                }
            },
            computed: {
                // item_error: function () {
                //     if (this.item_stor_dt_serial.length != this.item_serial) {
                //         this.disable_button = true
                //         return 'عدد العناصر الي تم اختيارها غير متساوي مع الكميه';
                //     } else {
                //         this.disable_button = false
                //         return '';
                //     }
                // },
                item_serial_rows: function () {
                    return parseInt(this.item_serial);
                }
            }
        })
    </script>

@endsection

